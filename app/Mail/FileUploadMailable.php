<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\UploadSession;

class FileUploadMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $session;
    public $viewName;
    public $data;

    public function __construct(string $viewName, UploadSession $session, array $data = [])
    {
        $this->viewName = $viewName;
        $this->session = $session;
        $this->data = $data;
    }

    public function build()
    {
        return $this->view($this->viewName)
            ->with(array_merge([
                'session' => $this->session,
            ], $this->data))
            ->subject($this->data['subject'] ?? 'DocShare Notification');
    }
}
