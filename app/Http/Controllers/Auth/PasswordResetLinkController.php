<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Show forgot password page.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }


    /**
     * Send password reset link.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Email
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Send Reset Link
        |--------------------------------------------------------------------------
        */

        $status = Password::sendResetLink([
            'email' => strtolower(
                trim($validated['email'])
            ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        if ($status === Password::RESET_LINK_SENT) {

            return back()->with(
                'status',
                'A password reset link has been sent to your registered email address. Please check your inbox and spam folder.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Error
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput([
                'email' => $validated['email'],
            ])
            ->withErrors([
                'email' => __($status),
            ]);
    }
}