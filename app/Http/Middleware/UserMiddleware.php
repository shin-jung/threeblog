<?php

namespace App\Http\Middleware;

use Closure;
use App\Article;
class UserMiddleware
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

        $findauthor = Article::where('id', $request->route('id'))->first();
        if($findauthor == NULL){
            return redirect('/home');
        }
        if(auth()->user()->admin != 'admin' && auth()->user()->name != $findauthor->author){
            return redirect('/home');
        }
        return $next($request);
    }
}
