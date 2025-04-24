<?php

namespace App\Services;

use App\Enum\PermissionsEnum;
use App\Enum\RolesEnum;
use App\Jobs\SendUploadNotification;
use App\Models\UploadSession;
use App\Models\UploadedFile;
use App\Models\User;
use App\Notifications\FileUploadNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class FileUploadService
{
    public function handleUpload(array $validated, bool $requireAuth = false)
    {
        $user = Auth::guard('api')->user();
        $isGuest = $user === null;

        try {
            $session = $this->createUploadSession($validated, $user, $requireAuth);
            $uploadedFiles = $this->processUploadedFiles(request()->file('files'), $session);

            $this->dispatchNotification($session);

            return [
                'success' => true,
                'download_link' => $this->generateDownloadLink($session, $requireAuth),
                'expires_at' => $session->expires_at->toDateTimeString(),
                'files' => collect($uploadedFiles)->map(function ($file) {
                    return [
                        'original_name' => $file['original_name'],
                        'size' => $file['size'],
                        'mime_type' => $file['mime_type']
                    ];
                }),
                'user_type' => $isGuest ? 'guest' : 'authenticated',
                'password_instructions' => $session->password
                    ? 'Add ?password=your_password to the download URL'
                    : null,
                'is_protected' => $requireAuth,
            ];

        } catch (\Exception $e) {
            Log::error("Upload failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function generateDownloadLink(UploadSession $session, bool $requireAuth): string
    {
        if ($session->password) {
            $baseUrl = $requireAuth
                ? url("/api/v1/files/download/secure/{$session->token}")
                : url("/api/v1/files/download/{$session->token}");

            return $baseUrl . '?password=YOUR_PASSWORD';
        }

        if ($requireAuth) {
            return URL::temporarySignedRoute(
                'files.download.secure',
                $session->expires_at,
                ['token' => $session->token]
            );
        }

        return url("/api/v1/files/download/{$session->token}");
    }

    public function createUploadSession(array $validated, ?User $user, bool $requireAuth = false): UploadSession
    {
        $expiresIn = isset($validated['expires_in']) ? (int)$validated['expires_in'] : ($requireAuth ? 30 : 7);

        return UploadSession::create([
            'token' => Str::uuid(),
            'expires_at' => Carbon::now()->addDays($expiresIn),
            'email_to_notify' => $validated['email_to_notify'] ?? null,
            'password' => isset($validated['password']) ? bcrypt($validated['password']) : null,
            'user_id' => $user?->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_protected' => $requireAuth,
        ]);
    }

    public function processUploadedFiles(array $files, UploadSession $session): array
    {
        return array_map(function ($file) use ($session) {
            return $this->storeUploadedFile($file, $session);
        }, $files);
    }

    protected function storeUploadedFile($file, UploadSession $session): array
    {
        $disk = config('filesystems.uploads_disk', config('filesystems.default'));

        $storagePath = Storage::disk($disk)->putFile(
            "uploads/{$session->token}",
            $file,
            ['visibility' => $this->getFileVisibility()]
        );

        $uploadedFile = UploadedFile::create([
            'upload_session_id' => $session->id,
            'original_name' => $file->getClientOriginalName(),
            'storage_path' => $storagePath,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'checksum' => hash_file('sha256', $file->getRealPath()),
            'disk' => $disk,
        ]);

        return [
            'original_name' => $uploadedFile->original_name,
            'size' => $uploadedFile->size,
            'mime_type' => $uploadedFile->mime_type
        ];
    }

    protected function getFileVisibility(): string
    {
        return config('filesystems.uploads_visibility', 'private');
    }

    protected function dispatchNotification(UploadSession $session): void
    {
        if ($session->email_to_notify) {
            $user = User::where('email', $session->email_to_notify)->first();

            if ($user) {
                $user->notify(new FileUploadNotification($session));
            } else {
                Notification::route('mail', $session->email_to_notify)
                    ->notify(new FileUploadNotification($session));
            }
        }


        $admins = User::role([RolesEnum::Admin->value, RolesEnum::SuperAdmin->value])
            ->where('receive_notifications', true)
            ->get();

        Notification::send($admins, new FileUploadNotification($session, true));
    }
}
