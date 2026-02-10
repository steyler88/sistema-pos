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
        Schema::table('orders', function (Blueprint $table) {
            // Agregar columna sales_channel después de order_type
            $table->string('sales_channel')->default('local')->after('order_type');
            // Valores posibles: 'local', 'rappi', 'web'
        });
        
        // Actualizar órdenes existentes para que tengan el canal 'local'
        DB::table('orders')->update(['sales_channel' => 'local']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('sales_channel');
        });
    }
};
