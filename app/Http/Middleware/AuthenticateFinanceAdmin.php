<?php

namespace App\Http\Middleware;

use Closure;
use Session;


class AuthenticateFinanceAdmin
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
        if (auth()->user()->type == 15 || Session::get('currentPortal') == 15) {
            return $next($request);
        }

    }
}
