<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Request logger default
    |--------------------------------------------------------------------------
    |
    */

    'default' => env('REQUEST_LOG_CHANNEL', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Request logger channels
    |--------------------------------------------------------------------------
    |
    | Available Drivers: "mongo", "file"
    |
    */

    'channels' => [
        'mongo' => [
            'database' => [
                'level' => 'debug',
                // If it is empty, the default database will be selected.
                'database' => 'request',
                'collection' => 'request',
            ],
        ],

        'file' => [
            'path' => storage_path('logs/request.log'),
            'level' => 'debug',
            'days' => 14,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Request logger message format
    |--------------------------------------------------------------------------
    |
    | Available keyword:
    | {ip}: client ip
    | {scheme}: http scheme
    | {method}: http method
    | {route}: request route
    | {url}: full http url
    | {agent}: user agent
    | {status_code}: response status code
    | {response_time}: response time of program execution
    | {user}: current user
    | {db_count}: request sql count
    |
    */

    'message' => '{ip}-{scheme}-{url}-{agent}-{status_code}-{response_time}-{user}-{db_count}',

    /*
    |--------------------------------------------------------------------------
    | Message formatter
    |--------------------------------------------------------------------------
    |
    */

    'formatter' => \CrCms\Request\Logger\Formatter\LaravelFormatter::class,

    /*
    |--------------------------------------------------------------------------
    | Open the SQL query statistics log
    |--------------------------------------------------------------------------
    |
    | If you need accurate statistics, place the middleware in the first place of the request.
    |
    */

    'enable_sql_log' => false,
];