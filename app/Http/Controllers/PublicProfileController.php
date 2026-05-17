<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function alumni(User $user): View
    {
        abort_unless($user->role === 'alumni', 404);

        return view('profiles.public-alumni', [
            'profileUser' => $user,
        ]);
    }

    public function student(User $user): View
    {
        abort_unless($user->role === 'student', 404);

        return view('profiles.public-student', [
            'profileUser' => $user,
        ]);
    }
}