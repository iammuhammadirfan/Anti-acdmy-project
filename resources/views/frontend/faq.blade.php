@extends('layouts.app')

@section('title', 'Frequently Asked Questions & Admissions Help — ' . config('app.name', 'Anti Academy'))

@section('schema_json')
@if(!empty($faqSchema))
<script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
</script>
@endif
@endsection

@section('content')
<section class="relative bg-slate-950 py-20 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
            ❓ Clear Answers, Zero Ambiguity
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            Frequently Asked <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Questions</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Everything you need to know about exam formats, score requirements, diagnostic trials, tuition, and admissions advisory.
        </p>

        <!-- Categories -->
        <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
            <a href="{{ route('faq') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($category) ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                All Questions
            </a>
            @foreach(['general' => 'General Inquiries', 'iets' => 'IETS & IELTS Tests', 'appointments' => 'Evaluation Bookings', 'courses' => 'Course Formats', 'admissions' => 'Global Admissions'] as $key => $lbl)
                <a href="{{ route('faq', ['category' => $key]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $category === $key ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    {{ $lbl }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900" x-data="{ openFaq: null }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-4">
            @forelse($faqs as $index => $item)
                <div class="bg-slate-800 rounded-2xl border border-slate-700/80 overflow-hidden transition-all shadow-md">
                    <button 
                        @click="openFaq = (openFaq === {{ $index }} ? null : {{ $index }})" 
                        class="w-full px-6 py-5 text-left flex items-center justify-between gap-4 font-bold text-white hover:text-blue-400 transition-colors"
                        type="button">
                        <span class="text-base md:text-lg">{{ $item->question }}</span>
                        <div class="w-8 h-8 rounded-full bg-slate-900 border border-slate-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-400 transition-transform duration-300" :class="{ 'rotate-180': openFaq === {{ $index }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-collapse class="px-6 pb-6 text-sm text-slate-300 leading-relaxed border-t border-slate-700/40 pt-4">
                        {{ $item->answer }}
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-400">
                    No FAQs found in this category.
                </div>
            @endforelse
        </div>

        <!-- Quick AI or Contact CTA -->
        <div class="mt-16 bg-slate-950 rounded-3xl border border-slate-800 p-8 text-center">
            <h3 class="text-xl font-bold text-white mb-2">Have a question not listed here?</h3>
            <p class="text-slate-400 text-sm max-w-lg mx-auto mb-6">Ask our autonomous AI Academic Advisor directly using the floating widget in the lower right, or message our admissions counselors.</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('contact') }}" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm border border-slate-700 transition-all">
                    Send Direct Inquiry
                </a>
                <a href="{{ route('appointments') }}" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-lg transition-all">
                    Book Free 1-on-1 Consultation
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
