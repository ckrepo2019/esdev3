<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AuthenticateCT
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
        if(auth()->user()->type== 18 || Session::get('currentPortal') == 18){

            return $next($request); 

        }

        return back();
    }
}
