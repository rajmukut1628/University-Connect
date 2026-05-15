<?php

namespace App\Services;

class VerifiedUserAISmartNormalizer
{
    public function normalize(string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($text));
        $users = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', preg_split('/[,|;]/', $line));

            $data = [
                'name' => $parts[0] ?? null,
                'email' => strtolower($parts[1] ?? ''),
                'role' => strtolower($parts[2] ?? 'student'),
                'student_id' => null,
                'alumni_id' => null,
                'department' => $parts[4] ?? null,
                'batch' => $parts[5] ?? null,
                'status' => 'active',
            ];

            if ($data['role'] === 'student') {
                $data['student_id'] = $parts[3] ?? null;
            }

            if ($data['role'] === 'alumni') {
                $data['alumni_id'] = $parts[3] ?? null;
            }

            if (!empty($data['name']) && !empty($data['email'])) {
                $users[] = $data;
            }
        }

        return $users;
    }
}