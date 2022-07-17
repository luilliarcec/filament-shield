<?php

namespace Luilliarcec\FilamentShield;

use Illuminate\Support\Collection;
use Spatie\Permission\PermissionRegistrar;

class ShieldFactory
{
    public static function generatePermissions($resource): void
    {
        $model = config('permission.models.permission');

        $permissions = collect($resource::permissions())
            ->reduce(
                fn($permissions, $suffix) => $permissions->push(
                    $model::firstOrCreate(
                        ['name' => $resource::getPermissionName($suffix)],
                        ['guard_name' => config('filament.auth.guard')]
                    )
                ),
                collect()
            );

        static::giveSuperAdminPermission($permissions);
    }

    protected static function giveSuperAdminPermission(string|array|Collection $permissions): void
    {
        $model = config('permission.models.role');

        $role = $model::firstOrCreate(
            ['name' => 'super_admin'],
            ['guard_name' => config('filament.auth.guard')]
        );

        $role->givePermissionTo($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
