<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Validator;


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
		
    	if ($showArticle) {
    		return response()->json([
    			'success' => true,
    			'message' => 'Success.',
    			'data' => $showArticle,
    		], 200);
    	} else {
    		return response()->json([
    			'success' => false,
    			'message' => 'Sorry, you can not see any articles.',
    			'data' => '',
    		], 500);
    	}
	}

	public function store(Request $request)
    {    	
    	$validator = validator::make($request->all(),[
    		'title' => 'required|alpha_dash',
    		'content' => 'required|alpha_dash',
    	]);

    	if ($validator->fails()) {
    		return response()->json([
    			'success' => false,
    			'message' => 'Sorry, data could not be added.',
    			'data' => '',
    		], 500);
    	}
		if ($this->articleService->storePost($request)) {
			return response()->json([
                'success' => true,
                'message' => 'Success.',
                'data' => '',
			], 200);
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
				'data' => '',
			], 500);
    	}
    	//$showpost是用來接return回來也就是->後的變數
    	//$this是指整個class裡的articless，因為已經有建構子把repositories引入所以只要showpost(articlerepositories內的function)內的變數即可
		if ($showPost->isEmpty()) {
			return response()->json([
				'success' => false,
				'message' => 'Sorry, data could not be show.',
				'data' => '',
			], 500);
		} else {
			return response()->json([
				'success' => true,
				'message' => 'Success',
				'data' => $showPost,
			], 200);
		}
	}

	public function update(Request $request, $articleId)
	{
    	$validator = validator::make($request->all(),[
    		'title' => 'required|alpha_dash',
    		'content' => 'required|alpha_dash',
    	]);

    	if ($validator->fails()) {
    		return response()->json([
    			'success' => false,
    			'message' => 'Sorry, data could not be added.',
    			'data' => '',
    		], 500);
    	}

		if ($this->articleService->updatePost($request, $articleId)) {
			return response()->json([
                'success' => true,
                'message' => 'Success.',
                'data' => '',
			], 200);			
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
				'data' => '',
			], 500);
		}
	}
}

