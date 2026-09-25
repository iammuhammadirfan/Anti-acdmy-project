<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\AppointmentSlot;
use App\Models\BlockedDate;
use Carbon\Carbon;

class AppointmentSlotService
{
    /**
     * Get slots for a given date and appointment type ('counseling' or 'iets_test').
     * Counseling and IETS Test are completely separate scheduling streams.
     *
     * @param string $dateStr
     * @param string $type
     * @return array
     */
    public function getSlotsForType(string $dateStr, string $type = 'counseling'): array
    {
        $date = Carbon::parse($dateStr);
        $today = Carbon::today();

        if ($date->lt($today)) {
            return [];
        }

        // Clean up expired unbooked slots from database
        AppointmentSlot::cleanupExpiredUnbookedSlots();

        // Check if date is blocked (holidays)
        if (BlockedDate::where('date', $date->toDateString())->exists()) {
            return [];
        }

        // ==========================================
        // 1. IETS TEST SLOTS (Explicit Admin Ranges)
        // ==========================================
        if ($type === 'iets_test') {
            $dbSlots = AppointmentSlot::where('slot_date', $date->toDateString())
                ->where('type', 'iets_test')
                ->where('is_active', true)
                ->orderByRaw("STR_TO_DATE(start_time, '%h:%i %p') ASC")
                ->get();

            $result = [];
            foreach ($dbSlots as $slot) {
                $isPast = false;
                if ($date->isToday()) {
                    $slotTime = Carbon::parse($date->toDateString() . ' ' . $slot->start_time);
                    if ($slotTime->lte(Carbon::now())) {
                        $isPast = true;
                    }
                } elseif ($date->lt(Carbon::today())) {
                    $isPast = true;
                }

                $bookedCount = $slot->appointments()
                    ->where('type', 'iets_test')
                    ->whereNotIn('status', ['cancelled'])
                    ->count();

                $capacity = (int) $slot->capacity;
                $remaining = max(0, $capacity - $bookedCount);
                $isFull = $bookedCount >= $capacity;
                $isAvailable = !$isPast && !$isFull && $slot->is_active;

                $statusText = "{$remaining} Seats Available";
                if ($isPast) {
                    $statusText = 'Time Passed';
                } elseif ($isFull) {
                    $statusText = 'FULLY BOOKED';
                }

                $result[] = [
                    'id' => $slot->id,
                    'time' => $slot->start_time,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'time_range' => $slot->start_time . ($slot->end_time ? ' – ' . $slot->end_time : ''),
                    'capacity' => $capacity,
                    'booked_count' => $bookedCount,
                    'remaining_seats' => $remaining,
                    'is_full' => $isFull,
                    'is_past' => $isPast,
                    'is_disabled' => !$isAvailable,
                    'is_available' => $isAvailable,
                    'status_text' => $statusText,
                    'type' => 'iets_test',
                ];
            }
            return $result;
        }

        // ============================================================
        // 2. COUNSELING SLOTS (Automatic Single Slots 9:00 AM to 6:00 PM)
        // ============================================================
        $settings = AppointmentSetting::getSettings();
        $workingDays = $settings->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $dayName = $date->format('l');

        if (!in_array($dayName, $workingDays)) {
            return [];
        }

        $startTime = Carbon::parse($settings->start_time ?: '09:00:00');
        $endTime = Carbon::parse($settings->end_time ?: '18:00:00'); // 9:00 AM to 6:00 PM
        $slotMinutes = max(15, (int) ($settings->slot_duration_minutes ?: 30));

        $breakStart = $settings->break_start ? Carbon::parse($settings->break_start) : null;
        $breakEnd = $settings->break_end ? Carbon::parse($settings->break_end) : null;

        // Fetch already booked counseling slots for this date (excluding cancelled)
        $bookedSlots = Appointment::where('appointment_date', $date->toDateString())
            ->where('type', 'counseling')
            ->whereNotIn('status', ['cancelled'])
            ->pluck('time_slot')
            ->map(fn($t) => AppointmentSlot::normalizeTime($t))
            ->toArray();

        $slots = [];
        $current = $startTime->copy();
        $maxPerSlot = (int) ($settings->max_per_slot ?? 1); // 1-on-1 counseling

        while ($current->copy()->addMinutes($slotMinutes)->lte($endTime)) {
            $slotFormatted = $current->format('h:i A');

            // Break check
            $inBreak = false;
            if ($breakStart && $breakEnd) {
                if ($current->gte($breakStart) && $current->lt($breakEnd)) {
                    $inBreak = true;
                }
            }

            // Past time check if today
            $isPastToday = false;
            if ($date->isToday()) {
                $slotCarbon = Carbon::parse($date->toDateString() . ' ' . $current->format('H:i:s'));
                if ($slotCarbon->lte(Carbon::now())) {
                    $isPastToday = true;
                }
            } elseif ($date->lt(Carbon::today())) {
                $isPastToday = true;
            }

            if (!$inBreak) {
                $countBooked = count(array_keys($bookedSlots, $slotFormatted));
                $remaining = max(0, $maxPerSlot - $countBooked);
                $isFull = $countBooked >= $maxPerSlot;
                $isAvailable = !$isPastToday && !$isFull;

                $statusText = 'Available';
                if ($isPastToday) {
                    $statusText = 'Time Passed';
                } elseif ($isFull) {
                    $statusText = 'BOOKED';
                }

                $slots[] = [
                    'id' => null,
                    'time' => $slotFormatted,
                    'start_time' => $slotFormatted,
                    'end_time' => $current->copy()->addMinutes($slotMinutes)->format('h:i A'),
                    'time_range' => $slotFormatted,
                    'capacity' => $maxPerSlot,
                    'booked_count' => $countBooked,
                    'remaining_seats' => $remaining,
                    'is_full' => $isFull,
                    'is_past' => $isPastToday,
                    'is_disabled' => !$isAvailable,
                    'is_available' => $isAvailable,
                    'status_text' => $statusText,
                    'type' => 'counseling',
                ];
            }

            $current->addMinutes($slotMinutes);
        }

        return $slots;
    }

    /**
     * Backward-compatible simple slot list
     */
    public function getAvailableSlots(string $dateStr, string $type = 'counseling'): array
    {
        $all = $this->getSlotsForType($dateStr, $type);
        return array_values(array_map(function ($s) {
            return $s['time'];
        }, array_filter($all, function ($s) {
            return $s['is_available'];
        })));
    }
}
