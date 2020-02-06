<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;  

        //如果我的token不等於我的輸入就回傳失敗以及無效的密碼和電子郵件
        if (!$token = JWTAuth::attempt($input)) {  //確定身分驗證是否成功
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password.',
                'data' => '',
            ], 401);
            //401 需要授權以回應請求。伺服器不知道用戶端身分
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Success, you are login.',
                'data' => $token, 
            ], 200);
        }
    }

    public function logout(Request $request)
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
            ], 500);
        }
        //try-使用異常的函數應該位於"try"代碼塊內。如果沒有觸發異常，則代碼將照常繼續執行。但如果一常被觸發，會拋出一個異常
        //catch-會捕獲try發出的異常，並創建一個包含異常信息的對象
        //500 伺服器端發生未知或無法處理的錯誤
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|max:20|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you have some validate error.',
                'data' => '',
            ], 500);
        } 
        if ($this->authService->register($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Success.',
                'data' => '',
            ], 200);
        }
        //200 請求成功
    }
}
