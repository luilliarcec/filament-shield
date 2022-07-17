<?php

use BezhanSalleh\FilamentShield\Resources;

return [
    'resources' => [
        'roles' => Resources\RoleResource::class
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
            'view',
            'view_any',
            'create',
            'delete',
            'delete_any',
            'update',
            'restore',
        ],
        'page' => 'view',
        'widget' => 'view',
    ],
];
