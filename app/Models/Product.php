<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Spécifier le nom de la table si elle n'est pas automatiquement inférée
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    public $timestamps = false;


    // Definition des champs
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
    ];

    // Pour les champs qui ne sont pas des timestamps
    protected $casts = [
        'price' => 'float',
        'stock_quantity' => 'integer',
    ];
}

