<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'images',
        'video_url',
        'capacity',
        'facilities',
        'class_type',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'facilities' => 'array',
        'status' => 'boolean',
        'capacity' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($classroom) {
            if (empty($classroom->slug)) {
                $classroom->slug = Str::slug($classroom->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
