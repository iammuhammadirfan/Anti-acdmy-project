@extends('layouts.admin')

@section('title', 'Edit Email Template: ' . $template->name)
@section('page_title', 'Edit Email Template')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.scheduling.emails') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Templates
        </a>
        <form action="{{ route('admin.scheduling.email.reset', $template) }}" method="POST" onsubmit="return confirm('Reset this email template to its factory default?');">
            @csrf
            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold hover:underline flex items-center gap-1">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset to Default
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
            <div>
                <span class="text-[10px] font-mono font-bold uppercase text-slate-400 block">{{ $template->slug }}</span>
                <h3 class="text-xl font-extrabold text-slate-900">{{ $template->name }}</h3>
                <p class="text-xs text-slate-500">{{ $template->description }}</p>
            </div>

            <form action="{{ route('admin.scheduling.email.update', $template) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject', $template->subject) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Email Body (HTML Supported) *</label>
                        <span class="text-[11px] text-slate-400">Insert tags from sidebar</span>
                    </div>
                    <textarea name="body" rows="14" required
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-brand-500 leading-relaxed">{{ old('body', $template->body) }}</textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.scheduling.emails') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-7 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                        Save Template Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Available Placeholders Panel (1 col) -->
        <div class="space-y-4">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Supported Variables</h4>
                    <p class="text-xs text-slate-400">Click a variable tag to copy it</p>
                </div>

                <div class="space-y-2 text-xs">
                    @foreach($variables as $tag => $label)
                        <button type="button" 
                                onclick="navigator.clipboard.writeText('{{ $tag }}'); alert('Copied {{ $tag }} to clipboard!');"
                                class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-brand-50 border border-slate-200 hover:border-brand-200 text-left transition flex flex-col group">
                            <span class="font-mono font-bold text-brand-600 text-[11px] group-hover:text-brand-700">{{ $tag }}</span>
                            <span class="text-[10px] text-slate-500 mt-0.5">{{ $label }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
