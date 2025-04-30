<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    public $timestamps = false;
    protected $table = 'shipping';
    use HasFactory;

    protected $fillable = [
        'order_id', 'shipping_address', 'shipping_date', 'tracking_number'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
