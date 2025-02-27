<?php

namespace App\Http\Controllers\StudentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use DB;
class StudentBehaviorController extends Controller
{
    public function index()
    {

        $id = DB::table('studinfo')
                ->where('userid', '=',auth()->user()->id)
                ->value('id');

        
        $records = DB::table('guidance_behavior_notify')
                    ->where('guidance_behavior_notify.studentid', '=', $id)
                    ->join('guidance_behavior',function($join){
                                        $join->on('guidance_behavior.id','=','guidance_behavior_notify.headerid');
                                        $join->where('guidance_behavior.deleted',0);
                                })
                    ->where('guidance_behavior_notify.deleted', 0)
                    ->orderByDesc('guidance_behavior_notify.createddatetime')
                    ->get();
        
        
        



    return view('studentPortal.pages.guidance.guidanceindex')
            ->with('records' , $records);
    

    }

        public function parentIndex()
        {
                $string = auth()->user()->email;


                // Get the first character of the email
				$firstCharacter = substr($string, 0, 1);

				// Use the rest of the string without the first character
				$sid = substr($string, 1);
			
			
				if($firstCharacter == 'P'){
					
					$parents = 1;
					
				}else{
					
					$parents = 0;
				}
			
				
				


                $id = DB::table('studinfo')
                        ->where('sid', '=', $sid)
                        ->value('id');


                $records = DB::table('guidance_behavior_notify')
                                ->where('guidance_behavior_notify.studentid', '=', $id)
                                ->join('guidance_behavior',function($join){
                                                $join->on('guidance_behavior.id','=','guidance_behavior_notify.headerid');
                                                $join->where('guidance_behavior.deleted',0);
                                        })
                                ->where('guidance_behavior_notify.deleted', 0)
                                ->where('guidance_behavior_notify.parent', $parents)
                                ->orderByDesc('guidance_behavior_notify.createddatetime')
                                ->get();
			

			
		






                return view('parentsportal.pages.guidanceindex')
                        ->with('records' , $records);


        }
}
