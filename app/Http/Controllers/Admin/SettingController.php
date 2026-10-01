<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Services\MediaUploadService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    protected MediaUploadService $mediaService;
    protected NotificationService $notificationService;

    public function __construct(MediaUploadService $mediaService, NotificationService $notificationService)
    {
        $this->mediaService = $mediaService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $settings = [
            'academy_name' => Setting::get('academy_name', 'Apex Academy & IETS'),
            'academy_logo' => Setting::get('academy_logo', ''),
            'contact_email' => Setting::get('contact_email', 'info@antiacademy.edu'),
            'contact_phone' => Setting::get('contact_phone', '+1 (555) 234-5678'),
            'contact_whatsapp' => Setting::get('contact_whatsapp', '+15552345678'),
            'contact_address' => Setting::get('contact_address', '124 Academic Boulevard, Knowledge Park'),
            'social_facebook' => Setting::get('social_facebook', 'https://facebook.com'),
            'social_instagram' => Setting::get('social_instagram', 'https://instagram.com'),
            'social_youtube' => Setting::get('social_youtube', 'https://youtube.com'),
            'social_linkedin' => Setting::get('social_linkedin', 'https://linkedin.com'),
            'social_tiktok' => Setting::get('social_tiktok', 'https://tiktok.com'),
            'whatsapp_provider' => Setting::get('whatsapp_provider', 'meta_cloud'),
            'whatsapp_phone_number_id' => Setting::get('whatsapp_phone_number_id', ''),
            'whatsapp_access_token' => Setting::get('whatsapp_access_token', ''),
            'twilio_sid' => Setting::get('twilio_sid', ''),
            'twilio_token' => Setting::get('twilio_token', ''),
            'twilio_from_whatsapp' => Setting::get('twilio_from_whatsapp', ''),
            'callmebot_enabled' => Setting::get('callmebot_enabled', '1'),
            'admin_whatsapp_phone' => Setting::get('admin_whatsapp_phone', '923235502570'),
            'callmebot_api_key' => Setting::get('callmebot_api_key', ''),
            'smtp_host' => Setting::get('smtp_host', config('mail.mailers.smtp.host', '127.0.0.1')),
            'smtp_port' => Setting::get('smtp_port', config('mail.mailers.smtp.port', 587)),
            'smtp_username' => Setting::get('smtp_username', ''),
            'smtp_password' => Setting::get('smtp_password', ''),
            'smtp_encryption' => Setting::get('smtp_encryption', 'tls'),
            'mail_from_name' => Setting::get('mail_from_name', 'Apex Academy & IETS'),
            'mail_from_address' => Setting::get('mail_from_address', 'no-reply@antiacademy.edu'),
            'admin_email' => Setting::get('admin_email', Setting::get('contact_email', 'admin@antiacademy.edu')),
            'results_page_badge' => Setting::get('results_page_badge', 'Official Verified Scorecards & Posters'),
            'results_page_title' => Setting::get('results_page_title', 'Student Hall of Fame & Results'),
            'results_page_subtitle' => Setting::get('results_page_subtitle', 'Authentic standardized result cards earned by our candidates. Filter by IELTS, PTE, or TOEFL to view genuine scorecards.'),
            'results_slider_title' => Setting::get('results_slider_title', 'Featured Result Scorecards'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $group = $request->get('group', 'general');

        if ($group === 'general') {
            Setting::set('academy_name', $request->academy_name, 'general');
            Setting::set('contact_email', $request->contact_email, 'general');
            Setting::set('contact_phone', $request->contact_phone, 'general');
            Setting::set('contact_whatsapp', $request->contact_whatsapp, 'general');
            Setting::set('contact_address', $request->contact_address, 'general');

            if ($request->hasFile('logo_file')) {
                $media = $this->mediaService->upload($request->file('logo_file'), 'settings', 'Logo');
                Setting::set('academy_logo', $media->file_path, 'general');
            }
        } elseif ($group === 'social') {
            Setting::set('social_facebook', $request->social_facebook, 'social');
            Setting::set('social_instagram', $request->social_instagram, 'social');
            Setting::set('social_youtube', $request->social_youtube, 'social');
            Setting::set('social_linkedin', $request->social_linkedin, 'social');
            Setting::set('social_tiktok', $request->social_tiktok, 'social');
        } elseif ($group === 'whatsapp') {
            Setting::set('whatsapp_provider', $request->whatsapp_provider, 'whatsapp');
            Setting::set('whatsapp_phone_number_id', $request->whatsapp_phone_number_id, 'whatsapp');
            if ($request->filled('whatsapp_access_token')) {
                Setting::set('whatsapp_access_token', $request->whatsapp_access_token, 'whatsapp', true);
            }
            Setting::set('twilio_sid', $request->twilio_sid, 'whatsapp');
            Setting::set('twilio_from_whatsapp', $request->twilio_from_whatsapp, 'whatsapp');
            if ($request->filled('twilio_token')) {
                Setting::set('twilio_token', $request->twilio_token, 'whatsapp', true);
            }

            // CallMeBot Free WhatsApp Gateway for Admin
            Setting::set('callmebot_enabled', $request->has('callmebot_enabled') ? '1' : '0', 'whatsapp');
            if ($request->filled('admin_whatsapp_phone')) {
                Setting::set('admin_whatsapp_phone', preg_replace('/[^0-9]/', '', $request->admin_whatsapp_phone), 'whatsapp');
            }
            if ($request->filled('callmebot_api_key')) {
                Setting::set('callmebot_api_key', trim($request->callmebot_api_key), 'whatsapp', true);
            }
        } elseif ($group === 'email') {
            Setting::set('smtp_host', $request->smtp_host, 'email');
            Setting::set('smtp_port', $request->smtp_port, 'email');
            Setting::set('smtp_username', $request->smtp_username, 'email');
            Setting::set('smtp_encryption', $request->smtp_encryption, 'email');
            Setting::set('mail_from_name', $request->mail_from_name, 'email');
            Setting::set('mail_from_address', $request->mail_from_address, 'email');
            Setting::set('admin_email', $request->admin_email, 'email');
            if ($request->filled('smtp_password')) {
                Setting::set('smtp_password', $request->smtp_password, 'email', true);
            }

            // Immediately reconfigure runtime dynamic mailer
            $this->notificationService->configureDynamicSmtp();
        } elseif ($group === 'results') {
            Setting::set('results_page_badge', $request->results_page_badge, 'results');
            Setting::set('results_page_title', $request->results_page_title, 'results');
            Setting::set('results_page_subtitle', $request->results_page_subtitle, 'results');
            Setting::set('results_slider_title', $request->results_slider_title, 'results');
        }

        ActivityLog::log('update', 'settings', "Updated {$group} system settings.");

        return back()->with('success', ucfirst($group) . ' settings saved.');
    }

    /**
     * Send a test SMTP email to verify credentials
     */
    public function testSmtp(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $testEmail = $request->test_email;
        $appName = Setting::get('academy_name', config('app.name', 'Apex Academy & IETS Center'));

        try {
            $this->notificationService->configureDynamicSmtp();

            $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
            $fromName = Setting::get('mail_from_name', $appName);

            $html = <<<HTML
<div style="font-family: Arial, sans-serif; background: #f8fafc; padding: 24px; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 500px; margin: 0 auto;">
    <h2 style="color: #16a34a; margin-top: 0;">&#10004; SMTP Server Connected Successfully!</h2>
    <p style="color: #334155; font-size: 14px; line-height: 1.6;">
        Congratulations! Your SMTP configuration on <strong>{$appName}</strong> is working perfectly.
    </p>
    <div style="background: #ffffff; padding: 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px; color: #475569;">
        <p style="margin: 2px 0;"><strong>Recipient:</strong> {$testEmail}</p>
        <p style="margin: 2px 0;"><strong>From:</strong> {$fromName} &lt;{$fromAddress}&gt;</p>
        <p style="margin: 2px 0;"><strong>Timestamp:</strong> {$request->server('REQUEST_TIME')}</p>
    </div>
    <p style="color: #64748b; font-size: 12px; margin-top: 16px; margin-bottom: 0;">
        All appointment bookings and contact inquiries will now dispatch emails seamlessly.
    </p>
</div>
HTML;

            Mail::html($html, function ($mail) use ($testEmail, $appName, $fromAddress, $fromName) {
                if (!empty($fromAddress)) {
                    $mail->from($fromAddress, $fromName);
                }
                $mail->to($testEmail)
                     ->subject("SMTP Test Connection Successful - {$appName}");
            });

            return response()->json([
                'success' => true,
                'message' => "Success! Test email was successfully dispatched to {$testEmail}. Your SMTP server is fully functional.",
            ]);
        } catch (\Throwable $e) {
            Log::error("SMTP Test Dispatch Error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => "SMTP Connection Failed: " . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Send an instant test WhatsApp message to verify CallMeBot credentials
     */
    public function testWhatsApp(Request $request)
    {
        $request->validate([
            'admin_phone' => 'required',
            'api_key' => 'required',
        ]);

        $rawPhone = $request->admin_phone;
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        $apiKey = trim($request->api_key);
        $appName = Setting::get('academy_name', config('app.name', 'Apex Academy & IETS Center'));

        if (empty($cleanPhone) || empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide both your WhatsApp number (with country code) and your CallMeBot API key.',
            ], 422);
        }

        $nowStr = now()->format('h:i A, d M Y');
        $testMsg = "✅ *CallMeBot Test Alert*\n\n"
                 . "🏛 *{$appName}*\n"
                 . "Congratulations! Your Admin WhatsApp alert system is 100% active.\n\n"
                 . "Whenever a candidate books an IETS test or counseling session, you will instantly receive full booking details here!\n\n"
                 . "🕒 *Sent at:* {$nowStr}";

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get('https://api.callmebot.com/whatsapp.php', [
                'phone' => $cleanPhone,
                'text' => $testMsg,
                'apikey' => $apiKey,
            ]);

            $body = $response->body();
            $lowerBody = strtolower($body);

            // CallMeBot returns 200 with text, or 401/400 if bad key/phone
            if ($response->successful() && !str_contains($lowerBody, 'error') && !str_contains($lowerBody, 'apikey is invalid') && !str_contains($lowerBody, 'not allowed')) {
                return response()->json([
                    'success' => true,
                    'message' => "WhatsApp test alert successfully sent to +{$cleanPhone}! Please check your WhatsApp app right now.",
                ]);
            }

            $cleanErr = strip_tags($body);
            if (empty($cleanErr)) {
                $cleanErr = "HTTP {$response->status()} Gateway error";
            }

            return response()->json([
                'success' => false,
                'message' => "CallMeBot returned: {$cleanErr}. Please ensure you sent 'I allow callmebot to send me messages' on WhatsApp and your phone number includes country code without + or 00.",
            ], 422);
        } catch (\Throwable $e) {
            Log::error('CallMeBot Test Dispatch Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'CallMeBot Connection Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}

