<?php

namespace App\Http\Middleware;

use Closure;
use Hash;

class AuthenticateDefaultPass
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
        
        if(Hash::check('123456', auth()->user()->password)){

            return response()->view('resetpass');

        }
        else{

            return $next($request);

        }


     
    }
}
