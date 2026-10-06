@extends('layouts.app')

@php
    $siteName = $globalSettings['academy_name'] ?? \App\Models\Setting::get('academy_name', 'Academy');
    $siteLogo = $globalSettings['academy_logo'] ?? \App\Models\Setting::get('academy_logo');
    $siteAddress = $globalSettings['contact_address'] ?? \App\Models\Setting::get('contact_address', '124 Academic Boulevard, Knowledge Park');
    $sitePhone = $globalSettings['contact_phone'] ?? \App\Models\Setting::get('contact_phone', '+1 (555) 234-5678');
    $siteEmail = $globalSettings['contact_email'] ?? \App\Models\Setting::get('contact_email', 'info@antiacademy.edu');
    $regCode = $appointment->registration_number ?: $appointment->booking_code;
    $isIets = $appointment->type === 'iets_test';
@endphp

@section('title', 'Official Admit Card & Registration Slip — ' . $regCode)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 sm:py-12">

    <!-- Screen-Only Actions Header -->
    <div class="no-print space-y-5 mb-8 text-center">
        <!-- Status Pill -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-extrabold {{ $isIets ? 'bg-emerald-50 text-emerald-800 border border-emerald-300' : 'bg-brand-50 text-brand-800 border border-brand-300' }}">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ $isIets ? 'IELTS Official Test Registration Confirmed' : 'Campus Counseling Appointment Confirmed' }}</span>
        </div>

        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            {{ $isIets ? 'Official Candidate Examination Admit Card' : 'Official Academic Consultation Pass' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto">
            Your seat is reserved. Print on <strong>1 single sheet</strong> or download as a high-resolution <strong>PNG image</strong>.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <!-- Download Image Button -->
            <button type="button" 
                    id="download-img-btn"
                    onclick="downloadSlipAsImage()" 
                    class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-600/25 flex items-center gap-2 transition cursor-pointer">
                <i data-lucide="image-down" class="w-4 h-4"></i>
                <span id="download-btn-text">Download as Image (PNG)</span>
            </button>

            <!-- Print Button (Guaranteed 1 Page) -->
            <button type="button" 
                    onclick="window.print()" 
                    class="px-5 py-3 rounded-xl bg-slate-900 hover:bg-black active:scale-95 text-white font-bold text-xs sm:text-sm shadow-lg flex items-center gap-2 transition cursor-pointer">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Print Official Slip (1-Page)</span>
            </button>

            <!-- Copy Code -->
            <button type="button" 
                    onclick="navigator.clipboard.writeText('{{ $regCode }}'); showToast('Copied: {{ $regCode }}');"
                    class="px-4 py-3 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 active:scale-95 text-slate-700 font-bold text-xs sm:text-sm shadow-sm flex items-center gap-2 transition cursor-pointer">
                <i data-lucide="copy" class="w-4 h-4"></i>
                <span>Copy Reg #</span>
            </button>

            <!-- Back to Home -->
            <a href="{{ route('home') }}" class="px-4 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm transition">
                Return to Home
            </a>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- OFFICIAL HIGH-END ADMIT CARD & EXAMINATION SLIP (Guaranteed 1-Page A4)   -->
    <!-- ========================================================================= -->
    <div class="flex justify-center">
        <div id="printable-slip" 
             style="width: 100%; max-width: 780px; background-color: #ffffff; color: #0f172a; line-height: 1.5; font-family: 'Plus Jakarta Sans', Arial, sans-serif; border: 2px solid #0f172a; border-radius: 4px;"
             class="p-6 sm:p-8 shadow-xl relative overflow-hidden">
            
            <!-- Watermark Security Emblem -->
            <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; opacity: 0.03; pointer-events: none; user-select: none;">
                <span style="font-size: 5rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.25em; color: #0f172a; transform: rotate(-25deg);">
                    OFFICIAL
                </span>
            </div>

            <!-- Top Academic Header -->
            <div class="relative z-10 border-b-2 border-slate-900 pb-4">
                <div class="flex items-center justify-between gap-4">
                    <!-- Left: Logo + Institution Name -->
                    <div class="flex items-center gap-3.5">
                        @if(!empty($siteLogo) && file_exists(public_path('storage/' . $siteLogo)))
                            <img src="{{ asset('storage/' . $siteLogo) }}" 
                                 alt="{{ $siteName }}" 
                                 crossorigin="anonymous"
                                 class="h-14 sm:h-16 w-auto max-w-[140px] object-contain shrink-0">
                        @else
                            <div class="w-14 h-14 rounded-lg bg-slate-900 text-white flex items-center justify-center font-black text-2xl shrink-0 shadow-sm">
                                {{ substr($siteName, 0, 1) }}
                            </div>
                        @endif

                        <div>
                            <h2 class="text-lg sm:text-xl font-black uppercase text-slate-900 tracking-tight leading-snug">
                                {{ $siteName }}
                            </h2>
                            <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-600 block">
                                Directorate of Admissions &amp; International Testing
                            </span>
                            <span class="text-[10px] text-slate-500 block mt-0.5">
                                {{ $siteAddress }} • Tel: {{ $sitePhone }}
                            </span>
                        </div>
                    </div>

                    <!-- Right: Verified Badge & Issue Timestamp -->
                    <div class="text-right shrink-0">
                        <div id="slip-confirmed-badge" 
                             style="display: inline-block; background-color: #059669; color: #ffffff !important; padding: 6px 14px; border-radius: 20px; font-weight: 800; font-size: 11px; line-height: 15px; text-align: center; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                            &#10003; CONFIRMED
                        </div>
                        <div style="font-size: 10px; color: #64748b; font-family: monospace; margin-top: 4px;">
                            Issued: {{ now()->format('M d, Y • h:i A') }}
                        </div>
                    </div>
                </div>

                <!-- Ribbon Title -->
                <div class="mt-3.5 pt-2.5 border-t border-slate-200 flex items-center justify-between">
                    <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-900">
                        {{ $isIets ? 'Official Candidate Admit Card & Examination Slip' : 'Campus Admissions & Counseling Registration Pass' }}
                    </span>
                    <span style="display: inline-block; font-size: 10px; font-weight: 800; text-transform: uppercase; padding: 3px 8px; border-radius: 4px; background-color: #f1f5f9; color: #334155 !important; border: 1px solid #cbd5e1; line-height: 14px;">
                        Valid for Entry
                    </span>
                </div>
            </div>

            <!-- Enrollment Number & Barcode Strip -->
            <div style="position: relative; z-index: 10; margin: 16px 0; padding: 14px 18px; border-radius: 6px; background-color: #f8fafc; border: 1px solid #cbd5e1;" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block">
                        {{ $isIets ? 'Official Candidate Enrollment Number' : 'Booking Verification Reference' }}
                    </span>
                    <div style="font-size: 26px; font-weight: 900; font-family: monospace; letter-spacing: 1px; color: #047857; padding: 2px 0;">
                        {{ $regCode }}
                    </div>
                    <span class="text-[10px] text-slate-500 block">
                        Present this registration number to the testing supervisor upon campus arrival.
                    </span>
                </div>

                <!-- Crisp Barcode SVG -->
                <div class="flex flex-col items-start sm:items-end justify-center shrink-0">
                    <svg class="h-9 w-44 sm:w-48 text-slate-900" viewBox="0 0 200 38" fill="#0f172a">
                        <rect x="0" y="0" width="3" height="38"/>
                        <rect x="5" y="0" width="2" height="38"/>
                        <rect x="9" y="0" width="4" height="38"/>
                        <rect x="16" y="0" width="2" height="38"/>
                        <rect x="20" y="0" width="5" height="38"/>
                        <rect x="28" y="0" width="2" height="38"/>
                        <rect x="33" y="0" width="3" height="38"/>
                        <rect x="39" y="0" width="2" height="38"/>
                        <rect x="44" y="0" width="4" height="38"/>
                        <rect x="51" y="0" width="2" height="38"/>
                        <rect x="56" y="0" width="5" height="38"/>
                        <rect x="64" y="0" width="3" height="38"/>
                        <rect x="70" y="0" width="2" height="38"/>
                        <rect x="75" y="0" width="4" height="38"/>
                        <rect x="82" y="0" width="3" height="38"/>
                        <rect x="88" y="0" width="2" height="38"/>
                        <rect x="93" y="0" width="5" height="38"/>
                        <rect x="101" y="0" width="2" height="38"/>
                        <rect x="106" y="0" width="4" height="38"/>
                        <rect x="113" y="0" width="3" height="38"/>
                        <rect x="119" y="0" width="2" height="38"/>
                        <rect x="124" y="0" width="5" height="38"/>
                        <rect x="132" y="0" width="3" height="38"/>
                        <rect x="138" y="0" width="2" height="38"/>
                        <rect x="143" y="0" width="4" height="38"/>
                        <rect x="150" y="0" width="2" height="38"/>
                        <rect x="155" y="0" width="5" height="38"/>
                        <rect x="163" y="0" width="3" height="38"/>
                        <rect x="169" y="0" width="2" height="38"/>
                        <rect x="174" y="0" width="4" height="38"/>
                        <rect x="181" y="0" width="2" height="38"/>
                        <rect x="186" y="0" width="4" height="38"/>
                        <rect x="193" y="0" width="3" height="38"/>
                        <rect x="198" y="0" width="2" height="38"/>
                    </svg>
                    <span class="text-[9px] font-mono font-bold tracking-widest text-slate-600 mt-0.5">
                        * {{ $regCode }} *
                    </span>
                </div>
            </div>

            <!-- Two-Column Candidate & Schedule Cards (No text clipping, explicit padding) -->
            <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                
                <!-- Box 1: Candidate Profile -->
                <div class="border border-slate-300 rounded-lg p-3.5 bg-white space-y-2">
                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-1 flex justify-between">
                        <span>1. Candidate Profile</span>
                        <span class="text-slate-400 font-mono">VERIFIED</span>
                    </div>

                    <div class="pt-1">
                        <span class="text-[10px] text-slate-500 font-semibold uppercase block">Full Name:</span>
                        <span class="text-sm font-bold text-slate-950 block leading-snug">{{ $appointment->name }}</span>
                    </div>

                    <div class="pt-0.5">
                        <span class="text-[10px] text-slate-500 font-semibold uppercase block">Email Address:</span>
                        <span class="text-xs font-medium text-slate-800 block break-all leading-normal">{{ $appointment->email }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-0.5">
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold uppercase block">Phone:</span>
                            <span class="text-xs font-bold text-slate-900 block font-mono">{{ $appointment->phone }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 font-semibold uppercase block">WhatsApp:</span>
                            <span class="text-xs font-bold text-slate-900 block font-mono">{{ $appointment->whatsapp ?: $appointment->phone }}</span>
                        </div>
                    </div>

                    @if($appointment->cnic_passport)
                    <div class="pt-0.5">
                        <span class="text-[10px] text-slate-500 font-semibold uppercase block">CNIC / Passport ID:</span>
                        <span class="text-xs font-bold text-slate-950 font-mono block">{{ $appointment->cnic_passport }}</span>
                    </div>
                    @endif
                </div>

                <!-- Box 2: Test & Venue Schedule -->
                <div class="border border-slate-300 rounded-lg p-3.5 bg-white space-y-2">
                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-1 flex justify-between">
                        <span>2. Examination Schedule</span>
                        <span style="color: #047857 !important; font-weight: 800; font-size: 10px; letter-spacing: 0.5px;">ACTIVE SEAT</span>
                    </div>

                    <div class="pt-1">
                        <span class="text-[10px] text-slate-500 font-semibold uppercase block">Session Type:</span>
                        <span class="text-sm font-bold text-slate-950 block leading-snug">
                            {{ $isIets ? ($appointment->test_type ?: 'IELTS Mock Test') : ($appointment->purpose ?: 'Campus Counseling') }}
                        </span>
                    </div>

                    <div class="pt-0.5">
                        <span class="text-[10px] text-slate-500 font-semibold uppercase block">Scheduled Date:</span>
                        <span class="text-xs font-bold text-slate-950 block leading-normal">
                            {{ $appointment->appointment_date ? $appointment->appointment_date->format('l, F j, Y') : '-' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-0.5 items-center">
                        <div>
                            <span style="display: block; font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Time Slot:</span>
                            <div id="slip-time-slot" 
                                 style="display: inline-block; background-color: #0f172a; color: #ffffff !important; padding: 5px 12px; border-radius: 6px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 13px; font-weight: 800; line-height: 20px; height: auto;">
                                {{ $appointment->time_slot }}
                            </div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Reporting:</span>
                            <span style="display: block; font-size: 12px; font-weight: 800; color: #e11d48 !important; line-height: 20px;">15 Mins Prior</span>
                        </div>
                    </div>

                    <div class="pt-0.5">
                        <span class="text-[10px] text-slate-500 font-semibold uppercase block">Examination Venue:</span>
                        <span class="text-xs font-medium text-slate-800 block leading-snug">
                            {{ $siteName }} — Main Campus Testing Suite
                        </span>
                    </div>
                </div>
            </div>

            <!-- Mandatory Instructions Box -->
            <div style="position: relative; z-index: 10; margin: 16px 0; padding: 12px 16px; border-radius: 6px; background-color: #fffbeb; border: 1px solid #fcd34d; color: #0f172a;">
                <div style="font-weight: 800; text-transform: uppercase; font-size: 10px; color: #78350f; letter-spacing: 0.5px; margin-bottom: 5px;">
                    ⚠ Mandatory Candidate Guidelines:
                </div>
                <ul style="font-size: 11px; color: #1e293b; margin: 0; padding-left: 18px; line-height: 1.6;">
                    <li>Report to the reception desk at least <strong>15 minutes prior</strong> to the scheduled time slot.</li>
                    <li>Original Government-issued <strong>CNIC or Passport</strong> is required for physical identity verification.</li>
                    <li>Keep this printed admit card or digital PNG image with you upon entry.</li>
                    <li>Electronic gadgets and unauthorized materials must be deposited at the locker desk before entering.</li>
                </ul>
            </div>

            <!-- Footer: Security Hash & Dynamic Academy Stamp Box -->
            <div style="position: relative; z-index: 10; padding-top: 14px; border-top: 2px solid #0f172a;" class="flex items-end justify-between gap-4">
                <div class="space-y-1">
                    <span style="font-size: 9px; font-family: monospace; color: #64748b; display: block;">
                        Security Hash: {{ hash('sha256', $regCode . $appointment->email . $appointment->time_slot) }}
                    </span>
                    <p style="font-size: 10px; color: #475569; font-weight: 500; margin: 0; max-width: 400px; line-height: 1.4;">
                        Computer-generated official admit slip issued by {{ $siteName }} Board of Examinations.
                    </p>
                </div>

                <!-- Dynamic Directorate Stamp Box (Always updates with Academy Name) -->
                <div style="text-align: center; flex-shrink: 0; border: 1px solid #94a3b8; border-radius: 6px; padding: 8px 16px; background-color: #f8fafc;">
                    <div style="font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">
                        {{ strtoupper($siteName) }}
                    </div>
                    <div style="font-family: Georgia, serif; font-style: italic; font-weight: bold; color: #0f172a; font-size: 12px; border-bottom: 1px solid #cbd5e1; padding: 2px 0;">
                        Examination Directorate
                    </div>
                    <span style="font-size: 9px; color: #047857 !important; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; display: block; margin-top: 3px;">
                        ● DIGITALLY AUTHORIZED ●
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- Screen-Only Bottom Help Info -->
    <div class="no-print mt-6 text-center text-xs text-slate-500">
        A copy of this confirmation has also been sent to <strong>{{ $appointment->email }}</strong> and WhatsApp <strong>{{ $appointment->whatsapp ?: $appointment->phone }}</strong>.
    </div>

</div>

<!-- Toast notification element -->
<div id="toast" class="no-print fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-xl transition-all duration-300 opacity-0 pointer-events-none">
</div>
@endsection

@section('scripts')
<!-- html2canvas library for high-resolution PNG rendering and download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    // Toast notification helper
    function showToast(msg) {
        const toast = document.getElementById('toast');
        if (!toast) return;
        toast.innerText = msg;
        toast.classList.remove('opacity-0', 'pointer-events-none');
        toast.classList.add('opacity-100');
        setTimeout(() => {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0', 'pointer-events-none');
        }, 2200);
    }

    // High-Resolution Image Download (PNG) matching the print document perfectly
    function downloadSlipAsImage() {
        const slip = document.getElementById('printable-slip');
        const btn = document.getElementById('download-img-btn');
        const btnText = document.getElementById('download-btn-text');
        if (!slip || !btn) return;

        const originalText = btnText.innerText;
        btnText.innerText = 'Generating High-Res Image...';
        btn.disabled = true;

        const executeCapture = () => {
            html2canvas(slip, {
                scale: 2, // 2x crisp retina resolution
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false,
                scrollX: 0,
                scrollY: 0,
                windowWidth: 1200,
                onclone: (clonedDoc) => {
                    const clonedSlip = clonedDoc.getElementById('printable-slip');
                    if (clonedSlip) {
                        clonedSlip.style.width = '780px';
                        clonedSlip.style.maxWidth = '780px';
                        clonedSlip.style.boxShadow = 'none';
                        clonedSlip.style.borderRadius = '0px'; // Pure rectangular sheet, eliminates black corner cutouts
                        clonedSlip.style.border = '2px solid #0f172a';
                        clonedSlip.style.backgroundColor = '#ffffff';
                        clonedSlip.style.margin = '0 auto';
                        clonedSlip.style.padding = '24px 28px';

                        const confirmedBadge = clonedSlip.querySelector('#slip-confirmed-badge');
                        if (confirmedBadge) {
                            confirmedBadge.style.display = 'inline-block';
                            confirmedBadge.style.backgroundColor = '#059669';
                            confirmedBadge.style.color = '#ffffff';
                            confirmedBadge.style.fontWeight = '800';
                            confirmedBadge.style.fontSize = '11px';
                            confirmedBadge.style.lineHeight = '15px';
                            confirmedBadge.style.padding = '6px 14px';
                            confirmedBadge.style.borderRadius = '20px';
                            confirmedBadge.style.textAlign = 'center';
                        }

                        const timeSlotPill = clonedSlip.querySelector('#slip-time-slot');
                        if (timeSlotPill) {
                            timeSlotPill.style.display = 'inline-block';
                            timeSlotPill.style.backgroundColor = '#0f172a';
                            timeSlotPill.style.color = '#ffffff';
                            timeSlotPill.style.fontWeight = '800';
                            timeSlotPill.style.fontSize = '13px';
                            timeSlotPill.style.lineHeight = '20px';
                            timeSlotPill.style.padding = '5px 12px';
                            timeSlotPill.style.borderRadius = '6px';
                        }
                    }
                }
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Admit_Slip_{{ $regCode }}.png';
                link.href = canvas.toDataURL('image/png', 1.0);
                link.click();

                btnText.innerText = '✓ Image Downloaded!';
                showToast('Slip downloaded successfully as PNG image!');

                setTimeout(() => {
                    btnText.innerText = originalText;
                    btn.disabled = false;
                }, 2500);
            }).catch(err => {
                console.error('Image generation error:', err);
                alert('Could not download image directly. Please use the Print button to Save as PDF.');
                btnText.innerText = originalText;
                btn.disabled = false;
            });
        };

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(executeCapture);
        } else {
            executeCapture();
        }
    }
</script>

<style>
    /* =========================================================================
       ABSOLUTE PRINT ISOLATION: GUARANTEED 1-PAGE & ZERO WEBSITE ARTIFACTS
       ========================================================================= */
    @page {
        size: A4 portrait;
        margin: 6mm 8mm;
    }

    @media print {
        /* Completely hide every element on page */
        body * {
            visibility: hidden !important;
        }

        /* Make only the slip and its descendants visible */
        #printable-slip, #printable-slip * {
            visibility: visible !important;
        }

        /* Fix slip to top-left of page with zero website margins */
        #printable-slip {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 16px 20px !important;
            border: 2px solid #0f172a !important;
            border-radius: 0px !important;
            box-shadow: none !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            page-break-after: avoid !important;
            page-break-before: avoid !important;
        }

        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection
