<?php

namespace App\Repositories\Api;

use App\Models\ArticleMessage;
use App\Models\Article;
use JWTAuth;
use Illuminate\Http\Request;

class ArticleRepository
{
    public function indexPost()
    {
        return Article::all();
    }

    public function storePost(Request $request)
    {
        return Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => JWTAuth::user()->name,
        ]);
    }

    public function showPost($articleId)
    {
        return Article::where('id', $articleId)->get();
    }

    public function updatePost(Request $request, $articleId)
    {
        return Article::where('id', $articleId)
                        ->update([
                            'title' => $request->title,
                            'content' => $request->content,
                        ]);
    }

    public function destroyPost($articleId)
    {
        return Article::where('id', $articleId)->delete();
    }

    public function createMessageToArticleDetail($request, $userId)
    {
        return ArticleMessage::create([
            'article_id' => $request['article_id'],
            'content' => $request['message'],
            'user_id' => $userId
        ]);
    }
}
