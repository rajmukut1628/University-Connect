<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerifiedUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show registration page.
     */
    public function create(): View
    {
        return view('auth.register');
    }


    /**
     * Register verified Student / Alumni.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Normalize input
        |--------------------------------------------------------------------------
        */

        $request->merge([

            'name' =>
                trim(
                    (string) $request->name
                ),

            'email' =>
                strtolower(
                    trim(
                        (string) $request->email
                    )
                ),

            'official_id' =>
                trim(
                    (string) $request->official_id
                ),

            'role' =>
                strtolower(
                    trim(
                        (string) $request->role
                    )
                ),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role' => [
                'required',
                'in:student,alumni',
            ],

            'official_id' => [
                'required',
                'string',
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
        | Find approved university verified user
        |--------------------------------------------------------------------------
        */

        $verifiedUserQuery =
            VerifiedUser::query()
                ->whereRaw(
                    'LOWER(TRIM(email)) = ?',
                    [$request->email]
                )
                ->where(
                    'role',
                    $request->role
                )
                ->where(
                    'status',
                    'active'
                );


        /*
        |--------------------------------------------------------------------------
        | Match official ID
        |--------------------------------------------------------------------------
        */

        if (
            $request->role === 'student'
        ) {
            $verifiedUserQuery->whereRaw(
                'TRIM(student_id) = ?',
                [
                    $request->official_id,
                ]
            );
        }


        if (
            $request->role === 'alumni'
        ) {
            $verifiedUserQuery->whereRaw(
                'TRIM(alumni_id) = ?',
                [
                    $request->official_id,
                ]
            );
        }


        $verifiedUser =
            $verifiedUserQuery->first();


        /*
        |--------------------------------------------------------------------------
        | Not verified
        |--------------------------------------------------------------------------
        */

        if (!$verifiedUser) {

            return back()
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ])
                )
                ->withErrors([

                    'official_id' =>
                        'Your email, role and official ID do not match the university verified user database.',

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Student ID protection
        |--------------------------------------------------------------------------
        */

        if (
            $request->role === 'student' &&
            User::where(
                'student_id',
                $verifiedUser->student_id
            )->exists()
        ) {

            return back()
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ])
                )
                ->withErrors([

                    'official_id' =>
                        'This Student ID is already registered.',

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Alumni ID protection
        |--------------------------------------------------------------------------
        */

        if (
            $request->role === 'alumni' &&
            User::where(
                'alumni_id',
                $verifiedUser->alumni_id
            )->exists()
        ) {

            return back()
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ])
                )
                ->withErrors([

                    'official_id' =>
                        'This Alumni ID is already registered.',

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create real login account
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            /*
             * Use university verified name.
             */
            'name' =>
                $verifiedUser->name,

            'email' =>
                $verifiedUser->email,

            /*
             * User model already has:
             *
             * 'password' => 'hashed'
             *
             * so Laravel will hash this automatically.
             */
            'password' =>
                $request->password,

            'role' =>
                $verifiedUser->role,

            /*
             * Student / Alumni ID
             */
            'student_id' =>
                $verifiedUser->role === 'student'
                    ? $verifiedUser->student_id
                    : null,

            'alumni_id' =>
                $verifiedUser->role === 'alumni'
                    ? $verifiedUser->alumni_id
                    : null,

            /*
             * University verified information
             */
            'phone' =>
                $verifiedUser->phone,

            'department' =>
                $verifiedUser->department,

            'batch' =>
                $verifiedUser->batch,

            /*
             * Account status
             */
            'email_verified_at' =>
                now(),

            'email_verified' =>
                true,

            'is_active' =>
                true,

            'is_blocked' =>
                false,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Laravel Registered Event
        |--------------------------------------------------------------------------
        */

        event(
            new Registered($user)
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect to login
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(

                'account_created',

                'Your account has been created successfully. Please login to continue.'

            );
    }
}