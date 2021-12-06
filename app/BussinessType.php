<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BussinessType extends Model
{
    protected $fillable = [
        'name_ar', 'name_en', 'user_id'
    ];
   protected $casts = [
        'id' => 'integer',
        
          'user_id'=> 'integer',
    ];
    public function Client()
    {
        return $this->hasMany(Client::class,'id','businessTypeId');

    }//end of Client
       public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/

}
