<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use DB;

class AuthenticateAccounting
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



        if ($user->refid == 19 || Session::get('currentPortal') == 15 || auth()->user()->type == 15 || $user->refid == 33) {
            return $next($request);
        }

    }
}
