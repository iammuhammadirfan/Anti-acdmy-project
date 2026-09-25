@extends('layouts.admin')

@section('title', 'Email Notification Templates')
@section('page_title', 'Email Notification Templates')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Automated Email Templates</h2>
            <p class="text-xs text-slate-500">Customize the emails dispatched to students for confirmations, IETS test enrollment numbers, cancellations, and reminders.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($templates as $tpl)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase bg-slate-100 text-slate-600">
                            {{ $tpl->slug }}
                        </span>
                        <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Active
                        </span>
                    </div>

                    <h3 class="text-base font-extrabold text-slate-900">{{ $tpl->name }}</h3>
                    <p class="text-xs text-slate-500">{{ $tpl->description }}</p>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs space-y-1 mt-2">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Default Subject:</span>
                        <div class="font-semibold text-slate-800">{{ $tpl->subject }}</div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400">Updated {{ $tpl->updated_at->diffForHumans() }}</span>
                    <a href="{{ route('admin.scheduling.email.edit', $tpl) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        <span>Edit Template</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
