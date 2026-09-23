<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'designation',
        'qualification',
        'experience',
        'subject',
        'classes_taught',
        'bio',
        'profile_image',
        'email',
        'phone',
        'social_links',
        'display_order',
        'status',
    ];

    protected $casts = [
        'social_links' => 'array',
        'status' => 'boolean',
        'display_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($teacher) {
            if (empty($teacher->slug)) {
                $teacher->slug = Str::slug($teacher->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('display_order', 'asc');
    }
}
