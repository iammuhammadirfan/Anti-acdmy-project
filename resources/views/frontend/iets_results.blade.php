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
    activeCategory: '{{ $type ? strtoupper($type) : 'ALL' }}',
    searchQuery: '{{ addslashes($search ?? '') }}',
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
    },
    setCategory(cat) {
        this.activeCategory = cat;
        const url = new URL(window.location);
        if (cat === 'ALL') {
            url.searchParams.delete('type');
        } else {
            url.searchParams.set('type', cat);
        }
        window.history.replaceState({}, '', url);
    },
    matches(cat, name, score) {
        const catUpper = (cat || '').toUpperCase();
        if (this.activeCategory !== 'ALL' && !catUpper.includes(this.activeCategory)) {
            return false;
        }
        if (!this.searchQuery || !this.searchQuery.trim()) {
            return true;
        }
        const q = this.searchQuery.toLowerCase().trim();
        return (name || '').toLowerCase().includes(q) ||
               (score || '').toLowerCase().includes(q) ||
               catUpper.toLowerCase().includes(q);
    }
}">

    <!-- 1. Hero Header & Quick Stats (Configurable from Admin -> Settings -> Results Page) -->
    <section class="relative bg-slate-950 py-12 sm:py-16 lg:py-20 text-white overflow-hidden border-b border-slate-800">
        <!-- Ambient background lighting -->
        <div class="absolute top-0 left-1/4 w-72 sm:w-96 h-72 sm:h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-72 sm:w-96 h-72 sm:h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-5">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 uppercase tracking-wider shadow-sm">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>{{ $heroBadge }}</span>
            </span>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white max-w-4xl mx-auto leading-tight">
                {{ $heroTitle }}
            </h1>

            <p class="text-slate-400 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed">
                {{ $heroSubtitle }}
            </p>

            <!-- Stat Counters (Fully Responsive: 2 cols on mobile, 4 cols on desktop) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-3xl mx-auto pt-4 sm:pt-6">
                <div class="bg-slate-900/90 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-slate-800 shadow-lg text-center">
                    <span class="text-xl sm:text-3xl font-black text-white block">{{ $totalCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mt-0.5 block">Total Cards</span>
                </div>
                <div class="bg-slate-900/90 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-red-500/25 shadow-lg text-center">
                    <span class="text-xl sm:text-3xl font-black text-red-400 block">{{ $ieltsCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mt-0.5 block">IELTS Cards</span>
                </div>
                <div class="bg-slate-900/90 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-amber-500/25 shadow-lg text-center">
                    <span class="text-xl sm:text-3xl font-black text-amber-400 block">{{ $pteCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mt-0.5 block">PTE Cards</span>
                </div>
                <div class="bg-slate-900/90 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-indigo-500/25 shadow-lg text-center">
                    <span class="text-xl sm:text-3xl font-black text-indigo-400 block">{{ $toeflCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mt-0.5 block">TOEFL Cards</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Portions Filter & Main Cards Grid (Anchor id: results-filter) -->
    <section id="results-filter" class="py-12 sm:py-16 bg-slate-950 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Filter Bar & Search (Instant in-place filtering with ZERO scroll jump) -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-xl">
                <!-- 3 Portion Category Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" @click.prevent="setCategory('ALL')"
                            :class="activeCategory === 'ALL' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800'"
                            class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        All ({{ $totalCount }})
                    </button>

                    <button type="button" @click.prevent="setCategory('IELTS')"
                            :class="activeCategory === 'IELTS' ? 'bg-red-600 text-white shadow-md shadow-red-600/30' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800'"
                            class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-red-400"></span>
                        IELTS ({{ $ieltsCount }})
                    </button>

                    <button type="button" @click.prevent="setCategory('PTE')"
                            :class="activeCategory === 'PTE' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800'"
                            class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        PTE Pearson ({{ $pteCount }})
                    </button>

                    <button type="button" @click.prevent="setCategory('TOEFL')"
                            :class="activeCategory === 'TOEFL' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800'"
                            class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                        TOEFL iBT ({{ $toeflCount }})
                    </button>
                </div>

                <!-- Live Search Box (Instant Live Filtering As You Type - Zero Reload) -->
                <div class="flex items-center gap-2 w-full lg:w-auto">
                    <div class="relative flex-1 lg:w-72">
                        <input type="text"
                               x-model.debounce.150ms="searchQuery"
                               placeholder="Search student name or score..."
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-9 pr-8 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-brand-500">
                        <i data-lucide="search" class="w-4 h-4 text-slate-500 absolute left-3 top-3"></i>
                        <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-2.5 text-slate-400 hover:text-white">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <button type="button" x-show="searchQuery || activeCategory !== 'ALL'"
                            @click="searchQuery = ''; setCategory('ALL');"
                            class="px-3.5 py-2.5 text-xs text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl font-semibold transition shrink-0">
                        Reset
                    </button>
                </div>
            </div>

            <!-- Active Filter Notification Bar -->
            <div class="flex items-center justify-between text-xs text-slate-400 px-1" x-show="activeCategory !== 'ALL' || searchQuery">
                <div>
                    Active Filter:
                    <span class="font-bold text-white" x-text="activeCategory === 'ALL' ? 'All Tests' : activeCategory"></span>
                    <span x-show="searchQuery"> &bull; Searching for: "<span class="text-brand-400 font-semibold" x-text="searchQuery"></span>"</span>
                </div>
                <button type="button" @click="searchQuery = ''; setCategory('ALL');" class="text-brand-400 hover:underline font-semibold">
                    Clear All
                </button>
            </div>

            <!-- Result Cards Grid (Fully Responsive: 1 col on mobile, 2 on sm, 3 on md, 4 on lg) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-6">
                @forelse($allCards as $item)
                    <div class="group bg-slate-900 rounded-2xl border border-slate-800 hover:border-blue-500/50 shadow-xl hover:shadow-2xl hover:shadow-blue-500/10 overflow-hidden transition-all duration-300 flex flex-col justify-between cursor-pointer"
                         x-show="matches('{{ $item->category }}', '{{ addslashes($item->student_name) }}', '{{ $item->overall_band }}')"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         @click="openModal('{{ $item->card_image_url }}', '{{ addslashes($item->student_name) }}', '{{ $item->overall_band }}', '{{ $item->category }}')">

                        <!-- Card Top Bar (Keeps badges separate from the scorecard image so NOTHING is blocked) -->
                        <div class="px-3.5 py-2.5 bg-slate-900 border-b border-slate-800 flex items-center justify-between">
                            @if($item->category === 'IELTS')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-red-600 text-white shadow-sm flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    IELTS
                                </span>
                            @elseif($item->category === 'PTE')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-600 text-white shadow-sm flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    PTE
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-600 text-white shadow-sm flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    TOEFL
                                </span>
                            @endif

                            <div class="px-2.5 py-0.5 rounded-lg text-xs font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/25">
                                {{ $item->category === 'IELTS' ? 'Band ' . $item->overall_band : 'Score ' . $item->overall_band }}
                            </div>
                        </div>

                        <!-- Fixed Standard 4:5 Aspect Ratio Container -->
                        <div class="relative w-full aspect-[4/5] bg-slate-950 overflow-hidden">
                            <img src="{{ $item->card_image_url }}"
                                 alt="{{ $item->student_name }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                <div class="flex items-center justify-between text-xs text-white font-semibold">
                                    <span class="inline-flex items-center gap-1.5 text-emerald-400">
                                        <i data-lucide="zoom-in" class="w-4 h-4"></i> View Scorecard
                                    </span>
                                    <span class="text-slate-400 text-[10px]">800&times;1000px</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-3.5 bg-slate-900 border-t border-slate-800 flex items-center justify-between">
                            <div class="min-w-0 pr-2">
                                <h3 class="font-bold text-white text-sm truncate group-hover:text-blue-400 transition">{{ $item->student_name }}</h3>
                                <p class="text-[11px] text-slate-400 truncate">
                                    {{ $item->test_date ? \Carbon\Carbon::parse($item->test_date)->format('M d, Y') : 'Verified Score' }}
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="text-[10px] text-slate-500 block uppercase font-bold tracking-wider">Overall</span>
                                <span class="text-sm font-extrabold text-white">{{ $item->overall_band }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-slate-900/50 rounded-2xl border border-slate-800 text-slate-400 space-y-2">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto text-slate-600"></i>
                        <h4 class="text-base font-bold text-white">No scorecards found</h4>
                        <p class="text-xs text-slate-500">No student result cards uploaded yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 4. Lightbox Modal for High-Resolution Card View -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/85 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="relative bg-slate-900 border border-slate-800 rounded-3xl p-4 sm:p-6 max-w-lg w-full shadow-2xl flex flex-col items-center max-h-[92vh] overflow-y-auto"
             @click.away="modalOpen = false">
            <div class="w-full flex items-center justify-between pb-3 mb-3 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full text-white"
                          :class="modalCategory === 'IELTS' ? 'bg-red-600' : (modalCategory === 'PTE' ? 'bg-amber-600' : 'bg-indigo-600')"
                          x-text="modalCategory"></span>
                    <h3 class="text-sm sm:text-base font-bold text-white truncate max-w-[220px]" x-text="modalTitle"></h3>
                </div>
                <button type="button" @click="modalOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Image 4:5 -->
            <div class="w-full aspect-[4/5] bg-black rounded-2xl overflow-hidden flex items-center justify-center shadow-inner">
                <img :src="modalImg" alt="Result Card" class="w-full h-full object-contain">
            </div>

            <div class="mt-4 flex items-center justify-between w-full text-xs text-slate-400">
                <div class="flex items-center gap-1.5">
                    <span class="font-bold text-white">Score:</span>
                    <span class="text-emerald-400 font-extrabold text-sm" x-text="modalScore"></span>
                </div>
                <a :href="modalImg" target="_blank" download class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs transition flex items-center gap-1.5">
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
