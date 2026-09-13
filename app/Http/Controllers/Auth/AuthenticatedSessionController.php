<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'login' => trim((string) $request->login),
        ]);

        $request->validate([
            'login' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $login = trim($request->login);

        /*
        |--------------------------------------------------------------------------
        | Find user by Email OR Official ID
        |--------------------------------------------------------------------------
        */

        $user = User::whereRaw(
            'LOWER(TRIM(email)) = ?',
            [strtolower($login)]
        )
            ->orWhere('official_id', $login)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Invalid account/password
        |--------------------------------------------------------------------------
        */

        if (
            !$user ||
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {
            throw ValidationException::withMessages([
                'login' => 'The provided login credentials are incorrect.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Blocked Account
        |--------------------------------------------------------------------------
        */

        if ($user->is_blocked) {
            throw ValidationException::withMessages([
                'login' => 'Your account has been blocked.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Inactive Account
        |--------------------------------------------------------------------------
        */

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'login' => 'Your account is currently inactive.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */

        Auth::login(
            $user,
            $request->boolean('remember')
        );

        $request->session()->regenerate();

        return redirect()->intended(
            route('dashboard')
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}