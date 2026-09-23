@extends('layouts.admin')

@section('title', 'Inquiries & Contact Messages')
@section('page_title', 'Contact Submissions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Contact Form Inquiries</h2>
            <p class="text-xs text-slate-500">Read messages submitted through the website, mark as replied, and keep notes.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-5">Sender</th>
                        <th class="py-3.5 px-4">Subject</th>
                        <th class="py-3.5 px-4">Message Snippet</th>
                        <th class="py-3.5 px-4">Date</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-slate-50/60 transition {{ $msg->status === 'unread' ? 'bg-blue-50/30 font-semibold' : '' }}">
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-900">{{ $msg->name }}</div>
                                <div class="text-xs text-slate-400">{{ $msg->email }} • {{ $msg->phone ?: 'No Phone' }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-800">{{ $msg->subject ?: 'General Inquiry' }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 truncate max-w-xs">{{ $msg->message }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-400 whitespace-nowrap">{{ $msg->created_at->format('M d, Y') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $msg->status === 'unread' ? 'bg-red-100 text-red-800' : ($msg->status === 'replied' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $msg->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.messages.show', $msg) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-xs font-bold transition">
                                    Open &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No contact messages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
