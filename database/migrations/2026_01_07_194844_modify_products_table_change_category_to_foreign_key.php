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
        Schema::table('products', function (Blueprint $table) {
            // Eliminar la columna category antigua (enum)
            $table->dropColumn('category');
        });

        Schema::table('products', function (Blueprint $table) {
            // Agregar la nueva columna category_id como foreign key
            $table->foreignId('category_id')->nullable()->after('name')->constrained('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Eliminar la foreign key y la columna category_id
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('products', function (Blueprint $table) {
            // Restaurar la columna category como enum
            $table->enum('category', ['Pizzas', 'Bebidas', 'Postres', 'Entradas', 'Otros'])->default('Pizzas')->after('name');
        });
    }
};
