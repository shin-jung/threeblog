<?php

namespace App\Repositories;

use App\Article;
use Illuminate\Http\Request;

class ArticleRepositories
{
	protected $article;

	public function __construct(Article $article)
	{
		$this->article = $article;
	}

	public function indexPost()
	{
		return $this->article->all();
	}

	public function storePost(Request $request)
	{
		return Article::create([
			'title' => $request->title,
    		'content' => $request->content,
    		'author' => $request->user()->name,
		]);
	} 

	public function showPost($articleId)
	{
		return $this->article->where('id', $articleId)->get();
	}

	public function editPost($articleId)
	{
		return $this->article->where('id', $articleId)->get();
	}

	public function updatePost(Request $request, $articleId)
	{
		$updatepost = $this->article->where('id', $articleId)->get();
		$updatepost[0]->update([
							'title' => $request->title,
							'content' => $request->content,
						]);
	}

	public function destoryPost($articleId)
	{
		$this->article->where('id', $articleId)->delete();
	}
}


