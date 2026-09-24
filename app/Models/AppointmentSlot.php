<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', // 'counseling' or 'iets_test'
        'slot_date',
        'start_time',
        'end_time',
        'capacity',
        'duration_minutes',
        'is_active',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'slot_date' => 'date',
        'capacity' => 'integer',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Standardize any time input (e.g. "09:00", "09:00AM", "9:00 am") to standard "09:00 AM"
     */
    public static function normalizeTime(string $timeStr): string
    {
        $clean = trim($timeStr);
        return Carbon::parse($clean)->format('h:i A');
    }

    /**
     * Check if a proposed IETS slot overlaps with any existing IETS slot on the same date.
     * Returns true if overlap is detected.
     */
    public static function checkIetsOverlap(string $date, string $startTime, string $endTime, ?int $excludeSlotId = null): ?self
    {
        $start = Carbon::parse($date . ' ' . $startTime);
        $end = Carbon::parse($date . ' ' . $endTime);

        if ($end->lte($start)) {
            // End time must be after start time
            return null;
        }

        $existingSlots = static::where('type', 'iets_test')
            ->where('slot_date', $date)
            ->when($excludeSlotId, fn($q) => $q->where('id', '!=', $excludeSlotId))
            ->get();

        foreach ($existingSlots as $slot) {
            $sStart = Carbon::parse($date . ' ' . $slot->start_time);
            $sEnd = $slot->end_time 
                ? Carbon::parse($date . ' ' . $slot->end_time)
                : $sStart->copy()->addMinutes($slot->duration_minutes ?: 60);

            // Time interval overlap condition: newStart < existingEnd AND newEnd > existingStart
            if ($start->lt($sEnd) && $end->gt($sStart)) {
                return $slot;
            }
        }

        return null;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'slot_id');
    }

    public function activeAppointments(): HasMany
    {
        return $this->appointments()->whereNotIn('status', ['cancelled']);
    }

    public function getBookedCountAttribute(): int
    {
        return $this->activeAppointments()->count();
    }

    public function getRemainingSeatsAttribute(): int
    {
        return max(0, $this->capacity - $this->booked_count);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->booked_count >= $this->capacity;
    }

    public function getIsPastAttribute(): bool
    {
        $slotDate = Carbon::parse($this->slot_date);
        $today = Carbon::today();

        if ($slotDate->lt($today)) {
            return true;
        }

        if ($slotDate->isToday()) {
            $slotStart = Carbon::parse($slotDate->toDateString() . ' ' . $this->start_time);
            return $slotStart->lte(Carbon::now());
        }

        return false;
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && !$this->is_full && !$this->is_past;
    }

    /**
     * Clean up expired unbooked slots from database.
     * Preserves all slots that have existing booking records.
     *
     * @return int Number of deleted empty slots
     */
    public static function cleanupExpiredUnbookedSlots(): int
    {
        $now = Carbon::now();
        $todayStr = $now->toDateString();

        // 1. Delete empty slots from past dates
        $pastDeleted = static::where('slot_date', '<', $todayStr)
            ->whereDoesntHave('appointments')
            ->delete();

        // 2. Delete empty slots from today whose end time (or start time) has passed
        $todaySlots = static::where('slot_date', $todayStr)
            ->whereDoesntHave('appointments')
            ->get();

        $todayDeleted = 0;
        foreach ($todaySlots as $slot) {
            $timeToCheck = $slot->end_time ?: $slot->start_time;
            $slotEnd = Carbon::parse($todayStr . ' ' . $timeToCheck);
            if ($slotEnd->lte($now)) {
                $slot->delete();
                $todayDeleted++;
            }
        }

        return $pastDeleted + $todayDeleted;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('slot_date', $date);
    }
}
