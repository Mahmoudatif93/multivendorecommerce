<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $fillable = [
        'name_ar', 'name_en', 'user_id'
    ];
    
         protected $casts = [
        'id' => 'integer',
        'user_id'=> 'integer',
    ];
    
    public $lang;
    public function regions()
    {
        
            return $this->hasMany(Regions::class);
        }
        

    
    public function Client()
    {
        return $this->hasMany(Client::class,'id','cities_id');

    }//end of Client
    
           public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
    
    
        
    
    public function regions_ar() 
{
      return $this->hasMany(Regions::class)->select('name_ar as name','id','cities_id');
}
    public function regions_en() 
{
      return $this->hasMany(Regions::class)->select('name_en as name','id','cities_id');
}
     
    
}
