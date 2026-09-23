<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationManualPayment;
use App\Models\Message;
use App\Models\ResumeAnalysis;
use App\Models\User;
use App\Services\EncryptedFileService;
use Illuminate\Http\Response;

class SecureFileController extends Controller
{
    public function __construct(
        private EncryptedFileService $files
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Image
    |--------------------------------------------------------------------------
    */

    public function profileImage(
        User $user
    ): Response {
        abort_unless(
            auth()->check(),
            403
        );

        abort_if(
            empty($user->profile_image),
            404
        );

        return $this->serve(
            $user->profile_image,
            'image/jpeg',
            'profile-image'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Donation Campaign Image
    |--------------------------------------------------------------------------
    */

    public function donationImage(
        Donation $donation
    ): Response {
        abort_unless(
            auth()->check(),
            403
        );

        abort_if(
            empty($donation->image),
            404
        );

        return $this->serve(
            $donation->image,
            'image/jpeg',
            'donation-image'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Donation Payment Screenshot
    |--------------------------------------------------------------------------
    */

    public function paymentScreenshot(
        DonationManualPayment $payment
    ): Response {
        $user = auth()->user();

        abort_unless(
            $user,
            403
        );

        $allowed =
            (int) $payment->user_id ===
                (int) $user->id
            ||
            in_array(
                $user->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            );

        abort_unless(
            $allowed,
            403
        );

        abort_if(
            empty($payment->screenshot),
            404
        );

        return $this->serve(
            $payment->screenshot,
            'image/jpeg',
            'payment-proof'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Message Attachment
    |--------------------------------------------------------------------------
    */

    public function messageAttachment(
        Message $message
    ): Response {
        $user = auth()->user();

        abort_unless(
            $user,
            403
        );

        $allowed =
            (int) $message->sender_id ===
                (int) $user->id
            ||
            (int) $message->recipient_id ===
                (int) $user->id;

        abort_unless(
            $allowed,
            403
        );

        abort_if(
            empty($message->attachment),
            404
        );

        $mime =
            $message->attachment_type
            ?: 'application/octet-stream';

        $name =
            $message->attachment_name
            ?: 'attachment';

        return $this->serve(
            $message->attachment,
            $mime,
            $name
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Resume
    |--------------------------------------------------------------------------
    */

    public function resume(
        ResumeAnalysis $resumeAnalysis
    ): Response {
        $user = auth()->user();

        abort_unless(
            $user &&
            (int) $resumeAnalysis->user_id ===
                (int) $user->id,
            403
        );

        abort_if(
            empty($resumeAnalysis->file_path),
            404
        );

        $extension = strtolower(
            $resumeAnalysis->file_type ?? ''
        );

        $mime = match ($extension) {
            'pdf' =>
                'application/pdf',

            'doc' =>
                'application/msword',

            'docx' =>
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

            'txt' =>
                'text/plain',

            default =>
                'application/octet-stream',
        };

        return $this->serve(
            $resumeAnalysis->file_path,
            $mime,
            $resumeAnalysis->original_file_name
                ?: 'resume'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Serve / Decrypt
    |--------------------------------------------------------------------------
    */

    private function serve(
        string $path,
        string $mime,
        string $filename
    ): Response {
        /*
         * Temporary backward compatibility.
         *
         * Existing public files continue working
         * until migration is completed.
         */
        if (
            !$this->files
                ->isEncryptedPath($path)
        ) {
            abort_unless(
                \Storage::disk('public')
                    ->exists($path),
                404
            );

            $bytes =
                \Storage::disk('public')
                    ->get($path);

            return response(
                $bytes,
                200,
                [
                    'Content-Type' =>
                        $mime,

                    'Content-Disposition' =>
                        'inline; filename="' .
                        addslashes($filename) .
                        '"',

                    'Cache-Control' =>
                        'private, no-store, max-age=0',

                    'Pragma' =>
                        'no-cache',

                    'X-Content-Type-Options' =>
                        'nosniff',
                ]
            );
        }

        $bytes = $this->files->get(
            $path
        );

        return response(
            $bytes,
            200,
            [
                'Content-Type' =>
                    $mime,

                'Content-Disposition' =>
                    'inline; filename="' .
                    addslashes($filename) .
                    '"',

                'Cache-Control' =>
                    'private, no-store, max-age=0',

                'Pragma' =>
                    'no-cache',

                'X-Content-Type-Options' =>
                    'nosniff',
            ]
        );
    }
}