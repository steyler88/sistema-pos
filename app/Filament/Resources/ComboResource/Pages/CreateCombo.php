<?php

namespace App\Filament\Resources\ComboResource\Pages;

use App\Filament\Resources\ComboResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCombo extends CreateRecord
{
    protected static string $resource = ComboResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extraer productos antes de crear el combo
        if (isset($data['combo_products'])) {
            $this->comboProducts = $data['combo_products'];
            unset($data['combo_products']);
        }
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        // Sincronizar productos después de crear el combo
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
