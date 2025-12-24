<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;

class Product extends Model
{
    use HasFactory;

    // ESTO ES LO QUE TE FALTA AGREGAR:
    protected $fillable = [
        'name',
        'price',
        'image',
        'is_active',
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->withPivot('quantity') // Le decimos que queremos acceder a la cantidad
                    ->withTimestamps();
    }
}