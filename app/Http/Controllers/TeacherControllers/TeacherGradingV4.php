<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use App\Models\Grading\IndividualGrading;
use App\Models\Grading\GradingSystem;
use App\Models\Grading\GradeSchool;
use App\Models\Principal\SPP_Subject;
use App\Models\Grading\GradeStatus;
use DB;
use Session;


class TeacherGradingV4 extends \App\Http\Controllers\Controller
{

    
    public function preschool(){
        
        return view('teacher.grading.v4.grading_version4');

    }

    public function individual_submission(Request $request){

        if($request->ajax()){

            $studid = $request->get('studid');
            $gdid = $request->get('gdid');
            
            return IndividualGrading::submit_student_grade($gdid , $studid);

        }


    }

    public function individual_pending(Request $request){

        if($request->ajax()){

            $studid = $request->get('studid');
            $gdid = $request->get('gdid');
            
            return IndividualGrading::submit_student_grade($gdid , $studid);

        }


    }


    public function individual_all(Request $request){


        $grade_list = IndividualGrading::submitted_grade();

        return view('principalsportal.pages.individual_grading.individ_grading')
                ->with('grade_list',$grade_list);


    }

    public function grade_detail(Request $request){

        $studid = $request->get('studid');
        $gdid = $request->get('gdid');

        $grades_detail = IndividualGrading::grade_detail($gdid , $studid);


        $gradesetup = $grades_detail[0]->gradeSetup;
        $hps  = $grades_detail[0]->hps ;
        $grades = $grades_detail[0]->detail;
        $transmutation  =  $grades_detail[0]->transmutation;


        return view('principalsportal.pages.individual_grading.student_grade')
                ->with('gradesetup',$gradesetup)
                ->with('hps',$hps )
                ->with('transmutation',$transmutation )
                ->with('grades',$grades);



    }

    public function post_student_grade(Request $request){

        $version_check = GradingSystem::checkVersion();

        if($version_check->version == 'v1'){

            $studid = $request->get('studid');
            $gdid = $request->get('gdid');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
    
            return IndividualGrading::post_student_grade($gdid , $studid, $quarter, $syid, $semid);
    

        }else if($version_check->version == 'v2'){

            $studid = $request->get('studid');
            $gdid = $request->get('gdid');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            return IndividualGrading::post_student_grade_v2($gdid , $studid, $quarter, $syid, $semid);

        }

    }


    public function grades_posting(){

        return view('principalsportal.pages.grading.grade_posting');
        
    }


    public static function  get_student(Request $request){

        
        $grades = self::get_student_data($request);


        foreach(collect($grades)->where('student','!=','SUBJECTS') as $item){
            $new_grades = array();
            foreach($item->grades as $key=>$second_item){
                if(isset($second_item->subjid)){
                    if($second_item->subjid != 'G1'  && $second_item->subjid != 'FRBOTH'){
                        array_push($new_grades,$second_item);
                    }
                }
            }
            for($x = 0; $x < ( 30 - count( $new_grades) ) ; $x++ ){
                $temp_data = (object)[
                    'subjid'=>"",
                    'subjdesc'=>"",
                    'qg'=>"",
                    'status'=>"",
                    'teacherid'=>""
                ];
                array_push($new_grades,$temp_data);
            }

            $item->grades =  $new_grades;
        }
        return $grades;
    }

