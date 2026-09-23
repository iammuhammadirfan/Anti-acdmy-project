<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'alt_text',
        'caption',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'folder',
    ];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
