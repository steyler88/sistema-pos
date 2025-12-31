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
            // Tipo de pedido: Mesa, Barra, Para Llevar, Delivery
            $table->enum('order_type', ['mesa', 'barra', 'para_llevar', 'delivery'])
                ->default('delivery')
                ->after('customer_name');
            
            // Ubicación específica (Mesa 1, Mesa 2, Barra, etc.)
            $table->string('table_location')->nullable()->after('order_type');
            
            // Notas adicionales para el pedido
            $table->text('notes')->nullable()->after('table_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'table_location', 'notes']);
        });
    }
};

