<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message Alert</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 24px; color: #ffffff; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800;">{{ $appName }} &mdash; Admin Alert</h2>
            <p style="margin: 4px 0 0 0; opacity: 0.85; font-size: 13px;">New Website Contact Inquiry Received</p>
        </div>
        <div style="padding: 24px; color: #334155; font-size: 14px; line-height: 1.6;">
            <p style="margin-top: 0;">A visitor has submitted a new inquiry via the website contact form:</p>

            <div style="background: #f8fafc; border-left: 4px solid #3b82f6; padding: 16px; margin: 16px 0; border-radius: 6px;">
                <p style="margin: 4px 0;"><strong>Sender Name:</strong> {{ $msg->name }}</p>
                <p style="margin: 4px 0;"><strong>Email Address:</strong> <a href="mailto:{{ $msg->email }}" style="color: #2563eb; text-decoration: none;">{{ $msg->email }}</a></p>
                <p style="margin: 4px 0;"><strong>Phone Number:</strong> {{ $msg->phone ?: 'Not provided' }}</p>
                <p style="margin: 4px 0;"><strong>Subject:</strong> {{ $msg->subject ?: 'General Inquiry' }}</p>
                <p style="margin: 4px 0;"><strong>Received At:</strong> {{ now()->format('M d, Y h:i A') }}</p>
            </div>

            <div style="background: #f1f5f9; padding: 16px; border-radius: 6px; margin-top: 14px;">
                <p style="margin: 0 0 6px 0; font-weight: 700; color: #475569; font-size: 12px; text-transform: uppercase;">Message Content:</p>
                <p style="margin: 0; white-space: pre-line; color: #1e293b; font-size: 13px;">{{ $msg->message }}</p>
            </div>

            <p style="margin-top: 20px;">Please log in to the Admin Dashboard to reply directly or contact the inquirer.</p>
        </div>
        <div style="background: #f8fafc; padding: 14px; text-align: center; color: #94a3b8; font-size: 11px; border-top: 1px solid #e2e8f0;">
            &copy; {{ date('Y') }} {{ $appName }} Administration Portal.
        </div>
    </div>
</body>
</html>
