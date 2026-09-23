@extends('layouts.admin')

@section('title', 'Activity Audit Trail')
@section('page_title', 'System Audit Trail & Security Logs')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-lg font-bold text-slate-900">Audit Logs</h2>
        <p class="text-xs text-slate-500">Immutable record of logins, creates, updates, status changes, and settings modifications.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Date / Time</th>
                        <th class="py-3 px-4">Actor</th>
                        <th class="py-3 px-4">Action</th>
                        <th class="py-3 px-4">Module</th>
                        <th class="py-3 px-4">Activity Description</th>
                        <th class="py-3 px-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3 px-4 text-slate-400 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="py-3 px-4 font-sans font-bold text-slate-900">{{ $log->user ? $log->user->name : 'System' }}</td>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-slate-100 text-slate-700">{{ $log->action }}</span></td>
                            <td class="py-3 px-4 font-sans font-semibold text-brand-600">{{ $log->module }}</td>
                            <td class="py-3 px-4 font-sans text-slate-800">{{ $log->description }}</td>
                            <td class="py-3 px-4 text-slate-400">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-sans">No activity logged.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
