<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductProvider extends Model
{
    protected $fillable = [
        'name_ar', 'name_en','image','user_id'
    ];

 protected $casts = [
        'id' => 'integer',
          'user_id'=> 'integer',
    ];
    public function product_category_ids()
    {
        return $this->hasMany(product_category_ids::class,'product_providers_id','id')->with('category');

    }//end fo product_category_ids
     public function ProductCategoryIds()
    {
        return $this->hasMany(product_category_ids::class,'product_providers_id','id');

    }//end fo product_category_ids
        public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
}
