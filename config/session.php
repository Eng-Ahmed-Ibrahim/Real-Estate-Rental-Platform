<?php

use Illuminate\Support\Str;

return [


    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 480),
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
    'path' => '/',
'domain' => env('SESSION_DOMAIN', '.dev2.tqnia.me'),
'secure' => env('SESSION_SECURE_COOKIE', false),  // Set to true in production with HTTPS
'same_site' => env('SESSION_SAME_SITE', 'lax'),

    'http_only' => true,
    'partitioned' => false,

];
