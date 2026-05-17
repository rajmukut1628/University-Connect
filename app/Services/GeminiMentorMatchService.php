<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeminiMentorMatchService
{
    public function analyze(User $student, User $mentor): array
    {
        $apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
        $model = config('services.gemini.model') ?? env('GEMINI_MODEL', 'gemini-2.5-flash');

        if (!$apiKey) {
            return $this->fallbackAnalysis($student, $mentor, 'Gemini API key is missing.');
        }

        $prompt = $this->buildPrompt($student, $mentor);

        try {
            $response = Http::timeout(25)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]
            );

            if (!$response->successful()) {
                return $this->fallbackAnalysis($student, $mentor, 'Gemini API request failed.');
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (!$text) {
                return $this->fallbackAnalysis($student, $mentor, 'Gemini response was empty.');
            }

            return $this->parseJson($text, $student, $mentor);
        } catch (\Throwable $e) {
            return $this->fallbackAnalysis($student, $mentor, $e->getMessage());
        }
    }

    private function buildPrompt(User $student, User $mentor): string
    {
        return <<<PROMPT
You are an AI mentorship matching assistant.

Compare this student profile with this alumni mentor profile and return ONLY valid JSON.

JSON format:
{
  "score": 0-100,
  "level": "Excellent Match | Good Match | Medium Match | Low Match",
  "summary": "short explanation",
  "reasons": ["reason 1", "reason 2", "reason 3"],
  "recommendations": ["recommendation 1", "recommendation 2"]
}

Student Profile:
Name: {$student->name}
Department: {$student->department}
Batch: {$student->batch}
Skills: {$student->skills}
Career Goal / Bio: {$student->bio}
Current Company: {$student->current_company}
Current Designation: {$student->current_designation}
Experience: {$student->work_experience_years}

Alumni Mentor Profile:
Name: {$mentor->name}
Department: {$mentor->department}
Batch: {$mentor->batch}
Skills: {$mentor->skills}
Professional Bio: {$mentor->bio}
Current Company: {$mentor->current_company}
Current Designation: {$mentor->current_designation}
Job Type: {$mentor->current_job_type}
Experience: {$mentor->work_experience_years}
Previous Company: {$mentor->previous_company}
Previous Designation: {$mentor->previous_designation}
Previous Job Details: {$mentor->previous_job_details}

Return only valid JSON. No markdown. No explanation outside JSON.
PROMPT;
    }

    private function parseJson(string $text, User $student, User $mentor): array
    {
        $clean = trim($text);
        $clean = Str::of($clean)
            ->replace('```json', '')
            ->replace('```', '')
            ->trim()
            ->toString();

        $data = json_decode($clean, true);

        if (!is_array($data)) {
            return $this->fallbackAnalysis($student, $mentor, 'Could not parse Gemini JSON.');
        }

        return [
            'score' => (int) ($data['score'] ?? 70),
            'level' => $data['level'] ?? 'Good Match',
            'summary' => $data['summary'] ?? 'AI found a possible mentorship match.',
            'reasons' => $data['reasons'] ?? [],
            'recommendations' => $data['recommendations'] ?? [],
            'source' => 'gemini',
        ];
    }

    private function fallbackAnalysis(User $student, User $mentor, ?string $error = null): array
    {
        $score = 55;

        if ($student->department && $mentor->department && strtolower($student->department) === strtolower($mentor->department)) {
            $score += 15;
        }

        if ($student->skills && $mentor->skills) {
            $studentSkills = collect(explode(',', strtolower($student->skills)))->map(fn ($s) => trim($s))->filter();
            $mentorSkills = collect(explode(',', strtolower($mentor->skills)))->map(fn ($s) => trim($s))->filter();

            $common = $studentSkills->intersect($mentorSkills)->count();
            $score += min($common * 5, 20);
        }

        if ($mentor->current_company || $mentor->current_designation) {
            $score += 10;
        }

        $score = min($score, 95);

        return [
            'score' => $score,
            'level' => $score >= 85 ? 'Excellent Match' : ($score >= 70 ? 'Good Match' : 'Medium Match'),
            'summary' => 'Fallback matching used because Gemini AI could not complete the request.',
            'reasons' => [
                'Department, skills, and mentor experience were compared.',
                'Mentor professional background was considered.',
                'Student career profile was checked against mentor expertise.',
            ],
            'recommendations' => [
                'Add more skills and career goals to improve matching.',
                'Choose mentors from the same department or industry.',
            ],
            'source' => 'fallback',
            'error' => $error,
        ];
    }
}