<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\UserService;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        //如果我的token不等於我的輸入就回傳失敗以及無效的密碼和電子郵件
        if (!$token = JWTAuth::attempt($input)) {  //確定身分驗證是否成功
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password.',
                'data' => '',
            ], 401);
        }
        $userData = $this->userService->getUserData($token, JWTAuth::user()->id);
        return response()->json([
            'success' => true,
            'message' => 'Success, you are login.',
            'data' => $userData,
        ], 200);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            //通過傳遞表單請求來調用該方法。
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully.',
                'data' => '',
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out.',
                'data' => '',
            ], 404);
        }
        //try-使用異常的函數應該位於"try"代碼塊內。如果沒有觸發異常，則代碼將照常繼續執行。但如果一常被觸發，會拋出一個異常
        //catch-會捕獲try發出的異常，並創建一個包含異常信息的對象
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:20',
                'email' => 'required|string|email|max:40|unique:users',
                'password' => 'required|string|min:6|max:20|confirmed',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 422);
            }
            if ($this->userService->register($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success.',
                    'data' => '',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage().'#'.$e->getLine(),
                'data' => '',
            ], empty($e->getCode()) ? 500 : $e->getCode());
        }
    }
}
