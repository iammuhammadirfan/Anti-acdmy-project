@extends('layouts.admin')

@section('title', 'Edit Section: ' . $section->section_key)
@section('page_title', 'Customize Section: ' . ucfirst(str_replace('_', ' ', $section->section_key)))

@section('content')
<div class="max-w-3xl space-y-6">
    <a href="{{ route('admin.sections.index') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Sections
    </a>

    <form action="{{ route('admin.sections.update', $section) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Section Key</label>
                <input type="text" value="{{ $section->section_key }}" disabled
                       class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-mono text-slate-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Section Main Title</label>
                <input type="text" name="title" value="{{ old('title', $section->title) }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Subtitle / Tagline</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $section->subtitle) }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Section Description / Content</label>
                <textarea name="content" rows="4"
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('content', $section->content) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Featured Section Image</label>
                @if($section->image)
                    <div class="mb-3 w-40 h-24 rounded-xl overflow-hidden border border-slate-200">
                        <img src="{{ asset('storage/' . $section->image) }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <input type="file" name="image_file" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Button Label</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $section->button_text) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Button Destination URL</label>
                    <input type="text" name="button_url" value="{{ old('button_url', $section->button_url) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $section->display_order) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $section->is_active ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                        <span class="text-sm font-semibold text-slate-800">Show Section on Homepage</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.sections.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold transition">Cancel</a>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">Save Section Settings</button>
        </div>
    </form>
</div>
@endsection
