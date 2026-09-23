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
    <div x-show="tab === 'email'" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-slate-900 text-base">SMTP Mail Server Settings</h3>
            <p class="text-xs text-slate-500">Configure outbound email credentials for automated appointment confirmations and inquiries.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="group" value="email">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SMTP Host</label>
                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] }}" placeholder="smtp.mailtrap.io"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SMTP Port</label>
                    <input type="number" name="smtp_port" value="{{ $settings['smtp_port'] }}" placeholder="587 / 465"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SMTP Username</label>
                    <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SMTP Password</label>
                    <input type="password" name="smtp_password" placeholder="{{ !empty($settings['smtp_password']) ? '••••••••' : 'Enter password' }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Encryption</label>
                    <select name="smtp_encryption" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="tls" {{ $settings['smtp_encryption'] === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ $settings['smtp_encryption'] === 'ssl' ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">From Email Address</label>
                    <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">Save SMTP Configuration</button>
            </div>
        </form>
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
