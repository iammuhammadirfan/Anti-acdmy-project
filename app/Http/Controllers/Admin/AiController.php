<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\Setting;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function index()
    {
        $conversations = AiConversation::latest()->paginate(20);

        if ($conversations->isNotEmpty()) {
            $latestMessageIds = AiMessage::whereIn('conversation_id', $conversations->pluck('id'))
                ->selectRaw('MAX(id) as id')
                ->groupBy('conversation_id')
                ->pluck('id');

            $latestMessages = AiMessage::whereIn('id', $latestMessageIds)
                ->get()
                ->keyBy('conversation_id');

            $conversations->each(function ($conv) use ($latestMessages) {
                $msg = $latestMessages->get($conv->id);
                $conv->setRelation('messages', $msg ? collect([$msg]) : collect([]));
            });
        }

        $aiSettings = [
            'provider' => Setting::get('ai_provider', 'openai'),
            'api_key' => Setting::get('ai_api_key', ''),
            'model' => Setting::get('ai_model', 'gpt-4o-mini'),
            'system_prompt' => Setting::get('ai_system_prompt', "You are Apex Academy & IETS's dedicated AI academic counselor and admissions advisor."),
            'chatbot_name' => Setting::get('ai_chatbot_name', 'Apex AI Counselor'),
            'welcome_message' => Setting::get('ai_welcome_message', 'Hello! Welcome to Apex Academy & IETS. How can I help you today with courses, faculty, or appointment booking?'),
            'is_enabled' => Setting::get('ai_is_enabled', '1'),
        ];

        return view('admin.ai.index', compact('conversations', 'aiSettings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'ai_provider' => 'required|string',
            'ai_model' => 'required|string',
            'ai_chatbot_name' => 'required|string|max:100',
            'ai_welcome_message' => 'required|string',
            'ai_system_prompt' => 'nullable|string',
        ]);

        Setting::set('ai_provider', $request->ai_provider, 'ai_agent');
        Setting::set('ai_model', $request->ai_model, 'ai_agent');
        Setting::set('ai_chatbot_name', $request->ai_chatbot_name, 'ai_agent');
        Setting::set('ai_welcome_message', $request->ai_welcome_message, 'ai_agent');
        Setting::set('ai_system_prompt', $request->ai_system_prompt, 'ai_agent');
        Setting::set('ai_is_enabled', $request->boolean('ai_is_enabled') ? '1' : '0', 'ai_agent');

        if ($request->filled('ai_api_key')) {
            Setting::set('ai_api_key', $request->ai_api_key, 'ai_agent', true); // encrypted
        }

        ActivityLog::log('update', 'ai', "Updated AI Chatbot settings and parameters.");

        return back()->with('success', 'AI Agent settings updated successfully.');
    }

    public function showConversation(AiConversation $conversation)
    {
        $messages = $conversation->messages()->orderBy('id', 'asc')->get();
        return view('admin.ai.show', compact('conversation', 'messages'));
    }

    public function destroyConversation(AiConversation $conversation)
    {
        $conversation->delete();
        return redirect()->route('admin.ai.index')->with('success', 'Chat session deleted.');
    }
}
