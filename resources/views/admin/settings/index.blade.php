@extends('layouts.admin')

@section('title', 'System Settings')
@section('page_title', 'Academy Settings & API Integrations')

@section('content')
<div class="w-full space-y-6" x-data="{ tab: 'general' }">
    <!-- Premium Modern Tab Navigation (100% Responsive Grid - No Scrollbar) -->
    <div class="bg-white p-1.5 sm:p-2 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-1.5 sm:gap-2 text-xs font-bold uppercase tracking-wider">
            <button @click="tab = 'general'" 
                    :class="tab === 'general' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 bg-slate-50/60'" 
                    class="w-full py-2.5 px-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 text-center">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="truncate">General Info</span>
            </button>

            <button @click="tab = 'results'" 
                    :class="tab === 'results' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 bg-slate-50/60'" 
                    class="w-full py-2.5 px-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 text-center">
                <span class="text-sm shrink-0">🏆</span>
                <span class="truncate">Hall of Fame</span>
            </button>

            <a href="{{ route('admin.about.index') }}" 
               class="w-full py-2.5 px-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 bg-slate-50/60 transition-all duration-200 flex items-center justify-center gap-1.5 text-center">
                <span class="text-sm shrink-0">📖</span>
                <span class="truncate">About Page</span>
            </a>

            <button @click="tab = 'social'" 
                    :class="tab === 'social' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 bg-slate-50/60'" 
                    class="w-full py-2.5 px-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 text-center">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                <span class="truncate">Social Links</span>
            </button>

            <button @click="tab = 'email'" 
                    :class="tab === 'email' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 bg-slate-50/60'" 
                    class="w-full py-2.5 px-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 text-center">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span class="truncate">SMTP Email</span>
            </button>

            <button @click="tab = 'telegram'" 
                    :class="tab === 'telegram' ? 'bg-sky-600 text-white shadow-md shadow-sky-600/25' : 'text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200/80'" 
                    class="w-full py-2.5 px-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 text-center">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                <span class="truncate">Telegram</span>
            </button>
        </div>
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Academy Subtitle / Tagline</label>
                    <input type="text" name="academy_tagline" value="{{ $settings['academy_tagline'] ?? '' }}" placeholder="&amp; IELTS Center"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Shown below academy logo/name in header (e.g. &amp; IELTS Center).</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Campus / Office Timings (Contact &amp; Footer)
                    </label>
                    <input type="text" name="academy_timings" value="{{ $settings['academy_timings'] ?? 'Mon - Sat: 8:00 AM - 7:00 PM' }}" placeholder="Mon - Sat: 8:00 AM - 7:00 PM"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Website footer aur contact page dono jagah yehi timing show hogi.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Public Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Primary Telephone</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Add Footer Second Phone Number 
                        <span class="text-[10px] lowercase font-normal text-slate-400">(optional)</span>
                    </label>
                    <input type="text" name="contact_phone_2" value="{{ $settings['contact_phone_2'] ?? '' }}" placeholder="+1 (555) 987-6543"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Optional. Agar add karenge to website footer me 2nd phone number show hoga, warna sirf 1st number show hoga.</p>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-1">
                <!-- Academy Logo -->
                <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Academy Logo</label>
                    @if(!empty($settings['academy_logo']))
                        <div class="mb-3 w-40 h-16 rounded-xl border border-slate-200 p-2 flex items-center justify-center bg-white shadow-xs">
                            <img src="{{ asset('storage/' . $settings['academy_logo']) }}" class="max-h-full max-w-full object-contain">
                        </div>
                    @endif
                    <input type="file" name="logo_file" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1.5">Appears in header, navbar, and email templates.</p>
                </div>

                <!-- Site Favicon -->
                <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Site Favicon
                        <span class="text-[10px] lowercase font-normal text-slate-400">(browser tab &amp; Google icon)</span>
                    </label>

                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-14 h-14 rounded-xl border border-slate-200 p-2 flex items-center justify-center bg-white shadow-xs relative shrink-0">
                            @if(!empty($settings['site_favicon']))
                                @php
                                    $fav = ltrim($settings['site_favicon'], '/\\');
                                    $favUrl = str_starts_with($fav, 'http') ? $fav : (str_starts_with($fav, 'storage/') || str_starts_with($fav, 'uploads/') ? asset($fav) : asset('storage/' . $fav));
                                @endphp
                                <img src="{{ $favUrl }}?v={{ time() }}" alt="Current Favicon" class="w-10 h-10 object-contain">
                            @else
                                <img src="{{ asset('favicon.ico') }}" alt="Default Favicon" class="w-8 h-8 object-contain opacity-60">
                            @endif
                        </div>
                        <div class="text-xs">
                            @if(!empty($settings['site_favicon']))
                                <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold text-[11px] bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60 mb-1">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Custom Favicon Active
                                </span>
                                <div class="text-[11px] text-slate-500 truncate max-w-[200px]" title="{{ $settings['site_favicon'] }}">
                                    {{ basename($settings['site_favicon']) }}
                                </div>
                            @else
                                <span class="inline-flex items-center text-slate-600 text-[11px] bg-slate-200/70 px-2 py-0.5 rounded-md mb-1 font-medium">
                                    Default Favicon (favicon.ico)
                                </span>
                                <p class="text-[11px] text-slate-400">Upload a custom icon for Google search and browser tabs.</p>
                            @endif
                        </div>
                    </div>

                    <input type="file" name="favicon_file" accept=".png,.ico,.jpg,.jpeg,.webp,.svg,image/png,image/x-icon,image/vnd.microsoft.icon,image/jpeg,image/webp,image/svg+xml"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                        Recommended: Square image <strong class="text-slate-700 font-semibold">(512&times;512 px)</strong>. Formats: <span class="text-slate-700 font-medium">PNG, ICO, JPG, WEBP, SVG</span> (Max 1MB).
                    </p>

                    @if(!empty($settings['site_favicon']))
                        <label class="inline-flex items-center gap-2 mt-2.5 text-xs text-rose-600 hover:text-rose-700 cursor-pointer">
                            <input type="checkbox" name="remove_favicon" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span>Revert to default favicon.ico</span>
                        </label>
                    @endif
                </div>
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

    <!-- Telegram Bot Instant Alerts Settings Form -->
    <div x-show="tab === 'telegram'" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6"
         x-data="{
             telegramTesting: false,
             telegramBotToken: '{{ $settings['telegram_bot_token'] ?? '' }}',
             telegramChatId: '{{ $settings['telegram_chat_id'] ?? '' }}',
             telegramTestResult: null,
             telegramTestSuccess: false,
             sendTestTelegram() {
                 if (!this.telegramBotToken || !this.telegramChatId) {
                     alert('Please enter both your Telegram Bot Token and Chat ID to run the test.');
                     return;
                 }
                 this.telegramTesting = true;
                 this.telegramTestResult = null;
                 fetch('{{ route('admin.settings.test_telegram') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json'
                     },
                     body: JSON.stringify({
                         bot_token: this.telegramBotToken,
                         chat_id: this.telegramChatId
                     })
                 })
                 .then(res => res.json().then(data => ({ status: res.status, body: data })))
                 .then(res => {
                     this.telegramTesting = false;
                     this.telegramTestSuccess = res.status === 200 && res.body.success;
                     this.telegramTestResult = res.body.message || (this.telegramTestSuccess ? 'Telegram test alert sent successfully!' : 'Failed to send Telegram message.');
                 })
                 .catch(err => {
                     this.telegramTesting = false;
                     this.telegramTestSuccess = false;
                     this.telegramTestResult = 'Connection error: ' + err.message;
                 });
             }
         }">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-sky-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-sky-500 text-white flex items-center justify-center font-bold shadow-md shadow-sky-500/20">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <span>Telegram Instant Booking Alerts</span>
                        <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 border border-sky-300">100% Free &amp; Instant</span>
                    </h3>
                    <p class="text-xs text-slate-600">Whenever a student books an IELTS Mock Test or Counseling session, receive an instant Telegram message on your phone.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="group" value="telegram">

            <!-- Enable Switch -->
            <div class="flex items-center justify-between p-3.5 bg-sky-50/50 rounded-xl border border-sky-200/80 shadow-xs">
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wider">Enable Telegram Instant Alerts</p>
                    <p class="text-[11px] text-slate-500">Automatically send instant notification to your Telegram when any new booking is submitted.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="telegram_enabled" value="1" {{ ($settings['telegram_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-600"></div>
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Telegram Bot API Token</label>
                    <input type="password" name="telegram_bot_token" x-model="telegramBotToken" required
                           placeholder="e.g. 7584930211:AAH89k3..."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <p class="text-[11px] text-slate-500 mt-1">Get this token from <code>@BotFather</code> on Telegram in 30 seconds.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Your Telegram Chat ID</label>
                    <input type="text" name="telegram_chat_id" x-model="telegramChatId" required
                           placeholder="e.g. 1234567890"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <p class="text-[11px] text-slate-500 mt-1">Get your Chat ID instantly by sending <code>/start</code> to <code>@userinfobot</code> on Telegram.</p>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Telegram Settings
                </button>
            </div>
        </form>

        <!-- Test Telegram Alert Card -->
        <div class="border border-sky-200 bg-sky-50/30 rounded-xl p-5 space-y-3">
            <div>
                <h4 class="font-bold text-slate-900 text-xs sm:text-sm flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500 animate-pulse"></span>
                    Test Telegram Notification
                </h4>
                <p class="text-xs text-slate-500">Send an instant test notification to your Telegram right now to verify that your bot is connected.</p>
            </div>

            <div class="pt-2">
                <button type="button" @click="sendTestTelegram()" :disabled="telegramTesting"
                        class="w-full sm:w-auto px-6 py-2.5 bg-sky-600 hover:bg-sky-700 disabled:opacity-50 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                    <template x-if="telegramTesting">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </template>
                    <span x-text="telegramTesting ? 'Sending Telegram Alert...' : '🚀 Send Test Telegram Alert'"></span>
                </button>
            </div>

            <!-- Test Feedback Banner -->
            <div x-show="telegramTestResult" x-cloak class="mt-3 p-3.5 rounded-xl text-xs font-medium transition"
                 :class="telegramTestSuccess ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-rose-50 text-rose-800 border border-rose-200'">
                <div class="flex items-start gap-2">
                    <span x-text="telegramTestSuccess ? '✔' : '✖'" class="font-bold text-sm"></span>
                    <span x-text="telegramTestResult" class="leading-relaxed"></span>
                </div>
            </div>
        </div>

        <!-- Visual Step-by-Step Instructions -->
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 space-y-3 shadow-xs">
            <p class="font-bold text-slate-900 flex items-center gap-1.5 text-sm">
                <span>📖 1 Minute Telegram Bot Setup (Urdu / English Guide):</span>
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    <p class="font-bold text-sky-900 mb-1">Step 1: Bot Banayein</p>
                    <p class="text-[11px] text-slate-600 leading-relaxed">Telegram par <strong>@BotFather</strong> search karein aur <code>/newbot</code> bhejein. Bot ka name aur username rakhein. Wo aapko <strong>API Token</strong> de dega.</p>
                </div>
                <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    <p class="font-bold text-sky-900 mb-1">Step 2: Bot Start Karein</p>
                    <p class="text-[11px] text-slate-600 leading-relaxed">Apne naye banaye hue Bot ke link par click karke <strong>START</strong> button daba dein taake wo aapko messages bhej sake.</p>
                </div>
                <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    <p class="font-bold text-sky-900 mb-1">Step 3: Chat ID Hasil Karein</p>
                    <p class="text-[11px] text-slate-600 leading-relaxed">Telegram par <strong>@userinfobot</strong> search karein aur <code>/start</code> bhejein. Wo aapka <strong>Id (Chat ID)</strong> batayega. Wo yahan paste karke Test karein!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
