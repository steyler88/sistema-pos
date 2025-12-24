<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
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

        // AQUÍ ACTIVAMOS EL VIGILANTE DE INVENTARIO
        OrderItem::observe(OrderItemObserver::class);
    }
}