<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\SaleType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SaleTypeController extends Controller
{
     use ApiResourceTrait; /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $SaleType=SaleType::all();
         $count = DB::table('sale_types')->count();
         
         return $this->apiResponse($SaleType,'',200,'success',$count);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $SaleType=new SaleType();
        $SaleType->title=$request->title;
         $SaleType->type=$request->type;
         $SaleType->from_id=$request->from_id;
         /*if($request->type == null){
             $SaleType->from_id=$SaleType->title;
         }else{
             $SaleType->from_id== null;
         }*/
         
             
        if($SaleType->save()){
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
      //return SaleType::find($id);
      $SaleType = DB::table('sale_types')->where('from_id',$id)->select('*')->get();
       $count = DB::table('sale_types')->where('from_id',$id)->select('*')->count();
       return $this->apiResponse($SaleType,'',200,'success',$count);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
