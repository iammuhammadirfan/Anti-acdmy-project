@extends('layouts.admin')

@section('title', 'IETS Test Schedule & Slots')
@section('page_title', 'IETS Test Schedule')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, editSlotData: {}, studentsModal: false, currentSlotStudents: [], currentSlotTitle: '' }">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">
                    IETS Testing Module
                </span>
                <span class="text-xs text-slate-400 font-semibold">• Max 15 Students Default</span>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">IETS Test Slot &amp; Capacity Manager</h2>
            <p class="text-xs text-slate-500">Create official IETS test slots with custom time ranges (e.g. 9:00 AM – 10:00 AM). Overlapping slots are strictly blocked.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="createModal = true" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Create IETS Test Slot</span>
            </button>
            <a href="{{ route('admin.scheduling.bookings', ['type' => 'iets_test']) }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>View IETS Bookings</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="text-xs font-bold text-slate-700">
            Total IETS Test Sessions: <span class="text-emerald-700 font-black">{{ $slots->total() }}</span>
        </div>

        <form method="GET" action="{{ route('admin.scheduling.iets') }}" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date }}" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <button type="submit" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                Filter Date
            </button>
            @if($date)
                <a href="{{ route('admin.scheduling.iets') }}" class="text-xs text-rose-600 font-bold hover:underline">
                    Clear Filter
                </a>
            @endif
        </form>
    </div>

    <!-- IETS Slots Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Test Date</th>
                        <th class="py-3.5 px-4">Time Range (Start – End)</th>
                        <th class="py-3.5 px-4">Capacity (Max 15)</th>
                        <th class="py-3.5 px-4">Registered Candidates</th>
                        <th class="py-3.5 px-4">Remaining Seats</th>
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
                                <div class="font-mono font-extrabold text-slate-900 text-sm">
                                    {{ $slot->start_time }} – {{ $slot->end_time ?: 'TBD' }}
                                </div>
                                <span class="text-[10px] text-slate-400 font-semibold">{{ $slot->duration_minutes }} minutes exam slot</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-800 text-sm">{{ $cap }} Students</span>
                            </td>
                            <td class="py-3.5 px-4 min-w-[160px]">
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="font-bold {{ $isFull ? 'text-rose-600' : 'text-slate-800' }}">
                                            {{ $booked }} registered
                                        </span>
                                        @if($booked > 0)
                                            <button type="button" 
                                                    @click="currentSlotTitle = '{{ $slot->slot_date->format('M d, Y') }} ({{ $slot->start_time }} – {{ $slot->end_time }})'; currentSlotStudents = {{ json_encode($appointmentsList) }}; studentsModal = true"
                                                    class="text-[10px] font-bold text-brand-600 hover:underline">
                                                View ({{ $booked }})
                                            </button>
                                        @endif
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase
                                    {{ $isFull ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $isFull ? 'FULLY BOOKED' : "{$rem} Seats Available" }}
                                </span>
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
                                            title="Edit Slot Capacity &amp; Range">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <form action="{{ route('admin.scheduling.slot.destroy', $slot) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this IETS slot?');">
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
                                No IETS test slots created yet. Click "Create IETS Test Slot" to schedule official test sessions.
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

    <!-- Create IETS Slot Modal with Overlap Prevention -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="createModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Create IETS Test Slot</h3>
                    <p class="text-xs text-slate-400">Select date and custom non-overlapping time range</p>
                </div>
                <button type="button" @click="createModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.scheduling.slot.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="iets_test">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Test Date *</label>
                        <input type="date" name="slot_date" required min="{{ date('Y-m-d') }}" value="{{ $date ?: date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Max Students Capacity *</label>
                        <input type="number" name="capacity" required min="1" max="50" value="{{ $defaultCapacity ?: 15 }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Configurable per slot (default: 15)</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Start Time *</label>
                        <input type="time" name="start_time" required value="09:00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">End Time *</label>
                        <input type="time" name="end_time" required value="10:00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                    <strong class="font-bold flex items-center gap-1.5">
                        <i data-lucide="shield-alert" class="w-4 h-4 text-amber-600"></i>
                        Overlap Protection Rule:
                    </strong>
                    <p>The system will verify that this slot does not overlap with any existing IETS test slot on the same date. For example, 9:00 AM – 10:00 AM is allowed, and the next slot must start at or after 10:00 AM.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Exam Venue / Notes (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="e.g. Lab Room 3, Invigilator: Prof. Davis"
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                        Save IETS Slot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit IETS Slot Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="editModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Edit IETS Slot Configuration</h3>
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
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Slot Status *</label>
                        <select name="is_active" x-model="editSlotData.is_active" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option :value="1">Active (Open for Booking)</option>
                            <option :value="0">Disabled (Blocked)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Start Time *</label>
                        <input type="text" name="start_time" x-model="editSlotData.start_time" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">End Time *</label>
                        <input type="text" name="end_time" x-model="editSlotData.end_time" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" x-model="editSlotData.notes" rows="2"
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
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
                    <h3 class="font-extrabold text-base text-slate-900">Registered Candidates in IETS Slot</h3>
                    <p class="text-xs text-emerald-600 font-semibold" x-text="currentSlotTitle"></p>
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
