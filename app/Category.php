<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/*use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;*/
class Category extends Model
{
  //  use Translatable; // 2. To add translation methods

    protected $guarded = [];
    //public $translatedAttributes = ['name'];
 protected $fillable = [
        'name_ar', 'name_en','image','user_id'
    ];
         protected $casts = [
        'id' => 'integer',
              'user_id'=> 'integer',
        
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class);

    }//end of products
    public function ProductProvider()
    {
        return $this->hasMany(ProductProvider::class);

    }//end of products
    
    
     /*public function ProductProviders()
    {
        return $this->belongsToMany(ProductProvider::class);
    }*/
    
           public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
    
    
}//end of model
