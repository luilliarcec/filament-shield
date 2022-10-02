<?php

namespace Luilliarcec\FilamentShield\Forms\Tabs;

use Filament\Facades\Filament;
use Filament\Forms;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Luilliarcec\FilamentShield\Contracts\HasPermissions;

trait WidgetPermissionsForm
{
    protected static function getWidgetTabs(): array
    {
        return [
            Forms\Components\Tabs\Tab::make(__('filament-shield::filament-shield.tabs.widgets'))
                ->visible(static::getWidgetEntities()->isNotEmpty())
                ->reactive()
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema(static::getWidgetEntityPermissionSchema())
                        ->columns([
                            'sm' => 3,
                            'lg' => 4
                        ])
                ])
        ];
    }

    protected static function getWidgetEntities(): ?Collection
    {
        return collect(Filament::getWidgets())
            ->filter(fn ($widget) => in_array(HasPermissions::class, class_implements($widget)))
            ->reduce(
                function ($widgets, $widget) {
                    $name = $widget::getPermissionName();

                    $widgets[$name] = $widget;

                    return $widgets;
                },
                collect()
            );
    }

    protected static function getWidgetEntityPermissionSchema(): ?array
    {
        return static::getWidgetEntities()
            ->reduce(
                function ($widgets, $widget, $name) {
                    $transKey = 'filament-shield::filament-shield.checkboxes.widgets.'.$widget::getPermissionLabel();

                    $label = trans()->has($transKey)
                        ? __($transKey)
                        : Str::headline($widget::getPermissionLabel());

                    $widgets[] = static::schemaForNotResourcePermissions($name, $label);

                    return $widgets;
                },
                []
            );
    }
}
