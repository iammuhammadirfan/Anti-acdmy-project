@extends('layouts.admin')

@section('title', 'Campus Counseling Schedule & Slots')
@section('page_title', 'Campus Counseling Schedule')

@section('content')
<div class="space-y-6" x-data="{ 
    createModal: false, 
    batchModal: false,
    editModal: false, 
    editSlotData: {}, 
    studentsModal: false, 
    currentSlotStudents: [], 
    currentSlotTitle: '',
    configModal: false,
    selectedSlots: [],
    allSlotIds: {{ json_encode($slots->pluck('id')->toArray()) }},
    createDuration: '30',
    createCustomMinutes: '',
    createStartTime: '09:00 AM',
    createEndTime: '09:30 AM',
    editDuration: '30',
    editCustomMinutes: '',
    toggleAll(event) {
        if (event.target.checked) {
            this.selectedSlots = [...this.allSlotIds];
        } else {
            this.selectedSlots = [];
        }
    },
    isSelected(id) {
        return this.selectedSlots.includes(id);
    },
    toggleSlot(id) {
        if (this.selectedSlots.includes(id)) {
            this.selectedSlots = this.selectedSlots.filter(s => s !== id);
        } else {
            this.selectedSlots.push(id);
        }
    },
    calculateEndTime(startTimeStr, minutes) {
        if (!startTimeStr || !minutes) return '';
        let match = startTimeStr.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i);
        if (!match) return '';
        let hours = parseInt(match[1]);
        let mins = parseInt(match[2]);
        let ampm = match[3] ? match[3].toUpperCase() : null;
        if (ampm === 'PM' && hours < 12) hours += 12;
        if (ampm === 'AM' && hours === 12) hours = 0;
        
        let totalMinutes = hours * 60 + mins + parseInt(minutes);
        let newHours = Math.floor(totalMinutes / 60) % 24;
        let newMins = totalMinutes % 60;
        let newAmpm = newHours >= 12 ? 'PM' : 'AM';
        let displayHours = newHours % 12;
        if (displayHours === 0) displayHours = 12;
        let formattedHours = displayHours < 10 ? '0' + displayHours : '' + displayHours;
        let formattedMins = newMins < 10 ? '0' + newMins : '' + newMins;
        return `${formattedHours}:${formattedMins} ${newAmpm}`;
    },
    applyCreateDuration() {
        let mins = this.createDuration === 'custom' ? parseInt(this.createCustomMinutes) : parseInt(this.createDuration);
        if (mins && mins > 0) {
            let calculated = this.calculateEndTime(this.createStartTime, mins);
            if (calculated) this.createEndTime = calculated;
        }
    },
    applyEditDuration() {
        let mins = this.editDuration === 'custom' ? parseInt(this.editCustomMinutes) : parseInt(this.editDuration);
        if (mins && mins > 0) {
            let calculated = this.calculateEndTime(this.editSlotData.start_time, mins);
            if (calculated) this.editSlotData.end_time = calculated;
            this.editSlotData.duration_minutes = mins;
        }
    },
    formatDurationDisplay(val) {
        let mins = parseInt(val);
        if (!mins) return '';
        if (mins < 60) return mins + ' Mins';
        let h = Math.floor(mins / 60);
        let m = mins % 60;
        if (m === 0) return h + (h === 1 ? ' Hour' : ' Hours');
        return h + 'h ' + m + 'm';
    },
    openEdit(slot) {
        this.editSlotData = { ...slot };
        let d = String(slot.duration_minutes || 30);
        let presets = ['10','15','20','30','45','60','90','120'];
        if (presets.includes(d)) {
            this.editDuration = d;
            this.editCustomMinutes = '';
        } else {
            this.editDuration = 'custom';
            this.editCustomMinutes = d;
        }
        this.editModal = true;
    }
}"
x-init="$watch('createStartTime', () => applyCreateDuration()); $watch('editSlotData.start_time', () => applyEditDuration())">


    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-brand-100 text-brand-800">
                    Campus Counseling Module
                </span>
                <span class="text-xs text-slate-400 font-semibold">• Admin Day, Seat &amp; Time Control</span>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">Campus Counseling Slot &amp; Capacity Manager</h2>
            <p class="text-xs text-slate-500">Manage counseling appointment dates, customize student seats per slot, and set time durations starting from 10 minutes upwards.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasPermission('scheduling_counseling', 'create'))
            <button type="button" @click="createModal = true" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Create Counseling Slot</span>
            </button>
            <button type="button" @click="batchModal = true" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition" title="Batch generate slots for a day">
                <i data-lucide="layers" class="w-4 h-4"></i>
                <span>Batch Generator</span>
            </button>
            @endif
            @if(auth()->user()->hasPermission('scheduling_counseling', 'edit'))
            <button type="button" @click="configModal = true" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-3.5 py-2.5 rounded-xl shadow-xs transition" title="Default fallback schedule settings">
                <i data-lucide="sliders" class="w-4 h-4"></i>
                <span>Settings</span>
            </button>
            @endif
            @if(auth()->user()->hasPermission('appointments', 'view'))
            <a href="{{ route('admin.scheduling.bookings', ['type' => 'counseling']) }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>View Bookings</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="text-xs font-bold text-slate-700">
            Total Counseling Sessions: <span class="text-brand-600 font-black">{{ $slots->total() }}</span>
        </div>

        <form method="GET" action="{{ route('admin.scheduling.counseling') }}" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date }}" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
            <button type="submit" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                Filter Date
            </button>
            @if($date)
                <a href="{{ route('admin.scheduling.counseling') }}" class="text-xs text-rose-600 font-bold hover:underline">
                    Clear Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Bulk Delete Action Bar (appears when 1 or more counseling slots are selected) -->
    @if(auth()->user()->hasPermission('scheduling_counseling', 'delete'))
    <div x-show="selectedSlots.length > 0" x-cloak 
         class="bg-slate-900 text-white rounded-2xl p-4 border border-slate-800 shadow-xl flex flex-wrap items-center justify-between gap-4 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-600 flex items-center justify-center text-white shrink-0">
                <i data-lucide="check-square" class="w-4 h-4"></i>
            </div>
            <div>
                <div class="text-xs font-bold text-white">
                    <span x-text="selectedSlots.length" class="text-rose-400 font-extrabold text-sm"></span> Counseling Slot(s) Selected
                </div>
                <div class="text-[11px] text-slate-400">
                    Delete selected counseling slots at once.
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" @click="selectedSlots = [...allSlotIds]" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition">
                Select All Visible (<span x-text="allSlotIds.length"></span>)
            </button>
            <button type="button" @click="selectedSlots = []" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 text-xs font-bold transition">
                Clear Selection
            </button>

            <form action="{{ route('admin.scheduling.slots.bulk-destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete ALL selected counseling slots?');">
                @csrf
                <template x-for="id in selectedSlots" :key="id">
                    <input type="hidden" name="slot_ids[]" :value="id">
                </template>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl shadow-lg shadow-rose-600/30 transition">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Delete Selected Slots</span>
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Counseling Slots Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4 w-10 text-center">
                            <input type="checkbox" 
                                   :checked="selectedSlots.length === allSlotIds.length && allSlotIds.length > 0" 
                                   @change="toggleAll($event)" 
                                   class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 cursor-pointer" 
                                   title="Select All Slots">
                        </th>
                        <th class="py-3.5 px-4">Counseling Date</th>
                        <th class="py-3.5 px-4">Time Range (Start – End)</th>
                        <th class="py-3.5 px-4">Capacity (Seats)</th>
                        <th class="py-3.5 px-4">Enrolled Students</th>
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
                            $appointmentsList = $slot->activeAppointments->map(function($a) {
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
                        <tr class="hover:bg-slate-50/70 transition" :class="isSelected({{ $slot->id }}) ? 'bg-brand-50/50' : ''">
                            <td class="py-3.5 px-4 text-center">
                                <input type="checkbox" 
                                       :value="{{ $slot->id }}" 
                                       :checked="isSelected({{ $slot->id }})" 
                                       @change="toggleSlot({{ $slot->id }})" 
                                       class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 cursor-pointer">
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 whitespace-nowrap">
                                {{ $slot->slot_date->format('l, M d, Y') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-mono font-extrabold text-slate-900 text-sm">
                                    {{ $slot->start_time }} – {{ $slot->end_time ?: 'TBD' }}
                                </div>
                                <div class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-800 text-[10px] font-bold">
                                        <i data-lucide="clock" class="w-3 h-3 text-brand-600"></i>
                                        <span>{{ $slot->formatted_duration }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-800 text-sm">{{ $cap }} {{ $cap == 1 ? 'Student' : 'Students' }}</span>
                            </td>
                            <td class="py-3.5 px-4 min-w-[160px]">
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="font-bold {{ $isFull ? 'text-rose-600' : 'text-slate-800' }}">
                                            {{ $booked }} enrolled
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
                                        <div class="h-1.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-brand-500' }}" style="width: {{ $pct }}%"></div>
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
                                @if(auth()->user()->hasPermission('scheduling_counseling', 'edit'))
                                <form action="{{ route('admin.scheduling.slot.toggle', $slot) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition
                                        {{ $slot->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $slot->is_active ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>
                                @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $slot->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $slot->is_active ? 'Active' : 'Disabled' }}
                                </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if(auth()->user()->hasPermission('scheduling_counseling', 'edit'))
                                    <button type="button" 
                                            @click="openEdit({{ json_encode($slot) }})"
                                            class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition"
                                            title="Edit Slot Capacity &amp; Range">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                    @endif

                                    @if(auth()->user()->hasPermission('scheduling_counseling', 'delete'))
                                    <form action="{{ route('admin.scheduling.slot.destroy', $slot) }}" method="POST" onsubmit="return confirm('Permanently delete this counseling slot?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-600 transition"
                                                title="Delete Slot">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i data-lucide="calendar-x" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <div class="font-bold text-slate-600">No Counseling Slots Scheduled</div>
                                <div class="text-xs text-slate-400 mt-1">Create individual custom slots or use the batch generator to schedule counseling slots.</div>
                                <div class="mt-4 flex items-center justify-center gap-2">
                                    <button type="button" @click="createModal = true" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition">
                                        + Create First Counseling Slot
                                    </button>
                                    <button type="button" @click="batchModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition">
                                        Quick Batch Generator
                                    </button>
                                </div>
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

    <!-- Create Single Counseling Slot Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="createModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Create Campus Counseling Slot</h3>
                    <p class="text-xs text-slate-400">Select day, seat capacity, and duration from 10 minutes upwards</p>
                </div>
                <button type="button" @click="createModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.scheduling.slot.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="counseling">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Counseling Date *</label>
                        <input type="date" name="slot_date" required min="{{ date('Y-m-d') }}" value="{{ $date ?: date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Seats / Capacity *</label>
                        <input type="number" name="capacity" required min="1" max="50" value="{{ $defaultCapacity ?: 1 }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Number of student seats (e.g. 1 for 1-on-1)</span>
                    </div>
                </div>

                <!-- Slot Duration Selector (10m, 15m, 20m, 30m, 45m, 1h, 1:30h, 2h, Custom) -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="timer" class="w-4 h-4 text-brand-600"></i>
                            Session Duration (10 Mins Upwards) *
                        </span>
                        <span class="text-[11px] font-extrabold text-brand-600" x-text="formatDurationDisplay(createDuration === 'custom' ? createCustomMinutes : createDuration)"></span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <select name="duration_minutes" x-model="createDuration" @change="applyCreateDuration()"
                                class="col-span-2 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="10">10 Minutes</option>
                            <option value="15">15 Minutes</option>
                            <option value="20">20 Minutes</option>
                            <option value="30">30 Minutes</option>
                            <option value="45">45 Minutes</option>
                            <option value="60">1 Hour (60 mins)</option>
                            <option value="90">1:30 Hour (90 mins)</option>
                            <option value="120">2 Hours (120 mins)</option>
                            <option value="custom">Custom Duration (Mins)...</option>
                        </select>
                        <div x-show="createDuration === 'custom'" x-cloak>
                            <input type="number" x-model="createCustomMinutes" @input="applyCreateDuration()" min="10" max="480" placeholder="e.g. 25"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div x-show="createDuration !== 'custom'" class="flex items-center justify-center px-2 py-1 bg-brand-100/70 text-brand-800 rounded-xl text-[11px] font-extrabold">
                            <span x-text="createDuration + ' Mins'"></span>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400">Selecting duration automatically calculates the End Time from Start Time.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-watch-time-picker name="start_time" model="createStartTime" value="09:00 AM" label="Start Time *" required placeholder="09:00 AM" />
                    </div>
                    <div>
                        <x-watch-time-picker name="end_time" model="createEndTime" value="09:30 AM" label="End Time *" required placeholder="09:30 AM" />
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-900 space-y-1">
                    <strong class="font-bold flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-blue-600"></i>
                        Overlap Protection:
                    </strong>
                    <p>The system prevents time conflicts so counseling slots on the same date will not unintentionally overlap.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Counselor / Room / Notes (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="e.g. Advisor: Dr. Sarah, Room 2"
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                        Save Counseling Slot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Batch Slot Generator Modal -->
    <div x-show="batchModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="batchModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Quick Batch Slot Generator</h3>
                    <p class="text-xs text-slate-400">Generate an entire day's counseling schedule in one click</p>
                </div>
                <button type="button" @click="batchModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.scheduling.slots.batch') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="counseling">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Target Date *</label>
                        <input type="date" name="slot_date" required min="{{ date('Y-m-d') }}" value="{{ $date ?: date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Seats Per Slot *</label>
                        <input type="number" name="capacity" required min="1" max="50" value="1"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Day Starts At *</label>
                        <input type="time" name="start_time" value="09:00" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Day Ends At *</label>
                        <input type="time" name="end_time" value="17:00" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Duration Per Slot *</label>
                    <select name="duration_minutes" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="10">10 Minutes per Session</option>
                        <option value="15">15 Minutes per Session</option>
                        <option value="20">20 Minutes per Session</option>
                        <option value="30" selected>30 Minutes per Session</option>
                        <option value="45">45 Minutes per Session</option>
                        <option value="60">60 Minutes (1 Hour)</option>
                    </select>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-1">
                    <p>• The system will automatically iterate between Start and End time and create non-overlapping slots.</p>
                    <p>• Any existing slots on that date will not be duplicated.</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="batchModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition">
                        Generate All Slots
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Counseling Slot Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="editModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Edit Counseling Slot Configuration</h3>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/scheduling/slots') }}/' + editSlotData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Seats / Capacity *</label>
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

                <!-- Slot Duration Selector in Edit Modal -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="timer" class="w-4 h-4 text-brand-600"></i>
                            Session Duration (10 Mins Upwards) *
                        </span>
                        <span class="text-[11px] font-extrabold text-brand-600" x-text="formatDurationDisplay(editDuration === 'custom' ? editCustomMinutes : editDuration)"></span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <select name="duration_minutes" x-model="editDuration" @change="applyEditDuration()"
                                class="col-span-2 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="10">10 Minutes</option>
                            <option value="15">15 Minutes</option>
                            <option value="20">20 Minutes</option>
                            <option value="30">30 Minutes</option>
                            <option value="45">45 Minutes</option>
                            <option value="60">1 Hour (60 mins)</option>
                            <option value="90">1:30 Hour (90 mins)</option>
                            <option value="120">2 Hours (120 mins)</option>
                            <option value="custom">Custom Duration (Mins)...</option>
                        </select>
                        <div x-show="editDuration === 'custom'" x-cloak>
                            <input type="number" x-model="editCustomMinutes" @input="applyEditDuration()" min="10" max="480" placeholder="e.g. 25"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div x-show="editDuration !== 'custom'" class="flex items-center justify-center px-2 py-1 bg-brand-100/70 text-brand-800 rounded-xl text-[11px] font-extrabold">
                            <span x-text="editDuration + ' Mins'"></span>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400">Updating duration recalculates the slot end time.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-watch-time-picker name="start_time" model="editSlotData.start_time" label="Start Time *" required />
                    </div>
                    <div>
                        <x-watch-time-picker name="end_time" model="editSlotData.end_time" label="End Time *" required />
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

    <!-- View Enrolled Students in Slot Modal -->
    <div x-show="studentsModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="studentsModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Enrolled Students in Counseling Slot</h3>
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
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-brand-100 text-brand-800" x-text="st.status"></span>
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

    <!-- Configure Counseling Fallback Schedule Modal -->
    <div x-show="configModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative" @click.away="configModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Default Working Days &amp; Hours</h3>
                    <p class="text-xs text-slate-400">Settings used for fallback schedules</p>
                </div>
                <button type="button" @click="configModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.scheduling.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="default_iets_capacity" value="15">
                <input type="hidden" name="max_per_slot" value="{{ $defaultCapacity ?: 1 }}">

                <!-- Working Days -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Available Working Days</label>
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
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Opening Time</label>
                        <input type="time" name="start_time" value="{{ substr($settings->start_time ?: '09:00:00', 0, 5) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Closing Time</label>
                        <input type="time" name="end_time" value="{{ substr($settings->end_time ?: '18:00:00', 0, 5) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Fallback Session Duration</label>
                    <select name="slot_duration_minutes" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="10" {{ $settings->slot_duration_minutes == 10 ? 'selected' : '' }}>10 Minutes</option>
                        <option value="15" {{ $settings->slot_duration_minutes == 15 ? 'selected' : '' }}>15 Minutes</option>
                        <option value="20" {{ $settings->slot_duration_minutes == 20 ? 'selected' : '' }}>20 Minutes</option>
                        <option value="30" {{ ($settings->slot_duration_minutes ?: 30) == 30 ? 'selected' : '' }}>30 Minutes</option>
                        <option value="45" {{ $settings->slot_duration_minutes == 45 ? 'selected' : '' }}>45 Minutes</option>
                        <option value="60" {{ $settings->slot_duration_minutes == 60 ? 'selected' : '' }}>60 Minutes (1 Hour)</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="configModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
