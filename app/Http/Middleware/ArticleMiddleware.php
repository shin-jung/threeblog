<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Article;
use JWTAuth;

class ArticleMiddleware
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
        $article = Article::where('id', $request->article_id)->first();
        
        if (is_null($article)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can not find this article.',
            ], 403);
        }
        if (JWTAuth::user()->is_admin != true && JWTAuth::user()->id != $article->author) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you can not do it.',
            ], 403);
        }

        return $next($request);
    }
}
