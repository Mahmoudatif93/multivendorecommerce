<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description','user_id'];
            public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/

}//end of model
