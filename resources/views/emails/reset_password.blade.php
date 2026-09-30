<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 30px 15px;">
    <div style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
        <!-- Header -->
        <div style="background-color: #0f172a; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.5px;">
                {{ \App\Models\Setting::get('academy_name', config('app.name', 'Apex Academy')) }}
            </h1>
            <p style="color: #94a3b8; margin: 4px 0 0; font-size: 12px;">Staff &amp; Management Security Portal</p>
        </div>

        <!-- Body -->
        <div style="padding: 32px 28px; color: #334155; line-height: 1.6;">
            <h2 style="color: #0f172a; font-size: 18px; margin-top: 0; margin-bottom: 16px;">
                Hello, {{ $user->name }}
            </h2>
            <p style="font-size: 14px; margin-bottom: 20px;">
                You are receiving this email because we received a password reset request for your account. Click the button below to choose a new password:
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" 
                   style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 28px; font-size: 14px; font-weight: bold; border-radius: 8px; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.25);">
                    Reset Password
                </a>
            </div>

            <p style="font-size: 12px; color: #64748b; margin-bottom: 8px;">
                This password reset link will expire in <strong>60 minutes</strong>.
            </p>
            <p style="font-size: 12px; color: #64748b; margin-bottom: 20px;">
                If you did not request a password reset, no further action is required. Your account remains secure.
            </p>

            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">

            <p style="font-size: 11px; color: #94a3b8; word-break: break-all;">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
                <a href="{{ $resetUrl }}" style="color: #2563eb;">{{ $resetUrl }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 16px 24px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8;">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('academy_name', 'Apex Academy') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
