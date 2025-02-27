<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use PDF;

class CollegeSchedController extends \App\Http\Controllers\Controller
{

      public static function teachers(Request $request){

            $syid = $request->get('syid');

            $teacher_array = array();

            $teachers = DB::table('teacher')
                              ->where('teacher.usertypeid',18)
                              ->where('teacher.deleted',0)
                              ->select(
                                    'teacher.id',
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'title',
                                    'tid',
                                    'suffix'
                              )
                              ->get();

            foreach($teachers as $item){
                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                              
                  $adviser = $item->title.' '.$item->firstname.' '.$temp_middle.' '.$item->lastname.' '.$item->suffix;
                  $item->fullname = $item->tid.' - '.$adviser;
                  $item->text = $item->fullname;
                              
                              $check = collect($teacher_array)->where('id',$item->id)->count();
                              
                              if($check == 0){
                                    array_push($teacher_array,$item);
                              }
            }

            // $teachers = DB::table('teacheracadprog')
            //                   ->join('teacher',function($join){
            //                         $join->on('teacheracadprog.teacherid','=','teacher.id');
            //                         $join->where('teacher.deleted',0);
            //                   })
            //                   ->where('teacheracadprog.acadprogid',6)
            //                   ->where('teacheracadprog.deleted',0)
            //                   ->where('teacheracadprog.syid',$syid)
            //                   ->select(
            //                         'teacher.id',
            //                         'firstname',
            //                         'lastname',
            //                         'middlename',
            //                         'title',
            //                         'tid',
            //                         'suffix'
            //                   )
            //                   ->get();
							  
			$fas_teachers = DB::table('faspriv')
                              ->join('teacher',function($join){
                                    $join->on('faspriv.userid','=','teacher.userid');
                                    $join->where('teacher.deleted',0);
                              })
                              ->where('faspriv.usertype',18)
                              ->where('faspriv.deleted',0)
                              ->select(
                                    'teacher.id',
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'title',
                                    'tid',
                                    'suffix'
                              )
                              ->get();
							  
			
            
            // foreach($teachers as $item){
            //       $middlename = explode(" ",$item->middlename);
            //       $temp_middle = '';
				  
            //       if($middlename != null){
            //           foreach ($middlename as $middlename_item) {
            //               if(strlen($middlename_item) > 0){
            //                   $temp_middle .= $middlename_item[0].'.';
            //               } 
            //           }
            //       }
				  
            //       $adviser = $item->title.' '.$item->firstname.' '.$temp_middle.' '.$item->lastname.' '.$item->suffix;
            //       $item->fullname = $item->tid.' - '.$adviser;
            //       $item->text = $item->fullname;
				  
            //       $check = collect($teacher_array)->where('id',$item->id)->count();
                  
            //       if($check == 0){
            //             array_push($teacher_array,$item);
            //       }
            // }
			
		foreach($fas_teachers as $item){
                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
				  
                  if($middlename != null){
                      foreach ($middlename as $middlename_item) {
                          if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                          } 
                      }
                  }
				  
                  $adviser = $item->title.' '.$item->firstname.' '.$temp_middle.' '.$item->lastname.' '.$item->suffix;
                  $item->fullname = $item->tid.' - '.$adviser;
                  $item->text = $item->fullname;
				  
				  $check = collect($teacher_array)->where('id',$item->id)->count();
				  
				  if($check == 0){
					  array_push($teacher_array,$item);
				  }
            }

            return collect($teacher_array)->unique('tid')->values();

      }


      public static function get_sectioninfo($sectionid){
            $sectioninfo = DB::table('college_sections')
                              ->where('id',$sectionid)
                              ->select(
                                    'sectionDesc'
                              )
                              ->first();

            return $sectioninfo;
      }

      public static function get_subjinfo($subjid){

            $subjinfo = DB::table('college_prospectus')
                              ->where('id',$subjid)
                              ->select(
                                    'subjCode',
                                    'subjDesc'
                              )
                              ->first();

            return $subjinfo;
      }

      public static function conflict_info($schedheader,$scheditem,$day,$conflicttype){

            $headerinfo = collect($schedheader)->where('id',$scheditem->headerid)->first();
            $sectinfo = self::get_sectioninfo($headerinfo->sectionID);
            $subjinfo = self::get_subjinfo($headerinfo->subjectID);

            return array((object)[
                  'conflicttype'=>$conflicttype,
                  'data'=>'conflict',
                  'status'=>0,
                  'time'=>$scheditem->time,
                  'section'=>$sectinfo->sectionDesc,
                  'subjcode'=>$subjinfo->subjCode,
                  'subjdesc'=>$subjinfo->subjDesc,
                  'day'=>$day
            ]);
      }


      public static function collegesched_create_sched(Request $request){

            try{

                  $syid = $request->get('syid');
                  $semid = $request->get('semid');

                  $room = $request->get('room');
                  $days = $request->get('days');
                  $schedotherclas = $request->get('term');
                  $time = explode(" - ", $request->get('time'));
                  $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
                  $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');
                  $time = explode(" - ", $request->get('time'));
                  $headerid = $request->get('headerid');
                  $teacherid = $request->get('teacherid');
                  $allowconflict = $request->get('allowconflict');

                  if($stime == $etime){
                        return array((object)[
                              'status'=>1,
                              'data'=>'Invalid Time',
                        ]);
      
                  }

                  if($allowconflict == 0){

                        $get_header_info = DB::table('college_classsched')
                                                ->where('id',$headerid)
                                                ->select(
                                                      'sectionID'
                                                )
                                                ->first();
                  
                        $sectsched = DB::table('college_classsched')
                                          ->where('college_classsched.sectionid',$get_header_info->sectionID)
                                          ->where('college_classsched.syid',$syid)
                                          ->where('college_classsched.deleted',0)
                                          ->where('college_classsched.semesterID',$semid)
                                          ->join('college_prospectus',function($join) use($room){
                                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                                $join->where('college_prospectus.deleted',0);
                                          })
                                          ->select(
                                                'college_classsched.id',
                                                'college_classsched.sectionID',
                                                'college_classsched.subjectID'
                                          )
                                          ->get();

                        $sectsched_detail = DB::table('college_scheddetail')
                                                ->whereIn('headerid',collect($sectsched)->pluck('id'))
                                                ->where('college_scheddetail.deleted',0)
                                                ->select(
                                                      'stime',
                                                      'etime',
                                                      'headerid',
                                                      'day',
                                                      DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                                                )
                                                ->get();

                        if($teacherid != ''){
                              $teachersched = DB::table('college_classsched')
                                                ->where('college_classsched.teacherid',$teacherid)
                                                ->where('college_classsched.syid',$syid)
                                                ->where('college_classsched.deleted',0)
                                                ->where('college_classsched.semesterID',$semid)
                                                ->join('college_prospectus',function($join) use($room){
                                                      $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                                      $join->where('college_prospectus.deleted',0);
                                                })
                                                ->select(
                                                      'college_classsched.id',
                                                      'college_classsched.sectionID',
                                                      'college_classsched.subjectID'
                                                )
                                                ->get();

                              $scheddetail = DB::table('college_scheddetail')
                                                ->whereIn('headerid',collect($teachersched)->pluck('id'))
                                                ->where('college_scheddetail.deleted',0)
                                                ->select(
                                                      'stime',
                                                      'etime',
                                                      'headerid',
                                                      'day',
                                                      DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                                                )
                                                ->get();
            
                        }else{
                              $teachersched = [];
                              $scheddetail = [];
                        }

                        if($room != ''){

                              $roomsched = DB::table('college_classsched')
                                                ->join('college_scheddetail',function($join) use($room){
                                                      $join->on('college_classsched.id','=','college_scheddetail.headerid');
                                                      $join->where('college_scheddetail.deleted',0);
                                                      $join->where('college_scheddetail.roomid',$room);
                                                })
                                                ->join('college_prospectus',function($join) use($room){
                                                      $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                                      $join->where('college_prospectus.deleted',0);
                                                })
                                                ->where('college_classsched.syid',$syid)
                                                ->where('college_classsched.deleted',0)
                                                ->where('college_classsched.semesterID',$semid)
                                                ->select(
                                                      'college_classsched.id',
                                                      'college_classsched.sectionID',
                                                      'college_classsched.subjectID',
                                                      'stime',
                                                      'etime',
                                                      'headerid',
                                                      'day',
                                                      DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                                                )
                                                ->get();
                        }else{
                              $roomsched = [];
                        }
                              
                        $is_time_conflict = false;
                        

                        foreach($days as $item){

                              $day = '';
                              if($item == 1){ $day = 'Mon';}
                              else if($item == 2){$day = 'Tue';}
                              else if($item == 3){$day = 'Wed';}
                              else if($item == 4){$day = 'Thu';}
                              else if($item == 5){$day = 'Fri';}
                              else if($item == 6){$day = 'Sat';}
                              else if($item == 7){$day = 'Sun';}
                        
                              //collect day sched
                              $temp_day_sched = collect($sectsched_detail)->where('day',$item)->values();

                              //check section conflict
                              foreach($temp_day_sched as $sched_item){
                                    $sched_stime = $sched_item->stime;
                                    $sched_etime = $sched_item->etime;
                                    if($stime >= $sched_stime && $stime <= $sched_etime ){
                                          if( $stime != $sched_etime){
                                                return self::conflict_info($sectsched,$sched_item,$day,'Section Schedule Conflict');
                                          }
                                    }else if( $etime >= $sched_stime && $etime <= $sched_etime ){
                                          if( $etime != $sched_stime){
                                                return self::conflict_info($sectsched,$sched_item,$day,'Section Schedule Conflict');
                                          }
                                    }else if( $sched_stime >= $stime && $sched_etime <= $etime ){
                                          return self::conflict_info($sectsched,$sched_item,$day,'Section Schedule Conflict');
                                    }
                              }

                              
                              //collect day sched
                              $temp_day_sched = collect($scheddetail)->where('day',$item)->values();
                              //check teacher conflict
                              foreach($temp_day_sched as $sched_item){
                                    $sched_stime = $sched_item->stime;
                                    $sched_etime = $sched_item->etime;
                                    if($stime >= $sched_stime && $stime <= $sched_etime ){
                                          if( $stime != $sched_etime){
                                                return self::conflict_info($teachersched,$sched_item,$day,'Teacher Schedule Conflict');
                                          }
                                    }else if( $etime >= $sched_stime && $etime <= $sched_etime ){
                                          if( $etime != $sched_stime){
                                                return self::conflict_info($teachersched,$sched_item,$day,'Teacher Schedule Conflict');
                                          }
                                    }else if( $sched_stime >= $stime && $sched_etime <= $etime ){
                                          return self::conflict_info($teachersched,$sched_item,$day,'Teacher Schedule Conflict');
                                    }
                              }

                              //collect day sched
                              $temp_day_sched = collect($roomsched)->where('day',$item)->values();
                              
                              //check room conflict
                              foreach($temp_day_sched as $sched_item){
                                    $sched_stime = $sched_item->stime;
                                    $sched_etime = $sched_item->etime;
                                    if($stime >= $sched_stime && $stime <= $sched_etime ){
                                          if( $stime != $sched_etime){
                                                return self::conflict_info($roomsched,$sched_item,$day,'Room Conflict');
                                          }
                                    }else if( $etime >= $sched_stime && $etime <= $sched_etime ){
                                          if( $etime != $sched_stime){
                                                return self::conflict_info($roomsched,$sched_item,$day,'Room Conflict');
                                          }
                                    }else if( $sched_stime >= $stime && $sched_etime <= $etime ){
                                          return self::conflict_info($roomsched,$sched_item,$day,'Room Conflict');
                                    }
                              }
                        
                        }
                  }

                  $group_sched = array();

                  // foreach($scheddetail as $item){
                  //       $check = collect($group_sched)
                  //                   ->where('stime',$item->stime)
                  //                   ->where('etime',$item->etime)
                  //                   ->count();

                  //       if($check == 0){
                  //             array_push((object)[
                  //                   'stime'=>$item->stime,
                  //                   'etime'=>$item->etime
                  //             ]);
                  //       }
                  // }
                  

                  // return collect($scheddetail)->groupBy('stime')->groupBy('etime')

                  // return $scheddetail;

                  DB::table('college_classsched')
                        ->where('id',$headerid)
                        ->take(1)
                        ->update([
                              'teacherid'=>$teacherid,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  foreach($days as $item){

                        $check_detail = Db::table('college_scheddetail')
                                          ->where('headerid',$headerid)
                                          ->where('day',$item)
                                          ->where('stime',$stime)
                                          ->where('etime',$etime)
                                          ->where('deleted',0)
                                          ->get();
            
                        if(count($check_detail) > 0){

                               DB::table('college_scheddetail')
                                    ->where('id',$check_detail[0]->id)
                                    ->where('deleted',0)
                                    ->take(1)
                                    ->update([
                                          'roomid'=>$room,
                                          'schedotherclass'=>$schedotherclas,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        }else{

                              DB::table('college_scheddetail')
                                    ->insert([
                                          'headerid'=> $headerid,
                                          'day'=>$item,
                                          'stime'=>$stime,
                                          'etime'=>$etime,
                                          'roomid'=>$room,
                                          'schedotherclass'=>$schedotherclas,
                                          'deleted'=>'0',
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Successfull.',
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function collegesched_update_sched(Request $request){

            try{

                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $room = $request->get('room');
                  $days = $request->get('days');
                  $schedotherclas = $request->get('term');
                  $time = explode(" - ", $request->get('time'));
                  $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
                  $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');

                  $from_time = explode(" - ", $request->get('from_time'));
                  $from_stime = \Carbon\Carbon::create($from_time[0])->isoFormat('HH:mm:ss');
                  $from_etime = \Carbon\Carbon::create($from_time[1])->isoFormat('HH:mm:ss');

                  $time = explode(" - ", $request->get('time'));
                  $headerid = $request->get('headerid');
                  $teacherid = $request->get('teacherid');
                  $schedid = $request->get('schedid');
                  $allowconflict = $request->get('allowconflict');

                  if($stime == $etime){
                        return array((object)[
                              'status'=>1,
                              'data'=>'Invalid Time',
                        ]);
      
                  }

                 

                  if($allowconflict == 0){

                        $get_header_info = DB::table('college_classsched')
                                                ->where('id',$headerid)
                                                ->select(
                                                      'sectionID'
                                                )
                                                ->first();

                        $detail_header = DB::table('college_scheddetail')
                                          ->where('id',$schedid)
                                          ->select('stime','etime')
                                          ->first();

                        $detail_array =  DB::table('college_scheddetail')
                                          ->where('headerid',$headerid)
                                          ->where('stime',$detail_header->stime)
                                          ->where('etime',$detail_header->etime)
                                          ->select('id')
                                          ->get();
                        
                  
                        $sectsched = DB::table('college_classsched')
                                          ->where('college_classsched.sectionid',$get_header_info->sectionID)
                                          ->where('college_classsched.syid',$syid)
                                          ->where('college_classsched.deleted',0)
                                          ->where('college_classsched.semesterID',$semid)
                                          ->join('college_prospectus',function($join) use($room){
                                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                                $join->where('college_prospectus.deleted',0);
                                          })
                                          ->select(
                                                'college_classsched.id',
                                                'college_classsched.sectionID',
                                                'college_classsched.subjectID'
                                          )
                                          ->get();

                        $sectsched_detail = DB::table('college_scheddetail')
                                                ->whereIn('headerid',collect($sectsched)->pluck('id'))
                                                ->where('college_scheddetail.deleted',0)
                                                ->whereNotIn('college_scheddetail.id',collect($detail_array)->pluck('id'))
                                                ->select(
                                                      'stime',
                                                      'etime',
                                                      'headerid',
                                                      'day',
                                                      DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                                                )
                                                ->get();

                        if($teacherid != ''){
                              $teachersched = DB::table('college_classsched')
                                                ->where('college_classsched.teacherid',$teacherid)
                                                ->where('college_classsched.syid',$syid)
                                                ->where('college_classsched.deleted',0)
                                                ->where('college_classsched.semesterID',$semid)
                                                ->join('college_prospectus',function($join) use($room){
                                                      $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                                      $join->where('college_prospectus.deleted',0);
                                                })
                                                ->select(
                                                      'college_classsched.id',
                                                      'college_classsched.sectionID',
                                                      'college_classsched.subjectID'
                                                )
                                                ->get();

                              $scheddetail = DB::table('college_scheddetail')
                                                ->whereIn('headerid',collect($teachersched)->pluck('id'))
                                                ->where('college_scheddetail.deleted',0)
                                                ->whereNotIn('college_scheddetail.id',collect($detail_array)->pluck('id'))
                                                ->select(
                                                      'stime',
                                                      'etime',
                                                      'headerid',
                                                      'day',
                                                      DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                                                )
                                                ->get();
            
                        }else{
                              $teachersched = [];
                              $scheddetail = [];
                        }

                        if($room != ''){

                              $roomsched = DB::table('college_classsched')
                                                ->join('college_scheddetail',function($join) use($room,$detail_array){
                                                      $join->on('college_classsched.id','=','college_scheddetail.headerid');
                                                      $join->where('college_scheddetail.deleted',0);
                                                      $join->where('college_scheddetail.roomid',$room);
                                                      $join->whereNotIn('college_scheddetail.id',collect($detail_array)->pluck('id'));
                                                })
                                                ->join('college_prospectus',function($join) use($room){
                                                      $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                                      $join->where('college_prospectus.deleted',0);
                                                })
                                                ->where('college_classsched.syid',$syid)
                                                ->where('college_classsched.id','!=',$schedid)
                                                ->where('college_classsched.deleted',0)
                                                ->where('college_classsched.semesterID',$semid)
                                                ->select(
                                                      'college_classsched.id',
                                                      'college_classsched.sectionID',
                                                      'college_classsched.subjectID',
                                                      'stime',
                                                      'etime',
                                                      'headerid',
                                                      'day',
                                                      DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                                                )
                                                ->get();
                        }else{
                              $roomsched = [];
                        }
                              
                        $is_time_conflict = false;
                        

                        foreach($days as $item){

                              $day = '';
                              if($item == 1){ $day = 'Mon';}
                              else if($item == 2){$day = 'Tue';}
                              else if($item == 3){$day = 'Wed';}
                              else if($item == 4){$day = 'Thu';}
                              else if($item == 5){$day = 'Fri';}
                              else if($item == 6){$day = 'Sat';}
                        
                              //collect day sched
                              $temp_day_sched = collect($sectsched_detail)->where('day',$item)->values();

                              //check section conflict
                              foreach($temp_day_sched as $sched_item){
                                    $sched_stime = $sched_item->stime;
                                    $sched_etime = $sched_item->etime;
                                    if($stime >= $sched_stime && $stime <= $sched_etime ){
                                          if( $stime != $sched_etime){
                                                return self::conflict_info($sectsched,$sched_item,$day,'Section Schedule Conflict');
                                          }
                                    }else if( $etime >= $sched_stime && $etime <= $sched_etime ){
                                          if( $etime != $sched_stime){
                                                return self::conflict_info($sectsched,$sched_item,$day,'Section Schedule Conflict');
                                          }
                                    }else if( $sched_stime >= $stime && $sched_etime <= $etime ){
                                          return self::conflict_info($sectsched,$sched_item,$day,'Section Schedule Conflict');
                                    }
                              }

                              
                              //collect day sched
                              $temp_day_sched = collect($scheddetail)->where('day',$item)->values();
                              //check teacher conflict
                              foreach($temp_day_sched as $sched_item){
                                    $sched_stime = $sched_item->stime;
                                    $sched_etime = $sched_item->etime;
                                    if($stime >= $sched_stime && $stime <= $sched_etime ){
                                          if( $stime != $sched_etime){
                                                return self::conflict_info($teachersched,$sched_item,$day,'Teacher Schedule Conflict');
                                          }
                                    }else if( $etime >= $sched_stime && $etime <= $sched_etime ){
                                          if( $etime != $sched_stime){
                                                return self::conflict_info($teachersched,$sched_item,$day,'Teacher Schedule Conflict');
                                          }
                                    }else if( $sched_stime >= $stime && $sched_etime <= $etime ){
                                          return self::conflict_info($teachersched,$sched_item,$day,'Teacher Schedule Conflict');
                                    }
                              }

                              //collect day sched
                              $temp_day_sched = collect($roomsched)->where('day',$item)->values();
                              
                              //check room conflict
                              foreach($temp_day_sched as $sched_item){
                                    $sched_stime = $sched_item->stime;
                                    $sched_etime = $sched_item->etime;
                                    if($stime >= $sched_stime && $stime <= $sched_etime ){
                                          if( $stime != $sched_etime){
                                                return self::conflict_info($roomsched,$sched_item,$day,'Room Conflict');
                                          }
                                    }else if( $etime >= $sched_stime && $etime <= $sched_etime ){
                                          if( $etime != $sched_stime){
                                                return self::conflict_info($roomsched,$sched_item,$day,'Room Conflict');
                                          }
                                    }else if( $sched_stime >= $stime && $sched_etime <= $etime ){
                                          return self::conflict_info($roomsched,$sched_item,$day,'Room Conflict');
                                    }
                              }
                        
                        }
                  
                  }


                  DB::table('college_classsched')
                              ->where('id',$headerid)
                              ->take(1)
                              ->update([
                                    'teacherid'=>$teacherid,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  //remove not included days
                  Db::table('college_scheddetail')
                              ->where('headerID',$headerid)
                              ->where('stime',$from_stime)
                              ->where('etime',$from_etime)
                              ->where('deleted',0)
                              ->whereNotIn('day',$days)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  //update existing days
                  $check_detail = Db::table('college_scheddetail')
                                          ->where('headerID',$headerid)
                                          ->where('stime',\Carbon\Carbon::create($from_stime)->isoFormat('HH:mm:ss'))
                                          ->where('etime',\Carbon\Carbon::create($from_etime)->isoFormat('HH:mm:ss'))
                                          ->where('deleted',0)
                                          ->get();

                  // return $check_detail;

                  foreach($check_detail as $item){

                        if($time != $from_time){

                              $detail_count = Db::table('college_scheddetail')
                                                ->where('headerID',$headerid)
                                                ->where('day',$item->day)
                                                ->where('stime',\Carbon\Carbon::create($stime)->isoFormat('HH:mm:ss'))
                                                ->where('etime',\Carbon\Carbon::create($etime)->isoFormat('HH:mm:ss'))
                                                ->where('deleted',0)
                                                ->count();

                              if($detail_count > 0){

                                    Db::table('college_scheddetail')
                                          ->where('id',$item->id)
                                          ->take(1)
                                          ->update([
                                                'deleted'=>1,
                                                'deletedby'=>auth()->user()->id,
                                                'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                              }

                        }

                        DB::table('college_scheddetail')
                              ->take(1)
                              ->where('id',$item->id)
                              ->update([
                                    'stime'=>$stime,
                                    'etime'=>$etime,
                                    'roomid'=>$room,
                                    'schedotherclass'=>$schedotherclas,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        //remove updated days from day list
                        $key = array_search($item->day, $days);
                        unset($days[$key]);
                      
                  }

                  //add new day
                  foreach($days as $item){

                        $detail_count = Db::table('college_scheddetail')
                                          ->where('headerid',$headerid)
                                          ->where('day',$item)
                                          ->where('stime',\Carbon\Carbon::create($from_stime)->isoFormat('HH:mm:ss'))
                                          ->where('etime',\Carbon\Carbon::create($from_stime)->isoFormat('HH:mm:ss'))
                                          ->where('deleted',0)
                                          ->count();

                        if($detail_count == 0){

                              DB::table('college_scheddetail')
                                          ->insert([
                                                'headerid'=> $headerid,
                                                'day'=>$item,
                                                'stime'=>$stime,
                                                'etime'=>$etime,
                                                'roomid'=>$room,
                                                'schedotherclass'=>$schedotherclas,
                                                'deleted'=>'0',
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                        }
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Successfull.',
                  ]);

            }catch(\Exception $e){
                  return $e;
                  return self::store_error($e);
            }
      }

      public static function collegesched_remove_sched(Request $request){

            try{
                  $schedid = $request->get('scheddetailid');
                  $sectionid = $request->get('sectionid');
                  
                  $details = DB::table('college_scheddetail')
                                    ->where('deleted',0)
                                    ->where('college_scheddetail.id',$schedid)
                                    ->first();

                  DB::table('college_scheddetail')
                        ->where('headerID',$details->headerID)
                        ->where('stime',$details->stime)
                        ->where('etime',$details->etime)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  //remove teacher
                  $check_detail = DB::table('college_scheddetail')
                                          ->where('headerID',$details->headerID)
                                          ->where('deleted',0)
                                          ->count();

                  if($check_detail == 0){
                        DB::table('college_classsched')
                              ->where('id',$details->headerID)
                              ->where('deleted',0)
                              ->update([
                                    'teacherID'=>null,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Successfull.',
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }


      public static function collegesched_list_ajax(Request $request){
            $sectionid = $request->get('sectionid');
            return self::collegesched_list($sectionid);
      }
     
      public static function collegesched_list(
            $sectionid = null
      ){    
         
            try{

                  $subjects = DB::table('college_classsched')
                                    ->join('college_prospectus',function($join){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->where('college_classsched.deleted',0);
                         

                  if($sectionid != null){
                        $subjects = $subjects->where('sectionID',$sectionid);
                  }

                  $subjects = $subjects
                                    ->select(
                                          'college_classsched.*',
                                          'subjDesc',
                                          'subjCode',
                                          'lecunits',
                                          'labunits'
                                    )
                                    ->get();

                  foreach($subjects as $item){

                        $item->units = $item->lecunits + $item->labunits;

                        $sched = DB::table('college_scheddetail')
                                          ->where('college_scheddetail.headerid',$item->id)
                                          ->where('college_scheddetail.deleted',0)
                                          ->leftJoin('rooms',function($join){
                                                $join->on('college_scheddetail.roomid','=','rooms.id');
                                                $join->where('rooms.deleted',0);
                                          })
                                          ->join('days',function($join){
                                                $join->on('college_scheddetail.day','=','days.id');
                                          })
                                          ->select(
                                                'day',
                                                'roomid',
                                                'college_scheddetail.id as detailid',
                                                'roomname',
                                                'stime',
                                                'etime',
                                                'days.description',
                                                'schedotherclass'
                                          )
                                          ->get();

                        $student_count = DB::table('college_studsched')
                                                ->join('college_enrolledstud',function($join) use($item){
                                                      $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                                                      $join->where('college_enrolledstud.deleted',0);
                                                      $join->whereIn('studstatus',[1,2,4]);
                                                      $join->where('syid',$item->syID);
                                                      $join->where('semid',$item->semesterID);
                                                })
                                                ->where('college_studsched.schedid',$item->id)
                                                ->where('college_studsched.schedstatus','!=','DROPPED')
                                                ->where('college_studsched.deleted',0)
                                                ->count();

                        $student_count_loaded = DB::table('college_studsched')
                                                ->where('college_studsched.schedid',$item->id)
                                                ->where('college_studsched.schedstatus','!=','DROPPED')
                                                ->where('college_studsched.deleted',0)
                                                ->count();

                        $item->teacher = null;
                        $item->teacherid = null;
                        $item->studentcount = $student_count;
                        $item->studentcountloaded = $student_count_loaded;

                        if(isset($item->teacherID)){
                              $temp_teacher = DB::table('teacher')
                                                ->where('id',$item->teacherID)
                                                ->first();
                              $item->teacher = $temp_teacher->firstname.' '.$temp_teacher->middlename.' '.$temp_teacher->lastname;
                              $item->teacherid = $temp_teacher->tid;
                        }


                        foreach($sched as $sched_item){
                              $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                        }

                        $starting = collect($sched)->groupBy('time');

                        $sched_list = array();
                        $sched_count = 1;

                        foreach($starting as $sched_item){
                              
                              $dayString = '';
                              $days = array();
                              $schedotherclass = '';

                              foreach($sched_item as $new_item){
                                    $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                                    $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                                    $dayString.= substr($new_item->description, 0,3).' / ';
                                    $detailid = $new_item->detailid;
                                    $roomname = $new_item->roomname;
                                    $roomid = $new_item->roomid;
                                    $time = $new_item->time;
                                    $schedotherclass = $new_item->schedotherclass;
                                    array_push($days,$new_item->day);
                              }

                              $dayString = substr($dayString, 0 , -2);
                              
                              array_push($sched_list,(object)[
                                    'day'=>$dayString,
                                    'start'=>$start,
                                    'end'=>$end,
                                    'roomid',
                                    'detailid'=>$detailid,
                                    'roomname'=>$roomname,
                                    'roomid'=>$roomid,
                                    'sched_count'=>$sched_count,
                                    'time'=>$time,
                                    'days'=>$days,
                                    'classification'=>$schedotherclass
                              ]);

                              $sched_count += 1;

                        }

                        $item->schedule = collect($sched_list)->sortByDesc('start')->values();

                  }
                  
                  return array((object)[
                        'status'=>1,
                        'data'=>'Successfull.',
                        'info'=>$subjects
                  ]);

            }catch(\Exception $e){
                  return $e;
                  return self::store_error($e);
            }

      }

      public static function update_sched_capacity(Request $request){

            try{

                  $schedid = $request->get('schedid');
                  $capacity = $request->get('capacity');

                  DB::table('college_classsched')
                        ->where('id',$schedid)
                        ->take(1)
                        ->update([
                              'capacity'=>$capacity,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                              'status'=>0,
                              'data'=>'Successfull.',
                        ]);

            }catch(\Exception $e){
                  
                  return self::store_error($e);

            }

      }


      public static function collegesched_plot(Request $request){
            $sectionid = $request->get('sectionid');

            $sectioninfo = DB::table('college_sections')
                              ->where('deleted',0)
                              ->where('id',$sectionid)
                              ->first();

            $schedule =  self::collegesched_list($sectionid);

            return view('superadmin.pages.college.collegeschedplot')
                              ->with('schedule',$schedule[0]->info)
                              ->with('sectionttype',$sectioninfo->section_specification);
      }


      public static function collegesched_print(Request $request){

            return back();

            // $sectionid = $request->get('sectionid');

            // $sectioninfo = DB::table('college_sections')
            //                   ->where('deleted',0)
            //                   ->where('id',$sectionid)
            //                   ->first();

            // $schedule =  self::collegesched_list($sectionid);


            // $pdf = PDF::loadView('principalsportal.forms.sf9layout.bct.shs',compact('finalhomeroom','homeroomsetup','adviser','grades','student','schoolinfo','attSum','coreValues','strandInfo','rv','checkGrades','acad','attendance_setup'))->setPaper('legal');
            // $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);

      }

      public static function collegesched_plot_ajax(Request $request){
            $sectionid = $request->get('sectionid');
            $schedule =  self::collegesched_list($sectionid);
            return $schedule[0]->info;
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
                  'module'=>4,
                  'message'=>$message,
                  'createdby'=>auth()->user()->id,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
      }

      public static function print_sched(Request $request){


            $sectionid = $request->get('sectionid');
            $schedule =  self::collegesched_list($sectionid);

            $sectioninfo = DB::table('college_sections')
                              ->leftJoin('college_courses',function($join){
                                    $join->on('college_sections.courseID','=','college_courses.id');
                              })
                              ->leftJoin('gradelevel',function($join){
                                    $join->on('college_sections.yearID','=','gradelevel.id');
                              })
                              ->where('college_sections.deleted',0)
                              ->where('college_sections.id',$sectionid)
                              ->select(
                                    'college_sections.*',
                                    'college_courses.courseDesc',
                                    'levelname as leveltext'
                              )
                              ->first();

            $schedule = $schedule[0]->info;
            $sectionttype = $sectioninfo->section_specification;
   
            // return collect( $sectioninfo);

            $schoolinfo = DB::table('schoolinfo')
                              ->first();

            $sydesc = DB::table('sy')
                              ->where('id',$sectioninfo->syID)
                              ->first();
      
            $semdesc = DB::table('semester')
                                    ->where('id',$sectioninfo->semesterID)
                                    ->first();

            $pdf = PDF::loadView('registrar.pdf.collegesched',compact('schedule','schoolinfo','sectionttype','sydesc','semdesc','sectioninfo'));
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();
            

      }

}
