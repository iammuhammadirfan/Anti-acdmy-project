@extends('layouts.admin')

@section('title', 'History Timelines')
@section('page_title', 'Academy Inception & Milestones')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Timeline History</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Manage historical milestones, foundation charters, and accreditation years displayed on the History page.</p>
        </div>
        <a href="{{ route('admin.timelines.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Add New Milestone</span>
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
                    <th class="px-6 py-4">Year</th>
                    <th class="px-6 py-4">Milestone Title</th>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($timelines as $t)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-brand-50 text-brand-700 border border-brand-200 rounded-lg font-extrabold font-mono text-xs">
                                {{ $t->year }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900 max-w-md">
                            <div class="text-sm font-bold text-slate-900">{{ $t->title }}</div>
                            <div class="text-[11px] text-slate-400 font-normal line-clamp-1 mt-0.5">{{ $t->description }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono text-xs font-semibold">{{ $t->display_order }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.timelines.toggle', $t) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $t->status ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}">
                                    {{ $t->status ? 'Active' : 'Hidden' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.timelines.edit', $t) }}" class="text-brand-600 hover:text-brand-800 font-bold transition">Edit</a>
                            <form action="{{ route('admin.timelines.destroy', $t) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this timeline milestone?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold transition">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">No timeline milestones added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
