<?php

namespace App\Console\Commands;

use App\Models\UploadSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanExpiredUploads extends Command
{
    protected $signature = 'clean:expired-uploads
    {--dry-run : Checking files that would be deleted without actually deleting them}';

    protected $description = 'Delete expired file uploads and their related records';

    public function handle()
    {
        $expiredSessions = UploadSession::with('files')
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredSessions->isEmpty()) {
            $this->info('No expired uploads found.');
            return 0;
        }

        if ($this->option('dry-run')) {
            $this->info('[DRY RUN] Would delete '.$expiredSessions->count().' expired sessions:');

            $this->table(
                ['Session ID', 'Files Count', 'Expired At'],
                $expiredSessions->map(fn($s) => [
                    $s->id,
                    $s->files->count(),
                    $s->expires_at->format('Y-m-d H:i:s')
                ])
            );

            return 0;
        }

        $deletedCount = 0;

        foreach ($expiredSessions as $session) {
            foreach ($session->files as $file) {
                Storage::delete($file->path);
            }

            $session->files()->delete();
            $session->delete();

            $deletedCount++;

            $this->info("Deleted session {$session->id} with {$session->files->count()} files");
        }

        $this->info("Successfully cleaned up {$deletedCount} expired upload sessions.");
        return 0;
    }
}
