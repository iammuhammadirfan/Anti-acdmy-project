@extends('layouts.admin')

@section('title', 'Appointment Bookings')
@section('page_title', 'Appointment Management & Calendar')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Student &amp; Parent Appointments</h2>
            <p class="text-xs text-slate-500">Track and manage appointment inquiries, confirm slots, and trigger automated emails/WhatsApp updates.</p>
        </div>
        <a href="{{ route('admin.calendar.index') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow transition">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            <span>Calendar &amp; Working Hours</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.appointments.index') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !$status ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            All Appointments
        </a>
        <a href="{{ route('admin.appointments.index', ['status' => 'pending']) }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
            Pending
        </a>
        <a href="{{ route('admin.appointments.index', ['status' => 'confirmed']) }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $status === 'confirmed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
            Confirmed
        </a>
        <a href="{{ route('admin.appointments.index', ['status' => 'completed']) }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $status === 'completed' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
            Completed
        </a>
        <a href="{{ route('admin.appointments.index', ['status' => 'cancelled']) }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $status === 'cancelled' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
            Cancelled
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-5">Code</th>
                        <th class="py-3.5 px-4">Student Name</th>
                        <th class="py-3.5 px-4">Contact Info</th>
                        <th class="py-3.5 px-4">Date &amp; Slot</th>
                        <th class="py-3.5 px-4">Purpose</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($appointments as $apt)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-5 font-mono text-xs font-bold text-brand-600">
                                <a href="{{ route('admin.appointments.show', $apt) }}" class="hover:underline">{{ $apt->booking_code }}</a>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $apt->name }}</td>
                            <td class="py-3.5 px-4 text-xs">
                                <div>{{ $apt->email }}</div>
                                <div class="text-slate-400">{{ $apt->phone }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-semibold text-slate-800">{{ $apt->appointment_date->format('M d, Y') }}</div>
                                <div class="text-brand-600 font-medium">{{ $apt->time_slot }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700 font-medium">{{ $apt->purpose }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $apt->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($apt->status === 'pending' ? 'bg-amber-100 text-amber-800' : ($apt->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-700')) }}">
                                    {{ $apt->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.appointments.show', $apt) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-xs font-bold transition">
                                    Manage &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No appointments found matching filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
