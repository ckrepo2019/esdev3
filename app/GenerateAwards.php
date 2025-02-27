<?php

namespace App;
use App\GenerateGrade;
use DB;
use Session;
use Illuminate\Support\Arr;

use Illuminate\Database\Eloquent\Model;

class GenerateAwards extends Model
{
    public static function AcadExcelAward(){

        // $pendingSections = self::checkIfGradesIsSubmitted();

     
        $students = DB::table('academicprogram')
                    ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
                    ->join('gradelevel',function($join){
                        $join->on('academicprogram.id','=','gradelevel.acadprogid');
                        $join->where('deleted','0');
                    })
                    ->join('studinfo','gradelevel.id','=','studinfo.levelid')
                    ->select('studinfo.id','studinfo.sectionname','gradelevel.levelname')
                    ->get();

        $gradesHolder = array();
        $quarter1awardees = array();
        $quarter2awardees = array();
        $quarter3awardees = array();
        $quarter4awardees = array();
        
        foreach($students as $student){

            $allquarterGrade =  GenerateGrade::generalAverage($student->id)[0];
 
            $checkAward = self::checkAcademicAward($allquarterGrade);

            if($checkAward[0]->quarter1 != "none"){
                array_push($quarter1awardees, (object) array(
                    'studentname'=>$allquarterGrade->studentname,
                    'section'=>$student->levelname.' - '.$student->sectionname,
                    'averagegrade'=>$allquarterGrade->quarter1,
                    'award' => $checkAward[0]->quarter1,
                ));
            }

            if($checkAward[1]->quarter2 != "none"){
                array_push($quarter2awardees, (object) array(
                    'studentname'=>$allquarterGrade->studentname,
                    'section'=>$student->levelname.' - '.$student->sectionname,
                    'averagegrade'=>$allquarterGrade->quarter2,
                    'award' => $checkAward[1]->quarter2,
                ));
            }

            if($checkAward[2]->quarter3 != "none"){
                array_push($quarter3awardees, (object) array(
                    'studentname'=>$allquarterGrade->studentname,
                    'section'=>$student->levelname.' - '.$student->sectionname,
                    'averagegrade'=>$allquarterGrade->quarter3,
                    'award' => $checkAward[2]->quarter3,
                ));
            }

            if($checkAward[3]->quarter4 != "none"){
                array_push($quarter4awardees, (object) array(
                    'studentname'=>$allquarterGrade->studentname,
                    'section'=>$student->levelname.' - '.$student->sectionname,
                    'averagegrade'=>$allquarterGrade->quarter4,
                    'award' => $checkAward[3]->quarter4,
                ));
            }
         
        }

        array_push($gradesHolder, (object)array(
            "quarter1" => $quarter1awardees,
            "quarter2" => $quarter2awardees,
            "quarter3" => $quarter3awardees,
            "quarter4" => $quarter4awardees,

        ));



        return $gradesHolder;
    }

    public static function checkAcademicAward($grade){

        $acadAward = array();

        if($grade->quarter1 >= 98 && $grade->quarter1 <= 100){
            array_push($acadAward, (object) ["quarter1"=>"With Highest Honors"]);
        }
        else if($grade->quarter1 >= 95 && $grade->quarter1 <= 97){
            array_push($acadAward, (object) ["quarter1"=>"With High Honors"]);
            
        }
        else if($grade->quarter1 >= 90 && $grade->quarter1 <= 94){
            array_push($acadAward, (object) ["quarter1"=>"With Honors"]);
        }
        else{
            array_push($acadAward,  (object) ['quarter1'=>'none']);
        }

        if($grade->quarter2 >= 98 && $grade->quarter2 <= 100){
            array_push($acadAward, (object) ["quarter2"=>"With Highest Honors"]);
        }
        else if($grade->quarter2 >= 95 && $grade->quarter2 <= 97){
            array_push($acadAward, (object) ["quarter2"=>"With High Honors"]);
            
        }
        else if($grade->quarter2 >= 90 && $grade->quarter2 <= 94){
            array_push($acadAward, (object) ["quarter2"=>"With Honors"]);
        }
        else{
            array_push($acadAward,(object)  ['quarter2'=>'none']);
        }

        if($grade->quarter3 >= 98 && $grade->quarter3 <= 100){
            array_push($acadAward, (object) ["quarter3"=>"With Highest Honors"]);
        }
        else if($grade->quarter3 >= 95 && $grade->quarter3 <= 97){
            array_push($acadAward, (object) ["quarter3"=>"With High Honors"]);
            
        }
        else if($grade->quarter3 >= 90 && $grade->quarter3 <= 94){
            array_push($acadAward, (object) ["quarter3"=>"With Honors"]);
        }
        else{
            array_push($acadAward,(object)  ['quarter3'=>'none']);
        }

        if($grade->quarter4 >= 98 && $grade->quarter4 <= 100){
            array_push($acadAward, (object) ["quarter4"=>"With Highest Honors"]);
        }
        else if($grade->quarter4 >= 95 && $grade->quarter4 <= 97){
            array_push($acadAward, (object) ["quarter4"=>"With High Honors"]);
            
        }
        else if($grade->quarter4 >= 90 && $grade->quarter4 <= 94){
            array_push($acadAward, (object) ["quarter4"=>"With Honors"]);
        }
        else{
            array_push($acadAward, (object) ['quarter4'=>'none']);
        }

        return $acadAward;
    }

    public static function checkIfGradesIsSubmitted($quarter){

        $subjects = DB::table('academicprogram')
                    ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
                    ->join('gradelevel',function($join){
                        $join->on('academicprogram.id','=','gradelevel.acadprogid');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->join('assignsubj',function($join){
                        $join->on('gradelevel.id','=','assignsubj.glevelid');
                        $join->where('assignsubj.deleted','0');
                    })
                    ->join('sy',function($join){
                        $join->on('assignsubj.syid','=','sy.id');
                        $join->where('sy.isactive','1');
                    })
                    ->join('assignsubjdetail',function($join){
                        $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.deleted','0');
                    })
                    ->join('teacher',function($join){
                        $join->on('assignsubjdetail.teacherid','=','teacher.id');
                        $join->where('teacher.deleted','0');
                        $join->where('teacher.isactive','1');
                    })
                    ->join('sections',function($join){
                        $join->on('sections.id','=','assignsubj.sectionid');
                        $join->where('sections.deleted','0');
                        })
                    ->join('subjects',function($join){
                        $join->on('assignsubjdetail.subjid','=','subjects.id');
                        $join->where('subjects.deleted','0');
                        $join->where('subjects.isactive','1');
                    })
                    // ->select('assignsubjdetail.teacherid',
                    //             'assignsubjdetail.subjid',
                    //             'assignsubj.sectionid',
                    //             'gradelevel.levelname',
                    //             'sections.sectionname')
                    
                    ->get();

        $status = array();
        
        foreach($subjects as $item){
            $result = DB::table('grades')
                     ->where('sectionid',$item->sectionid)
                     ->where('subjid',$item->subjid)
                     ->where('quarter',$quarter)
                     ->where('status','2')
                     ->get();

            if(count($result)==0){
                array_push($status, $item);
            }
        }

        return  collect($status)->unique();
    }

    
}
