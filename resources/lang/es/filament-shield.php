<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Nombre',
    'column.guard_name' => 'Guard',
    'column.roles' => 'Roles',
    'column.permissions' => 'Permisos',
    'column.updated_at' => 'Actualizado el',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Nombre',
    'field.guard_name' => 'Guard',
    'field.permissions' => 'Permisos',
    'field.select_all.name' => 'Seleccionar todos',
    'field.select_all.message' => 'Habilitar todos los permisos actualmente <span class="text-primary font-medium">habilitados</span> para este rol',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Seguridad',
    'nav.role.label' => 'Roles',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Rol',
    'resource.label.roles' => 'Roles',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Entidades',
    'resources' => 'Recursos',
    'widgets' => 'Widgets',
    'pages' => 'Páginas',
    'custom' => 'Permisos personalizados',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'Usted no tiene permiso de acceso',
    'update' => '¡La configuración del Shield ha sido renovada!',
    'generate' => '¡La configuración del Shield ha sido renovada y se han generado los permisos y/o políticas!',

    /*
    |--------------------------------------------------------------------------
    | Shield Replacements
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        'suffixes' => [
            'view' => 'Ver',
            'view_any' => 'Ver Algunos',
            'create' => 'Crear',
            'delete' => 'Eliminar',
            'delete_any' => 'Eliminar Algunos',
            'update' => 'Editar',
            'restore' => 'Restaurar',
            'restore_any' => 'Restaurar Algunos',
            'export' => 'Exportar',
        ],

        'resources' => [
            'activity' => 'Audit',
            'role' => 'Role',
            'user' => 'User',
        ],

        'pages' => [
            'backups' => 'Backups',
            'health_application' => 'Health application',
            'telescope' => 'Telescope',
        ],
    ],
];
