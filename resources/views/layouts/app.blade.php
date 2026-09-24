<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Apex Academy & IETS — World-Class Academic & IELTS Coaching')</title>
    <meta name="description" content="@yield('meta_description', 'Apex Academy & IETS provides premier higher education prep, IELTS band coaching with AI evaluation, modern multimedia classrooms, and distinguished faculty.')">
    <meta name="keywords" content="@yield('meta_keywords', 'IELTS, IETS, Academy, English Preparation, Band 8, Academic Coaching, Study Abroad')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    <meta name="robots" content="@yield('robots_meta', 'index, follow')">

    <!-- Open Graph & Social Cards -->
    <meta property="og:title" content="@yield('og_title', config('app.name', 'Apex Academy & IETS'))">
    <meta property="og:description" content="@yield('og_description', 'Empowering Students Through Modern Education & IELTS Excellence.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name', 'Apex Academy & IETS'))">
    <meta name="twitter:description" content="@yield('twitter_description', 'Empowering Students Through Modern Education & IELTS Excellence.')">

    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#0f172a',
                        },
                        accent: {
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Structured Data (JSON-LD) -->
    @yield('schema_json')

    @php
        $siteLogo = $globalSettings['academy_logo'] ?? \App\Models\Setting::get('academy_logo');
        $siteName = $globalSettings['academy_name'] ?? \App\Models\Setting::get('academy_name', 'Apex Academy & IETS');
        $sitePhone = $globalSettings['contact_phone'] ?? \App\Models\Setting::get('contact_phone', '+1 (555) 234-5678');
        $siteEmail = $globalSettings['contact_email'] ?? \App\Models\Setting::get('contact_email', 'info@antiacademy.edu');
        $siteAddress = $globalSettings['contact_address'] ?? \App\Models\Setting::get('contact_address', '124 Academic Boulevard, Knowledge Park');
        $fbLink = $globalSettings['social_facebook'] ?? \App\Models\Setting::get('social_facebook', 'https://facebook.com');
        $instaLink = $globalSettings['social_instagram'] ?? \App\Models\Setting::get('social_instagram', 'https://instagram.com');
        $ytLink = $globalSettings['social_youtube'] ?? \App\Models\Setting::get('social_youtube', 'https://youtube.com');
        $liLink = $globalSettings['social_linkedin'] ?? \App\Models\Setting::get('social_linkedin', 'https://linkedin.com');
    @endphp

    @if(!empty($siteLogo))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $siteLogo) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $siteLogo) }}">
    @endif

    <style>
        [x-cloak] { display: none !important; }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.12), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(245, 158, 11, 0.08), transparent 40%);
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen flex flex-col selection:bg-brand-500 selection:text-white" x-data="{ mobileMenu: false }">

    <!-- Top Announcement Bar -->
    <div class="bg-brand-950 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5"><i data-lucide="phone" class="w-3.5 h-3.5 text-accent-500"></i> {{ $sitePhone }}</span>
                <span class="flex items-center gap-1.5 hidden md:flex"><i data-lucide="mail" class="w-3.5 h-3.5 text-accent-500"></i> {{ $siteEmail }}</span>
                <span class="hidden lg:inline text-slate-500">|</span>
                <span class="hidden lg:inline text-slate-400">Admissions Open for Spring {{ date('Y') }} IETS & Language Batches</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('appointments') }}" class="text-accent-500 hover:text-accent-400 font-semibold flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3 h-3"></i> Book Appointment
                </a>
                <span class="text-slate-600">•</span>
                <a href="{{ route('admin.login') }}" class="text-slate-400 hover:text-white transition">Staff Portal</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 glassmorphism border-b border-slate-200/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    @if(!empty($siteLogo))
                        <div class="h-12 w-12 rounded-xl bg-white p-1 shadow-sm border border-slate-200/80 flex items-center justify-center shrink-0 group-hover:scale-105 transition transform">
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-full w-full object-contain">
                        </div>
                    @else
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-brand-700 to-brand-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/25 group-hover:scale-105 transition transform">
                            <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                        </div>
                    @endif
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-brand-600 transition">{{ $siteName }}</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-accent-600 block">&amp; IETS Center</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden xl:flex items-center gap-7 text-sm font-semibold text-slate-700">
                    <a href="{{ route('home') }}" class="hover:text-brand-600 transition {{ request()->routeIs('home') ? 'text-brand-600 font-bold' : '' }}">Home</a>
                    <a href="{{ route('about') }}" class="hover:text-brand-600 transition {{ request()->routeIs('about') ? 'text-brand-600 font-bold' : '' }}">About</a>
                    <a href="{{ route('history') }}" class="hover:text-brand-600 transition {{ request()->routeIs('history') ? 'text-brand-600 font-bold' : '' }}">History</a>
                    <a href="{{ route('iets') }}" class="hover:text-brand-600 transition {{ request()->routeIs('iets') ? 'text-brand-600 font-bold' : '' }}">IETS Prep</a>
                    <a href="{{ route('iets.results') }}" class="hover:text-brand-600 transition {{ request()->routeIs('iets.results') ? 'text-brand-600 font-bold' : '' }}">Results</a>
                    <a href="{{ route('teachers') }}" class="hover:text-brand-600 transition {{ request()->routeIs('teachers*') ? 'text-brand-600 font-bold' : '' }}">Teachers</a>
                    <a href="{{ route('classrooms') }}" class="hover:text-brand-600 transition {{ request()->routeIs('classrooms') ? 'text-brand-600 font-bold' : '' }}">Classrooms</a>
                    <a href="{{ route('gallery') }}" class="hover:text-brand-600 transition {{ request()->routeIs('gallery') ? 'text-brand-600 font-bold' : '' }}">Campus</a>
                    <a href="{{ route('videos') }}" class="hover:text-brand-600 transition {{ request()->routeIs('videos*') ? 'text-brand-600 font-bold' : '' }}">Vlogs</a>
                    <a href="{{ route('blog') }}" class="hover:text-brand-600 transition {{ request()->routeIs('blog*') ? 'text-brand-600 font-bold' : '' }}">News</a>
                    <a href="{{ route('faq') }}" class="hover:text-brand-600 transition {{ request()->routeIs('faq') ? 'text-brand-600 font-bold' : '' }}">FAQ</a>
                    <a href="{{ route('contact') }}" class="hover:text-brand-600 transition {{ request()->routeIs('contact') ? 'text-brand-600 font-bold' : '' }}">Contact</a>
                </nav>

                <!-- Action Button -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('appointments') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-brand-500/20 hover:shadow-lg transition transform hover:-translate-y-0.5">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span>Book Session</span>
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <button @click="mobileMenu = !mobileMenu" class="xl:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenu"></i>
                    <i data-lucide="x" class="w-6 h-6" x-show="mobileMenu" x-cloak></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenu" x-cloak class="xl:hidden border-t border-slate-200 bg-white/95 backdrop-blur-md px-4 pt-3 pb-6 space-y-2">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Home</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">About</a>
            <a href="{{ route('history') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">History</a>
            <a href="{{ route('iets') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">IETS Program</a>
            <a href="{{ route('iets.results') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">IETS Results</a>
            <a href="{{ route('teachers') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Teachers</a>
            <a href="{{ route('classrooms') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Classrooms</a>
            <a href="{{ route('gallery') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Campus Gallery</a>
            <a href="{{ route('videos') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Videos &amp; Vlogs</a>
            <a href="{{ route('blog') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Blog / News</a>
            <a href="{{ route('faq') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">FAQs</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Contact</a>
            <div class="pt-2">
                <a href="{{ route('appointments') }}" class="w-full flex items-center justify-center gap-2 bg-brand-600 text-white font-bold py-2.5 rounded-xl shadow">
                    <i data-lucide="calendar" class="w-4 h-4"></i> Book Appointment
                </a>
            </div>
        </div>
    </header>

    <!-- Main Dynamic Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg text-emerald-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg text-red-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                <!-- Col 1: About -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        @if(!empty($siteLogo))
                            <div class="h-12 w-12 rounded-xl bg-white p-1 shadow-sm flex items-center justify-center shrink-0">
                                <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-full w-full object-contain">
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white font-bold text-xl">
                                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                            </div>
                        @endif
                        <span class="text-xl font-bold text-white tracking-tight">{{ $siteName }}</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed pr-6">
                        An elite educational institution dedicated to higher learning, language fluency, and premier IELTS/IETS test preparation. Featuring AI-assisted evaluation, world-class faculty, and modern laboratory infrastructure.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        @if(!empty($fbLink))
                            <a href="{{ $fbLink }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition"><i data-lucide="facebook" class="w-4 h-4"></i></a>
                        @endif
                        @if(!empty($instaLink))
                            <a href="{{ $instaLink }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition"><i data-lucide="instagram" class="w-4 h-4"></i></a>
                        @endif
                        @if(!empty($ytLink))
                            <a href="{{ $ytLink }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition"><i data-lucide="youtube" class="w-4 h-4"></i></a>
                        @endif
                        @if(!empty($liLink))
                            <a href="{{ $liLink }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition"><i data-lucide="linkedin" class="w-4 h-4"></i></a>
                        @endif
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Quick Links</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                        <li><a href="{{ route('history') }}" class="hover:text-white transition">Milestone Timeline</a></li>
                        <li><a href="{{ route('teachers') }}" class="hover:text-white transition">Faculty Members</a></li>
                        <li><a href="{{ route('classrooms') }}" class="hover:text-white transition">Classrooms &amp; Labs</a></li>
                        <li><a href="{{ route('gallery') }}" class="hover:text-white transition">Campus Gallery</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-white transition">Admissions FAQ</a></li>
                    </ul>
                </div>

                <!-- Col 3: Programs -->
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">IETS / IELTS Prep</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('iets') }}" class="hover:text-white transition">IETS Overview</a></li>
                        <li><a href="{{ route('iets.results') }}" class="hover:text-white transition">Band Results &amp; Scores</a></li>
                        <li><a href="{{ route('iets') }}#modules" class="hover:text-white transition">Speaking &amp; Listening</a></li>
                        <li><a href="{{ route('iets') }}#evaluation" class="hover:text-white transition">AI Band Evaluation</a></li>
                        <li><a href="{{ route('appointments') }}" class="hover:text-white transition">Diagnostic Mock Test</a></li>
                        <li><a href="{{ route('videos') }}" class="hover:text-white transition">Lectures &amp; Vlogs</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact -->
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Contact Campus</h4>
                    <div class="space-y-3 text-sm text-slate-400">
                        <p class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-accent-500 shrink-0 mt-1"></i>
                            <span>{{ $siteAddress }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="phone" class="w-4 h-4 text-accent-500 shrink-0"></i>
                            <span>{{ $sitePhone }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="mail" class="w-4 h-4 text-accent-500 shrink-0"></i>
                            <span>{{ $siteEmail }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="clock" class="w-4 h-4 text-accent-500 shrink-0"></i>
                            <span>Mon - Sat: 8:00 AM - 7:00 PM</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Apex Academy &amp; IETS Management System. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('privacy') }}" class="hover:text-slate-400 transition">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-slate-400 transition">Terms of Service</a>
                    <a href="{{ route('sitemap') }}" class="hover:text-slate-400 transition">XML Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Agentic AI Chatbot Widget -->
    <div x-data="aiChatbotWidget()" class="fixed bottom-6 right-6 z-50">
        <!-- Floating Trigger Button -->
        <button @click="toggleChat()" 
                class="relative group w-14 h-14 rounded-full bg-gradient-to-r from-brand-600 to-brand-800 text-white flex items-center justify-center shadow-xl shadow-brand-500/40 hover:scale-105 active:scale-95 transition transform">
            <i data-lucide="bot" class="w-7 h-7" x-show="!isOpen"></i>
            <i data-lucide="x" class="w-7 h-7" x-show="isOpen" x-cloak></i>
            <!-- Ping indicator -->
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-500 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-accent-500 border-2 border-white"></span>
            </span>
        </button>

        <!-- Chat Window Modal -->
        <div x-show="isOpen" x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="absolute bottom-16 right-0 w-[360px] sm:w-[420px] max-h-[580px] h-[540px] bg-white rounded-2xl shadow-2xl border border-slate-200/90 flex flex-col overflow-hidden z-50">
            
            <!-- Chat Header -->
            <div class="bg-gradient-to-r from-brand-900 to-brand-700 text-white p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-accent-400">
                        <i data-lucide="bot" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm leading-tight">Apex AI Academic Counselor</h4>
                        <span class="text-[11px] text-emerald-400 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
                            Online • Action &amp; Booking Enabled
                        </span>
                    </div>
                </div>
                <button @click="isOpen = false" class="text-white/80 hover:text-white p-1">
                    <i data-lucide="minus" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Messages Stream -->
            <div class="flex-1 p-4 overflow-y-auto space-y-3 text-sm bg-slate-50/70" id="chat-stream">
                <!-- Welcome Message -->
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-brand-600 text-white flex items-center justify-center text-xs shrink-0 mt-0.5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-tl-sm p-3 shadow-sm max-w-[85%]">
                        <p>Hello! Welcome to <strong>Apex Academy &amp; IETS</strong>. I can assist you with courses, teacher schedules, admissions, or check live appointment slots for tomorrow. What would you like to know?</p>
                    </div>
                </div>

                <!-- Chat History Items -->
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex items-start gap-2.5'">
                        <template x-if="msg.role === 'assistant' || msg.role === 'tool'">
                            <div class="w-7 h-7 rounded-lg bg-brand-600 text-white flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i data-lucide="bot" class="w-3.5 h-3.5"></i>
                            </div>
                        </template>
                        <div :class="msg.role === 'user' 
                                ? 'bg-gradient-to-r from-brand-600 to-brand-700 text-white rounded-2xl rounded-tr-sm p-3 shadow-sm max-w-[85%]' 
                                : 'bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-tl-sm p-3 shadow-sm max-w-[85%]'">
                            <p class="whitespace-pre-line leading-relaxed" x-html="formatMessage(msg.content)"></p>
                        </div>
                    </div>
                </template>

                <!-- Loading Bubble -->
                <div x-show="isLoading" class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-brand-600 text-white flex items-center justify-center text-xs shrink-0 mt-0.5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm p-3 shadow-sm flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce"></span>
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce [animation-delay:0.2s]"></span>
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce [animation-delay:0.4s]"></span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Pills -->
            <div class="p-2 border-t border-slate-100 bg-white flex gap-1.5 overflow-x-auto text-xs whitespace-nowrap">
                <button @click="sendPreset('Check available slots for tomorrow')" class="bg-slate-100 hover:bg-brand-50 hover:text-brand-600 px-2.5 py-1 rounded-full text-slate-600 transition">📅 Tomorrow Slots</button>
                <button @click="sendPreset('Who teaches IETS?')" class="bg-slate-100 hover:bg-brand-50 hover:text-brand-600 px-2.5 py-1 rounded-full text-slate-600 transition">👨‍🏫 Teachers</button>
                <button @click="sendPreset('Tell me about IETS band scoring')" class="bg-slate-100 hover:bg-brand-50 hover:text-brand-600 px-2.5 py-1 rounded-full text-slate-600 transition">🎯 Band Scoring</button>
            </div>

            <!-- Chat Input Box -->
            <form @submit.prevent="sendMessage()" class="p-3 border-t border-slate-200 bg-white flex items-center gap-2">
                <input type="text" x-model="userInput" placeholder="Ask about courses, appointments..." 
                       class="flex-1 text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition"
                       :disabled="isLoading">
                <button type="submit" 
                        class="bg-brand-600 hover:bg-brand-700 text-white p-2.5 rounded-xl shadow transition disabled:opacity-50"
                        :disabled="!userInput.trim() || isLoading">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        function aiChatbotWidget() {
            return {
                isOpen: false,
                userInput: '',
                isLoading: false,
                sessionId: 'session_' + Math.random().toString(36).substring(2, 12),
                messages: [],
                toggleChat() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.$nextTick(() => {
                            lucide.createIcons();
                            this.scrollToBottom();
                        });
                    }
                },
                sendPreset(text) {
                    this.userInput = text;
                    this.sendMessage();
                },
                async sendMessage() {
                    const text = this.userInput.trim();
                    if (!text || this.isLoading) return;

                    this.messages.push({ role: 'user', content: text });
                    this.userInput = '';
                    this.isLoading = true;
                    this.$nextTick(() => {
                        this.scrollToBottom();
                        lucide.createIcons();
                    });

                    try {
                        const response = await fetch('{{ route('api.ai.chat') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                message: text,
                                session_id: this.sessionId,
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.messages.push({ role: 'assistant', content: data.message });
                        } else {
                            this.messages.push({ role: 'assistant', content: data.message || 'Sorry, I am currently unable to process your request.' });
                        }
                    } catch (e) {
                        this.messages.push({ role: 'assistant', content: 'Connection issue. Please verify your internet or try again later.' });
                    } finally {
                        this.isLoading = false;
                        this.$nextTick(() => {
                            this.scrollToBottom();
                            lucide.createIcons();
                        });
                    }
                },
                formatMessage(text) {
                    if (!text) return '';
                    // Basic markdown parsing for links and bold
                    let formatted = text
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                        .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="underline text-brand-600 font-bold hover:text-brand-800">$1</a>');
                    return formatted;
                },
                scrollToBottom() {
                    const el = document.getElementById('chat-stream');
                    if (el) el.scrollTop = el.scrollHeight;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>
</html>
