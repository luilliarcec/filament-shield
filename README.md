# Filament Shield

The easiest and most intuitive way to add access management to your Filament Admin:

- :fire: **Resources**
- :fire: **Pages**
- :fire: **Widgets**

Fork from [bezhanSalleh/filament-shield](https://github.com/bezhanSalleh/filament-shield)

## Support Filament

<a href="https://github.com/sponsors/danharrin">
<img width="320" alt="filament-logo" src="https://filamentadmin.com/images/sponsor-banner.jpg">
</a>

## Installation

Install the package via composer:

```bash
composer require luilliarcec/filament-shield
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="filament-shield-config"
```

Configure your options

```php
<?php

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
     * them from your filament `resource`, `page` or `widget`.
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
     * from the namespace of the filament `resource`, `page` or `widget`.
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
```

Add the `Spatie\Permission\Traits\HasRoles` trait to your User model(s):

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

If you want to generate your permissions, along with the policy, use the following command:

```bash
php artisan shield:generate
```

The package is smart enough to generate the policies in the same namespace
as your model associated with the filament resource.

To make use of this package, each `resource`, `page` or `widget` must implement the following contract.

`Luilliarcec\FilamentShield\Contracts\HasPermissions`

```php
use Filament\Resources\Resource;
use Luilliarcec\FilamentShield\Contracts\HasPermissions;

class RoleResource extends Resource implements HasPermissions
{
    // 
}
```

You must implement each of the methods or if you like you can use the traits for each case, `resource`, `page` or `
widget.

```php
use Filament\Resources\Resource;
use Luilliarcec\FilamentShield\Contracts\HasPermissions
use Luilliarcec\FilamentShield\Concerns\HasResourcePermissions;

class RoleResource extends Resource implements HasPermissions
{
    use HasResourcePermissions;
}
```

If you want to add or remove permissions to your resource for generation, you can override the permissions method.

```php
use Filament\Resources\Resource;
use Luilliarcec\FilamentShield\Contracts\HasPermissions;
use Luilliarcec\FilamentShield\Concerns\HasResourcePermissions;

class RoleResource extends Resource implements HasPermissions
{
    use HasResourcePermissions;
    
    //

    public static function permissions(): array|string
    {
        return [
            'view_any',
            'export'
        ];
    }
}
```

Now this resource will only have two permissions.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

If you want to contribute to these packages, you may want to test it in a real Filament project:

- Fork this repository to your GitHub account.
- Create a Filament app locally.
- Clone your fork in your Filament app's root directory.
- In the `/filament-shield` directory, create a branch for your fix, e.g. `fix/error-message`.

Install the packages in your app's `composer.json`:

## Credits

- [Bezhan Salleh](https://github.com/bezhanSalleh)
- [Luis Arce](https://github.com/luilliarcec)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
