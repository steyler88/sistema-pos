<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pizzas',
                'icon' => '🍕',
                'color' => '#ef4444',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Bebidas',
                'icon' => '🥤',
                'color' => '#3b82f6',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Postres',
                'icon' => '🍰',
                'color' => '#f59e0b',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Entradas',
                'icon' => '🥗',
                'color' => '#10b981',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Extras',
                'icon' => '🧀',
                'color' => '#8b5cf6',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
