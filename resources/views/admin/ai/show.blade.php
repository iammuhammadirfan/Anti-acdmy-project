@extends('layouts.admin')

@section('title', 'Chat Session: ' . $conversation->session_id)
@section('page_title', 'Conversation Transcript')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.ai.index') }}" class="text-xs text-slate-500 hover:text-brand-600 flex items-center gap-1 font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to AI Sessions
        </a>
        <form action="{{ route('admin.ai.conversation.destroy', $conversation) }}" method="POST" onsubmit="return confirm('Delete this conversation log?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">Delete Session</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-xs text-slate-500">
            <span>Session: <strong class="font-mono text-slate-800">{{ $conversation->session_id }}</strong></span>
            <span>IP: {{ $conversation->user_ip }} • Messages: {{ $conversation->total_messages }}</span>
        </div>

        <div class="space-y-4 pt-2">
            @foreach($messages as $msg)
                <div class="flex items-start gap-3 text-sm {{ $msg->role === 'user' ? 'justify-end' : '' }}">
                    @if($msg->role !== 'user')
                        <div class="w-8 h-8 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-1">
                            <i data-lucide="{{ $msg->role === 'tool' ? 'tool' : 'bot' }}" class="w-4 h-4"></i>
                        </div>
                    @endif

                    <div class="rounded-2xl p-4 max-w-lg shadow-sm {{ $msg->role === 'user' ? 'bg-brand-600 text-white rounded-tr-sm' : ($msg->role === 'tool' ? 'bg-amber-50 border border-amber-200 text-amber-900 rounded-tl-sm' : 'bg-slate-50 border border-slate-200 text-slate-800 rounded-tl-sm') }}">
                        <div class="flex items-center justify-between text-[10px] font-bold uppercase mb-1 opacity-75">
                            <span>{{ $msg->role }}</span>
                            <span>{{ $msg->created_at->format('H:i:s') }}</span>
                        </div>
                        <p class="whitespace-pre-line leading-relaxed text-xs">{{ $msg->content }}</p>
                        @if($msg->tool_called)
                            <div class="mt-2 pt-2 border-t border-amber-200/60 text-[11px] font-mono">
                                <strong>Tool Action:</strong> {{ $msg->tool_called }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
