<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $fillable = [
        'name_ar', 'name_en','cities_id','user_id'
    ];
  protected $casts = [
        'cities_id' => 'integer',
        'id' => 'integer',
                 'user_id'=> 'integer',
        
    ];
    public function cities()
    {
        return $this->belongsTo(Cities::class);

    }//end fo Cities
    public function Client()
    {
        return $this->hasMany(Client::class,'id','regions_id');

    }//end of Client
            public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
}
