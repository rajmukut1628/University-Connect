<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (
            $user->role !== 'super_admin' ||
            !$user->is_owner
        ) {
            abort(403, 'Only the Owner Super Admin can access this section.');
        }

        return $next($request);
    }
}