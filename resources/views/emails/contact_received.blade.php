<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden;">
        <div style="background: #1e3a8a; padding: 20px; color: #ffffff; text-align: center;">
            <h2 style="margin: 0;">New Contact Form Message</h2>
        </div>
        <div style="padding: 20px; color: #374151;">
            <p><strong>From:</strong> {{ $msg->name }} ({{ $msg->email }})</p>
            <p><strong>Phone:</strong> {{ $msg->phone ?: 'Not provided' }}</p>
            <p><strong>Subject:</strong> {{ $msg->subject ?: 'General Inquiry' }}</p>
            <div style="background: #f1f5f9; padding: 15px; border-radius: 4px; margin-top: 10px;">
                <p style="margin: 0; white-space: pre-line;">{{ $msg->message }}</p>
            </div>
        </div>
    </div>
</body>
</html>
