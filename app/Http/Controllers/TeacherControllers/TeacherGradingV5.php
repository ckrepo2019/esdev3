<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;

class TeacherGradingV5 extends \App\Http\Controllers\Controller
{

    public function teacher_grading_final(){
        return view('teacher.grading.finalgrading.finalgrading');
    }

    public function teacher_grading_final_getgrades(Request $request){

        $sectionid = $request->get('section');
        $gradelevel = $request->get('gradelevel');
        $subjid = $request->get('subject');
        $quarter = $request->get('quarter');
        $acadprogid = $request->get('acadprogid');


        $grading_system = \App\Models\FinalGrading\FinalGradingData::subject_grading_system($subjid,$acadprogid);
     

        if( $grading_system[0]->status == 1){
                $grading_system =  $grading_system[0]->data;
        }
        else{
                return $grading_system;
        }

        $checkStatus = \App\Models\Grading\GradeStatus::check_grade_status($sectionid, $subjid);
       
        if($checkStatus[0]->status == 1){
            $checkStatus = array($checkStatus[0]->data);
        }
        else{
            return $checkStatus;
        }

        
        $students = \App\Models\FinalGrading\FinalGradingData::teacher_grading_final_getgrades($sectionid,$gradelevel,$subjid,$quarter,$acadprogid);

        return view('teacher.grading.finalgrading.studenttable')
                    ->with('students',$students)
                    ->with('grading_system',$grading_system)
                    ->with('acadprogid',$acadprogid)
                    ->with('checkStatus',$checkStatus);
      
    }


   
    public function final_grading_store_grade(Request $request){

        $id = $request->get('id');
        $grade = $request->get('grade');
        $acadprogid = $request->get('acadprogid');
        $subjid = $request->get('subjid');
        $quarter = $request->get('quarter');
        return \App\Models\FinalGrading\FinalGradingProcess::final_grading_store_grade($id,$grade,$acadprogid,$subjid,$quarter);
        
      
    }
    

    public function teacher_grading_final_getgrades_type1(Request $request){

        $sectionid = $request->get('section');
        $gradelevel = $request->get('gradelevel');
        $subjid = $request->get('subject');
        $quarter = $request->get('quarter');
        $acadprogid = $request->get('acadprogid');
        $semid = $request->get('semid');
        $syid = $request->get('syid');

        $can_process = $sectionid != null && $gradelevel != null && $subjid != null && $quarter != null && $acadprogid != null && $semid != null && $syid != null ? true : false;

        if($can_process){

            //status
            $gradestatus =  \App\Models\FinalGrading\FinalGradingData::get_grades_status($syid,$semid,$sectionid,$subjid,$quarter);

            if(count($gradestatus) == 0){
                $gradesdetail =  \App\Models\FinalGrading\FinalGradingProcess::generate_grade_deatail_type1($syid,$semid,$gradelevel,$sectionid,$subjid,$quarter);
                $gradestatus =  \App\Models\FinalGrading\FinalGradingData::get_grades_status($syid,$semid,$sectionid,$subjid,$quarter);
            }else{
                //gradesdetail
                $gradesdetail =  \App\Models\FinalGrading\FinalGradingProcess::generate_grade_deatail_type1($syid,$semid,$gradelevel,$sectionid,$subjid,$quarter);
                $gradesdetail =  \App\Models\FinalGrading\FinalGradingData::get_grades_detail($syid,$semid,$sectionid,$subjid,$quarter,$acadprogid);
                
            }
            
            return view('teacher.grading.finalgrading.studenttable_type1')
                        ->with('gradestatus',$gradestatus)
                        ->with('acadprogid',$acadprogid)
                        ->with('gradesdetail',$gradesdetail);

        }
        else{
            return "Incomplete Fields.";
        }

       
                  
      
    }

    public function final_grading_store_grade_type1(Request $request){

        $gdid = $request->get('gdid');
        $studid = $request->get('studid');
        $fg = $request->get('fg');

       return  \App\Models\Grading\FinalGrading::save_final_grade($gdid,$studid,$fg);
        
    }

    
    public function final_grading_grade_submit_type1(Request $request){

        $id = $request->get('id');
        $levelid = $request->get('levelid');
        return  \App\Models\FinalGrading\FinalGradingProcess::final_grading_grade_submit_type1($id,$levelid);
        
    }

    

    

}


     