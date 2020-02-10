<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
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
            JWTAuth::parseToken();
            $token = JWTAuth::getToken();
        } catch (JWTException $e) {
            return response()->json(['Token missing or badly formatted'], 401);
        }
        try {
            $request->user = JWTAuth::authenticate($token);
        } catch (TokenExpiredException $e) {
            try {
                $token = JWTAuth::refresh($token);
                JWTAuth::setToken($token);
                var_dump($token);
                $request->user = JWTAuth::authenticate($token);
            } catch (TokenInvalidException $e) {
                return response()->json(['Token Invalid'], 401);
            }
        } catch (TokenInvalidException $e) {
            return response()->json(['Token Invalid'], 401);
        }
        return $next($request);
    }
}