@extends('layouts.app')

@section('title', '404 Service Notice — ' . ($globalSettings['academy_name'] ?? 'Academy'))

@section('content')
<div class="min-h-[55vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-xl w-full text-center space-y-6 bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/90 shadow-xl">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shadow-xs">
            <i data-lucide="alert-octagon" class="w-8 h-8"></i>
        </div>

        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-800">
                Notice 404
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Service Notice
            </h1>
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 font-semibold text-sm leading-relaxed">
                {{ $exception->getMessage() ?: '404 cant creat slot plz conct adminstration have isue your webste' }}
            </div>
        </div>

        <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span>Return to Home</span>
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span>Contact Administration</span>
            </a>
        </div>
    </div>
</div>
@endsection
