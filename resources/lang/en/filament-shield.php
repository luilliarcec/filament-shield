<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Name',
    'column.guard_name' => 'Guard Name',
    'column.roles' => 'Roles',
    'column.permissions' => 'Permissions',
    'column.updated_at' => 'Updated At',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Name',
    'field.guard_name' => 'Guard Name',
    'field.permissions' => 'Permissions',
    'field.select_all.name' => 'Select All',
    'field.select_all.message' => 'Enable all Permissions currently <span class="text-primary font-medium">Enabled</span> for this role',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Security',
    'nav.role.label' => 'Roles',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Role',
    'resource.label.roles' => 'Roles',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Permissions',
    'fieldset' => 'Permissions',
    'tabs' => [
        'resources' => 'Resources',
        'widgets' => 'Widgets',
        'pages' => 'Pages',
        'custom' => 'Custom Permissions',
        'auth' => 'Security',
    ],

    /*
    |--------------------------------------------------------------------------
    | Toggles & Checkboxes
    |--------------------------------------------------------------------------
    */

    'toggles' => [
        'activity' => 'Audit',
        'role' => 'Role',
        'user' => 'User',
    ],

    'checkboxes' => [
        'resources' => [
            'view' => 'View',
            'view_any' => 'View Any',
            'create' => 'Create',
            'delete' => 'Delete',
            'delete_any' => 'Delete Any',
            'update' => 'Update',
            'restore' => 'Restore',
            'restore_any' => 'Restore Any',
            'export' => 'Export',
        ],

        'pages' => [
            'backups' => 'Backups',
            'health_application' => 'Health application',
            'telescope' => 'Telescope',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'You do not have permission to access',
    'update' => 'Renewed Shield\'s Config!',
    'generate' => 'Renewed Shield\'s Config & Generated Permissions w/o Policies!',
];
