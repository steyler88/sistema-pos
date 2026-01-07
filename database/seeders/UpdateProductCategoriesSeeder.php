<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductCategoriesSeeder extends Seeder
{
    /**
     * Actualiza productos existentes con categorías por defecto.
     */
    public function run(): void
    {
        // Buscar todas las pizzas (productos que tienen "pizza" en el nombre)
        Product::where('name', 'like', '%pizza%')
            ->orWhere('name', 'like', '%Pizza%')
            ->update(['category' => 'Pizzas']);

        // Buscar bebidas
        Product::where('name', 'like', '%coca%')
            ->orWhere('name', 'like', '%inca%')
            ->orWhere('name', 'like', '%agua%')
            ->orWhere('name', 'like', '%gaseosa%')
            ->orWhere('name', 'like', '%jugo%')
            ->update(['category' => 'Bebidas']);

        // Si no tienen categoría, asignar Pizzas por defecto
        Product::whereNull('category')
            ->orWhere('category', '')
            ->update(['category' => 'Pizzas']);

        $this->command->info('✅ Categorías actualizadas correctamente');
    }
}

