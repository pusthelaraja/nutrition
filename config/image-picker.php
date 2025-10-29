<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Picker Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the image picker
    | components used throughout the admin panel.
    |
    */

    'defaults' => [
        'max_file_size' => 2048, // KB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'upload_path' => 'public',
        'thumbnail_size' => 300,
    ],

    'file_manager' => [
        'url' => '/laravel-filemanager',
        'type' => 'Images',
        'modal_size' => 'xl',
        'iframe_height' => 500,
    ],

    'components' => [
        'single_image' => [
            'preview_height' => 200,
            'placeholder_icon' => 'fas fa-image',
            'placeholder_text' => 'Click to select image',
        ],

        'multiple_images' => [
            'max_images' => 10,
            'grid_columns' => 'auto-fill',
            'grid_min_width' => 150,
            'preview_height' => 150,
            'placeholder_icon' => 'fas fa-images',
            'placeholder_text' => 'Click to select images',
        ],
    ],

    'responsive' => [
        'mobile' => [
            'grid_min_width' => 120,
            'preview_height' => 120,
            'modal_size' => 'lg',
            'iframe_height' => 400,
        ],

        'tablet' => [
            'grid_min_width' => 140,
            'preview_height' => 140,
            'modal_size' => 'xl',
            'iframe_height' => 450,
        ],
    ],

    'validation' => [
        'required_message' => 'Please select an image.',
        'max_images_message' => 'Maximum :max images allowed.',
        'file_size_message' => 'File size must be less than :size KB.',
        'file_type_message' => 'Only :types files are allowed.',
    ],

    'styling' => [
        'theme' => 'bootstrap',
        'border_color' => '#dee2e6',
        'hover_border_color' => '#007bff',
        'hover_background' => '#f8f9fa',
        'border_radius' => '8px',
        'box_shadow' => '0 2px 8px rgba(0,0,0,0.1)',
    ],
];
