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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // Ej: Pago de Luz del Mes
            $table->decimal('amount', 10, 2); // Cuánto gastaste
            
            // Categoría para agrupar en qué se va el dinero
            $table->enum('category', [
                'services',     // Luz, Agua, Internet
                'supplies',     // Compras de insumos (repo de emergencia)
                'salaries',     // Pagos de personal
                'rent',         // Alquiler
                'maintenance',  // Reparaciones
                'marketing',    // Publicidad
                'other'         // Otros
            ])->default('other');

            $table->date('expense_date'); // Cuándo se hizo el gasto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};