@extends('layouts.app')

@section('title', 'Book an Academic or IELTS Appointment — Apex Academy')
@section('meta_description', 'Schedule a 1-on-1 counseling session, diagnostic mock test, or campus tour. Interactive real-time slot booking calendar.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10" x-data="appointmentCalendar()">
    <!-- Header -->
    <div class="text-center space-y-3">
        <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
            Appointment Booking Calendar
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Schedule Your Campus Counseling Session</h1>
        <p class="text-sm text-slate-600 max-w-xl mx-auto">Select an available date below, choose your convenient time slot, and receive instant confirmation via email and WhatsApp.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xl space-y-8">
        <form action="{{ route('appointments.book') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Step 1: Select Date & Available Slots -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">1</span>
                    <h3 class="font-bold text-slate-900 text-base">Select Appointment Date</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-center">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Preferred Date *</label>
                        <input type="date" name="appointment_date" x-model="selectedDate" @change="fetchSlots()" 
                               min="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        <span class="text-[11px] text-slate-400 mt-1 block">Working days: {{ implode(', ', $settings->working_days ?? ['Mon-Sat']) }}</span>
                    </div>

                    <div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-600 space-y-1">
                            <span class="font-bold text-slate-800 block text-xs">Calendar Policy:</span>
                            <p>• Sessions are <strong>{{ $settings->slot_duration_minutes }} minutes</strong> each.</p>
                            <p>• Double booking is automatically blocked.</p>
                            <p>• Confirmation notifications are sent immediately.</p>
                        </div>
                    </div>
                </div>

                <!-- Slot Selector -->
                <div class="pt-4" x-show="selectedDate">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Available Time Slots *</label>

                    <div x-show="loadingSlots" class="py-6 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                        <i data-lucide="loader" class="w-4 h-4 animate-spin text-brand-600"></i>
                        <span>Checking real-time calendar availability...</span>
                    </div>

                    <div x-show="!loadingSlots && availableSlots.length === 0" class="p-5 rounded-2xl bg-amber-50 text-amber-900 border border-amber-200 text-xs text-center font-medium">
                        No available slots found for this date. The academy may be closed, fully booked, or on a scheduled break. Please pick another date.
                    </div>

                    <div x-show="!loadingSlots && availableSlots.length > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <template x-for="slot in availableSlots" :key="slot">
                            <button type="button" @click="selectedSlot = slot"
                                    :class="selectedSlot === slot 
                                        ? 'bg-brand-600 text-white font-bold border-brand-600 shadow-md shadow-brand-500/30' 
                                        : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border-slate-200'"
                                    class="py-3 px-2 rounded-xl border text-xs font-semibold text-center transition flex items-center justify-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5" :class="selectedSlot === slot ? 'text-white' : 'text-slate-400'"></i>
                                <span x-text="slot"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Hidden input to submit selected slot -->
                    <input type="hidden" name="time_slot" :value="selectedSlot" required>
                </div>
            </div>

            <!-- Step 2: Student Details -->
            <div class="space-y-4 pt-4 border-t border-slate-100" x-show="selectedSlot">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                    <h3 class="font-bold text-slate-900 text-base">Your Information</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Alexander Mitchell"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="alex@example.com"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+1 555 019 2831"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">WhatsApp Number</label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="For WhatsApp updates (optional)"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Appointment Purpose *</label>
                        <select name="purpose" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="IETS / IELTS Diagnostic Consultation">IETS / IELTS Diagnostic Consultation</option>
                            <option value="Admissions &amp; Course Enrollment">Admissions &amp; Course Enrollment</option>
                            <option value="Campus Tour &amp; Facility Inspection">Campus Tour &amp; Facility Inspection</option>
                            <option value="Academic Counseling &amp; Study Abroad Guidance">Academic Counseling &amp; Study Abroad Guidance</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Additional Notes / Inquiries (Optional)</label>
                        <textarea name="message" rows="3" placeholder="Tell us about your current score target or specific questions..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('message') }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div class="text-xs text-slate-500">
                        Selected: <strong class="text-slate-900" x-text="selectedDate + ' at ' + selectedSlot"></strong>
                    </div>
                    <button type="submit" 
                            class="bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 text-white font-extrabold text-sm px-8 py-3.5 rounded-xl shadow-lg shadow-brand-500/25 transition transform hover:-translate-y-0.5">
                        Confirm Appointment Booking
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function appointmentCalendar() {
        return {
            selectedDate: '{{ date('Y-m-d', strtotime('+1 day')) }}',
            selectedSlot: '',
            availableSlots: [],
            loadingSlots: false,
            init() {
                this.fetchSlots();
            },
            async fetchSlots() {
                if (!this.selectedDate) return;
                this.loadingSlots = true;
                this.selectedSlot = '';

                try {
                    const res = await fetch(`{{ route('appointments.slots') }}?date=${this.selectedDate}`);
                    const data = await res.json();
                    if (data.success) {
                        this.availableSlots = data.slots;
                    } else {
                        this.availableSlots = [];
                    }
                } catch (e) {
                    this.availableSlots = [];
                } finally {
                    this.loadingSlots = false;
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                }
            }
        }
    }
</script>
@endsection
