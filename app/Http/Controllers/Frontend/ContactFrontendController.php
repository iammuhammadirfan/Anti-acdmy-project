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
}
