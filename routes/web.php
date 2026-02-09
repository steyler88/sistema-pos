<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TouchPOS;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para el POS Táctil (con autenticación)
Route::get('/pos', TouchPOS::class)
    ->middleware(['auth'])
    ->name('pos.touch');
