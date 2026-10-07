@extends('layouts.admin')

@section('title', 'Add Class / Test Schedule')
@section('page_title', 'Create New Class & Test Timing')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('admin.iets.programs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <h3 class="font-bold text-slate-900 text-base">Class / Test Overview</h3>
                <p class="text-xs text-slate-500">Specify the course/test name, badge, and optional instructor.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Class / Test Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. IELTS Academic &amp; General Masterclass" required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Badge / Schedule Tag</label>
                    <input type="text" name="badge" value="{{ old('badge', 'Mon – Fri (3 Daily Slots)') }}" placeholder="e.g. Mon – Fri Regular or Weekend Intensive" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Course Category (Custom / Select)</label>
                    <input type="text" name="category" list="category_suggestions" value="{{ old('category', 'IELTS Academic & General') }}" placeholder="Type custom category or select from suggestions..." 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                    <datalist id="category_suggestions">
                        <option value="IELTS Academic & General">
                        <option value="IELTS Standard">
                        <option value="PTE Academic & Core">
                        <option value="TOEFL iBT">
                        <option value="Spoken English & Fluency">
                        <option value="Duolingo English Test (DET)">
                        <option value="Proctored Mock Trials">
                        <option value="GRE / GMAT Prep">
                        <option value="OET (Occupational English Test)">
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <!-- 3 Timing Slots Section -->
            <div class="pt-5 border-t border-slate-100 space-y-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4 text-brand-600"></i>
                        <span>3 Class Timing Batch Slots (Morning, Midday &amp; Evening)</span>
                    </h3>
                    <p class="text-xs text-slate-500">Configure the 3 timing slots for this class. (Default: 9-12, 11-2, 4-7).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Slot 1: Morning -->
                    <div x-data="{ enabled: {{ old('timing_slot_1_enabled', 1) ? 'true' : 'false' }} }" 
                         :class="enabled ? 'bg-amber-50/70 border-amber-200/80 shadow-xs' : 'bg-slate-50 border-slate-200 opacity-60'"
                         class="p-4 rounded-xl border space-y-3 transition-all">
                        <div class="flex items-center justify-between gap-2 pb-2 border-b" :class="enabled ? 'border-amber-200/60' : 'border-slate-200'">
                            <div class="flex items-center gap-2 font-bold text-xs" :class="enabled ? 'text-amber-900' : 'text-slate-500'">
                                <span class="w-2 h-2 rounded-full" :class="enabled ? 'bg-amber-500 animate-pulse' : 'bg-slate-400'"></span>
                                <span>Slot 1: Morning</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="timing_slot_1_enabled" value="1" 
                                       x-model="enabled"
                                       class="sr-only peer">
                                <div class="w-8 h-4 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-amber-600"></div>
                                <span class="ml-1.5 text-[10px] font-extrabold" :class="enabled ? 'text-amber-800' : 'text-slate-400'" x-text="enabled ? 'ON' : 'OFF'"></span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Batch Label</label>
                            <input type="text" name="timing_slot_1_name" value="{{ old('timing_slot_1_name', 'Morning Batch') }}" 
                                   class="w-full bg-white border border-amber-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Timing (e.g. 09:00 AM - 12:00 PM)</label>
                            <input type="text" name="timing_slot_1_time" value="{{ old('timing_slot_1_time', '09:00 AM - 12:00 PM') }}" 
                                   class="w-full bg-white border border-amber-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Slot Short Note / Details</label>
                            <input type="text" name="timing_slot_1_details" value="{{ old('timing_slot_1_details', 'Theoretical foundations, grammar drills & speaking sessions') }}" 
                                   class="w-full bg-white border border-amber-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500">
                        </div>
                    </div>

                    <!-- Slot 2: Midday -->
                    <div x-data="{ enabled: {{ old('timing_slot_2_enabled', 1) ? 'true' : 'false' }} }" 
                         :class="enabled ? 'bg-sky-50/70 border-sky-200/80 shadow-xs' : 'bg-slate-50 border-slate-200 opacity-60'"
                         class="p-4 rounded-xl border space-y-3 transition-all">
                        <div class="flex items-center justify-between gap-2 pb-2 border-b" :class="enabled ? 'border-sky-200/60' : 'border-slate-200'">
                            <div class="flex items-center gap-2 font-bold text-xs" :class="enabled ? 'text-sky-900' : 'text-slate-500'">
                                <span class="w-2 h-2 rounded-full" :class="enabled ? 'bg-sky-500 animate-pulse' : 'bg-slate-400'"></span>
                                <span>Slot 2: Midday</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="timing_slot_2_enabled" value="1" 
                                       x-model="enabled"
                                       class="sr-only peer">
                                <div class="w-8 h-4 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-sky-600"></div>
                                <span class="ml-1.5 text-[10px] font-extrabold" :class="enabled ? 'text-sky-800' : 'text-slate-400'" x-text="enabled ? 'ON' : 'OFF'"></span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Batch Label</label>
                            <input type="text" name="timing_slot_2_name" value="{{ old('timing_slot_2_name', 'Midday Batch') }}" 
                                   class="w-full bg-white border border-sky-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-sky-500">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Timing (e.g. 11:00 AM - 02:00 PM)</label>
                            <input type="text" name="timing_slot_2_time" value="{{ old('timing_slot_2_time', '11:00 AM - 02:00 PM') }}" 
                                   class="w-full bg-white border border-sky-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-sky-500">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Slot Short Note / Details</label>
                            <input type="text" name="timing_slot_2_details" value="{{ old('timing_slot_2_details', 'Interactive computer lab simulation & proctored mock trials') }}" 
                                   class="w-full bg-white border border-sky-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-sky-500">
                        </div>
                    </div>

                    <!-- Slot 3: Evening -->
                    <div x-data="{ enabled: {{ old('timing_slot_3_enabled', 1) ? 'true' : 'false' }} }" 
                         :class="enabled ? 'bg-indigo-50/70 border-indigo-200/80 shadow-xs' : 'bg-slate-50 border-slate-200 opacity-60'"
                         class="p-4 rounded-xl border space-y-3 transition-all">
                        <div class="flex items-center justify-between gap-2 pb-2 border-b" :class="enabled ? 'border-indigo-200/60' : 'border-slate-200'">
                            <div class="flex items-center gap-2 font-bold text-xs" :class="enabled ? 'text-indigo-900' : 'text-slate-500'">
                                <span class="w-2 h-2 rounded-full" :class="enabled ? 'bg-indigo-500 animate-pulse' : 'bg-slate-400'"></span>
                                <span>Slot 3: Evening</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="timing_slot_3_enabled" value="1" 
                                       x-model="enabled"
                                       class="sr-only peer">
                                <div class="w-8 h-4 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-1.5 text-[10px] font-extrabold" :class="enabled ? 'text-indigo-800' : 'text-slate-400'" x-text="enabled ? 'ON' : 'OFF'"></span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Batch Label</label>
                            <input type="text" name="timing_slot_3_name" value="{{ old('timing_slot_3_name', 'Evening Batch') }}" 
                                   class="w-full bg-white border border-indigo-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Timing (e.g. 04:00 PM - 07:00 PM)</label>
                            <input type="text" name="timing_slot_3_time" value="{{ old('timing_slot_3_time', '04:00 PM - 07:00 PM') }}" 
                                   class="w-full bg-white border border-indigo-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Slot Short Note / Details</label>
                            <input type="text" name="timing_slot_3_details" value="{{ old('timing_slot_3_details', 'Optimized for professionals, advanced writing & intensive speaking') }}" 
                                   class="w-full bg-white border border-indigo-200 rounded-lg px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="pt-5 border-t border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Duration (e.g. 8 Weeks)</label>
                    <input type="text" name="duration" value="{{ old('duration', '8 Weeks Intensive') }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Course Fee (Optional)</label>
                    <input type="text" name="fee" value="{{ old('fee') }}" placeholder="e.g. PKR 25,000" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Primary Instructor</label>
                    <input type="text" name="instructor_name" value="{{ old('instructor_name') }}" placeholder="e.g. Certified IELTS Examiner" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Course Summary / Pitch *</label>
                <textarea name="summary" rows="3" required placeholder="Short summary explaining the target test modules and goals..."
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">{{ old('summary') }}</textarea>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-700 mb-2">Key Highlights / Inclusions (One per line)</label>
                <textarea name="features_str" rows="4" placeholder="10+ Full Length Mock Tests&#10;Daily 1-on-1 Speaking Clinics&#10;Audio-Visual Multimedia Lab Access"
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-brand-500">{{ old('features_str', "12 Proctored Computer Mock Tests\nDaily 1-on-1 Speaking Interviews\nComprehensive Cambridge Study Pack\nBand Score Improvement Guarantee") }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.iets.programs.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20 transition">
                    Save Class &amp; Timings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
