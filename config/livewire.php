<?php

return [
    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => 'file|max:524288|mimes:jpg,jpeg,png,webp,mp4,webm,mov|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime',
        'preview_mimes' => [
            'gif',
            'bmp',
            'svg',
            'png',
            'jpg',
            'jpeg',
            'webp',
            'mp4',
            'webm',
            'mov',
        ],
        'max_upload_time' => 30,
    ],
];
