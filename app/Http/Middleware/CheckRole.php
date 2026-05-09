<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Supports:
     * - role:student
     * - role:alumni
     * - role:admin,super_admin
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // User must be logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Blocked User Check
        |--------------------------------------------------------------------------
        */
        if ($user->is_blocked) {
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'email' => $user->blocked_reason
                    ? 'Your account has been blocked. Reason: ' . $user->blocked_reason
                    : 'Your account has been blocked. Contact administration.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Active Account Check
        |--------------------------------------------------------------------------
        */
        if (!$user->is_active) {
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account is not active yet. Contact administration.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Role Check
        |--------------------------------------------------------------------------
        | Supports multiple roles:
        | role:admin,super_admin
        | role:student
        | role:alumni
        */
        if (!in_array($user->role, $roles)) {
            abort(403, 'UNAUTHORIZED ACCESS.');
        }

        return $next($request);
    }
}