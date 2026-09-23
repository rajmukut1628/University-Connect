<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class EncryptedFileService
{
    /**
     * Private disk.
     */
    private string $disk = 'local';

    /**
     * Root encrypted directory.
     */
    private string $root = 'private/encrypted';

    /**
     * Encrypt a newly uploaded file.
     */
    public function store(
        UploadedFile $file,
        string $directory
    ): string {
        $directory = trim($directory, '/');

        $bytes = file_get_contents(
            $file->getRealPath()
        );

        if ($bytes === false) {
            throw new RuntimeException(
                'Unable to read uploaded file.'
            );
        }

        $encrypted = Crypt::encryptString(
            base64_encode($bytes)
        );

        $path =
            $this->root . '/' .
            $directory . '/' .
            Str::uuid() . '.enc';

        Storage::disk($this->disk)->put(
            $path,
            $encrypted
        );

        return $path;
    }

    /**
     * Encrypt an existing file from public storage.
     *
     * Used for migration of old uploads.
     */
    public function encryptExistingPublic(
        string $oldPath,
        string $directory
    ): string {
        $oldPath = ltrim($oldPath, '/');

        if (
            !Storage::disk('public')
                ->exists($oldPath)
        ) {
            throw new RuntimeException(
                "Public file not found: {$oldPath}"
            );
        }

        $bytes = Storage::disk('public')
            ->get($oldPath);

        $encrypted = Crypt::encryptString(
            base64_encode($bytes)
        );

        $directory = trim(
            $directory,
            '/'
        );

        $newPath =
            $this->root . '/' .
            $directory . '/' .
            Str::uuid() . '.enc';

        Storage::disk($this->disk)->put(
            $newPath,
            $encrypted
        );

        return $newPath;
    }

    /**
     * Decrypt file and return raw bytes.
     */
    public function get(string $path): string
    {
        if (!$this->isEncryptedPath($path)) {
            throw new RuntimeException(
                'Invalid encrypted file path.'
            );
        }

        if (
            !Storage::disk($this->disk)
                ->exists($path)
        ) {
            throw new RuntimeException(
                'Encrypted file does not exist.'
            );
        }

        $encrypted = Storage::disk($this->disk)
            ->get($path);

        $decoded = Crypt::decryptString(
            $encrypted
        );

        $bytes = base64_decode(
            $decoded,
            true
        );

        if ($bytes === false) {
            throw new RuntimeException(
                'Unable to decode encrypted file.'
            );
        }

        return $bytes;
    }

    /**
     * Delete encrypted file.
     */
    public function delete(
        ?string $path
    ): void {
        if (!$path) {
            return;
        }

        if ($this->isEncryptedPath($path)) {
            Storage::disk($this->disk)
                ->delete($path);

            return;
        }

        /*
         * Backward compatibility:
         * old public files can still be deleted.
         */
        if (
            Storage::disk('public')
                ->exists($path)
        ) {
            Storage::disk('public')
                ->delete($path);
        }
    }

    /**
     * Check whether database path belongs
     * to the new encrypted storage.
     */
    public function isEncryptedPath(
        ?string $path
    ): bool {
        if (!$path) {
            return false;
        }

        return str_starts_with(
            ltrim($path, '/'),
            $this->root . '/'
        );
    }

    /**
     * Delete an old public original after
     * successful migration.
     */
    public function deletePublicOriginal(
        ?string $path
    ): void {
        if (!$path) {
            return;
        }

        $path = ltrim($path, '/');

        if (
            Storage::disk('public')
                ->exists($path)
        ) {
            Storage::disk('public')
                ->delete($path);
        }
    }
}