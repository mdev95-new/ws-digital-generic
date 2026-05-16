<?php

return [
    'default' => env('DB_CONNECTION', 'pgsql'),
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'db'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'ws_digital_generic'),
            'username' => env('DB_USERNAME', 'wsdg'),
            'password' => env('DB_PASSWORD', 'wsdg_secret'),
            'charset' => 'utf8',
            'prefix' => '',
            'search_path' => 'public',
        ],
    ],
    'migrations' => 'migrations',
];