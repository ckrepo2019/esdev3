<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AuthenticateCP
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
        if(auth()->user()->type== 16 || Session::get('currentPortal') == 16 || auth()->user()->type== 14 || Session::get('currentPortal') == 14){

            return $next($request); 

        }

        return back();
    }
}
