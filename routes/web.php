<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TouchPOS;
use App\Livewire\SettingsComponent;
use App\Models\Order;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para el POS Táctil (con autenticación)
Route::get('/pos', TouchPOS::class)
    ->middleware(['auth'])
    ->name('pos.touch');

// Ruta para imprimir ticket (con autenticación)
Route::get('/ticket/{order}', function (Order $order) {
    return view('ticket', ['order' => $order->load('items.product')]);
})
    ->middleware(['auth'])
    ->name('ticket.print');

// Ruta para Configuración del Sistema
Route::get('/settings', SettingsComponent::class)
    ->middleware(['auth'])
    ->name('settings');
