@extends('layouts.app')

@section('title', 'Academic Faculty & Instructors — Apex Academy')
@section('meta_description', 'Meet our team of certified educators, IELTS examiners, and academic counselors at Apex Academy & IETS.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <!-- Header -->
    <div class="text-center space-y-3">
        <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
            Faculty Directory
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Our Academic Mentors &amp; Trainers</h1>
        <p class="text-sm text-slate-600 max-w-xl mx-auto">Explore certified instructors dedicated to your linguistic mastery, band score elevation, and academic growth.</p>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($teachers as $teacher)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-xl transition transform hover:-translate-y-1">
                <div>
                    <div class="h-60 bg-slate-100 overflow-hidden relative">
                        @if($teacher->profile_image)
                            <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center font-extrabold text-4xl text-brand-600 bg-brand-50">
                                {{ substr($teacher->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="p-5 space-y-2">
                        <h3 class="font-bold text-slate-900 text-lg leading-snug">{{ $teacher->name }}</h3>
                        <span class="text-xs font-semibold text-brand-600 block">{{ $teacher->designation }}</span>
                        <p class="text-xs text-slate-500 line-clamp-2">{{ $teacher->qualification }}</p>
                        <div class="pt-2 text-[11px] text-slate-600 font-medium">
                            <span class="text-slate-400">Subject:</span> {{ $teacher->subject }}
                        </div>
                    </div>
                </div>

                <div class="px-5 pb-5 pt-2">
                    <a href="{{ route('teachers.show', $teacher->slug) }}" class="w-full block text-center py-2.5 rounded-xl bg-slate-50 hover:bg-brand-600 hover:text-white text-xs font-bold text-slate-700 transition">
                        View Full Bio &amp; Schedule &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">No faculty members found.</div>
        @endforelse
    </div>

    @if($teachers->hasPages())
        <div class="pt-6">
            {{ $teachers->links() }}
        </div>
    @endif
</div>
@endsection
