<?php

return [
    'base_url' => env('TALO_BASE_URL', 'https://sandbox-api.talo.com.ar'),
    'user_id' => env('TALO_USER_ID'),
    'client_id' => env('TALO_CLIENT_ID'),
    'client_secret' => env('TALO_CLIENT_SECRET'),

    'token_cache_key' => env('TALO_TOKEN_CACHE_KEY', 'talo.access_token'),
    'token_ttl_seconds' => (int) env('TALO_TOKEN_TTL_SECONDS', 3300),

    'timeout' => (int) env('TALO_TIMEOUT', 30),
    'connect_timeout' => (int) env('TALO_CONNECT_TIMEOUT', 10),

    'webhook_enabled' => (bool) env('TALO_WEBHOOK_ENABLED', true),
    'webhook_route' => env('TALO_WEBHOOK_ROUTE', '/webhooks/talo'),
    'webhook_secret' => env('TALO_WEBHOOK_SECRET'),
];
