<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'superstockez' => [
            'driver' => 'session',
            'provider' => 'superstockez_table',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admin_table',
        ],

        'stockez' => [
            'driver' => 'session',
            'provider' => 'stockez_table',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'superstockez_table' => [
            'driver' => 'eloquent',
            'model' => App\Models\superstockez::class,
        ],

        'admin_table' => [
            'driver' => 'eloquent',
            'model' => App\Models\admin::class,
        ],

        'stockez_table' => [
            'driver' => 'eloquent',
            'model' => App\Models\stockez::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
