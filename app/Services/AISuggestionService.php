<?php

namespace App\Services;

use App\Models\AISuggestion;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AISuggestionService
{
    public function generateFor(User $user): array
    {
        $suggestions = [];

        $role = strtolower($user->role ?? 'student');

        if (in_array($role, ['student', 'alumni'])) {
            $suggestions = array_merge($suggestions, $this->profileSuggestions($user));
            $suggestions = array_merge($suggestions, $this->skillSuggestions($user));
            $suggestions = array_merge($suggestions, $this->resumeSuggestions($user));
            $suggestions = array_merge($suggestions, $this->jobSuggestions($user));
            $suggestions = array_merge($suggestions, $this->eventSuggestions($user));
            $suggestions = array_merge($suggestions, $this->mentorshipSuggestions($user));
        }

        if ($role === 'alumni') {
            $suggestions = array_merge($suggestions, $this->alumniSuggestions($user));
        }

        $suggestions = collect($suggestions)
            ->unique(fn ($item) => $item['title'])
            ->sortByDesc('score')
            ->take(8)
            ->values()
            ->toArray();

        $this->syncSuggestions($user, $suggestions);

        return $suggestions;
    }

    public function latestFor(User $user, int $limit = 6)
    {
        if (!Schema::hasTable('ai_suggestions')) {
            return collect($this->generateFor($user))->take($limit);
        }

        $existing = AISuggestion::forUser($user->id)
            ->active()
            ->latest()
            ->take($limit)
            ->get();

        if ($existing->count() < 1) {
            $this->generateFor($user);

            return collect($this->generateFor($user))
    ->map(fn ($item) => (object) $item)
    ->take($limit);
        }

        return $existing;
    }

    private function profileSuggestions(User $user): array
    {
        $items = [];

        if (empty($user->phone)) {
            $items[] = $this->make(
                'Complete your phone number',
                'Adding your phone number helps mentors, recruiters, and university admins verify your profile more confidently.',
                'profile',
                'fa-user-check',
                90,
                1
            );
        }

        if (empty($user->bio)) {
            $items[] = $this->make(
                'Write a short professional bio',
                'Add a short bio about your department, career goal, skills, and interests to improve your profile strength.',
                'profile',
                'fa-pen-nib',
                86,
                1
            );
        }

        if (empty($user->linkedin) && empty($user->linkedin_url)) {
            $items[] = $this->make(
                'Add your LinkedIn profile',
                'LinkedIn improves your professional credibility and helps alumni or recruiters understand your background.',
                'profile',
                'fa-linkedin',
                84,
                2
            );
        }

        if (empty($user->github) && empty($user->github_url)) {
            $items[] = $this->make(
                'Connect your GitHub profile',
                'GitHub is important for showing real projects, especially for CSE, software, AI, and web development careers.',
                'profile',
                'fa-github',
                82,
                2
            );
        }

        return $items;
    }

    private function skillSuggestions(User $user): array
    {
        $items = [];

        $skills = strtolower((string)($user->skills ?? ''));

        if (!$skills) {
            $items[] = $this->make(
                'Add your key skills',
                'Add skills like Laravel, PHP, JavaScript, React, Python, MySQL, AI Tools, Communication, or Leadership.',
                'skills',
                'fa-brain',
                92,
                1
            );

            return $items;
        }

        if (Str::contains($skills, ['laravel', 'php'])) {
            $items[] = $this->make(
                'You match Laravel backend roles',
                'Your Laravel/PHP skillset is suitable for backend developer, full stack intern, and university project-based roles.',
                'career',
                'fa-code',
                88,
                1
            );
        }

        if (Str::contains($skills, ['react', 'vue', 'javascript'])) {
            $items[] = $this->make(
                'Improve frontend portfolio projects',
                'Your frontend skills can become stronger if you publish 2-3 polished UI projects with responsive design.',
                'skills',
                'fa-laptop-code',
                84,
                2
            );
        }

        if (!Str::contains($skills, ['git', 'github'])) {
            $items[] = $this->make(
                'Learn Git and GitHub workflow',
                'GitHub is essential for project collaboration, internship applications, and proving your coding activity.',
                'skills',
                'fa-code-branch',
                79,
                2
            );
        }

        if (!Str::contains($skills, ['communication', 'presentation'])) {
            $items[] = $this->make(
                'Build communication skills',
                'Good communication and presentation skills can increase your chance in interviews and mentorship programs.',
                'soft_skill',
                'fa-comments',
                74,
                3
            );
        }

        return $items;
    }

    private function resumeSuggestions(User $user): array
    {
        $items = [];

        if (empty($user->resume) && empty($user->cv)) {
            $items[] = $this->make(
                'Upload your resume for AI analysis',
                'Upload your CV so the system can analyze ATS score, missing skills, weak sections, and career readiness.',
                'resume',
                'fa-file-lines',
                91,
                1
            );
        } else {
            $items[] = $this->make(
                'Optimize your resume keywords',
                'Improve your CV by adding measurable achievements, project links, technical skills, and internship keywords.',
                'resume',
                'fa-file-circle-check',
                83,
                2
            );
        }

        return $items;
    }

    private function jobSuggestions(User $user): array
    {
        return [
            $this->make(
                'Check matching job opportunities',
                'Based on your profile, review available jobs and internships that match your department and skills.',
                'jobs',
                'fa-briefcase',
                80,
                2
            ),
        ];
    }

    private function eventSuggestions(User $user): array
    {
        return [
            $this->make(
                'Join career-related university events',
                'Participating in university events can improve your networking, confidence, and career exposure.',
                'events',
                'fa-calendar-check',
                76,
                3
            ),
        ];
    }

    private function mentorshipSuggestions(User $user): array
    {
        return [
            $this->make(
                'Request mentorship from alumni',
                'Connect with alumni mentors to get guidance about CV, internship, interview, and career roadmap.',
                'mentorship',
                'fa-user-graduate',
                85,
                1
            ),
        ];
    }

    private function alumniSuggestions(User $user): array
    {
        return [
            $this->make(
                'Become visible as a mentor',
                'Complete your experience, company, designation, and expertise so students can find you as a trusted mentor.',
                'alumni',
                'fa-handshake-angle',
                89,
                1
            ),
            $this->make(
                'Share job or internship opportunities',
                'Posting opportunities from your company or network can help students and improve alumni engagement.',
                'alumni',
                'fa-bullhorn',
                81,
                2
            ),
            $this->make(
                'Host a career guidance session',
                'You can support students by hosting a webinar about industry preparation, interview tips, or career roadmap.',
                'alumni',
                'fa-chalkboard-user',
                77,
                3
            ),
        ];
    }

    private function syncSuggestions(User $user, array $suggestions): void
    {
        if (!Schema::hasTable('ai_suggestions')) {
            return;
        }

        AISuggestion::where('user_id', $user->id)->update([
            'is_active' => false,
        ]);

        foreach ($suggestions as $suggestion) {
            AISuggestion::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $suggestion['title'],
                ],
                array_merge($suggestion, [
                    'user_id' => $user->id,
                    'is_active' => true,
                    'generated_by' => 'rule_based_ai',
                ])
            );
        }
    }

    private function make(
        string $title,
        string $description,
        string $category,
        string $icon,
        int $score,
        int $priority
    ): array {
        return [
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'icon' => $icon,
            'score' => $score,
            'priority' => $priority,
            'generated_by' => 'rule_based_ai',
            'is_active' => true,
        ];
    }
}