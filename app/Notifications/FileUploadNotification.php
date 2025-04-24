<?php

namespace App\Notifications;

use App\Models\UploadSession;
use App\Mail\FileUploadMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class FileUploadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public UploadSession $session,
        public bool $isAdminNotification = false
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        if ($this->isAdminNotification) {
            return $this->adminNotification($notifiable);
        }

        return $this->userNotification($notifiable);
    }

    protected function userNotification($notifiable)
    {
        return (new FileUploadMailable(
            'email.uploaded',
            $this->session,
            [
                'user' => $notifiable,
                'downloadUrl' => url("/download/{$this->session->token}"),
                'subject' => 'Your Files Are Ready to Share'
            ]
        ));
    }

    protected function adminNotification($notifiable)
    {
        $data = [
            'adminUrl' => url("/admin/uploads/{$this->session->id}"),
            'subject' => 'New File Upload - Requires Review'
        ];

        if ($notifiable instanceof \App\Models\User) {
            $data['recipient_name'] = $notifiable->name;
        } else {
            $data['recipient_name'] = 'Admin Team';
        }

        return (new FileUploadMailable(
            'email.uploaded_admin',
            $this->session,
            $data
        ));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => $this->isAdminNotification ? 'admin_upload_notification' : 'user_upload_notification',
            'session_id' => $this->session->id,
            'token' => $this->session->token,
            'file_count' => $this->session->files->count(),
            'expires_at' => $this->session->expires_at,
        ];
    }
}
