@extends('layouts.admin')

@section('title', 'Slot Calendar & Schedule Management')
@section('page_title', 'Appointment & Test Calendar')

@section('content')
<div class="space-y-6" x-data="{ addSlotModal: false, selectedDate: '{{ $selectedDate }}' }">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Interactive Slot Calendar</h2>
            <p class="text-xs text-slate-500">Inspect scheduled IETS tests and campus counseling sessions by date.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="addSlotModal = true" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Slot to Selected Date</span>
            </button>
            <a href="{{ route('admin.scheduling.slots') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Slot Table View</span>
            </a>
        </div>
    </div>

    <!-- Calendar Month Navigation & Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Grid (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            @php
                $carbonMonth = \Carbon\Carbon::parse($month . '-01');
                $prevMonth = $carbonMonth->copy()->subMonth()->format('Y-m');
                $nextMonth = $carbonMonth->copy()->addMonth()->format('Y-m');
                $daysInMonth = $carbonMonth->daysInMonth;
                $startDayOfWeek = $carbonMonth->copy()->startOfMonth()->dayOfWeek; // 0=Sun, 1=Mon...
            @endphp

            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <h3 class="font-extrabold text-base text-slate-900">{{ $carbonMonth->format('F Y') }}</h3>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('admin.scheduling.calendar', ['month' => $prevMonth, 'date' => $selectedDate]) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </a>
                    <a href="{{ route('admin.scheduling.calendar', ['month' => date('Y-m'), 'date' => date('Y-m-d')]) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Current Month
                    </a>
                    <a href="{{ route('admin.scheduling.calendar', ['month' => $nextMonth, 'date' => $selectedDate]) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Day Headers -->
            <div class="grid grid-cols-7 gap-1 text-center font-bold text-[11px] uppercase tracking-wider text-slate-400 py-1">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>

            <!-- Days Grid -->
            <div class="grid grid-cols-7 gap-1.5 text-xs">
                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="h-24 p-1.5 bg-slate-50/40 rounded-xl border border-transparent"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateStr = sprintf('%s-%02d', $month, $day);
                        $daySlotsList = $slots->get($dateStr, collect());
                        $isToday = ($dateStr === date('Y-m-d'));
                        $isSelected = ($dateStr === $selectedDate);
                        $isBlocked = in_array($dateStr, $blockedDates);
                    @endphp
                    <a href="{{ route('admin.scheduling.calendar', ['month' => $month, 'date' => $dateStr]) }}" 
                       class="h-24 p-1.5 rounded-xl border transition flex flex-col justify-between text-left group
                       {{ $isSelected ? 'border-brand-500 bg-brand-50/50 ring-2 ring-brand-400/50 shadow-sm' : ($isBlocked ? 'border-red-200 bg-red-50/30' : 'border-slate-200/80 bg-white hover:border-slate-300 hover:bg-slate-50') }}">
                        
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs {{ $isToday ? 'w-5 h-5 rounded-full bg-brand-600 text-white flex items-center justify-center text-[11px]' : ($isSelected ? 'text-brand-900 font-extrabold' : 'text-slate-700') }}">
                                {{ $day }}
                            </span>
                            @if($isBlocked)
                                <span class="w-2 h-2 rounded-full bg-red-500" title="Blocked Date"></span>
                            @endif
                        </div>

                        <!-- Mini Slot Pills -->
                        <div class="space-y-1 overflow-hidden">
                            @foreach($daySlotsList->take(2) as $s)
                                @php
                                    $bCount = $s->booked_count;
                                    $sFull = $s->is_full;
                                @endphp
                                <div class="px-1.5 py-0.5 rounded text-[9px] font-bold truncate flex items-center justify-between
                                    {{ $s->type === 'iets_test' 
                                        ? ($sFull ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800') 
                                        : 'bg-blue-100 text-blue-800' }}">
                                    <span>{{ $s->start_time }}</span>
                                    <span>{{ $bCount }}/{{ $s->capacity }}</span>
                                </div>
                            @endforeach
                            @if($daySlotsList->count() > 2)
                                <div class="text-[9px] text-slate-400 font-semibold px-1">+{{ $daySlotsList->count() - 2 }} more</div>
                            @endif
                        </div>
                    </a>
                @endfor
            </div>
        </div>

        <!-- Selected Day Details Side Panel -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 block">Selected Date</span>
                    <h4 class="font-extrabold text-slate-900 text-base">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}
                    </h4>
                </div>
                <button type="button" @click="addSlotModal = true" class="p-2 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-600 transition" title="Add Slot">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="space-y-3 max-h-[500px] overflow-y-auto">
                @forelse($daySlots as $slot)
                    @php
                        $bCount = $slot->booked_count;
                        $cap = $slot->capacity;
                        $rem = $slot->remaining_seats;
                        $isFull = $slot->is_full;
                    @endphp
                    <div class="p-3.5 rounded-2xl border border-slate-200 bg-slate-50 space-y-2.5">
                        <div class="flex items-center justify-between text-xs">
                            <div>
                                <span class="font-extrabold text-sm text-slate-900 block">{{ $slot->start_time }}</span>
                                <span class="text-[11px] font-semibold uppercase
                                    {{ $slot->type === 'iets_test' ? 'text-emerald-700' : 'text-blue-700' }}">
                                    {{ $slot->type === 'iets_test' ? 'IETS Test' : 'Counseling' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $isFull ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $isFull ? 'FULL' : "{$rem} Left" }}
                                </span>
                                <div class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $bCount }} / {{ $cap }} Registered</div>
                            </div>
                        </div>

                        <!-- Actions for this slot -->
                        <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
                            <form action="{{ route('admin.scheduling.slot.toggle', $slot) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold hover:underline {{ $slot->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $slot->is_active ? 'Active' : 'Disabled' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.scheduling.slot.destroy', $slot) }}" method="POST" onsubmit="return confirm('Delete this slot?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-bold text-rose-600 hover:underline">
                                    Delete Slot
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-xs space-y-2">
                        <i data-lucide="calendar-x" class="w-8 h-8 mx-auto text-slate-300"></i>
                        <p>No slots configured for this date.</p>
                        <button type="button" @click="addSlotModal = true" class="text-xs text-brand-600 font-bold hover:underline">
                            + Create Slot for this Date
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Add Slot Modal -->
    <div x-show="addSlotModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl relative" @click.away="addSlotModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Add Slot to Selected Date</h3>
                <button type="button" @click="addSlotModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.scheduling.slot.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="slot_date" :value="selectedDate">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Date</label>
                    <div class="text-sm font-bold text-slate-900 bg-slate-50 p-2.5 rounded-xl border border-slate-200" x-text="selectedDate"></div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Slot Type *</label>
                    <select name="type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="iets_test" selected>IETS Test (Capacity default: 15)</option>
                        <option value="counseling">Campus Counseling</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Start Time *</label>
                        <input type="text" name="start_time" required placeholder="09:00 AM" value="09:00 AM"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">End Time</label>
                        <input type="text" name="end_time" placeholder="10:00 AM" value="10:00 AM"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Capacity *</label>
                        <input type="number" name="capacity" required min="1" max="50" value="15"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Duration (Min)</label>
                        <input type="number" name="duration_minutes" required min="15" max="180" value="60"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="addSlotModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                        Create Slot
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
