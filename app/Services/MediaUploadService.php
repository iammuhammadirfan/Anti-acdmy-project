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

        try {
            $destFullPath = storage_path('app/public/' . $path);
            $publicStorageCopy = public_path('storage/' . $path);
            $publicDir = dirname($publicStorageCopy);
            if (!is_dir($publicDir)) {
                @mkdir($publicDir, 0755, true);
            }
            if (file_exists($destFullPath) && !file_exists($publicStorageCopy)) {
                @copy($destFullPath, $publicStorageCopy);
            }
        } catch (\Throwable $e) {
            // Ignore copy failure
        }

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

    /**
     * Upload and standardize result card image to fixed standard dimensions (800x1000 - 4:5 ratio).
     */
    public function uploadStandardResultCard(UploadedFile $file, string $folder = 'results/cards', ?string $altText = null, int $targetW = 800, int $targetH = 1000): Media
    {
        $origName = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension());
        $baseName = Str::slug(pathinfo($origName, PATHINFO_FILENAME)) . '-' . time();
        $safeName = $baseName . '.jpg';

        $destinationDir = storage_path('app/public/' . $folder);
        if (!is_dir($destinationDir)) {
            @mkdir($destinationDir, 0755, true);
        }

        $destFullPath = $destinationDir . DIRECTORY_SEPARATOR . $safeName;
        $relPath = $folder . '/' . $safeName;

        $processed = false;

        if (extension_loaded('gd')) {
            $srcPath = $file->getRealPath();
            $info = @getimagesize($srcPath);
            if ($info) {
                [$origW, $origH] = $info;
                $srcMime = $info['mime'];

                $srcImg = match ($srcMime) {
                    'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($srcPath),
                    'image/png' => @imagecreatefrompng($srcPath),
                    'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($srcPath) : null,
                    default => null,
                };

                if ($srcImg) {
                    $dstImg = imagecreatetruecolor($targetW, $targetH);

                    // High-quality antialiasing
                    imagealphablending($dstImg, false);
                    imagesavealpha($dstImg, true);

                    $srcRatio = $origW / $origH;
                    $targetRatio = $targetW / $targetH;

                    if ($srcRatio > $targetRatio) {
                        // Source is wider -> crop sides
                        $cropW = (int) ($origH * $targetRatio);
                        $cropH = $origH;
                        $cropX = (int) (($origW - $cropW) / 2);
                        $cropY = 0;
                    } else {
                        // Source is taller -> crop top/bottom
                        $cropW = $origW;
                        $cropH = (int) ($origW / $targetRatio);
                        $cropX = 0;
                        $cropY = (int) (($origH - $cropH) / 2);
                    }

                    imagecopyresampled($dstImg, $srcImg, 0, 0, $cropX, $cropY, $targetW, $targetH, $cropW, $cropH);
                    imagejpeg($dstImg, $destFullPath, 92);

                    imagedestroy($srcImg);
                    imagedestroy($dstImg);

                    $processed = true;
                }
            }
        }

        if (!$processed) {
            $path = $file->storeAs($folder, $safeName, 'public');
            $destFullPath = storage_path('app/public/' . $path);
            $relPath = $path;
        }

        // Also copy directly to public/storage if public/storage is a real dir
        $publicStorageCopy = public_path('storage/' . $relPath);
        $publicDir = dirname($publicStorageCopy);
        if (!is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }
        @copy($destFullPath, $publicStorageCopy);

        $fileSize = file_exists($destFullPath) ? filesize($destFullPath) : $file->getSize();

        return Media::create([
            'title' => pathinfo($origName, PATHINFO_FILENAME),
            'alt_text' => $altText ?: pathinfo($origName, PATHINFO_FILENAME),
            'caption' => 'Standardized Result Card (800x1000)',
            'file_name' => $safeName,
            'file_path' => $relPath,
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
            'file_size' => $fileSize,
            'folder' => $folder,
        ]);
    }
}
