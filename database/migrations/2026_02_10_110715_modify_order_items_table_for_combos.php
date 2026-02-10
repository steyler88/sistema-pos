<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero eliminar la restricción de clave foránea existente
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // Modificar la columna para que sea nullable
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change()->constrained()->cascadeOnDelete();
            $table->foreignId('combo_id')->nullable()->after('product_id')->constrained('combos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['combo_id']);
            $table->dropColumn('combo_id');
            
            $table->dropForeign(['product_id']);
            $table->foreignId('product_id')->nullable(false)->change()->constrained()->cascadeOnDelete();
        });
    }
};
