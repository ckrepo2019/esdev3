<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use DB;

class AuthenticatePrincipal
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
        
        $usertype = DB::table('usertype')->where('id',auth()->user()->type)->first();

        if(auth()->user()->type==2 || Session::get('currentPortal') == 2){

            $response = $next($request);

            return $response;

        }
        else if(collect($roles)->contains('princoor') && (isset($usertype->refid) && $usertype->refid == 20)){

            $response = $next($request);

            return $response;

        }
        else if(collect($roles)->contains('princoor') && (isset($usertype->refid) && $usertype->refid == 22)){

            $response = $next($request);

            return $response;

        }
        else{

            return redirect('/');

        }

       return redirect('/');
    }
}
