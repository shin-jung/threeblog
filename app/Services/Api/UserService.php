<?php

namespace App\Services\Api;

use App\Repositories\Api\UserRepository;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\General\CollectionHelper;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function indexUser()
    {
        $info = [];
        $user = $this->userRepository->indexUser()->sortByDesc('is_admin');
        $pageSize = 5;
        $userPage = CollectionHelper::paginate($user, $pageSize);
        foreach ($userPage as $userInfo) {
            $info['item'][] = [
                'id' => $userInfo['id'],
                'is_admin' => $userInfo['is_admin'],
                'name' => $userInfo['name'],
                'email' => $userInfo['email'],
                'created_at' => $userInfo['created_at']->format('Y-m-d H:m:i'),
                'updated_at' => $userInfo['updated_at']->format('Y-m-d H:m:i')
            ];
        }
        $info['page'] = [
            'count' => $userPage->count(), // 該頁數有幾筆資料
            'current_page' => $userPage->currentPage(), // 目前頁數
            'last_page' => $userPage->lastPage(), // 最後一頁的頁數
            'total' => $userPage->total() // 總共有幾筆資料
        ];
        return $info;
    }

    public function getUserData($token, $userId)
    {
        $getUserInfo = $this->userRepository->getUserById($userId);
        if (!is_null($getUserInfo['token'])) {
            try {
                JWTAuth::setToken($getUserInfo['token'])->invalidate();
            } catch (JWTException $e) {
                $this->userRepository->updateUserToken($userId, $token);
                $data['token'] = $token;
                return $data;
            }
        }
        $this->userRepository->updateUserToken($userId, $token);
        $data['token'] = $token;
        return $data;
    }
    
    public function register($request)
    {
        return $this->userRepository->register($request);
    }
}
