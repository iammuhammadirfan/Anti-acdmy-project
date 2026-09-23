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
