<?php

namespace App\Http\Controllers;

use JWTAuth;
use APP\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\registrationFormRequest;

class ApiController extends Controller
{
    public $loginAfterSignUp = true; //註冊之後登入

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;  //

        //如果我的token不等於我的輸入就回傳失敗以及無效的密碼和電子郵件
        if (!$token = JWTAuth::attempt($input)) {  //確定身分驗證是否成功
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
            //401 需要授權以回應請求。伺服器不知道用戶端身分
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [  //驗證甚麼
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            //通過傳遞表單請求來調用該方法。
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully',
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
            ], 500);
        }

        //try-使用異常的函數應該位於"try"代碼塊內。如果沒有觸發異常，則代碼將照常繼續執行。但如果一常被觸發，會拋出一個異常
        //catch-會捕獲try發出的異常，並創建一個包含異常信息的對象
        //500 伺服器端發生未知或無法處理的錯誤
    }

    public function register(RegistrationFormRequest $request)
    {
    	//reguster()從表單請求中獲取數據，並創建User模型的新案例並保存。
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        //$loginAfterSignup調用該login()方法來對用戶進行身分驗證並將成功回傳回去
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success'   =>  true,
            'data'      =>  $user,
        ], 200);
        //200 請求成功
    }
}
