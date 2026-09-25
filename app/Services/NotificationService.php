<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Dynamically configure SMTP mail settings from database settings if defined
     */
    public function configureDynamicSmtp(): void
    {
        try {
            $smtpHost = Setting::get('smtp_host');
// If Brevo API mode is selected
            if (!empty($smtpHost) && strtolower(trim($smtpHost)) === 'brevo-api') {
                $apiKey = Setting::get('smtp_password');
                $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
                $fromName = Setting::get('mail_from_name', Setting::get('academy_name', config('mail.from.name')));

                config([
                    'mail.default' => 'brevo',
                    'mail.mailers.brevo.transport' => 'brevo',
                    'services.brevo.key' => $apiKey,
                ]);

                if (!empty($fromAddress)) {
                    config([
                        'mail.from.address' => $fromAddress,
                        'mail.from.name' => $fromName,
                    ]);
                }

                if (app()->bound('mail.manager')) {
                    app('mail.manager')->purge('brevo');
                }
                return;
            }
            // If custom SMTP host is defined in settings
            if (!empty($smtpHost) && $smtpHost !== '127.0.0.1') {
                $port = (int) Setting::get('smtp_port', 587);
                $username = Setting::get('smtp_username');
                $password = Setting::get('smtp_password');
                $encryption = Setting::get('smtp_encryption', 'tls');
                $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
                $fromName = Setting::get('mail_from_name', Setting::get('academy_name', config('mail.from.name')));

                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.transport' => 'smtp',
                    'mail.mailers.smtp.host' => $smtpHost,
                    'mail.mailers.smtp.port' => $port,
                    'mail.mailers.smtp.encryption' => ($encryption === 'none' || empty($encryption)) ? null : $encryption,
                    'mail.mailers.smtp.username' => $username,
                    'mail.mailers.smtp.password' => $password,
                ]);

                if (!empty($fromAddress)) {
                    config([
                        'mail.from.address' => $fromAddress,
                        'mail.from.name' => $fromName,
                    ]);
                }

                if (app()->bound('mail.manager')) {
                    app('mail.manager')->purge('smtp');
                }
            } else {
                $fromAddress = Setting::get('mail_from_address');
                $fromName = Setting::get('mail_from_name');
                if (!empty($fromAddress)) {
                    config(['mail.from.address' => $fromAddress]);
                }
                if (!empty($fromName)) {
                    config(['mail.from.name' => $fromName]);
                }
            }
        } catch (\Throwable $e) {
            Log::debug('Dynamic SMTP configure notice: ' . $e->getMessage());
        }
    }

    /**
     * Send notifications when an appointment / test is booked
     */
    public function sendAppointmentCreated(Appointment $appointment): void
    {
        $this->configureDynamicSmtp();

        $appName = Setting::get('academy_name', config('app.name', 'Apex Academy & IETS Center'));
        $isIets = ($appointment->type === 'iets_test');

        $templateSlug = $isIets ? 'iets_confirmation' : 'counseling_confirmation';
        $template = EmailTemplate::getTemplate($templateSlug);

        $renderData = [
            'student_name' => $appointment->name,
            'email' => $appointment->email,
            'phone' => $appointment->phone,
            'date' => $appointment->appointment_date ? $appointment->appointment_date->format('l, F j, Y') : '',
            'time' => $appointment->time_slot,
            'registration_number' => $appointment->registration_number ?: $appointment->booking_code,
            'test_type' => $appointment->test_type ?: ($appointment->purpose ?: 'Campus Counseling'),
            'purpose' => $appointment->purpose ?: ($appointment->test_type ?: 'IETS Test Registration'),
            'status' => ucfirst($appointment->status),
            'academy_name' => $appName,
            'admin_notes' => $appointment->admin_notes ?: '',
        ];

        $rendered = $template->render($renderData);

        // 1. Send Student Confirmation Email
        $this->sendRawHtmlEmail($appointment->email, $appointment->name, $rendered['subject'], $rendered['body'], $appName);

        // 2. Send Admin Notification Alert
        $this->sendAdminAppointmentEmail($appointment, $appName);

        // 3. Send WhatsApp Notification
        $regNumber = $appointment->registration_number ?: $appointment->booking_code;
        $dateFormatted = $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : '';
        if ($isIets) {
            $waMessage = "Dear {$appointment->name},\n\nYour IETS test has been successfully scheduled at {$appName}.\n\nTest: {$appointment->test_type}\nDate: {$dateFormatted}\nTime: {$appointment->time_slot}\nRegistration Number: {$regNumber}\n\nPlease arrive on time with your registration number and valid ID.\n\nThank you.";
        } else {
            $waMessage = "Dear {$appointment->name},\n\nYour campus counseling session has been booked at {$appName}.\n\nDate: {$dateFormatted}\nTime: {$appointment->time_slot}\nRegistration Number: {$regNumber}\n\nPlease arrive on time.\n\nThank you.";
        }

        $this->sendWhatsAppMessage($appointment->whatsapp ?: $appointment->phone, $waMessage);
    }

    /**
     * Send notifications when appointment status is updated (confirmed/cancelled/completed)
     */
    public function sendAppointmentStatusUpdated(Appointment $appointment, string $newStatus): void
    {
        $this->configureDynamicSmtp();

        $appName = Setting::get('academy_name', config('app.name', 'Apex Academy & IETS Center'));
        $regNumber = $appointment->registration_number ?: $appointment->booking_code;

        $renderData = [
            'student_name' => $appointment->name,
            'email' => $appointment->email,
            'phone' => $appointment->phone,
            'date' => $appointment->appointment_date ? $appointment->appointment_date->format('l, F j, Y') : '',
            'time' => $appointment->time_slot,
            'registration_number' => $regNumber,
            'test_type' => $appointment->test_type ?: ($appointment->purpose ?: 'Campus Counseling'),
            'purpose' => $appointment->purpose ?: ($appointment->test_type ?: 'IETS Test Registration'),
            'status' => ucfirst($newStatus),
            'academy_name' => $appName,
            'admin_notes' => $appointment->admin_notes ?: '',
        ];

        if ($newStatus === 'cancelled') {
            $template = EmailTemplate::getTemplate('cancellation');
            $rendered = $template->render($renderData);
            $this->sendRawHtmlEmail($appointment->email, $appointment->name, $rendered['subject'], $rendered['body'], $appName);

            $dateStr = $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : '';
            $this->sendWhatsAppMessage(
                $appointment->whatsapp ?: $appointment->phone,
                "Hello {$appointment->name},\n\nYour booking ({$regNumber}) for {$dateStr} at {$appointment->time_slot} has been CANCELLED.\n\nPlease contact administration if you wish to reschedule."
            );
            return;
        }

        // Standard status email
        $subject = match ($newStatus) {
            'confirmed' => "Booking Confirmed: {$regNumber} - {$appName}",
            'completed' => "Thank you for visiting {$appName}",
            'no_show' => "Missed Appointment Notice - {$regNumber}",
            default => "Booking Status Update: {$regNumber}"
        };

        try {
            $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
            $fromName = Setting::get('mail_from_name', $appName);

            Mail::send('emails.appointment_status', ['appointment' => $appointment, 'appName' => $appName], function ($mail) use ($appointment, $subject, $fromAddress, $fromName) {
                if (!empty($fromAddress)) {
                    $mail->from($fromAddress, $fromName);
                }
                $mail->to($appointment->email, $appointment->name)->subject($subject);
            });
        } catch (\Exception $e) {
            Log::warning("Appointment status email to {$appointment->email} failed: " . $e->getMessage());
        }

        $dateStr = $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : '';
        $whatsappText = match ($newStatus) {
            'confirmed' => "Great news {$appointment->name}!\n\nYour booking (ID: {$regNumber}) is CONFIRMED for {$dateStr} at {$appointment->time_slot}.\n\nWe look forward to seeing you at {$appName}.",
            'completed' => "Hello {$appointment->name},\n\nThank you for visiting {$appName}. We hope your session went great!",
            'no_show' => "Hello {$appointment->name},\n\nYou were marked as absent for your scheduled booking ({$regNumber}). Please contact us to reschedule.",
            default => "Hello {$appointment->name},\n\nYour booking status has been updated to {$newStatus}."
        };

        $this->sendWhatsAppMessage($appointment->whatsapp ?: $appointment->phone, $whatsappText);
    }

    /**
     * Send reschedule notification email and WhatsApp
     */
    public function sendAppointmentRescheduled(Appointment $appointment): void
    {
        $this->configureDynamicSmtp();

        $appName = Setting::get('academy_name', config('app.name', 'Apex Academy & IETS Center'));
        $template = EmailTemplate::getTemplate('rescheduled');

        $renderData = [
            'student_name' => $appointment->name,
            'email' => $appointment->email,
            'phone' => $appointment->phone,
            'date' => $appointment->appointment_date ? $appointment->appointment_date->format('l, F j, Y') : '',
            'time' => $appointment->time_slot,
            'registration_number' => $appointment->registration_number ?: $appointment->booking_code,
            'test_type' => $appointment->test_type ?: ($appointment->purpose ?: 'Campus Counseling'),
            'purpose' => $appointment->purpose ?: ($appointment->test_type ?: 'IETS Test Registration'),
            'status' => ucfirst($appointment->status),
            'academy_name' => $appName,
            'admin_notes' => $appointment->admin_notes ?: '',
        ];

        $rendered = $template->render($renderData);
        $this->sendRawHtmlEmail($appointment->email, $appointment->name, $rendered['subject'], $rendered['body'], $appName);

        $dateStr = $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : '';
        $this->sendWhatsAppMessage(
            $appointment->whatsapp ?: $appointment->phone,
            "Hello {$appointment->name},\n\nYour booking ({$renderData['registration_number']}) at {$appName} has been RESCHEDULED to {$dateStr} at {$appointment->time_slot}.\n\nPlease arrive 15 minutes before your new time."
        );
    }

    /**
     * Send 24-hour reminder email
     */
    public function sendReminder(Appointment $appointment): void
    {
        $this->configureDynamicSmtp();

        $appName = Setting::get('academy_name', config('app.name', 'Apex Academy & IETS Center'));
        $template = EmailTemplate::getTemplate('reminder');

        $renderData = [
            'student_name' => $appointment->name,
            'email' => $appointment->email,
            'phone' => $appointment->phone,
            'date' => $appointment->appointment_date ? $appointment->appointment_date->format('l, F j, Y') : '',
            'time' => $appointment->time_slot,
            'registration_number' => $appointment->registration_number ?: $appointment->booking_code,
            'test_type' => $appointment->test_type ?: ($appointment->purpose ?: 'Campus Counseling'),
            'purpose' => $appointment->purpose ?: ($appointment->test_type ?: 'IETS Test Registration'),
            'status' => ucfirst($appointment->status),
            'academy_name' => $appName,
            'admin_notes' => $appointment->admin_notes ?: '',
        ];

        $rendered = $template->render($renderData);
        $this->sendRawHtmlEmail($appointment->email, $appointment->name, $rendered['subject'], $rendered['body'], $appName);

        $dateStr = $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : '';
        $this->sendWhatsAppMessage(
            $appointment->whatsapp ?: $appointment->phone,
            "Reminder for {$appointment->name}:\n\nYour session at {$appName} (ID: {$renderData['registration_number']}) is tomorrow ({$dateStr}) at {$appointment->time_slot}. Please be on time with your ID."
        );
    }

    /**
     * Helper to send HTML email with dynamically configured from address and branding
     */
    protected function sendRawHtmlEmail(string $recipientEmail, string $recipientName, string $subject, string $htmlBody, string $appName): void
    {
        $this->configureDynamicSmtp();

        try {
            $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
            $fromName = Setting::get('mail_from_name', Setting::get('academy_name', config('mail.from.name')));
            $currentYear = date('Y');

            $logoHtml = '';
            $academyLogo = Setting::get('academy_logo');
            if (!empty($academyLogo)) {
                $logoUrl = asset('storage/' . $academyLogo);
                $logoHtml = "<div style=\"margin-bottom: 12px;\"><img src=\"{$logoUrl}\" alt=\"{$appName}\" style=\"height: 52px; max-height: 52px; width: auto; background: #ffffff; padding: 4px; border-radius: 8px; display: inline-block;\"></div>";
            }

            $wrappedHtml = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>{$subject}</title></head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 28px 24px; color: #ffffff; text-align: center;">
            {$logoHtml}
            <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">{$appName}</h1>
            <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 13px;">Official Admissions &amp; IETS Testing Center</p>
        </div>
        <div style="padding: 28px 24px; color: #334155; font-size: 14px; line-height: 1.6;">
            {$htmlBody}
        </div>
        <div style="background: #f8fafc; padding: 16px; text-align: center; color: #94a3b8; font-size: 11px; border-top: 1px solid #e2e8f0;">
            &copy; {$currentYear} {$appName}. All rights reserved. &bull; This is an automated notification.
        </div>
    </div>
</body>
</html>
HTML;

            Mail::html($wrappedHtml, function ($mail) use ($recipientEmail, $recipientName, $subject, $appName, $fromAddress, $fromName) {
                if (!empty($fromAddress)) {
                    $mail->from($fromAddress, $fromName);
                }
                $mail->to($recipientEmail, $recipientName)
                     ->subject("{$subject} - {$appName}");
            });
        } catch (\Exception $e) {
            Log::warning("HTML email to {$recipientEmail} failed: " . $e->getMessage());
        }
    }

    /**
     * Notify admin of new appointment / test booking
     */
    protected function sendAdminAppointmentEmail(Appointment $appointment, string $appName): void
    {
        try {
            $adminEmail = Setting::get('admin_email') 
                ?: Setting::get('contact_email') 
                ?: config('mail.from.address', 'admin@antiacademy.edu');
            $regNumber = $appointment->registration_number ?: $appointment->booking_code;
            $typeLabel = ($appointment->type === 'iets_test') ? 'IETS Test Registration' : 'Counseling Appointment';
            $dateStr = $appointment->appointment_date ? $appointment->appointment_date->format('l, F j, Y') : '';

            $body = <<<HTML
<p>A new student booking has been submitted through the portal.</p>
<div style="background: #f8fafc; border-left: 4px solid #2563eb; padding: 14px; margin: 16px 0; border-radius: 6px;">
    <p style="margin: 3px 0;"><strong>Registration / Tracking:</strong> <span style="font-family: monospace; font-weight: bold; color: #2563eb;">{$regNumber}</span></p>
    <p style="margin: 3px 0;"><strong>Category:</strong> {$typeLabel}</p>
    <p style="margin: 3px 0;"><strong>Student Name:</strong> {$appointment->name}</p>
    <p style="margin: 3px 0;"><strong>Email:</strong> {$appointment->email}</p>
    <p style="margin: 3px 0;"><strong>Phone:</strong> {$appointment->phone}</p>
    <p style="margin: 3px 0;"><strong>WhatsApp:</strong> {$appointment->whatsapp}</p>
    <p style="margin: 3px 0;"><strong>Date:</strong> {$dateStr}</p>
    <p style="margin: 3px 0;"><strong>Time Slot:</strong> {$appointment->time_slot}</p>
    <p style="margin: 3px 0;"><strong>Test / Purpose:</strong> {$appointment->purpose} {$appointment->test_type}</p>
    <p style="margin: 3px 0;"><strong>Status:</strong> {$appointment->status}</p>
</div>
HTML;

            $this->sendRawHtmlEmail($adminEmail, 'Admin Office', "New {$typeLabel}: {$regNumber}", $body, $appName);
        } catch (\Exception $e) {
            Log::warning('Admin appointment alert failed: ' . $e->getMessage());
        }
    }

    /**
     * Notify both admin and user of a new contact form submission
     */
    public function sendContactMessageNotification(ContactMessage $msg): void
    {
        $this->configureDynamicSmtp();

        $adminEmail = Setting::get('admin_email') 
            ?: Setting::get('contact_email') 
            ?: config('mail.from.address', 'admin@antiacademy.edu');
        $appName = Setting::get('academy_name', config('app.name', 'Apex Academy & IETS Center'));
        $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
        $fromName = Setting::get('mail_from_name', $appName);

        // 1. Alert sent to Admin Office
        try {
            Mail::send('emails.contact_received', ['msg' => $msg, 'appName' => $appName], function ($mail) use ($adminEmail, $msg, $appName, $fromAddress, $fromName) {
                if (!empty($fromAddress)) {
                    $mail->from($fromAddress, $fromName);
                }
                $mail->to($adminEmail)
                    ->subject("New Contact Message from {$msg->name} - {$appName}");
            });
        } catch (\Exception $e) {
            Log::warning('Contact message admin email failed: ' . $e->getMessage());
        }

        // 2. Acknowledgment sent to the Student / Sender
        if (!empty($msg->email)) {
            try {
                Mail::send('emails.contact_acknowledgement', ['msg' => $msg, 'appName' => $appName], function ($mail) use ($msg, $appName, $fromAddress, $fromName) {
                    if (!empty($fromAddress)) {
                        $mail->from($fromAddress, $fromName);
                    }
                    $mail->to($msg->email, $msg->name)
                        ->subject("We received your message - {$appName}");
                });
            } catch (\Exception $e) {
                Log::warning("Contact acknowledgement email to {$msg->email} failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Send email to student when admin replies to a contact message
     */
    public function sendContactReplyNotification(ContactMessage $msg): void
    {
        if (empty($msg->email) || empty($msg->admin_reply)) {
            return;
        }

        $this->configureDynamicSmtp();

        $appName = Setting::get('academy_name', config('app.name', 'Apex Academy & IETS Center'));
        $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
        $fromName = Setting::get('mail_from_name', $appName);

        try {
            Mail::send('emails.contact_reply', ['msg' => $msg, 'appName' => $appName], function ($mail) use ($msg, $appName, $fromAddress, $fromName) {
                if (!empty($fromAddress)) {
                    $mail->from($fromAddress, $fromName);
                }
                $mail->to($msg->email, $msg->name)
                    ->subject("Response to your inquiry: " . ($msg->subject ?: 'General Inquiry') . " - {$appName}");
            });
        } catch (\Exception $e) {
            Log::warning("Contact reply email to {$msg->email} failed: " . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp Message using configured provider credentials
     */
    public function sendWhatsAppMessage(?string $phoneNumber, string $message): bool
    {
        if (empty($phoneNumber)) {
            return false;
        }

        $provider = Setting::get('whatsapp_provider', 'meta_cloud');
        $accessToken = Setting::get('whatsapp_access_token');
        $phoneNumberId = Setting::get('whatsapp_phone_number_id');

        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (empty($accessToken) || empty($phoneNumberId)) {
            Log::info("[WhatsApp Simulated Notification] To: {$cleanPhone} | Message: {$message}");
            return true;
        }

        try {
            if ($provider === 'meta_cloud') {
                $url = "https://graph.facebook.com/v18.0/{$phoneNumberId}/messages";
                $response = Http::withToken($accessToken)->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $cleanPhone,
                    'type' => 'text',
                    'text' => ['body' => $message],
                ]);
                return $response->successful();
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Dispatch Exception: ' . $e->getMessage());
        }

        return false;
    }
}
