@extends('layouts.app')

@section('title', 'Contact & Admissions Office — ' . ($globalSettings['academy_name'] ?? config('app.name', 'Academy')))

@section('content')
<!-- Hero Header (Light Gray Background with Gradient Shade) -->
<section class="relative bg-slate-100 py-12 sm:py-16 lg:py-20 text-slate-900 overflow-hidden border-b border-slate-200">
    <!-- Dot Pattern -->
    <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:28px_28px] opacity-40 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-slate-800 border border-slate-200 shadow-xs uppercase tracking-wider">
            <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
            <span>Admissions Advisory &amp; Inquiries</span>
        </span>
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 max-w-4xl mx-auto leading-tight">
            Get in Touch with <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Our Advisors</span>
        </h1>
        <p class="text-slate-600 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed">
            Have questions regarding upcoming batch schedules, fee structures, or international university partnerships? Our team is here to assist.
        </p>
    </div>
</section>

<!-- Main Contact Section (Light Gray Outer Background, Medium-Dark Softer Gray Cards) -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
            
            <!-- Contact Information & Headquarters Card (Softer Medium Dark Gray) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-[#2c333f] rounded-3xl p-8 border border-slate-600/80 shadow-xl text-white">
                    <h2 class="text-2xl font-bold text-white mb-6">Academy Headquarters</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#3b4453] border border-slate-500/50 flex items-center justify-center flex-shrink-0 text-indigo-300">
                                <i data-lucide="map-pin" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase font-bold text-slate-300 tracking-wider mb-1">Campus Location</h4>
                                <p class="text-slate-100 text-sm leading-relaxed">{{ $settings['address'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#3b4453] border border-slate-500/50 flex items-center justify-center flex-shrink-0 text-emerald-300">
                                <i data-lucide="phone" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase font-bold text-slate-300 tracking-wider mb-1">Direct Phone</h4>
                                <p class="text-slate-100 text-sm font-semibold">
                                    <a href="tel:{{ $settings['phone'] }}" class="hover:text-emerald-300 transition">{{ $settings['phone'] }}</a>
                                    @if(!empty($settings['phone_2']))
                                        <span class="text-slate-400 mx-1.5">•</span>
                                        <a href="tel:{{ $settings['phone_2'] }}" class="hover:text-emerald-300 transition">{{ $settings['phone_2'] }}</a>
                                    @endif
                                </p>
                                <span class="text-xs text-slate-300">{{ $settings['timings'] ?? ($globalSettings['academy_timings'] ?? 'Mon - Sat: 8:00 AM - 7:00 PM') }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#3b4453] border border-slate-500/50 flex items-center justify-center flex-shrink-0 text-sky-300">
                                <i data-lucide="mail" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase font-bold text-slate-300 tracking-wider mb-1">Admissions Desk</h4>
                                <p class="text-slate-100 text-sm font-semibold">{{ $settings['email'] }}</p>
                                <span class="text-xs text-slate-300">Guaranteed response within 4 hours</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#3b4453] border border-slate-500/50 flex items-center justify-center flex-shrink-0 text-teal-300">
                                <i data-lucide="message-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase font-bold text-slate-300 tracking-wider mb-1">WhatsApp Fastline</h4>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}" target="_blank" class="text-teal-300 hover:text-teal-200 text-sm font-semibold inline-flex items-center gap-1">
                                    {{ $settings['whatsapp'] }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#2c333f] p-8 rounded-3xl border border-slate-600/80 text-white shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-2">Prefer In-Person Evaluation?</h3>
                    <p class="text-xs text-slate-300 leading-relaxed mb-4">Book a reserved 30-minute diagnostic session directly on our calendar system.</p>
                    <a href="{{ route('appointments') }}" class="inline-block w-full text-center bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs py-3.5 rounded-xl shadow transition-all">
                        Open Slot Booking Calendar
                    </a>
                </div>
            </div>

            <!-- Contact Inquiry Form Card (Softer Medium Dark Gray Card) -->
            <div class="lg:col-span-7">
                <div class="bg-[#2c333f] rounded-3xl p-8 md:p-10 border border-slate-600/80 shadow-2xl text-white">
                    <h2 class="text-2xl font-bold text-white mb-2">Send an Official Inquiry</h2>
                    <p class="text-sm text-slate-300 mb-8">Fill in your information and our academic counselors will review your request promptly.</p>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 text-sm flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500/40 text-red-200 text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 uppercase tracking-wider mb-2">Your Full Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. David Miller" class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 uppercase tracking-wider mb-2">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. david@example.com" class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 uppercase tracking-wider mb-2">Phone / WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. +1 (555) 000-0000" class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-200 uppercase tracking-wider mb-2">Inquiry Topic</label>
                                <select name="subject" class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-400 transition-colors">
                                    <option value="IELTS Academic Program Inquiry">IELTS Academic Program Inquiry</option>
                                    <option value="General Training / Express Entry Inquiry">General Training / Express Entry Inquiry</option>
                                    <option value="Private 1-on-1 Speaking Clinics">Private 1-on-1 Speaking Clinics</option>
                                    <option value="Global University Admissions Guidance">Global University Admissions Guidance</option>
                                    <option value="Other General Questions">Other General Questions</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-200 uppercase tracking-wider mb-2">Detailed Message *</label>
                            <textarea name="message" rows="5" required placeholder="Please describe your current score level, target band, and questions..." class="w-full bg-[#1e242d] border border-slate-600 rounded-xl p-4 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 transition-colors">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg hover:shadow-indigo-500/20 transition-all flex items-center justify-center gap-2">
                            <span>Submit Official Message</span>
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Student Review & Testimonial Submission Section (Light Gray Outer Background, Softer Medium Dark Gray Form Card) -->
<section id="review-section" class="py-16 bg-slate-100 text-slate-900 relative overflow-hidden border-t border-slate-200 scroll-mt-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        @if(session('review_success'))
            <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-800 text-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-600"></i>
                <div>
                    <p class="font-bold text-slate-900">{{ session('review_success') }}</p>
                    <p class="text-xs text-slate-600">Thank you for sharing your learning journey with us!</p>
                </div>
            </div>
        @endif

        <!-- Softer Medium Dark Gray Review Form Card -->
        <div class="bg-[#2c333f] border border-slate-600/80 rounded-3xl p-8 sm:p-12 shadow-2xl text-white" x-data="{ rating: 5, hoverRating: 0 }">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-300 text-xs font-bold uppercase tracking-wider mb-4">
                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-300"></i>
                    <span>Student Voice &amp; Reviews</span>
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl text-white">Share Your Experience</h2>
                <p class="mt-3 text-slate-300 text-sm sm:text-base">
                    Are you a current or former student? Leave an honest review about our classes, teachers, and test prep atmosphere.
                </p>
            </div>

            <form action="{{ route('reviews.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Interactive Star Rating -->
                <div class="flex flex-col items-center justify-center p-4 bg-[#1e242d] rounded-2xl border border-slate-600">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Select Your Rating</label>
                    <input type="hidden" name="rating" :value="rating">
                    <div class="flex items-center gap-2">
                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <button type="button" 
                                @click="rating = star" 
                                @mouseenter="hoverRating = star" 
                                @mouseleave="hoverRating = 0"
                                class="p-1 transition transform hover:scale-125 focus:outline-none">
                                <svg class="w-8 h-8 transition-colors" 
                                    :class="(hoverRating ? hoverRating >= star : rating >= star) ? 'text-amber-400 fill-amber-400' : 'text-slate-500'" 
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </template>
                    </div>
                    <span class="text-xs font-bold text-amber-300 mt-2" x-text="rating + ' Stars'">5 Stars</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-2">Your Full Name <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Ali Raza" value="{{ old('name') }}"
                            class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 text-sm">
                        @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-2">Course / Test Taken</label>
                        <input type="text" name="course" placeholder="e.g. IELTS Academic / Spoken English" value="{{ old('course') }}"
                            class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 text-sm">
                        @error('course') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-2">Email Address (Optional)</label>
                        <input type="email" name="email" placeholder="ali@example.com" value="{{ old('email') }}"
                            class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-2">Phone / WhatsApp (Optional)</label>
                        <input type="text" name="phone" placeholder="+92 300 1234567" value="{{ old('phone') }}"
                            class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-2">Your Review / Testimonial <span class="text-rose-400">*</span></label>
                    <textarea name="review" rows="4" required placeholder="Tell future students about your learning experience, instructors, mock tests, and environment..."
                        class="w-full bg-[#1e242d] border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-indigo-400 text-sm">{{ old('review') }}</textarea>
                    @error('review') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="text-center pt-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-amber-500/20 hover:shadow-xl transition transform hover:-translate-y-0.5 text-sm">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Submit Student Review</span>
                    </button>
                    <p class="text-xs text-slate-400 mt-2">All submitted reviews are reviewed by the administration before displaying on the homepage.</p>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#review-section') {
            setTimeout(function() {
                const el = document.getElementById('review-section');
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
    });
</script>
@endsection
