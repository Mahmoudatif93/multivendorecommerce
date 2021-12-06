<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','user_id'];


       public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/
    
    
}//end of model
