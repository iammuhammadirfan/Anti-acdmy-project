@extends('layouts.admin')

@section('title', 'Add Program')
@section('header', 'Create IETS Program')

@section('content')
<div class="max-w-4xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.iets.programs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Program Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Category *</label>
                    <select name="category" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                        <option value="general">General Training</option>
                        <option value="academic">Academic Masterclass</option>
                        <option value="reading">Reading Focus</option>
                        <option value="writing">Writing Focus</option>
                        <option value="listening">Listening Lab</option>
                        <option value="speaking">Speaking Clinics</option>
                        <option value="mock_test">Proctored Mock Trials</option>
                        <option value="ai_evaluation">AI Evaluation Sandbox</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Program Banner Image</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Summary / Short Pitch</label>
                <textarea name="summary" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('summary') }}</textarea>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Full Course Details / Description</label>
                <textarea name="content" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('content') }}</textarea>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Features & Highlights (One per line)</label>
                <textarea name="features_str" rows="4" placeholder="12 Proctored Computer-Delivered Mock Exams&#10;Unlimited 1-on-1 Speaking Trials&#10;Cambridge Manuals Included" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('features_str') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.iets.programs.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Save Program</button>
            </div>
        </form>
    </div>
</div>
@endsection
