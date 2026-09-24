<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Response to your Inquiry</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 28px 24px; color: #ffffff; text-align: center;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">{{ $appName }}</h1>
            <p style="margin: 6px 0 0 0; opacity: 0.9; font-size: 13px;">Official Response to Your Inquiry</p>
        </div>
        <div style="padding: 28px 24px; color: #334155; font-size: 14px; line-height: 1.6;">
            <p style="margin-top: 0;">Dear <strong>{{ $msg->name }}</strong>,</p>
            <p>Thank you for waiting. Our admissions team has reviewed your inquiry regarding <strong>{{ $msg->subject ?: 'your message' }}</strong>.</p>

            <div style="background: #f0fdf4; border-left: 4px solid #16a34a; padding: 18px; margin: 20px 0; border-radius: 6px;">
                <h4 style="margin: 0 0 8px 0; color: #166534; font-size: 14px; font-weight: 700;">Response from Administration:</h4>
                <div style="color: #14532d; font-size: 14px; line-height: 1.6; white-space: pre-line;">{{ $msg->admin_reply }}</div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; margin: 20px 0; border-radius: 6px; font-size: 12px; color: #64748b;">
                <p style="margin: 0 0 4px 0; font-weight: 600;">Your Original Inquiry:</p>
                <p style="margin: 0; white-space: pre-line; color: #475569;">{{ $msg->message }}</p>
            </div>

            <p>If you have any further questions, simply reply to this email or visit our campus.</p>

            <p style="margin-bottom: 0;">Warm regards,<br>
            <strong>Admissions &amp; Student Affairs Desk</strong><br>
            {{ $appName }}</p>
        </div>
        <div style="background: #f8fafc; padding: 16px; text-align: center; color: #94a3b8; font-size: 11px; border-top: 1px solid #e2e8f0;">
            &copy; {{ date('Y') }} {{ $appName }}. All rights reserved. &bull; Official communication.
        </div>
    </div>
</body>
</html>
