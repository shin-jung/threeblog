<?php

namespace App\Repositories\Api;

use App\Models\ArticleMessage;
use App\Models\Article;
use App\Models\LikeToArticle;
use App\Models\LikeToArticleMessage;
use App\Models\LogArticle;
use App\Models\LogArticleMessage;

class ArticleRepository
{
    public function indexPost()
    {
        return Article::all();
    }

    public function storePost($request, $userId, $isAdmin, $ip, $apiUrl)
    {
        $create = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $userId,
        ]);
        if (!$this->createLogArticle($create->id, $isAdmin, $ip, $apiUrl, [], $create)) {
            throw new \Exception('建立LogArticle失敗', 500);
        }
        return $create;
    }

    public function showPost($articleId)
    {
        return Article::where('id', $articleId)->first();
    }

    public function updatePost($request, $isAdmin, $ip, $apiUrl)
    {
        $article = $this->showPost($request->article_id);
        $updateArticle = Article::where('id', $request->article_id)
                        ->update([
                            'title' => $request->title,
                            'content' => $request->content,
                        ]);
        $newArticle = $this->showPost($request->article_id);
        if (!$this->createLogArticle($request->article_id, $isAdmin, $ip, $apiUrl, $article, $newArticle)) {
            throw new \Exception('建立LogArticle失敗', 500);
        }
        return $updateArticle;
    }

    public function destroyPost($articleId, $isAdmin, $ip, $apiUrl)
    {
        $article = $this->showPost($articleId);
        $delete = Article::where('id', $articleId)->delete();
        if (!$this->createLogArticle($articleId, $isAdmin, $ip, $apiUrl, $article, [])) {
            throw new \Exception('建立LogArticle失敗', 500);
        }
        return $delete;
    }

    public function createLogArticle($articleId, $isAdmin, $ip, $apiUrl, $previousData, $currentData)
    {
        return LogArticle::create([
            'article_id' => $articleId,
            'is_admin' => $isAdmin,
            'ip' => $ip,
            'type' => $apiUrl,
            'previous_data' => json_encode($previousData),
            'current_data' => json_encode($currentData)
        ]);
    }

    public function createLogArticleMessage($articleMessageId, $isAdmin, $ip, $apiUrl, $previousData, $currentData)
    {
        return LogArticleMessage::create([
            'article_message_id' => $articleMessageId,
            'is_admin' => $isAdmin,
            'ip' => $ip,
            'type' => $apiUrl,
            'previous_data' => json_encode($previousData),
            'current_data' => json_encode($currentData)
        ]);
    }

    public function createMessageToArticleDetail($request, $userId, $isAdmin, $ip, $apiUrl)
    {
        $create = ArticleMessage::create([
            'article_id' => $request->article_id,
            'content' => $request->message,
            'parent' => $request->article_message_parent,
            'user_id' => $userId,
        ]);
        if (!$this->createLogArticleMessage($create->id, $isAdmin, $ip, $apiUrl, [], $create)) {
            throw new \Exception('建立LogArticleMessage失敗', 500);
        }
        return $create;
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
    
    public function modifyMessageToArticle($request, $userId, $isAdmin, $ip, $apiUrl)
    {
        $message = $this->getArticleMessageById($request->article_message_id);
        $update = ArticleMessage::where('id', $request->article_message_id)
                            ->update([
                                'content' => $request->message
                            ]);
        $newMessage = $this->getArticleMessageById($request->article_message_id);
        if (!$this->createLogArticleMessage($request->article_message_id, $isAdmin, $ip, $apiUrl, $message, $newMessage)) {
            throw new \Exception('建立LogArticleMessage失敗', 500);
        }
        return $update;
    }

    public function deleteMessageToArticle($articleMessageIds, $isAdmin, $ip, $apiUrl)
    {
        foreach ($articleMessageIds as $message) {
            $messageInfo = $this->getArticleMessageById($message);
            if (!$this->createLogArticleMessage($message, $isAdmin, $ip, $apiUrl, $messageInfo, [])) {
                throw new \Exception('建立LogArticle失敗', 500);
            }
        }
        $delete = ArticleMessage::whereIn('id', $articleMessageIds)
                            ->delete();
        return $delete;
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
