<?php

return [
    'storage' => [
        'disk' => env('UPLOADS_DISK', env('FILESYSTEM_DISK', 'local')),
        'visibility' => env('UPLOADS_VISIBILITY', 'private'),
        'root_path' => 'uploads',
    ],

    'limits' => [
        'guest' => [
            'max_files' => 3,
            'max_total_size' => 300000, // 300MB
            'max_expiry_days' => 7,
        ],
        'auth' => [
            'max_files' => 20,
            'max_total_size' => 1024000, // 1GB
            'max_expiry_days' => 365,
        ],
    ],

    'allowed_mime_types' => [
        'jpg', 'jpeg', 'png', 'gif', 'pdf',
        'doc', 'docx', 'xls', 'xlsx', 'ppt',
        'pptx', 'txt', 'csv', 'zip', 'rar', '7z'
    ],
];
