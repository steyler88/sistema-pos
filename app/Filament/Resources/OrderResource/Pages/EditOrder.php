<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Se ejecuta ANTES de guardar los cambios de la orden.
     * Calcula el total sumando todos los items del carrito.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Calcular el total sumando precio * cantidad de cada item
        $total = 0;
        
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['unit_price'] ?? 0);
                
                // Asegurar que cantidad sea al menos 1
                if ($quantity == 0) {
                    $quantity = 1;
                }
                
                $total += $quantity * $price;
            }
        }

        // Establecer el total calculado
        $data['total_price'] = $total;

        return $data;
    }
}
