<?php

namespace Luilliarcec\FilamentShield\Contracts;

interface HasResourcePermissions extends HasPermissions
{
    public static function getModuleName(): string;

    public static function getModuleResourceName(): string;
}
