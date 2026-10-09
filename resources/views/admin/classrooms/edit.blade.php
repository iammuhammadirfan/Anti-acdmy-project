@extends('layouts.admin')

@section('title', 'Edit Classroom')
@section('page_title', 'Edit Facility: ' . $classroom->title)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('admin.classrooms.update', $classroom) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Facility Title *</label>
                    <input type="text" name="title" value="{{ old('title', $classroom->title) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Classroom Type *</label>
                    <select name="class_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        <option value="Lab" {{ $classroom->class_type === 'Lab' ? 'selected' : '' }}>Acoustic / Computer Lab</option>
                        <option value="Lecture" {{ $classroom->class_type === 'Lecture' ? 'selected' : '' }}>Lecture Hall</option>
                        <option value="Seminar" {{ $classroom->class_type === 'Seminar' ? 'selected' : '' }}>Seminar Theatre</option>
                        <option value="Audio-Visual" {{ $classroom->class_type === 'Audio-Visual' ? 'selected' : '' }}>Private Speaking Studio</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Seating Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $classroom->capacity) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Facility Image</label>
                    @if($classroom->primary_image)
                        <div class="mb-3 w-36 h-24 rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                            <img src="{{ $classroom->primary_image }}" alt="{{ $classroom->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="image_file" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white">
                    <p class="text-[10px] text-slate-500 mt-1">Upload a new image to replace the current one.</p>
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('description', $classroom->description) }}</textarea>
            </div>

            <div>
                @php
                    $facString = is_array($classroom->facilities) ? implode("\n", $classroom->facilities) : '';
                @endphp
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Equipped Facilities (One item per line)</label>
                <textarea name="facilities_str" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('facilities_str', $facString) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.classrooms.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition-all">Update Facility</button>
            </div>
        </form>
    </div>
</div>
@endsection
