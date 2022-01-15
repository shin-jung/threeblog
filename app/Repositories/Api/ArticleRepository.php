<?php

namespace App\Repositories\Api;

use App\Models\ArticleMessage;
use App\Models\Article;

class ArticleRepository
{
    public function indexPost()
    {
        return Article::all();
    }

    public function storePost($request, $userId)
    {
        return Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $userId,
        ]);
    }

    public function showPost($articleId)
    {
        return Article::where('id', $articleId)->first();
    }

    public function updatePost($request)
    {
        return Article::where('id', $request->article_id)
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
