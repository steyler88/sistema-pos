<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\Page;

class ActiveOrders extends Page
{
    protected static string $resource = OrderResource::class;
    
    protected static string $view = 'filament.resources.orders.active-orders';
    
    protected static ?string $title = 'Órdenes Activas';
    
    protected static ?string $navigationLabel = 'Órdenes Activas';
}

