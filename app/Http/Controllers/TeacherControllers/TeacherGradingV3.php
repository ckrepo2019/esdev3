<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use App\Models\Grading\Preschool;
use App\Models\Grading\GradeSchool;
use App\Models\Grading\HighSchool;
use App\Models\Grading\CoreValue;
use App\Models\Grading\SeniorHigh;
use App\Models\Grading\GradeStatus;
use App\Models\Subjects\Subjects;
use App\Models\Grading\PreSchoolPer;
use App\Models\Grading\HomeRoom; //homeroom
use Session;


use DB;

class TeacherGradingV3 extends \App\Http\Controllers\Controller
{

    public function gradeschoolcoreval(Request $request){

        return self::coreValue($request, 3);

    }
    public function highschoolcoreval(Request $request){
        
        return self::coreValue($request, 4);

    }
    public function seniorhighcoreval(Request $request){

        return self::coreValue($request, 5);

    }


    //homeroom
    public function gradeschoolhomeroom(Request $request){

        return self::homeRoom($request, 3);

    }
    public function highschoolhomeroom(Request $request){
        
        return self::homeRoom($request, 4);

    }
    public function seniorhighhomeroom(Request $request){

        return self::homeRoom($request, 5);

    }
    //homeroom
    
    public function preschoolGrading(Request $request){

        if($request->get('grade') == 'grade' && $request->has('grade')){

            $gsid = $request->get('gsid');

            $teacherid = self::evaluationTeacherId($request->get('teacherid'));

            return Preschool::grade_student_preschool($gsid,$teacherid);

        }
        else if($request->get('evaluate') == 'evaluate' && $request->has('evaluate')){

            $student = $request->get('studid');
            $gsid = $request->get('gsid');
            $sectionid = $request->get('sectionid');

            return Preschool::evaluate_student($gsid, $student,$sectionid);
        
        }
        else if($request->get('generate') == 'generate' && $request->has('generate')){

            $student = $request->get('studid');
            $gsid = $request->get('gsid');

            return Preschool::generate_student_grade_preschool($gsid, $student);
        
        }
        else if($request->get('submit') == 'submit' && $request->has('submit')){

            $student = $request->get('studid');
            $gradeid = $request->get('gradeid');
            $field = $request->get('gardequarter');
            $value = $request->get('value');

            return Preschool::submit_student_grade_preschool($student, $gradeid, $field, $value);
        
        }
        else if($request->get('getgrades') == 'getgrades' && $request->has('getgrades')){

            $studid = $request->get('studid');
            $sectionid = $request->get('sectionid');
            $syid = $request->get('syid');
            $subjid = $request->get('subjid');
            $quarter = $request->get('quarter');

            return Preschool::get_grades($studid, $subjid, $sectionid, $syid, $quarter);
        
        }
          
    }


    public function gradeschoolGrading(Request $request){

        if($request->get('grade') == 'grade' && $request->has('grade')){

            $gsid = $request->get('gsid');

            $teacherid = self::evaluationTeacherId($request->get('teacherid'));

            return GradeSchool::grade_student_gradeschool($gsid,$teacherid);

        }
        else if($request->get('evaluate') == 'evaluate' && $request->has('evaluate')){

            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');

            return GradeSchool::evaluate_student_grade_gradeschool($gsid, $student, $sectionid, $subjectid, $quarter);
        
        }
        
        else if($request->get('generate') == 'generate' && $request->has('generate')){

            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');

            return GradeSchool::generate_student_grade_gradeschool($gsid, $student, $sectionid, $subjectid, $quarter);
        
        }
        else if($request->get('submit') == 'submit' && $request->has('submit')){

            $student = $request->get('studid');
            $gradeid = $request->get('gradeid');
            $field = $request->get('field');
            $value = $request->get('value');

            return GradeSchool::submit_student_grade_gradeschool($student, $gradeid, $field, $value);
        
        }
        else if($request->get('reload') == 'reload' && $request->has('reload')){

            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');

            return GradeSchool::reload_student_grade($student, $sectionid, $subjectid, $quarter);
        
        }
          
    }

