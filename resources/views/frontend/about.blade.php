@extends('layouts.app')

@section('title', 'About ' . ($globalSettings['academy_name'] ?? 'Our Academy') . ' — Mission, Vision & Excellence')
@section('meta_description', 'Discover the history, mission, vision, and infrastructure of ' . ($globalSettings['academy_name'] ?? 'our academy') . '. Pioneering excellence in higher education and language fluency.')

@section('schema_json')
    @if(!empty($orgSchema))
    <script type="application/ld+json">
        {!! json_encode($orgSchema) !!}
    </script>
    @endif
@endsection

@section('content')
<div class="space-y-16 sm:space-y-20 py-10">
    <!-- About Hero -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 bg-white border border-slate-200 px-3.5 py-1.5 rounded-full shadow-xs inline-block">
            {{ $globalSettings['about_hero_badge'] ?? 'About Our Institution' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">
            {{ $globalSettings['about_hero_title'] ?? 'Dedicated to Inspiring Academic & Language Distinction' }}
        </h1>
        <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
            {{ $globalSettings['about_hero_description'] ?? 'Founded to bridge ambitious students with premier global education opportunities through rigorous test preparation and mentorship.' }}
        </p>
    </div>

    <!-- Campus Statistics (Clean Light Gray Box) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-100 rounded-3xl p-8 sm:p-12 border border-slate-200 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @forelse($statistics as $stat)
                <div class="space-y-1">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight block">
                        {{ $stat->value }}<span class="text-slate-500">{{ $stat->suffix }}</span>
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-slate-600 uppercase tracking-wider block">{{ $stat->label }}</span>
                </div>
            @empty
                <div class="col-span-full py-4 text-center text-slate-400">Statistics configured dynamically via Admin Panel.</div>
            @endforelse
        </div>
    </div>

    <!-- Mission, Vision & Values -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xs space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 text-slate-900 flex items-center justify-center font-bold">
                    <i data-lucide="target" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900">{{ $globalSettings['about_mission_title'] ?? 'Our Mission' }}</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {{ $globalSettings['about_mission_desc'] ?? 'To deliver personalized, scientifically backed educational and language training that empowers students to exceed standard admission thresholds and flourish in global universities.' }}
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xs space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 text-slate-900 flex items-center justify-center font-bold">
                    <i data-lucide="eye" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900">{{ $globalSettings['about_vision_title'] ?? 'Our Vision' }}</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {{ $globalSettings['about_vision_desc'] ?? 'To be the foremost educational academy in the region, recognized internationally for producing Band 8.0+ IELTS candidates, innovative AI diagnostics, and inspiring academic leaders.' }}
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xs space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 text-slate-900 flex items-center justify-center font-bold">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900">{{ $globalSettings['about_values_title'] ?? 'Core Values' }}</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {{ $globalSettings['about_values_desc'] ?? 'Academic integrity, unyielding pursuit of student success, technological innovation in language learning, and accessible mentorship for learners of all backgrounds.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                {{ $globalSettings['about_why_title'] ?? ('Why Choose ' . ($globalSettings['academy_name'] ?? 'Our Academy') . '?') }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-600">
                {{ $globalSettings['about_why_subtitle'] ?? 'Our systematic educational framework sets the benchmark for test preparation and academic counseling.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-2">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-900 flex items-center justify-center mb-3">
                    <i data-lucide="cpu" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-slate-900 text-base">{{ $globalSettings['about_feature1_title'] ?? 'Integrated AI Diagnostics' }}</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    {{ $globalSettings['about_feature1_desc'] ?? 'Our AI speech and essay evaluation modules give instant band score predictions tailored to official IELTS criteria.' }}
                </p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-2">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-900 flex items-center justify-center mb-3">
                    <i data-lucide="headphones" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-slate-900 text-base">{{ $globalSettings['about_feature2_title'] ?? 'Acoustic Testing Labs' }}</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    {{ $globalSettings['about_feature2_desc'] ?? 'Simulate authentic exam day pressure with individual noise-isolated listening booths and digital recording consoles.' }}
                </p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-2">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-900 flex items-center justify-center mb-3">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-slate-900 text-base">{{ $globalSettings['about_feature3_title'] ?? 'Certified British & IDP Trainers' }}</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    {{ $globalSettings['about_feature3_desc'] ?? 'All faculty members possess CELTA / DELTA certifications with over a decade of verified classroom teaching experience.' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
