@extends('layouts.admin')

@section('title', 'All Bookings & Candidate Registrations')
@section('page_title', 'Appointment & Test Registrations')

@section('content')
<div class="space-y-6" x-data="{
    selectedBookings: [],
    allBookingIds: {{ json_encode($bookings->pluck('id')->toArray()) }},
    toggleAll(event) {
        if (event.target.checked) {
            this.selectedBookings = [...this.allBookingIds];
        } else {
            this.selectedBookings = [];
        }
    },
    isSelected(id) {
        return this.selectedBookings.includes(id);
    },
    toggleBooking(id) {
        if (this.selectedBookings.includes(id)) {
            this.selectedBookings = this.selectedBookings.filter(b => b !== id);
        } else {
            this.selectedBookings.push(id);
        }
    }
}">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Student &amp; Candidate Registrations</h2>
            <p class="text-xs text-slate-500">Track registrations with unique enrollment numbers, manage statuses, and delete or reschedule slots.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($bookings->total() > 0)
                <form action="{{ route('admin.scheduling.bookings.bulk-destroy') }}" method="POST" onsubmit="return confirm('DANGER: Are you sure you want to permanently delete ALL {{ $bookings->total() }} bookings matching current filter? This action CANNOT be undone!');" class="inline">
                    @csrf
                    <input type="hidden" name="all" value="1">
                    @if($type) <input type="hidden" name="type" value="{{ $type }}"> @endif
                    @if($testType) <input type="hidden" name="test_type" value="{{ $testType }}"> @endif
                    @if($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
                    @if($date) <input type="hidden" name="date" value="{{ $date }}"> @endif
                    <button type="submit" class="inline-flex items-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs px-3.5 py-2.5 rounded-xl border border-rose-200 transition shadow-sm" title="Delete all filtered bookings">
                        <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                        <span>Delete All ({{ $bookings->total() }})</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.scheduling.export', request()->all()) }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('admin.scheduling.slots') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Manage Slots</span>
            </a>
        </div>
    </div>

    <!-- Multi-field Filters Bar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-4">
        <form method="GET" action="{{ route('admin.scheduling.bookings') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            <!-- Search Text -->
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Search Student / Reg #</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Name, email, phone, IETS-2026..."
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Type</label>
                <select name="type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Types</option>
                    <option value="iets_test" {{ $type === 'iets_test' ? 'selected' : '' }}>IETS Test</option>
                    <option value="counseling" {{ $type === 'counseling' ? 'selected' : '' }}>Campus Counseling</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="no_show" {{ $status === 'no_show' ? 'selected' : '' }}>No Show</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Scheduled Date</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                    Apply Filters
                </button>
                <a href="{{ route('admin.scheduling.bookings') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 transition" title="Clear Filters">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Delete Action Bar (appears when 1 or more bookings are selected) -->
    <div x-show="selectedBookings.length > 0" x-cloak 
         class="bg-slate-900 text-white rounded-2xl p-4 border border-slate-800 shadow-xl flex flex-wrap items-center justify-between gap-4 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-600 flex items-center justify-center text-white shrink-0">
                <i data-lucide="check-square" class="w-4 h-4"></i>
            </div>
            <div>
                <div class="text-xs font-bold text-white">
                    <span x-text="selectedBookings.length" class="text-rose-400 font-extrabold text-sm"></span> Booking(s) Selected
                </div>
                <div class="text-[11px] text-slate-400">
                    Delete selected candidate registrations in bulk.
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button type="button" @click="selectedBookings = [...allBookingIds]" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition">
                Select All Visible (<span x-text="allBookingIds.length"></span>)
            </button>
            <button type="button" @click="selectedBookings = []" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 text-xs font-bold transition">
                Deselect All
            </button>
            <form action="{{ route('admin.scheduling.bookings.bulk-destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete ALL selected bookings?');">
                @csrf
                <template x-for="id in selectedBookings" :key="id">
                    <input type="hidden" name="booking_ids[]" :value="id">
                </template>
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow transition">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Delete Selected Bookings</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4 w-10 text-center">
                            <input type="checkbox" 
                                   @change="toggleAll($event)" 
                                   :checked="selectedBookings.length === allBookingIds.length && allBookingIds.length > 0" 
                                   class="rounded text-brand-600 focus:ring-brand-500 cursor-pointer"
                                   title="Select All Visible Bookings">
                        </th>
                        <th class="py-3.5 px-4">Registration #</th>
                        <th class="py-3.5 px-4">Student Candidate</th>
                        <th class="py-3.5 px-4">Type &amp; Subject</th>
                        <th class="py-3.5 px-4">Date &amp; Slot</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Booked At</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-slate-50/60 transition" :class="isSelected({{ $b->id }}) ? 'bg-rose-50/40' : ''">
                            <td class="py-3.5 px-4 text-center">
                                <input type="checkbox" 
                                       :value="{{ $b->id }}" 
                                       :checked="isSelected({{ $b->id }})" 
                                       @change="toggleBooking({{ $b->id }})" 
                                       class="rounded text-brand-600 focus:ring-brand-500 cursor-pointer">
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold whitespace-nowrap {{ $b->type === 'iets_test' ? 'text-emerald-700' : 'text-brand-600' }}">
                                <a href="{{ route('admin.scheduling.booking.show', $b) }}" class="hover:underline">
                                    {{ $b->registration_number ?: $b->booking_code }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $b->name }}</div>
                                <div class="text-slate-400 text-[11px]">{{ $b->email }} • {{ $b->phone }}</div>
                                @if($b->cnic_passport)
                                    <div class="text-[10px] text-slate-400 font-mono">ID: {{ $b->cnic_passport }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $b->type === 'iets_test' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                    {{ $b->type === 'iets_test' ? 'IETS Test' : 'Counseling' }}
                                </span>
                                <div class="text-slate-700 font-medium text-[11px] mt-1">
                                    {{ $b->test_type ?: $b->purpose }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ $b->appointment_date ? $b->appointment_date->format('l, M d, Y') : '-' }}</div>
                                <div class="text-brand-600 font-semibold">{{ $b->time_slot }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $b->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($b->status === 'pending' ? 'bg-amber-100 text-amber-800' : ($b->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : ($b->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700'))) }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-400 text-[11px]">
                                {{ $b->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.scheduling.booking.show', $b) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-xs font-bold transition">
                                        Manage &rarr;
                                    </a>
                                    <form action="{{ route('admin.scheduling.booking.destroy', $b) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete booking for {{ addslashes($b->name) }} ({{ $b->registration_number ?: $b->booking_code }})?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-400 transition" title="Delete Booking">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                No student bookings found matching the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
