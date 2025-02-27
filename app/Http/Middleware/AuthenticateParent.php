<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AuthenticateParent
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
        if(auth()->user()->type==9){
            return $next($request); 
       }

       return back();
    }
}
