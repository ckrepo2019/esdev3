<?php

namespace App\Http\Middleware;

use Closure;
use Session;

use DB;
class AuthenticateHumanResource
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
        $refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->first()->refid;

        if(auth()->user()->type==10 || Session::get('currentPortal') == 10 || $refid == 26 ){
            return $next($request); 
       }


       return back();
    }
}
