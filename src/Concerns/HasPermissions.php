<?php

namespace Luilliarcec\FilamentShield\Concerns;

use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Widgets\Widget;
use Illuminate\Support\Str;

trait HasPermissions
{
    protected static bool $useNavigationGroupAsModule = true;

    protected static int $moduleIndex = 0;

    public static function getModuleName(): ?string
    {
        if (self::shouldUseNavigationGroupAsModule()) {
            return static::getNavigationGroup();
        }

        $labels = Str::of(static::class)
            ->replace(static::getEntityNamespace(), '')
            ->explode('\\');

        return $labels->get(static::$moduleIndex);
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
        $module = mb_strtolower(static::getModuleName());

        $resource = mb_strtolower(static::getResourceName());

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
        return Str::of(static::getPermissionPrefix())
            ->replace('_', ' ')
            ->replace('-', ' - ')
            ->title();
    }

    protected static function shouldUseNavigationGroupAsModule(): bool
    {
        if (! method_exists(static::class, 'getNavigationGroup')) {
            return false;
        }

        return static::$useNavigationGroupAsModule && static::getNavigationGroup();
    }

    protected static function getEntityNamespace(): ?string
    {
        if (is_subclass_of(static::class, Resource::class)) {
            return config('filament.resources.namespace').'\\';
        }

        if (is_subclass_of(static::class, Page::class)) {
            return config('filament.pages.namespace').'\\';
        }

        if (is_subclass_of(static::class, Widget::class)) {
            return config('filament.widgets.namespace').'\\';
        }

        return null;
    }
}
