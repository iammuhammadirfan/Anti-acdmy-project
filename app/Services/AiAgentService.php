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
     * Generate answer using external API (Gemini/Groq/OpenRouter/OpenAI) if configured, or internal dynamic knowledge base
     */
    protected function generateKnowledgeAnswer(string $query, AiConversation $conversation): string
    {
        $apiKey = Setting::get('ai_api_key');
        $provider = Setting::get('ai_provider', 'gemini');
        $model = Setting::get('ai_model', 'gemini-1.5-flash');
        $systemPrompt = Setting::get('ai_system_prompt');

        if (!empty($apiKey)) {
            try {
                $context = $this->buildAcademyContext();
                $fullSystemPrompt = ($systemPrompt ?: "You are Apex Academy's expert AI Academic Counselor and Admissions Advisor. Answer accurately, politely, and guide students in English, Urdu or Roman Urdu depending on the user's language.") . "\n\nAcademy Knowledge Base:\n" . $context;

                // 1. Google Gemini API (100% Free Tier on Google AI Studio)
                if ($provider === 'gemini') {
                    $geminiModel = $model ?: 'gemini-1.5-flash';
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$geminiModel}:generateContent?key={$apiKey}";

                    $response = Http::timeout(15)->post($url, [
                        'contents' => [
                            [
                                'role' => 'user',
                                'parts' => [
                                    ['text' => $fullSystemPrompt . "\n\nUser Question:\n" . $query]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'maxOutputTokens' => 600,
                            'temperature' => 0.7,
                        ]
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                        if (!empty($text)) {
                            return trim($text);
                        }
                    } else {
                        Log::warning('Gemini API Error: ' . $response->body());
                    }
                }

                // 2. Groq Cloud (100% Free High-Speed Inference - Llama 3)
                if ($provider === 'groq') {
                    $groqModel = $model ?: 'llama-3.3-70b-versatile';
                    $response = Http::withToken($apiKey)->timeout(12)->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => $groqModel,
                        'messages' => [
                            ['role' => 'system', 'content' => $fullSystemPrompt],
                            ['role' => 'user', 'content' => $query],
                        ],
                        'max_tokens' => 600,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $text = $data['choices'][0]['message']['content'] ?? null;
                        if (!empty($text)) {
                            return trim($text);
                        }
                    } else {
                        Log::warning('Groq API Error: ' . $response->body());
                    }
                }

                // 3. OpenRouter (Free community models)
                if ($provider === 'openrouter') {
                    $orModel = $model ?: 'meta-llama/llama-3.2-3b-instruct:free';
                    $response = Http::withToken($apiKey)->timeout(15)->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model' => $orModel,
                        'messages' => [
                            ['role' => 'system', 'content' => $fullSystemPrompt],
                            ['role' => 'user', 'content' => $query],
                        ],
                        'max_tokens' => 600,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $text = $data['choices'][0]['message']['content'] ?? null;
                        if (!empty($text)) {
                            return trim($text);
                        }
                    } else {
                        Log::warning('OpenRouter API Error: ' . $response->body());
                    }
                }

                // 4. OpenAI (ChatGPT / GPT-4o)
                if ($provider === 'openai') {
                    $response = Http::withToken($apiKey)->timeout(12)->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $model ?: 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'system', 'content' => $fullSystemPrompt],
                            ['role' => 'user', 'content' => $query],
                        ],
                        'max_tokens' => 500,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        return $data['choices'][0]['message']['content'] ?? 'How can I assist you further?';
                    } else {
                        Log::warning('OpenAI API Error: ' . $response->body());
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
     * Match user query against database FAQs, knowledge base, and academic guidance
     */
    protected function fallbackKnowledgeMatcher(string $query): string
    {
        $q = strtolower(trim($query));

        // Greetings & pleasantries
        if (preg_match('/^(hi|hello|hey|salam|assalam|aoa|hy|hola|good morning|good afternoon|good evening|kese ho|kaise ho)\b/i', $q)) {
            $name = Setting::get('academy_name', 'Apex Academy & IETS');
            return "Walaikum Assalam / Hello! Welcome to **{$name}**! 👋\n\nI am your 24/7 AI Academic Advisor. How can I assist you today? You can ask me about:\n• **IETS & IELTS Prep Modules & Test Dates**\n• **Class Schedules & Faculty Details**\n• **Booking a Free Counseling Session**\n• **Admissions & Fee Details**";
        }

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

        // IETS Preparation & Modules (Reading, Writing, Listening, Speaking)
        if (str_contains($q, 'speaking') || str_contains($q, 'interview') || str_contains($q, 'cue card')) {
            return "🗣️ **IETS Speaking Module Preparation**:\nOur speaking preparation includes:\n• Daily 1-on-1 mock interviews with certified British Council & IDP trained trainers\n• Audio recorded feedback & vocabulary booster sessions\n• Part 1, 2 (Cue Card) & Part 3 discussion strategies\n\nCheck our certified faculty on the [Teachers Page](/teachers) or schedule a practice evaluation under [Appointments](/appointments)!";
        }

        if (str_contains($q, 'writing') || str_contains($q, 'essay') || str_contains($q, 'task 1') || str_contains($q, 'task 2')) {
            return "✍️ **IETS Writing Mastery (Task 1 & Task 2)**:\n• Comprehensive sentence structures, band 8+ vocabulary, and grammar polishing\n• Detailed daily essay checking and rubric-based band score analysis\n• Formal academic graphs, charts, letters, and opinion essay frameworks\n\nVisit our [IETS Programs](/iets) to choose your preparation track!";
        }

        if (str_contains($q, 'reading') || str_contains($q, 'listening') || str_contains($q, 'skimming') || str_contains($q, 'scanning')) {
            return "📖 **IETS Reading & Listening Strategies**:\n• Proven keyword matching, scanning, and skimming techniques\n• Multimedia audio listening labs with noise-canceling headphones\n• Weekly real exam simulation tests with authentic Cambridge material\n\nExplore our high-tech facilities on the [Classrooms & Labs Page](/classrooms)!";
        }

        // Band score queries
        if (str_contains($q, 'band') || str_contains($q, 'score') || str_contains($q, 'result') || str_contains($q, 'pass')) {
            return "🎯 **IETS Band Score Target**:\nOur students consistently achieve **Band 7.0 to 8.5+** in Academic and General Training tests.\n\nTake a look at verified student scorecards and testimonials on our [IETS Results Showcase](/iets/results)!";
        }

        // Location / Address / Contact
        if (str_contains($q, 'location') || str_contains($q, 'address') || str_contains($q, 'where') || str_contains($q, 'map') || str_contains($q, 'kahan')) {
            $addr = Setting::get('contact_address', '124 Academic Boulevard, Knowledge Park');
            $phone = Setting::get('contact_phone', '+1 (555) 234-5678');
            return "📍 **Campus Location**: {$addr}\n📞 **Phone**: {$phone}\n\nOur campus is equipped with multimedia classrooms, computer labs, and a dedicated testing center. View photos on our [Campus Gallery](/gallery)!";
        }

        // Timing / Hours
        if (str_contains($q, 'timing') || str_contains($q, 'hour') || str_contains($q, 'open') || str_contains($q, 'schedule') || str_contains($q, 'time')) {
            return "🕒 **Campus Hours**:\n• Monday – Friday: 8:00 AM – 7:00 PM\n• Saturday: 9:00 AM – 4:00 PM\n• Sunday: Closed\n\nMorning, evening, and weekend batches are available for working professionals and students!";
        }

        // Admissions / Fees / Cost
        if (str_contains($q, 'admission') || str_contains($q, 'fee') || str_contains($q, 'cost') || str_contains($q, 'enroll') || str_contains($q, 'register') || str_contains($q, 'price')) {
            return "🎓 **Admissions & Fee Packages**:\nNew batches start every Monday! We offer flexible plans including Standard (4 weeks), Comprehensive (8 weeks), and Crash Courses (2 weeks) with complete study materials and mock exams included.\n\nTo get customized fee details and a free diagnostic test, please [Book an Appointment](/appointments) or send a query on [Contact](/contact).";
        }

        // Faculty & Teachers
        if (str_contains($q, 'teacher') || str_contains($q, 'faculty') || str_contains($q, 'trainer') || str_contains($q, 'sir') || str_contains($q, 'madam')) {
            return "👨‍🏫 **Expert Faculty**:\nOur faculty consists of certified language specialists and master degree holders with 8+ years of IELTS/IETS coaching experience.\n\nMeet our faculty team and view their qualifications on the [Teachers Directory](/teachers)!";
        }

        $academyName = Setting::get('academy_name', 'Apex Academy & IETS');
        return "Thank you for reaching out to **{$academyName}**!\n\nI can help you with anything related to our academy:\n• **IETS & English Courses**: [View Programs](/iets)\n• **Book Test / Counseling**: [Book Appointment](/appointments)\n• **Verified Student Scores**: [View Results](/iets/results)\n• **Campus Facilities**: [View Classrooms & Labs](/classrooms)\n• **Contact Us**: [Contact Page](/contact)\n\nFeel free to ask any specific question about courses, teachers, or schedules!";
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
