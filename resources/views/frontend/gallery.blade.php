@extends('layouts.app')

@section('title', 'Campus & Event Gallery — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="relative bg-slate-950 py-20 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
            📸 Academy Life & Milestones
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            Campus <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Photo Gallery</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Take a look inside our dynamic testing labs, student convocation celebrations, collaborative seminar theatres, and masterclass sessions.
        </p>

        <!-- Category Filters -->
        <div class="flex flex-wrap items-center justify-center gap-2 mt-8">
            <a href="{{ route('gallery') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ empty($category) ? 'bg-blue-600 text-white shadow-lg' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                All Photos
            </a>
            @foreach(['classroom' => 'Classrooms & Labs', 'events' => 'Events & Ceremonies', 'student_activity' => 'Student Life', 'library' => 'Study Lounges'] as $catKey => $catLabel)
                <a href="{{ route('gallery', ['category' => $catKey]) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $category === $catKey ? 'bg-blue-600 text-white shadow-lg' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    {{ $catLabel }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($photos as $photo)
                <div class="group relative bg-slate-800 rounded-2xl overflow-hidden border border-slate-700/60 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="aspect-video sm:aspect-square w-full overflow-hidden bg-slate-950">
                        <img src="{{ $photo->image_path }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-4 bg-slate-800">
                        <div class="text-xs uppercase font-bold text-blue-400 tracking-wider mb-1">{{ str_replace('_', ' ', $photo->category) }}</div>
                        <h3 class="text-base font-bold text-white mb-1 truncate">{{ $photo->title }}</h3>
                        @if($photo->caption)
                            <p class="text-xs text-slate-400 line-clamp-2">{{ $photo->caption }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    No images found for this category.
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $photos->links() }}
        </div>
    </div>
</section>
@endsection
