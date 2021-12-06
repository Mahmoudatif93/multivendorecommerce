<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SubTitle;
use App\MainTitle;
use App\DirectSale;
use App\Dropdownlistid;
use App\BookedDirectSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Session;
class TitleController extends Controller
{
    
    use ApiResourceTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index(Request $request)
    {

        $titles = MainTitle::when($request->category_id, function ($q) use ($request) {

            return $q->Where('cate_id', $request->category_id);
        })->with('SubTitles')->select('id','mainTitle','ismandatory')->get();

   $count = DB::table('main_titles')->count();
        
         return $this->apiResponse($titles,'',200,'success',$count);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
    }
    
    
    
        public function directsale(Request $request)
    {
               $post['img1']=$request->img1;
              $post['img2']=$request->img2;
             $post['img3']=$request->img3;
             $post['band_rkm']=$request->band_rkm;
             $post['general_description']=$request->general_description;
            $post['maindropdownlist']=$request->maindropdownlist;
            $post['subdropdownlist']=$request->subdropdownlist;
             $post['price']=$request->price;
             $post['available_number']=$request->available_number;
            $post['publisher_id']=$request->publisher_id;
               $post['cate_id']=$request->cate_id;
    
            if ($request->img1) {

            Image::make($request->img1)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/directsale/' . $request->img1->hashName()));

            $post['img1'] = $request->img1->hashName();

        }
             if ($request->img2) {

            Image::make($request->img2)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/directsale/' . $request->img2->hashName()));

            $post['img2'] = $request->img2->hashName();

        }
        
            if ($request->img3) {

            Image::make($request->img3)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/directsale/' . $request->img3->hashName()));

            $post['img3'] = $request->img3->hashName();

        }
               return $this->apiResponse($post,'',200,'success','');
         $direct=DB::table('direct_sales')->insertGetId($post);
        for ($i = 0; $i < count(json_decode($request->subdropdownlist, TRUE)); $i++) {
            $data['mainTitle_id'] = json_decode($request->maindropdownlist, TRUE)[$i];
            $data['subTitle_id'] = json_decode($request->subdropdownlist, TRUE)[$i];

             $data['DirectSale_id']=$direct;
            DB::table('dropdownlistids')->insertGetId($data);
            
            }
        if($direct){
           // return response()->json(['status'=>'success']);
           
            $count = DB::table('direct_sales')->count();
                    
         return $this->apiResponse('','',200,'success',$count);
          
        }else{
          return $this->apiResponse(null,'',500,'???? ??? ?? ??? ????????',0);
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
        $title = SubTitle::where('cate_id', '=', $id)->first();
        
         return $this->apiResponse($title,'',200,'success',0);;

    }
    
      public function allads(request $request)
    {
        $page=$request->page_number;
      if(!empty($page)){
        $adds = DB::table('direct_sales') ->select('img1','band_rkm','general_description','price','isbooked')->paginate($page);
  
      }else{
       // $adds =$page;
            $adds = DB::table('direct_sales') ->select('img1','band_rkm','general_description','price','isbooked')->get();      
      }
        
        $count = DB::table('direct_sales')->count();          
         return $this->apiResponse($adds,'',200,'success',$count);

    }
    
       public function add_details($id, request $request)
    {
       $add= DirectSale::where(array('id'=>$id))->with('Dropdownlistids')->first();
            return $this->apiResponse($add,'',200,'success',0);
    }
        public function my_adds($user_id)
    {
        $add = DirectSale::where(array('publisher_id'=>$user_id,'isbooked'=>0))->get();
        
         return $this->apiResponse($add,'',200,'success',0);;

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
    
    
    
    
    
    
        public function book_product(Request $request)
    {
        
             $post['DirectSale_id']=$request->add_id;
              $post['cate_id']=$request->cate_id;
             $post['number_products']=$request->number_products;
            $post['publisher_id']=$request->publisher_id;
            $directsale=DirectSale::where(array('id'=>$post['DirectSale_id']))->first();
    
            if($post['number_products'] > $directsale->available_number){
                return $this->apiResponse('','',404,'there is not enough stock',0); 
            }else{
                  if($directsale->available_number == 1){
                          
                             DirectSale::where('id', $post['DirectSale_id'])
                            ->update(['available_number' => 0,'isbooked'=>'1']);
                    }else{
                        $update=$directsale->available_number - $post['number_products'];
                        DirectSale::where('id', $post['DirectSale_id'])
                            ->update(['available_number' => $update]);
                    }
         $direct=DB::table('booked_direct_sales')->insertGetId($post);
        
        if($direct){
            $count = DB::table('booked_direct_sales')->count();
                    
         return $this->apiResponse('','',200,'success',$count);
          
        }else{
          return $this->apiResponse('','erro to stor category',404);
        }
        }
    }
    
       public function allbooked($id)
    {
       $adds= BookedDirectSale::where(array('DirectSale_id'=>$id,'status'=>0))->with('posts')->with('User')->get();
            return $this->apiResponse($adds,'',200,'success',0);
    }
    
    
      public function acceptadds($id,$publisher)
    {
       $add= BookedDirectSale::orderBy('id', 'asc')->where(array('DirectSale_id'=>$id,'publisher_id'=>$publisher,'status'=>0))->first();
       $direc= DirectSale::orderBy('id', 'asc')->where(array('id'=>$id))->first();
       
       
       
       $post['img1']=$direc->img1;
              $post['img2']=$direc->img2;
             $post['img3']=$direc->img3;
             $post['band_rkm']=$direc->band_rkm;
             $post['general_description']=$direc->general_description;
            $post['maindropdownlist']=$direc->maindropdownlist;
            $post['subdropdownlist']=$direc->subdropdownlist;
             $post['price']=$direc->$direc;
             $post['available_number']=$add->number_products;
            $post['publisher_id']=$add->publisher_id;
               $post['cate_id']=$add->cate_id;
       
       $direct=DB::table('direct_sales')->insertGetId($post);
       
       BookedDirectSale::where(array('DirectSale_id'=>$id,'publisher_id'=>$publisher))
          ->update(['status' =>1]);
            return $this->apiResponse('','',200,'adds is now paid and transfer to client ',0);
    }
    
    
    
    
}
