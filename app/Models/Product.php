<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;
use App\Models\Setting;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'price',
        'price_local',    // Precio para ventas locales
        'price_rappi',    // Precio para pedidos de Rappi
        'price_web',      // Precio para pedidos web
        'image',
        'is_active',
    ];

    /**
     * Relación: Un producto pertenece a una categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación: Un producto puede estar en muchos combos
     */
    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->withPivot('quantity') // Le decimos que queremos acceder a la cantidad
                    ->withTimestamps();
    }

    /**
     * Obtener el precio según el canal de venta
     * 
     * @param string $channel Canal: 'local', 'rappi', 'web'
     * @return float
     */
    public function getPriceByChannel(string $channel = 'local'): float
    {
        // Si multi-precios está deshabilitado, siempre usar 'price'
        if (!Setting::get('enable_multi_pricing', false)) {
            return (float) $this->price;
        }

        // Si multi-precios está habilitado, usar el precio específico del canal
        return match($channel) {
            'rappi' => (float) ($this->price_rappi ?? $this->price),
            'web' => (float) ($this->price_web ?? $this->price),
            default => (float) ($this->price_local ?? $this->price),
        };
    }
}