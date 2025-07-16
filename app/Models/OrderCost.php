<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderCost extends Model
{
    use HasFactory;

    /**
     * Table name (optional because Laravel
     * would pluralize “order_cost” as “order_costs” automatically).
     */
    protected $table = 'order_costs';

    /**
     * Mass‑assignable columns.
     */
    protected $fillable = [
        'order_id',
        'product_cost',
        'china_courier_charge',
        'product_cost_tk',
        'china_courier_charge_tk',
        'shipping_cost_per_kg',
        'port',
        'tracking_no',
        'product_weight',
        'warehouse_message',
        'cn_airport_message',
        'bd_airport_message',
       
        'cn_airport_msg_sent_at',
        'bd_airport_msg_sent_at',
    ];

    /**
     * The order this cost record belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Cast boolean flags to true/false automatically.
     */
    protected $casts = [
        
        'product_cost'           => 'decimal:2',
        'china_courier_charge'   => 'decimal:2',
        'product_cost_tk'           => 'decimal:2',
        'china_courier_charge_tk'   => 'decimal:2',
        'shipping_cost_per_kg'   => 'decimal:2',
        'product_weight'         => 'decimal:3',
         'cn_airport_message' => 'datetime',
        'bd_airport_message' => 'datetime',
    ];
}
