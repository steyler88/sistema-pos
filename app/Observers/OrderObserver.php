<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Se ejecuta después de que se guarda una orden (crear o actualizar).
     * Recalcula el total sumando todos los items.
     */
    public function saved(Order $order): void
    {
        $this->recalculateTotal($order);
    }

    /**
     * Recalcula el total de la orden basado en sus items.
     */
    private function recalculateTotal(Order $order): void
    {
        // Calcular el total sumando precio * cantidad de cada item
        $total = $order->items()->get()->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        // Actualizar el total sin disparar eventos (para evitar bucle infinito)
        $order->updateQuietly(['total_price' => $total]);
    }
}

