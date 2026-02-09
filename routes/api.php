<?php

use App\Http\Controllers\WooCommerceWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se definen las rutas API de la aplicación. Estas rutas son
| cargadas por RouteServiceProvider y asignadas al grupo middleware "api".
|
*/

// Webhook de WooCommerce para órdenes creadas
Route::post('/webhooks/woocommerce/order-created', [WooCommerceWebhookController::class, 'orderCreated'])
    ->name('webhooks.woocommerce.order-created');

// Endpoint de prueba para debugging
Route::post('/webhooks/woocommerce/test', [WooCommerceWebhookController::class, 'test'])
    ->name('webhooks.woocommerce.test');

