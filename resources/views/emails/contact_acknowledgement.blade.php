<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inquiry Confirmation</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 28px 24px; color: #ffffff; text-align: center;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">{{ $appName }}</h1>
            <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 13px;">Official Admissions &amp; Inquiries Desk</p>
        </div>
        <div style="padding: 28px 24px; color: #334155; font-size: 14px; line-height: 1.6;">
            <p style="margin-top: 0;">Dear <strong>{{ $msg->name }}</strong>,</p>
            <p>Thank you for reaching out to <strong>{{ $appName }}</strong>. We have received your inquiry and our counseling and support team is reviewing your message.</p>

            <div style="background: #f8fafc; border-left: 4px solid #2563eb; padding: 16px; margin: 20px 0; border-radius: 6px;">
                <h4 style="margin: 0 0 10px 0; color: #1e3a8a; font-size: 14px; font-weight: 700;">Summary of Your Inquiry</h4>
                <p style="margin: 3px 0;"><strong>Subject:</strong> {{ $msg->subject ?: 'General Inquiry' }}</p>
                @if($msg->phone)
                    <p style="margin: 3px 0;"><strong>Phone / Contact:</strong> {{ $msg->phone }}</p>
                @endif
                <p style="margin: 3px 0;"><strong>Date Received:</strong> {{ now()->format('l, F j, Y - g:i A') }}</p>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                    <p style="margin: 0 0 4px 0; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase;">Your Message:</p>
                    <p style="margin: 0; white-space: pre-line; color: #1e293b; font-size: 13px;">{{ $msg->message }}</p>
                </div>
            </div>

            <p>A member of our team will contact you shortly via email or phone. If your matter is urgent, you can also reach us via WhatsApp or phone during campus office hours.</p>

            <p style="margin-bottom: 0;">Warm regards,<br>
            <strong>Admissions &amp; Information Desk</strong><br>
            {{ $appName }}</p>
        </div>
        <div style="background: #f8fafc; padding: 16px; text-align: center; color: #94a3b8; font-size: 11px; border-top: 1px solid #e2e8f0;">
            &copy; {{ date('Y') }} {{ $appName }}. All rights reserved. &bull; Automated notification.
        </div>
    </div>
</body>
</html>
