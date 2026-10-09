@extends('layouts.app')

@section('title', 'Frequently Asked Questions & Admissions Help — ' . ($globalSettings['academy_name'] ?? config('app.name', 'Academy')))

@section('schema_json')
@if(!empty($faqSchema))
<script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
</script>
@endif
@endsection

@section('content')
<section class="relative bg-[#f8fafc] py-16 text-slate-900 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 shadow-xs">
            ❓ Clear Answers, Zero Ambiguity
        </span>
        <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-slate-900">
            Frequently Asked Questions
        </h1>
        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto">
            Everything you need to know about exam formats, score requirements, diagnostic trials, tuition, and admissions advisory.
        </p>

        <!-- Categories -->
        <div class="flex flex-wrap items-center justify-center gap-2 pt-4">
            <a href="{{ route('faq') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($category) ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                All Questions
            </a>
            @foreach(['general' => 'General Inquiries', 'iets' => 'IELTS & English Tests', 'appointments' => 'Evaluation Bookings', 'courses' => 'Course Formats', 'admissions' => 'Global Admissions'] as $key => $lbl)
                <a href="{{ route('faq', ['category' => $key]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $category === $key ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    {{ $lbl }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-white" x-data="{ openFaq: null }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-3">
            @forelse($faqs as $index => $item)
                <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs">
                    <button 
                        @click="openFaq = (openFaq === {{ $index }} ? null : {{ $index }})" 
                        class="w-full px-6 py-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-black transition-colors"
                        type="button">
                        <span class="text-sm sm:text-base">{{ $item->question }}</span>
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-600 transition-transform duration-300" :class="{ 'rotate-180': openFaq === {{ $index }} }"></i>
                        </div>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-cloak class="px-6 pb-6 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        {{ $item->answer }}
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-400">
                    No FAQs found in this category.
                </div>
            @endforelse
        </div>

        <!-- Quick Contact CTA -->
        <div class="mt-16 bg-slate-900 rounded-3xl p-8 text-center text-white shadow-lg">
            <h3 class="text-xl font-black mb-2">Have a question not listed here?</h3>
            <p class="text-slate-400 text-xs sm:text-sm max-w-lg mx-auto mb-6">Ask our autonomous AI Academic Advisor directly using the floating widget in the lower right, or message our admissions counselors.</p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('contact') }}" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs uppercase tracking-wider border border-slate-700 transition-all">
                    Send Direct Inquiry
                </a>
                <a href="{{ route('appointments') }}" class="px-6 py-3 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs uppercase tracking-wider shadow transition-all">
                    Book Free 1-on-1 Consultation
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
