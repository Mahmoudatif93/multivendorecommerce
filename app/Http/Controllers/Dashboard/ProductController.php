<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Product;
use App\product_category_ids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use App\ProductProvider;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
 if(auth()->user()->id !=1 ){
        $products = Product::where('user_id',auth()->user()->id)->when($request->search, function ($q) use ($request) {

          return $q->where('name_ar', 'like', '%' . $request->search . '%')
                ->orWhere('name_en', 'like', '%' . $request->search . '%');

        })->when($request->category_id, function ($q) use ($request) {

            return $q->where('category_id', $request->category_id);

        })->latest()->paginate(5);
}
else{
      $products = Product::when($request->search, function ($q) use ($request) {

          return $q->where('name_ar', 'like', '%' . $request->search . '%')
                ->orWhere('name_en', 'like', '%' . $request->search . '%');

        })->when($request->category_id, function ($q) use ($request) {

            return $q->where('category_id', $request->category_id);

        })->latest()->paginate(5);
}
        return view('dashboard.products.index', compact('categories', 'products'));

    }//end of index

    public function create()
    {
        $categories = Category::all();
        $productproviders = ProductProvider::all();
        return view('dashboard.products.create', compact('categories','productproviders'));

    }//end of create

    public function store(Request $request)
    {
        
      //  dd($request);
        $rules = [
            'category_id' => 'required',
            'product_providers_id' => 'required',
        ];

       /* foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => 'required|unique:product_translations,name'];
            $rules += [$locale . '.description' => 'required'];

        }//end of  for each*/

        $rules += [
           'name_ar' => 'required',
              'name_en' => 'required',
           'price' => 'required',
            'pricetext_ar' => 'required',
             'pricetext_ar' => 'required',
            'availableAmount' => 'required',
            'availableAmountText_ar' => 'required',
            'availableAmountText_en' => 'required',
            'type_ar' => 'required',
            'type_en' => 'required',
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if ($request->image) {

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = 'product_images/'.$request->image->hashName();

        }//end of if
      
        $replace = array('<p>','</p>');
        $description_ar =  str_replace($replace,'',$request->description_ar);
      $description_en=  str_replace($replace,'',$request->description_en);
      
       $request_data['description_ar']=$description_ar;
      $request_data['description_en']=$description_en ;
              if(auth()->user()->id !=1){
           
             $request_data['user_id']=auth()->user()->id;
         }else{
              $request_data['user_id']=null;
         }
         
        Product::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');

    }//end of store

    public function edit(Product $product)
    {
        $categories = Category::all();
        $productproviders = ProductProvider::all();
        return view('dashboard.products.edit', compact('categories', 'product','productproviders'));

    }//end of edit

    public function update(Request $request, Product $product)
    {
        $rules = [
             'category_id' => 'required',
            'product_providers_id' => 'required',
        ];

      /*  foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('product_translations', 'name')->ignore($product->id, 'product_id')]];
            $rules += [$locale . '.description' => 'required'];

        }//end of  for each*/

        $rules += [
                 'name_ar' => 'required',
              'name_en' => 'required',
           'price' => 'required',
            'pricetext_ar' => 'required',
             'pricetext_ar' => 'required',
            'availableAmount' => 'required',
            'availableAmountText_ar' => 'required',
            'availableAmountText_en' => 'required',
            'type_ar' => 'required',
            'type_en' => 'required',
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if ($request->image) {

            if ($product->image != 'default.png') {

                Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

            }//end of if

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = 'product_images/'.$request->image->hashName();

        }//end of if
       $replace = array('<p>','</p>');
        $description_ar =  str_replace($replace,'',$request->description_ar);
      $description_en=  str_replace($replace,'',$request->description_en);
      
       $request_data['description_ar']=$description_ar;
      $request_data['description_en']=$description_en ;
        $product->update($request_data);
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');

    }//end of update

    public function destroy(Product $product)
    {
        if ($product->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

        }//end of if

        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');

    }//end of destroy
    
    
    
    
    
    public function findproviderWitheID($id)
    {
         $productproviders = ProductProvider::where('id',$id)->first();
         $city= [];
         $cat_ids = product_category_ids::where(['product_providers_id' =>$productproviders->id])->get();
         foreach($cat_ids as $row){
            $cat = Category::where(['id' =>$row->category_id])->get();     
             array_push($city,$cat);
         }
         

/*
$cat = Category::where(['id' =>json_decode($productproviders->category_id, true)[$i]])->get();  
        for($i=0;$i< count(json_decode($productproviders->category_id));$i++){
           $cat = Category::where(['id' =>json_decode($productproviders->category_id, true)[$i]])->get();                   
           array_push($city,$cat);
        }*/
    
       return response()->json($city);
    }
    
    
    
    
    
    
    

}//end of controller
