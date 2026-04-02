<?php

return [
    'enabled'       => env('MJKN_INTEGRATION_ENABLED', false),
    'base_url'      => env('MJKN_API_BASE_URL'),
    'api_key'       => env('MJKN_API_KEY'),
    'sync_interval' => env('MJKN_SYNC_INTERVAL_MINUTES', 5),
];
