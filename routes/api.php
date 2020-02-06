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

Route::post('/register', 'Api\AuthController@register');

Route::get('/user', 'Api\UserController@index')->middleware(['auth.jwt','user']);

Route::group(['prefix' => 'article' , 'middleware' => 'auth.jwt'], function(){

	Route::post('/logout', 'Api\AuthController@logout');

	Route::get('/home', 'Api\ArticleController@index');

	Route::post('/store', 'Api\ArticleController@store');

	Route::get('/show/{id?}', 'Api\ArticleController@show');

	Route::post('/update/{id?}', 'Api\ArticleController@update')->middleware('article');

	Route::post('/delete/{id?}', 'Api\ArticleController@destory')->middleware('article');
});

Route::fallback(function() {
    return response()->json([
        'success' => false,
    	'message' => 'Sorry, can not find this web.',
    	'data' => '',
    ], 500);
});