<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Services\ArticleService;


class ArticleController extends Controller
{
	public $articleService;

	public function __construct(ArticleService $articleService)
	{
		$this->articleService = $articleService;
	}

    public function index()
	{
		
		$showArticle = $this->articleService->indexPost(); 
		
    	return view('/home')
    	    ->with('articles', $showArticle);
	}

	public function create()
	{

    	return view('/create');
	}

	public function store(Request $request)
    {    	
    	$request->validate([
    		'title' => ['required', 'alpha_dash'],
    		'content' => ['required', 'alpha_dash'],
    	]);

		$this->articleService->storePost($request);

    	return redirect('/home');
    }

    public function show($articleId)
    {
    	if(is_numeric($articleId)){
    		$showPost = $this->articleService->showPost($articleId);
    	} else {
    		return redirect('/home');
    	}
    	//$showpost是用來接return回來也就是->後的變數
    	//$this是指整個class裡的articless，因為已經有建構子把repositories引入所以只要showpost(articlerepositories內的function)內的變數即可
    	//$showpost = Article::where('id', $article_id)->get();
		if($showPost->isEmpty()){
			return redirect('/home');
		}
    	return view('/show')
    		->with('articles', $showPost);
	}

	public function edit($articleId)
	{
		if(is_numeric($articleId)){
    		$editPost = $this->articleService->editPost($articleId);
    	} else {
    		return redirect('/home');
    	}
		
    	return view('/edit')
    		->with('articles', $editPost); //
	}

	public function update(Request $request, $articleId)
	{
		
		$request->validate([
			'title' => ['required', 'alpha_dash'],
			'content' => ['required', 'alpha_dash'],
		]);

		if(is_numeric($articleId)){
			$this->articleService->updatePost($request, $articleId);
		}
		return redirect('/home');
	}

	public function destory($articleId)
	{
		if(is_numeric($articleId)){
			$this->articleService->destoryPost($articleId);
		}
    	return redirect('/home');
	}
}

