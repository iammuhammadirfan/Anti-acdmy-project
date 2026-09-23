@extends('layouts.admin')

@section('title', 'Hero Sliders')
@section('page_title', 'Hero Slider Management')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Homepage Hero Sliders</h2>
            <p class="text-xs text-slate-500">Manage full-width hero slides, headings, call-to-action buttons, and order.</p>
        </div>
        <a href="{{ route('admin.sliders.create') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add New Slide</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sliders as $slider)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="h-44 bg-slate-100 relative overflow-hidden">
                        @if($slider->image)
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->heading }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-brand-900 to-brand-700 text-white/50">
                                <i data-lucide="image" class="w-10 h-10"></i>
                            </div>
                        @endif
                        <span class="absolute top-3 right-3 text-[10px] font-bold uppercase px-2.5 py-1 rounded-full {{ $slider->status ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white' }}">
                            {{ $slider->status ? 'Active' : 'Draft' }}
                        </span>
                        <span class="absolute bottom-3 left-3 bg-slate-900/80 text-white text-[11px] font-mono px-2 py-0.5 rounded">
                            Order: #{{ $slider->display_order }}
                        </span>
                    </div>

                    <div class="p-5">
                        <h4 class="font-bold text-slate-900 text-base leading-snug line-clamp-1">{{ $slider->heading }}</h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $slider->short_description }}</p>
                        
                        <div class="flex flex-wrap gap-2 mt-4 text-xs font-semibold">
                            @if($slider->button_text)
                                <span class="bg-brand-50 text-brand-700 px-2 py-1 rounded">Btn 1: {{ $slider->button_text }}</span>
                            @endif
                            @if($slider->secondary_button_text)
                                <span class="bg-amber-50 text-amber-700 px-2 py-1 rounded">Btn 2: {{ $slider->secondary_button_text }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="text-xs font-bold text-brand-600 hover:text-brand-800 flex items-center gap-1">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                    </a>
                    <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Delete this slide?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                No hero slides created yet. Click "Add New Slide" to create one.
            </div>
        @endforelse
    </div>
</div>
@endsection
