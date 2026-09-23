@extends('layouts.app')

@section('title', 'Academy History & Milestones — Apex Academy')
@section('meta_description', 'Trace the historical journey and key achievements of Apex Academy & IETS from founding to AI-assisted modern education.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <!-- Header -->
    <div class="text-center space-y-3">
        <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
            Our Legacy
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Institutional Timeline &amp; Milestones</h1>
        <p class="text-sm text-slate-600 max-w-xl mx-auto">Explore how Apex Academy evolved into a pioneer of IELTS coaching and student success.</p>
    </div>

    <!-- Vertical Timeline -->
    <div class="relative border-l-2 border-brand-500/30 ml-4 sm:ml-32 space-y-12 py-4">
        @forelse($timelines as $item)
            <div class="relative pl-8 sm:pl-10 group">
                <!-- Marker Dot -->
                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-brand-600 border-4 border-white shadow"></div>

                <!-- Year Badge on left for desktop -->
                <div class="sm:absolute sm:-left-32 sm:top-1 sm:text-right sm:w-24">
                    <span class="inline-block px-2.5 py-1 rounded-lg bg-brand-900 text-white font-black text-xs sm:text-sm font-mono tracking-wider shadow">
                        {{ $item->year }}
                    </span>
                </div>

                <!-- Card Content -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition space-y-3">
                    <h3 class="text-lg font-bold text-slate-900">{{ $item->title }}</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $item->description }}</p>

                    @if($item->image)
                        <div class="pt-2">
                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded-xl w-full max-h-60 object-cover border border-slate-100">
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="pl-8 text-slate-400 text-sm">Milestones configured dynamically via Admin Panel.</div>
        @endforelse
    </div>
</div>
@endsection
