<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — Apex Academy &amp; IETS</title>

    @php
        $siteLogo = $globalSettings['academy_logo'] ?? \App\Models\Setting::get('academy_logo');
        $siteName = $globalSettings['academy_name'] ?? \App\Models\Setting::get('academy_name', 'Apex Academy & IETS');
    @endphp

    @if(!empty($siteLogo))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $siteLogo) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $siteLogo) }}">
    @endif

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
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Custom scrollbar for sidebar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-100 min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 transition-all duration-300 z-30 overflow-y-auto"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               class="fixed md:static inset-y-0 left-0">
            
            <!-- Brand Header -->
            <div class="h-16 px-6 bg-slate-950 flex items-center justify-between border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    @if(!empty($siteLogo))
                        <div class="w-9 h-9 rounded-xl bg-white p-0.5 flex items-center justify-center shrink-0 shadow">
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-full w-full object-contain">
                        </div>
                    @else
                        <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white font-bold shadow-md shadow-brand-500/20">
                            <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                        </div>
                    @endif
                    <div>
                        <span class="font-extrabold text-white text-base tracking-tight block">Apex Admin</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block truncate max-w-[130px]">{{ $siteName }}</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- User Quick Info -->
            <div class="p-4 border-b border-slate-800/80 bg-slate-900/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand-700 text-white font-bold flex items-center justify-center text-sm shadow">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <h5 class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</h5>
                    <span class="text-[11px] text-brand-400 font-medium capitalize">
                        {{ auth()->user()->isSuperAdmin() ? 'Super Administrator' : 'Staff Member' }}
                    </span>
                </div>
            </div>

            <!-- Navigation Menu Items (with Dynamic RBAC) -->
            <nav class="flex-1 px-3 py-4 space-y-1 text-sm font-medium">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Dashboard</span>
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Academics &amp; IETS</div>

                @if(auth()->user()->canAccessSection('teachers'))
                <a href="{{ route('admin.teachers.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.teachers*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>Teachers</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('iets'))
                <a href="{{ route('admin.iets.programs.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.iets.programs*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>IETS Programs</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('iets_results'))
                <a href="{{ route('admin.iets.results.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.iets.results*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="award" class="w-4 h-4"></i>
                    <span>IETS Results</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('classrooms'))
                <a href="{{ route('admin.classrooms.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.classrooms*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="building" class="w-4 h-4"></i>
                    <span>Classrooms &amp; Labs</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('gallery'))
                <a href="{{ route('admin.gallery.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.gallery*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="image" class="w-4 h-4"></i>
                    <span>Campus Gallery</span>
                </a>
                @endif

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Scheduling &amp; Admissions</div>

                @if(auth()->user()->canAccessSection('appointments'))
                <div x-data="{ open: {{ request()->routeIs('admin.scheduling*') || request()->routeIs('admin.appointments*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button type="button" @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.scheduling*') || request()->routeIs('admin.appointments*') ? 'bg-slate-800 text-white font-bold' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="calendar-range" class="w-4 h-4 text-brand-400"></i>
                            <span>Scheduling</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180 text-brand-400' : 'text-slate-400'"></i>
                    </button>

                    <div x-show="open" x-cloak class="pl-4 pr-1 py-1 space-y-1 text-xs">
                        <a href="{{ route('admin.scheduling.dashboard') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.dashboard') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
                            <span>Scheduling Dashboard</span>
                        </a>
                        <a href="{{ route('admin.scheduling.calendar') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.calendar') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span>Calendar</span>
                        </a>
                        <a href="{{ route('admin.scheduling.counseling') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.counseling') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="messages-square" class="w-3.5 h-3.5"></i>
                            <span>Counseling Appointments</span>
                        </a>
                        <a href="{{ route('admin.scheduling.iets') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.iets') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="file-check-2" class="w-3.5 h-3.5"></i>
                            <span>IETS Test Schedule</span>
                        </a>
                        <a href="{{ route('admin.scheduling.slots') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.slots') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            <span>Slot Management</span>
                        </a>
                        <a href="{{ route('admin.scheduling.bookings') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.bookings') && !request()->routeIs('admin.scheduling.counseling') && !request()->routeIs('admin.scheduling.iets') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            <span>Bookings</span>
                        </a>
                        <a href="{{ route('admin.scheduling.students') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.students') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                            <span>Students</span>
                        </a>
                        <a href="{{ route('admin.scheduling.emails') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.email*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                            <span>Email Notifications</span>
                        </a>
                        <a href="{{ route('admin.scheduling.settings') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.settings') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
                @endif

                @if(auth()->user()->canAccessSection('contact'))
                <a href="{{ route('admin.messages.index') }}" 
                   class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.messages*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="inbox" class="w-4 h-4"></i>
                        <span>Inquiries</span>
                    </div>
                </a>
                @endif

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Content Management</div>

                @if(auth()->user()->canAccessSection('sliders'))
                <a href="{{ route('admin.sliders.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.sliders*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    <span>Hero Sliders</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('homepage'))
                <a href="{{ route('admin.sections.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.sections*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="layout" class="w-4 h-4"></i>
                    <span>Page Sections</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('about'))
                <a href="{{ route('admin.timelines.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.timelines*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="history" class="w-4 h-4"></i>
                    <span>Timeline History</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('students'))
                <a href="{{ route('admin.statistics.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.statistics*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                    <span>Statistics &amp; Counters</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('videos'))
                <a href="{{ route('admin.videos.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.videos*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="video" class="w-4 h-4"></i>
                    <span>Videos &amp; Vlogs</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('blog'))
                <a href="{{ route('admin.blog.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.blog*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Blog &amp; News</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('faq'))
                <a href="{{ route('admin.faqs.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.faqs*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="help-circle" class="w-4 h-4"></i>
                    <span>FAQs</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('media'))
                <a href="{{ route('admin.media.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.media*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="folder-image" class="w-4 h-4"></i>
                    <span>Media Library</span>
                </a>
                @endif

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">System &amp; Intelligence</div>

                @if(auth()->user()->canAccessSection('seo'))
                <a href="{{ route('admin.seo.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.seo*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>Technical SEO &amp; GEO</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('ai'))
                <a href="{{ route('admin.ai.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.ai*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="bot" class="w-4 h-4"></i>
                    <span>Agentic AI Chatbot</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('users'))
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.users*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                    <span>Staff Management</span>
                </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.roles.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.roles*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    <span>Roles &amp; Permissions</span>
                </a>
                @endif

                @if(auth()->user()->canAccessSection('settings'))
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.settings*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    <span>Settings &amp; APIs</span>
                </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.activity.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.activity*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="activity" class="w-4 h-4"></i>
                    <span>Activity Audit Logs</span>
                </a>
                @endif
            </nav>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h1 class="text-lg font-bold text-slate-900">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Live Site Link -->
                    <a href="{{ route('home') }}" target="_blank" 
                       class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-brand-600 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 transition">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>View Website</span>
                    </a>

                    <!-- Profile Link -->
                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 hover:bg-slate-50 p-1.5 rounded-lg transition text-slate-700">
                        <div class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-xs">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-semibold hidden md:inline">{{ auth()->user()->name }}</span>
                    </a>

                    <!-- Logout Form -->
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Log Out">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl text-emerald-800 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl text-red-800 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>
</html>
