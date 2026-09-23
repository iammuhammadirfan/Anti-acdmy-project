@extends('layouts.app')

@section('title', 'Terms & Conditions — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="py-16 bg-slate-950 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-4">Terms & Conditions of Enrollment</h1>
        <p class="text-xs text-slate-400 mb-8">Effective Date: January 1, 2026 &bull; Anti Academy & IETS Institute</p>

        <div class="bg-slate-900 rounded-3xl p-8 md:p-10 border border-slate-800 text-slate-300 text-sm leading-relaxed space-y-6">
            <h2 class="text-xl font-bold text-white">1. Academic Protocol & Mock Examinations</h2>
            <p>Enrollment in Anti Academy programs requires strict adherence to academic integrity and punctuality. Proctored mock examinations conducted in our Smart Digital Acoustic Labs follow official Cambridge and IDP rules. Unethical behavior or unapproved aids will result in immediate disqualification.</p>

            <h2 class="text-xl font-bold text-white">2. Appointment Cancellations & Rescheduling</h2>
            <p>Free diagnostic assessments and admissions consultations can be rescheduled up to 24 hours prior to the booked time slot. Failure to attend without prior notice may temporarily restrict future automated bookings.</p>

            <h2 class="text-xl font-bold text-white">3. Score Progression Guarantees</h2>
            <p>Our Band 8.0+ score guarantee applies exclusively to students who achieve 95% attendance across our 12-week comprehensive program, complete all 12 proctored mock trials, and execute assigned homework essays. In cases where the benchmark is not achieved, eligible candidates receive complimentary batch repeat access.</p>

            <h2 class="text-xl font-bold text-white">4. Intellectual Property</h2>
            <p>All curriculum documents, model essay compilations, acoustic shadowing audio files, and lecture recordings remain the intellectual property of Anti Academy and may not be distributed or reproduced without explicit written consent.</p>
        </div>
    </div>
</section>
@endsection
