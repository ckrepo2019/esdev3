<?php

namespace App\Models\Grading;
use DB;
use App\Models\Grading\GradingSystem;

use Illuminate\Database\Eloquent\Model;

class Preschool extends Model
{


      public static function get_advisory_class($teacherid,$acadprog){

            $advisoryclass = DB::table('sections')  
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
                            ->where('acadprogid',$acadprog)
                            ->select('sections.id','sectionname','sectiondetail.teacherid')
                            ->where('sections.deleted',0)
                            ->get();
    
    
            return  $advisoryclass;
    
      }
   
      public static function grade_student_preschool(
            $gsid = null,
            $teacherid = null
      ){

            $grading_system = array();
            $teacher_subjects = array();
            $sections = array();

            $grading_system = GradingSystem::evaluate_grading_system_preschool($gsid);


            if( $grading_system[0]->status == 1){

                  foreach($grading_system[0]->data as $item){

                        if($item->type == 1){
      
      
                              $subjects = self::teacher_assign_subjects($teacherid);
                   
                              if(count($subjects ) == 0){
                  
                                    $data = array((object)[
                                          'status'=>0,
                                          'data'=>"You are not assigned to a section."
                                    ]);
                  
                                    return $data;
                  
                              }
      
                              $sectionsDistinct = collect($subjects)->unique('sectionname');
                             
                              return view('superadmin.pages.gradingsystem.ps_per_grading')
                                                ->with('sections',$sectionsDistinct)
                                                ->with('subjects',$subjects);
      
                        }
      
                  }

                  if(count($grading_system[0]->data) > 1){


                        $data =  array((object)[
                              'status'=>0,
                              'data'=>"Mutiple grading system is active."
                        ]);
      
                        return $data;
                  
                  }

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            

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
                              ->where('acadprogid',2)
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

            $student = DB::table('studinfo')
                              ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->where('gradelevel.acadprogid',2);
                              })
                              ->whereIn('sectionid',$sectionsArray)
                              ->whereIn('studstatus',[1,2,4])
                              ->select('studinfo.firstname','studinfo.lastname','studinfo.id','sectionid')
                              ->get();

            return view('superadmin.pages.gradingsystem.psgrading')
                              ->with('grading_system',$grading_system)
                              ->with('sections',$sections)
                              ->with('students',$student);


      }


      public static function evaluate_student(
            $gsid = null,
            $studid = null,
            $sectionid = null
      ){

            $rv = [];
            $activeSy = DB::table('sy')->where('isactive',1)->first();

            //evaluate grading system
            $grading_system = GradingSystem::evaluate_grading_system_preschool($gsid);

            if( $grading_system[0]->status == 1){

                  $grading_system =  $grading_system[0]->data;

            }
            else{

                  return $grading_system;

            }

            $checkStatus = DB::table('grading_sytem_gradestatus')
                              ->where('sectionid',$sectionid)
                              ->where('subjid',0)
                              ->where('deleted',0)
                              ->where('syid',$activeSy->id)
                              ->select('q1status','q2status','q3status','q4status')
                              ->first();

            $checkGrades = DB::table('grading_system_pgrades')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system_pgrades.gsdid','=','grading_system_detail.id');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->join('grading_system',function($join) use($grading_system){
                                    $join->on('grading_system_detail.headerid','=','grading_system.id');
                                    $join->where('grading_system.deleted',0);
                                    $join->where('grading_system.id',$grading_system[0]->id);
                              })
                              ->where('grading_system_pgrades.deleted',0)
                              ->where('studid',$studid)
                              ->where('syid',$activeSy->id)
                              ->select(
                                    'grading_system_pgrades.id',
                                    'grading_system_pgrades.q1eval',
                                    'grading_system_pgrades.q2eval',
                                    'grading_system_pgrades.q3eval',
                                    'grading_system_pgrades.q4eval',
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

                  $lackinggsd = DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('grading_system_detail.deleted',0)
                                    ->count();

                  $widthAdditionalgs = false;

                  if($lackinggsd != count($checkGrades)){

                        $widthAdditionalgs = true;

                  }

                  return view('superadmin.pages.gradingsystem.pstable')
                              ->with('checkGrades',$checkGrades)
                              ->with('lackinggsd',$lackinggsd)
                              ->with('ratingValue',$rv)
                              ->with('checkStatus',$checkStatus)
                              ->with('widthAdditionalgs',$widthAdditionalgs)
                              ->with('grading_system',$grading_system);;
               
            }
            else{

                  return 0;

            }

      }

      public static function generate_student_grade_preschool(
            $gsid = null,
            $studid = null
      ){

            $grading_system = GradingSystem::evaluate_grading_system_preschool($gsid);

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
                        ->where('acadprogid',2)
                        ->where('grading_system.id',$grading_system[0]->id)
                        ->where('grading_system.deleted',0)
                        ->select('grading_system_detail.id')
                        ->get();

            $studinfo = DB::table('studinfo')
                        ->where('id',$studid)
                        ->select('sectionid','levelid')
                        ->first();

            foreach($grading_system_detail as $item){

                  $gsgradescount = DB::table('grading_system_pgrades')
                                    ->where('studid',$studid)
                                    ->where('syid',$activeSy->id)
                                    ->where('sectionid',$studinfo->sectionid)
                                    ->where('levelid',$studinfo->levelid)
                                    ->where('gsdid',$item->id)
                                    ->count();

                  if($gsgradescount == 0){

                        $proccesCount +=1;

                        $grading_system_detail = DB::table('grading_system_pgrades')
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

      public static function submit_student_grade_preschool(
            $studid = null,
            $gradid = null,
            $field  = null,
            $value = 0
      ){

            try{

                  DB::table('grading_system_pgrades')
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

      public static function teacher_assign_subjects($teacherid){

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
                                    $join->where('gradelevel.acadprogid',2);
                              })
                              ->join('subjects',function($join){
                                    $join->on('assignsubjdetail.subjid','=','subjects.id');
                                    $join->where('subjects.deleted',0);
                              })
                              ->select('sections.id','sectionname','subjdesc','subjid','subjcode','subjid')
                              ->get();


            return $subjects;

      }

      public static function get_grades($studid, $subjid, $sectionid, $syid, $quarter){

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;
            }

            $quarter_val = 'q'.$quarter.'eval';

            return DB::table('grading_system_pgrades')
                        ->where('studid',$studid)
                        ->where('sectionid',$sectionid)
                        ->leftJoin('grading_system_ratingvalue',function($join) use($quarter_val){
                              $join->on('grading_system_pgrades.'.$quarter_val,'=','grading_system_ratingvalue.id');
                        })
                        ->join('grading_system_detail',function($join){
                              $join->on('grading_system_pgrades.gsdid','=','grading_system_detail.id');
                              $join->where('grading_system_detail.deleted',0);
                        })
                        ->select(
                              'grading_system_detail.description',
                              'grading_system_detail.value as dvalue',
                              'grading_system_ratingvalue.value as quarter_val',
                        )
                        ->orderBy('grading_system_detail.sort')
                        ->get();

      }
     

}
