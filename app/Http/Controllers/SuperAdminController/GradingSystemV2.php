<?php

namespace App\Http\Controllers\SuperAdminController;

use App\Http\Controllers\Controller;
use App\Models\Grading\VersionTransfer;
use App\Models\Grading\HighSchool;
use App\Models\Grading\GradeSchool;
use App\Models\Grading\SeniorHigh;
use App\Models\Grading\GradingSystem;
use App\Models\Subjects\Subjects;
use DB;

use Illuminate\Http\Request;

class GradingSystemV2 extends Controller
{
    
    public function transferv1grades(Request $request){

        $grade = DB::table('grades')
                    ->where('grades.id',$request->get('gid'))
                    ->join('gradelevel',function($join){
                        $join->on('grades.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->select('grades.*','gradelevel.acadprogid')
                    ->first();

        $students = DB::table('gradesdetail')
                        ->where('headerid', $grade->id)
                        ->get();

        if($grade->acadprogid == 3){

            $grading_system = GradeSchool::subject_grading_system($grade->subjid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            GradeSchool::generate_student_grade_gradeschool(
                $grading_system[0]->id,
                0,
                $grade->sectionid,
                $grade->subjid,
                $grade->quarter,
                $grade->levelid
            );

            $grading_system_detail = DB::table('grading_system_detail')
                                        ->where('deleted',0)
                                        ->where('headerid',$grading_system[0]->id)
                                        ->get();

            $ww = collect($grading_system_detail)->where('sf9val',1)->first()->id;
            $pt = collect($grading_system_detail)->where('sf9val',2)->first()->id;

            $gradeidww = DB::table('grading_system_gsgrades')
                            ->where('gsdid',$ww)
                            ->where('studid',0)
                            ->where('sectionid',$grade->sectionid)
                            ->where('subjid',$grade->subjid)
                            ->where('levelid',$grade->levelid)
                            ->select('id')
                            ->first();

            $gradeidpt = DB::table('grading_system_gsgrades')
                            ->where('gsdid',$pt)
                            ->where('studid',0)
                            ->where('sectionid',$grade->sectionid)
                            ->where('subjid',$grade->subjid)
                            ->where('levelid',$grade->levelid)
                            ->select('id')
                            ->first();

            for($x = 0 ; $x < 10 ; $x++){

                $wwfield = 'wwhr'.$x;
                $ptfield = 'pthr'.$x;
                $tablefield = 'g'.($x+1).'q'.$grade->quarter;

                if($grade->$wwfield != 0){

                    GradeSchool::submit_student_grade_gradeschool(
                                    0,
                                    $gradeidww->id,
                                    $tablefield,
                                    $grade->$wwfield,
                    );

                }

                if($grade->$ptfield != 0){

                    GradeSchool::submit_student_grade_gradeschool(
                                    0,
                                    $gradeidpt->id,
                                    $tablefield,
                                    $grade->$ptfield,
                    );

                }


            }

            foreach($students as $item){

                GradeSchool::generate_student_grade_gradeschool(
                    $grading_system[0]->id,
                    $item->studid,
                    $grade->sectionid,
                    $grade->subjid,
                    $grade->quarter,
                    $grade->levelid
                );

                $gradeidww = DB::table('grading_system_gsgrades')
                                ->where('gsdid',$ww)
                                ->where('studid',$item->studid)
                                ->where('sectionid',$grade->sectionid)
                                ->where('subjid',$grade->subjid)
                                ->where('levelid',$grade->levelid)
                                ->select('id')
                                ->first();

                $gradeidpt = DB::table('grading_system_gsgrades')
                                ->where('gsdid',$pt)
                                ->where('studid',$item->studid)
                                ->where('sectionid',$grade->sectionid)
                                ->where('subjid',$grade->subjid)
                                ->where('levelid',$grade->levelid)
                                ->select('id')
                                ->first();

                for($x = 0 ; $x < 10 ; $x++){

                    $wwfield = 'ww'.$x;
                    $ptfield = 'pt'.$x;
                    $tablefield = 'g'.($x+1).'q'.$grade->quarter;

                    if($item->$wwfield != 0){

                            GradeSchool::submit_student_grade_gradeschool(
                                            $item->studid,
                                            $gradeidww->id,
                                            $tablefield,
                                            $item->$wwfield,
                            );

                    }

                    if($item->$ptfield != 0){

                        GradeSchool::submit_student_grade_gradeschool(
                                        $item->studid,
                                        $gradeidpt->id,
                                        $tablefield,
                                        $item->$ptfield,
                        );

                    }

                }

            }

            

            // foreach($students as $item){

            //     GradeSchool::generate_student_grade(
            //         $grading_system[0]->id,
            //         $item->studid,
            //         $grade->sectionid,
            //         $grade->subjid,
            //         $grade->quarter,
            //         $grade->levelid
            //     );

            // }


        }

        else if($grade->acadprogid == 4){

            $grading_system = HighSchool::subject_grading_system($grade->subjid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            HighSchool::generate_student_grade(
                $grading_system[0]->id,
                0,
                $grade->sectionid,
                $grade->subjid,
                $grade->quarter,
                $grade->levelid
            );

            $grading_system_detail = DB::table('grading_system_detail')
                                        ->where('deleted',0)
                                        ->where('headerid',$grading_system[0]->id)
                                        ->get();

            $ww = collect($grading_system_detail)->where('sf9val',1)->first()->id;
            $pt = collect($grading_system_detail)->where('sf9val',2)->first()->id;

            $gradeidww = DB::table('grading_system_grades_hs')
                            ->where('gsdid',$ww)
                            ->where('studid',0)
                            ->where('sectionid',$grade->sectionid)
                            ->where('subjid',$grade->subjid)
                            ->where('levelid',$grade->levelid)
                            ->select('id')
                            ->first();

            $gradeidpt = DB::table('grading_system_grades_hs')
                            ->where('gsdid',$pt)
                            ->where('studid',0)
                            ->where('sectionid',$grade->sectionid)
                            ->where('subjid',$grade->subjid)
                            ->where('levelid',$grade->levelid)
                            ->select('id')
                            ->first();

            for($x = 0 ; $x < 10 ; $x++){

                $wwfield = 'wwhr'.$x;
                $ptfield = 'pthr'.$x;
                $tablefield = 'g'.($x+1).'q'.$grade->quarter;

                if($grade->$wwfield != 0){

                    HighSchool::submit_student_grade(
                                    0,
                                    $gradeidww->id,
                                    $tablefield,
                                    $grade->$wwfield,
                    );

                }

                if($grade->$ptfield != 0){

                    HighSchool::submit_student_grade(
                                    0,
                                    $gradeidpt->id,
                                    $tablefield,
                                    $grade->$ptfield,
                    );

                }


            }

            foreach($students as $item){

                HighSchool::generate_student_grade(
                    $grading_system[0]->id,
                    $item->studid,
                    $grade->sectionid,
                    $grade->subjid,
                    $grade->quarter,
                    $grade->levelid
                );

                $gradeidww = DB::table('grading_system_grades_hs')
                                ->where('gsdid',$ww)
                                ->where('studid',$item->studid)
                                ->where('sectionid',$grade->sectionid)
                                ->where('subjid',$grade->subjid)
                                ->where('levelid',$grade->levelid)
                                ->select('id')
                                ->first();

                $gradeidpt = DB::table('grading_system_grades_hs')
                                ->where('gsdid',$pt)
                                ->where('studid',$item->studid)
                                ->where('sectionid',$grade->sectionid)
                                ->where('subjid',$grade->subjid)
                                ->where('levelid',$grade->levelid)
                                ->select('id')
                                ->first();

                for($x = 0 ; $x < 10 ; $x++){

                    $wwfield = 'ww'.$x;
                    $ptfield = 'pt'.$x;
                    $tablefield = 'g'.($x+1).'q'.$grade->quarter;

                    if($item->$wwfield != 0){

                            HighSchool::submit_student_grade(
                                            $item->studid,
                                            $gradeidww->id,
                                            $tablefield,
                                            $item->$wwfield,
                            );

                    }

                    if($item->$ptfield != 0){

                        HighSchool::submit_student_grade(
                                        $item->studid,
                                        $gradeidpt->id,
                                        $tablefield,
                                        $item->$ptfield,
                        );

                    }

                }

            }

        }

        else if($grade->acadprogid == 5){

            // $studentstrackid = DB::table('gradesdetail')
            //             ->join('studinfo',function($join){
            //                 $join->on('gradesdetail.studid','=','studinfo.id');
            //                 $join->where('studinfo.deleted',0);
            //             })
            //             ->join('sh_strand',function($join){
            //                 $join->on('studinfo.strandid','=','sh_strand.id');
            //                 $join->where('sh_strand.deleted',0);
            //             })
            //             ->where('studinfo.sectionid',$grade->sectionid)
            //             ->where('studinfo.deleted',0)
            //             ->select('trackid')
            //             ->where('headerid', $grade->id)
            //             ->get();


            // $tvlCount = 0;
            // $

            // $grading_system = GradingSystem::evaluate_grading_system_seniorhigh();

            // if( $grading_system[0]->status == 1){

            //       $grading_system =  $grading_system[0]->data;

            // }
            // else{

            //       return $grading_system;

            // }
 
            // SeniorHigh::generate_student_grade(
            //     $grading_system[0]->id,
            //     0,
            //     $grade->sectionid,
            //     $grade->subjid,
            //     $grade->quarter,
            //     $grade->levelid
            // );

            // foreach($students as $item){

            //     SeniorHigh::generate_student_grade(
            //         $grading_system[0]->id,
            //         $item->studid,
            //         $grade->sectionid,
            //         $grade->subjid,
            //         $grade->quarter,
            //         $grade->levelid
            //     );

            // }

            $subjectStatus = Subjects::get_sh_subject($grade->subjid);

            if(count($subjectStatus) == 0){
                  
                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Subject does not exist"
                  ]);

                  return $data;
            }

            $grading_system = SeniorHigh::subject_grading_system($grade->subjid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;


                  if($subjectStatus[0]->type == 1){

                    $grading_system[0]->trackid = 1;

                  }

            }
            else{

                  return $grading_system;

            }

            if($subjectStatus[0]->type == 1){

                

                if(collect($grading_system)->where('trackid',1)->count() == 0){


                    $data = array((object)[
                        'status'=>0,
                        'data'=>"No available grading system this subject"
                    ]);

                    return $data;

                }else{

                    $gsacad = collect($grading_system)->where('trackid',1)->first();

                    $gradingSystemAcad = DB::table('grading_system_detail')
                                            ->where('deleted',0)
                                            ->where('headerid',$gsacad->id)
                                            ->get();

                    $wwacad = collect($gradingSystemAcad)->where('sf9val',1)->first()->id;
                    $ptaacad = collect($gradingSystemAcad)->where('sf9val',2)->first()->id;
                    
                }

            }
            else{


                if(collect($grading_system)->where('trackid',1)->count() == 0){


                    $data = array((object)[
                        'status'=>0,
                        'data'=>"No available grading system this academic track"
                    ]);

                    return $data;

                }
                else{

                    $gsacad = collect($grading_system)->where('trackid',1)->first();

                    $gradingSystemAcad = DB::table('grading_system_detail')
                                            ->where('deleted',0)
                                            ->where('headerid',$gsacad->id)
                                            ->get();

                    $wwacad = collect($gradingSystemAcad)->where('sf9val',1)->first()->id;
                    $ptaacad = collect($gradingSystemAcad)->where('sf9val',2)->first()->id;
                                

                }




                if(collect($grading_system)->where('trackid',2)->count() == 0){


                    $data = array((object)[
                        'status'=>0,
                        'data'=>"No available grading system this tvl track"
                    ]);

                    return $data;

                }else{

                    $gstvl = collect($grading_system)->where('trackid',2)->first();


                    $gradingSystemTVL = DB::table('grading_system_detail')
                                            ->where('deleted',0)
                                            ->where('headerid',$gstvl->id)
                                            ->get();

                    $wwtvl = collect($gradingSystemTVL)->where('sf9val',1)->first()->id;
                    $pttvl = collect($gradingSystemTVL)->where('sf9val',2)->first()->id;
                        

                }

            }

            // return $students;


            foreach($grading_system as $item){

                $itemgsid = null;

                if($item->trackid == 1){
                    $itemgsid = $gsacad->id;
                }
                else{
                    $itemgsid = $gstvl->id;
                }

                SeniorHigh::generate_student_grade(
                    $itemgsid,
                    0,
                    $grade->sectionid,
                    $grade->subjid,
                    $grade->quarter,
                    $grade->levelid
                );

                $grading_system_detail = DB::table('grading_system_detail')
                                            ->where('deleted',0)
                                            ->where('headerid',$itemgsid)
                                            ->get();

                $ww = collect($grading_system_detail)->where('sf9val',1)->first()->id;
                $pt = collect($grading_system_detail)->where('sf9val',2)->first()->id;

                $gradeidww = DB::table('grading_system_grades_sh')
                                ->where('gsdid',$ww)
                                ->where('studid',0)
                                ->where('sectionid',$grade->sectionid)
                                ->where('subjid',$grade->subjid)
                                ->where('levelid',$grade->levelid)
                                ->select('id')
                                ->first();

                $gradeidpt = DB::table('grading_system_grades_sh')
                                ->where('gsdid',$pt)
                                ->where('studid',0)
                                ->where('sectionid',$grade->sectionid)
                                ->where('subjid',$grade->subjid)
                                ->where('levelid',$grade->levelid)
                                ->select('id')
                                ->first();

                for($x = 0 ; $x < 10 ; $x++){

                    $wwfield = 'wwhr'.$x;
                    $ptfield = 'pthr'.$x;
                    $tablefield = 'g'.($x+1).'q'.$grade->quarter;

                    if($grade->$wwfield != 0){

                        SeniorHigh::submit_student_grade(
                                        0,
                                        $gradeidww->id,
                                        $tablefield,
                                        $grade->$wwfield,
                        );

                    }

                    if($grade->$ptfield != 0){

                        SeniorHigh::submit_student_grade(
                                        0,
                                        $gradeidpt->id,
                                        $tablefield,
                                        $grade->$ptfield,
                        );

                    }

                }

            }

            // return $ww;

            // return "gjhgjh";

            foreach($students as $item){

                    $studww = null;
                    $studpt = null;
                    $studtrackid = null;

                    if($subjectStatus[0]->type == 1){

                        $studww = $wwacad;
                        $studpt = $ptaacad;
                        $studtrackid = 1;

                    }else{

                        $students = DB::table('studinfo')   
                                            ->where('studinfo.studstatus',1)
                                            ->join('sh_enrolledstud',function($join){
                                                $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                                $join->where('sh_enrolledstud.deleted',0);
                                            })
                                            ->join('sh_strand',function($join){
                                                $join->on('studinfo.strandid','=','sh_strand.id');
                                                $join->where('sh_strand.deleted',0);
                                            })
                                            ->where('studinfo.sectionid',$grade->sectionid)
                                            ->where('studinfo.deleted',0)
                                            ->select('trackid')
                                            ->first();


                        

                        if($students->trackid == 1){

                            $studww = $wwacad;
                            $studpt = $ptaacad;
                            $studtrackid = 2;

                        }
                        else{

                            $studww = $wwtvl;
                            $studpt = $pttvl;
                            $studtrackid = 2;

                        }
                    }

                    $gs = collect($grading_system)->where('trackid',$studtrackid)->first();

                    SeniorHigh::generate_student_grade(
                        $gs->id,
                        $item->studid,
                        $grade->sectionid,
                        $grade->subjid,
                        $grade->quarter,
                        $grade->levelid
                    );

                    $gradeidww = DB::table('grading_system_grades_sh')
                                    ->where('gsdid',$studww)
                                    ->where('studid',$item->studid)
                                    ->where('sectionid',$grade->sectionid)
                                    ->where('subjid',$grade->subjid)
                                    ->where('levelid',$grade->levelid)
                                    ->select('id')
                                    ->first();

                    $gradeidpt = DB::table('grading_system_grades_sh')
                                    ->where('gsdid',$studpt)
                                    ->where('studid',$item->studid)
                                    ->where('sectionid',$grade->sectionid)
                                    ->where('subjid',$grade->subjid)
                                    ->where('levelid',$grade->levelid)
                                    ->select('id')
                                    ->first();

                    if(!isset($gradeidww->id)){

                        return $item->studid;

                    }

                    for($x = 0 ; $x < 10 ; $x++){

                        $wwfield = 'ww'.$x;
                        $ptfield = 'pt'.$x;
                        $tablefield = 'g'.($x+1).'q'.$grade->quarter;

                        if($item->$wwfield != 0){

                            SeniorHigh::submit_student_grade(
                                                $item->studid,
                                                $gradeidww->id,
                                                $tablefield,
                                                $item->$wwfield,
                                );


                        }

                        if($item->$ptfield != 0){

                            SeniorHigh::submit_student_grade(
                                            $item->studid,
                                            $gradeidpt->id,
                                            $tablefield,
                                            $item->$ptfield,
                            );

                        }

                    }

            }


        }
            

        $grade = DB::table('grades')
                    ->where('grades.id',$request->get('gid'))
                    ->update([
                        'transfered'=>'1',
                        'datetransfered'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

        return 1;


    }

    public function getv1grades(){

        $grade_school = VersionTransfer::v1gradesgs();
        $junior_high_school = VersionTransfer::v1gradesjs();
        $senior_high_school = VersionTransfer::v1gradessh();

        $available_grades = array();

        foreach($grade_school as $item){

            array_push($available_grades, $item);

        }

        foreach($junior_high_school as $item){

            array_push($available_grades, $item);

        }

        foreach($senior_high_school as $item){

            array_push($available_grades, $item);

        }

        
        foreach($available_grades as $key=>$item){

            $grades = DB::table('grades')->where('id',$item->id)->first();

            $wwwcount = 10;
            $ptcount = 10;

            for($x = 0; $x < 10 ;$x++){

                $field = 'wwhr'.$x;

                if($grades->$field != 0){

                    $wwwcount -= 1;

                }

                $field = 'pthr'.$x;

                if($grades->$field != 0){

                    $ptcount -= 1;

                }

            }

            if($wwwcount == 10 && $ptcount == 10){

                unset($available_grades[$key]);

            }

        }

        $available_grades = collect($available_grades)->sortBy('transfered');

        return view('superadmin.pages.gradingsystem.v1grades')
                    ->with('available_grades',$available_grades);
     
    }


    public static function subjectAssignment(Request $request){

        $grading_system_info = GradingSystem::get_grading_system_by_id($request->get('gsid'));

        $subject_assignments = array();

        if($grading_system_info[0]->acadprogid == 3 ){

            $subject_assignments =  Subjects::grade_school_subject_assignment($grading_system_info[0]->id);

        }
        else if($grading_system_info[0]->acadprogid == 4 ){

            $subject_assignments =  Subjects::high_school_subject_assignment($grading_system_info[0]->id);

        }
        else if($grading_system_info[0]->acadprogid == 5 ){

            $subject_assignments =  Subjects::senior_high_subject_assignment($grading_system_info[0]->id);
            
        }

        $subject_assignments = collect($subject_assignments)->sortBy('gsid');

        return view('superadmin.pages.gradingsystem.grading_subject_assignment')
                        ->with('subject_assignments',$subject_assignments)
                        ->with('grading_system_info',$grading_system_info)
                        ->with('gsid',$request->get('gsid'));

    }

    public static function addSubjectAssignment(Request $request){

        $gsid = $request->get('gsid');
        $subjectid = $request->get('subjectid');

        return GradingSystem::add_subject_assignment($gsid, $subjectid);
    
    }

    public static function removeSubjectAssignment(Request $request){

        $gssid = $request->get('gssid');

        return GradingSystem::remove_subject_assignment($gssid);
    
    }


}
