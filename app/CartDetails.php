<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartDetails extends Model
{
      protected $fillable = [
        'carts_id','client_id', 'products_id','productCount','productCount_ar','productCount_en','user_id'
    ];
    
    
          protected $casts = [
        'client_id' => 'integer',
        'carts_id' => 'integer',
        'cost' => 'double',
        'products_id' => 'integer',
        'productCount' => 'integer',
        'user_id'=> 'integer',
    ];
    
      public function Cart()
    {
        return $this->belongsTo(Cart::class, 'carts_id','id');

    }//end of Cart*/
    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');

    }//end of Client*/
    
      public function Product()
    {
        return $this->belongsTo(Product::class,'products_id')->with('category');

    }//end of Product*/
    
    
           public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
    
}
