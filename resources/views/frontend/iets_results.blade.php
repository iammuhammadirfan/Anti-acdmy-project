@extends('layouts.app')

@php
    $heroBadge = \App\Models\Setting::get('results_page_badge', 'Official Verified Scorecards & Posters');
    $heroTitle = \App\Models\Setting::get('results_page_title', 'Student Hall of Fame & Results');
    $heroSubtitle = \App\Models\Setting::get('results_page_subtitle', 'Authentic standardized result cards earned by our candidates. Filter by IELTS, PTE, or TOEFL to view genuine scorecards.');
@endphp

@section('title', $heroTitle . ' — ' . ($globalSettings['academy_name'] ?? config('app.name', 'Academy')))
@section('meta_description', $heroSubtitle)

@section('content')
<div x-data="{
    modalOpen: false,
    modalImg: '',
    modalTitle: '',
    modalScore: '',
    modalCategory: '',
    openModal(img, title, score, cat) {
        this.modalImg = img;
        this.modalTitle = title;
        this.modalScore = score;
        this.modalCategory = cat;
        this.modalOpen = true;
    }
}">

    <!-- 1. Hero Header & Quick Stats (Light Gray Background with Gradient Shade) -->
    <section class="relative bg-slate-100 py-12 sm:py-16 lg:py-20 text-slate-900 overflow-hidden border-b border-slate-200">
        <!-- Ambient background dot pattern -->
        <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:28px_28px] opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-5">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-slate-800 border border-slate-200 uppercase tracking-wider shadow-xs">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ $heroBadge }}</span>
            </span>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 max-w-4xl mx-auto leading-tight">
                Student <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Hall of Fame</span> &amp; Results
            </h1>

            <p class="text-slate-600 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed">
                {{ $heroSubtitle }}
            </p>

            <!-- Stat Counters (Clean Light White Cards) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-3xl mx-auto pt-4 sm:pt-6">
                <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-slate-200 shadow-xs text-center">
                    <span class="text-xl sm:text-3xl font-black text-slate-900 block">{{ $totalCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5 block">Total Cards</span>
                </div>
                <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-slate-200 shadow-xs text-center">
                    <span class="text-xl sm:text-3xl font-black text-slate-900 block">{{ $ieltsCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5 block">IELTS Cards</span>
                </div>
                <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-slate-200 shadow-xs text-center">
                    <span class="text-xl sm:text-3xl font-black text-slate-900 block">{{ $pteCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5 block">PTE Cards</span>
                </div>
                <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-slate-200 shadow-xs text-center">
                    <span class="text-xl sm:text-3xl font-black text-slate-900 block">{{ $toeflCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5 block">TOEFL Cards</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Portions Filter & Main Cards Grid (Anchor id: results-filter) -->
    <section id="results-filter" class="py-12 sm:py-16 bg-white scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Filter Bar & Search -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-xs">
                <!-- 3 Portion Category Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('iets.results', array_filter(['search' => $search])) }}"
                       class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($type) || strtoupper($type) === 'ALL' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                        All ({{ $totalCount }})
                    </a>

                    <a href="{{ route('iets.results', array_filter(['type' => 'IELTS', 'search' => $search])) }}"
                       class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ strtoupper($type ?? '') === 'IELTS' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        IELTS ({{ $ieltsCount }})
                    </a>

                    <a href="{{ route('iets.results', array_filter(['type' => 'PTE', 'search' => $search])) }}"
                       class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ strtoupper($type ?? '') === 'PTE' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        PTE Pearson ({{ $pteCount }})
                    </a>

                    <a href="{{ route('iets.results', array_filter(['type' => 'TOEFL', 'search' => $search])) }}"
                       class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ strtoupper($type ?? '') === 'TOEFL' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        TOEFL iBT ({{ $toeflCount }})
                    </a>
                </div>

                <!-- Search Box -->
                <form method="GET" action="{{ route('iets.results') }}" class="flex items-center gap-2 w-full lg:w-auto">
                    @if(!empty($type) && strtoupper($type) !== 'ALL')
                        <input type="hidden" name="type" value="{{ $type }}">
                    @endif
                    <div class="relative flex-1 lg:w-72">
                        <input type="text"
                               name="search"
                               value="{{ $search ?? '' }}"
                               placeholder="Search student name or score..."
                               class="w-full bg-white border border-slate-300 rounded-xl pl-9 pr-8 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 shadow-xs">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3"></i>
                        @if(!empty($search))
                            <a href="{{ route('iets.results', array_filter(['type' => $type])) }}" class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-900">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="px-4 py-2.5 text-xs text-white bg-slate-900 hover:bg-slate-800 rounded-xl font-bold transition shrink-0 shadow-xs">
                        Search
                    </button>
                    @if(!empty($search) || (!empty($type) && strtoupper($type) !== 'ALL'))
                        <a href="{{ route('iets.results') }}"
                           class="px-3.5 py-2.5 text-xs text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl font-semibold transition shrink-0">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Active Filter Notification Bar -->
            @if((!empty($type) && strtoupper($type) !== 'ALL') || !empty($search))
            <div class="flex items-center justify-between text-xs text-slate-600 px-1">
                <div>
                    Active Filter:
                    <span class="font-bold text-slate-900">{{ !empty($type) && strtoupper($type) !== 'ALL' ? $type : 'All Tests' }}</span>
                    @if(!empty($search))
                        &bull; Searching for: "<span class="text-indigo-600 font-semibold">{{ $search }}</span>"
                    @endif
                </div>
                <a href="{{ route('iets.results') }}" class="text-indigo-600 hover:underline font-semibold">
                    Clear All
                </a>
            </div>
            @endif

            <!-- Result Cards Grid (Clean White Frames with Soft Shadows) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-6">
                @forelse($results as $item)
                    <div class="group bg-white rounded-2xl border border-slate-200 hover:border-slate-400 shadow-xs hover:shadow-md overflow-hidden transition-all duration-300 flex flex-col justify-between cursor-pointer"
                         @click="openModal('{{ $item->card_image_url }}', '{{ addslashes($item->student_name) }}', '{{ $item->overall_band }}', '{{ $item->category }}')">

                        <!-- Card Top Bar -->
                        <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-900 text-white shadow-xs">
                                {{ $item->category }}
                            </span>

                            <div class="px-2.5 py-0.5 rounded-lg text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $item->category === 'IELTS' ? 'Band ' . $item->overall_band : 'Score ' . $item->overall_band }}
                            </div>
                        </div>

                        <!-- Fixed Standard 4:5 Aspect Ratio Container with Skeleton Loader -->
                        <div class="relative w-full aspect-[4/5] bg-slate-100 overflow-hidden" x-data="{ imgLoaded: false }">
                            <!-- Animated Spinner Skeleton while loading -->
                            <div x-show="!imgLoaded" class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 via-slate-200 to-slate-100 animate-pulse z-10">
                                <div class="w-8 h-8 rounded-full border-2 border-indigo-600 border-t-transparent animate-spin mb-2"></div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Loading Scorecard...</span>
                            </div>

                            <img src="{{ $item->card_image_url }}"
                                 alt="{{ $item->student_name }}"
                                 loading="lazy"
                                 decoding="async"
                                 @load="imgLoaded = true"
                                 class="w-full h-full object-cover group-hover:scale-103 transition-all duration-500"
                                 :class="imgLoaded ? 'opacity-100' : 'opacity-0'">

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4 z-20">
                                <span class="px-3.5 py-1.5 rounded-xl bg-white text-slate-900 text-xs font-bold shadow flex items-center gap-1.5">
                                    <i data-lucide="zoom-in" class="w-3.5 h-3.5"></i> View Full Card
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-3.5 bg-white border-t border-slate-100 flex items-center justify-between">
                            <div class="min-w-0 pr-2">
                                <h3 class="font-bold text-slate-900 text-sm truncate group-hover:text-indigo-600 transition">{{ $item->student_name }}</h3>
                                <p class="text-[11px] text-slate-500 truncate">
                                    {{ $item->test_date ? \Carbon\Carbon::parse($item->test_date)->format('M d, Y') : 'Verified Score' }}
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wider">Overall</span>
                                <span class="text-sm font-black text-slate-900">{{ $item->overall_band }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-slate-50 rounded-2xl border border-slate-200 text-slate-500 space-y-2">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto text-slate-400"></i>
                        <h4 class="text-base font-bold text-slate-800">No scorecards found</h4>
                        <p class="text-xs text-slate-500">No student result cards match your criteria.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Controls -->
            @if($results->hasPages())
                <div class="pt-8 flex justify-center">
                    {{ $results->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- 4. Lightbox Modal for High-Resolution Card View -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/80 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="relative bg-white rounded-3xl p-4 sm:p-6 max-w-lg w-full shadow-2xl flex flex-col items-center max-h-[92vh] overflow-y-auto"
             @click.away="modalOpen = false">
            <div class="w-full flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-slate-900 text-white"
                          x-text="modalCategory"></span>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 truncate max-w-[220px]" x-text="modalTitle"></h3>
                </div>
                <button type="button" @click="modalOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Image 4:5 with Loader -->
            <div class="w-full aspect-[4/5] bg-slate-100 rounded-2xl overflow-hidden flex items-center justify-center shadow-inner relative"
                 x-data="{ modalImgLoaded: false }"
                 x-effect="if(modalOpen) modalImgLoaded = false">
                <div x-show="!modalImgLoaded" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-100 z-10">
                    <div class="w-9 h-9 rounded-full border-3 border-indigo-600 border-t-transparent animate-spin mb-2"></div>
                    <span class="text-xs font-bold text-slate-400">Loading High-Res Poster...</span>
                </div>
                <img :src="modalImg" alt="Result Card" @load="modalImgLoaded = true" class="w-full h-full object-contain transition-opacity duration-300" :class="modalImgLoaded ? 'opacity-100' : 'opacity-0'">
            </div>

            <div class="mt-4 flex items-center justify-between w-full text-xs text-slate-600">
                <div class="flex items-center gap-1.5">
                    <span class="font-bold text-slate-900">Score:</span>
                    <span class="text-slate-900 font-extrabold text-sm" x-text="modalScore"></span>
                </div>
                <a :href="modalImg" target="_blank" download class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition flex items-center gap-1.5">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    Open High-Res Card
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Prevent unwanted top scrolling if URL contains query parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('type') || urlParams.has('search') || window.location.hash === '#results-filter') {
            const filterEl = document.getElementById('results-filter');
            if (filterEl) {
                setTimeout(() => {
                    filterEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }
    });
</script>
@endsection
