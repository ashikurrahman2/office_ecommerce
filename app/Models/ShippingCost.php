<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    use HasFactory;
 
    protected $table = 'shipping_cost';

    protected $fillable = [
        'order_id',
        'name',
        'type',
        'weight',
        'cost_per_kg',
        'courier_fee',
        'discount',
        'total_shipping_cost',
    ];

    protected $casts = [
        'total_shipping_cost' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
