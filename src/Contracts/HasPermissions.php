<?php

namespace Luilliarcec\FilamentShield\Contracts;

interface HasPermissions
{
    public static function permissions(): array|string;

    public static function getModuleName(): ?string;

    public static function getResourceName(): string;

    public static function getPermissionPrefix(): string;

    public static function getPermissionName(?string $suffix = null): string;

    public static function getPermissionLabel(): string;
}
