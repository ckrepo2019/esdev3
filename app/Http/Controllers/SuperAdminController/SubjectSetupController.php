<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class SubjectSetupController extends \App\Http\Controllers\Controller
{
    
      public static function list_ajax(Request $request){
            $stage = $request->get('stage');
            if($stage == 1){
                  return self::list();
            }else{
                  return self::list_sh();
            }
      }

      public static function create_ajax(Request $request){
            $stage = $request->get('stage');
            $subjdesc = $request->get('subjdesc');
            $subjcode = $request->get('subjcode');
            // JAM START: catch subjunit
            $subjunit = $request->get('subjunit');
            $hours = $request->get('hours');
            // JAM END: catch subjunit
            $isCon = $request->get('isCon');
            $isSP = $request->get('isSP');
            $comp = $request->get('comp');
            $type = $request->get('type');
            $per = $request->get('per');
            $isVisible = $request->get('isVisible');
            $isInSF9 = $request->get('isInSF9');
            if($stage == 1){
                  return self::create($subjdesc,$subjcode,$subjunit,$isCon,$isSP,$comp,$per,$isVisible,$isInSF9,$hours);
            }else{
                  return self::create_sh($subjdesc,$subjcode,$subjunit,$type,$isInSF9,$isVisible,$hours);
            }
      }

      public static function update_ajax(Request $request){
            $id = $request->get('id');
            $stage = $request->get('stage');
            $subjdesc = $request->get('subjdesc');
            $subjcode = $request->get('subjcode');
            // JAM START: catch subjunit
            $subjunit = $request->get('subjunit');
            $hours = $request->get('hours');
            // JAM END: catch subjunit
            $isCon = $request->get('isCon');
            $isSP = $request->get('isSP');
            $comp = $request->get('comp');
            $type = $request->get('type');
            $per = $request->get('per');
            $isVisible = $request->get('isVisible');
            $isInSF9 = $request->get('isInSF9');
            if($stage == 1){
                  return self::update($id,$subjdesc,$subjcode,$subjunit,$isCon,$isSP,$comp,$per,$isVisible,$isInSF9,$hours);
            }else{
                  return self::update_sh($id,$subjdesc,$subjcode,$subjunit,$type,$isInSF9,$isVisible,$hours);
            }
      }

      public static function delete_ajax(Request $request){
            $stage = $request->get('stage');
            $id = $request->get('id');
            if($stage == 1){
                  return self::delete($id);
            }else{
                  return self::delete_sh($id);
            }
      }
      //attendance setup end

      //proccess
      public static function create(
           $subjdesc = null,
           $subjcode = null,
            // JAM START: add new variable subjunit
            $subjunit = null,
            // JAM END: add new variable subjunit
           $isCon = null,
           $isSP = null,
           $comp = array(),
           $per = 100,
           $isVisible = 1,
           $isInSF9 = 1,
           $hours = 0
      ){
            try{
                  $subject_id = DB::table('subjects')
                        ->insertGetId([
                              'subjdesc'=>$subjdesc,
                              'subjcode'=>$subjcode,
                              // JAM START: add new variable subjunit
                              'subjunit'=>$subjunit,
                              // JAM END: add new variable subjunit
                              'isCon'=>$isCon,
                              'isSP'=>$isSP,
                              'deleted'=>0,
                              'isactive'=>1,
                              'hours'=>$hours,
                              'subj_per'=>$per,
                              'isVisible'=>$isVisible,
                              'inSF9'=>$isInSF9,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  if($comp != null){
                        foreach($comp as $item){
                              DB::table('subjects')
                                    ->take(1)
                                    ->where('id',$item)
                                    ->where('deleted',0)
                                    ->update([
                                          'subjCom'=>$subject_id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id
                                    ]);
                        }
                  }

                  $message = auth()->user()->name.' added '.$subjdesc;
                  
                  self::create_logs($message,$subject_id);

                  $info = self::list();

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'id'=> $subject_id,
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function create_sh(
            $subjdesc = null,
            $subjcode = null,
            // JAM START: add new variable subjunit
            $subjunit = null,
            // JAM END: add new variable subjunit
            $type = null,
            $isInSF9 = 1,
            $isVisible = 1,
            $hours = 0
      ){
            try{
                  $subject_id = DB::table('sh_subjects')
                        ->insertGetId([
                              'subjtitle'=>$subjdesc,
                              'subjcode'=>$subjcode,
                              // JAM START: add new variable subjunit
                              'subjunit'=>$subjunit,
                              // JAM END: add new variable subjunit
                              'type'=>$type,
                              'hours'=>$hours,
                              'inSF9'=>$isInSF9,
                              'deleted'=>0,
                              'isactive'=>1,
                              'sh_isVisible'=>$isVisible,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $message = auth()->user()->name.' added '.$subjdesc;
                  
                  self::create_logs($message,$subject_id);

                  $info = self::list_sh();

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'id'=> $subject_id,
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update(
            $id = null,
            $subjdesc = null,
            $subjcode = null,
            // JAM START: add new variable subjunit
            $subjunit = null,
            // JAM END: add new variable subjunit
            $isCon = null,
            $isSP = null,
            $comp = array(),
            $per = 100,
            $isVisible = 1,
            $isInSF9 = 1,
            $hours = 0
      ){
            try{

                  //get subject info
                  $temp_info = self::list($id);

                  //get ended sy
                  $endedsy = DB::table('sy')
                                    ->where('ended',1)
                                    ->select('id')
                                    ->get();

                  $endedsy = collect($endedsy)->pluck('id');

               

                  //check if subject is consolidated or not
                  if($temp_info[0]->isCon == 1){

                        $getsubjcomp = DB::table('subjects')
                                          ->where('deleted',0)
                                          ->where('subjCom',$id)
                                          ->select('id')
                                          ->get();

                        $grade_usage = DB::table('grades')
                                          ->whereIn('subjid',collect($getsubjcomp)->pluck('id'))
                                          ->whereNotIn('levelid',[14,15])
                                          ->whereIn('syid',$endedsy)
                                          ->where('deleted',0)
                                          ->where('status','!=',0)
                                          ->count();

                        if($grade_usage == 0){
                              $grade_usage = DB::table('subject_plot')
                                                      ->whereIn('syid',$endedsy)
                                                      ->where('deleted',0)
                                                      ->whereIn('subjid',collect($getsubjcomp)->pluck('id'))
                                                      ->whereNotIn('levelid',[14,15])
                                                      ->count();
                        }

                        if($grade_usage == 0){
                              $check_subject_plot = DB::table('subject_plot')
                                                      ->whereNotIn('levelid',[14,15])
                                                      ->where('subjid',$id)
                                                      ->whereIn('syid',$endedsy)
                                                      ->where('deleted',0)
                                                      ->count();

                        }
                  }else{

                        $grade_usage = DB::table('grades')
                                          ->whereNotIn('levelid',[14,15])
                                          ->where('subjid',$id)
                                          ->whereIn('syid',$endedsy)
                                          ->where('deleted',0)
                                          ->where('status','!=',0)
                                          ->count();

                        if($grade_usage == 0){
                              $check_subject_plot = DB::table('subject_plot')
                                                      ->whereNotIn('levelid',[14,15])
                                                      ->where('subjid',$id)
                                                      ->whereIn('syid',$endedsy)
                                                      ->where('deleted',0)
                                                      ->count();
                        }

                  }

                  if($grade_usage > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Already used from previous S.Y.'
                        ]);
                  }

                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$id)
                        ->where('deleted',0)
                        ->update([
                              'subjdesc'=>$subjdesc,
                              'subjcode'=>$subjcode,
                              // JAM START: add new variable subjunit
                              'subjunit'=>$subjunit,
                              'hours'=>$hours,
                              // JAM END: add new variable subjunit
                              'isCon'=>$isCon,
                              'isSP'=>$isSP,
                              'inSF9'=>$isInSF9,
                              'subj_per'=>$per,
                              'isVisible'=>$isVisible,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  if($temp_info[0]->isCon == 1){

                        $exist_comp = DB::table('subjects')
                                          ->where('deleted',0)
                                          ->where('subjcom',$id)
                                          ->select('id')
                                          ->get();

                        foreach($exist_comp as $item){                
                              DB::table('subjects')
                                    ->take(1)
                                    ->where('id',$item->id)
                                    ->where('deleted',0)
                                    ->update([
                                          'subjCom'=>null,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id
                                    ]);
                        }

                        if($comp != null){
                              foreach($comp as $item){
                                    DB::table('subjects')
                                          ->take(1)
                                          ->where('id',$item)
                                          ->where('deleted',0)
                                          ->update([
                                                'subjCom'=>$id,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                'updatedby'=>auth()->user()->id
                                          ]);
                              }
                        }

                  }
               
                  $message = auth()->user()->name.' updated subject '.$temp_info[0]->subjdesc;
                  self::create_logs($message,$id);
                  $info = self::list();
                
                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!',
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update_sh(
            $id = null,
            $subjdesc = null,
            $subjcode = null,
            // JAM START: add new variable subjunit
            $subjunit = null,
            // JAM END: add new variable subjunit
            $type = null,
            $isInSF9 = 1,
            $isVisible = 1,
            $hours = 0
      ){
            try{

                  $endedsy = DB::table('sy')
                                    ->where('ended',1)
                                    ->select('id')
                                    ->get();

                  $endedsy = collect($endedsy)->pluck('id');

                  $grade_usage = DB::table('grades')
                                    ->where('subjid',$id)
                                    ->whereIn('levelid',[14,15])
                                    ->whereIn('syid',$endedsy)
                                    ->where('deleted',0)
                                    ->where('status','!=',0)
                                    ->count();

                  if($grade_usage == 0){
                        $grade_usage = DB::table('subject_plot')
                                                ->where('subjid',$id)
                                                ->whereIn('levelid',[14,15])
                                                ->whereIn('syid',$endedsy)
                                                ->where('deleted',0)
                                                ->count();
                  }

                  if($grade_usage > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Already used from previous S.Y.'
                        ]);
                  }

                  $temp_info = self::list_sh($id);

                  DB::table('sh_subjects')
                        ->take(1)
                        ->where('id',$id)
                        ->where('deleted',0)
                        ->update([
                              'inSF9'=>$isInSF9,
                              'subjtitle'=>$subjdesc,
                              'subjcode'=>$subjcode,
                              'hours'=>$hours,
                              // JAM START: add new variable subjunit
                              'subjunit'=>$subjunit,
                              // JAM END: add new variable subjunit
                              'type'=>$type,
                              'sh_isVisible'=>$isVisible,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  $message = auth()->user()->name.' updated subject '.$temp_info[0]->subjdesc;
                  self::create_logs($message,$id);
                  $info = self::list_sh();
                
                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!',
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }
      
      public static function delete_sh(
            $id = null
      ){
            try{
                  $temp_info = self::list_sh($id);   

                  $endedsy = DB::table('sy')
                                    ->where('ended',1)
                                    ->select('id')
                                    ->get();

                  $endedsy = collect($endedsy)->pluck('id');

                  $grade_usage = DB::table('grades')
                                    ->whereIn('levelid',[14,15])
                                    ->where('subjid',$id)
                                    ->whereIn('syid',$endedsy)
                                    ->where('deleted',0)
                                    ->where('status','!=',0)
                                    ->count();

                  if($grade_usage > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Already used from previous S.Y.'
                        ]);
                  }

                  $check = DB::table('subject_plot')
                              ->whereIn('levelid',[14,15])
                              ->where('deleted',0)
                              ->where('subjid',$temp_info[0]->id)
                              ->count();

                  if( $check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already used in plotting!',
                        ]);
                  }

                  DB::table('sh_subjects')
                        ->take(1)
                        ->where('id',$id)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  

                      
                  $message = auth()->user()->name.' removed '.$temp_info[0]->subjdesc.' subject.';

                  self::create_logs($message,$id);
                  $info = self::list_sh();

                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!',
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function delete(
            $id = null
      ){
            try{
                  $temp_info = self::list($id); 

                  //get ended sy
                  $endedsy = DB::table('sy')
                                    ->where('ended',1)
                                    ->select('id')
                                    ->get();

                  $endedsy = collect($endedsy)->pluck('id');

                  //check if subject is consolidated or not
                  if($temp_info[0]->isCon == 1){

                        $getsubjcomp = DB::table('subjects')
                                          ->where('deleted',0)
                                          ->where('subjCom',$id)
                                          ->select('id')
                                          ->get();

                        $grade_usage = DB::table('grades')
                                          ->whereIn('subjid',collect($getsubjcomp)->pluck('id'))
                                          ->whereIn('syid',$endedsy)
                                          ->whereNotIn('levelid',[14,15])
                                          ->where('deleted',0)
                                          ->where('status','!=',0)
                                          ->count();

                  }else{

                        $grade_usage = DB::table('grades')
                                          ->where('subjid',$id)
                                          ->whereIn('syid',$endedsy)
                                          ->where('deleted',0)
                                          ->whereNotIn('levelid',[14,15])
                                          ->where('status','!=',0)
                                          ->count();

                  }

                  if($grade_usage > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Already used from previous S.Y.'
                        ]);
                  }
                  
                  $check = DB::table('subject_plot')
                                    ->whereNotIn('levelid',[14,15])
                                    ->where('deleted',0)
                                    ->where('subjid',$temp_info[0]->id)
                                    ->count();

                  if( $check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already used in plotting!',
                        ]);
                  }

                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$id)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);


                  $exist_comp = DB::table('subjects')
                        ->where('deleted',0)
                        ->where('subjcom',$id)
                        ->select('id')
                        ->get();

                  foreach($exist_comp as $item){                
                        DB::table('subjects')
                              ->take(1)
                              ->where('id',$item->id)
                              ->where('deleted',0)
                              ->update([
                                    'subjCom'=>null,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'deletedby'=>auth()->user()->id0
                              ]);
                  }
                
                  $message = auth()->user()->name.' removed '.$temp_info[0]->subjdesc.' subject.';

                  self::create_logs($message,$id);
                  $info = self::list();

                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!',
                        'info'=>$info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      
      //data
      public static function list_sh($id = null){

            $subjects = DB::table('sh_subjects');

            if($id != null){
                  $subjects = $subjects->where('id',$id);
            }

            $subjects = $subjects->select(
                                    'id',
                                    'subjcode',
                                    // JAM START: include subjunit to output data
                                    'subjunit',
                                    'hours',
                                    // JAM END
                                    'subjtitle as subjdesc',
                                    'subjtitle as text',
                                    'type',
                                    'inSF9',
                                    'sh_isVisible as isVisible'
                              )
                              ->where('deleted',0)
                              ->get();
                              
            foreach($subjects as $item){
                  $item->search = $item->subjcode . ' - ' .$item->subjdesc. ' - '.$item->id;
            }

            return $subjects;
      }

      public static function list($id = null){

            $subjects = DB::table('subjects');

            if($id != null){
                  $subjects = $subjects->where('id',$id);
            }
                  
            $subjects = $subjects->select(
                                    'id',
                                    'subjcode',
                                    'subjdesc',
                                    'hours',
                                    // JAM START: include subjunit to output data
                                    'subjunit',
                                    // JAM END
                                    'subjdesc as text',
                                    'isSP',
                                    'subjCom',
                                    'isCon',
                                    'subj_per',
                                    'isVisible',
                                    'inSF9'
                              )
                              ->where('deleted',0)
                              ->get();

            foreach($subjects as $item){
                  $item->text = $item->subjcode.' - '.$item->subjdesc;
            }
            
            foreach($subjects as $item){
                  $isCon = '';
                  $isCom = '';
                  $isSP = '';
                  $isVisible = 'NOT VISIBLE';

                  if($item->isCon == 1){
                        $isCon  = 'CONSOLIDATED';
                  }
                  if($item->isSP == 1){
                        $isSP  = 'SPECIALIZED';
                  }
                  if($item->subjCom != null){
                        $isCom  = 'COMPONENT';
                  }
                  if($item->isVisible == 1){
                        $isVisible  = 'VISIBLE';
                  }
                  $item->search = $item->subjcode . ' ' .$item->subjdesc. ' '.$item->id.' '.$isCon.' '.$isCom.' '.$isSP.' '.$isVisible;
            }

           
            return $subjects;
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
                  'message'=>'Something went wrong!'
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



      // Grade Setup ----------------------------------------

      public static function grade_setup_list_ajax(){



      }
      
      public static function grade_setup_list(
            $syid = null,
            $levelid = null,
            $subjid = null
      ){

            $gradessetup = Db::table('gradessetup')
                              ->where('deleted',0);
            
            if($syid != null){
                  $gradessetup = $gradessetup->where('syid',$syid);
            }
            if($levelid != null){
                  $gradessetup = $gradessetup->where('levelid',$levelid);
            }
            if($subjid != null){
                  $subjid = $gradessetup->where('subjid',$subjid);
            }

            return $gradessetup->select(
                              'writtenworks',
                              'performancetask',
                              'qassesment'
                        )
                        ->get();


      }
      

}
