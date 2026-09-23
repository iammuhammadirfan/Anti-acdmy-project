@extends('layouts.admin')

@section('title', 'History Timelines')
@section('header', 'Academy Inception & Milestones')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Manage historical milestones, foundation charters, and accreditation years displayed on the History page.</p>
        <a href="{{ route('admin.timelines.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Milestone
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
                    <th class="px-6 py-4">Year</th>
                    <th class="px-6 py-4">Milestone Title</th>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($timelines as $t)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-600/20 text-blue-400 border border-blue-500/30 rounded-lg font-black font-mono text-sm">
                                {{ $t->year }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-white max-w-md">
                            <div class="text-sm">{{ $t->title }}</div>
                            <div class="text-[11px] text-slate-500 font-normal line-clamp-1 mt-0.5">{{ $t->description }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-400 font-mono">{{ $t->display_order }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.timelines.toggle', $t) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $t->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                    {{ $t->status ? 'Active' : 'Hidden' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.timelines.edit', $t) }}" class="text-blue-400 hover:text-blue-300 font-semibold">Edit</a>
                            <form action="{{ route('admin.timelines.destroy', $t) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this timeline milestone?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">No timeline milestones added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
