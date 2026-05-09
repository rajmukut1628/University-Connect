<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\VerifiedUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'in:student,alumni'],
            'official_id' => ['required', 'string', 'max:100'],
            'password' => $this->passwordRules(),
        ])->validate();

        $verifiedUser = VerifiedUser::where('email', $input['email'])
            ->where('role', $input['role'])
            ->where('status', 'active')
            ->where(function ($query) use ($input) {
                $query->where('student_id', $input['official_id'])
                      ->orWhere('alumni_id', $input['official_id']);
            })
            ->first();

        if (!$verifiedUser) {
            throw ValidationException::withMessages([
                'official_id' => 'Your official ID, email, and account type do not match our verified university database.',
            ]);
        }

        return User::create([
            'name' => $verifiedUser->name,
            'email' => $verifiedUser->email,
            'password' => Hash::make($input['password']),
            'role' => $verifiedUser->role,

            'student_id' => $verifiedUser->student_id,
            'alumni_id' => $verifiedUser->alumni_id,
            'department' => $verifiedUser->department,
            'batch' => $verifiedUser->batch,

            'is_active' => 1,
            'is_blocked' => 0,
        ]);
    }
}