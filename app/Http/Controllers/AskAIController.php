<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\ResumeAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

class AskAIController extends Controller
{
    public function index()
    {
        $analyses = ResumeAnalysis::where('user_id', auth()->id())
            ->latest()
            ->get();

        $latestAnalysis = $analyses->first();

        $matchedJobs = $latestAnalysis
            ? $this->getMatchedJobs($latestAnalysis->detected_skills ?? [])
            : collect();

        return view('ask-ai.index', compact('analyses', 'latestAnalysis', 'matchedJobs'));
    }

    public function ask(Request $request)
{
    // Accept both question and prompt
    $question = trim(
        $request->input('question')
        ?? $request->input('prompt')
        ?? ''
    );

    if ($question === '') {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error' => 'Please enter a question.',
            ], 422);
        }

        return back()
            ->withInput()
            ->withErrors([
                'question' => 'Please enter a question.',
            ]);
    }

    try {
        $answer = $this->askGemini($question, 'chat');

        if (empty(trim($answer))) {
            $answer = 'AI did not return any response. Please try again.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'question' => $question,
                'answer'   => $answer,
                'time'     => now()->format('d M Y, h:i A'),
            ]);
        }

        return back()
            ->withInput()
            ->with('ai_question', $question)
            ->with('ai_answer', $answer);

    } catch (\Throwable $e) {
        Log::error('Ask AI Chat Error', [
            'message' => $e->getMessage(),
        ]);

        $message = 'AI Assistant is temporarily unavailable. Please try again.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error'   => $message,
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }

        return back()
            ->withInput()
            ->with('ai_answer', $message);
    }
}

    public function analyzeResume(Request $request)
    {
        $request->validate([
            'resume_title' => ['nullable', 'string', 'max:255'],
            'resume_file' => ['required', 'file', 'mimes:pdf,doc,docx,txt', 'max:5120'],
        ]);

        $file = $request->file('resume_file');
        $path = $file->store('resume-analyses', 'public');

        try {
            $resumeText = $this->extractResumeText($file->getRealPath(), $file->getClientOriginalExtension());

            if (strlen(trim($resumeText)) < 80) {
                throw new \Exception('Could not read enough text from this resume. Please upload a clearer PDF/DOCX/TXT file.');
            }

            $analysis = $this->generateRealResumeAnalysis($resumeText);

            ResumeAnalysis::create([
                'user_id' => auth()->id(),
                'resume_title' => $request->resume_title ?: 'My Resume',
                'original_file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'score' => $analysis['score'],
                'detected_skills' => $analysis['detected_skills'],
                'missing_skills' => $analysis['missing_skills'],
                'suggestions' => $analysis['suggestions'],
                'recommended_roles' => $analysis['recommended_roles'],
                'summary' => $analysis['summary'],
            ]);

            return redirect()
                ->route('ask-ai.index')
                ->with('success', 'Resume analyzed successfully with real AI.');

        } catch (\Throwable $e) {
            Log::error('Ask AI Resume Analyze Error', [
                'message' => $e->getMessage(),
            ]);

            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()
                ->withInput()
                ->withErrors([
                    'resume_file' => $e->getMessage(),
                ]);
        }
    }

    public function destroy(ResumeAnalysis $resumeAnalysis)
    {
        if ($resumeAnalysis->user_id !== auth()->id()) {
            abort(403);
        }

        if ($resumeAnalysis->file_path && Storage::disk('public')->exists($resumeAnalysis->file_path)) {
            Storage::disk('public')->delete($resumeAnalysis->file_path);
        }

        $resumeAnalysis->delete();

        return back()->with('success', 'Resume analysis deleted successfully.');
    }

    private function askGemini(string $prompt, string $mode = 'chat'): string
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');
        $baseUrl = rtrim(config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');

        if (!$apiKey) {
            throw new \Exception('Gemini API key is missing.');
        }

        $systemPrompt = $mode === 'resume'
            ? $this->resumeSystemPrompt()
            : $this->chatSystemPrompt();

        $response = Http::timeout(90)
            ->acceptJson()
            ->post($baseUrl . '/models/' . $model . ':generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $systemPrompt . "\n\nUser Input:\n" . $prompt,
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.55,
                    'topP' => 0.9,
                    'maxOutputTokens' => 1800,
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception($response->body());
        }

        $data = $response->json();

        return trim($data['candidates'][0]['content']['parts'][0]['text'] ?? '');
    }

    private function chatSystemPrompt(): string
    {
        return "
You are Ask AI for University Connect.

You help students and alumni with:
- study planning
- final year project defense
- CV and resume improvement
- internship preparation
- career planning
- programming and project guidance
- communication skills
- mentorship and alumni networking
- university life and personal growth

Rules:
- Answer naturally like a real AI assistant.
- Give practical and step-by-step guidance when useful.
- Keep answers clear, professional and helpful.
- Do not claim access to private university data.
";
    }

    private function resumeSystemPrompt(): string
    {
        return "
You are an expert AI resume analyzer.

Analyze the resume text and return ONLY valid JSON.

JSON format:
{
  \"score\": 75,
  \"detected_skills\": [\"PHP\", \"Laravel\", \"MySQL\"],
  \"missing_skills\": [\"GitHub\", \"API\", \"Deployment\"],
  \"recommended_roles\": [\"Junior Web Developer\", \"Laravel Intern\"],
  \"suggestions\": [
    \"Add measurable achievements.\",
    \"Add GitHub links.\"
  ],
  \"summary\": \"Short professional summary of the resume quality.\"
}

