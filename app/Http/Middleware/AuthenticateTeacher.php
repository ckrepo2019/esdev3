<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AuthenticateTeacher
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
        if(auth()->user()->type==1 || Session::get('currentPortal') == 1){

            return $next($request); 
            
        }

        return back();
        

    }
}
