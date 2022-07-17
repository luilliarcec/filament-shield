<?php

namespace Luilliarcec\FilamentShield\Concerns;

use Filament\Facades\Filament;
use Illuminate\Support\Str;

trait HasPagePermissions
{
    public function booted(): void
    {
        $this->beforeBooted();

        if (!static::canView()) {
            $this->notify('warning', __('filament-shield::filament-shield.forbidden'));

            $this->beforeShieldRedirects();

            redirect($this->getShieldRedirectPath());

            return;
        }

        if (method_exists(parent::class, 'booted')) {
            parent::booted();
        }

        $this->afterBooted();
    }

    protected function beforeBooted(): void
    {
        return;
    }

    protected function afterBooted(): void
    {
        return;
    }

    protected function beforeShieldRedirects(): void
    {
        return;
    }

    protected function getShieldRedirectPath(): string
    {
        return config('filament.path');
    }

    public static function canView(): bool
    {
        return Filament::auth()->user()->can(static::getPermissionName());
    }

    public static function permissions(): array|string
    {
        return config('filament-shield.suffixes.page');
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

    protected static function shouldRegisterNavigation(): bool
    {
        return static::canView() && static::$shouldRegisterNavigation;
    }
}
