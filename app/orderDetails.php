<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderDetails extends Model
{
      protected $fillable = [
        'orders_id','client_id', 'products_id','productCount','productCount_ar','productCount_en','user_id'
    ];
      protected $casts = [
        'client_id' => 'integer',
        'orders_id' => 'integer',
        'cost' => 'double',
        'products_id' => 'integer',
        'productCount' => 'integer',
         'user_id'=> 'integer',
    ];
      public function Order()
    {
        return $this->belongsTo(Order::class, 'orders_id');

    }//end of Order*/
    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');

    }//end of Client*/
    
      public function Product()
    {
        return $this->belongsTo(Product::class, 'products_id')->with('category');

    }//end of Product*/
           public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
    
}
