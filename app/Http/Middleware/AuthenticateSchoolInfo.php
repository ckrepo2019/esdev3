<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class AuthenticateSchoolInfo
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
        $infoCount = DB::table('schoolinfo')->first();

        if($infoCount->schoolname == null){

            return redirect('home');
            
        }
        else{
            return $next($request);
        }
        
    }
}
