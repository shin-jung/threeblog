<?php

namespace App\Services\Api;

use App\Repositories\Api\ArticleRepository;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function indexPost()
    {
        return $this->articleRepository->indexPost();
    }

    public function storePost($request, $userId)
    {
        return $this->articleRepository->storePost($request, $userId);
    }

    public function showPost($articleId)
    {
        $getArticle = $this->articleRepository->showPost($articleId);
        if (is_null($getArticle)) {
            throw new \Exception('查無文章', 403);
        }
        return $this->articleRepository->showPost($articleId);
    }

    public function updatePost($request)
    {
        return $this->articleRepository->updatePost($request);
    }

    public function destroyPost($request)
    {
        return $this->articleRepository->destroyPost($request->article_id);
    }

    public function createMessageToArticleInfo($request, $userId)
    {
        $searchArticle = $this->articleRepository->showPost($request['article_id']);
        if (is_null($searchArticle)) {
            throw new \Exception('查無文章', 403);
        }
        return $this->articleRepository->createMessageToArticleDetail($request, $userId);
    }

    public function modifyMessageToArticleInfo($request, $userId)
    {
        $searchArticleMessage = $this->articleRepository->getArticleMessageById($request['article_message_id']);
        if (is_null($searchArticleMessage)) {
            throw new \Exception('查無文章留言', 403);
        }
        if ($searchArticleMessage['user_id'] != $userId) {
            throw new \Exception('你沒有資格修改文章啦!', 403);
        }
        return $this->articleRepository->modifyMessageToArticle($request);
    }
}
