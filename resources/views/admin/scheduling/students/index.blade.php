@extends('layouts.admin')

@section('title', 'Registered Students Directory')
@section('page_title', 'Student Directory')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Student &amp; Candidate Registry</h2>
            <p class="text-xs text-slate-500">Directory of all students who have registered for counseling or IETS test slots.</p>
        </div>
        <form method="GET" action="{{ route('admin.scheduling.students') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, phone..."
                   class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm transition">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Student Name</th>
                        <th class="py-3.5 px-4">Email Address</th>
                        <th class="py-3.5 px-4">Phone / WhatsApp</th>
                        <th class="py-3.5 px-4">Total Bookings</th>
                        <th class="py-3.5 px-4">Latest Booking</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $st)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                                {{ $st->name }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                {{ $st->email }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-500">
                                {{ $st->phone }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                    {{ $st->total_bookings }} booking(s)
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">
                                {{ \Carbon\Carbon::parse($st->last_booking_at)->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.scheduling.bookings', ['search' => $st->email]) }}" 
                                   class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-xs font-bold transition">
                                    View History &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No students found matching search.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
