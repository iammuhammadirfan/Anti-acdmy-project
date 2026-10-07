@extends('layouts.app')

@section('title', 'Courses, Test Schedules & Batch Timings — ' . ($globalSettings['academy_name'] ?? config('app.name', 'Anti Academy')))
@section('meta_description', 'View official course timings, test schedules, and flexible 3-slot daily batch timings (Morning 9-12, Midday 11-2, Evening 4-7) for IELTS, PTE, and standardized language courses.')

@section('content')
<!-- Light / Cream Hero Section -->
<section class="relative bg-gradient-to-b from-amber-50/60 via-slate-50 to-white py-16 lg:py-24 text-slate-900 overflow-hidden border-b border-slate-200/70">
    <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:24px_24px] opacity-60 pointer-events-none"></div>
    <div class="absolute -top-24 right-10 w-96 h-96 bg-brand-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 left-10 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-amber-100/80 text-amber-900 border border-amber-300/60 mb-5 shadow-xs">
            <i data-lucide="clock" class="w-4 h-4 text-amber-700"></i>
            <span>Flexible 3-Slot Daily Schedules (9-12 • 11-2 • 4-7)</span>
        </span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 mb-5">
            Courses, Test Schedules &amp; <span class="bg-gradient-to-r from-brand-600 via-indigo-600 to-brand-700 bg-clip-text text-transparent">Batch Timings</span>
        </h1>
        <p class="text-slate-600 text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
            Select your target exam and choose from 3 flexible daily batch slots designed for students, university candidates, and working professionals.
        </p>
    </div>
</section>

