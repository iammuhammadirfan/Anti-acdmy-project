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
            'smtp_host' => Setting::get('smtp_host', config('mail.mailers.smtp.host', '127.0.0.1')),
            'smtp_port' => Setting::get('smtp_port', config('mail.mailers.smtp.port', 587)),
            'smtp_username' => Setting::get('smtp_username', ''),
            'smtp_password' => Setting::get('smtp_password', ''),
            'smtp_encryption' => Setting::get('smtp_encryption', 'tls'),
            'mail_from_name' => Setting::get('mail_from_name', 'Apex Academy & IETS'),
            'mail_from_address' => Setting::get('mail_from_address', 'no-reply@antiacademy.edu'),
            'admin_email' => Setting::get('admin_email', Setting::get('contact_email', 'admin@antiacademy.edu')),
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
}
