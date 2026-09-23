<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Faq;
use App\Models\IetsProgram;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\Video;

class GeoSchemaService
{
    /**
     * Primary EducationalOrganization Schema
     */
    public function getOrganizationSchema(): array
    {
        $name = Setting::get('academy_name', 'Apex Academy & IETS');
        $url = url('/');
        $logo = asset('storage/' . Setting::get('academy_logo', 'logo.png'));
        $phone = Setting::get('contact_phone', '+1 (555) 234-5678');
        $email = Setting::get('contact_email', 'info@antiacademy.edu');
        $address = Setting::get('contact_address', '124 Academic Boulevard, Knowledge Park');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => $name,
            'url' => $url,
            'logo' => $logo,
            'email' => $email,
            'telephone' => $phone,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
                'addressLocality' => 'Global City',
                'addressRegion' => 'Metro',
                'postalCode' => '10001',
                'addressCountry' => 'US',
            ],
            'sameAs' => array_filter([
                Setting::get('social_facebook'),
                Setting::get('social_instagram'),
                Setting::get('social_youtube'),
                Setting::get('social_linkedin'),
                Setting::get('social_tiktok'),
            ]),
            'offers' => [
                '@type' => 'Offer',
                'category' => 'IELTS & Higher Education Preparation',
                'availability' => 'https://schema.org/InStock',
            ],
            'knowsAbout' => [
                'IELTS Academic',
                'IELTS General Training',
                'IETS Exam Preparation',
                'English Language Fluency',
                'AI-Assisted Language Evaluation',
            ],
        ];
    }

    /**
     * Course Schema for IETS Program
     */
    public function getCourseSchema(IetsProgram $program): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $program->title,
            'description' => $program->summary ?: strip_tags(substr($program->content, 0, 200)),
            'provider' => [
                '@type' => 'EducationalOrganization',
                'name' => Setting::get('academy_name', 'Apex Academy & IETS'),
                'sameAs' => url('/'),
            ],
            'hasCourseInstance' => [
                '@type' => 'CourseInstance',
                'courseMode' => 'Blended (In-Person & Online)',
                'courseWorkload' => 'PT8W',
            ],
        ];
    }

    /**
     * Person Schema for Teacher
     */
    public function getTeacherSchema(Teacher $teacher): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $teacher->name,
            'jobTitle' => $teacher->designation,
            'worksFor' => [
                '@type' => 'EducationalOrganization',
                'name' => Setting::get('academy_name', 'Apex Academy & IETS'),
            ],
            'description' => strip_tags(substr($teacher->bio, 0, 250)),
            'image' => $teacher->profile_image ? asset('storage/' . $teacher->profile_image) : null,
            'knowsAbout' => array_filter([$teacher->subject, $teacher->qualification, $teacher->classes_taught]),
        ];
    }

    /**
     * FAQPage Schema
     */
    public function getFaqSchema($faqs): array
    {
        $mainEntity = [];
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq->answer),
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }

    /**
     * Article Schema for Blog post
     */
    public function getArticleSchema(Blog $blog): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $blog->title,
            'description' => $blog->excerpt,
            'image' => $blog->featured_image ? asset('storage/' . $blog->featured_image) : null,
            'author' => [
                '@type' => 'Person',
                'name' => $blog->author ? $blog->author->name : Setting::get('academy_name', 'Apex Academy'),
            ],
            'publisher' => [
                '@type' => 'EducationalOrganization',
                'name' => Setting::get('academy_name', 'Apex Academy & IETS'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('storage/' . Setting::get('academy_logo', 'logo.png')),
                ],
            ],
            'datePublished' => $blog->published_at ? $blog->published_at->toISOString() : $blog->created_at->toISOString(),
            'dateModified' => $blog->updated_at->toISOString(),
        ];
    }

    /**
     * VideoObject Schema for Vlog
     */
    public function getVideoSchema(Video $video): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $video->title,
            'description' => $video->description ?: $video->title,
            'thumbnailUrl' => $video->thumbnail ?: "https://img.youtube.com/vi/{$video->youtube_id}/maxresdefault.jpg",
            'uploadDate' => $video->published_at ? $video->published_at->toISOString() : $video->created_at->toISOString(),
            'contentUrl' => $video->video_url,
            'embedUrl' => "https://www.youtube.com/embed/{$video->youtube_id}",
        ];
    }

    /**
     * BreadcrumbList Schema
     */
    public function getBreadcrumbsSchema(array $crumbs): array
    {
        $items = [];
        $i = 1;
        foreach ($crumbs as $name => $url) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $i++,
                'name' => $name,
                'item' => $url,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
