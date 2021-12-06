<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientBankBalance extends Model
{
     protected $fillable = [
        'client_id', 'nationalId','balance','nationalIdImageFront',
        'nationalIdImageBack','balanceText_ar','balanceText_en','commercialRegisterImageFront','commercialRegisterImageBack'
        ,'taxCardImage','activityLicenseImage','residenceContractImage','businessContractImage'
    ];
    
    
         protected $casts = [
        'client_id' => 'integer',
        'balance' => 'double',
      
    ];
        public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id','id');

    }//end of Client*/
}
