<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'CategoryId',
        'ProviderType',
        'IsHidden',
        'IsVirtual',
        'ExternalId',
        'Name',
        'CustomName',
        'IsParent',
        'ParentId',
        'IsInternal',
        'banner',
        'IsShow',
        'created_at',
        'updated_at',
    ];

    protected $with = ['category_translations'];

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $category_translation = $this->category_translations->where('lang', $lang)->first();
        return $category_translation != null ? $category_translation->$field : $this->$field;
    }

    public function category_translations(){
    	return $this->hasMany(CategoryTranslation::class);
    }

    public function coverImage(){
    	return $this->belongsTo(Upload::class, 'cover_image');
    }

    public function catIcon(){
    	return $this->belongsTo(Upload::class, 'icon');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }
    
    public function bannerImage(){
    	return $this->belongsTo(Upload::class, 'banner');
    }

    public function classified_products(){
    	return $this->hasMany(CustomerProduct::class);
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
        return $this->belongsTo(Category::class, 'ParentId', 'CategoryId');
    }


    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function sizeChart()
    {
        return $this->belongsTo(SizeChart::class, 'id', 'category_id');
    }

    public function shippingCosts()
    {
        return $this->hasMany(CategoryShippingCost::class, 'category_id');
    }

}
