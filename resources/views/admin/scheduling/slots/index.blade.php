@extends('layouts.admin')

@section('title', 'Manage Appointment & Test Slots')
@section('page_title', 'Appointment & Test Slots')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, batchModal: false, editModal: false, editSlotData: {}, studentsModal: false, currentSlotStudents: [], currentSlotTitle: '' }">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Time Slot &amp; Capacity Control</h2>
            <p class="text-xs text-slate-500">Configure appointment dates, times, and maximum student capacities (default 15 for IETS).</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" @click="createModal = true" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Single Slot</span>
            </button>
            <button type="button" @click="batchModal = true" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                <span>Batch Generate Day</span>
            </button>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.scheduling.slots') }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !$type ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Slots
            </a>
            <a href="{{ route('admin.scheduling.slots', ['type' => 'iets_test']) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $type === 'iets_test' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                IETS Tests Only (Max 15)
            </a>
            <a href="{{ route('admin.scheduling.slots', ['type' => 'counseling']) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $type === 'counseling' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                Counseling Sessions
            </a>
        </div>

        <form method="GET" action="{{ route('admin.scheduling.slots') }}" class="flex items-center gap-2">
            @if($type) <input type="hidden" name="type" value="{{ $type }}"> @endif
            <input type="date" name="date" value="{{ $date }}" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                Filter
            </button>
            @if($date)
                <a href="{{ route('admin.scheduling.slots', ['type' => $type]) }}" class="text-xs text-rose-600 font-bold hover:underline">
                    Clear Date
                </a>
            @endif
        </form>
    </div>

    <!-- Slots Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Date</th>
                        <th class="py-3.5 px-4">Time Window</th>
                        <th class="py-3.5 px-4">Type</th>
                        <th class="py-3.5 px-4">Capacity &amp; Seats</th>
                        <th class="py-3.5 px-4">Registered Students</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($slots as $slot)
                        @php
                            $booked = $slot->booked_count;
                            $cap = $slot->capacity;
                            $rem = $slot->remaining_seats;
                            $isFull = $slot->is_full;
                            $pct = $cap > 0 ? min(100, round(($booked / $cap) * 100)) : 0;
                            $appointmentsList = $slot->appointments->map(function($a) {
                                return [
                                    'name' => $a->name,
                                    'email' => $a->email,
                                    'phone' => $a->phone,
                                    'reg' => $a->registration_number ?: $a->booking_code,
                                    'status' => $a->status,
                                    'show_url' => route('admin.scheduling.booking.show', $a)
                                ];
                            });
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900 whitespace-nowrap">
                                {{ $slot->slot_date->format('l, M d, Y') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-extrabold text-brand-600 text-sm">{{ $slot->start_time }}</span>
                                @if($slot->end_time)
                                    <span class="text-slate-400 block text-[11px]">Until {{ $slot->end_time }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $slot->type === 'iets_test' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                    {{ $slot->type === 'iets_test' ? 'IETS Test' : 'Counseling' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 min-w-[180px]">
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="font-bold {{ $isFull ? 'text-rose-600' : 'text-slate-800' }}">
                                            {{ $booked }} / {{ $cap }} Registered
                                        </span>
                                        <span class="font-extrabold text-[10px] px-1.5 py-0.5 rounded
                                            {{ $isFull ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $isFull ? 'FULL' : "{$rem} Left" }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($booked > 0)
                                    <button type="button" 
                                            @click="currentSlotTitle = '{{ $slot->slot_date->format('M d, Y') }} at {{ $slot->start_time }}'; currentSlotStudents = {{ json_encode($appointmentsList) }}; studentsModal = true"
                                            class="text-xs font-bold text-brand-600 hover:text-brand-800 hover:underline flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>View {{ $booked }} Student(s)</span>
                                    </button>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">No bookings yet</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.scheduling.slot.toggle', $slot) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition
                                        {{ $slot->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $slot->is_active ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            @click="editSlotData = {{ json_encode($slot) }}; editModal = true"
                                            class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition"
                                            title="Edit Slot Capacity &amp; Time">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <form action="{{ route('admin.scheduling.slot.destroy', $slot) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slot?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-400 transition" title="Delete Slot">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                No slots found. Use "Add Single Slot" or "Batch Generate Day" to create testing and counseling slots.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($slots->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $slots->links() }}
            </div>
        @endif
    </div>

    <!-- Create Single Slot Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="createModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Create New Time Slot</h3>
                <button type="button" @click="createModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.scheduling.slot.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Slot Type *</label>
                    <select name="type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="iets_test" selected>IETS Test Slot (Capacity default: 15)</option>
                        <option value="counseling">Campus Counseling Slot</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Date *</label>
                        <input type="date" name="slot_date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Capacity (Max Students) *</label>
                        <input type="number" name="capacity" required min="1" max="50" value="15"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 text-center font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Start Time *</label>
                        <input type="text" name="start_time" required placeholder="09:00 AM"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">End Time (Optional)</label>
                        <input type="text" name="end_time" placeholder="10:00 AM"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Duration (Minutes)</label>
                    <select name="duration_minutes" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="30">30 Minutes</option>
                        <option value="45">45 Minutes</option>
                        <option value="60" selected>60 Minutes (1 Hour)</option>
                        <option value="90">90 Minutes (1.5 Hours)</option>
                        <option value="120">120 Minutes (2 Hours)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Internal Notes (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="e.g. Lab Suite B, Invigilator: Dr. Vance"
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                        Save Slot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Batch Generate Slots Modal -->
    <div x-show="batchModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="batchModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Batch Generate Day's Schedule</h3>
                    <p class="text-xs text-slate-400">Generate multiple consecutive slots automatically</p>
                </div>
                <button type="button" @click="batchModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.scheduling.slots.batch') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Target Date *</label>
                        <input type="date" name="slot_date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Type *</label>
                        <select name="type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="iets_test" selected>IETS Test (Capacity: 15)</option>
                            <option value="counseling">Campus Counseling</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Day Opening Time *</label>
                        <input type="time" name="start_time" required value="09:00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Day Closing Time *</label>
                        <input type="time" name="end_time" required value="17:00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Slot Interval (Minutes) *</label>
                        <select name="duration_minutes" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="30">Every 30 Minutes</option>
                            <option value="60" selected>Every 60 Minutes (1 Hour)</option>
                            <option value="90">Every 90 Minutes</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Capacity Per Slot *</label>
                        <input type="number" name="capacity" required min="1" max="50" value="15"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 text-center font-bold">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="batchModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white font-bold text-xs shadow-sm transition">
                        Generate Slots
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Slot Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="editModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Edit Slot Configuration</h3>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/scheduling/slots') }}/' + editSlotData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Max Students Capacity *</label>
                        <input type="number" name="capacity" x-model="editSlotData.capacity" required min="1" max="50"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Slot Status *</label>
                        <select name="is_active" x-model="editSlotData.is_active" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option :value="1">Active (Open for Booking)</option>
                            <option :value="0">Disabled (Blocked)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Start Time *</label>
                        <input type="text" name="start_time" x-model="editSlotData.start_time" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">End Time</label>
                        <input type="text" name="end_time" x-model="editSlotData.end_time"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" x-model="editSlotData.notes" rows="2"
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                        Update Slot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Registered Students in Slot Modal -->
    <div x-show="studentsModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="studentsModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Registered Students in Slot</h3>
                    <p class="text-xs text-brand-600 font-semibold" x-text="currentSlotTitle"></p>
                </div>
                <button type="button" @click="studentsModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 text-xs">
                <template x-for="st in currentSlotStudents" :key="st.reg">
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div>
                            <div class="font-bold text-slate-900 text-sm" x-text="st.name"></div>
                            <div class="text-slate-400 font-mono text-[11px]" x-text="st.reg"></div>
                            <div class="text-slate-500 text-[11px]" x-text="st.email + ' • ' + st.phone"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800" x-text="st.status"></span>
                            <a :href="st.show_url" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-xs font-bold transition">
                                Details &rarr;
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <div class="pt-3 border-t border-slate-100 text-right">
                <button type="button" @click="studentsModal = false" class="px-5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
