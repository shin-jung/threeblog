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
		
    	return $showArticle;
	}

	public function store(Request $request)
    {    	
    	$request->validate([
    		'title' => ['required', 'alpha_dash'],
    		'content' => ['required', 'alpha_dash'],
    	]);

		if ($this->articleService->storePost($request)) {
			return response()->json([
                'success' => true,
                'message' => 'Success.',
                'data' => '',
			], 200);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Sorry, data could not be added.',
			], 500);
		}
    }

    public function show($articleId)
    {
    	if (is_numeric($articleId)) {
    		$showPost = $this->articleService->showPost($articleId);
    	} else {
			return response()->json([
				'success' => false,
				'message' => 'Sorry, data could not be show.',
			], 500);
    	}
    	//$showpost是用來接return回來也就是->後的變數
    	//$this是指整個class裡的articless，因為已經有建構子把repositories引入所以只要showpost(articlerepositories內的function)內的變數即可
		if ($showPost->isEmpty()) {
			return response()->json([
				'success' => false,
				'message' => 'Sorry, data could not be show.',
			], 500);
		} else {
			return response()->json([
				'success' => true,
				'message' => 'Success',
				'data' => $showPost,
			]);
		}
	}

	public function update(Request $request, $articleId)
	{
		$request->validate([
			'title' => ['required', 'alpha_dash'],
			'content' => ['required', 'alpha_dash'],
		]);
		
		if ($this->articleService->updatePost($request, $articleId)) {
			return response()->json([
                'success' => true,
                'message' => 'Success.',
                'data' => '',
			], 200);			
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Sorry, data could not be added.',
			], 500);
		}
	}

	public function destory($articleId)
	{
		if ($this->articleService->destoryPost($articleId)) {
			return response()->json([
                'success' => true,
                'message' => 'Success.',
                'data' => '',
			], 200);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Sorry, data could not be delete.',
			], 500);
		}
	}
}

