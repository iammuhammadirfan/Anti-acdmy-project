<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteLogo = $globalSettings['academy_logo'] ?? \App\Models\Setting::get('academy_logo');
        $siteName = $globalSettings['academy_name'] ?? \App\Models\Setting::get('academy_name', 'Academy');
    @endphp
    <title>Forgot Password — {{ $siteName }}</title>

    @include('partials.favicon')

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-slate-100 min-h-screen flex items-center justify-center p-4 selection:bg-blue-600 selection:text-white">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            @if(!empty($siteLogo))
                <div class="inline-flex w-20 h-20 rounded-2xl bg-white p-2 items-center justify-center shadow-lg mb-3 border border-slate-200">
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-full w-full object-contain">
                </div>
            @else
                <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 to-blue-500 items-center justify-center text-white shadow-lg shadow-blue-500/20 mb-3">
                    <i data-lucide="key-round" class="w-8 h-8"></i>
                </div>
            @endif
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $siteName }}</h1>
            <p class="text-sm text-slate-600 mt-1">Staff &amp; Admin Password Recovery</p>
        </div>

        <!-- Recovery Card -->
        <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-200">
            <div class="mb-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="mail-check" class="w-6 h-6"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Forgot Password?</h2>
                <p class="text-xs text-slate-500 mt-1">Enter your registered email address and we'll send you secure instructions to reset your password.</p>
            </div>

            @if(session('error'))
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-lg text-red-800 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r-lg text-emerald-800 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if(session('reset_url'))
                <div class="mb-5 p-4 rounded-2xl bg-blue-50/90 border border-blue-200 text-xs text-blue-950 shadow-sm">
                    <div class="font-bold flex items-center gap-1.5 mb-1.5 text-blue-800">
                        <i data-lucide="sparkles" class="w-4 h-4 text-blue-600"></i>
                        <span>Direct Password Reset Link</span>
                    </div>
                    <p class="text-slate-600 mb-3 text-[11px] leading-relaxed">
                        Click the button below to proceed to the secure password reset screen immediately:
                    </p>
                    <a href="{{ session('reset_url') }}" 
                       class="inline-flex items-center gap-2 font-bold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2.5 rounded-xl transition shadow text-xs">
                        <i data-lucide="lock-keyhole" class="w-4 h-4"></i>
                        <span>Reset Password Now</span>
                    </a>
                </div>
            @endif

            <form action="{{ route('admin.password.email') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Registered Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="admin@antiacademy.edu"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Send Password Reset Link</span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500">
                <a href="{{ route('admin.login') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Sign In</span>
                </a>
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">
                    Public Website
                </a>
            </div>
        </div>

        <div class="text-center text-xs text-slate-500 mt-6">
            Protected by enterprise cryptographic token authentication.
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
