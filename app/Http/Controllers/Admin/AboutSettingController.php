<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class AboutSettingController extends Controller
{
    public function index()
    {
        $academyName = Setting::get('academy_name', 'Our Academy');

        $settings = [
            // Hero Section
            'about_hero_badge' => Setting::get('about_hero_badge', 'About Our Institution'),
            'about_hero_title' => Setting::get('about_hero_title', 'Dedicated to Inspiring Academic & Language Distinction'),
            'about_hero_description' => Setting::get('about_hero_description', 'Founded to bridge ambitious students with premier global education opportunities through rigorous test preparation and mentorship.'),
            
            // Mission, Vision & Core Values
            'about_mission_title' => Setting::get('about_mission_title', 'Our Mission'),
            'about_mission_desc' => Setting::get('about_mission_desc', 'To deliver personalized, scientifically backed educational and language training that empowers students to exceed standard admission thresholds and flourish in global universities.'),
            'about_vision_title' => Setting::get('about_vision_title', 'Our Vision'),
            'about_vision_desc' => Setting::get('about_vision_desc', 'To be the foremost educational academy in the region, recognized internationally for producing Band 8.0+ IELTS candidates, innovative AI diagnostics, and inspiring academic leaders.'),
            'about_values_title' => Setting::get('about_values_title', 'Core Values'),
            'about_values_desc' => Setting::get('about_values_desc', 'Academic integrity, unyielding pursuit of student success, technological innovation in language learning, and accessible mentorship for learners of all backgrounds.'),
            
            // Why Choose Us Section
            'about_why_title' => Setting::get('about_why_title', "Why Choose {$academyName}?"),
            'about_why_subtitle' => Setting::get('about_why_subtitle', 'Our systematic educational framework sets the benchmark for test preparation and academic counseling.'),
            'about_feature1_title' => Setting::get('about_feature1_title', 'Integrated AI Diagnostics'),
            'about_feature1_desc' => Setting::get('about_feature1_desc', 'Our AI speech and essay evaluation modules give instant band score predictions tailored to official IELTS criteria.'),
            'about_feature2_title' => Setting::get('about_feature2_title', 'Acoustic Testing Labs'),
            'about_feature2_desc' => Setting::get('about_feature2_desc', 'Simulate authentic exam day pressure with individual noise-isolated listening booths and digital recording consoles.'),
            'about_feature3_title' => Setting::get('about_feature3_title', 'Certified British & IDP Trainers'),
            'about_feature3_desc' => Setting::get('about_feature3_desc', 'All faculty members possess CELTA / DELTA certifications with over a decade of verified classroom teaching experience.'),
        ];

        return view('admin.about.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'about_hero_badge',
            'about_hero_title',
            'about_hero_description',
            'about_mission_title',
            'about_mission_desc',
            'about_vision_title',
            'about_vision_desc',
            'about_values_title',
            'about_values_desc',
            'about_why_title',
            'about_why_subtitle',
            'about_feature1_title',
            'about_feature1_desc',
            'about_feature2_title',
            'about_feature2_desc',
            'about_feature3_title',
            'about_feature3_desc',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field), 'about');
            }
        }

        ActivityLog::log('update', 'about', 'Updated About Page content and settings.');

        return back()->with('success', 'About Page content updated successfully.');
    }
}
