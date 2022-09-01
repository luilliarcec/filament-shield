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
        $labels = array_reverse(explode('\\', static::class));

        array_shift($labels);

        foreach ($labels as $label) {
            $label = Str::of($label)->snake()->lower();

            if (! in_array($label, config('filament-shield.dont_modules'))) {
                break;
            }
        }

        return $label ?? __('Unknown');
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
