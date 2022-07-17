<?php

namespace Luilliarcec\FilamentShield;

use Filament\PluginServiceProvider;
use Luilliarcec\FilamentShield\Commands;
use Spatie\LaravelPackageTools\Package;

class FilamentShieldServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-shield';

    public array $commands = [
        Commands\ShieldPolicyMakeCommands::class,
        Commands\ShieldPermissionsMakeCommand::class,
    ];

    public function configurePackage(Package $package): void
    {
        parent::configurePackage($package);

        $package
            ->hasConfigFile('filament-shield')
            ->hasTranslations()
            ->hasCommands();
    }

    protected function getResources(): array
    {
        return config('filament-shield.resources');
    }
}
