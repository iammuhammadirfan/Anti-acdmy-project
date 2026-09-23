@extends('layouts.app')

@section('title', $teacher->name . ' — ' . $teacher->designation)
@section('meta_description', strip_tags(substr($teacher->bio, 0, 160)))

@section('schema_json')
    <script type="application/ld+json">
        {!! json_encode($teacherSchema) !!}
    </script>
@endsection

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <a href="{{ route('teachers') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Faculty Directory
    </a>

    <!-- Teacher Profile Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Left: Photo & Socials -->
            <div class="space-y-4 text-center md:text-left">
                <div class="aspect-square rounded-2xl bg-slate-100 overflow-hidden border border-slate-200 shadow-inner">
                    @if($teacher->profile_image)
                        <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center font-black text-6xl text-brand-600 bg-brand-50">
                            {{ substr($teacher->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                @if($teacher->social_links)
                    <div class="flex items-center justify-center md:justify-start gap-2 pt-2">
                        @if(isset($teacher->social_links['linkedin']))
                            <a href="{{ $teacher->social_links['linkedin'] }}" target="_blank" class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-brand-600 hover:text-white flex items-center justify-center text-slate-600 transition"><i data-lucide="linkedin" class="w-4 h-4"></i></a>
                        @endif
                        @if(isset($teacher->social_links['twitter']))
                            <a href="{{ $teacher->social_links['twitter'] }}" target="_blank" class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-brand-600 hover:text-white flex items-center justify-center text-slate-600 transition"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                        @endif
                        @if(isset($teacher->social_links['facebook']))
                            <a href="{{ $teacher->social_links['facebook'] }}" target="_blank" class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-brand-600 hover:text-white flex items-center justify-center text-slate-600 transition"><i data-lucide="facebook" class="w-4 h-4"></i></a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right: Credentials & Bio -->
            <div class="md:col-span-2 space-y-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-2.5 py-1 rounded-md">{{ $teacher->subject }}</span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">{{ $teacher->name }}</h1>
                    <span class="text-sm font-semibold text-slate-500 block mt-0.5">{{ $teacher->designation }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 py-4 border-y border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Qualification</span>
                        <strong class="text-slate-800 text-sm block mt-0.5">{{ $teacher->qualification }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Experience</span>
                        <strong class="text-slate-800 text-sm block mt-0.5">{{ $teacher->experience ?: '10+ Years' }}</strong>
                    </div>
                    @if($teacher->classes_taught)
                        <div class="col-span-2">
                            <span class="text-slate-400 block font-semibold uppercase">Batches &amp; Modules Taught</span>
                            <strong class="text-slate-800 text-sm block mt-0.5">{{ $teacher->classes_taught }}</strong>
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    <h3 class="font-bold text-slate-900 text-base">Biography &amp; Teaching Philosophy</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $teacher->bio }}</p>
                </div>

                <div class="pt-4">
                    <a href="{{ route('appointments') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-3 rounded-xl shadow transition">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span>Book 1-on-1 Consultation Session</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
