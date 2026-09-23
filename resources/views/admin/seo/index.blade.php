@extends('layouts.admin')

@section('title', 'Technical SEO & GEO')
@section('page_title', 'Technical SEO & Generative Engine Optimization (GEO)')

@section('content')
<div class="space-y-8" x-data="{ tab: 'pages' }">
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3 text-xs font-bold uppercase tracking-wider">
        <button @click="tab = 'pages'" :class="tab === 'pages' ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl transition shadow-sm">Page Metadata &amp; Schemas</button>
        <button @click="tab = 'redirects'" :class="tab === 'redirects' ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl transition shadow-sm">301 / 302 Redirects</button>
    </div>

    <!-- Page SEO Metadata Table -->
    <div x-show="tab === 'pages'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Page-Specific SEO &amp; GEO Schemas</h3>
                <p class="text-xs text-slate-500">Configure custom meta titles, descriptions, Open Graph images, and Schema.org types.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-5">Target Page</th>
                        <th class="py-3 px-4">SEO Title</th>
                        <th class="py-3 px-4">Meta Description</th>
                        <th class="py-3 px-4">Schema Type</th>
                        <th class="py-3 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pages as $key => $title)
                        @php $rec = $seoRecords->get($key); @endphp
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3.5 px-5 font-bold text-slate-900">
                                {{ $title }}
                                <span class="block text-[11px] text-slate-400 font-mono">/{{ $key === 'home' ? '' : $key }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-medium text-slate-800">{{ $rec ? $rec->seo_title : 'Default Site Title' }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 truncate max-w-xs">{{ $rec ? $rec->meta_description : 'Default institutional description' }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-purple-50 text-purple-700 font-semibold">
                                    {{ $rec ? $rec->schema_type : 'EducationalOrganization' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.seo.edit', $key) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-xs font-bold transition">
                                    Edit SEO &amp; GEO &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Redirects Manager -->
    <div x-show="tab === 'redirects'" x-cloak class="space-y-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-base">Add 301 / 302 Redirect Rule</h3>
            <form action="{{ route('admin.seo.redirects.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Source Path</label>
                    <input type="text" name="source_url" placeholder="/old-ielts-page" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Destination URL</label>
                    <input type="text" name="destination_url" placeholder="/iets" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">HTTP Code</label>
                    <select name="status_code" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="301">301 (Permanent)</option>
                        <option value="302">302 (Temporary)</option>
                    </select>
                </div>
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs py-2.5 px-4 rounded-xl shadow transition">
                    Create Redirect
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Source</th>
                            <th class="py-3 px-4">Destination</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-mono">
                        @forelse($redirects as $redir)
                            <tr>
                                <td class="py-3 px-4 font-bold text-slate-800">{{ $redir->source_url }}</td>
                                <td class="py-3 px-4 text-brand-600">{{ $redir->destination_url }}</td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700">{{ $redir->status_code }}</span></td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.seo.redirects.destroy', $redir) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-sans font-semibold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400 font-sans">No custom redirects configured yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
