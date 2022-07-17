<a href="https://github.com/bezhansalleh/filament-shield">
<img style="width: 100%; max-width: 100%;" alt="filament-shield-art" src="https://user-images.githubusercontent.com/10007504/148662315-35d4bd74-fc1c-4f8c-8c02-689309b414b0.png" >
</a>

<hr style="background-color: #ebb304">

# Filament Shield
The easiest and most intuitive way to add access management to your Filament Admin:
- :fire: **Resources** 
- :fire: **Pages** 
- :fire: **Widgets** 

<!-- One Plugin to rule them all, One Plugin to find them, One Plugin to bring them all, and in the light bind them, In the Land of Filament where building them is really fun! -->

## Support Filament

<a href="https://github.com/sponsors/danharrin">
<img width="320" alt="filament-logo" src="https://filamentadmin.com/images/sponsor-banner.jpg">
</a>

## Installation

1. Install the package via composer:

```bash
composer require luilliarcec/filament-shield
```

2. Publish the config file with:

```bash
php artisan vendor:publish --tag="filament-shield-config"
```

3. Configure your options

```php
<?php

return [
    'resources' => [
        'role' => Resources\RoleResource::class
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
```

4. Add the `Spatie\Permission\Traits\HasRoles` trait to your User model(s):

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

5. Now run the following command to set up everything:

```bash
php artisan shield:generate
```


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
