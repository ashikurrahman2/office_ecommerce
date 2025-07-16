<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubPointDetail extends Model
{
    public function club_point()
    {
        return $this->belongsTo(ClubPoint::class);
    }
}
