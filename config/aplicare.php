<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Aplicare BPJS Kesehatan API Integration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi dengan API Aplicare BPJS Kesehatan.
    | Set APLICARE_ENABLED=true di .env untuk mengaktifkan sinkronisasi otomatis.
    |
    */

    'enabled' => env('APLICARE_ENABLED', false),

    'url' => env('APLICARE_URL', 'https://new-api.bpjs-kesehatan.go.id/aplicaresws'),

    'kodeppk' => env('APLICARE_KODEPPK', ''),

    'consid' => env('APLICARE_CONSID', ''),

    'userkey' => env('APLICARE_USERKEY', ''),

    'secretkey' => env('APLICARE_SECRETKEY', ''),

    'sync_interval' => (int) env('APLICARE_SYNC_INTERVAL', 5),

    'read_start' => (int) env('APLICARE_READ_START', 1),

    'read_limit' => (int) env('APLICARE_READ_LIMIT', 100),

    'timeout' => (int) env('APLICARE_TIMEOUT', 20),

    'connect_timeout' => (int) env('APLICARE_CONNECT_TIMEOUT', 10),

    'force_ipv4' => (bool) env('APLICARE_FORCE_IPV4', true),

    'user_agent' => env('APLICARE_USER_AGENT', 'BED-MONITORING-V2/1.0'),
];
