<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Définir la table si elle ne suit pas la convention de nommage
    protected $table = 'reviews';

    // Définir les colonnes qui sont autorisées à être mass-assignées
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    // Si vous n'avez pas de colonnes created_at et updated_at, désactivez-les
    public $timestamps = false;
}
