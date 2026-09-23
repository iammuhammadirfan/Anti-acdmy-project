<?php

namespace App\Services;

use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\Appointment;
use App\Models\Faq;
use App\Models\IetsProgram;
use App\Models\Setting;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiAgentService
{
    protected AppointmentSlotService $slotService;
    protected NotificationService $notificationService;

    public function __construct(AppointmentSlotService $slotService, NotificationService $notificationService)
    {
        $this->slotService = $slotService;
        $this->notificationService = $notificationService;
    }

    /**
     * Process an incoming chat message with session and tool execution
     */
    public function processChat(string $sessionId, string $userMessage, ?string $userIp = null): array
    {
        // 1. Sanitize user input & prevent prompt injection
        $sanitizedInput = strip_tags(trim($userMessage));
        if (strlen($sanitizedInput) > 1000) {
            $sanitizedInput = substr($sanitizedInput, 0, 1000);
        }

        // 2. Retrieve or create conversation session
        $conversation = AiConversation::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'user_ip' => $userIp ?: request()->ip(),
                'total_messages' => 0,
            ]
        );

        // 3. Save incoming user message
        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $sanitizedInput,
        ]);

        $conversation->increment('total_messages');

        // 4. Check for tool invocation requests directly in the query
        $toolResponse = $this->evaluateAndRunTools($sanitizedInput, $conversation);
        if ($toolResponse !== null) {
            return $toolResponse;
        }

        // 5. Generate AI response using Provider or Dynamic Knowledge Base
        $aiReply = $this->generateKnowledgeAnswer($sanitizedInput, $conversation);

        // Save AI reply
        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $aiReply,
        ]);
        $conversation->increment('total_messages');

        return [
            'success' => true,
            'message' => $aiReply,
            'tool_called' => null,
            'tool_result' => null,
        ];
    }

    /**
     * Evaluate query against Agentic action tools
     */
    protected function evaluateAndRunTools(string $query, AiConversation $conversation): ?array
    {
        $qLower = strtolower($query);

        // Action Tool: Check appointment availability (e.g., "check slots for tomorrow", "is 2026-09-25 available?")
        if (preg_match('/(?:appointment|slot|book|available).*(?:tomorrow|today|\d{4}-\d{2}-\d{2}|monday|tuesday|wednesday|thursday|friday|saturday)/i', $qLower)) {
            $date = $this->extractDateFromQuery($qLower);
            if ($date) {
                $slots = $this->slotService->getAvailableSlots($date->toDateString());
                $formattedDate = $date->format('l, F j, Y');

                if (empty($slots)) {
                    $reply = "I checked our appointment calendar for **{$formattedDate}**, but unfortunately there are no open slots available on that day. You can check another date or submit an inquiry through our Contact page!";
                } else {
                    $reply = "Here are the open appointment slots for **{$formattedDate}**:\n\n" .
                             implode("  •  ", array_slice($slots, 0, 8)) .
                             "\n\nTo book a slot, you can click on [Book Appointment](/appointments) or reply with your **Name, Email, Phone, Date, Time Slot, and Purpose**!";
                }

                $this->logToolExecution($conversation, 'check_appointment_availability', ['date' => $date->toDateString()], ['slots' => $slots], $reply);

                return [
                    'success' => true,
                    'message' => $reply,
                    'tool_called' => 'check_appointment_availability',
                    'tool_result' => ['date' => $date->toDateString(), 'slots' => $slots],
                ];
            }
        }

        // Action Tool: Find teachers / faculty
        if (preg_match('/(?:teacher|instructor|faculty|trainer|who teaches|prof)/i', $qLower)) {
            $subject = null;
            if (str_contains($qLower, 'ielts') || str_contains($qLower, 'iets')) $subject = 'IETS';
            elseif (str_contains($qLower, 'english') || str_contains($qLower, 'speaking')) $subject = 'English';
            elseif (str_contains($qLower, 'math')) $subject = 'Mathematics';

            $queryBuilder = Teacher::active();
            if ($subject) {
                $queryBuilder->where('subject', 'like', "%{$subject}%");
            }
            $teachers = $queryBuilder->limit(4)->get();

            if ($teachers->isNotEmpty()) {
                $teacherLines = [];
                foreach ($teachers as $t) {
                    $teacherLines[] = "🎓 **{$t->name}** — {$t->designation} ({$t->qualification})\nSpeciality: {$t->subject} • Experience: {$t->experience}";
                }
                $reply = "Here are our esteemed faculty members:\n\n" . implode("\n\n", $teacherLines) .
                         "\n\nYou can view full faculty profiles on our [Teachers Page](/teachers).";

                $this->logToolExecution($conversation, 'search_teachers', ['subject' => $subject], $teachers->toArray(), $reply);

                return [
                    'success' => true,
                    'message' => $reply,
                    'tool_called' => 'search_teachers',
                    'tool_result' => $teachers,
                ];
            }
        }

        // Action Tool: IETS details
        if (str_contains($qLower, 'iets') || str_contains($qLower, 'ielts') || str_contains($qLower, 'band')) {
            $programs = IetsProgram::active()->limit(4)->get();
            if ($programs->isNotEmpty()) {
                $lines = [];
                foreach ($programs as $p) {
                    $lines[] = "📘 **{$p->title}**\n{$p->summary}";
                }
                $reply = "Apex Academy offers comprehensive IETS / IELTS preparation modules:\n\n" .
                         implode("\n\n", $lines) .
                         "\n\nWe feature AI-powered band score evaluation and mock exams. Learn more on our [IETS Program Page](/iets) or check student band score achievements on [IETS Results](/iets/results)!";

                $this->logToolExecution($conversation, 'get_iets_programs', [], $programs->toArray(), $reply);

                return [
                    'success' => true,
                    'message' => $reply,
                    'tool_called' => 'get_iets_programs',
                    'tool_result' => $programs,
                ];
            }
        }

        return null;
    }

    /**
     * Generate answer using external API (OpenAI/Gemini) if configured, or internal dynamic knowledge base
     */
    protected function generateKnowledgeAnswer(string $query, AiConversation $conversation): string
    {
        $apiKey = Setting::get('ai_api_key');
        $provider = Setting::get('ai_provider', 'openai');
        $model = Setting::get('ai_model', 'gpt-4o-mini');
        $systemPrompt = Setting::get('ai_system_prompt');

        if (!empty($apiKey)) {
            try {
                $context = $this->buildAcademyContext();
                $fullSystemPrompt = ($systemPrompt ?: "You are Apex Academy's expert AI Academic Counselor. Answer accurately, politely, and guide students.") . "\n\nAcademy Knowledge Base:\n" . $context;

                if ($provider === 'openai') {
                    $response = Http::withToken($apiKey)->timeout(12)->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'system', 'content' => $fullSystemPrompt],
                            ['role' => 'user', 'content' => $query],
                        ],
                        'max_tokens' => 350,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        return $data['choices'][0]['message']['content'] ?? 'How can I assist you further?';
                    }
                }
            } catch (\Exception $e) {
                Log::warning('AI API call failed: ' . $e->getMessage());
            }
        }

        // Dynamic Native Knowledge Fallback
        return $this->fallbackKnowledgeMatcher($query);
    }

    /**
     * Match user query against database FAQs and Academy settings
     */
    protected function fallbackKnowledgeMatcher(string $query): string
    {
        $q = strtolower($query);

        // Check FAQs in database
        $faqs = Faq::active()->get();
        foreach ($faqs as $faq) {
            $words = explode(' ', strtolower($faq->question));
            $matchCount = 0;
            foreach ($words as $w) {
                if (strlen($w) > 3 && str_contains($q, $w)) {
                    $matchCount++;
                }
            }
            if ($matchCount >= 2) {
                return $faq->answer . "\n\nFor more details, you can visit our [FAQ Page](/faq) or [Book an Appointment](/appointments).";
            }
        }

        // Location / Address / Contact
        if (str_contains($q, 'location') || str_contains($q, 'address') || str_contains($q, 'where') || str_contains($q, 'map')) {
            $addr = Setting::get('contact_address', '124 Academic Boulevard, Knowledge Park');
            $phone = Setting::get('contact_phone', '+1 (555) 234-5678');
            return "📍 **Campus Location**: {$addr}\n📞 **Phone**: {$phone}\n\nOur campus is equipped with state-of-the-art multimedia classrooms, speech laboratories, and a dedicated testing center. View photos on our [Campus Gallery](/gallery)!";
        }

        // Timing / Hours
        if (str_contains($q, 'timing') || str_contains($q, 'hour') || str_contains($q, 'open') || str_contains($q, 'schedule')) {
            return "🕒 **Campus Hours**:\nMonday – Friday: 8:00 AM – 7:00 PM\nSaturday: 9:00 AM – 4:00 PM\nSunday: Closed\n\nAdmissions counseling and mock tests run daily by appointment.";
        }

        // Admissions / Fees
        if (str_contains($q, 'admission') || str_contains($q, 'fee') || str_contains($q, 'cost') || str_contains($q, 'enroll') || str_contains($q, 'register')) {
            return "🎓 **Admissions & Enrollment**:\nEnrollment is currently open for our upcoming IETS and English language batches! Courses include complete study materials, 1-on-1 speaking evaluations, and weekly mock exams.\n\nTo get personalized fee information and a level placement test, please [Book an Appointment](/appointments) or submit a message via [Contact](/contact).";
        }

        $academyName = Setting::get('academy_name', 'Apex Academy & IETS');
        return "Hello! I am your **{$academyName} AI Assistant**. I can help you with:\n\n" .
               "• **IETS Programs & Scoring**: Listening, Reading, Writing, Speaking modules\n" .
               "• **Checking Appointment Slots**: e.g., *'Check available slots for tomorrow'*\n" .
               "• **Faculty Information**: e.g., *'Who teaches IETS?'*\n" .
               "• **Classrooms & Campus**: Lab facilities and modern infrastructure\n" .
               "• **Admissions & Timings**\n\n" .
               "How may I help you today?";
    }

    /**
     * Build context summary from active DB records
     */
    protected function buildAcademyContext(): string
    {
        $name = Setting::get('academy_name', 'Apex Academy & IETS');
        $phone = Setting::get('contact_phone', '+1 (555) 234-5678');
        $email = Setting::get('contact_email', 'info@antiacademy.edu');
        $address = Setting::get('contact_address', '124 Academic Boulevard, Knowledge Park');

        $teachers = Teacher::active()->pluck('name')->implode(', ');
        $programs = IetsProgram::active()->pluck('title')->implode(', ');

        return "Academy Name: {$name}\nContact: {$phone}, {$email}\nAddress: {$address}\nPrograms: {$programs}\nKey Teachers: {$teachers}";
    }

    /**
     * Extract date from text
     */
    protected function extractDateFromQuery(string $text): ?Carbon
    {
        if (str_contains($text, 'today')) {
            return Carbon::today();
        }
        if (str_contains($text, 'tomorrow')) {
            return Carbon::tomorrow();
        }
        if (preg_match('/\b(\d{4}-\d{2}-\d{2})\b/', $text, $matches)) {
            try {
                return Carbon::parse($matches[1]);
            } catch (\Exception $e) {}
        }
        return Carbon::tomorrow();
    }

    protected function logToolExecution(AiConversation $conversation, string $tool, array $params, $result, string $content): void
    {
        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'tool',
            'content' => $content,
            'tool_called' => $tool,
            'tool_parameters' => $params,
            'tool_result' => is_array($result) ? $result : ['result' => $result],
        ]);
        $conversation->increment('total_messages');
    }
}
