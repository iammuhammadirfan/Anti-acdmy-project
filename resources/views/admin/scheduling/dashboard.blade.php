@extends('layouts.admin')

@section('title', 'Scheduling Dashboard — Apex Academy')
@section('page_title', 'Appointment & IETS Test Scheduling')

@section('content')
<div class="space-y-8">
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Scheduling Operations Center</h2>
            <p class="text-xs text-slate-500">Live monitoring for IETS test sessions, candidate capacities, and campus counseling appointments.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.scheduling.calendar') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Slot Calendar</span>
            </a>
            <a href="{{ route('admin.scheduling.slots') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Manage Slots</span>
            </a>
            <a href="{{ route('admin.scheduling.export') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-bold text-xs px-3.5 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="download" class="w-4 h-4 text-slate-500"></i>
                <span>Export CSV</span>
            </a>
        </div>
    </div>

    <!-- Quick Navigation Submenu Tabs -->
    <div class="bg-white rounded-2xl p-2 border border-slate-200/80 shadow-sm flex flex-wrap items-center gap-1.5 text-xs font-bold">
        <a href="{{ route('admin.scheduling.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-brand-600 text-white shadow-sm flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.scheduling.calendar') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 flex items-center gap-2 transition">
            <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
            <span>Calendar</span>
        </a>
        <a href="{{ route('admin.scheduling.slots') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 flex items-center gap-2 transition">
            <i data-lucide="clock" class="w-4 h-4 text-slate-400"></i>
            <span>Test &amp; Counseling Slots</span>
        </a>
        <a href="{{ route('admin.scheduling.bookings') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 flex items-center gap-2 transition">
            <i data-lucide="users" class="w-4 h-4 text-slate-400"></i>
            <span>All Bookings</span>
        </a>
        <a href="{{ route('admin.scheduling.counseling') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 flex items-center gap-2 transition">
            <i data-lucide="message-square" class="w-4 h-4 text-slate-400"></i>
            <span>Counseling</span>
        </a>
        <a href="{{ route('admin.scheduling.iets') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 flex items-center gap-2 transition">
            <i data-lucide="award" class="w-4 h-4 text-slate-400"></i>
            <span>IETS Tests</span>
        </a>
        <a href="{{ route('admin.scheduling.students') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 flex items-center gap-2 transition">
            <i data-lucide="user-check" class="w-4 h-4 text-slate-400"></i>
            <span>Students</span>
        </a>
        <a href="{{ route('admin.scheduling.emails') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 flex items-center gap-2 transition">
            <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
            <span>Email Templates</span>
        </a>
        <a href="{{ route('admin.scheduling.settings') }}" class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 flex items-center gap-2 transition">
            <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
            <span>Settings</span>
        </a>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Today's Counseling -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Today's Counseling</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $metrics['today_counseling'] }}</span>
                <span class="text-[11px] text-brand-600 font-semibold mt-0.5 block">Campus Sessions</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                <i data-lucide="messages-square" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Today's IETS Tests -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Today's IETS Tests</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $metrics['today_iets'] }}</span>
                <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block">Scheduled Candidates</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <i data-lucide="file-check-2" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Available Seats -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Available Seats</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $metrics['total_available_seats'] }}</span>
                <span class="text-[11px] text-slate-500 font-semibold mt-0.5 block">Across {{ $metrics['available_slots'] }} active slots</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <i data-lucide="armchair" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Fully Booked Slots -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Fully Booked Slots</span>
                <span class="text-2xl font-black text-rose-600 mt-1 block">{{ $metrics['fully_booked_slots'] }}</span>
                <span class="text-[11px] text-slate-400 font-semibold mt-0.5 block">At 100% capacity</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                <i data-lucide="lock" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Secondary Counters -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-brand-900 to-slate-900 text-white rounded-2xl p-5 shadow-sm space-y-1">
            <span class="text-[11px] text-brand-300 uppercase font-bold tracking-wider">Total Bookings &amp; Registrations</span>
            <div class="text-3xl font-black tracking-tight">{{ $metrics['total_bookings'] }}</div>
            <span class="text-xs text-slate-300 block">{{ $metrics['confirmed_bookings'] }} confirmed bookings recorded</span>
        </div>

        <div class="bg-gradient-to-br from-emerald-900 to-slate-900 text-white rounded-2xl p-5 shadow-sm space-y-1">
            <span class="text-[11px] text-emerald-300 uppercase font-bold tracking-wider">Total IETS Test Candidates</span>
            <div class="text-3xl font-black tracking-tight">{{ $metrics['total_iets_students'] }}</div>
            <span class="text-xs text-slate-300 block">Registered with unique IETS numbers</span>
        </div>

        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-2xl p-5 shadow-sm space-y-1">
            <span class="text-[11px] text-indigo-300 uppercase font-bold tracking-wider">Default Test Slot Capacity</span>
            <div class="text-3xl font-black tracking-tight">15 Students</div>
            <span class="text-xs text-slate-300 block">Configurable per slot from Admin Panel</span>
        </div>
    </div>

    <!-- Two-Column Section: Upcoming IETS Slots & Recent Bookings -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming IETS Slots with Seat Tracking -->
        <div class="lg:col-span-1 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Upcoming IETS Test Slots</h3>
                    <p class="text-xs text-slate-400">Live seat capacity tracker</p>
                </div>
                <a href="{{ route('admin.scheduling.slots', ['type' => 'iets_test']) }}" class="text-xs font-bold text-brand-600 hover:underline">
                    View All &rarr;
                </a>
            </div>

            <div class="space-y-3.5">
                @forelse($upcomingTestSlots as $ts)
                    @php
                        $booked = $ts->booked_count;
                        $cap = $ts->capacity;
                        $pct = $cap > 0 ? min(100, round(($booked / $cap) * 100)) : 0;
                        $isFull = $booked >= $cap;
                    @endphp
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <div>
                                <span class="font-extrabold text-slate-900 block">{{ $ts->slot_date->format('M d, Y') }}</span>
                                <span class="font-semibold text-brand-600">{{ $ts->start_time }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                                {{ $isFull ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $isFull ? 'FULL (15/15)' : ($ts->remaining_seats . ' Left') }}
                            </span>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="space-y-1">
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-500 {{ $isFull ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                                <span>{{ $booked }} registered</span>
                                <span>Capacity: {{ $cap }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">No upcoming test slots scheduled. Click "Manage Slots" to create test batches.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm">Recent Student Bookings</h3>
                        <p class="text-xs text-slate-400">Latest registration entries across counseling &amp; IETS</p>
                    </div>
                    <a href="{{ route('admin.scheduling.bookings') }}" class="text-xs font-bold text-brand-600 hover:underline">
                        View All Bookings &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Registration #</th>
                                <th class="py-3 px-4">Student</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">Date &amp; Slot</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentBookings as $b)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-3 px-4 font-mono font-bold text-brand-600">
                                        <a href="{{ route('admin.scheduling.booking.show', $b) }}" class="hover:underline">
                                            {{ $b->registration_number ?: $b->booking_code }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900">{{ $b->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $b->email }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                            {{ $b->type === 'iets_test' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ $b->type === 'iets_test' ? 'IETS Test' : 'Counseling' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-slate-800">{{ $b->appointment_date ? $b->appointment_date->format('M d, Y') : '-' }}</div>
                                        <div class="text-slate-500 font-medium">{{ $b->time_slot }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                            {{ $b->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($b->status === 'pending' ? 'bg-amber-100 text-amber-800' : ($b->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700')) }}">
                                            {{ $b->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('admin.scheduling.booking.show', $b) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-[11px] font-bold transition">
                                            Manage &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">No student bookings recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="p-3 bg-slate-50/50 border-t border-slate-100 text-right">
                <a href="{{ route('admin.scheduling.bookings') }}" class="text-xs font-bold text-slate-600 hover:text-brand-600">
                    Browse All {{ $metrics['total_bookings'] }} Registrations &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
