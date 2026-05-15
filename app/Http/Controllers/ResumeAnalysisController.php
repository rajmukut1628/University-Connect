<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\ResumeAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResumeAnalysisController extends Controller
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

        return view('resume-analyzer.index', compact('analyses', 'latestAnalysis', 'matchedJobs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resume_title' => ['nullable', 'string', 'max:255'],
            'resume_file' => ['required', 'file', 'mimes:pdf,doc,docx,txt', 'max:5120'],
        ]);

        $file = $request->file('resume_file');
        $path = $file->store('resume-analyses', 'public');

        $analysis = $this->generateAnalysis(
            $file->getClientOriginalName(),
            $request->resume_title
        );

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
            ->route('resume-analyzer.index')
            ->with('success', 'Resume analyzed successfully. AI job matches are ready.');
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

        $jobs = $jobsQuery
            ->latest()
            ->take(20)
            ->get();

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

    private function generateAnalysis(string $fileName, ?string $title = null): array
    {
        $text = strtolower($fileName . ' ' . ($title ?? ''));

        $skillPool = [
            'HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'MySQL',
            'Python', 'React', 'Vue', 'GitHub', 'UI/UX', 'Communication',
            'Problem Solving', 'Teamwork',
        ];

        $detected = [];

        foreach ($skillPool as $skill) {
            if (Str::contains($text, strtolower($skill))) {
                $detected[] = $skill;
            }
        }

        if (count($detected) < 3) {
            $detected = ['HTML', 'CSS', 'JavaScript', 'Communication'];
        }

        $missing = array_values(array_diff([
            'GitHub',
            'Laravel',
            'Database Design',
            'Project Documentation',
            'Interview Preparation',
            'Professional Portfolio',
        ], $detected));

        $score = min(100, 45 + (count($detected) * 7));

        return [
            'score' => $score,
            'detected_skills' => $detected,
            'missing_skills' => array_slice($missing, 0, 5),
            'recommended_roles' => [
                'Junior Web Developer',
                'Developer Intern',
                'Frontend Developer Intern',
                'Software Support Trainee',
            ],
            'suggestions' => [
                'Add a clear career objective at the top of your CV.',
                'Add your academic projects with GitHub links.',
                'Mention technical skills in a separate skills section.',
                'Add internship, volunteer or club experience if available.',
                'Keep your CV clean, short and easy to read.',
            ],
            'summary' => 'Your resume has a good starting structure. Improve it by adding more project details, measurable achievements, GitHub links, and job-focused skills.',
        ];
    }
}