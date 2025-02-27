<?php

namespace App;
use DB;
use App\ClassSched;
use App\SPP_SHClassSchedule;
use App\SPP_Student;
use Session;

use Illuminate\Database\Eloquent\Model;

class GenerateGrade extends Model
{


    //calculate quarter grade
    public static function studentGradeReport($gradeinfo,$gradeSetup){
        
            $setup = $gradeSetup;

            $hps_ww_total_score = $gradeinfo->wwhr1 + $gradeinfo->wwhr2 + $gradeinfo->wwhr3 + $gradeinfo->wwhr4 + $gradeinfo->wwhr5 + $gradeinfo->wwhr6 + $gradeinfo->wwhr7 + $gradeinfo->wwhr8 + $gradeinfo->wwhr9+ $gradeinfo->wwhr0;

            $hps_pt_total_score = $gradeinfo->pthr1 + $gradeinfo->pthr2 + $gradeinfo->pthr3 + $gradeinfo->pthr4 + $gradeinfo->pthr5 + $gradeinfo->pthr6 + $gradeinfo->pthr7 + $gradeinfo->pthr8 + $gradeinfo->pthr9+ $gradeinfo->pthr0;

            $hps_qa_total_score = $gradeinfo->qahr1;

            $gradeinfo->qatotal = $gradeinfo->qa1;

            $gradeinfo->wwtotal = $gradeinfo->ww1+$gradeinfo->ww2+$gradeinfo->ww3+$gradeinfo->ww4+$gradeinfo->ww5+$gradeinfo->ww6+$gradeinfo->ww7+$gradeinfo->ww8+$gradeinfo->ww9+$gradeinfo->ww0;
            
            $gradeinfo->pttotal = $gradeinfo->pt1+$gradeinfo->pt2+$gradeinfo->pt3+$gradeinfo->pt4+$gradeinfo->pt5+$gradeinfo->pt6+$gradeinfo->pt7+$gradeinfo->pt8+$gradeinfo->pt9+$gradeinfo->pt0;

            if($hps_ww_total_score == 0){
               $gradeinfo->wwws = 0;
               $gradeinfo->wwps = 0;
            }
            else{
               $gradeinfo->wwws = number_format((($gradeinfo->wwtotal / $hps_ww_total_score ) * 100) * ($setup->writtenworks/100),2);
               $gradeinfo->wwps = number_format(($gradeinfo->wwtotal / $hps_ww_total_score ) * 100,2);
            }

            if($hps_pt_total_score == 0){
                $gradeinfo->ptws = 0;
                $gradeinfo->ptps = 0;
            }
            else{
               $gradeinfo->ptws = number_format((($gradeinfo->pttotal / $hps_pt_total_score ) * 100) * ($setup->performancetask/100),2);
               $gradeinfo->ptps = number_format(($gradeinfo->pttotal / $hps_pt_total_score ) * 100,2);
            }

            if($hps_qa_total_score == 0){
               $gradeinfo->qaps = 0 ;
               $gradeinfo->qaws = 0;
            }
            else{
               $gradeinfo->qaws = number_format((($gradeinfo->qatotal / $hps_qa_total_score ) * 100) * ($setup->qassesment/100),2);
               $gradeinfo->qaps = number_format(($gradeinfo->qatotal / $hps_qa_total_score) * 100,2);
            }
            
            try {

                $gradeinfo->ig =  number_format($gradeinfo->wwws + $gradeinfo->ptws + $gradeinfo->qaws,2);

            } catch (\ErrorException $e) {

                return false;

            }
           

            $gradeinfo->ig = number_format((float)$gradeinfo->ig, 2, '.', '');

            $gts = DB::table('gradetransmutation')->get();

            $qG = 0;
            $gtsfound = 0;

            foreach ($gts as $gt){
                if($gt->gfrom >= $gradeinfo->ig && $gtsfound == 0){
                    foreach ($gts as $gtx){
                        if($gtx->gto >= $gradeinfo->ig && $gtsfound == 0){
                            $gtsfound = 1;
                            $gradeinfo->qg = $gtx->gvalue;
                        }
                    }
                }
            }

        return $gradeinfo;

    }


