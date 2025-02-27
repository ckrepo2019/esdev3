<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AuthenticateRegistrar
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
        if(auth()->user()->type==3 || Session::get('currentPortal') == 3){
            return $next($request); 
        }

       return back();
    }
}
