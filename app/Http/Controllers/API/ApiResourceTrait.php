<?php
namespace App\Http\Controllers\API;

    trait ApiResourceTrait{



        public function apiResponse($data=null,$error=null,$code=200,$msg=null,$count=0){
            $array=[
            
                'data'=>$data,
                "itemsCount"=>$count,
                'success'=>$data==null ? false:true,
                'statusCode' =>$code,
                'message' =>$msg,
            ];
            return response($array);
        }
        
        
           public function apiResponse2($isFound=false,$isCodeSent=false,$msg=null){
            $array=[
            
                'isFound'=>$isFound,
                "isCodeSent"=>$isCodeSent,
                'message' =>$msg,
            ];
            return response($array);
        }
        
        
           public function apiResponse3($isCodeValid=false,$msg=null){
            $array=[
            
                'isCodeValid'=>$isCodeValid,
                'message' =>$msg,
            ];
            return response($array);
        }
        
        
        
        
          public function apiResponse4($items=null){
            $array=[
            
                'items'=>$items,
              
            ];
            return response($array);
        }
        
        public function apiResponse5($client=null,$msg=null){
            $array=[
            
                'client'=>$client,
                'success'=>$client==null ? false:true,
                'message' =>$msg,
            ];
            return response($array);
        }
        
        public function apiResponse6($data=null,$msg=null){
            $array=[
            
                'data'=>$data,
            
                'success'=>$data==null ? false:true,
               
                'message' =>$msg,
            ];
            return response($array);
        }
        
         public function apiResponse7($data=null,$msg=null){
            $array=[
            
            
                'success'=>$data==null ? false:true,
               
                'message' =>$msg,
            ];
            return response($array);
        }
        
         public function apiResponse8($items=null,$page=0,$pageItems=0,$totalPages){
            $array=[
            
                'items'=>$items,
                "page"=>$page,
                //'success'=>$items==null ? false:true,
                'pageItems' =>$pageItems,
                'totalPages' =>$totalPages,
            ];
            return response($array);
        }
        
        
         public function apiResponse9($items=null,$isFound=false,$msg=null){
            $array=[
            
                'items'=>$items,
               'isFound'=>$isFound,
             'success'=>$items==null ? false:true,
                'message' =>$msg,
            ];
            return response($array);
        }
        
         public function apiResponse10($product=null,$isFound=false,$msg=null){
            $array=[
            
                'product'=>$product,
               'isFound'=>$isFound,
             'success'=>$product==null ? false:true,
                'message' =>$msg,
            ];
            return response($array);
        }
        
              public function apiResponse11($data=null){
         
                $array=[
            
                'data'=>$data,
      
            ];
            return response($array);
            return response($data);
        }
        
        public function apiResponse12($order=null,$isFound=false,$msg=null){
            $array=[
            
                'order'=>$order,
               'isFound'=>$isFound,
             'success'=>$order==null ? false:true,
                'message' =>$msg,
            ];
            return response($array);
        }
        
          public function apiResponse13($isFound,$success,$msg=null){
            $array=[
            
        
               'isCurrentPasswordValid'=>$isFound,
             'success'=>$success,
                'message' =>$msg,
            ];
            return response($array);
        }
        public function apiResponse14($isFirstRequest,$data=null,$msg=null){
            $array=[
            
            'isFirstRequest'=>$isFirstRequest,
                'success'=>$data==null ? false:true,
               
                'message' =>$msg,
            ];
            return response($array);
        }
        
        
        
        
        public function apiResponse15($data=null,$isFirstRequest,$msg=null){
            $array=[
             'data'=>$data,
            'isFirstRequest'=>$isFirstRequest,
                'success'=>$data==null ? false:true,
               
                'message' =>$msg,
            ];
            return response($array);
        }
        
        
    }


?>
