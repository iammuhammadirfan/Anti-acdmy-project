@extends('layouts.app')

@section('title', $video->title . ' — ' . config('app.name', 'Anti Academy'))

@section('schema_json')
@if(!empty($videoSchema))
<script type="application/ld+json">
{!! json_encode($videoSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
</script>
@endif
@endsection

@section('content')
<section class="py-12 bg-slate-950 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('videos') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white mb-6">
            &larr; Back to Video Vault
        </a>

        <!-- Video Player Card -->
        <div class="bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden shadow-2xl mb-8">
            <div class="aspect-video w-full bg-black relative">
                @if($video->youtube_id)
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $video->youtube_id }}?autoplay=1" title="{{ $video->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-950 p-6 text-center">
                        <img src="{{ $video->thumbnail ?: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $video->title }}" class="absolute inset-0 w-full h-full object-cover opacity-30">
                        <div class="relative z-10">
                            <span class="inline-block p-4 rounded-full bg-red-600 text-white mb-3 shadow-xl">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </span>
                            <h3 class="text-xl font-bold text-white mb-2">{{ $video->title }}</h3>
                            <a href="{{ $video->video_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-lg">
                                Watch on External Platform &rarr;
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 pb-6 mb-6 border-b border-slate-800">
                    <div>
                        <span class="text-xs uppercase font-bold text-red-400 tracking-wider block mb-1">
                            {{ $video->category ? $video->category->name : 'Masterclass' }}
                        </span>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-white">{{ $video->title }}</h1>
                    </div>
                    <div class="text-right text-xs text-slate-400">
                        <div>Published: {{ $video->published_at ? $video->published_at->format('M d, Y') : 'Recent' }}</div>
                        <div class="mt-1 text-slate-500">{{ number_format($video->views_count) }} views</div>
                    </div>
                </div>

                <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed">
                    <p>{{ $video->description }}</p>
                </div>
            </div>
        </div>

        <!-- Related Videos -->
        @if($relatedVideos->isNotEmpty())
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Related Tutorials & Sessions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($relatedVideos as $rel)
                        <a href="{{ route('videos.show', $rel->slug) }}" class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden hover:border-red-500/50 transition-all p-4 group flex flex-col justify-between">
                            <div class="aspect-video rounded-xl bg-slate-950 overflow-hidden mb-3">
                                <img src="{{ $rel->thumbnail ?: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            </div>
                            <h4 class="text-xs font-bold text-white group-hover:text-red-400 transition-colors line-clamp-2">{{ $rel->title }}</h4>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
