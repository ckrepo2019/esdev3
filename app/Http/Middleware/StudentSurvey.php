<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class StudentSurvey
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
        if(auth()->user()->type == 7){

            $student = DB::table('studinfo')->where('userid',auth()->user()->id)->where('deleted',0)->select('id')->first();
            $syid = DB::table('sy')->where('isactive',1)->first()->id;

            if(isset($student->id)){

                $check_leaf = DB::table('leasf')
                            ->where('studid',$student->id)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->count();

                if($check_leaf > 0){
                    return $next($request);
                }else{

                    return redirect('/student/view/surveyForm');

                }
            }

           
           
       }

       return back();
    }
}
