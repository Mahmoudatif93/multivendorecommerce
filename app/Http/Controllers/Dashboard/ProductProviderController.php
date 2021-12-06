<?php

namespace App\Http\Controllers\Dashboard;
use App\Category;
use App\ProductProvider;
use App\Product;
use App\product_category_ids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class ProductProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->id !=1 ){
        $productproviders = ProductProvider::where('user_id',auth()->user()->id)->when($request->search, function ($q) use ($request) {

              return $q->where('name_ar', 'like', '%' . $request->search . '%')
                ->orWhere('name_en', 'like', '%' . $request->search . '%');

        })->latest()->paginate(5);
   $cates=[];
   $catid=[];
        }else{
            
                $productproviders = ProductProvider::when($request->search, function ($q) use ($request) {

              return $q->where('name_ar', 'like', '%' . $request->search . '%')
                ->orWhere('name_en', 'like', '%' . $request->search . '%');

        })->latest()->paginate(5);
   $cates=[];
   $catid=[];
        }
        return view('dashboard.productprovider.index', compact('productproviders','cates','catid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('dashboard.productprovider.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules=   $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'category_id' => 'required'
        ]);

       // $request_data = $request->all();
     
    
        
         //$request_data['category_id']=json_encode($request->category_id);
             $request_data['name_ar']=$request->name_ar;
              $request_data['name_en']=$request->name_en;
          if ($request->image) {
                      Image::make($request->image)
                      ->resize(300, null, function ($constraint) {
                     $constraint->aspectRatio();
                      })
                     ->save(public_path('uploads/productprovider_images/' . $request->image->hashName()));
                    $request_data['image'] = 'productprovider_images/'.$request->image->hashName();
                 }//end of if
                         if(auth()->user()->id !=1){
           
             $request_data['user_id']=auth()->user()->id;
         }else{
              $request_data['user_id']=null;
         }
                 
       $id=   ProductProvider::create($request_data);
      
              for($i=0;$i< count($request->category_id);$i++){
                 $data['product_providers_id']= $id->id;
            $data['category_id']=$request->category_id[$i];
         product_category_ids::create($data);
        }
          
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.productprovider.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $productprovider = ProductProvider::where('id', $id)->first();
        $cates=product_category_ids::where('product_providers_id',$productprovider->id)->get();
    $catesIds = [];
        foreach($cates as $ids)
        {
            $catesIds[] = $ids->category_id;
        }    
        return view('dashboard.productprovider.edit', compact('productprovider', 'categories','cates','catesIds'));
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
        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'category_id' => 'required'
        ]);

       // $request_data = $request->except(['_token', '_method']);
       
       // $request_data['category_id']=json_encode($request->category_id);
             $request_data['name_ar']=$request->name_ar;
              $request_data['name_en']=$request->name_en;
        if ($request->image) {

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/productprovider_images/' . $request->image->hashName()));

            $request_data['image'] = 'productprovider_images/'.$request->image->hashName();

        }//end of if
       ProductProvider::where('id', $id)->update($request_data);
        product_category_ids::where('product_providers_id',$id)->delete();
            for($i=0;$i< count($request->category_id);$i++){
                 $data['product_providers_id']= $id;
            $data['category_id']=$request->category_id[$i];
         product_category_ids::create($data);
        }
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.productprovider.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductProvider::where('id', $id)->delete();
         product_category_ids::where('product_providers_id', $id)->delete();
         Product::where('product_providers_id', $id)->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.productprovider.index');
    }
}
