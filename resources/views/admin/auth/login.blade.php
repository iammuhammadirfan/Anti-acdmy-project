<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff &amp; Admin Sign In — Apex Academy</title>

    @php
        $siteLogo = $globalSettings['academy_logo'] ?? \App\Models\Setting::get('academy_logo');
        $siteName = $globalSettings['academy_name'] ?? \App\Models\Setting::get('academy_name', 'Apex Academy & IETS');
    @endphp

    @if(!empty($siteLogo))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $siteLogo) }}">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-slate-900 min-h-screen flex items-center justify-center p-4 selection:bg-blue-600 selection:text-white">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            @if(!empty($siteLogo))
                <div class="inline-flex w-20 h-20 rounded-2xl bg-white p-2 items-center justify-center shadow-xl shadow-blue-500/20 mb-3 border border-slate-100">
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-full w-full object-contain">
                </div>
            @else
                <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 to-blue-500 items-center justify-center text-white shadow-xl shadow-blue-500/30 mb-3">
                    <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                </div>
            @endif
            <h1 class="text-2xl font-extrabold text-white tracking-tight">{{ $siteName }}</h1>
            <p class="text-sm text-slate-400 mt-1">Management Portal &amp; Staff Login</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-8 shadow-2xl border border-slate-100">
            @if(session('error'))
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-lg text-red-800 text-xs font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r-lg text-emerald-800 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="admin@antiacademy.edu"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" name="password" required
                               placeholder="••••••••••••"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded text-blue-600 focus:ring-blue-500">
                        <span>Remember my session</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Sign In to Dashboard</span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <a href="{{ route('home') }}" class="text-xs font-medium text-slate-500 hover:text-blue-600 transition flex items-center justify-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Return to Public Website</span>
                </a>
            </div>
        </div>

        <div class="text-center text-xs text-slate-500 mt-6">
            Protected by enterprise-grade rate limiting &amp; session security.
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
