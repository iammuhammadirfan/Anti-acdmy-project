@extends('layouts.admin')

@section('title', 'Student Result Cards (IELTS, PTE, TOEFL)')
@section('header', 'Student Result Cards & Hall of Fame')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, modalImg: '', modalTitle: '' }">
    <!-- Header bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Student Result Cards</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage official result posters and scorecards across IELTS, PTE, and TOEFL with automated 800&times;1000px standardization.</p>
        </div>
        <a href="{{ route('admin.iets.results.create') }}" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all flex items-center gap-2 self-start md:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Upload New Result Card
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Category Tabs & Search Bar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
            @php $currentType = request('type'); @endphp
            <a href="{{ route('admin.iets.results.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ empty($currentType) ? 'bg-brand-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:text-slate-900 border border-slate-200' }}">
                All Results
            </a>
            <a href="{{ route('admin.iets.results.index', ['type' => 'IELTS']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentType === 'IELTS' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:text-slate-900 border border-slate-200' }}">
                IELTS
            </a>
            <a href="{{ route('admin.iets.results.index', ['type' => 'PTE']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentType === 'PTE' ? 'bg-amber-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:text-slate-900 border border-slate-200' }}">
                PTE Pearson
            </a>
            <a href="{{ route('admin.iets.results.index', ['type' => 'TOEFL']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentType === 'TOEFL' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:text-slate-900 border border-slate-200' }}">
                TOEFL iBT
            </a>
        </div>

        <form action="{{ route('admin.iets.results.index') }}" method="GET" class="flex items-center gap-2">
            @if($currentType)
                <input type="hidden" name="type" value="{{ $currentType }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student name or score..." class="bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500 w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Search
            </button>
            @if(request('search') || request('type'))
                <a href="{{ route('admin.iets.results.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-900 transition">Reset</a>
            @endif
        </form>
    </div>

    <!-- Results Table / Cards -->
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Poster Preview</th>
                        <th class="px-6 py-4">Student &amp; Roll</th>
                        <th class="px-6 py-4">Exam &amp; Overall Score</th>
                        <th class="px-6 py-4">Sub-Module Breakdown</th>
                        <th class="px-6 py-4">Test Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $res)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-14 h-18 rounded-lg overflow-hidden border border-slate-200 shadow-xs relative group cursor-pointer"
                                     @click="modalOpen = true; modalImg = '{{ $res->result_image ? asset('storage/' . $res->result_image) : asset('assets/images/placeholder.svg') }}'; modalTitle = '{{ addslashes($res->student_name) }} - {{ $res->test_type }} ({{ $res->overall_score }})'">
                                    <img src="{{ $res->result_image ? asset('storage/' . $res->result_image) : asset('assets/images/placeholder.svg') }}" 
                                         alt="{{ $res->student_name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $res->student_name }}</div>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">Roll: {{ $res->roll_number ?: 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase
                                        @if(str_contains(strtoupper($res->test_type), 'IELTS')) bg-rose-50 text-rose-700 border border-rose-200
                                        @elseif(str_contains(strtoupper($res->test_type), 'PTE')) bg-amber-50 text-amber-700 border border-amber-200
                                        @else bg-indigo-50 text-indigo-700 border border-indigo-200 @endif">
                                        {{ $res->test_type }}
                                    </span>
                                    <span class="font-black text-slate-900 text-base font-mono">
                                        {{ $res->overall_score }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($res->scores_breakdown && is_array($res->scores_breakdown))
                                    <div class="grid grid-cols-2 gap-x-2 gap-y-1 text-[11px] font-mono">
                                        @foreach($res->scores_breakdown as $skill => $score)
                                            <div class="flex items-center justify-between text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">
                                                <span class="font-semibold capitalize text-[10px] text-slate-500">{{ substr($skill, 0, 1) }}:</span>
                                                <span class="font-bold text-slate-900">{{ $score }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-mono text-xs">
                                {{ $res->test_date ? \Carbon\Carbon::parse($res->test_date)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <form action="{{ route('admin.iets.results.toggle', $res) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $res->status ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                            {{ $res->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                    @if($res->is_featured)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 text-center">★ Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.iets.results.edit', $res) }}" class="text-brand-600 hover:text-brand-800 font-bold text-xs">Edit</a>
                                <form action="{{ route('admin.iets.results.destroy', $res) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this result card permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <p class="text-sm font-semibold">No student result scorecards uploaded yet.</p>
                                <p class="text-xs mt-1">Upload 800&times;1000px standardized result banners to showcase on the Hall of Fame.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($results->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $results->links() }}
            </div>
        @endif
    </div>

    <!-- Modal for Full Poster Preview -->
    <div x-show="modalOpen" x-cloak 
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         @click.self="modalOpen = false"
         @keydown.escape.window="modalOpen = false">
        <div class="relative bg-white border border-slate-200 rounded-3xl p-4 max-w-lg w-full shadow-2xl flex flex-col items-center">
            <button @click="modalOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 bg-slate-100 p-1.5 rounded-full transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="font-bold text-slate-900 text-sm mb-3" x-text="modalTitle"></h3>
            <div class="w-full max-h-[75vh] overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center">
                <img :src="modalImg" class="w-full h-full object-contain">
            </div>
        </div>
    </div>
</div>
@endsection
