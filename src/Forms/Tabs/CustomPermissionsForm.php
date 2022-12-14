<?php

namespace Luilliarcec\FilamentShield\Forms\Tabs;

use Filament\Forms;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait CustomPermissionsForm
{
    protected static Collection|null $customPermissions = null;

    protected static function getCustomPermissionTabs(): array
    {
        if (! config('filament-shield.entities.custom_permissions')) {
            return [];
        }

        return [
            Forms\Components\Tabs\Tab::make(__('filament-shield::filament-shield.tabs.custom'))
                ->reactive()
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema(static::getCustomEntityPermissionsSchema())
                        ->columns([
                            'sm' => 3,
                            'lg' => 4
                        ])
                ])
        ];
    }

    protected static function getCustomEntities(): ?Collection
    {
        $permissions = static::getResourceEntities()
            ->reduce(
                function ($permissions, $resource, $entity) {
                    collect($resource::permissions())
                        ->map(
                            function ($suffix) use ($permissions, $resource) {
                                $permissions->push($resource::getPermissionName($suffix));
                            }
                        );

                    return $permissions;
                },
                collect()
            );

        $permissions = $permissions
            ->merge(static::getPageEntities()->keys())
            ->merge(static::getWidgetEntities()->keys())
            ->values();

        $model = config('permission.models.permission');

        return static::$customPermissions ??= $model::query()
            ->whereNotIn('name', $permissions)
            ->get('name')
            ->pluck('name');
    }

    protected static function getCustomEntityPermissionsSchema(): ?array
    {
        return static::getCustomEntities()
            ->reduce(
                function ($entities, $permission) {
                    $entity = Str::of($permission)
                        ->headline()
                        ->toString();

                    $entities[] = static::schemaForNotResourcePermissions($permission, $entity);

                    return $entities;
                },
                []
            );
    }
}
