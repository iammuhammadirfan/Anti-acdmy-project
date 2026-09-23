<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        $blogs = Blog::with('category', 'author', 'tags')->latest()->paginate(15);
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'category_id' => 'nullable|exists:blog_categories,id',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,review,published,scheduled',
            'image_file' => 'nullable|image|max:5120',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:191',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'tags' => 'array',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'blog', $request->title);
            $imagePath = $media->file_path;
        }

        $blog = Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'author_id' => auth()->id(),
            'featured_image' => $imagePath,
            'excerpt' => $request->excerpt ?: Str::limit(strip_tags($request->content), 160),
            'content' => $request->content,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? ($request->published_at ?: now()) : $request->published_at,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
        ]);

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        ActivityLog::log('create', 'blog', "Created blog article: {$blog->title} ({$blog->status})");

        return redirect()->route('admin.blog.index')->with('success', 'Blog article created.');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        $selectedTagIds = $blog->tags()->pluck('id')->toArray();
        return view('admin.blog.edit', compact('blog', 'categories', 'tags', 'selectedTagIds'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'category_id' => 'nullable|exists:blog_categories,id',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,review,published,scheduled',
            'image_file' => 'nullable|image|max:5120',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:191',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'tags' => 'array',
        ]);

        if ($request->hasFile('image_file')) {
            $media = $this->mediaService->upload($request->file('image_file'), 'blog', $request->title);
            $blog->featured_image = $media->file_path;
        }

        $blog->title = $request->title;
        $blog->category_id = $request->category_id;
        $blog->excerpt = $request->excerpt ?: Str::limit(strip_tags($request->content), 160);
        $blog->content = $request->content;
        $blog->status = $request->status;
        $blog->published_at = $request->status === 'published' ? ($request->published_at ?: now()) : $request->published_at;
        $blog->seo_title = $request->seo_title;
        $blog->seo_description = $request->seo_description;
        $blog->seo_keywords = $request->seo_keywords;
        $blog->save();

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        } else {
            $blog->tags()->detach();
        }

        ActivityLog::log('update', 'blog', "Updated blog article: {$blog->title}");

        return redirect()->route('admin.blog.index')->with('success', 'Blog article updated.');
    }

    public function destroy(Blog $blog)
    {
        $title = $blog->title;
        $blog->delete();
        ActivityLog::log('delete', 'blog', "Deleted blog article: {$title}");
        return redirect()->route('admin.blog.index')->with('success', 'Blog article deleted.');
    }
}
