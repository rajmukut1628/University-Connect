<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerifiedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifiedUserController extends Controller
{
    public function index()
    {
        $verifiedUsers = VerifiedUser::latest()->paginate(20);

        $stats = [
            'total' => VerifiedUser::count(),
            'students' => VerifiedUser::where('role', 'student')->count(),
            'alumni' => VerifiedUser::where('role', 'alumni')->count(),
            'active' => VerifiedUser::where('status', 'active')->count(),
        ];

        return view('admin.verified-users.index', compact('verifiedUsers', 'stats'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'name' => ['required', 'string'],
                'email' => ['required', 'email'],
                'role' => ['required', 'in:student,alumni'],
                'status' => ['nullable', 'in:active,inactive'],
            ]);

            if ($validator->fails()) {
                $skipped++;
                continue;
            }

            $exists = VerifiedUser::where('email', $data['email'])->exists();

            VerifiedUser::updateOrCreate(
                ['email' => $data['email']],
                [
                    'student_id' => $data['student_id'] ?? null,
                    'alumni_id' => $data['alumni_id'] ?? null,
                    'name' => $data['name'],
                    'department' => $data['department'] ?? null,
                    'batch' => $data['batch'] ?? null,
                    'role' => $data['role'],
                    'status' => $data['status'] ?? 'active',
                ]
            );

            $exists ? $updated++ : $imported++;
        }

        fclose($file);

        return back()->with('success', "Import completed. New: {$imported}, Updated: {$updated}, Skipped: {$skipped}");
    }

    public function destroy(VerifiedUser $verifiedUser)
    {
        $verifiedUser->delete();

        return back()->with('success', 'Verified user deleted successfully.');
    }
}