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
        'result_image',
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
        'overall_band' => 'string',
        'test_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get unified display image for the result card (banner or photo).
     */
    public function getCardImageUrlAttribute(): string
    {
        if ($this->result_image) {
            return str_starts_with($this->result_image, 'http')
                ? $this->result_image
                : asset('storage/' . $this->result_image);
        }

        if ($this->certificate_image) {
            return str_starts_with($this->certificate_image, 'http')
                ? $this->certificate_image
                : asset('storage/' . $this->certificate_image);
        }

        if ($this->student_image) {
            return str_starts_with($this->student_image, 'http')
                ? $this->student_image
                : asset('storage/' . $this->student_image);
        }

        return asset('images/default-result-card.jpg');
    }

    /**
     * Normalize test category to IELTS, PTE, or TOEFL.
     */
    public function getCategoryAttribute(): string
    {
        $type = strtoupper((string)$this->test_type);
        if (str_contains($type, 'PTE')) {
            return 'PTE';
        }
        if (str_contains($type, 'TOEFL')) {
            return 'TOEFL';
        }
        return 'IELTS';
    }
}
