<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PageSection;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $sections = PageSection::orderBy('display_order', 'asc')->get();
        return view('admin.sections.index', compact('sections'));
    }

    public function edit(PageSection $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, PageSection $section)
    {
        $request->validate([
            'title' => 'nullable|string|max:191',
            'subtitle' => 'nullable|string|max:191',
            'content' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:5120',
            'display_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'sections', $section->section_key);
            $section->image = $media->file_path;
        }

        $section->title = $request->title;
        $section->subtitle = $request->subtitle;
        $section->content = $request->content;
        $section->button_text = $request->button_text;
        $section->button_url = $request->button_url;
        $section->display_order = (int) $request->display_order;
        $section->is_active = $request->boolean('is_active', true);

        if ($request->filled('meta_json')) {
            $decoded = json_decode($request->meta_json, true);
            if (is_array($decoded)) {
                $section->meta = $decoded;
            }
        }

        $section->save();

        ActivityLog::log('update', 'homepage', "Updated section: {$section->section_key}");

        return redirect()->route('admin.sections.index')->with('success', "Section '{$section->section_key}' updated successfully.");
    }

    public function toggle(PageSection $section)
    {
        $section->is_active = !$section->is_active;
        $section->save();

        $state = $section->is_active ? 'enabled' : 'disabled';
        ActivityLog::log('update', 'homepage', "Toggled section {$section->section_key} to {$state}.");

        return back()->with('success', "Section {$section->section_key} is now {$state}.");
    }
}
