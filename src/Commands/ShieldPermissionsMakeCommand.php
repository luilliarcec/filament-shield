<?php

namespace Luilliarcec\FilamentShield\Commands;

use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Luilliarcec\FilamentShield\Contracts\HasPermissions;
use Luilliarcec\FilamentShield\ShieldFactory;

class ShieldPermissionsMakeCommand extends Command
{
    public $signature = 'shield:generate {--force}';

    public $description = '(Re)Discovers Filament resources and (re)generates Permissions and Policies.';

    public function handle(): int
    {
        $this->generate();

        return self::SUCCESS;
    }

    protected function generate()
    {
        collect(Filament::getResources())
            ->filter(fn ($resource) => in_array(HasPermissions::class, class_implements($resource)))
            ->each(fn ($resource) => $this->fromResource($resource));

        collect(Filament::getPages())
            ->filter(fn ($page) => in_array(HasPermissions::class, class_implements($page)))
            ->each(fn ($page) => $this->fromPages($page));

        collect(Filament::getWidgets())
            ->filter(fn ($widget) => in_array(HasPermissions::class, class_implements($widget)))
            ->each(fn ($widget) => $this->fromWidgets($widget));
    }

    protected function fromResource($resource)
    {
        $model = $resource::getModel();

        $this->callSilently('shield:policy', [
            'name' => class_basename($model).'Policy',
            '--model' => '/'.str_replace('\\', '/', $model),
            '--resource' => $resource,
            '--force' => $this->option('force')
        ]);

        ShieldFactory::generatePermissions($resource);

        $this->info('Policy and Permissions successfully created for Resource: '.class_basename($resource).'!');
    }

    protected function fromPages($page)
    {
        ShieldFactory::generatePermissions($page);

        $this->info('Permissions successfully created for Page: '.class_basename($page).'!');
    }

    protected function fromWidgets($widget)
    {
        ShieldFactory::generatePermissions($widget);

        $this->info('Permissions successfully created for Widget: '.class_basename($widget).'!');
    }
}
