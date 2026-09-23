@extends('layouts.admin')

@section('title', 'Inquiry from ' . $message->name)
@section('page_title', 'Inquiry Details')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.messages.index') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Messages
        </a>
        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">Delete Message</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <div class="flex items-start justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-bold text-slate-900">{{ $message->subject ?: 'General Inquiry' }}</h3>
                <p class="text-xs text-slate-500 mt-1">From: <strong class="text-slate-800">{{ $message->name }}</strong> ({{ $message->email }}) • {{ $message->created_at->format('M d, Y H:i') }}</p>
                @if($message->phone)
                    <p class="text-xs text-slate-500">Phone: <strong class="text-slate-800">{{ $message->phone }}</strong></p>
                @endif
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase
                {{ $message->status === 'unread' ? 'bg-red-100 text-red-800' : ($message->status === 'replied' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                {{ $message->status }}
            </span>
        </div>

        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-sm leading-relaxed text-slate-800 whitespace-pre-line">
            {{ $message->message }}
        </div>

        <!-- Admin Reply / Notes Form -->
        <div class="pt-4 border-t border-slate-100 space-y-3">
            <h4 class="font-bold text-slate-900 text-sm">Counselor Response &amp; Follow-Up Notes</h4>
            <form action="{{ route('admin.messages.reply', $message) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="admin_reply" rows="3" placeholder="Enter notes or email response record..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $message->admin_reply }}</textarea>
                <div class="flex justify-end">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-5 py-2 rounded-xl shadow transition">
                        Save Reply &amp; Mark Replied
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
