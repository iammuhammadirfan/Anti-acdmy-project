@extends('layouts.app')

@section('title', 'Booking Confirmation — Apex Academy & IETS Center')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/90 shadow-xl space-y-6">
        
        <!-- Status Icon -->
        <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center font-bold text-2xl shadow-inner
            {{ $appointment->type === 'iets_test' ? 'bg-emerald-100 text-emerald-600' : 'bg-brand-100 text-brand-600' }}">
            <i data-lucide="check" class="w-8 h-8"></i>
        </div>

        <!-- Heading -->
        <div class="space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-wider px-3.5 py-1 rounded-full
                {{ $appointment->type === 'iets_test' ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-brand-700 bg-brand-50 border border-brand-200' }}">
                {{ $appointment->type === 'iets_test' ? 'IETS Test Registration Confirmed' : 'Campus Counseling Confirmed' }}
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $appointment->type === 'iets_test' ? 'Your IETS Test Has Been Successfully Scheduled!' : 'Appointment Successfully Booked!' }}
            </h1>
            <p class="text-sm text-slate-600 max-w-md mx-auto">
                {{ $appointment->type === 'iets_test'
                    ? 'A seat has been reserved for you in this official testing session. Please review your enrollment details below.'
                    : 'Your counseling session has been recorded with our admissions guidance office.' }}
            </p>
        </div>

        <!-- Details Card -->
        <div class="bg-slate-50 rounded-2xl p-6 sm:p-7 border border-slate-200 text-left space-y-4">
            <!-- Unique Enrollment / Registration Number -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                        {{ $appointment->type === 'iets_test' ? 'Official Enrollment / Registration Number' : 'Booking Reference Number' }}
                    </span>
                    <span class="font-mono font-extrabold text-lg sm:text-xl {{ $appointment->type === 'iets_test' ? 'text-emerald-600' : 'text-brand-600' }}">
                        {{ $appointment->registration_number ?: $appointment->booking_code }}
                    </span>
                </div>
                <button type="button" 
                        onclick="navigator.clipboard.writeText('{{ $appointment->registration_number ?: $appointment->booking_code }}'); alert('Registration Number copied to clipboard!');"
                        class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-slate-300 text-xs font-semibold text-slate-600 shadow-sm flex items-center gap-1.5 transition">
                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                    <span>Copy</span>
                </button>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                <div>
                    <span class="text-slate-400 block font-semibold">Student / Candidate Name:</span>
                    <strong class="text-slate-800 text-sm">{{ $appointment->name }}</strong>
                </div>

                <div>
                    <span class="text-slate-400 block font-semibold">Email Address:</span>
                    <strong class="text-slate-800 text-sm">{{ $appointment->email }}</strong>
                </div>

                <div>
                    <span class="text-slate-400 block font-semibold">Scheduled Date:</span>
                    <strong class="text-slate-800 text-sm">{{ $appointment->appointment_date->format('l, F j, Y') }}</strong>
                </div>

                <div>
                    <span class="text-slate-400 block font-semibold">Scheduled Time Slot:</span>
                    <strong class="text-slate-800 text-sm">{{ $appointment->time_slot }}</strong>
                </div>

                @if($appointment->type === 'iets_test')
                    <div>
                        <span class="text-slate-400 block font-semibold">Test Type:</span>
                        <strong class="text-slate-800 text-sm">{{ $appointment->test_type ?: 'IETS Official Test' }}</strong>
                    </div>

                    @if($appointment->cnic_passport)
                    <div>
                        <span class="text-slate-400 block font-semibold">CNIC / Passport:</span>
                        <strong class="text-slate-800 text-sm">{{ $appointment->cnic_passport }}</strong>
                    </div>
                    @endif
                @else
                    <div class="sm:col-span-2">
                        <span class="text-slate-400 block font-semibold">Session Purpose:</span>
                        <strong class="text-slate-800 text-sm">{{ $appointment->purpose }}</strong>
                    </div>
                @endif

                <div class="sm:col-span-2 pt-2 border-t border-slate-200/80">
                    <span class="text-slate-400 block font-semibold">Test / Counseling Venue:</span>
                    <strong class="text-slate-800 text-sm">Apex Academy &amp; IETS Center — Main Academic Campus</strong>
                </div>
            </div>

            <!-- Guidelines Notice -->
            <div class="p-3.5 rounded-xl border text-xs leading-relaxed
                {{ $appointment->type === 'iets_test' ? 'bg-emerald-50/70 border-emerald-200 text-emerald-950' : 'bg-blue-50/70 border-blue-200 text-blue-950' }}">
                <strong class="block font-bold mb-1">
                    {{ $appointment->type === 'iets_test' ? 'Exam Day Instructions:' : 'Appointment Day Instructions:' }}
                </strong>
                @if($appointment->type === 'iets_test')
                    <p>• Please arrive at the academy 15 minutes before your scheduled test time.</p>
                    <p>• Keep your registration number (<strong>{{ $appointment->registration_number ?: $appointment->booking_code }}</strong>) and original CNIC or Passport with you upon entry.</p>
                @else
                    <p>• Please arrive on time at the campus admissions desk.</p>
                    <p>• Bring any transcripts or test records you would like reviewed during your counseling session.</p>
                @endif
            </div>
        </div>

        <p class="text-xs text-slate-500">
            A confirmation receipt has been sent to <strong>{{ $appointment->email }}</strong>. Automated SMS/WhatsApp notifications are also dispatched to <strong>{{ $appointment->whatsapp ?: $appointment->phone }}</strong>.
        </p>

        <!-- Actions -->
        <div class="pt-4 flex flex-col sm:flex-row justify-center gap-3">
            <button type="button" onclick="window.print()" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Print Confirmation Slip
            </button>
            <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow">
                Return to Homepage
            </a>
        </div>
    </div>
</div>
@endsection
