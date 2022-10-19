<?php

namespace Luilliarcec\FilamentShield\Forms\Tabs;

use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Luilliarcec\FilamentShield\Contracts\HasPermissions;

trait ResourcePermissionsForm
{
    /**
     * Gets the filament resources that should have permissions.
     *
     * @return array|null
     */
    protected static function getResourceEntities(): ?Collection
    {
        return collect(Filament::getResources())
            ->filter(fn ($resource) => in_array(HasPermissions::class, class_implements($resource)));
    }

    /**
     * Get the grouping schema tabs per module.
     *
     * @return array
     */
    public static function getResourceTabs(): array
    {
        if (! config('filament-shield.entities.resources')) {
            return [];
        }

        return static::getResourceEntities()
            ->groupBy(fn ($resource) => $resource::getModuleName())
            ->reduce(
                function ($tabs, $resources, $module) {
                    $label = trans()->has($key = 'filament-shield::filament-shield.tabs.'.$module)
                        ? __($key)
                        : Str::headline($module);

                    $tabs[] = Forms\Components\Tabs\Tab::make($label)
                        ->visible($resources->isNotEmpty())
                        ->reactive()
                        ->schema([
                            Forms\Components\Grid::make()
                                ->schema(static::getResourceEntitiesSchema($resources))
                                ->columns([
                                    'sm' => 2,
                                    'lg' => 3
                                ])
                        ]);

                    return $tabs;
                },
                []
            );
    }

    /**
     * Get the grouping schema per resource.
     *
     * @param $resources
     *
     * @return array|null
     */
    public static function getResourceEntitiesSchema($resources): ?array
    {
        return $resources
            ->reduce(
                function ($cards, $resource) {
                    $transKey = 'filament-shield::filament-shield.toggles.'.$resource::getResourceName();

                    $label = trans()->has($transKey)
                        ? __($transKey)
                        : Str::headline($resource::getResourceName());

                    $cards[] = Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Toggle::make($resource::getPermissionPrefix())
                                ->onIcon('heroicon-s-lock-open')
                                ->offIcon('heroicon-s-lock-closed')
                                ->reactive()
                                ->label($label)
                                ->afterStateUpdated(
                                    function (Closure $set, Closure $get, $state) use ($resource) {
                                        collect($resource::permissions())->each(
                                            function ($suffix) use ($set, $resource, $state) {
                                                $set($resource::getPermissionName($suffix), $state);
                                            }
                                        );

                                        if (! $state) {
                                            $set('select_all', false);
                                        }

                                        static::refreshSelectAllStateViaEntities($set, $get);
                                    }
                                )
                                ->dehydrated(false),

                            Forms\Components\Fieldset::make(__('filament-shield::filament-shield.fieldset'))
                                ->extraAttributes([
                                    'class' => 'text-primary-600',
                                    'style' => 'border-color:var(--primary)'
                                ])
                                ->columns([
                                    'default' => 2,
                                    'xl' => 2,
                                ])
                                ->schema(
                                    static::getResourceEntityPermissionsSchema(
                                        $resource::getPermissionPrefix(),
                                        $resource
                                    )
                                ),
                        ])
                        ->columns(2)
                        ->columnSpan(1);

                    return $cards;
                },
                []
            );
    }

    /**
     * Get the permissions of the resource.
     *
     * @param $entity
     * @param $resource
     *
     * @return array|null
     */
    public static function getResourceEntityPermissionsSchema($entity, $resource): ?array
    {
        return collect($resource::permissions())
            ->reduce(
                function ($checkboxes, $suffix) use ($entity, $resource) {
                    $permission = $resource::getPermissionName($suffix);

                    $checkboxes[] = Forms\Components\Checkbox::make($permission)
                        ->label(__('filament-shield::filament-shield.checkboxes.resources.'.$suffix))
                        ->extraAttributes(['class' => 'text-primary-600'])
                        ->afterStateHydrated(
                            function (Closure $set, Closure $get, $record) use ($permission, $entity, $resource) {
                                if (is_null($record)) {
                                    return;
                                }

                                $set($permission, $record->checkPermissionTo($permission));

                                static::refreshResourceEntityStateAfterHydrated($record, $set, $entity, $resource);

                                static::refreshSelectAllStateViaEntities($set, $get);
                            }
                        )
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) use ($entity, $resource) {
                            static::refreshResourceEntityStateAfterUpdate($set, $get, $entity, $resource);

                            if (! $state) {
                                $set($entity, false);
                                $set('select_all', false);
                            }

                            static::refreshSelectAllStateViaEntities($set, $get);
                        })
                        ->dehydrated(fn ($state): bool => $state);

                    return $checkboxes;
                },
                []
            );
    }
}