    public function preschoolper(Request $request){

      if($request->get('evaluate') == 'evaluate' && $request->has('evaluate')){

            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');

            return PreSchoolPer::evaluate_student_grade_gradeschool($gsid, $student, $sectionid, $subjectid, $quarter);
        
        }
        
        else if($request->get('generate') == 'generate' && $request->has('generate')){

            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');

            return PreSchoolPer::generate_student_grade_gradeschool($gsid, $student, $sectionid, $subjectid, $quarter);
        
        }
        else if($request->get('submit') == 'submit' && $request->has('submit')){

            $student = $request->get('studid');
            $gradeid = $request->get('gradeid');
            $field = $request->get('field');
            $value = $request->get('value');

            return PreSchoolPer::submit_student_grade_gradeschool($student, $gradeid, $field, $value);
        
        }
        else if($request->get('reload') == 'reload' && $request->has('reload')){

            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');

            return PreSchoolPer::reload_student_grade($student, $sectionid, $subjectid, $quarter);
        
        }
          
    }

    public function highSchoolGrading(Request $request){

        if($request->get('grade') == 'grade' && $request->has('grade')){
            $gsid = $request->get('gsid');
            $syid = $request->get('syid');
            $teacherid = self::evaluationTeacherId($request->get('teacherid'));
            return HighSchool::grade_info($gsid,$teacherid,$syid);
        }
        else if($request->get('evaluate') == 'evaluate' && $request->has('evaluate')){

            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            return HighSchool::evaluate_student_grade($gsid, $student, $sectionid, $subjectid, $quarter,$syid);
        
        }
        else if($request->get('generate') == 'generate' && $request->has('generate')){

            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            $gradelevel = $request->get('gradelevel');
            

            return HighSchool::generate_student_grade($gsid, $student, $sectionid, $subjectid, $quarter, $gradelevel);
        
        }
        else if($request->get('submit') == 'submit' && $request->has('submit')){

            $student = $request->get('studid');
            $gradeid = $request->get('gradeid');
            $field = $request->get('field');
            $value = $request->get('value');

            return HighSchool::submit_student_grade($student, $gradeid, $field, $value);
        
        }
        else if($request->get('reload') == 'reload' && $request->has('reload')){

            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');

            return HighSchool::reload_student_grade($student, $sectionid, $subjectid, $quarter);
        
        }

        else if($request->get('update') == 'update' && $request->has('update')){


            return $request->all();
            // $student = $request->get('studid');
            // $sectionid = $request->get('section');
            // $subjectid = $request->get('subject');
            // $quarter = $request->get('quarter');

            // return HighSchool::reload_student_grade($student, $sectionid, $subjectid, $quarter);
        
        }
          
    }

    public function seniorHighGrading(Request $request){

        if($request->get('grade') == 'grade' && $request->has('grade')){

            $gsid = $request->get('gsid');

            $teacherid = self::evaluationTeacherId($request->get('teacherid'));
            $syid = self::evaluationTeacherId($request->get('syid'));
            $semid = self::evaluationTeacherId($request->get('semid'));

            return SeniorHigh::grade_info($gsid,$teacherid,$syid,$semid);

        }
        else if($request->get('evaluate') == 'evaluate' && $request->has('evaluate')){

            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            
            return SeniorHigh::evaluate_student_grade($gsid, $student, $sectionid, $subjectid, $quarter, $syid, $semid);
        
        }
        else if($request->get('generate') == 'generate' && $request->has('generate')){

            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            
            return SeniorHigh::generate_student_grade($gsid, $student, $sectionid, $subjectid, $quarter, null, $syid, $semid);
        
        }
        else if($request->get('submit') == 'submit' && $request->has('submit')){

            $student = $request->get('studid');
            $gradeid = $request->get('gradeid');
            $field = $request->get('field');
            $value = $request->get('value');

            return SeniorHigh::submit_student_grade($student, $gradeid, $field, $value);
        
        }
        else if($request->get('reload') == 'reload' && $request->has('reload')){

            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');

            return SeniorHigh::reload_student_grade($student, $sectionid, $subjectid, $quarter);
        
        }
          
    }

    

    public static function evaluationTeacherId($teacherid){

        if($teacherid != null && $teacherid != null){

            $teacherid = $teacherid;

        }else{

            $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;

        }

        return $teacherid;

    }


