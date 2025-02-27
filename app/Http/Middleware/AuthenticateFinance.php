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
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect('/'); // Redirect if not authenticated
        }

        $authUser = auth()->user();

        // Fetch user details from the database
        $user = DB::table('users')
            ->select('users.id', 'refid')
            ->join('usertype', 'users.type', '=', 'usertype.id')
            ->where('users.id', $authUser->id)
            ->first();

        // Ensure $user is not null before accessing properties
        $refid = $user ? $user->refid : null;

        // Authorization logic
        if (
            in_array($authUser->type, [4, 15]) ||
            in_array(Session::get('currentPortal'), [4, 15]) ||
            in_array($refid, [19, 33])
        ) {
            try {
                return $next($request); // Proceed with the request
            } catch (\Exception $e) {
                return redirect('/'); // Handle unexpected errors
            }
        }

        // If the user is unauthorized, redirect to a forbidden page or home
        return redirect('/');
    }
}
