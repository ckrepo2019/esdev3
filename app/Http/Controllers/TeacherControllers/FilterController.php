<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class FilterController extends \App\Http\Controllers\Controller
{
    public function showSubjects($id,$syid,$gradelevelid)
    {
        $getteacherid = DB::table('teacher')
            ->select('id','lastname','firstname','middlename')
            ->where('userid',auth()->user()->id)
            ->get();
            // return $getteacherid;
        $sy_id = $syid; 
        $grade_level_id = $gradelevelid;
        $acadProg = DB::table('gradelevel')
            ->select('academicprogram.progname')
            ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
            ->where('gradelevel.id',$gradelevelid)
            ->get();
        if($acadProg[0]->progname == "SENIOR HIGH SCHOOL"){
            $assignsubjdetail = DB::table('sh_classsched')
                ->select('sections.id as sectionid','sections.sectionname','sh_classsched.glevelid','sh_classsched.syid','sh_subjects.id as subjectid','sh_subjects.subjtitle as subjdesc')
                // ->join('assignsubjdetail','assignsubj.ID','=','assignsubjdetail.headerid')
                ->join('sections','sh_classsched.sectionid','=','sections.id')
                ->join('sh_subjects','sh_classsched.subjid','=','sh_subjects.id')
                ->where('sh_classsched.glevelid',$grade_level_id)
                ->where('sh_classsched.sectionid',$id)
                ->where('sh_classsched.deleted',0)
                ->where('sh_classsched.syid',$sy_id)
                ->where('sh_classsched.teacherid',$getteacherid[0]->id)
                ->where('sh_subjects.deleted','0')
                ->where('sh_classsched.deleted','0')
                ->distinct()
                ->get();
            $assignsubjdetailblock = DB::table('sh_blocksched')
                ->select('sections.id as sectionid','sections.sectionname','sections.levelid as glevelid','sy.id as syid','sh_subjects.id as subjectid','sh_subjects.subjtitle as subjdesc')
                // ->join('teacher','sh_blocksched.teacherid','=','teacher.id')
                ->join('sy','sh_blocksched.syid','=','sy.id')
                ->join('sh_subjects','sh_blocksched.subjid','=','sh_subjects.id')
                ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
                ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                ->where('sh_blocksched.teacherid',$getteacherid[0]->id)
                ->where('sections.id',$id)
                ->where('sy.isactive','1')
                ->where('sh_subjects.deleted','0')
                ->where('sh_blocksched.deleted','0')
                ->distinct()
                ->get();
                
                // return $assignsubjdetailblock;
            if(count($assignsubjdetailblock)!=0){
                foreach($assignsubjdetailblock as $block){
                    $assignsubjdetail->push($block);
                }
            }
        }
        else {
            $assignsubjdetail = DB::table('assignsubj')
                ->select('sections.id as sectionid','sections.sectionname','assignsubj.glevelid','assignsubj.syid','subjects.id as subjectid','subjects.subjdesc')
                ->join('assignsubjdetail','assignsubj.ID','=','assignsubjdetail.headerid')
                ->join('sections','assignsubj.sectionid','=','sections.id')
                ->join('subjects','assignsubjdetail.subjid','=','subjects.id')
                ->where('assignsubj.glevelid',$grade_level_id)
                ->where('assignsubj.sectionid',$id)
                ->where('assignsubj.deleted',0)
                ->where('assignsubj.syid',$sy_id)
                ->where('assignsubjdetail.teacherid',$getteacherid[0]->id)
                ->where('assignsubjdetail.deleted','0')
                ->where('subjects.deleted','0')
                ->where('assignsubj.deleted','0')
                ->distinct()
                ->get();
        }
        // return $assignsubjdetail;
        if(count($assignsubjdetail)!=0){
            return view('teacher.showsubjects')
                ->with('gradeLevelid',$grade_level_id)
                ->with('schoolyearid',$sy_id)
                // ->with('sectionid',$id)
                // ->with('sectionname',$getsectionname[0]->sectionname)
                ->with('subjects',$assignsubjdetail);
        }
        else{
            return view('teacher.showsubjects')
                ->with('gradeLevelid',$grade_level_id)
                ->with('schoolyearid',$sy_id)
                // ->with('sectionid',$id)
                // ->with('sectionname',$getsectionname[0]->sectionname)
                ->with('message','No Assigned Subjects!');
        }
    }
    public function showQuarters($id,$syid,$gradelevelid,$sectionid)
    {

        $sy_id = $syid; 
        $grade_level_id = $gradelevelid;
        $section_id = $sectionid;
        $acadProg = DB::table('gradelevel')
            ->select('academicprogram.progname')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id',$grade_level_id)
            ->get();
        
        $getsectionname = DB::table('sections') 
            ->select('sectionname')
            ->where('id',$section_id)
            ->where('deleted',0)
            ->get();
        if($acadProg[0]->progname == "SENIOR HIGH SCHOOL"){
            $getsubjectname = DB::table('sh_subjects') 
                ->select('subjcode as subjdesc')
                ->where('id',$id)
                ->where('deleted',0)
                ->distinct()
                ->get();
        }
        else{
            $getsubjectname = DB::table('subjects') 
                ->select('subjdesc')
                ->where('id',$id)
                ->where('deleted',0)
                ->distinct()
                ->get();
        }   
        if(count($getsubjectname) == 0){
            
        }else{
            // $firstquarter = Db::table('grades')
            //     ->where('subjid', $id)
            //     ->where('sectionid', $section_id)
            //     ->where('levelid', $grade_level_id)
            //     ->where('syid', $sy_id)
            //     ->where('quarter', '1')
            //     ->get();
            // if(count($firstquarter) == 0){

            // }else{
            //     $firstquarterdetails = Db::table('gradesdetail')
            //         ->where('headerid', $firstquarter[0]->id)
            //         ->get();
            //     // return $firstquarterdetails;
            //     if(count($firstquarterdetails) == 0){

            //     }else{

            //     }
            // }
        }
  
        return view('teacher.showgrades')
            ->with('gradeLevelid',$grade_level_id)
            ->with('schoolyearid',$sy_id)
            ->with('sectionid',$section_id)
            ->with('subjectid',$id)
            ->with('sectionname',$getsectionname[0]->sectionname)
            ->with('subjectname',$getsubjectname[0]->subjdesc);
    }
    public function getGrades($id,Request $request)
    { 
        // return $request->all();
        $mutable = Carbon::now();
        $created_date_time = $mutable->toDateTimeString();
        $sy = $request->get('syid'); 
        $grade_level_id = $request->get('gradelevelid');
        $section_id = $request->get('sectionid');
        $subject = $request->get('subjectid');
        $quarter = $request->get('quarter');
        $acadProg = DB::table('gradelevel')
            ->select('academicprogram.progname')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id',$grade_level_id)
            ->get();
        $sem = Db::table('semester')
            ->where('isactive','1')
            ->get();
        if($quarter == 1){
            $quarterdesc = 'first';
        }
        elseif($quarter == 2){
            $quarterdesc = 'second';
        }
        elseif($quarter == 3){
            $quarterdesc = 'third';
        }
        elseif($quarter == 4){
            $quarterdesc = 'fourth';
        }
        $checkGradesSetup = Db::table('gradessetup')
            // ->select('id')
            ->where('levelid',$grade_level_id)
            ->where('subjid',$subject)
            ->where(''.$quarterdesc.'','1')
            ->get();
        if(count($checkGradesSetup)==0){
            return $message = 1;
        }
        else{
            $subj_id = DB::table('grades')
                ->where('syid',$sy)
                ->where('levelid',$grade_level_id)
                ->where('sectionid',$section_id)
                ->where('quarter',$quarter)
                ->where('subjid',$subject)
                ->where('grades.deleted','0')
                ->get();
            // return $subj_id;
            if(count($subj_id)==0){
    
                DB::insert('insert into grades (syid,levelid,sectionid,subjid,quarter,deleted,createddatetime,submitted,status,wwhr1,wwhr2,wwhr3,wwhr4,wwhr5,wwhr6,wwhr7,wwhr8,wwhr9,wwhr0,pthr1,pthr2,pthr3,pthr4,pthr5,pthr6,pthr7,pthr8,pthr9,pthr0,qahr1) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$sy,$grade_level_id,$section_id,$subject,$quarter,0,$created_date_time,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                
     
                $headerid = DB::table('grades')
                    ->where('grades.syid',$sy)
                    ->where('grades.levelid',$grade_level_id)
                    ->where('grades.sectionid',$section_id)
                    ->where('grades.quarter',$quarter)
                    ->where('grades.subjid',$subject)
                    ->where('grades.deleted','0')
                    ->get();
                            
                    $academicprogram = Db::table('gradelevel')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('gradelevel.id',$grade_level_id)
                    ->first();
                if($academicprogram->acadprogcode == 'SHS'){
                    $get_students = DB::table('studinfo')
                                ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix')
                                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                                ->where('sh_enrolledstud.levelid',$grade_level_id)
                                ->where('sh_enrolledstud.sectionid',$section_id)
                                ->where('sh_enrolledstud.syid',$sy)
                                // ->orderBy('lastname','asc')
                                ->where('sh_enrolledstud.studstatus','!=',0)
                                ->distinct()
                                ->get(); 
                }else{
                    $get_students = DB::table('studinfo')
                                ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix')
                                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                                ->where('enrolledstud.levelid',$grade_level_id)
                                ->where('enrolledstud.sectionid',$section_id)
                                ->where('enrolledstud.syid',$sy)
                                // ->orderBy('lastname','asc')
                                ->where('enrolledstud.studstatus','!=',0)
                                ->distinct()
                                ->get(); 
                }
                    // return $get_students;
                foreach($get_students as $student_grades_detail){
                    // return $student_grades_detail->firstname;
                    $studentname = $student_grades_detail->lastname.', '.$student_grades_detail->firstname;
                    DB::insert('insert into gradesdetail (headerid,studid,studname,wwws,ptws,qaws,wwps,ptps,qaps,wwtotal,pttotal,ig,qg,ww1,ww2,ww3,ww4,ww5,ww6,ww7,ww8,ww9,ww0,pt1,pt2,pt3,pt4,pt5,pt6,pt7,pt8,pt9,pt0,qa1,remarks) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$headerid[0]->id,$student_grades_detail->id,$studentname,0,0,0,0,0,0,0,0,0,60,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                }
                // return $headerid;
                $get_students = DB::table('studinfo')
                    ->select('id','lastname','firstname','middlename','suffix')
                    ->where('levelid',$grade_level_id)
                    ->where('sectionid',$section_id)
                    ->where('studstatus','!=',0)
                    ->get();
                foreach($get_students  as $student){
                    $students = DB::table('gradesdetail')
                        ->where('studid',$student->id)
                        ->where('headerid',$headerid[0]->id)
                        ->get();
                    if(count($students)==0){
                        if($acadProg[0]->progname == "SENIOR HIGH SCHOOL"){
                            $students_enrolled = DB::table('sh_enrolledstud')
                                ->where('studid',$student->id)
                                ->where('syid',$sy)
                                ->where('levelid',$grade_level_id)
                                ->where('sectionid',$section_id)
                                ->where('studstatus','!=',0)
                                ->get();
                        }
                        else{
                            $students_enrolled = DB::table('enrolledstud')
                                ->where('studid',$student->id)
                                ->where('syid',$sy)
                                ->where('levelid',$grade_level_id)
                                ->where('sectionid',$section_id)
                                ->where('studstatus','!=',0)
                                ->get();
                        }
                        $student_name = $student->lastname.', '.$student->firstname;
                        DB::insert('insert into gradesdetail (headerid,studid,enrollid,studname,wwws,ptws,qaws,wwps,ptps,qaps,wwtotal,pttotal,ig,qg,ww1,ww2,ww3,ww4,ww5,ww6,ww7,ww8,ww9,ww0,pt1,pt2,pt3,pt4,pt5,pt6,pt7,pt8,pt9,pt0,qa1,remarks) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$headerid[0]->id,$student->id,$students_enrolled[0]->id,$student_name,0,0,0,0,0,0,0,0,0,0,60,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0," "]);
                    }
                }
                 $hps_ww_total_score = $headerid[0]->wwhr1 + $headerid[0]->wwhr2 + $headerid[0]->wwhr3 + $headerid[0]->wwhr4 + $headerid[0]->wwhr5 + $headerid[0]->wwhr6 + $headerid[0]->wwhr7 + $headerid[0]->wwhr8 + $headerid[0]->wwhr9+ $headerid[0]->wwhr0;
                 
                $hps_pt_total_score = $headerid[0]->pthr1 + $headerid[0]->pthr2 + $headerid[0]->pthr3 + $headerid[0]->pthr4 + $headerid[0]->pthr5 + $headerid[0]->pthr6 + $headerid[0]->pthr7 + $headerid[0]->pthr8 + $headerid[0]->pthr9+ $headerid[0]->pthr0;
    
                $hps_qa_total_score = $headerid[0]->qahr1;
    
                $gradeDetails = DB::table('gradesdetail')
                    ->where('headerid',$headerid[0]->id)
                    ->get();
                    
                $setup = DB::table('gradessetup')
                        ->where('subjid',$subject)
                        ->where('levelid',$grade_level_id)
                        ->where('deleted','0')
                        ->get();
                // return $setup;
                $detailsArray = array();
                
    
                array_push($detailsArray,$setup);
                array_push($detailsArray,$gradeDetails);
                array_push($detailsArray,$headerid);
                array_push($detailsArray,$hps_ww_total_score);
                $wwtotal = array();
                $wwps = array();
                $wwws = array();
                
                foreach($gradeDetails as $grades){
                    $student_total_ww_grade = $grades->ww1 + $grades->ww2 + $grades->ww3 + $grades->ww4 + $grades->ww5 + $grades->ww6 + $grades->ww7 + $grades->ww8 + $grades->ww9 + $grades->ww0;
                    
                    if($student_total_ww_grade == 0){
                        array_push($wwtotal,0);
                        $wwpstotal = number_format((float)$student_total_ww_grade, 2, '.',''); 
                        $wwwstotal = number_format((float)$student_total_ww_grade, 2, '.','');
                        array_push($wwws,0);
                        array_push($wwps,0);
                    }
                    else if($student_total_ww_grade > 0){
                        array_push($wwtotal,$student_total_ww_grade);
                        if($hps_ww_total_score==0){
                            array_push($wwps,0);
                            array_push($wwws,0);
                        }
                        elseif($hps_ww_total_score>0){
                        $parttotal = $student_total_ww_grade/$hps_ww_total_score;
                        $sub = $parttotal*100;
                        $wwpstotal = number_format((float)$sub, 2, '.','');
                        array_push($wwps,$wwpstotal);
                        $float = '.'.$setup[0]->writtenworks;
                        $wwws1 = $wwpstotal *  $float;
                        $wwwstotal = number_format((float)$wwws1, 2, '.','');
                        array_push($wwws,$wwwstotal);
                        }
                    }
                }
                $pttotal = array();
                $ptps = array();
                $ptws = array();
                array_push($detailsArray,$wwtotal);
                array_push($detailsArray,$wwps);
                array_push($detailsArray,$wwws);
                array_push($detailsArray,$hps_pt_total_score);
                foreach($gradeDetails as $grades){
                    $student_total_pt_grade = $grades->pt1 + $grades->pt2 + $grades->pt3 + $grades->pt4 + $grades->pt5 + $grades->pt6 + $grades->pt7 + $grades->pt8 + $grades->pt9 + $grades->pt0;
                    if($student_total_pt_grade == 0){
                        array_push($pttotal,0);
                        $ptpstotal = number_format((float)$student_total_pt_grade, 2, '.','');
                        $ptwstotal = number_format((float)$student_total_pt_grade, 2, '.','');
                        array_push($ptws,0);
                        array_push($ptps,0);
                    }
                    elseif($student_total_pt_grade > 0){
                        array_push($pttotal,$student_total_pt_grade);
                        if($hps_pt_total_score==0){
                            array_push($ptps,0);
                            array_push($ptws,0);
                        }
                        elseif($hps_pt_total_score>0){
                            $parttotal2 = $student_total_pt_grade/$hps_pt_total_score;
                            $sub2 = $parttotal2*100;
                            $ptpstotal = number_format((float)$sub2, 2, '.','');
                            array_push($ptps,$ptpstotal);
                            $float2 = '.'.$setup[0]->performancetask; 
                            $ptws1 = $ptpstotal *  $float2; 
                            $ptwstotal = number_format((float)$ptws1, 2, '.','');
                            array_push($ptws,$ptwstotal);
                        } 
                    }
                }
                $qaps = array();
                $qaws = array();
                array_push($detailsArray,$pttotal);
                array_push($detailsArray,$ptps);
                array_push($detailsArray,$ptws);
    
                foreach($gradeDetails as $grades){
                    if($grades->qa1 == 0){
                        array_push($qaps,0); 
                        array_push($qaws,0);
                    }
                    elseif($grades->qa1 > 0){
                        if($hps_qa_total_score==0){
                        array_push($qaps,0);
                        array_push($qaws,0);
                        }
                        elseif($hps_qa_total_score>0){
                        $parttotal3 = $grades->qa1/$hps_qa_total_score;
                        $sub3 = $parttotal3*100;
                        $qapstotal = number_format((float)$sub3, 2, '.','');
                        array_push($qaps,$qapstotal);
                        $float3 = '.'.$setup[0]->qassesment;
                        $qaws2 = $qapstotal *  $float3;
                        $qawstotal = number_format((float)$qaws2, 2, '.','');
                        array_push($qaws,$qawstotal);
                        }
                    } 
                }
                
                array_push($detailsArray,$qaps);
                array_push($detailsArray,$qaws);
                $ig = array();
                $qg = array();
                for($x = 0; $x < count($wwws); $x++){ 
                    $totalIG = $wwws[$x] + $ptws[$x] + $qaws[$x];
                    $igtotal = number_format((float)$totalIG, 2, '.','');
                    array_push($ig,$igtotal);
                    $gts = DB::table('gradetransmutation')->get();
                    $quarterGrade = 0;
                    $gtsfound = 0;
                    foreach($gts as $gt){
                        if($gt->gfrom >= $igtotal && $gtsfound == 0){
                            foreach($gts as $gtx){
                                if($gtx->gto >= $igtotal && $gtsfound == 0){
                                    $gtsfound = 1;
                                    array_push($qg,$gtx->gvalue);
                                }
                            }
                        }
                    }
                    
                    if($gtsfound==0){
                        return 'hello';
                    }
                    
                }
                array_push($detailsArray,$ig);
                array_push($detailsArray,$qg);
                array_push($detailsArray,$headerid);
                return $detailsArray; 
            }
            else{
                $headerid = DB::table('grades')
                            // ->select('grades.id')
                            ->where('grades.syid',$sy)
                            ->where('grades.levelid',$grade_level_id)
                            ->where('grades.sectionid',$section_id)
                            ->where('grades.quarter',$quarter)
                            ->where('grades.subjid',$subject)
                            ->where('grades.deleted','0')
                            ->get();
                // return $headerid;
                // $unpostrequest = DB::table('unpostrequest')
                //             ->where('gradesid',$headerid[0]->id)
                //             ->get();
                //         // reu
                // if(count($unpostrequest)==0){
                //     $unpostrequest = array(['status'=>'none']);
                // }
                // return $grade_level_id;
                $academicprogram = Db::table('gradelevel')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('gradelevel.id',$grade_level_id)
                    ->first();
                if($academicprogram->acadprogcode == 'SHS'){
                    $get_students = DB::table('studinfo')
                                ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix')
                                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                                ->where('sh_enrolledstud.levelid',$grade_level_id)
                                ->where('sh_enrolledstud.sectionid',$section_id)
                                // ->orderBy('lastname','asc')
                                ->where('sh_enrolledstud.studstatus','!=',0)
                                ->where('sh_enrolledstud.syid',$sy)
                                ->distinct()
                                ->get(); 
                }else{
                    $get_students = DB::table('studinfo')
                                ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix')
                                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                                ->where('enrolledstud.levelid',$grade_level_id)
                                ->where('enrolledstud.sectionid',$section_id)
                                // ->orderBy('lastname','asc')
                                ->where('enrolledstud.studstatus','!=',0)
                                ->where('enrolledstud.syid',$sy)
                                ->distinct()
                                ->get(); 
                }
                // return $get_students;
                foreach($get_students  as $student){
                    $students = DB::table('gradesdetail')
                                ->where('studid',$student->id)
                                ->where('headerid',$headerid[0]->id)
                                // ->orderBy('studname','asc')
                                ->get();
                    if(count($students)==0){
                        $student_name = $student->lastname.', '.$student->firstname;
                        // if>
                            DB::insert('insert into gradesdetail (headerid,studid,enrollid,studname,wwws,ptws,qaws,wwps,ptps,qaps,wwtotal,pttotal,ig,qg,ww1,ww2,ww3,ww4,ww5,ww6,ww7,ww8,ww9,ww0,pt1,pt2,pt3,pt4,pt5,pt6,pt7,pt8,pt9,pt0,qa1,remarks) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$headerid[0]->id,$student->id,$student->id,$student_name,0,0,0,0,0,0,0,0,0,60,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0," "]);

                    }
    
                }
                // return count($get_students);
                $hps_ww_total_score = $headerid[0]->wwhr1 + $headerid[0]->wwhr2 + $headerid[0]->wwhr3 + $headerid[0]->wwhr4 + $headerid[0]->wwhr5 + $headerid[0]->wwhr6 + $headerid[0]->wwhr7 + $headerid[0]->wwhr8 + $headerid[0]->wwhr9+ $headerid[0]->wwhr0;
                $hps_pt_total_score = $headerid[0]->pthr1 + $headerid[0]->pthr2 + $headerid[0]->pthr3 + $headerid[0]->pthr4 + $headerid[0]->pthr5 + $headerid[0]->pthr6 + $headerid[0]->pthr7 + $headerid[0]->pthr8 + $headerid[0]->pthr9+ $headerid[0]->pthr0;
                $hps_qa_total_score = $headerid[0]->qahr1;
                // return $hps_qa_total_score;
                $gradeDetails = DB::table('gradesdetail')
                                ->where('headerid',$headerid[0]->id)
                                // ->orderBy('studname','asc')
                                ->get(); 
                            // return $gradeDetails;
                $setup = DB::table('gradessetup')
                        ->where('subjid',$subject)
                        ->where('levelid',$grade_level_id)
                        ->where('deleted','0')
                        ->get();
                // return $setup;
                if(count($setup)==0){
                    return $message = '1';
                }
                else{
                    $detailsArray = array();
                    
        
                    $wwtotal = array();
                    $wwps = array();
                    $wwws = array();
                    //  return count($gradeDetails);
                    foreach($gradeDetails as $grades){
                        $student_total_ww_grade = $grades->ww1 + $grades->ww2 + $grades->ww3 + $grades->ww4 + $grades->ww5 + $grades->ww6 + $grades->ww7 + $grades->ww8 + $grades->ww9 + $grades->ww0;
                        // return $student_total_ww_grade;
                        if($student_total_ww_grade == 0){
                            array_push($wwtotal,0);
                            $wwpstotal = number_format((float)$student_total_ww_grade, 2, '.',''); 
                            $wwwstotal = number_format((float)$student_total_ww_grade, 2, '.','');
                            array_push($wwws,0);
                            array_push($wwps,0);
                        }
                        else if($student_total_ww_grade > 0){
                            array_push($wwtotal,$student_total_ww_grade);
                            if($hps_ww_total_score==0){
                                array_push($wwps,0);
                                array_push($wwws,0);
                            }
                            elseif($hps_ww_total_score>0){
                                $parttotal = $student_total_ww_grade/$hps_ww_total_score;
                                $sub = $parttotal*100;
                                $wwpstotal = number_format((float)$sub, 2, '.','');
                                array_push($wwps,$wwpstotal);
                                $float = '.'.$setup[0]->writtenworks;
                                $wwws1 = $wwpstotal *  $float;
                                // return $setup[0]->writtenworks;
                                $wwwstotal = number_format((float)$wwws1, 2, '.','');
                                array_push($wwws,$wwwstotal);
                            }
                        }
                    }
                    // return $wwps;
                    $pttotal = array();
                    $ptps = array();
                    $ptws = array();
                    
                    foreach($gradeDetails as $grades){
                        $student_total_pt_grade = $grades->pt1 + $grades->pt2 + $grades->pt3 + $grades->pt4 + $grades->pt5 + $grades->pt6 + $grades->pt7 + $grades->pt8 + $grades->pt9 + $grades->pt0;
                        if($student_total_pt_grade == 0){
                            array_push($pttotal,0);
                            $ptpstotal = number_format((float)$student_total_pt_grade, 2, '.','');
                            $ptwstotal = number_format((float)$student_total_pt_grade, 2, '.','');
                            array_push($ptws,0);
                            array_push($ptps,0);
                        }
                        elseif($student_total_pt_grade > 0){
                            array_push($pttotal,$student_total_pt_grade);
                            if($hps_pt_total_score==0){
                                array_push($ptps,0);
                                array_push($ptws,0);
                            }
                            elseif($hps_pt_total_score>0){
                                $parttotal2 = $student_total_pt_grade/$hps_pt_total_score;
                                $sub2 = $parttotal2*100;
                                $ptpstotal = number_format((float)$sub2, 2, '.','');
                                array_push($ptps,$ptpstotal);
                                $float2 = '.'.$setup[0]->performancetask;
                                // return $float2;
                                $ptws1 = $ptpstotal *  $float2;
                                // return $ptws;
                                $ptwstotal = number_format((float)$ptws1, 2, '.','');
                                array_push($ptws,$ptwstotal);
                            }
                            
                        }
                    }
                    $qaps = array();
                    $qaws = array();
                    
        
                    foreach($gradeDetails as $grades){
                        if($grades->qa1 == 0){
                            array_push($qaps,0);
                            // $qawstotal = number_format((float)$grades->qa1, 2, '.','');
                            array_push($qaws,0);
                        }
                        elseif($grades->qa1 > 0){
                            if($hps_qa_total_score==0){
                            array_push($qaps,0);
                            array_push($qaws,0);
                            }
                            elseif($hps_qa_total_score>0){
                            $parttotal3 = $grades->qa1/$hps_qa_total_score;
                            $sub3 = $parttotal3*100;
                            $qapstotal = number_format((float)$sub3, 2, '.','');
                            array_push($qaps,$qapstotal);
                            $float3 = '.'.$setup[0]->qassesment;
                            $qaws2 = $qapstotal *  $float3;
                            $qawstotal = number_format((float)$qaws2, 2, '.','');
                            array_push($qaws,$qawstotal);
                            }
                        }
        
                    }
                    
                    $ig = array();
                    $qg = array();
                    for($x = 0; $x < count($wwws); $x++){
                        $totalIG = $wwws[$x] + $ptws[$x] + $qaws[$x];
                        $igtotal = number_format((float)$totalIG, 2, '.','');
                        array_push($ig,$igtotal);
                        $gts = DB::table('gradetransmutation')->get();
                        $quarterGrade = 0;
                        $gtsfound = 0;
                        foreach($gts as $gt){
                            if($gt->gfrom >= $igtotal && $gtsfound == 0){
                                foreach($gts as $gtx){
                                    if($gtx->gto >= $igtotal && $gtsfound == 0){
                                        $gtsfound = 1;
                                        array_push($qg,$gtx->gvalue);
                                    }
                                }
                            }
                        } 
                        
                    } 
                // return $gradeDetails;
                    // return $ig;
                    // return $qg;
                    
                    array_push($detailsArray,$setup);
                    array_push($detailsArray,$gradeDetails);
                    array_push($detailsArray,$subj_id);
                    array_push($detailsArray,$hps_ww_total_score);
                    array_push($detailsArray,$wwtotal);
                    array_push($detailsArray,$wwps);
                    array_push($detailsArray,$wwws);
                    array_push($detailsArray,$hps_pt_total_score);
                    array_push($detailsArray,$pttotal);
                    array_push($detailsArray,$ptps);
                    array_push($detailsArray,$ptws);
                    array_push($detailsArray,$qaps);
                    array_push($detailsArray,$qaws);
                    array_push($detailsArray,$ig);
        
                    array_push($detailsArray,$qg);
        
                    return $detailsArray;
                }
            
                }
        }
        
    }
    public function updateData(Request $request, $id)
    {
        if($request->get('identifier')=='th'){
            $syid = $request->get('syid');
            $headerTH = $request->get('headerTH');
            $levelID = $request->get('levelID');
            $sectionid = $request->get('sectionid');
            $quarterID = $request->get('quarterID');
            $subjectID = $request->get('subjectID');
            $headerClass = $request->get('headerClass');
            $headerValue = $request->get('headerValue');
            $subj_id = DB::table('grades')
                ->where('syid',$syid)
                ->where('levelid',$levelID)
                ->where('sectionid',$sectionid)
                ->where('quarter',$quarterID)
                ->where('subjid',$subjectID)
                ->where('grades.deleted','0')
                ->get();
                // return strlen($headerClass);
            // if(strlen($headerClass) == 5){
            //     DB::update('update grades set '.$headerClass.' = ? where syid = ? and levelid = ? and sectionid = ? and subjid = ? and quarter = ?' ,[$headerValue,$syid,$levelID,$sectionid,$subjectID,$quarterID]);
            //     $subj_id = DB::table('grades')
            //         ->where('syid',$syid)
            //         ->where('levelid',$levelID)
            //         ->where('sectionid',$sectionid)
            //         ->where('quarter',$quarterID)
            //         ->where('subjid',$subjectID)
            //         ->get();
            //     $totalscores = array();
            //     $hps_ww_total_score = $subj_id[0]->wwhr1 + $subj_id[0]->wwhr2 + $subj_id[0]->wwhr3 + $subj_id[0]->wwhr4 + $subj_id[0]->wwhr5 + $subj_id[0]->wwhr6 + $subj_id[0]->wwhr7 + $subj_id[0]->wwhr8 + $subj_id[0]->wwhr9+ $subj_id[0]->wwhr0;
            //     $hps_pt_total_score = $subj_id[0]->pthr1 + $subj_id[0]->pthr2 + $subj_id[0]->pthr3 + $subj_id[0]->pthr4 + $subj_id[0]->pthr5 + $subj_id[0]->pthr6 + $subj_id[0]->pthr7 + $subj_id[0]->pthr8 + $subj_id[0]->pthr9+ $subj_id[0]->pthr0; 
            //     array_push($totalscores,$hps_ww_total_score);
            //     array_push($totalscores,$hps_pt_total_score);
            //     return response()->json($totalscores);
            // }
            // else if(strlen($headerClass) == 33){ 
                // return 'sdf';
                // RETURN 
                $moreheaderclass = explode(' ',trim($headerClass)); 
                DB::update('update grades set '.$moreheaderclass[0].' = ? where syid = ? and levelid = ? and sectionid = ? and subjid = ? and quarter = ?' ,[$headerValue,$syid,$levelID,$sectionid,$subjectID,$quarterID]);
                $subj_id = DB::table('grades')
                        ->where('syid',$syid)
                        ->where('levelid',$levelID)
                        ->where('sectionid',$sectionid)
                        ->where('quarter',$quarterID)
                        ->where('subjid',$subjectID)
                        ->where('grades.deleted','0')
                        ->get();
                $totalscores = array();
                $hps_ww_total_score = $subj_id[0]->wwhr1 + $subj_id[0]->wwhr2 + $subj_id[0]->wwhr3 + $subj_id[0]->wwhr4 + $subj_id[0]->wwhr5 + $subj_id[0]->wwhr6 + $subj_id[0]->wwhr7 + $subj_id[0]->wwhr8 + $subj_id[0]->wwhr9+ $subj_id[0]->wwhr0;
                $hps_pt_total_score = $subj_id[0]->pthr1 + $subj_id[0]->pthr2 + $subj_id[0]->pthr3 + $subj_id[0]->pthr4 + $subj_id[0]->pthr5 + $subj_id[0]->pthr6 + $subj_id[0]->pthr7 + $subj_id[0]->pthr8 + $subj_id[0]->pthr9+ $subj_id[0]->pthr0; 
                array_push($totalscores,$subj_id);
                array_push($totalscores,$hps_ww_total_score);
                array_push($totalscores,$hps_pt_total_score);
                return response()->json($totalscores);
                
            // }
        }
        else if($request->get('identifier')=='td'){ 
            $syid = $request->get('syid');
            $levelID = $request->get('levelID');
            $sectionid = $request->get('sectionid');
            $quarterID = $request->get('quarterID');
            $subjectID = $request->get('subjectID');
            $student_ID = $request->get('student_ID');
            $student_header_class = $request->get('student_header_class');
            $student_grade = $request->get('student_grade');
            $headerID = $request->get('headerID');
            $wwtotal = $request->get('wwtotal');
            $wwps = $request->get('wwps');
            $wwws = $request->get('wwws');
            $pttotal = $request->get('pttotal');
            $ptps = $request->get('ptps');
            $ptws = $request->get('ptws');
            $qaps = $request->get('qaps');
            $qaws = $request->get('qaws');
            $ig = $request->get('ig');
            $qg = $request->get('qg');
            
            $student_header_class = explode(' ',trim($student_header_class));  
            // if(strlen($student_header_class) == 3){ 
                DB::update('update gradesdetail set '.$student_header_class[0].' = ?, wwtotal = ?, wwws= ?, wwps = ?, pttotal = ?, ptws = ?, ptps = ?, qaws = ?, qaps = ?, ig = ?, qg = ? where studid = ? and headerid = ?' ,[$student_grade,$wwtotal,$wwws,$wwps,$pttotal,$ptws,$ptps,$qaws,$qaps,$ig,$qg,$student_ID,$headerID]);
                $gradeInfo = DB::table('grades')
                        // ->select('gradesdetail.wwtotal','gradesdetail.wwps')
                        ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                        ->join('gradessetup','grades.levelid','=','gradessetup.levelid')
                        ->where('grades.syid',$syid)
                        ->where('grades.levelid',$levelID)
                        ->where('grades.sectionid',$sectionid)
                        ->where('grades.quarter',$quarterID)
                        ->where('grades.subjid',$subjectID)
                        ->where('gradesdetail.studid',$student_ID)
                        ->where('gradessetup.subjid',$subjectID)
                        ->where('grades.deleted','0')
                        ->get();
                        
                $hps_ww_total_score = $gradeInfo[0]->wwhr1 + $gradeInfo[0]->wwhr2 + $gradeInfo[0]->wwhr3 + $gradeInfo[0]->wwhr4 + $gradeInfo[0]->wwhr5 + $gradeInfo[0]->wwhr6 + $gradeInfo[0]->wwhr7 + $gradeInfo[0]->wwhr8 + $gradeInfo[0]->wwhr9+ $gradeInfo[0]->wwhr0; 

                $totalscores = array();

                $ww_total_score = $gradeInfo[0]->ww1 + $gradeInfo[0]->ww2 + $gradeInfo[0]->ww3 + $gradeInfo[0]->ww4 + $gradeInfo[0]->ww5 + $gradeInfo[0]->ww6 + $gradeInfo[0]->ww7 + $gradeInfo[0]->ww8 + $gradeInfo[0]->ww9+ $gradeInfo[0]->ww0;
                if($ww_total_score==0){
                    $wwps_student = 0;
                }else{
                    $wwps_student = ($ww_total_score/$hps_ww_total_score)*100;
                }

                $wwps_st = number_format((float)$wwps_student, 2, '.','');

                $wwps_float = '.'.$gradeInfo[0]->writtenworks;

                $wwws_student = $wwps_st*$wwps_float;

                $wwws_st = number_format((float)$wwws_student, 2, '.','');

                $hps_pt_total_score = $gradeInfo[0]->pthr1 + $gradeInfo[0]->pthr2 + $gradeInfo[0]->pthr3 + $gradeInfo[0]->pthr4 + $gradeInfo[0]->pthr5 + $gradeInfo[0]->pthr6 + $gradeInfo[0]->pthr7 + $gradeInfo[0]->pthr8 + $gradeInfo[0]->pthr9+ $gradeInfo[0]->pthr0;

                $pt_total_score = $gradeInfo[0]->pt1 + $gradeInfo[0]->pt2 + $gradeInfo[0]->pt3 + $gradeInfo[0]->pt4 + $gradeInfo[0]->pt5 + $gradeInfo[0]->pt6 + $gradeInfo[0]->pt7 + $gradeInfo[0]->pt8 + $gradeInfo[0]->pt9+ $gradeInfo[0]->pt0;
                
                if($pt_total_score==0){
                    $ptps_student = 0;
                }
                else{
                    $ptps_student = ($pt_total_score/$hps_pt_total_score)*100;
                }


                $ptps_st = number_format((float)$ptps_student, 2, '.','');

                $ptps_float = '.'.$gradeInfo[0]->performancetask;

                $ptws_student = $ptps_st*$ptps_float;

                $ptws_st = number_format((float)$ptws_student, 2, '.','');
                
                $hps_qa_total_score = $gradeInfo[0]->qahr1;

                $qa_total_score = $gradeInfo[0]->qa1;
                // return $qa_total_score;
                if($qa_total_score==0){
                    $qaps_student = 0;
                }
                else{
                    $qaps_student = ($qa_total_score/$hps_qa_total_score)*100;
                }

                $qaps_st = number_format((float)$qaps_student, 2, '.','');

                $qaps_float = '.'.$gradeInfo[0]->qassesment;

                $qaws_student = $qaps_st*$qaps_float;

                $qaws_st = number_format((float)$qaws_student, 2, '.','');

                $initial_grade = number_format((float)$wwws_st+$ptws_st+$qaws_st, 2, '.','');

                $gts = DB::table('gradetransmutation')->get();

                $qG = 0;
                $gtsfound = 0;

                foreach ($gts as $gt){
                    if($gt->gfrom >= $initial_grade && $gtsfound == 0){
                        foreach ($gts as $gtx){
                            if($gtx->gto >= $initial_grade && $gtsfound == 0){
                                $gtsfound = 1;
                                $qG = $gtx->gvalue;
                            }
                        }
                    }
                }
                
                array_push($totalscores,array($student_ID,$student_header_class,$headerID,$ww_total_score,$wwps_st,$wwws_st,$pt_total_score,$ptps_st,$ptws_st,$qaps_st,$qaws_st,$initial_grade,$qG));

                return response()->json($totalscores);
    
            // }
            // else if(strlen($student_header_class) == 14){
            //     $moreclass = substr($student_header_class, 0,-11);
            //     DB::update('update gradesdetail set '.$moreclass.' = ?, wwtotal = ?, wwws= ?, wwps = ?, pttotal = ?, ptws = ?, ptps = ?, qaws = ?, qaps = ?, ig = ?, qg = ? where studid = ? and headerid = ?' ,[$student_grade,$wwtotal,$wwws,$wwps,$pttotal,$ptws,$ptps,$qaws,$qaps,$ig,$qg,$student_ID,$headerID]);
                
            //     $gradeInfo = DB::table('grades')
            //             // ->select('gradesdetail.wwtotal','gradesdetail.wwps')
            //             ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
            //             ->join('gradessetup','grades.levelid','=','gradessetup.levelid')
            //             ->where('grades.syid',$syid)
            //             ->where('grades.levelid',$levelID)
            //             ->where('grades.sectionid',$sectionid)
            //             ->where('grades.quarter',$quarterID)
            //             ->where('grades.subjid',$subjectID)
            //             ->where('gradesdetail.studid',$student_ID)
            //             ->where('gradessetup.subjid',$subjectID)
            //             ->get();
                        
            //     $hps_ww_total_score = $gradeInfo[0]->wwhr1 + $gradeInfo[0]->wwhr2 + $gradeInfo[0]->wwhr3 + $gradeInfo[0]->wwhr4 + $gradeInfo[0]->wwhr5 + $gradeInfo[0]->wwhr6 + $gradeInfo[0]->wwhr7 + $gradeInfo[0]->wwhr8 + $gradeInfo[0]->wwhr9+ $gradeInfo[0]->wwhr0;
                
            //     $totalscores = array();

            //     $ww_total_score = $gradeInfo[0]->ww1 + $gradeInfo[0]->ww2 + $gradeInfo[0]->ww3 + $gradeInfo[0]->ww4 + $gradeInfo[0]->ww5 + $gradeInfo[0]->ww6 + $gradeInfo[0]->ww7 + $gradeInfo[0]->ww8 + $gradeInfo[0]->ww9+ $gradeInfo[0]->ww0;

            //     $wwps_student = ($ww_total_score/$hps_ww_total_score)*100;

            //     $wwps_st = number_format((float)$wwps_student, 2, '.','');

            //     $wwps_float = '.'.$gradeInfo[0]->writtenworks;
                
            //     $wwws_student = $wwps_st*$wwps_float;

            //     $wwws_st = number_format((float)$wwws_student, 2, '.','');

            //     $hps_pt_total_score = $gradeInfo[0]->pthr1 + $gradeInfo[0]->pthr2 + $gradeInfo[0]->pthr3 + $gradeInfo[0]->pthr4 + $gradeInfo[0]->pthr5 + $gradeInfo[0]->pthr6 + $gradeInfo[0]->pthr7 + $gradeInfo[0]->pthr8 + $gradeInfo[0]->pthr9+ $gradeInfo[0]->pthr0;

            //     $pt_total_score = $gradeInfo[0]->pt1 + $gradeInfo[0]->pt2 + $gradeInfo[0]->pt3 + $gradeInfo[0]->pt4 + $gradeInfo[0]->pt5 + $gradeInfo[0]->pt6 + $gradeInfo[0]->pt7 + $gradeInfo[0]->pt8 + $gradeInfo[0]->pt9+ $gradeInfo[0]->pt0;

            //     if($pt_total_score == 0){
            //         $ptps_student = 0;
            //     }
            //     else{
            //         $ptps_student = ($pt_total_score/$hps_pt_total_score)*100;
            //     }

            //     $ptps_st = number_format((float)$ptps_student, 2, '.','');

            //     $ptps_float = '.'.$gradeInfo[0]->performancetask;

            //     $ptws_student = $ptps_st*$ptps_float;

            //     $ptws_st = number_format((float)$ptws_student, 2, '.','');
                
            //     $hps_qa_total_score = $gradeInfo[0]->qahr1;

            //     $qa_total_score = $gradeInfo[0]->qa1;

            //     if($qa_total_score == 0){
            //         $qaps_student = 0;
            //     }
            //     else{
            //         $qaps_student = ($qa_total_score/$hps_qa_total_score)*100;
            //     }

            //     $qaps_st = number_format((float)$qaps_student, 2, '.','');

            //     $qaps_float = '.'.$gradeInfo[0]->qassesment;

            //     $qaws_student = $qaps_st*$qaps_float;

            //     $qaws_st = number_format((float)$qaws_student, 2, '.','');

            //     $initial_grade = number_format((float)$wwws_st+$ptws_st+$qaws_st, 2, '.','');

            //     $gts = DB::table('gradetransmutation')->get();

            //     $qG = 0;
            //     $gtsfound = 0;

            //     foreach ($gts as $gt){
            //         if($gt->gfrom >= $initial_grade && $gtsfound == 0){
            //             foreach ($gts as $gtx){
            //                 if($gtx->gto >= $initial_grade && $gtsfound == 0){
            //                     $gtsfound = 1;
            //                     $qG = $gtx->gvalue;
            //                 }
            //             }
            //         }
            //     }
                
            //     array_push($totalscores,array($student_ID,$student_header_class,$headerID,$ww_total_score,$wwps_st,$wwws_st,$pt_total_score,$ptps_st,$ptws_st,$qaps_st,$qaws_st,$initial_grade,$qG));

            //     return response()->json($totalscores);
            // }
        }
        else if($request->get('identifier')=='passinggrade'){
            $test=DB::update('update grades set passinggrade = ? where levelid = ? and sectionid = ? and subjid = ? and quarter = ?',[$request->get('passinggrade'),$request->get('gradeLevel'),$request->get('section'),$request->get('subjects'),$request->get('quarters')]);

            $updatedPassingGrade = DB::table('grades')
                ->select('passinggrade')
                ->where('levelid',$request->get('gradeLevel'))
                ->where('sectionid',$request->get('section'))
                ->where('subjid',$request->get('subjects'))
                ->where('quarter',$request->get('quarters'))
                ->where('grades.deleted','0')
                ->get();
            return $updatedPassingGrade;
        }
    }
    public function updateGradeStatus($id,Request $request)
    {

        $datetime = \Carbon\Carbon::now('Asia/Manila');

        $mutable = Carbon::now();
        $created_date_time = $mutable->toDateTimeString();
        $sy_id = $request->get('syid');
        $grade_level_id = $request->get('gradelevelid');
        $section_id = $request->get('section');
        $quarter = $request->get('quarter');
        $subject_id = $request->get('subjectid');
        $excluded = $request->get('excluded');

        $levelinfo = DB::table('gradelevel')->where('id', $grade_level_id)->first();

        $semid = DB::table('semester')->where('id',$request->get('semid'))->first();

        if($request->get('dataHolder')=="submit"){
            if($levelinfo->acadprogid != 5){
                DB::table('grades')
                    ->where('syid',$sy_id)
                    ->where('sectionid',$section_id)
                    ->where('subjid',$subject_id)
                    ->where('quarter',$quarter)
                    ->where('deleted',0)
                    ->update([
                        'status'=>0,
                        'submitted'=>1,
                        'submittedby'=>auth()->user()->id,
                        'date_submitted'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'coorapp'=>null,
                        'coorappdatetime'=>null
                    ]);
            }
            else{
                DB::table('grades')
                    ->where('syid',$sy_id)
                    ->where('semid',$semid->id)
                    ->where('sectionid',$section_id)
                    ->where('subjid',$subject_id)
                    ->where('quarter',$quarter)
                    ->where('deleted',0)
                    ->update([
                        'status'=>0,
                        'submitted'=>1,
                        'submittedby'=>auth()->user()->id,
                        'date_submitted'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'coorapp'=>null,
                        'coorappdatetime'=>null
                    ]);
            }

            if($levelinfo->acadprogid != 5){

                $get_grade_id = DB::table('grades')
                                        ->select('id')
                                        ->where('syid',$sy_id)
                                        ->where('levelid',$grade_level_id)
                                        ->where('sectionid',$section_id)
                                        ->where('subjid',$subject_id)
                                        ->where('quarter',$quarter)
                                        ->where('deleted',0)
                                        // ->where('submitted',1)
                                        ->get();

                DB::table('grading_system_pending_grade')
                        ->where('syid',$sy_id)
                        ->where('levelid',$grade_level_id)
                        ->where('sectionid',$section_id)
                        ->where('subjid',$subject_id)
                        ->where('quarter',$quarter)
                        ->where('deleted',0)
                        ->update([
                            'deleted'=>1,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            }
            else{

                $get_grade_id = DB::table('grades')
                            ->select('id')
                            ->where('syid',$sy_id)
                            ->where('levelid',$grade_level_id)
                            ->where('sectionid',$section_id)
                            ->where('subjid',$subject_id)
                            ->where('quarter',$quarter)
                            ->where('semid',$semid->id)
                            ->where('deleted',0)
                            // ->where('submitted',1)
                            ->get();


                DB::table('grading_system_pending_grade')
                    ->where('syid',$sy_id)
                    ->where('levelid',$grade_level_id)
                    ->where('sectionid',$section_id)
                    ->where('subjid',$subject_id)
                    ->where('quarter',$quarter)
                    ->where('semid',$semid->id)
                    ->where('deleted',0)
                    ->update([
                        'deleted'=>1,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
                    
            }

            try{

                if($excluded == null){
                    $excluded = array();
                }

                
                DB::table('gradesdetail')
                    ->where('headerid',$get_grade_id[0]->id)
                    ->whereNotIn('studid',$excluded)
                    ->update([
                        'gdstatus'=>1,
                        'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    ]);

                DB::table('gradesdetail')
                    ->where('headerid',$get_grade_id[0]->id)
                    ->whereIn('studid',$excluded)
                    ->update([
                        'gdstatus'=>3,
                        'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    ]);


            }catch(\Exception $e){}
          

            $getPrincipalId = DB::table('gradelevel')
                                ->select('teacher.userid')
                                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                                ->join('teacher','academicprogram.principalid','=','teacher.id')
                                ->where('gradelevel.id',$grade_level_id)
                                ->get();
    
            $gradelogid = DB::table('gradelogs')->insertGetId([
                'user_id'=> auth()->user()->id,
                'gradeid'=>$get_grade_id[0]->id,
                'action'=>'1',
                'createddatetime'=>$datetime,
                'createdby'=>auth()->user()->id
            ]);
            
            DB::table('notifications')
                ->insert([
                    'headerid' => $gradelogid,
                    'type' => '3',
                    'status' => '0',
                    'recieverid' => $getPrincipalId[0]->userid
                ]);

            return 1;

        }
        elseif($request->get('dataHolder')=="request"){
         
            $gradeAndSection = DB::table('sections')
                ->select('sections.sectionname','gradelevel.levelname','teacher.id as principalid','academicprogram.progname')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->join('teacher','academicprogram.principalid','=','teacher.id')
                ->where('sections.id',$section_id)
                ->where('gradelevel.id',$grade_level_id)
                ->where('teacher.deleted','0')
                ->get();

            if($gradeAndSection[0]->progname == "SENIOR HIGH SCHOOL"){
                $getSubjectname = DB::table('sh_subjects')
                    ->select('subjcode as subjdesc')
                    ->where('id',$subject_id)
                    ->where('deleted','0')
                    ->where('sh_subjects.deleted','0')
                    ->get();
            }
            else{
                $getSubjectname = DB::table('subjects')
                    ->where('id',$subject_id)
                    ->where('deleted','0')
                    ->get();
            }
                
            $numOfPendingRequests = DB::table('announcements')
                ->where('title','Grades change request')
                ->where('content','Requesting permission to make changes to the posted grades of '.$gradeAndSection[0]->levelname.' - '.$gradeAndSection[0]->sectionname.', '.$quarter.' grading of '.$getSubjectname[0]->subjdesc.'')
                ->where('createdby',auth()->user()->id)
                ->get();
                
            if(count($numOfPendingRequests)==0){
                $gradelogid = DB::table('announcements')
                    ->insertGetId([
                        'title' => 'Grades change request',
                        'content'=> 'Requesting permission to make changes to the posted grades of '.$gradeAndSection[0]->levelname.' - '.$gradeAndSection[0]->sectionname.', '.$quarter.' grading of '.$getSubjectname[0]->subjdesc.'',
                        'recievertype' => '6',
                        'announcementtype' => 2,
                        'createdby' => auth()->user()->id
                    ]);
                DB::table('notifications')
                    ->insert([
                        'headerid' => $gradelogid,
                        'recieverid' => $gradeAndSection[0]->principalid,
                        'type' => 2,
                        'status' => '0'
                    ]);
                $message = 'Request sent!';
            }
            else{
                $message = 'Request already exist!';
            }
            return response()->json(['message' => $message]);
        }
        // DB::insert('insert into gradelogs (user_id,gradeid,action,date) values(?,?,?,?)',[$getteacherid[0]->id,$get_grade_id[0]->id,1,$created_date_time]);
        // $getHeader = DB::table('grades')
        //             ->where()
        
    }
}
