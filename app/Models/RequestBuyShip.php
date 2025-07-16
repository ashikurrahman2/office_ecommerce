<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestBuyShip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
      'order_id',
        'order_code',
        'product_link',
        'product_title',
        'description',
        'quantity',
        'status',
    ];

}
