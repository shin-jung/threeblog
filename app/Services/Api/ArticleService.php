<?php

namespace App\Services\Api;

use App\Repositories\Api\ArticleRepository;
use Illuminate\Http\Request;

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

    public function storePost(Request $request)
    {
        return $this->articleRepository->storePost($request);
    }

    public function showPost($articleId)
    {
        return $this->articleRepository->showPost($articleId);
    }

    public function updatePost(Request $request, $articleId)
    {
        return $this->articleRepository->updatePost($request, $articleId);
    }

    public function destroyPost($articleId)
    {
        return $this->articleRepository->destroyPost($articleId);
    }

    public function createMessageToArticleInfo($request, $userId)
    {
        $searchArticle = $this->articleRepository->showPost($request['article_id']);
        if (is_null($searchArticle)) {
            throw new \Exception('查無文章', 403);
        }
        return $this->articleRepository->createMessageToArticleDetail($request, $userId);
    }

    public function doLikeArticle($request, $userId)
    {
    }
}
