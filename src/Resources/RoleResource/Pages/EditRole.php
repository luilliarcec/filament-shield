<?php

namespace Luilliarcec\FilamentShield\Resources\RoleResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Luilliarcec\FilamentShield\Resources\RoleResource;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissions = collect($data)
            ->filter(
                fn($permission, $key) => !in_array($key, ['name', 'guard_name', 'select_all'])
                    && Str::contains($key, '-')
            )
            ->keys();

        return Arr::only($data, ['name', 'guard_name']);
    }

    protected function afterSave(): void
    {
        $model = config('permission.models.permission');

        $permissions = $this->permissions
            ->reduce(
                fn($permissions, $permission) => $permissions->push(
                    $model::firstOrCreate(
                        ['name' => $permission],
                        ['guard_name' => config('filament.auth.guard')]
                    )
                ),
                collect()
            );

        $this->record->syncPermissions($permissions);
    }
}
