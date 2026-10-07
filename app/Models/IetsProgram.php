<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IetsProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'timing_slot_1_name',
        'timing_slot_1_time',
        'timing_slot_1_details',
        'timing_slot_1_enabled',
        'timing_slot_2_name',
        'timing_slot_2_time',
        'timing_slot_2_details',
        'timing_slot_2_enabled',
        'timing_slot_3_name',
        'timing_slot_3_time',
        'timing_slot_3_details',
        'timing_slot_3_enabled',
        'badge',
        'instructor_name',
        'duration',
        'fee',
        'summary',
        'content',
        'features',
        'icon',
        'image',
        'display_order',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'status' => 'boolean',
        'timing_slot_1_enabled' => 'boolean',
        'timing_slot_2_enabled' => 'boolean',
        'timing_slot_3_enabled' => 'boolean',
        'display_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('display_order', 'asc');
    }
}
