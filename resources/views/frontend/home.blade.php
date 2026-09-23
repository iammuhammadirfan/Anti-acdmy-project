@extends('layouts.app')

@section('title', 'Apex Academy & IETS — World-Class Academic & IELTS Coaching')
@section('meta_description', 'Empowering Students Through Modern Education, AI-Assisted IELTS Preparation, Expert Faculty, and State-of-the-Art Infrastructure.')

@section('schema_json')
    <script type="application/ld+json">
        {!! json_encode($orgSchema) !!}
    </script>
    @if($faqSchema)
        <script type="application/ld+json">
            {!! json_encode($faqSchema) !!}
        </script>
    @endif
@endsection

@section('content')
<div class="space-y-24">

    <!-- 1. Hero / Large Slider Section -->
    <section class="relative bg-slate-950 text-white overflow-hidden" x-data="heroSlider()">
        <div class="relative min-h-[580px] sm:min-h-[640px] flex items-center">
            @forelse($sliders as $idx => $slide)
                <div x-show="currentSlide === {{ $idx }}" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 flex items-center">
                    
                    <!-- Background Image with Gradient Overlays -->
                    <div class="absolute inset-0 z-0">
                        @if($slide->image)
                            <img src="{{ asset('storage/' . $slide->image) }}" class="w-full h-full object-cover opacity-35 filter brightness-75">
                        @else
                            <div class="w-full h-full bg-gradient-to-r from-brand-950 via-slate-900 to-brand-900 opacity-90"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    </div>

                    <!-- Slide Content -->
                    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                        <div class="max-w-3xl space-y-6">
                            <span class="inline-flex items-center gap-2 bg-brand-500/20 text-brand-300 border border-brand-500/30 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                                <span class="w-2 h-2 rounded-full bg-accent-500 animate-pulse"></span>
                                Premier Educational Excellence
                            </span>

                            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                                {{ $slide->heading }}
                            </h1>

                            <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal">
                                {{ $slide->short_description }}
                            </p>

                            <div class="flex flex-wrap items-center gap-4 pt-4">
                                @if($slide->button_text)
                                    <a href="{{ $slide->button_url ?: route('appointments') }}" 
                                       class="bg-brand-600 hover:bg-brand-500 text-white font-bold text-sm px-7 py-3.5 rounded-xl shadow-lg shadow-brand-500/30 transition transform hover:-translate-y-0.5 inline-flex items-center gap-2">
                                        <span>{{ $slide->button_text }}</span>
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                @endif

                                @if($slide->secondary_button_text)
                                    <a href="{{ $slide->secondary_button_url ?: route('iets') }}" 
                                       class="bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-sm px-7 py-3.5 rounded-xl backdrop-blur-md transition inline-flex items-center gap-2">
                                        <span>{{ $slide->secondary_button_text }}</span>
                                        <i data-lucide="book-open" class="w-4 h-4"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Slide -->
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center mx-auto">
                    <h1 class="text-4xl sm:text-6xl font-extrabold text-white">Empowering Students Through Modern Education</h1>
                    <p class="text-slate-300 mt-4 max-w-2xl mx-auto">Join the premier academy for higher education, IELTS band coaching, and global academic success.</p>
                </div>
            @endforelse
        </div>

        <!-- Slider Controls -->
        @if($sliders->count() > 1)
            <div class="absolute bottom-6 left-0 right-0 z-20 flex justify-center items-center gap-2">
                @foreach($sliders as $idx => $s)
                    <button @click="currentSlide = {{ $idx }}" 
                            :class="currentSlide === {{ $idx }} ? 'w-8 bg-brand-500' : 'w-2 bg-white/40'" 
                            class="h-2 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        @endif
    </section>

    <!-- 2. Statistics Counter Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-200/80 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @forelse($statistics as $stat)
                <div class="space-y-1">
                    <span class="text-3xl sm:text-4xl font-extrabold text-brand-700 tracking-tight block">
                        {{ $stat->value }}<span class="text-accent-500">{{ $stat->suffix }}</span>
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wider block">{{ $stat->label }}</span>
                </div>
            @empty
                <div class="col-span-full py-4 text-center text-slate-400">Statistics configured dynamically via Admin Panel.</div>
            @endforelse
        </div>
    </section>

    <!-- 3. Academy Introduction Section -->
    @php $intro = $sections->get('intro'); @endphp
    @if(!$intro || $intro->is_active)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                        {{ $intro ? $intro->subtitle : 'Welcome to Apex Academy' }}
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                        {{ $intro ? $intro->title : 'A Center of Educational Excellence & Language Mastery' }}
                    </h2>
                    <p class="text-base text-slate-600 leading-relaxed">
                        {{ $intro ? $intro->content : 'Apex Academy & IETS Center brings together globally certified educators, state-of-the-art multimedia facilities, and specialized curricula designed to propel students into top international universities and career pathways.' }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="flex items-start gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/70">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 font-bold">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">AI-Powered Evaluations</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Instant speech &amp; writing diagnostics matching official criteria.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/70">
                            <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center shrink-0 font-bold">
                                <i data-lucide="award" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Band 8.0+ Track Record</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Over 1,200 successful international candidate scores.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ $intro ? ($intro->button_url ?: route('about')) : route('about') }}" 
                           class="inline-flex items-center gap-2 text-brand-600 hover:text-brand-800 font-bold text-sm">
                            <span>{{ $intro ? ($intro->button_text ?: 'Learn More About Our Institution') : 'Learn More About Us' }}</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Intro Graphic -->
                <div class="relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-200 aspect-[4/3] bg-gradient-to-tr from-brand-900 to-slate-800 relative flex items-center justify-center">
                        @if($intro && $intro->image)
                            <img src="{{ asset('storage/' . $intro->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-8 text-white/60">
                                <i data-lucide="book-marked" class="w-16 h-16 mx-auto mb-3"></i>
                                <span class="font-bold text-white text-lg block">Modern Academic Campus</span>
                                <span class="text-xs">Excellence in Teaching &amp; Student Research</span>
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white p-5 rounded-2xl shadow-xl border border-slate-200 hidden sm:flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent-500 text-white flex items-center justify-center font-extrabold text-xl">
                            15+
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 text-sm block">Years of Academic Leadership</span>
                            <span class="text-xs text-slate-500">Accredited by International Bodies</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- 4. IETS Program Section -->
    <section class="bg-gradient-to-b from-slate-100 to-white py-20 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                    Official Exam Preparation
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Comprehensive IETS / IELTS Modules</h2>
                <p class="text-sm text-slate-600">Tailored study programs with intensive 1-on-1 speaking clinics, computer-delivered mock tests, and AI evaluation.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($ietsPrograms as $prog)
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                                <i data-lucide="book-open" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-bold text-slate-900 text-lg mb-2">{{ $prog->title }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-3 mb-4">{{ $prog->summary }}</p>

                            @if($prog->features)
                                <ul class="space-y-1.5 text-xs text-slate-600 border-t border-slate-100 pt-3">
                                    @foreach(array_slice($prog->features, 0, 3) as $feat)
                                        <li class="flex items-center gap-2">
                                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                            <span class="truncate">{{ $feat }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="pt-6 border-t border-slate-100 mt-4">
                            <a href="{{ route('iets') }}" class="text-xs font-bold text-brand-600 hover:text-brand-800 flex items-center justify-between">
                                <span>Module Details</span>
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-400">Programs configured dynamically via Admin Panel.</div>
                @endforelse
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('iets') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm px-6 py-3 rounded-xl shadow transition">
                    <span>Explore Full IETS Curriculum</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- 5. IETS Success Stories & High Band Results -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-wider text-purple-600 bg-purple-50 border border-purple-200 px-3 py-1 rounded-full">
                    Student Achievement
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Recent IETS Band Score Achievements</h2>
                <p class="text-xs text-slate-500 mt-1">Authentic results achieved by Apex Academy students across Academic &amp; General tests.</p>
            </div>
            <a href="{{ route('iets.results') }}" class="text-sm font-bold text-brand-600 hover:text-brand-800 inline-flex items-center gap-1">
                <span>View All Results Board</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($ietsResults as $res)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center font-bold text-lg text-brand-700">
                            @if($res->student_image)
                                <img src="{{ asset('storage/' . $res->student_image) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($res->student_name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $res->student_name }}</h4>
                            <span class="text-[11px] text-slate-500 block">{{ $res->test_type }}</span>
                            <div class="flex items-center gap-2 mt-1 text-[10px] text-slate-400">
                                <span>L: {{ $res->listening_score }}</span>
                                <span>R: {{ $res->reading_score }}</span>
                                <span>W: {{ $res->writing_score }}</span>
                                <span>S: {{ $res->speaking_score }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block font-semibold">OVERALL</span>
                        <span class="text-2xl font-black text-brand-600">{{ $res->overall_band }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-slate-400">Results configured dynamically via Admin Panel.</div>
            @endforelse
        </div>
    </section>

    <!-- 6. Academic Faculty Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                Meet the Faculty
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900">Distinguished Instructors &amp; Examiners</h2>
            <p class="text-sm text-slate-600">Learn from seasoned IELTS examiners and subject specialists with decades of proven student success.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($teachers as $teacher)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between group">
                    <div>
                        <div class="h-56 bg-slate-100 overflow-hidden relative">
                            @if($teacher->profile_image)
                                <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-extrabold text-4xl text-brand-600 bg-brand-50">
                                    {{ substr($teacher->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h4 class="font-bold text-slate-900 text-base leading-snug">{{ $teacher->name }}</h4>
                            <span class="text-xs font-semibold text-brand-600 block mt-0.5">{{ $teacher->designation }}</span>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $teacher->qualification }}</p>
                        </div>
                    </div>
                    <div class="px-5 pb-5">
                        <a href="{{ route('teachers.show', $teacher->slug) }}" class="w-full block text-center py-2 rounded-xl bg-slate-50 hover:bg-brand-50 hover:text-brand-600 text-xs font-bold text-slate-700 transition">
                            View Credentials &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-slate-400">Faculty profiles configured dynamically via Admin Panel.</div>
            @endforelse
        </div>
    </section>

    <!-- 7. Classrooms & Labs Section -->
    <section class="bg-slate-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-accent-500 bg-accent-500/10 border border-accent-500/20 px-3 py-1 rounded-full">
                        Modern Infrastructure
                    </span>
                    <h2 class="text-3xl font-extrabold text-white mt-2">World-Class Multimedia Classrooms &amp; Speech Labs</h2>
                    <p class="text-xs text-slate-400 mt-1">High-tech sound-isolated testing suites and acoustic audio-visual setups.</p>
                </div>
                <a href="{{ route('classrooms') }}" class="text-sm font-bold text-accent-500 hover:text-accent-400 inline-flex items-center gap-1">
                    <span>Explore All Classrooms</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($classrooms as $room)
                    <div class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 shadow-lg flex flex-col justify-between">
                        <div class="h-48 bg-slate-950 relative overflow-hidden flex items-center justify-center">
                            @php $imgs = $room->images ?? []; @endphp
                            @if(!empty($imgs) && isset($imgs[0]))
                                <img src="{{ asset('storage/' . $imgs[0]) }}" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="building" class="w-12 h-12 text-slate-600"></i>
                            @endif
                            <span class="absolute top-3 right-3 bg-slate-900/90 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg">
                                Capacity: {{ $room->capacity }} Seats
                            </span>
                        </div>
                        <div class="p-6">
                            <h4 class="font-bold text-white text-lg">{{ $room->title }}</h4>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $room->description }}</p>
                            @if($room->facilities)
                                <div class="flex flex-wrap gap-1.5 mt-4">
                                    @foreach(array_slice($room->facilities, 0, 3) as $fac)
                                        <span class="text-[10px] bg-slate-700 text-slate-300 px-2 py-0.5 rounded">{{ $fac }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-500">Classrooms configured dynamically via Admin Panel.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 8. Interactive Appointment Booking CTA Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-r from-brand-900 via-brand-800 to-brand-700 p-8 sm:p-14 text-white shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl space-y-4">
                <span class="text-xs font-extrabold uppercase tracking-wider text-accent-400 bg-accent-500/20 px-3 py-1 rounded-full">
                    Direct Admissions &amp; Counseling
                </span>
                <h3 class="text-2xl sm:text-4xl font-extrabold leading-tight">Ready to Assess Your Current IELTS Level?</h3>
                <p class="text-sm text-slate-200 leading-relaxed">Book a personalized 1-on-1 counseling session with our master trainers. Review diagnostic test slots, choose your preferred timing, and receive instant email/WhatsApp confirmations.</p>
            </div>
            <div class="shrink-0 flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <a href="{{ route('appointments') }}" class="bg-accent-500 hover:bg-accent-600 text-slate-950 font-extrabold text-sm px-8 py-4 rounded-xl shadow-xl transition transform hover:-translate-y-0.5 text-center flex items-center justify-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>Book Your Free Slot</span>
                </a>
                <a href="{{ route('contact') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold text-sm px-6 py-4 rounded-xl border border-white/20 transition text-center">
                    Contact Admissions
                </a>
            </div>
        </div>
    </section>

    <!-- 9. FAQ Section -->
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="text-center space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">Frequently Asked Questions</span>
            <h2 class="text-3xl font-extrabold text-slate-900">Got Questions About Admissions &amp; IETS?</h2>
        </div>

        <div class="space-y-3" x-data="{ activeFaq: null }">
            @forelse($faqs as $fIndex => $faq)
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <button @click="activeFaq = activeFaq === {{ $fIndex }} ? null : {{ $fIndex }}"
                            class="w-full p-5 text-left font-bold text-slate-900 text-sm flex items-center justify-between hover:text-brand-600 transition">
                        <span>{{ $faq->question }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition transform" :class="activeFaq === {{ $fIndex }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === {{ $fIndex }}" x-cloak class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        {{ $faq->answer }}
                    </div>
                </div>
            @empty
                <div class="py-6 text-center text-slate-400">FAQs configured dynamically via Admin Panel.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    function heroSlider() {
        return {
            currentSlide: 0,
            totalSlides: {{ $sliders->count() ?: 1 }},
            init() {
                if (this.totalSlides > 1) {
                    setInterval(() => {
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    }, 6500);
                }
            }
        }
    }
</script>
@endsection
