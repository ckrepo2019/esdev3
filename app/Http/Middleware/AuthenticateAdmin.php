<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use DB;


class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {

        try{

            $adminitmodule = DB::table('modules_enable')->where('description','adminit')->first();

        }catch (\Exception $e) {

            $adminitmodule = (object)[
                'create'=>1,
                'updated'=>1,
                'delete'=>1,
            ];

        }

        if( ( auth()->user()->type==6 || Session::get('currentPortal') == 6 ) && ( collect($roles)->contains('admin') || collect($roles)->contains('principal') || collect($roles)->contains('dean') ) ){

            // if( ( collect($roles)->contains('update') && $adminitmodule->update == 0 ) || ( collect($roles)->contains('create') && $adminitmodule->create == 0 ) || ( collect($roles)->contains('delete') && $adminitmodule->delete == 0 )){
                
            //     if($request->ajax()){
            //         return redirect('errormessage');
            //     }
            //     else{
            //         toast('Unable to process!','error')->autoClose(2000)->toToast($position = 'top-right');
            //         return back();
            //     }

            // }
            // else{
                
                return $next($request); 
            // }
            
        }
        elseif( ( auth()->user()->type==2 || Session::get('currentPortal') == 2 ) && collect($roles)->contains('principal')){
       
            return $next($request); 

        }
        elseif( ( auth()->user()->type==14 || Session::get('currentPortal') == 14 ) && collect($roles)->contains('dean')){
       
            return $next($request); 

        }
        elseif( ( auth()->user()->type==16 || Session::get('currentPortal') == 16 ) && collect($roles)->contains('chairperson')){
       
            return $next($request); 

        }
        else{

            $usertype = db::table('teacher')
                            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                            ->where('userid',auth()->user()->id)
                            ->select('refid')
                            ->first();

            if(isset($usertype->refid)){

                if( $usertype->refid == 20 && collect($roles)->contains('assprin')){

                    return $next($request); 

                }


            }

            toast('Unable to process!','error')->autoClose(2000)->toToast($position = 'top-right');
            return redirect()->route('home'); 
            
        }

     
    }
}
