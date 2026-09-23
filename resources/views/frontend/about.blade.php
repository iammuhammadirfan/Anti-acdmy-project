@extends('layouts.app')

@section('title', 'About Apex Academy & IETS — Mission, Vision & Excellence')
@section('meta_description', 'Discover the history, mission, vision, and infrastructure of Apex Academy & IETS Center. Pioneering excellence in higher education and language fluency.')

@section('schema_json')
    <script type="application/ld+json">
        {!! json_encode($orgSchema) !!}
    </script>
@endsection

@section('content')
<div class="space-y-20 py-12">
    <!-- About Hero -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">About Our Institution</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Dedicated to Inspiring Academic &amp; Language Distinction</h1>
        <p class="text-base text-slate-600 max-w-2xl mx-auto">Founded to bridge ambitious students with premier global education opportunities through rigorous test preparation and mentorship.</p>
    </div>

    <!-- Mission, Vision & Values -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                    <i data-lucide="target" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Our Mission</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    To deliver personalized, scientifically backed educational and language training that empowers students to exceed standard admission thresholds and flourish in global universities.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <i data-lucide="eye" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Our Vision</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    To be the foremost educational academy in the region, recognized internationally for producing Band 8.0+ IELTS candidates, innovative AI diagnostics, and inspiring academic leaders.
                </p>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Core Values</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Academic integrity, unyielding pursuit of student success, technological innovation in language learning, and accessible mentorship for learners of all backgrounds.
                </p>
            </div>
        </div>
    </div>

    <!-- Campus Statistics -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 rounded-3xl p-8 sm:p-12 text-white grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @foreach($statistics as $stat)
                <div>
                    <span class="text-3xl sm:text-4xl font-extrabold text-accent-500 block">{{ $stat->value }}{{ $stat->suffix }}</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-300 uppercase tracking-wider block mt-1">{{ $stat->label }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <h2 class="text-3xl font-extrabold text-slate-900">Why Choose Apex Academy?</h2>
            <p class="text-sm text-slate-600">Our systematic educational framework sets the benchmark for test preparation and academic counseling.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-2">
                <i data-lucide="cpu" class="w-6 h-6 text-brand-600"></i>
                <h4 class="font-bold text-slate-900 text-base">Integrated AI Diagnostics</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Our AI speech and essay evaluation modules give instant band score predictions tailored to official IELTS criteria.</p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-2">
                <i data-lucide="headphones" class="w-6 h-6 text-brand-600"></i>
                <h4 class="font-bold text-slate-900 text-base">Acoustic Testing Labs</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Simulate authentic exam day pressure with individual noise-isolated listening booths and digital recording consoles.</p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-2">
                <i data-lucide="user-check" class="w-6 h-6 text-brand-600"></i>
                <h4 class="font-bold text-slate-900 text-base">Certified British &amp; IDP Trainers</h4>
                <p class="text-xs text-slate-600 leading-relaxed">All faculty members possess CELTA / DELTA certifications with over a decade of verified classroom teaching experience.</p>
            </div>
        </div>
    </div>
</div>
@endsection
