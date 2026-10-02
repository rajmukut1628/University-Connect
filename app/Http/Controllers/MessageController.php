<?php



namespace App\Http\Controllers;



use App\Services\EncryptedFileService;

use App\Models\Message;
use App\Models\Mentorship;

use App\Models\Notification;

use App\Models\User;

use App\Services\NotificationService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;



class MessageController extends Controller

{

    /**

     * Message inbox.

     */

    public function index()
    {
        $authUser = auth()->user();

        abort_unless(
            $authUser !== null && in_array($authUser->role, ['student', 'alumni'], true),
            403,
            'Messaging is available only for students and alumni.'
        );

        // Only currently accepted mentorship connections can appear in Messages.
        $approvedUserIds = $authUser->role === 'student'
            ? Mentorship::query()
                ->where('student_id', $authUser->id)
                ->where('status', 'accepted')
                ->pluck('mentor_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
            : Mentorship::query()
                ->where('mentor_id', $authUser->id)
                ->where('status', 'accepted')
                ->pluck('student_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

        // Existing conversations are also limited to accepted mentorships.
        $partnerIds = Message::query()
            ->where(function ($query) use ($authUser) {
                $query->where('sender_id', $authUser->id)
                    ->where('deleted_by_sender', false);
            })
            ->orWhere(function ($query) use ($authUser) {
                $query->where('recipient_id', $authUser->id)
                    ->where('deleted_by_receiver', false);
            })
            ->get()
            ->map(function ($message) use ($authUser) {
                return (int) $message->sender_id === (int) $authUser->id
                    ? (int) $message->recipient_id
                    : (int) $message->sender_id;
            })
            ->filter(fn ($id) => $approvedUserIds->contains((int) $id))
            ->unique()
            ->values();

        $conversations = User::query()
            ->whereIn('id', $partnerIds)
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->get()
            ->map(function ($user) use ($authUser) {
                $lastMessage = Message::query()
                    ->where(function ($query) use ($authUser, $user) {
                        $query->where('sender_id', $authUser->id)
                            ->where('recipient_id', $user->id)
                            ->where('deleted_by_sender', false);
                    })
                    ->orWhere(function ($query) use ($authUser, $user) {
                        $query->where('sender_id', $user->id)
                            ->where('recipient_id', $authUser->id)
                            ->where('deleted_by_receiver', false);
                    })
                    ->latest()
                    ->first();

                $unreadCount = Message::query()
                    ->where('sender_id', $user->id)
                    ->where('recipient_id', $authUser->id)
                    ->where('is_read', false)
                    ->where('deleted_by_receiver', false)
                    ->count();

                $user->last_message = $lastMessage;
                $user->unread_count = $unreadCount;

                return $user;
            })
            ->filter(fn ($user) => $user->last_message !== null)
            ->sortByDesc(fn ($user) => optional($user->last_message)->created_at)
            ->values();

        // New Chat/Search shows only accepted mentor/mentee connections.
        $users = User::query()
            ->whereIn('id', $approvedUserIds)
            ->where('id', '!=', $authUser->id)
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->orderBy('name')
            ->get();

        $preferredRole = $authUser->role === 'student' ? 'alumni' : 'student';

        $users = $users
            ->sort(function ($a, $b) use ($partnerIds) {
                $aHasConversation = $partnerIds->contains((int) $a->id);
                $bHasConversation = $partnerIds->contains((int) $b->id);

                if ($aHasConversation !== $bHasConversation) {
                    return $aHasConversation <=> $bHasConversation;
                }

                return strcasecmp((string) $a->name, (string) $b->name);
            })
            ->values();

        return view('messages.index', compact('users', 'conversations', 'preferredRole'));
    }


    /**

     * Open conversation.

     */

    public function show(User $user)

    {

        $authUser = auth()->user();



        /*

        |--------------------------------------------------------------------------

        | Access

        |--------------------------------------------------------------------------

        */



        abort_unless(

            in_array(

                $authUser->role,

                ['student', 'alumni'],

                true

            ),

            403

        );



        /*

        |--------------------------------------------------------------------------

        | Cannot Message Yourself

        |--------------------------------------------------------------------------

        */



        if (

            (int) $authUser->id ===

            (int) $user->id

        ) {

            abort(

                403,

                'You cannot message yourself.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | Only Student / Alumni

        |--------------------------------------------------------------------------

        */



        if (

            !in_array(

                $user->role,

                ['student', 'alumni'],

                true

            )

        ) {

            abort(

                403,

                'Only students and alumni can be messaged.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | Availability

        |--------------------------------------------------------------------------

        */



        if (

            !$user->is_active ||

            $user->is_blocked

        ) {

            abort(

                403,

                'This user is currently unavailable.'

            );

        }



        $this->ensureAcceptedMentorship($authUser, $user);

        /*

        |--------------------------------------------------------------------------

        | Mark Received Messages As Read

        |--------------------------------------------------------------------------

        */



        Message::query()

            ->where(

                'sender_id',

                $user->id

            )

            ->where(

                'recipient_id',

                $authUser->id

            )

            ->where(

                'is_read',

                false

            )

            ->update([

                'is_read' => true,

                'read_at' => now(),

            ]);



        /*

        |--------------------------------------------------------------------------

        | Mark Related Notifications Read

        |--------------------------------------------------------------------------

        */



        Notification::query()

            ->where(

                'user_id',

                $authUser->id

            )

            ->where(

                'actor_id',

                $user->id

            )

            ->where(

                'type',

                'message'

            )

            ->where(

                'is_read',

                false

            )

            ->update([

                'is_read' => true,

                'read_at' => now(),

            ]);



        /*

        |--------------------------------------------------------------------------

        | Conversation Messages

        |--------------------------------------------------------------------------

        */



        $messages = Message::query()

            ->where(function ($query) use (

                $authUser,

                $user

            ) {

                $query

                    ->where(

                        'sender_id',

                        $authUser->id

                    )

                    ->where(

                        'recipient_id',

                        $user->id

                    )

                    ->where(

                        'deleted_by_sender',

                        false

                    );

            })

            ->orWhere(function ($query) use (

                $authUser,

                $user

            ) {

                $query

                    ->where(

                        'sender_id',

                        $user->id

                    )

                    ->where(

                        'recipient_id',

                        $authUser->id

                    )

                    ->where(

                        'deleted_by_receiver',

                        false

                    );

            })

            ->orderBy('created_at')

            ->get();



        /*

        |--------------------------------------------------------------------------

        | View

        |--------------------------------------------------------------------------

        */



        return view(

            'messages.show',

            compact(

                'user',

                'messages'

            )

        );

    }



    /**

     * Send message.

     */

    public function store(

        Request $request,

        User $user

    ) {

        $authUser = auth()->user();



        /*

        |--------------------------------------------------------------------------

        | Access

        |--------------------------------------------------------------------------

        */



        abort_unless(

            in_array(

                $authUser->role,

                ['student', 'alumni'],

                true

            ),

            403

        );



        /*

        |--------------------------------------------------------------------------

        | Cannot Message Yourself

        |--------------------------------------------------------------------------

        */



        if (

            (int) $authUser->id ===

            (int) $user->id

        ) {

            abort(

                403,

                'You cannot message yourself.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | Only Student / Alumni

        |--------------------------------------------------------------------------

        */



        if (

            !in_array(

                $user->role,

                ['student', 'alumni'],

                true

            )

        ) {

            abort(

                403,

                'Only students and alumni can be messaged.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | User Availability

        |--------------------------------------------------------------------------

        */



        if (

            !$user->is_active ||

            $user->is_blocked

        ) {

            return back()->withErrors([

                'message' =>

                    'This user is currently unavailable.',

            ]);

        }



        $this->ensureAcceptedMentorship($authUser, $user);

        /*

        |--------------------------------------------------------------------------

        | Validation

        |--------------------------------------------------------------------------

        */



        $validated = $request->validate([

            'body' => [

                'nullable',

                'string',

                'max:5000',

            ],



            'attachment' => [

                'nullable',

                'file',

                'mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip,rar,txt,mp3,mp4',

                'max:10240',

            ],

        ]);



        /*

        |--------------------------------------------------------------------------

        | Message Body

        |--------------------------------------------------------------------------

        */



        $body = trim(

            (string) (

                $validated['body'] ?? ''

            )

        );



        if (

            $body === '' &&

            !$request->hasFile('attachment')

        ) {

            return back()

                ->withInput()

                ->withErrors([

                    'message' =>

                        'Please write a message or attach a file.',

                ]);

        }



        /*

        |--------------------------------------------------------------------------

        | Attachment

        |--------------------------------------------------------------------------

        */



        $attachmentPath = null;

        $attachmentName = null;

        $attachmentType = null;



        if ($request->hasFile('attachment')) {



            $file =

                $request->file('attachment');



            $attachmentPath =

          app(EncryptedFileService::class)

         ->store(

            $file,

            'message-attachments'

        );



            $attachmentName =

                $file->getClientOriginalName();



            $attachmentType =

                $file->getClientMimeType();

        }



        /*

        |--------------------------------------------------------------------------

        | Save Message

        |--------------------------------------------------------------------------

        */



        $message = DB::transaction(

            function () use (

                $authUser,

                $user,

                $body,

                $attachmentPath,

                $attachmentName,

                $attachmentType

            ) {



                return Message::create([

                    'sender_id' =>

                        $authUser->id,



                    'recipient_id' =>

                        $user->id,



                   'content' => $body,



                    'attachment' =>

                        $attachmentPath,



                    'attachment_name' =>

                        $attachmentName,



                    'attachment_type' =>

                        $attachmentType,



                    'is_read' =>

                        false,



                    'read_at' =>

                        null,



                    'is_edited' =>

                        false,



                    'deleted_by_sender' =>

                        false,



                    'deleted_by_receiver' =>

                        false,

                ]);

            }

        );



        /*

        |--------------------------------------------------------------------------

        | Notification -> Receiver

        |--------------------------------------------------------------------------

        */



        $preview =

            $body !== ''

                ? str($body)->limit(80)

                : 'Sent you an attachment.';



        NotificationService::send(

            $user,

            'message',

            'New Message',

            $authUser->name .

                ': ' .

                $preview,

            route(

                'messages.show',

                $authUser

            ),

            'medium',

            $message,

            $authUser

        );



        return back();

    }



    /**

     * Edit own message.

     */

    public function update(

        Request $request,

        Message $message

    ) {

        $authUser = auth()->user();



        /*

        |--------------------------------------------------------------------------

        | Ownership

        |--------------------------------------------------------------------------

        */



        if (

            (int) $message->sender_id !==

            (int) $authUser->id

        ) {

            abort(

                403,

                'You can only edit your own messages.'

            );

        }



        if ($message->deleted_by_sender) {

            abort(

                404,

                'Message not found.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | Validation

        |--------------------------------------------------------------------------

        */



        $validated = $request->validate([

            'body' => [

                'required',

                'string',

                'max:5000',

            ],

        ]);



        /*

        |--------------------------------------------------------------------------

        | Update

        |--------------------------------------------------------------------------

        */



        $message->update([

            'content' =>

                trim(

                    $validated['body']

                ),



            'is_edited' =>

                true,

        ]);



        return back()->with(

            'success',

            'Message updated.'

        );

    }



    /**

     * Delete message for current user.

     */

    public function destroy(

        Message $message

    ) {

        $authUser = auth()->user();



        /*

        |--------------------------------------------------------------------------

        | Soft Delete For Current User

        |--------------------------------------------------------------------------

        */



        if (

            (int) $message->sender_id ===

            (int) $authUser->id

        ) {



            $message->update([

                'deleted_by_sender' =>

                    true,

            ]);



        } elseif (

            (int) $message->recipient_id ===

            (int) $authUser->id

        ) {



            $message->update([

                'deleted_by_receiver' =>

                    true,

            ]);



        } else {



            abort(

                403,

                'Unauthorized action.'

            );

        }



        $message->refresh();



        /*

        |--------------------------------------------------------------------------

        | Permanently Delete When Both Sides Removed It

        |--------------------------------------------------------------------------

        */



        if (

            $message->deleted_by_sender &&

            $message->deleted_by_receiver

        ) {



            if (

                $message->attachment &&

                Storage::disk('public')->exists(

                    $message->attachment

                )

            ) {

                Storage::disk('public')->delete(

                    $message->attachment

                );

            }



            $message->delete();

        }



        return back()->with(

            'success',

            'Message deleted.'

        );

    }

    /**
     * Messaging is allowed only while the mentorship is accepted.
     */
    private function ensureAcceptedMentorship(User $authUser, User $otherUser): void
    {
        $isApprovedConnection = false;

        if ($authUser->role === 'student' && $otherUser->role === 'alumni') {
            $isApprovedConnection = Mentorship::query()
                ->where('student_id', $authUser->id)
                ->where('mentor_id', $otherUser->id)
                ->where('status', 'accepted')
                ->exists();
        } elseif ($authUser->role === 'alumni' && $otherUser->role === 'student') {
            $isApprovedConnection = Mentorship::query()
                ->where('mentor_id', $authUser->id)
                ->where('student_id', $otherUser->id)
                ->where('status', 'accepted')
                ->exists();
        }

        abort_unless(
            $isApprovedConnection,
            403,
            'Messaging is available only between approved mentorship connections.'
        );
    }

}
