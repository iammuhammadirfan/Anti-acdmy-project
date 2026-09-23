<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Slider;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $sliders = Slider::orderBy('display_order', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:191',
            'short_description' => 'nullable|string',
            'image_file' => 'nullable|image|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:255',
            'display_order' => 'integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'sliders', $request->heading);
            $imagePath = $media->file_path;
        }

        $slider = Slider::create([
            'heading' => $request->heading,
            'short_description' => $request->short_description,
            'image' => $imagePath,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'secondary_button_text' => $request->secondary_button_text,
            'secondary_button_url' => $request->secondary_button_url,
            'display_order' => (int) $request->display_order,
            'status' => $request->boolean('status', true),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        ActivityLog::log('create', 'sliders', "Created slider: {$slider->heading}");

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'heading' => 'required|string|max:191',
            'short_description' => 'nullable|string',
            'image_file' => 'nullable|image|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:255',
            'display_order' => 'integer',
        ]);

        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'sliders', $request->heading);
            $slider->image = $media->file_path;
        }

        $slider->heading = $request->heading;
        $slider->short_description = $request->short_description;
        $slider->button_text = $request->button_text;
        $slider->button_url = $request->button_url;
        $slider->secondary_button_text = $request->secondary_button_text;
        $slider->secondary_button_url = $request->secondary_button_url;
        $slider->display_order = (int) $request->display_order;
        $slider->status = $request->boolean('status', true);
        $slider->start_date = $request->start_date;
        $slider->end_date = $request->end_date;
        $slider->save();

        ActivityLog::log('update', 'sliders', "Updated slider: {$slider->heading}");

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        $heading = $slider->heading;
        $slider->delete();
        ActivityLog::log('delete', 'sliders', "Deleted slider: {$heading}");
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
