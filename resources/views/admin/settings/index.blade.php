@extends('layouts.admin')

@section('title', 'System Settings')
@section('page_title', 'Academy Settings & API Integrations')

@section('content')
<div class="max-w-4xl space-y-8" x-data="{ tab: 'general' }">
    <!-- Tab Navigation -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3 text-xs font-bold uppercase tracking-wider overflow-x-auto">
        <button @click="tab = 'general'" :class="tab === 'general' ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl transition shadow-sm">General Info</button>
        <button @click="tab = 'results'" :class="tab === 'results' ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
            <span>🏆 Results Page (Hall of Fame)</span>
        </button>
        <a href="{{ route('admin.about.index') }}" class="px-4 py-2 rounded-xl transition shadow-sm bg-white text-slate-600 hover:bg-slate-50 flex items-center gap-1.5 shrink-0 border border-slate-200">
            <span>📖 About Page Content</span>
        </a>
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
                    <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] }}" placeholder="Official Academy Name" required
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

    <!-- WhatsApp Settings Form -->
    <div x-show="tab === 'whatsapp'" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-8"
         x-data="{
             waTesting: false,
             waTestPhone: '{{ $settings['admin_whatsapp_phone'] ?? '923235502570' }}',
             waTestApiKey: '{{ $settings['callmebot_api_key'] ?? '' }}',
             waTestResult: null,
             waTestSuccess: false,
             sendTestWhatsApp() {
                 if (!this.waTestPhone || !this.waTestApiKey) {
                     alert('Please enter both your WhatsApp phone number and CallMeBot API Key to run the test.');
                     return;
                 }
                 this.waTesting = true;
                 this.waTestResult = null;
                 fetch('{{ route('admin.settings.test_whatsapp') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json'
                     },
                     body: JSON.stringify({
                         admin_phone: this.waTestPhone,
                         api_key: this.waTestApiKey
                     })
                 })
                 .then(res => res.json().then(data => ({ status: res.status, body: data })))
                 .then(res => {
                     this.waTesting = false;
                     this.waTestSuccess = res.status === 200 && res.body.success;
                     this.waTestResult = res.body.message || (this.waTestSuccess ? 'Test WhatsApp message dispatched successfully!' : 'Failed to send WhatsApp message.');
                 })
                 .catch(err => {
                     this.waTesting = false;
                     this.waTestSuccess = false;
                     this.waTestResult = 'Connection error: ' + err.message;
                 });
             }
         }">

        <!-- SECTION 1: CallMeBot Free WhatsApp Admin Gateway -->
        <div class="border border-emerald-200 bg-emerald-50/40 rounded-2xl p-5 sm:p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-emerald-200/80 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-600/20">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <span>Admin WhatsApp Instant Alerts</span>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">100% Free - CallMeBot</span>
                        </h3>
                        <p class="text-xs text-slate-600">Receive instant WhatsApp alerts on your phone whenever any student registers for an IETS Mock Test or Counseling Session.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="group" value="whatsapp">

                <!-- Enable / Disable Switch -->
                <div class="flex items-center justify-between p-3.5 bg-white rounded-xl border border-emerald-200/80 shadow-xs">
                    <div>
                        <p class="text-xs font-bold text-slate-800 uppercase tracking-wider">Enable WhatsApp Alerts</p>
                        <p class="text-[11px] text-slate-500">When enabled, the system automatically sends a WhatsApp message with candidate details to your number.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="callmebot_enabled" value="1" {{ ($settings['callmebot_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Admin WhatsApp Phone Number</label>
                        <input type="text" name="admin_whatsapp_phone" x-model="waTestPhone" required
                               placeholder="923235502570"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <p class="text-[11px] text-slate-500 mt-1">Format: Country code without <code>+</code> or <code>00</code> (e.g. <code>923235502570</code> for Pakistan).</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">CallMeBot API Key</label>
                        <input type="text" name="callmebot_api_key" x-model="waTestApiKey" required
                               placeholder="Enter your API key (e.g. 1234567)"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <p class="text-[11px] text-slate-500 mt-1">Get this key in 10 seconds via WhatsApp (see 1-click guide below).</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="https://api.whatsapp.com/send?phone=34684783708&text=I%20allow%20callmebot%20to%20send%20me%20messages" 
                       target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900 bg-white hover:bg-emerald-100/70 border border-emerald-300 px-3.5 py-2 rounded-xl transition shadow-xs">
                        <span>📲 1-Click: Send Authorization Message on WhatsApp</span>
                    </a>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">
                        Save WhatsApp Settings
                    </button>
                </div>
            </form>

            <!-- Test CallMeBot Card -->
            <div class="border-t border-emerald-200/80 pt-4 mt-4 bg-white/70 p-4 rounded-xl border border-emerald-100">
                <h4 class="font-bold text-slate-900 text-xs sm:text-sm mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Test Admin WhatsApp Notification
                </h4>
                <p class="text-xs text-slate-500 mb-3">Send a real-time test notification to your phone right now to verify that your number and API key are working.</p>

                <div class="flex flex-col sm:flex-row gap-3 items-center">
                    <button type="button" @click="sendTestWhatsApp()" :disabled="waTesting"
                            class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                        <template x-if="waTesting">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </template>
                        <span x-text="waTesting ? 'Sending Alert to WhatsApp...' : '🚀 Send Test WhatsApp Alert'"></span>
                    </button>
                    <span class="text-[11px] text-slate-400">Will test recipient: <code class="font-bold text-slate-700" x-text="waTestPhone || 'Not set'"></code></span>
                </div>

                <!-- Test Feedback Banner -->
                <div x-show="waTestResult" x-cloak class="mt-4 p-3.5 rounded-xl text-xs font-medium"
                     :class="waTestSuccess ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-rose-50 text-rose-800 border border-rose-200'">
                    <div class="flex items-start gap-2">
                        <span x-text="waTestSuccess ? '✔' : '✖'" class="font-bold text-sm"></span>
                        <span x-text="waTestResult" class="leading-relaxed"></span>
                    </div>
                </div>
            </div>

            <!-- Visual Step-by-Step Instructions -->
            <div class="p-4 bg-white rounded-xl border border-emerald-200/90 text-xs text-slate-700 space-y-2.5 shadow-xs">
                <p class="font-bold text-emerald-900 flex items-center gap-1.5 text-sm">
                    <span>📖 Free API Key Hasil Karne Ka Tareeqa (Urdu / English Guide):</span>
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                    <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                        <p class="font-bold text-emerald-950 mb-1">Step 1: Save Contact</p>
                        <p class="text-[11px] text-slate-600 leading-relaxed">Apne phone mein ye WhatsApp number save karein: <strong class="text-emerald-700 select-all">+34 684 783 708</strong> ya oper diye gaye green link par click karein.</p>
                    </div>
                    <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                        <p class="font-bold text-emerald-950 mb-1">Step 2: Send Message</p>
                        <p class="text-[11px] text-slate-600 leading-relaxed">Is number par WhatsApp se bilkul ye message send karein:<br><code class="bg-white px-1.5 py-0.5 rounded text-[11px] font-bold text-emerald-800 border select-all">I allow callmebot to send me messages</code></p>
                    </div>
                    <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                        <p class="font-bold text-emerald-950 mb-1">Step 3: Get API Key</p>
                        <p class="text-[11px] text-slate-600 leading-relaxed">CallMeBot 5 se 10 second mein reply karega: <em>"Your APIKEY is: 123456"</em>. Wo key yahan paste karein aur Save button daba dein!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Meta Cloud & Twilio (Student SMS / WhatsApp Dispatch) -->
        <div class="border border-slate-200 rounded-2xl p-5 sm:p-6 space-y-5">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Student Direct WhatsApp Gateway (Meta Cloud / Twilio)</h3>
                <p class="text-xs text-slate-500">Optional: Configure enterprise gateways to send direct confirmation WhatsApp messages to candidate phone numbers.</p>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="group" value="whatsapp">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Student Provider Integration</label>
                    <select name="whatsapp_provider" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="meta_cloud" {{ ($settings['whatsapp_provider'] ?? '') === 'meta_cloud' ? 'selected' : '' }}>Meta WhatsApp Cloud API (Recommended)</option>
                        <option value="twilio" {{ ($settings['whatsapp_provider'] ?? '') === 'twilio' ? 'selected' : '' }}>Twilio WhatsApp Gateway</option>
                        <option value="webhook" {{ ($settings['whatsapp_provider'] ?? '') === 'webhook' ? 'selected' : '' }}>Custom Webhook HTTP Relay</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number ID (Meta Cloud)</label>
                        <input type="text" name="whatsapp_phone_number_id" value="{{ $settings['whatsapp_phone_number_id'] ?? '' }}" placeholder="e.g. 104829104928"
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
                        <input type="text" name="twilio_sid" value="{{ $settings['twilio_sid'] ?? '' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Twilio Auth Token</label>
                        <input type="password" name="twilio_token" placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Twilio From Number</label>
                        <input type="text" name="twilio_from_whatsapp" value="{{ $settings['twilio_from_whatsapp'] ?? '' }}" placeholder="+14155238886"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                    </div>
                </div>

                <div class="flex justify-end pt-3 border-t border-slate-100">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">Save Provider Credentials</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Page (Hall of Fame) Content Settings Form -->
    <div x-show="tab === 'results'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-slate-900 text-base">Results Page (Hall of Fame) Content &amp; Headings</h3>
            <p class="text-xs text-slate-500">Edit the public badge, main title, and description displayed at the top of the Results page (/iets/results).</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="group" value="results">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Top Badge Text</label>
                <input type="text" name="results_page_badge" value="{{ $settings['results_page_badge'] }}" required
                       placeholder="e.g. Official Verified Scorecards & Posters"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                <span class="text-[11px] text-slate-400 mt-1 block">Displays inside the pill badge above the main heading.</span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Main Heading (Title)</label>
                <input type="text" name="results_page_title" value="{{ $settings['results_page_title'] }}" required
                       placeholder="e.g. Student Hall of Fame & Results"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-brand-500">
                <span class="text-[11px] text-slate-400 mt-1 block">The large title shown in Screenshot 2.</span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Subheading / Description</label>
                <textarea name="results_page_subtitle" rows="3" required
                          placeholder="Authentic standardized result cards earned by our candidates..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $settings['results_page_subtitle'] }}</textarea>
                <span class="text-[11px] text-slate-400 mt-1 block">Paragraph shown below the title.</span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Featured Slider Section Title</label>
                <input type="text" name="results_slider_title" value="{{ $settings['results_slider_title'] }}" required
                       placeholder="e.g. Featured Result Scorecards"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">
                    Save Results Page Content
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
