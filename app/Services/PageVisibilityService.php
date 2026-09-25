<?php

namespace App\Services;

use App\Models\Setting;

class PageVisibilityService
{
    /**
     * Complete list of all public pages configurable on the website.
     */
    public static function getAllPages(): array
    {
        return [
            'home' => [
                'key' => 'home',
                'name' => 'Home Page',
                'urdu_name' => 'مرکزی صفحہ (Home)',
                'route' => 'home',
                'url' => '/',
                'icon' => 'home',
                'badge' => 'Main',
                'description' => 'Main landing page featuring hero slider, key highlights, stats, and course teasers.',
            ],
            'about' => [
                'key' => 'about',
                'name' => 'About Us',
                'urdu_name' => 'ہمارے بارے میں (About)',
                'route' => 'about',
                'url' => '/about',
                'icon' => 'info',
                'badge' => 'Core',
                'description' => 'Institution mission, vision, methodology, and why students choose Apex Academy.',
            ],
            'history' => [
                'key' => 'history',
                'name' => 'Academy History',
                'urdu_name' => 'تاریخ و سنگ میل (History)',
                'route' => 'history',
                'url' => '/history',
                'icon' => 'history',
                'badge' => 'Profile',
                'description' => 'Timeline and evolutionary milestones of the institution from founding to date.',
            ],
            'iets' => [
                'key' => 'iets',
                'name' => 'IETS Prep Programs',
                'urdu_name' => 'آئی ای ٹی ایس کورسز (IETS Prep)',
                'route' => 'iets',
                'url' => '/iets',
                'icon' => 'book-open',
                'badge' => 'Academics',
                'description' => 'Official IETS/IELTS test preparation course modules, mock exams, and syllabi.',
            ],
            'results' => [
                'key' => 'results',
                'name' => 'Student Results & Badges',
                'urdu_name' => 'طلباء کے نتائج (Results)',
                'route' => 'iets.results',
                'url' => '/iets/results',
                'icon' => 'award',
                'badge' => 'Achievements',
                'description' => 'Showcases high band achievers, testimonials, and verified student scores.',
            ],
            'teachers' => [
                'key' => 'teachers',
                'name' => 'Faculty & Teachers',
                'urdu_name' => 'اساتذہ و فیکلٹی (Teachers)',
                'route' => 'teachers',
                'url' => '/teachers',
                'icon' => 'graduation-cap',
                'badge' => 'Staff',
                'description' => 'Directory of certified professors, master trainers, and expert examiners.',
            ],
            'classrooms' => [
                'key' => 'classrooms',
                'name' => 'Multimedia Classrooms',
                'urdu_name' => 'کلاس رومز و لیبز (Classrooms)',
                'route' => 'classrooms',
                'url' => '/classrooms',
                'icon' => 'monitor',
                'badge' => 'Facilities',
                'description' => 'Smart digital halls, soundproof listening labs, and speaking test cabins.',
            ],
            'campus' => [
                'key' => 'campus',
                'name' => 'Campus & Facilities Gallery',
                'urdu_name' => 'کیمپس گیلری (Campus)',
                'route' => 'gallery',
                'url' => '/gallery',
                'icon' => 'image',
                'badge' => 'Gallery',
                'description' => 'Visual photo gallery of campus grounds, computer centers, and libraries.',
            ],
            'videos' => [
                'key' => 'videos',
                'name' => 'Videos & Vlogs',
                'urdu_name' => 'ویڈیوز و لاگز (Vlogs)',
                'route' => 'videos',
                'url' => '/videos',
                'icon' => 'video',
                'badge' => 'Media',
                'description' => 'Video tutorials, campus tours, masterclasses, and student video interviews.',
            ],
            'news' => [
                'key' => 'news',
                'name' => 'News & Blog Articles',
                'urdu_name' => 'خبریں و مضامین (News)',
                'route' => 'blog',
                'url' => '/blog',
                'icon' => 'newspaper',
                'badge' => 'Articles',
                'description' => 'Articles, admission announcements, schedule updates, and preparation tips.',
            ],
            'faq' => [
                'key' => 'faq',
                'name' => 'FAQ & Help Center',
                'urdu_name' => 'عمومی سوالات (FAQ)',
                'route' => 'faq',
                'url' => '/faq',
                'icon' => 'help-circle',
                'badge' => 'Support',
                'description' => 'Answers to common questions about admissions, fees, tests, and schedules.',
            ],
            'contact' => [
                'key' => 'contact',
                'name' => 'Contact & Location',
                'urdu_name' => 'رابطہ و نقشہ (Contact)',
                'route' => 'contact',
                'url' => '/contact',
                'icon' => 'mail',
                'badge' => 'Contact',
                'description' => 'Direct contact form, Google map, telephone numbers, and campus address.',
            ],
            'appointments' => [
                'key' => 'appointments',
                'name' => 'Book Appointment & Tests',
                'urdu_name' => 'بکنگ و اپوائنٹمنٹ (Book Session)',
                'route' => 'appointments',
                'url' => '/appointments',
                'icon' => 'calendar',
                'badge' => 'Booking',
                'description' => 'Student appointment booking portal for 1-on-1 counseling and IETS testing.',
            ],
        ];
    }

    /**
     * Get the array of enabled/visible page keys from settings.
     * Default: all pages enabled if setting not yet initialized.
     */
    public static function getVisiblePageKeys(): array
    {
        $raw = Setting::get('visible_pages');
        if (empty($raw)) {
            return array_keys(static::getAllPages());
        }

        if (is_array($raw)) {
            return $raw;
        }

        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return array_keys(static::getAllPages());
    }

    /**
     * Check if a specific page key is visible/enabled.
     */
    public static function isPageVisible(string $pageKey): bool
    {
        $visible = static::getVisiblePageKeys();
        return in_array($pageKey, $visible, true);
    }

    /**
     * Save the list of visible page keys.
     */
    public static function setVisiblePages(array $pageKeys): void
    {
        $validKeys = array_keys(static::getAllPages());
        $cleanKeys = array_values(array_intersect($validKeys, $pageKeys));
        Setting::set('visible_pages', json_encode($cleanKeys), 'cms');
    }

    /**
     * Toggle a single page key on or off.
     */
    public static function togglePage(string $pageKey): bool
    {
        $visible = static::getVisiblePageKeys();
        if (in_array($pageKey, $visible, true)) {
            $visible = array_values(array_diff($visible, [$pageKey]));
            $newStatus = false;
        } else {
            $visible[] = $pageKey;
            $newStatus = true;
        }
        static::setVisiblePages($visible);
        return $newStatus;
    }
}
