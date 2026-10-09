@extends('layouts.admin')

@section('title', 'Campus Gallery')
@section('page_title', 'Campus Gallery Photos')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.gallery.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ empty($category) ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">All</a>
            @foreach(['classroom' => 'Classroom', 'lab' => 'Lab', 'events' => 'Events', 'student_activity' => 'Students', 'library' => 'Library'] as $k => $l)
                <a href="{{ route('admin.gallery.index', ['category' => $k]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $category === $k ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">{{ $l }}</a>
            @endforeach
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all shrink-0 self-start sm:self-auto">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Upload Photo</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($galleries as $item)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <div class="aspect-video bg-slate-100 overflow-hidden relative">
                        <img src="{{ $item->image_path }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-white/90 backdrop-blur-xs text-[10px] font-bold text-slate-800 border border-slate-200 shadow-xs">
                            {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="text-sm font-bold text-slate-900 truncate">{{ $item->title }}</h4>
                        @if($item->caption)
                            <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">{{ $item->caption }}</p>
                        @endif
                    </div>
                </div>
                <div class="p-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <form action="{{ route('admin.gallery.toggle', $item) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-2 py-0.5 rounded-full text-[10px] font-bold transition {{ $item->status ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}">
                            {{ $item->status ? '● Active' : '○ Hidden' }}
                        </button>
                    </form>
                    <div class="space-x-3">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="text-brand-600 hover:text-brand-800 font-bold transition">Edit</a>
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this image?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold transition">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-slate-400">No gallery images uploaded.</div>
        @endforelse
    </div>

    <div>{{ $galleries->links() }}</div>
</div>
@endsection
