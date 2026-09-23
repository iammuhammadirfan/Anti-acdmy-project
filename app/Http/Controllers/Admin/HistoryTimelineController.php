<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HistoryTimeline;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;

class HistoryTimelineController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $timelines = HistoryTimeline::orderBy('display_order', 'asc')->get();
        return view('admin.timelines.index', compact('timelines'));
    }

    public function create()
    {
        return view('admin.timelines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:20',
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|max:5120',
            'display_order' => 'integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'history', $request->title);
            $imagePath = $media->file_path;
        }

        $item = HistoryTimeline::create([
            'year' => $request->year,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'display_order' => (int) $request->display_order,
            'status' => $request->boolean('status', true),
        ]);

        ActivityLog::log('create', 'about', "Created timeline event: {$item->year} - {$item->title}");

        return redirect()->route('admin.timelines.index')->with('success', 'History timeline item created.');
    }

    public function edit(HistoryTimeline $timeline)
    {
        return view('admin.timelines.edit', compact('timeline'));
    }

    public function update(Request $request, HistoryTimeline $timeline)
    {
        $request->validate([
            'year' => 'required|string|max:20',
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|max:5120',
            'display_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'history', $request->title);
            $timeline->image = $media->file_path;
        }

        $timeline->year = $request->year;
        $timeline->title = $request->title;
        $timeline->description = $request->description;
        $timeline->display_order = (int) $request->display_order;
        $timeline->status = $request->boolean('status', true);
        $timeline->save();

        ActivityLog::log('update', 'about', "Updated timeline event: {$timeline->title}");

        return redirect()->route('admin.timelines.index')->with('success', 'History timeline item updated.');
    }

    public function toggle(HistoryTimeline $timeline)
    {
        $timeline->status = !$timeline->status;
        $timeline->save();

        $state = $timeline->status ? 'active' : 'hidden';
        ActivityLog::log('update', 'about', "Toggled status for timeline event {$timeline->title} to {$state}.");

        return back()->with('success', "Timeline milestone is now {$state}.");
    }

    public function destroy(HistoryTimeline $timeline)
    {
        $title = $timeline->title;
        $timeline->delete();
        ActivityLog::log('delete', 'about', "Deleted timeline event: {$title}");
        return redirect()->route('admin.timelines.index')->with('success', 'Timeline item deleted.');
    }
}
