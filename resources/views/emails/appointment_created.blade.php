<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointment Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); padding: 30px; color: #ffffff; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">{{ $appName }}</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Appointment Request Received</p>
        </div>
        <div style="padding: 30px; color: #374151; line-height: 1.6;">
            <p>Dear <strong>{{ $appointment->name }}</strong>,</p>
            <p>Thank you for booking an appointment with {{ $appName }}. Your appointment request has been received and is currently under review by our admissions counseling team.</p>

            <div style="background: #f8fafc; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 4px 0;"><strong>Tracking Code:</strong> <span style="color: #2563eb; font-weight: bold;">{{ $appointment->booking_code }}</span></p>
                <p style="margin: 4px 0;"><strong>Date:</strong> {{ $appointment->appointment_date->format('l, F j, Y') }}</p>
                <p style="margin: 4px 0;"><strong>Time Slot:</strong> {{ $appointment->time_slot }}</p>
                <p style="margin: 4px 0;"><strong>Purpose:</strong> {{ $appointment->purpose }}</p>
                <p style="margin: 4px 0;"><strong>Status:</strong> <span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 9999px; font-size: 12px; font-weight: bold; text-transform: uppercase;">{{ $appointment->status }}</span></p>
            </div>

            <p>You will receive a confirmation email and WhatsApp message once your appointment slot is approved.</p>
            <p>If you have any questions or wish to reschedule, please feel free to reply to this email.</p>
            <br>
            <p style="margin-bottom: 0;">Warm regards,<br><strong>Admissions Team</strong><br>{{ $appName }}</p>
        </div>
        <div style="background: #f9fafb; padding: 15px; text-align: center; color: #9ca3af; font-size: 12px; border-top: 1px solid #e5e7eb;">
            &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
        </div>
    </div>
</body>
</html>