    public static function  get_student_data(Request $request){

        $activesem = null;
        $activesy = $request->get('sy');
        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $strandid = $request->get('strand');
        if($request->get('inSF9') != ""){
            $isSF9 = $request->get('inSF9');
        }else{
            $isSF9 = false;
        }

        if(Session::get('currentPortal') == 1){
            $isSF9 = true;
        }
       

        if($gradelevel == 14 || $gradelevel == 15){
            $activesem = $request->get('semid');
            $students =  \App\Models\SuperAdmin\SuperAdminData::student_sh_enrollment($activesy, $activesem, null, $section);
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel,$strandid,$activesem,$activesy);
        }else{
            $activesem = 1;
            $students =  \App\Models\SuperAdmin\SuperAdminData::student_gshs_enrollment($activesy, null, $section);

            $isforsp = false;

            $sectioninfo = DB::table('sectiondetail')
                                        ->where('syid',$activesy)
                                        ->where('sectionid',$section)
                                        ->where('deleted',0)
                                        ->first();

            if(isset($sectioninfo)){
                if($sectioninfo->sd_issp == 1){
                    $isforsp = true;
                }
            }
            

            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel,$activesy,$isforsp);
            $schoolinfo = DB::table('schoolinfo')->select('abbreviation')->first();
            if($schoolinfo->abbreviation == strtoupper('HCHS CP')){
                  $final_subject = array();
                  foreach($subjects as $item){
                        if($section == 4 || $section == 6 || $section == 10 || $section == 4){
                            array_push($final_subject, $item);
                        }else{
                              if(
                                    $item->id == 10 ||
                                    $item->id == 11 ||
                                    $item->id == 12 ||
                                    $item->id == 48 ||
                                    $item->id == 22 ||
                                    $item->id == 24 ||
                                    $item->id == 47 ||
                                    $item->id == 34 ||
                                    $item->id == 35 ||
                                    $item->id == 36 ||
                                    $item->id == 46
                              ){

                              }else{
                                    array_push($final_subject, $item);
                              }
                              
                        }
                  }
                $subjects = $final_subject;
           }
        }
        $subjects = collect($subjects)->sortBy('sortid')->values();
   
        $grading_version = \App\Models\Grading\GradingSystem::checkVersion();

        foreach($students as $item){
            $strand = null;
            
                if($gradelevel == 14 || $gradelevel == 15){
                    $strand = $item->strandid;
                }
                
                if($activesy == 2 && $grading_version->version == 'v2'){
                    $grades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2($gradelevel,$item->studid,$activesy,$strand,$activesem,$section,$isSF9);
                }else{
                    $grades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel,$item->studid,$activesy,$strand,$activesem,$section,$isSF9);
                }

                $grades = collect($grades)->sortBy('sortid')->values();

                if($gradelevel == 14 || $gradelevel == 15){
                    if($activesem != null){
                        if($activesem == 1){
                            unset($grades[count($grades) - 1]);
                        }else{
                            unset($grades[count($grades) - 2]);
                        }
                    }
                }
                $item->grades = $grades;
                // return $grades;
             
               
                $item->finalgrade = collect($grades)->where('id','G1')->values();
                if($quarter != 6){
                    if($quarter == 5){
                        $gid = null;
                        $temp_quarter = 'finalrating';
                        $temp_quarter_status = 5;
                    }else{
                        $gid = 'q'.$quarter.'gid';
                        $temp_quarter_status = 'q'.$quarter.'status';
                        $temp_quarter = 'q'.$quarter;
                    }
                    if($quarter != null){
                        foreach($grades as $grade_item){
                            $grade_item->qg = $grade_item->$temp_quarter;
                            $grade_item->status = $quarter != 5 ? $grade_item->$temp_quarter_status : 4;
                            $grade_item->gdid = $quarter != 5 ? $grade_item->$gid : null;
                        }
                    }
                }
               
                $middlename = explode(" ",$item->middlename);
                $temp_middle = '';
                if($item->middlename != null){
                    $temp_middle = $item->middlename[0].'.';
                }
                $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
            // }
        }

        $holder = array();

        foreach($subjects as $item){
            $headerid = DB::table('grades')
                                ->where('subjid',$item->id)
                                ->where('sectionid',$section)   
                                ->where('levelid',$gradelevel)   
                                ->where('syid',$activesy)   
                                ->where('deleted',0)
                                ->where('quarter',$quarter)   
                                ->select('id','submitted','status')
                                ->first();
            if( isset($headerid->id) ){
                $item->gsdid = $headerid->id;
                if( $headerid->submitted == 0 && $headerid->status == 0 ){
                    $item->status = 0;
                }
                else if( $headerid->submitted == 1 && $headerid->status == 0 ){
                    $item->status = 1;
                }
                else{
                    $item->status = $headerid->status;
                }
            }else{
                $item->gsdid = null;
                $item->status = 0;
            }
        }
     

     
        //grade summary
        $temp_subject = array();
        foreach($subjects as $key=>$item){
            if(isset($item->subjcode)){
                $item->subjtitle = $item->subjdesc;
                $item->subjdesc = $item->subjcode;
            }else{
                $item->subjtitle = $item->subjdesc;
            }
            $item->subjid = $item->id;
            // if($item->isVisible == 1){
                array_push($temp_subject,$item);
            // }
           
        }


        $subjects = $temp_subject;
        
        for($x = 0; $x < ( 20 - count( $temp_subject ) ) ; $x++ ){
            $temp_data = (object)[
                'subjid'=>"",
                'subjdesc'=>"",
                'qg'=>"",
                'status'=>"",
                'teacherid'=>""
            ];
            array_push($subjects,$temp_data);
            
        }

        array_push($holder,(object)[
            'student'=>'SUBJECTS',
            'sort'=>1,
            'grades'=>$subjects
        ]);

        $sort = 2;
        foreach($students as $item){
            $temp_grades = array();
            foreach($item->grades as $studgrades_item){
                if($quarter != null){
                    if(isset($studgrades_item->gdstatus)){
                        $studgrades_item->status = $studgrades_item->gdstatus;
                    }else{
                        if(isset($studgrades_item->subjid)){
                            if($studgrades_item->subjid == 'M1' || $studgrades_item->subjid == 'TLE1'){
                                $temp_quarter = 'q'.$quarter;
                                $studgrades_item->status = 1;
                                $studgrades_item->qg =  $studgrades_item->$temp_quarter;
                            }
                        }
                    }
                }
            
                array_push($temp_grades,$studgrades_item);
            }
            $temp_grades  = $temp_grades;
            for($x = 0; $x < ( 19 - count( $temp_grades ) ) ; $x++ ){
                $temp_data = (object)[
                    'subjid'=>"",
                    'subjdesc'=>"",
                    'qg'=>"",
                    'status'=>"",
                    'teacherid'=>""
                ];
                array_push($temp_grades,$temp_data);
            }

            $temp_semid = 1;

            if($gradelevel == 14 || $gradelevel == 15){
                $temp_semid = $item->semid;
            }

            array_push($holder,(object)[
                'strand'=>isset($item->strandid) ? $item->strandid : null,
				'strandcode'=>isset($item->strandcode) ? $item->strandcode : null,
				'sectionname'=>$item->sectionname,
                'studid'=>$item->studid,
                'student'=>$item->student,
                'gender'=>$item->gender,
                'semid'=>$temp_semid,
                'sort'=>$sort,
                'grades'=>$temp_grades
            ]);

            $sort += 1; 
        }

        return $holder;

    }

    public static function unpost_student_grade(Request $request){

        $version_check = GradingSystem::checkVersion();

        if($version_check->version == 'v1'){

            $gdid = $request->get('gdid');
            $studid = $request->get('studid');
            $quarter = $request->get('quarter');
           
            return IndividualGrading::unpost_student_grade($gdid, $studid);


        }else if($version_check->version == 'v2'){

            $gdid = $request->get('gdid');
            $studid = $request->get('studid');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
    
            return IndividualGrading::unpost_student_grade_v2($gdid, $studid, $quarter, $syid, $semid);


        }


    }

    public static function approve_student_grade(Request $request){

        $version_check = GradingSystem::checkVersion();

        if($version_check->version == 'v1'){

            $gdid = $request->get('gdid');
            $studid = $request->get('studid');
            $quarter = $request->get('quarter');
    
            return IndividualGrading::approve_student_grade($gdid, $studid);


        }
        else if($version_check->version == 'v2'){

            $gdid = $request->get('gdid');
            $studid = $request->get('studid');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            return IndividualGrading::approve_student_grade_v2($gdid, $studid, $quarter, $syid, $semid);


        }


    }

    public static function pending_student_grade(Request $request){

        $version_check = GradingSystem::checkVersion();

        if($version_check->version == 'v1'){

            $gdid = $request->get('gdid');
            $studid = $request->get('studid');
            $teacherid = $request->get('teacherid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            return IndividualGrading::pending_student_grade($gdid, $studid, $teacherid);

        }else if($version_check->version == 'v2'){

            $gdid = $request->get('gdid');
            $studid = $request->get('studid');
            $teacherid = $request->get('teacherid');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            return IndividualGrading::pending_student_grade_v2($gdid, $studid, $teacherid, $quarter, $syid, $semid);

        }

    }


    public static function unpost_subject_grade(Request $request){
        $teacherid = $request->get('teacherid');
        $gdid = $request->get('gdid');
        return IndividualGrading::unpost_subject_grade($gdid,$teacherid);
    }

    public static function post_subject_grade(Request $request){
        $teacherid = $request->get('teacherid');
        $gdid = $request->get('gdid');
        return IndividualGrading::post_subject_grade($gdid,$teacherid);
    }
    

    public static function pending_subject_grade(Request $request){
        $teacherid = $request->get('teacherid');
        $gdid = $request->get('gdid');
        return IndividualGrading::pending_subject_grade($gdid);
    }

    public static function approve_subject_grade(Request $request){
        $teacherid = $request->get('teacherid');
        $gdid = $request->get('gdid');
        return IndividualGrading::approve_subject_grade($gdid);
    }


    


    public static function resubmit_student_grade(Request $request){

        $pid = $request->get('pending_id');
        return IndividualGrading::resubmit_grades($pid);

    }

    public static function invalid_grades(Request $request){

        if(!$request->has('quarter') && $request->get('quarter') != null && $request->get('quarter') != ''){
            return "Please specify a quarter";
        }
        if(!$request->has('acadprog') && $request->get('acadprog') != null && $request->get('acadprog') != ''){
            return "Please specify a acadprog";
        }

        $quarter = $request->get('quarter');
        $igquarter = 'igq'.$quarter;
        $qgquarter = 'qgq'.$quarter;
        
        if($request->get('acadprog') == 2){


        }
        if($request->get('acadprog') == 3){

            $grades = DB::table('grading_system_gsgrades')
                            ->select('id',$igquarter,$qgquarter)
                            ->where('deleted',0)
                            ->get();

        }
        else if($request->get('acadprog') == 4){

            $grades = DB::table('grading_system_grades_hs')
                            ->select('id',$igquarter,$qgquarter)
                            ->where('deleted',0)
                            ->get();

        }
        else if($request->get('acadprog') == 5){

            $grades = DB::table('grading_system_grades_sh')
            ->select('id',$igquarter,$qgquarter)
            ->where('deleted',0)
            ->get();

        }

       
            
        $transmutation = DB::table('gradetransmutation')->get();

        $invalid_count = 0;
        $invalid_array = array();
        foreach($grades as $item){

            $found = false;

            if($item->$igquarter == 100 ){

                $item->transmute = 100;

            }else{
                foreach ($transmutation as $gt){
                    if($item->$igquarter >= $gt->gfrom && $item->$igquarter <= $gt->gto){
                        $item->transmute = $gt->gvalue;
                    }
                }
            }

            if($item->$qgquarter != $item->transmute && ($item->$qgquarter != 0.00)){

                array_push($invalid_array , $item);

            }

        }

        return $invalid_array;

        

    }


    public static function invalid_final_grades(Request $request){

        if(!$request->has('quarter') && $request->get('quarter') != null && $request->get('quarter') != ''){
            return "Please specify a quarter";
        }

        $quarter = $request->get('quarter');
        $tempqg = 'q'.$quarter;
        $detail_qg = 'qgq'.$quarter;
       

        $invalid_array = array();
        $temp_gradesum = DB::table('tempgradesum')->whereNotNull($tempqg)->select( $tempqg,'studid','subjid')->get();

        foreach($temp_gradesum as $item){

            $qg = DB::table('grading_system_grades_sh')
                    ->where('studid',$item->studid)
                    ->where('subjid',$item->subjid)
                    ->where('deleted',0)
                    ->select($detail_qg)
                    ->first();

            if(isset($qg->qgq1)){
                if(number_format($qg->$detail_qg) != $item->$tempqg){
                    $item->f_qg = $qg->$detail_qg;
                    array_push($invalid_array , $item);
                }
            }
           
        }

        return  $invalid_array;

    }


    public static function grading_sheet(Request $request){

        $gradelevel = $request->get('gradelevel');
        $section = $request->get('section');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $subjid = $request->get('subjid');
        $status = $request->get('status');

   
       
        return \App\Models\Grading\GradingReport::get_student_grading_sheet($gradelevel, $section, $syid, $semid, $subjid, $status);

    }

    public static function grading_sheet_gradelevel(Request $request){

        $gradelevel = $request->get('gradelevel');
        $syid = $request->get('sy');
        $semid = $request->get('semid');
        return \App\Models\Grading\GradingReport::grade_report_gradelevel($syid, $semid, $gradelevel);

    }

    public function checkActualGrades(Request $request){

        $subject = $request->get('subject');
        $section = $request->get('section');
        $quarter = $request->get('quarter');
        $track = $request->get('track');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        return \App\Models\Grading\SeniorHigh::checkActualGrades($section,$subject,$quarter,$track,$syid,$semid);

    }

    public static function teacher_grade_summary(Request $request){
        return view('teacher.grading.grade_summary.grading_summary');
    }

    public static function teacher_grade_summary_quarter(Request $request){
        return view('teacher.grading.grade_summary.grading_summary_quarter');
    }

    public static function get_section_all(Request $request){
        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        
        $additional = array();
        $subjects = \App\Models\Teacher\TeacherData::get_all_sections($teacherid,$syid,$semid);
        
        foreach($subjects as $item){
            
            if($item->acadprogid != 5){
                
                $check = DB::table('subjects')
                            ->where('id',$item->subjid)
                            ->select('subjCom','subjdesc','subjcode')
                            ->first();
                            
                if(isset($check->subjCom)){
                    
                    $check_additional = collect($additional)
                                    ->where('subjid',$check->subjCom)
                                    ->where('sectionid',$item->sectionid)
                                    ->count();
                
                    if($check_additional == 0){
                        
                        $get_subjCom_info = DB::table('subjects')
                                            ->where('id',$check->subjCom)
                                            ->select('subjCom','subjdesc','subjcode')
                                            ->first();
                        
                        $temp_item = (object)[
                                "sectionname"=> $item->sectionname,
                                "subjcode"=> $get_subjCom_info->subjcode,
                                "id"=> $item->id,
                                "subjid"=> $check->subjCom,
                                "levelid"=> $item->levelid,
                                "levelname"=> $item->levelname,
                                "sectionid"=> $item->sectionid,
                                "subjdesc"=> $get_subjCom_info->subjdesc,
                                "acadprogid"=> $item->acadprogid
                          ];
                        
                        array_push($additional,$temp_item);
                    }
                  
                   
                }
            }
        }
        
        $temp_subj = array();
        foreach($subjects as $item){
            array_push($temp_subj,$item);
        }
        foreach($additional as $item){
            array_push($temp_subj,$item);
        }
        
        return $temp_subj;
        
    }


    

    
    public static function registrar_grade_summary(Request $request){
        return view('registrar.grade_summary.grading_summary');
    }

    public static function registrar_student_awards(Request $request){
        return view('registrar.grade_summary.student_awards');
    }

    public static function get_advisory_sections(Request $request){
        $teacherid = $request->get('tid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        return GradeStatus::get_advisory_sections($teacherid, $syid, $semid);
    }

    public static function finalize_grades($grades){

    }
    
  

}


     