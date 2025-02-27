<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;

class StudentQuarterController extends \App\Http\Controllers\Controller
{
      public static function students(Request $request){

            $syid = $request->get('syid');

            $all_enrolledstudents = array();

            $enrolled = DB::table('enrolledstud')
                        ->join('studinfo',function($join){
                              $join->on('studinfo.id','=','enrolledstud.studid');
                              $join->where('studinfo.deleted',0);
                        })
                        ->join('sections',function($join){
                              $join->on('enrolledstud.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->join('gradelevel',function($join) {
                              $join->on('enrolledstud.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                        })
                        ->join('studentstatus',function($join){
                              $join->on('enrolledstud.studstatus','=','studentstatus.id');
                        })
                        ->where('enrolledstud.deleted',0)
                        ->where('enrolledstud.syid',$syid)
                        ->whereIn('enrolledstud.studstatus',[3,5,6,7])
                        ->select(
                              'sections.sectionname',
                              'levelname',
                              'levelname',
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'description',
                              'enrolledstud.studid',
                              'sid',
                              'studinfo.id',
                              DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                              'enrolledstud.studstatdate'
                        )
                        ->get();
      
            foreach($enrolled as $item){
                  array_push($all_enrolledstudents,$item);
            } 


            $enrolled = DB::table('sh_enrolledstud')
                        ->join('studinfo',function($join){
                              $join->on('studinfo.id','=','sh_enrolledstud.studid');
                              $join->where('studinfo.deleted',0);
                        })
                        ->join('sections',function($join){
                              $join->on('sh_enrolledstud.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                        })
                        ->join('studentstatus',function($join){
                              $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                        })
                        ->where('sh_enrolledstud.deleted',0)
                        ->where('sh_enrolledstud.syid',$syid)
                        ->whereIn('sh_enrolledstud.studstatus',[3,5,6,7])
                        ->distinct()
                        ->select(
                              'sections.sectionname',
                              'levelname',
                              'levelname',
                              'lastname',
                              'firstname',
                              'middlename',
                              'description',
                              'suffix',
                              'sid',
                              'sh_enrolledstud.studid',
                              'studinfo.id',
                              DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                              'sh_enrolledstud.studstatdate'
                        )
                        ->get();

            foreach($enrolled as $item){
                  array_push($all_enrolledstudents,$item);
            } 

            $student_quarter = DB::table('student_quarter')
                                    ->where('syid',$syid)
                                    ->whereIn('studid',collect($all_enrolledstudents)->pluck('studid'))
                                    ->get();

            // return  $student_quarter;

            foreach($all_enrolledstudents as $item){
                  $item->q1 = 1;
                  $item->q2 = 1;
                  $item->q3 = 1;
                  $item->q4 = 1;

                  $check_quarter = collect($student_quarter)->where('studid',$item->studid)->first();

                  if(isset($check_quarter->id)){
                        $item->q1 = $check_quarter->quarter1;
                        $item->q2 = $check_quarter->quarter2;
                        $item->q3 = $check_quarter->quarter3;
                        $item->q4 = $check_quarter->quarter4;
                  }

                  $item->date = \Carbon\Carbon::create($item->studstatdate)->isoFormat('MMM DD, YYYY');

                  
            }

            return $all_enrolledstudents;

      }


      public static function updatequarterstatus(Request $request){

            $syid = $request->get('syid');
            $studid = $request->get('studid');
            $quarter = $request->get('quarter');
            $status = $request->get('status');

            try{

                  $check_data = DB::table('student_quarter')
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->get();

                  if(count($check_data) == 0){
                        $datid = DB::table('student_quarter')
                             ->insertGetId([
                                    'studid'=>$studid,
                                    'syid'=>$syid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                             ]);
                  }else{
                        $datid = $check_data[0]->id;
                  }

                  $quartercolumn = 'quarter'.$quarter;

                  DB::table('student_quarter')
                        ->where('id',$datid)
                        ->take(1)
                        ->update([
                              $quartercolumn=>$status,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  return array((object)[
                        'status'=>1,
                        'message'=>'Updated Successfully'
                  ]);

            }catch(\Exception $e){

                  return $e;

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong'
                  ]);


            }

      }

      
     
      

}
