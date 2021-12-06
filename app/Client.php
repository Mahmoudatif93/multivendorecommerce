<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laratrust\Traits\LaratrustUserTrait;

class Client extends Authenticatable implements JWTSubject
{
    protected $guarded = [];
 protected $fillable = [
        'phone', 'name','email','password', 'businessName','businessTypeId','businessType', 'businessOtherType','cities_id'
        ,'regions_id', 'address','mapAddress','type', 'latitude','longitude','enable','firebaseToken'
    ];
  /*  protected $casts = [
        'phone' => 'array'
    ];
*/

     protected $casts = [
        
        'regions_id' => 'integer',
        'businessTypeId' => 'integer',
        'cities_id' => 'integer',
        'enable' => 'integer',
        'type' => 'integer',
    ];


   public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getNameAttribute($value)
    {
        return ucfirst($value);

    }//end of get name attribute

    public function orders()
    {
        return $this->hasMany(Order::class);

    }//end of orders

    public function BussinessType()
    {
        return $this->belongsTo(BussinessType::class,'id','businessTypeId');

    }//end fo BussinessType
    public function Cities()
    {
        return $this->belongsTo(Cities::class,'id','cities_id');

    }//end fo Cities
    public function Regions()
    {
        return $this->belongsTo(Regions::class,'id','regions_id');

    }//end fo Regions

     public function Cart()
    {
        return $this->hasMany(Cart::class);

    }//end of Cart
    
     public function CartDetails()
    {
        return $this->hasMany(CartDetails::class);

    }//end of CartDetails
    
    public function ClientBankBalance()
    {
        return $this->hasOne(ClientBankBalance::class);

    }//end of ClientBankBalance
     public function ClientBankCards()
    {
        return $this->hasOne(ClientBankCards::class);
    }//end of ClientBankCards
    

}//end of model
