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

    public function getPrimaryImageAttribute(): string
    {
        $imgs = $this->images;
        if (is_string($imgs)) {
            $decoded = json_decode($imgs, true);
            $imgs = is_array($decoded) ? $decoded : [$imgs];
        }

        if (is_array($imgs) && count($imgs) > 0 && !empty($imgs[0])) {
            $img = $imgs[0];
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://') || str_starts_with($img, '//')) {
                return $img;
            }
            if (str_starts_with($img, 'storage/') || str_starts_with($img, '/storage/')) {
                return asset(ltrim($img, '/'));
            }
            return asset('storage/' . ltrim($img, '/'));
        }

        return 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=800&auto=format&fit=crop';
    }
}
