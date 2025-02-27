<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\AttendanceReport;
use App\GenerateGrade;
class ElementaryController extends Controller
{
    public function form10($action, Request $request){
        
        // return $a
        if($action == 'dashboard'){
            
            $gradelevels = DB::table('gradelevel')
                ->select(
                    'gradelevel.id',
                    'gradelevel.levelname'
                )
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('academicprogram.acadprogcode','ELEM')
                ->where('gradelevel.deleted','0')
                ->get();

            $currentschoolyear = Db::table('sy')
                ->where('isactive','1')
                ->first();

            $studid = $request->get('studid');

            $school = DB::table('schoolinfo')
                ->first();
                

            $studinfo = Db::table('studinfo')
                            ->select(
                                'studinfo.id',
                                'studinfo.firstname',
                                'studinfo.middlename',
                                'studinfo.lastname',
                                'studinfo.suffix',
                                'studinfo.lrn',
                                'studinfo.dob',
                                'studinfo.gender',
                                'studinfo.levelid',
                                'studinfo.street',
                                'studinfo.barangay',
                                'studinfo.city',
                                'studinfo.province',
                                'studinfo.mothername',
                                'studinfo.moccupation',
                                'studinfo.fathername',
                                'studinfo.foccupation',
                                'studinfo.guardianname',
                                'gradelevel.levelname'
                                )
                            ->leftJoin('gradelevel','studinfo.levelid','gradelevel.id')
                            ->where('studinfo.id',$studid)
                            ->first();


            $studaddress = '';

            if($studinfo->street!=null)
            {
                $studaddress.=$studinfo->street.', ';
            }
            if($studinfo->barangay!=null)
            {
                $studaddress.=$studinfo->barangay.', ';
            }
            if($studinfo->city!=null)
            {
                $studaddress.=$studinfo->city.', ';
            }
            if($studinfo->province!=null)
            {
                $studaddress.=$studinfo->province.', ';
            }

            $studinfo->address = substr($studaddress,0,-2);

           
            $schoolyears = DB::table('enrolledstud')
                ->select(
                    'enrolledstud.id',
                    'enrolledstud.syid',
                    'sy.sydesc',
                    'enrolledstud.levelid',
                    'gradelevel.levelname',
                    'enrolledstud.sectionid',
                    'sections.sectionname as section'
                    )
                ->join('gradelevel','enrolledstud.levelid','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
                ->join('sy','enrolledstud.syid','sy.id')
                ->join('sections','enrolledstud.sectionid','sections.id')
                ->where('enrolledstud.deleted','0')
                ->where('academicprogram.id','3')
                ->where('enrolledstud.studid',$studid)
                ->distinct()
                ->orderByDesc('enrolledstud.levelid')
                ->get();
                
            if(count($schoolyears) != 0){
                
                $currentlevelid = (object)array(
                    'syid'      => $schoolyears[0]->syid,
                    'levelid'   => $schoolyears[0]->levelid,
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
                    ->select(
                        'subjects.id',
                        'subjects.subjcode',
                        'subjects.subjdesc as subjtitle'
                        )
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
                        ->select(
                            'grades.quarter',
                            'gradesdetail.qg'
                            )
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
                        'subjcode'      => $subjects->subjcode,
                        'subjtitle'     => $subjects->subjtitle,
                        'quarter1'      => $quarter1,
                        'quarter2'      => $quarter2,
                        'quarter3'      => $quarter3,
                        'quarter4'      => $quarter4,
                        'finalrating'   => $qg,
                        'remarks'   => $remarks
                    ));
                    
                    
                    $summer = Db::table('gradesspclass')
                        ->select(
                            'subjects.subjcode',
                            'subjects.subjdesc as subjtitle',
                            'gradesspclass.qg'
                            )
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
                                'id'        => $subjects->id,
                                'subjcode'  => $subjects->subjcode,
                                'subjtitle' => $subjects->subjtitle,
                                'grade'     => $qg,
                                'levelid'   => $sy->levelid
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
                }

                $getTeacher = Db::table('sectiondetail')
                    ->select(
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix'
                        )
                    ->join('teacher','sectiondetail.teacherid','teacher.id')
                    ->where('sectiondetail.sectionid',$sy->sectionid)
                    ->where('sectiondetail.syid',$sy->syid)
                    ->where('sectiondetail.deleted','0')
                    ->get();
                    
                $attendance = AttendanceReport::schoolYearBasedAttendanceReport($sy);
                
                array_push($gradesArray, (object) array(
                        'gradedetails'      => $sy,
                        'attendance'        => $attendance[0]->monthly,
                        'teacher'           => $getTeacher,
                        'schoolinformation' => $schoolinformation,
                        'grades'            => $grades,
                        'summer'            => $summerArray
                ));


            }
            
            if(count(collect($gradelevelsenrolled)->unique()) == 2){

                $completed = 1;

            }

            elseif(count(collect($gradelevelsenrolled)->unique()) < 2){

                $completed = 0;

            }

            $transfergradesArray = array();

            $transferrecord = Db::table('sf10_student_elem')
                ->select('sf10_student_elem.id')
                ->join('sf10_schoollist','sf10_student_elem.sf10_schoolid','=','sf10_student_elem.id')
                ->where('sf10_student_elem.studid',$studid)
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
                

            $elementarytorifexists =  DB::table('sf10_student_elem')
                ->where('studid',$studid)
                ->where('deleted','0')
                ->get();
                
            $tor = array();

            if(count($elementarytorifexists) == 0){

            }else{
                
                
                foreach($elementarytorifexists as $elemtor){

                    $schoolinfo = DB::table('sf10_schoollist')
                        ->where('id',$elemtor->sf10_schoolid)
                        ->first();


                    $levelname = DB::table('gradelevel')
                        ->where('id',$elemtor->levelid)
                        ->first();

                    $grades = DB::table('sf10childgrades')
                        ->where('headerid',$elemtor->id)
                        ->where('acadprog','elem')
                        ->get();

                    $generalaverage = DB::table('sf10_generalaverage')
                        ->where('headerid',$elemtor->id)
                        ->where('acadprog','elem')
                        ->where('deleted','0')
                        ->get();

                    $attendance = DB::table('sf10childattendance')
                        ->where('headerid',$elemtor->id)
                        ->get();

                    array_push($tor, (object) array(
                        'schoolinfo'    => $schoolinfo,
                        'schoolyear'    => $elemtor,
                        'levelname'     => $levelname,
                        'grades'        => $grades,
                        'generalaverage'=> $generalaverage,
                        'attendance'    => $attendance
                    ));
                }

            }
            // return $tor;

            if($request->get('action') == 'print'){
                $pdf = PDF::loadview('registrar/pdf/pdf_schoolform10_elem',compact('eligibility','studinfo','transfergradesArray','gradesArray','completed','failingsubjectsArray','currentlevelid','schoolinformation','school','tor'))->setPaper('8.5x11','portrait'); 
                return $pdf->stream('School Form 10 - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf');

            }
            // return $tor;
            return view("registrar.studentsform10elempreview")
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
            
            $eligibility = Db::table('sf10eligibility')
                ->where('studid', $request->get('studentid'))
                ->where('acadprogid', '4')
                ->where('deleted', '0')
                ->get();

            if(count($eligibility) == 0){

                DB::table('sf10eligibility')
                    ->insert([
                        'studid'                    => $request->get('studentid'),
                        'completer'                 => $request->get('completer'),
                        'gen_ave'                   => $request->get('gen_ave'),
                        'citation'                  => $request->get('citation'),
                        'completion_date'           => $request->get('graduation_date'),
                        'schoolname'                => $request->get('schoolname'),
                        'schooladdress'             => $request->get('schooladdress'),
                        'passer'                    => $request->get('passer'),
                        'rating'                    => $request->get('rating'),
                        'exam_date'                 => $request->get('exam_date'),
                        'learning_center_name'      => $request->get('center_name'),
                        'learning_center_address'   => $request->get('center_address'),
                        'acadprogid'                => '4'
                    ]);

            }
            else{
                
                Db::update('update sf10eligibility set completer = ?, gen_ave = ?, citation = ?, completion_date = ?, schoolname = ?, schooladdress = ?, passer = ?, rating = ?, exam_date = ?, learning_center_name = ?, learning_center_address = ? where studid = ? and acadprogid = ?',[$request->get('completer'),$request->get('gen_ave'), $request->get('citation'), $request->get('graduation_date'),$request->get('schoolname'),$request->get('schooladdress'),$request->get('passer'),$request->get('rating'),$request->get('exam_date'),$request->get('center_name'),$request->get('center_address'),$request->get('studentid'),'4']);
                
            }
            return back();
        }
    }
    public function addform10(Request $request){

        // return $request->all();

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
        // return $gradelevelid;
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
        
        $checkifgradelevelexists    = DB::table('sf10_student_elem')
                                        ->where('studid', $student_id)
                                        ->where('sf10_schoolid',$checkschoolifexists[0]->id)
                                        ->where('levelid',$gradelevelid)
                                        ->where('deleted','0')
                                        ->get();
                                        
        if(count($checkifgradelevelexists) == 0){

            DB::table('sf10_student_elem')
                ->insert([
                    'studid'            => $student_id,
                    'sf10_schoolid'     => $checkschoolifexists[0]->id,
                    'levelid'           => $gradelevelid,
                    'schoolyear'        => $schoolyear_from.'-'.$schoolyear_to,
                    'adviser'           => '',
                    'unitsearned'       => $request->get('numUnits'),
                    'yearsinschool'     => $request->get('numYears')
                ]);
                
            $checkifgradelevelexists    = DB::table('sf10_student_elem')
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
                                        ->where('acadprog','elem')
                                        ->get();  
                                        
        if(count($checkifgradesexists) == 0){
            
            
                foreach ($request->except('_token','studentid','gradelevelid','schoolname','schoolyear_from','schoolyear_to','levelid','sectionid','numUnits','numYears','entryentryGen','gradelevelid','academicprogram') as $key => $part) {
                    if($key == 'schooldays'){
                        $keys = 0;
                        if($keys == 0){
                            $numDays[0]->Jun = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 1){
                            $numDays[0]->Jul = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 2){
                            $numDays[0]->Aug = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 3){
                            $numDays[0]->Sep = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 4){
                            $numDays[0]->Oct = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 5){
                            $numDays[0]->Nov = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 6){
                            $numDays[0]->Dec = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 7){
                            $numDays[0]->Jan = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 8){
                            $numDays[0]->Feb = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 9){
                            $numDays[0]->Mar = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 10){
                            $numDays[0]->Apr = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 11){
                            // return 'sdfsdf';
                            $numDays[0]->Total = $part[$keys];
                            // return $numDays[0]->Total;
                            $keys+=1;
                        }
                    }
                    elseif($key == 'dayspresent'){
                        $keys = 0;
                        if($keys == 0){
                            $numDaysPresent[0]->Jun = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 1){
                            $numDaysPresent[0]->Jul = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 2){
                            $numDaysPresent[0]->Aug = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 3){
                            $numDaysPresent[0]->Sep = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 4){
                            $numDaysPresent[0]->Oct = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 5){
                            $numDaysPresent[0]->Nov = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 6){
                            $numDaysPresent[0]->Dec = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 7){
                            $numDaysPresent[0]->Jan = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 8){
                            $numDaysPresent[0]->Feb = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 9){
                            $numDaysPresent[0]->Mar = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 10){
                            $numDaysPresent[0]->Apr = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 11){
                            $numDaysPresent[0]->Total = $part[$keys];
                            $keys+=1;
                        }
                    }
                    elseif($key == 'daysabsent'){
                        $keys = 0;
                        if($keys == 0){
                            $numDaysAbsent[0]->Jun = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 1){
                            $numDaysAbsent[0]->Jul = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 2){
                            $numDaysAbsent[0]->Aug = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 3){
                            $numDaysAbsent[0]->Sep = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 4){
                            $numDaysAbsent[0]->Oct = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 5){
                            $numDaysAbsent[0]->Nov = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 6){
                            $numDaysAbsent[0]->Dec = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 7){
                            $numDaysAbsent[0]->Jan = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 8){
                            $numDaysAbsent[0]->Feb = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 9){
                            $numDaysAbsent[0]->Mar = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 10){
                            $numDaysAbsent[0]->Apr = $part[$keys];
                            $keys+=1;
                        }
                        if($keys == 11){
                            $numDaysAbsent[0]->Total = $part[$keys];
                            $keys+=1;
                        }
                    }
                    else{
                        array_push($gradesArray,collect($part));
                    }
                    
                }
                array_push($newRecord, (object) array(
                    'grades'            => $gradesArray,
                    'genave'            => $request->get('entryentryGen')[0],
                    'numofdays'         => $numDays,
                    'numofdayspresent'  => $numDaysPresent,
                    'numofdaysabsent'   => $numDaysAbsent
                ));

                
                foreach($newRecord as $record){

                    foreach($record->grades as $grade){
                        DB::table('sf10childgrades')
                            ->insert([
                                'headerid'            => $checkifgradelevelexists[0]->id,
                                'subj_desc'           => $grade[0],
                                'quarter1'            => $grade[1],
                                'quarter2'            => $grade[2],
                                'quarter3'            => $grade[3],
                                'quarter4'            => $grade[4],
                                'finalrating'         => $grade[5],
                                'action'              => $grade[6],
                                'credits'             => $grade[7],
                                'acadprog'            => 'elem'
                            ]);
                        

                    }
                    DB::table('sf10_generalaverage')
                        ->insert([
                            'acadprog'          =>  'elem',
                            'headerid'          =>  $checkifgradelevelexists[0]->id,
                            'genave'            =>  $record->genave
                        ]);
                        
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Jun',
                            'numofschooldays'   => $record->numofdays[0]->Jun,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Jun,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Jun
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Jul',
                            'numofschooldays'   => $record->numofdays[0]->Jul,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Jul,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Jul
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Aug',
                            'numofschooldays'   => $record->numofdays[0]->Aug,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Aug,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Aug
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Sep',
                            'numofschooldays'   => $record->numofdays[0]->Sep,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Sep,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Sep
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Oct',
                            'numofschooldays'   => $record->numofdays[0]->Oct,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Oct,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Oct
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Nov',
                            'numofschooldays'   => $record->numofdays[0]->Nov,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Nov,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Nov
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Dec',
                            'numofschooldays'   => $record->numofdays[0]->Dec,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Dec,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Dec
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Jan',
                            'numofschooldays'   => $record->numofdays[0]->Jan,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Jan,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Jan
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Feb',
                            'numofschooldays'   => $record->numofdays[0]->Feb,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Feb,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Feb
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Mar',
                            'numofschooldays'   => $record->numofdays[0]->Mar,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Mar,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Mar
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Apr',
                            'numofschooldays'   => $record->numofdays[0]->Apr,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Apr,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Apr
                        ]);
                    DB::table('sf10childattendance')
                        ->insert([
                            'headerid'          => $checkifgradelevelexists[0]->id,
                            'month'             => 'Total',
                            'numofschooldays'   => $record->numofdays[0]->Total,
                            'numofdayspresent'  => $record->numofdayspresent[0]->Total,
                            'numofdaysabsent'   => $record->numofdaysabsent[0]->Total
                        ]);
                }



                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Jun',$newRecord[1][0]->Jun,$newRecord[2][0]->Jun,$newRecord[3][0]->Jun]);
                // //July
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Jul',$newRecord[1][0]->Jul,$newRecord[2][0]->Jul,$newRecord[3][0]->Jul]);
                // //August
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Aug',$newRecord[1][0]->Aug,$newRecord[2][0]->Aug,$newRecord[3][0]->Aug]);
                // //September
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Sep',$newRecord[1][0]->Sep,$newRecord[2][0]->Sep,$newRecord[3][0]->Sep]);
                // //October
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Oct',$newRecord[1][0]->Oct,$newRecord[2][0]->Oct,$newRecord[3][0]->Oct]);
                // //November
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Nov',$newRecord[1][0]->Nov,$newRecord[2][0]->Nov,$newRecord[3][0]->Nov]);
                // //December
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Dec',$newRecord[1][0]->Dec,$newRecord[2][0]->Dec,$newRecord[3][0]->Dec]);
                // //January
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Jan',$newRecord[1][0]->Jan,$newRecord[2][0]->Jan,$newRecord[3][0]->Jan]);
                // //February
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Feb',$newRecord[1][0]->Feb,$newRecord[2][0]->Feb,$newRecord[3][0]->Feb]);
                // //March
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Mar',$newRecord[1][0]->Mar,$newRecord[2][0]->Mar,$newRecord[3][0]->Mar]);
                // //April
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Apr',$newRecord[1][0]->Apr,$newRecord[2][0]->Apr,$newRecord[3][0]->Apr]);
                // //Total
                // DB::insert('insert into sf10childattendance (headerid, month, numofschooldays, numofdayspresent, numofdaysabsent) values (?,?,?,?,?)',[$schoolid,'Total',$newRecord[1][0]->Total,$newRecord[2][0]->Total,$newRecord[3][0]->Total]);

                return redirect()->back()->withInput()->with("newData", 'Record added uccessfuly!');
            }
        

        return back();

    }
    public function editform10($id, Request $request){

        $studid = $request->get('student_id');
        $recordid = $request->get('recordid');

        if($id == 'edit'){

            $getrecord = DB::table('sf10_student_elem')
                        ->select(
                            'sf10_student_elem.id',
                            'sf10_schoollist.id as recordschoolid',
                            'sf10_schoollist.schoolid',
                            'sf10_schoollist.schoolname',
                            'sf10_schoollist.schooladdress',
                            'sf10_student_elem.sectionname',
                            'sf10_student_elem.adviser',
                            'sf10_student_elem.unitsearned',
                            'sf10_student_elem.yearsinschool'
                        )
                        ->join('sf10_schoollist','sf10_student_elem.sf10_schoolid','=','sf10_schoollist.id')
                        ->where('sf10_student_elem.id', $recordid)
                        ->get();
            // return   $getrecord; 
            $getgrades = DB::table('sf10childgrades')   
                        ->where('headerid',$recordid)
                        // ->where('studid',$studid)
                        ->where('acadprog','elem')
                        ->get();
                        
            $getgeneralaverage = DB::table('sf10_generalaverage')
                        ->where('headerid',$recordid)
                        ->where('acadprog','elem')
                        ->get();
                        
            return view('registrar.studentsform10elemedit')
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
                'adviser',
                'numUnits',
                'numYears'
                ) as $key=>$gradesbysubject){

                if (strpos($key, 'old') !== false) {
                    DB::table('sf10childgrades')
                        ->where('id',$gradesbysubject[0])
                        ->where('headerid',$recordid)
                        ->where('acadprog','elem')
                        ->update([
                            'subj_desc'     =>  $gradesbysubject[1],
                            'quarter1'      =>  $gradesbysubject[2],
                            'quarter2'      =>  $gradesbysubject[3],
                            'quarter3'      =>  $gradesbysubject[4],
                            'quarter4'      =>  $gradesbysubject[5],
                            'finalrating'   =>  $gradesbysubject[6],
                            'action'        =>  $gradesbysubject[7],
                            'credits'       =>  $gradesbysubject[8]
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
                            'credits'       =>  $gradesbysubject[7],
                            'acadprog'      =>  'elem'
                        ]);
                }

            }

            $checkifgenaveexists = DB::table('sf10_generalaverage')
                ->where('id', $request->get('genAve')[0])
                ->where('headerid', $recordid)
                ->where('acadprog','elem')
                ->get();

            if(count($checkifgenaveexists) == 0){

                DB::table('sf10_generalaverage')
                    ->insert([
                        'headerid'  => $recordid,
                        'genave'    => $request->get('genAve')[1],
                        'acadprog'  =>'elem'
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

            DB::table('sf10_student_elem')
                    ->where('id',$recordid)
                    ->update([
                        'sectionname'       =>  $request->get('section'),
                        'adviser'           =>  $request->get('adviser'),
                        'unitsearned'       =>  $request->get('numUnits'),
                        'yearsinschool'     =>  $request->get('numYears')
                    ]);

            return back();

        }

    }
    public function deleteform10(Request $request)
    {

            // return $request->all();
            DB::table('sf10_student_elem')
                ->where('studid', $request->get('studentid'))
                ->where('id', $request->get('headerid'))
                ->update([
                    'deleted'   => 1
                ]);
                return back();
    }
}
