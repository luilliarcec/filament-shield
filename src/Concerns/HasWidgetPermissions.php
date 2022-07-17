<?php

namespace Luilliarcec\FilamentShield\Concerns;

use Filament\Facades\Filament;
use Illuminate\Support\Str;

trait HasWidgetPermissions
{
    public static function canView(): bool
    {
        return Filament::auth()->user()->can(static::getPermissionName());
    }

    public static function permissions(): array|string
    {
        return config('filament-shield.suffixes.widget');
    }

    public static function getResourceName(): string
    {
        return Str::of(class_basename(static::class))
            ->snake()
            ->lower();
    }

    public static function getPermissionName(?string $suffix = null): string
    {
        return sprintf('%s-%s', static::getResourceName(), static::permissions());
    }
}
