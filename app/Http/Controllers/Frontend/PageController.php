<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\CampusGallery;
use App\Models\Classroom;
use App\Models\Faq;
use App\Models\HistoryTimeline;
use App\Models\IetsProgram;
use App\Models\IetsResult;
use App\Models\SeoMeta;
use App\Models\Statistic;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Services\GeoSchemaService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected GeoSchemaService $geoService;

    public function __construct(GeoSchemaService $geoService)
    {
        $this->geoService = $geoService;
    }

    public function about()
    {
        $statistics = Statistic::active()->get();
        $timelines = HistoryTimeline::active()->get();
        $seo = SeoMeta::getForPage('about');
        $orgSchema = $this->geoService->getOrganizationSchema();
        return view('frontend.about', compact('statistics', 'timelines', 'seo', 'orgSchema'));
    }

    public function history()
    {
        $timelines = HistoryTimeline::active()->get();
        $seo = SeoMeta::getForPage('history');
        return view('frontend.history', compact('timelines', 'seo'));
    }

    public function classrooms()
    {
        $classrooms = Classroom::active()->get();
        $seo = SeoMeta::getForPage('classrooms');
        return view('frontend.classrooms', compact('classrooms', 'seo'));
    }

    public function gallery(Request $request)
    {
        $category = $request->get('category');
        $query = CampusGallery::active();
        if ($category) {
            $query->where('category', $category);
        }
        $photos = $query->paginate(24);
        $seo = SeoMeta::getForPage('gallery');
        return view('frontend.gallery', compact('photos', 'category', 'seo'));
    }

    public function iets()
    {
        $programs = IetsProgram::active()->get();
        $results = IetsResult::featured()->latest('test_date')->limit(4)->get();
        if ($results->isEmpty()) {
            $results = IetsResult::latest('test_date')->limit(4)->get();
        }
        $seo = SeoMeta::getForPage('iets');
        return view('frontend.iets', compact('programs', 'results', 'seo'));
    }

    public function ietsResults(Request $request)
    {
        $band = $request->get('band');
        $type = $request->get('type');
        $query = IetsResult::latest('test_date');
        if ($band) {
            $query->where('overall_band', '>=', (float) $band);
        }
        if ($type) {
            $query->where('test_type', $type);
        }
        $results = $query->paginate(18);
        $seo = SeoMeta::getForPage('iets_results');
        return view('frontend.iets_results', compact('results', 'band', 'type', 'seo'));
    }

    public function videos(Request $request)
    {
        $categorySlug = $request->get('category');
        $categories = VideoCategory::where('status', true)->get();
        $query = Video::active()->latest('published_at');
        if ($categorySlug) {
            $cat = VideoCategory::where('slug', $categorySlug)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }
        $videos = $query->paginate(12);
        $seo = SeoMeta::getForPage('videos');
        return view('frontend.videos.index', compact('videos', 'categories', 'categorySlug', 'seo'));
    }

    public function videoDetail(string $slug)
    {
        $video = Video::where('slug', $slug)->active()->firstOrFail();
        $video->increment('views_count');
        $relatedVideos = Video::active()->where('id', '!=', $video->id)->limit(4)->get();
        $videoSchema = $this->geoService->getVideoSchema($video);
        return view('frontend.videos.show', compact('video', 'relatedVideos', 'videoSchema'));
    }

    public function blog(Request $request)
    {
        $categorySlug = $request->get('category');
        $categories = BlogCategory::withCount('blogs')->get();
        $query = Blog::published();
        if ($categorySlug) {
            $cat = BlogCategory::where('slug', $categorySlug)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }
        $blogs = $query->paginate(9);
        $seo = SeoMeta::getForPage('blog');
        return view('frontend.blog.index', compact('blogs', 'categories', 'categorySlug', 'seo'));
    }

    public function blogDetail(string $slug)
    {
        $blog = Blog::where('slug', $slug)->with('category', 'author', 'tags')->firstOrFail();
        $relatedBlogs = Blog::published()->where('id', '!=', $blog->id)->limit(3)->get();
        $articleSchema = $this->geoService->getArticleSchema($blog);
        return view('frontend.blog.show', compact('blog', 'relatedBlogs', 'articleSchema'));
    }

    public function faq(Request $request)
    {
        $category = $request->get('category');
        $query = Faq::active();
        if ($category) {
            $query->where('category', $category);
        }
        $faqs = $query->get();
        $seo = SeoMeta::getForPage('faq');
        $faqSchema = $this->geoService->getFaqSchema($faqs);
        return view('frontend.faq', compact('faqs', 'category', 'seo', 'faqSchema'));
    }

    public function privacy()
    {
        return view('frontend.legal.privacy');
    }

    public function terms()
    {
        return view('frontend.legal.terms');
    }
}
