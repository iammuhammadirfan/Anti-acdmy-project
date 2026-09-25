@extends('layouts.app')

@section('title', 'Book an Academic Counseling Session or IETS Test — Apex Academy')
@section('meta_description', 'Official booking portal for Campus Counseling Sessions and IETS Testing. Real-time slot availability, 15-student capacity limits, and instant confirmed enrollment.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8" 
     x-data="appointmentCalendar('{{ $defaultType ?? 'counseling' }}', '{{ old('appointment_type', $defaultType ?? 'counseling') }}')">

    <!-- Header Section with Dynamic Title & Subtitle -->
    <div class="text-center space-y-3">
        <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200/80 px-3.5 py-1 rounded-full shadow-sm">
            <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i>
            Appointment &amp; Test Booking Portal
        </span>
        
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight" 
            x-text="appointmentType === 'iets_test' ? 'Schedule Your IETS Test' : 'Schedule Your Campus Counseling Session'">
            Schedule Your Campus Counseling Session
        </h1>
        
        <p class="text-sm text-slate-600 max-w-xl mx-auto"
           x-text="appointmentType === 'iets_test' 
               ? 'Select an available test date and time slot. Test slots have a strict 15-student capacity limit for maximum focus and official testing standards.' 
               : 'Select an available date below, choose your convenient time slot, and receive instant confirmation via email and WhatsApp.'">
            Select an available date below, choose your convenient time slot, and receive instant confirmation via email and WhatsApp.
        </p>
    </div>

    <!-- Flash Error / Success Notifications -->
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0 mt-0.5"></i>
            <div>
                <strong class="font-bold block">Booking Notice:</strong>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1 shadow-sm">
            <div class="font-bold flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                <span>Please correct the errors below:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Booking Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-xl space-y-8">

        <!-- Appointment Type Selector (Toggle Cards) -->
        <div class="space-y-3">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                Step 1: Select Appointment Type *
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Campus Counseling Option -->
                <button type="button" 
                        @click="setType('counseling')"
                        :class="appointmentType === 'counseling' 
                            ? 'bg-brand-50/80 border-brand-600 ring-2 ring-brand-500 shadow-md text-brand-950' 
                            : 'bg-slate-50 hover:bg-slate-100/80 border-slate-200 text-slate-700'"
                        class="p-4 rounded-2xl border text-left transition flex items-start gap-3 relative group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition"
                         :class="appointmentType === 'counseling' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-500 border border-slate-200'">
                        <i data-lucide="messages-square" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-sm text-slate-900">Campus Counseling</span>
                            <span x-show="appointmentType === 'counseling'" class="w-2.5 h-2.5 rounded-full bg-brand-600"></span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                            1-on-1 diagnostic advice, admission counseling, course guidance, and campus tours.
                        </p>
                    </div>
                </button>

                <!-- IETS Test Option -->
                <button type="button" 
                        @click="setType('iets_test')"
                        :class="appointmentType === 'iets_test' 
                            ? 'bg-emerald-50/80 border-emerald-600 ring-2 ring-emerald-500 shadow-md text-emerald-950' 
                            : 'bg-slate-50 hover:bg-slate-100/80 border-slate-200 text-slate-700'"
                        class="p-4 rounded-2xl border text-left transition flex items-start gap-3 relative group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition"
                         :class="appointmentType === 'iets_test' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-500 border border-slate-200'">
                        <i data-lucide="file-check-2" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-sm text-slate-900">IETS Test</span>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800">Max 15 Seats</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                            Official IETS exam &amp; mock test sessions. Controlled batches with unique enrollment number.
                        </p>
                    </div>
                </button>
            </div>
        </div>

        <form action="{{ route('appointments.book') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Hidden input for appointment type -->
            <input type="hidden" name="appointment_type" :value="appointmentType">
            <!-- Hidden input to submit selected slot & slot ID -->
            <input type="hidden" name="time_slot" :value="selectedSlot" required>
            <input type="hidden" name="slot_id" :value="selectedSlotId">

            <!-- Step 2: Date & Available Slot Selector -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                    <h3 class="font-bold text-slate-900 text-base"
                        x-text="appointmentType === 'iets_test' ? 'Select IETS Test Date & Time Slot' : 'Select Counseling Date & Time Slot'">
                        Select Appointment Date
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Preferred Date *
                        </label>
                        <input type="date" name="appointment_date" x-model="selectedDate" @change="fetchSlots()" 
                               min="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition shadow-sm">
                        
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1.5">
                            <span>Working days: {{ implode(', ', $settings->working_days ?? ['Mon-Sat']) }}</span>
                            <span x-show="appointmentType === 'iets_test'" class="text-emerald-600 font-semibold">Scheduled test dates</span>
                        </div>
                    </div>

                    <!-- Dynamic Policy Card -->
                    <div>
                        <div class="p-4 rounded-xl border text-xs space-y-1.5 transition"
                             :class="appointmentType === 'iets_test' ? 'bg-emerald-50/60 border-emerald-200/80 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <span class="font-bold block text-xs"
                                  :class="appointmentType === 'iets_test' ? 'text-emerald-950' : 'text-slate-800'">
                                <span x-text="appointmentType === 'iets_test' ? 'IETS Test Session Policy:' : 'Counseling Session Policy:'"></span>
                            </span>
                            <template x-if="appointmentType === 'iets_test'">
                                <div class="space-y-1">
                                    <p>• Strict maximum capacity of <strong>15 students per slot</strong>.</p>
                                    <p>• Unique Enrollment Number (<strong>IETS-2026-XXXXXX</strong>) generated on confirmation.</p>
                                    <p>• Real-time server-side race condition &amp; duplicate check.</p>
                                </div>
                            </template>
                            <template x-if="appointmentType === 'counseling'">
                                <div class="space-y-1">
                                    <p>• Sessions are <strong>{{ $settings->slot_duration_minutes ?? 30 }} minutes</strong> each.</p>
                                    <p>• 1-on-1 counseling with experienced academic advisors.</p>
                                    <p>• Instant confirmation via email and WhatsApp alert.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Slot Selector -->
                <div class="pt-4" x-show="selectedDate">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700">
                            Available Time Slots *
                        </label>
                        <span class="text-xs text-slate-400" x-show="availableSlots.length > 0">
                            Click on an available slot to reserve
                        </span>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loadingSlots" class="py-8 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                        <i data-lucide="loader" class="w-4 h-4 animate-spin text-brand-600"></i>
                        <span>Checking real-time seat availability...</span>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!loadingSlots && availableSlots.length === 0" class="p-6 rounded-2xl bg-amber-50 text-amber-900 border border-amber-200 text-xs text-center font-medium space-y-1">
                        <p class="font-bold text-sm">No scheduled slots available for this date.</p>
                        <p x-text="appointmentType === 'iets_test' 
                            ? 'No IETS test sessions are scheduled on this date. Please check dates like Sept 25, 26, or 28, or pick another upcoming date.' 
                            : 'The academy may be closed or on a scheduled break. Please pick another date.'"></p>
                    </div>

                    <!-- Slot Cards Grid -->
                    <div x-show="!loadingSlots && availableSlots.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <template x-for="slot in availableSlots" :key="slot.time + (slot.id || '')">
                            <div>
                                <!-- Case 1: Time Passed Slot Card (Disabled) -->
                                <template x-if="slot.is_past">
                                    <div class="w-full p-3.5 rounded-2xl border border-slate-200 bg-slate-100/70 text-slate-400 cursor-not-allowed flex items-center justify-between opacity-65 select-none"
                                         title="This time slot has already passed for today and cannot be booked.">
                                        <div class="flex items-center gap-2.5">
                                            <i data-lucide="clock" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                            <div>
                                                <div class="font-bold text-sm text-slate-400 line-through" x-text="slot.time"></div>
                                                <div class="text-[11px] text-slate-400" x-show="slot.end_time" x-text="'Until ' + slot.end_time"></div>
                                                <div class="text-[11px] text-slate-400" x-show="!slot.end_time">Session Passed</div>
                                            </div>
                                        </div>
                                        <span class="text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-600">
                                            Time Passed
                                        </span>
                                    </div>
                                </template>

                                <!-- Case 2: Fully Booked Slot Card (Disabled) -->
                                <template x-if="!slot.is_past && slot.is_full">
                                    <div class="w-full p-3.5 rounded-2xl border border-red-200 bg-red-50/50 text-slate-400 cursor-not-allowed flex items-center justify-between opacity-85 select-none"
                                         title="This slot has reached maximum capacity.">
                                        <div class="flex items-center gap-2.5">
                                            <i data-lucide="lock" class="w-4 h-4 text-red-400 shrink-0"></i>
                                            <div>
                                                <div class="font-bold text-sm text-slate-500 line-through" x-text="slot.time"></div>
                                                <div class="text-[11px] text-red-500 font-semibold" x-text="slot.booked_count + ' / ' + slot.capacity + ' Registered'"></div>
                                            </div>
                                        </div>
                                        <span class="text-[11px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full bg-red-100 text-red-700">
                                            Fully Booked
                                        </span>
                                    </div>
                                </template>

                                <!-- Case 3: Available Slot Button (Active & Clickable) -->
                                <template x-if="!slot.is_past && !slot.is_full">
                                    <button type="button" 
                                            @click="selectSlot(slot)"
                                            :class="selectedSlot === slot.time 
                                                ? (appointmentType === 'iets_test' 
                                                    ? 'bg-emerald-600 text-white border-emerald-600 ring-2 ring-emerald-500 shadow-md shadow-emerald-600/20' 
                                                    : 'bg-brand-600 text-white border-brand-600 ring-2 ring-brand-500 shadow-md shadow-brand-500/20')
                                                : 'bg-slate-50 hover:bg-slate-100/90 text-slate-800 border-slate-200 hover:border-slate-300'"
                                            class="w-full p-3.5 rounded-2xl border text-left transition flex items-center justify-between group">
                                        <div class="flex items-center gap-2.5">
                                            <i data-lucide="clock" class="w-4 h-4 shrink-0" 
                                               :class="selectedSlot === slot.time ? 'text-white' : 'text-slate-400 group-hover:text-slate-600'"></i>
                                            <div>
                                                <div class="font-bold text-sm" x-text="slot.time"></div>
                                                <div class="text-[11px] opacity-80" x-show="slot.end_time" x-text="'Until ' + slot.end_time"></div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-block text-[11px] font-bold px-2 py-0.5 rounded-full"
                                                  :class="selectedSlot === slot.time 
                                                      ? 'bg-white/20 text-white' 
                                                      : (appointmentType === 'iets_test' ? 'bg-emerald-100 text-emerald-800' : 'bg-brand-100 text-brand-800')">
                                                <span x-text="slot.remaining_seats + ' Seats Left'"></span>
                                            </span>
                                            <div class="text-[10px] mt-0.5 opacity-70" x-show="appointmentType === 'iets_test'">
                                                <span x-text="slot.booked_count + ' / ' + slot.capacity + ' Registered'"></span>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Step 3: Student Details & Test Specifications -->
            <div class="space-y-5 pt-4 border-t border-slate-100" x-show="selectedSlot">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">3</span>
                    <h3 class="font-bold text-slate-900 text-base"
                        x-text="appointmentType === 'iets_test' ? 'IETS Candidate Information' : 'Student & Visitor Information'">
                        Your Information
                    </h3>
                </div>

                <!-- Test Type Selection (Shown only when IETS Test is selected) -->
                <div x-show="appointmentType === 'iets_test'" class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200/80 space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-emerald-900">
                        Select IETS Test Type *
                    </label>
                    <select name="test_type" :required="appointmentType === 'iets_test'" 
                            class="w-full px-3.5 py-2.5 bg-white border border-emerald-300 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="IELTS Academic Test" {{ old('test_type') == 'IELTS Academic Test' ? 'selected' : '' }}>IELTS Academic Test (University / Higher Education)</option>
                        <option value="IELTS General Training" {{ old('test_type') == 'IELTS General Training' ? 'selected' : '' }}>IELTS General Training (Work &amp; Permanent Migration)</option>
                        <option value="IELTS Life Skills A1" {{ old('test_type') == 'IELTS Life Skills A1' ? 'selected' : '' }}>IELTS Life Skills A1 (Spouse / Family Visa)</option>
                        <option value="IELTS Life Skills B1" {{ old('test_type') == 'IELTS Life Skills B1' ? 'selected' : '' }}>IELTS Life Skills B1 (Settlement / Citizenship)</option>
                        <option value="UKVI IELTS Academic" {{ old('test_type') == 'UKVI IELTS Academic' ? 'selected' : '' }}>UKVI IELTS Academic (UK Visas and Immigration)</option>
                        <option value="Full-Length Diagnostic Mock Exam" {{ old('test_type') == 'Full-Length Diagnostic Mock Exam' ? 'selected' : '' }}>Full-Length Diagnostic Mock Exam</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Student Full Name *
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Alexander Mitchell"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Email Address *
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="student@example.com"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Phone / WhatsApp *
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+1 555 019 2831"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Secondary WhatsApp (Optional)
                        </label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="For automated WhatsApp updates"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <!-- Conditional Fields for IETS Test -->
                    <template x-if="appointmentType === 'iets_test'">
                        <div class="contents">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    CNIC / Passport Number (Optional)
                                </label>
                                <input type="text" name="cnic_passport" value="{{ old('cnic_passport') }}" placeholder="e.g. 42101-1234567-1 or PK123456"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Course / Target Program (Optional)
                                </label>
                                <input type="text" name="program" value="{{ old('program') }}" placeholder="e.g. UK Undergraduate / Band 7.5 Target"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                            </div>
                        </div>
                    </template>

                    <!-- Purpose Field (for Counseling) -->
                    <div class="sm:col-span-2" x-show="appointmentType === 'counseling'">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Session Purpose *
                        </label>
                        <select name="purpose" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="IETS / IELTS Diagnostic Consultation">IETS / IELTS Diagnostic Consultation</option>
                            <option value="Admissions &amp; Course Enrollment">Admissions &amp; Course Enrollment</option>
                            <option value="Campus Tour &amp; Facility Inspection">Campus Tour &amp; Facility Inspection</option>
                            <option value="Academic Counseling &amp; Study Abroad Guidance">Academic Counseling &amp; Study Abroad Guidance</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Additional Notes / Target Requirements (Optional)
                        </label>
                        <textarea name="message" rows="2" placeholder="Any specific requirements, prior band score, or queries..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('message') }}</textarea>
                    </div>
                </div>

                <!-- Confirmation Bar -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500 text-center sm:text-left">
                        <div>Selected: <strong class="text-slate-900" x-text="selectedDate + ' at ' + selectedSlot"></strong></div>
                        <div class="text-[11px] text-brand-600 font-semibold" 
                             x-text="appointmentType === 'iets_test' ? 'Enrollment number will be generated automatically' : 'Instant confirmation via email'"></div>
                    </div>

                    <button type="submit" 
                            :class="appointmentType === 'iets_test' 
                                ? 'bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 shadow-emerald-500/25' 
                                : 'bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 shadow-brand-500/25'"
                            class="w-full sm:w-auto text-white font-extrabold text-sm px-8 py-3.5 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span x-text="appointmentType === 'iets_test' ? 'Register for IETS Test' : 'Confirm Counseling Booking'">
                            Confirm Appointment Booking
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function appointmentCalendar(defaultType, oldType) {
        return {
            appointmentType: oldType || defaultType || 'counseling',
            selectedDate: '{{ old('appointment_date', date('Y-m-d')) }}',
            selectedSlot: '',
            selectedSlotId: null,
            availableSlots: [],
            loadingSlots: false,
            init() {
                this.fetchSlots();
            },
            setType(type) {
                if (this.appointmentType !== type) {
                    this.appointmentType = type;
                    this.selectedSlot = '';
                    this.selectedSlotId = null;
                    this.fetchSlots();
                }
            },
            selectSlot(slot) {
                if (slot.is_past || slot.is_full || slot.is_disabled || !slot.is_available) {
                    return;
                }
                this.selectedSlot = slot.time;
                this.selectedSlotId = slot.id || null;
            },
            async fetchSlots() {
                if (!this.selectedDate) return;
                this.loadingSlots = true;
                this.selectedSlot = '';
                this.selectedSlotId = null;

                try {
                    const res = await fetch(`{{ route('appointments.slots') }}?date=${this.selectedDate}&type=${this.appointmentType}`);
                    const data = await res.json();
                    if (data.success && Array.isArray(data.slots)) {
                        this.availableSlots = data.slots;
                    } else {
                        this.availableSlots = [];
                    }
                } catch (e) {
                    console.error('Error fetching slots:', e);
                    this.availableSlots = [];
                } finally {
                    this.loadingSlots = false;
                    this.$nextTick(() => {
                        if (window.lucide) {
                            lucide.createIcons();
                        }
                    });
                }
            }
        }
    }
</script>
@endsection
