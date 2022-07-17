<?php

namespace Luilliarcec\FilamentShield\Concerns;

use Illuminate\Support\Str;

trait HasResourcePermissions
{
    public static function permissions(): array|string
    {
        return config('filament-shield.suffixes.resource');
    }

    public static function getModuleResourceName(): string
    {
        $module = static::getModuleName();

        $resource = static::getResourceName();

        return sprintf('%s-%s', $module, $resource);
    }

    public static function getModuleName(): string
    {
        $labels = explode('\\', static::class);

        return Str::of($labels[count($labels) - 2])
            ->snake()
            ->lower();
    }

    public static function getResourceName(): string
    {
        return Str::of(class_basename(static::getModel()))
            ->snake()
            ->lower();
    }

    public static function getPermissionName(?string $suffix = null): string
    {
        return sprintf('%s-%s', static::getModuleResourceName(), $suffix);
    }
}
