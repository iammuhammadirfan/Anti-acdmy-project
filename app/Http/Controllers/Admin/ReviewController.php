<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Review;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(Request $request)
    {
        $status = $request->get('filter'); // 'approved', 'pending'
        $query = Review::query()->latest('id');

        if ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'pending') {
            $query->where('is_approved', false);
        }

        $reviews = $query->paginate(20);
        $pendingCount = Review::where('is_approved', false)->count();
        $approvedCount = Review::where('is_approved', true)->count();

        return view('admin.reviews.index', compact('reviews', 'pendingCount', 'approvedCount', 'status'));
    }

    public function create()
    {
        return view('admin.reviews.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'course' => 'nullable|string|max:191',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
            'avatar_file' => 'nullable|image|max:2048',
            'display_order' => 'integer',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar_file')) {
            $media = $this->mediaService->upload($request->file('avatar_file'), 'reviews', $request->name);
            $avatarPath = $media->file_path;
        }

        $review = Review::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'course' => $request->course ?? 'IELTS Academic',
            'rating' => (int) $request->rating,
            'review' => $request->review,
            'avatar' => $avatarPath,
            'is_approved' => $request->boolean('is_approved', true),
            'is_featured' => $request->boolean('is_featured', false),
            'display_order' => (int) $request->display_order,
        ]);

        ActivityLog::log('create', 'reviews', "Added review from {$review->name}");

        return redirect()->route('admin.reviews.index')->with('success', 'Review added successfully.');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'course' => 'nullable|string|max:191',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
            'avatar_file' => 'nullable|image|max:2048',
            'display_order' => 'integer',
        ]);

        if ($request->hasFile('avatar_file')) {
            $media = $this->mediaService->upload($request->file('avatar_file'), 'reviews', $request->name);
            $review->avatar = $media->file_path;
        }

        $review->name = $request->name;
        $review->email = $request->email;
        $review->phone = $request->phone;
        $review->course = $request->course;
        $review->rating = (int) $request->rating;
        $review->review = $request->review;
        $review->is_approved = $request->boolean('is_approved', false);
        $review->is_featured = $request->boolean('is_featured', false);
        $review->display_order = (int) $request->display_order;
        $review->save();

        ActivityLog::log('update', 'reviews', "Updated review for {$review->name}");

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function toggle(Review $review)
    {
        $review->is_approved = !$review->is_approved;
        $review->save();

        $state = $review->is_approved ? 'Approved & Published' : 'Unpublished (Pending)';
        ActivityLog::log('update', 'reviews', "Review from {$review->name} is now {$state}.");

        return back()->with('success', "Review marked as {$state}.");
    }

    public function destroy(Review $review)
    {
        $name = $review->name;
        $review->delete();
        ActivityLog::log('delete', 'reviews', "Deleted review from {$name}");

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }
}
