@extends('layouts.app')

@section('title', 'Privacy Policy — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="py-16 bg-slate-950 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-4">Privacy & Data Governance Policy</h1>
        <p class="text-xs text-slate-400 mb-8">Effective Date: January 1, 2026 &bull; Compliant with GDPR & State Privacy Standards</p>

        <div class="bg-slate-900 rounded-3xl p-8 md:p-10 border border-slate-800 text-slate-300 text-sm leading-relaxed space-y-6">
            <h2 class="text-xl font-bold text-white">1. Information Collection & Usage</h2>
            <p>At Anti Academy & IETS Preparation Institute, we collect personal information you provide when scheduling consultations, registering for diagnostic assessments, submitting inquiries, or communicating with our Agentic AI Advisor. This includes your name, email address, phone number, current English proficiency level, and academic history.</p>

            <h2 class="text-xl font-bold text-white">2. Appointment Scheduling & Double-Booking Safeguards</h2>
            <p>Your calendar appointments and mock test booking requests are stored securely in our database. We use this information solely to coordinate admissions interviews, send appointment confirmation emails via SMTP, and provide optional WhatsApp appointment notifications.</p>

            <h2 class="text-xl font-bold text-white">3. AI Interaction Data</h2>
            <p>Interactions with our embedded autonomous AI Academic Advisor are logged in session logs to improve guidance accuracy, resolve diagnostic queries, and facilitate handoffs to human counselors. We never sell or share conversational data with third-party advertisers.</p>

            <h2 class="text-xl font-bold text-white">4. Cookies & Analytics</h2>
            <p>We use essential cookies to maintain user session state and anonymous analytics to assess page performance and technical SEO metrics.</p>

            <h2 class="text-xl font-bold text-white">5. Contact Our Data Protection Officer</h2>
            <p>If you have any questions about this privacy statement or wish to request data erasure, please contact us at <a href="mailto:privacy@antiacademy.edu" class="text-blue-400 underline">privacy@antiacademy.edu</a>.</p>
        </div>
    </div>
</section>
@endsection
