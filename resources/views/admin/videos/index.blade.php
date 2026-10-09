@extends('layouts.admin')

@section('title', 'Videos & Vlogs')
@section('page_title', 'Video Vault & Masterclasses')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Videos &amp; Vlogs</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Manage educational tutorials, strategy breakdowns, and YouTube video embeds.</p>
        </div>
        <a href="{{ route('admin.videos.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Add New Video</span>
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
                    <th class="px-6 py-4">Video Title</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">YouTube ID</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($videos as $vid)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-3">
                            <img src="{{ $vid->thumbnail ?: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=100&auto=format&fit=crop' }}" class="w-12 h-8 rounded-lg object-cover border border-slate-200 shadow-2xs">
                            <div>
                                <div class="text-sm font-bold text-slate-900 line-clamp-1">{{ $vid->title }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">{{ $vid->slug }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-100 rounded-md border border-slate-200 text-slate-700 font-semibold text-[11px]">{{ $vid->category ? $vid->category->name : 'Uncategorized' }}</span>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-600 font-medium">{{ $vid->youtube_id ?: 'Direct Link' }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.videos.toggle', $vid) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $vid->status ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}">
                                    {{ $vid->status ? 'Published' : 'Hidden' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.videos.edit', $vid) }}" class="text-brand-600 hover:text-brand-800 font-bold transition">Edit</a>
                            <form action="{{ route('admin.videos.destroy', $vid) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this video?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold transition">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">No videos uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $videos->links() }}</div>
</div>
@endsection
