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

    'consid' => env('APLICARE_CONSID', ''),

    'userkey' => env('APLICARE_USERKEY', ''),

    'secretkey' => env('APLICARE_SECRETKEY', ''),

    'sync_interval' => (int) env('APLICARE_SYNC_INTERVAL', 5),
];
