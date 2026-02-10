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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index(); // Clave única (ej: 'company_name')
            $table->text('value')->nullable(); // Valor (puede ser texto largo)
            $table->string('group')->default('general')->index(); // Grupo (general, business, security)
            $table->string('type')->default('string'); // Tipo de dato (string, boolean, integer, json)
            $table->text('description')->nullable(); // Descripción para el admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
