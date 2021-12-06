<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/////////
Route::middleware('jwtAuth')->group(function() {
    Route::get('logout','AuthController@logout');
    Route::get('me','AuthController@me');
    Route::get('payload','AuthController@payload');
    Route::post('directsale','TitleController@directsale');
    Route::post('allads','TitleController@allads');
    Route::get('add_details/{id}','TitleController@add_details');
    Route::get('my_adds/{id}','TitleController@my_adds');
    Route::post('book_product','TitleController@book_product');
    Route::get('allbooked/{id}','TitleController@allbooked');
    Route::post('acceptadds/{id}/{publisher}','TitleController@acceptadds');
  

    Route::post('CartAdd','CarController@CartAdd');
    Route::post('Cart','CarController@Cart');
    Route::post('CartUpdate','CarController@CartUpdate');
    Route::post('CartDelete','CarController@CartDelete');
    Route::post('OrderConfirm','CarController@OrderConfirm');
    Route::post('Orders','CarController@Orders');
    Route::post('Orders/{id}','CarController@OrdersById');
    Route::post('BankServiceRequest','CarController@addclientbalance');
    Route::post('ClientUpdate','CarController@updateprofile');
    Route::post('ChangePassword','AuthController@ChangePassword');

});


Route::post('CheckPhoneRegister','AuthController@phonecheck');
Route::post('CheckPhoneReset','AuthController@phoneresetcheck');
Route::post('CheckCode','AuthController@checkcode');
Route::post('ResetPassword','AuthController@resetpass');
Route::post('Cities','CitiesController@cities');
Route::post('Regions','CitiesController@regions');
Route::post('BussinessType','CitiesController@BussinessType');
Route::post('Register', 'AuthController@register');
Route::post('Login','AuthController@login');
Route::resource('posts','postController');
Route::post('Providers','postController@providers');
Route::post('Categories','postController@categories');
Route::post('Products','postController@products');
Route::post('Products/{id}','postController@product_details');
Route::resource('sale_type','SaleTypeController');
Route::resource('title','TitleController');
Route::post('Complaints','AuthController@Complaints');

//Route::middleware('jwt.auth')->post('login', 'API/AuthController@login');
