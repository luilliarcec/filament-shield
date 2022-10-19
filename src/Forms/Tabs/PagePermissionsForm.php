<?php

namespace Luilliarcec\FilamentShield\Forms\Tabs;

use Filament\Facades\Filament;
use Filament\Forms;
use Illuminate\Support\Collection;
use Luilliarcec\FilamentShield\Contracts\HasPermissions;

trait PagePermissionsForm
{
    protected static function getPageTabs(): array
    {
        if (! config('filament-shield.entities.pages')) {
            return [];
        }

        return [
            Forms\Components\Tabs\Tab::make(__('filament-shield::filament-shield.tabs.pages'))
                ->visible(static::getPageEntities()->isNotEmpty())
                ->reactive()
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema(static::getPageEntityPermissionsSchema())
                        ->columns([
                            'sm' => 3,
                            'lg' => 4
                        ])
                ])
        ];
    }

    protected static function getPageEntities(): ?Collection
    {
        return collect(Filament::getPages())
            ->filter(fn ($page) => in_array(HasPermissions::class, class_implements($page)))
            ->sortBy(fn ($page) => $page::getPermissionPrefix())
            ->reduce(
                function ($pages, $page) {
                    $name = $page::getPermissionName();

                    $pages[$name] = $page;

                    return $pages;
                },
                collect()
            );
    }

    protected static function getPageEntityPermissionsSchema(): ?array
    {
        return static::getPageEntities()
            ->reduce(
                function ($pages, $page, $name) {
                    $transKey = 'filament-shield::filament-shield.checkboxes.pages.'.$page::getPermissionPrefix();

                    $label = trans()->has($transKey)
                        ? __($transKey)
                        : $page::getPermissionLabel();

                    $pages[] = static::schemaForNotResourcePermissions($name, $label);

                    return $pages;
                },
                []
            );
    }
}
