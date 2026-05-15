<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerifiedUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => trim((string) $request->name),
            'email' => strtolower(trim((string) $request->email)),
            'official_id' => trim((string) $request->official_id),
            'role' => strtolower(trim((string) $request->role)),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:student,alumni'],
            'official_id' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $verifiedUser = VerifiedUser::whereRaw('LOWER(TRIM(email)) = ?', [$request->email])
            ->whereRaw('LOWER(TRIM(role)) = ?', [$request->role])
            ->whereRaw('LOWER(TRIM(status)) = ?', ['active'])
            ->where(function ($query) use ($request) {
                if ($request->role === 'student') {
                    $query->whereRaw('TRIM(student_id) = ?', [$request->official_id]);
                }

                if ($request->role === 'alumni') {
                    $query->whereRaw('TRIM(alumni_id) = ?', [$request->official_id]);
                }
            })
            ->first();

        if (!$verifiedUser) {
            return back()->withInput()->withErrors([
                'official_id' => 'Your official email, role and ID did not match the university verified database.',
            ]);
        }

        if ($request->role === 'student' && User::where('student_id', $verifiedUser->student_id)->exists()) {
            return back()->withInput()->withErrors([
                'official_id' => 'This Student ID is already registered.',
            ]);
        }

        if ($request->role === 'alumni' && User::where('alumni_id', $verifiedUser->alumni_id)->exists()) {
            return back()->withInput()->withErrors([
                'official_id' => 'This Alumni ID is already registered.',
            ]);
        }

        $user = User::create([
            'name' => $verifiedUser->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $verifiedUser->role,

            'student_id' => $request->role === 'student' ? $verifiedUser->student_id : null,
            'alumni_id' => $request->role === 'alumni' ? $verifiedUser->alumni_id : null,

            'phone' => $verifiedUser->phone ?? null,
            'department' => $verifiedUser->department ?? null,
            'batch' => $verifiedUser->batch ?? null,

            'email_verified_at' => now(),
            'email_verified' => true,
            'is_active' => true,
            'is_blocked' => false,
        ]);

        event(new Registered($user));

        return redirect()
            ->route('login')
            ->with('account_created', 'Your account has been created successfully. Please login to continue.');
    }
}