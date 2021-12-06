<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/*use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;*/

class Product extends Model
{
    //use Translatable; // 2. To add translation methods

    protected $guarded = ['id'];

   // public $translatedAttributes = ['name', 'description'];
    protected $appends = ['image_path', 'profit_percent'];

 protected $fillable = [
        'name_ar', 'name_en','image','category_id','product_providers_id','price','pricetext_ar','pricetext_en','type','availableAmount','availableAmountText_ar'
        ,'availableAmountText_en','type_ar','type_en','description_ar','description_en','user_id'
    ];
    
     protected $casts = [
        'category_id' => 'integer',
        'product_providers_id' => 'integer',
        'price' => 'double',
        'availableAmount' => 'integer',
         'user_id'=> 'integer',
   
    ];
    
    
    public function getImagePathAttribute()
    {
        return asset('uploads/product_images/' . $this->image);

    }//end of image path attribute

    public function getProfitPercentAttribute()
    {
       /* $profit = $this->sale_price - $this->purchase_price;
        $profit_percent = $profit * 100 / $this->purchase_price;
        return number_format($profit_percent, 2);*/

    }//end of get profit attribute

    public function category()
    {
        return $this->belongsTo(Category::class);

    }//end fo category

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_order');

    }//end of orders*/

    public function ProductProvider()
    {
        return $this->belongsTo(ProductProvider::class,'product_providers_id','id');

    }//end fo category
        public function CartDetails()
         {
        return $this->hasMany(CartDetails::class,'products_id','id')->with('category');

        }//end of CartDetails
        
                 public function User()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of User*/

}//end of model
