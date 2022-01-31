<?php

namespace App\Services\Api;

use App\Repositories\Api\ArticleRepository;
use DB;

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

    public function storePost($request, $userId)
    {
        return $this->articleRepository->storePost($request, $userId);
    }

    public function showPost($articleId, $authorId)
    {
        $getArticle = $this->articleRepository->showPost($articleId);
        if (is_null($getArticle)) {
            throw new \Exception('查無文章', 403);
        }
        if ($getArticle['author'] == $authorId) {
            return $this->articleRepository->showPost($articleId);
        }
        throw new \Exception('非作者本人不可修改文章', 403);
    }

    public function updatePost($request)
    {
        return $this->articleRepository->updatePost($request);
    }

    public function destroyPost($request)
    {
        DB::beginTransaction();
        if ($this->articleRepository->destroyPost($request->article_id)) {
            $getArticleMessage = $this->articleRepository->getArticleMessages($request['article_id'])->pluck('id')->toArray();
            $deleteArticleMessage = $this->articleRepository->deleteArticleMessages($getArticleMessage);
            if ($deleteArticleMessage) {
                DB::commit();
                return true;
            } else {
                DB::rollback();
                throw new \Exception('刪除文章失敗', 500);
            }
        } else {
            throw new \Exception('刪除文章失敗', 500);
            DB::rollback();
        }
    }

    public function createMessageToArticleInfo($request, $userId)
    {
        $searchArticle = $this->articleRepository->showPost($request['article_id']);
        if (is_null($searchArticle)) {
            throw new \Exception('查無文章', 403);
        }
        if (!is_null($request['article_message_parent'])) {
            $articleMessage = $this->articleRepository->getArticleMessageById($request['article_message_parent']);
            if (is_null($articleMessage) || !is_null($articleMessage['parent'])) {
                throw new \Exception('查無留言', 403);
            }
            if ($articleMessage['article_id'] != $request['article_id']) {
                throw new \Exception('文章id不符', 403);
            }
        }
        return $this->articleRepository->createMessageToArticleDetail($request, $userId);
    }

    public function modifyMessageToArticleInfo($request, $userId)
    {
        $searchArticleMessage = $this->articleRepository->getArticleMessageById($request['article_message_id']);
        if (is_null($searchArticleMessage)) {
            throw new \Exception('查無文章留言', 403);
        }
        if ($searchArticleMessage['user_id'] != $userId) {
            throw new \Exception('你沒有資格修改文章啦!', 403);
        }
        return $this->articleRepository->modifyMessageToArticle($request);
    }

    public function deleteMessageToArticleInfo($request, $userId)
    {
        DB::beginTransaction();
        // 作者或留言本人才可刪除
        $searchArticleMessage = $this->articleRepository->getArticleMessageById($request['article_message_id']);
        if (is_null($searchArticleMessage)) {
            throw new \Exception('查無文章留言', 403);
        }
        if (is_null($searchArticleMessage['parent'])) {
            $parentId = $searchArticleMessage['id'];
            // 刪除主留言
            if ($searchArticleMessage['user_id'] != $userId) {
                if ($searchArticleMessage->relatedArticle->author != $userId) {
                    throw new \Exception('你沒有資格刪除文章留言啦!', 403);
                }
            }
            $delete = $this->articleRepository->deleteMessageToArticle([$request['article_message_id']]);
            if (!$delete) {
                DB::rollback();
                throw new \Exception('刪除文章留言失敗!', 500);
            }
            $childMessage = $this->articleRepository->getArticleMessageByParent($parentId)->pluck('id');
            if (!$childMessage->isEmpty()) {
                $deleteMessages = $this->articleRepository->deleteMessageToArticle($childMessage);
                if (!$deleteMessages) {
                    DB::rollback();
                    throw new \Exception('刪除文章留言失敗!', 500);
                }
            }
        }
        DB::commit();
        return true;
    }

    public function doLikeArticle($request, $userId)
    {
        // 只能按一次讚
        DB::beginTransaction();
        $searchLikeToArticle = $this->articleRepository->getLikeToArticle($request['article_id'], $userId);
        if (!is_null($searchLikeToArticle)) {
            throw new \Exception('看過讚了啦', 403);
        }
        $addLikeArticle = $this->articleRepository->addLikeArticle($request['article_id']);
        if (!$addLikeArticle) {
            DB::rollback();
            throw new \Exception('增加文章按讚數失敗', 500);
        }
        $createLikeToArticle = $this->articleRepository->createLikeArticle($request['article_id'], $userId);
        if (!$createLikeToArticle) {
            DB::rollback();
            throw new \Exception('文章按讚失敗', 500);
        }
        DB::commit();
        return true;
    }

    public function doCancelLikeArticle($request, $userId)
    {
        DB::beginTransaction();
        $searchLikeArticle = $this->articleRepository->getLikeToArticle($request['article_id'], $userId);
        if (is_null($searchLikeArticle)) {
            throw new \Exception('你根本沒有按讚這篇文，何來取消按讚？', 403);
        }
        $cancelLike = $this->articleRepository->cancelLikeArticle($request['article_id']);
        if (!$cancelLike) {
            DB::rollback();
            throw new \Exception('減少文章按讚數失敗', 500);
        }
        $deleteLikeToArticle = $this->articleRepository->deleteLikeArticle($request['article_id'], $userId);
        if (!$deleteLikeToArticle) {
            DB::rollback();
            throw new \Exception('刪除文章按讚紀錄失敗', 500);
        }
        DB::commit();
        return true;
    }

    public function doLikeArticleMessage($request, $userId)
    {
        // 只能按一次讚
        DB::beginTransaction();
        $searchLikeToArticleMessage = $this->articleRepository->getLikeToArticleMessage($request['article_message_id'], $userId);
        if (!is_null($searchLikeToArticleMessage)) {
            throw new \Exception('看過讚了啦', 403);
        }
        $addLikeArticleMessage = $this->articleRepository->addLikeArticleMessage($request['article_message_id']);
        if (!$addLikeArticleMessage) {
            DB::rollback();
            throw new \Exception('增加文章留言按讚數失敗', 500);
        }
        $createLikeToArticleMessage = $this->articleRepository->createLikeArticleMessage($request['article_message_id'], $userId);
        if (!$createLikeToArticleMessage) {
            DB::rollback();
            throw new \Exception('文章留言按讚失敗', 500);
        }
        DB::commit();
        return true;
    }

    public function doCancelLikeArticleMessage($request, $userId)
    {
        DB::beginTransaction();
        $searchLikeArticleMessage = $this->articleRepository->getLikeToArticleMessage($request['article_message_id'], $userId);
        if (is_null($searchLikeArticleMessage)) {
            throw new \Exception('你根本沒有按讚這篇文章留言，何來取消按讚？', 403);
        }
        $cancelLike = $this->articleRepository->cancelLikeArticleMessage($request['article_message_id']);
        if (!$cancelLike) {
            DB::rollback();
            throw new \Exception('減少文章按讚數失敗', 500);
        }
        $deleteLikeToArticle = $this->articleRepository->deleteLikeArticle($request['article_id'], $userId);
        if (!$deleteLikeToArticle) {
            DB::rollback();
            throw new \Exception('刪除文章按讚紀錄失敗', 500);
        }
        DB::commit();
        return true;
    }
}
