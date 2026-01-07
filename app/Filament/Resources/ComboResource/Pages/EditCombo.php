<?php

namespace App\Filament\Resources\ComboResource\Pages;

use App\Filament\Resources\ComboResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCombo extends EditRecord
{
    protected static string $resource = ComboResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Cargar productos del combo para edición
        $data['combo_products'] = $this->record->products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'quantity' => $product->pivot->quantity,
            ];
        })->toArray();
        
        return $data;
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extraer productos antes de guardar
        if (isset($data['combo_products'])) {
            $this->comboProducts = $data['combo_products'];
            unset($data['combo_products']);
        }
        
        return $data;
    }
    
    protected function afterSave(): void
    {
        // Sincronizar productos después de guardar
        if (isset($this->comboProducts) && is_array($this->comboProducts)) {
            $syncData = [];
            
            foreach ($this->comboProducts as $item) {
                if (isset($item['product_id']) && isset($item['quantity'])) {
                    $syncData[$item['product_id']] = [
                        'quantity' => $item['quantity']
                    ];
                }
            }
            
            $this->record->products()->sync($syncData);
        }
    }
}
