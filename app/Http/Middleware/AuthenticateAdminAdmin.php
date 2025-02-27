<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AuthenticateAdminAdmin
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
        if(auth()->user()->type==12){
            
            return $next($request); 
            
       }

       return back();    }
}
