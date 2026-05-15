<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    public function ask(Request $request)
{
    $request->validate([
        'question' => ['required', 'string', 'max:2000'],
    ]);

    $question = trim($request->question);

    try {
        $answer = $this->askGemini($question);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'question' => $question,
                'answer' => $answer,
                'time' => now()->format('d M Y, h:i A'),
            ]);
        }

        return back()
            ->withInput()
            ->with('ai_question', $question)
            ->with('ai_answer', $answer);

    } catch (\Throwable $e) {
        $message = 'AI Assistant is temporarily unavailable. Please try again.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 500);
        }

        return back()
            ->withInput()
            ->with('ai_question', $question)
            ->with('ai_answer', $message);
    }
}

    private function askGemini(string $question): string
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');
        $baseUrl = rtrim(config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');

        if (!$apiKey) {
            throw new \Exception('Gemini API key is missing.');
        }

        $systemPrompt = "
You are University Connect AI Assistant.

You help university students, alumni, mentors and admins.
Answer like a real AI assistant, not fixed keyword replies.

Focus on:
- Study planning
- Final year project defense
- CV and resume improvement
- Internship preparation
- Career planning
- Programming and project guidance
- Communication skills
- Mentorship and alumni networking
- University life and personal growth

Rules:
- Give practical, clear and helpful answers.
- Use step-by-step guidance when useful.
- Keep the answer professional and easy to understand.
- If the question is general, connect it with university life when relevant.
- Do not claim access to private university data unless the user gives it.
";

        $prompt = $systemPrompt . "\n\nStudent Question:\n" . $question;

        $response = Http::timeout(60)
            ->acceptJson()
            ->post($baseUrl . '/models/' . $model . ':generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                'temperature' => 0.7,
                 'topP' => 0.9,
                 'maxOutputTokens' => 1500,
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception($response->body());
        }

        $data = $response->json();

        return $this->extractGeminiAnswer($data);
    }

    private function extractGeminiAnswer(array $data): string
    {
        if (!empty($data['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($data['candidates'][0]['content']['parts'][0]['text']);
        }

        return 'AI generated a response, but the text could not be displayed. Please try again.';
    }
}