@extends('layouts.admin')

@section('title', 'Edit Hero Slide')
@section('page_title', 'Edit Slide')

@section('content')
<div class="max-w-3xl space-y-6">
    <a href="{{ route('admin.sliders.index') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Sliders
    </a>

    <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Heading *</label>
                <input type="text" name="heading" value="{{ old('heading', $slider->heading) }}" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Short Description</label>
                <textarea name="short_description" rows="3"
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('short_description', $slider->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Background Image</label>
                @if($slider->image)
                    <div class="mb-3 w-40 h-24 rounded-xl overflow-hidden border border-slate-200">
                        <img src="{{ asset('storage/' . $slider->image) }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <input type="file" name="image_file" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Primary Button Text</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $slider->button_text) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Primary Button URL</label>
                    <input type="text" name="button_url" value="{{ old('button_url', $slider->button_url) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Secondary Button Text</label>
                    <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', $slider->secondary_button_text) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Secondary Button URL</label>
                    <input type="text" name="secondary_button_url" value="{{ old('secondary_button_url', $slider->secondary_button_url) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $slider->display_order) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="status" value="1" {{ $slider->status ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                        <span class="text-sm font-semibold text-slate-800">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.sliders.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold transition">Cancel</a>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">Save Changes</button>
        </div>
    </form>
</div>
@endsection
