<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\Page;

class CreateOrder extends Page
{
    protected static string $resource = OrderResource::class;
    
    protected static string $view = 'filament.resources.orders.create-touch-pos';
    
    protected static ?string $title = '';
    
    // Eliminar breadcrumbs para vista completa
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
