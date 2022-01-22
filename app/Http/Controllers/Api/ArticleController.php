<?php

namespace App\Http\Controllers\Api;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ArticleService;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

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
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = validator::make($request->all(), [
                'title' => 'required|string|alpha_dash',
                'content' => 'required|string|alpha_dash',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            $data = $this->articleService->storePost($request, JWTAuth::user()->id);
            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success',
                    'data' => $data,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Fail',
                    'data' => [],
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() . '#' . $e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function show(Request $request)
    {
        try {
            if (!is_numeric($request->article_id)) {
                throw new \Exception('格式錯誤', 422);
            }
            $showPost = $this->articleService->showPost($request->article_id);

            return response()->json([
                'success' => true,
                'message' => '成功取得文章',
                'data' => $showPost,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => '',
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = validator::make($request->all(), [
                'title' => 'required|alpha_dash',
                'content' => 'required|alpha_dash',
                'article_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }

            if ($this->articleService->updatePost($request, JWTAuth::user()->id)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success.',
                    'data' => '',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Fail',
                    'data' => [],
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => '',
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validator = validator::make($request->all(), [
                'article_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }

            if ($this->articleService->destroyPost($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success.',
                    'data' => '',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Fail',
                    'data' => [],
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => '',
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function createMessageToArticle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_id' => 'required|integer',
                'message' => 'string',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            $message = $this->articleService->createMessageToArticleInfo($request->all(), JWTAuth::user()->id);
            if ($message) {
                return response()->json([
                    'success' => true,
                    'message' => '新增文章留言成功',
                    'data' => ''
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '新增文章留言失敗',
                    'data' => ''
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function modifyLeaveMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_message_id' => 'required|integer',
                'message' => 'string',
                ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            $message = $this->articleService->modifyMessageToArticleInfo($request->all(), JWTAuth::user()->id);
            if ($message) {
                return response()->json([
                    'success' => true,
                    'message' => '修改文章留言成功',
                    'data' => ''
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '修改文章留言失敗',
                    'data' => ''
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function deleteLeaveMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_message_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            $message = $this->articleService->deleteMessageToArticleInfo($request->all(), JWTAuth::user()->id);
            if ($message) {
                return response()->json([
                    'success' => true,
                    'message' => '刪除文章留言成功',
                    'data' => ''
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '刪除文章留言失敗',
                    'data' => ''
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }


    
    public function likeArticle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            $message = $this->articleService->doLikeArticle($request, JWTAuth::user()->id);
            if ($message) {
                return response()->json([
                    'success' => true,
                    'message' => '喜歡文章成功',
                    'data' => ''
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function cancelLikeArticle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            $message = $this->articleService->doCancelLikeArticle($request, JWTAuth::user()->id);
            if ($message) {
                return response()->json([
                    'success' => true,
                    'message' => '取消按讚文章成功',
                    'data' => ''
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function likeArticleMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_message_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            $message = $this->articleService->doLikeArticleMessage($request, JWTAuth::user()->id);
            if ($message) {
                return response()->json([
                    'success' => true,
                    'message' => '喜歡文章留言成功',
                    'data' => ''
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }

    public function cancelLikeArticleMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_message_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            $message = $this->articleService->doCancelLikeArticleMessage($request, JWTAuth::user()->id);
            if ($message) {
                return response()->json([
                    'success' => true,
                    'message' => '取消按讚文章留言成功',
                    'data' => ''
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }
}
