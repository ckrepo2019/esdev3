<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\GenerateGrade;
class JuniorHighController extends Controller
{
    public function form10($action, Request $request){
        
        // return 'asd';
        if($action == 'dashboard'){

            $gradelevels = DB::table('gradelevel')
                ->select(
                    'gradelevel.id',
                    'gradelevel.levelname'
                )
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('academicprogram.acadprogcode','HS')
                ->where('gradelevel.deleted','0')
                ->get();

            $currentschoolyear = Db::table('sy')
                ->where('isactive','1')
                ->first();

            $studid = $request->get('studid');
            
            $studinfo = Db::table('studinfo')
                ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.lrn','studinfo.dob','studinfo.gender','studinfo.levelid','gradelevel.levelname')
                ->join('gradelevel','studinfo.levelid','gradelevel.id')
                ->where('studinfo.id',$studid)
                ->first();

            $schoolyears = DB::table('enrolledstud')
                                ->select('enrolledstud.syid','sy.sydesc','enrolledstud.levelid','gradelevel.levelname','enrolledstud.sectionid','sections.sectionname as section')
                                ->join('gradelevel','enrolledstud.levelid','gradelevel.id')
                                ->join('sy','enrolledstud.syid','sy.id')
                                ->join('sections','enrolledstud.sectionid','sections.id')
                                ->where('enrolledstud.deleted','0')
                                ->where('enrolledstud.studid',$studid)
                                ->distinct()
                                ->orderByDesc('enrolledstud.levelid')
                                ->get();
                




            if(count($schoolyears) != 0){
            
                $currentlevelid = (object)array(
                    'syid' => $schoolyears[0]->syid,
                    'levelid' => $schoolyears[0]->levelid,
                    'levelname' => $schoolyears[0]->levelname
                );

            }
            else{

                $currentlevelid = (object)array(
                    'syid' => $currentschoolyear->id,
                    'levelid' => $studinfo->levelid,
                    'levelname' => $studinfo->levelname
                );


            }

            $failingsubjectsArray = array();

            $gradelevelsenrolled = array();

            $gradesArray = array();

            foreach($schoolyears as $sy){
    
                array_push($gradelevelsenrolled,(object)array(
                    'levelid' => $sy->levelid,
                    'levelname' => $sy->levelname
                ));

                $getsubjects = Db::table('assignsubj')
                    ->select('subjects.id','subjects.subjcode','subjects.subjdesc as subjtitle')
                    ->join('assignsubjdetail','assignsubj.id','assignsubjdetail.headerid')
                    ->join('subjects','assignsubjdetail.subjid','subjects.id')
                    ->where('assignsubj.syid',$sy->syid)
                    ->where('assignsubj.glevelid',$sy->levelid)
                    ->where('assignsubj.sectionid',$sy->sectionid)
                    ->distinct()
                    ->get();
                    
                $grades = array();
                
                $summerArray = array();
                
                foreach($getsubjects as $subjects){

                    $quarter1 = 0;

                    $quarter2 = 0;

                    $quarter3 = 0;

                    $quarter4 = 0;

                    $getgrades = Db::table('grades')
                        ->select('grades.quarter','gradesdetail.qg')
                        ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                        ->where('grades.syid',$sy->syid)
                        ->where('grades.levelid',$sy->levelid)
                        ->where('grades.sectionid',$sy->sectionid)
                        ->where('grades.subjid',$subjects->id)
                        ->where('gradesdetail.studid',$studid)
                        ->distinct()
                        ->get();
                        
                    foreach ($getgrades as $value) {

                        if($value->quarter == 1){

                            $quarter1+=$value->qg;

                        }

                        if($value->quarter == 2){

                            $quarter2+=$value->qg;

                        }

                        if($value->quarter == 3){

                            $quarter3+=$value->qg;

                        }

                        if($value->quarter == 4){

                            $quarter4+=$value->qg;

                        }

                    }

                    $qg = ($quarter1 + $quarter2 + $quarter3 + $quarter4) / 4;
                    
                    if($qg>75){

                        $remarks = "PASSED";

                    }else{

                        $remarks = "FAILED";

                    }

                    array_push($grades,(object)array(
                        'subjcode' => $subjects->subjcode,
                        'subjtitle' => $subjects->subjtitle,
                            'quarter1' => $quarter1,
                            'quarter2' => $quarter2,
                            'quarter3' => $quarter3,
                            'quarter4' => $quarter4,
                            'finalrating' => $qg,
                            'remarks' => $remarks
                    ));
                    
                    
                    $summer = Db::table('gradesspclass')
                        ->select('subjects.subjcode','subjects.subjdesc as subjtitle','gradesspclass.qg')
                        ->join('assignsubjdetail','gradesspclass.subjid','assignsubjdetail.id')
                        ->join('subjects','assignsubjdetail.subjid','subjects.id')
                        ->join('assignsubj','assignsubjdetail.subjid','assignsubj.id')
                        ->where('gradesspclass.syid',$sy->syid)
                        ->where('gradesspclass.levelid',$sy->levelid)
                        ->where('gradesspclass.studid',$studid)
                        ->where('gradesspclass.subjid',$subjects->id)
                        ->where('assignsubj.syid',$sy->syid)
                        ->where('assignsubj.glevelid',$sy->levelid)
                        ->where('assignsubjdetail.subjid',$subjects->id)
                        ->distinct()
                        ->get();
                        
                    if(count($summer)!=0){

                        array_push($summerArray,$summer);

                    }

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
                $schoolinfo = Db::table('sf10_student_jh')
                    ->where('studid',$studid)
                    ->where('levelid',$sy->levelid)
                    ->get();

                if(count($schoolinfo) == 0){

                    $schoolinformation = Db::table('schoolinfo')
                        ->first();
                }else{

                    $schoolinformation = Db::table('sf10_schoollist')
                        ->where('id', $schoolinfo[0]->id)
                        ->first();
                }

                $getTeacher = Db::table('sectiondetail')
                    ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                    ->join('teacher','sectiondetail.teacherid','teacher.id')
                    ->where('sectiondetail.sectionid',$sy->sectionid)
                    ->where('sectiondetail.syid',$sy->syid)
                    ->where('sectiondetail.deleted','0')
                    ->get();
                    
                array_push($gradesArray, (object) array(
                        'gradedetails' => $sy,
                        'teacher' => $getTeacher,
                        'schoolinformation' => $schoolinformation,
                        'grades' => $grades,
                        'summer' => $summerArray
                ));

            }

            if(count(collect($gradelevelsenrolled)->unique()) == 2){

                $completed = 1;

            }

            elseif(count(collect($gradelevelsenrolled)->unique()) < 2){

                $completed = 0;

            }

            $transfergradesArray = array();

            $transferrecord = Db::table('sf10_student_jh')
                ->select('sf10_student_jh.id')
                ->join('sf10_schoollist','sf10_student_jh.sf10_schoolid','=','sf10_student_jh.id')
                ->where('sf10_student_jh.studid',$studid)
                ->where('sf10_student_jh.deleted','0')
                ->get();

            foreach($transferrecord as $record){

                $transfergrades = Db::table('sf10childgrades')
                    ->where('sf10childgrades.headerid',$record->id)
                    ->get();

                array_push($transfergradesArray,$transfergrades);

            }
            
            $sh_subjects = Db::table('sh_subjects')
                ->select('id','subjtitle')
                ->where('deleted','0')
                ->distinct()
                ->get();

            $eligibility = Db::table('sf10eligibility')
                ->where('studid', $studid)
                ->where('acadprogid', '4')
                ->where('deleted', '0')
                ->get();
                
            $juniorhightorifexists =  DB::table('sf10_student_jh')
                ->where('studid',$studid)
                ->where('sf10_student_jh.deleted','0')
                ->get();
                
            $tor = array();

            if(count($juniorhightorifexists) == 0){


            }else{
                
                foreach($juniorhightorifexists as $juniortor){

                    $schoolinfo = DB::table('sf10_schoollist')
                        ->where('id',$juniortor->sf10_schoolid)
                        ->first();


                    $levelname = DB::table('gradelevel')
                        ->where('id',$juniortor->levelid)
                        ->first();

                    $grades = DB::table('sf10childgrades')
                        ->where('headerid',$juniortor->id)
                        ->where('acadprog','js')
                        ->get();

                    $generalaverage = DB::table('sf10_generalaverage')
                        ->where('headerid',$juniortor->id)
                        ->where('acadprog','js')
                        ->where('deleted','0')
                        ->get();

                    array_push($tor, (object) array(
                        'schoolinfo'    => $schoolinfo,
                        'schoolyear'    => $juniortor,
                        'levelname'     => $levelname,
                        'grades'        => $grades,
                        'generalaverage'=> $generalaverage
                    ));
                }

            }
                
            if($request->get('action') == 'print'){

                $schoolinformation = DB::table('schoolinfo')
                    ->first();
                    
                $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_junior',compact('eligibility','studinfo','transfergradesArray','gradesArray','completed','failingsubjectsArray','currentlevelid','schoolinformation','tor'))->setPaper('8.5x11','portrait'); 
                return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');

            }

            // return $eligibility;
            return view("registrar.studentsform10juniorpreview")
                ->with('eligibility', $eligibility)
                ->with('academicprogram', $request->get('academicprogram'))
                ->with('studentdata', $studinfo)
                ->with('transferrecords', $transfergradesArray)
                ->with('records', $gradesArray)
                ->with('gradelevels', $gradelevels)
                ->with('completed', $completed)
                ->with('failingsubjects', $failingsubjectsArray)
                ->with('failedlevelid', $currentlevelid)
                ->with('torrecords', $tor);

        }
        elseif($action == 'eligibility'){
            
            $eligibility = Db::table('sf10eligibility')
                ->where('studid', $request->get('studentid'))
                ->where('acadprogid', '4')
                ->where('deleted', '0')
                ->get();

            if(count($eligibility) == 0){

                DB::table('sf10eligibility')
                    ->insert([
                        'studid' => $request->get('studentid'),
                        'completer' => $request->get('completer'),
                        'gen_ave' => $request->get('gen_ave'),
                        'citation' => $request->get('citation'),
                        'completion_date' => $request->get('graduation_date'),
                        'schoolname' => $request->get('schoolname'),
                        'schooladdress' => $request->get('schooladdress'),
                        'passer' => $request->get('passer'),
                        'rating' => $request->get('rating'),
                        'exam_date' => $request->get('exam_date'),
                        'learning_center_name' => $request->get('center_name'),
                        'learning_center_address' => $request->get('center_address'),
                        'acadprogid' => '4'
                    ]);

            }
            else{
                
                Db::update('update sf10eligibility set completer = ?, gen_ave = ?, citation = ?, completion_date = ?, schoolname = ?, schooladdress = ?, passer = ?, rating = ?, exam_date = ?, learning_center_name = ?, learning_center_address = ? where studid = ? and acadprogid = ?',[$request->get('completer'),$request->get('gen_ave'), $request->get('citation'), $request->get('graduation_date'),$request->get('schoolname'),$request->get('schooladdress'),$request->get('passer'),$request->get('rating'),$request->get('exam_date'),$request->get('center_name'),$request->get('center_address'),$request->get('studentid'),'4']);
                
            }
            return back();
        }
    }
    public function addform10(Request $request){

        
        $student_id             = $request->get('studentid');
        $gradelevelid           = $request->get('gradelevelid');
        $schoolname             = $request->get('schoolname');
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

        $checkschoolifexists    = DB::table('sf10_schoollist')
                                    ->where('schoolname','like','%'.$schoolname)
                                    ->get();
                                    
        if(count($checkschoolifexists) == 0){

            DB::table('sf10_schoollist')
                ->insert([
                    'schoolid'      => '',
                    'schoolname'    => strtoupper($schoolname)
                ]);
                
            $checkschoolifexists    = DB::table('sf10_schoollist')
                                        ->where('schoolname','like','%'.$schoolname)
                                        ->get();
        

        }
        
        $checkifgradelevelexists    = DB::table('sf10_student_jh')
                                        ->where('studid', $student_id)
                                        ->where('sf10_schoolid',$checkschoolifexists[0]->id)
                                        ->where('levelid',$gradelevelid)
                                        ->where('deleted','0')
                                        ->get();
                                        
        if(count($checkifgradelevelexists) == 0){

            DB::table('sf10_student_jh')
                ->insert([
                    'studid'            => $student_id,
                    'sf10_schoolid'     => $checkschoolifexists[0]->id,
                    'levelid'           => $gradelevelid,
                    'schoolyear'        => $schoolyear_from.'-'.$schoolyear_to,
                    'adviser'           => ''
                ]);
                
            $checkifgradelevelexists    = DB::table('sf10_student_jh')
                                        ->where('studid', $student_id)
                                        ->where('sf10_schoolid',$checkschoolifexists[0]->id)
                                        ->where('levelid',$gradelevelid)
                                        ->where('deleted','0')
                                        ->get();
        

        }else{


            return redirect()->back()->withInput()->with("message", 'Data already exist!');

        }
        

        $checkifgradesexists        = DB::table('sf10childgrades')
                                        ->where('studid', $student_id)
                                        ->where('headerid',$checkifgradelevelexists[0]->id)
                                        ->where('acadprog','js')
                                        ->get();  
                                        
        if(count($checkifgradesexists) == 0){
            
            foreach($request->except('_token','school','schoolid','district','division','region','gradelevelid','section','schoolyear_from','schoolyear_to','teacher','studentid','academicprogram','entryGen') as $grade){

                DB::table('sf10childgrades')
                    ->insert([
                        'headerid'          =>  $checkifgradelevelexists[0]->id,
                        'studid'            =>  $student_id,
                        'subj_desc'         =>  $grade[0],
                        'quarter1'          =>  $grade[1],
                        'quarter2'          =>  $grade[2],
                        'quarter3'          =>  $grade[3],
                        'quarter4'          =>  $grade[4],
                        'finalrating'       =>  $grade[5],
                        'action'            =>  $grade[6],
                        'acadprog'          =>  'js'
                    ]);

            }

            DB::table('sf10_generalaverage')
                ->insert([
                    'acadprog'          =>  'js',
                    'headerid'          =>  $checkifgradelevelexists[0]->id,
                    'genave'            =>  $request->get('entryGen')[0]
                ]);
                
        $checkifgradesexists            = DB::table('sf10childgrades')
                                            ->where('studid', $student_id)
                                            ->where('headerid',$checkifgradelevelexists[0]->id)
                                            ->where('acadprog','js')
                                            ->get();  
        

        }
        return back();

    }
    public function editform10($id,Request $request){
        // return $request->all();
        
        $studid = $request->get('student_id');
        $recordid = $request->get('recordid');

        if($id == 'edit'){

            $getrecord = DB::table('sf10_student_jh')
                        ->select(
                            'sf10_student_jh.id',
                            'sf10_schoollist.id as recordschoolid',
                            'sf10_schoollist.schoolid',
                            'sf10_schoollist.schoolname',
                            'sf10_schoollist.schooladdress',
                            'sf10_student_jh.sectionname',
                            'sf10_student_jh.adviser'
                        )
                        ->join('sf10_schoollist','sf10_student_jh.sf10_schoolid','=','sf10_schoollist.id')
                        ->where('sf10_student_jh.id', $recordid)
                        ->get();
                        
            $getgrades = DB::table('sf10childgrades')   
                        ->where('headerid',$getrecord[0]->id)
                        ->where('studid',$studid)
                        ->where('acadprog','js')
                        ->get();

            $getgeneralaverage = DB::table('sf10_generalaverage')
                        ->where('headerid',$getrecord[0]->id)
                        ->where('acadprog','js')
                        ->get();

            return view('registrar.studentsform10junioredit')
                        ->with('schoolinfo',$getrecord)
                        ->with('grades',$getgrades)
                        ->with('genave',$getgeneralaverage)
                        ->with('studid',$studid)
                        ->with('recordid',$recordid);
        }
        elseif($id == 'savechanges'){

            // return $request->all();
            foreach($request->except(
                '_token',
                'student_id',
                'recordid',
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
                        ->where('acadprog','js')
                        ->update([
                            'subj_desc'     =>  $gradesbysubject[1],
                            'quarter1'      =>  $gradesbysubject[2],
                            'quarter2'      =>  $gradesbysubject[3],
                            'quarter3'      =>  $gradesbysubject[4],
                            'quarter4'      =>  $gradesbysubject[5],
                            'finalrating'   =>  $gradesbysubject[6],
                            'action'        =>  $gradesbysubject[7]
                        ]);
                }
                if (strpos($key, 'new') !== false) {

                    DB::table('sf10childgrades')
                        ->insert([
                            'headerid'      =>  $recordid,
                            'studid'        =>  $studid,
                            'subj_desc'     =>  $gradesbysubject[0],
                            'quarter1'      =>  $gradesbysubject[1],
                            'quarter2'      =>  $gradesbysubject[2],
                            'quarter3'      =>  $gradesbysubject[3],
                            'quarter4'      =>  $gradesbysubject[4],
                            'finalrating'   =>  $gradesbysubject[5],
                            'action'        =>  $gradesbysubject[6],
                            'acadprog'      =>  'js'
                        ]);
                }

            }

            $checkifgenaveexists = DB::table('sf10_generalaverage')
                ->where('id', $request->get('genAve')[0])
                ->where('headerid', $recordid)
                ->where('acadprog','js')
                ->get();

            if(count($checkifgenaveexists) == 0){

                DB::table('sf10_generalaverage')
                    ->insert([
                        'headerid'  => $recordid,
                        'genave'    => $request->get('genAve')[1],
                        'acadprog'  =>'js'
                    ]);

            }else{

                DB::table('sf10_generalaverage')
                    ->where('id', $request->get('genAve')[0])
                    ->where('headerid', $recordid)
                    ->update([
                        'genave'    => $request->get('genAve')[1]
                    ]);
            }

            DB::table('sf10_schoollist')
                    ->where('id',$request->get('recordschoolid'))
                    ->update([
                        'schoolid'          =>  $request->get('schoolid'),
                        'schoolname'        =>  $request->get('schoolname'),
                        'schooladdress'     =>  $request->get('schooladdress')
                    ]);

            DB::table('sf10_student_jh')
                    ->where('id',$recordid)
                    ->update([
                        'sectionname'       =>  $request->get('section'),
                        'adviser'       =>  $request->get('adviser'),
                    ]);

            return back();

        }
    }
    public function deleteform10(Request $request)
    {
        // return $request->all();
        DB::table('sf10_student_jh')
            ->where('studid', $request->get('studentid'))
            ->where('id', $request->get('headerid'))
            ->update([
                'deleted'   => 1
            ]);
            return back();
    }
}
