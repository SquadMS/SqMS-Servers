<?php

return [
    'worker' => [
        'auth_token' => env('SQMS_WORKER_AUTH_TOKEN', 'SECRETTOKEN'),
        'auth_ip'    => env('SQMS_WORKER_AUTH_IP', '127.0.0.1'),
        'host'       => env('SQMS_WORKER_HOST', 'http://127.0.0.1'),
        'port'       => env('SQMS_WORKER_PORT', 8080),
    ],

    'models' => [
        'server' => \SquadMS\Servers\Models\Server::class,
    ],

    'routes' => [
        'prefix' => null,
        'middleware' => ['web'],
        'def' => [
            'servers' => [
                'type' => 'get',
                'name' => 'servers',
                'path' => 'servers',
                'middlewares' => [],
                'controller' => \SquadMS\Servers\Http\Controllers\ServersController::class,
                'executor' => 'index',
                'localized' => true,
            ],
            'server' => [
                'type' => 'get',
                'name' => 'server',
                'path' => 'servers/{server}',
                'middlewares' => [],
                'controller' => \SquadMS\Servers\Http\Controllers\ServersController::class,
                'executor' => 'show',
                'localized' => true,
            ],
            'admin-servers' => [
                'type' => 'get',
                'name' => 'admin.servers',
                'path' => 'admin/servers',
                'middlewares' => ['auth', 'can:sqms admin', 'can:sqms-servers admin servers'],
                'controller' => \SquadMS\Servers\Admin\Http\Controllers\ServersController::class,
                'executor' => 'index',
                'localized' => false,
            ]
        ]
    ],

    'permissions' => [
        'module' => 'sqms-servers',
        'definitions' => [
            'admin servers' => 'Grant access to the Server Management',
        ]
    ]
];