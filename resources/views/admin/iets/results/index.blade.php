@extends('layouts.admin')

@section('title', 'Student Result Cards (IELTS, PTE, TOEFL)')
@section('header', 'Student Result Cards & Hall of Fame')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, modalImg: '', modalTitle: '' }">
    <!-- Header bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Student Result Cards</h1>
            <p class="text-xs text-slate-400 mt-0.5">Manage official result posters and scorecards across IELTS, PTE, and TOEFL with automated 800&times;1000px standardization.</p>
        </div>
        <a href="{{ route('admin.iets.results.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-2 self-start md:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Upload New Result Card
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Category Tabs & Search Bar -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
            @php $currentType = request('type'); @endphp
            <a href="{{ route('admin.iets.results.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ empty($currentType) ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800' }}">
                All Results
            </a>
            <a href="{{ route('admin.iets.results.index', ['type' => 'IELTS']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentType === 'IELTS' ? 'bg-red-600 text-white shadow-md' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800' }}">
                IELTS
            </a>
            <a href="{{ route('admin.iets.results.index', ['type' => 'PTE']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentType === 'PTE' ? 'bg-amber-600 text-white shadow-md' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800' }}">
                PTE Pearson
            </a>
            <a href="{{ route('admin.iets.results.index', ['type' => 'TOEFL']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentType === 'TOEFL' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800' }}">
                TOEFL iBT
            </a>
        </div>

        <form action="{{ route('admin.iets.results.index') }}" method="GET" class="flex items-center gap-2">
            @if($currentType)
                <input type="hidden" name="type" value="{{ $currentType }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student name or score..." class="bg-slate-950 border border-slate-800 rounded-xl pl-9 pr-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 w-64">
                <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold rounded-xl transition">
                Search
            </button>
            @if(request('search') || request('type'))
                <a href="{{ route('admin.iets.results.index') }}" class="px-3 py-2 text-xs text-slate-400 hover:text-white transition">Reset</a>
            @endif
        </form>
    </div>

    <!-- Results Table / Cards -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/80 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Card Preview (4:5)</th>
                        <th class="px-6 py-4">Student Name</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Score / Band</th>
                        <th class="px-6 py-4">Exam Date</th>
                        <th class="px-6 py-4 text-center">Slider Featured</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($results as $res)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-3">
                                <button type="button" @click="modalImg = '{{ $res->card_image_url }}'; modalTitle = '{{ addslashes($res->student_name) }} ({{ $res->category }})'; modalOpen = true" class="relative group block w-14 aspect-[4/5] rounded-xl overflow-hidden border border-slate-700 hover:border-blue-400 transition shadow-sm bg-slate-950">
                                    <img src="{{ $res->card_image_url }}" alt="{{ $res->student_name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </div>
                                </button>
                            </td>

                            <td class="px-6 py-4 font-bold text-white">
                                <div class="text-sm">{{ $res->student_name }}</div>
                                @if($res->description)
                                    <div class="text-[11px] text-slate-400 font-normal line-clamp-1 mt-0.5">{{ $res->description }}</div>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($res->category === 'IELTS')
                                    <span class="px-2.5 py-1 bg-red-500/15 text-red-400 border border-red-500/30 rounded-lg font-extrabold text-[11px] inline-flex items-center gap-1">
                                        🇬🇧 IELTS
                                    </span>
                                @elseif($res->category === 'PTE')
                                    <span class="px-2.5 py-1 bg-amber-500/15 text-amber-400 border border-amber-500/30 rounded-lg font-extrabold text-[11px] inline-flex items-center gap-1">
                                        🇦🇺 PTE
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 rounded-lg font-extrabold text-[11px] inline-flex items-center gap-1">
                                        🇺🇸 TOEFL
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-lg font-black text-sm">
                                    {{ $res->overall_band }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-slate-400 text-xs">
                                {{ $res->test_date ? \Carbon\Carbon::parse($res->test_date)->format('M d, Y') : '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.iets.results.toggle', $res) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-full text-[10px] font-bold transition {{ $res->is_featured ? 'bg-amber-500/15 text-amber-400 border border-amber-500/30 hover:bg-amber-500/25' : 'bg-slate-800 text-slate-500 border border-slate-700 hover:text-slate-300' }}">
                                        {{ $res->is_featured ? '★ In Slider' : '☆ Hidden' }}
                                    </button>
                                </form>
                            </td>

                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.iets.results.edit', $res) }}" class="inline-flex items-center gap-1 text-blue-400 hover:text-blue-300 font-semibold px-2.5 py-1 bg-blue-500/10 rounded-lg hover:bg-blue-500/20 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.iets.results.destroy', $res) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this result card?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-rose-400 hover:text-rose-300 font-semibold px-2.5 py-1 bg-rose-500/10 rounded-lg hover:bg-rose-500/20 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="w-12 h-12 mx-auto rounded-full bg-slate-800/80 flex items-center justify-center text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="text-sm font-semibold block text-slate-400">No result cards found</span>
                                <p class="text-xs text-slate-600 mt-1">Upload the first IELTS, PTE, or TOEFL scorecard using the button above.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $results->links() }}</div>

    <!-- Modal for Full-Size Card Preview -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="relative bg-slate-900 border border-slate-800 rounded-3xl p-4 max-w-lg w-full shadow-2xl flex flex-col items-center"
             @click.away="modalOpen = false">
            <div class="w-full flex items-center justify-between pb-3 mb-3 border-b border-slate-800">
                <h3 class="text-sm font-bold text-white" x-text="modalTitle"></h3>
                <button type="button" @click="modalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="w-full aspect-[4/5] bg-black rounded-2xl overflow-hidden flex items-center justify-center">
                <img :src="modalImg" alt="Result Card" class="w-full h-full object-contain">
            </div>
            <div class="mt-3 flex items-center justify-between w-full text-xs text-slate-400">
                <span>Standardized Resolution: 800&times;1000 px</span>
                <a :href="modalImg" target="_blank" download class="text-blue-400 hover:underline flex items-center gap-1 font-semibold">
                    Open Original Image &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
