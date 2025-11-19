<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    */

    'merchant_id'    => env('MIDTRANS_MERCHANT_ID', ''),
    'client_key'     => env('MIDTRANS_CLIENT_KEY', ''),
    'server_key'     => env('MIDTRANS_SERVER_KEY', ''),
    'is_production'  => env('MIDTRANS_IS_PRODUCTION', false),

    // Snap Redirect / Snap Popup
    'snap_url' => env('MIDTRANS_IS_PRODUCTION')
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions',

    // Core API URL
    'api_url' => env('MIDTRANS_IS_PRODUCTION')
        ? 'https://api.midtrans.com/v2/'
        : 'https://api.sandbox.midtrans.com/v2/',

    // Callback notification URL
    'callback_url' => env('MIDTRANS_CALLBACK_URL', ''),

    // Extra options
    'sanitized' => true,
    '3ds'       => true,
];
