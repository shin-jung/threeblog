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

	public function destoryPost($articleId)
	{
		return $this->articleRepository->destoryPost($articleId);
	}
}
