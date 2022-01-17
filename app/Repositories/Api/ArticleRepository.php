<?php

namespace App\Repositories\Api;

use App\Models\ArticleMessage;
use App\Models\Article;
use App\Models\LikeToArticle;

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
            'user_id' => $userId,
            'file' => json_encode([])
        ]);
    }

    public function getArticleMessageById($articleMessageId)
    {
        return ArticleMessage::with('relatedArticle')
                            ->where('id', $articleMessageId)
                            ->first();
    }
    
    public function modifyMessageToArticle($request)
    {
        return ArticleMessage::where('id', $request['article_message_id'])
                            ->update([
                                'content' => $request['message']
                            ]);
    }

    public function deleteMessageToArticle($articleMessageId)
    {
        return ArticleMessage::where('id', $articleMessageId)
                            ->delete();
    }

    public function getLikeToArticle($articleId, $userId)
    {
        return LikeToArticle::where('article_id', $articleId)
                            ->where('user_id', $userId)
                            ->first();
    }

    public function addLikeArticle($articleId)
    {
        return Article::where('id', $articleId)
                    ->increment('count_like', 1);
    }

    public function createLikeArticle($articleId, $userId)
    {
        return LikeToArticle::create([
            'article_id' => $articleId,
            'user_id' => $userId
        ]);
    }

    public function cancelLikeArticle($articleId)
    {
        return Article::where('id', $articleId)
                    ->decrement('count_like', 1);
    }

    public function deleteLikeArticle($articleId, $userId)
    {
        return LikeToArticle::where('article_id', $articleId)
                            ->where('user_id', $userId)
                            ->delete();
    }
}
