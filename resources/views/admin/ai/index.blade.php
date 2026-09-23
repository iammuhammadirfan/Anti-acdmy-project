@extends('layouts.admin')

@section('title', 'Agentic AI Chatbot')
@section('page_title', 'Agentic AI Chatbot Engine')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- AI Settings Panel -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-5">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Chatbot Configuration</h3>
                <p class="text-xs text-slate-500">Configure LLM credentials, model parameters, and counselor prompt instructions.</p>
            </div>

            <form action="{{ route('admin.ai.settings') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Chatbot Display Name</label>
                    <input type="text" name="ai_chatbot_name" value="{{ $aiSettings['chatbot_name'] }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">AI Provider</label>
                    <select name="ai_provider" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="openai" {{ $aiSettings['provider'] === 'openai' ? 'selected' : '' }}>OpenAI (ChatGPT / GPT-4o)</option>
                        <option value="gemini" {{ $aiSettings['provider'] === 'gemini' ? 'selected' : '' }}>Google Gemini API</option>
                        <option value="groq" {{ $aiSettings['provider'] === 'groq' ? 'selected' : '' }}>Groq Llama 3 Fast Inference</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Model Name</label>
                    <input type="text" name="ai_model" value="{{ $aiSettings['model'] }}" placeholder="gpt-4o-mini / gemini-1.5-flash" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">API Key (Securely Encrypted)</label>
                    <input type="password" name="ai_api_key" placeholder="{{ !empty($aiSettings['api_key']) ? '••••••••••••••••••••' : 'Enter API Key' }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <span class="text-[10px] text-slate-400 mt-1 block">API keys are encrypted in MySQL and never sent to the browser.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Welcome Message</label>
                    <textarea name="ai_welcome_message" rows="2" required
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $aiSettings['welcome_message'] }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">System Instructions Prompt</label>
                    <textarea name="ai_system_prompt" rows="4"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $aiSettings['system_prompt'] }}</textarea>
                </div>

                <div class="flex items-center pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ai_is_enabled" value="1" {{ $aiSettings['is_enabled'] == '1' ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-800">Enable Floating Chatbot Widget on Frontend</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs py-2.5 rounded-xl shadow transition">
                    Save AI Settings
                </button>
            </form>
        </div>

        <!-- Chat Conversation Logs -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Visitor Chat Sessions &amp; Action Logs</h3>
                <p class="text-xs text-slate-500">Review real inquiries, questions asked, AI answers, and automated tool actions.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-3">Session</th>
                            <th class="py-3 px-3">IP Address</th>
                            <th class="py-3 px-3">Messages</th>
                            <th class="py-3 px-3">Last Query</th>
                            <th class="py-3 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($conversations as $conv)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3 px-3 font-mono font-bold text-slate-900">{{ substr($conv->session_id, 0, 14) }}...</td>
                                <td class="py-3 px-3 text-slate-400 font-mono">{{ $conv->user_ip }}</td>
                                <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-brand-50 text-brand-700 font-bold">{{ $conv->total_messages }}</span></td>
                                <td class="py-3 px-3 text-slate-700 truncate max-w-xs">{{ $conv->messages->first() ? $conv->messages->first()->content : '—' }}</td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('admin.ai.conversation', $conv) }}" class="text-brand-600 hover:text-brand-800 font-bold">Inspect &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">No chat sessions recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($conversations->hasPages())
                <div class="pt-3 border-t border-slate-100">
                    {{ $conversations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
