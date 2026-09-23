<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Classroom;
use App\Models\Faq;
use App\Models\IetsProgram;
use App\Models\IetsResult;
use App\Models\PageSection;
use App\Models\SeoMeta;
use App\Models\Slider;
use App\Models\Statistic;
use App\Models\Teacher;
use App\Models\Video;
use App\Services\GeoSchemaService;

class HomeController extends Controller
{
    public function index(GeoSchemaService $geoService)
    {
        $sliders = Slider::active()->get();
        $sections = PageSection::active()->get()->keyBy('section_key');
        $statistics = Statistic::active()->get();
        $teachers = Teacher::active()->limit(4)->get();
        $ietsPrograms = IetsProgram::active()->limit(4)->get();
        $ietsResults = IetsResult::featured()->latest('test_date')->limit(6)->get();
        if ($ietsResults->isEmpty()) {
            $ietsResults = IetsResult::latest('test_date')->limit(6)->get();
        }
        $classrooms = Classroom::active()->limit(3)->get();
        $videos = Video::active()->latest('published_at')->limit(3)->get();
        $blogs = Blog::published()->limit(3)->get();
        $faqs = Faq::active()->limit(6)->get();

        $seo = SeoMeta::getForPage('home');
        $orgSchema = $geoService->getOrganizationSchema();
        $faqSchema = $faqs->isNotEmpty() ? $geoService->getFaqSchema($faqs) : null;

        return view('frontend.home', compact(
            'sliders',
            'sections',
            'statistics',
            'teachers',
            'ietsPrograms',
            'ietsResults',
            'classrooms',
            'videos',
            'blogs',
            'faqs',
            'seo',
            'orgSchema',
            'faqSchema'
        ));
    }
}
