<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $videos = Video::with('category')->latest()->paginate(15);
        $categories = VideoCategory::all();
        return view('admin.videos.index', compact('videos', 'categories'));
    }

    public function create()
    {
        $categories = VideoCategory::all();
        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'video_url' => 'required|string|max:255',
            'category_id' => 'nullable|exists:video_categories,id',
            'description' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|max:5120',
            'seo_title' => 'nullable|string|max:191',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail_file')) {
            $media = $this->mediaService->upload($request->file('thumbnail_file'), 'videos/thumbs', $request->title);
            $thumbnailPath = $media->file_path;
        }

        // Parse YouTube ID
        $youtubeId = null;
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $request->video_url, $matches)) {
            $youtubeId = $matches[1];
        }

        if (!$thumbnailPath && $youtubeId) {
            $thumbnailPath = "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
        }

        $video = Video::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'video_url' => $request->video_url,
            'youtube_id' => $youtubeId,
            'thumbnail' => $thumbnailPath,
            'description' => $request->description,
            'published_at' => $request->filled('published_at') ? $request->published_at : now(),
            'status' => $request->boolean('status', true),
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
        ]);

        ActivityLog::log('create', 'videos', "Added video: {$video->title}");

        return redirect()->route('admin.videos.index')->with('success', 'Video published successfully.');
    }

    public function edit(Video $video)
    {
        $categories = VideoCategory::all();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'video_url' => 'required|string|max:255',
            'category_id' => 'nullable|exists:video_categories,id',
            'description' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|max:5120',
            'seo_title' => 'nullable|string|max:191',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $media = $this->mediaService->upload($request->file('thumbnail_file'), 'videos/thumbs', $request->title);
            $video->thumbnail = $media->file_path;
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $request->video_url, $matches)) {
            $video->youtube_id = $matches[1];
        }

        $video->title = $request->title;
        $video->category_id = $request->category_id;
        $video->video_url = $request->video_url;
        $video->description = $request->description;
        $video->status = $request->boolean('status', true);
        $video->seo_title = $request->seo_title;
        $video->seo_description = $request->seo_description;
        $video->seo_keywords = $request->seo_keywords;
        $video->save();

        ActivityLog::log('update', 'videos', "Updated video: {$video->title}");

        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully.');
    }

    public function toggle(Video $video)
    {
        $video->status = !$video->status;
        $video->save();

        $state = $video->status ? 'published' : 'hidden';
        ActivityLog::log('update', 'videos', "Toggled status for video {$video->title} to {$state}.");

        return back()->with('success', "Video status changed to {$state}.");
    }

    public function destroy(Video $video)
    {
        $title = $video->title;
        $video->delete();
        ActivityLog::log('delete', 'videos', "Deleted video: {$title}");
        return redirect()->route('admin.videos.index')->with('success', 'Video deleted.');
    }
}
