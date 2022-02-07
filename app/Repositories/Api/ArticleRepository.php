<?php

namespace App\Repositories\Api;

use App\Models\ArticleMessage;
use App\Models\Article;
use App\Models\LikeToArticle;
use App\Models\LikeToArticleMessage;

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
            'parent' => $request['article_message_parent'],
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
    
    public function getArticleMessageByParent($parent)
    {
        return ArticleMessage::with('relatedArticle')
                            ->where('parent', $parent)
                            ->get();
    }
    
    public function modifyMessageToArticle($request)
    {
        return ArticleMessage::where('id', $request['article_message_id'])
                            ->update([
                                'content' => $request['message']
                            ]);
    }

    public function deleteMessageToArticle($articleMessageIds)
    {
        return ArticleMessage::whereIn('id', $articleMessageIds)
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

    public function getLikeToArticleMessage($articleMessageId, $userId)
    {
        return LikeToArticleMessage::where('article_message_id', $articleMessageId)
                            ->where('user_id', $userId)
                            ->first();
    }

    public function addLikeArticleMessage($articleMessageId)
    {
        return ArticleMessage::where('id', $articleMessageId)
                    ->increment('count_like', 1);
    }

    public function createLikeArticleMessage($articleMessageId, $userId)
    {
        return LikeToArticleMessage::create([
            'article_message_id' => $articleMessageId,
            'user_id' => $userId
        ]);
    }

    public function cancelLikeArticleMessage($articleMessageId)
    {
        return ArticleMessage::where('id', $articleMessageId)
                    ->decrement('count_like', 1);
    }

    public function getArticleMessages($articleId)
    {
        return ArticleMessage::where('article_id', $articleId)->get();
    }

    public function deleteArticleMessages($articleMessageArray)
    {
        return ArticleMessage::whereIn('id', $articleMessageArray)->delete();
    }

    public function getArticleMotherMessage($articleId)
    {
        return ArticleMessage::where('article_id', $articleId)
                            ->whereNull('parent')
                            ->get();
    }
}
