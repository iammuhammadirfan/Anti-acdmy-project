<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'course',
        'rating',
        'review',
        'avatar',
        'is_approved',
        'is_featured',
        'display_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
    ];

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true)->orderBy('display_order', 'asc')->latest('id');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_approved', true)->where('is_featured', true)->orderBy('display_order', 'asc');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->latest('id');
    }
}
