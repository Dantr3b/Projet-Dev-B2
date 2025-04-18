<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlists';

    protected $fillable = ['user_id', 'name'];

    public function items()
    {
        return $this->hasMany(WishlistItem::class, 'wishlist_id');
    }
}
