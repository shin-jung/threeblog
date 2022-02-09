<?php

namespace App\Services\Api;

use App\Repositories\Api\ArticleRepository;
use App\Repositories\Api\UserRepository;
use App\Helpers\General\CollectionHelper;
use DB;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
    }

    public function indexPost($userId)
    {
        $info = [];
        $articleInfo = $this->articleRepository->indexPost();
        $pageSize = 5;
        $articlePage = CollectionHelper::paginate($articleInfo, $pageSize);
        foreach ($articlePage as $article) {
            $author = false;
            $userInfo = $this->userRepository->getUserById($userId);
            if ($userInfo->is_admin || $article['author'] == $userId) {
                $author = true;
            }
            $message = $this->getArticleMessageFamily($article['id'], $userId);
            $info['item'][] = [
                'article_id' => $article['id'],
                'author_id' => $article['author'],
                'is_author' => $author,
                'author_name' => $article->relatedAuthor->name,
                'like' => $article->count_like,
                'title' => $article['title'],
                'content' => $article['content'],
                'create_date' => $article['created_at']->format('Y-m-d H:m:i'),
                'update_date' => $article['updated_at']->format('Y-m-d H:m:i'),
                'message' => $message
            ];
        }
        $info['page'] = [
            'count' => $articlePage->count(), // 該頁數有幾筆資料
            'current_page' => $articlePage->currentPage(), // 目前頁數
            'last_page' => $articlePage->lastPage(), // 最後一頁的頁數
            'total' => $articlePage->total() // 總共有幾筆資料
        ];
        return $info;
    }

    public function getArticleMessageFamily($articleId, $userId)
    {
        $mother = $this->articleRepository->getArticleMotherMessage($articleId);
        $motherMessages = [];
        foreach ($mother as $motherMessage) {
            $child = $this->articleRepository->getArticleMessageByParent($motherMessage['id']);
            $childMessages = [];
            foreach ($child as $childMessage) {
                $author = false;
                $userInfo = $this->userRepository->getUserById($userId);
                if ($userInfo->is_admin || $childMessage['user_id'] == $userId) {
                    $author = true;
                }
                $childMessages[] = [
                    'message_id' => $childMessage['id'],
                    'user_id' => $childMessage['user_id'],
                    'like' => $childMessage['count_like'],
                    'is_author' => $author,
                    'content' => $childMessage['content'],
                    'create_date' => $childMessage['created_at']->format('Y-m-d H:m:i'),
                    'update_date' => $childMessage['updated_at']->format('Y-m-d H:m:i'),
                    'parent' => $childMessage['parent'],
                    'child_message' => []
                ];
            }
            $author = false;
            $userInfo = $this->userRepository->getUserById($userId);
            if ($userInfo->is_admin || $motherMessage['user_id'] == $userId) {
                $author = true;
            }
            $motherMessages[] = [
                'message_id' => $motherMessage['id'],
                'user_id' => $motherMessage['user_id'],
                'like' => $motherMessage['count_like'],
                'content' => $motherMessage['content'],
                'is_author' => $author,
                'create_date' => $motherMessage['created_at']->format('Y-m-d H:m:i'),
                'update_date' => $motherMessage['updated_at']->format('Y-m-d H:m:i'),
                'parent' => $motherMessage['parent'],
                'child_message' => $childMessages
            ];
        }
        return $motherMessages;
    }

    public function storePost($request, $userId)
    {
        return $this->articleRepository->storePost($request, $userId);
    }

    public function showPost($articleId, $userId)
    {
        $getArticle = $this->articleRepository->showPost($articleId);
        if (is_null($getArticle)) {
            throw new \Exception('查無文章', 403);
        }
        $info = [];
        $author = false;
        $message = $this->getArticleMessageFamily($getArticle['id'], $userId);
        $userInfo = $this->userRepository->getUserById($userId);
        if ($userInfo->is_admin || $getArticle['author'] == $userId) {
            $author = true;
        }
        $info[] = [
            'article_id' => $getArticle['id'],
            'author_id' => $getArticle['author'],
            'author_name' => $getArticle->relatedAuthor->name,
            'title' => $getArticle['title'],
            'content' => $getArticle['content'],
            'is_author' => $author,
            'like' => $getArticle['count_like'],
            'create_date' => $getArticle['created_at']->format('Y-m-d H:m:i'),
            'update_date' => $getArticle['updated_at']->format('Y-m-d H:m:i'),
            'message' => $message
        ];
        return $info;
    }

    public function updatePost($request, $authorId)
    {
        $getArticle = $this->articleRepository->showPost($request['article_id']);
        if (is_null($getArticle)) {
            throw new \Exception('查無文章', 403);
        }
        if ($getArticle['author'] == $authorId) {
            return $this->articleRepository->updatePost($request);
        }
        throw new \Exception('非作者本人不可修改文章', 403);
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
