<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'category',
        'expense_date',
    ];

    // Esto ayuda a traducir el código 'services' a 'Servicios' automáticamente
    protected $casts = [
        'expense_date' => 'date',
    ];
}