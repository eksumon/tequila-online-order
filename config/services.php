<?php

return [
    'tequilapos' => [
        'base_url' => env('TEQUILAPOS_BASE_URL', 'https://tequilapos.net/api'),
        'image_base' => env('TEQUILAPOS_IMAGE_BASE', 'https://tequilapos.net/'),
        'auth_key' => env('TEQUILAPOS_AUTH_KEY', '4446760d-1aae-486d-bc24-d175eb934395'),
        'default_restaurant_id' => (int) env('TEQUILAPOS_DEFAULT_RESTAURANT_ID', 43),
    ],
];
