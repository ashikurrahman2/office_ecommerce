<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryShippingCost extends Model
{
    protected $table = 'category_shipping_costs';

    protected $fillable = [
        'category_id',
        'air_cost',
        'air_delivery_time',
        'ship_cost',
        'ship_delivery_time',
        'origin',
    ];

    /**
     * Get the category associated with this shipping cost.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
}
