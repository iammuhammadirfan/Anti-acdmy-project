<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function showForgotPasswordForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $throttleKey = 'forgot-pw|' . Str::lower($request->input('email')) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Too many password reset attempts. Please wait {$seconds} seconds.")->withInput();
        }
        RateLimiter::hit($throttleKey, 300);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Friendly message even if email not found
            return back()->with('error', 'No user account found with this email address. Please verify your email.');
        }

        if (!$user->is_active) {
            return back()->with('error', 'This account has been deactivated. Please contact the Super Administrator.');
        }

        // Generate cryptographically secure token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        $resetUrl = route('admin.password.reset', ['token' => $token, 'email' => $user->email]);

        $mailSent = false;
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));
            $mailSent = true;
        } catch (\Throwable $e) {
            Log::warning('Password reset email could not be sent: ' . $e->getMessage());
            $mailSent = false;
        }

        ActivityLog::log('password_reset_request', 'auth', "Password reset requested for {$user->email}");

        if ($mailSent) {
            return back()->with('status', 'A password reset link has been sent to your email address.');
        }

        // If local development or SMTP credentials are not yet configured
        return back()
            ->with('status', 'A secure password reset link has been generated.')
            ->with('reset_url', $resetUrl);
    }

    public function showResetForm(Request $request, $token)
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        $email = $request->query('email');

        $record = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->when($email, fn($q) => $q->where('email', $email))
            ->first();

        if (!$record) {
            return redirect()->route('admin.password.request')
                ->with('error', 'This password reset link is invalid or has already been used.');
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $record->email)->delete();
            return redirect()->route('admin.password.request')
                ->with('error', 'This password reset link has expired. Please request a new one.');
        }

        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $record->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->with('error', 'This password reset link is invalid or has already been used.')->withInput();
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('admin.password.request')
                ->with('error', 'This reset link has expired. Please request a new one.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Unable to find an account with this email address.')->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        ActivityLog::log('password_reset_success', 'auth', "Password successfully reset for {$user->name} ({$user->email})");

        return redirect()->route('admin.login')
            ->with('success', 'Your password has been successfully reset! You can now log in with your new password.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Too many login attempts. Please try again in {$seconds} seconds.")->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                RateLimiter::hit($throttleKey);
                return back()->with('error', 'Your account has been deactivated. Please contact Super Admin.');
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            ActivityLog::log('login', 'auth', "User {$user->name} logged in successfully.");

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey);

        return back()->with('error', 'The provided credentials do not match our records.')->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::log('logout', 'auth', "User " . Auth::user()->name . " logged out.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        ActivityLog::log('update', 'users', "User updated profile: {$user->name}");

        return back()->with('success', 'Profile updated successfully.');
    }
}
