<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\IetsResult;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;

class IetsResultController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $results = IetsResult::latest('test_date')->paginate(15);
        return view('admin.iets.results.index', compact('results'));
    }

    public function create()
    {
        return view('admin.iets.results.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:191',
            'test_type' => 'required|string|max:100',
            'overall_band' => 'required|numeric|min:0|max:9',
            'listening_score' => 'nullable|numeric|min:0|max:9',
            'reading_score' => 'nullable|numeric|min:0|max:9',
            'writing_score' => 'nullable|numeric|min:0|max:9',
            'speaking_score' => 'nullable|numeric|min:0|max:9',
            'test_date' => 'nullable|date',
            'student_image_file' => 'nullable|image|max:5120',
            'certificate_image_file' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
        ]);

        $studentImg = null;
        if ($request->hasFile('student_image_file')) {
            $media = $this->mediaService->upload($request->file('student_image_file'), 'results/students', $request->student_name);
            $studentImg = $media->file_path;
        }

        $certImg = null;
        if ($request->hasFile('certificate_image_file')) {
            $media = $this->mediaService->upload($request->file('certificate_image_file'), 'results/certs', $request->student_name . ' Certificate');
            $certImg = $media->file_path;
        }

        $result = IetsResult::create([
            'student_name' => $request->student_name,
            'student_image' => $studentImg,
            'test_type' => $request->test_type,
            'overall_band' => $request->overall_band,
            'listening_score' => $request->listening_score,
            'reading_score' => $request->reading_score,
            'writing_score' => $request->writing_score,
            'speaking_score' => $request->speaking_score,
            'certificate_image' => $certImg,
            'test_date' => $request->test_date,
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        ActivityLog::log('create', 'iets_results', "Added IETS result for student: {$result->student_name} (Band {$result->overall_band})");

        return redirect()->route('admin.iets.results.index')->with('success', 'Student IETS Result recorded successfully.');
    }

    public function edit(IetsResult $result)
    {
        return view('admin.iets.results.edit', compact('result'));
    }

    public function update(Request $request, IetsResult $result)
    {
        $request->validate([
            'student_name' => 'required|string|max:191',
            'test_type' => 'required|string|max:100',
            'overall_band' => 'required|numeric|min:0|max:9',
            'listening_score' => 'nullable|numeric|min:0|max:9',
            'reading_score' => 'nullable|numeric|min:0|max:9',
            'writing_score' => 'nullable|numeric|min:0|max:9',
            'speaking_score' => 'nullable|numeric|min:0|max:9',
            'test_date' => 'nullable|date',
            'student_image_file' => 'nullable|image|max:5120',
            'certificate_image_file' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('student_image_file')) {
            $media = $this->mediaService->upload($request->file('student_image_file'), 'results/students', $request->student_name);
            $result->student_image = $media->file_path;
        }

        if ($request->hasFile('certificate_image_file')) {
            $media = $this->mediaService->upload($request->file('certificate_image_file'), 'results/certs', $request->student_name . ' Certificate');
            $result->certificate_image = $media->file_path;
        }

        $result->student_name = $request->student_name;
        $result->test_type = $request->test_type;
        $result->overall_band = $request->overall_band;
        $result->listening_score = $request->listening_score;
        $result->reading_score = $request->reading_score;
        $result->writing_score = $request->writing_score;
        $result->speaking_score = $request->speaking_score;
        $result->test_date = $request->test_date;
        $result->description = $request->description;
        $result->is_featured = $request->boolean('is_featured', false);
        $result->save();

        ActivityLog::log('update', 'iets_results', "Updated IETS result: {$result->student_name}");

        return redirect()->route('admin.iets.results.index')->with('success', 'Result updated successfully.');
    }

    public function toggle(IetsResult $result)
    {
        $result->is_featured = !$result->is_featured;
        $result->save();

        $state = $result->is_featured ? 'featured' : 'standard';
        ActivityLog::log('update', 'iets_results', "Toggled featured status for result {$result->student_name} to {$state}.");

        return back()->with('success', "Result featured status changed to {$state}.");
    }

    public function destroy(IetsResult $result)
    {
        $name = $result->student_name;
        $result->delete();
        ActivityLog::log('delete', 'iets_results', "Deleted IETS result for: {$name}");
        return redirect()->route('admin.iets.results.index')->with('success', 'Result deleted.');
    }
}
