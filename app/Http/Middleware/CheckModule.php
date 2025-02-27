<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Auth;

class CheckModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
       
        try{

            $check = DB::table('modules_enable')->where('description',$role)->first();

        }catch (\Exception $e) {

            DB::table('zerrorlogs')->insert(
                [
                    'table'=>'modules_enable',
                    'error'=>$e->getMessage(),
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return $next($request);

        }

        if(isset($check->isactive)){

            if($check->isactive == 1){

                return $next($request);

            }

        }

        try{
            Auth::logout();
            return back();
        } catch (\Exception $e) {
            return back();
        }
        
       
        
       
    }
}
