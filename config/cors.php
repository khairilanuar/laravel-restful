<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => [
        // '*',
        'POST',
        'GET',
        'OPTIONS',
        'PUT',
        'PATCH',
        'DELETE',
    ],

    'allowed_origins' => [
        // NOTE: should only allow your trusted origin endpoint here
        // 'https://*.yourdomain.com',
        // '*',
        config('app.url'),
        config('app.client_url'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        // '*',
        'Content-Type',
        'X-Auth-Token',
        'Origin',
        'Authorization',

        // crypton headers
        'x-request-encrypted',
        'x-response-encrypted',
    ],

    'exposed_headers' => [
        'Cache-Control',
        'Content-Language',
        'Content-Type',
        'Expires',
        'Last-Modified',
        'Pragma',
    ],

    'max_age' => 60 * 60 * 24,

    'supports_credentials' => true,
];
