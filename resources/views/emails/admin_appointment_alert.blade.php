<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Appointment Alert</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden;">
        <div style="background: #1e293b; padding: 20px; color: #ffffff; text-align: center;">
            <h2 style="margin: 0;">New Appointment Booking Alert</h2>
        </div>
        <div style="padding: 20px; color: #374151;">
            <p>A new appointment has been scheduled through the website:</p>
            <ul>
                <li><strong>Tracking Code:</strong> {{ $appointment->booking_code }}</li>
                <li><strong>Student Name:</strong> {{ $appointment->name }}</li>
                <li><strong>Email:</strong> {{ $appointment->email }}</li>
                <li><strong>Phone:</strong> {{ $appointment->phone }}</li>
                <li><strong>WhatsApp:</strong> {{ $appointment->whatsapp ?: 'N/A' }}</li>
                <li><strong>Date:</strong> {{ $appointment->appointment_date->format('Y-m-d') }}</li>
                <li><strong>Time Slot:</strong> {{ $appointment->time_slot }}</li>
                <li><strong>Purpose:</strong> {{ $appointment->purpose }}</li>
                <li><strong>Message:</strong> {{ $appointment->message ?: 'None' }}</li>
            </ul>
            <p>Please log in to the Admin Dashboard to confirm or manage this appointment.</p>
        </div>
    </div>
</body>
</html>
