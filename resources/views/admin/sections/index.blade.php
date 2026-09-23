@extends('layouts.admin')

@section('title', 'Homepage Sections')
@section('page_title', 'Homepage Section Builder')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Homepage Sections &amp; Layout</h2>
            <p class="text-xs text-slate-500">Enable, disable, reorder, and customize the text, images, and buttons of all homepage sections.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-5">Order</th>
                        <th class="py-3.5 px-4">Section Key</th>
                        <th class="py-3.5 px-4">Title / Subtitle</th>
                        <th class="py-3.5 px-4">Button Action</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($sections as $sec)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-5 font-mono text-xs font-bold text-slate-400">#{{ $sec->display_order }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $sec->section_key) }}</span>
                                <span class="block text-[11px] text-slate-400 font-mono">{{ $sec->section_key }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-800 text-xs">{{ $sec->title ?: '—' }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $sec->subtitle }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                @if($sec->button_text)
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded">{{ $sec->button_text }} &rarr; {{ $sec->button_url }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.sections.toggle', $sec) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-2.5 py-1 rounded-full text-xs font-bold uppercase transition 
                                                   {{ $sec->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        {{ $sec->is_active ? 'Visible' : 'Hidden' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('admin.sections.edit', $sec) }}" class="p-1.5 text-slate-500 hover:text-brand-600 inline-block transition font-semibold text-xs">
                                    <i data-lucide="edit-3" class="w-4 h-4 inline mr-1"></i> Edit Section
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
