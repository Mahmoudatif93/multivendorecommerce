<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Client;
use App\complaints;
use App\SmsCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{
    use ApiResourceTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
     
     
      public function phonecheck(request $request)
    {
        $validator = Validator::make($request->all(), [  
              'phone' => 'required|unique:clients',
         ]);

           if($validator->passes()){
        $phone = request('phone');
        $smscode=1234;
        $create=SmsCode::create([
            'phone'=>request('phone'),
            'smscode'=>$smscode,
        ]);
        
        return $this->apiResponse2(false,true,'Verification code sent to your phone number');
        }else{
             return $this->apiResponse2(true,false,'Error To Sent Code');
        }
    }
    
    
       public function phoneresetcheck(request $request)
    {
        $validator = Validator::make($request->all(), [  
              'phone' => 'required|unique:clients',
         ]);

           if($validator->passes()){
                return $this->apiResponse2(false,false,'phone not exist');
                }else{
             
                 $phone = request('phone');
                 $smscode=1234;
                 $create=SmsCode::create([
                'phone'=>request('phone'),
                'smscode'=>$smscode,
              ]);
               $data='code sent in Your phone';
              return $this->apiResponse2(true,true,'Verification code sent to your phone number');
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
          public function checkcode(request $request)
    {
        $phone = request('phone');
        $smsCode=request('smsCode');
     
        $sms=SmsCode::where(['phone'=>$phone,'smscode'=>$smsCode])->orderBy('id', 'DESC')->first();
          
       
         if($sms){
            
           
            
           return $this->apiResponse3(true,'Phone number verified');
         }else{
            return $this->apiResponse3(false,'Code Not Valid');
           }
    }
     
     
    public function login(request $request)
    {
        $credentials = request(['phone', 'password']);
        $phone=$request->phone;
//$password=Hash::make(request('password'));

        if (! $token = auth('api')->attempt($credentials )) {
            return $this->apiResponse6(null,'رقم الموبيل او الرقم السري خطأ');
            //return response()->json(['error' => 'Unauthorized'], 401);
        }
        $data['token'] =$token;
     $data['client']=  Client::with('ClientBankBalance')->with('ClientBankCards')->where(array('phone'=>$phone))->first();
     
     return $this->apiResponse6($data,'success');
        //return $this->respondWithToken($token,$credentials);
    }


  public function loginwithouttoken()
    {
        $credentials = request(['mobile', 'password']);
        $token = auth('api')->attempt($credentials);
         return $this->respondWithToken($token,$credentials);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function register(Request $request)
    {
        
          
        $validator = Validator::make($request->all(), [
             'email' => 'required|unique:clients',  
              'phone' => 'required|unique:clients',
         ]);
           

           if($validator->passes()){
          /*   if (request('nationalIdImageFront')) {

            Image::make(request('nationalIdImageFront'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('nationalIdImageFront')->hashName()));

            $post['nationalIdImageFront'] = request('nationalIdImageFront')->hashName();
                }else{
                    $post['nationalIdImageFront'] = 'noimage';
                }
                
                  if (request('nationalIdImageBack')) {

            Image::make(request('nationalIdImageBack'))
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . request('nationalIdImageBack')->hashName()));

            $post['nationalIdImageBack'] = request('nationalIdImageBack')->hashName();
                }else{
                    $post['nationalIdImageBack'] = 'noimage';
                }
                    */    
        $create=Client::create([
            'name'=>request('name'),
            'firebaseToken'=>request('firebaseToken'),
            'email'=>request('email'),
            'businessName'=>request('businessName'),
            'businessTypeId'=>request('businessTypeId'),
            'phone'=>request('phone'),
            'businessType'=>request('businessType'),
            'businessOtherType'=>request('businessOtherType'),
            'cities_id'=>request('cities_id'),
            'regions_id'=>request('regions_id'),
            'address'=>request('address'),
            'mapAddress'=>request('mapAddress'),
            'type'=>request('type'),
             'latitude'=>request('latitude'),
              'longitude'=>request('longitude'),
            'password'=>Hash::make(request('password')),
          
        ]);
        
      return $this->apiResponse5($create,'success');
      //Session::put('user_id',$create->id);
           // return $this->login(request());
           }else{
            
            return $this->apiResponse5(null,'برجاء مراجعه البيانات المدخله مع التاكد ان الايميل او الموبيل غير مسجلين من قبل');
           }  
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }
    public function payload()
    {
        return $this->respondWithToken(auth('api')->payload());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$data='')
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
           // 'expires_in'   => auth('api')->factory()->getTTL() * 60,
             'data' => $data,
             'itemsCount' => 0,
             'success' => 'true',
             'statusCode' => 200,
             'message' => 'Login Successful',
        ]);
    }
    
 public function resetpass(Request $request)
    {
        $password = $request->password;
       $Client=Client::where('phone', $request->phone)->first();
        if($Client){
            Client::where('phone', $request->phone)->update(['password' => Hash::make($request->password)]);
            return $this->apiResponse7($Client,'Password reset successfully');
        }else{
            return $this->apiResponse7($Client,'wrong phone ');
        }
             
    }
    
     public function ChangePassword(Request $request)
    {
        
          $credentials = request(['phone', 'password']);
       
        if (! $token = auth('api')->attempt($credentials )) {
           return $this->apiResponse13(false,false,'failed to reset Password');
        }else{
            Client::where('phone', $request->phone)->update(['password' =>Hash::make($request->newPassword)]);
            return $this->apiResponse13(true,true,'Password reset successfully');
        }
            
    }
    
    public function Complaints(){
           $create=complaints::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'phone'=>request('phone'),
            'message'=>request('message'),
        ]);
        if($create){
            return $this->apiResponse7($create,'Your message has been Recived');
        }else{
             return $this->apiResponse7($create,'Error to send message');
        }
    }
    
    public function fcmsend(Request $request){
        
        
    }
}
