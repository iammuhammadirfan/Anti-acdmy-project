<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Inquiry Received</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 28px 24px; color: #ffffff; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Prime Iets College</h1>
            <p style="margin: 6px 0 0 0; opacity: 0.95; font-size: 13px; font-weight: 700; letter-spacing: 0.5px;">Official Admissions &amp; IETS Training Center</p>
        </div>
        <div style="padding: 28px 24px; color: #334155; font-size: 14px; line-height: 1.6;">
            <div style="display: inline-block; background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 3px 10px; border-radius: 9999px; margin-bottom: 12px;">
                New Student Inquiry
            </div>
            <h3 style="margin: 0 0 16px 0; color: #0f172a; font-size: 18px; font-weight: 700;">
                A new inquiry was submitted via the website contact form
            </h3>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin-bottom: 20px;">
                <p style="margin: 4px 0;"><strong>Student Name:</strong> {{ $msg->name }}</p>
                <p style="margin: 4px 0;"><strong>Email Address:</strong> <a href="mailto:{{ $msg->email }}" style="color: #2563eb;">{{ $msg->email }}</a></p>
                @if($msg->phone)
                    <p style="margin: 4px 0;"><strong>Phone / WhatsApp:</strong> <a href="tel:{{ $msg->phone }}" style="color: #2563eb;">{{ $msg->phone }}</a></p>
                @endif
                <p style="margin: 4px 0;"><strong>Subject:</strong> {{ $msg->subject ?: 'General Inquiry' }}</p>
                <p style="margin: 4px 0;"><strong>Timestamp:</strong> {{ now()->format('M d, Y - g:i A') }}</p>
            </div>

            <div style="background: #ffffff; border-left: 4px solid #3b82f6; padding: 14px 16px; margin: 16px 0; border: 1px solid #e2e8f0; border-left-width: 4px; border-radius: 6px;">
                <p style="margin: 0 0 6px 0; font-weight: 700; color: #1e3a8a; font-size: 12px; text-transform: uppercase;">Message Content:</p>
                <p style="margin: 0; white-space: pre-line; color: #1e293b; font-size: 13px;">{{ $msg->message }}</p>
            </div>
        </div>
        <div style="background: #f8fafc; padding: 16px; text-align: center; color: #94a3b8; font-size: 11px; border-top: 1px solid #e2e8f0;">
            &copy; {{ date('Y') }} Prime Iets College &bull; Official Admissions &amp; IETS Training Center. Internal Admin Notification.
        </div>
    </div>
</body>
</html>
