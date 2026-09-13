<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Notification Center.
     */
    public function index(Request $request)
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Main Query
        |--------------------------------------------------------------------------
        */

        $query = Notification::query()
            ->with([
                'actor',
            ])
            ->where(
                'user_id',
                $user->id
            )
            ->visibleTo($user);


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->status === 'unread') {

            $query->where(
                'is_read',
                false
            );

        } elseif ($request->status === 'read') {

            $query->where(
                'is_read',
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {

            $query->where(
                'type',
                $request->type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Priority Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('priority') &&
            in_array(
                $request->priority,
                ['low', 'medium', 'high'],
                true
            )
        ) {

            $query->where(
                'priority',
                $request->priority
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Notification List
        |--------------------------------------------------------------------------
        */

        $notifications = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics Base Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = Notification::query()
            ->where(
                'user_id',
                $user->id
            )
            ->visibleTo($user);


        $stats = [

            'total' =>
                (clone $baseQuery)
                    ->count(),

            'unread' =>
                (clone $baseQuery)
                    ->where(
                        'is_read',
                        false
                    )
                    ->count(),

            'high' =>
                (clone $baseQuery)
                    ->where(
                        'priority',
                        'high'
                    )
                    ->count(),

            'read' =>
                (clone $baseQuery)
                    ->where(
                        'is_read',
                        true
                    )
                    ->count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | Available Types
        |--------------------------------------------------------------------------
        */

        $types = Notification::query()
            ->where(
                'user_id',
                $user->id
            )
            ->visibleTo($user)
            ->select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');


        return view(
            'notifications.index',
            compact(
                'notifications',
                'stats',
                'types'
            )
        );
    }


    /**
     * Mark single notification read.
     */
    public function markAsRead(
        Notification $notification
    ) {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $notification->user_id ===
            (int) $user->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Role Visibility Check
        |--------------------------------------------------------------------------
        */

        $visible = Notification::query()
            ->whereKey(
                $notification->id
            )
            ->where(
                'user_id',
                $user->id
            )
            ->visibleTo($user)
            ->exists();


        abort_unless(
            $visible,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Mark Read
        |--------------------------------------------------------------------------
        */

        if (!$notification->is_read) {

            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Open Related Page
        |--------------------------------------------------------------------------
        */

        if (
            $notification->action_url &&
            request()->boolean('open')
        ) {

            return redirect(
                $notification->action_url
            );
        }


        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }


    /**
     * Mark all visible notifications read.
     */
    public function markAllAsRead()
    {
        $user = auth()->user();


        Notification::query()
            ->where(
                'user_id',
                $user->id
            )
            ->visibleTo($user)
            ->where(
                'is_read',
                false
            )
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);


        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}