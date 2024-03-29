<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Models\User;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        } catch (TokenExpiredException $e) {
            try {
                $token = JWTAuth::refresh(JWTAuth::getToken());
                $newToken = $token;
                $response = $next($request);
                $response->headers->set('Authorization', 'Bearer '. $token);
                // refresh後的token需進行setToken轉換後才可以取得payload內的sub（取得id）
                JWTAuth::setToken($newToken)->toUser();
                $id = JWTAuth::getPayLoad($newToken)['sub'];
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, token is wrong',
                    'data' => '',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, token is absent',
                'data' => '',
            ], 401);
        }
        // 將refresh後的token更新至資料庫的token欄位
        User::where('id', $id)->update(['remember_token' => $token]);
        return $this->setAuthenticationHeader($next($request), $token);
    }
}
