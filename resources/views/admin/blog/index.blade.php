@extends('layouts.admin')

@section('title', 'Blog & Articles')
@section('page_title', 'Academy Articles & Editorial')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Blog &amp; News Articles</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Publish pedagogical articles, IELTS exam breakdowns, and study abroad insights.</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Write New Article</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-left text-xs text-slate-700">
            <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200 text-[11px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Article</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Author</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($blogs as $post)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-3">
                            <img src="{{ $post->featured_image ?: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=100&auto=format&fit=crop' }}" class="w-12 h-8 rounded-lg object-cover border border-slate-200 shadow-2xs">
                            <div>
                                <div class="text-sm font-bold text-slate-900 line-clamp-1">{{ $post->title }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-100 rounded-md border border-slate-200 text-slate-700 font-semibold text-[11px]">{{ $post->category ? $post->category->name : 'Uncategorized' }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $post->author ? $post->author->name : 'Admin' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $post->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="text-brand-600 hover:text-brand-800 font-bold transition">Edit</a>
                            <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this article?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold transition">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">No blog posts drafted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $blogs->links() }}</div>
</div>
@endsection
