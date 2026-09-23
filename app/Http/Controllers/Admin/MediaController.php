<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Media;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(Request $request)
    {
        $type = $request->get('type');
        $search = $request->get('search');

        $query = Media::latest();
        if ($type) {
            $query->where('file_type', $type);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        $mediaItems = $query->paginate(24);

        return view('admin.media.index', compact('mediaItems', 'type', 'search'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:20480',
        ]);

        $uploaded = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $item = $this->mediaService->upload($file, 'uploads');
                $uploaded[] = $item;
            }
        }

        ActivityLog::log('create', 'media', 'Uploaded ' . count($uploaded) . ' media files.');

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'items' => $uploaded]);
        }

        return back()->with('success', count($uploaded) . ' file(s) uploaded successfully.');
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'alt_text' => 'nullable|string|max:191',
            'caption' => 'nullable|string|max:255',
        ]);

        $media->title = $request->title;
        $media->alt_text = $request->alt_text;
        $media->caption = $request->caption;
        $media->save();

        return back()->with('success', 'Media details updated.');
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $title = $media->title;
        $media->delete();

        ActivityLog::log('delete', 'media', "Deleted media file: {$title}");

        return back()->with('success', 'Media file deleted.');
    }
}
