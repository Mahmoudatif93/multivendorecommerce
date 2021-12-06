<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;
use App\Order;
use App\Client;
use App\Product;
use App\ClientBankBalance;
use App\ClientBankCards;
use App\orderDetails;
use App\CartDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
class CarController extends Controller
{
    use ApiResourceTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $post=Cart::all();
         $count = DB::table('carts')->count();

         return $this->apiResponse($post,'',200,'success',$count);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     

      public function Cart(Request $request)
    {
        $lang=$request->lang;
        $orderField=$request->orderField;
        $orderType=$request->orderType;
        $pageItems=$request->pageItems;
        $page=$request->page;
        if($orderField =='name'){
            $orderField='name_ar';
        }
        if($orderField ==null ){
            $orderField='id';
        }
        if($orderType ==null ){
            $orderType='asc';
        }
        if($pageItems <=0){
            $pageItems=20;
        }
         if($pageItems >100){
            $pageItems=100;
        }
        
     
           if($lang=='ar'){
           // $cat=$this->product_details();
         $categories=Cart::where('client_id',$request->clientId)->with('items:id,carts_id,products_id,client_id,productCount,productCount_ar as productCounttext ,created_at,updated_at')->select('id','cost','costText_ar as costText','client_id','productsCount','productsCount_ar as productsCountText','created_at','updated_at')->orderBy($orderField, $orderType)->first();
         $count = Cart::where('client_id',$request->clientId)->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse11($categories);
        } else{
            
            $categories=Cart::where('client_id',$request->clientId)->with('items:id,carts_id,products_id,client_id,productCount,productCount_en as productCounttext,created_at,updated_at')->select('id','cost','costText_en as costText','client_id','productsCount','productsCount_en as productsCountText','created_at','updated_at')->orderBy($orderField, $orderType)->first();
         $count = Cart::where('client_id',$request->clientId)->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
          if($pageItems > $count && $count >0){
            $totalPages=1;
        }
           return $this->apiResponse11($categories);
         }
    }
    
    
    public function CartAdd(Request $request)
    {
        $carts=new Cart();
        $cartsdetails=new CartDetails();
        $existcarts=Cart::where('client_id',$request->clientId)->get();
        $existcartsdetails=CartDetails::where(array('client_id'=>$request->clientId,'products_id'=>$request->productId))->get();
        $cart=Cart::where('client_id',$request->clientId)->first();
        $products=Product::where('id',$request->productId)->first();
        if(count($existcartsdetails) >0)
        {
            $details=CartDetails::where(array('client_id'=>$request->clientId,'products_id'=>$request->productId))->first();
        

             $updatecartss['cost']= ($cart->cost + $products->price * $request->productCount);
             $updatecartss['costText_ar']=  ($cart->cost + $products->price * $request->productCount) . ' '. 'جنيه';
             $updatecartss['costText_en']=($cart->cost + $products->price * $request->productCount) .' '. 'EGP';
        
        if($carts->where('client_id',$request->clientId)->update($updatecartss)){
            $cartsdetailsupdate['productCount']=$request->productCount + $details->productCount;
            $cartsdetailsupdate['productCount_ar']= ($request->productCount + $details->productCount) . ' '. 'وحده';
            $cartsdetailsupdate['productCount_en']= ($request->productCount + $details->productCount) .' ' .'item';
            CartDetails::where(array('client_id'=>$request->clientId,'products_id'=>$request->productId))->update($cartsdetailsupdate);
            return  $this->apiResponse7(true,'Product added to cart successfully');
            }else{
                return $this->apiResponse7(false,'error to add Product to Cart');
            }
        }
        if(count($existcarts) >0)
        {
             $updatecarts['client_id']=$request->clientId;
             $updatecarts['cost']= ($cart->cost + $products->price * $request->productCount);
             $updatecarts['costText_ar']=  ($cart->cost + $products->price * $request->productCount) . ' '. 'جنيه';
             $updatecarts['costText_en']=($cart->cost + $products->price * $request->productCount) . ' '.'EGP';
            $updatecarts['productsCount']= $cart->productsCount + 1;
            $updatecarts['productsCount_ar']= ($cart->productsCount +1). ' '. 'وحده';
           $updatecarts['productsCount_en']= ($cart->productsCount + 1) .' ' .'item';
      
        if($carts->where('client_id',$request->clientId)->update($updatecarts)){
            $cartsdetails->carts_id=$cart->id;
            $cartsdetails->client_id=$request->clientId;
            $cartsdetails->products_id=$request->productId;
            $cartsdetails->productCount=$request->productCount;
            $cartsdetails->productCount_ar='وحده' .' '.$request->productCount ;
            $cartsdetails->productCount_en=$request->productCount .' ' .'item';
      
          $cartsdetails->save();
         
           return  $this->apiResponse7(true,'Product added to cart successfully');
        }else{
          //  return response()->json(['status'=>'error']);
          return $this->apiResponse7(false,'error to add Product to Cart');
        }
            
            
            
        }else{
              
             $carts->client_id=$request->clientId;
             $carts->cost=$products->price * $request->productCount;
             $carts->costText_ar=$products->price * $request->productCount . ' '. 'جنيه';
             $carts->costText_en=$products->price * $request->productCount . ' '. 'EGP';
            $carts->productsCount= 1;
            $carts->productsCount_ar='1'. ' '. 'وحده';
           $carts->productsCount_en='1 ' .' ' .'item';
      
        if($carts->save()){
            $cartsdetails->carts_id=$carts->id;
            $cartsdetails->client_id=$request->clientId;
            $cartsdetails->products_id=$request->productId;
            $cartsdetails->productCount=$request->productCount;
            $cartsdetails->productCount_ar=$request->productCount . ' '. 'وحده';
            $cartsdetails->productCount_en=$request->productCount .' ' .'item';
      
          $cartsdetails->save();
         
           return  $this->apiResponse7(true,'Product added to cart successfully');
        }else{
          //  return response()->json(['status'=>'error']);
          return $this->apiResponse7(false,'error to add Product to Cart');
        }
        }
      
    }
    
