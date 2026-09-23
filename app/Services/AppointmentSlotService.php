<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\BlockedDate;
use Carbon\Carbon;

class AppointmentSlotService
{
    /**
     * Get available time slots for a given date string (YYYY-MM-DD)
     *
     * @param string $dateStr
     * @return array
     */
    public function getAvailableSlots(string $dateStr): array
    {
        $date = Carbon::parse($dateStr);
        $today = Carbon::today();

        if ($date->lt($today)) {
            return [];
        }

        // Check if date is blocked
        if (BlockedDate::where('date', $date->toDateString())->exists()) {
            return [];
        }

        $settings = AppointmentSetting::getSettings();
        $workingDays = $settings->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $dayName = $date->format('l');

        if (!in_array($dayName, $workingDays)) {
            return [];
        }

        $startTime = Carbon::parse($settings->start_time);
        $endTime = Carbon::parse($settings->end_time);
        $slotMinutes = max(15, (int) $settings->slot_duration_minutes);

        $breakStart = $settings->break_start ? Carbon::parse($settings->break_start) : null;
        $breakEnd = $settings->break_end ? Carbon::parse($settings->break_end) : null;

        // Fetch already booked slots for this date (excluding cancelled)
        $bookedSlots = Appointment::where('appointment_date', $date->toDateString())
            ->whereNotIn('status', ['cancelled'])
            ->pluck('time_slot')
            ->toArray();

        $slots = [];
        $current = $startTime->copy();

        while ($current->copy()->addMinutes($slotMinutes)->lte($endTime)) {
            $slotFormatted = $current->format('h:i A');

            // Check if current slot falls inside break time
            $inBreak = false;
            if ($breakStart && $breakEnd) {
                if ($current->gte($breakStart) && $current->lt($breakEnd)) {
                    $inBreak = true;
                }
            }

            // If booking for today, slot must be in the future
            $isPastToday = false;
            if ($date->isToday()) {
                $slotCarbon = Carbon::parse($date->toDateString() . ' ' . $current->format('H:i:s'));
                if ($slotCarbon->lte(Carbon::now())) {
                    $isPastToday = true;
                }
            }

            if (!$inBreak && !$isPastToday) {
                $countBooked = count(array_keys($bookedSlots, $slotFormatted));
                if ($countBooked < (int) $settings->max_per_slot) {
                    $slots[] = $slotFormatted;
                }
            }

            $current->addMinutes($slotMinutes);
        }

        return $slots;
    }

    /**
     * Check if a specific slot on a specific date is available
     */
    public function isSlotAvailable(string $dateStr, string $timeSlot): bool
    {
        $available = $this->getAvailableSlots($dateStr);
        return in_array($timeSlot, $available);
    }
}
