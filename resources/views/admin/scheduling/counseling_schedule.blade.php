@extends('layouts.admin')

@section('title', 'Campus Counseling Schedule & Slots')
@section('page_title', 'Campus Counseling Schedule')

@section('content')
<div class="space-y-6" x-data="{ configModal: false }">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-blue-100 text-blue-800">
                    Campus Counseling Module
                </span>
                <span class="text-xs text-slate-400 font-semibold">• Auto 9:00 AM – 6:00 PM • Single Slots</span>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">Counseling Daily Schedule</h2>
            <p class="text-xs text-slate-500">Automatically generated 1-on-1 counseling appointment slots. Independent from IETS test sessions.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="configModal = true" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="sliders" class="w-4 h-4"></i>
                <span>Configure Counseling Schedule</span>
            </button>
            <a href="{{ route('admin.scheduling.bookings', ['type' => 'counseling']) }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>Counseling Bookings</span>
            </a>
        </div>
    </div>

    <!-- Date Bar & Statistics -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.scheduling.counseling') }}" class="flex items-center gap-3">
            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Date:</label>
            <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()"
                   class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500">
            <span class="text-xs font-semibold text-slate-400">
                ({{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }})
            </span>
        </form>

        <div class="flex items-center gap-4 text-xs font-bold">
            <div class="flex items-center gap-1.5 text-slate-700">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                <span>Total Slots: {{ $totalSlotsCount }}</span>
            </div>
            <div class="flex items-center gap-1.5 text-emerald-700">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Available: {{ $availableCount }}</span>
            </div>
            <div class="flex items-center gap-1.5 text-rose-700">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <span>Booked: {{ $bookedCount }}</span>
            </div>
        </div>
    </div>

    <!-- 9:00 AM to 6:00 PM Slots Grid -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-base text-slate-900">
                    Auto-Generated Time Slots ({{ substr($settings->start_time ?: '09:00:00', 0, 5) }} – {{ substr($settings->end_time ?: '18:00:00', 0, 5) }})
                </h3>
                <p class="text-xs text-slate-400">Duration: {{ $settings->slot_duration_minutes ?? 30 }} minutes per counseling session • Max 1 student per slot</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5">
            @forelse($counselingSlots as $slot)
                @php
                    $isBooked = $slot['is_full'];
                    $booking = $bookedAppointments->get($slot['time']);
                @endphp
                <div class="p-4 rounded-2xl border transition flex flex-col justify-between space-y-2.5
                    {{ $isBooked ? 'bg-amber-50/70 border-amber-200 text-amber-950' : 'bg-slate-50 border-slate-200 text-slate-700 hover:border-brand-300 hover:bg-white' }}">
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4 {{ $isBooked ? 'text-amber-600' : 'text-slate-400' }}"></i>
                            <span class="font-extrabold text-sm text-slate-900">{{ $slot['time'] }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                            {{ $isBooked ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $isBooked ? 'Booked' : 'Available' }}
                        </span>
                    </div>

                    @if($isBooked && $booking)
                        <div class="pt-2 border-t border-amber-200/60 text-xs space-y-1">
                            <span class="text-[10px] text-amber-700 font-bold uppercase block">Student Reserved:</span>
                            <div class="font-bold text-slate-900 truncate">{{ $booking->name }}</div>
                            <div class="text-[10px] text-slate-500 font-mono">{{ $booking->registration_number ?: $booking->booking_code }}</div>
                            <div class="pt-1">
                                <a href="{{ route('admin.scheduling.booking.show', $booking) }}" class="text-[11px] font-bold text-brand-600 hover:underline">
                                    Manage Booking &rarr;
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="pt-1 text-[11px] text-slate-400 font-medium">
                            1-on-1 counseling slot open for student booking
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-4 py-12 text-center text-slate-400 text-xs">
                    No counseling slots active for this day. Check if this day is a designated working day in schedule settings.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Configure Counseling Schedule Modal -->
    <div x-show="configModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="configModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Counseling Schedule Settings</h3>
                    <p class="text-xs text-slate-400">Manage operating hours and session duration</p>
                </div>
                <button type="button" @click="configModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.scheduling.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="default_iets_capacity" value="15">
                <input type="hidden" name="max_per_slot" value="1">

                <!-- Working Days -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Available Counseling Days</label>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        @php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $selectedDays = $settings->working_days ?? ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                        @endphp
                        @foreach($days as $day)
                            <label class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-200 cursor-pointer">
                                <input type="checkbox" name="working_days[]" value="{{ $day }}" 
                                       {{ in_array($day, $selectedDays) ? 'checked' : '' }}
                                       class="rounded text-brand-600 focus:ring-brand-500">
                                <span class="font-semibold text-slate-800">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Opening Time (9:00 AM)</label>
                        <input type="time" name="start_time" value="{{ substr($settings->start_time ?: '09:00:00', 0, 5) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Closing Time (6:00 PM)</label>
                        <input type="time" name="end_time" value="{{ substr($settings->end_time ?: '18:00:00', 0, 5) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Session Duration</label>
                        <select name="slot_duration_minutes" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="15" {{ $settings->slot_duration_minutes == 15 ? 'selected' : '' }}>15 Minutes</option>
                            <option value="30" {{ ($settings->slot_duration_minutes ?: 30) == 30 ? 'selected' : '' }}>30 Minutes</option>
                            <option value="45" {{ $settings->slot_duration_minutes == 45 ? 'selected' : '' }}>45 Minutes</option>
                            <option value="60" {{ $settings->slot_duration_minutes == 60 ? 'selected' : '' }}>60 Minutes (1 Hour)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Capacity (Per Slot)</label>
                        <div class="px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-bold text-center text-slate-700">
                            1 Student (1-on-1)
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="configModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                        Save Schedule Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
