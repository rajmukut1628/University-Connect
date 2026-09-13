<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Show users.
     */
    public function index(Request $request)
    {
        $search = trim(
            (string) $request->input('search', '')
        );

        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::query()

            ->when(
                $search !== '',
                function ($query) use ($search) {

                    $query->where(
                        function ($q) use ($search) {

                            $q->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'official_id',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'department',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'batch',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )

            ->when(
                $role,
                function ($query) use ($role) {
                    $query->where(
                        'role',
                        $role
                    );
                }
            )

            ->when(
                $status === 'active',
                function ($query) {
                    $query
                        ->where(
                            'is_active',
                            true
                        )
                        ->where(
                            'is_blocked',
                            false
                        );
                }
            )

            ->when(
                $status === 'blocked',
                function ($query) {
                    $query->where(
                        'is_blocked',
                        true
                    );
                }
            )

            ->when(
                $status === 'inactive',
                function ($query) {
                    $query->where(
                        'is_active',
                        false
                    );
                }
            )

            ->latest()
            ->paginate(10)
            ->withQueryString();


        $stats = [

            'total' =>
                User::count(),

            'students' =>
                User::where(
                    'role',
                    'student'
                )->count(),

            'alumni' =>
                User::where(
                    'role',
                    'alumni'
                )->count(),

            'admins' =>
                User::whereIn(
                    'role',
                    [
                        'admin',
                        'super_admin',
                    ]
                )->count(),

            'blocked' =>
                User::where(
                    'is_blocked',
                    true
                )->count(),

            'inactive' =>
                User::where(
                    'is_active',
                    false
                )->count(),
        ];


        return view(
            'admin.users.index',
            compact(
                'users',
                'stats',
                'search',
                'role',
                'status'
            )
        );
    }


    /**
     * Decide whether logged-in admin
     * can manage target account.
     */
    private function canManageUser(
        User $currentUser,
        User $targetUser
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        |
        | Can manage:
        | - Student
        | - Alumni
        | - General Admin
        |
        | Cannot manage:
        | - Another Super Admin
        |
        */

        if (
            $currentUser->role ===
            'super_admin'
        ) {
            return
                $targetUser->role !==
                'super_admin';
        }


        /*
        |--------------------------------------------------------------------------
        | General Admin
        |--------------------------------------------------------------------------
        |
        | Can manage:
        | - Student
        | - Alumni
        |
        | Cannot manage:
        | - Admin
        | - Super Admin
        |
        */

        if (
            $currentUser->role ===
            'admin'
        ) {
            return in_array(
                $targetUser->role,
                [
                    'student',
                    'alumni',
                ],
                true
            );
        }


        return false;
    }


    /**
     * Show edit form.
     */
    public function edit(User $user)
    {
        $currentUser =
            auth()->user();


        abort_unless(
            $currentUser !== null &&
            in_array(
                $currentUser->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403,
            'Unauthorized.'
        );


        if (
            !$this->canManageUser(
                $currentUser,
                $user
            )
        ) {
            return back()->withErrors([
                'error' =>
                    'You are not allowed to edit this account.',
            ]);
        }


        return view(
            'admin.users.edit',
            compact('user')
        );
    }


    /**
     * Update user.
     */
    public function update(
        Request $request,
        User $user
    ) {
        $currentUser =
            auth()->user();


        abort_unless(
            $currentUser !== null &&
            in_array(
                $currentUser->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403,
            'Unauthorized.'
        );


        if (
            !$this->canManageUser(
                $currentUser,
                $user
            )
        ) {
            return back()->withErrors([
                'error' =>
                    'You are not allowed to modify this account.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $allowedRoles =
            $currentUser->role ===
            'super_admin'
                ? [
                    'student',
                    'alumni',
                    'admin',
                ]
                : [
                    'student',
                    'alumni',
                ];


        $validated =
            $request->validate([

                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',

                    Rule::unique(
                        'users',
                        'email'
                    )->ignore(
                        $user->id
                    ),
                ],

                'official_id' => [
                    'nullable',
                    'string',
                    'max:100',

                    Rule::unique(
                        'users',
                        'official_id'
                    )->ignore(
                        $user->id
                    ),
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'max:30',
                ],

                'department' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'batch' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'address' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

                'role' => [
                    'required',
                    Rule::in(
                        $allowedRoles
                    ),
                ],

                'is_active' => [
                    'required',
                    'boolean',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | Optional Values
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'official_id',
                'phone',
                'department',
                'batch',
                'address',
            ] as $field
        ) {

            if (
                array_key_exists(
                    $field,
                    $validated
                ) &&
                trim(
                    (string)
                    $validated[$field]
                ) === ''
            ) {
                $validated[$field] =
                    null;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Keep Information For Notification
        |--------------------------------------------------------------------------
        */

        $oldRole =
            $user->role;

        $oldActiveStatus =
            (bool) $user->is_active;


        /*
        |--------------------------------------------------------------------------
        | Update User
        |--------------------------------------------------------------------------
        */

        $user->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Personal Notification
        |--------------------------------------------------------------------------
        |
        | Student/Alumni receive notification
        | about their own account.
        |
        */

        if (
            in_array(
                $user->role,
                [
                    'student',
                    'alumni',
                ],
                true
            )
        ) {

            NotificationService::personal(
                $user,
                'account_updated',
                'Account Information Updated',
                'Your account information was updated by the administration.',
                route('dashboard'),
                'medium',
                null,
                $currentUser
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Management Notification
        |--------------------------------------------------------------------------
        */

        $managementMessage =
            $currentUser->name .
            ' updated the account of ' .
            $user->name .
            '.';


        if (
            $oldRole !==
            $user->role
        ) {
            $managementMessage .=
                ' Role changed from ' .
                ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $oldRole
                    )
                ) .
                ' to ' .
                ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $user->role
                    )
                ) .
                '.';
        }


        if (
            $oldActiveStatus !==
            (bool) $user->is_active
        ) {
            $managementMessage .=
                $user->is_active
                    ? ' Account was activated.'
                    : ' Account was deactivated.';
        }


        NotificationService::management(
            'user_updated',
            'User Account Updated',
            $managementMessage,
            null,
            'medium',
            null,
            $currentUser
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        $route =
            $currentUser->role ===
            'super_admin'
                ? 'superadmin.users.index'
                : 'admin.users.index';


        return redirect()
            ->route($route)
            ->with(
                'success',
                'User information updated successfully.'
            );
    }


    /**
     * Block user.
     */
    public function block(User $user)
    {
        $currentUser =
            auth()->user();


        abort_unless(
            $currentUser !== null &&
            in_array(
                $currentUser->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403,
            'Unauthorized.'
        );


        if (
            !$this->canManageUser(
                $currentUser,
                $user
            )
        ) {
            return back()->withErrors([
                'error' =>
                    'You are not allowed to block this account.',
            ]);
        }


        if (
            (int) $currentUser->id ===
            (int) $user->id
        ) {
            return back()->withErrors([
                'error' =>
                    'You cannot block your own account.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Already Blocked
        |--------------------------------------------------------------------------
        */

        if ($user->is_blocked) {
            return back()->with(
                'success',
                'User is already blocked.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Block User
        |--------------------------------------------------------------------------
        */

        $user->update([
            'is_blocked' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Personal Notification
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $user->role,
                [
                    'student',
                    'alumni',
                ],
                true
            )
        ) {
            NotificationService::personal(
                $user,
                'account_blocked',
                'Account Restricted',
                'Your account has been blocked by the administration.',
                null,
                'high',
                null,
                $currentUser
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Management Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::management(
            'user_blocked',
            'User Account Blocked',
            $currentUser->name .
                ' blocked the account of ' .
                $user->name .
                '.',
            null,
            'high',
            null,
            $currentUser
        );


        return back()->with(
            'success',
            'User blocked successfully.'
        );
    }


    /**
     * Unblock user.
     */
    public function unblock(User $user)
    {
        $currentUser =
            auth()->user();


        abort_unless(
            $currentUser !== null &&
            in_array(
                $currentUser->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403,
            'Unauthorized.'
        );


        if (
            !$this->canManageUser(
                $currentUser,
                $user
            )
        ) {
            return back()->withErrors([
                'error' =>
                    'You are not allowed to unblock this account.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Already Active
        |--------------------------------------------------------------------------
        */

        if (
            !$user->is_blocked &&
            $user->is_active
        ) {
            return back()->with(
                'success',
                'User account is already active.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Unblock User
        |--------------------------------------------------------------------------
        */

        $user->update([
            'is_blocked' =>
                false,

            'is_active' =>
                true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Personal Notification
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $user->role,
                [
                    'student',
                    'alumni',
                ],
                true
            )
        ) {
            NotificationService::personal(
                $user,
                'account_unblocked',
                'Account Restored',
                'Your account has been unblocked and restored by the administration.',
                route('dashboard'),
                'high',
                null,
                $currentUser
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Management Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::management(
            'user_unblocked',
            'User Account Unblocked',
            $currentUser->name .
                ' unblocked the account of ' .
                $user->name .
                '.',
            null,
            'medium',
            null,
            $currentUser
        );


        return back()->with(
            'success',
            'User unblocked successfully.'
        );
    }


    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        $currentUser =
            auth()->user();


        abort_unless(
            $currentUser !== null &&
            in_array(
                $currentUser->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403,
            'Unauthorized.'
        );


        if (
            !$this->canManageUser(
                $currentUser,
                $user
            )
        ) {
            return back()->withErrors([
                'error' =>
                    'You are not allowed to delete this account.',
            ]);
        }


        if (
            (int) $currentUser->id ===
            (int) $user->id
        ) {
            return back()->withErrors([
                'error' =>
                    'You cannot delete your own account.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Save Information Before Delete
        |--------------------------------------------------------------------------
        */

        $deletedUserName =
            $user->name;

        $deletedUserRole =
            $user->role;

        $deletedUserOfficialId =
            $user->official_id;


        /*
        |--------------------------------------------------------------------------
        | Delete User
        |--------------------------------------------------------------------------
        */

        $user->delete();


        /*
        |--------------------------------------------------------------------------
        | Management Notification
        |--------------------------------------------------------------------------
        |
        | We cannot send a personal notification after deletion
        | because the target user no longer exists.
        |
        */

        $message =
            $currentUser->name .
            ' deleted ' .
            $deletedUserName .
            '\'s ' .
            ucfirst(
                str_replace(
                    '_',
                    ' ',
                    $deletedUserRole
                )
            ) .
            ' account.';


        if ($deletedUserOfficialId) {
            $message .=
                ' Official ID: ' .
                $deletedUserOfficialId .
                '.';
        }


        NotificationService::management(
            'user_deleted',
            'User Account Deleted',
            $message,
            null,
            'high',
            null,
            $currentUser
        );


        return back()->with(
            'success',
            'User deleted successfully.'
        );
    }
}