    public static function coreValue($request, $acadprog){
        if($request->get('grade') == 'grade' && $request->has('grade')){
            $gsid = $request->get('gsid');
            if($request->has('teacherid')){
                $teacherid = $request->get('teacherid');
            }
            else{
                $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
            }

            $teacherid = self::evaluationTeacherId($teacherid);
            return CoreValue::grade_student_corevalue(null,$teacherid, $acadprog);
        }
        else if($request->get('evaluate') == 'evaluate' && $request->has('evaluate')){
            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            return CoreValue::evaluate_corevalue_setup($gsid, $student, $sectionid, $subjectid, $quarter, $acadprog);
        }
        else if($request->get('generate') == 'generate' && $request->has('generate')){
            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            return CoreValue::genereate_student_core_value($gsid, $student, $sectionid, $subjectid, $quarter, $acadprog);
        }
        else if($request->get('submit') == 'submit' && $request->has('submit')){
            $student = $request->get('studid');
            $gradeid = $request->get('gradeid');
            $field = $request->get('gardequarter');
            $value = $request->get('value');
            return CoreValue::submit_student_core_value($student, $gradeid, $field, $value);
        }
    }

    //homeroom
    public static function homeRoom($request, $acadprog){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        if($request->get('grade') == 'grade' && $request->has('grade')){
            $gsid = $request->get('gsid');
            $teacherid = self::evaluationTeacherId($request->get('teacherid'));
            return HomeRoom::grade_student_homeroom(null,$teacherid, $acadprog, $syid, $semid);
        }
        else if($request->get('evaluate') == 'evaluate' && $request->has('evaluate')){
            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            return HomeRoom::evaluate_homeroom_setup($gsid, $student, $sectionid, $subjectid, $quarter, $acadprog, $syid, $semid);
        }
        else if($request->get('generate') == 'generate' && $request->has('generate')){
            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            return HomeRoom::genereate_student_homeroom($gsid, $student, $sectionid, $subjectid, $quarter, $acadprog, $syid, $semid);
        }
        else if($request->get('submit') == 'submit' && $request->has('submit')){
            $student = $request->get('studid');
            $gradeid = $request->get('gradeid');
            $field = $request->get('gardequarter');
            $value = $request->get('value');
            return HomeRoom::submit_student_homeroom($student, $gradeid, $field, $value);
        }
        else if($request->get('printable') == 'printable' && $request->has('printable')){
            $gsid = $request->get('gsid');
            $student = $request->get('studid');
            $sectionid = $request->get('section');
            $subjectid = $request->get('subject');
            $quarter = $request->get('quarter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            return HomeRoom::printable_student_homeroom($gsid, $student, $sectionid, $subjectid, $quarter, $acadprog, $syid, $semid);
        }
    }
    //homeroom

    public function studentreportcard(){

        return view('teacher.grading.reportcard');

    }

    public function gradeStatus(Request $request){

        $teacherid = self::evaluationTeacherId($request->get('teacherid'));
        $syid = $request->get('syid');
        $sem = $request->get('semid');

     

        return GradeStatus::get_grade_status($teacherid,$syid,$sem);

    }

    public function get_grade_status(Request $request){

        $sectionid = $request->get('sectionid');
        $subjid = $request->get('subjid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        return  GradeStatus::filtered_grades_status($sectionid,$subjid,$syid,$semid);
        

    }

    //grading v5
    public function gradeStatusPreSchool(Request $request){

        $teacherid = self::evaluationTeacherId($request->get('teacherid'));
        $syid = $request->get('syid');
        $sem = $request->get('semid');

        return GradeStatus::get_grade_status_preschool($teacherid,$syid,$sem);

    }
    //grading v5

    public function generateStatus(Request $request){

       $sectionid = $request->get('dse');
       $subjid = $request->get('dsu');
       $lvl = $request->get('dlvl');
       $syid = $request->get('syid');
       $semid = $request->get('semid');

       return GradeStatus::generate_grade_status($sectionid, $subjid, $lvl, $syid, $semid);


    }
   
    public function submitGrade(Request $request){

        $quarter = $request->get('dq');
        $did = $request->get('did');
      
        return GradeStatus::submit_grades($quarter, $did);
 
 
     }
        

     public function principalGrading(){

        $sectionsubj = array();

        if(Session::get('isGradeSchoolPrinicpal')){
            $gssectionsubj = GradesChool::get_sections_subject_all();
            foreach($gssectionsubj as $item){
                array_push($sectionsubj, $item);
            }
        }


        if(Session::get('isJuniorHighPrinicpal')){
            $hssectionsubj = HighSchool::get_sections_subject_all();
            foreach($hssectionsubj as $item){
                array_push($sectionsubj, $item);
            }
        }

        if(Session::get('isSeniorHighPrincipal')){
            $shsectionsubj = SeniorHigh::get_sections_subject_all(2,2);
            foreach($shsectionsubj as $item){
                array_push($sectionsubj, $item);
            }
        }
        
        $teachers = collect($sectionsubj)->unique('teacherid');
        $sections = collect($sectionsubj)->unique('id');
        $subjects = collect($sectionsubj)->unique('subjcode');

        // return $sectionsubj;

        
        return view('principalsportal.pages.grading.report_card')
                ->with('teachers', $teachers)
                ->with('sections', $sections)
                ->with('subjects', $subjects)
                ->with('sectionsubj', $sectionsubj);

     }

     public function approveGrade(Request $request){
      
        $quarter = $request->get('quarter');
        $gsid = $request->get('gstatusid');
      
        return GradeStatus::approve_grade($quarter, $gsid);

     }

     public function postGrade(Request $request){
      
        $quarter = $request->get('quarter');
        $gsid = $request->get('gstatusid');
      
        return GradeStatus::post_grade($quarter, $gsid);

     }

     public function pendingGrade(Request $request){
      
        $quarter = $request->get('quarter');
        $gsid = $request->get('gstatusid');
      
        return GradeStatus::pending_grade($quarter, $gsid);

     }


     public static function viewsf9gs(Request $request){

        $gsid = $request->get('gsid');
        $student = $request->get('studid');
        $sectionid = $request->get('section');
        $subjectid = $request->get('subject');
        $quarter = $request->get('quarter');
        $syid = $request->get('syid');
        
        return GradeSchool::view_sf9($gsid, $student, $sectionid, $subjectid, $quarter, $syid);

     }

     public static function viewsf9hs(Request $request){

        $gsid = $request->get('gsid');
        $student = $request->get('studid');
        $sectionid = $request->get('section');
        $subjectid = $request->get('subject');
        $quarter = $request->get('quarter');

        return HighSchool::view_sf9($gsid, $student, $sectionid, $subjectid, $quarter);

     }

     public static function viewsf9sh(Request $request){

        $gsid = $request->get('gsid');
        $student = $request->get('studid');
        $sectionid = $request->get('section');
        $subjectid = $request->get('subject');
        $quarter = $request->get('quarter');
        $track = $request->get('track');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        return SeniorHigh::view_sf9($gsid, $student, $sectionid, $subjectid, $quarter,$track,$syid,$semid);



     }

     public static function viewsf9psper(Request $request){

        $gsid = $request->get('gsid');
        $student = $request->get('studid');
        $sectionid = $request->get('section');
        $subjectid = $request->get('subject');
        $quarter = $request->get('quarter');

        return PreSchoolPer::view_sf9($gsid, $student, $sectionid, $subjectid, $quarter);

     }

     public function student_ranking(){

        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first();

        $allsections = DB::table('sectiondetail')
                        ->where('sectiondetail.teacherid',$teacherid->id)
                        ->where('sectiondetail.deleted',0)
                        ->join('sections',function($join){
                            $join->on('sectiondetail.sectionid','=','sections.id');
                            $join->where('sections.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                            $join->on('sections.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        })
                        ->select('sections.id','sectionname','sectionname as text','levelid','gradelevel.levelname','sectiondetail.syid')
                        ->get();

        $temp_gradelevel = array();

        foreach($allsections as $item){
            $check = collect($temp_gradelevel)->where('levelid',$item->levelid)->where('syid',$item->syid)->count();
            if($check == 0){
                array_push($temp_gradelevel,(object)[
                            'text'=> $item->levelname,
                            'id'=>  $item->levelid,
                            'levelname' => $item->levelname,
                            'levelid' => $item->levelid,
                            'syid'=>$item->syid
                    ]);
            }
        }
        
        $gradelevel = $temp_gradelevel;

        return view('teacher.grading.student_raking')
                    ->with('allsections',$allsections)
                    ->with('gradelevel',$gradelevel);

     }
      

    public function updatehsgrades(Request $request){

        $quarter = $request->get('quarter');

        DB::table('grading_system_grades_hs')
                ->where('id',$request->get('storeid'))
                ->where('studid',$request->get('storestudid'))
                ->update([
                    $request->get('storefield')=>$request->get('storegrades'),
                    'wsq'.$quarter => $request->get('storews'),
                    'psq'.$quarter => $request->get('storeps'),
                    'q'.$quarter.'total' => $request->get('storetotal'),
                    'updatedby'=> auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

        foreach($request->get('storeids') as $item){

            if($item != null || $item != ''){

                DB::table('grading_system_grades_hs')
                    ->where('id',$item)
                    ->where('studid',$request->get('storestudid'))
                    ->update([
                        'igq'.$quarter => $request->get('storeig'),
                        'qgq'.$quarter => $request->get('storeqg'),
                        'updatedby'=> auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

        }

    }


    public function updateshgrades(Request $request){

        $quarter = $request->get('quarter');
        
        if($quarter == 3){
            $quarter = 1;
        }

        if($quarter == 4){
            $quarter = 2;
        }
        
        DB::table('grading_system_grades_sh')
                ->where('id',$request->get('storeid'))
                ->where('studid',$request->get('storestudid'))
                ->update([
                    $request->get('storefield')=>$request->get('storegrades'),
                    'wsq'.$quarter => $request->get('storews'),
                    'psq'.$quarter => $request->get('storeps'),
                    'q'.$quarter.'total' => $request->get('storetotal'),
                    'updatedby'=> auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
                
        foreach($request->get('storeids') as $item){

            if($item != null || $item != ''){

                DB::table('grading_system_grades_sh')
                    ->where('id',$item)
                    ->where('studid',$request->get('storestudid'))
                    ->update([
                        'igq'.$quarter => $request->get('storeig'),
                        'qgq'.$quarter => $request->get('storeqg'),
                        'updatedby'=> auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

        }

    }

    public function updategsgrades(Request $request){

        $quarter = $request->get('quarter');

        DB::table('grading_system_gsgrades')
                ->where('id',$request->get('storeid'))
                ->where('studid',$request->get('storestudid'))
                ->update([
                    $request->get('storefield')=>$request->get('storegrades'),
                    'wsq'.$quarter => $request->get('storews'),
                    'psq'.$quarter => $request->get('storeps'),
                    'q'.$quarter.'total' => $request->get('storetotal'),
                    'updatedby'=> auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
                
        foreach($request->get('storeids') as $item){

            if($item != null || $item != ''){

                DB::table('grading_system_gsgrades')
                    ->where('id',$item)
                    ->where('studid',$request->get('storestudid'))
                    ->update([
                        'igq'.$quarter => $request->get('storeig'),
                        'qgq'.$quarter => $request->get('storeqg'),
                        'updatedby'=> auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

        }

    }

    public function updatepsgrades(Request $request){

        $quarter = $request->get('quarter');

        DB::table('grading_system_grades_psper')
                ->where('id',$request->get('storeid'))
                ->where('studid',$request->get('storestudid'))
                ->update([
                    $request->get('storefield')=>$request->get('storegrades'),
                    'wsq'.$quarter => $request->get('storews'),
                    'psq'.$quarter => $request->get('storeps'),
                    'q'.$quarter.'total' => $request->get('storetotal'),
                    'updatedby'=> auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
                
        foreach($request->get('storeids') as $item){

            if($item != null || $item != ''){

                DB::table('grading_system_grades_psper')
                    ->where('id',$item)
                    ->where('studid',$request->get('storestudid'))
                    ->update([
                        'igq'.$quarter => $request->get('storeig'),
                        'qgq'.$quarter => $request->get('storeqg'),
                        'updatedby'=> auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

        }

    }

    












}


     