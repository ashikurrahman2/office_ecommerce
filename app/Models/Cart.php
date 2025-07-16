<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];
    protected $fillable = ['address_id','price','tax','shipping_cost','discount','product_referral_code','coupon_code','coupon_applied','quantity', 'min_quantity', 'user_id','temp_user_id','owner_id','product_id', 'product_title', 'attributes', 'product_weight', 'vid', 'pid','image', 'stock','externalitemurl'];

    protected $casts = [
        'attributes' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
