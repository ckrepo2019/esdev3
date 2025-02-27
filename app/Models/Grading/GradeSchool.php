<?php

namespace App\Models\Grading;
use DB;
use App\Models\Grading\GradingSystem;
use App\Models\Grading\GradeCalculation;
use App\Models\Grading\GradeStatus;
use PDF;
use App\Models\Subjects\Subjects;


use Illuminate\Database\Eloquent\Model;

class GradeSchool extends Model
{
   
      public static function acadprog(){

            return 3;

      }

      public static function section_count($teacherid = null){

            $activesy = DB::table('sy')->where('isactive',1)->first();

            if($teacherid != null && $teacherid != null){

                  $teacherid = $teacherid;

            }else{

                  $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;

            }

            $sections = DB::table('assignsubj')
                              ->where('syid', $activesy->id)
                              ->where('assignsubj.deleted',0)
                              ->join('assignsubjdetail',function($join) use($teacherid){
                                    $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                                    $join->where('assignsubjdetail.deleted',0);
                                    $join->where('assignsubjdetail.teacherid',$teacherid);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('assignsubj.glevelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->where('gradelevel.acadprogid',3);
                              })
                              ->select('assignsubjdetail.sectionid')
                              ->distinct('sectionid')
                              ->count();

            return $sections;

      }

      public static function get_sections($teacherid = null, $syid = null){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }

            if($teacherid != null && $teacherid != null){

                  $teacherid = $teacherid;

            }else{

                  $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;

            }

            $subjects = DB::table('assignsubj')
                              ->where('syid', $syid)
                              ->where('assignsubj.deleted',0)
                              ->join('assignsubjdetail',function($join) use($teacherid){
                                    $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                                    $join->where('assignsubjdetail.deleted',0);
                                    $join->where('assignsubjdetail.teacherid',$teacherid);
                              })
                              ->join('sections',function($join){
                                    $join->on('assignsubj.sectionid','=','sections.id');
                                    $join->where('assignsubjdetail.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('assignsubj.glevelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->where('gradelevel.acadprogid',3);
                              })
                              ->join('subjects',function($join){
                                    $join->on('assignsubjdetail.subjid','=','subjects.id');
                                    $join->where('subjects.deleted',0);
                              })
                              
                              ->select('sections.id','sectionname','subjdesc','subjid','subjcode','subjid','gradelevel.id as levelid')
                              ->get();

            return $subjects;

      }

      public static function get_sections_subject_all(){

            $activesy = DB::table('sy')->where('isactive',1)->first();

            $subjects = DB::table('assignsubj')
                        ->where('assignsubj.syid', $activesy->id)
                        ->where('assignsubj.deleted',0)
                        ->join('assignsubjdetail',function($join){
                              $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                              $join->where('assignsubjdetail.deleted',0);
                        })
                        ->join('sections',function($join){
                              $join->on('assignsubj.sectionid','=','sections.id');
                              $join->where('assignsubjdetail.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('assignsubj.glevelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('gradelevel.acadprogid',3);
                        })
                        ->join('subjects',function($join){
                              $join->on('assignsubjdetail.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })
                        ->leftJoin('grading_sytem_gradestatus',function($join){
                              $join->on('sections.id','=','grading_sytem_gradestatus.sectionid');
                              $join->on('subjects.id','=','grading_sytem_gradestatus.subjid');
                        })
                        ->join('teacher',function($join){
                              $join->on('assignsubjdetail.teacherid','=','teacher.id');
                              $join->where('teacher.deleted',0);
                        })
                        ->select(
                              'gradelevel.acadprogid',
                              'sections.id',
                              'sectionname',
                              'subjdesc',
                              'assignsubjdetail.subjid',
                              'subjcode',
                              'assignsubjdetail.subjid',
                              'gradelevel.id as levelid',
                              'teacher.firstname',
                              'teacher.lastname',
                              'gradelevel.levelname',
                              'grading_sytem_gradestatus.id as gstatus',
                              'grading_sytem_gradestatus.q1status',
                              'grading_sytem_gradestatus.q2status',
                              'grading_sytem_gradestatus.q3status',
                              'grading_sytem_gradestatus.q4status',
                              'teacher.id as teacherid'
                              
                              )
                        ->get();

            return $subjects;




      }

      public static function teacher_assign_subjects($teacherid = null){

            $activesy = DB::table('sy')->where('isactive',1)->first();

            $subjects = DB::table('assignsubj')
                              ->where('syid', $activesy->id)
                              ->where('assignsubj.deleted',0)
                              ->join('assignsubjdetail',function($join) use($teacherid){
                                    $join->on('assignsubj.ID','=','assignsubjdetail.headerid');
                                    $join->where('assignsubjdetail.deleted',0);
                                    $join->where('assignsubjdetail.teacherid',$teacherid);
                              })
                              ->join('sections',function($join){
                                    $join->on('assignsubj.sectionid','=','sections.id');
                                    $join->where('assignsubjdetail.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('assignsubj.glevelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->where('gradelevel.acadprogid',3);
                              })
                              ->join('subjects',function($join){
                                    $join->on('assignsubjdetail.subjid','=','subjects.id');
                                    $join->where('subjects.deleted',0);
                              })
                              ->select('sections.id','sectionname','subjdesc','subjid','subjcode','subjid')
                              ->get();


            return $subjects;

      }

      public static function grade_student_gradeschool(
            $gsid = null,
            $teacherid = null
      ){

            $subjects = self::teacher_assign_subjects($teacherid);
             
            if(count($subjects ) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"You are not assigned to a section."
                  ]);

                  return $data;

            }

            $sectionsDistinct = collect($subjects)->unique('sectionname');

            return view('superadmin.pages.gradingsystem.gsgrading')
                              ->with('sections',$sectionsDistinct)
                              ->with('subjects',$subjects);
                              // ->with('grading_system',$grading_system);

      }

 


      public static function evaluate_student_grade_gradeschool(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null
      ){
            $checkStatus = GradeStatus::check_grade_status($sectionid, $subjectid, $quarter);
            
            $grading_system = self::subject_grading_system($subjectid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            if($checkStatus[0]->status == 0){

                  return $checkStatus;

            }
            else{
                  
                  $checkStatus = $checkStatus[0]->data;
            }


            $activeSy = DB::table('sy')->where('isactive',1)->first();


            $check_subeject_gs = DB::table('grading_system_gsgrades')
                                    ->where('grading_system_gsgrades.sectionid',$sectionid)
                                    ->where('grading_system_gsgrades.subjid',$subjectid)
                                    ->where('grading_system_gsgrades.deleted',0)
                                    ->where('studid',0)
                                    ->where('syid',$activeSy->id)
                                    ->whereNotNull('levelid')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system_gsgrades.gsdid','=','grading_system_detail.id');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->select('grading_system_detail.headerid')
                                    ->first();


            if(isset($check_subeject_gs->headerid)){

                  $gs = DB::table('grading_system')
                              ->where('grading_system.id',$check_subeject_gs->headerid)
                              ->where('grading_system.deleted',0)
                              ->first();

                  $grading_system_detail = DB::table('grading_system')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->where('acadprogid',3)
                              ->where('grading_system.id',$check_subeject_gs->headerid)
                              ->where('grading_system.deleted',0)
                              ->select('grading_system_detail.*')
                              ->get();

            }
            else{

                  $gs = DB::table('grading_system')
                              ->where('grading_system.id',$grading_system[0]->id)
                              ->where('grading_system.deleted',0)
                              ->first();

                  $grading_system_detail = DB::table('grading_system')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->where('acadprogid',3)
                              ->where('grading_system.id',$grading_system[0]->id)
                              ->where('grading_system.deleted',0)
                              ->select('grading_system_detail.*')
                              ->get();

            }

            $students = DB::table('studinfo')   
                              ->where('studinfo.studstatus',1)
                              ->join('enrolledstud',function($join){
                                    $join->on('studinfo.id','=','enrolledstud.studid');
                                    $join->where('enrolledstud.deleted',0);
                              })
                              ->where('studinfo.sectionid',$sectionid)
                              ->where('studinfo.deleted',0)
                              ->whereIn('studinfo.studstatus',[1,2,3])
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->select('firstname','lastname','studinfo.id','gender')
                              ->get();

            $nogscount = 0;

            foreach($students as $item){

                  $gsdget = DB::table('grading_system_gsgrades')
                              ->where('studid',$item->id)
                              ->where('syid',$activeSy->id)
                              ->where('subjid',$subjectid)
                              ->where('grading_system_gsgrades.deleted',0)
                              ->whereNotNull('levelid')
                              ->select('gsdid','id');
                        
                  for($x = 1; $x <= 10; $x++){

                        $gsdget =  $gsdget->addSelect('g'.$x.'q'.$quarter);

                  }

                  $gsdget =  $gsdget->addSelect('psq'.$quarter);
                  $gsdget =  $gsdget->addSelect('wsq'.$quarter);
                  $gsdget =  $gsdget->addSelect('q'.$quarter.'total');
                  $gsdget =  $gsdget->addSelect('igq'.$quarter);
                  $gsdget =  $gsdget->addSelect('qgq'.$quarter);

                  $gsdget =  $gsdget->get();

                  if(count($gsdget) == 0){

                        $nogscount += 1;
                        $item->nogs = 0;
                        $item->gsdget = [];

                  }
                  else{
                        $item->nogs = 1;
                        $item->gsdget = $gsdget;
                  }

            }



            $gsheader = DB::table('grading_system_gsgrades')
                                    ->where('studid',0)
                                    ->where('syid',$activeSy->id)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjectid)
                                    ->whereNotNull('levelid')
                                    ->where('grading_system_gsgrades.deleted',0)
                                    ->select('gsdid','id');
                        
            for($x = 1; $x <= 10; $x++){

                  $gsheader =  $gsheader->addSelect('g'.$x.'q'.$quarter);

            }

            $gsheader =  $gsheader->addSelect('psq'.$quarter);
            $gsheader =  $gsheader->addSelect('wsq'.$quarter);
            $gsheader =  $gsheader->addSelect('q'.$quarter.'total');
            $gsheader =  $gsheader->get();

            if(count($gsheader) == 0){

                  $nogscount += 1;

                  $gsdetail = (object)[
                        'id'=>0,
                        'nogs'=>0,
                        'gsdget' => []
                  ];
            }
            else{

                  $gsdetail = (object)[
                        'id'=>0,
                        'nogs'=>1,
                        'studid'=>0,
                        'gsdget' => $gsheader
                  ];

            }

            $gradelogs = DB::table('grading_system_gradestatus_logs')
                              ->join('users',function($join){
                                    $join->on('grading_system_gradestatus_logs.createdby','=','users.id');
                                    $join->where('users.deleted',0);
                              })
                              ->where('headerid',$checkStatus->id)
                              ->select('users.name','status','createddatetime')
                              ->get();

            return view('superadmin.pages.gradingsystem.gstable')
                        ->with('nogscount',$nogscount)
                        ->with('checkStatus',$checkStatus)
                        ->with('gsheader',$gsdetail)
                        ->with('quarter',$quarter)
                        ->with('gradelogs',$gradelogs)
                        ->with('gsd',$grading_system[0])
                        ->with('grading_system_detail',$grading_system_detail)
                        ->with('students',$students);

      }


      

      public static function generate_student_grade_gradeschool(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null,
            $levelid = null
      ){

            

            $activeSy = DB::table('sy')->where('isactive',1)->first();
            $proccesCount = 0;

            if($levelid == null){

                  $levelid = DB::table('sections')->where('id',$sectionid)->first()->levelid;

            }
          
            $grading_system_detail = DB::table('grading_system')
                        ->join('grading_system_detail',function($join){
                              $join->on('grading_system.id','=','grading_system_detail.headerid');
                              $join->where('grading_system_detail.deleted',0);
                        })
                        ->where('acadprogid',3)
                        ->where('grading_system.id',$gsid)
                        ->where('grading_system.deleted',0)
                        ->select('grading_system_detail.id','grading_system_detail.headerid')
                        ->get();

            foreach($grading_system_detail as $item){

                  $gsgradescount = DB::table('grading_system_gsgrades')
                              ->where('gsdid',$item->id)
                              ->where('sectionid',$sectionid)
                              ->where('studid',$studid)
                              ->where('levelid',$levelid)
                              ->where('syid',$activeSy->id)
                              ->where('grading_system_gsgrades.deleted',0)
                              ->where('subjid',$subjectid)
                              ->count();

                  if($gsgradescount == 0){

                        $proccesCount +=1;

                        DB::table('grading_system_gsgrades')
                              ->insert([
                                    'studid'=>$studid,
                                    'syid'=>$activeSy->id,
                                    'gsdid'=>$item->id,
                                    'sectionid'=>$sectionid,
                                    'subjid'=>$subjectid,
                                    'levelid'=>$levelid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

            }

            $gsheader = DB::table('grading_system_gsgrades')
                  ->where('studid',0)
                  ->where('deleted',0)
                  ->where('syid',$activeSy->id)
                  ->where('sectionid',$sectionid)
                  ->where('subjid',$subjectid)
                  ->count();

            if($gsheader == 0){

                  $proccesCount +=1;

                  DB::table('grading_system_gsgrades')
                        ->insert([
                              'studid'=>0,
                              'syid'=>$activeSy->id,
                              'gsdid'=>$gsid,
                              'sectionid'=>$sectionid,
                              'subjid'=>$subjectid,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            }

            return $proccesCount;

      }

      public static function submit_student_grade_gradeschool(
            $studid = null,
            $gradid = null,
            $field  = null,
            $value = 0
      ){

            $gradeDetail = DB::table('grading_system_gsgrades')
                                    ->where('studid',$studid)
                                    ->where('grading_system_gsgrades.deleted',0)
                                    ->where('id',$gradid);

            $forHPS = $gradeDetail->select('syid','sectionid','subjid','gsdid')->first();

            $hps = DB::table('grading_system_gsgrades')
                              ->where('studid',0)
                              ->where('syid',$forHPS->syid)
                              ->where('sectionid',$forHPS->sectionid)
                              ->where('gsdid',$forHPS->gsdid)
                              ->where('grading_system_gsgrades.deleted',0)
                              ->where('subjid',$forHPS->subjid);

            $gradeDetail->update([
                        $field=>$value,
                        'updatedby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);


            $studentgsd =   DB::table('grading_system_gsgrades')
                              ->where('studid',$studid)
                              ->where('grading_system_gsgrades.deleted',0)
                              ->where('id',$gradid);

            if(strpos($field , 'q1')){

            for($x = 1; $x <= 10; $x++){

                  $studentgsd =  $studentgsd->addSelect('g'.$x.'q1');
                  $hps =  $hps->addSelect('g'.$x.'q1');

            }

            $quarter = 1;

            }
            elseif(strpos($field , 'q2')){

            for($x = 1; $x <= 10; $x++){

                  $studentgsd =  $studentgsd->addSelect('g'.$x.'q2');
                  $hps =  $hps->addSelect('g'.$x.'q2');

            }

            $quarter = 2;

            }
            elseif(strpos($field , 'q3')){

            for($x = 1; $x <= 10; $x++){

                  $studentgsd =  $studentgsd->addSelect('g'.$x.'q3');
                  $hps =  $hps->addSelect('g'.$x.'q3');

            }

            $quarter = 3;



            }

            elseif(strpos($field , 'q4')){

            for($x = 1; $x <= 10; $x++){

                  $studentgsd =  $studentgsd->addSelect('g'.$x.'q4');
                  $hps =  $hps->addSelect('g'.$x.'q4');

            }

            $quarter = 4;


            }

            $qtotal = collect(  $studentgsd->first())->sum();

            $gsdid = $studentgsd->select('gsdid')->first();

            $gsdetail = DB::table('grading_system_detail')     
                              ->where('deleted',0)
                              ->where('id',$gsdid->gsdid)
                              ->select('value')
                              ->first();

            $hpssum = collect($hps->first())->sum();

            if($studid != 0){

            if($hpssum == 0){

                  $ps = 0;
                  $ws = 0;

            }
            else{

                  $ps = ( $qtotal /  $hpssum ) * 100;
                  $ws = $ps * ( $gsdetail->value / 100 );

            }

            }else{

                  $ps = 0;
                  $ws = 0;
                  $ig = 0;
            }

            DB::table('grading_system_gsgrades')
                  ->where('studid',$studid)
                  ->where('id',$gradid)
                  ->update([
                        'q'.$quarter.'total'=>$qtotal,
                        'psq'.$quarter=>$ps,
                        'wsq'.$quarter=>$ws,
                        'updatedby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            if($studid != 0){

            $ig = self::calcig(
                  $studid,
                  $forHPS->syid,
                  $forHPS->subjid,
                  $forHPS->sectionid,
                  $quarter
            );

            if($studid != null && $forHPS->syid != null  && $forHPS->sectionid != null && $forHPS->subjid != null ){

                  DB::table('grading_system_gsgrades')
                              ->where('studid',$studid)
                              ->where('syid',$forHPS->syid)
                              ->where('sectionid',$forHPS->sectionid)
                              ->where('subjid',$forHPS->subjid)
                              ->update([
                                    'igq'.$quarter=>$ig,
                                    'updatedby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                        
                  try{

                        $ig = number_format($ig , 2);

                        $qg = GradeCalculation::grade_transmutation($ig);

                        DB::table('grading_system_gsgrades')
                              ->where('studid',$studid)
                              ->where('syid',$forHPS->syid)
                              ->where('sectionid',$forHPS->sectionid)
                              ->where('subjid',$forHPS->subjid)
                              ->update([
                                    'qgq'.$quarter=>$qg,
                                    'updatedby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);


                  }
                  catch(\Exception $e){

                        DB::table('grading_system_gsgrades')
                              ->where('studid',$studid)
                              ->where('syid',$forHPS->syid)
                              ->where('sectionid',$forHPS->sectionid)
                              ->where('subjid',$forHPS->subjid)
                              ->update([
                                    'qgq'.$quarter=>0,
                                    'updatedby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return 0;

                  }

                 
               
            }


            }

            return 1;

      }


      public static function calcig($student = null, $syid = null, $subject = null, $section = null, $quarter = null){

            $studgd = DB::table('grading_system_gsgrades')
                                    ->where('studid',$student)
                                    ->where('syid',$syid)
                                    ->where('subjid',$subject)
                                    ->where('sectionid',$section)
                                    ->where('deleted',0)
                                    ->select('wsq'.$quarter)
                                    ->sum('wsq'.$quarter);

            return  $studgd;
            

      }


      public static function subject_grading_system($subject = null){

            $grading_system = DB::table('subjects')
                        ->where('subjects.acadprogid',self::acadprog())
                        ->join('grading_system_subjassignment',function($join){
                              $join->on('subjects.id','=','grading_system_subjassignment.subjid');
                              $join->where('grading_system_subjassignment.deleted',0);
                        })
                        ->join('grading_system',function($join){
                              $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                              $join->where('grading_system.deleted',0);
                              $join->where('grading_system.acadprogid',self::acadprog());
                        
                        })
                        ->where('inSF9',1)
                        ->where('subjects.id',$subject)
                        ->select('grading_system.*')
                        ->get();

            if(count($grading_system ) == 1 && $grading_system[0]->id != null){

                  $gsdetail =  DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('deleted',0)
                                    ->count();

                  if($gsdetail == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue.",
                        ]);

                        return $data;

                  }

            }
            else if(count($grading_system ) == 1 && $grading_system[0]->id == null){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"This subject is not yet assigned to a grading system",
                  ]);

                  return $data;

            }
            else if(count($grading_system) > 1){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Mutiple grading system is active."
                  ]);

                  return $data;

            }
            else if(count($grading_system) == 0){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"No available grading system for grade school."
                  ]);

                  return $data;

            }
            
            return    $data =  array((object)[
                              'status'=>1,
                              'data'=> $grading_system
                        ]);
                        
      }

      public static function reload_student_grade(
            $student = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null
      ){


            $checkStatus = GradeStatus::check_grade_status($sectionid, $subjectid, $quarter);

            $grading_system = self::subject_grading_system($subjectid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            if($checkStatus[0]->status == 0){

                  return $checkStatus;

            }
            else{
                  
                  $checkStatus = $checkStatus[0]->data;
            }

            $activeSy = DB::table('sy')->where('isactive',1)->first();

            $check_subeject_gs = DB::table('grading_system_gsgrades')
                                    ->where('grading_system_gsgrades.sectionid',$sectionid)
                                    ->where('grading_system_gsgrades.subjid',$subjectid)
                                    ->where('grading_system_gsgrades.deleted',0)
                                    ->where('studid',$student)
                                    ->where('syid',$activeSy->id)
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system_gsgrades.gsdid','=','grading_system_detail.id');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->select('grading_system_detail.headerid')
                                    ->first();

            $grading_system_detail = DB::table('grading_system')
                        ->join('grading_system_detail',function($join){
                              $join->on('grading_system.id','=','grading_system_detail.headerid');
                              $join->where('grading_system_detail.deleted',0);
                        })
                        ->where('acadprogid',3)
                        ->where('grading_system.id',$grading_system[0]->id)
                        ->where('grading_system.deleted',0)
                        ->select('grading_system_detail.*')
                        ->get();
      
            if(isset($check_subeject_gs->headerid)){

                  if($grading_system[0]->id != $check_subeject_gs->headerid){

                        $get_studentgrades_detail = $check_subeject_gs = DB::table('grading_system_gsgrades')
                                                                              ->where('grading_system_gsgrades.sectionid',$sectionid)
                                                                              ->where('grading_system_gsgrades.subjid',$subjectid)
                                                                              ->where('grading_system_gsgrades.deleted',0)
                                                                              ->where('studid',$student)
                                                                              ->join('grading_system_detail',function($join){
                                                                                    $join->on('grading_system_gsgrades.gsdid','=','grading_system_detail.id');
                                                                                    $join->where('grading_system_detail.deleted',0);
                                                                              })
                                                                              ->select('grading_system_gsgrades.*','grading_system_detail.sf9val')
                                                                              ->where('syid',$activeSy->id)
                                                                              ->get();

                        foreach($get_studentgrades_detail as $item){

                              DB::table('grading_system_gsgrades')
                                          ->where('id',$item->id)
                                          ->update([
                                                'deleted'=>1,
                                          ]);
                               
                              unset($item->id);
                              unset($item->gsdid);

                              $gsdid = collect($grading_system_detail)->where('sf9val',$item->sf9val)->first();
                              $item->gsdid =  $gsdid->id;

                              DB::table('grading_system_gsgrades')
                                          ->insert([
                                                'syid'=>$item->syid,
                                                'studid'=>$item->studid,
                                                'sectionid'=>$item->sectionid,
                                                'subjid'=>$item->subjid,
                                                'levelid'=>$item->levelid,
                                                'gsdid'=>$item->gsdid,
                                                'g1q1'=>$item->g1q1,
                                                'g2q1'=>$item->g2q1,
                                                'g3q1'=>$item->g3q1,
                                                'g4q1'=>$item->g4q1,
                                                'g5q1'=>$item->g5q1,
                                                'g6q1'=>$item->g6q1,
                                                'g7q1'=>$item->g7q1,
                                                'g8q1'=>$item->g8q1,
                                                'g9q1'=>$item->g9q1,
                                                'g10q1'=>$item->g10q1,
                                                'g1q2'=>$item->g1q2,
                                                'g2q2'=>$item->g2q2,
                                                'g3q2'=>$item->g3q2,
                                                'g4q2'=>$item->g4q2,
                                                'g5q2'=>$item->g5q2,
                                                'g6q2'=>$item->g6q2,
                                                'g7q2'=>$item->g7q2,
                                                'g8q2'=>$item->g8q2,
                                                'g9q2'=>$item->g9q2,
                                                'g10q2'=>$item->g10q2,
                                                'g1q3'=>$item->g1q3,
                                                'g2q3'=>$item->g2q3,
                                                'g3q3'=>$item->g3q3,
                                                'g4q3'=>$item->g4q3,
                                                'g5q3'=>$item->g5q3,
                                                'g6q3'=>$item->g6q3,
                                                'g7q3'=>$item->g7q3,
                                                'g8q3'=>$item->g8q3,
                                                'g9q3'=>$item->g9q3,
                                                'g10q3'=>$item->g10q3,
                                                'g1q4'=>$item->g1q4,
                                                'g2q4'=>$item->g2q4,
                                                'g3q4'=>$item->g3q4,
                                                'g4q4'=>$item->g4q4,
                                                'g5q4'=>$item->g5q4,
                                                'g6q4'=>$item->g6q4,
                                                'g7q4'=>$item->g7q4,
                                                'g8q4'=>$item->g8q4,
                                                'g9q4'=>$item->g9q3,
                                                'g10q4'=>$item->g10q3,
                                                'q1total'=>$item->q1total,
                                                'q2total'=>$item->q2total,
                                                'q3total'=>$item->q3total,
                                                'q4total'=>$item->q4total,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                         

                        }


                        if($student != 0){
                        
                              return self::reCalculateGrade( 
                                    $student,
                                    $sectionid,
                                    $subjectid,
                                    $quarter
                              );
                        }

                        return "done";
                  }
                  else{

                        if($student != 0){

                              return self::reCalculateGrade( 
                                    $student,
                                    $sectionid,
                                    $subjectid,
                                    $quarter
                              );
                        }
      
                  }

          

            }

      }

      public static function reCalculateGrade($studid = null, $sectionid = null, $subjid = null, $quarter = null ){

            $grading_system = self::subject_grading_system($subjid);

            $grading_system = $grading_system[0]->data;

            $gradeDetail = DB::table('grading_system_gsgrades')
                              ->where('studid',$studid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('syid',self::activeSy())
                              ->where('grading_system_gsgrades.deleted',0)
                              ->select('q1total','q2total','q3total','q4total','gsdid','id')
                              ->get();

            $gradeDetailHPS = DB::table('grading_system_gsgrades')
                                    ->where('studid',0)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjid)
                                    ->where('syid',self::activeSy())
                                    ->where('grading_system_gsgrades.deleted',0)
                                    ->select('q1total','q2total','q3total','q4total','gsdid')
                                    ->get();

            $grading_system_detail = DB::table('grading_system_detail')
                                                ->where('grading_system_detail.headerid',$grading_system[0]->id)
                                                ->where('grading_system_detail.deleted',0)
                                                ->select('grading_system_detail.*')
                                                ->get();

            $totalig = 0;

            foreach($gradeDetail as $item){

                  $field = 'q'.$quarter.'total';
                  $hps = collect($gradeDetailHPS)->where('gsdid',$item->gsdid)->first()->$field;
                  $gsp = collect($grading_system_detail)->where('id',$item->gsdid)->first()->value;

                  $ps = ( $item->$field / $hps) * 100;
                  $ws =  $ps * ( $gsp / 100 );

                  $totalig += $ws;

                  DB::table('grading_system_gsgrades')
                        ->where('id',$item->id)
                        ->update([
                              'wsq'.$quarter=>  $ws ,
                              'psq'.$quarter=>  $ps ,
                        ]);


            }

            foreach($gradeDetail as $item){

                  DB::table('grading_system_gsgrades')
                              ->where('id',$item->id)
                              ->update([
                                    'igq'.$quarter =>  $totalig ,
                              ]);
            
            }

            $totalig = number_format($totalig , 2);

            $qg = GradeCalculation::grade_transmutation($totalig);


            foreach($gradeDetail as $item){

                  DB::table('grading_system_gsgrades')
                              ->where('id',$item->id)
                              ->update([
                                    'qgq'.$quarter =>  $qg ,
                              ]);
            
            }


            return count($gradeDetail);


      }

      public static function activeSy(){

            return  DB::table('sy')->where('isactive',1)->first()->id;

      }


      public static function view_sf9(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null
      ){

            $activeSy = DB::table('sy')->where('isactive',1)->first();
            $activeSem = DB::table('semester')->where('isactive',1)->first();


            $teacherid = DB::table('assignsubj')
                              ->where('syid',$activeSy->id)
                              ->where('sectionid',$sectionid)
                              ->join('assignsubjdetail',function($join) use($subjectid){
                                    $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                    $join->where('assignsubjdetail.deleted','0');
                                    $join->where('assignsubjdetail.subjid',$subjectid);
                              })
                              ->where('assignsubj.deleted','0')
                              ->select('teacherid')
                              ->first();
                              
            $teacher = DB::table('teacher')->where('id',$teacherid->teacherid)->select('firstname','lastname','userid')->first();
            
            if($teacher->userid != auth()->user()->id){
                
                return back();
                
            }

            $grading_system = self::subject_grading_system($subjectid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            $checkStatus = GradeStatus::check_grade_status($sectionid, $subjectid, $quarter);
            $subjectStatus = Subjects::get_subject($subjectid);
            if($checkStatus[0]->status == 0){

                  return $checkStatus;

            }
            else{
                  
                  $checkStatus = $checkStatus[0]->data;

            }

            $activeSy = DB::table('sy')->where('isactive',1)->first();

            $gs = DB::table('grading_system')
                        ->where('grading_system.id',$grading_system[0]->id)
                        ->where('grading_system.deleted',0)
                        ->first();

            $grading_system_detail = DB::table('grading_system')
                        ->join('grading_system_detail',function($join){
                              $join->on('grading_system.id','=','grading_system_detail.headerid');
                              $join->where('grading_system_detail.deleted',0);
                        })
                        ->where('acadprogid',3)
                        ->where('grading_system.id',$grading_system[0]->id)
                        ->where('grading_system.deleted',0)
                        ->select('grading_system_detail.*')
                        ->get();

            $students = DB::table('studinfo')   
                              ->where('studinfo.studstatus',1)
                              ->join('enrolledstud',function($join){
                                    $join->on('studinfo.id','=','enrolledstud.studid');
                                    $join->where('enrolledstud.deleted',0);
                              })
                              ->where('studinfo.sectionid',$sectionid)
                              ->where('studinfo.deleted',0)
                              ->whereIn('studinfo.studstatus',[1,2,3])
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->select('firstname','lastname','studinfo.id','gender','studinfo.levelid')
                              ->get();
      
            $nogscount = 0;

            $gradelevel = null;
            $teacherid = null;


            if($teacherid = null){

            }
          

            foreach($students as $item){

                  if($gradelevel == null){

                        $gradelevel = $item->levelid;
                  }

                  $gsdget = DB::table('grading_system_gsgrades')
                              ->where('studid',$item->id)
                              ->where('syid',$activeSy->id)
                              ->where('subjid',$subjectid)
                              ->where('grading_system_gsgrades.deleted',0)
                              ->select('gsdid','id');
                        
                  for($x = 1; $x <= 10; $x++){

                        $gsdget =  $gsdget->addSelect('g'.$x.'q'.$quarter);

                  }

                  $gsdget =  $gsdget->addSelect('psq'.$quarter);
                  $gsdget =  $gsdget->addSelect('wsq'.$quarter);
                  $gsdget =  $gsdget->addSelect('q'.$quarter.'total');
                  $gsdget =  $gsdget->addSelect('igq'.$quarter);
                  $gsdget =  $gsdget->addSelect('qgq'.$quarter);

                  $gsdget =  $gsdget->get();

                  if(count($gsdget) == 0){

                        $nogscount += 1;
                        $item->nogs = 0;
                        $item->gsdget = [];

                  }
                  else{
                        $item->nogs = 1;
                        $item->gsdget = $gsdget;
                  }

            }

            $gsheader = DB::table('grading_system_gsgrades')
                                    ->where('studid',0)
                                    ->where('syid',$activeSy->id)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjectid)
                                    ->where('grading_system_gsgrades.deleted',0)
                                    ->select('gsdid','id');
                                    
            for($x = 1; $x <= 10; $x++){

                  $gsheader =  $gsheader->addSelect('g'.$x.'q'.$quarter);

            }

            $gsheader =  $gsheader->addSelect('psq'.$quarter);
            $gsheader =  $gsheader->addSelect('wsq'.$quarter);
            $gsheader =  $gsheader->addSelect('q'.$quarter.'total');
            $gsheader =  $gsheader->get();
         
            if(count($gsheader) == 0){

                  $nogscount += 1;

                  $gsdetail = (object)[
                        'id'=>0,
                        'nogs'=>0,
                        'gsdget' => []
                  ];
            }
            else{

                  $gsdetail = (object)[
                        'id'=>0,
                        'nogs'=>1,
                        'studid'=>0,
                        'gsdget' => $gsheader
                  ];

            }

            $gsd = $grading_system[0];
            $gsheader = $gsdetail;

            $schoolinfo = DB::table('schoolinfo')->get();
            $schoolyear = DB::table('sy')->where('isactive',1)->get();

            $section = DB::table('sections')
                              ->where('sections.id',$sectionid)
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','gradelevel.id');
                                    $join->where('gradelevel.deleted','0');
                              })
                              ->select('sectionname','levelname')
                              ->where('sections.deleted',0)
                              ->first();

            $activesy = DB::table('sy')->where('isactive',1)->first();

            $signatory = array();

            array_push($signatory,(object)[
                  'title'=>"Subject Teacher",
                  'name'=>$teacher->firstname.' '.$teacher->lastname,
                  'description'=>'Submitted by:'
            ]);
      
            $temp_signatory = DB::table('signatory')->where('form','ecl')->get();

            foreach($temp_signatory as $item){
                  array_push($signatory,$item);
            }

            $pdf = PDF::loadView('teacher.grading.sf9forms.gssf9',compact('signatory','nogscount','schoolinfo','schoolyear','gsheader','quarter','checkStatus','gradelevel','gsd','grading_system_detail','students','teacher','section'))->setPaper('legal', 'landscape');
            $pdf->getDomPDF()->set_option("enable_php", true);
    
            return $pdf->stream();

      }


      public static function evaluate_student_grade_gradeschool_pending(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null
      ){
            $checkStatus = GradeStatus::check_grade_status($sectionid, $subjectid, $quarter);
            
            $grading_system = self::subject_grading_system($subjectid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            if($checkStatus[0]->status == 0){

                  return $checkStatus;

            }
            else{
                  
                  $checkStatus = $checkStatus[0]->data;
            }


            $activeSy = DB::table('sy')->where('isactive',1)->first();


            $check_subeject_gs = DB::table('grading_system_gsgrades')
                                    ->where('grading_system_gsgrades.sectionid',$sectionid)
                                    ->where('grading_system_gsgrades.subjid',$subjectid)
                                    ->where('grading_system_gsgrades.deleted',0)
                                    ->where('studid',0)
                                    ->where('syid',$activeSy->id)
                                    ->whereNotNull('levelid')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system_gsgrades.gsdid','=','grading_system_detail.id');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->select('grading_system_detail.headerid')
                                    ->first();


            if(isset($check_subeject_gs->headerid)){

                  $gs = DB::table('grading_system')
                              ->where('grading_system.id',$check_subeject_gs->headerid)
                              ->where('grading_system.deleted',0)
                              ->first();

                  $grading_system_detail = DB::table('grading_system')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->where('acadprogid',3)
                              ->where('grading_system.id',$check_subeject_gs->headerid)
                              ->where('grading_system.deleted',0)
                              ->select('grading_system_detail.*')
                              ->get();

            }
            else{

                  $gs = DB::table('grading_system')
                              ->where('grading_system.id',$grading_system[0]->id)
                              ->where('grading_system.deleted',0)
                              ->first();

                  $grading_system_detail = DB::table('grading_system')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->where('acadprogid',3)
                              ->where('grading_system.id',$grading_system[0]->id)
                              ->where('grading_system.deleted',0)
                              ->select('grading_system_detail.*')
                              ->get();

            }

            $students = DB::table('studinfo')   
                              ->where('studinfo.studstatus',1)
                              ->join('enrolledstud',function($join){
                                    $join->on('studinfo.id','=','enrolledstud.studid');
                                    $join->where('enrolledstud.deleted',0);
                              })
                              ->where('studinfo.sectionid',$sectionid)
                              ->where('studinfo.deleted',0)
                              ->whereIn('studinfo.studstatus',[1,2,3])
                              ->where('studid',$studid)
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->select('firstname','lastname','studinfo.id','gender')
                              ->get();

            $nogscount = 0;

            foreach($students as $item){

                  $gsdget = DB::table('grading_system_gsgrades')
                              ->where('studid',$item->id)
                              ->where('syid',$activeSy->id)
                              ->where('subjid',$subjectid)
                              ->where('grading_system_gsgrades.deleted',0)
                              ->whereNotNull('levelid')
                              ->select('gsdid','id');
                        
                  for($x = 1; $x <= 10; $x++){

                        $gsdget =  $gsdget->addSelect('g'.$x.'q'.$quarter);

                  }

                  $gsdget =  $gsdget->addSelect('psq'.$quarter);
                  $gsdget =  $gsdget->addSelect('wsq'.$quarter);
                  $gsdget =  $gsdget->addSelect('q'.$quarter.'total');
                  $gsdget =  $gsdget->addSelect('igq'.$quarter);
                  $gsdget =  $gsdget->addSelect('qgq'.$quarter);

                  $gsdget =  $gsdget->get();

                  if(count($gsdget) == 0){

                        $nogscount += 1;
                        $item->nogs = 0;
                        $item->gsdget = [];

                  }
                  else{
                        $item->nogs = 1;
                        $item->gsdget = $gsdget;
                  }

            }



            $gsheader = DB::table('grading_system_gsgrades')
                                    ->where('studid',0)
                                    ->where('syid',$activeSy->id)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjectid)
                                    ->whereNotNull('levelid')
                                    ->where('grading_system_gsgrades.deleted',0)
                                    ->select('gsdid','id');
                        
            for($x = 1; $x <= 10; $x++){

                  $gsheader =  $gsheader->addSelect('g'.$x.'q'.$quarter);

            }

            $gsheader =  $gsheader->addSelect('psq'.$quarter);
            $gsheader =  $gsheader->addSelect('wsq'.$quarter);
            $gsheader =  $gsheader->addSelect('q'.$quarter.'total');
            $gsheader =  $gsheader->get();

            if(count($gsheader) == 0){

                  $nogscount += 1;

                  $gsdetail = (object)[
                        'id'=>0,
                        'nogs'=>0,
                        'gsdget' => []
                  ];
            }
            else{

                  $gsdetail = (object)[
                        'id'=>0,
                        'nogs'=>1,
                        'studid'=>0,
                        'gsdget' => $gsheader
                  ];

            }

            $gradelogs = DB::table('grading_system_gradestatus_logs')
                              ->join('users',function($join){
                                    $join->on('grading_system_gradestatus_logs.createdby','=','users.id');
                                    $join->where('users.deleted',0);
                              })
                              ->where('headerid',$checkStatus->id)
                              ->select('users.name','status','createddatetime')
                              ->get();

            $field = 'q'.$quarter.'status';
            $checkStatus->$field = 4;

           
            $check_pending = DB::table('grading_system_pending_grade')
                                    ->where('studid',$item->id)
                                    ->where('subjid',$subjectid)   
                                    ->where('syid',$activeSy->id)   
                                    ->where('deleted',0) 
                                    ->select('id')  
                                    ->first();

            return view('teacher.pendinggrades.v2.gspending')
                        ->with('nogscount',$nogscount)
                        ->with('check_pending',$check_pending)
                        ->with('checkStatus',$checkStatus)
                        ->with('gsheader',$gsdetail)
                        ->with('quarter',$quarter)
                        ->with('gradelogs',$gradelogs)
                        ->with('gsd',$grading_system[0])
                        ->with('grading_system_detail',$grading_system_detail)
                        ->with('students',$students);

      }


}
