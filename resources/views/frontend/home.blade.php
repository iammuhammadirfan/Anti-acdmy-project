@extends('layouts.app')

@section('title', ($globalSettings['academy_name'] ?? 'Academy') . ' — World-Class Academic & IELTS Coaching')
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
<div class="space-y-20 sm:space-y-24">

    <!-- 1. Hero / Master IELTS Section (Original Layout with Clean Light Gray Background) -->
    <section class="relative bg-slate-100 text-slate-900 overflow-hidden pt-6 pb-16 sm:pb-20 lg:pt-10 lg:pb-24 border-b border-slate-200" 
             x-data="{ currentSlide: 0, total: {{ max(1, $sliders->count()) }} }">
        
        <!-- Subtle Light Ambient Glows -->
        <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:28px_28px] opacity-40 pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php $slideItems = $sliders->isNotEmpty() ? $sliders : [null]; @endphp

            @foreach($slideItems as $idx => $slide)
                <div x-show="currentSlide === {{ $idx }}" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
                    
                    <!-- Left Side: Content & Action Buttons -->
                    <div class="lg:col-span-6 xl:col-span-7 space-y-6 text-left">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 text-slate-800 text-xs font-bold uppercase tracking-wider shadow-xs">
                            <span class="text-amber-500 text-sm leading-none">★</span>
                            <span>Premier Educational Excellence</span>
                        </div>

                        <!-- Main Headline with Gradient Shade -->
                        <h1 class="text-3xl sm:text-5xl xl:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.12]">
                            @if($slide && !empty($slide->heading))
                                {!! preg_replace('/(IELTS|PTE|IETS|Mastery|Education)/i', '<span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">$1</span>', e($slide->heading)) !!}
                            @else
                                Empowering Students Through Modern Education &amp; <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">IETS Mastery</span>
                            @endif
                        </h1>

                        <!-- Supporting Text -->
                        <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed max-w-xl font-normal">
                            {{ $slide?->short_description ?: 'Join thousands of students who achieved Band 8+ and secured admissions into premier international universities with our AI-enhanced learning programs.' }}
                        </p>

                        <!-- Buttons: Explore IETS Programs & Book Appointment -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <!-- Primary Button -->
                            @php
                                $btn1Text = $slide?->button_text ?: 'Explore IETS Programs';
                                $btn1Url = $slide ? ($slide->formatted_button_url ?: route('iets')) : route('iets');
                                $btn1Ext = $slide ? $slide->is_button_external : false;
                            @endphp
                            <a href="{{ $btn1Url }}" 
                               @if($btn1Ext) target="_blank" rel="noopener noreferrer" @endif
                               class="group inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm sm:text-base shadow hover:shadow-md hover:scale-[1.02] transition-all duration-300">
                                <span>{{ $btn1Text }}</span>
                                <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform duration-300"></i>
                            </a>

                            <!-- Secondary Button -->
                            @php
                                $btn2Text = $slide?->secondary_button_text ?: 'Book Appointment';
                                $btn2Url = $slide ? ($slide->formatted_secondary_button_url ?: route('appointments')) : route('appointments');
                                $btn2Ext = $slide ? $slide->is_secondary_button_external : false;
                            @endphp
                            <a href="{{ $btn2Url }}" 
                               @if($btn2Ext) target="_blank" rel="noopener noreferrer" @endif
                               class="group inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-full bg-white hover:bg-slate-50 text-slate-900 border border-slate-300 font-bold text-sm sm:text-base shadow-xs hover:shadow hover:scale-[1.02] transition-all duration-300">
                                <span>{{ $btn2Text }}</span>
                                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform duration-300"></i>
                            </a>
                        </div>

                        <!-- Bottom Floating Feature Strip (Light Minimalist Style) -->
                        <div class="pt-6 sm:pt-8">
                            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-sm grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <!-- Feature 1 -->
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-indigo-600 shrink-0">
                                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold text-slate-900 leading-tight">Official Cambridge</div>
                                        <div class="text-[11px] text-slate-500">Curriculum &amp; Tests</div>
                                    </div>
                                </div>

                                <!-- Feature 2 -->
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-indigo-600 shrink-0">
                                        <i data-lucide="user-check" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold text-slate-900 leading-tight">1-on-1 Coaching</div>
                                        <div class="text-[11px] text-slate-500">Expert Mentorship</div>
                                    </div>
                                </div>

                                <!-- Feature 3 -->
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-indigo-600 shrink-0">
                                        <i data-lucide="mic" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold text-slate-900 leading-tight">AI Speech Labs</div>
                                        <div class="text-[11px] text-slate-500">Acoustic Scoring</div>
                                    </div>
                                </div>

                                <!-- Feature 4 -->
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-indigo-600 shrink-0">
                                        <i data-lucide="target" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold text-slate-900 leading-tight">8.0+ Band Target</div>
                                        <div class="text-[11px] text-slate-500">Proven Results</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Student Photo Composition & Floating Badges -->
                    <div class="lg:col-span-6 xl:col-span-5 relative mt-4 lg:mt-0">
                        <!-- Student Hero Image Box -->
                        <div class="relative w-full rounded-3xl overflow-hidden shadow-xl border border-slate-200 bg-white group">
                            @php
                                $currentHeroImg = ($slide && $slide->image_url) ? $slide->image_url : asset('images/hero-student.jpg');
                            @endphp
                            <img src="{{ $currentHeroImg }}" 
                                 alt="{{ $slide?->heading ?: 'Student Excellence' }}" 
                                 class="w-full h-full object-cover object-center min-h-[360px] sm:min-h-[460px] max-h-[540px] transform group-hover:scale-103 transition-transform duration-700">
                        </div>

                        <!-- Handwritten Script Overlay ("Better English Bigger Opportunities") -->
                        <div class="absolute top-4 right-3 sm:top-6 sm:right-6 z-20 select-none pointer-events-none transform -rotate-3 text-right">
                            <div class="font-handwriting text-3xl sm:text-4xl lg:text-5xl text-white font-bold leading-tight drop-shadow-[0_2px_8px_rgba(0,0,0,0.8)]">
                                Better English<br>
                                <span class="text-amber-300">Bigger</span> Opportunities
                            </div>
                            <div class="flex justify-end">
                                <svg class="w-24 sm:w-32 h-3.5 text-amber-400 mt-1 opacity-90 drop-shadow" viewBox="0 0 140 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 12C45 2 95 3 138 12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Right Side Floating Feature Badge Card (Light Theme) -->
                        <div class="absolute -bottom-4 left-3 sm:bottom-6 sm:left-6 z-20 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-xl max-w-[260px] sm:max-w-[280px] transition transform hover:-translate-y-1 duration-300">
                            <div class="flex items-center gap-2 mb-2 pb-2 border-b border-slate-100">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-extrabold text-slate-900">Cambridge Certified</div>
                                    <div class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Official Exam Prep
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-1.5 text-[11px] text-slate-600 font-medium">
                                <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-indigo-600 shrink-0"></i> Expert Trainers</div>
                                <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-indigo-600 shrink-0"></i> Real Exam Mock Tests</div>
                                <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-indigo-600 shrink-0"></i> AI Speech Evaluation Labs</div>
                                <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-indigo-600 shrink-0"></i> Proven 8.0+ Band Results</div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach

            <!-- Slider Dots (if multiple active slides exist) -->
            @if($sliders->count() > 1)
                <div class="flex justify-center items-center gap-2 pt-6">
                    @foreach($sliders as $idx => $s)
                        <button @click="currentSlide = {{ $idx }}" 
                                :class="currentSlide === {{ $idx }} ? 'w-8 bg-slate-900' : 'w-2 bg-slate-300 hover:bg-slate-400'" 
                                class="h-2 rounded-full transition-all duration-300"
                                aria-label="Go to slide {{ $idx + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- 2. Statistics Counter Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 sm:-mt-16 relative z-20">
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-lg border border-slate-200 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @forelse($statistics as $stat)
                <div class="space-y-1">
                    <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight block">
                        {{ $stat->value }}<span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $stat->suffix }}</span>
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-slate-600 uppercase tracking-wider block">{{ $stat->label }}</span>
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
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full">
                        {{ $intro ? $intro->subtitle : ('Welcome to ' . ($globalSettings['academy_name'] ?? 'Our Academy')) }}
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                        @if($intro && $intro->title)
                            {!! preg_replace('/(Excellence|Mastery|Language)/i', '<span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">$1</span>', e($intro->title)) !!}
                        @else
                            A Center of Educational Excellence &amp; <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Language Mastery</span>
                        @endif
                    </h2>
                    <p class="text-base text-slate-600 leading-relaxed">
                        {{ $intro ? $intro->content : (($globalSettings['academy_name'] ?? 'Our Academy') . ' brings together globally certified educators, state-of-the-art multimedia facilities, and specialized curricula designed to propel students into top international universities and career pathways.') }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="flex items-start gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">AI-Powered Evaluations</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Instant speech &amp; writing diagnostics matching official criteria.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0 font-bold">
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
                           class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-bold text-sm">
                            <span>{{ $intro ? ($intro->button_text ?: 'Learn More About Our Institution') : 'Learn More About Us' }}</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Intro Graphic -->
                <div class="relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-200/90 aspect-[4/3] bg-slate-100 relative flex items-center justify-center group">
                        <img src="{{ ($intro && $intro->image) ? asset('storage/' . $intro->image) : asset('images/about-campus.jpg') }}" 
                             alt="Modern Academic Campus - {{ $globalSettings['academy_name'] ?? 'Prime IELTS College' }}"
                             loading="lazy"
                             decoding="async"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white/95 backdrop-blur-md p-5 rounded-2xl shadow-xl border border-slate-200/90 hidden sm:flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-extrabold text-xl shadow-md shadow-slate-900/20">
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

    <!-- 4. Student Result Cards Slider (with Indigo Gradient Shade) -->
    <section class="py-16 bg-slate-100 text-slate-900 border-y border-slate-200 relative min-h-[460px]"
             x-data="{
                sectionReady: false,
                activeTab: 'ALL',
                modalOpen: false,
                modalImg: '',
                modalTitle: '',
                modalScore: '',
                modalCategory: '',
                init() {
                    const images = Array.from(this.$el.querySelectorAll('.home-results-swiper img'));
                    if (images.length === 0) {
                        this.sectionReady = true;
                        return;
                    }
                    let loadedCount = 0;
                    const totalToCheck = Math.min(images.length, 3);
                    const markReady = () => {
                        loadedCount++;
                        if (loadedCount >= totalToCheck && !this.sectionReady) {
                            this.sectionReady = true;
                            this.$nextTick(() => {
                                if (window.homeResultSwiper) {
                                    window.homeResultSwiper.update();
                                }
                            });
                        }
                    };

                    // Maximum safety fallback timeout so page is never stuck
                    setTimeout(() => {
                        if (!this.sectionReady) {
                            this.sectionReady = true;
                            this.$nextTick(() => {
                                if (window.homeResultSwiper) {
                                    window.homeResultSwiper.update();
                                }
                            });
                        }
                    }, 1200);

                    images.slice(0, 3).forEach(img => {
                        if (img.complete && img.naturalHeight !== 0) {
                            markReady();
                        } else {
                            img.addEventListener('load', markReady, { once: true });
                            img.addEventListener('error', markReady, { once: true });
                        }
                    });
                },
                filterResults(category) {
                    this.activeTab = category;
                    this.$nextTick(() => {
                        if (window.homeResultSwiper) {
                            window.homeResultSwiper.update();
                            window.homeResultSwiper.slideTo(0);
                        }
                    });
                }
             }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-white text-slate-800 border border-slate-200 uppercase tracking-wider mb-3 shadow-xs">
                        <i data-lucide="award" class="w-3.5 h-3.5 text-slate-700"></i>
                        Verified Hall of Fame
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">
                        Student Result <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Scorecards</span>
                    </h2>
                    <p class="text-sm text-slate-600 mt-2 max-w-xl">
                        Real test scorecards achieved by our students. Standardized verified results across <strong class="text-slate-900">IELTS</strong>, <strong class="text-slate-900">PTE</strong>, and <strong class="text-slate-900">TOEFL</strong>.
                    </p>
                </div>

                <!-- 3 Portion Category Filter Tabs -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" @click="filterResults('ALL')"
                            :class="activeTab === 'ALL' ? 'bg-slate-900 text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-300'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        All Results
                    </button>
                    <button type="button" @click="filterResults('IELTS')"
                            :class="activeTab === 'IELTS' ? 'bg-slate-900 text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-300'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        IELTS
                    </button>
                    <button type="button" @click="filterResults('PTE')"
                            :class="activeTab === 'PTE' ? 'bg-slate-900 text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-300'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        PTE Pearson
                    </button>
                    <button type="button" @click="filterResults('TOEFL')"
                            :class="activeTab === 'TOEFL' ? 'bg-slate-900 text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-300'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        TOEFL iBT
                    </button>

                    <!-- Swiper Navigation Arrows -->
                    <div class="hidden sm:flex items-center gap-1.5 ml-2 pl-2 border-l border-slate-300">
                        <button type="button" id="home-swiper-prev" class="w-9 h-9 rounded-xl bg-white hover:bg-slate-200 text-slate-900 flex items-center justify-center transition border border-slate-300 shadow-xs">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </button>
                        <button type="button" id="home-swiper-next" class="w-9 h-9 rounded-xl bg-white hover:bg-slate-200 text-slate-900 flex items-center justify-center transition border border-slate-300 shadow-xs">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Clean Dedicated Loader Container (Only this loader shows until results are 100% loaded) -->
            <div x-show="!sectionReady" class="w-full py-16 sm:py-24 bg-white rounded-3xl border border-slate-200/90 shadow-xs flex flex-col items-center justify-center text-center space-y-4">
                <div class="relative flex items-center justify-center">
                    <div class="w-12 h-12 rounded-full border-4 border-indigo-100 border-t-indigo-600 animate-spin"></div>
                    <div class="absolute w-3.5 h-3.5 rounded-full bg-indigo-600/30 animate-ping"></div>
                </div>
                <div class="space-y-1">
                    <h4 class="text-sm font-extrabold text-slate-900 tracking-tight">Loading Result Scorecards...</h4>
                    <p class="text-xs text-slate-500">Preparing verified IELTS, PTE &amp; TOEFL student result cards</p>
                </div>
            </div>

            <!-- Swiper Slider Container (Fades in smoothly when ready) -->
            <div x-show="sectionReady" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-[0.99]"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="swiper home-results-swiper !overflow-visible">
                <div class="swiper-wrapper">
                    @forelse($ietsResults as $res)
                        <div class="swiper-slide !h-auto"
                             x-show="activeTab === 'ALL' || activeTab === '{{ $res->category }}'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <div class="group relative bg-white border border-slate-200 hover:border-slate-400 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col cursor-pointer"
                                 @click="modalImg = '{{ $res->card_image_url }}'; modalTitle = '{{ addslashes($res->student_name) }}'; modalScore = '{{ $res->overall_band }}'; modalCategory = '{{ $res->category }}'; modalOpen = true">

                                <!-- Card Top Bar -->
                                <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-900 text-white shadow-xs">
                                        {{ $res->category }}
                                    </span>

                                    <div class="px-2 py-0.5 rounded-lg text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $res->category === 'IELTS' ? 'Band ' . $res->overall_band : 'Score ' . $res->overall_band }}
                                    </div>
                                </div>

                                <!-- Fixed 4:5 Aspect Ratio Standard Card Frame with Animated Loader -->
                                <div class="relative w-full aspect-[4/5] bg-slate-100 overflow-hidden" x-data="{ imgLoaded: false }">
                                    <!-- Animated Spinner & Shimmer Skeleton while loading -->
                                    <div x-show="!imgLoaded" class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 via-slate-200 to-slate-100 animate-pulse z-10">
                                        <div class="w-8 h-8 rounded-full border-2 border-indigo-600 border-t-transparent animate-spin mb-2"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Loading Scorecard...</span>
                                    </div>

                                    <img src="{{ $res->card_image_url }}"
                                         alt="{{ $res->student_name }} Result Card"
                                         loading="lazy"
                                         decoding="async"
                                         @load="imgLoaded = true"
                                         class="w-full h-full object-cover group-hover:scale-103 transition-all duration-500"
                                         :class="imgLoaded ? 'opacity-100' : 'opacity-0'">

                                    <!-- Hover Magnify Overlay -->
                                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4 z-20">
                                        <span class="px-3.5 py-1.5 rounded-xl bg-white text-slate-900 text-xs font-bold shadow flex items-center gap-1.5">
                                            <i data-lucide="zoom-in" class="w-3.5 h-3.5"></i> Click to Enlarge
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Footer Info -->
                                <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between">
                                    <div class="min-w-0 pr-2">
                                        <h4 class="font-bold text-slate-900 text-sm truncate group-hover:text-indigo-600 transition">{{ $res->student_name }}</h4>
                                        <p class="text-[11px] text-slate-500 truncate">
                                            {{ $res->test_date ? \Carbon\Carbon::parse($res->test_date)->format('M Y') : 'Verified Score' }}
                                        </p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wider">Result</span>
                                        <span class="text-base font-extrabold text-slate-900">{{ $res->overall_band }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-400">
                            No student result cards available yet.
                        </div>
                    @endforelse
                </div>

                <!-- Swiper Pagination Dots -->
                <div class="swiper-pagination !relative !mt-6"></div>
            </div>

            <!-- Bottom CTA Link -->
            <div class="flex items-center justify-center pt-2">
                <a href="{{ route('iets.results') }}" class="px-8 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow transition transform hover:-translate-y-0.5 flex items-center gap-2.5">
                    <span>See More Results (View Full Hall of Fame)</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <!-- Lightbox Modal for Full Card View -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="relative bg-white rounded-3xl p-4 sm:p-6 max-w-xl w-full shadow-2xl flex flex-col items-center"
                 @click.away="modalOpen = false">
                <div class="w-full flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-slate-900 text-white"
                              x-text="modalCategory"></span>
                        <h3 class="text-base font-bold text-slate-900 inline-block ml-2" x-text="modalTitle"></h3>
                    </div>
                    <button type="button" @click="modalOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="w-full aspect-[4/5] bg-slate-100 rounded-2xl overflow-hidden flex items-center justify-center relative"
                     x-data="{ modalImgLoaded: false }"
                     x-effect="if(modalOpen) modalImgLoaded = false">
                    <!-- Modal image loader -->
                    <div x-show="!modalImgLoaded" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-100 z-10">
                        <div class="w-9 h-9 rounded-full border-3 border-indigo-600 border-t-transparent animate-spin mb-2"></div>
                        <span class="text-xs font-bold text-slate-400">Loading High-Res Poster...</span>
                    </div>
                    <img :src="modalImg" alt="Result Card" @load="modalImgLoaded = true" class="w-full h-full object-contain transition-opacity duration-300" :class="modalImgLoaded ? 'opacity-100' : 'opacity-0'">
                </div>

                <div class="mt-4 flex items-center justify-between w-full text-xs text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900">Score:</span>
                        <span class="text-slate-900 font-extrabold text-sm" x-text="modalScore"></span>
                    </div>
                    <a :href="modalImg" target="_blank" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition flex items-center gap-1.5">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        Open High-Res Card
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. IELTS Program Section (with Gradient Shade) -->
    <section class="bg-white py-20 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full">
                    Official Exam Preparation
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                    Comprehensive <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">IELTS Modules</span>
                </h2>
                <p class="text-sm text-slate-600">Tailored study programs with intensive 1-on-1 speaking clinics, computer-delivered mock tests, and AI evaluation.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($ietsPrograms as $prog)
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 shadow-xs hover:shadow-md transition transform hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-white text-indigo-600 border border-slate-200 flex items-center justify-center mb-4 shadow-xs">
                                <i data-lucide="book-open" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-bold text-slate-900 text-lg mb-2">{{ $prog->title }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-3 mb-4">{{ $prog->summary }}</p>

                            @if($prog->features)
                                <ul class="space-y-1.5 text-xs text-slate-600 border-t border-slate-200 pt-3">
                                    @foreach(array_slice($prog->features, 0, 3) as $feat)
                                        <li class="flex items-center gap-2">
                                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                            <span class="truncate">{{ $feat }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="pt-6 border-t border-slate-200 mt-4">
                            <a href="{{ route('iets') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center justify-between">
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
                    <span>Explore Full IELTS Curriculum</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- 6. Academic Faculty Section (with Gradient Shade) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full">
                Meet the Faculty
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900">
                Distinguished <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Instructors &amp; Examiners</span>
            </h2>
            <p class="text-sm text-slate-600">Learn from seasoned IELTS examiners and subject specialists with decades of proven student success.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($teachers as $teacher)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between group">
                    <div>
                        <div class="h-64 sm:h-72 bg-slate-100 overflow-hidden relative">
                            @if($teacher->profile_image)
                                <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover object-top group-hover:scale-103 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-extrabold text-4xl text-slate-700 bg-slate-200">
                                    {{ substr($teacher->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h4 class="font-bold text-slate-900 text-base leading-snug">{{ $teacher->name }}</h4>
                            <span class="text-xs font-semibold text-indigo-600 block mt-0.5">{{ $teacher->designation }}</span>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $teacher->qualification }}</p>
                        </div>
                    </div>
                    <div class="px-5 pb-5">
                        <a href="{{ route('teachers.show', $teacher->slug) }}" class="w-full block text-center py-2.5 rounded-xl bg-slate-100 hover:bg-slate-900 hover:text-white text-xs font-bold text-slate-700 transition">
                            View Credentials &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-slate-400">Faculty profiles configured dynamically via Admin Panel.</div>
            @endforelse
        </div>
    </section>

    <!-- 7. Classrooms & Labs Section (with Gradient Shade) -->
    <section class="bg-slate-100 text-slate-900 py-20 overflow-hidden border-y border-slate-200" 
             x-data="{
                 scrollLeft() {
                     this.$refs.carousel.scrollBy({ left: -380, behavior: 'smooth' });
                 },
                 scrollRight() {
                     this.$refs.carousel.scrollBy({ left: 380, behavior: 'smooth' });
                 }
             }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 bg-white border border-slate-200 px-3 py-1 rounded-full shadow-xs">
                        Modern Infrastructure
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">
                        World-Class Multimedia Classrooms &amp; <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Speech Labs</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1">High-tech sound-isolated testing suites and acoustic audio-visual setups.</p>
                </div>
                
                <!-- Slider Controls & All Link -->
                <div class="flex items-center gap-3 shrink-0">
                    <div class="flex items-center gap-1.5">
                        <button @click="scrollLeft()" 
                                class="p-2.5 rounded-xl bg-white hover:bg-slate-200 border border-slate-300 text-slate-800 transition shadow-xs cursor-pointer"
                                aria-label="Previous Classrooms">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button @click="scrollRight()" 
                                class="p-2.5 rounded-xl bg-white hover:bg-slate-200 border border-slate-300 text-slate-800 transition shadow-xs cursor-pointer"
                                aria-label="Next Classrooms">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <a href="{{ route('classrooms') }}" class="text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1 pl-2">
                        <span>Explore All</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Classrooms Horizontal Slider -->
            <div x-ref="carousel" 
                 class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4 pt-2 no-scrollbar"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @forelse($classrooms as $room)
                    <div class="w-[85vw] sm:w-[320px] md:w-[370px] shrink-0 snap-start bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                        <!-- Top Image -->
                        <div class="h-52 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                            <img src="{{ $room->primary_image }}" 
                                 alt="{{ $room->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500"
                                 loading="lazy">
                            
                            <!-- Badges -->
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-md text-slate-900 border border-slate-200 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-xs">
                                {{ $room->class_type ?? 'Lab' }} Suite
                            </span>
                            <span class="absolute top-3 right-3 bg-slate-900 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-xs">
                                Capacity: {{ $room->capacity }} Scholars
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-slate-900 text-lg leading-snug">{{ $room->title }}</h4>
                                <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">{{ $room->description }}</p>
                                
                                @if($room->facilities && count($room->facilities) > 0)
                                    <div class="flex flex-wrap gap-1.5 mt-4">
                                        @foreach(array_slice($room->facilities, 0, 3) as $fac)
                                            <span class="text-[10px] bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-1 rounded-md font-medium">{{ $fac }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer -->
                            <div class="pt-4 mt-5 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400">Active Course Facility</span>
                                <a href="{{ route('appointments') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1 group/btn">
                                    <span>Book In-Person Tour</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full py-12 text-center text-slate-400">Classrooms configured dynamically via Admin Panel.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 8. Interactive Appointment Booking CTA Section (Original Layout with Clean Slate Card) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-slate-900 p-8 sm:p-14 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl space-y-4">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-300 bg-slate-800 px-3 py-1 rounded-full">
                    Direct Admissions &amp; Counseling
                </span>
                <h3 class="text-2xl sm:text-4xl font-extrabold leading-tight">
                    Ready to Assess Your <span class="bg-gradient-to-r from-sky-400 via-blue-300 to-indigo-300 bg-clip-text text-transparent">Current IELTS Level</span>?
                </h3>
                <p class="text-sm text-slate-300 leading-relaxed">Book a personalized 1-on-1 counseling session with our master trainers. Review diagnostic test slots, choose your preferred timing, and receive instant email/WhatsApp confirmations.</p>
            </div>
            <div class="shrink-0 flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <a href="{{ route('appointments') }}" class="bg-white hover:bg-slate-100 text-slate-900 font-extrabold text-sm px-8 py-4 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 text-center flex items-center justify-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>Book Appointment</span>
                </a>
                <a href="{{ route('contact') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm px-6 py-4 rounded-xl border border-slate-700 transition text-center">
                    Contact Admissions
                </a>
            </div>
        </div>
    </section>

    <!-- 9. Student Reviews & Testimonials Section (with Gradient Shade) -->
    @if(isset($reviews) && $reviews->isNotEmpty())
    <section class="bg-slate-100 py-20 text-slate-900 relative overflow-hidden border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-slate-800 bg-white border border-slate-200 px-3 py-1 rounded-full shadow-xs">
                        <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                        <span>Student Testimonials &amp; Reviews</span>
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3">
                        What Our <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Students Say</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-xl">
                        Real experiences and feedback from students who mastered IELTS, PTE, and spoken English with our faculty.
                    </p>
                </div>
                
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('contact') }}#review-section" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow transition transform hover:-translate-y-0.5">
                        <i data-lucide="message-square-plus" class="w-4 h-4"></i>
                        <span>Write a Review</span>
                    </a>
                    <div class="flex items-center gap-1.5 ml-2">
                        <button id="home-review-prev" class="p-2.5 rounded-xl bg-white hover:bg-slate-200 border border-slate-300 text-slate-800 transition shadow-xs cursor-pointer" aria-label="Previous Review">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button id="home-review-next" class="p-2.5 rounded-xl bg-white hover:bg-slate-200 border border-slate-300 text-slate-800 transition shadow-xs cursor-pointer" aria-label="Next Review">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reviews Swiper -->
            <div class="swiper home-reviews-swiper !pb-12">
                <div class="swiper-wrapper">
                    @foreach($reviews as $rev)
                        <div class="swiper-slide h-auto">
                            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 h-full flex flex-col justify-between shadow-sm hover:shadow-md transition group">
                                <div>
                                    <!-- Top Rating & Quote Icon -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-1 text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $rev->rating ? 'fill-amber-400 text-amber-400' : 'text-slate-200' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                                            <i data-lucide="quote" class="w-4 h-4 text-slate-400"></i>
                                        </div>
                                    </div>

                                    <!-- Review Text -->
                                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic line-clamp-4 mb-6">
                                        "{{ $rev->review }}"
                                    </p>
                                </div>

                                <!-- Reviewer Info -->
                                <div class="pt-4 border-t border-slate-100 flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-full bg-slate-900 text-white font-extrabold flex items-center justify-center text-sm shadow-sm shrink-0">
                                        {{ substr($rev->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-slate-900 text-sm truncate">{{ $rev->name }}</h4>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[11px] text-slate-500 font-semibold truncate">{{ $rev->course ?: 'Student' }}</span>
                                            <span class="text-slate-300 text-[10px]">•</span>
                                            <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-0.5">
                                                <i data-lucide="badge-check" class="w-3 h-3"></i> Verified
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination !-bottom-1"></div>
            </div>
        </div>
    </section>
    @endif

    <!-- 10. FAQ Section (with Gradient Shade) -->
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 pb-10">
        <div class="text-center space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full">Frequently Asked Questions</span>
            <h2 class="text-3xl font-extrabold text-slate-900">
                Got Questions About <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Admissions &amp; IELTS</span>?
            </h2>
        </div>

        <div class="space-y-3" x-data="{ activeFaq: null }">
            @forelse($faqs as $fIndex => $faq)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <button @click="activeFaq = activeFaq === {{ $fIndex }} ? null : {{ $fIndex }}"
                            class="w-full p-5 text-left font-bold text-slate-900 text-sm flex items-center justify-between hover:text-indigo-600 transition">
                        <span>{{ $faq->question }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition transform" :class="activeFaq === {{ $fIndex }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === {{ $fIndex }}" x-cloak class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
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
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            window.homeResultSwiper = new Swiper('.home-results-swiper', {
                slidesPerView: 1.15,
                spaceBetween: 16,
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '#home-swiper-next',
                    prevEl: '#home-swiper-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 20 },
                    768: { slidesPerView: 2.8, spaceBetween: 22 },
                    1024: { slidesPerView: 3.5, spaceBetween: 24 },
                    1280: { slidesPerView: 4, spaceBetween: 28 },
                }
            });

            window.homeReviewSwiper = new Swiper('.home-reviews-swiper', {
                slidesPerView: 1.15,
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.home-reviews-swiper .swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '#home-review-next',
                    prevEl: '#home-review-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 20 },
                    1024: { slidesPerView: 3, spaceBetween: 24 },
                }
            });
        }
    });
</script>
@endsection
