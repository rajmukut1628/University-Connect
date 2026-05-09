<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $role = $request->role;

        $users = User::query()
            ->where('is_active', false)
            ->where('is_blocked', false)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('department', 'like', "%{$search}%")
                      ->orWhere('batch', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'pending' => User::where('is_active', false)->where('is_blocked', false)->count(),
            'students' => User::where('role', 'student')->where('is_active', false)->where('is_blocked', false)->count(),
            'alumni' => User::where('role', 'alumni')->where('is_active', false)->where('is_blocked', false)->count(),
            'blocked' => User::where('is_blocked', true)->count(),
        ];

        return view('admin.verification.index', compact('users', 'stats', 'search', 'role'));
    }

    public function approve(User $user)
    {
        $user->update([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        return back()->with('success', 'User verified successfully.');
    }

    public function reject(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->withErrors([
                'error' => 'Super Admin account cannot be rejected.',
            ]);
        }

        $user->update([
            'is_active' => false,
            'is_blocked' => true,
        ]);

        return back()->with('success', 'User rejected and blocked successfully.');
    }
}