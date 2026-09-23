@extends('layouts.admin')

@section('title', 'FAQs')
@section('header', 'Frequently Asked Questions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Manage questions, answers, and category organization for students.</p>
        <a href="{{ route('admin.faqs.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New FAQ
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
                    <th class="px-6 py-4">Question</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($faqs as $f)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4 font-bold text-white max-w-md">
                            <div class="text-sm">{{ $f->question }}</div>
                            <div class="text-[11px] text-slate-500 font-normal line-clamp-1 mt-0.5">{{ $f->answer }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-800 rounded-md border border-slate-700 text-blue-400 font-semibold">{{ ucfirst($f->category) }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 font-mono">{{ $f->display_order }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.faqs.toggle', $f) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $f->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                    {{ $f->status ? 'Active' : 'Hidden' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.faqs.edit', $f) }}" class="text-blue-400 hover:text-blue-300 font-semibold">Edit</a>
                            <form action="{{ route('admin.faqs.destroy', $f) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this FAQ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">No FAQs created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $faqs->links() }}</div>
</div>
@endsection
