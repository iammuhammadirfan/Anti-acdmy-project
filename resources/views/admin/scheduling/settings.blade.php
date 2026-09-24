@extends('layouts.admin')

@section('title', 'Appointment & Test Scheduling Settings')
@section('page_title', 'Scheduling Rules & Settings')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            <div>
                <h3 class="font-extrabold text-base text-slate-900">Capacity &amp; Working Hours Rules</h3>
                <p class="text-xs text-slate-500">Configure global defaults for IETS test capacities, counseling durations, and operating schedules.</p>
            </div>

            <form action="{{ route('admin.scheduling.settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- IETS Test Capacity Settings -->
                <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200/80 space-y-3">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600"></i>
                        <h4 class="font-extrabold text-sm text-emerald-950">IETS Test Slot Capacity Enforcement</h4>
                    </div>
                    <p class="text-xs text-emerald-800 leading-relaxed">
                        Default capacity is set to <strong>15 students per slot</strong>. Individual slots can also have custom capacities set in the Slot Manager.
                    </p>
                    <div class="flex items-center gap-4 pt-1">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-900 mb-1">
                                Default Capacity (Students Per Slot)
                            </label>
                            <input type="number" name="default_iets_capacity" value="{{ $defaultIetsCapacity }}" min="1" max="100" required
                                   class="w-32 px-3.5 py-2.5 bg-white border border-emerald-300 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Working Days -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Operational Days</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                        @php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $selectedDays = $settings->working_days ?? ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                        @endphp
                        @foreach($days as $day)
                            <label class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200 cursor-pointer">
                                <input type="checkbox" name="working_days[]" value="{{ $day }}" 
                                       {{ in_array($day, $selectedDays) ? 'checked' : '' }}
                                       class="rounded text-brand-600 focus:ring-brand-500">
                                <span class="font-semibold text-slate-800">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Times -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Academy Opening Time</label>
                        <input type="time" name="start_time" value="{{ substr($settings->start_time, 0, 5) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Academy Closing Time</label>
                        <input type="time" name="end_time" value="{{ substr($settings->end_time, 0, 5) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <!-- Intervals & Breaks -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Counseling Duration (Min)</label>
                        <select name="slot_duration_minutes" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="15" {{ $settings->slot_duration_minutes == 15 ? 'selected' : '' }}>15 Minutes</option>
                            <option value="30" {{ $settings->slot_duration_minutes == 30 ? 'selected' : '' }}>30 Minutes</option>
                            <option value="45" {{ $settings->slot_duration_minutes == 45 ? 'selected' : '' }}>45 Minutes</option>
                            <option value="60" {{ $settings->slot_duration_minutes == 60 ? 'selected' : '' }}>60 Minutes (1 Hour)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Daily Break Starts</label>
                        <input type="time" name="break_start" value="{{ $settings->break_start ? substr($settings->break_start, 0, 5) : '13:00' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Daily Break Ends</label>
                        <input type="time" name="break_end" value="{{ $settings->break_end ? substr($settings->break_end, 0, 5) : '14:00' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <!-- Concurrency -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-0.5">Max Counseling Bookings Per Slot</label>
                        <p class="text-xs text-slate-400">Default 1 to ensure 1-on-1 private student counseling.</p>
                    </div>
                    <input type="number" name="max_per_slot" value="{{ $settings->max_per_slot }}" min="1" max="10"
                           class="w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-center font-bold">
                </div>

                <div class="flex justify-end pt-3">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-7 py-3 rounded-xl shadow-sm transition">
                        Save Scheduling Configuration
                    </button>
                </div>
            </form>
        </div>

        <!-- Blocked Dates / Holidays (1 col) -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="font-extrabold text-base text-slate-900">Block Dates / Campus Holidays</h3>
                <p class="text-xs text-slate-500">Blocked dates are immediately hidden on the public scheduling portal.</p>

                <form action="{{ route('admin.calendar.block') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Date</label>
                        <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Reason (Optional)</label>
                        <input type="text" name="reason" placeholder="e.g. Official Public Holiday / Renovation"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-sm">
                        Block Selected Date
                    </button>
                </form>

                <div class="pt-4 border-t border-slate-100">
                    <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Currently Blocked ({{ $blockedDates->count() }})</h5>
                    <div class="space-y-1.5 max-h-48 overflow-y-auto">
                        @forelse($blockedDates as $bd)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-red-50/70 border border-red-100 text-xs">
                                <div>
                                    <span class="font-bold text-red-900">{{ $bd->date->format('M d, Y') }}</span>
                                    @if($bd->reason)
                                        <span class="text-[11px] text-slate-500 block">({{ $bd->reason }})</span>
                                    @endif
                                </div>
                                <form action="{{ route('admin.calendar.unblock', $bd) }}" method="POST" onsubmit="return confirm('Unblock this date?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 p-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-3">No dates currently blocked.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
