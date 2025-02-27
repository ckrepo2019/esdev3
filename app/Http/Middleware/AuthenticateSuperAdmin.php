<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next,  ... $roles)
    {
        if(auth()->user()->type==17){

            return $next($request); 
            
        }
        elseif(auth()->user()->type == 6 && collect($roles)->contains('admin')){

                return $next($request); 

        }
        elseif(auth()->user()->type == 3 && collect($roles)->contains('registrar')){

            return $next($request); 

        }
        elseif(auth()->user()->type == 1 && collect($roles)->contains('teacher')){

            return $next($request); 

        }
        elseif(auth()->user()->type == 2 && collect($roles)->contains('principal')){

            return $next($request); 

        }

        return back();
    }
}
