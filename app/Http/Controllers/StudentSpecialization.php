<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class StudentSpecialization extends \App\Http\Controllers\Controller
{
      public static function subjects(Request $request){

            $all_subject = array();

            $subjects =  DB::table('subjects')
                              ->where('deleted',0)
                              ->where('isSP',1)
                              ->select(
                                    'subjdesc as text',
                                    'subjdesc',
                                    'subjcode',
                                    'subjects.id'      
                              )
                              ->get();


            return $subjects;
      }

      public static function all_student_ajax(Request $request){
            $syid = $request->get('syid');
            $subjid = $request->get('subjid');
            return self::all_student($syid,$subjid);
      }


      public static function all_student($syid = null, $subjid = null){

            $temp_gradelevel = DB::table('subject_plot')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('subjid',$subjid)
                                    ->select('levelid')
                                    ->get();

            $gradelevel = array();

            foreach($temp_gradelevel as $item){
                  array_push($gradelevel,$item->levelid);
            }

            $students = DB::table('enrolledstud')
                              ->where('enrolledstud.deleted',0)
                              ->whereIn('enrolledstud.levelid',$gradelevel)
                              ->where('enrolledstud.syid',$syid)
                              ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->select(
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'studid',
                                    'sid',
                                    'suffix'
                              )
                              ->get();

            return $students;

            foreach($students as $item){
                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                  $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                  $item->text=$item->sid.' - '.$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
            }
                
            return $students;

      }

      
      public static function subjects_studspec_ajax(Request $request){
            $syid = $request->get('syid');
            $subjid = $request->get('subjid');
            return self::subjects_studspec($syid,$subjid);
      }
      
      public static function subjects_studspec($syid = null, $subjid = null){

           
            $subjects_studspec = DB::table('subjects_studspec')
                                    ->where('subjects_studspec.deleted',0);

            if( $subjid != null){
                  $subjects_studspec->where('subjects_studspec.subjid',$subjid);
            }

            if( $syid != null){
                  $subjects_studspec->where('subjects_studspec.syid',$syid);
            }

            $students = $subjects_studspec->join('studinfo',function($join){
                                    $join->on('subjects_studspec.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('enrolledstud',function($join) use($syid){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('enrolledstud.deleted',0);
                                    $join->where('enrolledstud.syid',$syid);
                              })
                              ->join('gradelevel',function($join) use($syid){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('sections',function($join) use($syid){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'sid',
                                    'sections.sectionname',
                                    'levelname',
                                    'subjects_studspec.studid',
                                    'q1',
                                    'q2',
                                    'q3',
                                    'q4',
                                    'subjects_studspec.id',
                                    'subjects_studspec.studid',
                                    'suffix'
                              )
                              ->get();

            foreach($students as $item){
                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                  $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                  $item->text=$item->sid.' - '.$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                  $item->search = $item->sid.' '.$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle.' '.$item->sectionname.' '.$item->levelname;
            }

            return $students;
                  
      }


      public static function subjects_studspec_create(Request $request){

            try{

                  $validate = DB::table('subjects_studspec')
                                    ->where('studid',$request->get('studid'))
                                    ->where('subjid',$request->get('subjid'))
                                    ->where('syid',$request->get('syid'))
                                    ->where('deleted',0)
                                    ->count();

                  if($validate > 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Learner already exist!'
                        ]);
                  }

                  $validate =  $validate = DB::table('subjects_studspec')
                                    ->where('studid',$request->get('studid'))
                                    ->where('syid',$request->get('syid'))
                                    ->where('deleted',0)
                                    ->get();

                  $q1 = collect($validate)->where('q1',1)->count() > 0 ? 0 : $request->get('q1');
                  $q2 = collect($validate)->where('q2',1)->count() > 0 ? 0 : $request->get('q2');
                  $q3 = collect($validate)->where('q3',1)->count() > 0 ? 0 : $request->get('q3');
                  $q4 = collect($validate)->where('q4',1)->count() > 0 ? 0 : $request->get('q4');

                  if($q1 == 0 && $q2 == 0&& $q3 == 0 && $q4 == 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Cannot add learner!'
                        ]);
                  }

                  $id = DB::table('subjects_studspec')
                        ->insertGetId([
                              'studid'=>$request->get('studid'),
                              'subjid'=>$request->get('subjid'),
                              'syid'=>$request->get('syid'),
                              'q1'=>$q1,
                              'q2'=>$q2,
                              'q3'=>$q3,
                              'q4'=>$q4,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $syid = $request->get('syid');
                  $subjid = $request->get('subjid');

                  return array((object)[
                        'status'=>1,
                        'info'=>self::subjects_studspec($syid,$subjid),
                        'data'=>'Created Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
                  
      }

      public static function subjects_studspec_delete(Request $request){

            try{

                  DB::table('subjects_studspec')
                        ->where('deleted',0)
                        ->where('id',$request->get('id'))
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted successfully!'
                  ]);

            }catch(\Exception $e){
                 
                  return self::store_error($e);
            }
                  
      }


      public static function store_error($e){

            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            return array((object)[
                  'status'=>0,
                  'data'=>'Something went wrong!'
            ]);

      }

}
