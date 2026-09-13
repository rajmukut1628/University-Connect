<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Send notification to one user.
     */
    public static function send(
        User|int $user,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $priority = 'medium',
        ?Model $notifiable = null,
        User|int|null $actor = null
    ): Notification {

        $userId = $user instanceof User
            ? $user->id
            : $user;

        $actorId = null;

        if ($actor instanceof User) {
            $actorId = $actor->id;
        } elseif (is_int($actor)) {
            $actorId = $actor;
        } elseif (auth()->check()) {
            $actorId = auth()->id();
        }

        $priority = in_array(
            $priority,
            ['low', 'medium', 'high'],
            true
        )
            ? $priority
            : 'medium';

        $data = [
            'user_id' => $userId,
            'actor_id' => $actorId,

            'type' => $type,

            'title' => $title,

            'message' => $message,

            'action_url' => $actionUrl,

            'priority' => $priority,

            'is_read' => false,

            'read_at' => null,
        ];

        if ($notifiable) {
            $data['notifiable_type'] =
                get_class($notifiable);

            $data['notifiable_id'] =
                $notifiable->getKey();
        }

        return Notification::create($data);
    }


    /**
     * Send to multiple users.
     */
    public static function sendMany(
        Collection|array $users,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $priority = 'medium',
        ?Model $notifiable = null,
        User|int|null $actor = null
    ): void {

        foreach ($users as $user) {

            self::send(
                $user,
                $type,
                $title,
                $message,
                $actionUrl,
                $priority,
                $notifiable,
                $actor
            );
        }
    }


    /**
     * Send private/personal activity.
     *
     * Example:
     * - message
     * - mentorship accepted
     * - own job application update
     * - own event update
     */
    public static function personal(
        User|int $user,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $priority = 'medium',
        ?Model $notifiable = null,
        User|int|null $actor = null
    ): Notification {

        return self::send(
            $user,
            $type,
            $title,
            $message,
            $actionUrl,
            $priority,
            $notifiable,
            $actor
        );
    }


    /**
     * Notify all General Admins.
     */
    public static function admins(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $priority = 'medium',
        ?Model $notifiable = null,
        User|int|null $actor = null
    ): void {

        $users = User::query()
            ->where('role', 'admin')
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->get();

        self::sendMany(
            $users,
            $type,
            $title,
            $message,
            $actionUrl,
            $priority,
            $notifiable,
            $actor
        );
    }


    /**
     * Notify all Super Admins.
     */
    public static function superAdmins(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $priority = 'medium',
        ?Model $notifiable = null,
        User|int|null $actor = null
    ): void {

        $users = User::query()
            ->where('role', 'super_admin')
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->get();

        self::sendMany(
            $users,
            $type,
            $title,
            $message,
            $actionUrl,
            $priority,
            $notifiable,
            $actor
        );
    }


    /**
     * Notify Admin + Super Admin.
     *
     * Use this for management activities.
     */
    public static function management(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $priority = 'medium',
        ?Model $notifiable = null,
        User|int|null $actor = null
    ): void {

        $users = User::query()
            ->whereIn(
                'role',
                [
                    'admin',
                    'super_admin',
                ]
            )
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->get();

        self::sendMany(
            $users,
            $type,
            $title,
            $message,
            $actionUrl,
            $priority,
            $notifiable,
            $actor
        );
    }


    /**
     * Notify only the Owner Super Admin.
     */
    public static function owner(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $priority = 'high',
        ?Model $notifiable = null,
        User|int|null $actor = null
    ): void {

        $owner = User::query()
            ->where('role', 'super_admin')
            ->where('is_owner', true)
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->first();

        if (!$owner) {
            return;
        }

        self::send(
            $owner,
            $type,
            $title,
            $message,
            $actionUrl,
            $priority,
            $notifiable,
            $actor
        );
    }


    /**
     * Send notification according to a role.
     */
    public static function role(
        string $role,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $priority = 'medium',
        ?Model $notifiable = null,
        User|int|null $actor = null
    ): void {

        $allowedRoles = [
            'student',
            'alumni',
            'admin',
            'super_admin',
        ];

        if (!in_array(
            $role,
            $allowedRoles,
            true
        )) {
            return;
        }

        $users = User::query()
            ->where('role', $role)
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->get();

        self::sendMany(
            $users,
            $type,
            $title,
            $message,
            $actionUrl,
            $priority,
            $notifiable,
            $actor
        );
    }
}