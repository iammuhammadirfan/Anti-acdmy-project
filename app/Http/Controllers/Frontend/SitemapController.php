<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\IetsProgram;
use App\Models\Teacher;
use App\Models\Video;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $urls = [
            url('/'),
            url('/about'),
            url('/history'),
            url('/teachers'),
            url('/classrooms'),
            url('/gallery'),
            url('/iets'),
            url('/iets/results'),
            url('/videos'),
            url('/blog'),
            url('/faq'),
            url('/appointments'),
            url('/contact'),
            url('/privacy-policy'),
            url('/terms-and-conditions'),
        ];

        $teachers = Teacher::active()->pluck('slug');
        foreach ($teachers as $slug) {
            $urls[] = url("/teachers/{$slug}");
        }

        $videos = Video::active()->pluck('slug');
        foreach ($videos as $slug) {
            $urls[] = url("/videos/{$slug}");
        }

        $blogs = Blog::published()->pluck('slug');
        foreach ($blogs as $slug) {
            $urls[] = url("/blog/{$slug}");
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $u) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($u) . '</loc>';
            $xml .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /api/\n\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
