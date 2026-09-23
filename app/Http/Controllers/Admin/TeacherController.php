<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Teacher;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $teachers = Teacher::orderBy('display_order', 'asc')->paginate(15);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'designation' => 'required|string|max:191',
            'qualification' => 'required|string|max:191',
            'experience' => 'nullable|string|max:100',
            'subject' => 'required|string|max:191',
            'classes_taught' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:50',
            'image_file' => 'nullable|image|max:5120',
            'display_order' => 'integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'teachers', $request->name);
            $imagePath = $media->file_path;
        }

        $socialLinks = [
            'linkedin' => $request->social_linkedin,
            'twitter' => $request->social_twitter,
            'facebook' => $request->social_facebook,
        ];

        $teacher = Teacher::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'designation' => $request->designation,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'subject' => $request->subject,
            'classes_taught' => $request->classes_taught,
            'bio' => $request->bio,
            'profile_image' => $imagePath,
            'email' => $request->email,
            'phone' => $request->phone,
            'social_links' => array_filter($socialLinks),
            'display_order' => (int) $request->display_order,
            'status' => $request->boolean('status', true),
        ]);

        ActivityLog::log('create', 'teachers', "Added teacher: {$teacher->name}");

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher added successfully.');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'designation' => 'required|string|max:191',
            'qualification' => 'required|string|max:191',
            'experience' => 'nullable|string|max:100',
            'subject' => 'required|string|max:191',
            'classes_taught' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:50',
            'image_file' => 'nullable|image|max:5120',
            'display_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'teachers', $request->name);
            $teacher->profile_image = $media->file_path;
        }

        $socialLinks = [
            'linkedin' => $request->social_linkedin,
            'twitter' => $request->social_twitter,
            'facebook' => $request->social_facebook,
        ];

        $teacher->name = $request->name;
        $teacher->designation = $request->designation;
        $teacher->qualification = $request->qualification;
        $teacher->experience = $request->experience;
        $teacher->subject = $request->subject;
        $teacher->classes_taught = $request->classes_taught;
        $teacher->bio = $request->bio;
        $teacher->email = $request->email;
        $teacher->phone = $request->phone;
        $teacher->social_links = array_filter($socialLinks);
        $teacher->display_order = (int) $request->display_order;
        $teacher->status = $request->boolean('status', true);
        $teacher->save();

        ActivityLog::log('update', 'teachers', "Updated teacher: {$teacher->name}");

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $name = $teacher->name;
        $teacher->delete();
        ActivityLog::log('delete', 'teachers', "Deleted teacher: {$name}");
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted.');
    }
}
