<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::group(['prefix' => 'article', 'middleware' => 'auth'], function() {

	Route::post('/store', 'ArticleController@store');

	Route::post('/update/{id}', 'ArticleController@update')->middleware('article');

	Route::get('/delete/{id}', 'ArticleController@destory')->middleware('article');
});

Route::fallback(function() {
    return redirect('/home');
});

