@extends('layouts.admin')

@section('title', 'Edit Result Card — ' . $result->student_name)
@section('header', 'Edit Result Card')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('admin.iets.results.update', $result) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Result Poster Image Preview & Replace -->
            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">
                    Result Card Poster (Standardized Ratio ~800x1000px)
                </label>
                <div class="flex flex-col sm:flex-row items-center gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="w-28 h-36 rounded-xl overflow-hidden border border-slate-200 bg-white shadow-xs shrink-0 flex items-center justify-center">
                        <img id="current-poster-img" src="{{ $result->result_image ? asset('storage/' . $result->result_image) : asset('assets/images/placeholder.svg') }}" 
                             alt="{{ $result->student_name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 w-full space-y-2">
                        <p class="text-xs font-semibold text-slate-800">Replace current poster image (Optional)</p>
                        <p class="text-[11px] text-slate-400">Leave blank to keep current photo. PNG, JPG or WebP up to 5MB.</p>
                        <input type="file" name="result_image_file" accept="image/*"
                               class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-700 focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    </div>
                </div>
            </div>

            <!-- Section 2: Student & Test Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Student Full Name *</label>
                    <input type="text" name="student_name" value="{{ old('student_name', $result->student_name) }}" required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Student Roll / Candidate Number</label>
                    <input type="text" name="roll_number" value="{{ old('roll_number', $result->roll_number) }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Test Type *</label>
                    <select name="test_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-brand-500">
                        <option value="IELTS Academic" {{ old('test_type', $result->test_type) == 'IELTS Academic' ? 'selected' : '' }}>IELTS Academic</option>
                        <option value="IELTS General Training" {{ old('test_type', $result->test_type) == 'IELTS General Training' ? 'selected' : '' }}>IELTS General Training</option>
                        <option value="PTE Pearson" {{ old('test_type', $result->test_type) == 'PTE Pearson' ? 'selected' : '' }}>PTE Pearson Academic</option>
                        <option value="TOEFL iBT" {{ old('test_type', $result->test_type) == 'TOEFL iBT' ? 'selected' : '' }}>TOEFL iBT</option>
                        <option value="LanguageCert" {{ old('test_type', $result->test_type) == 'LanguageCert' ? 'selected' : '' }}>LanguageCert / Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Overall Score / Band *</label>
                    <input type="text" name="overall_score" value="{{ old('overall_score', $result->overall_score) }}" required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Official Exam Date</label>
                    <input type="date" name="test_date" value="{{ old('test_date', $result->test_date ? \Carbon\Carbon::parse($result->test_date)->format('Y-m-d') : '') }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Display Priority Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $result->display_order) }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <!-- Section 3: Sub-Skill Breakdown -->
            @php $scores = $result->scores_breakdown ?? []; @endphp
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs uppercase font-bold text-slate-700 mb-3">
                    Sub-Skill Breakdown (Listening, Reading, Writing, Speaking)
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <span class="block text-[11px] text-slate-500 font-semibold mb-1">Listening Score</span>
                        <input type="text" name="score_listening" value="{{ old('score_listening', $scores['listening'] ?? '') }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <span class="block text-[11px] text-slate-500 font-semibold mb-1">Reading Score</span>
                        <input type="text" name="score_reading" value="{{ old('score_reading', $scores['reading'] ?? '') }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <span class="block text-[11px] text-slate-500 font-semibold mb-1">Writing Score</span>
                        <input type="text" name="score_writing" value="{{ old('score_writing', $scores['writing'] ?? '') }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <span class="block text-[11px] text-slate-500 font-semibold mb-1">Speaking Score</span>
                        <input type="text" name="score_speaking" value="{{ old('score_speaking', $scores['speaking'] ?? '') }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>
            </div>

            <!-- Options: Featured & Active -->
            <div class="flex items-center gap-6 pt-4 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $result->is_featured) ? 'checked' : '' }}
                           class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-xs font-semibold text-slate-800">Feature in Homepage Slider</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="status" value="1" {{ old('status', $result->status) ? 'checked' : '' }}
                           class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-xs font-semibold text-slate-800">Publish / Active</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.iets.results.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition">
                    Update Result Card
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
