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
Route::post('login', 'ApiController@login');

Route::post('register', 'ApiController@register');

Route::get('/user', 'UserController@index')->middleware(['auth.jwt','user']);

Route::group(['prefix' => 'article' , 'middleware' => 'auth.jwt'], function(){

	Route::post('logout', 'ApiController@logout');

	Route::get('/home', 'ArticleController@index');

	Route::post('/store', 'ArticleController@store');

	Route::get('/show/{id}', 'ArticleController@show');

	Route::post('/update/{id}', 'ArticleController@update')->middleware('article');

	Route::get('/delete/{id}', 'ArticleController@destory')->middleware('article');
});

Route::fallback(function() {
    return response()->json([
        'success' => false,
    	'message' => 'Sorry, can not find this web.',
    ], 500);
});