<?php

namespace BezhanSalleh\FilamentShield;

use BezhanSalleh\FilamentShield\Resources\RoleResource;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelPackageTools\Package;

class FilamentShieldServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-shield';

    protected array $resources = [
        RoleResource::class,
    ];

    public array $commands = [

    ];

    public function configurePackage(Package $package): void
    {
        parent::configurePackage($package);

        $package
            ->hasConfigFile('filament-shield')
            ->hasTranslations()
            ->hasCommands($this->getCommands());
    }

    protected function getResources(): array
    {
        return config('filament-shield.resources');
    }
}
