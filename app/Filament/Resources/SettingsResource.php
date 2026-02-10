<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;

class SettingsResource extends Resource
{
    // No tiene modelo porque no queremos CRUD tradicional
    protected static ?string $model = null;

    // Configuración visual del menú
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Configuración';
    protected static ?string $navigationGroup = 'Sistema';
    protected static ?int $navigationSort = 100; // Último en el menú

    /**
     * Items de navegación personalizados
     */
    public static function getNavigationItems(): array
    {
        return [
            \Filament\Navigation\NavigationItem::make('Configuración del Sistema')
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->url(route('settings'))
                ->isActiveWhen(fn () => request()->routeIs('settings'))
                ->sort(static::getNavigationSort()),
        ];
    }

    /**
     * Determinar si debe mostrarse en la navegación
     */
    public static function canViewAny(): bool
    {
        return true; // O puedes agregar lógica de permisos aquí
    }

    /**
     * Definir que no tiene páginas de CRUD
     */
    public static function getPages(): array
    {
        return [];
    }
}

