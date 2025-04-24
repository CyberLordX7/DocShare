<?php

namespace App\Services;

use App\Models\UploadSession;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Enum\PermissionsEnum;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\URL;

class FileDownloadService
{
    public function handleDownload(string $token, bool $requireAuth = false): StreamedResponse|BinaryFileResponse
    {
        $session = UploadSession::with('files')
            ->where('token', $token)
            ->firstOrFail();

        $this->validateDownload($session, $requireAuth);

        $session->increment('download_count');
        $session->update(['last_download_at' => now()]);

        return $session->files->count() === 1 ? $this->downloadSingleFile($session->files->first())  : $this->downloadAsZip($session);
    }

    protected function validateDownload(UploadSession $session, bool $requireAuth): void
    {
        if ($session->isExpired()) {
            abort(410, 'This download link has expired');
        }

        if ($requireAuth && !Gate::allows(PermissionsEnum::DownloadFiles->value, $session)) {
            abort(403, 'You do not have permission to download these files');
        }

        if ($session->password) {
            if (!request()->has('password')) {
                abort(403, json_encode([
                    'success' => false,
                    'message' => 'Password required',
                    'instructions' => 'Add ?password=your_password to the URL'
                ]));
            }

            if (!Hash::check(request('password'), $session->password)) {
                abort(403, json_encode([
                    'success' => false,
                    'message' => 'Invalid password'
                ]));
            }
        }
    }

    public function downloadSingleFile(UploadedFile $file): StreamedResponse
    {
        $file->increment('download_count');
        $disk = $file->disk ?? config('filesystems.default');

        $stream = Storage::disk($disk)->readStream($file->storage_path);

        return new StreamedResponse(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'attachment; filename="' . $file->original_name . '"',
        ]);
    }

    public function downloadAsZip(UploadSession $session): StreamedResponse
    {
        $zipFileName = "files-{$session->token}.zip";

        return response()->streamDownload(function () use ($session) {
            $zip = new \ZipArchive();
            $zip->open('php://output', \ZipArchive::CREATE);

            foreach ($session->files as $file) {
                $file->increment('download_count');
                $disk = $file->disk ?? config('filesystems.default');

                if (Storage::disk($disk)->exists($file->storage_path)) {
                    $zip->addFile(
                        Storage::disk($disk)->path($file->storage_path),
                        $file->original_name
                    );
                }
            }

            $zip->close();
        }, $zipFileName, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"'
        ]);
    }

    public function authenticateDownload(string $token, string $password): array
    {
        $session = UploadSession::where('token', $token)->firstOrFail();

        if (!Hash::check($password, $session->password)) {
            throw new \Exception('Invalid password', 403);
        }

        return [
            'download_url' => URL::temporarySignedRoute(
                'files.download.secure',
                now()->addMinutes(15),
                ['token' => $token]
            ),
            'expires_at' => now()->addMinutes(15)->toDateTimeString()
        ];
    }
}
