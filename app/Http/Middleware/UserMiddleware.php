<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use JWTAuth;

class UserMiddleware
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
        if(JWTAuth::user()->admin != 'admin'){
            return redirect('/home');
        }
        return $next($request);
    }
}
