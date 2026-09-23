<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'name',
        'email',
        'phone',
        'whatsapp',
        'appointment_date',
        'time_slot',
        'purpose',
        'message',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($apt) {
            if (empty($apt->booking_code)) {
                $apt->booking_code = 'APT-' . strtoupper(date('ym')) . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