<!-- Classes & 3-Slot Timings Grid Section (Light / Cream Background) -->
<section class="py-14 lg:py-20 bg-[#fbfbfa] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($programs->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-8 max-w-xl mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-200/60">
                    <i data-lucide="calendar" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Class Schedules Being Updated</h3>
                <p class="text-sm text-slate-500 mb-6">Please contact admissions or book an appointment for live slot assistance.</p>
                <div>
                    <a href="{{ route('appointments') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-md transition">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span>Book Counseling Session</span>
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-10">
                @foreach($programs as $prog)
                    <div class="bg-white rounded-3xl border border-slate-200/90 hover:border-brand-500/40 transition-all duration-300 shadow-sm hover:shadow-xl p-6 sm:p-8 lg:p-10">
                        
                        <!-- Class Header Info -->
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 pb-6 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2.5 flex-wrap mb-3">
                                    <span class="px-3.5 py-1 rounded-full text-xs font-extrabold bg-brand-50 text-brand-700 border border-brand-200/80">
                                        {{ $prog->badge ?: 'Mon – Fri Batches' }}
                                    </span>
                                    @if(!empty($prog->duration))
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            <i data-lucide="hourglass" class="w-3.5 h-3.5 inline mr-1 text-slate-500"></i>{{ $prog->duration }}
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $prog->title }}</h2>
                                @if(!empty($prog->summary))
                                    <p class="text-slate-600 text-xs sm:text-sm mt-2 leading-relaxed max-w-3xl">{{ $prog->summary }}</p>
                                @endif
                            </div>

                            <div class="shrink-0 flex items-center gap-3">
                                <a href="{{ route('appointments') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 text-white font-bold text-xs sm:text-sm px-6 py-3.5 rounded-xl shadow-md shadow-brand-500/20 transition transform hover:-translate-y-0.5">
                                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                    <span>Enroll in Batch</span>
                                </a>
                            </div>
                        </div>

                        <!-- 3 Class Timing Cards Grid (9-12, 11-2, 4-7) -->
                        <div class="mt-8">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-brand-600"></i>
                                    <span>3 Daily Class Timing Slots</span>
                                </h4>
                                <span class="text-xs text-slate-500 font-medium">All sessions held at main campus &amp; live multimedia labs</span>
                            </div>

                            @php
                                $slot1Active = (bool) ($prog->timing_slot_1_enabled ?? true);
                                $slot2Active = (bool) ($prog->timing_slot_2_enabled ?? true);
                                $slot3Active = (bool) ($prog->timing_slot_3_enabled ?? true);
                                $activeSlotsCount = ($slot1Active ? 1 : 0) + ($slot2Active ? 1 : 0) + ($slot3Active ? 1 : 0);
                            @endphp

                            @if($activeSlotsCount > 0)
                                <div class="grid grid-cols-1 {{ $activeSlotsCount == 2 ? 'md:grid-cols-2 max-w-4xl' : ($activeSlotsCount == 1 ? 'md:grid-cols-1 max-w-xl' : 'md:grid-cols-3') }} gap-5">
                                    @if($slot1Active)
                                    <!-- Card Slot 1: Morning Batch (Warm Cream / Amber) -->
                                    <div class="bg-gradient-to-br from-amber-50/80 via-white to-amber-50/40 rounded-2xl p-6 border border-amber-200/90 hover:border-amber-400/80 hover:shadow-lg transition-all group flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-4">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300/80">
                                                    <span>🌅</span>
                                                    <span>{{ $prog->timing_slot_1_name ?: 'Morning Batch' }}</span>
                                                </span>
                                                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Open
                                                </span>
                                            </div>

                                            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight my-2 font-mono group-hover:text-amber-800 transition">
                                                {{ $prog->timing_slot_1_time ?: '09:00 AM - 12:00 PM' }}
                                            </div>

                                            <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed">
                                                {{ $prog->timing_slot_1_details ?: 'Comprehensive theoretical concepts, examiner vocabulary, and guided classroom drills.' }}
                                            </p>
                                        </div>

                                        <div class="pt-5 mt-5 border-t border-amber-200/60 flex items-center justify-between">
                                            <span class="text-xs text-slate-500 font-semibold">Morning Slot</span>
                                            <a href="{{ route('appointments') }}" class="text-xs font-bold text-amber-800 hover:text-amber-900 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                                                <span>Reserve Slot</span> &rarr;
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    @if($slot2Active)
                                    <!-- Card Slot 2: Midday Batch (Crisp Sky / Light Cream) -->
                                    <div class="bg-gradient-to-br from-sky-50/80 via-white to-sky-50/40 rounded-2xl p-6 border border-sky-200/90 hover:border-sky-400/80 hover:shadow-lg transition-all group flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-4">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-900 border border-sky-300/80">
                                                    <span>☀️</span>
                                                    <span>{{ $prog->timing_slot_2_name ?: 'Midday Batch' }}</span>
                                                </span>
                                                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Open
                                                </span>
                                            </div>

                                            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight my-2 font-mono group-hover:text-sky-800 transition">
                                                {{ $prog->timing_slot_2_time ?: '11:00 AM - 02:00 PM' }}
                                            </div>

                                            <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed">
                                                {{ $prog->timing_slot_2_details ?: 'Interactive multimedia lab simulations, reading speed training, and proctored mock tests.' }}
                                            </p>
                                        </div>

                                        <div class="pt-5 mt-5 border-t border-sky-200/60 flex items-center justify-between">
                                            <span class="text-xs text-slate-500 font-semibold">Midday Slot</span>
                                            <a href="{{ route('appointments') }}" class="text-xs font-bold text-sky-800 hover:text-sky-900 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                                                <span>Reserve Slot</span> &rarr;
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    @if($slot3Active)
                                    <!-- Card Slot 3: Evening Batch (Elegant Violet / Warm Light) -->
                                    <div class="bg-gradient-to-br from-indigo-50/80 via-white to-indigo-50/40 rounded-2xl p-6 border border-indigo-200/90 hover:border-indigo-400/80 hover:shadow-lg transition-all group flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-4">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-900 border border-indigo-300/80">
                                                    <span>🌙</span>
                                                    <span>{{ $prog->timing_slot_3_name ?: 'Evening Batch' }}</span>
                                                </span>
                                                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Open
                                                </span>
                                            </div>

                                            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight my-2 font-mono group-hover:text-indigo-800 transition">
                                                {{ $prog->timing_slot_3_time ?: '04:00 PM - 07:00 PM' }}
                                            </div>

                                            <p class="text-xs sm:text-sm text-slate-600 mt-2.5 leading-relaxed">
                                                {{ $prog->timing_slot_3_details ?: 'Designed for working executives, intensive speaking clinics, and customized writing feedback.' }}
                                            </p>
                                        </div>

                                        <div class="pt-5 mt-5 border-t border-indigo-200/60 flex items-center justify-between">
                                            <span class="text-xs text-slate-500 font-semibold">Evening Slot</span>
                                            <a href="{{ route('appointments') }}" class="text-xs font-bold text-indigo-800 hover:text-indigo-900 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                                                <span>Reserve Slot</span> &rarr;
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-amber-50/50 border border-amber-200 rounded-2xl p-6 text-center">
                                    <p class="text-sm font-semibold text-slate-700">Customized Batch Timings on Demand</p>
                                    <p class="text-xs text-slate-500 mt-1">Please book a session or contact administration for 1-on-1 personalized slot timings.</p>
                                    <a href="{{ route('appointments') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-brand-600 text-white rounded-xl text-xs font-bold shadow hover:bg-brand-700 transition">
                                        <span>Book Personalized Consultation</span> &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Highlights / Inclusions -->
                        @if($prog->features && count($prog->features) > 0)
                            <div class="mt-8 pt-6 border-t border-slate-100">
                                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Included in this Course Batch:</h5>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($prog->features as $feat)
                                        <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 bg-slate-50/80 border border-slate-200/70 px-3 py-2 rounded-xl">
                                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                            <span class="font-medium">{{ $feat }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

<!-- Bottom CTA Section (Light Cream Theme) -->
<section class="py-16 bg-white text-slate-900 border-t border-slate-200/80">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="bg-gradient-to-r from-amber-50 via-slate-50 to-brand-50/40 rounded-3xl border border-amber-200/80 p-8 sm:p-12 text-center shadow-sm">
            <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3">Need Custom Timings or 1-on-1 Fast-Track Preparation?</h3>
            <p class="text-slate-600 text-sm sm:text-base max-w-xl mx-auto mb-8 leading-relaxed">
                Our certified academic evaluators offer personalized diagnostic sessions to evaluate your current level and assign the best timing batch for you.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('appointments') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm px-8 py-4 rounded-xl shadow-lg shadow-brand-600/20 transition transform hover:-translate-y-0.5">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>Book Free Diagnostic Test</span>
                </a>
                <a href="{{ route('contact') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-100 text-slate-800 font-bold text-sm px-8 py-4 rounded-xl border border-slate-300 shadow-xs transition">
                    <i data-lucide="phone-call" class="w-4 h-4"></i>
                    <span>Speak with an Advisor</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
