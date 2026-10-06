<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Booking Alert</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
        <div style="background: #0f172a; padding: 20px; color: #ffffff; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800;">Prime Iets College</h2>
            <p style="margin: 4px 0 0 0; font-size: 12px; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px;">Official Admissions &amp; IETS Training Center</p>
        </div>
        <div style="padding: 25px; color: #374151; line-height: 1.6;">
            <h3 style="color: #1e3a8a; margin-top: 0;">New Appointment / Test Booking Received</h3>
            <p>A new student appointment has been requested through the public booking portal.</p>
            
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 6px; margin: 15px 0;">
                <p style="margin: 4px 0;"><strong>Tracking Code:</strong> <span style="color: #2563eb; font-weight: bold;">{{ $appointment->booking_code }}</span></p>
                <p style="margin: 4px 0;"><strong>Student Name:</strong> {{ $appointment->name }}</p>
                <p style="margin: 4px 0;"><strong>Email:</strong> {{ $appointment->email }}</p>
                <p style="margin: 4px 0;"><strong>Phone:</strong> {{ $appointment->phone }}</p>
                <p style="margin: 4px 0;"><strong>Date:</strong> {{ $appointment->appointment_date ? $appointment->appointment_date->format('l, F j, Y') : 'N/A' }}</p>
                <p style="margin: 4px 0;"><strong>Slot:</strong> {{ $appointment->time_slot }}</p>
                <p style="margin: 4px 0;"><strong>Type:</strong> {{ strtoupper($appointment->type) }}</p>
                <p style="margin: 4px 0;"><strong>Purpose:</strong> {{ $appointment->purpose }}</p>
            </div>
            
            <p>Please log in to the admin dashboard to review, approve or manage this appointment slot.</p>
        </div>
        <div style="background: #f9fafb; padding: 12px; text-align: center; color: #9ca3af; font-size: 11px; border-top: 1px solid #e5e7eb;">
            &copy; {{ date('Y') }} Prime Iets College &bull; Official Admissions &amp; IETS Training Center. Internal Alert.
        </div>
    </div>
</body>
</html>
