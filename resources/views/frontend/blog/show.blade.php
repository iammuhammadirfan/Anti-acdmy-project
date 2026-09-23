@extends('layouts.app')

@section('title', $blog->title . ' — ' . config('app.name', 'Anti Academy'))

@section('schema_json')
@if(!empty($articleSchema))
<script type="application/ld+json">
{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
</script>
@endif
@endsection

@section('content')
<article class="py-12 bg-slate-950 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white mb-6">
            &larr; Back to All Articles
        </a>

        <!-- Article Header -->
        <header class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                @if($blog->category)
                    <span class="text-xs uppercase font-bold text-blue-400 bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20">
                        {{ $blog->category->name }}
                    </span>
                @endif
                <span class="text-xs text-slate-400">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recent' }}</span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs text-slate-400">Examiner Verified</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-6">
                {{ $blog->title }}
            </h1>
            <p class="text-lg text-slate-300 leading-relaxed font-normal mb-8">
                {{ $blog->excerpt }}
            </p>

            <div class="flex items-center justify-between py-4 border-y border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white shadow-md">
                        {{ substr($blog->author ? $blog->author->name : 'Admin', 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white">{{ $blog->author ? $blog->author->name : 'Faculty Lead' }}</div>
                        <div class="text-xs text-slate-400">Senior IELTS Examiner & Academic Mentor</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        @if($blog->featured_image)
            <div class="aspect-video w-full rounded-3xl overflow-hidden bg-slate-900 border border-slate-800 shadow-2xl mb-10">
                <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Body Content -->
        <div class="prose prose-invert prose-blue max-w-none text-slate-300 leading-loose text-base mb-12">
            {!! $blog->content !!}
        </div>

        <!-- Tags -->
        @if($blog->tags && count($blog->tags) > 0)
            <div class="pt-6 border-t border-slate-800 mb-12">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Tagged Under:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($blog->tags as $t)
                        <span class="text-xs bg-slate-900 border border-slate-700 text-blue-400 px-3 py-1 rounded-lg">#{{ $t->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Posts -->
        @if($relatedBlogs->isNotEmpty())
            <div class="pt-10 border-t border-slate-800">
                <h3 class="text-2xl font-bold text-white mb-6">Related Academic Guides</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedBlogs as $rel)
                        <a href="{{ route('blog.show', $rel->slug) }}" class="bg-slate-900 rounded-2xl border border-slate-800 p-5 hover:border-blue-500/50 transition-all flex flex-col justify-between group">
                            <div>
                                <h4 class="font-bold text-sm text-white group-hover:text-blue-400 transition-colors line-clamp-2 mb-2">{{ $rel->title }}</h4>
                                <p class="text-slate-400 text-xs line-clamp-2">{{ $rel->excerpt }}</p>
                            </div>
                            <span class="text-xs text-blue-400 font-semibold mt-4 block">Read More &rarr;</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection
