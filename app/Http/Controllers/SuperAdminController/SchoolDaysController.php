<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;

class SchoolDaysController extends \App\Http\Controllers\Controller
{

      public static function attendance_setup_summary(Request $request){

            $gradelevel = self::get_gradelevel($request);
            $syid = $request->get('schoolyear');
            $attclass = $request->get('attclass');

            foreach($gradelevel as $item){
                  $list = self::attendance_setup_list($syid,$item->id,$attclass);
                  $item->attsetup =   $list;
            }

            return $gradelevel;

      }

      public static function set_month_to_active(Request $request){

            try{
                  $syid = $request->get('syid');
                  $levelid = $request->get('levelid');
                  $month = $request->get('month');
                  $status = $request->get('status');

                  if($levelid == null || $levelid == ""){
                        $gradelevel = self::get_gradelevel($request);
                  }else{
                        $gradelevel = array((object)['id'=>$levelid]);
                  }

                  foreach($gradelevel as $item){
                        DB::table('studattendance_setup')
                              ->where('syid',$syid)
                              ->where('levelid',$item->id)
                              ->where('deleted',0)
                              ->where('month',$month)
                              ->update([
                                    'isactive'=>$status,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
                  return array((object)[
                        'status'=>1,
                        'data'=>'Status Updated!',
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e,$userid);
            }

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
      
            }else{
      
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

                  if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){

                        $acadprog = DB::table('teacheracadprog')
                                    ->where('teacherid',$teacherid)
                                    ->where('acadprogutype',2)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->select('acadprogid as id')
                                    ->distinct('acadprogid')
                                    ->get();

                  }else{

                        $acadprog = DB::table('teacheracadprog')
                                    ->where('teacherid',$teacherid)
                                    ->where('acadprogutype',Session::get('currentPortal'))
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->select('acadprogid as id')
                                    ->distinct('acadprogid')
                                    ->get();
                  }
            }


            $acadprog_list = array();
            foreach($acadprog as $item){
                  array_push($acadprog_list,$item->id);
            }

            return $acadprog_list;

      }

      //attendance setup start
      public static function list(Request $request){
            $syid = $request->get('schoolyear');
            $levelid = $request->get('levelid');
            $attclass = $request->get('attclass');
            return self::attendance_setup_list($syid,$levelid,$attclass);
      }

      public static function create(Request $request){
            $month = $request->get('month');
            $days = $request->get('days');
            $syid = $request->get('syid');
            $sort = $request->get('sort');
            $year = $request->get('year');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $userid = $request->get('userid');
            $attclass = $request->get('attclass');
            $dates = explode(",",$request->get('dates'));

            return self::attendance_setup_create($month, $days, $syid, $sort, $year,$levelid,$semid,$userid,$attclass,$dates);
      }
      public static function update(Request $request){
            $attsetupid = $request->get('attsetupid');
            $month = $request->get('month');
            $days = $request->get('days');
            $syid = $request->get('syid');
            $sort = $request->get('sort');
            $year = $request->get('year');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $userid = $request->get('userid');
            $attclass = $request->get('attclass');
            $dates = explode(",",$request->get('dates'));
            return self::attendance_setup_update($attsetupid, $month, $days, $syid, $sort, $year,$levelid,$semid,$userid,$attclass,$dates);
      }
      public static function delete(Request $request){
            $attsetupid = $request->get('attsetupid');
            $syid = $request->get('syid');
            $userid = $request->get('userid');
            return self::attendance_setup_delete($attsetupid, $syid,$userid);
      }
      //attendance setup end

      //proccess
      public static function attendance_setup_create(
            $month = null, 
            $days = null,
            $syid  = null,
            $sort = null,
            $year = null,
            $levelid = null,
            $semid = null,
            $userid = null,
            $attclass = 0,
            $dates = []
      ){
           
            if(Auth::check()){
                  $userid = auth()->user()->id;
                  $username = auth()->user()->name;
            }else{
                  $username = DB::table('users')->where('id',$userid)->first()->name;
            }

            try{
                 
                  $check_sy = DB::table('sy')
                                    ->where('id',$syid)
                                    ->first();

                  if($check_sy->ended == 1){
                        return array((object)[
                              'status'=>2,
                              'data'=>'S.Y. Ended!'
                        ]);
                  }

                  $check = DB::table('studattendance_setup')
                              ->where('syid',$syid)
                              ->where('levelid',$levelid)
                              ->where('month',$month)
                              ->where('deleted',0)
                              ->where('attclass',$attclass)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Already exist!',
                        ]);
                  }

                  $attendance_setup_id = DB::table('studattendance_setup')
                        ->insertGetId([
                              'syid'=>$syid,
                              'month'=>\Carbon\Carbon::create($dates[0])->isoFormat('MM'),
                              'days'=>count($dates),
                              'sort'=>$sort,
                              'year'=>$year,
                              'levelid'=>$levelid,
                              'attclass'=>$attclass,
                              'semid'=>$semid,
                              'createdby'=>$userid,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  foreach($dates as $item){
                        Db::table('studattendance_setup_dates')
                              ->insert([
                                    'headerid'=>$attendance_setup_id,
                                    'date'=>\Carbon\Carbon::create($item)->isoFormat('YYYY-MM-DD'),
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  $year = DB::table('sy')->where('id',$syid)->first()->sydesc;

                  $message = $username.' added month of '.\Carbon\Carbon::create(null, $month)->isoFormat('MMMM').' for school year '. $year;
                  
                  self::create_logs($message,$attendance_setup_id,$userid);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'id'=> $attendance_setup_id
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e,$userid);
            }
      }

      public static function attendance_setup_update(
            $attsetupid = null,
            $month = null, 
            $days = null,
            $syid  = null,
            $sort = null,
            $year = null,
            $levelid = null,
            $semid = null,
            $userid = null,
            $attclass = 0,
            $dates = []
      ){

            
            if(Auth::check()){
                  $userid = auth()->user()->id;
                  $username = auth()->user()->name;
            }else{
                  $username = DB::table('users')->where('id',$userid)->first()->name;
            }

            try{

                  $check_sy = DB::table('sy')
                                    ->where('id',$syid)
                                    ->first();

                  if($check_sy->ended == 1){
                        return array((object)[
                              'status'=>2,
                              'data'=>'S.Y. Ended!'
                        ]);
                  }

                  DB::table('studattendance_setup')
                        ->take(1)
                        ->where('id',$attsetupid)
                        ->where('deleted',0)
                        ->update([
                              'syid'=>$syid,
                              'month'=>\Carbon\Carbon::create($dates[0])->isoFormat('MM'),
                              'days'=>count($dates),
                              'sort'=>$sort,
                              'year'=>$year,
                              'semid'=>$semid,
                              'attclass'=>$attclass,
                              'levelid'=>$levelid,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>$userid
                        ]);

                  //remove unselected dates
                  Db::table('studattendance_setup_dates')
                        ->where('deleted',0)
                        ->where('headerid',$attsetupid)
                        ->whereNotIn('date',$dates)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                  
                  //add new dates
                  foreach($dates as $item){
                        Db::table('studattendance_setup_dates')
                              ->updateOrInsert(
                                    [ 'headerid'=>$attsetupid,'deleted'=>0,'date'=>$item],
                                    ['date'=>$item,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')]
                              );
                  }

                  $year = DB::table('sy')->where('id',$syid)->first()->sydesc;

                  $message = $username.' updated month of '.\Carbon\Carbon::create(null, $month)->isoFormat('MMMM').' school year '. $year;
                  
                  self::create_logs($message,$attsetupid,$userid);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e,$userid);
            }
      }

      
      public static function attendance_setup_delete(
            $attsetupid = null,
            $syid = null,
            $userid = null
      ){

            if(Auth::check()){
                  $userid = auth()->user()->id;
                  $username = auth()->user()->name;
            }else{
                  $username = DB::table('users')->where('id',$userid)->first()->name;
            }

            try{
                  DB::table('studattendance_setup')
                        ->take(1)
                        ->where('id',$attsetupid)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'deletedby'=>$userid
                        ]);


                  DB::table('studattendance_setup_dates')    
                        ->where('headerid',$attsetupid)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'deletedby'=>$userid
                        ]);
                        
                  $month = DB::table('studattendance_setup')->where('id',$attsetupid)->first()->month;
                  $year = DB::table('sy')->where('id',$syid)->first()->sydesc;

                  $message = $username.' remove month of '.\Carbon\Carbon::create(null, $month)->isoFormat('MMMM').' for school year '. $year;

                  self::create_logs($message,$attsetupid,$userid);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e,$userid);
            }
      }

      
      //data
      public static function attendance_setup_list($syid = null, $levelid = null, $attclass = 0 , $month = null){



            $attendance_setup = DB::table('studattendance_setup')
                              ->where('deleted',0);
            if($syid != null){
                  $attendance_setup = $attendance_setup->where('syid',$syid);
            }

            if($attclass != null){
                  $attendance_setup = $attendance_setup->where('attclass',$attclass);
            }


            if($levelid != null){
                  $attendance_setup = $attendance_setup->where('levelid',$levelid);
            }else{
                  $attendance_setup = $attendance_setup->whereNull('levelid');
            }

            if($month != null){
                  $attendance_setup = $attendance_setup->where('month',$month);
            }



            $attendance_setup = $attendance_setup
                                    ->join('sy',function($join){
                                          $join->on('studattendance_setup.syid','=','sy.id');
                                    })
                                    ->select(
                                          'studattendance_setup.id',
                                          'studattendance_setup.syid',
                                          'studattendance_setup.month',
                                          'studattendance_setup.days',
                                          'studattendance_setup.year',
                                          'studattendance_setup.semid',
                                          'studattendance_setup.levelid',
                                          'sydesc',
                                          'sort',
                                          'attclass',
                                          'studattendance_setup.isactive'
                                    )
                                    ->get();

            
            $dates = DB::table('studattendance_setup_dates')
                        ->whereIn('headerid',collect($attendance_setup)->pluck('id'))
                        ->where('deleted',0)
                        ->orderBy('date')
                        ->get();

            foreach( $attendance_setup as $item){
                  $item->monthdesc = \Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM');
                  $item->dates = collect( $dates)->where('headerid',$item->id)->pluck('date');
            }
            return $attendance_setup;
      }

      public static function schooldayscopy(Request $request){

            try{
                  $gradelevelto = $request->get('gradelevel_to');
                  $gradefrom = $request->get('gradelevel_from');

                  $syidto = $request->get('syid_to');
                  $syidfrom = $request->get('syid_from');


                  $copied = 0;

                  if($gradefrom != null){
                        $list = self::attendance_setup_list($syidto,$gradefrom);

                        foreach($list as $item){

                              $status = self::attendance_setup_create(
                                    $item->month, 
                                    $item->days, 
                                    $item->syid, 
                                    $item->sort, 
                                    $item->year,
                                    $gradelevelto,
                                    $item->semid,
                                    null,
                                    $item->attclass,
                                    $item->dates);

                              if($status[0]->status == 1){
                                    $copied += 1;
                              }
                        }
                        
                  
                  }
                  // else if($syidfrom != null){

                  //       $list = self::attendance_setup_list($syidfrom,$gradelevelto);
                     
                  //       foreach($list as $item){
                             
                  //             $status = self::attendance_setup_create(
                  //                   $item->month, 
                  //                   $item->days, 
                  //                   $syidto, 
                  //                   $item->sort, 
                  //                   $item->year,
                  //                   $item->levelid,
                  //                   $item->semid,
                  //                   null,
                  //                   $item->attclass,
                  //                   $item->dates);
                              
                  //             // $status = self::attendance_setup_create($new_request);
                  //             if($status[0]->status == 1){
                  //                   $copied += 1;
                  //             }
                  //       }

                  // }

                  return array((object)[
                        'status'=>1,
                        'data'=>'School Days Copied!',
                        'copied'=>$copied
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }


      }



      public static function logs($syid = null){
            return DB::table('logs')->where('module',1)->get();
      }

      public static function store_error($e,$userid = null){
            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        'createdby'=>$userid,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'data'=>'Something went wrong!'
            ]);
      }

      public static function create_logs($message = null, $id = null,$userid = null){
           DB::table('logs') 
             ->insert([
                  'dataid'=>$id,
                  'module'=>1,
                  'message'=>$message,
                  'createdby'=>$userid,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
      }


      
      
     
      

}
