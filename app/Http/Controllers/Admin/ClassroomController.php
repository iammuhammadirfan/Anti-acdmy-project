<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Classroom;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $classrooms = Classroom::latest()->paginate(15);
        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('admin.classrooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'class_type' => 'required|string|max:100',
            'facilities_str' => 'nullable|string',
            'video_url' => 'nullable|string|max:255',
            'images_files.*' => 'nullable|image|max:5120',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images_files')) {
            foreach ($request->file('images_files') as $file) {
                $media = $this->mediaService->upload($file, 'classrooms', $request->title);
                $imagePaths[] = $media->file_path;
            }
        }

        $facilities = array_filter(array_map('trim', explode(',', $request->input('facilities_str', ''))));

        $classroom = Classroom::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'images' => $imagePaths,
            'video_url' => $request->video_url,
            'capacity' => (int) $request->capacity,
            'facilities' => $facilities,
            'class_type' => $request->class_type,
            'status' => $request->boolean('status', true),
        ]);

        ActivityLog::log('create', 'classrooms', "Created classroom: {$classroom->title}");

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom created successfully.');
    }

    public function edit(Classroom $classroom)
    {
        return view('admin.classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'class_type' => 'required|string|max:100',
            'facilities_str' => 'nullable|string',
            'video_url' => 'nullable|string|max:255',
            'images_files.*' => 'nullable|image|max:5120',
        ]);

        $currentImages = $classroom->images ?? [];
        if ($request->hasFile('images_files')) {
            foreach ($request->file('images_files') as $file) {
                $media = $this->mediaService->upload($file, 'classrooms', $request->title);
                $currentImages[] = $media->file_path;
            }
        }

        $facilities = array_filter(array_map('trim', explode(',', $request->input('facilities_str', ''))));

        $classroom->title = $request->title;
        $classroom->description = $request->description;
        $classroom->images = $currentImages;
        $classroom->video_url = $request->video_url;
        $classroom->capacity = (int) $request->capacity;
        $classroom->facilities = $facilities;
        $classroom->class_type = $request->class_type;
        $classroom->status = $request->boolean('status', true);
        $classroom->save();

        ActivityLog::log('update', 'classrooms', "Updated classroom: {$classroom->title}");

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom updated successfully.');
    }

    public function toggle(Classroom $classroom)
    {
        $classroom->status = !$classroom->status;
        $classroom->save();

        $state = $classroom->status ? 'active' : 'inactive';
        ActivityLog::log('update', 'classrooms', "Toggled status for classroom {$classroom->title} to {$state}.");

        return back()->with('success', "Classroom status changed to {$state}.");
    }

    public function destroy(Classroom $classroom)
    {
        $title = $classroom->title;
        $classroom->delete();
        ActivityLog::log('delete', 'classrooms', "Deleted classroom: {$title}");
        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom deleted.');
    }
}
