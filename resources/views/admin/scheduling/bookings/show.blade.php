@extends('layouts.admin')

@section('title', 'Booking Details: ' . ($booking->registration_number ?: $booking->booking_code))
@section('page_title', 'Registration Details')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.scheduling.bookings') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to All Bookings
        </a>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Student & Session Details (2 cols) -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                <!-- Registration Banner -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                            {{ $booking->type === 'iets_test' ? 'Official IETS Enrollment Number' : 'Counseling Tracking Code' }}
                        </span>
                        <h3 class="text-2xl font-black font-mono tracking-tight {{ $booking->type === 'iets_test' ? 'text-emerald-600' : 'text-brand-600' }}">
                            {{ $booking->registration_number ?: $booking->booking_code }}
                        </h3>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                            {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : ($booking->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : ($booking->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700'))) }}">
                            {{ $booking->status }}
                        </span>
                    </div>
                </div>

                <!-- Fields Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Student Candidate:</span>
                        <strong class="text-slate-900 text-sm block mt-0.5">{{ $booking->name }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Email Address:</span>
                        <strong class="text-slate-900 text-sm block mt-0.5">{{ $booking->email }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Primary Phone:</span>
                        <strong class="text-slate-900 text-sm block mt-0.5">{{ $booking->phone }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">WhatsApp Contact:</span>
                        <strong class="text-slate-900 text-sm block mt-0.5">{{ $booking->whatsapp ?: $booking->phone }}</strong>
                    </div>

                    @if($booking->cnic_passport)
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">CNIC / Passport Number:</span>
                        <strong class="text-slate-900 text-sm font-mono block mt-0.5">{{ $booking->cnic_passport }}</strong>
                    </div>
                    @endif

                    @if($booking->program)
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Target Course / Program:</span>
                        <strong class="text-slate-900 text-sm block mt-0.5">{{ $booking->program }}</strong>
                    </div>
                    @endif

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Scheduled Date:</span>
                        <strong class="text-brand-600 text-sm block mt-0.5">{{ $booking->appointment_date ? $booking->appointment_date->format('l, F j, Y') : '-' }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase">Time Slot:</span>
                        <strong class="text-brand-600 text-sm block mt-0.5">{{ $booking->time_slot }}</strong>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="text-slate-400 block font-semibold uppercase">Category &amp; Session Subject:</span>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $booking->type === 'iets_test' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                {{ $booking->type === 'iets_test' ? 'IETS Test Session' : 'Campus Counseling' }}
                            </span>
                            <span class="font-bold text-slate-800 text-sm">
                                {{ $booking->test_type ?: $booking->purpose }}
                            </span>
                        </div>
                    </div>

                    @if($booking->message)
                    <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block font-semibold uppercase">Candidate Notes / Inquiries:</span>
                        <p class="text-xs text-slate-700 bg-slate-50 p-3.5 rounded-xl mt-1 leading-relaxed border border-slate-200">{{ $booking->message }}</p>
                    </div>
                    @endif

                    @if($booking->admin_notes)
                    <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block font-semibold uppercase">Administrative Logs / Counselor Notes:</span>
                        <p class="text-xs text-slate-700 bg-amber-50/60 p-3.5 rounded-xl mt-1 leading-relaxed border border-amber-200 whitespace-pre-line">{{ $booking->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reschedule Section -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Reschedule Student to Another Slot</h4>
                        <p class="text-xs text-slate-400">Moves this booking to a new active slot, checks capacity, keeps enrollment number, and emails the student.</p>
                    </div>
                    <i data-lucide="calendar-sync" class="w-5 h-5 text-brand-600"></i>
                </div>

                <form action="{{ route('admin.scheduling.booking.reschedule', $booking) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Select Alternative Available Slot *</label>
                        <select name="new_slot_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="">-- Choose New Date &amp; Slot --</option>
                            @foreach($availableSlots as $altSlot)
                                <option value="{{ $altSlot->id }}">
                                    {{ $altSlot->slot_date->format('M d, Y') }} — {{ $altSlot->start_time }} (Capacity: {{ $altSlot->appointments_count }}/{{ $altSlot->capacity }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Reason for Rescheduling</label>
                        <input type="text" name="admin_notes" placeholder="e.g. Student requested time change or batch re-allocation"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-bold text-xs py-2.5 rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        Confirm Rescheduling &amp; Notify Student
                    </button>
                </form>
            </div>
        </div>

        <!-- Status Management Column (1 col) -->
        <div class="space-y-6">
            <!-- Update Status Box -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h4 class="font-bold text-slate-900 text-sm">Update Booking Status</h4>
                <p class="text-xs text-slate-500">
                    Changing status triggers automated email &amp; WhatsApp alerts. Marking as <strong class="text-rose-600">Cancelled</strong> automatically releases the seat back to available capacity.
                </p>

                <form action="{{ route('admin.scheduling.booking.status', $booking) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Booking Status</label>
                        <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed (Accept)</option>
                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed (Attended)</option>
                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Release Seat)</option>
                            <option value="no_show" {{ $booking->status === 'no_show' ? 'selected' : '' }}>No Show (Absent)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Internal Log / Counselor Note</label>
                        <textarea name="admin_notes" rows="3" placeholder="Add administrative notes or feedback..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $booking->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs py-2.5 rounded-xl shadow-sm transition">
                        Update Status &amp; Dispatch Alerts
                    </button>
                </form>
            </div>

            <!-- Booking Meta Card -->
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 text-xs text-slate-500 space-y-2.5">
                <span class="font-bold text-slate-700 uppercase tracking-wider block text-[10px]">Booking Meta</span>
                <p>Registration Number: <strong class="text-slate-800 font-mono">{{ $booking->registration_number ?: $booking->booking_code }}</strong></p>
                <p>Created At: <strong class="text-slate-800">{{ $booking->created_at->format('M d, Y H:i') }}</strong></p>
                <p>Last Updated: <strong class="text-slate-800">{{ $booking->updated_at->format('M d, Y H:i') }}</strong></p>
                @if($booking->reminder_sent_at)
                    <p class="text-emerald-600 font-semibold">24-hour reminder sent at {{ $booking->reminder_sent_at->format('M d, Y H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
