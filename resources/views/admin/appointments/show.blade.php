@extends('layouts.admin')

@section('title', 'Appointment Details: ' . $appointment->booking_code)
@section('page_title', 'Appointment ' . $appointment->booking_code)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.appointments.index') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Appointments
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-mono font-bold text-slate-400 block">TRACKING ID</span>
                        <h3 class="text-xl font-extrabold text-slate-900">{{ $appointment->booking_code }}</h3>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                        {{ $appointment->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($appointment->status === 'pending' ? 'bg-amber-100 text-amber-800' : ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-700')) }}">
                        {{ $appointment->status }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Student / Visitor</span>
                        <span class="font-bold text-slate-900 block mt-0.5">{{ $appointment->name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Email</span>
                        <span class="font-bold text-slate-900 block mt-0.5">{{ $appointment->email }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Phone</span>
                        <span class="font-bold text-slate-900 block mt-0.5">{{ $appointment->phone }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase">WhatsApp</span>
                        <span class="font-bold text-slate-900 block mt-0.5">{{ $appointment->whatsapp ?: $appointment->phone }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Date</span>
                        <span class="font-bold text-brand-600 block mt-0.5">{{ $appointment->appointment_date->format('l, F j, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Time Slot</span>
                        <span class="font-bold text-brand-600 block mt-0.5">{{ $appointment->time_slot }}</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Session Purpose</span>
                        <span class="font-bold text-slate-800 block mt-0.5">{{ $appointment->purpose }}</span>
                    </div>
                    @if($appointment->message)
                        <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                            <span class="text-xs text-slate-400 block font-semibold uppercase">Student's Message</span>
                            <p class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl mt-1 leading-relaxed">{{ $appointment->message }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status & Counselor Action Panel -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h4 class="font-bold text-slate-900 text-sm">Update Appointment Status</h4>
                <p class="text-xs text-slate-500">Changing status automatically triggers automated email &amp; WhatsApp alerts to student.</p>

                <form action="{{ route('admin.appointments.status', $appointment) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">New Status</label>
                        <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed (Accept)</option>
                            <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Reject)</option>
                            <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed (Attended)</option>
                            <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>No Show (Absent)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Internal Notes / Instructions</label>
                        <textarea name="admin_notes" rows="3" placeholder="Notes for counselor or included in student confirmation email..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $appointment->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs py-2.5 rounded-xl shadow transition">
                        Update Status &amp; Notify
                    </button>
                </form>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 text-xs text-slate-500 space-y-2">
                <span class="font-bold text-slate-700 uppercase tracking-wider block text-[10px]">Notification Status</span>
                <p>Emails: <span class="text-emerald-600 font-semibold">Active (SMTP)</span></p>
                <p>WhatsApp: <span class="text-emerald-600 font-semibold">Configured</span></p>
                <p class="text-[11px] text-slate-400 pt-1">Booking registered at: {{ $appointment->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
