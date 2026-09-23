@extends('layouts.admin')

@section('title', 'IETS Band Results')
@section('header', 'Student Scorecards & Hall of Fame')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Manage verified Cambridge/IDP exam band results and candidate testimonials.</p>
        <a href="{{ route('admin.iets.results.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Student Result
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
                    <th class="px-6 py-4">Student</th>
                    <th class="px-6 py-4">Exam Type</th>
                    <th class="px-6 py-4">Overall Band</th>
                    <th class="px-6 py-4">Detailed Breakdown (L / R / W / S)</th>
                    <th class="px-6 py-4">Featured</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($results as $res)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4 font-bold text-white flex items-center gap-3">
                            <img src="{{ $res->student_image ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop' }}" class="w-8 h-8 rounded-full object-cover border border-slate-700">
                            <div>
                                <div>{{ $res->student_name }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ $res->test_date ? \Carbon\Carbon::parse($res->test_date)->format('M d, Y') : '' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-800 rounded-md border border-slate-700 text-slate-300 font-semibold">{{ $res->test_type }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-lg font-black text-sm">
                                Band {{ number_format($res->overall_band, 1) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-400">
                            {{ $res->listening_score ?? '-' }} / {{ $res->reading_score ?? '-' }} / {{ $res->writing_score ?? '-' }} / {{ $res->speaking_score ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.iets.results.toggle', $res) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $res->is_featured ? 'bg-amber-500/10 text-amber-400 border border-amber-500/30' : 'bg-slate-800 text-slate-500' }}">
                                    {{ $res->is_featured ? '★ Featured' : '☆ Standard' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.iets.results.edit', $res) }}" class="text-blue-400 hover:text-blue-300 font-semibold">Edit</a>
                            <form action="{{ route('admin.iets.results.destroy', $res) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this result?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">No student scorecards added.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $results->links() }}</div>
</div>
@endsection
