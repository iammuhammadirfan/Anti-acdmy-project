@extends('layouts.admin')

@section('title', 'Statistics & Social Proof')
@section('page_title', 'Academy Key Performance Metrics')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Statistics &amp; Metrics</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Counters, pass percentages, and achievement numbers displayed across homepage and about pages.</p>
        </div>
        <a href="{{ route('admin.statistics.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Add New Statistic</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($statistics as $stat)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold font-mono text-slate-400">{{ $stat->metric_key }}</span>
                        <form action="{{ route('admin.statistics.toggle', $stat) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded-full transition {{ $stat->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}">
                                {{ $stat->is_active ? 'Active' : 'Disabled' }}
                            </button>
                        </form>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-900 mb-1">
                        {{ $stat->value }}<span class="text-brand-600 font-extrabold">{{ $stat->suffix }}</span>
                    </div>
                    <div class="text-sm font-bold text-slate-700">{{ $stat->label }}</div>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-mono text-[11px]">Order: {{ $stat->display_order }}</span>
                    <div class="space-x-3">
                        <a href="{{ route('admin.statistics.edit', $stat) }}" class="text-brand-600 hover:text-brand-800 font-bold transition">Edit</a>
                        <form action="{{ route('admin.statistics.destroy', $stat) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this metric?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold transition">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-slate-400">No statistics created yet.</div>
        @endforelse
    </div>
</div>
@endsection
