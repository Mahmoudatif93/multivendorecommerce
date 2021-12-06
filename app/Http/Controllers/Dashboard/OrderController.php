<?php

namespace App\Http\Controllers\Dashboard;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->id !=1 ){
        $orders = Order::where('user_id',auth()->user()->id)->whereHas('client', function ($q) use ($request) {

            return $q->where('name', 'like', '%' . $request->search . '%');

        })->paginate(5);
}else{
    $orders = Order::whereHas('client', function ($q) use ($request) {

            return $q->where('name', 'like', '%' . $request->search . '%');

        })->paginate(5);
}
        return view('dashboard.orders.index', compact('orders'));

    }//end of index

    public function products(Order $order)
    {
        $products = $order->items;
        return view('dashboard.orders._products', compact('order', 'products'));

    }//end of products
    
    public function destroy(Order $order)
    {
        foreach ($order->products as $product) {

            $product->update([
                'stock' => $product->stock + $product->pivot->quantity
            ]);

        }//end of for each

        $order->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');
    
    }//end of order
    
    
    public function changestatus(Request $request){
        
        $status['status']=$request->status;
        if($request->status==1){
              $body= 'الطلب قيد التوصيل';
            $status['statusText_en']='pending delivery';
            $status['statusText_ar']='قيد التوصيل';
        }
        if($request->status==2){
             $body= 'تم توصيل الطلب';
            $status['statusText_en']='Delivered';
            $status['statusText_ar']='تم التوصيل';
        }
          if($request->status==3){
             $body= 'تم الغاء الطلب';
            $status['statusText_en']='Cancelled';
            $status['statusText_ar']='تم الالغاء';
        }
       Order::where('id',$request->id)->update($status);
       $order= Order::where('id',$request->id)->with('client')->first();
        
         //========================================

       define('API_ACCESS_KEY','AAAAzhKvS1k:APA91bEKV40XsqI9YoF_Ugu3eUY8vzuSa0AlMwa0GpK71vDyXzybImLGu46poQ-v912r8bj7QT5TbVGcfJpYdkYYgbpSEkAfvF7EYvw81_TegQz6STdLqIUYfPc3nhwg7W9ixYcGLv-2');	
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        
         
        

          $notification = [
            'title' =>'order',
            'body' => $body ,
             'icon' =>'',
            'sound' => ''
         ];
         $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

         $fcmNotification = [
           // 'to' => '/topics/ads',
           
           'to' => $order->client->firebaseToken, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
       // echo $result;
        curl_close($ch);
      
      //============================
        
        
        
        
        
        
        
        
        
        
        
        
    }
    
    
    
    
     public function addnotes(Request $request)
    {
        $post_id = $request->id;

        return view('dashboard.orders.details', compact('post_id'));
    }
    
    public function updatenotes(Request $request){
        
        $status['notes_ar']=$request->notes_ar;
            $status['notes_en']=$request->notes_en;
         Order::where('id',$request->id)->update($status);
         
          session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}//end of controller
