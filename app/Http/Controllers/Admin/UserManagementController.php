<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $role = $request->role;
        $status = $request->status;

        $users = User::query()
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
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true)->where('is_blocked', false);
            })
            ->when($status === 'blocked', function ($query) {
                $query->where('is_blocked', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'alumni' => User::where('role', 'alumni')->count(),
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'blocked' => User::where('is_blocked', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search', 'role', 'status'));
    }

    public function block(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->withErrors([
                'error' => 'Super Admin account cannot be blocked.',
            ]);
        }

        $user->update([
            'is_blocked' => true,
        ]);

        return back()->with('success', 'User blocked successfully.');
    }

    public function unblock(User $user)
    {
        $user->update([
            'is_blocked' => false,
            'is_active' => true,
        ]);

        return back()->with('success', 'User unblocked successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->withErrors([
                'error' => 'Super Admin account cannot be deleted.',
            ]);
        }

        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'error' => 'You cannot delete your own account.',
            ]);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}