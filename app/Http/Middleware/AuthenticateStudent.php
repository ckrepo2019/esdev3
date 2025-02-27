<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateStudent
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
        if(auth()->user()->type==7){
            return $next($request); 
       }

       return back();
    }
}
