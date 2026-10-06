<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SeoMeta;
use App\Models\Setting;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ContactFrontendController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $settings = [
            'address' => Setting::get('contact_address', '124 Academic Boulevard, Knowledge Park'),
            'phone' => Setting::get('contact_phone', '+1 (555) 234-5678'),
            'phone_2' => Setting::get('contact_phone_2', ''),
            'timings' => Setting::get('academy_timings', 'Mon - Sat: 8:00 AM - 7:00 PM'),
            'email' => Setting::get('contact_email', 'info@antiacademy.edu'),
            'whatsapp' => Setting::get('contact_whatsapp', '+15552345678'),
        ];
        $seo = SeoMeta::getForPage('contact');
        return view('frontend.contact', compact('settings', 'seo'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:191',
            'message' => 'required|string|max:2000',
        ]);

        $msg = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread',
        ]);

        $this->notificationService->sendContactMessageNotification($msg);

        return back()->with('success', 'Thank you! Your message has been received. Our team will get back to you shortly.');
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'course' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
        ]);

        \App\Models\Review::create([
            'name' => $request->name,
            'email' => $request->email,
            'course' => $request->course ?? 'IELTS / Language Student',
            'rating' => (int) $request->rating,
            'review' => $request->review,
            'is_approved' => false, // Requires admin approval
            'is_featured' => false,
        ]);

        return back()->with('review_success', 'Thank you! Your review has been submitted successfully and will be published after administrator verification.');
    }
}
