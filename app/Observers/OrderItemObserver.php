<?php

namespace App\Observers;

use App\Models\OrderItem;

class OrderItemObserver
{
    /**
     * Se ejecuta cuando se CREA un item en una venta (Vendiste una pizza).
     * ACCIÓN: Restar insumos del inventario.
     */
    public function created(OrderItem $orderItem): void
    {
        // 1. Buscamos el producto (pizza) que se vendió
        $product = $orderItem->product;

        // 2. Si el producto tiene ingredientes en la receta...
        if ($product && $product->ingredients->count() > 0) {
            foreach ($product->ingredients as $ingredient) {
                // Cantidad que lleva la receta (ej: 200g)
                $recipeQuantity = $ingredient->pivot->quantity;
                
                // Cantidad de pizzas vendidas (ej: 2 pizzas)
                $soldQuantity = $orderItem->quantity;

                // Cálculo total a descontar (200g * 2 = 400g)
                $totalToDeduct = $recipeQuantity * $soldQuantity;

                // 3. Restamos del stock actual
                $ingredient->decrement('stock', $totalToDeduct);
            }
        }
    }

    /**
     * Se ejecuta cuando ELIMINAS un item de una venta (Cancelación).
     * ACCIÓN: Devolver insumos al inventario.
     */
    public function deleted(OrderItem $orderItem): void
    {
        $product = $orderItem->product;

        if ($product && $product->ingredients->count() > 0) {
            foreach ($product->ingredients as $ingredient) {
                $recipeQuantity = $ingredient->pivot->quantity;
                $soldQuantity = $orderItem->quantity;
                $totalToReturn = $recipeQuantity * $soldQuantity;

                // Devolvemos al stock (Incrementamos)
                $ingredient->increment('stock', $totalToReturn);
            }
        }
    }
}