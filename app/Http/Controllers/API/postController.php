<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductProvider;
use App\product_category_ids;
use App\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use App\Product;
class postController extends Controller
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
       if($lang=='ar'){
         $providers=ProductProvider::select('id','name_ar as name','created_at','updated_at')->paginate(5);
         $count = DB::table('product_providers')->count();
         return $this->apiResponse($providers,'',200,'success',$count);
         }else{
            $providers=ProductProvider::select('id','name_en as name','created_at','updated_at')->paginate(5);
         $count = DB::table('product_providers')->count();
         return $this->apiResponse($providers,'',200,'success',$count);
         }
    }
    
    
    
      public function categories(Request $request)
    {           $lang=$request->lang;
        $orderField=$request->orderField;
        $orderType=$request->orderType;
        $pageItems=$request->pageItems;
        $page=$request->page;
         $searchTerm=$request->searchTerm;
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
         if($searchTerm !=null ){
          
          if($lang=='ar'){
         $categories=Category::where('name_ar', 'like', '%' . $request->searchTerm . '%')->orWhere('name_en', 'like', '%' . $request->searchTerm . '%')->select('id','name_ar as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count = Category::where('name_ar', 'like', '%' . $request->searchTerm . '%')->orWhere('name_en', 'like', '%' . $request->searchTerm . '%')->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse8($categories,$page,$pageItems,ceil($totalPages));
        } else{
            $categories=Category::where('name_ar', 'like', '%' . $request->searchTerm . '%')->orWhere('name_en', 'like', '%' . $request->searchTerm . '%')->select('id','name_en as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count =Category::where('name_ar', 'like', '%' . $request->searchTerm . '%')->orWhere('name_en', 'like', '%' . $request->searchTerm . '%')->count();
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
        
     if($lang=='ar'){
         $categories=Category::select('id','name_ar as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count = DB::table('categories')->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse8($categories,$page,$pageItems,ceil($totalPages));
        } else{
            $categories=Category::select('id','name_en as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count = DB::table('categories')->count();
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
    
    
     public function providers(Request $request)
    {
           $lang=$request->lang;
           $orderField=$request->orderField;
        $orderType=$request->orderType;
        $pageItems=$request->pageItems;
        $page=$request->page;
        $category_id=$request->category_id;
      
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
        
       if($category_id != null){
        
        // $categories=Category::where('id',$category_id)->with('ProductProviders')->get();
         $product_categoryids=product_category_ids::where('category_id',$category_id)->pluck('product_providers_id')->toArray();
        $products=ProductProvider::whereIn('id',$product_categoryids)->with('ProductCategoryIds:id,category_id,product_providers_id')->select('id','name_ar as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $product_providers= [];

         foreach($product_categoryids as $row){
         $providers=ProductProvider::where('id',$row)->with('ProductCategoryIds:id,product_providers_id')->select('id','name_ar as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
          array_push($product_providers,$providers);
      
        $count = ProductProvider::where('id',$row)->count();
           $totalPages=$count/$pageItems;
            if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
     
           
        }
       
            return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
       
        }
        $providers=ProductProvider::with('ProductCategoryIds:id,category_id,product_providers_id')->select('id','name_ar as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count = DB::table('product_providers')->count();
           $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         return $this->apiResponse8($providers,$page,$pageItems,ceil($totalPages));
         }else{
        if($category_id != null){
          $product_categoryids=product_category_ids::where('category_id',$category_id)->pluck('product_providers_id')->toArray();
        $products=ProductProvider::whereIn('id',$product_categoryids)->with('ProductCategoryIds:id,category_id,product_providers_id')->select('id','name_en as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $product_providers= [];

         foreach($product_categoryids as $row){
         $providers=ProductProvider::where('id',$row)->with('ProductCategoryIds:id,product_providers_id')->select('id','name_en as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
        array_push($product_providers,$providers);
      
        $count = ProductProvider::where('id',$row)->count();
           $totalPages=$count/$pageItems;
       
         if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
           
        }
            return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
       
        }
        
            $providers=ProductProvider::with('ProductCategoryIds:id,category_id,product_providers_id')->select('id','name_en as name','image','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count = DB::table('product_providers')->count();
           $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse8($providers,$page,$pageItems,ceil($totalPages));
         }
    }

      public function products(Request $request)
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
        $category_id=$request->category_id;
        $product_providers_id=$request->product_providers_id;
        if($category_id !=null && $product_providers_id !=null){
                       if($lang=='ar'){
         $products=Product::where('product_providers_id',$product_providers_id)->where('category_id',$category_id)->with('category:id,name_ar as name')->with('ProductProvider:id,name_ar as name')->select('id','category_id','product_providers_id','name_ar as name','image','price','pricetext_ar as priceText','type','availableAmount','availableAmountText_ar as availableAmountText','type_ar as typeText','description_ar as description','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page); 
         $count =Product::where('category_id',$category_id)->where('product_providers_id',$product_providers_id)->count();
         $totalPages=$count/$pageItems;
          if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
           }else{
            $products=Product::where('product_providers_id',$product_providers_id)->where('category_id',$category_id)->with('category:id,name_en as name')->with('ProductProvider:id,name_en as name')->select('id','category_id','product_providers_id','name_en as name','image','price','pricetext_en as priceText','type','availableAmount','availableAmountText_en as availableAmountText','type_en As typeText','description_en as description','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
          $count =Product::where('product_providers_id',$product_providers_id)->where('category_id',$category_id)->count();
         $totalPages=$count/$pageItems;
        if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
           }
        }
        
        if($category_id !=null){
            if($lang=='ar'){
         $products=Product::where('category_id',$category_id)->with('category:id,name_ar as name')->with('ProductProvider:id,name_ar as name')->select('id','category_id','product_providers_id','name_ar as name','image','price','pricetext_ar as priceText','type','availableAmount','availableAmountText_ar as availableAmountText','type_ar as typeText','description_ar as description','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count =Product::where('category_id',$category_id)->count();
         $totalPages=$count/$pageItems;
          if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
           }else{
            $products=Product::where('category_id',$category_id)->with('category:id,name_en as name')->with('ProductProvider:id,name_en as name')->select('id','category_id','product_providers_id','name_en as name','image','price','pricetext_en as priceText','type','availableAmount','availableAmountText_en as availableAmountText','type_en as typeText','description_en as description','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
          $count =Product::where('category_id',$category_id)->count();
         $totalPages=$count/$pageItems;
        if($count < 1){
            $totalPages=0;
         }
         if($pageItems > $count && $count >0){
            $totalPages=1;
        }
         return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
           }
        }
        elseif($product_providers_id !=null){
            if($lang=='ar'){
         $products=Product::where('product_providers_id',$product_providers_id)->with('category:id,name_ar as name')->with('ProductProvider:id,name_ar as name')->select('id','category_id','product_providers_id','name_ar as name','image','price','pricetext_ar as priceText','type','availableAmount','availableAmountText_ar as availableAmountText','type_ar as typeText','description_ar as description','created_at','updated_at')->orderBy( $orderField, $orderType)->paginate($pageItems,$page);
         $count =Product::where('product_providers_id',$product_providers_id)->count();
         $totalPages=$count/$pageItems;
         if($count < 1) {
            $totalPages=0;
         }
         return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
           }else{
            $products=Product::where('product_providers_id',$product_providers_id)->with('category:id,name_en as name')->with('ProductProvider:id,name_en as name')->select('id','category_id','product_providers_id','name_en as name','image','price','pricetext_en as priceText','type','availableAmount','availableAmountText_en as availableAmountText','type_en as typeText','description_en as description','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
        $count =Product::where('product_providers_id',$product_providers_id)->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
           }  
        }else{
              if($lang=='ar'){
         $products=Product::with('category:id,name_ar as name')->with('ProductProvider:id,name_ar as name')->select('id','category_id','product_providers_id','name_ar as name','image','price','pricetext_ar as priceText','type','availableAmount','availableAmountText_ar as availableAmountText','type_ar as tyepText','description_ar as description','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
        $count = DB::table('products')->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
           }else{
            $products=Product::with('category:id,name_en as name')->with('ProductProvider:id,name_en as name')->select('id','category_id','product_providers_id','name_en as name','image','price','pricetext_en as priceText','type','availableAmount','availableAmountText_en as availableAmountText','type_en as typeText','description_en as description','created_at','updated_at')->orderBy($orderField, $orderType)->paginate($pageItems,$page);
         $count = DB::table('products')->count();
         $totalPages=$count/$pageItems;
         if($count < 1){
            $totalPages=0;
         }
         return $this->apiResponse8($products,$page,$pageItems,ceil($totalPages));
           }
        }
        
    }

    public function product_details($id,Request $request)
    {    $lang=$request->lang;
          if($lang=='ar'){
         $productsdetails=Product::where('id',$id)->with('category:id,name_ar as name,created_at,updated_at')->with('ProductProvider:id,name_ar as name,created_at,updated_at')->select('id','category_id','product_providers_id','name_ar as name','image','price','pricetext_ar as priceText','type','availableAmount','availableAmountText_ar as availableAmountText','type_ar as typeText','description_ar as description','created_at','updated_at')->first();
         $count = Product::where('id',$id)->count();
         if($count>0){
            return $this->apiResponse10($productsdetails,true,'Valid Id');
         }else{
            return $this->apiResponse10($productsdetails=null,false,'Not Valid Id');
         }
         
         }else{
            $productsdetails=Product::where('id',$id)->with('category:id,name_en as name,created_at,updated_at')->with('ProductProvider:id,name_en as name,created_at,updated_at')->select('id','category_id','product_providers_id','name_en as name','image','price','pricetext_en as priceText','type','availableAmount','availableAmountText_en as availableAmountText','type_en as typeText','description_en as description','created_at','updated_at')->first();
         $count = Product::where('id',$id)->count();
        if($count>0){
            return $this->apiResponse10($productsdetails,true,'Valid Id');
         }else{
            return $this->apiResponse10($productsdetails=null,false,'Not Valid Id');
         }
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
        return ProductProvider::find($id);
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
