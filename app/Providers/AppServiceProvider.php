<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Livewire\Livewire;
use App\Models\Order;
use App\Models\OrderItem;
use App\Observers\OrderObserver;
use App\Observers\OrderItemObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Esto hace que Laravel deje de bloquear cambios masivos por defecto (seguridad extra)
        Model::unguard();

        // AQUÍ ACTIVAMOS LOS VIGILANTES (OBSERVERS)
        Order::observe(OrderObserver::class); // ✅ Calcula total automáticamente
        OrderItem::observe(OrderItemObserver::class); // Maneja inventario y recalcula total
        
        // Registrar componente Livewire del POS Táctil
        Livewire::component('touch-p-o-s', \App\Livewire\TouchPOS::class);
    }
}