<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\Models\ArticleMessage;

class ArticleMessageMiddleware
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
        if (!is_numeric(JWTAuth::user()->id) || JWTAuth::user()->id == '') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can not find your information',
            ], 403);
        }
        $articleMessage = ArticleMessage::where('id', $request->article_message_id)->first();
        if (is_null($articleMessage)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can not find this article message.',
            ], 403);
        }
        if (JWTAuth::user()->is_admin != true) {
            if (JWTAuth::user()->id != $articleMessage->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, you can not do it.',
                ], 403);
            }
        }
        return $next($request);
    }
}
