<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'type',
        'bank_name',
        'account_name',
        'account_number',
        'branch',
        'routing_no',
        'image_path',
    ];
}
