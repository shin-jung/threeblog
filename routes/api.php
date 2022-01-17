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
// 登入
Route::post('/login', 'Api\AuthController@login');
// 註冊
Route::post('/register', 'Api\AuthController@register');

Route::group(['middleware' => 'auth.jwt'], function () {
    // 查看全部使用者
    Route::get('/user', 'Api\UserController@index')->middleware('user');
    // 登出
    Route::post('/logout', 'Api\AuthController@logout');
});

Route::group(['prefix' => 'article', 'middleware' => 'auth.jwt'], function () {
    // 查看所有文章
    Route::get('/home', 'Api\ArticleController@index');
    // 修改自己文章
    Route::post('/store', 'Api\ArticleController@store');
    // 查看自己文章
    Route::get('/show', 'Api\ArticleController@show');
    // 修改自己文章
    Route::put('/update', 'Api\ArticleController@update')->middleware('article');
    // 刪除自己文章
    Route::post('/delete', 'Api\ArticleController@destroy')->middleware('article');
    // 按讚文章
    Route::post('/like-article', 'Api\ArticleController@likeArticle');
    // 取消按讚文章
    Route::put('/cancel-like-article', 'Api\ArticleController@cancelLikeArticle');

    Route::group(['prefix' => 'message'], function () {
        // 新增文章留言
        Route::post('/leave-message-to-article', 'Api\ArticleController@createMessageToArticle');
        // 修改文章留言
        Route::put('/modify_leave_message', 'Api\ArticleController@modifyLeaveMessage')->middleware('article.message');
        // 刪除文章留言
        Route::post('/delete_leave_message', 'Api\ArticleController@deleteLeaveMessage');
        // 按讚文章留言
        Route::post('/like-article-message', 'Api\ArticleController@likeArticleMessage');
    });
});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Sorry, can not find this web.',
        'data' => '',
    ], 403);
});
