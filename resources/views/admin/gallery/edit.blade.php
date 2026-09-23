@extends('layouts.admin')

@section('title', 'Edit Photo')
@section('header', 'Edit Photo: ' . $gallery->title)

@section('content')
<div class="max-w-2xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Photo Title *</label>
                <input type="text" name="title" value="{{ old('title', $gallery->title) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Category *</label>
                    <select name="category" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                        @foreach(['classroom', 'lab', 'library', 'student_activity', 'events', 'outdoor'] as $c)
                            <option value="{{ $c }}" {{ $gallery->category === $c ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $c)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $gallery->display_order) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Replace Image File (Optional)</label>
                <input type="file" name="image_file" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Caption / Short Description</label>
                <input type="text" name="caption" value="{{ old('caption', $gallery->caption) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.gallery.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
