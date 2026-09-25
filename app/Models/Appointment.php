<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_id',
        'type', // 'counseling' or 'iets_test'
        'booking_code',
        'registration_number',
        'name',
        'email',
        'phone',
        'whatsapp',
        'cnic_passport',
        'program',
        'appointment_date',
        'time_slot',
        'purpose',
        'test_type',
        'message',
        'status',
        'admin_notes',
        'reminder_sent_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'reminder_sent_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($apt) {
            $year = date('Y');
            $type = $apt->type ?? 'counseling';

            if (empty($apt->registration_number)) {
                $prefix = ($type === 'iets_test') ? "IETS-{$year}-" : "COUN-{$year}-";

                // Generate safe sequential number
                $maxSeq = 0;
                $lastBooking = DB::table('appointments')
                    ->where('registration_number', 'LIKE', $prefix . '%')
                    ->lockForUpdate()
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastBooking && !empty($lastBooking->registration_number)) {
                    $part = str_replace($prefix, '', $lastBooking->registration_number);
                    if (is_numeric($part)) {
                        $maxSeq = (int) $part;
                    }
                }

                $nextSeq = $maxSeq + 1;
                $regNumber = sprintf('%s%06d', $prefix, $nextSeq);

                // Check for safety loop in case of race
                while (DB::table('appointments')->where('registration_number', $regNumber)->exists()) {
                    $nextSeq++;
                    $regNumber = sprintf('%s%06d', $prefix, $nextSeq);
                }

                $apt->registration_number = $regNumber;
            }

            if (empty($apt->booking_code)) {
                $apt->booking_code = $apt->registration_number;
            }
        });
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(AppointmentSlot::class, 'slot_id');
    }

    public function scopeCounseling($query)
    {
        return $query->where('type', 'counseling');
    }

    public function scopeIetsTest($query)
    {
        return $query->where('type', 'iets_test');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled']);
    }

    public function getStudentNameAttribute(): string
    {
        return $this->name;
    }
}
