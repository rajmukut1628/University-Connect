<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class SuperAdminManagementController extends Controller
{
    /**
     * Show all Super Admin accounts.
     */
    public function index()
    {
        $superAdmins = User::where('role', 'super_admin')
            ->orderByDesc('is_owner')
            ->orderBy('name')
            ->get();

        return view('superadmin.super-admins.index', compact('superAdmins'));
    }

    /**
     * Show the form for creating a new Super Admin.
     */
    public function create()
    {
        return view('superadmin.super-admins.create');
    }

    /**
     * Store a new Super Admin account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,

            'role' => 'super_admin',
            'is_owner' => false,

            'is_active' => true,
            'is_blocked' => false,

            'password' => $request->password,
        ]);

        return redirect()
            ->route('superadmin.super-admins.index')
            ->with('success', 'New Super Admin account created successfully.');
    }

    /**
     * Transfer ownership to another Super Admin.
     */
    public function transferOwnership(Request $request, User $user)
    {
        $currentOwner = $request->user();

        if (!$currentOwner || !$currentOwner->isOwner()) {
            abort(403, 'Only the current Owner Super Admin can transfer ownership.');
        }

        if (!$user->isSuperAdmin()) {
            return redirect()
                ->route('superadmin.super-admins.index')
                ->with('error', 'Ownership can only be transferred to another Super Admin.');
        }

        if ($user->id === $currentOwner->id) {
            return redirect()
                ->route('superadmin.super-admins.index')
                ->with('error', 'You are already the current Owner.');
        }

        DB::transaction(function () use ($currentOwner, $user) {
            /*
             * Remove ownership from every Super Admin first.
             * This helps ensure there is only one owner.
             */
            User::where('role', 'super_admin')
                ->where('is_owner', true)
                ->update([
                    'is_owner' => false,
                ]);

            /*
             * Assign ownership to the selected Super Admin.
             */
            $user->update([
                'is_owner' => true,
            ]);
        });

        return redirect()
            ->route('superadmin.super-admins.index')
            ->with(
                'success',
                'Ownership has been transferred successfully to ' . $user->name . '.'
            );
    }

    /**
     * Delete a normal Super Admin.
     *
     * The current Owner cannot be deleted.
     */
    public function destroy(Request $request, User $user)
    {
        $currentOwner = $request->user();

        if (!$currentOwner || !$currentOwner->isOwner()) {
            abort(403, 'Only the Owner Super Admin can remove a Super Admin.');
        }

        if (!$user->isSuperAdmin()) {
            return redirect()
                ->route('superadmin.super-admins.index')
                ->with('error', 'The selected user is not a Super Admin.');
        }

        if ($user->is_owner) {
            return redirect()
                ->route('superadmin.super-admins.index')
                ->with(
                    'error',
                    'The current Owner cannot be removed. Transfer ownership first.'
                );
        }

        if ($user->id === $currentOwner->id) {
            return redirect()
                ->route('superadmin.super-admins.index')
                ->with('error', 'You cannot remove your own Owner account.');
        }

        $user->delete();

        return redirect()
            ->route('superadmin.super-admins.index')
            ->with('success', 'Super Admin account removed successfully.');
    }
}