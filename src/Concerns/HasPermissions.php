<?php

namespace Luilliarcec\FilamentShield\Concerns;

use Illuminate\Support\Str;

trait HasPermissions
{
    public static function getModuleName(): ?string
    {
        $labels = array_reverse(explode('\\', static::class));

        array_shift($labels);

        foreach ($labels as $label) {
            $label = Str::of($label)->snake()->lower();

            if (! in_array($label, config('filament-shield.dont_modules'))) {
                break;
            }

            $label = null;
        }

        return $label ?? null;
    }

    public static function getResourceName(): string
    {
        return Str::of(class_basename(static::class))
            ->replaceLast('Resource', '')
            ->replaceLast('Page', '')
            ->replaceLast('Widget', '')
            ->snake()
            ->lower();
    }

    public static function getPermissionPrefix(): string
    {
        $module = static::getModuleName();

        $resource = static::getResourceName();

        return $module == null
            ? $resource
            : sprintf('%s-%s', $module, $resource);
    }

    public static function getPermissionName(?string $suffix = null): string
    {
        return sprintf('%s-%s', static::getPermissionPrefix(), $suffix ?: static::permissions());
    }

    public static function getPermissionLabel(): string
    {
        return static::getPermissionPrefix();
    }
}
