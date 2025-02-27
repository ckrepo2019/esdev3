<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;

class SubjectPlotController extends \App\Http\Controllers\Controller
{


      public static function get_subjcoor(Request $request){

            $syid = $request->get('syid');
            $levelid = $request->get('levelid');

            $acad = self::get_acad($syid);

            $teachers = DB::table('usertype')
                              ->where('refid',22)
                              ->join('teacheracadprog',function($join) use($syid){
                                    $join->on('usertype.id','=','teacheracadprog.acadprogutype');
                                    $join->where('teacheracadprog.deleted',0);
                                    $join->where('syid',$syid);
                              })
                              ->join('teacher',function($join){
                                    $join->on('teacheracadprog.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($levelid){
                                    $join->on('teacheracadprog.acadprogid','=','gradelevel.acadprogid');
                                    $join->where('gradelevel.deleted',0);
                                    $join->where('gradelevel.id',$levelid);
                              })
                              ->select(
                                    DB::raw("CONCAT(teacher.tid,' - ',teacher.lastname,', ',teacher.firstname) as text"),
                                    'teacher.id'
                              )
                              ->get();

            return $teachers;
                  


      }


      public static function get_gradelevel(Request $request){

            $syid = $request->get('syid');

            $acad = self::get_acad($syid);

            if(Session::get('currentPortal') == 2){
                  
                  $teacherid = DB::table('teacher')
                                    ->where('deleted',0)
                                    ->where('tid',auth()->user()->email)
                                    ->first();
      
                  $gradelevel = DB::table('gradelevel')
                              ->where('deleted',0)
                              ->whereIn('acadprogid',$acad)
                              ->where('gradelevel.acadprogid','!=',6)
                              ->orderBy('sortid')
                              ->select(
                                    'gradelevel.levelname as text',
                                    'gradelevel.id',
                                    'acadprogid'
                              )
                              ->get(); 
      
            }else if(Session::get('currentPortal') == 17){
                  $gradelevel = DB::table('gradelevel')
                                    ->where('deleted',0)
                                    ->where('gradelevel.acadprogid','!=',6)
                                    ->orderBy('sortid')
                                    ->select(
                                          'gradelevel.levelname as text',
                                          'gradelevel.id',
                                          'acadprogid'
                                    )
                                    ->get(); 
            }else{
      
                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->select('id')
                                    ->first()
                                    ->id;
      
                  $gradelevel = DB::table('gradelevel')
                              ->where('deleted',0)
                              ->where('gradelevel.acadprogid','!=',6)
                              ->whereIn('gradelevel.acadprogid',$acad)
                              ->orderBy('sortid')
                              ->select(
                                    'gradelevel.levelname as text',
                                    'gradelevel.id',
                                    'acadprogid'
                              )
                              ->get(); 
            }


            return $gradelevel;

      }


      public static function get_acad($syid = null){

            if(auth()->user()->type == 17){
                  $acadprog = DB::table('academicprogram')
                                          ->select('id')
                                          ->get();
            }
            else{

                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->select('id')
                                    ->first()
                                    ->id;

                  // if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){

                  //       $acadprog = DB::table('academicprogram')
                  //                         ->where('principalid',$teacherid)
                  //                         ->get();

                  // }else{

                        $acadprog = DB::table('teacheracadprog')
                                    ->where('teacherid',$teacherid)
                                    ->where('acadprogutype',Session::get('currentPortal'))
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->select('acadprogid as id')
                                    ->distinct('acadprogid')
                                    ->get();
                  // }
            }


            $acadprog_list = array();
            foreach($acadprog as $item){
                  array_push($acadprog_list,$item->id);
            }

            return $acadprog_list;

      }

      public static function update_component_percentage(Request $request){
            $id = $request->get('id');
            try{

                  $ww = $request->get('ww_input');
                  $pt = $request->get('pt_input');
                  $qa = $request->get('qa_input');
                  $ct = $request->get('ct_input');
                  
                  $check = DB::table('subject_gradessetup')
                              ->where('ww',$ww)
                              ->where('pt',$pt)
                              ->where('qa',$qa)
                              ->where('comp4',$ct)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Exist!',
                        ]); 
                  }


