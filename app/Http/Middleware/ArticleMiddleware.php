<?php

namespace App\Http\Middleware;

use Closure;
use App\Article;
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
        if (!is_numeric($request->route('id')) || $request->route('id') == '') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can not find this web.',
            ], 403);
        }

        $findAuthor = Article::where('id', $request->route('id'))->first();
        
        if (is_null($findAuthor)) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can not find this web.',
            ], 403);
        }

        if (JWTAuth::user()->is_admin != true && JWTAuth::user()->name != $findAuthor->author) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can not do it.',
            ], 403);
        }

        return $next($request);
    }
}
