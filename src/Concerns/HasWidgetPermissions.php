<?php

namespace Luilliarcec\FilamentShield\Concerns;

use Filament\Facades\Filament;

trait HasWidgetPermissions
{
    use HasPermissions;

    public static function canView(): bool
    {
        return Filament::auth()->user()->can(static::getPermissionName());
    }

    public static function permissions(): array|string
    {
        return config('filament-shield.suffixes.widget');
    }
}
