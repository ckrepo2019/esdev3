<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;

class CORPrintingController extends \App\Http\Controllers\Controller
{

      public static function enrolled(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $enrolled = DB::table('college_enrolledstud')
                                    ->join('studinfo',function($join) {
                                          $join->on('college_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->leftJoin('college_courses',function($join){
                                          $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->join('college_sections',function($join){
                                          $join->on('college_enrolledstud.sectionid','=','college_sections.id');
                                          $join->where('college_sections.deleted',0);
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->where('college_enrolledstud.syid',$syid)
                                    ->where('college_enrolledstud.semid',$semid)
                                    ->where('college_enrolledstud.deleted',0)
                                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                          'levelname',
                                          'yearLevel as levelid',
                                          'courseabrv',
                                          'gender',
                                          'sid',
                                          'college_enrolledstud.studid',
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'sectionDesc',
                                          'college_enrolledstud.sectionid',
                                          DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                                    )
                                    ->orderBy('studentname')
                                    ->orderBy('studentname')
                                    ->get();

            return $enrolled;

      }
}
