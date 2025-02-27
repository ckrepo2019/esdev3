<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use DB;

class AdminMiddleware
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
        if (auth()->check()) {
            $check_refid = DB::table('usertype')
                ->where('id', \Session::get('currentPortal'))
                ->select('refid', 'resourcepath')
                ->first();

            if ($check_refid->refid == 34) {
                return $next($request);
            }
        }

        // If not an admin, you can redirect or perform other actions
        return redirect('/home'); // Redirect to home or another route
    }
}
