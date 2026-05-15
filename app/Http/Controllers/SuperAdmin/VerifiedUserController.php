<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\VerifiedUser;
use App\Services\VerifiedUserAISmartNormalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class VerifiedUserController extends Controller
{
    public function index(Request $request)
    {
        $query = VerifiedUser::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('unique_id', 'like', "%{$search}%")
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

        return view('superadmin.verified-users.index', compact(
            'verifiedUsers',
            'stats'
        ));
    }

    public function store(Request $request, VerifiedUserAISmartNormalizer $normalizer)
    {
        $data = $normalizer->normalize($request->all());

        $request->merge($data);

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('verified_users', 'email'),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'unique_id' => ['nullable', 'string', 'max:100'],
            'role' => ['required', Rule::in(['student', 'alumni'])],
            'department' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'blocked'])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['created_by'] = auth()->id();

        VerifiedUser::create($validated);

        return back()->with('success', 'Verified user added successfully with AI smart formatting.');
    }

    public function update(Request $request, VerifiedUser $verifiedUser, VerifiedUserAISmartNormalizer $normalizer)
    {
        $data = $normalizer->normalize($request->all());

        $request->merge($data);

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('verified_users', 'email')->ignore($verifiedUser->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'unique_id' => ['nullable', 'string', 'max:100'],
            'role' => ['required', Rule::in(['student', 'alumni'])],
            'department' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'blocked'])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $verifiedUser->update($validated);

        return back()->with('success', 'Verified user updated successfully with AI smart formatting.');
    }

    public function destroy(VerifiedUser $verifiedUser)
    {
        $verifiedUser->delete();

        return back()->with('success', 'Verified user deleted successfully.');
    }

    public function bulkStore(Request $request)
{
    $request->validate([
    'bulk_text' => ['nullable', 'string'],
    'import_file' => ['nullable', 'file', 'mimes:txt,csv,pdf,doc,docx', 'max:10240'],
]);

    $text = '';

    if ($request->filled('bulk_text')) {
        $text .= "\n" . $request->bulk_text;
    }

    if ($request->hasFile('import_file')) {
        $text .= "\n" . $this->extractTextFromFile($request->file('import_file'));
    }

    if (trim($text) === '') {
        return back()->withErrors([
            'bulk_text' => 'Please paste text or upload a file.',
        ]);
    }

    $rows = preg_split('/\r\n|\r|\n/', trim($text));

    $created = 0;
    $skipped = 0;

    foreach ($rows as $row) {
        $row = trim($row);

        if ($row === '') {
            continue;
        }

        $parts = array_map('trim', preg_split('/[,|;]/', $row));

        $name = $parts[0] ?? null;
        $email = strtolower($parts[1] ?? '');
        $phone = $parts[2] ?? null;
        $uniqueId = $parts[3] ?? null;
        $role = strtolower($parts[4] ?? 'student');
        $department = $parts[5] ?? null;
        $batch = $parts[6] ?? null;
        $status = strtolower($parts[7] ?? 'active');
        $notes = $parts[8] ?? null;

        if (!$name || !$email || !$uniqueId || !in_array($role, ['student', 'alumni'])) {
            $skipped++;
            continue;
        }

        $exists = \App\Models\VerifiedUser::where('email', $email)->exists();

        if ($exists) {
            $skipped++;
            continue;
        }

        \App\Models\VerifiedUser::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'student_id' => $role === 'student' ? $uniqueId : null,
            'alumni_id' => $role === 'alumni' ? $uniqueId : null,
            'department' => $department,
            'batch' => $batch,
            'status' => $status ?: 'active',
            'notes' => $notes,
        ]);

        $created++;
    }

    return back()->with('success', "{$created} verified users imported successfully. {$skipped} rows skipped.");
}
private function extractTextFromFile($file): string
{
    $extension = strtolower($file->getClientOriginalExtension());
    $path = $file->getRealPath();

    if (in_array($extension, ['txt', 'csv'])) {
        return file_get_contents($path);
    }

    if ($extension === 'pdf') {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($path);

        return $pdf->getText();
    }

    if (in_array($extension, ['doc', 'docx'])) {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }

        return $text;
    }

    return '';
}
public function bulkPreview(Request $request)
{
    $request->validate([
        'bulk_text' => ['nullable', 'string'],
        'import_file' => ['nullable', 'file', 'mimes:txt,csv,pdf,doc,docx,ppt,pptx,xls,xlsx', 'max:10240'],
    ]);

    $text = '';

    if ($request->filled('bulk_text')) {
        $text .= "\n" . $request->bulk_text;
    }

    if ($request->hasFile('import_file')) {
        $text .= "\n" . $this->extractTextFromFile($request->file('import_file'));
    }

    if (trim($text) === '') {
        return back()->withErrors([
            'bulk_text' => 'Please paste text or upload a file.',
        ]);
    }

    $rows = preg_split('/\r\n|\r|\n/', trim($text));

    $previewRows = [];

    foreach ($rows as $row) {
        $row = trim($row);

        if ($row === '') {
            continue;
        }

        $parts = array_map('trim', preg_split('/[,|;]/', $row));

        $name = $parts[0] ?? '';
        $email = strtolower($parts[1] ?? '');
        $phone = $parts[2] ?? '';
        $uniqueId = $parts[3] ?? '';
        $role = strtolower($parts[4] ?? 'student');
        $department = $parts[5] ?? '';
        $batch = $parts[6] ?? '';
        $status = strtolower($parts[7] ?? 'active');
        $notes = $parts[8] ?? '';

        if (!$name || !$email || !$uniqueId || !in_array($role, ['student', 'alumni'])) {
            continue;
        }

        $previewRows[] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'student_id' => $role === 'student' ? $uniqueId : null,
            'alumni_id' => $role === 'alumni' ? $uniqueId : null,
            'department' => $department,
            'batch' => $batch,
            'status' => $status ?: 'active',
            'notes' => $notes,
        ];
    }

    if (empty($previewRows)) {
        return back()->withErrors([
            'bulk_text' => 'No valid rows detected.',
        ]);
    }

    session(['verified_users_preview' => $previewRows]);

    return view('superadmin.verified-users.preview', [
        'previewRows' => $previewRows,
    ]);
}

public function bulkConfirm()
{
    $previewRows = session('verified_users_preview', []);

    if (empty($previewRows)) {
        return redirect()
            ->route('superadmin.verified-users.index')
            ->withErrors([
                'bulk_text' => 'Preview session expired.',
            ]);
    }

    $created = 0;
    $skipped = 0;

    foreach ($previewRows as $row) {
        $exists = \App\Models\VerifiedUser::where('email', $row['email'])->exists();

        if ($exists) {
            $skipped++;
            continue;
        }

        \App\Models\VerifiedUser::create($row);
        $created++;
    }

    session()->forget('verified_users_preview');

    return redirect()
        ->route('superadmin.verified-users.index')
        ->with('success', "{$created} verified users imported successfully. {$skipped} rows skipped.");
}
}