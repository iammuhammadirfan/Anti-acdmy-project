@extends('layouts.app')

@section('title', 'Contact & Admissions Office — ' . config('app.name', 'Anti Academy'))

@section('content')
<section class="relative bg-slate-950 py-20 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
            📍 Admissions Advisory & Inquiries
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            Get in Touch with <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Our Advisors</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Have questions regarding upcoming batch schedules, fee structures, or international university partnerships? Our team is here to assist.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Contact Information Card -->
            <div class="lg:col-span-5 space-y-8">
                <div class="bg-slate-800 rounded-3xl p-8 border border-slate-700/80 shadow-xl">
                    <h2 class="text-2xl font-bold text-white mb-6">Academy Headquarters</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center flex-shrink-0 text-blue-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-1">Campus Location</h4>
                                <p class="text-slate-200 text-sm leading-relaxed">{{ $settings['address'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-600/20 border border-emerald-500/30 flex items-center justify-center flex-shrink-0 text-emerald-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-1">Direct Phone</h4>
                                <p class="text-slate-200 text-sm font-semibold">
                                    <a href="tel:{{ $settings['phone'] }}" class="hover:text-emerald-400 transition">{{ $settings['phone'] }}</a>
                                    @if(!empty($settings['phone_2']))
                                        <span class="text-slate-500 mx-1.5">•</span>
                                        <a href="tel:{{ $settings['phone_2'] }}" class="hover:text-emerald-400 transition">{{ $settings['phone_2'] }}</a>
                                    @endif
                                </p>
                                <span class="text-xs text-slate-400">{{ $settings['timings'] ?? ($globalSettings['academy_timings'] ?? 'Mon - Sat: 8:00 AM - 7:00 PM') }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center flex-shrink-0 text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-1">Admissions Desk</h4>
                                <p class="text-slate-200 text-sm font-semibold">{{ $settings['email'] }}</p>
                                <span class="text-xs text-slate-400">Guaranteed response within 4 hours</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-teal-600/20 border border-teal-500/30 flex items-center justify-center flex-shrink-0 text-teal-400">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.586-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.087-.179.181-.077.355.101.174.449.741.964 1.2 0 .001.001.001.001.001.664.593 1.224.777 1.398.864.174.087.275.072.376-.043.101-.116.433-.505.549-.679.116-.174.231-.145.39-.087.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.073.043.419-.101.824z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-1">WhatsApp Fastline</h4>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}" target="_blank" class="text-teal-400 hover:text-teal-300 text-sm font-semibold inline-flex items-center gap-1">
                                    {{ $settings['whatsapp'] }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-900/40 to-slate-900 p-8 rounded-3xl border border-blue-500/20">
                    <h3 class="text-lg font-bold text-white mb-2">Prefer In-Person Evaluation?</h3>
                    <p class="text-xs text-slate-300 leading-relaxed mb-4">Book a reserved 30-minute diagnostic session directly on our calendar system.</p>
                    <a href="{{ route('appointments') }}" class="inline-block w-full text-center bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs py-3 rounded-xl shadow-lg transition-all">
                        Open Slot Booking Calendar
                    </a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-7">
                <div class="bg-slate-800 rounded-3xl p-8 md:p-10 border border-slate-700/80 shadow-2xl">
                    <h2 class="text-2xl font-bold text-white mb-2">Send an Official Inquiry</h2>
                    <p class="text-sm text-slate-400 mb-8">Fill in your information and our academic counselors will review your request promptly.</p>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
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
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Your Full Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. David Miller" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. david@example.com" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Phone / WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. +1 (555) 000-0000" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Inquiry Topic</label>
                                <select name="subject" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors">
                                    <option value="IELTS Academic Program Inquiry">IELTS Academic Program Inquiry</option>
                                    <option value="General Training / Express Entry Inquiry">General Training / Express Entry Inquiry</option>
                                    <option value="Private 1-on-1 Speaking Clinics">Private 1-on-1 Speaking Clinics</option>
                                    <option value="Global University Admissions Guidance">Global University Admissions Guidance</option>
                                    <option value="Other General Questions">Other General Questions</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Detailed Message *</label>
                            <textarea name="message" rows="5" required placeholder="Please describe your current score level, target band, and questions..." class="w-full bg-slate-900 border border-slate-700 rounded-xl p-4 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-xl hover:shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                            <span>Submit Official Message</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Student Review & Testimonial Submission Section -->
<section class="py-16 bg-slate-900 text-white relative overflow-hidden border-t border-slate-800">
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        @if(session('review_success'))
            <div class="mb-8 p-4 bg-emerald-500/20 border border-emerald-500/40 rounded-2xl text-emerald-300 text-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-400"></i>
                <div>
                    <p class="font-bold text-white">{{ session('review_success') }}</p>
                    <p class="text-xs text-emerald-200/80">Thank you for sharing your learning journey with us!</p>
                </div>
            </div>
        @endif

        <div class="bg-slate-800/80 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-8 sm:p-12 shadow-2xl" x-data="{ rating: 5, hoverRating: 0 }">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-wider mb-4">
                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400"></i>
                    <span>Student Voice & Reviews</span>
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl text-white">Share Your Experience</h2>
                <p class="mt-3 text-slate-300 text-sm sm:text-base">
                    Are you a current or former student? Leave an honest review about our classes, teachers, and test prep atmosphere.
                </p>
            </div>

            <form action="{{ route('reviews.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Interactive Star Rating -->
                <div class="flex flex-col items-center justify-center p-4 bg-slate-900/60 rounded-2xl border border-slate-700/50">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Select Your Rating</label>
                    <input type="hidden" name="rating" :value="rating">
                    <div class="flex items-center gap-2">
                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <button type="button" 
                                @click="rating = star" 
                                @mouseenter="hoverRating = star" 
                                @mouseleave="hoverRating = 0"
                                class="p-1 transition transform hover:scale-125 focus:outline-none">
                                <svg class="w-8 h-8 transition-colors" 
                                    :class="(hoverRating ? hoverRating >= star : rating >= star) ? 'text-amber-400 fill-amber-400' : 'text-slate-600'" 
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </template>
                    </div>
                    <span class="text-xs font-bold text-amber-400 mt-2" x-text="rating + ' Stars'">5 Stars</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Your Full Name <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Ali Raza" value="{{ old('name') }}"
                            class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm">
                        @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Course / Test Taken</label>
                        <input type="text" name="course" placeholder="e.g. IELTS Academic / Spoken English" value="{{ old('course') }}"
                            class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm">
                        @error('course') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Email Address (Optional)</label>
                        <input type="email" name="email" placeholder="ali@example.com" value="{{ old('email') }}"
                            class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Phone / WhatsApp (Optional)</label>
                        <input type="text" name="phone" placeholder="+92 300 1234567" value="{{ old('phone') }}"
                            class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Your Review / Testimonial <span class="text-rose-400">*</span></label>
                    <textarea name="review" rows="4" required placeholder="Tell future students about your learning experience, instructors, mock tests, and environment..."
                        class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm">{{ old('review') }}</textarea>
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
