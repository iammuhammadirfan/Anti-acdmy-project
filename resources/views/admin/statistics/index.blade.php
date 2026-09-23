@extends('layouts.admin')

@section('title', 'Statistics & Social Proof')
@section('header', 'Academy Key Performance Metrics')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Counters, pass percentages, and achievement numbers displayed across homepage and about pages.</p>
        <a href="{{ route('admin.statistics.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Statistic
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($statistics as $stat)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold font-mono text-slate-500">{{ $stat->metric_key }}</span>
                        <form action="{{ route('admin.statistics.toggle', $stat) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $stat->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                {{ $stat->is_active ? 'Active' : 'Disabled' }}
                            </button>
                        </form>
                    </div>
                    <div class="text-3xl font-black text-white mb-1">
                        {{ $stat->value }}<span class="text-blue-400 font-bold">{{ $stat->suffix }}</span>
                    </div>
                    <div class="text-sm font-bold text-slate-300">{{ $stat->label }}</div>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-mono">Order: {{ $stat->display_order }}</span>
                    <div class="space-x-2">
                        <a href="{{ route('admin.statistics.edit', $stat) }}" class="text-blue-400 hover:text-blue-300 font-semibold">Edit</a>
                        <form action="{{ route('admin.statistics.destroy', $stat) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this metric?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12 text-slate-500">No statistics created yet.</div>
        @endforelse
    </div>
</div>
@endsection
