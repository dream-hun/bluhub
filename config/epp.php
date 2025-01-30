<?php

return [
    /*
    |--------------------------------------------------------------------------
    | EPP Server Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the EPP client
    | connection to the registry server.
    |
    */

    // EPP Server hostname
    'server' => env('EPP_SERVER', 'epp.registry.example.com'),

    // EPP Server port (default is usually 700)
    'port' => env('EPP_PORT', 700),

    // EPP Server credentials
    'username' => env('EPP_USERNAME', ''),
    'password' => env('EPP_PASSWORD', ''),

    // SSL Configuration
    'ssl_enabled' => env('EPP_SSL', 'on'),
    'certificate_path' =>env('EPP_CERTIFICATE_PATH', storage_path('app/certificate/test.pem')),
];
