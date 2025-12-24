<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable(); // Nombre cliente (opcional)
            $table->decimal('total_price', 10, 2)->default(0); // Total a pagar
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Estado
            $table->enum('payment_method', ['cash', 'card', 'yape', 'plin'])->nullable(); // Forma de pago
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
