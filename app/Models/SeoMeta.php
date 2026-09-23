<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    use HasFactory;

    protected $table = 'seo_meta';

    protected $fillable = [
        'page_key',
        'seo_title',
        'meta_description',
        'keywords',
        'canonical_url',
        'robots_meta',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_type',
        'custom_schema_json',
    ];

    public static function getForPage(string $pageKey): ?self
    {
        return static::where('page_key', $pageKey)->first();
    }
}
