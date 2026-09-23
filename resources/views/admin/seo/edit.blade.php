@extends('layouts.admin')

@section('title', 'Edit SEO: ' . $pageName)
@section('page_title', 'Configure Technical SEO & GEO: ' . $pageName)

@section('content')
<div class="max-w-3xl space-y-6">
    <a href="{{ route('admin.seo.index') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to SEO Management
    </a>

    <form action="{{ route('admin.seo.update', $pageKey) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Meta Title Tag (Google Search &amp; Browser)</label>
                <input type="text" name="seo_title" value="{{ old('seo_title', $meta->seo_title) }}" placeholder="e.g. Best IELTS Coaching &amp; Language Academy | Apex Education"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Meta Description Tag</label>
                <textarea name="meta_description" rows="3" placeholder="Compelling 150-160 character snippet describing this page for search engines..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ old('meta_description', $meta->meta_description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Keywords (Comma separated)</label>
                    <input type="text" name="keywords" value="{{ old('keywords', $meta->keywords) }}" placeholder="IELTS, IETS, English course, band 8"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Robots Meta Directive</label>
                    <input type="text" name="robots_meta" value="{{ old('robots_meta', $meta->robots_meta ?: 'index, follow') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Canonical URL Override</label>
                    <input type="url" name="canonical_url" value="{{ old('canonical_url', $meta->canonical_url) }}" placeholder="{{ url('/' . ($pageKey === 'home' ? '' : $pageKey)) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Schema.org Primary Entity</label>
                    <select name="schema_type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="EducationalOrganization" {{ $meta->schema_type === 'EducationalOrganization' ? 'selected' : '' }}>EducationalOrganization</option>
                        <option value="Course" {{ $meta->schema_type === 'Course' ? 'selected' : '' }}>Course</option>
                        <option value="Person" {{ $meta->schema_type === 'Person' ? 'selected' : '' }}>Person (Teacher)</option>
                        <option value="FAQPage" {{ $meta->schema_type === 'FAQPage' ? 'selected' : '' }}>FAQPage</option>
                        <option value="Article" {{ $meta->schema_type === 'Article' ? 'selected' : '' }}>Article / News</option>
                        <option value="VideoObject" {{ $meta->schema_type === 'VideoObject' ? 'selected' : '' }}>VideoObject</option>
                    </select>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Open Graph &amp; Twitter Share Image</label>
                @if($meta->og_image)
                    <div class="mb-3 w-40 h-24 rounded-xl overflow-hidden border border-slate-200">
                        <img src="{{ asset('storage/' . $meta->og_image) }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <input type="file" name="og_image_file" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Custom JSON-LD Schema (GEO)</label>
                <textarea name="custom_schema_json" rows="4" placeholder='{"@@context": "https://schema.org", "@@type": "..."}'
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('custom_schema_json', $meta->custom_schema_json) }}</textarea>
                <span class="text-[10px] text-slate-400 mt-1 block">Optional custom structured data for Google, Bing, and Generative AI engines.</span>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.seo.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold transition">Cancel</a>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition">Save SEO &amp; GEO Metadata</button>
        </div>
    </form>
</div>
@endsection
