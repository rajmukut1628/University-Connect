<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UnblockExpiredAccounts
{
    public function handle(Request $request, Closure $next): Response
    {
        User::unblockExpiredAccounts();

        return $next($request);
    }
}
