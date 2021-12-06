<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class CategoryController extends Controller
{
    public function index(Request $request)
    {
            if(auth()->user()->id !=1 ){
        $categories = Category::where('user_id',auth()->user()->id)->when($request->search, function ($q) use ($request) {

             return $q->where('name_ar', 'like', '%' . $request->search . '%')
                ->orWhere('name_en', 'like', '%' . $request->search . '%');

        })->latest()->paginate(5);
}else{
      $categories = Category::when($request->search, function ($q) use ($request) {

             return $q->where('name_ar', 'like', '%' . $request->search . '%')
                ->orWhere('name_en', 'like', '%' . $request->search . '%');

        })->latest()->paginate(5);
}
        return view('dashboard.categories.index', compact('categories'));

    }//end of index

    public function create()
    {
        return view('dashboard.categories.create');

    }//end of create

    public function store(Request $request)
    {
        $rules = [];
/*
        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations', 'name')]];

        }//end of for each
*/
         $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
        
        ]);
        $request_data = $request->all();
        if ($request->image) {

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/category_images/' . $request->image->hashName()));

            $request_data['image'] = 'category_images/'.$request->image->hashName();

        }//end of if
              if(auth()->user()->id !=1){
           
             $request_data['user_id']=auth()->user()->id;
         }else{
              $request_data['user_id']=null;
         }
        Category::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.categories.index');

    }//end of store

    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));

    }//end of edit

    public function update(Request $request, Category $category)
    {
       /* $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations', 'name')->ignore($category->id, 'category_id')]];

        }//end of for each

        $request->validate($rules);*/
           $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
        
        ]);
        $request_data = $request->all();
        if ($request->image) {

            if ($category->image != 'default.png') {

                Storage::disk('public_uploads')->delete('/category_images/' . $category->image);

            }//end of if

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/category_images/' . $request->image->hashName()));

            $request_data['image'] = 'category_images/'.$request->image->hashName();

        }//end of if
        $category->update($request_data);
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.categories.index');

    }//end of update

    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.categories.index');

    }//end of destroy

}//end of controller
