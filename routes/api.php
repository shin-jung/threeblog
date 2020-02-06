<?php

use Illuminate\Http\Request;

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
Route::post('/login', 'Api\AuthController@login');

Route::post('/register', 'AuthController@register');

Route::get('/user', 'UserController@index')->middleware(['auth.jwt','user']);

Route::group(['prefix' => 'article' , 'middleware' => 'auth.jwt'], function(){

	Route::post('/logout', 'AuthController@logout');

	Route::get('/home', 'ArticleController@index');

	Route::post('/store', 'ArticleController@store');

	Route::get('/show/{id?}', 'ArticleController@show');

	Route::post('/update/{id?}', 'ArticleController@update')->middleware('article');

	Route::post('/delete/{id?}', 'ArticleController@destory')->middleware('article');
});

Route::fallback(function() {
    return response()->json([
        'success' => false,
    	'message' => 'Sorry, can not find this web.',
    	'data' => '',
    ], 500);
});