@extends('layouts.app')

@section('title', $video->title . ' — ' . ($globalSettings['academy_name'] ?? config('app.name', 'Academy')))

@section('schema_json')
@if(!empty($videoSchema))
<script type="application/ld+json">
{!! json_encode($videoSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
</script>
@endif
@endsection

@section('content')
<section class="py-12 bg-slate-50 text-slate-900 border-b border-slate-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('videos') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 mb-6 bg-white px-3.5 py-1.5 rounded-xl border border-slate-200 shadow-xs transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Back to Video Vault</span>
        </a>

        <!-- Video Player Card (Clean Light Style) -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm mb-10">
            <div class="aspect-video w-full bg-slate-900 relative">
                @if($video->youtube_id)
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $video->youtube_id }}?autoplay=1" title="{{ $video->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-900 p-6 text-center text-white">
                        <img src="{{ $video->thumbnail ?: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $video->title }}" class="absolute inset-0 w-full h-full object-cover opacity-30">
                        <div class="relative z-10 space-y-3">
                            <span class="inline-block p-4 rounded-full bg-slate-800/90 text-white shadow-xl">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </span>
                            <h3 class="text-xl font-bold">{{ $video->title }}</h3>
                            <a href="{{ $video->video_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow">
                                <span>Watch on External Platform</span>
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 pb-6 mb-6 border-b border-slate-100">
                    <div>
                        <span class="text-xs uppercase font-extrabold text-indigo-600 tracking-wider block mb-1">
                            {{ $video->category ? $video->category->name : 'Masterclass' }}
                        </span>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">{{ $video->title }}</h1>
                    </div>
                    <div class="text-right text-xs text-slate-500 space-y-1">
                        <div>Published: {{ $video->published_at ? $video->published_at->format('M d, Y') : 'Recent' }}</div>
                        <div class="text-slate-400">{{ number_format($video->views_count) }} views</div>
                    </div>
                </div>

                <div class="prose prose-slate max-w-none text-slate-600 text-sm sm:text-base leading-relaxed">
                    <p>{{ $video->description }}</p>
                </div>
            </div>
        </div>

        <!-- Related Videos -->
        @if($relatedVideos->isNotEmpty())
            <div class="space-y-4">
                <h3 class="text-xl font-extrabold text-slate-900">Related Tutorials &amp; Sessions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($relatedVideos as $rel)
                        <a href="{{ route('videos.show', $rel->slug) }}" class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-slate-300 shadow-xs hover:shadow-sm transition-all p-3.5 group flex flex-col justify-between">
                            <div class="aspect-video rounded-xl bg-slate-100 overflow-hidden mb-3">
                                <img src="{{ $rel->thumbnail ?: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform">
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug">{{ $rel->title }}</h4>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
