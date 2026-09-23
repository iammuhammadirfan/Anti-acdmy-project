<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\IetsProgram;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IetsProgramController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $programs = IetsProgram::orderBy('display_order', 'asc')->paginate(15);
        return view('admin.iets.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.iets.programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'category' => 'required|string|max:100',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'features_str' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image_file' => 'nullable|image|max:5120',
            'display_order' => 'integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'iets', $request->title);
            $imagePath = $media->file_path;
        }

        $features = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->input('features_str', '')))));

        $program = IetsProgram::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category' => $request->category,
            'summary' => $request->summary,
            'content' => $request->content,
            'features' => $features,
            'icon' => $request->icon,
            'image' => $imagePath,
            'display_order' => (int) $request->display_order,
            'status' => $request->boolean('status', true),
        ]);

        ActivityLog::log('create', 'iets', "Created IETS program: {$program->title}");

        return redirect()->route('admin.iets.programs.index')->with('success', 'IETS Program created successfully.');
    }

    public function edit(IetsProgram $program)
    {
        return view('admin.iets.programs.edit', compact('program'));
    }

    public function update(Request $request, IetsProgram $program)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'category' => 'required|string|max:100',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'features_str' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image_file' => 'nullable|image|max:5120',
            'display_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'iets', $request->title);
            $program->image = $media->file_path;
        }

        $features = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->input('features_str', '')))));

        $program->title = $request->title;
        $program->category = $request->category;
        $program->summary = $request->summary;
        $program->content = $request->content;
        $program->features = $features;
        $program->icon = $request->icon;
        $program->display_order = (int) $request->display_order;
        $program->status = $request->boolean('status', true);
        $program->save();

        ActivityLog::log('update', 'iets', "Updated IETS program: {$program->title}");

        return redirect()->route('admin.iets.programs.index')->with('success', 'IETS Program updated successfully.');
    }

    public function toggle(IetsProgram $program)
    {
        $program->status = !$program->status;
        $program->save();

        $state = $program->status ? 'active' : 'inactive';
        ActivityLog::log('update', 'iets', "Toggled status for IETS program {$program->title} to {$state}.");

        return back()->with('success', "Program status changed to {$state}.");
    }

    public function destroy(IetsProgram $program)
    {
        $title = $program->title;
        $program->delete();
        ActivityLog::log('delete', 'iets', "Deleted IETS program: {$title}");
        return redirect()->route('admin.iets.programs.index')->with('success', 'Program deleted.');
    }
}
