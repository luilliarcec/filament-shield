<?php

use Luilliarcec\FilamentShield\Resources;

return [
    'resources' => [
        'role' => Resources\RoleResource::class
    ],

    'roles' => [
        'super_admin' => [
            'enabled' => true,
            'role_name' => 'super_admin',
        ],

        'filament_user' => [
            'enabled' => false,
            'role_name' => 'filament_user',
        ],
    ],

    'suffixes' => [
        'resource' => [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
            'restore',
        ],
        'page' => 'view',
        'widget' => 'view',
    ],

    'dont_modules' => [
        'app',
        'src',
        'domain',
        'manages',
        'pages',
        'filament',
    ]
];
