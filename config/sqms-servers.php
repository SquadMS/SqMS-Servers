<?php

return [
    'worker' => [
        'enabled'    => env('SQMS_WORKER_ENABLED', true),
        'auth_token' => env('SQMS_WORKER_AUTH_TOKEN', 'SECRETTOKEN'),
        'auth_ip'    => env('SQMS_WORKER_AUTH_IP', '127.0.0.1'),
        'host'       => env('SQMS_WORKER_HOST', 'http://localhost'),
        'port'       => env('SQMS_WORKER_PORT', 8080),
    ],

    'models' => [
        'server'  => \SquadMS\Servers\Models\Server::class,
        'banList' => \SquadMS\Servers\Models\BanList::class,
        'ban'     => \SquadMS\Servers\Models\Ban::class,
    ],

    'routes' => [
        'prefix'     => null,
        'middleware' => ['web'],
        'def'        => [
            'servers' => [
                'type'        => 'get',
                'name'        => 'servers',
                'path'        => 'servers',
                'middlewares' => [],
                'controller'  => \SquadMS\Servers\Http\Controllers\ServersController::class,
                'executor'    => 'index',
                'localized'   => true,
            ],
            'server' => [
                'type'        => 'get',
                'name'        => 'server',
                'path'        => 'servers/{server}',
                'middlewares' => [],
                'controller'  => \SquadMS\Servers\Http\Controllers\ServersController::class,
                'executor'    => 'show',
                'localized'   => true,
            ]
        ],
    ],

    'permissions' => [
        'module'      => 'sqms-servers',
        'definitions' => [
            'admin servers'                      => 'Grant access to the Servers overview',
            'admin servers manage'               => 'Grant access to managing Servers',
            'admin servers moderation'           => 'Grant access to moderating Servers',
            'admin servers moderation warn'      => 'Grant access to warning Players on Servers',
            'admin servers moderation kick'      => 'Grant access to kicking Players on Servers',
            'admin servers moderation ban'       => 'Grant access to banning Players on Servers',
            'admin servers moderation broadcast' => 'Grant access to sending Broadcasts on Servers',
        ],
    ],
];
