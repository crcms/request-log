<?php

use CrCms\Log\MongoDBLogger;

return [

    'default' => env('REQUEST_LOG_CHANNEL', 'file'),

    'channels' => [
        'mongo' => [
            'driver' => 'custom',
            // If it is empty, the default app.env
            //'name' => env('APP_ENV', 'production'),
            'via' => MongoDBLogger::class,
            'database' => [
                'driver' => 'mongodb',
                // If it is empty, the default database will be selected.
                'database' => 'logger',
                'collection' => 'logger',
            ],
        ],

        'file' => [
            'driver' => 'daily',
            'path' => storage_path('logs/request.log'),
            'level' => 'debug',
            'days' => 14,
        ],
    ],

    'formatter' => '{date} {method} {url} {fullUrl} {agent}',
];