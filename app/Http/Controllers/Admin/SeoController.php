<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Redirect;
use App\Models\SeoMeta;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    protected MediaUploadService $mediaService;

    public function __construct(MediaUploadService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public static array $pages = [
        'home' => 'Home Page',
        'about' => 'About Page',
        'history' => 'Academy History Page',
        'teachers' => 'Teachers Directory',
        'classrooms' => 'Classrooms & Facilities',
        'gallery' => 'Campus Gallery',
        'iets' => 'IETS Preparation Program',
        'iets_results' => 'IETS Results & Band Scores',
        'videos' => 'Videos & Vlogs',
        'blog' => 'Blog & News',
        'faq' => 'Frequently Asked Questions',
        'appointments' => 'Book Appointment Page',
        'contact' => 'Contact Us Page',
        'privacy-policy' => 'Privacy Policy',
        'terms-and-conditions' => 'Terms and Conditions',
    ];

    public function index()
    {
        $pages = self::$pages;
        $seoRecords = SeoMeta::all()->keyBy('page_key');
        $redirects = Redirect::all();
        return view('admin.seo.index', compact('pages', 'seoRecords', 'redirects'));
    }

    public function edit(string $pageKey)
    {
        $pages = self::$pages;
        if (!isset($pages[$pageKey])) {
            abort(404);
        }

        $pageName = $pages[$pageKey];
        $meta = SeoMeta::firstOrNew(['page_key' => $pageKey]);

        return view('admin.seo.edit', compact('pageKey', 'pageName', 'meta'));
    }

    public function update(Request $request, string $pageKey)
    {
        $request->validate([
            'seo_title' => 'nullable|string|max:191',
            'meta_description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'robots_meta' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:191',
            'og_description' => 'nullable|string',
            'og_image_file' => 'nullable|image|max:5120',
            'twitter_title' => 'nullable|string|max:191',
            'twitter_description' => 'nullable|string',
            'schema_type' => 'required|string',
            'custom_schema_json' => 'nullable|string',
        ]);

        $meta = SeoMeta::firstOrNew(['page_key' => $pageKey]);

        if ($request->hasFile('og_image_file')) {
            $media = $this->mediaService->upload($request->file('og_image_file'), 'seo', "og-{$pageKey}");
            $meta->og_image = $media->file_path;
            $meta->twitter_image = $media->file_path;
        }

        $meta->seo_title = $request->seo_title;
        $meta->meta_description = $request->meta_description;
        $meta->keywords = $request->keywords;
        $meta->canonical_url = $request->canonical_url;
        $meta->robots_meta = $request->robots_meta ?: 'index, follow';
        $meta->og_title = $request->og_title ?: $request->seo_title;
        $meta->og_description = $request->og_description ?: $request->meta_description;
        $meta->twitter_title = $request->twitter_title ?: $request->seo_title;
        $meta->twitter_description = $request->twitter_description ?: $request->meta_description;
        $meta->schema_type = $request->schema_type;
        $meta->custom_schema_json = $request->custom_schema_json;
        $meta->save();

        ActivityLog::log('update', 'seo', "Updated SEO/GEO metadata for: {$pageKey}");

        return redirect()->route('admin.seo.index')->with('success', "SEO metadata for '{$pageKey}' saved successfully.");
    }

    public function storeRedirect(Request $request)
    {
        $request->validate([
            'source_url' => 'required|string|unique:redirects,source_url',
            'destination_url' => 'required|string',
            'status_code' => 'required|in:301,302',
        ]);

        Redirect::create([
            'source_url' => $request->source_url,
            'destination_url' => $request->destination_url,
            'status_code' => (int) $request->status_code,
            'is_active' => true,
        ]);

        ActivityLog::log('create', 'seo', "Created 301/302 redirect: {$request->source_url} -> {$request->destination_url}");

        return back()->with('success', 'Redirect created.');
    }

    public function destroyRedirect(Redirect $redirect)
    {
        $redirect->delete();
        return back()->with('success', 'Redirect deleted.');
    }
}
