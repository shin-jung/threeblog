<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Api\UserService;
use JWTAuth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        try {
            $showUser = $this->userService->indexUser();

            if ($showUser) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success.',
                    'data' => $showUser,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() . '#' . $e->getLine(),
                'data' => ''
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }
}
