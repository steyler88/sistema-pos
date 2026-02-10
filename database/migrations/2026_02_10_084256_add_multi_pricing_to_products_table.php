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
            // Agregar columnas de multi-precios después de 'price'
            $table->decimal('price_local', 10, 2)->nullable()->after('price');
            $table->decimal('price_rappi', 10, 2)->nullable()->after('price_local');
            $table->decimal('price_web', 10, 2)->nullable()->after('price_rappi');
        });
        
        // Copiar el precio actual a price_local para productos existentes
        DB::table('products')->update([
            'price_local' => DB::raw('price'),
            'price_rappi' => DB::raw('price'),
            'price_web' => DB::raw('price'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_local', 'price_rappi', 'price_web']);
        });
    }
};
