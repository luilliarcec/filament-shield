<?php

namespace Luilliarcec\FilamentShield\Concerns;

use Filament\Facades\Filament;
use Filament\Pages\Actions\Action;

trait HasPagePermissions
{
    use HasPermissions;

    public function mount(): void
    {
        abort_unless(static::canView(), 403);
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return static::canView() && static::$shouldRegisterNavigation;
    }

    protected function configureAction(Action $action): void
    {
        $action->authorize(static::canView());
    }

    public static function canView(): bool
    {
        return Filament::auth()->user()->can(static::getPermissionName());
    }

    public static function permissions(): array|string
    {
        return config('filament-shield.suffixes.page');
    }
}
