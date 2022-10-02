<?php

namespace Luilliarcec\FilamentShield\Concerns;

trait HasResourcePermissions
{
    use HasPermissions;

    public static function permissions(): array|string
    {
        return config('filament-shield.suffixes.resource');
    }
}
