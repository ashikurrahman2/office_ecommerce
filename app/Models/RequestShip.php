<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestShip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
           'order_id',
        'order_code',
        'product_link',
        'product_title',
        'category_name',
        'description',
        'price',
        'quantity',
        'ship_to',
        'valid_to',
        'weight',
        'shipping_type',
        'length',
        'width',
        'height',
        'images',
        'request_type',
        'order_status',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
