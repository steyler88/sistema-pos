<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class MigrateProductCategoriesToRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Este seeder se ejecutará DESPUÉS de ejecutar las migraciones
        // para asignar productos sin categoría a la primera categoría disponible
        
        $defaultCategory = Category::first();
        
        if (!$defaultCategory) {
            $this->command->warn('No hay categorías disponibles. Ejecuta CategorySeeder primero.');
            return;
        }

        // Actualizar productos que no tienen categoría
        Product::whereNull('category_id')->update([
            'category_id' => $defaultCategory->id
        ]);

        $this->command->info('Productos migrados exitosamente a la nueva estructura de categorías.');
    }
}
