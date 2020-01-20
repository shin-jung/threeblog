<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Services\ArticleServices;


class ArticleController extends Controller
{
	public $articless;

	public function __construct(ArticleServices $article)
	{
		$this->articless = $article;
	}

    public function index()
	{
		
		$showarticle = $this->articless->indexPost(); 
		
    	return view('/home')
    	    ->with('articles', $showarticle);
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

		$this->articless->storePost($request);

    	return redirect('/home');
    }

    public function show($articleId)
    {
    	$showpost = $this->articless->showpost($articleId);
    	//$showpost是用來接return回來也就是->後的變數
    	//$this是指整個class裡的articless，因為已經有建構子把repositories引入所以只要showpost(articlerepositories內的function)內的變數即可
    	//$showpost = Article::where('id', $article_id)->get();
		if($showpost->isEmpty()){
			return redirect('/home');
		}
    	return view('/show')
    		->with('articles', $showpost);
	}

	public function edit($articleId)
	{
		$editpost = $this->articless->editPost($articleId);

		if($editpost->isEmpty()){
			return redirect('/home');
		}
    	return view('/edit')
    		->with('articles', $editpost[0]);
	}

	public function update(Request $request, $articleId)
	{
		
		$request->validate([
			'title' => ['required', 'alpha_dash'],
			'content' => ['required', 'alpha_dash'],
		]);

		$this->articless->updatePost($request, $articleId);

    	return redirect('/home');
	}

	public function destory($articleId)
	{

		$this->articless->destoryPost($articleId);

    	return redirect('/home');
	}
}

