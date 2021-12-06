<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
     protected $fillable = [
        'client_id','products_id', 'cost','costText_ar','costText_en', 'productsCount','productsCount_ar','productsCount_en', 'user_id'
    ];
          protected $casts = [
        'client_id' => 'integer',
        'cost' => 'double',
        'products_id' => 'integer',
        'productsCount' => 'integer',
         'user_id'=> 'integer',
    ];
      public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');

    }//end of Client*/
     public function items()
    {
        return $this->hasMany(CartDetails::class, 'carts_id','id')->with('Product');

    }//end of items
          public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');

    }//end of CartDetails*/
    
          public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
    
    
    
    
    
}
