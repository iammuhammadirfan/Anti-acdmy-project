<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'working_days',
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'break_start',
        'break_end',
        'max_per_slot',
    ];

    protected $casts = [
        'working_days' => 'array',
        'slot_duration_minutes' => 'integer',
        'max_per_slot' => 'integer',
    ];

    public static function getSettings(): self
    {
        $setting = static::first();
        if (!$setting) {
            return static::create([
                'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'start_time' => '09:00:00',
                'end_time' => '18:00:00', // 9:00 AM to 6:00 PM
                'slot_duration_minutes' => 30,
                'break_start' => '13:00:00',
                'break_end' => '14:00:00',
                'max_per_slot' => 1, // Single slot for counseling
            ]);
        }
        return $setting;
    }
}
