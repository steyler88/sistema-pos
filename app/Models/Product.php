<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'image',
        'is_active',
    ];

    /**
     * Relación: Un producto pertenece a una categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->withPivot('quantity') // Le decimos que queremos acceder a la cantidad
                    ->withTimestamps();
    }
}