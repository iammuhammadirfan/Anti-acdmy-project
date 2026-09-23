@extends('layouts.app')

@section('title', 'Video Masterclasses & Strategy Vlogs — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="relative bg-slate-950 py-20 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20 mb-4">
            🎥 Video Learning Vault
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            Masterclasses & <span class="bg-gradient-to-r from-red-400 to-rose-300 bg-clip-text text-transparent">Strategy Vlogs</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Free educational tutorials, IELTS exam breakdowns, speaking trials, and student success narratives recorded by senior faculty.
        </p>

        <!-- Category Filters -->
        <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
            <a href="{{ route('videos') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($categorySlug) ? 'bg-red-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                All Videos
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('videos', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $categorySlug === $cat->slug ? 'bg-red-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($videos as $vid)
                <div class="bg-slate-800 rounded-3xl border border-slate-700/80 overflow-hidden shadow-xl hover:border-red-500/50 transition-all group flex flex-col justify-between">
                    <div>
                        <a href="{{ route('videos.show', $vid->slug) }}" class="relative block aspect-video bg-slate-950 overflow-hidden">
                            <img src="{{ $vid->thumbnail ?: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $vid->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-slate-950/30 group-hover:bg-slate-950/10 transition-colors flex items-center justify-center">
                                <div class="w-14 h-14 rounded-full bg-red-600/90 text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </a>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-2 group-hover:text-red-400 transition-colors line-clamp-2">
                                <a href="{{ route('videos.show', $vid->slug) }}">{{ $vid->title }}</a>
                            </h3>
                            <p class="text-slate-400 text-xs line-clamp-3 leading-relaxed">{{ $vid->description }}</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0 border-t border-slate-700/60 flex items-center justify-between text-xs text-slate-400">
                        <span>{{ $vid->published_at ? $vid->published_at->format('M d, Y') : 'Recent' }}</span>
                        <a href="{{ route('videos.show', $vid->slug) }}" class="text-red-400 font-semibold hover:text-red-300">
                            Watch Tutorial &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    No videos available in this category.
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $videos->links() }}
        </div>
    </div>
</section>
@endsection
