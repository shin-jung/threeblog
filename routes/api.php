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


Route::group(['prefix' => 'article' , 'middleware' => 'auth.jwt'], function(){

	Route::post('logout', 'ApiController@logout');

	Route::get('/home', 'ArticleController@index');

	Route::post('/store', 'ArticleController@store');
});
