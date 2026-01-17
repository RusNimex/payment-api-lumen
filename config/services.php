<?php

return [
    'token_issuer' => [
        'service_name' => env('SERVICE_NAME', 'orders'),
        'service_secret' => env('SERVICE_SECRET', 'demo-secret'),
        'ttl_seconds' => (int) env('SERVICE_TOKEN_TTL', 86400),
        'scopes' => env('SERVICE_TOKEN_SCOPES', 'pay.create'),
        'redis_prefix' => env('SERVICE_TOKEN_REDIS_PREFIX', 'svc_token:'),
    ],
];
