<?php

namespace App\Services;

use App\Repositories\ArticleRepositories;
use Illuminate\Http\Request;

class ArticleServices
{
	protected $article;

	public function __construct(ArticleRepositories $article)
	{
		$this->article = $article;
	}

	public function indexPost()
	{
		return $this->article->indexPost();
	}

	public function storePost(Request $request)
	{
		$this->article->storePost($request);
	}

	public function showPost($articleId)
	{
		return $this->article->showPost($articleId);
	}

	public function editPost($articleId)
	{
		return $this->article->editPost($articleId);
	}

	public function updatePost(Request $request, $articleId)
	{
		return $this->article->updatePost($request, $articleId);
	}

	public function destoryPost($articleId)
	{
		$this->article->destoryPost($articleId);

	}
}
