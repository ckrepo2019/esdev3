<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\GenerateGrade;
class SeniorHighController extends Controller
{
    public function form10($action, Request $request){

        $gradelevels = DB::table('gradelevel')
            ->select(
                'gradelevel.id',
                'gradelevel.levelname'
            )
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('academicprogram.acadprogcode','SHS')
            ->where('gradelevel.deleted','0')
            ->get();

        if($action == 'dashboard'){

            $currentschoolyear = Db::table('sy')
                ->where('isactive','1')
                ->first();

            $studid = $request->get('studid');

            $studinfo = Db::table('studinfo')
                ->select(
                    'id',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.lastname',
                    'studinfo.suffix',
                    'studinfo.lrn',
                    'studinfo.dob',
                    'studinfo.gender'
                    )
                ->where('id',$studid)
                ->first();

            $schoolyears = DB::table('sh_enrolledstud')
                ->select(
                    'sh_enrolledstud.syid',
                    'sy.sydesc',
                    'sh_enrolledstud.levelid',
                    'gradelevel.levelname',
                    'sh_enrolledstud.sectionid',
                    'sh_enrolledstud.blockid',
                    // 'sh_enrolledstud.semid',
                    'sh_strand.strandcode as strand',
                    'sh_track.trackname as track',
                    'sections.sectionname as section'
                    )
                ->join('gradelevel','sh_enrolledstud.levelid','gradelevel.id')
                ->join('sy','sh_enrolledstud.syid','sy.id')
                ->join('sections','sh_enrolledstud.sectionid','sections.id')
                ->join('sh_strand','sh_enrolledstud.strandid','sh_strand.id')
                ->join('sh_track','sh_strand.trackid','sh_track.id')
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.studid',$studid)
                ->distinct()
                ->orderByDesc('sh_enrolledstud.levelid')
                ->get();
            
            // if(count($schoolyears) > 0){

            //     $schoolyearsarray = array();
                
            //     foreach($schoolyears as $schoolyear){



            //     }
            // }
            
            $failingsubjectsArray = array();

            $gradelevelsenrolled = array();

            $gradesArray = array();
            
            if(count($schoolyears) == 0){

                $currentlevelid = array();
            }
            elseif(count($schoolyears) > 0){

                $currentlevelid = (object)array(
                    'syid' => $schoolyears[0]->syid,
                    'levelid' => $schoolyears[0]->levelid,
                    'levelname' => $schoolyears[0]->levelname
                );
    
    
                foreach($schoolyears as $sy){
        
                    array_push($gradelevelsenrolled,(object)array(
                        'levelid' => $sy->levelid,
                        'levelname' => $sy->levelname
                    ));
    
                    $getsubjects = Db::table('sh_classsched')
                        ->select(
                            'sh_subjects.id',
                            'sh_subjects.subjcode',
                            'sh_subjects.subjtitle',
                            'sh_classsched.semid'
                            )
                        ->join('sh_subjects','sh_classsched.subjid','sh_subjects.id')
                        ->where('sh_classsched.syid',$sy->syid)
                        ->where('sh_classsched.glevelid',$sy->levelid)
                        ->where('sh_classsched.sectionid',$sy->sectionid)
                        ->distinct()
                        ->get();
    
                    $firstsem = array();
    
                    $secondsem = array();
    
                    $summerArray = array();
                    
                    foreach($getsubjects as $subjects){
    
                        $quarter1 = 0;
                        $quarter2 = 0;
                        // $quarter3 = 0;
                        // $quarter4 = 0;
    
                        $getgrades = Db::table('tempgradesum')
                            // ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                            ->where('tempgradesum.syid',$sy->syid)
                            // ->where('grades.levelid',$sy->levelid)
                            ->where('tempgradesum.semid',$subjects->semid)
                            ->where('tempgradesum.subjid',$subjects->id)
                            ->where('tempgradesum.studid',$studid)
                            ->distinct()
                            ->get();
                        // return $getgrades;
                        foreach ($getgrades as $value) {
    
                            // if($value->quarter == 1){
    
                                $quarter1=$value->q1;
    
                            // }
    
                            // if($value->quarter == 2){
    
                                $quarter2=$value->q2;
    
                            // }
    
                            // if($value->quarter == 3){
    
                            //     $quarter3+=$value->qg;
    
                            // }
    
                            // if($value->quarter == 4){
    
                            //     $quarter4+=$value->qg;
    
                            // }
    
                        }
    
                        if($subjects->semid == 1){
    
                            array_push($firstsem,(object)array(
                                'subjcode' => $subjects->subjcode,
                                'subjtitle' => $subjects->subjtitle,
                                    'quarter1' => $quarter1,
                                    'quarter2' => $quarter2
                            ));
                        
                        }
    
                        elseif($subjects->semid == 2){
    
                            array_push($secondsem,(object)array(
                                'subjcode' => $subjects->subjcode,
                                'subjtitle' => $subjects->subjtitle,
                                    'quarter1' => $quarter1,
                                    'quarter2' => $quarter2
                            ));
    
                        }
                        
                        $summer = Db::table('gradesspclass')
                            ->select('sh_subjects.subjcode','sh_subjects.subjtitle','gradesspclass.qg','sh_classsched.semid')
                            ->join('sh_subjects','gradesspclass.subjid','sh_subjects.id')
                            ->join('sh_classsched','gradesspclass.subjid','sh_subjects.id')
                            ->where('gradesspclass.syid',$sy->syid)
                            ->where('gradesspclass.levelid',$sy->levelid)
                            ->where('gradesspclass.studid',$studid)
                            ->where('gradesspclass.subjid',$subjects->id)
                            ->where('sh_classsched.syid',$sy->syid)
                            ->where('sh_classsched.glevelid',$sy->levelid)
                            ->where('sh_classsched.subjid',$subjects->id)
                            ->distinct()
                            ->get();
    
                        if(count($summer)!=0){
    
                            array_push($summerArray,$summer);
    
                        }
    
                        $qg = ($quarter1 + $quarter2) / 2;
    
                        if($currentlevelid->syid == $currentschoolyear->id && $currentlevelid->levelid == $sy->levelid){
    
                            if($qg<75){
    
                                array_push($failingsubjectsArray,(object)array(
                                    'id' => $subjects->id,
                                    'subjcode' => $subjects->subjcode,
                                    'subjtitle' => $subjects->subjtitle,
                                    'grade' => $qg,
                                    'levelid' => $sy->levelid
                                ));
    
                            }
    
                        }
    
                    }
    
                    
    
                    $schoolinfo = Db::table('sf10_student_sh')
                        ->where('studid',$studid)
                        ->where('levelid',$sy->levelid)
                        ->get();
    
                    if(count($schoolinfo) == 0){
                        $schoolinformation = Db::table('schoolinfo')
                            ->first();
                    }else{
                        $schoolinformation = Db::table('sf10_student_sh')
                        ->where('studid',$studid)
                        ->where('levelid',$sy->levelid)
                        ->first();
                    }
                    
                    array_push($gradesArray, (object) array(
                            'gradedetails' => $sy,
                            'schoolinformation' => $schoolinformation,
                            'firstsem' => $firstsem,
                            'secondsem' => $secondsem,
                            'summer' => $summerArray
                    ));
    
                }
                
            }
            
            if(count(collect($gradelevelsenrolled)->unique()) == 2){

                $completed = 1;

            }
            elseif(count(collect($gradelevelsenrolled)->unique()) < 2){

                $completed = 0;

            }
            // return collect($gradesArray)->unique();
            $transfergradesArray = array();

            $transferrecord = Db::table('sf10_student_sh')
                ->select(
                    'sf10_student_sh.id'
                    )
                ->join('sf10_schoollist','sf10_student_sh.sf10_schoolid','=','sf10_schoollist.id')
                ->where('sf10_student_sh.studid',$studid)
                ->get();

            foreach($transferrecord as $record){

                $transfergrades = Db::table('sf10_grades_sh')
                    ->where('sf10_grades_sh.sf10_studentshid',$record->id)
                    ->get();

                array_push($transfergradesArray,$transfergrades);

            }
            
            $sh_subjects = Db::table('sh_subjects')
                ->select(
                    'id',
                    'subjtitle'
                    )
                ->where('deleted','0')
                ->distinct()
                ->get();

            $eligibility = Db::table('sf10eligibility')
                ->where('studid', $studid)
                ->where('acadprogid', '5')
                ->where('deleted', '0')
                ->get();

            $seniorhightorifexists =  DB::table('sf10_student_sh')
                ->where('studid',$studid)
                ->get();
                
            $tor = array();

            if(count($seniorhightorifexists) == 0){


            }else{
                
                foreach($seniorhightorifexists as $seniortor){

                    $levelname = DB::table('gradelevel')
                        ->where('id',$seniortor->levelid)
                        ->first();

                    $firstsemester = array();

                    $schoolinfofirstsem = DB::table('sf10_student_sh_schoolbysem')
                        ->where('headerid',$seniortor->id)
                        ->where('semester','1')
                        ->get();

                    if(count($schoolinfofirstsem) > 0){

    
                        $grades = DB::table('sf10childgrades')
                            ->where('headerid',$seniortor->id)
                            ->where('acadprog','sh')
                            ->where('semester','1')
                            ->get();
    
                        $generalaverage = DB::table('sf10_generalaverage')
                            ->where('headerid',$seniortor->id)
                            ->where('acadprog','sh')
                            ->where('deleted','0')
                            ->where('semester','1')
                            ->get();
                            
                        // if()
                        array_push($firstsemester, (object) array(
                            'schoolinfo'    => $schoolinfofirstsem,
                            'grades'        => $grades,
                            'generalaverage'=> $generalaverage
                        ));
                        
                    }

                    $secondsemester = array();
    
                    $schoolinfosecondsem = DB::table('sf10_student_sh_schoolbysem')
                        ->where('headerid',$seniortor->id)
                        ->where('semester','2')
                        ->get();
    
                    if(count($schoolinfosecondsem) > 0){
    
    
                        $grades = DB::table('sf10childgrades')
                            ->where('headerid',$seniortor->id)
                            ->where('acadprog','sh')
                            ->where('semester','2')
                            ->get();
    
                        $generalaverage = DB::table('sf10_generalaverage')
                            ->where('headerid',$seniortor->id)
                            ->where('acadprog','sh')
                            ->where('deleted','0')
                            ->where('semester','2')
                            ->get();
                            
                        // if()
                        array_push($secondsemester, (object) array(
                            'schoolinfo'    => $schoolinfosecondsem,
                            'grades'        => $grades,
                            'generalaverage'=> $generalaverage
                        ));
                        // array_push($tor, (object) array(
                        //     'schoolinfo'    => $schoolinfofirstsem,
                        //     'schoolyear'    => $seniortor,
                        //     'levelname'     => $levelname,
                        //     'grades'        => $grades,
                        //     'generalaverage'=> $generalaverage
                        // ));
                    }
                    
                    array_push($tor, (object) array(
                        'schoolyear'    => $seniortor,
                        'levelname'     => $levelname,
                        'firstsem'      => $firstsemester,
                        'secondsem'     => $secondsemester,
                    ));
                }

            }
            // return $tor;
            if($request->get('action') == 'print'){
                // return $studinfo;
                $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_senior',compact('eligibility','studinfo','transfergradesArray','gradesArray','completed','failingsubjectsArray','currentlevelid','schoolinformation','tor'))->setPaper('8.5x11','portrait'); 
                return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');
            }
            
            return view("registrar.studentsform10seniorpreview")
                ->with('gradelevels', $gradelevels)
                ->with('eligibility', $eligibility)
                ->with('academicprogram', $request->get('academicprogram'))
                ->with('studentdata', $studinfo)
                ->with('transferrecords', $transfergradesArray)
                ->with('records', $gradesArray)
                ->with('completed', $completed)
                ->with('failingsubjects', $failingsubjectsArray)
                ->with('failedlevelid', $currentlevelid)
                ->with('torrecords', $tor);
        }
        elseif($action == 'eligibility'){
            // return $request->all();
            $eligibility = Db::table('sf10eligibility')
                ->where('studid', $request->get('studid'))
                ->where('acadprogid', '5')
                ->where('deleted', '0')
                ->get();
            if(count($eligibility) == 0){
                DB::table('sf10eligibility')
                    ->insert([
                        'studid' => $request->get('studid'),
                        'completer' => $request->get('completer'),
                        'gen_ave' => $request->get('gen_ave'),
                        'completion_date' => $request->get('graduation_date'),
                        'schoolname' => $request->get('schoolname'),
                        'schooladdress' => $request->get('schooladdress'),
                        'passer' => $request->get('passer'),
                        'rating' => $request->get('rating'),
                        'exam_date' => $request->get('exam_date'),
                        'learning_center_name' => $request->get('center_name'),
                        'learning_center_address' => $request->get('center_address'),
                        'acadprogid' => '5'
                    ]);
            }
            else{
                Db::update('update sf10eligibility set completer = ?, gen_ave = ?, completion_date = ?, schoolname = ?, schooladdress = ?, passer = ?, rating = ?, exam_date = ?, learning_center_name = ?, learning_center_address = ? where studid = ? and acadprogid = ?',[$request->get('completer'),$request->get('gen_ave'), $request->get('graduation_date'),$request->get('schoolname'),$request->get('schooladdress'),$request->get('passer'),$request->get('rating'),$request->get('exam_date'),$request->get('center_name'),$request->get('center_address'),$request->get('studid'),'5']);
            }
            return back();
        }
    }
    public function addform10(Request $request){
        // return $request->all();
        
        $student_id             = $request->get('studentid');
        $gradelevelid           = $request->get('gradelevelid');
        $schoolname             = $request->get('school');
        $schoolid             = $request->get('schoolid');
        $district             = $request->get('district');
        $division             = $request->get('division');
        $region             = $request->get('region');
        $section             = $request->get('section');
        $schoolyear_from        = $request->get('schoolyear_from');
        $schoolyear_to          = $request->get('schoolyear_to');
        $numUnits               = $request->get('numUnits');
        $numYears               = $request->get('numYears');
        $newRecord              = array();
        $numDays                = array((object)[]);
        $numDaysPresent         = array((object)[]);
        $numDaysAbsent          = array((object)[]);
        $gradesArray            = array();
        $countNumDays           = 0;
        $countNumDaysPresent    = 0;
        $countNumDaysAbsent     = 0;

        if($request->get('semester') == 'first'){
            $semester           = 1;
        }else{
            $semester           = 2;
        }
        
        
        // return $re
        
        $checkifgradelevelexists    = DB::table('sf10_student_sh')
                                        ->where('studid', $student_id)
                                        ->where('levelid',$gradelevelid)
                                        ->get();
                                        
        if(count($checkifgradelevelexists) == 0){

            DB::table('sf10_student_sh')
                ->insert([
                    'studid'            => $student_id,
                    'levelid'           => $gradelevelid,
                    'schoolyear'        => $schoolyear_from.'-'.$schoolyear_to
                ]);
                
            $checkifgradelevelexists    = DB::table('sf10_student_sh')
                                        ->where('studid', $student_id)
                                        ->where('levelid',$gradelevelid)
                                        ->get();
        

        }

        $checkifschoolsexists           = DB::table('sf10_student_sh_schoolbysem')
                                        ->where('headerid',$checkifgradelevelexists[0]->id)
                                        ->where('semester',$semester)
                                        ->get();  

        if(count($checkifschoolsexists) == 0){

            DB::table('sf10_student_sh_schoolbysem')
                ->insert([
                    'headerid'           => $checkifgradelevelexists[0]->id,
                    'schoolid'           => $request->get('schoolid'),
                    'schoolname'         => $request->get('school'),
                    'schooladdress'      => $request->get('schooladdress'),
                    'district'           => $request->get('district'),
                    'division'           => $request->get('division'),
                    'region'             => $request->get('region'),
                    'sectionname'        => $request->get('section'),
                    'adviser'            => $request->get('teacher'),
                    'track'             => $request->get('track'),
                    'strand'            => $request->get('strand'),
                    'semester'           => $semester
                ]);
                
        $checkifschoolsexists           = DB::table('sf10_student_sh_schoolbysem')
                                        ->where('headerid',$checkifgradelevelexists[0]->id)
                                        ->where('semester',$semester)
                                        ->get();  
        

        }

        $checkifgradesexists        = DB::table('sf10childgrades')
                                        ->where('studid', $student_id)
                                        ->where('headerid',$checkifgradelevelexists[0]->id)
                                        ->where('semester',$semester)
                                        ->where('acadprog','sh')
                                        ->get();  
                                        
        if(count($checkifgradesexists) == 0){
            
            foreach($request->except(
                '_token',
                'school',
                'schoolid',
                'district',
                'division',
                'region',
                'gradelevelid',
                'section',
                'schoolyear_from',
                'schoolyear_to',
                'track',
                'strand',
                'teacher',
                'semester',
                'levelid',
                'sectionid',
                'studentid',
                'academicprogram',
                'semester',
                'firstGen'
                ) as $grade){

                DB::table('sf10childgrades')
                    ->insert([
                        'headerid'          =>  $checkifgradelevelexists[0]->id,
                        'studid'            =>  $student_id,
                        'core'              =>  $grade[0],
                        'subj_desc'         =>  $grade[1],
                        'quarter1'          =>  $grade[2],
                        'quarter2'          =>  $grade[3],
                        'finalrating'       =>  $grade[4],
                        'action'            =>  $grade[5],
                        'semester'          =>  $semester,
                        'acadprog'          =>  'sh'
                    ]);

            }

            DB::table('sf10_generalaverage')
                ->insert([
                    'acadprog'          =>  'sh',
                    'headerid'          =>  $checkifgradelevelexists[0]->id,
                    'genave'            =>  $request->get('firstGen')[0],
                    'semester'          =>  $semester
                ]);
                
        }
        
        return back();
    }
    public function editform10($id,Request $request){
        
        // return $request->all();
        
        $studid = $request->get('student_id');
        $recordid = $request->get('recordid');
        $semester = $request->get('semester');
        
        if($id == 'edit'){

            $getrecord = DB::table('sf10_student_sh')
                        ->select(
                            'sf10_student_sh.id',
                            'sf10_student_sh.id as recordschoolid',
                            'sf10_student_sh_schoolbysem.schoolid',
                            'sf10_student_sh_schoolbysem.schoolname',
                            'sf10_student_sh_schoolbysem.schooladdress',
                            'sf10_student_sh_schoolbysem.sectionname',
                            'sf10_student_sh_schoolbysem.adviser',
                            'sf10_student_sh_schoolbysem.semester'
                        )
                        ->leftJoin('sf10_student_sh_schoolbysem','sf10_student_sh.id','=','sf10_student_sh_schoolbysem.headerid')
                        ->where('sf10_student_sh.id', $recordid)
                        ->where('sf10_student_sh_schoolbysem.semester', $semester)
                        ->get();
                        
            $getgrades = DB::table('sf10childgrades')   
                        ->where('headerid',$recordid)
                        ->where('studid',$studid)
                        ->where('acadprog','sh')
                        ->where('semester',$semester)
                        ->get();

            $getgeneralaverage = DB::table('sf10_generalaverage')
                        ->where('headerid',$recordid)
                        ->where('semester',$semester)
                        ->where('acadprog','sh')
                        ->get();
                        
            return view('registrar.studentsform10senioredit')
                        ->with('schoolinfo',$getrecord)
                        ->with('grades',$getgrades)
                        ->with('genave',$getgeneralaverage)
                        ->with('studid',$studid)
                        ->with('recordid',$recordid)
                        ->with('semester',$semester);
        }
        elseif($id == 'savechanges'){

            
            foreach($request->except(
                '_token',
                'student_id',
                'recordid',
                'semester',
                'genAve',
                'recordschoolid',
                'schoolid',
                'schoolname',
                'schooladdress',
                'section',
                'adviser'
                ) as $key=>$gradesbysubject){

                if (strpos($key, 'old') !== false) {
                    DB::table('sf10childgrades')
                        ->where('id',$gradesbysubject[0])
                        ->where('headerid',$recordid)
                        ->where('semester',$request->get('semester'))
                        ->where('acadprog','sh')
                        ->update([
                            'core'          =>  $gradesbysubject[1],
                            'subj_desc'     =>  $gradesbysubject[2],
                            'quarter1'      =>  $gradesbysubject[3],
                            'quarter2'      =>  $gradesbysubject[4],
                            'finalrating'   =>  $gradesbysubject[5],
                            'action'        =>  $gradesbysubject[6]
                        ]);
                }
                if (strpos($key, 'new') !== false) {
                    
                    DB::table('sf10childgrades')
                        ->insert([
                            'headerid'      =>  $recordid,
                            'studid'        =>  $request->get('student_id'),
                            'core'          =>  $gradesbysubject[0],
                            'subj_desc'     =>  $gradesbysubject[1],
                            'quarter1'      =>  $gradesbysubject[2],
                            'quarter2'      =>  $gradesbysubject[3],
                            'finalrating'   =>  $gradesbysubject[4],
                            'action'        =>  $gradesbysubject[5],
                            'semester'      =>  $request->get('semester'),
                            'acadprog'      =>  'sh'
                        ]);
                }

            }

            $checkifgenaveexists = DB::table('sf10_generalaverage')
                ->where('id', $request->get('genAve')[0])
                ->where('headerid', $recordid)
                ->where('semester', $request->get('semester'))
                ->where('acadprog','sh')
                ->get();

            if(count($checkifgenaveexists) == 0){

                DB::table('sf10_generalaverage')
                    ->insert([
                        'headerid'  => $recordid,
                        'genave'    => $request->get('genAve')[1],
                        'semester'  => $request->get('semester'),
                        'acadprog'  =>'sh'
                    ]);

            }else{

                DB::table('sf10_generalaverage')
                    ->where('id', $request->get('genAve')[0])
                    ->where('headerid', $recordid)
                    ->where('semester', $request->get('semester'))
                    ->update([
                        'genave'    => $request->get('genAve')[1]
                    ]);
            }
            $checkifgenaveexists = DB::table('sf10_student_sh_schoolbysem')
                ->where('headerid', $recordid)
                ->where('semester', $request->get('semester'))
                ->get();

            if(count($checkifgenaveexists) == 0){

                DB::table('sf10_student_sh_schoolbysem')
                ->insert([
                    'headerid'          =>  $recordid,
                    'schoolid'          =>  $request->get('schoolid'),
                    'schoolname'        =>  $request->get('schoolname'),
                    'schooladdress'     =>  $request->get('schooladdress'),
                    'sectionname'       =>  $request->get('section'),
                    'adviser'           =>  $request->get('adviser'),
                    'semester'           =>  $request->get('semester')
                ]);

            }else{

                DB::table('sf10_student_sh_schoolbysem')
                ->where('headerid',$recordid)
                ->where('semester',$request->get('semester'))
                ->update([
                    'schoolid'          =>  $request->get('schoolid'),
                    'schoolname'        =>  $request->get('schoolname'),
                    'schooladdress'     =>  $request->get('schooladdress'),
                    'sectionname'       =>  $request->get('section'),
                    'adviser'           =>  $request->get('adviser')
                ]);
                
            }
            


            return back();

        }
    }
}
