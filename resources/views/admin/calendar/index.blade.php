@extends('layouts.admin')

@section('title', 'Appointment Calendar & Settings')
@section('page_title', 'Calendar Rules & Working Hours')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Working Schedule & Slot Rules -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Working Hours &amp; Slot Configuration</h3>
                <p class="text-xs text-slate-500">Configure appointment calendar availability, duration, break times, and concurrency.</p>
            </div>

            <form action="{{ route('admin.calendar.settings') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Working Days -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Available Working Days</label>
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Daily Opening Time</label>
                        <input type="time" name="start_time" value="{{ substr($settings->start_time, 0, 5) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Daily Closing Time</label>
                        <input type="time" name="end_time" value="{{ substr($settings->end_time, 0, 5) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Slot Duration (Min)</label>
                        <select name="slot_duration_minutes" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="15" {{ $settings->slot_duration_minutes == 15 ? 'selected' : '' }}>15 Minutes</option>
                            <option value="30" {{ $settings->slot_duration_minutes == 30 ? 'selected' : '' }}>30 Minutes</option>
                            <option value="45" {{ $settings->slot_duration_minutes == 45 ? 'selected' : '' }}>45 Minutes</option>
                            <option value="60" {{ $settings->slot_duration_minutes == 60 ? 'selected' : '' }}>60 Minutes (1 Hour)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Break Starts</label>
                        <input type="time" name="break_start" value="{{ $settings->break_start ? substr($settings->break_start, 0, 5) : '13:00' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Break Ends</label>
                        <input type="time" name="break_end" value="{{ $settings->break_end ? substr($settings->break_end, 0, 5) : '14:00' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Max Bookings Per Slot</label>
                        <p class="text-xs text-slate-500">Default 1 to prevent double bookings entirely.</p>
                    </div>
                    <input type="number" name="max_per_slot" value="{{ $settings->max_per_slot }}" min="1" max="10"
                           class="w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-center font-bold">
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition">
                        Save Calendar Rules
                    </button>
                </div>
            </form>
        </div>

        <!-- Blocked Dates / Holidays -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 text-base">Block Dates / Holidays</h3>
                <p class="text-xs text-slate-500">Dates blocked here will immediately disappear from the frontend booking calendar.</p>

                <form action="{{ route('admin.calendar.block') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Select Date</label>
                        <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Reason (Optional)</label>
                        <input type="text" name="reason" placeholder="e.g. National Holiday / Mock Exam"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs py-2 rounded-xl transition">
                        Block Selected Date
                    </button>
                </form>

                <div class="pt-4 border-t border-slate-100">
                    <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Blocked Dates ({{ $blockedDates->count() }})</h5>
                    <div class="space-y-1.5 max-h-48 overflow-y-auto">
                        @forelse($blockedDates as $bd)
                            <div class="flex items-center justify-between p-2 rounded-lg bg-red-50/60 border border-red-100 text-xs">
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
                            <p class="text-xs text-slate-400 text-center py-2">No dates currently blocked.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
