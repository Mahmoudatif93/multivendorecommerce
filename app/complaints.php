<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class complaints extends Model
{
     protected $fillable = [
        'phone', 'name','email','message'
    ];
  protected $casts = [
        'phone' => 'integer',
        'id' => 'integer',
    ];
}