                  $check = DB::table('subject_plot')
                              ->where('gradessetup',$id)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Used!',
                        ]); 
                  }

                  if($check == 0){

                        $description = '';

                        if($ww != null){
                              $description .= 'WW'.$ww.' ';
                        }
                        if($pt != null){
                              $description .= 'PT'.$pt.' ';
                        }

                        if($qa != null){
                              $description .= 'QA'.$qa.' ';
                        }

                        if($ct != null){
                              $description .= 'CG'.$ct.' ';
                        }

                        DB::table('subject_gradessetup')
                              ->where('id',$id)
                              ->update([
                                    'description'=>$description,
                                    'ww'=>$ww,
                                    'pt'=>$pt,
                                    'qa'=>$qa,
                                    'comp4'=>$ct,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return array((object)[
                              'status'=>1,
                              'message'=>'Component Percentage Updated!',
                        ]); 

                  }else{
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Exist!',
                        ]); 
                  }

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function delete_component_percentage(Request $request){
            $id = $request->get('id');
            $check = DB::table('subject_plot')
                              ->where('gradessetup',$id)
                              ->where('deleted',0)
                              ->count();


            try{
                  if($check == 0){

                        DB::table('subject_gradessetup')
                              ->where('id',$id)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return array((object)[
                              'status'=>1,
                              'message'=>'Component Percentage Deleted!',
                        ]); 

                  }else{
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Used!',
                        ]); 
                  }
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function create_component_percentage(Request $request){
            try{

                  $ww = $request->get('ww_input');
                  $pt = $request->get('pt_input');
                  $qa = $request->get('qa_input');
                  $ct = $request->get('ct_input');
                  
                  $check = DB::table('subject_gradessetup')
                              ->where('ww',$ww)
                              ->where('pt',$pt)
                              ->where('qa',$qa)
                              ->where('comp4',$ct)
                              ->where('deleted',0)
                              ->count();

                  if($check == 0){

                        $description = '';

                        if($ww != null){
                              $description .= 'WW'.$ww.' ';
                        }
                        if($pt != null){
                              $description .= 'PT'.$pt.' ';
                        }

                        if($qa != null){
                              $description .= 'QA'.$qa.' ';
                        }

                        if($ct != null){
                              $description .= 'CG'.$ct.' ';
                        }


                        DB::table('subject_gradessetup')
                              ->insert([
                                    'description'=>$description,
                                    'ww'=>$ww,
                                    'pt'=>$pt,
                                    'qa'=>$qa,
                                    'comp4'=>$ct,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return array((object)[
                              'status'=>1,
                              'message'=>'Component Percentage Created!',
                        ]);

                  }else{
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Exist!',
                        ]);
                  }

                

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }



      public static function get_component_percentage(){
            return DB::table('subject_gradessetup')
                        ->where('deleted',0)
                        ->select(
                              'description as text',
                              'description',
                              'ww',
                              'pt',
                              'qa',
                              'comp4',
                              'id'
                        )
                        ->get();

      }

      public static function all_subjects_ajax(Request $request){
            $acadprog = $request->get('acadprog');
            $all_subjects = array();

            $strandid  = $request->get('strandid');
            $syid = $request->get('syid');
            $module = $request->get('module');


            if($module == 'subjectplot'){

                  $addedsubjects = DB::table('subject_plot')
                                          ->where('syid',$syid)
                                          ->where('strandid',$strandid)
                                          ->where('deleted',0)
                                          ->select('subjid')
                                          ->whereNotNull('subjid')
                                          ->get();

                  $addedsubjects = collect($addedsubjects)->pluck('subjid');

                 

            }else{
                  $addedsubjects = array();
            }

            if($acadprog == 5)
            {
                  $subjects = DB::table('sh_subjects')
                                    ->where('deleted',0)
                                    ->select(
                                          'id',
                                          'subjtitle as subjdesc',
                                          'subjcode'
                                    )
                                    ->whereNotIn('id',$addedsubjects)
                                    ->get();

                  foreach($subjects as $item){
                        $item->acadprogid = 12;
                        $item->text = $item->subjcode.' - '.$item->subjdesc;
                        $item->subjCom = null;
                        array_push( $all_subjects, $item);
                  }
            }else{
                  $subjects = DB::table('subjects')
                                    ->where('deleted',0)
                                    ->where(function($query) use($acadprog){
                                          $query->where('acadprogid',$acadprog);
                                          $query->orWhere('acadprogid',null);
                                    })
                                    ->select(
                                          'id',
                                          'subjdesc',
                                          'subjcode',
                                          'acadprogid',
                                          'subjCom',
                                          'isCon'
                                    )
                                    ->get();

                  foreach($subjects as $item){
                        $item->text = $item->subjcode.' - '.$item->subjdesc;
                        array_push( $all_subjects, $item);
                  }
            }   
            return $all_subjects;
      }


      // //attendance setup start
      public static function list_ajax(Request $request){
            $id = $request->get('id');
            $subjid = $request->get('subjid');
            $levelid = $request->get('levelid');
            $sort = $request->get('sort');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $strandid = $request->get('strandid');
            $acadprog = $request->get('acadprog');
            $issp = $request->get('issp');

            return self::list($id, $subjid, $levelid, $sort, $syid, $semid, $strandid,array(),$issp,$acadprog);
      }

      public static function create_ajax(Request $request){
            $subjid = $request->get('subjid');
            $levelid = $request->get('levelid');
            $sort = $request->get('sort');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $strandid = $request->get('strandid');
            $acadprog = $request->get('acadprog');
            $setupid = $request->get('setupid');
            $isforsp = $request->get('isforsp');
            
            return self::create($subjid, $levelid, $sort, $syid, $semid, $strandid, $setupid, $isforsp);
      }
      public static function update_ajax(Request $request){
            $id = $request->get('id');
            $syid = $request->get('syid');
            $sort = $request->get('sort');
            $levelid = $request->get('levelid');
            $setupid = $request->get('setupid');
            $subjcoor = $request->get('subjcoor');
            $isforsp = $request->get('isforsp');
            $apptocon = $request->get('apptocon');

            return self::update($id, $syid, $sort, $levelid, $setupid, $subjcoor, $isforsp, $apptocon);
      }
      public static function delete_ajax(Request $request){
            $id = $request->get('id');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $strandid = $request->get('strandid');
            $semid = $request->get('semid');
            $notallow = $request->get('notallow');
            return self::delete($id,$syid,$semid,$levelid,$strandid,$notallow);
      }
      //attendance setup end

      //proccess
      public static function create(
            $subjid = null, 
            $levelid = null, 
            $sort = null, 
            $syid = null, 
            $semid = null, 
            $strandid = null,
            $setupid = null,
            $isforsp = null,
            $copyto = false
      ){
            try{

                  $count = '';

                  if($levelid == 14 || $levelid == 15){
                        $check = DB::table('subject_plot')
                                    ->where('subjid',$subjid)
                                    ->where('syid',$syid)
                                    ->where('strandid',$strandid)
                                    ->where('deleted',0)
                                    ->get();

                        if(count($check) > 0){
                              return array((object)[
                                    'status'=>0,
                                    'data'=>'Subject already exist for this strand!',
                              ]);
                        }
                  }
                 

                 
                 
                  $subjplot_id = DB::table('subject_plot')
                        ->insertGetId([
                              'isforsp'=>$isforsp,
                              'syid'=>$syid,
                              'semid'=>$semid,
                              'subjid'=>$subjid,
                              'strandid'=>$strandid,
                              'plotsort'=>$sort.$count,
                              'levelid'=>$levelid,
                              'gradessetup'=>$setupid,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $year = DB::table('sy')->where('id',$syid)->first()->sydesc;

                  $message = auth()->user()->name.' added a subject plot for school year '. $year;
                  self::create_logs($message,$subjplot_id);

                  if( ( $levelid != 14 && $levelid != 15 ) && !$copyto){

                        
                        $tem_subj_info = DB::table('subjects')
                                    ->where('id',$subjid)
                                    ->where('deleted',0)
                                    ->first();

                        if($tem_subj_info->isCon == 1){
                              $count = 0;
                        }
                  

                        $subj = DB::table('subjects')
                                    ->where('subjCom',$subjid)
                                    ->where('deleted',0)
                                    ->select('id')
                                    ->get();

                        $count = 1;
                        foreach($subj as $item){

                              $check = DB::table('subject_plot')
                                          ->where('syid',$syid)
                                          ->where('subjid',$item->id)
                                          ->where('levelid',$levelid)
                                          ->where('deleted',0)
                                          ->count();

                              if($check == 0){
                                    
                                    $subjplot_id_com = DB::table('subject_plot')
                                                      ->insertGetId([
                                                            'isforsp'=>$isforsp,
                                                            'syid'=>$syid,
                                                            'semid'=>$semid,
                                                            'subjid'=>$item->id,
                                                            'strandid'=>$strandid,
                                                            'plotsort'=>$sort.$count,
                                                            'levelid'=>$levelid,
                                                            'gradessetup'=>$setupid,
                                                            'createdby'=>auth()->user()->id,
                                                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                      ]);
                                                      
                                    $message = auth()->user()->name.' added a subject plot for school year '. $year;
                                    self::create_logs($message,$subjplot_id_com);

                              }

                              $count += 1;
                        }

                  }


                  $info = self::list(null,null,$levelid,null,$syid,$semid,$strandid);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'id'=> $subjplot_id,
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }


      public static function copy_to(Request $request){

            $gradelevel_to = $request->get('gradelevel_to');
            $gradelevel_from = $request->get('gradelevel_from');
            $syid_to = $request->get('syid_to');
            $syid_from = $request->get('syid_from');
            $semid = $request->get('semid');
            $strandid = $request->get('strandid');
            $strand_to = $request->get('strand_to');


            if($gradelevel_from == 14 || $gradelevel_from == 15){
                  $subjects = DB::table('subject_plot')
                                    ->where('levelid',$gradelevel_from)
                                    ->where('syid',$syid_from)
                                    ->where('semid',$semid)
                                    ->where('strandid',$strandid)
                                    ->where('deleted',0)
                                    ->get();
            }else{

                  $subjects = DB::table('subject_plot')
                                    ->join('subjects',function($join){
                                          $join->on('subject_plot.subjid','=','subjects.id');
                                          $join->where('subjects.deleted',0);
                                          $join->whereNull('subjCom');
                                    })
                                    ->where('subject_plot.levelid',$gradelevel_from)
                                    ->where('subject_plot.syid',$syid_from)
                                    ->where('subject_plot.semid',$semid)
                                    ->where('subject_plot.strandid',$strandid)
                                    ->where('subject_plot.deleted',0)
                                    ->select('subject_plot.*')
                                    ->get();
            }


            $copy_count = 0;

            foreach($subjects as $item){

                  $temp_gradelevel = $gradelevel_to != null ?  $gradelevel_to : $item->levelid;
                  $temp_sy = $syid_to != null ? $temp_sy = $syid_to : $item->syid;
                  $temp_strand =  $strand_to != null ? $strand_to : $item->strandid;

                  $check = DB::table('subject_plot')
                                    ->where('levelid',$temp_gradelevel)
                                    ->where('syid',$temp_sy)
                                    ->where('semid',$item->semid)
                                    ->where('strandid',$temp_strand)
                                    ->where('subjid',$item->subjid)
                                    ->where('deleted',0)
                                    ->count();

                  if($check == 0){

                        $data = self::create(
                                    $item->subjid,
                                    $temp_gradelevel,
                                    $item->plotsort,
                                    $temp_sy,
                                    $item->semid,
                                    $temp_strand,
                                    $item->gradessetup,
                                    $item->isforsp,
                                    true
                              );

                        $copy_count += 1;
                       
                  }

            }

            if($gradelevel_from != 14 && $gradelevel_from != 15){
        
                  $subjects = DB::table('subject_plot')
                                    ->join('subjects',function($join){
                                          $join->on('subject_plot.subjid','=','subjects.id');
                                          $join->where('subjects.deleted',0);
                                          $join->whereNotNull('subjCom');
                                    })
                                    ->where('subject_plot.levelid',$gradelevel_from)
                                    ->where('subject_plot.syid',$syid_from)
                                    ->where('subject_plot.semid',$semid)
                                    ->where('subject_plot.strandid',$strandid)
                                    ->where('subject_plot.deleted',0)
                                    ->select('subject_plot.*')
                                    ->get();

                  foreach($subjects as $item){
      
                        $temp_gradelevel = $gradelevel_to != null ?  $gradelevel_to : $item->levelid;
                        $temp_sy = $syid_to != null ? $temp_sy = $syid_to : $item->syid;
                        $temp_strand =  $strand_to != null ? $strand_to : $item->strandid;
      
                        $check = DB::table('subject_plot')
                                          ->where('levelid',$temp_gradelevel)
                                          ->where('syid',$temp_sy)
                                          ->where('semid',$item->semid)
                                          ->where('strandid',$temp_strand)
                                          ->where('subjid',$item->subjid)
                                          ->where('deleted',0)
                                          ->count();
      
                        if($check == 0){
      
                              $data = self::create(
                                          $item->subjid,
                                          $temp_gradelevel,
                                          $item->plotsort,
                                          $temp_sy,
                                          $item->semid,
                                          $temp_strand,
                                          $item->gradessetup,
                                          $item->isforsp,
                                          true
                                    );
      
                              $copy_count += 1;
                              
                        }
      
                  }
            }


           

            return array((object)[
                  'status'=>1,
                  'data'=>$copy_count.' Subjects Added!',
            ]);

      }

      public static function update(
            $id = null,
            $syid = null, 
            $sort = null,
            $levelid = null,
            $setupid = null,
            $subjcoor = null,
            $isforsp = null,
            $apptocon = 0
      ){
            try{
                  DB::table('subject_plot')
                        ->take(1)
                        ->where('id',$id)
                        ->where('deleted',0)
                        ->update([
                              'isforsp'=>$isforsp,
                              'subjcoor'=>$subjcoor,
                              'plotsort'=>$sort,
                              'gradessetup'=> $setupid,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  $year = DB::table('sy')->where('id',$syid)->first()->sydesc;
                  $message = auth()->user()->name.' added a subject plot for school year '. $year;

                  $subjid = DB::table('subject_plot')
                              ->where('deleted',0)
                              ->where('id',$id)
                              ->select('subjid')
                              ->first()
                              ->subjid;

                  if($levelid != 14 && $levelid != 15){

                        $subj = DB::table('subjects')
                                    ->where('subjCom',$subjid)
                                    ->where('deleted',0)
                                    ->select('id')
                                    ->get();

                        foreach($subj as $item){

                              $check_if_exist = DB::table('subject_plot')
                                                      ->where('syid',$syid)
                                                      ->where('levelid',$levelid)
                                                      ->where('subjid',$item->id)
                                                      ->where('deleted',0)
                                                      ->count();


                              if($check_if_exist == 0){

                                    $subjplot_id_com = DB::table('subject_plot')
                                          ->insertGetId([
                                                'isforsp'=>$isforsp,
                                                'subjcoor'=>$subjcoor,
                                                'syid'=>$syid,
                                                'semid'=>null,
                                                'subjid'=>$item->id,
                                                'strandid'=>null,
                                                'plotsort'=>$sort,
                                                'levelid'=>$levelid,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                                    $message = auth()->user()->name.' added a subject plot for school year '. $year;
                                    self::create_logs($message,$subjplot_id_com);

                              }

                        }
                              
                        //apply other subject percentage component to all subject component if apply to 
                        // other componenents is checked
                        if($apptocon == 1){

                              $subj = DB::table('subjects')
                                          ->where('id',$subjid)
                                          ->where('deleted',0)
                                          ->select(
                                                'id',
                                                'subjCom'
                                          )
                                          ->first();

                              if($subj->subjCom != null){

                                    

                                    $subj = DB::table('subjects')
                                                ->where('subjCom',$subj->subjCom)
                                                ->where('deleted',0)
                                                ->select('id')
                                                ->get();

                                    foreach($subj as $item){

                                          $subjplot_id_com = DB::table('subject_plot')
                                                ->where('syid',$syid)
                                                ->where('levelid',$levelid)
                                                ->where('subjid',$item->id)
                                                ->where('deleted',0)
                                                ->update([
                                                      'gradessetup'=> $setupid,
                                                      'updatedby'=>auth()->user()->id,
                                                      'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);

                                          $message = auth()->user()->name.' update a subject plot for school year '. $year;
                                          self::create_logs($message,$subjplot_id_com);

                                    }

                              }
                        }

                  }
                  
                  self::create_logs($message,$id);
                  $temp_info = self::list($id,null,$levelid);
                
                  $info = self::list(null,null,$temp_info[0]->levelid,null,$temp_info[0]->syid,$temp_info[0]->semid,$temp_info[0]->strandid);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!',
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      
      public static function delete(
            $id = null,
            $syid = null,
            $semid = null,
            $levelid = null,
            $strandid = null,
            $notallow = 0
      ){
            try{

             

                  $temp_info = self::list($id,null,$levelid,null,null,null,null,array(),true);

                  if($levelid != 14 && $levelid != 15){
                       
                        $sched = DB::table('classsched')
                                    ->join('sections',function($join){
                                          $join->on('classsched.sectionid','=','sections.id');
                                          $join->where('sections.deleted',0);
                                    })
                                    ->where('classsched.syid',$syid)
                                    ->where('classsched.subjid',$temp_info[0]->subjid)
                                    ->where('classsched.glevelid',$levelid)
                                    ->where('classsched.deleted',0)
                                    ->count();
                                   
                        if( $sched > 0 && $notallow == 0){
                              return array((object)[
                                    'status'=>0,
                                    'data'=>'Plot contains schedule!',
                              ]);
                        }
                  
                  }else{

                              
                        

                        

                        $temp_sched = DB::table('sh_classsched')
                                          ->join('sections',function($join){
                                                $join->on('sh_classsched.sectionid','=','sections.id');
                                                $join->where('sections.deleted',0);
                                          })
                                          ->join('sh_classscheddetail',function($join){
                                                $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                                                $join->where('sh_classscheddetail.deleted',0);
                                          })
                                          ->where('sh_classsched.syid',$syid)
                                          ->where('sh_classsched.semid',$semid)
                                          ->where('sh_classsched.subjid',$temp_info[0]->subjid)
                                          ->where('sh_classsched.glevelid',$levelid)
                                          ->where('sh_classsched.deleted',0)
                                          ->select('sectionid')
                                          ->get();

                        $sched = 0;

                        foreach($temp_sched as $item){

                              $section_block = DB::table('sh_sectionblockassignment')
                                                      ->join('sh_block',function($join) use($strandid){
                                                            $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                                            $join->where('sh_block.deleted',0);
                                                            $join->where('sh_block.strandid',$strandid);
                                                      })
                                                      ->where('sectionid',$item->sectionid)
                                                      ->where('sh_sectionblockassignment.deleted',0)
                                                      ->get();

                              if(count($section_block) > 0){
                                    $sched = 1;
                              }

                              $plotinfo = DB::table('subject_plot')
                                          ->where('id',$id)
                                          ->first();

                              $sectionstrands = DB::table('sh_sectionblockassignment')
                                                      ->join('sh_block',function($join){
                                                            $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                                            $join->where('sh_block.deleted',0);
                                                      })
                                                      ->where('sectionid',$item->sectionid)
                                                      ->where('sh_sectionblockassignment.deleted',0)
                                                      ->where('syid',$syid)
                                                      ->select(
                                                            'strandid'
                                                      )
                                                      ->get();

                              $checksubjectplot = DB::table('subject_plot')
                                                      ->where('syid',$plotinfo->syid)
                                                      ->where('deleted',0)
                                                      ->where('semid',$plotinfo->semid)
                                                      ->where('subjid',$plotinfo->subjid)
                                                      ->where('levelid',$plotinfo->levelid)
                                                      ->whereIn('strandid',collect($sectionstrands)->pluck('strandid'))
                                                      ->count();

                              if( $sched > 0 && $notallow == 0){
                                    if( $checksubjectplot == 1){
                                          return array((object)[
                                                'status'=>0,
                                                'data'=>'Subject contains schedule!',
                                          ]);
                                    }
                              }
                        }
   
                        if( $sched > 0 && $notallow == 0){
                              return array((object)[
                                    'status'=>3,
                                    'data'=>'Subject contains schedule!',
                              ]);
                        }

                  }
                  

                  DB::table('subject_plot')
                        ->take(1)
                        ->where('id',$id)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  $year = DB::table('sy')->where('id',$syid)->first()->sydesc;
                  $message = auth()->user()->name.' removed a subject plot for school year '. $year;
                  self::create_logs($message,$id);


                  if($levelid != 14 && $levelid != 15){

                        $subj = DB::table('subjects')
                                    ->where('subjCom',$temp_info[0]->subjid)
                                    ->where('deleted',0)
                                    ->select('id')
                                    ->get();

                        foreach($subj as $item){

                              DB::table('subject_plot')
                                    ->take(1)
                                    ->where('syid',$syid)
                                    ->where('subjid',$item->id)
                                    ->where('levelid',$levelid)
                                    ->where('deleted',0)
                                    ->update([
                                          'deleted'=>1,
                                          'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'deletedby'=>auth()->user()->id
                                    ]);
                          
                        }

                  }else{


                        if($notallow  == 0){
                              $temp_sched = DB::table('sh_classsched')
                                          ->where('sh_classsched.syid',$syid)
                                          ->where('sh_classsched.semid',$semid)
                                          ->where('sh_classsched.subjid',$temp_info[0]->subjid)
                                          ->where('sh_classsched.glevelid',$levelid)
                                          ->where('sh_classsched.deleted',0)
                                          ->select(
                                              'sectionid',
                                              'id'
                                             )
                                          ->get();

                              foreach($temp_sched as $item){

                                    $section_block = DB::table('sh_sectionblockassignment')
                                                            ->join('sh_block',function($join) use($strandid){
                                                                  $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                                                  $join->where('sh_block.deleted',0);
                                                                  $join->where('sh_block.strandid',$strandid);
                                                            })
                                                            ->where('sectionid',$item->sectionid)
                                                            ->where('sh_sectionblockassignment.deleted',0)
                                                            ->get();

                                    if(count($section_block) > 0){
                                          
                                          DB::table('sh_classsched')
                                          ->where('id',$item->id)
                                          ->take(1)
                                          ->update([
                                                'deleted'=>1,
                                                'deletedby'=>auth()->user()->id
                                                ]);
                                          
                                          
                                    }

                              }
                        }
                        
                    }
                        
                  $info = self::list(null,null,$levelid,null,$syid,$semid,$strandid);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!',
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return $e;
                  return self::store_error($e);
            }
      }

      
      //data
      public static function list( 
            $id = null,
            $subjid = null, 
            $levelid = null, 
            $sort = null, 
            $syid = null, 
            $semid = null, 
            $strandid = null,
            $subjlist = array(),
            $issp = false,
            $acadprog = null
      ){

            $subjectplot = DB::table('subject_plot')
                              ->leftJoin('subject_gradessetup',function($join){
                                    $join->on('subject_plot.gradessetup','=','subject_gradessetup.id');
                                    $join->where('subject_gradessetup.deleted',0);
                              })
                              ->leftJoin('teacher',function($join){
                                    $join->on('subject_plot.subjcoor','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->where('subject_plot.deleted',0);

            if($id != null){
                  $subjectplot = $subjectplot->where('subject_plot.id',$id);
            }
            if($subjid != null){
                  $subjectplot = $subjectplot->where('subject_plot.subjid',$subjid);
            }
            if($levelid != null){
                  $subjectplot = $subjectplot->where('subject_plot.levelid',$levelid);
            }
            if($sort != null){
                  $subjectplot = $subjectplot->where('subject_plot.sort',$sort);
            }
            if($syid != null){
                  $subjectplot = $subjectplot->where('subject_plot.syid',$syid);
            }
            if($semid != null){
                  $subjectplot = $subjectplot->where('subject_plot.semid',$semid);
            }
            if($strandid != null){
                  $subjectplot = $subjectplot->where('subject_plot.strandid',$strandid);
            }
          
            if(!$issp){
                  $subjectplot = $subjectplot->where('subject_plot.isforsp',0);
            }else if($issp){
                  $subjectplot = $subjectplot->whereIn('subject_plot.isforsp',[0,1]);
            }else{
                  $subjectplot = $subjectplot->whereIn('subject_plot.isforsp',[0,1]);
            }

            if($levelid == 14 || $levelid == 15 || $acadprog == 5){
                  $subjectplot = $subjectplot->join('sh_subjects',function($join){
                        $join->on('subject_plot.subjid','=','sh_subjects.id');
                        $join->where('sh_subjects.deleted',0);
                  })
                  ->select(
                        'subjtitle as subjdesc',
                        'subjcode',
                        'inSF9',
                        'type',
                        'sh_subjCom as subjCom',
                        'sh_subjects.sh_isVisible as isVisble'
                  );
            }
            else{
                  $subjectplot = $subjectplot->join('subjects',function($join){
                        $join->on('subject_plot.subjid','=','subjects.id');
                        $join->where('subjects.deleted',0);
                  })
                  ->orderBy('plotsort')
                  ->select(
                        'subjdesc',
                        'subjcode',
                        'isSP',
                        'isCon',
                        'subjCom',
                        'isVisible',
                        'inSF9',
                        'subj_per'
                  );
            }

            $subjectplot = $subjectplot
                              ->addSelect(
                                    'isforsp',
                                    'lastname',
                                    'firstname',
                                    'tid',
                                    'subject_plot.id',
                                    'subject_plot.plotsort',
                                    'subject_plot.plotsort as sortid',
                                    'subject_plot.subjid',
                                    'subject_plot.levelid',
                                    'subject_plot.syid',
                                    'subject_plot.semid',
                                    'subject_plot.strandid',
                                    'subject_gradessetup.ww',
                                    'subject_gradessetup.comp4',
                                    'subject_gradessetup.pt',
                                    'subject_gradessetup.qa',
                                    'gradessetup',
                                    'subject_plot.subjcoor',
                                    'subjunit'
                              )
                              ->get();

            foreach($subjectplot as $item){

                  $item->search = $item->subjid.' '.$item->subjcode.' '.$item->subjdesc.' '.$item->lastname.' '.$item->firstname;

            }
           
            return $subjectplot;
      }


      public static function logs($syid = null){
            return DB::table('logs')->where('module',1)->get();
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

      public static function create_logs($message = null, $id = null){
           DB::table('logs') 
             ->insert([
                  'dataid'=>$id,
                  'module'=>1,
                  'message'=>$message,
                  'createdby'=>auth()->user()->id,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
      }

}
