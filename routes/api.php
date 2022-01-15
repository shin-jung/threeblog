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

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('/user', 'Api\UserController@index')->middleware('user');

    Route::post('/logout', 'Api\AuthController@logout');
});

Route::group(['prefix' => 'article', 'middleware' => 'auth.jwt'], function () {
    Route::get('/home', 'Api\ArticleController@index');

    Route::post('/store', 'Api\ArticleController@store');

    Route::get('/show', 'Api\ArticleController@show');

    Route::group(['prefix' => 'message'], function () {
        // 新增文章留言
        Route::post('/leave-message-to-article', 'Api\ArticleController@createMessageToArticle');
        // 修改文章留言
        Route::put('/modify_leave_message', 'Api\ArticleController@modifyLeaveMessage');
        // 刪除文章留言
        Route::post('/delete_leave_message', 'Api\ArticleController@deleteLeaveMessage');
    });
    Route::put('/update', 'Api\ArticleController@update')->middleware('article');

    Route::post('/delete', 'Api\ArticleController@destroy')->middleware('article');
    
    Route::post('/like-article', 'Api\ArticleController@likeArticle');
});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Sorry, can not find this web.',
        'data' => '',
    ], 403);
});
