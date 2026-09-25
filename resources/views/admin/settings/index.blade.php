@extends('layouts.admin')

@section('title', 'System Settings')
@section('page_title', 'Academy Settings & API Integrations')

@section('content')
<div class="max-w-4xl space-y-8" x-data="{ tab: 'general' }">
    <!-- Tab Navigation -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3 text-xs font-bold uppercase tracking-wider overflow-x-auto">
        <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl transition shadow-sm">General Info</button>
        <button @click="tab = 'social'" :class="tab === 'social' ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl transition shadow-sm">Social Channels</button>
        <button @click="tab = 'email'" :class="tab === 'email' ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl transition shadow-sm">SMTP Email Server</button>
        <button @click="tab = 'whatsapp'" :class="tab === 'whatsapp' ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl transition shadow-sm">WhatsApp Business API</button>
    </div>

    <!-- General Settings Form -->
    <div x-show="tab === 'general'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-slate-900 text-base">Academy Identity &amp; Contact</h3>
            <p class="text-xs text-slate-500">Configure global contact details, logo, and address displayed on the public website.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="group" value="general">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Academy Name</label>
                    <input type="text" name="academy_name" value="{{ $settings['academy_name'] }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Public Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Telephone</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">WhatsApp Contact Number</label>
                    <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] }}" placeholder="+15552345678"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Physical Campus Address</label>
                <input type="text" name="contact_address" value="{{ $settings['contact_address'] }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Academy Logo</label>
                @if($settings['academy_logo'])
                    <div class="mb-3 w-32 h-16 rounded-xl border border-slate-200 p-2 flex items-center justify-center bg-slate-50">
                        <img src="{{ asset('storage/' . $settings['academy_logo']) }}" class="max-h-full">
                    </div>
                @endif
                <input type="file" name="logo_file" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">Save General Settings</button>
            </div>
        </form>
    </div>

    <!-- Social Settings Form -->
    <div x-show="tab === 'social'" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-slate-900 text-base">Social Media Profiles</h3>
            <p class="text-xs text-slate-500">Manage links to the academy's official social media accounts.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="group" value="social">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Facebook URL</label>
                <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] }}" placeholder="https://facebook.com/..."
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Instagram URL</label>
                <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] }}" placeholder="https://instagram.com/..."
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">YouTube Channel URL</label>
                <input type="url" name="social_youtube" value="{{ $settings['social_youtube'] }}" placeholder="https://youtube.com/@..."
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">LinkedIn Page URL</label>
                <input type="url" name="social_linkedin" value="{{ $settings['social_linkedin'] }}" placeholder="https://linkedin.com/company/..."
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">TikTok URL</label>
                <input type="url" name="social_tiktok" value="{{ $settings['social_tiktok'] }}" placeholder="https://tiktok.com/@..."
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">Save Social Links</button>
            </div>
        </form>
    </div>

    <!-- SMTP Email Settings Form -->
    <div x-show="tab === 'email'" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6"
         x-data="{
             testing: false,
             testEmail: '{{ $settings['admin_email'] ?: $settings['contact_email'] }}',
             testResult: null,
             testSuccess: false,
             sendTestEmail() {
                 if (!this.testEmail) {
                     alert('Please enter a valid recipient email address.');
                     return;
                 }
                 this.testing = true;
                 this.testResult = null;
                 fetch('{{ route('admin.settings.test_smtp') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json'
                     },
                     body: JSON.stringify({ test_email: this.testEmail })
                 })
                 .then(res => res.json().then(data => ({ status: res.status, body: data })))
                 .then(res => {
                     this.testing = false;
                     this.testSuccess = res.status === 200 && res.body.success;
                     this.testResult = res.body.message || (this.testSuccess ? 'Test email dispatched successfully!' : 'Failed to send test email.');
                 })
                 .catch(err => {
                     this.testing = false;
                     this.testSuccess = false;
                     this.testResult = 'Network or request error: ' + err.message;
                 });
             }
         }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base">SMTP Mail Server Settings</h3>
                <p class="text-xs text-slate-500">Configure outbound email credentials for automated appointment confirmations and contact inquiries.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Active Auto Dispatch
            </span>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="group" value="email">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SMTP Host</label>
                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] }}" placeholder="smtp.gmail.com or live.smtp.mailtrap.io" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">e.g. <code>smtp.gmail.com</code> (Gmail) or your cPanel mail server</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SMTP Port</label>
                    <input type="number" name="smtp_port" value="{{ $settings['smtp_port'] }}" placeholder="587 or 465" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Recommended: <code>587</code> for TLS or <code>465</code> for SSL</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SMTP Username / Email</label>
                    <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] }}" placeholder="your-email@gmail.com" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SMTP Password / App Password</label>
                    <input type="password" name="smtp_password" placeholder="{{ !empty($settings['smtp_password']) ? '•••••••• (leave blank to keep current)' : 'Enter password or 16-char App Password' }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">For Gmail, use Google 2FA 16-character "App Password"</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Encryption Protocol</label>
                    <select name="smtp_encryption" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="tls" {{ $settings['smtp_encryption'] === 'tls' ? 'selected' : '' }}>TLS (Recommended for Port 587)</option>
                        <option value="ssl" {{ $settings['smtp_encryption'] === 'ssl' ? 'selected' : '' }}>SSL (For Port 465)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Sender Name (From Name)</label>
                    <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] }}" placeholder="Apex Academy & IETS Center" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">From Email Address</label>
                    <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] }}" placeholder="no-reply@yourdomain.com" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Must match your SMTP account or authenticated domain</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Admin Notification Inbox (Alerts Email)</label>
                    <input type="email" name="admin_email" value="{{ $settings['admin_email'] }}" placeholder="admin@yourdomain.com" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">All new appointment bookings and contact inquiries will arrive here</p>
                </div>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">Save SMTP Configuration</button>
            </div>
        </form>

        <!-- Test SMTP Connection Card -->
        <div class="mt-8 pt-6 border-t border-slate-200/80 bg-slate-50/70 p-5 rounded-xl border">
            <h4 class="font-bold text-slate-900 text-sm mb-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Instant SMTP Connection Test
            </h4>
            <p class="text-xs text-slate-500 mb-4">Send an instant test email to verify that your credentials, port, and authentication are functioning without errors.</p>

            <div class="flex flex-col sm:flex-row gap-3 items-center">
                <input type="email" x-model="testEmail" placeholder="Enter recipient email (e.g. yourname@gmail.com)"
                       class="w-full sm:flex-1 px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                <button type="button" @click="sendTestEmail()" :disabled="testing"
                        class="w-full sm:w-auto px-5 py-2.5 bg-slate-900 hover:bg-slate-800 disabled:opacity-50 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                    <template x-if="testing">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </template>
                    <span x-text="testing ? 'Connecting & Sending...' : 'Send Test Email'"></span>
                </button>
            </div>

            <!-- Test Feedback Display -->
            <div x-show="testResult" x-cloak class="mt-4 p-3.5 rounded-xl text-xs font-medium"
                 :class="testSuccess ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'">
                <div class="flex items-start gap-2">
                    <span x-text="testSuccess ? '✔' : '✖'" class="font-bold text-sm"></span>
                    <span x-text="testResult" class="leading-relaxed"></span>
                </div>
            </div>
        </div>

        <!-- Quick SMTP Provider Guide Box -->
        <div class="p-4 bg-brand-50/50 border border-brand-100 rounded-xl text-xs text-slate-600 space-y-2">
            <h5 class="font-bold text-brand-900 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Quick Reference for Popular SMTP Providers:
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-1">
                <div class="bg-white p-3 rounded-lg border border-slate-200/60 shadow-xs">
                    <p class="font-bold text-slate-800">Gmail SMTP</p>
                    <p class="text-[11px] text-slate-500 mt-1">Host: <code>smtp.gmail.com</code></p>
                    <p class="text-[11px] text-slate-500">Port: <code>587</code> (TLS)</p>
                    <p class="text-[11px] text-slate-500">Pass: Google 16-char App Password</p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-slate-200/60 shadow-xs">
                    <p class="font-bold text-slate-800">Mailtrap (Testing)</p>
                    <p class="text-[11px] text-slate-500 mt-1">Host: <code>sandbox.smtp.mailtrap.io</code></p>
                    <p class="text-[11px] text-slate-500">Port: <code>2525</code> or <code>587</code></p>
                    <p class="text-[11px] text-slate-500">Safe sandbox for email preview</p>
                </div>
                <div class="bg-white p-3 rounded-lg border border-slate-200/60 shadow-xs">
                    <p class="font-bold text-slate-800">cPanel / Custom Domain</p>
                    <p class="text-[11px] text-slate-500 mt-1">Host: <code>mail.yourdomain.com</code></p>
                    <p class="text-[11px] text-slate-500">Port: <code>465</code> (SSL) or <code>587</code> (TLS)</p>
                    <p class="text-[11px] text-slate-500">User: full email address</p>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp Business API Settings Form -->
    <div x-show="tab === 'whatsapp'" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-slate-900 text-base">WhatsApp Notification Integration</h3>
            <p class="text-xs text-slate-500">Configure provider credentials for instant automated appointment booking and status dispatch.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="group" value="whatsapp">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Provider Integration</label>
                <select name="whatsapp_provider" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="meta_cloud" {{ $settings['whatsapp_provider'] === 'meta_cloud' ? 'selected' : '' }}>Meta WhatsApp Cloud API (Recommended)</option>
                    <option value="twilio" {{ $settings['whatsapp_provider'] === 'twilio' ? 'selected' : '' }}>Twilio WhatsApp Gateway</option>
                    <option value="webhook" {{ $settings['whatsapp_provider'] === 'webhook' ? 'selected' : '' }}>Custom Webhook HTTP Relay</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number ID (Meta Cloud)</label>
                    <input type="text" name="whatsapp_phone_number_id" value="{{ $settings['whatsapp_phone_number_id'] }}" placeholder="e.g. 104829104928"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Access Token (Meta Cloud)</label>
                    <input type="password" name="whatsapp_access_token" placeholder="{{ !empty($settings['whatsapp_access_token']) ? '••••••••••••••••' : 'EAAG...' }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Twilio Account SID</label>
                    <input type="text" name="twilio_sid" value="{{ $settings['twilio_sid'] }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Twilio Auth Token</label>
                    <input type="password" name="twilio_token" placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Twilio From Number</label>
                    <input type="text" name="twilio_from_whatsapp" value="{{ $settings['twilio_from_whatsapp'] }}" placeholder="+14155238886"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                </div>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">Save WhatsApp API Credentials</button>
            </div>
        </form>
    </div>
</div>
@endsection