Rules:
- score must be 0 to 100.
- detected_skills must come from resume text.
- missing_skills should be useful for student/alumni career improvement.
- suggestions must be practical.
- Return JSON only. No markdown.
";
    }

    private function generateRealResumeAnalysis(string $resumeText): array
    {
        $json = $this->askGemini($resumeText, 'resume');

        $json = trim($json);
        $json = preg_replace('/^```json\s*/', '', $json);
        $json = preg_replace('/^```\s*/', '', $json);
        $json = preg_replace('/\s*```$/', '', $json);

        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new \Exception('AI resume analysis response was invalid. Please try again.');
        }

        return [
            'score' => max(0, min(100, (int) ($data['score'] ?? 50))),
            'detected_skills' => array_values($data['detected_skills'] ?? []),
            'missing_skills' => array_values($data['missing_skills'] ?? []),
            'recommended_roles' => array_values($data['recommended_roles'] ?? []),
            'suggestions' => array_values($data['suggestions'] ?? []),
            'summary' => $data['summary'] ?? 'Resume analysis completed.',
        ];
    }

    private function extractResumeText(string $filePath, string $extension): string
    {
        $extension = strtolower($extension);

        if ($extension === 'txt') {
            return file_get_contents($filePath) ?: '';
        }

        if ($extension === 'pdf') {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            return $pdf->getText();
        }

        if (in_array($extension, ['doc', 'docx'])) {
            $phpWord = IOFactory::load($filePath);
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }

            return $text;
        }

        return '';
    }

    private function getMatchedJobs(array $skills)
    {
        if (!Schema::hasTable('jobs')) {
            return collect();
        }

        $skills = collect($skills)
            ->filter()
            ->map(fn ($skill) => strtolower(trim($skill)))
            ->values()
            ->toArray();

        if (empty($skills)) {
            return collect();
        }

        $jobsQuery = Job::query();

        if (Schema::hasColumn('jobs', 'status')) {
            $jobsQuery->where('status', 'approved');
        }

        $jobs = $jobsQuery->latest()->take(30)->get();

        return $jobs->map(function ($job) use ($skills) {
            $jobText = strtolower(
                ($job->title ?? '') . ' ' .
                ($job->category ?? '') . ' ' .
                ($job->type ?? '') . ' ' .
                ($job->description ?? '') . ' ' .
                ($job->requirements ?? '')
            );

            $matchedSkills = [];

            foreach ($skills as $skill) {
                if ($skill !== '' && Str::contains($jobText, $skill)) {
                    $matchedSkills[] = ucfirst($skill);
                }
            }

            $matchScore = count($skills) > 0
                ? round((count($matchedSkills) / count($skills)) * 100)
                : 0;

            $job->match_score = min($matchScore, 100);
            $job->matched_skills = array_unique($matchedSkills);

            return $job;
        })
        ->filter(fn ($job) => $job->match_score > 0)
        ->sortByDesc('match_score')
        ->take(6)
        ->values();
    }
}