@extends('layouts.admin')

@section('title', 'Website Page Visibility Manager')
@section('page_title', 'Website Page Visibility Control')

@section('content')
<div class="space-y-6" x-data="{
    search: '',
    selectedPages: {{ json_encode($visibleKeys) }},
    allPageKeys: {{ json_encode(array_keys($allPages)) }},
    selectAll() {
        this.selectedPages = [...this.allPageKeys];
    },
    deselectAll() {
        this.selectedPages = [];
    },
    isPageSelected(key) {
        return this.selectedPages.includes(key);
    },
    togglePage(key) {
        if (this.selectedPages.includes(key)) {
            this.selectedPages = this.selectedPages.filter(k => k !== key);
        } else {
            this.selectedPages.push(key);
        }
    }
}">

    <!-- Page Header & Admin Rights Notice -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 p-6 rounded-3xl text-white shadow-xl">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-400/20 text-amber-300 border border-amber-400/30">
                    <i data-lucide="shield-alert" class="w-3 h-3 inline mr-1"></i> Admin Exclusive Control
                </span>
                <span class="text-xs text-slate-400">• Real-Time Website Navigation Synced</span>
            </div>
            <h2 class="text-2xl font-black tracking-tight text-white">صفحات کی نمائش و کنٹرول (Page Visibility Control)</h2>
            <p class="text-xs text-slate-300 max-w-2xl">
                جس جس پیج پر آپ کلک کریں گے، ویب سائٹ پر صرف وہی پیج شو ہو گا۔ غیر منتخب شدہ پیجز ویب سائٹ نیویگیشن، موبائل مینو اور فوٹر سے خودکار طور پر غائب ہو جائیں گے اور ان تک عوامی رسائی بند ہو جائے گی۔
            </p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold text-xs px-4 py-2.5 rounded-xl border border-white/20 transition">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Preview Website</span>
            </a>
        </div>
    </div>

    <!-- Stats & Quick Actions Toolbar -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Counters -->
        <div class="flex flex-wrap items-center gap-5 text-xs font-bold">
            <div class="flex items-center gap-2 text-slate-700">
                <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                <span>Total Registered Pages: <strong class="text-slate-900">{{ $totalCount }}</strong></span>
            </div>
            <div class="flex items-center gap-2 text-emerald-700">
                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Active / Visible: <strong x-text="selectedPages.length"></strong></span>
            </div>
            <div class="flex items-center gap-2 text-rose-700">
                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                <span>Hidden from Site: <strong x-text="allPageKeys.length - selectedPages.length"></strong></span>
            </div>
        </div>

        <!-- Quick Select Buttons -->
        <div class="flex items-center gap-2">
            <button type="button" @click="selectAll()" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                Select All Pages
            </button>
            <button type="button" @click="deselectAll()" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 transition">
                Deselect All
            </button>
            <button type="button" @click="$refs.visibilityForm.submit()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-extrabold bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-500/20 transition transform hover:-translate-y-0.5">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Save Visibility Changes</span>
            </button>
        </div>
    </div>

    <!-- Form for Submitting All Pages -->
    <form x-ref="visibilityForm" method="POST" action="{{ route('admin.page_visibility.update') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($allPages as $key => $page)
                @php
                    $isDefaultVisible = in_array($key, $visibleKeys);
                @endphp
                <div class="rounded-3xl border-2 p-5 transition-all duration-200 flex flex-col justify-between space-y-4 relative overflow-hidden group cursor-pointer"
                     :class="isPageSelected('{{ $key }}') 
                        ? 'bg-white border-emerald-500/80 shadow-md shadow-emerald-500/10' 
                        : 'bg-slate-50/70 border-slate-200 opacity-70 hover:opacity-100 hover:border-slate-300'"
                     @click="togglePage('{{ $key }}')">

                    <!-- Hidden Input for Form Submission -->
                    <input type="checkbox" name="pages[]" value="{{ $key }}"
                           :checked="isPageSelected('{{ $key }}')"
                           class="hidden">

                    <!-- Top Card Info -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition shadow-sm"
                                     :class="isPageSelected('{{ $key }}') ? 'bg-emerald-600 text-white shadow-emerald-600/30' : 'bg-slate-200 text-slate-500'">
                                    <i data-lucide="{{ $page['icon'] }}" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <h3 class="font-black text-slate-900 text-sm group-hover:text-brand-600 transition">{{ $page['name'] }}</h3>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500">
                                            {{ $page['badge'] }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] font-bold text-amber-700 font-sans tracking-wide">
                                        {{ $page['urdu_name'] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive Toggle Switch -->
                            <div class="relative inline-flex items-center cursor-pointer">
                                <div class="w-11 h-6 rounded-full transition-colors duration-200 ease-in-out"
                                     :class="isPageSelected('{{ $key }}') ? 'bg-emerald-600' : 'bg-slate-300'">
                                    <div class="w-5 h-5 rounded-full bg-white shadow-md transform transition-transform duration-200 ease-in-out mt-0.5 ml-0.5"
                                         :class="isPageSelected('{{ $key }}') ? 'translate-x-5' : 'translate-x-0'"></div>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 leading-relaxed">
                            {{ $page['description'] }}
                        </p>
                    </div>

                    <!-- Footer of Card: URL & Live Badge -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5 font-mono text-[11px] text-slate-400">
                            <i data-lucide="link" class="w-3 h-3"></i>
                            <span>{{ $page['url'] }}</span>
                        </div>

                        <div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider inline-flex items-center gap-1"
                                  :class="isPageSelected('{{ $key }}') ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'">
                                <span class="w-1.5 h-1.5 rounded-full" :class="isPageSelected('{{ $key }}') ? 'bg-emerald-600' : 'bg-rose-600'"></span>
                                <span x-text="isPageSelected('{{ $key }}') ? 'Visible on Website' : 'Hidden from Website'"></span>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Save Button at Bottom -->
        <div class="sticky bottom-6 z-20 mt-8 bg-slate-900/90 backdrop-blur-md p-4 rounded-3xl shadow-2xl border border-slate-800 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <i data-lucide="info" class="w-5 h-5 text-amber-400"></i>
                <div class="text-xs">
                    <span class="font-bold text-white block">Done selecting your desired pages?</span>
                    <span class="text-slate-400">Click Save to immediately update public website navigation and page access.</span>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-extrabold text-sm px-6 py-3 rounded-2xl shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-0.5">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Save &amp; Apply to Website</span>
            </button>
        </div>
    </form>
</div>
@endsection