     public function CartUpdate(Request $request)
    {

        $data['productCount']=$request->productCount;
        $data['productCount_en']=$request->productCount .' '. 'item';
        $data['productCount_ar']=$request->productCount. ' '. 'وحده';
        $products=Product::where('id',$request->productId)->first();
       
       $details=CartDetails::where(array('client_id'=>$request->clientId,'products_id'=>$request->productId))->first();
         $cart=Cart::where('client_id',$request->clientId)->first();
       if($details->productCount < $request->productCount){
        
           $carts['cost']=$cart->cost + ($products->price * ($request->productCount - $details->productCount ) );
           $carts['costText_ar']= ($cart->cost + ($products->price * ($request->productCount - $details->productCount ) )). ' '. 'جنيه';
           $carts['costText_en']=($cart->cost + ($products->price * ($request->productCount - $details->productCount ) )).' ' .'EGP';
           if(Cart::where('client_id',$request->clientId)->update($carts)){
            CartDetails::where(array('client_id'=>$request->clientId,'products_id'=>$request->productId))->update($data);
           return  $this->apiResponse7(true,'Product updated to cart successfully');
           }else{
            return  $this->apiResponse7(false,'falied to  update product in cart');
           }
        
       }else{
           $cartss['cost']=$cart->cost - ($products->price * ($details->productCount -$request->productCount ) ) ;
           $cartss['costText_ar']= ($cart->cost - ($products->price * ($details->productCount - $request->productCount ) )) . ' '. 'جنيه';
           $cartss['costText_en']=($cart->cost - ($products->price * ($details->productCount - $request->productCount ) )).' ' .'EGP';
          if(Cart::where('client_id',$request->clientId)->update($cartss)){
            CartDetails::where(array('client_id'=>$request->clientId,'products_id'=>$request->productId))->update($data);
           return  $this->apiResponse7(true,'Product updated to cart successfully');
            }else{
            return  $this->apiResponse7(false,'falied to  update product in cart');
           }
       }
         
       
    }
     public function CartDelete(Request $request)
    {
    
     
          $cartdetails= CartDetails::where(array('client_id'=>$request->clientId,'products_id'=>$request->productId))->first();
            $delete= CartDetails::where(array('client_id'=>$request->clientId,'products_id'=>$request->productId))->delete();
           $cart=Cart::where('client_id',$request->clientId)->first();
           $products=Product::where('id',$request->productId)->first();
             $updatecartss['cost']= ($cart->cost - ($products->price * $cartdetails->productCount) );
             $updatecartss['costText_ar']= ($cart->cost - ($products->price * $cartdetails->productCount) )  . ' '. 'جنيه';
             $updatecartss['costText_en']=($cart->cost - ($products->price * $cartdetails->productCount)) .' '. 'EGP';
          $updatecartss['productsCount']=$cart->productsCount -1;
        $updatecartss['productsCount']=($cart->productsCount -1) .' '. 'item';
        $updatecartss['productsCount_ar']=($cart->productsCount -1) . ' '. 'وحده';
        Cart::where('client_id',$request->clientId)->update($updatecartss);
       
         
          if($delete){
                 $cadetails= CartDetails::where(array('client_id'=>$request->clientId))->get();
                 if(count($cadetails) ==0){
                     $deletes=  Cart::where(array('client_id'=>$request->clientId))->delete();
                     return  $this->apiResponse7(true,'Product deleted from cart successfully');
                 }else{
                     return  $this->apiResponse7(true,'Product deleted from cart successfully');
                 }
           
          }else{
            return  $this->apiResponse7(false,'Fail to Delete Product');
          }
           
    }
     public function OrderConfirm(Request $request)
    {
          $cart= Cart::where(array('id'=>$request->cartId))->first();
          $cartdetails=  CartDetails::where(array('carts_id'=>$request->cartId))->get();
          
         $order=new Order();
         
        $order->client_id=$cart->client_id;
        $order->cost=$cart->cost;
        $order->costText_ar= $cart->cost . ' '. 'جنيه';
        $order->costText_en=$cart->cost . ' '.'EGP';
        $order->productsCount=$cart->productsCount;
        $order->paymentMethod=$request->paymentMethod;
        $order->productsCountText_ar=$cart->productsCount. ' '. 'وحده';
        $order->productsCountText_en=$cart->productsCount .' ' .'item';
        $order->latitude=$request->latitude;
        $order->longitude=$request->longitude;
        $order->notes_ar=$request->notes_ar;
        $order->notes_en=$request->notes_en;
         $order->status=1;
        $order->statusText_ar='قيد التوصيل';
        $order->statusText_en='pending delivery';
        if($order->save()){
               foreach($cartdetails as $row){
                $ordersdetails=new orderDetails();
                  $ordersdetails->orders_id=$order->id;
                   $ordersdetails->client_id=$order->client_id;
                   $ordersdetails->products_id=$row->products_id;
                   $ordersdetails->productCount=$row->productCount;
                  $ordersdetails->productCount_ar=$row->productCount . ' '. 'وحده';
                  $ordersdetails->productCount_en=$row->productCount .' ' .'item';
               $ordersdetails->save();
               }
          Cart::where(array('id'=>$request->cartId))->delete();
           return  $this->apiResponse7(true,'Order confirmed successfully');
          
        }else{
          //  return response()->json(['status'=>'error']);
          return $this->apiResponse7(false,'Fail to confirmed order');
        }
           
    }
    
