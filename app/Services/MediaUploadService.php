<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaUploadService
{
    /**
     * Upload an uploaded file into storage/app/public and create Media entry
     */
    public function upload(UploadedFile $file, string $folder = 'uploads', ?string $altText = null): Media
    {
        $mime = $file->getMimeType();
        $type = 'document';
        if (str_starts_with($mime, 'image/')) {
            $type = 'image';
        } elseif (str_starts_with($mime, 'video/')) {
            $type = 'video';
        } elseif ($mime === 'application/pdf') {
            $type = 'pdf';
        }

        $origName = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $safeName = Str::slug(pathinfo($origName, PATHINFO_FILENAME)) . '-' . time() . '.' . $ext;

        $path = $file->storeAs($folder, $safeName, 'public');

        return Media::create([
            'title' => pathinfo($origName, PATHINFO_FILENAME),
            'alt_text' => $altText ?: pathinfo($origName, PATHINFO_FILENAME),
            'caption' => null,
            'file_name' => $origName,
            'file_path' => $path,
            'file_type' => $type,
            'mime_type' => $mime,
            'file_size' => $file->getSize(),
            'folder' => $folder,
        ]);
    }
}
