@extends('layouts.admin')

@section('title', 'About Page Content Settings')
@section('page_title', 'About Us Page Content Manager')

@section('content')
<div class="max-w-4xl space-y-8" x-data="{ activeTab: 'hero' }">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-brand-100 text-brand-800">
                    Frontend Page Builder
                </span>
                <span class="text-xs text-slate-400 font-semibold">• About Institution</span>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">About Page Content &amp; Story Settings</h2>
            <p class="text-xs text-slate-500">Edit all public text, missions, vision statement, core values, and features displayed on the About page.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('about') }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>View Live About Page</span>
            </a>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3 text-xs font-bold uppercase tracking-wider overflow-x-auto">
        <button type="button" @click="activeTab = 'hero'" 
                :class="activeTab === 'hero' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'"
                class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 shrink-0">
            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
            <span>Hero &amp; Introduction</span>
        </button>
        <button type="button" @click="activeTab = 'pillars'" 
                :class="activeTab === 'pillars' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'"
                class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 shrink-0">
            <i data-lucide="target" class="w-3.5 h-3.5"></i>
            <span>Mission, Vision &amp; Values</span>
        </button>
        <button type="button" @click="activeTab = 'why'" 
                :class="activeTab === 'why' ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'"
                class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 shrink-0">
            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
            <span>Why Choose Us Features</span>
        </button>
    </div>

    <form action="{{ route('admin.about.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- 1. Hero & Introduction Tab -->
        <div x-show="activeTab === 'hero'" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="font-bold text-slate-900 text-base">Hero Section (Top of About Page)</h3>
                <p class="text-xs text-slate-500">Configure the top badge, primary headline, and introductory paragraph.</p>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Badge Text</label>
                    <input type="text" name="about_hero_badge" value="{{ $settings['about_hero_badge'] }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    <span class="text-[11px] text-slate-400 mt-1 block">Displays inside the top pill badge (e.g. "About Our Institution").</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Main Heading (H1)</label>
                    <input type="text" name="about_hero_title" value="{{ $settings['about_hero_title'] }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Introductory Description</label>
                    <textarea name="about_hero_description" rows="3" required
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition leading-relaxed">{{ $settings['about_hero_description'] }}</textarea>
                </div>
            </div>
        </div>

        <!-- 2. Mission, Vision & Values Tab -->
        <div x-show="activeTab === 'pillars'" x-cloak class="space-y-6">
            <!-- Our Mission Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                        <i data-lucide="target" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Mission Pillar</h4>
                        <p class="text-xs text-slate-400">Define the core mission of your institution</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mission Title</label>
                        <input type="text" name="about_mission_title" value="{{ $settings['about_mission_title'] }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mission Statement Content</label>
                        <textarea name="about_mission_desc" rows="3" required
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">{{ $settings['about_mission_desc'] }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Our Vision Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                        <i data-lucide="eye" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Vision Pillar</h4>
                        <p class="text-xs text-slate-400">Define the long-term vision &amp; academic ambition</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Vision Title</label>
                        <input type="text" name="about_vision_title" value="{{ $settings['about_vision_title'] }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Vision Statement Content</label>
                        <textarea name="about_vision_desc" rows="3" required
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white transition">{{ $settings['about_vision_desc'] }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Core Values Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i data-lucide="award" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Core Values Pillar</h4>
                        <p class="text-xs text-slate-400">Institutional integrity, standards, and values</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Values Title</label>
                        <input type="text" name="about_values_title" value="{{ $settings['about_values_title'] }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Core Values Content</label>
                        <textarea name="about_values_desc" rows="3" required
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition">{{ $settings['about_values_desc'] }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Why Choose Us Features Tab -->
        <div x-show="activeTab === 'why'" x-cloak class="space-y-6">
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">"Why Choose Us" Section Header</h3>
                    <p class="text-xs text-slate-500">Customize the main heading and supporting subtitle.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Section Title</label>
                        <input type="text" name="about_why_title" value="{{ $settings['about_why_title'] }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Section Subtitle</label>
                        <input type="text" name="about_why_subtitle" value="{{ $settings['about_why_subtitle'] }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- 3 Highlights Cards -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Key Feature Cards (3 Highlights)</h3>
                    <p class="text-xs text-slate-500">Edit the three highlighted selling propositions shown to students.</p>
                </div>

                <!-- Feature 1 -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-extrabold text-brand-700">
                        <i data-lucide="cpu" class="w-4 h-4"></i> Feature 1 (AI &amp; Tech)
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Feature 1 Title</label>
                        <input type="text" name="about_feature1_title" value="{{ $settings['about_feature1_title'] }}" required
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Feature 1 Description</label>
                        <textarea name="about_feature1_desc" rows="2" required
                                  class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $settings['about_feature1_desc'] }}</textarea>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-extrabold text-brand-700">
                        <i data-lucide="headphones" class="w-4 h-4"></i> Feature 2 (Labs &amp; Facilities)
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Feature 2 Title</label>
                        <input type="text" name="about_feature2_title" value="{{ $settings['about_feature2_title'] }}" required
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Feature 2 Description</label>
                        <textarea name="about_feature2_desc" rows="2" required
                                  class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $settings['about_feature2_desc'] }}</textarea>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-extrabold text-brand-700">
                        <i data-lucide="user-check" class="w-4 h-4"></i> Feature 3 (Faculty &amp; Trainers)
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Feature 3 Title</label>
                        <input type="text" name="about_feature3_title" value="{{ $settings['about_feature3_title'] }}" required
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Feature 3 Description</label>
                        <textarea name="about_feature3_desc" rows="2" required
                                  class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $settings['about_feature3_desc'] }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Save Bar -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <span class="text-xs text-slate-500 flex items-center gap-1.5">
                <i data-lucide="info" class="w-4 h-4 text-brand-600"></i>
                <span>Changes reflect on the public About page immediately upon saving.</span>
            </span>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-extrabold text-xs px-6 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Save About Page Content</span>
            </button>
        </div>
    </form>
</div>
@endsection
