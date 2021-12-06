<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
 protected $fillable = [
        'client_id', 'cost','paymentMethod','costText_ar','costText_en','status','	statusText_ar','statusText_en', 'productsCount',
        'productsCountText_ar','productsCountText_en', 'latitude','longitude','notes_ar','notes_en'
    ];
    
     protected $casts = [
        'client_id' => 'integer',
        'paymentMethod' => 'integer',
        'cost' => 'double',
        'productsCount' => 'integer',
        'status' => 'integer',
        
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);

    }//end of user

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order')->withPivot('quantity');

    }//end of products
    
        public function items()
    {
        return $this->hasMany(orderDetails::class, 'orders_id','id')->with('Product');

    }//end of CartDetails
           public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
    
    

}//end of model
