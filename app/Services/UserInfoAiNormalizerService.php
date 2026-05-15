<?php

namespace App\Services;

use Illuminate\Support\Str;

class UserInfoAiNormalizerService
{
    public function normalize(array $data): array
    {
        $role = strtolower(trim($data['role'] ?? 'student'));

        if (!in_array($role, ['student', 'alumni'])) {
            $role = 'student';
        }

        $department = $this->normalizeDepartment($data['department'] ?? null);
        $skills = $this->normalizeSkills($data['skills'] ?? null);

        return [
            'name' => Str::title(trim($data['name'] ?? '')),
            'email' => strtolower(trim($data['email'] ?? '')),
            'phone' => $this->normalizePhone($data['phone'] ?? null),
            'role' => $role,
            'department' => $department,
            'batch' => trim($data['batch'] ?? ''),
            'skills' => $skills,
            'bio' => $this->normalizeBio($data['bio'] ?? null, $role, $department, $skills),
            'address' => trim($data['address'] ?? ''),
            'password' => $data['password'] ?? '12345678',
        ];
    }

    private function normalizeDepartment(?string $department): ?string
    {
        if (!$department) {
            return null;
        }

        $value = strtolower(trim($department));

        return match (true) {
            str_contains($value, 'cse') || str_contains($value, 'computer') => 'CSE',
            str_contains($value, 'eee') || str_contains($value, 'electrical') => 'EEE',
            str_contains($value, 'bba') || str_contains($value, 'business') => 'BBA',
            str_contains($value, 'english') => 'English',
            str_contains($value, 'law') => 'Law',
            default => Str::upper($department),
        };
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $phone = preg_replace('/[^0-9+]/', '', $phone);

        if (str_starts_with($phone, '8801')) {
            return '+' . $phone;
        }

        if (str_starts_with($phone, '01')) {
            return '+88' . $phone;
        }

        return $phone;
    }

    private function normalizeSkills(?string $skills): ?string
    {
        if (!$skills) {
            return null;
        }

        $items = preg_split('/[,|\/]+/', $skills);

        $items = collect($items)
            ->map(fn ($item) => Str::title(trim($item)))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return implode(', ', $items);
    }

    private function normalizeBio(?string $bio, string $role, ?string $department, ?string $skills): string
    {
        if ($bio && strlen(trim($bio)) >= 25) {
            return trim($bio);
        }

        if ($role === 'alumni') {
            return 'Alumni member from ' . ($department ?? 'the university') . ', interested in mentoring students, sharing career guidance, and supporting professional growth.';
        }

        return 'Student from ' . ($department ?? 'the university') . ', interested in learning, career development, mentorship, and building professional skills such as ' . ($skills ?: 'communication, technology, and teamwork') . '.';
    }
}