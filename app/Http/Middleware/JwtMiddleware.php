<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
            if(!$token = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry user not found',
                    'data' => '',
                ], 404);
            }
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, token is invalid',
                'data' => '',
            ], 404);
        } catch (TokenExpiredException $e) {
            try {
                $token = JWTAuth::refresh(JWTAuth::getToken());
                $response = $next($request);
                $response->headers->set('Authorization', 'Bearer '. $token);
            } catch (JWTException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, token is expired.',
                    'data' => '',
                ], 404);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, token is absent',
                'data' => '',
            ], 404);
        }
        return $this->setAuthenticationHeader($next($request), $token);
    }
}