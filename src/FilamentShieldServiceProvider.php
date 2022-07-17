<?php

namespace Luilliarcec\FilamentShield;

use Filament\PluginServiceProvider;
use Luilliarcec\FilamentShield\Commands;
use Spatie\LaravelPackageTools\Package;

class FilamentShieldServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-shield';

    public function configurePackage(Package $package): void
    {
        parent::configurePackage($package);

        $package
            ->hasConfigFile('filament-shield');
    }

    protected function getResources(): array
    {
        return config('filament-shield.resources');
    }

    protected function getCommands(): array
    {
        return [
            Commands\ShieldPolicyMakeCommands::class,
            Commands\ShieldPermissionsMakeCommand::class,
        ];
    }
}
