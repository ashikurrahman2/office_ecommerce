<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ShippingCategory extends Model
{

    public function coverImage(){
    	return $this->belongsTo(Upload::class, 'cover_image');
    }

    public function catIcon(){
    	return $this->belongsTo(Upload::class, 'icon');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('categories');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

}
