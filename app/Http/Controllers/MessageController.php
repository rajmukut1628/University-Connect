<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();

        $allowedRoles = ['student', 'alumni'];

        $users = User::whereIn('role', $allowedRoles)
            ->where('id', '!=', $authUser->id)
            ->latest()
            ->get();

        $conversations = User::whereIn('id', function ($query) use ($authUser) {
                $query->select('sender_id')
                    ->from('messages')
                    ->where('recipient_id', $authUser->id)
                    ->union(
                        Message::select('recipient_id')
                            ->where('sender_id', $authUser->id)
                    );
            })
            ->where('id', '!=', $authUser->id)
            ->get();

        return view('messages.index', compact('users', 'conversations'));
    }

    public function show(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            abort(403, 'You cannot message yourself.');
        }

        if (!in_array($user->role, ['student', 'alumni'])) {
            abort(403, 'Only student and alumni messaging is allowed.');
        }

        Message::where('sender_id', $user->id)
            ->where('recipient_id', $authUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($query) use ($authUser, $user) {
                $query->where('sender_id', $authUser->id)
                    ->where('recipient_id', $user->id)
                    ->where('deleted_by_sender', false);
            })
            ->orWhere(function ($query) use ($authUser, $user) {
                $query->where('sender_id', $user->id)
                    ->where('recipient_id', $authUser->id)
                    ->where('deleted_by_receiver', false);
            })
            ->orderBy('created_at')
            ->get();

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            abort(403, 'You cannot message yourself.');
        }

        if (!in_array($user->role, ['student', 'alumni'])) {
            abort(403, 'Only student and alumni messaging is allowed.');
        }

        $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip,rar,txt,mp3,mp4', 'max:10240'],
        ]);

        if (!$request->filled('body') && !$request->hasFile('attachment')) {
            return back()->withErrors([
                'message' => 'Please write a message or attach a file.',
            ]);
        }

        $attachmentPath = null;
        $attachmentName = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            $attachmentPath = $file->store('messages/attachments', 'public');
            $attachmentName = $file->getClientOriginalName();
            $attachmentType = $file->getClientMimeType();
        }

        $data = [
    'sender_id' => $authUser->id,
    'recipient_id' => $user->id,
    'body' => $request->body,
    'message' => $request->body,
    
];

if (\Schema::hasColumn('messages', 'body')) {
    $data['body'] = $request->body;
}

if (\Schema::hasColumn('messages', 'message')) {
    $data['message'] = $request->body;
}

if (\Schema::hasColumn('messages', 'attachment')) {
    $data['attachment'] = $attachmentPath;
}

if (\Schema::hasColumn('messages', 'attachment_name')) {
    $data['attachment_name'] = $attachmentName;
}

if (\Schema::hasColumn('messages', 'attachment_type')) {
    $data['attachment_type'] = $attachmentType;
}

if (\Schema::hasColumn('messages', 'read_at')) {
    $data['read_at'] = null;
}

if (\Schema::hasColumn('messages', 'is_edited')) {
    $data['is_edited'] = false;
}

if (\Schema::hasColumn('messages', 'deleted_by_sender')) {
    $data['deleted_by_sender'] = false;
}

if (\Schema::hasColumn('messages', 'deleted_by_receiver')) {
    $data['deleted_by_receiver'] = false;
}

$finalData = [];

foreach ($data as $key => $value) {
    if (Schema::hasColumn('messages', $key)) {
        $finalData[$key] = $value;
    }
}
if (Schema::hasColumn('messages', 'content')) {
    $finalData['content'] = $request->body;
}

if (Schema::hasColumn('messages', 'created_at')) {
    $finalData['created_at'] = now();
}

if (Schema::hasColumn('messages', 'updated_at')) {
    $finalData['updated_at'] = now();
}

DB::table('messages')->insert($finalData);

        return back()->with('success', 'Message sent successfully.');
    }

    public function update(Request $request, Message $message)
    {
        $authUser = auth()->user();

        if ($message->sender_id !== $authUser->id) {
            abort(403, 'You can only edit your own messages.');
        }

        $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message->update([
            'body' => $request->body,
            'is_edited' => true,
        ]);

        return back()->with('success', 'Message updated successfully.');
    }

    public function destroy(Message $message)
    {
        $authUser = auth()->user();

        if ($message->sender_id === $authUser->id) {
            $message->update([
                'deleted_by_sender' => true,
            ]);
        } elseif ($message->recipient_id === $authUser->id) {
            $message->update([
                'deleted_by_receiver' => true,
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }

        if ($message->deleted_by_sender && $message->deleted_by_receiver) {
            if ($message->attachment && Storage::disk('public')->exists($message->attachment)) {
                Storage::disk('public')->delete($message->attachment);
            }

            $message->delete();
        }

        return back()->with('success', 'Message deleted successfully.');
    }
}