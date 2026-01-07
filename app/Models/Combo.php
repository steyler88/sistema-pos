<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Combo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Relación: Un combo tiene muchos productos
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'combo_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Obtener el precio total de los productos del combo (sin descuento)
     */
    public function getProductsTotalAttribute(): float
    {
        return $this->products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });
    }

    /**
     * Obtener el ahorro del combo
     */
    public function getSavingsAttribute(): float
    {
        return max(0, $this->products_total - $this->price);
    }
}
