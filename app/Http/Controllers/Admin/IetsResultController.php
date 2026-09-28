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

    public function index(Request $request)
    {
        $query = IetsResult::latest('test_date')->latest('id');

        if ($request->filled('type') && in_array(strtoupper($request->type), ['IELTS', 'PTE', 'TOEFL'])) {
            $type = strtoupper($request->type);
            $query->where('test_type', 'LIKE', "%{$type}%");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'LIKE', "%{$search}%")
                  ->orWhere('overall_band', 'LIKE', "%{$search}%")
                  ->orWhere('test_type', 'LIKE', "%{$search}%");
            });
        }

        $results = $query->paginate(15)->withQueryString();
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
            'test_type' => 'required|string|in:IELTS,PTE,TOEFL',
            'overall_band' => 'required|string|max:50',
            'result_image_file' => 'required|image|max:10240',
            'test_date' => 'nullable|date',
            'description' => 'nullable|string|max:500',
            'is_featured' => 'nullable',
        ]);

        $cardImg = null;
        if ($request->hasFile('result_image_file')) {
            $media = $this->mediaService->uploadStandardResultCard(
                $request->file('result_image_file'),
                'results/cards',
                $request->student_name . ' ' . $request->test_type . ' Result Card'
            );
            $cardImg = $media->file_path;
        }

        $result = IetsResult::create([
            'student_name' => $request->student_name,
            'test_type' => $request->test_type,
            'overall_band' => $request->overall_band,
            'result_image' => $cardImg,
            'student_image' => $cardImg, // backward compatibility
            'certificate_image' => $cardImg,
            'test_date' => $request->test_date ?: now()->toDateString(),
            'description' => $request->description,
            'is_featured' => $request->boolean('is_featured', true),
        ]);

        ActivityLog::log('create', 'iets_results', "Added {$result->test_type} result card for: {$result->student_name} (Score/Band: {$result->overall_band})");

        return redirect()->route('admin.iets.results.index')->with('success', 'Student Result Card uploaded and standardized successfully.');
    }

    public function edit(IetsResult $result)
    {
        return view('admin.iets.results.edit', compact('result'));
    }

    public function update(Request $request, IetsResult $result)
    {
        $request->validate([
            'student_name' => 'required|string|max:191',
            'test_type' => 'required|string|in:IELTS,PTE,TOEFL',
            'overall_band' => 'required|string|max:50',
            'result_image_file' => 'nullable|image|max:10240',
            'test_date' => 'nullable|date',
            'description' => 'nullable|string|max:500',
            'is_featured' => 'nullable',
        ]);

        if ($request->hasFile('result_image_file')) {
            $media = $this->mediaService->uploadStandardResultCard(
                $request->file('result_image_file'),
                'results/cards',
                $request->student_name . ' ' . $request->test_type . ' Result Card'
            );
            $result->result_image = $media->file_path;
            $result->student_image = $media->file_path;
            $result->certificate_image = $media->file_path;
        }

        $result->student_name = $request->student_name;
        $result->test_type = $request->test_type;
        $result->overall_band = $request->overall_band;
        $result->test_date = $request->test_date ?: $result->test_date;
        $result->description = $request->description;
        $result->is_featured = $request->boolean('is_featured', false);
        $result->save();

        ActivityLog::log('update', 'iets_results', "Updated Result Card for: {$result->student_name}");

        return redirect()->route('admin.iets.results.index')->with('success', 'Result Card updated successfully.');
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
        ActivityLog::log('delete', 'iets_results', "Deleted result card for: {$name}");
        return redirect()->route('admin.iets.results.index')->with('success', 'Result Card deleted.');
    }
}
