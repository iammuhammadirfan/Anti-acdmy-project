@extends('layouts.app')

@section('title', 'Smart Classrooms & Audio-Visual Labs — ' . ($globalSettings['academy_name'] ?? config('app.name', 'Academy')))

@section('content')
<section class="relative bg-slate-100 py-16 text-slate-900 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white text-slate-800 border border-slate-200 shadow-xs">
            🏢 World-Class Infrastructure
        </span>
        <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-slate-900">
            State-of-the-Art Smart Facilities
        </h1>
        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto">
            Experience soundproof simulation booths, computer-delivered acoustic testing labs, and collaborative workshop suites built to official Cambridge and British Council specifications.
        </p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($classrooms as $room)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col group">
                    <div class="relative h-64 overflow-hidden bg-slate-100">
                        <img src="{{ $room->primary_image }}" alt="{{ $room->title }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-slate-900 border border-slate-200 shadow-xs">
                            {{ $room->class_type }} Suite
                        </div>
                        <div class="absolute top-4 right-4 bg-slate-900 text-white px-3 py-1 rounded-full text-xs font-bold shadow-xs">
                            Capacity: {{ $room->capacity }} Scholars
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ $room->title }}</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-4">{{ $room->description }}</p>
                            
                            @if($room->facilities && count($room->facilities) > 0)
                                <div class="mb-4">
                                    <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-2">Equipped Facilities:</div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($room->facilities as $fac)
                                            <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md border border-slate-200">
                                                <i data-lucide="check" class="w-3.5 h-3.5 text-slate-900"></i>
                                                {{ $fac }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500">Available for Active Course Enrollees</span>
                            <a href="{{ route('appointments') }}" class="text-xs font-bold text-slate-900 hover:underline inline-flex items-center gap-1">
                                <span>Book In-Person Tour</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
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
<section class="bg-slate-100 py-16 text-slate-900 text-center border-t border-slate-200">
    <div class="max-w-4xl mx-auto px-4 space-y-4">
        <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Want to Tour Our Campus and Digital Labs in Person?</h2>
        <p class="text-slate-600 text-sm max-w-xl mx-auto">Schedule a 30-minute campus walk-through with our academic counselors and experience our simulation suites firsthand.</p>
        <div class="pt-2">
            <a href="{{ route('appointments') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3.5 rounded-xl shadow transition transform hover:-translate-y-0.5">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Book Campus Tour Now</span>
            </a>
        </div>
    </div>
</section>
@endsection
