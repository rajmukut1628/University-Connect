<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerifiedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VerifiedUserController extends Controller
{
    /**
     * Show all verified users.
     */
    public function index(Request $request)
    {
        $query = VerifiedUser::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%")
                    ->orWhere('alumni_id', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $verifiedUsers = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => VerifiedUser::count(),
            'students' => VerifiedUser::where('role', 'student')->count(),
            'alumni' => VerifiedUser::where('role', 'alumni')->count(),
            'active' => VerifiedUser::where('status', 'active')->count(),
        ];

        return view(
            'superadmin.verified-users.index',
            compact('verifiedUsers', 'stats')
        );
    }

    /**
     * Add one verified user manually.
     */
    public function store(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->name),
            'email' => strtolower(trim((string) $request->email)),
            'unique_id' => trim((string) $request->unique_id),
            'role' => strtolower(trim((string) $request->role)),
            'status' => strtolower(trim((string) $request->status)),
            'phone' => trim((string) $request->phone),
            'department' => trim((string) $request->department),
            'batch' => trim((string) $request->batch),
            'notes' => trim((string) $request->notes),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('verified_users', 'email'),
            ],

            'phone' => ['nullable', 'string', 'max:30'],

            'unique_id' => [
                'required',
                'string',
                'max:100',
            ],

            'role' => [
                'required',
                Rule::in(['student', 'alumni']),
            ],

            'department' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:255'],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],

            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (
            User::where('email', $validated['email'])->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'An account already exists with this email address.',
                ]);
        }

        if (
            User::where('official_id', $validated['unique_id'])->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'unique_id' => 'An account already exists with this Official ID.',
                ]);
        }

        if (
            $validated['role'] === 'student' &&
            VerifiedUser::where('student_id', $validated['unique_id'])->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'unique_id' => 'This Student ID already exists in the verified database.',
                ]);
        }

        if (
            $validated['role'] === 'alumni' &&
            VerifiedUser::where('alumni_id', $validated['unique_id'])->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'unique_id' => 'This Alumni ID already exists in the verified database.',
                ]);
        }

        DB::transaction(function () use ($validated) {

            VerifiedUser::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,

                'student_id' =>
                    $validated['role'] === 'student'
                        ? $validated['unique_id']
                        : null,

                'alumni_id' =>
                    $validated['role'] === 'alumni'
                        ? $validated['unique_id']
                        : null,

                'role' => $validated['role'],
                'department' => $validated['department'] ?? null,
                'batch' => $validated['batch'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            if ($validated['status'] === 'active') {
                User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'official_id' => $validated['unique_id'],

                    /*
                     * Default password = Official ID
                     * User model hashes automatically.
                     */
                    'password' => $validated['unique_id'],

                    'role' => $validated['role'],

                    'phone' => $validated['phone'] ?? null,
                    'department' => $validated['department'] ?? null,
                    'batch' => $validated['batch'] ?? null,

                    /*
                     * Avoid old foreign-key conflict.
                     */
                    'student_id' => null,
                    'alumni_id' => null,

                    'email_verified_at' => now(),
                    'email_verified' => true,
                    'is_active' => true,
                    'is_blocked' => false,
                    'is_owner' => false,
                ]);
            }
        });

        return redirect()
            ->route('superadmin.verified-users.index')
            ->with(
                'success',
                'Verified user and login account created successfully.'
            );
    }

    /**
     * Update verified user.
     */
    public function update(
        Request $request,
        VerifiedUser $verifiedUser
    ) {
        $uniqueId = trim(
            (string) (
                $request->unique_id
                ??
                $verifiedUser->student_id
                ??
                $verifiedUser->alumni_id
            )
        );

        $request->merge([
            'name' => trim((string) $request->name),
            'email' => strtolower(trim((string) $request->email)),
            'unique_id' => $uniqueId,
            'role' => strtolower(trim((string) $request->role)),
            'status' => strtolower(trim((string) $request->status)),
            'phone' => trim((string) $request->phone),
            'department' => trim((string) $request->department),
            'batch' => trim((string) $request->batch),
            'notes' => trim((string) $request->notes),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('verified_users', 'email')
                    ->ignore($verifiedUser->id),
            ],

            'phone' => ['nullable', 'string', 'max:30'],

            'unique_id' => [
                'required',
                'string',
                'max:100',
            ],

            'role' => [
                'required',
                Rule::in(['student', 'alumni']),
            ],

            'department' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:255'],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],

            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['role'] === 'student') {
            $exists = VerifiedUser::where(
                'student_id',
                $validated['unique_id']
            )
                ->where('id', '!=', $verifiedUser->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'unique_id' => 'This Student ID already exists.',
                    ]);
            }
        }

        if ($validated['role'] === 'alumni') {
            $exists = VerifiedUser::where(
                'alumni_id',
                $validated['unique_id']
            )
                ->where('id', '!=', $verifiedUser->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'unique_id' => 'This Alumni ID already exists.',
                    ]);
            }
        }

        DB::transaction(function () use ($validated, $verifiedUser) {

            $oldEmail = $verifiedUser->email;

            $verifiedUser->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,

                'student_id' =>
                    $validated['role'] === 'student'
                        ? $validated['unique_id']
                        : null,

                'alumni_id' =>
                    $validated['role'] === 'alumni'
                        ? $validated['unique_id']
                        : null,

                'role' => $validated['role'],
                'department' => $validated['department'] ?? null,
                'batch' => $validated['batch'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $user = User::where('official_id', $validated['unique_id'])
                ->orWhere('email', $oldEmail)
                ->first();

            if ($validated['status'] === 'active') {

                if ($user) {
                    $user->update([
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'official_id' => $validated['unique_id'],
                        'role' => $validated['role'],
                        'phone' => $validated['phone'] ?? null,
                        'department' => $validated['department'] ?? null,
                        'batch' => $validated['batch'] ?? null,
                        'is_active' => true,
                        'is_blocked' => false,
                    ]);
                } else {
                    User::create([
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'official_id' => $validated['unique_id'],
                        'password' => $validated['unique_id'],
                        'role' => $validated['role'],
                        'phone' => $validated['phone'] ?? null,
                        'department' => $validated['department'] ?? null,
                        'batch' => $validated['batch'] ?? null,
                        'student_id' => null,
                        'alumni_id' => null,
                        'email_verified_at' => now(),
                        'email_verified' => true,
                        'is_active' => true,
                        'is_blocked' => false,
                        'is_owner' => false,
                    ]);
                }
            } else {
                if ($user) {
                    $user->update([
                        'is_active' => false,
                    ]);
                }
            }
        });

        return redirect()
            ->route('superadmin.verified-users.index')
            ->with(
                'success',
                'Verified user updated successfully.'
            );
    }

    /**
     * Delete verified user.
     */
    public function destroy(VerifiedUser $verifiedUser)
    {
        DB::transaction(function () use ($verifiedUser) {

            $officialId = $verifiedUser->student_id
                ?? $verifiedUser->alumni_id;

            if ($officialId) {
                User::where('official_id', $officialId)
                    ->whereIn('role', ['student', 'alumni'])
                    ->delete();
            }

            $verifiedUser->delete();
        });

        return back()->with(
            'success',
            'Verified user and linked login account deleted successfully.'
        );
    }

    /**
     * Bulk import without preview.
     *
     * Supported CSV format:
     *
     * ID,Name,Email,Department,Batch,Role,Status
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'bulk_text' => [
                'nullable',
                'string',
            ],

            'import_file' => [
                'nullable',
                'file',
                'mimes:txt,csv',
                'max:10240',
            ],
        ]);

        $text = '';

        if ($request->filled('bulk_text')) {
            $text .= "\n" . $request->bulk_text;
        }

        if ($request->hasFile('import_file')) {
            $text .= "\n" . file_get_contents(
                $request->file('import_file')->getRealPath()
            );
        }

        if (trim($text) === '') {
            return back()->withErrors([
                'bulk_text' => 'Please paste text or upload a CSV/TXT file.',
            ]);
        }

        $rows = preg_split(
            '/\r\n|\r|\n/',
            trim($text)
        );

        $created = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {

            $row = trim($row);

            if ($row === '') {
                continue;
            }

            $parts = str_getcsv($row);

            if (count($parts) < 7) {
                $skipped++;
                continue;
            }

            $officialId = trim($parts[0] ?? '');
            $name = trim($parts[1] ?? '');
            $email = strtolower(trim($parts[2] ?? ''));
            $department = trim($parts[3] ?? '');
            $batch = trim($parts[4] ?? '');
            $role = strtolower(trim($parts[5] ?? 'student'));
            $status = strtolower(trim($parts[6] ?? 'active'));

            /*
             * Ignore header row.
             */
            if (
                $index === 0 &&
                strtolower($officialId) === 'id'
            ) {
                continue;
            }

            if (
                !$officialId ||
                !$name ||
                !$email ||
                !filter_var($email, FILTER_VALIDATE_EMAIL) ||
                !in_array($role, ['student', 'alumni'], true)
            ) {
                $skipped++;
                continue;
            }

            if (
                !in_array(
                    $status,
                    ['active', 'inactive'],
                    true
                )
            ) {
                $status = 'active';
            }

            if (
                VerifiedUser::where('email', $email)->exists() ||
                User::where('email', $email)->exists() ||
                User::where('official_id', $officialId)->exists()
            ) {
                $skipped++;
                continue;
            }

            if (
                $role === 'student' &&
                VerifiedUser::where(
                    'student_id',
                    $officialId
                )->exists()
            ) {
                $skipped++;
                continue;
            }

            if (
                $role === 'alumni' &&
                VerifiedUser::where(
                    'alumni_id',
                    $officialId
                )->exists()
            ) {
                $skipped++;
                continue;
            }

            DB::transaction(function () use (
                $officialId,
                $name,
                $email,
                $department,
                $batch,
                $role,
                $status
            ) {

                VerifiedUser::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => null,

                    'student_id' =>
                        $role === 'student'
                            ? $officialId
                            : null,

                    'alumni_id' =>
                        $role === 'alumni'
                            ? $officialId
                            : null,

                    'role' => $role,
                    'department' => $department ?: null,
                    'batch' => $batch ?: null,
                    'status' => $status,
                    'notes' => null,
                    'created_by' => auth()->id(),
                ]);

                if ($status === 'active') {
                    User::create([
                        'name' => $name,
                        'email' => $email,
                        'official_id' => $officialId,

                        /*
                         * Default password = Official ID
                         */
                        'password' => $officialId,

                        'role' => $role,
                        'phone' => null,
                        'department' => $department ?: null,
                        'batch' => $batch ?: null,

                        'student_id' => null,
                        'alumni_id' => null,

                        'email_verified_at' => now(),
                        'email_verified' => true,
                        'is_active' => true,
                        'is_blocked' => false,
                        'is_owner' => false,
                    ]);
                }
            });

            $created++;
        }

        return back()->with(
            'success',
            "{$created} verified users and login accounts imported successfully. {$skipped} rows skipped."
        );
    }

    /**
     * Preview bulk import.
     *
     * Supported CSV:
     * ID,Name,Email,Department,Batch,Role,Status
     */
    public function bulkPreview(Request $request)
    {
        $request->validate([
            'bulk_text' => [
                'nullable',
                'string',
            ],

            'import_file' => [
                'nullable',
                'file',
                'mimes:txt,csv',
                'max:10240',
            ],
        ]);

        $text = '';

        if ($request->filled('bulk_text')) {
            $text .= "\n" . $request->bulk_text;
        }

        if ($request->hasFile('import_file')) {
            $text .= "\n" . file_get_contents(
                $request->file('import_file')->getRealPath()
            );
        }

        if (trim($text) === '') {
            return back()->withErrors([
                'bulk_text' => 'Please paste text or upload a CSV/TXT file.',
            ]);
        }

        $rows = preg_split(
            '/\r\n|\r|\n/',
            trim($text)
        );

        $previewRows = [];

        foreach ($rows as $index => $row) {

            $row = trim($row);

            if ($row === '') {
                continue;
            }

            $parts = str_getcsv($row);

            if (count($parts) < 7) {
                continue;
            }

            $officialId = trim($parts[0] ?? '');
            $name = trim($parts[1] ?? '');
            $email = strtolower(trim($parts[2] ?? ''));
            $department = trim($parts[3] ?? '');
            $batch = trim($parts[4] ?? '');
            $role = strtolower(trim($parts[5] ?? 'student'));
            $status = strtolower(trim($parts[6] ?? 'active'));

            if (
                $index === 0 &&
                strtolower($officialId) === 'id'
            ) {
                continue;
            }

            if (
                !$officialId ||
                !$name ||
                !$email ||
                !filter_var($email, FILTER_VALIDATE_EMAIL) ||
                !in_array($role, ['student', 'alumni'], true)
            ) {
                continue;
            }

            if (
                !in_array(
                    $status,
                    ['active', 'inactive'],
                    true
                )
            ) {
                $status = 'active';
            }

            $previewRows[] = [
                'official_id' => $officialId,
                'name' => $name,
                'email' => $email,
                'department' => $department,
                'batch' => $batch,
                'role' => $role,
                'status' => $status,

                'student_id' =>
                    $role === 'student'
                        ? $officialId
                        : null,

                'alumni_id' =>
                    $role === 'alumni'
                        ? $officialId
                        : null,
            ];
        }

        if (empty($previewRows)) {
            return back()->withErrors([
                'bulk_text' => 'No valid rows detected.',
            ]);
        }

        session([
            'verified_users_preview' => $previewRows,
        ]);

        return view(
            'superadmin.verified-users.preview',
            [
                'previewRows' => $previewRows,
            ]
        );
    }

    /**
     * Confirm preview import.
     */
    public function bulkConfirm()
    {
        $previewRows = session(
            'verified_users_preview',
            []
        );

        if (empty($previewRows)) {
            return redirect()
                ->route(
                    'superadmin.verified-users.index'
                )
                ->withErrors([
                    'bulk_text' => 'Preview session expired.',
                ]);
        }

        $created = 0;
        $skipped = 0;

        foreach ($previewRows as $row) {

            $officialId = $row['official_id'];
            $email = strtolower($row['email']);
            $role = $row['role'];
            $status = $row['status'];

            if (
                VerifiedUser::where('email', $email)->exists() ||
                User::where('email', $email)->exists() ||
                User::where('official_id', $officialId)->exists()
            ) {
                $skipped++;
                continue;
            }

            if (
                $role === 'student' &&
                VerifiedUser::where(
                    'student_id',
                    $officialId
                )->exists()
            ) {
                $skipped++;
                continue;
            }

            if (
                $role === 'alumni' &&
                VerifiedUser::where(
                    'alumni_id',
                    $officialId
                )->exists()
            ) {
                $skipped++;
                continue;
            }

            DB::transaction(function () use (
                $row,
                $officialId,
                $email,
                $role,
                $status
            ) {

                VerifiedUser::create([
                    'name' => $row['name'],
                    'email' => $email,
                    'phone' => null,

                    'student_id' =>
                        $role === 'student'
                            ? $officialId
                            : null,

                    'alumni_id' =>
                        $role === 'alumni'
                            ? $officialId
                            : null,

                    'role' => $role,
                    'department' => $row['department'] ?: null,
                    'batch' => $row['batch'] ?: null,
                    'status' => $status,
                    'notes' => null,
                    'created_by' => auth()->id(),
                ]);

                if ($status === 'active') {
                    User::create([
                        'name' => $row['name'],
                        'email' => $email,
                        'official_id' => $officialId,
                        'password' => $officialId,
                        'role' => $role,

                        'phone' => null,
                        'department' => $row['department'] ?: null,
                        'batch' => $row['batch'] ?: null,

                        'student_id' => null,
                        'alumni_id' => null,

                        'email_verified_at' => now(),
                        'email_verified' => true,
                        'is_active' => true,
                        'is_blocked' => false,
                        'is_owner' => false,
                    ]);
                }
            });

            $created++;
        }

        session()->forget(
            'verified_users_preview'
        );

        return redirect()
            ->route(
                'superadmin.verified-users.index'
            )
            ->with(
                'success',
                "{$created} verified users and login accounts imported successfully. {$skipped} rows skipped."
            );
    }
}