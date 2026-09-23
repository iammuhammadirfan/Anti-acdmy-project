@extends('layouts.admin')

@section('title', 'Blog & Articles')
@section('header', 'Academy Articles & Editorial')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Publish pedagogical articles, IELTS exam breakdowns, and study abroad insights.</p>
        <a href="{{ route('admin.blog.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Write New Article
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950/80 text-slate-400 uppercase font-semibold border-b border-slate-800">
                <tr>
                    <th class="px-6 py-4">Article</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Author</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($blogs as $post)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4 font-bold text-white flex items-center gap-3">
                            <img src="{{ $post->featured_image ?: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=100&auto=format&fit=crop' }}" class="w-12 h-8 rounded-lg object-cover border border-slate-700">
                            <div>
                                <div class="text-sm line-clamp-1">{{ $post->title }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-800 rounded-md border border-slate-700 text-blue-400 font-semibold">{{ $post->category ? $post->category->name : 'Uncategorized' }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ $post->author ? $post->author->name : 'Admin' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $post->status === 'published' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/10 text-amber-400 border border-amber-500/30' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="text-blue-400 hover:text-blue-300 font-semibold">Edit</a>
                            <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this article?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">No blog posts drafted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $blogs->links() }}</div>
</div>
@endsection
