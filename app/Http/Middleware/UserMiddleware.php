<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use JWTAuth;

class UserMiddleware //看會員列表
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) //
    {
        if (JWTAuth::user()->admin != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you can not look this article.',
                'data' => '',
            ], 500);
        } 
    
        return $next($request);
    }
}
