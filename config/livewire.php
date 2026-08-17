<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'components.layouts.app',
    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => 'file|max:524288|mimes:jpg,jpeg,png,webp,mp4,webm,mov|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime',
        'directory' => 'livewire-tmp',
        'middleware' => 'throttle:60,1',
        'preview_mimes' => ['mp4', 'webm', 'mov'],
        'max_upload_time' => 30,
        'cleanup' => true,
    ],
    'render_on_redirect' => false,
    'legacy_model_binding' => false,
    'inject_assets' => true,
    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#4f46e5',
    ],
    'inject_morph_markers' => true,
    'smart_wire_keys' => false,
    'pagination_theme' => 'tailwind',
];
