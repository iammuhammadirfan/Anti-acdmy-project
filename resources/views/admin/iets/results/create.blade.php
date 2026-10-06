@extends('layouts.admin')

@section('title', 'Upload Student Result Card')
@section('header', 'Upload New Student Result Poster')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('admin.iets.results.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Result Poster Image Upload -->
            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">
                    Result Card Poster (Standardized Ratio ~800x1000px) *
                </label>
                <div class="border-2 border-dashed border-slate-300 hover:border-brand-500 rounded-2xl p-6 text-center transition bg-slate-50 relative group">
                    <input type="file" name="result_image_file" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           onchange="previewResultImage(event)">
                    <div id="upload-prompt" class="space-y-2">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-xs text-slate-700 font-semibold">Click to browse or drop result card poster</p>
                        <p class="text-[11px] text-slate-400">PNG, JPG or WebP up to 5MB (Auto-cropped to standard dimensions)</p>
                    </div>
                    <div id="image-preview-container" class="hidden">
                        <img id="image-preview" src="#" alt="Preview" class="max-h-64 mx-auto rounded-xl shadow-md border border-slate-200 object-contain">
                        <p class="text-xs text-emerald-600 font-semibold mt-2">New Image Selected</p>
                    </div>
                </div>
                @error('result_image_file') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Section 2: Student & Test Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Student Full Name *</label>
                    <input type="text" name="student_name" value="{{ old('student_name') }}" required placeholder="e.g. Muhammad Bilal" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    @error('student_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Student Roll / Candidate Number</label>
                    <input type="text" name="roll_number" value="{{ old('roll_number') }}" placeholder="e.g. 049281 or IDP-PK-891" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Test Type *</label>
                    <select name="test_type" id="test_type" onchange="adjustScoreLabels()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-brand-500">
                        <option value="IELTS Academic" {{ old('test_type') == 'IELTS Academic' ? 'selected' : '' }}>IELTS Academic</option>
                        <option value="IELTS General Training" {{ old('test_type') == 'IELTS General Training' ? 'selected' : '' }}>IELTS General Training</option>
                        <option value="PTE Pearson" {{ old('test_type') == 'PTE Pearson' ? 'selected' : '' }}>PTE Pearson Academic</option>
                        <option value="TOEFL iBT" {{ old('test_type') == 'TOEFL iBT' ? 'selected' : '' }}>TOEFL iBT</option>
                        <option value="LanguageCert" {{ old('test_type') == 'LanguageCert' ? 'selected' : '' }}>LanguageCert / Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Overall Score / Band *</label>
                    <input type="text" name="overall_score" value="{{ old('overall_score') }}" required placeholder="e.g. Band 8.0 or 79/90" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    @error('overall_score') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Official Exam Date</label>
                    <input type="date" name="test_date" value="{{ old('test_date') }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Display Priority Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <!-- Section 3: Sub-Skill Breakdown -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs uppercase font-bold text-slate-700 mb-3">
                    Sub-Skill Breakdown (Listening, Reading, Writing, Speaking)
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <span class="block text-[11px] text-slate-500 font-semibold mb-1">Listening Score</span>
                        <input type="text" name="score_listening" value="{{ old('score_listening') }}" placeholder="e.g. 8.5" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <span class="block text-[11px] text-slate-500 font-semibold mb-1">Reading Score</span>
                        <input type="text" name="score_reading" value="{{ old('score_reading') }}" placeholder="e.g. 8.0" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <span class="block text-[11px] text-slate-500 font-semibold mb-1">Writing Score</span>
                        <input type="text" name="score_writing" value="{{ old('score_writing') }}" placeholder="e.g. 7.5" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <span class="block text-[11px] text-slate-500 font-semibold mb-1">Speaking Score</span>
                        <input type="text" name="score_speaking" value="{{ old('score_speaking') }}" placeholder="e.g. 8.0" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>
            </div>

            <!-- Options: Featured & Active -->
            <div class="flex items-center gap-6 pt-4 border-t border-slate-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-xs font-semibold text-slate-800">Feature in Homepage Slider</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
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
                    Upload Result Card
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewResultImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('upload-prompt').classList.add('hidden');
                const container = document.getElementById('image-preview-container');
                container.classList.remove('hidden');
                document.getElementById('image-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
