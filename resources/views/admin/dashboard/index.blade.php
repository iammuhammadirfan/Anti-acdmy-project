@extends('layouts.admin')

@section('title', 'Institutional Overview & Analytics')
@section('page_title', 'Institutional Overview & Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Top Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Teachers -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Faculty</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ $metrics['total_teachers'] }}</span>
                <span class="text-[11px] text-emerald-600 block mt-1 font-semibold flex items-center gap-0.5">
                    <i data-lucide="check" class="w-3 h-3"></i> Qualified Instructors
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Students Stat -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Active Students</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ $metrics['total_students'] }}</span>
                <span class="text-[11px] text-brand-600 block mt-1 font-semibold">Enrolled across batches</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Appointments -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Appointments</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ $metrics['total_appointments'] }}</span>
                <span class="text-[11px] text-amber-600 font-semibold block mt-1">
                    {{ $metrics['pending_appointments'] }} Pending Review
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- IETS Results -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">IETS Success</span>
                <span class="text-2xl font-extrabold text-slate-900">{{ $metrics['iets_results'] }}</span>
                <span class="text-[11px] text-purple-600 font-semibold block mt-1">Verified Band Scores</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i data-lucide="award" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Appointments Growth -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Booking Inquiries (Last 6 Months)</h3>
                    <p class="text-xs text-slate-500">Student counseling and diagnostic test appointments</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="appointmentsChart"></canvas>
            </div>
        </div>

        <!-- Appointment Status Breakdown -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base mb-1">Booking Status Breakdown</h3>
                <p class="text-xs text-slate-500 mb-4">Distribution by appointment state</p>
            </div>
            <div class="h-56 relative flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Two-Column Feed: Recent Appointments & Contact Inquiries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Appointments -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-5 h-5 text-brand-600"></i>
                    <h3 class="font-bold text-slate-900 text-base">Recent Appointments</h3>
                </div>
                <a href="{{ route('admin.appointments.index') }}" class="text-xs text-brand-600 hover:text-brand-800 font-semibold">View All &rarr;</a>
            </div>

            @if($recentAppointments->isEmpty())
                <p class="text-sm text-slate-400 py-6 text-center">No appointments booked yet.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($recentAppointments as $apt)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <h5 class="text-sm font-bold text-slate-900">{{ $apt->name }}</h5>
                                <div class="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                                    <span>{{ $apt->appointment_date->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $apt->time_slot }}</span>
                                    <span>•</span>
                                    <span class="text-slate-600 font-medium">{{ $apt->purpose }}</span>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold uppercase px-2.5 py-1 rounded-full 
                                {{ $apt->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($apt->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                {{ $apt->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Contact Messages -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <i data-lucide="inbox" class="w-5 h-5 text-amber-600"></i>
                    <h3 class="font-bold text-slate-900 text-base">Latest Contact Messages</h3>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="text-xs text-brand-600 hover:text-brand-800 font-semibold">View All &rarr;</a>
            </div>

            @if($recentMessages->isEmpty())
                <p class="text-sm text-slate-400 py-6 text-center">No messages submitted yet.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($recentMessages as $msg)
                        <div class="py-3 flex items-start justify-between">
                            <div>
                                <h5 class="text-sm font-bold text-slate-900">{{ $msg->name }} <span class="text-xs font-normal text-slate-500">({{ $msg->email }})</span></h5>
                                <p class="text-xs text-slate-600 mt-1 line-clamp-1">{{ $msg->message }}</p>
                                <span class="text-[10px] text-slate-400 mt-1 block">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full {{ $msg->status === 'unread' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $msg->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Audit Logs -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <i data-lucide="activity" class="w-5 h-5 text-slate-700"></i>
                <h3 class="font-bold text-slate-900 text-base">System Activity &amp; Audit Trail</h3>
            </div>
            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.activity.index') }}" class="text-xs text-brand-600 hover:text-brand-800 font-semibold">All Audit Logs &rarr;</a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-2.5 px-3">Timestamp</th>
                        <th class="py-2.5 px-3">User</th>
                        <th class="py-2.5 px-3">Action</th>
                        <th class="py-2.5 px-3">Module</th>
                        <th class="py-2.5 px-3">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentActivities as $act)
                        <tr>
                            <td class="py-2.5 px-3 text-slate-400 whitespace-nowrap">{{ $act->created_at->format('M d, H:i') }}</td>
                            <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $act->user ? $act->user->name : 'System/Guest' }}</td>
                            <td class="py-2.5 px-3"><span class="px-2 py-0.5 rounded font-mono uppercase text-[10px] bg-slate-100">{{ $act->action }}</span></td>
                            <td class="py-2.5 px-3 font-medium text-brand-600">{{ $act->module }}</td>
                            <td class="py-2.5 px-3 text-slate-700">{{ $act->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-400">No activity logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Appointments Line Chart
        const aptCtx = document.getElementById('appointmentsChart').getContext('2d');
        new Chart(aptCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthsLabels) !!},
                datasets: [{
                    label: 'Booked Appointments',
                    data: {!! json_encode($monthlyAppointments) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Status Doughnut Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $statusCounts['pending'] }},
                        {{ $statusCounts['confirmed'] }},
                        {{ $statusCounts['completed'] }},
                        {{ $statusCounts['cancelled'] }}
                    ],
                    backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
