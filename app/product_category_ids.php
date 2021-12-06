<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_category_ids extends Model
{
    protected $fillable = [
       'product_providers_id','category_id', 'user_id'
    ];
    protected $casts = [
        'category_id' => 'integer',
        'product_providers_id' => 'integer',
          'user_id'=> 'integer',
       
        
   
    ];

 public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');

    }//end fo category

    public function ProductProvider()
    {
        return $this->belongsTo(ProductProvider::class,'product_providers_id','id');

    }//end fo category
    
        public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
}
