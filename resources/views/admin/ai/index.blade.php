@extends('layouts.admin')

@section('title', 'Agentic AI Chatbot')
@section('page_title', 'Agentic AI Chatbot Engine')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- AI Settings Panel -->
        <div class="bg-white rounded-2xl p-5 sm:p-7 border border-slate-200/80 shadow-sm space-y-5 min-w-0">
            <div>
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <i data-lucide="bot" class="w-5 h-5 text-brand-600"></i>
                    Chatbot Configuration
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Use 100% Free AI models (Google Gemini / Groq) or OpenAI to answer student queries 24/7.</p>
            </div>

            <!-- Free AI Guidance Banner -->
            <div class="p-3.5 rounded-xl bg-blue-50/80 border border-blue-200 text-blue-900 text-xs space-y-2">
                <div class="font-bold flex items-center gap-1.5 text-blue-950">
                    <i data-lucide="sparkles" class="w-4 h-4 text-brand-600"></i>
                    100% Always Free AI Models:
                </div>
                <ul class="space-y-1 text-[11px] text-blue-800 list-disc list-inside">
                    <li><strong>Google Gemini (Recommended):</strong> Free API key on <a href="https://aistudio.google.com/app/apikey" target="_blank" class="underline font-bold text-brand-700 hover:text-brand-900">Google AI Studio</a>. Model: <code class="bg-blue-100 px-1 rounded">gemini-1.5-flash</code>.</li>
                    <li><strong>Groq Cloud:</strong> Free high-speed Llama 3 key on <a href="https://console.groq.com/keys" target="_blank" class="underline font-bold text-brand-700 hover:text-brand-900">Groq Console</a>. Model: <code class="bg-blue-100 px-1 rounded">llama-3.3-70b-versatile</code>.</li>
                    <li><strong>Built-in Smart Fallback:</strong> If no API key is provided, the chatbot still answers all academy questions for free!</li>
                </ul>
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
                    <select id="aiProviderSelect" name="ai_provider" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="gemini" {{ $aiSettings['provider'] === 'gemini' ? 'selected' : '' }}>Google Gemini (100% Free Forever)</option>
                        <option value="groq" {{ $aiSettings['provider'] === 'groq' ? 'selected' : '' }}>Groq Llama 3.3 (100% Free &amp; Fast)</option>
                        <option value="openrouter" {{ $aiSettings['provider'] === 'openrouter' ? 'selected' : '' }}>OpenRouter (Free Community Models)</option>
                        <option value="openai" {{ $aiSettings['provider'] === 'openai' ? 'selected' : '' }}>OpenAI (ChatGPT / GPT-4o)</option>
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Model Name</label>
                        <span id="modelHint" class="text-[10px] text-brand-600 font-semibold cursor-pointer">Auto-suggest model</span>
                    </div>
                    <input type="text" id="aiModelInput" name="ai_model" value="{{ $aiSettings['model'] }}" placeholder="gemini-1.5-flash" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">API Key</label>
                        <a id="getApiKeyLink" href="https://aistudio.google.com/app/apikey" target="_blank" class="text-[10px] text-brand-600 hover:text-brand-800 font-bold underline flex items-center gap-0.5">
                            Get Free Key &rarr;
                        </a>
                    </div>
                    <input type="password" name="ai_api_key" placeholder="{{ !empty($aiSettings['api_key']) ? '••••••••••••••••••••' : 'Paste API Key here' }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <span class="text-[10px] text-slate-400 mt-1 block">Keys are stored securely with Laravel encryption. Leave blank to keep existing key.</span>
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

                <!-- Test Connection Diagnostic Box -->
                <div id="testResultBox" class="hidden p-3.5 rounded-xl text-xs space-y-1.5 transition"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                    <button type="button" id="testApiBtn" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs py-2.5 rounded-xl shadow transition flex items-center justify-center gap-1.5">
                        <i data-lucide="zap" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>Test Connection</span>
                    </button>
                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs py-2.5 rounded-xl shadow transition">
                        Save AI Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Chat Conversation Logs -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Visitor Chat Sessions &amp; Action Logs</h3>
                    <p class="text-xs text-slate-500">Review real inquiries, questions asked, AI answers, and automated tool actions.</p>
                </div>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const providerSelect = document.getElementById('aiProviderSelect');
        const modelInput = document.getElementById('aiModelInput');
        const keyLink = document.getElementById('getApiKeyLink');
        const modelHint = document.getElementById('modelHint');

        const providerPresets = {
            gemini: {
                model: 'gemini-1.5-flash',
                link: 'https://aistudio.google.com/app/apikey',
                label: 'Get Free Gemini Key (No Card Required) &rarr;'
            },
            groq: {
                model: 'qwen/qwen3.8-27b',
                link: 'https://console.groq.com/keys',
                label: 'Get Free Groq Key (Fast & Free) &rarr;'
            },
            openrouter: {
                model: 'meta-llama/llama-3.2-3b-instruct:free',
                link: 'https://openrouter.ai/keys',
                label: 'Get OpenRouter Key &rarr;'
            },
            openai: {
                model: 'gpt-4o-mini',
                link: 'https://platform.openai.com/api-keys',
                label: 'Get OpenAI Key &rarr;'
            }
        };

        function updateProviderUI() {
            const selected = providerSelect.value;
            const preset = providerPresets[selected] || providerPresets.gemini;
            if (keyLink) {
                keyLink.href = preset.link;
                keyLink.innerHTML = preset.label;
            }
            if (modelInput && !modelInput.value) {
                modelInput.value = preset.model;
            }
        }

        providerSelect?.addEventListener('change', () => {
            const selected = providerSelect.value;
            const preset = providerPresets[selected] || providerPresets.gemini;
            if (modelInput) {
                modelInput.value = preset.model;
            }
            updateProviderUI();
        });

        modelHint?.addEventListener('click', () => {
            const selected = providerSelect.value;
            const preset = providerPresets[selected] || providerPresets.gemini;
            if (modelInput) {
                modelInput.value = preset.model;
            }
        });

        updateProviderUI();

        // AJAX Test API Connection
        const testBtn = document.getElementById('testApiBtn');
        const resultBox = document.getElementById('testResultBox');

        testBtn?.addEventListener('click', async () => {
            const provider = providerSelect.value;
            const model = modelInput.value;
            const keyInput = document.querySelector('input[name="ai_api_key"]');
            const apiKey = keyInput ? keyInput.value : '';

            testBtn.disabled = true;
            testBtn.innerHTML = `<span class="animate-spin inline-block mr-1">⏳</span> Testing...`;
            resultBox.className = 'p-3.5 rounded-xl text-xs space-y-1 bg-slate-100 text-slate-700 border border-slate-200 block';
            resultBox.innerHTML = `<strong>Testing API connection...</strong> Contacting AI servers...`;

            try {
                const res = await fetch("{{ route('admin.ai.test') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ai_provider: provider,
                        ai_model: model,
                        ai_api_key: apiKey
                    })
                });

                const data = await res.json();
                if (data.success) {
                    if (data.active_model && modelInput) {
                        modelInput.value = data.active_model;
                    }
                    resultBox.className = 'p-3.5 rounded-xl text-xs space-y-1 bg-emerald-50 text-emerald-900 border border-emerald-200 block';
                    resultBox.innerHTML = `<div class="font-bold flex items-center gap-1 text-emerald-950">✅ Connected successfully! (${data.provider})</div><div class="text-[11px] text-emerald-800 bg-white/70 p-2 rounded-lg border border-emerald-100 font-mono mt-1">${data.message}</div>`;
                } else {
                    resultBox.className = 'p-3.5 rounded-xl text-xs space-y-1 bg-rose-50 text-rose-900 border border-rose-200 block';
                    resultBox.innerHTML = `<div class="font-bold flex items-center gap-1 text-rose-950">❌ Connection Failed</div><div class="text-[11px] text-rose-800 break-words mt-1">${data.message}</div>`;
                }
            } catch (err) {
                resultBox.className = 'p-3.5 rounded-xl text-xs space-y-1 bg-rose-50 text-rose-900 border border-rose-200 block';
                resultBox.innerHTML = `<div class="font-bold flex items-center gap-1 text-rose-950">❌ Error</div><div class="text-[11px] text-rose-800 mt-1">${err.message}</div>`;
            } finally {
                testBtn.disabled = false;
                testBtn.innerHTML = `<i data-lucide="zap" class="w-3.5 h-3.5 text-amber-400"></i><span>Test Connection</span>`;
                if (window.lucide) lucide.createIcons();
            }
        });
    });
</script>
@endsection
