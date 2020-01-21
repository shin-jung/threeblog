<?php

namespace App\Repositories;

use App\Article;
use Illuminate\Support\Facades\Auth; //現在正在登入的
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
    		'author' => Auth::user()->name,
		]);
	} 

	public function showPost($articleId)
	{
		return Article::where('id', $articleId)->get();
	}

	public function editPost($articleId)
	{
		return Article::where('id', $articleId)->first();
	}

	public function updatePost(Request $request, $articleId)
	{
		$updatePost = Article::where('id', $articleId)->first()->update([
							'title' => $request->title,
							'content' => $request->content,
						]);
	}

	public function destoryPost($articleId)
	{
		Article::where('id', $articleId)->delete();
	}
}


