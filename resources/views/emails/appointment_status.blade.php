<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointment Update</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 30px; color: #ffffff; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">{{ $appName }}</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Appointment Status Update</p>
        </div>
        <div style="padding: 30px; color: #374151; line-height: 1.6;">
            <p>Dear <strong>{{ $appointment->name }}</strong>,</p>
            <p>This is an automated update regarding your appointment (Tracking Code: <strong>{{ $appointment->booking_code }}</strong>).</p>

            <div style="background: #f8fafc; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 4px 0;"><strong>Date:</strong> {{ $appointment->appointment_date->format('l, F j, Y') }}</p>
                <p style="margin: 4px 0;"><strong>Time Slot:</strong> {{ $appointment->time_slot }}</p>
                <p style="margin: 4px 0;"><strong>Updated Status:</strong> 
                    <span style="font-weight: bold; text-transform: uppercase; color: {{ $appointment->status === 'confirmed' ? '#059669' : ($appointment->status === 'cancelled' ? '#dc2626' : '#2563eb') }};">
                        {{ $appointment->status }}
                    </span>
                </p>
                @if($appointment->admin_notes)
                    <p style="margin: 8px 0 4px 0;"><strong>Notes from Counselor:</strong> {{ $appointment->admin_notes }}</p>
                @endif
            </div>

            @if($appointment->status === 'confirmed')
                <p>We look forward to meeting with you! Please arrive 10 minutes prior to your scheduled time at our campus reception.</p>
            @elseif($appointment->status === 'cancelled')
                <p>If you wish to reschedule or if this was made in error, please visit our website to book another convenient time.</p>
            @endif

            <p style="margin-bottom: 0;">Warm regards,<br><strong>Admissions & Counseling Team</strong><br>{{ $appName }}</p>
        </div>
        <div style="background: #f9fafb; padding: 15px; text-align: center; color: #9ca3af; font-size: 12px; border-top: 1px solid #e5e7eb;">
            &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
        </div>
    </div>
</body>
</html>
