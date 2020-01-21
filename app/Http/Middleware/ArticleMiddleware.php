<?php

namespace App\Http\Middleware;

use Closure;
use App\Article;

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

        if(auth()->user()->admin != 'admin' && auth()->user()->name != $findAuthor->author){
            return redirect('/home');
        }
        return $next($request);
    }
}
