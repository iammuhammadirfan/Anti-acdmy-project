@extends('layouts.app')

@section('title', 'Smart Classrooms & Audio-Visual Labs — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="relative bg-slate-950 py-20 text-white overflow-hidden">
    <div class="absolute inset-0 bg-radial-gradient from-blue-900/30 via-slate-950 to-slate-950"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
            🏢 World-Class Infrastructure
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            State-of-the-Art <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Smart Facilities</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Experience soundproof simulation booths, computer-delivered acoustic testing labs, and collaborative workshop suites built to official Cambridge and British Council specifications.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($classrooms as $room)
                <div class="bg-slate-800/80 rounded-2xl border border-slate-700 overflow-hidden shadow-xl hover:border-blue-500/50 transition-all flex flex-col group">
                    <div class="relative h-64 overflow-hidden bg-slate-950">
                        @php
                            $img = is_array($room->images) && count($room->images) > 0 ? $room->images[0] : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=800&auto=format&fit=crop';
                        @endphp
                        <img src="{{ $img }}" alt="{{ $room->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-4 left-4 bg-slate-900/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-blue-400 border border-slate-700">
                            {{ $room->class_type }} Suite
                        </div>
                        <div class="absolute top-4 right-4 bg-slate-900/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-emerald-400 border border-slate-700">
                            Capacity: {{ $room->capacity }} Scholars
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $room->title }}</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-4">{{ $room->description }}</p>
                            
                            @if($room->facilities && count($room->facilities) > 0)
                                <div class="mb-4">
                                    <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-2">Equipped Facilities:</div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($room->facilities as $fac)
                                            <span class="inline-flex items-center gap-1 text-xs bg-slate-700/60 text-slate-300 px-2.5 py-1 rounded-md border border-slate-600/50">
                                                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                {{ $fac }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="pt-4 border-t border-slate-700/60 flex items-center justify-between">
                            <span class="text-xs text-slate-400">Available for Active Course Enrollees</span>
                            <a href="{{ route('appointments') }}" class="text-xs font-semibold text-blue-400 hover:text-blue-300 inline-flex items-center gap-1">
                                Book In-Person Tour &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 text-slate-400">
                    No classroom details published at this moment.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Call to action -->
<section class="bg-gradient-to-r from-blue-900 to-indigo-900 py-12 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold mb-3">Want to Tour Our Campus and Digital Labs in Person?</h2>
        <p class="text-blue-200 text-sm mb-6">Schedule a 30-minute campus walk-through with our academic counselors and experience our simulation suites firsthand.</p>
        <a href="{{ route('appointments') }}" class="inline-flex items-center gap-2 bg-white text-blue-950 font-bold px-6 py-3 rounded-xl shadow-lg hover:bg-blue-50 transition-colors">
            Book Campus Tour Now
        </a>
    </div>
</section>
@endsection
