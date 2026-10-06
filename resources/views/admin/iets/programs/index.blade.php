@extends('layouts.admin')

@section('title', 'Classes & Test Timings')
@section('page_title', 'Classes & Test Timings Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Classes, Tests &amp; 3-Slot Batch Timings</h3>
            <p class="text-xs text-slate-500">Add training classes or mock tests and configure their 3 timing batch slots (Morning 9-12, Midday 11-2, Evening 4-7).</p>
        </div>
        <a href="{{ route('admin.iets.programs.create') }}" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all flex items-center gap-1.5 self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add New Class / Test</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Class / Test Title</th>
                        <th class="px-6 py-4">3 Batch Timings</th>
                        <th class="px-6 py-4">Badge / Days</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($programs as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">
                                <div class="text-sm font-bold text-slate-900">{{ $p->title }}</div>
                                <div class="text-[11px] text-slate-500 font-normal mt-0.5 line-clamp-1">{{ $p->summary ?: 'Standard preparation curriculum' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1 text-[11px]">
                                    <div class="flex items-center gap-1.5 text-amber-700 font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>{{ $p->timing_slot_1_name ?? 'Morning' }}:</span>
                                        <span class="text-slate-800 font-mono">{{ $p->timing_slot_1_time ?? '09:00 AM - 12:00 PM' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-sky-700 font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                        <span>{{ $p->timing_slot_2_name ?? 'Midday' }}:</span>
                                        <span class="text-slate-800 font-mono">{{ $p->timing_slot_2_time ?? '11:00 AM - 02:00 PM' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-indigo-700 font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        <span>{{ $p->timing_slot_3_name ?? 'Evening' }}:</span>
                                        <span class="text-slate-800 font-mono">{{ $p->timing_slot_3_time ?? '04:00 PM - 07:00 PM' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if(!empty($p->badge))
                                    <span class="px-2.5 py-1 bg-brand-50 rounded-md border border-brand-200 text-brand-700 font-semibold text-[11px]">{{ $p->badge }}</span>
                                @else
                                    <span class="text-slate-400 text-[11px]">Mon – Sat Batches</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono font-bold">{{ $p->display_order }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.iets.programs.toggle', $p) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $p->status ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                        {{ $p->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap">
                                <a href="{{ route('admin.iets.programs.edit', $p) }}" class="text-brand-600 hover:text-brand-800 font-bold">Edit</a>
                                <form action="{{ route('admin.iets.programs.destroy', $p) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this class / test schedule?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                <i data-lucide="calendar-off" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                <p>No classes or test schedules added yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($programs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
