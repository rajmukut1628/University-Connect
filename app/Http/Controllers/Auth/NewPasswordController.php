<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Show password reset form.
     */
    public function create(Request $request): View
    {
        return view(
            'auth.reset-password',
            [
                'request' => $request,
            ]
        );
    }


    /**
     * Reset password.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'token' => [
                'required',
                'string',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Reset Password
        |--------------------------------------------------------------------------
        */

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),

            function (User $user) use ($request) {

                /*
                |--------------------------------------------------------------------------
                | User model already uses:
                |
                | 'password' => 'hashed'
                |
                | Therefore plain password can safely be assigned here.
                |--------------------------------------------------------------------------
                */

                $user->forceFill([
                    'password' =>
                        $request->input('password'),

                    'remember_token' =>
                        Str::random(60),
                ]);

                $user->save();


                /*
                |--------------------------------------------------------------------------
                | Fire Password Reset Event
                |--------------------------------------------------------------------------
                */

                event(
                    new PasswordReset($user)
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Successful Reset
        |--------------------------------------------------------------------------
        */

        if ($status === Password::PASSWORD_RESET) {

            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Your password has been reset successfully. You can now login with your new password.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Reset Failed
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput(
                $request->only('email')
            )
            ->withErrors([
                'email' => __($status),
            ]);
    }
}