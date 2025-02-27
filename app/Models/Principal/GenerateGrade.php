<?php

namespace App\Models\Principal;
use DB;
use App\Models\Principal\ClassSched;
use App\Models\Principal\SPP_SHClassSchedule;
use App\Models\Principal\SPP_Student;
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
                            ->where('grades.deleted','0')
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

    public static function finalGradesV2(
        $studendinfo = null,
        $subjid = null){


        $assignsubjects = SPP_Subject::getSubject(
            null,
            null,
            null,
            $studendinfo->ensectid,
            null,
            null,
            null,
            $subjid
        );

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
                                            'remarks'=>null,
                                            'subjid'=>$assignsubject->id
                                        );
            
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
            

            if($studendinfo->acadprogid == 5){

                $studentgrades->join('semester',function($join){
                    $join->on('grades.semid','=','semester.id');
                    $join->where('semester.isactive','1');
                });

            }

         
                                
            $studentgrades->where('grades.sectionid',$studendinfo->ensectid)
                                ->where('grades.deleted','0')
                                ->where('subjid',$assignsubject->id)
                                ->where('submitted','1')
                                ->where('status','4')
                                ->orderBy('grades.quarter')
                                ->get();


            // return $studentgrades->get();

            foreach($studentgrades->get() as $studentgrade){

                // return $studentgrade;
                
                // $gradeinfo = self::studentGradeReport($studentgrade,$setup);

                // return $gradeinfo;

                $quarterGrades->{'quarter'.$studentgrade->quarter} = $studentgrade->qg;
                
            }

            // return $quarterGrades;
            
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


    public static function finalGrades(
        $studendinfo = null,
        $subjid = null
        ){

        $assignsubjects = SPP_Subject::getSubject(
            null,
            null,
            null,
            $studendinfo->ensectid,
            null,
            null,
            null,
            $subjid
        );

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
                                            'remarks'=>null,
                                            'subjid'=>$assignsubject->id
                                        );

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
            
            if($studendinfo->acadprogid == 5){

                $studentgrades->join('semester',function($join){
                    $join->on('grades.semid','=','semester.id');
                    $join->where('semester.isactive','1');
                });

            }
           
                                
            $studentgrades->where('grades.sectionid',$studendinfo->ensectid)
                                ->where('grades.deleted','0')
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

        // $finalGrades = self::finalGrades($studentInfo);

        
        $finalGrades = self::finalGradesV2($studentInfo);

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

    public static function reportCardV3($studentInfo, $all = false, $type = null){

        $subjects = SPP_Subject::getSubject(null,null,null,$studentInfo->ensectid,null,null,null,null,$type);

        $gradelevel = $studentInfo->levelid;

        try{
            if($gradelevel != 14 && $gradelevel != 15){
                foreach($subjects[0]->data as $item){
                    $get_tle = DB::table('subjects')->where('id',$item->id)->select('isTLE')->first();
                    $item->isTLE = $get_tle->isTLE;
                }
            }
        }catch(\Exception $e){
       
            if($gradelevel != 14 && $gradelevel != 15){
                foreach($subjects[0]->data as $item){
                    $item->isTLE = 0;
                }
            }
        }

        if($gradelevel != 14 && $gradelevel != 15){
            foreach($subjects[0]->data as $key=>$item){
                $get_setup = DB::table('gradessetup')
                            ->where('subjid',$item->id)
                            ->where('levelid',$gradelevel)   
                            ->where('deleted',0)
                            ->first();
                if(isset($get_setup->first)){
                    if($get_setup->first == 0 && $get_setup->second == 0 && $get_setup->third == 0 && $get_setup->fourth == 0){
                        unset($subjects[0]->data[$key]);
                    }
                }
                else{
                    unset($subjects[0]->data[$key]);
                }
            }
        }

 
       
        $subjects = collect($subjects)->where('inSF9',0);

        try{
            
            $subjectsForTemp = collect($subjects[0]->data)->map(function($subject){
                return $subject->id;
            });

        } catch (\Exception $e) {
            
            $subjectsForTemp = [];

        }

        $gradeinformation = DB::table('tempgradesum')->whereIn('subjid',$subjectsForTemp);

        if(Session::has('schoolYear')){

            $gradeinformation = $gradeinformation->join('sy',function($join){
                $join->on('tempgradesum.syid','=','sy.id');
                $join->where('sy.id',Session::get('schoolYear')->id);
            });

        }
        else{

            $gradeinformation = $gradeinformation->join('sy',function($join){
                $join->on('tempgradesum.syid','=','sy.id');
                $join->where('sy.isactive','1');
            });

        };

        if(!$all){

            if($studentInfo->acadprogid == 5){


                $gradeinformation = $gradeinformation->join('sh_subjects',function($join){
                    $join->on('tempgradesum.subjid','=','sh_subjects.id');
                });


                $gradeinformation = $gradeinformation->join('semester',function($join){
                    $join->on('tempgradesum.semid','=','semester.id');
                    $join->where('semester.id',Session::get('semester')->id);
                });


                $gradeinformation = $gradeinformation->where('studid',$studentInfo->id)->select('tempgradesum.*','sh_subjects.subjtitle','sh_subjects.inMAPEH','sh_subj_sortid as subj_sortid');

            }
            else{

                $gradeinformation = $gradeinformation->join('subjects',function($join){
                    $join->on('tempgradesum.subjid','=','subjects.id');
                });

                $gradeinformation = $gradeinformation->where('studid',$studentInfo->id)->select('tempgradesum.*','subjects.subjdesc','subjects.id as subjid','subjects.inMAPEH','inTLE','subj_sortid');

            }

        }
        else{

            if($studentInfo->acadprogid == 5){

                $gradeinformation = $gradeinformation->join('sh_subjects',function($join){
                    $join->on('tempgradesum.subjid','=','sh_subjects.id');
                });

                $gradeinformation = $gradeinformation->where('studid',$studentInfo->id)->select('tempgradesum.*','sh_subjects.subjtitle','sh_subjects.inMAPEH','sh_subj_sortid as subj_sortid');

            }
            else{

                $gradeinformation = $gradeinformation->join('subjects',function($join){
                    $join->on('tempgradesum.subjid','=','subjects.id');
                });

                $gradeinformation = $gradeinformation->where('studid',$studentInfo->id)->select('tempgradesum.*','subjects.subjdesc','subjects.id as subjid','subjects.inMAPEH','inTLE','subj_sortid');

            }

        }
        

        $gradeinformation = $gradeinformation->get();

        $grades = array();

        $generalave = array((object)[
            'quarter1'=>null,
            'quarter2'=>null,
            'quarter3'=>null,
            'quarter4'=>null,
            'Final'=>null
        ]);

        foreach($subjects[0]->data  as $item){

            if($item->inSF9 == 1){

                if($studentInfo->acadprogid != 5){

                    $withSubjects = collect($gradeinformation)->where('subjid',$item->id)->where('semid',1)->count(); 

                }
                else{
                    $withSubjects = collect($gradeinformation)->where('subjid',$item->id)->where('semid',Session::get('semester')->id)->count();
                }


                if($withSubjects == 0){


                    if($studentInfo->acadprogid != 5){

                        $gradeinformation->push((object)[
                            'studid'=>$studentInfo->id,
                            'subjid'=>$item->id,
                            'q1'=>null,
                            'q2'=>null,
                            'q3'=>null,
                            'q4'=>null,
                            'subjdesc'=>$item->subjdesc,
                            'Final'=>null,
                            'semid'=>1,
                            'inMAPEH'=>$item->inMAPEH,
                            'inTLE'=>$item->inTLE,
                            'subj_sortid'=>$item->subj_sortid
                        ]);

                    }else{

                        $gradeinformation->push((object)[
                            'studid'=>$studentInfo->acadprogid,
                            'subjid'=>$item->id,
                            'type'=>$item->type,
                            'subjtitle'=>$item->subjdesc,
                            'q1'=>null,
                            'q2'=>null,
                            'q3'=>null,
                            'q4'=>null,
                            'Final'=>null,
                            'semid'=>$item->semid,
                            'inMAPEH'=>$item->inMAPEH,
                            'inTLE'=>0,
                            'subj_sortid'=>$item->subj_sortid
                        ]);

                    }

                }   

            }

        }

        foreach($gradeinformation as $item){

            if($studentInfo->acadprogid != 5){

                $finalRating = null;
                $remarks = null;

                if( $item->q1 != null && $item->q2 != null && $item->q3 != null && $item->q4 != null){

                    $finalRating =number_format(  ( $item->q1 + $item->q2 + $item->q3 + $item->q4) / 4 );

                    if( $finalRating >= 75 ){
                        $remarks = 'PASSED';
                    }else{
                        $remarks = 'FAILED';
                    }

                }
            

                array_push($grades,(object)[
                                'subjectcode'=>$item->subjdesc,
                                'quarter1'=>$item->q1,
                                'quarter2'=>$item->q2,
                                'quarter3'=>$item->q3,
                                'quarter4'=>$item->q4,
                                'semid'=>$item->semid,
                                'subjid'=>$item->subjid,
                                'finalRating'=> $finalRating,
                                'remarks'=>$remarks,
                                'mapeh'=>$item->inMAPEH,
                                'inTLE'=>$item->inTLE,
                                'sortid'=>$item->subj_sortid
                            ]);

            }
            else{

                $finalRating = null;
                $remarks = null;


                if( $item->q1 != null && $item->q2 != null){

                    $finalRating = number_format( ( $item->q1 + $item->q2 ) / 2 );

                    if( $finalRating >= 75 ){
                        $remarks = 'PASSED';
                    }else{
                        $remarks = 'FAILED';
                    }



                }

                if(!isset($item->type)){

                    $item->type = 1;

                }

                array_push($grades,(object)[
                    'subjectcode'=>$item->subjtitle,
                    'type'=>$item->type,
                    'quarter1'=>$item->q1,
                    'quarter2'=>$item->q2,
                    'quarter3'=>$item->q3,
                    'quarter4'=>$item->q4,
                    'semid'=>$item->semid,
                    'subjid'=>$item->subjid,
                    'finalRating'=> $finalRating,
                    'remarks'=>$remarks,
                    'mapeh'=>$item->inMAPEH,
                    'inTLE'=>0,
                    'sortid'=>$item->subj_sortid
                ]);


            }

        }
    
        if(count($grades)==0){

            array_push($grades,(object)[
                'subjectcode'=>null,
                'quarter1'=>null,
                'quarter2'=>null,
                'quarter3'=>null,
                'quarter4'=>null,
                'quarter4'=>null,
                'subjid'=>null,
                'subjid'=>null,
                'finalRating'=>null,
                'remarks'=>null,
                'mapeh'=>null,
                'inTLE'=>0,
                'sortid'=>null
            ]);

        };


        if(collect($grades)->where('mapeh',1)->count() > 0){

            $finalMAPEH = 0;
            $mapehq1 = 0;
            $mapehq2 = 0;
            $mapehq3 = 0;
            $mapehq4 = 0;

            $inMapehCount = collect($grades)->where('mapeh',1)->count();

            if(collect($grades)->where('mapeh',1)->where('finalRating',null)->count() == 0){

                $finalMAPEH = collect($grades)->where('mapeh',1)->sum('finalRating') / $inMapehCount;

            }

            if(collect($grades)->where('mapeh',1)->where('quarter1',null)->count() == 0){

                $mapehq1 = number_format(collect($grades)->where('mapeh',1)->sum('quarter1') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter2',null)->count() == 0){

                $mapehq2 = number_format(collect($grades)->where('mapeh',1)->sum('quarter2') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter3',null)->count() == 0){

                $mapehq3 = number_format(collect($grades)->where('mapeh',1)->sum('quarter3') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter4',null)->count() == 0){

                $mapehq4 = number_format(collect($grades)->where('mapeh',1)->sum('quarter4') / $inMapehCount);

            }

            if( $finalMAPEH == 0){
                $finalMAPEH = null;
            }
            if( $mapehq1 == 0){
                $mapehq1 = null;
            }
            if( $mapehq2 == 0){
                $mapehq2 = null;
            }
            if( $mapehq3 == 0){
                $mapehq3 = null;
            }
            if( $mapehq4 == 0){
                $mapehq4 = null;
            }
            
            array_push($grades,(object)[
                'subjectcode'=>'MAPEH',
                'quarter1'=> $mapehq1,
                'quarter2'=> $mapehq2,
                'quarter3'=> $mapehq3,
                'quarter4'=> $mapehq4,
                'subjid'=>null,
                'subjid'=>null,
                'finalRating'=>$finalMAPEH,
                'remarks'=>null,
                'mapeh'=>0,
                'inTLE'=>0,
                'sortid'=>'2M0'
            ]);

        }

       

        return collect($grades)->sortBy('sortid');


    }

    public static function genAveV3($grades){

        $generalave = array((object)[
            'quarter1'=>null,
            'quarter2'=>null,
            'quarter3'=>null,
            'quarter4'=>null,
            'Final'=>null
        ]);

    

        if( collect($grades)->where('quarter1',null)->where('mapeh',0)->count() == 0 ){

            if(collect($grades)->where('mapeh',0)->count('quarter1') != 0){

                $generalave[0]->quarter1 = number_format(collect($grades)->where('mapeh',0)->sum('quarter1') / collect($grades)->where('mapeh',0)->count('quarter1'));

            }

           
        }

        if( collect($grades)->where('quarter2',null)->where('mapeh',0)->count() == 0 ){

            if(collect($grades)->where('mapeh',0)->count('quarter2') != 0){

                $generalave[0]->quarter2 = number_format(collect($grades)->where('mapeh',0)->sum('quarter2') / collect($grades)->where('mapeh',0)->count('quarter2'));

            }
        }

        if( collect($grades)->where('quarter3',null)->where('mapeh',0)->count() == 0 ){

            if(collect($grades)->where('mapeh',0)->count('quarter3') != 0){

                $generalave[0]->quarter3 = number_format(collect($grades)->where('mapeh',0)->sum('quarter3') / collect($grades)->where('mapeh',0)->count('quarter3'));
            }
        }

        if( collect($grades)->where('quarter4',null)->where('mapeh',0)->count() == 0 ){

            if(collect($grades)->where('mapeh',0)->count('quarter4') != 0){

                $generalave[0]->quarter4 = number_format(collect($grades)->where('mapeh',0)->sum('quarter4') / collect($grades)->where('mapeh',0)->count('quarter4'));
            
            }
        }
        
       
        if( collect($grades)->where('finalRating',null)->where('mapeh',0)->count() == 0
        ){

            if(collect($grades)->where('mapeh',0)->count('finalRating') != 0){

                $generalave[0]->Final = number_format(collect($grades)->where('mapeh',0)->sum('finalRating') / collect($grades)->where('mapeh',0)->count('finalRating'));

            }

        }
        
        return $generalave;

    }



    public static function reportCardV4($studentInfo, $all = false, $type = null){

        $subjects = SPP_Subject::getSubject(null,null,null,$studentInfo->ensectid,null,null,null,null,$type);

        $gradelevel = $studentInfo->levelid;

        try{
            if($gradelevel != 14 && $gradelevel != 15){
                foreach($subjects[0]->data as $item){
                    $get_tle = DB::table('subjects')->where('id',$item->id)->select('isTLE')->first();
                    $item->isTLE = $get_tle->isTLE;
                }
            }
        }catch(\Exception $e){
       
            if($gradelevel != 14 && $gradelevel != 15){
                foreach($subjects[0]->data as $item){
                    $item->isTLE = 0;
                }
            }
        }

        if($gradelevel != 14 && $gradelevel != 15){
            foreach($subjects[0]->data as $key=>$item){
                $get_setup = DB::table('gradessetup')
                            ->where('subjid',$item->id)
                            ->where('levelid',$gradelevel)   
                            ->where('deleted',0)
                            ->first();
                if(isset($get_setup->first)){
                    if($get_setup->first == 0 && $get_setup->second == 0 && $get_setup->third == 0 && $get_setup->fourth == 0){
                        unset($subjects[0]->data[$key]);
                    }
                }
                else{
                    unset($subjects[0]->data[$key]);
                }
            }
        }


        $activeSy = DB::table('sy')->where('isactive',1)->first();

        $grades = array();

        foreach($subjects[0]->data as $item){

            $grade = DB::table('gradesdetail')
                        ->join('grades',function($join) use($item,$activeSy){
                            $join->on('gradesdetail.headerid','=','grades.id');
                            $join->where('subjid',$item->id);
                            $join->where('grades.deleted',0);
                            $join->whereIn('status',[2,4]);
                            $join->where('syid',$activeSy->id);
                        })
                        ->where('studid',$studentInfo->id)
                        ->select('qg','quarter','semid','gradesdetail.id as gdid')
                        ->get();

            $qgq1 = null;
            $qgq2 = null;
            $qgq3 = null;
            $qgq4 = null;


            if(isset(collect($grade)->where('quarter','1')->first()->qg)){
                $qgq1 = collect($grade)->where('quarter','1')->first()->qg;
            }
            if(isset(collect($grade)->where('quarter','2')->first()->qg)){
                $qgq2 = collect($grade)->where('quarter','2')->first()->qg;
            }
            if(isset(collect($grade)->where('quarter','3')->first()->qg)){
                $qgq3 = collect($grade)->where('quarter','3')->first()->qg;
            }
            if(isset(collect($grade)->where('quarter','4')->first()->qg)){
                $qgq4 = collect($grade)->where('quarter','4')->first()->qg;
            }

            if(!isset($item->insSF9)){
                $item->inSF9 = 1;
            }

            $semid = 1;

            $tle = 0;
            if(isset($item->inTLE)){
                $tle = $item->inTLE;
            }


            $subj_per = null;

            if(isset($item->subj_per)){
               
                $subj_per = $item->subj_per;
            }

            if(count($grade) > 0){
                if($gradelevel == 14 || $gradelevel == 15){
                    if($item->semid != null){
                        $semid = $item->semid;
                    }
                }

                
                $type = null;

                if(isset($item->type)){
                    $type = $item->type;
                }

                array_push($grades,(object)[
                    'subjectcode'=>$item->subjdesc,
                    'quarter1'=>$qgq1,
                    'quarter2'=>$qgq2,
                    'quarter3'=>$qgq3,
                    'quarter4'=>$qgq4,
                    'subjid'=>$item->id,
                    'mapeh'=>$item->inMAPEH,
                    'inSF9'=>$item->inSF9,
                    'subj_per'=>$subj_per,
                    'inTLE'=>$tle,
                    'semid'=>$semid,
                    'finalRating'=>null,
                    'type'=>$type,
                    'gdid'=>$grade[0]->gdid,
                    'sortid'=>$item->subj_sortid
                ]);

            }else{

                if($gradelevel == 14 || $gradelevel == 15){
                    if($item->semid != null){
                        $semid = $item->semid;
                    }
                }


                array_push($grades,(object)[
                    'subjectcode'=>$item->subjdesc,
                    'quarter1'=>$qgq1,
                    'quarter2'=>$qgq2,
                    'quarter3'=>$qgq3,
                    'quarter4'=>$qgq4,
                    'subjid'=>$item->id,
                    'mapeh'=>$item->inMAPEH,
                    'inSF9'=>$item->inSF9,
                    'inTLE'=>$tle,
                    'semid'=>$semid,
                    'subj_per'=>$subj_per,
                    'finalRating'=>null,
                    'type'=>$type,
                    'gdid'=>null,
                    'sortid'=>$item->subj_sortid
                ]);



            }
        }


       


        if(collect($grades)->where('inTLE',1)->count() > 0){
    
            $tle_grades = collect($grades)->where('inTLE',1);

            $tle1 = 0;
            $tle2 = 0;
            $tle3 = 0; 
            $tle4 = 0; 
            $with_grade1 = true;
            $with_grade2 = true;
            $with_grade3 = true;
            $with_grade4 = true;
            $mapehcount = 0;
            
            $grade_list = array();

            foreach($tle_grades as $tle_item){
                
                $with_grade1 = $with_grade1 ? $tle_item->quarter1 == null ? false : $with_grade1 : false;
                $with_grade2 = $with_grade2 ? $tle_item->quarter2 == null ? false : $with_grade1 : false;
                $with_grade3 = $with_grade3 ? $tle_item->quarter3 == null ? false : $with_grade1 : false;
                $with_grade4 = $with_grade4 ? $tle_item->quarter4 == null ? false : $with_grade1 : false;
                
                $tle1 += number_format( $tle_item->quarter1 * ( $tle_item->subj_per / 100 ) );
                $tle2 += number_format( $tle_item->quarter2 * ( $tle_item->subj_per / 100 ) );
                $tle3 += number_format( $tle_item->quarter3 * ( $tle_item->subj_per / 100 ) );
                $tle4 += number_format( $tle_item->quarter4 * ( $tle_item->subj_per / 100 ) );
            }
              
            if($with_grade1 == 0){
                $tle1 = null;
            }
            if($with_grade2 == 0){
                $tle2 = null;
            }
            if($with_grade3 == 0){
                $tle3 = null;
            }
            if($with_grade4 == 0){
                $tle4 = null;
            }

            array_push($grades,(object)[
                'subjectcode'=>'HELE / COMPUTER',
                'quarter1'=>$tle1,
                'quarter2'=>$tle2,
                'quarter3'=>$tle3,
                'quarter4'=>$tle4,
                'subjid'=>'tle1',
                'mapeh'=>0,
                'inSF9'=>1,
                'inTLE'=>0,
                'semid'=>1,
                'subj_per'=>null,
                'finalRating'=>null,
                'type'=>1,
                'gdid'=>null,
                'sortid'=>'3T0'
            ]);
            

        }


        if(collect($grades)->where('mapeh',1)->count() > 0){

            $finalMAPEH = 0;
            $mapehq1 = 0;
            $mapehq2 = 0;
            $mapehq3 = 0;
            $mapehq4 = 0;

            $inMapehCount = collect($grades)->where('mapeh',1)->count();

            if(collect($grades)->where('mapeh',1)->where('finalRating',null)->count() == 0){

                $finalMAPEH = collect($grades)->where('mapeh',1)->sum('finalRating') / $inMapehCount;

            }

            if(collect($grades)->where('mapeh',1)->where('quarter1',null)->count() == 0){

                $mapehq1 = number_format(collect($grades)->where('mapeh',1)->sum('quarter1') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter2',null)->count() == 0){

                $mapehq2 = number_format(collect($grades)->where('mapeh',1)->sum('quarter2') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter3',null)->count() == 0){

                $mapehq3 = number_format(collect($grades)->where('mapeh',1)->sum('quarter3') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter4',null)->count() == 0){

                $mapehq4 = number_format(collect($grades)->where('mapeh',1)->sum('quarter4') / $inMapehCount);

            }

            if( $finalMAPEH == 0){
                $finalMAPEH = null;
            }
            if( $mapehq1 == 0){
                $mapehq1 = null;
            }
            if( $mapehq2 == 0){
                $mapehq2 = null;
            }
            if( $mapehq3 == 0){
                $mapehq3 = null;
            }
            if( $mapehq4 == 0){
                $mapehq4 = null;
            }
            
            array_push($grades,(object)[
                'subjectcode'=>'MAPEH',
                'quarter1'=> $mapehq1,
                'quarter2'=> $mapehq2,
                'quarter3'=> $mapehq3,
                'quarter4'=> $mapehq4,
                'subjid'=>null,
                'subjid'=>null,
                'finalRating'=>$finalMAPEH,
                'remarks'=>null,
                'subj_per'=>null,
                'inTLE'=>0,
                'mapeh'=>0,
                'sortid'=>'2M0'
            ]);

        }

        
       

        return collect($grades)->sortBy('sortid');

    }


    public static function reportCardV5($studentInfo, $all = false, $type = null){

        // $subjects = SPP_Subject::getSubject(null,null,null,$studentInfo->ensectid,null,null,null,null,$type);

        // $gradelevel = $studentInfo->levelid;

        // try{
        //     if($gradelevel != 14 && $gradelevel != 15){
        //         foreach($subjects[0]->data as $item){
        //             $get_tle = DB::table('subjects')->where('id',$item->id)->select('isTLE')->first();
        //             $item->isTLE = $get_tle->isTLE;
        //         }
        //     }
        // }catch(\Exception $e){
       
        //     if($gradelevel != 14 && $gradelevel != 15){
        //         foreach($subjects[0]->data as $item){
        //             $item->isTLE = 0;
        //         }
        //     }
        // }

        // if($gradelevel != 14 && $gradelevel != 15){
        //     foreach($subjects[0]->data as $key=>$item){
        //         $get_setup = DB::table('gradessetup')
        //                     ->where('subjid',$item->id)
        //                     ->where('levelid',$gradelevel)   
        //                     ->where('deleted',0)
        //                     ->first();
        //         if(isset($get_setup->first)){
        //             if($get_setup->first == 0 && $get_setup->second == 0 && $get_setup->third == 0 && $get_setup->fourth == 0){
        //                 unset($subjects[0]->data[$key]);
        //             }
        //         }
        //         else{
        //             unset($subjects[0]->data[$key]);
        //         }
        //     }
        // }

        // $activeSy = DB::table('sy')->where('isactive',1)->first();
      
        // if(Session::has('semester')){

        //     $activeSem = Session::get('semester');
        // }
        // else{

        //     $activeSem = DB::table('semester')->where('isactive',1)->first();

        // }

        // $grades = array();

        // foreach($subjects[0]->data as $item){

        //     $qgq1 = null;
        //     $qgq2 = null;
        //     $qgq3 = null;
        //     $qgq4 = null;

        //     if($studentInfo->acadprogid == 2){



        //     }else if($studentInfo->acadprogid == 3){
    
    
    
    
        //     }
        //     else if($studentInfo->acadprogid == 4){
    
        //         $grade = DB::table('grading_system_grades_hs')
        //                 ->where('studid',$studentInfo->id)
        //                 ->where('syid',$activeSy->id)
        //                 ->where('deleted',0)
        //                 ->where('subjid',$item->id)
        //                 ->select('qgq1','qgq2','qgq3','qgq4')
        //                 ->get();

        //         if(count($grade) > 0){
        //             if($grade[0]->qgq1 != 0.00){
        //                 $qgq1 = number_format($grade[0]->qgq1);
        //             }
        //             if($grade[0]->qgq2  != 0.00){
        //                 $qgq2 = number_format($grade[0]->qgq2);
        //             }
        //             if($grade[0]->qgq3  != 0.00){
        //                 $qgq3 = number_format($grade[0]->qgq3);
        //             }
        //             if($grade[0]->qgq4 != 0.00){
        //                 $qgq4 = number_format($grade[0]->qgq4);
        //             }
             
        //         }
              
        //     }
        //     else if($studentInfo->acadprogid == 5){
    
        //          $grade = DB::table('grading_system_grades_sh')
        //                     ->where('studid',$studentInfo->id)
        //                     ->where('syid',$activeSy->id)
        //                     ->where('semid',$activeSem->id)
        //                     ->where('deleted',0)
        //                     ->where('subjid',$item->id)
        //                     ->select('qgq1','qgq2','semid')
        //                     ->first();


        //          $subject_sem = DB::table('sh_subjects')
        //                     ->where('id',$item->id)
        //                     ->select('semid')
        //                     ->first();



        //         if(isset($grade->semid)){
        //             if($activeSem->id == 1 ){
        //                 if($grade->qgq1 > 60 ){
        //                     $qgq1 = $grade->qgq1;
        //                 }
        //                 if($grade->qgq2 > 60 ){
        //                     $qgq2 = $grade->qgq2;
        //                 }

        //             }elseif($activeSem->id == 2){
        //                 if($grade->qgq1 > 60 ){
        //                     $qgq3 = $grade->qgq1;
        //                 }
        //                 if($grade->qgq2 > 60 ){
        //                     $qgq4 = $grade->qgq2;
        //                 }
        //             }
        //         }

        //     }

        //     $semid = null;
        //     $type = null;

        //     if(isset($subject_sem->semid)){
        //         $semid = $subject_sem->semid;
        //     }
         
        //     if(isset($item->type)){
        //         $type = $item->type;
        //     }

        //     if(!isset($item->inSF9)){
        //         $item->inSF9 = 1;
        //     }
            
        //     array_push($grades,(object)[
        //         'subjectcode'=>$item->subjdesc,
        //         'quarter1'=>$qgq1,
        //         'quarter2'=>$qgq2,
        //         'quarter3'=>$qgq3,
        //         'quarter4'=>$qgq4,
        //         'subjid'=>$item->id,
        //         'mapeh'=>$item->inMAPEH,
        //         'inSF9'=>$item->inSF9,
        //         'semid'=>$semid,
        //         'type'=>$type,
        //         'sc'=>$item->subjcode
        //     ]);

        // }

        // if(collect($grades)->where('mapeh',1)->count() > 0){

        //     $finalMAPEH = 0;
        //     $mapehq1 = 0;
        //     $mapehq2 = 0;
        //     $mapehq3 = 0;
        //     $mapehq4 = 0;

        //     $inMapehCount = collect($grades)->where('mapeh',1)->count();

        //     if(collect($grades)->where('mapeh',1)->where('finalRating',null)->count() == 0){

        //         $finalMAPEH = collect($grades)->where('mapeh',1)->sum('finalRating') / $inMapehCount;

        //     }

        //     if(collect($grades)->where('mapeh',1)->where('quarter1',null)->count() == 0){

        //         $mapehq1 = number_format(collect($grades)->where('mapeh',1)->sum('quarter1') / $inMapehCount);

        //     }

        //     if(collect($grades)->where('mapeh',1)->where('quarter2',null)->count() == 0){

        //         $mapehq2 = number_format(collect($grades)->where('mapeh',1)->sum('quarter2') / $inMapehCount);

        //     }

        //     if(collect($grades)->where('mapeh',1)->where('quarter3',null)->count() == 0){

        //         $mapehq3 = number_format(collect($grades)->where('mapeh',1)->sum('quarter3') / $inMapehCount);

        //     }

        //     if(collect($grades)->where('mapeh',1)->where('quarter4',null)->count() == 0){

        //         $mapehq4 = number_format(collect($grades)->where('mapeh',1)->sum('quarter4') / $inMapehCount);

        //     }

        //     if( $finalMAPEH == 0){
        //         $finalMAPEH = null;
        //     }
        //     if( $mapehq1 == 0){
        //         $mapehq1 = null;
        //     }
        //     if( $mapehq2 == 0){
        //         $mapehq2 = null;
        //     }
        //     if( $mapehq3 == 0){
        //         $mapehq3 = null;
        //     }
        //     if( $mapehq4 == 0){
        //         $mapehq4 = null;
        //     }
            
        //     array_push($grades,(object)[
        //         'subjectcode'=>'MAPEH',
        //         'quarter1'=> $mapehq1,
        //         'quarter2'=> $mapehq2,
        //         'quarter3'=> $mapehq3,
        //         'quarter4'=> $mapehq4,
        //         'subjid'=>null,
        //         'subjid'=>null,
        //         'finalRating'=>$finalMAPEH,
        //         'remarks'=>null,
        //         'mapeh'=>0,
        //     ]);

        // }

        // return collect($grades)->sortBy('mapeh');

        $subjects = SPP_Subject::getSubject(null,null,null,$studentInfo->ensectid,null,null,null,null,$type);

        $activeSy = DB::table('sy')->where('isactive',1)->first();
      

        if(Session::has('semester')){

            $activeSem = Session::get('semester');
        }
        else{

            $activeSem = DB::table('semester')->where('isactive',1)->first();

        }
        
      

        $grades = array();

        foreach($subjects[0]->data as $item){

            $qgq1 = null;
            $qgq2 = null;
            $qgq3 = null;
            $qgq4 = null;

            if($studentInfo->acadprogid == 2){



            }else if($studentInfo->acadprogid == 3){
    
    
    
                $grade = DB::table('grading_system_gsgrades')
                        ->where('studid',$studentInfo->id)
                        ->where('syid',$activeSy->id)
                        ->where('deleted',0)
                        ->where('subjid',$item->id)
                        ->select('qgq1','qgq2','qgq3','qgq4')
                        ->get();

                if(count($grade) > 0){
                    if($grade[0]->qgq1 != 0.00){
                        $qgq1 = number_format($grade[0]->qgq1);
                    }
                    if($grade[0]->qgq2  != 0.00){
                        $qgq2 = number_format($grade[0]->qgq2);
                    }
                    if($grade[0]->qgq3  != 0.00){
                        $qgq3 = number_format($grade[0]->qgq3);
                    }
                    if($grade[0]->qgq4 != 0.00){
                        $qgq4 = number_format($grade[0]->qgq4);
                    }
             
                }
    
            }
            else if($studentInfo->acadprogid == 4){
    
                $grade = DB::table('grading_system_grades_hs')
                        ->where('studid',$studentInfo->id)
                        ->where('syid',$activeSy->id)
                        ->where('deleted',0)
                        ->where('subjid',$item->id)
                        ->select('qgq1','qgq2','qgq3','qgq4')
                        ->get();

                if(count($grade) > 0){
                    if($grade[0]->qgq1 != 0.00){
                        $qgq1 = number_format($grade[0]->qgq1);
                    }
                    if($grade[0]->qgq2  != 0.00){
                        $qgq2 = number_format($grade[0]->qgq2);
                    }
                    if($grade[0]->qgq3  != 0.00){
                        $qgq3 = number_format($grade[0]->qgq3);
                    }
                    if($grade[0]->qgq4 != 0.00){
                        $qgq4 = number_format($grade[0]->qgq4);
                    }
             
                }
    
    
            }
            else if($studentInfo->acadprogid == 5){
    
                 $grade = DB::table('grading_system_grades_sh')
                            ->where('studid',$studentInfo->id)
                            ->where('syid',$activeSy->id)
                            // ->where('semid',$activeSem->id)
                            ->where('deleted',0)
                            ->where('subjid',$item->id)
                            ->select('qgq1','qgq2','semid')
                            ->first();


                 $subject_sem = DB::table('sh_subjects')
                            ->where('id',$item->id)
                            ->select('semid')
                            ->first();



                if(isset($grade->semid)){
                    if($activeSem->id == 1 ){
                        if($grade->qgq1 >= 60 ){
                            $qgq1 = $grade->qgq1;
                        }
                        if($grade->qgq2 >= 60 ){
                            $qgq2 = $grade->qgq2;
                        }
                    }elseif($activeSem->id == 2){
                        if($grade->qgq1 >= 60 ){
                            $qgq3 = $grade->qgq1;
                        }
                        if($grade->qgq2 >= 60 ){
                            $qgq4 = $grade->qgq2;
                        }
                    }
                }
            }

            $semid = null;

            if(isset($subject_sem->semid)){

                $semid = $subject_sem->semid;

            }

            $type = null;

            if(isset($item->type)){

                $type = $item->type;

            }

            if(!isset($item->inSF9)){

                $item->inSF9 = 1;

            }
            
            array_push($grades,(object)[
                'subjectcode'=>$item->subjdesc,
                'quarter1'=>$qgq1,
                'quarter2'=>$qgq2,
                'quarter3'=>$qgq3,
                'quarter4'=>$qgq4,
                'subjid'=>$item->id,
                'mapeh'=>$item->inMAPEH,
                'inSF9'=>$item->inSF9,
                'semid'=>$semid,
                'type'=>$type,
                'sc'=>$item->subjcode,
                'sortid'=>$item->subj_sortid
            ]);

        }

      

        if(collect($grades)->where('mapeh',1)->count() > 0){

            $finalMAPEH = 0;
            $mapehq1 = 0;
            $mapehq2 = 0;
            $mapehq3 = 0;
            $mapehq4 = 0;

            $inMapehCount = collect($grades)->where('mapeh',1)->count();

            if(collect($grades)->where('mapeh',1)->where('finalRating',null)->count() == 0){

                $finalMAPEH = collect($grades)->where('mapeh',1)->sum('finalRating') / $inMapehCount;

            }

            if(collect($grades)->where('mapeh',1)->where('quarter1',null)->count() == 0){

                $mapehq1 = number_format(collect($grades)->where('mapeh',1)->sum('quarter1') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter2',null)->count() == 0){

                $mapehq2 = number_format(collect($grades)->where('mapeh',1)->sum('quarter2') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter3',null)->count() == 0){

                $mapehq3 = number_format(collect($grades)->where('mapeh',1)->sum('quarter3') / $inMapehCount);

            }

            if(collect($grades)->where('mapeh',1)->where('quarter4',null)->count() == 0){

                $mapehq4 = number_format(collect($grades)->where('mapeh',1)->sum('quarter4') / $inMapehCount);

            }

            if( $finalMAPEH == 0){
                $finalMAPEH = null;
            }
            if( $mapehq1 == 0){
                $mapehq1 = null;
            }
            if( $mapehq2 == 0){
                $mapehq2 = null;
            }
            if( $mapehq3 == 0){
                $mapehq3 = null;
            }
            if( $mapehq4 == 0){
                $mapehq4 = null;
            }
            
            array_push($grades,(object)[
                'subjectcode'=>'MUSIC, ARTS, PE, HEALTH',
                'quarter1'=> $mapehq1,
                'quarter2'=> $mapehq2,
                'quarter3'=> $mapehq3,
                'quarter4'=> $mapehq4,
                'subjid'=>null,
                'subjid'=>null,
                'finalRating'=>$finalMAPEH,
                'remarks'=>null,
                'mapeh'=>0,
                'sortid'=>'2M0'
                
            ]);

        }

       
        
        return collect($grades)->sortBy('sortid');

    }
  

    public static function all_grade_filtered($studentInfo, $all = false, $type = null, $syid = null, $semid = null){

     

        $subjects = SPP_Subject::getSubject(null,null,null,$studentInfo->ensectid,null,null,null,null,$type);

        try{
            
            $subjectsForTemp = collect($subjects[0]->data)->map(function($subject){
                return $subject->id;
            });

        } catch (\Exception $e) {
            
            $subjectsForTemp = [];

        }

        $gradeinformation = DB::table('tempgradesum')
                                ->whereIn('tempgradesum.subjid',$subjectsForTemp)
                                ->where('tempgradesum.syid',$syid)
                                ->where('tempgradesum.semid',$semid);

        if(!$all){

            if($studentInfo->acadprogid == 5){


                $gradeinformation = $gradeinformation->join('sh_subjects',function($join){
                    $join->on('tempgradesum.subjid','=','sh_subjects.id');
                });


                // $gradeinformation = $gradeinformation->join('semester',function($join){
                //     $join->on('tempgradesum.semid','=','semester.id');
                //     $join->where('semester.id',Session::get('semester')->id);
                // });


                $gradeinformation = $gradeinformation->where('studid',$studentInfo->id)->select('tempgradesum.*','sh_subjects.subjtitle as subjectcode','sh_subjects.inMAPEH');

            }
            else{

                $gradeinformation = $gradeinformation->join('subjects',function($join){
                    $join->on('tempgradesum.subjid','=','subjects.id');
                });

                $gradeinformation = $gradeinformation->where('studid',$studentInfo->id)->select('tempgradesum.*','subjects.subjdesc as  subjectcode','subjects.id as subjid','subjects.inMAPEH');

            }

        }
        else{

            if($studentInfo->acadprogid == 5){

                $gradeinformation = $gradeinformation->join('sh_subjects',function($join){
                    $join->on('tempgradesum.subjid','=','sh_subjects.id');
                });

                $gradeinformation = $gradeinformation->where('studid',$studentInfo->id)->select('tempgradesum.*','sh_subjects.subjtitle as subjectcode','sh_subjects.inMAPEH','type');

            }
            else{

                $gradeinformation = $gradeinformation->join('subjects',function($join){
                    $join->on('tempgradesum.subjid','=','subjects.id');
                });

                $gradeinformation = $gradeinformation->where('studid',$studentInfo->id)->select('tempgradesum.*','subjects.subjdesc as subjectcode','subjects.id as subjid','subjects.inMAPEH');

            }

        }
        

        $gradeinformation = $gradeinformation->get();

        $grades = array();

        $generalave = array((object)[
            'quarter1'=>null,
            'quarter2'=>null,
            'quarter3'=>null,
            'quarter4'=>null,
            'Final'=>null
        ]);

        foreach($subjects[0]->data  as $item){
        
            if($studentInfo->acadprogid != 5){

                $withSubjects = collect($gradeinformation)->where('subjid',$item->id)->where('semid',1)->count(); 

            }
            else{

                $withSubjects = collect($gradeinformation)->where('subjid',$item->id)->where('semid',$semid)->count();
            }

            if($withSubjects == 0){

                if($studentInfo->acadprogid != 5){
                   
                    $gradeinformation->push((object)[
                        'studid'=>$studentInfo->id,
                        'subjid'=>$item->id,
                        'q1'=>null,
                        'q2'=>null,
                        'q3'=>null,
                        'q4'=>null,
                        'subjectcode'=>$item->subjdesc,
                        'Final'=>null,
                        'semid'=>null,
                        'inMAPEH'=>$item->inMAPEH
                    ]);

                }else{

                    $gradeinformation->push((object)[
                        'studid'=>$studentInfo->acadprogid,
                        'subjid'=>$item->id,
                        'type'=>$item->type,
                        'subjectcode'=>$item->subjdesc,
                        'q1'=>null,
                        'q2'=>null,
                        'q3'=>null,
                        'q4'=>null,
                        'Final'=>null,
                        'semid'=>$studentInfo->semid,
                        'inMAPEH'=>$item->inMAPEH
                    ]);

                }

            }   

        }

        return $gradeinformation;

    }


}
