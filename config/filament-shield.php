<?php

use Luilliarcec\FilamentShield\Resources;

return [
    /*
     * Predefined resource to handle system roles, you can replace it with your own.
     */
    'resources' => [
        'role' => Resources\RoleResource::class
    ],

    /*
     * System roles, enable them to your liking.
     */
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

    /**
     * Default global permissions are defined here, however you are free to change
     * them from your filament resource, page or widget.
     */
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

    /**
     * The package uses a wildcard format with "-" instead of dots, due to the representation of objects
     * that livewire gives you, when using this format, the package discovers the segments
     * from the namespace of the filament resource, page or widget.
     *
     * {module}.{resource}.{action} => {module}-{resource}-{action}
     *
     * Filament
     *   - Resources
     *     - Security
     *       - RoleResource.php
     *
     * App\Filament\Resources\Security\RoleResource
     *
     * Ex.: security-role-view_any
     */

    'dont_modules' => [
        'src',
        'domain',
        'manages',
        'app',
        'filament',
        'resources',
        'pages',
        'widgets',
    ]
];
