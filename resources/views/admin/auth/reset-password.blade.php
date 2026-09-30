<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password — {{ \App\Models\Setting::get('academy_name', 'Apex Academy') }}</title>

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
                    <i data-lucide="shield-check" class="w-8 h-8"></i>
                </div>
            @endif
            <h1 class="text-2xl font-extrabold text-white tracking-tight">{{ $siteName }}</h1>
            <p class="text-sm text-slate-400 mt-1">Create New Password</p>
        </div>

        <!-- Reset Password Card -->
        <div class="bg-white rounded-3xl p-8 shadow-2xl border border-slate-100">
            <div class="mb-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="lock-keyhole" class="w-6 h-6"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Set New Password</h2>
                <p class="text-xs text-slate-500 mt-1">Please enter your new strong password below to regain account access.</p>
            </div>

            @if(session('error'))
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-lg text-red-800 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-lg text-red-800 text-xs font-semibold">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Account Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email', $email) }}" required readonly
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">New Password (Min 8 Characters)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" id="password" name="password" required autofocus
                               placeholder="••••••••••••" minlength="8"
                               class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                        <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-1')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <i id="eye-icon-1" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Confirm New Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               placeholder="••••••••••••" minlength="8"
                               class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-2')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <i id="eye-icon-2" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-500/25 transition transform hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>Save New Password &amp; Sign In</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <a href="{{ route('admin.login') }}" class="text-xs font-medium text-slate-500 hover:text-blue-600 transition flex items-center justify-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Sign In</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>
