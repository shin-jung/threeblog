<?php

namespace App\Services\Api;

use App\Repositories\Api\UserRepository;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function indexUser()
    {
        return $this->userRepository->indexUser();
    }

    public function getUserData($token, $userId)
    {
        $getUserInfo = $this->userRepository->getUserById($userId);
        if (!is_null($getUserInfo['token'])) {
            try {
                JWTAuth::setToken($getUserInfo['token'])->invalidate();
            } catch (JWTException $e) {
                $this->userRepository->updateUserToken($userId, $token);
                return $token;
            }
        }
        $this->userRepository->updateUserToken($userId, $token);
        return $token;
    }
    
    public function register($request)
    {
        return $this->userRepository->register($request);
    }
}
