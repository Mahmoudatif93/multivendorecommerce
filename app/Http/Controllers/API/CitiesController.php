<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cities;
use App\Regions;
use App\BussinessType;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class CitiesController extends Controller
{
    use ApiResourceTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lang=$request->lang;
       
         $cties=Cities::with('regions')->select('id','name_ar')->get();
         $count = DB::table('cities')->count();
         
         return $this->apiResponse($cties,'',200,'success',$count);
    }
    
     public function cities(Request $request)
    {
        $lang=$request->lang;
          $orderField=$request->orderField;
        $orderType=$request->orderType;
        if($orderField =='name'){
            $orderField='name_ar';
        }
        if($orderField ==null ){
            $orderField='id';
        }
        if($orderType ==null ){
            $orderType='asc';
        }
        
       if($lang=='ar'){
        $cties=Cities::with('regions:cities_id,id,name_ar as name,created_at,updated_at')->select('id','name_ar as name','created_at','updated_at')->orderBy($orderField, $orderType)->get();
         $count = DB::table('cities')->count();
         return $this->apiResponse4($cties);
       }else{
       
        $cties=Cities::with('regions:cities_id,id,name_en as name,created_at,updated_at')->select('id','name_en as name','created_at','updated_at')->orderBy($orderField, $orderType)->get();
         $count = DB::table('cities')->count();
         return $this->apiResponse4($cties);
       }
         
    }
    
    
    
      public function regions(Request $request)
    {
        $lang=$request->lang;
           $orderField=$request->orderField;
        $orderType=$request->orderType;
        if($orderField =='name'){
            $orderField='name_ar';
        }
        if($orderField ==null ){
            $orderField='id';
        }
        if($orderType ==null ){
            $orderType='asc';
        }
        
       if($lang=='ar'){
         $cities_id=$request->cities_id;
         $regions=Regions::where('cities_id',$cities_id)->select('id','cities_id','name_ar as name','created_at','updated_at')->orderBy($orderField, $orderType)->get();
         $count = Regions::where('cities_id',$cities_id)->count();

         if($count >0){
              return $this->apiResponse4($regions);
         }else{
             return $this->apiResponse4($regions);
         }
         }else{
              $cities_id=$request->cities_id;
         $regions=Regions::where('cities_id',$cities_id)->select('id','cities_id','name_en as name','created_at','updated_at')->orderBy($orderField, $orderType)->get();
         $count = Regions::where('cities_id',$cities_id)->count();

         if($count >0){
              return $this->apiResponse4($regions);
         }else{
             return $this->apiResponse4($regions);
         }
         }
       
    }
     public function BussinessType(Request $request)
    { 
        $lang=$request->lang;
        $orderField=$request->orderField;
        $orderType=$request->orderType;
        if($orderField =='type'){
            $orderField='name_ar';
        }
        if($orderField ==null ){
            $orderField='id';
        }
        if($orderType ==null ){
            $orderType='asc';
        }
       if($lang=='ar'){
         $bussiness_types=BussinessType::select('id','name_ar as type','created_at','updated_at')->orderBy($orderField, $orderType)->get();
         $count = DB::table('bussiness_types')->count();
         return $this->apiResponse4($bussiness_types);
         }else{
             $bussiness_types=BussinessType::select('id','name_en as type','created_at','updated_at')->orderBy($orderField, $orderType)->get();
         $count = DB::table('bussiness_types')->count();
         return $this->apiResponse4($bussiness_types);
         }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post=new posts();
        $post->title=$request->title;
        //$post->image=$request->image;
        
            /*if ($request->image) {

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $post['image'] = $request->image->hashName();

        }//end of if*/
        if($post->save()){
           // return response()->json(['status'=>'success']);
           return  $this->apiResponse('',200);
        }else{
          //  return response()->json(['status'=>'error']);
          return $this->apiResponse('','erro to stor category',404);
        }
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
        $post=posts::find($id);
        $post->title=$request->title;
        $post->body=$request->body;
    //  dd($request->title);
        if($post->update()){
            return response()->json(['status'=>'success']);
        }else{
            return response()->json(['status'=>'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post= posts::find($id);

        if(  $post->delete()){
            return response()->json(['status'=>'success']);
        }else{
            return response()->json(['status'=>'error']);
        }
    }
}
