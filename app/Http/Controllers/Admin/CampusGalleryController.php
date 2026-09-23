<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CampusGallery;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;

class CampusGalleryController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(Request $request)
    {
        $category = $request->get('category');
        $query = CampusGallery::orderBy('display_order', 'asc');
        if ($category) {
            $query->where('category', $category);
        }
        $galleries = $query->paginate(20);
        return view('admin.gallery.index', compact('galleries', 'category'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'category' => 'required|in:classroom,lab,library,student_activity,events,outdoor',
            'image_file' => 'required|image|max:5120',
            'caption' => 'nullable|string|max:191',
            'display_order' => 'integer',
        ]);

        $media = $this->mediaService->upload($request->file('image_file'), 'gallery', $request->title);

        $item = CampusGallery::create([
            'title' => $request->title,
            'category' => $request->category,
            'image_path' => $media->file_path,
            'caption' => $request->caption,
            'display_order' => (int) $request->display_order,
            'status' => $request->boolean('status', true),
        ]);

        ActivityLog::log('create', 'gallery', "Uploaded campus photo: {$item->title}");

        return redirect()->route('admin.gallery.index')->with('success', 'Photo added to campus gallery.');
    }

    public function edit(CampusGallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, CampusGallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'category' => 'required|in:classroom,lab,library,student_activity,events,outdoor',
            'image_file' => 'nullable|image|max:5120',
            'caption' => 'nullable|string|max:191',
            'display_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'gallery', $request->title);
            $gallery->image_path = $media->file_path;
        }

        $gallery->title = $request->title;
        $gallery->category = $request->category;
        $gallery->caption = $request->caption;
        $gallery->display_order = (int) $request->display_order;
        $gallery->status = $request->boolean('status', true);
        $gallery->save();

        ActivityLog::log('update', 'gallery', "Updated campus photo: {$gallery->title}");

        return redirect()->route('admin.gallery.index')->with('success', 'Photo updated.');
    }

    public function toggle(CampusGallery $gallery)
    {
        $gallery->status = !$gallery->status;
        $gallery->save();

        $state = $gallery->status ? 'active' : 'hidden';
        ActivityLog::log('update', 'gallery', "Toggled status for campus photo {$gallery->title} to {$state}.");

        return back()->with('success', "Photo status changed to {$state}.");
    }

    public function destroy(CampusGallery $gallery)
    {
        $title = $gallery->title;
        $gallery->delete();
        ActivityLog::log('delete', 'gallery', "Deleted campus photo: {$title}");
        return redirect()->route('admin.gallery.index')->with('success', 'Photo deleted.');
    }
}