     public function Orders(Request $request)
    {
        $lang=$request->lang;
        $orderField=$request->orderField;
        $orderType=$request->orderType;
        $pageItems=$request->pageItems;
        $page=$request->page;
        if($orderField =='name'){
            $orderField='name_ar';
        }
        if($orderField ==null ){
            $orderField='id';
        }
        if($orderType ==null ){
            $orderType='asc';
        }
        if($pageItems <=0){
            $pageItems=20;
        }
         if($pageItems >100){
            $pageItems=100;
        }
     
           if($lang=='ar'){
         $categories=Order::where('client_id',$request->clientId)->with('items:id,orders_id,products_id,client_id,productCount,productCount_ar as productCountText ,created_at,updated_at')->select('id','cost','costText_ar as costText', 'status', 'statusText_ar as statusText','client_id','productsCount','productsCountText_ar as productsCountText','paymentMethod','latitude','longitude','notes_ar as notes','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count = Order::where('client_id',$request->clientId)->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse8($categories,$page,$pageItems,ceil($totalPages));
        } else{
            $categories=Order::where('client_id',$request->clientId)->with('items:id,orders_id,products_id,client_id,productCount,productCount_en as productCountText,created_at,updated_at')->select('id','cost','costText_en as costText', 'status', 'statusText_en as statusText','client_id','productsCount','productsCountText_en as productsCountText','paymentMethod','latitude','longitude','notes_en as notes','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
        $count = Order::where('client_id',$request->clientId)->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
          if($pageItems > $count && $count >0){
            $totalPages=1;
        }
          return $this->apiResponse8($categories,$page,$pageItems,ceil($totalPages));
         }
    }
    




 public function OrdersById(Request $request,$id)
    {
        $lang=$request->lang;

        
     
           if($lang=='ar'){
         $categories=Order::where('id',$id)->with('items:id,orders_id,products_id,client_id,productCount,productCount_ar as productCountText,created_at,updated_at')->select('id','cost','costText_ar as costText', 'status', 'statusText_ar as statusText','client_id','productsCount','productsCountText_ar as productsCountText','paymentMethod','latitude','longitude','notes_ar as notes','created_at','updated_at')->first();
  if($categories){
    return $this->apiResponse12($categories,true,'Valid Id');
  }else{
    return $this->apiResponse12($categories,false,'not Valid Id');
  }
         
        } else{
            $categories=Order::where('id',$id)->with('items:id,orders_id,products_id,client_id,productCount,productCount_en as productCountText,created_at,updated_at')->select('id','cost','costText_en as costText', 'status', 'statusText_en as statusText','client_id','productsCount','productsCountText_en as productsCountText','paymentMethod','latitude','longitude','notes_en as notes','created_at','updated_at')->first();
          if($categories){
    return $this->apiResponse12($categories,true,'Valid Id');
  }else{
    return $this->apiResponse12($categories,false,'not Valid Id');
  }
         }
    }







public function addclientbalance(Request $request){
  //  dd($request);
  $exist=ClientBankBalance::where('client_id',$request->client_id)->get();
  if(count($exist) <1){
    
  
        $clientbalance=new ClientBankBalance();
        $clientbalance->client_id=$request->client_id;
        $clientbalance->nationalId=$request->nationalId;
      $clientbalance->balance=0;
        $clientbalance->balanceText_ar=0 .' ' .'جنيه';
        $clientbalance->balanceText_en=0 .' ' .'EGP';
           if (request('nationalIdImageFront')) {
            Image::make(request('nationalIdImageFront'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('nationalIdImageFront')->hashName()));

               $clientbalance->nationalIdImageFront = 'user_images/'.request('nationalIdImageFront')->hashName();
                }else{
                    $clientbalance->nationalIdImageFront = 'noimage';
                }
                  if (request('nationalIdImageBack')) {

            Image::make(request('nationalIdImageBack'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('nationalIdImageBack')->hashName()));

            $clientbalance->nationalIdImageBack  = 'user_images/'.request('nationalIdImageBack')->hashName();
                }else{
                   $clientbalance->nationalIdImageBack  = 'noimage';
                }
        
        
             if (request('commercialRegisterImageFront')) {
            Image::make(request('commercialRegisterImageFront'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('commercialRegisterImageFront')->hashName()));

               $clientbalance->commercialRegisterImageFront = 'user_images/'.request('commercialRegisterImageFront')->hashName();
                }else{
                    $clientbalance->commercialRegisterImageFront = 'noimage';
                }
                
                   if (request('commercialRegisterImageBack')) {
            Image::make(request('commercialRegisterImageBack'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('commercialRegisterImageBack')->hashName()));

               $clientbalance->commercialRegisterImageBack = 'user_images/'.request('commercialRegisterImageBack')->hashName();
                }else{
                    $clientbalance->commercialRegisterImageBack = 'noimage';
                }
                
                   if (request('taxCardImage')) {
            Image::make(request('taxCardImage'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('taxCardImage')->hashName()));

               $clientbalance->taxCardImage = 'user_images/'.request('taxCardImage')->hashName();
                }else{
                    $clientbalance->taxCardImage = 'noimage';
                }
                
                
                   if (request('activityLicenseImage')) {
            Image::make(request('activityLicenseImage'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('activityLicenseImage')->hashName()));

               $clientbalance->activityLicenseImage = 'user_images/'.request('activityLicenseImage')->hashName();
                }else{
                    $clientbalance->activityLicenseImage = 'noimage';
                }
                
                
                   if (request('residenceContractImage')) {
            Image::make(request('residenceContractImage'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('residenceContractImage')->hashName()));

               $clientbalance->residenceContractImage = 'user_images/'.request('residenceContractImage')->hashName();
                }else{
                    $clientbalance->residenceContractImage = 'noimage';
                }
                     if (request('businessContractImage')) {
            Image::make(request('businessContractImage'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('businessContractImage')->hashName()));

               $clientbalance->businessContractImage = 'user_images/'.request('businessContractImage')->hashName();
                }else{
                    $clientbalance->businessContractImage = 'noimage';
                }
                
        if($clientbalance->save()){
           return  $this->apiResponse15($clientbalance,true,true,'Request Recived');
        }else{
          return $this->apiResponse15(null,false,false,'Fail to Recive Request');
        }
        }else{
            return $this->apiResponse15(null,false,false,'exist before');
        }
}


    public function updateprofile(Request $request)
    {
        
       
        
        if($request->businessTypeId !=null){
            $data['businessTypeId']=$request->businessTypeId;
        }
        if($request->cityId !=null){
                   $data['cities_id']=$request->cityId;
        }
        if($request->regionId !=null){
               $data['regions_id']=$request->regionId;
        }
        
        if($request->email !=null){
         $data['email']=$request->email;
        }
        if($request->businessName !=null){
             $data['businessName']=$request->businessName;
        }
        if($request->businessType !=null){
              $data['businessType']=$request->businessType;
        }
        
        if($request->businessOtherType !=null){
            $data['businessOtherType']=$request->businessOtherType;
        }
        if($request->address !=null){
                   $data['address']=$request->address;
        }
        if($request->mapAddress !=null){
              $data['mapAddress']=$request->mapAddress;
        }
        
        
        
        if($request->type !=null){
            $data['type']=$request->type;
        }
        if($request->latitude !=null){
           $data['latitude']=$request->latitude;
        }
        if($request->longitude !=null){
               
        $data['longitude']=$request->longitude;
        }
    
        if($request->password !=null){
               
        $data['password']=Hash::make($request->password);
        }
    
          if($request->firebaseToken !=null){
                   $data['firebaseToken']=$request->firebaseToken;
        }
        
       
        Client::where('id',$request->clientId)->update($data);
       $client= Client::where('id',$request->clientId)->first();
        return $this->apiResponse5($client,'success');
      
           
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return posts::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
}
