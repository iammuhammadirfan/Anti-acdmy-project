@extends('layouts.admin')

@section('title', 'Add Student Scorecard')
@section('header', 'Add IETS Result')

@section('content')
<div class="max-w-3xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="{{ route('admin.iets.results.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Student Name *</label>
                    <input type="text" name="student_name" value="{{ old('student_name') }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Test Format *</label>
                    <select name="test_type" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                        <option value="IELTS Academic">IELTS Academic</option>
                        <option value="IELTS General">IELTS General Training</option>
                        <option value="IETS Intensive Diagnostic">IETS Intensive Diagnostic</option>
                    </select>
                </div>
            </div>

            <!-- Score breakdown -->
            <div class="bg-slate-950/80 p-5 rounded-2xl border border-slate-800">
                <div class="text-xs uppercase font-bold text-blue-400 tracking-wider mb-4">Official Band Scores (0.0 - 9.0)</div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-400 mb-1">Overall Band *</label>
                        <input type="number" step="0.5" min="0" max="9" name="overall_band" value="{{ old('overall_band', '8.0') }}" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm font-bold text-white focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Listening</label>
                        <input type="number" step="0.5" min="0" max="9" name="listening_score" value="{{ old('listening_score', '8.5') }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Reading</label>
                        <input type="number" step="0.5" min="0" max="9" name="reading_score" value="{{ old('reading_score', '8.0') }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Writing</label>
                        <input type="number" step="0.5" min="0" max="9" name="writing_score" value="{{ old('writing_score', '7.5') }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Speaking</label>
                        <input type="number" step="0.5" min="0" max="9" name="speaking_score" value="{{ old('speaking_score', '8.0') }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Student Portrait Photo</label>
                    <input type="file" name="student_image_file" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Official Exam Date</label>
                    <input type="date" name="test_date" value="{{ old('test_date') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Student Testimonial / Story</label>
                <textarea name="description" rows="3" placeholder="Describe their experience, key faculty mentor, or university accepted into..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-blue-500">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.iets.results.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all">Save Scorecard</button>
            </div>
        </form>
    </div>
</div>
@endsection
