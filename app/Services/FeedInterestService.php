<?php

namespace App\Services;

class FeedInterestService
{
    public const OPTIONS = [
        'technology' => 'Technology & Programming', 'career' => 'Jobs & Career',
        'research' => 'Research & Higher Studies', 'mentorship' => 'Mentorship & Networking',
        'business' => 'Business & Entrepreneurship', 'culture' => 'Culture & Campus Events',
        'community' => 'Community Support & Donations',
    ];

    public function score(array $item, array $interests): int
    {
        $keywords = [
            'technology' => ['technology', 'programming', 'coding', 'software', 'developer', 'laravel', 'python', 'computer', 'data science', 'artificial intelligence'],
            'career' => ['career', 'job', 'internship', 'interview', 'cv', 'resume', 'employment'],
            'research' => ['research', 'higher studies', 'scholarship', 'publication', 'thesis', 'masters', 'phd'],
            'mentorship' => ['mentor', 'mentorship', 'networking', 'alumni', 'guidance'],
            'business' => ['business', 'entrepreneur', 'startup', 'marketing', 'finance', 'accounting'],
            'culture' => ['culture', 'cultural', 'festival', 'music', 'sports', 'campus', 'club'],
            'community' => ['donation', 'charity', 'support', 'volunteer', 'fund', 'relief'],
        ];
        $text = mb_strtolower(strip_tags(($item['title'] ?? '') . ' ' . ($item['description'] ?? '') . ' ' . ($item['interest_text'] ?? '')));
        $score = 0;
        foreach ($interests as $interest) {
            $matches = 0;
            foreach ($keywords[$interest] ?? [] as $keyword) {
                if (str_contains($text, $keyword)) $matches++;
            }
            if ($interest === 'career' && ($item['type'] ?? '') === 'job') $matches++;
            if ($interest === 'community' && ($item['type'] ?? '') === 'donation') $matches++;
            $score += min(3, $matches);
        }

        return $score;
    }
}
