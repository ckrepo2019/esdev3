<?php

namespace App\Models\Grading;
use DB;
use App\Models\Grading\GradingSystem;
use App\Models\Grading\GradeCalculation;



use Illuminate\Database\Eloquent\Model;

class CoreValue extends Model
{



      public static function advisory_count($teacherid = null, $acaprogid = null){

            $activesy = DB::table('sy')->where('isactive',1)->first();

            if($teacherid != null && $teacherid != null){

                  $teacherid = $teacherid;
      
            }else{

                  $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;

            }

            $sections = DB::table('sections')  
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->join('sectiondetail',function($join) use($activesy,$teacherid){
                                    $join->on('sections.id','=','sectiondetail.sectionid');
                                    $join->where('sectiondetail.deleted',0);
                                    $join->where('sectiondetail.teacherid',$teacherid);
                                    $join->where('sectiondetail.syid',$activesy->id);
                              })
                              ->where('acadprogid',$acaprogid)
                              ->select('sections.id','sectionname','sectiondetail.teacherid')
                              ->where('sections.deleted',0)
                              ->count();

            return $sections;

      }


   
      public static function grade_student_corevalue(
            $gsid = null,
            $teacherid = null,
            $acaprogid = null
      ){

            $grading_system = array();
            $teacher_subjects = array();
            $sections = array();

            $grading_system = GradingSystem::get_corevalue_setup($gsid, $acaprogid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            // return $grading_system;

            $teacherid = $teacherid;

            $activesy = DB::table('sy')->where('isactive',1)->first();

            $sections = DB::table('sections')  
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->join('sectiondetail',function($join) use($activesy,$teacherid){
                                    $join->on('sections.id','=','sectiondetail.sectionid');
                                    $join->where('sectiondetail.deleted',0);
                                    $join->where('sectiondetail.teacherid',$teacherid);
                                    $join->where('sectiondetail.syid',$activesy->id);
                              })
                              ->where('acadprogid',$acaprogid)
                              ->select('sections.id','sectionname','sectiondetail.teacherid')
                              ->where('sections.deleted',0)
                              ->get();

            if(count($sections ) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"You are not assigned to a section."
                  ]);

                  return $data;

            }

            $sectionsArray = collect($sections)->map(function($query){return $query->id;})->toArray();

            // $student = DB::table('studinfo')
            //                   ->join('gradelevel',function($join) use($acaprogid){
            //                         $join->on('studinfo.levelid','=','gradelevel.id');
            //                         $join->where('gradelevel.deleted',0);
            //                         $join->where('gradelevel.acadprogid',$acaprogid);
            //                   })
            //                   ->whereIn('sectionid',$sectionsArray)
            //                   ->whereIn('studstatus',[1,2,4])
            //                   ->select('studinfo.firstname','studinfo.lastname','studinfo.id','sectionid')
            //                   ->get();

            $student = DB::table('sh_enrolledstud')
                                    ->join('gradelevel',function($join) use($acaprogid){
                                          $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                          $join->where('gradelevel.acadprogid',$acaprogid);
                                    })
                                    ->join('studinfo',function($join) use($acaprogid){
                                          $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->whereIn('sh_enrolledstud.sectionid',$sectionsArray)
                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                    ->where('sh_enrolledstud.semid',1)
                                    ->where('sh_enrolledstud.syid',2)
                                    ->select('studinfo.firstname','studinfo.lastname','studinfo.id','sh_enrolledstud.sectionid')
                                    ->get();

            return $student;

            return view('superadmin.pages.gradingsystem.coregrading')
                              ->with('grading_system',$grading_system)
                              ->with('sections',$sections)
                              ->with('acadprog',$acaprogid)
                              ->with('students',$student);

      }

      public static function evaluate_corevalue_setup(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null,
            $acaprogid = null
      ){

            $rv = [];
            $activeSy = DB::table('sy')->where('isactive',1)->first();

            //evaluate grading system
            $grading_system = GradingSystem::get_corevalue_setup($gsid, $acaprogid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }


            $checkGrades = DB::table('grading_system_grades_cv')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system_grades_cv.gsdid','=','grading_system_detail.id');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->join('grading_system',function($join) use($grading_system){
                                    $join->on('grading_system_detail.headerid','=','grading_system.id');
                                    $join->where('grading_system.deleted',0);
                                    $join->where('grading_system.id',$grading_system[0]->id);
                              })
                              ->where('grading_system_grades_cv.deleted',0)
                              ->where('studid',$studid)
                              ->where('syid',$activeSy->id)
                              ->select(
                                    'grading_system_grades_cv.id',
                                    'grading_system_grades_cv.q1eval',
                                    'grading_system_grades_cv.q2eval',
                                    'grading_system_grades_cv.q3eval',
                                    'grading_system_grades_cv.q4eval',
                                    'grading_system_detail.description',
                                    'value',
                                    'sort',
                                    'type',
                                    'group'
                              )
                              ->orderBy('sort')
                              ->get();

            

                              
            if($grading_system[0]->type == 3 ){

                  $rv = DB::table('grading_system_ratingvalue')
                                    ->where('deleted',0)
                                    ->where('gsid',$grading_system[0]->id)
                                    ->orderBy('sort')
                                    ->get();

            }

          


