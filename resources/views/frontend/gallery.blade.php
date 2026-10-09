@extends('layouts.app')

@section('title', 'Campus & Event Gallery — ' . ($globalSettings['academy_name'] ?? config('app.name', 'Academy')))

@section('content')
<section class="relative bg-[#f8fafc] py-16 text-slate-900 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 shadow-xs">
            📸 Academy Life &amp; Milestones
        </span>
        <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-slate-900">
            Campus Photo Gallery
        </h1>
        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto">
            Take a look inside our dynamic testing labs, student convocation celebrations, collaborative seminar theatres, and masterclass sessions.
        </p>

        <!-- Category Filters -->
        <div class="flex flex-wrap items-center justify-center gap-2 pt-4">
            <a href="{{ route('gallery') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($category) ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                All Photos
            </a>
            @foreach(['classroom' => 'Classrooms & Labs', 'events' => 'Events & Ceremonies', 'student_activity' => 'Student Life', 'library' => 'Study Lounges'] as $catKey => $catLabel)
                <a href="{{ route('gallery', ['category' => $catKey]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $category === $catKey ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    {{ $catLabel }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($photos as $photo)
                <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-200/90 shadow-xs hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                    <div class="aspect-video sm:aspect-square w-full overflow-hidden bg-slate-100">
                        <img src="{{ $photo->image_path }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                    </div>
                    <div class="p-4 bg-white border-t border-slate-100">
                        <div class="text-[10px] uppercase font-extrabold text-slate-500 tracking-wider mb-1">{{ str_replace('_', ' ', $photo->category) }}</div>
                        <h3 class="text-sm font-extrabold text-slate-900 mb-1 truncate group-hover:text-black">{{ $photo->title }}</h3>
                        @if($photo->caption)
                            <p class="text-xs text-slate-500 line-clamp-2">{{ $photo->caption }}</p>
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
