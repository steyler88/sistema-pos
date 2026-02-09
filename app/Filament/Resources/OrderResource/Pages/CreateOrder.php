<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    
    protected static ?string $title = 'Nueva Venta';
    
    // Redireccionar después de crear
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    // Mensaje de éxito
    protected function getCreatedNotificationTitle(): ?string
    {
        return '¡Venta registrada exitosamente!';
    }
}
