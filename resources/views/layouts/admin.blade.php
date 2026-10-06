<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteLogo = $globalSettings['academy_logo'] ?? \App\Models\Setting::get('academy_logo');
        $siteName = $globalSettings['academy_name'] ?? \App\Models\Setting::get('academy_name', 'Academy');
    @endphp
    <title>@yield('title', 'Admin Dashboard') — {{ $siteName }}</title>

    @include('partials.favicon')

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

    <div class="flex h-screen overflow-hidden relative">
        <!-- Mobile Sidebar Overlay Backdrop -->
        <div x-show="sidebarOpen" 
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-slate-950/60 z-30 md:hidden backdrop-blur-xs"></div>

        <!-- Sidebar Navigation -->
        <aside class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 transition-transform duration-300 ease-in-out overflow-y-auto shadow-2xl md:shadow-none"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            
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
                        <span class="font-extrabold text-white text-base tracking-tight block truncate max-w-[140px]">{{ $siteName }}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Admin Portal</span>
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

                @if(auth()->user()->canAccessAnySection(['teachers', 'iets', 'iets_results', 'classrooms', 'gallery']))
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
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <span>Classes &amp; Timings</span>
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
                @endif

                @if(auth()->user()->canAccessAnySection(['appointments', 'scheduling_iets', 'scheduling_counseling', 'calendar', 'contact']))
                <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Scheduling &amp; Admissions</div>

                @if(auth()->user()->canAccessAnySection(['appointments', 'scheduling_iets', 'scheduling_counseling', 'calendar']))
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
                        @if(auth()->user()->canAccessAnySection(['scheduling_iets', 'scheduling_counseling']))
                        <a href="{{ route('admin.scheduling.dashboard') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.dashboard') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
                            <span>Scheduling Dashboard</span>
                        </a>
                        @endif

                        @if(auth()->user()->canAccessSection('calendar'))
                        <a href="{{ route('admin.scheduling.calendar') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.calendar') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span>Calendar</span>
                        </a>
                        @endif

                        @if(auth()->user()->canAccessSection('scheduling_counseling'))
                        <a href="{{ route('admin.scheduling.counseling') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.counseling') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="messages-square" class="w-3.5 h-3.5"></i>
                            <span>Counseling Appointments</span>
                        </a>
                        @endif

                        @if(auth()->user()->canAccessSection('scheduling_iets'))
                        <a href="{{ route('admin.scheduling.iets') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.iets') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="file-check-2" class="w-3.5 h-3.5"></i>
                            <span>IETS Test Schedule</span>
                        </a>
                        @endif

                        @if(auth()->user()->canAccessAnySection(['scheduling_iets', 'scheduling_counseling']))
                        <a href="{{ route('admin.scheduling.slots') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.slots') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            <span>Slot Management</span>
                        </a>
                        @endif

                        @if(auth()->user()->canAccessSection('appointments'))
                        <a href="{{ route('admin.scheduling.bookings') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.bookings') && !request()->routeIs('admin.scheduling.counseling') && !request()->routeIs('admin.scheduling.iets') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            <span>Bookings</span>
                        </a>
                        @endif

                        @if(auth()->user()->canAccessSection('students'))
                        <a href="{{ route('admin.scheduling.students') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.students') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                            <span>Students</span>
                        </a>
                        @endif

                        @if(auth()->user()->canAccessSection('appointments', 'edit'))
                        <a href="{{ route('admin.scheduling.emails') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.email*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                            <span>Email Notifications</span>
                        </a>
                        @endif

                        @if(auth()->user()->canAccessSection('settings') || auth()->user()->canAccessSection('appointments', 'edit'))
                        <a href="{{ route('admin.scheduling.settings') }}" 
                           class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.scheduling.settings') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                            <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                            <span>Settings</span>
                        </a>
                        @endif
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
                @endif

                @if(auth()->user()->canAccessAnySection(['sliders', 'homepage', 'about', 'students', 'videos', 'blog', 'faq', 'media']))
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
                <a href="{{ route('admin.about.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.about*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <span>About Page Content</span>
                </a>
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

                @if(auth()->user()->canAccessSection('settings') || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.reviews.index') }}" 
                   class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.reviews*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="message-square-quote" class="w-4 h-4 text-amber-400"></i>
                        <span>Student Reviews</span>
                    </div>
                    @php
                        $pendingReviewsCount = \App\Models\Review::where('is_approved', false)->count();
                    @endphp
                    @if($pendingReviewsCount > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-500 text-black font-extrabold">{{ $pendingReviewsCount }}</span>
                    @endif
                </a>
                @endif

                @if(auth()->user()->canAccessSection('media'))
                <a href="{{ route('admin.media.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.media*') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i data-lucide="folder-image" class="w-4 h-4"></i>
                    <span>Media Library</span>
                </a>
                @endif
                @endif

                @if(auth()->user()->canAccessAnySection(['seo', 'ai', 'users', 'settings']) || auth()->user()->isSuperAdmin())
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

                <a href="{{ route('admin.page_visibility.index') }}" 
                   class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.page_visibility*') ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="eye" class="w-4 h-4 text-emerald-400"></i>
                        <span>Page Visibility</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase bg-emerald-500/20 text-emerald-300">Admin</span>
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
                @endif
            </nav>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            <!-- Top Navbar -->
            <header class="h-16 bg-white border-b border-slate-200 px-3 sm:px-6 flex items-center justify-between shrink-0 gap-2">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg shrink-0" aria-label="Toggle navigation">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h1 class="text-sm sm:text-lg font-bold text-slate-900 truncate">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-1.5 sm:gap-4 shrink-0">
                    <!-- Live Site Link -->
                    <a href="{{ route('home') }}" target="_blank" 
                       class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-brand-600 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 transition">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>View Website</span>
                    </a>

                    <!-- Notification Bell Icon & Dropdown -->
                    <div class="relative" x-data="{ 
                        bellOpen: false, 
                        unreadCount: {{ $adminUnreadBookingsCount ?? 0 }},
                        cleared: false,
                        async markAllAsRead() {
                            this.cleared = true;
                            this.unreadCount = 0;
                            try {
                                await fetch('{{ route('admin.notifications.mark-all-read') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    }
                                });
                            } catch(e) {}
                        },
                        markSingle(id) {
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                            if (this.unreadCount === 0) {
                                this.cleared = true;
                            }
                        }
                    }">
                        <button @click="bellOpen = !bellOpen; if(bellOpen) { $nextTick(() => lucide.createIcons()); }" 
                                class="relative p-2 text-slate-600 hover:text-brand-600 hover:bg-slate-100 rounded-xl transition cursor-pointer flex items-center justify-center"
                                title="Booking Notifications & Alerts"
                                aria-label="View notifications">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <template x-if="unreadCount > 0 && !cleared">
                                <span class="absolute top-1 right-1 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow ring-2 ring-white animate-pulse">
                                    <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                                </span>
                            </template>
                        </button>

                        <!-- Notification Dropdown Menu -->
                        <div x-show="bellOpen" 
                             x-cloak
                             @click.away="bellOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             class="absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200/90 z-50 overflow-hidden">
                            
                            <!-- Dropdown Header -->
                            <div class="p-3.5 px-4 bg-gradient-to-r from-slate-900 to-slate-800 text-white flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="bell" class="w-4 h-4 text-brand-400"></i>
                                    <span class="font-bold text-sm">Booking Alerts</span>
                                    <template x-if="unreadCount > 0 && !cleared">
                                        <span class="bg-rose-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-xs">
                                            <span x-text="unreadCount"></span> New
                                        </span>
                                    </template>
                                </div>
                                <template x-if="unreadCount > 0 && !cleared">
                                    <button @click="markAllAsRead()" class="text-[11px] text-slate-300 hover:text-white transition underline cursor-pointer" title="Dismiss all alerts">
                                        Mark as read
                                    </button>
                                </template>
                            </div>

                            <!-- Bookings List -->
                            <div x-show="cleared || unreadCount === 0" class="p-8 text-center text-slate-400">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2.5 border border-emerald-100">
                                    <i data-lucide="check-check" class="w-5 h-5"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-800">All Caught Up!</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">No unread booking alerts.</p>
                            </div>

                            <div x-show="!cleared && unreadCount > 0" class="max-h-[380px] overflow-y-auto divide-y divide-slate-100">
                                @forelse($adminRecentBookings ?? [] as $booking)
                                @php
                                    $isIets = ($booking->type === 'iets_test');
                                    $reg = $booking->registration_number ?: $booking->booking_code;
                                @endphp
                                <a href="{{ route('admin.notifications.read', $booking->id) }}" 
                                   @click="markSingle({{ $booking->id }})"
                                   class="block p-3.5 hover:bg-slate-50/90 transition group">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs font-bold {{ $isIets ? 'bg-violet-100 text-violet-700 border border-violet-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                            {{ $isIets ? 'IETS' : 'COUN' }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                                <span class="font-bold text-xs text-slate-900 truncate group-hover:text-brand-600 transition">{{ $booking->name }}</span>
                                                <span class="text-[10px] text-slate-400 shrink-0">{{ $booking->created_at ? $booking->created_at->diffForHumans(null, true, true) : '' }}</span>
                                            </div>
                                            <p class="text-[11px] text-slate-600 truncate mb-1">
                                                {{ $booking->test_type ?: ($booking->purpose ?: ($isIets ? 'Mock Test' : 'Counseling')) }}
                                            </p>
                                            <div class="flex items-center gap-2 text-[10px] text-slate-500">
                                                <span class="font-mono bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-semibold">{{ $reg }}</span>
                                                <span>•</span>
                                                <span>{{ $booking->appointment_date ? $booking->appointment_date->format('M d') : '' }} ({{ $booking->time_slot }})</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @empty
                                <div class="p-8 text-center text-slate-400">
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2.5 border border-emerald-100">
                                        <i data-lucide="check-check" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800">All Caught Up!</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">No unread booking alerts.</p>
                                </div>
                                @endforelse
                            </div>

                            <!-- Dropdown Footer -->
                            <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                                <a href="{{ route('admin.scheduling.bookings') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 transition flex items-center justify-center gap-1">
                                    <span>View All Bookings &amp; Schedules</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Link -->
                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 hover:bg-slate-50 p-1.5 rounded-lg transition text-slate-700">
                        <div class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-xs shrink-0">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-semibold hidden lg:inline truncate max-w-[120px]">{{ auth()->user()->name }}</span>
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
            <main class="flex-1 overflow-y-auto p-3.5 sm:p-6 lg:p-8 min-w-0">
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

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl text-red-800 shadow-sm space-y-1">
                        <div class="flex items-center gap-2.5 font-bold text-sm">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                            <span>Please check the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs pl-7 space-y-0.5 text-red-700 font-medium">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        window.watchTimePicker = function(initialValue = '09:00 AM', modelName = null) {
            return {
                isOpen: false,
                hour: 9,
                minute: 0,
                period: 'AM',
                modelName: modelName,
                displayTime: initialValue || '09:00 AM',
                clockNumbers: [
                    { num: 12, x: 50, y: 15 },
                    { num: 1,  x: 67.5, y: 19.7 },
                    { num: 2,  x: 80.3, y: 32.5 },
                    { num: 3,  x: 85, y: 50 },
                    { num: 4,  x: 80.3, y: 67.5 },
                    { num: 5,  x: 67.5, y: 80.3 },
                    { num: 6,  x: 50, y: 85 },
                    { num: 7,  x: 32.5, y: 80.3 },
                    { num: 8,  x: 19.7, y: 67.5 },
                    { num: 9,  x: 15, y: 50 },
                    { num: 10, x: 19.7, y: 32.5 },
                    { num: 11, x: 32.5, y: 19.7 }
                ],
                init() {
                    this.parseInitial(this.displayTime);
                    this.updateDisplay(false);
                },
                parseInitial(val) {
                    if (!val) return;
                    const match = String(val).match(/^(\d{1,2}):(\d{2})(?:\s*([APap][Mm]))?/);
                    if (match) {
                        let h = parseInt(match[1], 10);
                        let m = parseInt(match[2], 10);
                        let p = match[3] ? match[3].toUpperCase() : 'AM';
                        if (h > 12) {
                            h = h - 12;
                            p = 'PM';
                        } else if (h === 0) {
                            h = 12;
                        }
                        this.hour = h;
                        this.minute = m;
                        this.period = p;
                    }
                },
                togglePicker() {
                    this.isOpen = !this.isOpen;
                },
                openPicker() {
                    this.isOpen = true;
                },
                closePicker() {
                    this.isOpen = false;
                },
                setHour(h) {
                    this.hour = h;
                    this.updateDisplay();
                },
                setMinute(m) {
                    this.minute = (m + 60) % 60;
                    this.updateDisplay();
                },
                stepMinute(delta) {
                    let m = this.minute + delta;
                    if (m >= 60) {
                        m = 0;
                        this.stepHour(1);
                    } else if (m < 0) {
                        m = 55;
                        this.stepHour(-1);
                    }
                    this.minute = m;
                    this.updateDisplay();
                },
                stepHour(delta) {
                    let h = this.hour + delta;
                    if (h > 12) h = 1;
                    if (h < 1) h = 12;
                    this.hour = h;
                    this.updateDisplay();
                },
                setPeriod(p) {
                    this.period = p;
                    this.updateDisplay();
                },
                setPreset(preset) {
                    this.parseInitial(preset);
                    this.updateDisplay();
                    this.closePicker();
                },
                updateDisplay(emit = true) {
                    const hStr = String(this.hour).padStart(2, '0');
                    const mStr = String(this.minute).padStart(2, '0');
                    this.displayTime = `${hStr}:${mStr} ${this.period}`;
                    if (this.modelName) {
                        try {
                            const parts = this.modelName.split('.');
                            let target = this;
                            for (let i = 0; i < parts.length - 1; i++) {
                                target = target[parts[i]];
                            }
                            if (target) {
                                target[parts[parts.length - 1]] = this.displayTime;
                            }
                        } catch(e) {}
                    }
                    if (emit) {
                        this.$nextTick(() => {
                            if (this.$refs.inputEl) {
                                this.$refs.inputEl.dispatchEvent(new Event('input', { bubbles: true }));
                                this.$refs.inputEl.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        });
                    }
                },
                hourAngle() {
                    return (this.hour % 12) * 30 + (this.minute / 60) * 30;
                },
                minuteAngle() {
                    return this.minute * 6;
                }
            };
        };

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>
</html>
