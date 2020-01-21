<?php

namespace App\Http\Middleware;

use Closure;
use App\Article;
use Illuminate\Support\Facades\Auth;

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
        if(!is_numeric($request->route('id'))){
            return redirect('/home');
        }

        $findAuthor = Article::where('id', $request->route('id'))->first();
        
        if($findAuthor == NULL){
            return redirect('/home');
        }

        if(Auth::user()->admin != 'admin' && Auth::user()->name != $findAuthor->author){
            return redirect('/home');
        }
        return $next($request);
    }
}
