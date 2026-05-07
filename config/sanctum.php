<?php

use Laravel\Sanctum\Sanctum;

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1,10.0.2.2,192.168.*,*.test',
        Sanctum::currentApplicationUrlWithPort()
))),
    'guard' => ['web'],
    'expiration' => env('SANCTUM_EXPIRATION', null),
    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', 'healthsys_'),
    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
        'validate_csrf_token' => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ],
];