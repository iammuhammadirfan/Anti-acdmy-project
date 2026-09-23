@extends('layouts.app')

@section('title', 'IETS Student Band Results & Hall of Fame — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="relative bg-slate-950 py-20 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 mb-4">
            🏆 Verified Cambridge & IDP Scorecards
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            Student <span class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">Hall of Fame</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Explore authentic band achievements from Anti Academy candidates now studying or practicing across the globe.
        </p>

        <!-- Band Filter -->
        <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
            <a href="{{ route('iets.results') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($band) ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                All Scores
            </a>
            <a href="{{ route('iets.results', ['band' => '8.5']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $band === '8.5' ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                Band 8.5+ Elite
            </a>
            <a href="{{ route('iets.results', ['band' => '8.0']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $band === '8.0' ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                Band 8.0 & Above
            </a>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($results as $item)
                <div class="bg-slate-800 rounded-3xl border border-slate-700/80 p-6 flex flex-col justify-between shadow-xl hover:border-emerald-500/50 transition-all">
                    <div>
                        <div class="flex items-center gap-4 mb-5">
                            <img src="{{ $item->student_image ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop' }}" alt="{{ $item->student_name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-emerald-400">
                            <div>
                                <h3 class="font-bold text-lg text-white">{{ $item->student_name }}</h3>
                                <span class="text-xs bg-slate-700/80 text-slate-300 px-2.5 py-0.5 rounded-full inline-block mt-0.5">{{ $item->test_type }}</span>
                            </div>
                        </div>

                        <!-- Score Grid -->
                        <div class="bg-slate-950/70 rounded-2xl p-4 border border-slate-700/50 mb-5">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-800">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Overall Band</span>
                                <span class="text-3xl font-black text-emerald-400">{{ number_format($item->overall_band, 1) }}</span>
                            </div>
                            <div class="grid grid-cols-4 gap-2 text-center">
                                <div class="bg-slate-900 p-2 rounded-lg">
                                    <span class="text-[10px] text-slate-400 block uppercase">List</span>
                                    <span class="text-sm font-bold text-white">{{ number_format($item->listening_score ?? 0, 1) }}</span>
                                </div>
                                <div class="bg-slate-900 p-2 rounded-lg">
                                    <span class="text-[10px] text-slate-400 block uppercase">Read</span>
                                    <span class="text-sm font-bold text-white">{{ number_format($item->reading_score ?? 0, 1) }}</span>
                                </div>
                                <div class="bg-slate-900 p-2 rounded-lg">
                                    <span class="text-[10px] text-slate-400 block uppercase">Writ</span>
                                    <span class="text-sm font-bold text-white">{{ number_format($item->writing_score ?? 0, 1) }}</span>
                                </div>
                                <div class="bg-slate-900 p-2 rounded-lg">
                                    <span class="text-[10px] text-slate-400 block uppercase">Speak</span>
                                    <span class="text-sm font-bold text-white">{{ number_format($item->speaking_score ?? 0, 1) }}</span>
                                </div>
                            </div>
                        </div>

                        @if($item->description)
                            <p class="text-slate-300 text-xs italic leading-relaxed mb-4">
                                "{{ $item->description }}"
                            </p>
                        @endif
                    </div>

                    @if($item->test_date)
                        <div class="pt-3 border-t border-slate-700/60 flex items-center justify-between text-[11px] text-slate-400">
                            <span>Exam Date: {{ \Carbon\Carbon::parse($item->test_date)->format('M d, Y') }}</span>
                            <span class="text-emerald-400 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Verified
                            </span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    No results matched your search filter.
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $results->links() }}
        </div>
    </div>
</section>
@endsection
