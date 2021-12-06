<?php

namespace App\Http\Controllers\Dashboard;

use App\Cities;
use App\Regions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cities = Cities::all();
 if(auth()->user()->id !=1 ){
         
        $regions = Regions::where('user_id',auth()->user()->id)->when($request->search, function ($q) use ($request) {

            return $q->where('name_ar', 'like', '%' . $request->search . '%')
                ->orWhere('name_en', 'like', '%' . $request->search . '%');
        })->when($request->cities_id, function ($q) use ($request) {

            return $q->where('cities_id', $request->cities_id);
        })->latest()->paginate(5);
}else{

      $regions = Regions::when($request->search, function ($q) use ($request) {

            return $q->where('name_ar', 'like', '%' . $request->search . '%')
                ->orWhere('name_en', 'like', '%' . $request->search . '%');
        })->when($request->cities_id, function ($q) use ($request) {

            return $q->where('cities_id', $request->cities_id);
        })->latest()->paginate(5);
    
}
        return view('dashboard.regions.index', compact('cities', 'regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = Cities::all();
        return view('dashboard.regions.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'cities_id' => 'required'
        ]);
        $request_data = $request->all();
              if(auth()->user()->id !=1){
           
             $request_data['user_id']=auth()->user()->id;
         }else{
              $request_data['user_id']=null;
         }
        $regions = Regions::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.regions.index');
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
        $cities = Cities::all();
        $regions = Regions::where('id', $id)->first();

        return view('dashboard.regions.edit', compact('regions', 'cities'));
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
            'cities_id' => 'required'
        ]);

        $request_data = $request->except(['_token', '_method']);
        Regions::where('id', $id)->update($request_data);
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.regions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Regions::where('id', $id)->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.regions.index');
    }
}
