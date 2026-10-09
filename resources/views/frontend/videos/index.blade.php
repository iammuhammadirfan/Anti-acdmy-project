@extends('layouts.app')

@section('title', 'Video Masterclasses & Strategy Vlogs — ' . ($globalSettings['academy_name'] ?? config('app.name', 'Academy')))

@section('content')
<!-- Hero Header (Light Gray Background with Gradient Shade) -->
<section class="relative bg-slate-100 py-12 sm:py-16 lg:py-20 text-slate-900 overflow-hidden border-b border-slate-200">
    <!-- Dot Pattern -->
    <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:28px_28px] opacity-40 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-slate-800 border border-slate-200 shadow-xs uppercase tracking-wider">
            <i data-lucide="video" class="w-4 h-4 text-indigo-600"></i>
            <span>Video Learning Vault</span>
        </span>
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 max-w-4xl mx-auto leading-tight">
            Masterclasses &amp; <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Strategy Vlogs</span>
        </h1>
        <p class="text-slate-600 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed">
            Free educational tutorials, IELTS exam breakdowns, speaking trials, and student success narratives recorded by senior faculty.
        </p>

        <!-- Category Filters -->
        <div class="flex flex-wrap items-center justify-center gap-2 pt-4">
            <a href="{{ route('videos') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($categorySlug) ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-300' }}">
                All Videos
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('videos', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $categorySlug === $cat->slug ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-200 border border-slate-300' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Videos Grid Section (Clean Light Surface) -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($videos as $vid)
                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-slate-300 transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <!-- Thumbnail Box with Play Badge -->
                        <a href="{{ route('videos.show', $vid->slug) }}" class="relative block aspect-video bg-slate-100 overflow-hidden">
                            <img src="{{ $vid->thumbnail ?: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop' }}" 
                                 alt="{{ $vid->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                            
                            <!-- Soft Gradient Overlay -->
                            <div class="absolute inset-0 bg-slate-900/20 group-hover:bg-slate-900/40 transition-colors flex items-center justify-center">
                                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-full bg-slate-900/90 backdrop-blur-md text-white flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:bg-indigo-600 transition-all duration-300">
                                    <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>

                            @if($vid->category)
                                <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-md text-slate-800 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200 shadow-xs">
                                    {{ $vid->category->name }}
                                </span>
                            @endif
                        </a>

                        <!-- Card Body -->
                        <div class="p-5 sm:p-6 space-y-2">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('videos.show', $vid->slug) }}">{{ $vid->title }}</a>
                            </h3>
                            <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 leading-relaxed">{{ $vid->description }}</p>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-5 sm:p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                            {{ $vid->published_at ? $vid->published_at->format('M d, Y') : 'Recent' }}
                        </span>
                        <a href="{{ route('videos.show', $vid->slug) }}" class="text-indigo-600 font-bold hover:text-indigo-800 inline-flex items-center gap-1">
                            <span>Watch Session</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-slate-50 rounded-2xl border border-slate-200 text-slate-500 space-y-2">
                    <i data-lucide="film" class="w-12 h-12 mx-auto text-slate-400"></i>
                    <h4 class="text-base font-bold text-slate-800">No videos available</h4>
                    <p class="text-xs text-slate-500">No video masterclasses found in this category.</p>
                </div>
            @endforelse
        </div>

        @if($videos->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $videos->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
