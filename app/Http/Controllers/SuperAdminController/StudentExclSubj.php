<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class StudentExclSubj extends \App\Http\Controllers\Controller
{

      public static function subjects(Request $request){

            $levelid = $request->get('levelid');
            // $strandid = $request->get('strandid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            if($levelid == 14 || $levelid == 15){

                  $subjects = DB::table('subject_plot')
                                    ->join('sh_subjects',function($join){
                                          $join->on('subject_plot.subjid','=','sh_subjects.id');
                                          $join->where('sh_subjects.deleted',0);
                                    })
                                    ->where('subject_plot.syid',$syid)
                                    ->where('subject_plot.semid',$semid)
                                    // ->where('subject_plot.syid',$strandid)
                                    ->where('subject_plot.deleted',0)
                                    ->where('subject_plot.levelid',$levelid)
                                    ->select(
                                          'subject_plot.subjid as id',
                                          'subjtitle as subjdesc',
                                          'subjcode',
                                          DB::raw("CONCAT(sh_subjects.subjcode,' - ',sh_subjects.subjtitle) as text")
                                    )
                                    ->distinct('subjid')
                                    ->get();

            }else{

                  $subjects = DB::table('subject_plot')
                                    ->join('subjects',function($join){
                                          $join->on('subject_plot.subjid','=','subjects.id');
                                          $join->where('subjects.deleted',0);
                                          $join->where('subjects.isCon',0);
                                    })
                                    ->where('subject_plot.syid',$syid)
                                    ->where('subject_plot.deleted',0)
                                    ->where('subject_plot.levelid',$levelid)
                                    
                                    ->select(
                                          'subject_plot.subjid as id',
                                          'subjdesc',
                                          'subjcode',
                                          DB::raw("CONCAT(subjects.subjcode,' - ',subjects.subjdesc) as text")
                                    )
                                    ->get();


            }

            return $subjects;
            
      }

      public static function gradelevel(Request $request){

            $gradelevel = DB::table('gradelevel')
                              ->where('gradelevel.deleted',0)
                              ->where('acadprogid','!=',6)
                              ->orderBy('sortid')
                              ->select(
                                    'id',
                                    'levelname as text'
                              )
                              ->get();

            return $gradelevel;
            
      }

      public static function sections(Request $request){

            $levelid = $request->get('levelid');

            $sections = DB::table('sections')
                              ->where('sections.deleted',0)
                              ->where('levelid',$levelid)
                              ->select(
                                    'id',
                                    'sectionname as text'
                              )
                              ->get();

            return $sections;
            
      }

      public static function student_exclsubj_delete(Request $request){

            $studid = $request->get('studid');
            $dataid = $request->get('dataid');

            try{

                  DB::table('student_exclsubj')
                        ->where('id',$dataid)
                        ->where('studid',$studid)
                        ->update([
                              'deleted'=>1,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'stdspcsynstat'=>3,
                              'stdspcsynstatdate'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Excluded Subject Removed!',
                  ]); 

            }catch(\Exception $e){

                  return self::store_error($e);

            }
      }


      public static function student_exclsubj_update(Request $request){

            $studid = $request->get('studid');
            $id = $request->get('id');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $sytoapplygrade = $request->get('sytoapplygrade');
            $semtoapplygrade = $request->get('semtoapplygrade');

            try{

                  $checking = DB::table('student_exclsubj')
                                    ->where('id','!=',$id)
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('subjid',$subjid)
                                    ->where('deleted',0)
                                    ->count();

                  if( $checking > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Subject Already Exist!',
                        ]); 
                  }

                  if($levelid == 14 || $levelid == 15){}
                  else{ $semid = 1; }

                  DB::table('student_exclsubj')
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                              'studid'=>$studid,
                              'levelid'=>$levelid,
                              'sectionid'=>$sectionid,
                              'subjid'=>$subjid,
                              'semid'=>$semid,
                              'syid'=>$syid,
                              'sytoapplygrade'=>$sytoapplygrade,
                              'semtoapplygrade'=>$semtoapplygrade,
                              'status'=>'ADDITIONAL',
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'stdspcsynstat'=>2,
                              'stdspcsynstatdate'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Excluded Subject Updated!',
                  ]); 

            }catch(\Exception $e){

                  return self::store_error($e);

            }
      }

      public static function student_exclsubj_create(Request $request){

            $studid = $request->get('studid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $sytoapplygrade = $request->get('sytoapplygrade');
            $semtoapplygrade = $request->get('semtoapplygrade');

            try{

                  $checking = DB::table('student_exclsubj')
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('subjid',$subjid)
                                    ->where('deleted',0)
                                    ->count();

                  if( $checking > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Subject Already Exist!',
                        ]); 
                  }

                  if($levelid == 14 || $levelid == 15){}
                  else{ $semid = 1; }

                  DB::table('student_exclsubj')
                        ->insert([
                              'studid'=>$studid,
                              'levelid'=>$levelid,
                              'sectionid'=>$sectionid,
                              'subjid'=>$subjid,
                              'semid'=>$semid,
                              'syid'=>$syid,
                              'sytoapplygrade'=>$sytoapplygrade,
                              'semtoapplygrade'=>$semtoapplygrade,
                              'status'=>'ADDITIONAL',
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'stdspcsynstat'=>0,
                              'stdspcsynstatdate'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Excluded Subject Added!',
                  ]); 

            }catch(\Exception $e){

                  return self::store_error($e);

            }
      }
      
      public static function student_exclsubj(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $all_students = array();

            $students = DB::table('student_exclsubj')
                        ->join('studinfo',function($join){
                              $join->on('student_exclsubj.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })  
                        ->join('enrolledstud',function($join) use($syid){
                              $join->on('student_exclsubj.studid','=','enrolledstud.studid');
                              $join->where('enrolledstud.deleted',0);
                              $join->where('enrolledstud.syid',$syid);
                              $join->whereIn('enrolledstud.studstatus',[1,2,4]);
                        })  
                        ->join('sections',function($join) use($syid){
                              $join->on('student_exclsubj.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        }) 
                        ->join('gradelevel',function($join) use($syid){
                              $join->on('student_exclsubj.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('gradelevel.acadprogid','!=',5);
                        }) 
                        ->join('subjects',function($join) use($syid){
                              $join->on('student_exclsubj.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })  
                        ->leftJoin('sy',function($join) use($syid){
                              $join->on('student_exclsubj.sytoapplygrade','=','sy.id');
                        }) 
                        ->leftJoin('semester',function($join) use($syid){
                              $join->on('student_exclsubj.semtoapplygrade','=','semester.id');
                        }) 
                        ->where('student_exclsubj.deleted',0)
                        ->where('student_exclsubj.syid',$syid)
                        ->select(
                              'subjcode',
                              'subjdesc',
                              'levelname',
                              'sections.sectionname',
                              'sid',
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'enrolledstud.studid',
                              'student_exclsubj.id',
                              DB::raw("CONCAT(subjects.subjdesc,' - ',subjects.subjcode) as subjtext"),
                              'status',
                              'sydesc',
                              'semester',
                              'sytoapplygrade',
                              'semtoapplygrade',
                              'student_exclsubj.sectionid',
                              'student_exclsubj.levelid',
                              'student_exclsubj.subjid'
                        )
                        ->get();

            foreach($students as $item){
                  array_push( $all_students , $item);
            }

            $students = DB::table('student_exclsubj')
                        ->join('studinfo',function($join){
                              $join->on('student_exclsubj.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })  
                        ->join('sh_enrolledstud',function($join) use($syid,$semid){
                              $join->on('student_exclsubj.studid','=','sh_enrolledstud.studid');
                              $join->where('sh_enrolledstud.deleted',0);
                              $join->where('sh_enrolledstud.syid',$syid);
                              $join->where('sh_enrolledstud.semid',$semid);
                              $join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                        })  
                        ->join('sections',function($join) use($syid){
                              $join->on('student_exclsubj.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        }) 
                        ->join('gradelevel',function($join) use($syid){
                              $join->on('student_exclsubj.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('gradelevel.acadprogid',5);
                        }) 
                        ->join('sh_subjects',function($join) use($syid){
                              $join->on('student_exclsubj.subjid','=','sh_subjects.id');
                              $join->where('sh_subjects.deleted',0);
                        })  
                        ->leftJoin('sy',function($join) use($syid){
                              $join->on('student_exclsubj.sytoapplygrade','=','sy.id');
                        }) 
                        ->leftJoin('semester',function($join) use($syid){
                              $join->on('student_exclsubj.semtoapplygrade','=','semester.id');
                        })
                        ->where('student_exclsubj.syid',$syid)
                        ->where('student_exclsubj.semid',$semid)
                        ->where('student_exclsubj.deleted',0)
                        ->select(
                              'subjcode',
                              'subjtitle as subjdesc',
                              'levelname',
                              'sections.sectionname',
                              'sid',
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'sh_enrolledstud.studid',
                              DB::raw("CONCAT(sh_subjects.subjtitle,' - ',sh_subjects.subjcode) as subjtext"),
                              'student_exclsubj.id',
                              'status',
                              'sydesc',
                              'semester',
                              'sytoapplygrade',
                              'semtoapplygrade',
                              'student_exclsubj.sectionid',
                              'student_exclsubj.levelid',
                              'student_exclsubj.subjid'
                        )
                        ->get();

            foreach($students as $item){
                  array_push( $all_students , $item);
            }

            foreach($all_students as $item){
                  $temp_middle = '';
                  $temp_suffix = '';
                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }
                  $item->full_name = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                  $item->text =  $item->sid.' - '.$item->full_name;
            }

            return $all_students;

      }

      public static function students(Request $request){

            $semid = $request->get('semid');
            $syid = $request->get('syid');

            $all_students = array();

            $students = DB::table('enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.deleted',0)
                              ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->select(
                                    'enrolledstud.sectionid',
                                    'acadprogid',
                                    'enrolledstud.levelid',
                                    'sid',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'enrolledstud.studid',
                                    'studinfo.id'
                              )
                              ->get();

            foreach($students as $item){
                  array_push( $all_students , $item);
            }

            $students = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                              ->select(
                                    'sh_enrolledstud.sectionid',
                                    'acadprogid',
                                    'sh_enrolledstud.levelid',
                                    'sid',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'sh_enrolledstud.studid',
                                    'studinfo.id'
                              )
                              ->get();

            foreach($students as $item){
                  array_push( $all_students , $item);
            }

            foreach($all_students as $item){
                  $temp_middle = '';
                  $temp_suffix = '';
                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }
                  $item->full_name = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                  $item->text =  $item->sid.' - '.$item->full_name;
            }

            return $all_students;

      }


      public static function syncNew(Request $request){
            try{
    
                $tablename = $request->get('tablename');
                $data = $request->get('data');
    
                DB::table($tablename)   
                    ->insert($data);

                  return array((object)[
                        'status'=>1,
                        'message'=>'New Info Created!'
                  ]);
    
            }catch(\Exception $e){
                return self::store_error($e);
            }
        }
    
        public static function syncUpdate(Request $request){
            try{
    
                $tablename = $request->get('tablename');
                $data = $request->get('data');
                $dataid = $data['id'];
    
                DB::table($tablename)
                    ->take(1)
                    ->where('id',$dataid)
                    ->update($data);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Info Updated!'
                  ]);
    
            }catch(\Exception $e){
                return self::store_error($e);
            }
        }
    
        public static function syncDelete(Request $request){
            try{
    
                $tablename = $request->get('tablename');
                $data = $request->get('data');
                $dataid = $data['id'];
    
                DB::table($tablename)
                    ->take(1)
                    ->where('id',$dataid)
                    ->update([
                        'deleted'=>1,
                        'deleteddatetime'=>$data['deleteddatetime']
                    ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Info Deleted!'
                  ]);
    
            }catch(\Exception $e){
                return self::store_error($e);
            }
        }
    
        public static function getNewInfo(Request $request){
            $tablename = $request->get('tablename');
    
            $table_date = DB::table($tablename)
                            ->where('stdspcsynstat',0)
                            ->get();
    
            return $table_date;
        }
    
        public static function getUpdateInfo(Request $request){
            $tablename = $request->get('tablename');    
    
            $table_date = DB::table($tablename)
                            ->where('stdspcsynstat',2)
                            ->get();
    
            return $table_date;
        }
    
        public static function getDeleteInfo(Request $request){
            $tablename = $request->get('tablename');
    
            $table_date = DB::table($tablename)
                            ->where('stdspcsynstat',3)
                            ->get();
    
            return $table_date;
        }
    
        public static function getUpdateStat(Request $request){
            $tablename = $request->get('tablename');
            $data = $request->get('data');
    
            DB::table($tablename)
                            ->where('id', $data['id'])
                            ->take(1)
                            ->update([
                                'stdspcsynstat'=>1,
                                'stdspcsynstatdate'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
        }
        
        
        public static function store_error($e){
            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        // 'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'icon'=>'error',
                  'message'=>'Something went wrong!'
            ]);
        }
}
