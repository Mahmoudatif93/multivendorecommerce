<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsCode extends Model
{
    protected $fillable = [
        'phone', 'smscode'
    ];

  protected $casts = [
        'phone' => 'integer',
        'id' => 'integer',
    ];

}
