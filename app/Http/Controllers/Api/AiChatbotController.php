<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AiAgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AiChatbotController extends Controller
{
    protected AiAgentService $aiService;

    public function __construct(AiAgentService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'required|string|max:100',
        ]);

        $isEnabled = Setting::get('ai_is_enabled', '1');
        if ($isEnabled === '0' || $isEnabled === false) {
            return response()->json([
                'success' => false,
                'message' => 'The AI Assistant is currently offline. Please use our Contact form or WhatsApp to reach us.',
            ], 503);
        }

        $throttleKey = 'ai_chat|' . $request->ip() . '|' . $request->session_id;

        if (RateLimiter::tooManyAttempts($throttleKey, 20)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "You have sent too many messages. Please wait {$seconds} seconds before typing again.",
            ], 429);
        }

        RateLimiter::hit($throttleKey, 60);

        $result = $this->aiService->processChat(
            $request->session_id,
            $request->message,
            $request->ip()
        );

        return response()->json($result);
    }
}
