<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BookedDirectSale;

class posts extends Model
{
     public function BookedDirectSale()
    {
        return $this->hasMany(BookedDirectSale::class,'cate_id','id');

    }
}
