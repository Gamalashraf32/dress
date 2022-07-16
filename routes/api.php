<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\customer\homecontroller;

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

Route::group(['namespace' => 'App\Http\Controllers\Auth', 'prefix' => 'auth-admins'], function () {
    Route::post('login', 'AdminController@login');
    Route::post('register', 'AdminController@register');
    Route::get('profile', 'AdminController@profile')->middleware('auth.guard:admin');
    Route::post('update', 'AdminController@update')->middleware('auth.guard:admin');
    Route::post('logout', 'AdminController@logout')->middleware('auth.guard:admin');
    // Route::post('add_details', 'ShopOwnerController@add_details');
});
#===============================================================================================================
Route::group(['namespace' => 'App\Http\Controllers\Auth', 'prefix' => 'auth-customer'], function () {

    Route::post('login', 'CustomerController@login');
    Route::post('register', 'CustomerController@register');
    Route::get('profile', 'CustomerController@profile')->middleware('auth.guard:api');
    Route::post('update', 'CustomerController@update')->middleware('auth.guard:api');
    Route::post('logout', 'CustomerController@logout')->middleware('auth.guard:api');
});
#===============================================================================================================
Route::group(['middleware'=>'auth.guard:admin','namespace' => 'App\Http\Controllers\Owner', 'prefix' => 'owner'], function () {
Route::post('add-dress','AddDressController@add');
Route::post('update-dress/{id}','AddDressController@update');
Route::post('delete-dress/{id}','AddDressController@delete');
Route::get('show-dresses','AddDressController@show');
Route::get('show-dressid/{id}','AddDressController@showid');
Route::get('show-cat','CategoryController@showcat');
});

#dodo===============================================================================================================

Route::group(['middleware'=>'auth.guard:api','namespace' => 'App\Http\Controllers\customer', 'prefix' => 'customer'], function () {
    Route::post('requestorder/{id}',[homecontroller::class,'requestorder']);

    
    });

    Route::get('showspacificdress/{id}',[homecontroller::class,'showspacificdress']);
    Route::get('viewdresses',[homecontroller::class,'viewdresses']);
    Route::get('viewcategories',[homecontroller::class,'viewcategories']);

    Route::get('showdressdetails/{id}',[homecontroller::class,'showdressdetails']);

    