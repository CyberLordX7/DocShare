<?php

namespace App\Jobs;

use App\Models\UploadSession;
use App\Notifications\FileUploadNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendUploadNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public UploadSession $session) {}

    public function handle(): void
    {

        if ($this->session->email_to_notify) {
            Notification::route('mail', $this->session->email_to_notify)
                ->notify(new FileUploadNotification($this->session));
        }


        $adminEmail = config('app.admin_email'); 
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new FileUploadNotification($this->session, true));
        }
    }
}
