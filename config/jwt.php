<?php

declare(strict_types=1);

return [
    'expiration' => env('JWT_EXPIRATION', 3600), // in minutes,
    'secret_key' => env('JWT_SECRET')
];
