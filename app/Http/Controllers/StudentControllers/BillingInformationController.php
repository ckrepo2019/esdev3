<?php

namespace App\Http\Controllers\StudentControllers;

use Illuminate\Http\Request;
use DB;


class BillingInformationController extends \App\Http\Controllers\Controller
{

    
    public static function enrollment_history(Request $request){


        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }  

        $schoolinfo = DB::table('schoolinfo')->first();

        $enrollment_list = array();

        $enrollment = DB::table('enrolledstud')
                                ->where('studid',$studid)
                                ->where('enrolledstud.deleted',0)
                                ->whereIn('studstatus',[1,2,4])
                                ->join('sy',function($join){
                                    $join->on('enrolledstud.syid','=','sy.id');
                                })
                                ->join('semester',function($join){
                                    $join->on('enrolledstud.ghssemid','=','semester.id');
                                })
                                ->select(
                                    'syid',
                                    'sydesc',
                                    'ghssemid as semid',
                                    'sy.isactive',
                                    'semester',
                                    'levelid'
                                )
                                ->get();

        foreach($enrollment as $item){
            if($item->semid != 3){
                $item->text = $item->sydesc;
                $item->id = $item->syid.'-'.$item->semid;
            }else{
                $item->text = $item->sydesc.' - Summer';
                $item->id = $item->syid.'-'.$item->semid;
            }
            array_push($enrollment_list,$item);
        }


        //if($schoolinfo->shssetup == 1){

            $enrollment = DB::table('sh_enrolledstud')
                                ->where('studid',$studid)
                                ->where('sh_enrolledstud.deleted',0)
                                ->whereIn('studstatus',[1,2,4])
                                ->whereIn('semid',[1,2])
                                ->join('sy',function($join){
                                    $join->on('sh_enrolledstud.syid','=','sy.id');
                                })
                                ->join('semester',function($join){
                                    $join->on('sh_enrolledstud.semid','=','semester.id');
                                })
                                ->select(
                                    'studid',
                                    'syid',
                                    'sydesc',
                                    'semid',
                                    'semester',
                                    'sy.isactive',
                                    'levelid'
                                )
                                ->groupBy('syid')
                                ->get();

            foreach($enrollment as $item){
                $item->text = $item->sydesc;
                $item->id = $item->syid.'-'.$item->semid;
                array_push($enrollment_list,$item);
            }

            $enrollment = DB::table('sh_enrolledstud')
                                ->where('studid',$studid)
                                ->where('sh_enrolledstud.deleted',0)
                                ->whereIn('studstatus',[1,2,4])
                                ->whereIn('semid',[3])
                                ->join('sy',function($join){
                                    $join->on('sh_enrolledstud.syid','=','sy.id');
                                })
                                ->join('semester',function($join){
                                    $join->on('sh_enrolledstud.semid','=','semester.id');
                                })
                                ->select(
                                    'syid',
                                    'sydesc',
                                    'semid',
                                    'semester',
                                    'sy.isactive',
                                    'levelid'
                                )
                                ->distinct('studid')
                                ->get();

            foreach($enrollment as $item){
                $item->text = $item->sydesc.' - Summer';
                $item->id = $item->syid.'-'.$item->semid;
                array_push($enrollment_list,$item);
            }

        //}else{

        //}

        $get_enrollment = DB::table('college_enrolledstud')
                                ->where('studid',$studid)
                                ->where('college_enrolledstud.deleted',0)
                                ->join('college_sections',function($join){
                                    $join->on('college_enrolledstud.sectionid','=','college_sections.id');
                                })
                                ->join('gradelevel',function($join){
                                    $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                })
                                ->join('sy',function($join){
                                    $join->on('college_enrolledstud.syid','=','sy.id');
                                })
                                ->join('college_courses',function($join){
                                    $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                                })
                                ->join('semester',function($join){
                                    $join->on('college_enrolledstud.semid','=','semester.id');
                                })
                                ->select(
                                    'semid',
                                    'semester',
                                    'acadprogid',
                                    'courseabrv',
                                    'date_enrolled as dateenrolled',
                                    'college_enrolledstud.yearLevel as levelid',
                                    'college_enrolledstud.syid',
                                    'sydesc',
                                    'sy.isactive',
                                    'levelname',
                                    'sectionDesc as sectionname'
                                )
                                ->get();

        foreach($get_enrollment as $item){
            $item->text = $item->sydesc.' - '.$item->semester;
            $item->id = $item->syid.'-'.$item->semid;
            $item->semester = str_replace(" Semester"," Sem",$item->semester);
            $item->levelname = str_replace(" COLLEGE","",$item->levelname);
            $item->dateenrolled = \Carbon\Carbon::create($item->dateenrolled)->isoFormat('MMM DD, YYYY');
            array_push($enrollment_list,$item);      
        }


        // $balance = self::student_balance_info();

        return $enrollment_list;

    }
    
}
