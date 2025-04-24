<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FileUploadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requireAuth = $this->routeIs('secureStore');
        $limits = config("files.limits." . ($requireAuth ? 'auth' : 'guest'));

        return [
            'files.*' => [
                'required',
                'file',
                "max:{$limits['max_total_size']}",
                'mimes:' . implode(',', config('files.allowed_mime_types')),
            ],
            'expires_in' => "sometimes|numeric|min:1|max:{$limits['max_expiry_days']}",
            'email_to_notify' => 'sometimes|email',
            'password' => 'sometimes|string|min:6|max:128'
        ];
    }

    public function validateGuestRestrictions()
    {
        if (!Auth::guard('api')->check()) {
            $limits = config('files.limits.guest');
            $files = $this->file('files', []);

            if (count($files) > $limits['max_files']) {
                abort(422, "Guests can upload maximum {$limits['max_files']} files");
            }

            $totalSize = array_reduce($files, function($carry, $file) {
                return $carry + $file->getSize();
            }, 0);

            if ($totalSize > $limits['max_total_size']) {
                abort(422, "Total file size exceeds maximum of " .
                    ($limits['max_total_size'] / 1000000) . "MB for guests");
            }
        }
    }
}
