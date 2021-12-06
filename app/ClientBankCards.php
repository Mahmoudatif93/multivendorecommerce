<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientBankCards extends Model
{
      protected $fillable = [
        'client_id', 'cardType','cardName','cardNumber','expMonth','expYear','cvv'
    ];
         protected $casts = [
        'client_id' => 'integer',
    ];
        public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id','id');


    }//end of Client*/
}
