<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use DB;

class AuthenticateFinance
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

        $user = db::table('users')
            ->select('users.id', 'refid')
            ->join('usertype', 'users.type', '=', 'usertype.id')
            ->where('users.id', auth()->user()->id)
            ->first();

        if (auth()->user()->type == 4 || auth()->user()->type == 15 || Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15 || $user->refid == 19 || $user->refid == 33) {
            return $next($request);
        }

    }
}
