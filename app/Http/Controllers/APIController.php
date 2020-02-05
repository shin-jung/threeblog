<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\Services\ApiService;

class ApiController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;  //

        //如果我的token不等於我的輸入就回傳失敗以及無效的密碼和電子郵件
        if (!$token = JWTAuth::attempt($input)) {  //確定身分驗證是否成功
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password.',
                'data' => '',
            ], 401);
            //401 需要授權以回應請求。伺服器不知道用戶端身分
        }

        return response()->json([
            'success' => true,
            'token' => $token, ///////
        ]);
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
            'password' => 'required|string|min:6|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you can not register.',  //reason
                'data' => '',
            ], 500);
        } 
        if ($this->apiService->register($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Success.',
                'data' => '',
            ], 200);
        } ///

        //200 請求成功
    }
}
