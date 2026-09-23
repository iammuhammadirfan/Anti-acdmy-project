<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send notifications when an appointment is booked
     */
    public function sendAppointmentCreated(Appointment $appointment): void
    {
        $this->sendAppointmentEmail($appointment, 'Appointment Request Received', 'emails.appointment_created');
        $this->sendAdminAppointmentEmail($appointment);
        $this->sendWhatsAppMessage(
            $appointment->whatsapp ?: $appointment->phone,
            "Hello {$appointment->name},\n\nYour appointment request (ID: {$appointment->booking_code}) has been received for {$appointment->appointment_date->format('M d, Y')} at {$appointment->time_slot}.\n\nPurpose: {$appointment->purpose}\nStatus: Pending confirmation.\n\nThank you for choosing Apex Academy & IETS."
        );
    }

    /**
     * Send notifications when appointment status is updated (confirmed/cancelled/completed)
     */
    public function sendAppointmentStatusUpdated(Appointment $appointment, string $newStatus): void
    {
        $subject = match ($newStatus) {
            'confirmed' => 'Your Appointment has been Confirmed',
            'cancelled' => 'Your Appointment has been Cancelled',
            'completed' => 'Thank you for visiting Apex Academy',
            default => 'Appointment Status Update'
        };

        $this->sendAppointmentEmail($appointment, $subject, 'emails.appointment_status');

        $whatsappText = match ($newStatus) {
            'confirmed' => "Great news {$appointment->name}!\n\nYour appointment (ID: {$appointment->booking_code}) is CONFIRMED for {$appointment->appointment_date->format('M d, Y')} at {$appointment->time_slot}.\n\nWe look forward to seeing you at Apex Academy & IETS campus.",
            'cancelled' => "Hello {$appointment->name},\n\nYour appointment (ID: {$appointment->booking_code}) scheduled for {$appointment->appointment_date->format('M d, Y')} has been CANCELLED.\n\nIf you have any questions, please contact our support.",
            'completed' => "Hello {$appointment->name},\n\nThank you for visiting Apex Academy & IETS. We hope your session was helpful. Let us know if you need any further guidance.",
            default => "Hello {$appointment->name},\n\nYour appointment status has been updated to {$newStatus}."
        };

        $this->sendWhatsAppMessage($appointment->whatsapp ?: $appointment->phone, $whatsappText);
    }

    /**
     * Notify admin of a new contact submission
     */
    public function sendContactMessageNotification(ContactMessage $msg): void
    {
        try {
            $adminEmail = Setting::get('admin_email', config('mail.from.address', 'admin@antiacademy.edu'));
            $appName = Setting::get('academy_name', config('app.name', 'Apex Academy'));

            Mail::send('emails.contact_received', ['msg' => $msg, 'appName' => $appName], function ($mail) use ($adminEmail, $msg, $appName) {
                $mail->to($adminEmail)
                    ->subject("New Contact Message from {$msg->name} - {$appName}");
            });
        } catch (\Exception $e) {
            Log::warning('Contact message email failed: ' . $e->getMessage());
        }
    }

    /**
     * Internal email sender for student
     */
    protected function sendAppointmentEmail(Appointment $appointment, string $subject, string $view): void
    {
        try {
            $appName = Setting::get('academy_name', config('app.name', 'Apex Academy'));
            Mail::send($view, ['appointment' => $appointment, 'appName' => $appName], function ($mail) use ($appointment, $subject, $appName) {
                $mail->to($appointment->email, $appointment->name)
                    ->subject("{$subject} - {$appName}");
            });
        } catch (\Exception $e) {
            Log::warning("Appointment email to {$appointment->email} failed: " . $e->getMessage());
        }
    }

    /**
     * Notify admin of new appointment
     */
    protected function sendAdminAppointmentEmail(Appointment $appointment): void
    {
        try {
            $adminEmail = Setting::get('admin_email', config('mail.from.address', 'admin@antiacademy.edu'));
            $appName = Setting::get('academy_name', config('app.name', 'Apex Academy'));

            Mail::send('emails.admin_appointment_alert', ['appointment' => $appointment, 'appName' => $appName], function ($mail) use ($adminEmail, $appointment, $appName) {
                $mail->to($adminEmail)
                    ->subject("New Appointment Booking: {$appointment->booking_code} - {$appName}");
            });
        } catch (\Exception $e) {
            Log::warning('Admin appointment alert failed: ' . $e->getMessage());
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

        $provider = Setting::get('whatsapp_provider', 'meta_cloud'); // meta_cloud, twilio, webhook
        $accessToken = Setting::get('whatsapp_access_token');
        $phoneNumberId = Setting::get('whatsapp_phone_number_id');

        // Clean phone number: remove spaces, dashes, plus
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If credentials are not configured yet, log safely and return true
        if (empty($accessToken) || empty($phoneNumberId)) {
            Log::info("[WhatsApp Simulated Notification] To: {$cleanPhone} | Provider: {$provider} | Message: {$message}");
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

                if ($response->successful()) {
                    Log::info("WhatsApp message sent successfully to {$cleanPhone}");
                    return true;
                } else {
                    Log::warning("WhatsApp API Error: " . $response->body());
                    return false;
                }
            } elseif ($provider === 'twilio') {
                $accountSid = Setting::get('twilio_sid');
                $authToken = Setting::get('twilio_token');
                $fromNumber = Setting::get('twilio_from_whatsapp');

                if ($accountSid && $authToken && $fromNumber) {
                    $url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";
                    $response = Http::withBasicAuth($accountSid, $authToken)->asForm()->post($url, [
                        'From' => 'whatsapp:' . $fromNumber,
                        'To' => 'whatsapp:+' . $cleanPhone,
                        'Body' => $message,
                    ]);
                    return $response->successful();
                }
            } elseif ($provider === 'webhook') {
                $webhookUrl = Setting::get('whatsapp_webhook_url');
                if ($webhookUrl) {
                    $response = Http::post($webhookUrl, [
                        'phone' => $cleanPhone,
                        'message' => $message,
                        'timestamp' => now()->toISOString(),
                    ]);
                    return $response->successful();
                }
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Dispatch Exception: ' . $e->getMessage());
        }

        return false;
    }
}
