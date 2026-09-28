@extends('layouts.admin')

@section('title', 'Edit Result Card')
@section('header', 'Edit Result Card: ' . $result->student_name)

@section('content')
<div class="max-w-4xl">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
        <div class="mb-6 pb-5 border-b border-slate-800">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <span class="p-2 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </span>
                Edit Student Result Card
            </h2>
            <p class="text-xs text-slate-400 mt-1">
                Update result card details or upload a new standardized image (auto-formatted to 800&times;1000 px).
            </p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.iets.results.update', $result) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ previewUrl: null, testType: '{{ old('test_type', $result->category) }}' }">
            @csrf
            @method('PUT')

            <!-- Upload Area & Preview -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                <div class="md:col-span-7 space-y-4">
                    <label class="block text-xs uppercase font-bold text-slate-300">
                        Replace Result Card Image
                        <span class="text-[11px] text-blue-400 normal-case font-normal block mt-0.5">Leave empty to keep existing image. Max 10MB (JPG, PNG, WebP).</span>
                    </label>

                    <div class="relative border-2 border-dashed border-slate-700 hover:border-blue-500 rounded-2xl p-6 text-center transition-colors bg-slate-950/60">
                        <input type="file" name="result_image_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               @change="const file = $event.target.files[0]; if (file) { previewUrl = URL.createObjectURL(file); }">
                        <div class="space-y-2">
                            <div class="w-12 h-12 mx-auto rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <div class="text-xs font-semibold text-white">Click or drag & drop to replace image</div>
                            <p class="text-[11px] text-slate-500">Auto-scaled to standard 800&times;1000 px</p>
                        </div>
                    </div>

                    <!-- Category Portion Selection -->
                    <div>
                        <label class="block text-xs uppercase font-bold text-slate-300 mb-2">Test Category (Portion) *</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border cursor-pointer transition-all text-center"
                                   :class="testType === 'IELTS' ? 'bg-red-500/15 border-red-500 text-white shadow-md' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'">
                                <input type="radio" name="test_type" value="IELTS" class="sr-only" x-model="testType">
                                <span class="font-extrabold text-sm tracking-wide">IELTS</span>
                                <span class="text-[10px] mt-0.5" :class="testType === 'IELTS' ? 'text-red-300' : 'text-slate-500'">Academic & General</span>
                            </label>

                            <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border cursor-pointer transition-all text-center"
                                   :class="testType === 'PTE' ? 'bg-amber-500/15 border-amber-500 text-white shadow-md' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'">
                                <input type="radio" name="test_type" value="PTE" class="sr-only" x-model="testType">
                                <span class="font-extrabold text-sm tracking-wide">PTE</span>
                                <span class="text-[10px] mt-0.5" :class="testType === 'PTE' ? 'text-amber-300' : 'text-slate-500'">Pearson Academic</span>
                            </label>

                            <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border cursor-pointer transition-all text-center"
                                   :class="testType === 'TOEFL' ? 'bg-indigo-500/15 border-indigo-500 text-white shadow-md' : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'">
                                <input type="radio" name="test_type" value="TOEFL" class="sr-only" x-model="testType">
                                <span class="font-extrabold text-sm tracking-wide">TOEFL</span>
                                <span class="text-[10px] mt-0.5" :class="testType === 'TOEFL' ? 'text-indigo-300' : 'text-slate-500'">iBT / Essentials</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Current / Preview Card Frame -->
                <div class="md:col-span-5">
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Card Image (4:5 Fixed Size)</label>
                    <div class="relative w-full aspect-[4/5] bg-slate-950 rounded-2xl border-2 border-slate-800 overflow-hidden flex flex-col items-center justify-center shadow-lg group">
                        <template x-if="previewUrl">
                            <img :src="previewUrl" alt="New Card Preview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!previewUrl">
                            <img src="{{ $result->card_image_url }}" alt="{{ $result->student_name }}" class="w-full h-full object-cover">
                        </template>

                        <!-- Tag overlay -->
                        <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md shadow"
                             :class="testType === 'IELTS' ? 'bg-red-600 text-white' : (testType === 'PTE' ? 'bg-amber-600 text-white' : 'bg-indigo-600 text-white')"
                             x-text="testType">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Name & Score/Band -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-800">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-300 mb-2">Student Name *</label>
                    <input type="text" name="student_name" value="{{ old('student_name', $result->student_name) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold text-slate-300 mb-2">Score / Overall Band *</label>
                    <input type="text" name="overall_band" value="{{ old('overall_band', $result->overall_band) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <!-- Exam Date & Featured in Slider -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Official Exam Date</label>
                    <input type="date" name="test_date" value="{{ old('test_date', $result->test_date ? \Carbon\Carbon::parse($result->test_date)->format('Y-m-d') : '') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex items-center pt-6">
                    <label class="relative flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $result->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 bg-slate-950 border-slate-800 focus:ring-blue-500">
                        <div>
                            <span class="text-xs font-bold text-white block">Feature in Home & Results Slider</span>
                            <span class="text-[11px] text-slate-500 block">Rotate card in the slider showcase</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Optional Remarks / Description -->
            <div>
                <label class="block text-xs uppercase font-bold text-slate-400 mb-2">Remarks / Story (Optional)</label>
                <textarea name="description" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-blue-500">{{ old('description', $result->description) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.iets.results.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-400 hover:text-white">Cancel</a>
                <button type="submit" class="px-7 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Update Result Card
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
