<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PostAccess
{
    public static function ownerId(string $type, Model $post): int
    {
        return (int) $post->{match ($type) {
            'job' => 'posted_by', 'event' => 'created_by', 'donation' => 'user_id',
        }};
    }

    public static function canManage(?User $user, string $type, Model $post): bool
    {
        return $user && $user->is_active && !$user->is_blocked
            && (self::ownerId($type, $post) === (int) $user->id
            || in_array($user->role, ['admin', 'super_admin'], true));
    }
}
