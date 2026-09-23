@extends('layouts.admin')

@section('title', 'Campus Gallery')
@section('header', 'Campus Gallery Photos')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.gallery.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($category) ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-300' }}">All</a>
            @foreach(['classroom' => 'Classroom', 'lab' => 'Lab', 'events' => 'Events', 'student_activity' => 'Students', 'library' => 'Library'] as $k => $l)
                <a href="{{ route('admin.gallery.index', ['category' => $k]) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $category === $k ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-300' }}">{{ $l }}</a>
            @endforeach
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Upload Photo
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($galleries as $item)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl flex flex-col justify-between group">
                <div>
                    <div class="aspect-video bg-slate-950 overflow-hidden relative">
                        <img src="{{ $item->image_path }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-slate-950/80 text-[10px] font-bold text-blue-400 border border-slate-700">
                            {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="text-sm font-bold text-white truncate">{{ $item->title }}</h4>
                        @if($item->caption)
                            <p class="text-[11px] text-slate-400 line-clamp-2 mt-1">{{ $item->caption }}</p>
                        @endif
                    </div>
                </div>
                <div class="p-4 pt-0 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <form action="{{ route('admin.gallery.toggle', $item) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-[10px] font-bold {{ $item->status ? 'text-emerald-400' : 'text-slate-500' }}">
                            {{ $item->status ? '● Active' : '○ Hidden' }}
                        </button>
                    </form>
                    <div class="space-x-2">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="text-blue-400 hover:text-blue-300 font-semibold">Edit</a>
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this image?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12 text-slate-500">No gallery images uploaded.</div>
        @endforelse
    </div>

    <div>{{ $galleries->links() }}</div>
</div>
@endsection
