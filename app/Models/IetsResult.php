<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IetsResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'student_image',
        'test_type',
        'overall_band',
        'listening_score',
        'reading_score',
        'writing_score',
        'speaking_score',
        'certificate_image',
        'test_date',
        'description',
        'is_featured',
    ];

    protected $casts = [
        'overall_band' => 'decimal:1',
        'listening_score' => 'decimal:1',
        'reading_score' => 'decimal:1',
        'writing_score' => 'decimal:1',
        'speaking_score' => 'decimal:1',
        'test_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
