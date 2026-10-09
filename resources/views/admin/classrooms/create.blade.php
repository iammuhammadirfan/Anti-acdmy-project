@extends('layouts.admin')

@section('title', 'Add New Classroom / Lab')
@section('page_title', 'Add Facility')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('admin.classrooms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Facility Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Classroom Type *</label>
                    <select name="class_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        <option value="Lab">Acoustic / Computer Lab</option>
                        <option value="Lecture">Lecture Hall</option>
                        <option value="Seminar">Seminar Theatre</option>
                        <option value="Audio-Visual">Private Speaking Studio</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Seating Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 30) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Primary Facility Image</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Equipped Facilities (One item per line)</label>
                <textarea name="facilities_str" rows="4" placeholder="Smart Interactive 4K Board&#10;Noise-Cancelling Studio Headsets&#10;Acoustic Foam Soundproofing" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('facilities_str') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.classrooms.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all">Save Facility</button>
            </div>
        </form>
    </div>
</div>
@endsection
