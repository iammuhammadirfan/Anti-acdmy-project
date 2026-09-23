@extends('layouts.app')

@section('title', 'Appointment Confirmed — Apex Academy')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-xl space-y-6">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center font-bold text-2xl shadow-inner">
            <i data-lucide="check" class="w-8 h-8"></i>
        </div>

        <div class="space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Booking Received</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Appointment Request Confirmed!</h1>
            <p class="text-sm text-slate-600">Your session request has been submitted to the admissions counseling office.</p>
        </div>

        <!-- Appointment Card -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-left space-y-3">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <span class="text-xs font-bold text-slate-400 uppercase">Booking Tracking Code</span>
                <span class="font-mono font-bold text-base text-brand-600">{{ $appointment->booking_code }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-slate-400 block font-semibold">Student Name:</span>
                    <strong class="text-slate-800">{{ $appointment->name }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Email:</span>
                    <strong class="text-slate-800">{{ $appointment->email }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Scheduled Date:</span>
                    <strong class="text-slate-800">{{ $appointment->appointment_date->format('M d, Y') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Time Slot:</span>
                    <strong class="text-slate-800">{{ $appointment->time_slot }}</strong>
                </div>
                <div class="col-span-2">
                    <span class="text-slate-400 block font-semibold">Session Purpose:</span>
                    <strong class="text-slate-800">{{ $appointment->purpose }}</strong>
                </div>
            </div>
        </div>

        <p class="text-xs text-slate-500">
            A confirmation receipt was sent to <strong>{{ $appointment->email }}</strong>. If you provided a WhatsApp number, you will also receive automated booking alerts.
        </p>

        <div class="pt-4 flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                Return to Home
            </a>
            <a href="{{ route('iets') }}" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow">
                Explore IETS Programs
            </a>
        </div>
    </div>
</div>
@endsection
