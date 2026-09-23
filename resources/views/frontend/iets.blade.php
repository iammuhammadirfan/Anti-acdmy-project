@extends('layouts.app')

@section('title', 'IETS Preparation Programs & Courses — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="relative bg-slate-950 py-20 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
            🎓 Cambridge & IDP Standardized Curricula
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            Elite <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">IETS Training Programs</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Choose from comprehensive full-length tracks, fast-track Express Entry immigration modules, or emergency 30-day bootcamps designed by certified examiners.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-12">
            @forelse($programs as $prog)
                <div class="bg-slate-800 rounded-3xl border border-slate-700/80 overflow-hidden shadow-2xl hover:border-blue-500/50 transition-all flex flex-col lg:flex-row">
                    <div class="lg:w-2/5 relative h-72 lg:h-auto overflow-hidden bg-slate-950">
                        <img src="{{ $prog->image ?: 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800&auto=format&fit=crop' }}" alt="{{ $prog->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent lg:hidden"></div>
                        <div class="absolute top-4 left-4 bg-blue-600/90 backdrop-blur-md px-3.5 py-1.5 rounded-full text-xs font-bold text-white shadow-md">
                            Category: {{ ucfirst(str_replace('_', ' ', $prog->category)) }}
                        </div>
                    </div>
                    <div class="lg:w-3/5 p-8 lg:p-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-xs uppercase tracking-wider text-emerald-400 font-bold bg-emerald-500/10 px-2.5 py-0.5 rounded-md border border-emerald-500/20">
                                    Official Curriculum
                                </span>
                            </div>
                            <h2 class="text-2xl lg:text-3xl font-extrabold text-white mb-3">{{ $prog->title }}</h2>
                            <p class="text-slate-300 text-sm leading-relaxed mb-6">{{ $prog->summary }}</p>
                            
                            @if($prog->features && count($prog->features) > 0)
                                <div class="mb-6">
                                    <h4 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-3">Key Program Features & Inclusions:</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        @foreach($prog->features as $feat)
                                            <div class="flex items-start gap-2 text-xs text-slate-300">
                                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span>{{ $feat }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="pt-6 border-t border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs text-slate-400 block">Class Formats:</span>
                                <span class="text-sm font-semibold text-white">Hybrid (On-Campus Labs + Online LMS)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('appointments') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm text-center shadow-lg transition-all">
                                    Enroll or Book Assessment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-400">
                    No programs active currently.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Hall of Fame Preview -->
@if(isset($results) && count($results) > 0)
<section class="py-16 bg-slate-950 text-white border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Verified Triumphs</span>
                <h2 class="text-3xl font-bold mt-1">Recent Student Band Scores</h2>
            </div>
            <a href="{{ route('iets.results') }}" class="text-sm font-semibold text-blue-400 hover:text-blue-300 mt-2 md:mt-0">
                View All Hall of Fame Results &rarr;
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($results as $res)
                <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="{{ $res->student_image }}" alt="{{ $res->student_name }}" class="w-12 h-12 rounded-full object-cover border border-blue-500/50">
                            <div>
                                <h4 class="font-bold text-sm text-white">{{ $res->student_name }}</h4>
                                <span class="text-xs text-slate-400">{{ $res->test_type }}</span>
                            </div>
                        </div>
                        <div class="bg-blue-950/60 p-3 rounded-xl border border-blue-800/40 text-center mb-3">
                            <span class="text-xs uppercase text-slate-400 font-semibold block">Overall Band</span>
                            <span class="text-3xl font-extrabold text-blue-400">{{ number_format($res->overall_band, 1) }}</span>
                        </div>
                        <p class="text-xs text-slate-300 italic">"{{ $res->description }}"</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
