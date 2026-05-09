<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OfficialAlumni;
use App\Models\OfficialStudent;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:student,alumni'],
            'official_id' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $officialStudent = null;
        $officialAlumni = null;

        if ($request->role === 'student') {
            $officialStudent = OfficialStudent::where('email', $request->email)
                ->where('student_id', $request->official_id)
                ->first();

            if (!$officialStudent) {
                return back()->withInput()->withErrors([
                    'official_id' => 'Student information did not match university official database.',
                ]);
            }
        }

        if ($request->role === 'alumni') {
            $officialAlumni = OfficialAlumni::where('email', $request->email)
                ->where('alumni_id', $request->official_id)
                ->first();

            if (!$officialAlumni) {
                return back()->withInput()->withErrors([
                    'official_id' => 'Alumni information did not match university official database.',
                ]);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'student_id' => $officialStudent?->id,
            'alumni_id' => $officialAlumni?->id,
            'email_verified' => true,
            'is_active' => true,
            'is_blocked' => false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}