    public static function generateFinalGradesByGradeLevel($studentid,$gradelevel){

        $allGrades = array();

        $finalinformations = array();
        $acadprog = DB::table('gradelevel')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id',$gradelevel)
            ->get();

        if($acadprog[0]->progname == "SENIOR HIGH SCHOOL"){
            $studentInfo = DB::table('sh_enrolledstud')
                        ->join('sy',function($join){
                            $join->on('sy.id','=','sh_enrolledstud.syid');
                            $join->where('sy.isactive','1');
                        })
                        ->join('gradelevel',function($join){
                            $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->where('sh_enrolledstud.deleted','0')
                        ->where('sh_enrolledstud.studid',$studentid)
                        ->where('sh_enrolledstud.levelid',$gradelevel)
                        ->first();
        }else{
            $studentInfo = DB::table('enrolledstud')
                        ->join('sy',function($join){
                            $join->on('sy.id','=','enrolledstud.syid');
                            $join->where('sy.isactive','1');
                        })
                        ->join('gradelevel',function($join){
                            $join->on('enrolledstud.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->where('enrolledstud.deleted','0')
                        ->where('enrolledstud.studid',$studentid)
                        ->where('enrolledstud.levelid',$gradelevel)
                        ->first();
        }
    
        $assignsubjects = ClassSched::gradelevelassignedsub($studentid,$gradelevel);

        $id = $studentInfo->studid;

        

        foreach($assignsubjects  as $assignsubject){

            $quarterGrades = (object) array('subject'=>'',
                                        'quarter1'=>null,
                                        'quarter2'=>null,
                                        'quarter3'=>null,
                                        'quarter4'=>null,
                                        'final'=>null);

            $quarterGrades->subject = $assignsubject->subjdesc;

            $setup = DB::table('gradessetup')
                    ->where('subjid',$assignsubject->subjid)
                    ->where('levelid',$studentInfo->levelid)
                    ->where('deleted','0')
                    ->first();

            $studentgrades = DB::table('grades')
                            ->leftJoin('gradesdetail',function($join)  use ($id){
                                $join->on('grades.id','=','gradesdetail.headerid');
                                $join->where('gradesdetail.studid',$id);
                            })
                            ->where('grades.sectionid',$studentInfo->sectionid)
                            ->where('deleted','0')
                            ->where('subjid',$assignsubject->subjid)
                            ->where('submitted','1')
                            ->where('status','4')
                            ->orderBy('grades.quarter')
                            ->get();

            foreach($studentgrades as $studentgrade){
                
                $gradeinfo = self::studentGradeReport($studentgrade,$setup);
                $quarterGrades->{'quarter'.$studentgrade->quarter} = $gradeinfo->qg;

            }

            if($quarterGrades->quarter1!=null &&
                $quarterGrades->quarter2!=null &&
                $quarterGrades->quarter3!=null &&
                $quarterGrades->quarter4!=null){
                    $quarterGrades->final =  ($quarterGrades->quarter1 + $quarterGrades->quarter2 + $quarterGrades->quarter3 + $quarterGrades->quarter4)/4; 
                }
            
            if($quarterGrades->final!=null){
                if($quarterGrades->final>=75){
                    $quarterGrades->remarks = "PASSED";
                }
                else{
                    $quarterGrades->remarks = "FAILED";
                }
            }
           
        
           array_push($allGrades,$quarterGrades);

        }

        array_push($finalinformations, (object) array(
            'schoolyear'=> $studentInfo->sydesc,
            'gradelevel'=>$studentInfo->levelname,
            'grades'=>$allGrades
        ));
        
        return  $finalinformations;
    }

    public static function finalGrades($studendinfo){

        $assignsubjects = SPP_Subject::getSubject(null,null,null,$studendinfo->ensectid);

       

        $allGrades = array();
        $finalinformations = array();
        $id = $studendinfo->id;

        foreach($assignsubjects[0]->data  as $assignsubject){

            $quarterGrades = (object) array('assignsubject'=>$assignsubject->subjdesc,
                                            'subjectcode'=>$assignsubject->subjcode,
                                            'quarter1'=>null,
                                            'quarter2'=>null,
                                            'quarter3'=>null,
                                            'quarter4'=>null,
                                            'finalRating'=>null,
                                            'remarks'=>'');

            $setup = DB::table('gradessetup')
                            ->where('subjid',$assignsubject->id)
                            ->where('levelid',$studendinfo->enlevelid)
                            ->where('deleted','0')
                            ->first();

            
            
            $studentgrades = DB::table('grades')
                                ->leftJoin('gradesdetail',function($join)  use ($id){
                                    $join->on('grades.id','=','gradesdetail.headerid');
                                    $join->where('gradesdetail.studid',$id);
                                });

        

            if(Session::has('schoolYear')){

                $studentgrades->join('sy',function($join) {
                                    $join->on('sy.id','=','grades.syid');
                                    $join->where('sy.id',Session::get('schoolYear')->id);
                                });

                
            }
            else{

                

                $studentgrades->join('sy',function($join){
                        $join->on('sy.id','=','grades.syid');
                        $join->where('sy.isactive','1');
                    });

            }
            

           
                                
            $studentgrades->where('grades.sectionid',$studendinfo->ensectid)
                                ->where('deleted','0')
                                ->where('subjid',$assignsubject->id)
                                ->where('submitted','1')
                                ->where('status','4')
                                ->orderBy('grades.quarter')
                                ->get();

            foreach($studentgrades->get() as $studentgrade){
                
                $gradeinfo = self::studentGradeReport($studentgrade,$setup);

                $quarterGrades->{'quarter'.$studentgrade->quarter} = $gradeinfo->qg;
               
            }

           
            if( $quarterGrades->quarter1!=null &&
                $quarterGrades->quarter2!=null &&
                $quarterGrades->quarter3!=null &&
                $quarterGrades->quarter4!=null){

                    $quarterGrades->finalRating =  ($quarterGrades->quarter1 + $quarterGrades->quarter2 + $quarterGrades->quarter3 + $quarterGrades->quarter4)/4; 

                }
            
            if($quarterGrades->finalRating!=null){

                if($quarterGrades->finalRating>=75){
                    $quarterGrades->remarks = "PASSED";
                }
                else{
                    $quarterGrades->remarks = "FAILED";
                }
            }

           array_push($allGrades,$quarterGrades);

        }
       
        return   $allGrades;
    }


    public static function gradeInfo($id){

   

        $grades = DB::table('grades')
                    ->where('id',$id)
                    ->where('deleted','0')
                    ->first();

        $gradedetails = DB::table('gradesdetail')
                        ->join('grades','grades.id','=','gradesdetail.headerid')
                        ->where('headerid',$id)
                        ->orderBy('studname')
                        ->get();

        $setup = DB::table('gradessetup')
                        ->where('subjid',$grades->subjid)
                        ->where('levelid',$grades->levelid)
                        ->where('deleted','0')
                        ->get();
        
        

        foreach($gradedetails as $gradedetail){
         
            $gradedetail = self::studentGradeReport($gradedetail,$setup[0]);

        }

        return  $gradedetails;

    }

    public static function sectiongradeinfo($section, $subject, $quarter){

        $grades = DB::table('grades')
                ->where('sectionid',$section)
                ->where('subjid',$subject)
                ->where('quarter', $quarter)
                ->where('deleted','0')
                ->get();


        
        if( count($grades) > 0){
            
            $gradedetails = DB::table('gradesdetail')
                    ->join('grades','grades.id','=','gradesdetail.headerid')
                    ->join('studinfo',function($join){
                        $join->on('gradesdetail.studid','=','studinfo.id');
                    })
                    ->where('headerid',$grades[0]->id)
                    ->get();

            $setup = DB::table('gradessetup')
                ->where('subjid',$grades[0]->subjid)
                ->where('levelid',$grades[0]->levelid)
                ->where('deleted','0')
                ->get();

            foreach($gradedetails as $gradedetail){
                $gradedetail = self::studentGradeReport($gradedetail,$setup[0]);
                $gradedetail->{'gender'} = $gradedetail->gender;
            }

            return  $gradedetails;

        }

    }

    public static function generalAverage($studentInfo){

        $finalGrades = self::finalGrades($studentInfo);

        $generalAverage = array();
        $fgrades = collect((object) $finalGrades);

        $quarter1Grades = true;
        $quarter2Grades = true;
        $quarter3Grades = true;
        $quarter4Grades = true;

        foreach($finalGrades as $item){

            if($item->quarter1 == null){

                $quarter1Grades=false;
            }
            if($item->quarter2 == null){

                $quarter2Grades=false;

            }

            if($item->quarter3 == null){

                $quarter3Grades=false;

            }

            if($item->quarter4 == null){

                $quarter4Grades=false;

            }

        }

        $quarter1=null;
        $quarter2=null;
        $quarter3=null;
        $quarter4=null;
        $final = null;

        if($quarter1Grades){

            $quarter1 = round(collect($finalGrades)->avg('quarter1'));

        }
        if($quarter2Grades){

            $quarter2 = round(collect($finalGrades)->avg('quarter2'));

        }
        if($quarter3Grades){

            $quarter3 = round(collect($finalGrades)->avg('quarter3'));

        }
        if($quarter4Grades){

            $quarter4 = round(collect($finalGrades)->avg('quarter4'));

        }

        if($quarter1Grades && $quarter2Grades && $quarter3Grades && $quarter4Grades){

            $final = round(collect($finalGrades)->avg('finalRating'),2);

        }

    
        array_push($generalAverage, (object) array(
            "studentname"=>$studentInfo->firstname,
            "quarter1"=>$quarter1,
            "quarter2"=>$quarter2,
            "quarter3"=>$quarter3,
            "quarter4"=>$quarter4,
            "Final"=>$final,
        ));

        return $generalAverage;
    }

    public static function topGrader($quarter, $subject, $gradelevel){

        $setup = DB::table('gradessetup')
                ->where('subjid',$svvubject)
                ->where('levelid',$gradelevel)
                ->where('deleted','0')
                ->get();

        $studentgrade = DB::table('grades')
                ->join('gradesdetail',function($join){
                    $join->on('gradesdetail.headerid','=','grades.id');
                })
                ->join('subjects',function($join){
                    $join->on('grades.subjid','=','subjects.id');
                    $join->where('subjects.isactive','1');
                    $join->where('subjects.deleted','0');
                })
                ->join('sy',function($join){
                    $join->on('grades.syid','=','sy.id');
                    $join->where('sy.isactive','1');
                })
                ->join('sections',function($join){
                    $join->on('grades.sectionid','=','sections.id');
                    $join->where('sections.deleted','0');
                    })
                ->where('grades.quarter',$quarter)
                ->where('grades.deleted','0')
                ->where('grades.subjid',$subject)
                ->where('grades.submitted','1')
                ->where('grades.status','4')
                ->get();
        
     
        
        if(count($studentgrade)>0 && count($setup)>0){
            $ranking = array();
            
            foreach($studentgrade as $item){
                $item = self::studentGradeReport($item,$setup[0]);
               
             }

             $studentgrade = $studentgrade->sortByDesc('qg')->splice(0,5);
     
             $topGraders = array();
     
             array_push($topGraders,(object)array(
                 'subject'=>$studentgrade[0]->subjdesc,
             ));
     
             foreach($studentgrade as $key=>$item){
                 array_push($ranking,(object) array(
                     'ranking'=>$key+1,
                     'section'=>$item->sectionname,
                     'studname'=>$item->studname,
                     'grade'=>$item->qg
                 ));
             }
             
             array_push($topGraders,(object) ['rankings'=>$ranking]);
              
     
             return  $topGraders;
        }
        else{
            return "Empty";
        }
        

       
        

    }

  

 


}
