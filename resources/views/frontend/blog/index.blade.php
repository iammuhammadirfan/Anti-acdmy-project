@extends('layouts.app')

@section('title', 'Academy Insights, IELTS Strategy & Immigration News — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="relative bg-slate-950 py-20 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
            ✍️ Expert Academic Articles
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            Academy <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Insights & News</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Deep-dive examination strategies, lexical breakdowns, immigration score calculators, and global study abroad updates from certified examiners.
        </p>

        <!-- Categories -->
        <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
            <a href="{{ route('blog') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($categorySlug) ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                All Articles
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('blog', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $categorySlug === $cat->slug ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    {{ $cat->name }} ({{ $cat->blogs_count }})
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $post)
                <article class="bg-slate-800 rounded-3xl border border-slate-700/80 overflow-hidden shadow-xl hover:border-blue-500/50 transition-all flex flex-col justify-between group">
                    <div>
                        <a href="{{ route('blog.show', $post->slug) }}" class="block aspect-video bg-slate-950 overflow-hidden relative">
                            <img src="{{ $post->featured_image ?: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @if($post->category)
                                <span class="absolute top-4 left-4 bg-slate-900/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-blue-400 border border-slate-700">
                                    {{ $post->category->name }}
                                </span>
                            @endif
                        </a>
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-slate-400 mb-3">
                                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                                <span>&bull;</span>
                                <span>5 min read</span>
                            </div>
                            <h2 class="text-xl font-bold text-white mb-3 group-hover:text-blue-400 transition-colors line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="text-slate-400 text-xs line-clamp-3 leading-relaxed mb-4">
                                {{ $post->excerpt }}
                            </p>
                        </div>
                    </div>
                    <div class="p-6 pt-0 border-t border-slate-700/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($post->author ? $post->author->name : 'Admin', 0, 1) }}
                            </div>
                            <span class="text-xs text-slate-300 font-medium">{{ $post->author ? $post->author->name : 'Faculty Lead' }}</span>
                        </div>
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-blue-400 group-hover:text-blue-300 inline-flex items-center gap-1">
                            Read Article &rarr;
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    No articles published in this category yet.
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $blogs->links() }}
        </div>
    </div>
</section>
@endsection