            if(count($checkGrades) > 0){

                  $nogscount  = DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('grading_system_detail.deleted',0)
                                    ->count();

                  $widthAdditionalgs = false;

                  if($nogscount  != count($checkGrades)){

                        $widthAdditionalgs = true;

                  }
            
                  return view('superadmin.pages.gradingsystem.coregradingtable')
                              ->with('checkGrades',$checkGrades)
                              ->with('nogscount',$nogscount )
                              ->with('acadprog',$acaprogid )
                              ->with('ratingValue',$rv)
                              ->with('widthAdditionalgs',$widthAdditionalgs)
                              ->with('grading_system',$grading_system);;
               
            }
            else{

                  return 0;

            }
            
      }


      

      public static function genereate_student_core_value(
            $gsid = null,
            $studid = null,
            $sectionid = null,
            $subjectid = null,
            $quarter = null,
            $acaprogid = null
      ){

            //evaluate grading system
            $grading_system = GradingSystem::get_corevalue_setup($gsid, $acaprogid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            $activeSy = DB::table('sy')->where('isactive',1)->first();

            $proccesCount = 0;

            $grading_system_detail = DB::table('grading_system')
                        ->join('grading_system_detail',function($join){
                              $join->on('grading_system.id','=','grading_system_detail.headerid');
                              $join->where('grading_system_detail.deleted',0);
                        })
                        ->where('acadprogid', $acaprogid)
                        ->where('grading_system.id',$grading_system[0]->id)
                        ->where('grading_system.deleted',0)
                        ->select('grading_system_detail.id')
                        ->get();

            $studinfo = DB::table('studinfo')
                        ->where('id',$studid)
                        ->select('sectionid','levelid')
                        ->first();

            foreach($grading_system_detail as $item){

                  $gsgradescount = DB::table('grading_system_grades_cv')
                                    ->where('studid',$studid)
                                    ->where('syid',$activeSy->id)
                                    ->where('sectionid',$studinfo->sectionid)
                                    ->where('levelid',$studinfo->levelid)
                                    ->where('gsdid',$item->id)
                                    ->count();

                  if($gsgradescount == 0){

                        $proccesCount +=1;

                        $grading_system_detail = DB::table('grading_system_grades_cv')
                                                ->insert([
                                                      'studid'=>$studid,
                                                      'syid'=>$activeSy->id,
                                                      'gsdid'=>$item->id,
                                                      'sectionid'=>$studinfo->sectionid,
                                                      'levelid'=>$studinfo->levelid,
                                                      'createdby'=>auth()->user()->id,
                                                      'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);
                              
                  }

            }

            return $proccesCount;

      }

      public static function submit_student_core_value(
            $studid = null,
            $gradid = null,
            $field  = null,
            $value = 0
      ){

            try{

                  DB::table('grading_system_grades_cv')
                              ->where('studid',$studid)
                              ->where('id',$gradid)
                              ->update([
                                    $field=>$value,
                                    'updatedby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 1;

            }catch(\Exception $e){

                  DB::table('zerrorlogs')
                              ->insert([
                              'error'=>$e,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 0;

            }


      }
      


      
}
