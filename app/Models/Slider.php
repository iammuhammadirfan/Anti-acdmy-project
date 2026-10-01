<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'short_description',
        'image',
        'button_text',
        'button_url',
        'secondary_button_text',
        'secondary_button_url',
        'display_order',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'status' => 'boolean',
        'display_order' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('display_order', 'asc');
    }

    /**
     * Clean & normalize a URL so external links have https:// and internal links have /
     */
    public static function normalizeUrl(?string $url): string
    {
        if (empty($url)) {
            return '';
        }

        $trimmed = trim($url);

        // If starts with /, #, tel:, mailto:, javascript:
        if (preg_match('#^(/|\#|tel:|mailto:|javascript:)#i', $trimmed)) {
            return $trimmed;
        }

        // If already has protocol (http://, https://, //)
        if (preg_match('#^(https?:)?//#i', $trimmed)) {
            return $trimmed;
        }

        // If starts with www. or contains domain-like dot (e.g. pearsonpte.com, google.com, exam.net/path)
        if (str_starts_with(strtolower($trimmed), 'www.') || preg_match('#^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(/.*)?$#i', $trimmed) || str_contains($trimmed, '.')) {
            return 'https://' . ltrim($trimmed, '/');
        }

        // Otherwise internal route
        return '/' . ltrim($trimmed, '/');
    }

    /**
     * Determine if a URL points to an external site
     */
    public static function isExternalUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $normalized = self::normalizeUrl($url);

        if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://') || str_starts_with($normalized, '//')) {
            $host = parse_url($normalized, PHP_URL_HOST);
            $currentHost = request()->getHost();

            if (!empty($host) && strtolower($host) !== strtolower($currentHost)) {
                return true;
            }
        }

        return false;
    }

    public function getFormattedButtonUrlAttribute(): string
    {
        return self::normalizeUrl($this->button_url);
    }

    public function getIsButtonExternalAttribute(): bool
    {
        return self::isExternalUrl($this->button_url);
    }

    public function getFormattedSecondaryButtonUrlAttribute(): string
    {
        return self::normalizeUrl($this->secondary_button_url);
    }

    public function getIsSecondaryButtonExternalAttribute(): bool
    {
        return self::isExternalUrl($this->secondary_button_url);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://') || str_starts_with($this->image, '//')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'storage/') || str_starts_with($this->image, '/storage/')) {
            return asset(ltrim($this->image, '/'));
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }
}
