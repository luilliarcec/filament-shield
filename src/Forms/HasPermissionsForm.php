<?php

namespace Luilliarcec\FilamentShield\Forms;

use Closure;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Luilliarcec\FilamentShield\Forms\Tabs;

trait HasPermissionsForm
{
    use Tabs\ResourcePermissionsForm;
    use Tabs\PagePermissionsForm;
    use Tabs\WidgetPermissionsForm;
    use Tabs\CustomPermissionsForm;

    protected static function getPermissionsSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('filament-shield::filament-shield.section'))
            ->schema([
                Forms\Components\Tabs::make('Permissions')
                    ->tabs([
                        ...static::getResourceTabs(),

                        ...static::getPageTabs(),

                        ...static::getWidgetTabs(),

                        ...static::getCustomPermissionTabs(),
                    ])
                    ->columnSpan('full'),
            ]);
    }

    /**
     * Refreshes the state of select all from the entities state.
     *
     * @param  Closure  $set
     * @param  Closure  $get
     *
     * @return void
     */
    protected static function refreshSelectAllStateViaEntities(Closure $set, Closure $get): void
    {
        $states = static::getResourceEntities()
            ->map(fn ($resource, $entity) => (bool)$get($resource::getPermissionPrefix()));

        $states = static::getPageEntities()
            ->map(fn ($page, $entity) => (bool)$get($entity))
            ->merge($states);

        $states = static::getWidgetEntities()
            ->map(fn ($widget, $entity) => (bool)$get($entity))
            ->merge($states);

        $states = static::getCustomEntities()
            ->map(fn ($name) => (bool)$get($name))
            ->merge($states);

        if ($states->containsStrict(false) === false) {
            $set('select_all', true);
        }

        if ($states->containsStrict(false) === true) {
            $set('select_all', false);
        }
    }

    /**
     * Refreshes the state of all entities from the all select.
     *
     * @param  Closure  $set
     * @param           $state
     *
     * @return void
     */
    protected static function refreshEntitiesStatesViaSelectAll(Closure $set, $state): void
    {
        static::getResourceEntities()
            ->each(function ($resource, $entity) use ($set, $state) {
                $set($resource::getPermissionPrefix(), $state);

                collect($resource::permissions())->each(
                    function ($suffix) use ($resource, $set, $state) {
                        $set($resource::getPermissionName($suffix), $state);
                    }
                );
            });

        static::getPageEntities()
            ->each(function ($page, $entity) use ($set, $state) {
                $set($entity, $state);
            });

        static::getWidgetEntities()
            ->each(function ($widget, $entity) use ($set, $state) {
                $set($entity, $state);
            });

        static::getCustomEntities()
            ->each(function ($name) use ($set, $state) {
                $set($name, $state);
            });
    }

    /**
     * Refreshes the state of toggle entities after updating an entity permission.
     *
     * @param  Closure  $set
     * @param  Closure  $get
     * @param  string  $entity
     * @param           $resource
     *
     * @return void
     */
    protected static function refreshResourceEntityStateAfterUpdate(
        Closure $set,
        Closure $get,
        string $entity,
        $resource
    ): void {
        $permissionStates = collect($resource::permissions())
            ->map(
                fn ($suffix) => (bool)$get($resource::getPermissionName($suffix))
            );

        if ($permissionStates->containsStrict(false) === false) {
            $set($entity, true);
        }

        if ($permissionStates->containsStrict(false) === true) {
            $set($entity, false);
        }
    }

    /**
     * Refreshes the state of the resources in the edition.
     *
     * @param  Model  $record
     * @param  Closure  $set
     * @param  string  $entity
     * @param           $resource
     *
     * @return void
     */
    protected static function refreshResourceEntityStateAfterHydrated(
        Model $record,
        Closure $set,
        string $entity,
        $resource
    ): void {
        $entities = $record->permissions
            ->pluck('name')
            ->reduce(function ($entities, $permission) {
                $entities[$permission] = Str::beforeLast($permission, '-');

                return $entities;
            }, collect())
            ->groupBy(fn ($item) => $item)
            ->map
            ->count()
            ->reduce(function ($counts, $counted, $entity) use ($resource) {
                if ($counted > 1 && $counted == count($resource::permissions())) {
                    $counts[$entity] = true;
                } else {
                    $counts[$entity] = false;
                }

                return $counts;
            }, []);

        // set entity's state if one are all permissions are true
        if (in_array($entity, array_keys($entities)) && $entities[$entity]) {
            $set($entity, true);
        } else {
            $set($entity, false);
            $set('select_all', false);
        }
    }

    /**
     * Form schema (checkbox) for pages, widgets and custom permissions.
     *
     * @param  string  $name
     * @param  string  $label
     * @return Forms\Components\Grid
     */
    public static function schemaForNotResourcePermissions(string $name, string $label): Forms\Components\Grid
    {
        return Forms\Components\Grid::make()
            ->schema([
                Forms\Components\Checkbox::make($name)
                    ->label($label)
                    ->inline()
                    ->afterStateHydrated(function (Closure $set, Closure $get, $record) use ($name) {
                        if (is_null($record)) {
                            return;
                        }

                        $set($name, $record->checkPermissionTo($name));

                        static::refreshSelectAllStateViaEntities($set, $get);
                    })
                    ->reactive()
                    ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                        if (! $state) {
                            $set('select_all', false);
                        }

                        static::refreshSelectAllStateViaEntities($set, $get);
                    })
                    ->dehydrated(fn ($state): bool => $state),
            ])
            ->columns(1)
            ->columnSpan(1);
    }
}
