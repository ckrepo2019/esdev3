<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grading\GradeSchool;
use App\Models\Student\TeacherEvaluation;
use DB;

class TeacherEvaluations extends Controller
{
    
    public function admin_view_results(Request $request){

        $teachers = DB::table('teacheracadprog')
                        ->join('sy',function($join){
                            $join->on('teacheracadprog.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('teacher',function($join){
                            $join->on('teacher.id','teacheracadprog.teacherid');
                            $join->where('teacher.deleted','0');
                            $join->where('teacher.isactive','1');
                        })
                        ->where('teacheracadprog.deleted',0)
                        ->select('lastname','firstname','teacher.id','userid','usertypeid')
                        ->distinct()
                        ->get();

        foreach($teachers as $key=>$item){

            if($item->usertypeid != 1){

                $checkWithPriv = DB::table('faspriv')
                                    ->where('userid',$item->userid)
                                    ->where('usertype',1)
                                    ->count();

                if($checkWithPriv == 0){

                    unset($teachers[$key]);

                }

            }

        }

        // resources\views\superadmin\pages\gradingsystem\teacherEvaluation\teachers.blade.php

        return view('superadmin.pages.gradingsystem.teacherEvaluation.teachers')->with('teachers',$teachers);

    

    }

    public static function teachr_schedule(Request $request){

        
        $subjects = GradeSchool::teacher_assign_subjects($request->get('teacherid'));

        $teacherEvaluation = TeacherEvaluation::getTeacherEvaluationSetup();

        if($teacherEvaluation[0]->status == 0){

            return $teacherEvaluation;

        }
        else{

            $teacherEvaluation =  $teacherEvaluation[0]->data;

        }

        $evaluationDetail = TeacherEvaluation::evaluate_teacher_evaluation_setup($teacherEvaluation[0]->id);

        if($evaluationDetail[0]->status == 0){

            return    $evaluationDetail;

        }
        else{

            $evaluationDetail =  $evaluationDetail[0]->data;

        }

        $ratingValue = TeacherEvaluation::evaluate_teacher_evaluation_rating_value($teacherEvaluation[0]->id);

        if($ratingValue[0]->status == 0){

            return    $ratingValue;

        }
        else{

            $ratingValue =  $ratingValue[0]->data;

        }

        foreach( $subjects as $item){

            $studentCount = DB::table('studinfo')
                              ->whereIn('studstatus',[1,2,4])
                              ->where('sectionid',$item->id)
                              ->where('studinfo.deleted',0)
                              ->count();

            $item->studcount = $studentCount;

        }



        return view('superadmin.pages.gradingsystem.teacherEvaluation.assignment_table')
                ->with('evaluationDetail',$evaluationDetail)
                ->with('ratingvalue',$ratingValue)
                ->with('subjects',$subjects);


        
    }


    public static function activeQuarter(){

        $activeQuarter = DB::table('quarter_setup')->where('isactive',1)->where('deleted',0)->first();
        
        if(isset($activeQuarter->id)){
            return $activeQuarter->id;
        }else{
            return 0;
        }

    }


    public function check_evaluation(Request $request){

        $syid = $request->get('syid');
        $quarter = $request->get('yearfilter');
        
        $teacherEvaluation = TeacherEvaluation::getTeacherEvaluationSetup();

        

        if($teacherEvaluation[0]->status == 0){
            return $teacherEvaluation;
        }
        else{
            $teacherEvaluation =  $teacherEvaluation[0]->data;
        }

        $evaluationDetail = TeacherEvaluation::evaluate_teacher_evaluation_setup($teacherEvaluation[0]->id);


        if($evaluationDetail[0]->status == 0){
            return    $evaluationDetail;
        }
        else{
            $evaluationDetail =  $evaluationDetail[0]->data;
        }

        $ratingValue = TeacherEvaluation::evaluate_teacher_evaluation_rating_value($teacherEvaluation[0]->id);

        if($ratingValue[0]->status == 0){

            return    $ratingValue;

        }
        else{

            $ratingValue =  $ratingValue[0]->data;

        }

        $data = array((object)[
            'respondents'=>null,
            'responses'=>null
        ]);
        
        $field = 'q'.$quarter.'val';

        $detail = array();
        $newRv = array();

        $evaluationDetail = collect($evaluationDetail)->where('id',$request->get('data-id'));

        $header = DB::table('grading_system_student_header')
                    ->where('syid',$syid)
                    ->where('teacherid',$request->get('teacherid'))
                    ->where('deleted',0)
                    ->select('id')
                    ->get();

        foreach($evaluationDetail as $item){

            // $responses = DB::table('grading_system_student_header')
            //                 ->join('grading_system_student_evaluation',function($join){
            //                     $join->on('grading_system_student_header.id','=','grading_system_student_evaluation.studheader');
            //                     $join->where('grading_system_student_evaluation.deleted',0);
            //                 })
            //                 ->where('grading_system_student_header.syid',$syid)
            //                 ->where('teacherid',$request->get('teacherid'))
            //                 ->where('grading_system_student_header.deleted',0)
            //                 ->where('grading_system_student_evaluation.gsid',$item->id)
            //                 ->select($field)
            //                 ->get();

            $responses = DB::table('grading_system_student_evaluation')
                            ->whereIn('studheader',collect($header)->pluck('id'))
                            ->where('deleted',0)
                            ->where('gsid',$item->id)
                            ->select($field)
                            ->get();

                            
            foreach($ratingValue as $rtvalue){
                array_push($newRv,(object)[
                    'rtid'=>$rtvalue->id,
                    'ratingCount'=>collect($responses)->where($field, $rtvalue->value)->count()
                ]);
            }

            array_push($detail, (object)[
                'detail'=>$item,
                'responses'=>$newRv
            ]);

        }

        $respondents = DB::table('grading_system_student_evaluation')
                        ->whereIn('studheader',collect($header)->pluck('id'))
                        ->where('deleted',0)
                        ->where($field, '!=' ,null)
                        ->where('gsid',$item->id)
                        ->select('studid')
                        ->count();

        // return $respondents;

        // $respondents = DB::table('grading_system_student_header')
        //                 ->join('grading_system_student_evaluation',function($join){
        //                     $join->on('grading_system_student_header.id','=','grading_system_student_evaluation.studheader');
        //                     $join->where('grading_system_student_evaluation.deleted',0);
        //                 })
        //                 ->where('grading_system_student_header.syid',$syid)
        //                 ->where($field, '!=' ,null)
        //                 ->where('teacherid',$request->get('teacherid'))
        //                 ->where('grading_system_student_header.deleted',0)
        //                 ->where('grading_system_student_evaluation.gsid',$item->id)
        //                 ->select('studid')
        //                 ->count();

        $data[0]->respondents  = $respondents;
        $data[0]->responses  = $detail;
           
        return $data;


    }


}
