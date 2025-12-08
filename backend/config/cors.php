<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
    ],

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | 🚀 DEVELOPMENT MODE - WILDCARD CORS
    |--------------------------------------------------------------------------
    |
    | ✅ KEUNTUNGAN: Tidak perlu tambah port baru setiap Flutter berganti port
    | ✅ OTOMATIS: Semua localhost:* ports diizinkan
    | ✅ FLEKSIBEL: Mendukung port dinamis Flutter development server
    |
    | 🔒 KEAMANAN: Hanya untuk development environment!
    | ⚠️  JANGAN GUNAKAN DI PRODUCTION!
    |
    | 📝 CARA KERJA:
    | - Pattern `/^http:\/\/localhost:\d+$/` = semua localhost ports
    | - Pattern `/^http:\/\/127\.0\.0\.1:\d+$/` = semua 127.0.0.1 ports
    | - Mendukung port: 3000, 5173, 8080, 58267, dll.
    |
    */

    // DEVELOPMENT: Empty array + patterns untuk semua localhost ports
    'allowed_origins' => [],

    'allowed_origins_patterns' => [
        '/^http:\/\/localhost:\d+$/',     // All localhost ports (localhost:3000, localhost:8080, etc.)
        '/^http:\/\/127\.0\.0\.1:\d+$/', // All 127.0.0.1 ports (127.0.0.1:3000, 127.0.0.1:8080, etc.)
    ],

    /*
    |--------------------------------------------------------------------------
    | PRODUCTION CONFIGURATION EXAMPLE
    |--------------------------------------------------------------------------
    |
    | Untuk production, gunakan konfigurasi berikut:
    |
    'allowed_origins' => [
        'https://yourdomain.com',
        'https://www.yourdomain.com',
    ],
    'allowed_origins_patterns' => [], // Kosongkan untuk production
    |
    |--------------------------------------------------------------------------
    */

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
