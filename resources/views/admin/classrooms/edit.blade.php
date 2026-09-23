@extends('layouts.admin')

@section('title', 'Edit Classroom')
@section('header', 'Edit Facility: ' . $classroom->title)

@section('content')
<div class="max-w-4xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.classrooms.update', $classroom) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Facility Title *</label>
                    <input type="text" name="title" value="{{ old('title', $classroom->title) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Classroom Type *</label>
                    <select name="class_type" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                        <option value="Lab" {{ $classroom->class_type === 'Lab' ? 'selected' : '' }}>Acoustic / Computer Lab</option>
                        <option value="Lecture" {{ $classroom->class_type === 'Lecture' ? 'selected' : '' }}>Lecture Hall</option>
                        <option value="Seminar" {{ $classroom->class_type === 'Seminar' ? 'selected' : '' }}>Seminar Theatre</option>
                        <option value="Audio-Visual" {{ $classroom->class_type === 'Audio-Visual' ? 'selected' : '' }}>Private Speaking Studio</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Seating Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $classroom->capacity) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Replace Facility Image</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('description', $classroom->description) }}</textarea>
            </div>

            <div>
                @php
                    $facString = is_array($classroom->facilities) ? implode("\n", $classroom->facilities) : '';
                @endphp
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Equipped Facilities (One item per line)</label>
                <textarea name="facilities_str" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('facilities_str', $facString) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.classrooms.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Update Facility</button>
            </div>
        </form>
    </div>
</div>
@endsection
