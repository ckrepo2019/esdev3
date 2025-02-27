<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;

class CollegeSchedList extends \App\Http\Controllers\Controller
{




      public static function checkteacherconflict(Request $request)
      {

            $teacherid = $request->get('teacherid');
            $schedid = $request->get('schedid');

            $schedinfo = DB::table('college_classsched')
                  ->where('id', $schedid)
                  ->first();


            $schedinfodetail = DB::table('college_scheddetail')
                  ->where('college_scheddetail.deleted', 0);

            if ($request->get('tobe_updated') != '') {
                  $schedinfodetail = $schedinfodetail->whereIn('id', $request->get('tobe_updated'));
            } else {
                  $schedinfodetail = $schedinfodetail->where('headerid', $schedid);
            }

            $schedinfodetail = $schedinfodetail->select(
                  'stime',
                  'etime',
                  'headerid',
                  'day',
                  DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
            )
                  ->get();

            $schedinfodetail = collect($schedinfodetail)->unique('day')->values();

            $syid = $schedinfo->syID;
            $semid = $schedinfo->semesterID;

            $teachersched = DB::table('college_classsched')
                  ->where('college_classsched.teacherid', $teacherid)
                  ->where('college_classsched.syid', $syid)
                  ->where('college_classsched.deleted', 0)
                  ->where('college_classsched.semesterID', $semid)
                  ->whereNotIn('id', collect($schedinfodetail)->pluck('headerid'))
                  ->select(
                        'college_classsched.id',
                        'college_classsched.sectionID',
                        'college_classsched.subjectID'
                  )
                  ->get();



            $teacherscheddetail = DB::table('college_scheddetail')
                  ->whereIn('headerid', collect($teachersched)->pluck('id'))
                  ->where('college_scheddetail.deleted', 0);

            if ($request->get('tobe_updated') != '') {
                  $teacherscheddetail = $teacherscheddetail->whereNotIn('id', $request->get('tobe_updated'));
            }

            $teacherscheddetail = $teacherscheddetail->select(
                  'stime',
                  'etime',
                  'headerid',
                  'day',
                  DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
            )
                  ->get();


            $conflict_list = array();


            $schedgroups = DB::table('college_schedgroup_detail')
                  ->where('college_schedgroup_detail.deleted', 0)
                  ->whereIn('college_schedgroup_detail.schedid', collect($teacherscheddetail)->pluck('headerid'))
                  ->join('college_schedgroup', function ($join) {
                        $join->on('college_schedgroup_detail.groupid', '=', 'college_schedgroup.id');
                        $join->where('college_schedgroup.deleted', 0);
                  })
                  ->select(
                        'schedgroupdesc',
                        'schedid'
                  )
                  ->get();

            foreach ($schedinfodetail as $item) {

                  $stime = $item->stime;
                  $etime = $item->etime;

                  $temp_day_sched = collect($teacherscheddetail)->where('day', $item->day)->values();



                  foreach ($temp_day_sched as $sched_item) {
                        $sched_stime = $sched_item->stime;
                        $sched_etime = $sched_item->etime;


                        $grouptext = '';
                        $count = 0;
                        $schedetialgroups = collect($schedgroups)->where('schedid', $sched_item->headerid)->values();
                        foreach ($schedetialgroups as $schedetialgroup) {
                              $grouptext .= $schedetialgroup->schedgroupdesc;
                              if ($count != (count($schedetialgroups) - 1)) {
                                    $grouptext .= ' / ';
                                    $count += 1;
                              }
                        }

                        $sched_item->group = $grouptext;

                        if ($stime >= $sched_stime && $stime <= $sched_etime) {
                              if ($stime != $sched_etime) {
                                    array_push($conflict_list, $sched_item);
                              }
                        } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                              if ($etime != $sched_stime) {
                                    array_push($conflict_list, $sched_item);
                              }
                        } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                              array_push($conflict_list, $sched_item);
                        }
                  }

            }

            return $conflict_list;

      }


      public static function checkroomconflict(Request $request)
      {

            $room = $request->get('room');
            $schedid = $request->get('schedid');

            $schedinfo = DB::table('college_classsched')
                  ->where('id', $schedid)
                  ->first();


            $schedinfodetail = DB::table('college_scheddetail')
                  ->whereIn('id', $request->get('tobe_updated'))
                  ->where('college_scheddetail.deleted', 0)
                  ->select(
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time"),
                        'schedotherclass'
                  )
                  ->get();


            $syid = $schedinfo->syID;
            $semid = $schedinfo->semesterID;

            $temp_allsched = array();

            //get all room sched
            $roomscheddetail = DB::table('college_scheddetail')
                  ->where('college_scheddetail.roomid', $room)
                  ->where('college_scheddetail.deleted', 0)
                  ->where('college_scheddetail.headerID', '!=', $schedid)
                  ->join('college_classsched', function ($join) use ($syid, $semid) {
                        $join->on('college_scheddetail.headerid', '=', 'college_classsched.id');
                        $join->where('college_classsched.syid', $syid);
                        $join->where('college_classsched.deleted', 0);
                        $join->where('college_classsched.semesterID', $semid);
                  })
                  ->select(
                        'college_scheddetail.stime',
                        'college_scheddetail.etime',
                        'college_scheddetail.headerid',
                        'college_scheddetail.day',
                        DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                  )
                  ->get();

            //get sched detail with different class
            $schedscheddetail = DB::table('college_scheddetail')
                  ->where('college_scheddetail.deleted', 0)
                  ->where('college_scheddetail.roomid', $room)
                  ->where('college_scheddetail.headerID', $schedid)
                  ->where('college_scheddetail.stime', '!=', $schedinfodetail[0]->stime)
                  ->where('college_scheddetail.etime', '!=', $schedinfodetail[0]->etime)
                  ->join('college_classsched', function ($join) use ($syid, $semid) {
                        $join->on('college_scheddetail.headerid', '=', 'college_classsched.id');
                        $join->where('college_classsched.syid', $syid);
                        $join->where('college_classsched.deleted', 0);
                        $join->where('college_classsched.semesterID', $semid);
                  })
                  ->select(
                        'college_scheddetail.stime',
                        'college_scheddetail.etime',
                        'college_scheddetail.headerid',
                        'college_scheddetail.day',
                        DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                  )
                  ->get();



            foreach ($roomscheddetail as $item) {
                  array_push($temp_allsched, $item);
            }

            foreach ($schedscheddetail as $item) {
                  array_push($temp_allsched, $item);
            }

            // return $temp_allsched;

            $roomscheddetail = $temp_allsched;

            $conflict_list = array();

            $schedgroups = DB::table('college_schedgroup_detail')
                  ->where('college_schedgroup_detail.deleted', 0)
                  ->whereIn('college_schedgroup_detail.schedid', collect($roomscheddetail)->pluck('headerid'))
                  ->join('college_schedgroup', function ($join) {
                        $join->on('college_schedgroup_detail.groupid', '=', 'college_schedgroup.id');
                        $join->where('college_schedgroup_detail.deleted', 0);
                  })
                  ->select(
                        'schedgroupdesc',
                        'schedid'
                  )
                  ->get();

            foreach ($schedinfodetail as $item) {

                  $stime = $item->stime;
                  $etime = $item->etime;

                  $temp_day_sched = collect($roomscheddetail)->where('day', $item->day)->values();
                  foreach ($temp_day_sched as $sched_item) {

                        $grouptext = '';
                        $count = 0;
                        $schedetialgroups = collect($schedgroups)->where('schedid', $sched_item->headerid)->values();
                        foreach ($schedetialgroups as $schedetialgroup) {
                              $grouptext .= $schedetialgroup->schedgroupdesc;
                              if ($count != (count($schedetialgroups) - 1)) {
                                    $grouptext .= ' / ';
                                    $count += 1;
                              }
                        }

                        $sched_item->group = $grouptext;

                        $sched_stime = $sched_item->stime;
                        $sched_etime = $sched_item->etime;
                        if ($stime >= $sched_stime && $stime <= $sched_etime) {
                              if ($stime != $sched_etime) {
                                    array_push($conflict_list, $sched_item);
                              }
                        } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                              if ($etime != $sched_stime) {
                                    array_push($conflict_list, $sched_item);
                              }
                        } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                              array_push($conflict_list, $sched_item);
                        }
                  }



            }

            return $conflict_list;

      }


      public static function checksectionconflict(Request $request, $sections = array())
      {


            $schedid = $request->get('schedid');



            $schedinfo = DB::table('college_classsched')
                  ->where('id', $schedid)
                  ->first();


            $schedinfodetail = DB::table('college_scheddetail')
                  ->whereIn('id', $request->get('tobe_updated'))
                  ->where('college_scheddetail.deleted', 0)
                  ->select(
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time"),
                        'schedotherclass'
                  )
                  ->get();

            $syid = $schedinfo->syID;
            $semid = $schedinfo->semesterID;


            $conflict_list = array();

            foreach ($sections as $item) {

                  $temp_allsched = array();

                  $get_all_section_sched = DB::table('college_schedgroup_detail')
                        ->where('deleted', 0)
                        ->where('groupid', $item->groupid)
                        ->get();



                  //get all room sched
                  $sectioncheddetail = DB::table('college_scheddetail')
                        ->whereIn('college_scheddetail.headerid', collect($get_all_section_sched)->pluck('schedid'))
                        ->where('college_scheddetail.deleted', 0)
                        ->where('college_scheddetail.headerID', '!=', $schedid)
                        ->join('college_classsched', function ($join) use ($syid, $semid) {
                              $join->on('college_scheddetail.headerid', '=', 'college_classsched.id');
                              $join->where('college_classsched.syid', $syid);
                              $join->where('college_classsched.deleted', 0);
                              $join->where('college_classsched.semesterID', $semid);
                        })
                        ->select(
                              'college_scheddetail.stime',
                              'college_scheddetail.etime',
                              'college_scheddetail.headerid',
                              'college_scheddetail.day',
                              DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                        )
                        ->get();




                  foreach ($sectioncheddetail as $item) {
                        array_push($temp_allsched, $item);
                  }

                  $sectioncheddetail = $temp_allsched;



                  $schedgroups = DB::table('college_schedgroup_detail')
                        ->where('college_schedgroup_detail.deleted', 0)
                        ->whereIn('college_schedgroup_detail.schedid', collect($sectioncheddetail)->pluck('headerid'))
                        ->join('college_schedgroup', function ($join) {
                              $join->on('college_schedgroup_detail.groupid', '=', 'college_schedgroup.id');
                              $join->where('college_schedgroup_detail.deleted', 0);
                        })
                        ->select(
                              'schedgroupdesc',
                              'schedid'
                        )
                        ->get();


                  foreach ($schedinfodetail as $item) {

                        $stime = $item->stime;
                        $etime = $item->etime;

                        $temp_day_sched = collect($sectioncheddetail)->where('day', $item->day)->values();
                        foreach ($temp_day_sched as $sched_item) {

                              $grouptext = '';
                              $count = 0;
                              $schedetialgroups = collect($schedgroups)->where('schedid', $sched_item->headerid)->values();
                              foreach ($schedetialgroups as $schedetialgroup) {
                                    $grouptext .= $schedetialgroup->schedgroupdesc;
                                    if ($count != (count($schedetialgroups) - 1)) {
                                          $grouptext .= ' / ';
                                          $count += 1;
                                    }
                              }

                              $sched_item->group = $grouptext;

                              $sched_stime = $sched_item->stime;
                              $sched_etime = $sched_item->etime;
                              if ($stime >= $sched_stime && $stime <= $sched_etime) {
                                    if ($stime != $sched_etime) {
                                          array_push($conflict_list, $sched_item);
                                    }
                              } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                                    if ($etime != $sched_stime) {
                                          array_push($conflict_list, $sched_item);
                                    }
                              } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                                    array_push($conflict_list, $sched_item);
                              }
                        }



                  }

            }

            return $conflict_list;

      }

      public static function schedgroup(Request $request)
      {

            $search = $request->get('search');

            $schedgroup = DB::table('college_schedgroup')
                  ->where('deleted', 0)
                  ->where(function ($query) use ($search) {
                        if ($search != null && $search != "") {
                              $query->orWhere('schedgroupdesc', 'like', '%' . $search . '%');
                        }
                  })
                  ->select(
                        'id',
                        'schedgroupdesc',
                        'schedgroupdesc as text'
                  )
                  ->take(10)
                  ->skip($request->get('page') * 10)
                  ->get();

            $schedgroup_count = DB::table('college_schedgroup')
                  ->where('deleted', 0)
                  ->where(function ($query) use ($search) {
                        if ($search != null && $search != "") {
                              $query->orWhere('schedgroupdesc', 'like', '%' . $search . '%');
                        }
                  })
                  ->count();

            return @json_encode((object) [
                  "results" => $schedgroup,
                  "pagination" => (object) [
                        "more" => $schedgroup_count > 10 ? true : false
                  ],
                  "count_filtered" => $schedgroup_count
            ]);

      }

      public static function rooms(Request $request)
      {

            $search = $request->get('search');

            $rooms = DB::table('rooms')
                  ->where('deleted', 0)
                  ->where(function ($query) use ($search) {
                        if ($search != null && $search != "") {
                              $query->orWhere('roomname', 'like', '%' . $search . '%');
                        }
                  })
                  ->select(
                        'id',
                        'roomname',
                        'roomname as text'
                  )
                  ->take(10)
                  ->skip($request->get('page') * 10)
                  ->get();

            $rooms_count = DB::table('rooms')
                  ->where('deleted', 0)
                  ->where(function ($query) use ($search) {
                        if ($search != null && $search != "") {
                              $query->orWhere('roomname', 'like', '%' . $search . '%');
                        }
                  })
                  ->count();

            return @json_encode((object) [
                  "results" => $rooms,
                  "pagination" => (object) [
                        "more" => $rooms_count > 10 ? true : false
                  ],
                  "count_filtered" => $rooms_count
            ]);

      }

      public static function removesched(Request $request)
      {


            try {

                  $schedid = $request->get('schedid');
                  $sectionid = $request->get('sectionid');
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');

                  $sectioninfo = DB::table('college_sections')
                        ->where('syID', $syid)
                        ->where('semesterID', $semid)
                        ->where('id', $sectionid)
                        ->select(
                              'issubjsched'
                        )
                        ->first();

                  $enrollment_count = DB::table('college_studsched')
                        ->where('schedid', $schedid)
                        ->where('schedstatus', '!=', 'DROPPED')
                        ->where('deleted', 0)
                        ->count();

                  if ($enrollment_count > 0) {

                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Schedule already used!',
                              ]
                        );

                  }

                  if ($sectioninfo->issubjsched) {

                        DB::table('college_sections')
                              ->where('syID', $syid)
                              ->where('semesterID', $semid)
                              ->where('id', $sectionid)
                              ->where('deleted', 0)
                              ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

                  DB::table('college_classsched')
                        ->where('syID', $syid)
                        ->where('semesterID', $semid)
                        ->where('id', $schedid)
                        ->where('deleted', 0)
                        ->update([
                              'deleted' => 1,
                              'deletedby' => auth()->user()->id,
                              'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('college_scheddetail')
                        ->where('headerid', $schedid)
                        ->where('deleted', 0)
                        ->update([
                              'deleted' => 1,
                              'deletedby' => auth()->user()->id,
                              'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('college_schedgroup_detail')
                        ->where('schedid', $schedid)
                        ->where('deleted', 0)
                        ->update([
                              'deleted' => 1,
                              'deletedby' => auth()->user()->id,
                              'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Schedule Deleted!',
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);

            }


      }

      //no conflict detection
      public static function updatescheddetail(Request $request)
      {

            try {

                  $letter_count = 65;
                  $schedgroup = $request->get('schedgroup');

                  $check_creator = DB::table('college_classsched')
                        ->where('id', $request->get('id'))
                        ->first();

                  if ($check_creator->createdby != auth()->user()->id && auth()->user()->type != 17) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'data' => 'Unable to process!',
                              ]
                        );
                  }


                  if ($schedgroup != "" && $schedgroup != null) {

                        $subjid = $request->get('headerid');

                        $schedinfo = DB::table('college_classsched')
                              ->where('college_classsched.id', $request->get('id'))
                              ->first();

                        $syid = $schedinfo->syID;
                        $semid = $schedinfo->semesterID;

                        $schedgroupinfo = DB::table('college_schedgroup_detail')
                              ->join('college_classsched', function ($join) use ($syid, $semid) {
                                    $join->on('college_schedgroup_detail.schedid', '=', 'college_classsched.id');
                                    $join->where('college_classsched.deleted', 0);
                                    $join->where('college_classsched.syid', $syid);
                                    $join->where('college_classsched.semesterID', $semid);
                              })
                              ->join('college_prospectus', function ($join) use ($subjid) {
                                    $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                    $join->where('college_prospectus.deleted', 0);
                                    $join->where('college_prospectus.subjectID', $subjid);
                              })
                              ->whereIn('college_schedgroup_detail.groupid', $schedgroup)
                              ->where('schedid', '!=', $request->get('id'))
                              ->where('college_schedgroup_detail.deleted', 0)
                              ->count();

                        if ($schedgroupinfo > 0) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'data' => 'Subject already exist from selected section!',
                                    ]
                              );
                        }


                  }


                  $subject = DB::table('college_prospectus')
                        ->where('subjectID', $request->get('headerid'))
                        ->where('deleted', 0)
                        ->first();

                  $sectioninfo = DB::table('college_classsched')
                        ->join('college_sections', function ($join) {
                              $join->on('college_sections.id', '=', 'college_classsched.sectionid');
                              $join->where('college_sections.deleted', 0);
                        })
                        ->where('college_classsched.id', $request->get('id'))
                        ->select(
                              'college_sections.id'
                        )
                        ->first();

                  $section_count = DB::table('college_classsched')
                        ->join('college_sections', function ($join) {
                              $join->on('college_sections.id', '=', 'college_classsched.sectionid');
                              $join->where('college_sections.deleted', 0);
                              $join->where('issubjsched', 1);
                        })
                        ->where('college_classsched.subjectID', $subject->id)
                        ->where('college_classsched.syiD', $request->get('syid'))
                        ->where('college_classsched.semesterID', $request->get('semid'))
                        ->where('college_classsched.deleted', 0)
                        ->count();




                  DB::table('college_classsched')
                        ->where('id', $request->get('id'))
                        ->take(1)
                        ->update([
                              // 'schedgroup'=>$schedgroup,
                              'subjectID' => $subject->id,
                              'capacity' => $request->get('capacity'),
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('college_sections')
                        ->where('id', $sectioninfo->id)
                        ->take(1)
                        ->update([
                              'sectionDesc' => $subject->subjCode . ' ' . chr($letter_count + $section_count),
                              'yearID' => $request->get('levelid'),
                              'section_specification' => $request->get('classtype'),
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  if ($schedgroup != "") {
                        foreach ($schedgroup as $item) {
                              $check = DB::table('college_schedgroup_detail')
                                    ->where('schedid', $request->get('id'))
                                    ->where('groupid', $item)
                                    ->where('deleted', 0)
                                    ->count();

                              if ($check == 0) {
                                    DB::table('college_schedgroup_detail')
                                          ->insert([
                                                'schedid' => $request->get('id'),
                                                'groupid' => $item,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }
                        }

                        $check = DB::table('college_schedgroup_detail')
                              ->where('schedid', $request->get('id'))
                              ->whereNotIn('groupid', $schedgroup)
                              ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  } else {
                        $check = DB::table('college_schedgroup_detail')
                              ->where('schedid', $request->get('id'))
                              ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }




                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Sched Updated!',
                        ]
                  );




            } catch (\Exception $e) {
                  return self::store_error($e);
            }



            // $room = $request->get('room');
            // $days = $request->get('days');
            // $schedotherclas = $request->get('schedotherclas');
            // $time = explode(" - ", $request->get('time'));
            // $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
            // $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');
            // $time = explode(" - ", $request->get('time'));
            // $allowconflict = $request->get('allowconflict');
            // $schedgroup = $request->get('schedgroup');
            // $schedid = $request->get('schedid');
            // $tobe_updated = $request->get('tobe_updated');

            // foreach($tobe_updated as $item){
            //       DB::table('college_scheddetail')
            //             ->where('id',$item)
            //                   ->where('deleted',0)
            //                   ->take(1)
            //                   ->update([
            //                         'stime'=>$stime,
            //                         'etime'=>$etime,
            //                         'roomid'=>$room,
            //                         'schedotherclass'=>$schedotherclas,
            //                         'updatedby'=>auth()->user()->id,
            //                         'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            //                   ]);
            // }

            // foreach($days as $item){

            //       $check_detail = Db::table('college_scheddetail')
            //                         ->where('headerid',$schedid)
            //                         ->where('day',$item)
            //                         ->where('stime',$stime)
            //                         ->where('etime',$etime)
            //                         ->where('deleted',0)
            //                         ->get();

            //       if(count($check_detail) > 0){

            //                   DB::table('college_scheddetail')
            //                         ->where('id',$check_detail[0]->id)
            //                         ->where('deleted',0)
            //                         ->take(1)
            //                         ->update([
            //                               'roomid'=>$room,
            //                               'schedotherclass'=>$schedotherclas,
            //                               'updatedby'=>auth()->user()->id,
            //                               'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            //                         ]);

            //       }else{

            //             DB::table('college_scheddetail')
            //                   ->insert([
            //                         'headerid'=> $schedid,
            //                         'day'=>$item,
            //                         'stime'=>$stime,
            //                         'etime'=>$etime,
            //                         'roomid'=>$room,
            //                         'schedotherclass'=>$schedotherclas,
            //                         'deleted'=>'0',
            //                         'createdby'=>auth()->user()->id,
            //                         'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            //                   ]);
            //       }
            // }

            // return array((object)[
            //       'status'=>1,
            //       'data'=>'Successfull.',
            // ]);

      }

      //no conflict detection
      public static function createsched(Request $request)
      {

            $room = $request->get('room');
            $days = $request->get('days');
            $schedotherclas = $request->get('schedotherclas');
            $time = explode(" - ", $request->get('time'));
            $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
            $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');
            $time = explode(" - ", $request->get('time'));
            $allowconflict = $request->get('allowconflict');
            $schedgroup = $request->get('schedgroup');
            $schedid = $request->get('schedid');



            foreach ($days as $item) {

                  $check_detail = Db::table('college_scheddetail')
                        ->where('headerid', $schedid)
                        ->where('day', $item)
                        ->where('stime', $stime)
                        ->where('etime', $etime)
                        ->where('deleted', 0)
                        ->get();

                  if (count($check_detail) > 0) {

                        DB::table('college_scheddetail')
                              ->where('id', $check_detail[0]->id)
                              ->where('deleted', 0)
                              ->take(1)
                              ->update([
                                    'roomid' => $room,
                                    'schedotherclass' => $schedotherclas,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                  } else {

                        DB::table('college_scheddetail')
                              ->insert([
                                    'headerid' => $schedid,
                                    'day' => $item,
                                    'stime' => $stime,
                                    'etime' => $etime,
                                    'roomid' => $room,
                                    'schedotherclass' => $schedotherclas,
                                    'deleted' => '0',
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
            }

            return array(
                  (object) [
                        'status' => 1,
                        'data' => 'Created Successfully!',
                  ]
            );

      }

      //no conflict detection
      public static function updatesched(Request $request)
      {

            $room = $request->get('room');
            $days = $request->get('days');
            $schedotherclas = $request->get('schedotherclas');
            $time = explode(" - ", $request->get('time'));
            $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
            $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');
            $time = explode(" - ", $request->get('time'));
            $allowconflict = $request->get('allowconflict');
            $schedgroup = $request->get('schedgroup');
            $schedid = $request->get('schedid');
            $tobe_updated = $request->get('tobe_updated');
            $conflict_info = array();

            $check_creator = DB::table('college_classsched')
                  ->where('id', $schedid)
                  ->first();

            if ($check_creator->createdby != auth()->user()->id && auth()->user()->type != 17) {
                  return array(
                        (object) [
                              'status' => 0,
                              'message' => 'Unable to process!',
                        ]
                  );
            }

            if ($allowconflict == 0) {

                  $section = DB::table('college_schedgroup_detail')
                        ->where('deleted', 0)
                        ->where('schedid', $schedid)
                        ->get();

                  if (count($section) > 0) {
                        $conflict_info_section = self::checksectionconflict($request, $section);

                        $conflict_info_section = $conflict_info_section;

                        if (count($conflict_info_section) > 0) {

                              foreach ($conflict_info_section as $item) {
                                    if ($item->day == 1) {
                                          $item->description = 'M';
                                    } else if ($item->day == 2) {
                                          $item->description = 'T';
                                    } else if ($item->day == 3) {
                                          $item->description = 'W';
                                    } else if ($item->day == 4) {
                                          $item->description = 'Th';
                                    } else if ($item->day == 5) {
                                          $item->description = 'F';
                                    } else if ($item->day == 6) {
                                          $item->description = 'S';
                                    } else if ($item->day == 7) {
                                          $item->description = 'Sun';
                                    }
                              }

                              $grouped_header_list = collect($conflict_info_section)->groupBy('headerid');


                              foreach ($grouped_header_list as $item) {

                                    $dayString = '';
                                    $temp_info = $item[0];
                                    foreach ($item as $header_group_item) {
                                          $dayString .= $header_group_item->description;
                                    }

                                    $subject = DB::table('college_classsched')
                                          ->where('college_classsched.id', $item[0]->headerid)
                                          ->join('college_prospectus', function ($join) {
                                                $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                                $join->where('college_prospectus.deleted', 0);
                                          })
                                          ->select(
                                                'subjCode',
                                                'subjDesc'
                                          )
                                          ->first();

                                    array_push($conflict_info, (object) [
                                          'type' => 'Section',
                                          'group' => $item[0]->group,
                                          'subject' => '[' . $subject->subjCode . '] ' . $subject->subjDesc,
                                          'days' => $dayString,
                                          'time' => \Carbon\Carbon::create($item[0]->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::create($item[0]->etime)->isoFormat('hh:mm A')
                                    ]);

                              }

                        }
                  }

                  $schedinfo = DB::table('college_classsched')
                        ->join('teacher', function ($join) {
                              $join->on('college_classsched.teacherID', '=', 'teacher.id');
                              $join->where('teacher.deleted', 0);
                        })
                        ->where('college_classsched.id', $schedid)
                        ->select(
                              'lastname',
                              'firstname',
                              'teacherID'
                        )->first();

                  if ($room != null && $room != "") {
                        $conflict_info_room = self::checkroomconflict($request);
                        $conflict_info_room = $conflict_info_room;

                        if (count($conflict_info_room) > 0) {

                              foreach ($conflict_info_room as $item) {
                                    if ($item->day == 1) {
                                          $item->description = 'M';
                                    } else if ($item->day == 2) {
                                          $item->description = 'T';
                                    } else if ($item->day == 3) {
                                          $item->description = 'W';
                                    } else if ($item->day == 4) {
                                          $item->description = 'Th';
                                    } else if ($item->day == 5) {
                                          $item->description = 'F';
                                    } else if ($item->day == 6) {
                                          $item->description = 'S';
                                    } else if ($item->day == 7) {
                                          $item->description = 'Sun';
                                    }
                              }

                              $grouped_header_list = collect($conflict_info_room)->groupBy('headerid');


                              foreach ($grouped_header_list as $item) {

                                    $dayString = '';
                                    $temp_info = $item[0];
                                    foreach ($item as $header_group_item) {
                                          $dayString .= $header_group_item->description;
                                    }

                                    $subject = DB::table('college_classsched')
                                          ->where('college_classsched.id', $item[0]->headerid)
                                          ->join('college_prospectus', function ($join) {
                                                $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                                $join->where('college_prospectus.deleted', 0);
                                          })
                                          ->select(
                                                'subjCode',
                                                'subjDesc'
                                          )
                                          ->first();

                                    array_push($conflict_info, (object) [
                                          'type' => 'Room',
                                          'group' => $item[0]->group,
                                          'subject' => '[' . $subject->subjCode . '] ' . $subject->subjDesc,
                                          'days' => $dayString,
                                          'time' => \Carbon\Carbon::create($item[0]->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::create($item[0]->etime)->isoFormat('hh:mm A')
                                    ]);

                              }

                        }
                  }

                  if (isset($schedinfo->teacherID)) {

                        //teacher conflict
                        if (isset($schedinfo->teacherID)) {
                              if ($schedinfo->teacherID != null) {

                                    $request->request->add(['teacherid' => $schedinfo->teacherID]);
                                    $conflict_info_teacher = self::checkteacherconflict($request);
                                    $conflict_info_teacher = $conflict_info_teacher;

                                    if (count($conflict_info_teacher) > 0) {

                                          foreach ($conflict_info_teacher as $item) {
                                                if ($item->day == 1) {
                                                      $item->description = 'M';
                                                } else if ($item->day == 2) {
                                                      $item->description = 'T';
                                                } else if ($item->day == 3) {
                                                      $item->description = 'W';
                                                } else if ($item->day == 4) {
                                                      $item->description = 'Th';
                                                } else if ($item->day == 5) {
                                                      $item->description = 'F';
                                                } else if ($item->day == 6) {
                                                      $item->description = 'S';
                                                } else if ($item->day == 7) {
                                                      $item->description = 'Sun';
                                                }
                                          }

                                          $grouped_header_list = collect($conflict_info_teacher)->groupBy('headerid');

                                          foreach ($grouped_header_list as $item) {

                                                $dayString = '';
                                                $temp_info = $item[0];
                                                foreach ($item as $header_group_item) {
                                                      $dayString .= $header_group_item->description;
                                                }

                                                $subject = DB::table('college_classsched')
                                                      ->where('college_classsched.id', $item[0]->headerid)
                                                      ->join('college_prospectus', function ($join) {
                                                            $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                                            $join->where('college_prospectus.deleted', 0);
                                                      })
                                                      ->select(
                                                            'subjCode',
                                                            'subjDesc'
                                                      )
                                                      ->first();

                                                array_push($conflict_info, (object) [
                                                      'type' => 'Teacher : ' . $schedinfo->lastname . ', ' . $schedinfo->firstname,
                                                      'group' => $item[0]->group,
                                                      'subject' => '[' . $subject->subjCode . '] ' . $subject->subjDesc,
                                                      'days' => $dayString,
                                                      'time' => \Carbon\Carbon::create($item[0]->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::create($item[0]->etime)->isoFormat('hh:mm A')
                                                ]);

                                          }

                                    }
                              }
                        }
                  }


                  if (count($conflict_info) > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Schedule Conflict!',
                                    'data' => 'Conflict',
                                    'conflict' => $conflict_info
                              ]
                        );
                  }


            }



            if ($tobe_updated != "" && $tobe_updated != null) {
                  foreach ($tobe_updated as $item) {
                        DB::table('college_scheddetail')
                              ->where('id', $item)
                              ->where('deleted', 0)
                              ->take(1)
                              ->update([
                                    'stime' => $stime,
                                    'etime' => $etime,
                                    'roomid' => $room,
                                    'schedotherclass' => $schedotherclas,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
            }

            foreach ($days as $item) {

                  $check_detail = Db::table('college_scheddetail')
                        ->where('headerid', $schedid)
                        ->where('day', $item)
                        ->where('stime', $stime)
                        ->where('etime', $etime)
                        ->where('deleted', 0)
                        ->get();

                  if (count($check_detail) > 0) {

                        DB::table('college_scheddetail')
                              ->where('id', $check_detail[0]->id)
                              ->where('deleted', 0)
                              ->take(1)
                              ->update([
                                    'roomid' => $room,
                                    'schedotherclass' => $schedotherclas,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                  } else {

                        DB::table('college_scheddetail')
                              ->insert([
                                    'headerid' => $schedid,
                                    'day' => $item,
                                    'stime' => $stime,
                                    'etime' => $etime,
                                    'roomid' => $room,
                                    'schedotherclass' => $schedotherclas,
                                    'deleted' => '0',
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
            }

            DB::table('college_scheddetail')
                  ->where('headerid', $schedid)
                  ->whereNotIn('day', $days)
                  ->where('deleted', 0)
                  ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            return array(
                  (object) [
                        'status' => 1,
                        'data' => 'Updated Successfully!',
                  ]
            );

      }

      public static function removescheddetail(Request $request)
      {

            $schedid = $request->get('schedid');
            $tobe_deleted = $request->get('tobe_deleted');

            $check_creator = DB::table('college_classsched')
                  ->where('id', $schedid)
                  ->first();

            if ($check_creator->createdby != auth()->user()->id) {
                  return array(
                        (object) [
                              'status' => 0,
                              'creator' => $check_creator->createdby,
                              'stat' => 'error',
                              'message' => 'Access denied. Only the creator can remove details.',
                        ]
                  );
            }

            $check_detail = Db::table('college_scheddetail')
                  ->where('headerid', $schedid)
                  ->whereIn('id', $tobe_deleted)
                  ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            return array(
                  (object) [
                        'status' => 1,
                        'stat' => 'success',
                        'data' => 'Deleted Successfully!',
                        'message' => 'Detail Deleted!'
                  ]
            );

      }

      public static function updateteacher(Request $request)
      {

            try {

                  $schedid = $request->get('schedid');
                  $teacherid = $request->get('teacherid');
                  $allowconflict = $request->get('allowconflict');

                  $check_creator = DB::table('college_classsched')
                        ->where('id', $schedid)
                        ->first();

                  if ($check_creator->createdby != auth()->user()->id) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'data' => 'Unable to process!',
                              ]
                        );
                  }

                  if ($teacherid == null || $teacherid == '') {
                        DB::table('college_classsched')
                              ->where('id', $schedid)
                              ->take(1)
                              ->update([
                                    'teacherID' => $teacherid,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return array(
                              (object) [
                                    'status' => 1,
                                    'message' => 'Updated Successfully!',
                              ]
                        );
                  }

                  if ($allowconflict == 0) {
                        $conflict_info = self::checkteacherconflict($request);
                        $conflict_list = $conflict_info;
                        $teacher = DB::table('teacher')
                              ->where('id', $teacherid)
                              ->first();

                        if (count($conflict_info) > 0) {

                              foreach ($conflict_list as $item) {
                                    if ($item->day == 1) {
                                          $item->description = 'M';
                                    } else if ($item->day == 2) {
                                          $item->description = 'T';
                                    } else if ($item->day == 3) {
                                          $item->description = 'W';
                                    } else if ($item->day == 4) {
                                          $item->description = 'Th';
                                    } else if ($item->day == 5) {
                                          $item->description = 'F';
                                    } else if ($item->day == 6) {
                                          $item->description = 'S';
                                    } else if ($item->day == 7) {
                                          $item->description = 'Sun';
                                    }
                              }

                              $grouped_header_list = collect($conflict_list)->groupBy('headerid');
                              $conflict_info = array();

                              foreach ($grouped_header_list as $item) {

                                    $dayString = '';
                                    $temp_info = $item[0];
                                    foreach ($item as $header_group_item) {
                                          $dayString .= $header_group_item->description;
                                    }

                                    $subject = DB::table('college_classsched')
                                          ->where('college_classsched.id', $item[0]->headerid)
                                          ->join('college_prospectus', function ($join) {
                                                $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                                $join->where('college_prospectus.deleted', 0);
                                          })
                                          ->select(
                                                'subjCode',
                                                'subjDesc'
                                          )
                                          ->first();

                                    array_push($conflict_info, (object) [
                                          'type' => 'Teacher : ' . $teacher->lastname . ', ' . $teacher->firstname,
                                          'group' => $item[0]->group,
                                          'subject' => '[' . $subject->subjCode . '] ' . $subject->subjDesc,
                                          'days' => $dayString,
                                          'time' => \Carbon\Carbon::create($item[0]->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::create($item[0]->etime)->isoFormat('hh:mm A')
                                    ]);

                              }

                              return array(
                                    (object) [
                                          'status' => 0,
                                          'message' => 'Schedule Conflict!',
                                          'data' => 'Conflict',
                                          'conflict' => $conflict_info
                                    ]
                              );
                        }
                  }

                  DB::table('college_classsched')
                        ->where('id', $schedid)
                        ->take(1)
                        ->update([
                              'teacherID' => $teacherid,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Updated Successfully!',
                        ]
                  );

            } catch (\Exception $e) {

                  return self::store_error($e);

            }

      }

      public static function updatecapacity(Request $request)
      {

            try {

                  $schedid = $request->get('schedid');
                  $capacity = $request->get('capacity');

                  DB::table('college_classsched')
                        ->where('id', $schedid)
                        ->take(1)
                        ->update([
                              'capacity' => $capacity,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Updated Successfully!',
                        ]
                  );

            } catch (\Exception $e) {

                  return self::store_error($e);

            }

      }

      public static function update_sched_teacher(Request $request)
      {

            try {

                  $schedid = $request->get('schedid');
                  $teacher = $request->get('teacher');

                  DB::table('college_classsched')
                        ->where('id', $schedid)
                        ->take(1)
                        ->update([
                              'teacherID' => $teacher,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'data' => 'Successfull.',
                        ]
                  );

            } catch (\Exception $e) {

                  return self::store_error($e);

            }

      }

      public static function teachers(Request $request)
      {

            $syid = $request->get('syid');

            $teacher_array = array();

            $search = $request->get('search');

            $teachers_faspriv = DB::table('teacher')
                  ->where('teacher.deleted', 0)
                  ->join('faspriv', function ($join) {
                        $join->on('teacher.userid', '=', 'faspriv.userid');
                        $join->where('faspriv.deleted', 0);
                        $join->where('usertype', 18);
                  })
                  ->where(function ($query) use ($search) {
                        if ($search != null && $search != "") {
                              $query->orWhere('lastname', 'like', '%' . $search . '%');
                              $query->orWhere('firstname', 'like', '%' . $search . '%');
                        }
                  })
                  ->distinct()
                  ->select(
                        'teacher.id',
                        'firstname',
                        'lastname',
                        'middlename',
                        'title',
                        'tid',
                        'suffix',
                        DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text")

                  );

            $teachers = DB::table('teacher')
                  ->where('teacher.usertypeid', 18)
                  ->where('teacher.deleted', 0)
                  ->where('teacher.isactive', 1)
                  ->where(function ($query) use ($search) {
                        if ($search != null && $search != "") {
                              $query->orWhere('lastname', 'like', '%' . $search . '%');
                              $query->orWhere('firstname', 'like', '%' . $search . '%');
                        }
                  })
                  ->take(10)
                  ->skip($request->get('page') * 10)
                  ->union($teachers_faspriv)
                  ->select(
                        'teacher.id',
                        'firstname',
                        'lastname',
                        'middlename',
                        'title',
                        'tid',
                        'suffix',
                        DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text")
                  )
                  ->distinct()
                  ->get();

            $teachers_count = DB::table('teacher')
                  ->where('teacher.usertypeid', 18)
                  ->where('teacher.isactive', 1)
                  ->where('teacher.deleted', 0)
                  ->where(function ($query) use ($search) {
                        if ($search != null && $search != "") {
                              $query->orWhere('lastname', 'like', '%' . $search . '%');
                              $query->orWhere('firstname', 'like', '%' . $search . '%');
                        }
                  })
                  ->union($teachers_faspriv)
                  ->select(
                        'teacher.id',
                        'firstname',
                        'lastname',
                        'middlename',
                        'title',
                        'tid',
                        'suffix',
                        DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text")
                  )
                  ->distinct()
                  ->count();

            return @json_encode((object) [
                  "results" => $teachers,
                  "pagination" => (object) [
                        "more" => $all_subjects_count > 10 ? true : false
                  ],
                  "count_filtered" => $teachers_count
            ]);

      }

      public static function store_error($e)
      {
            DB::table('zerrorlogs')
                  ->insert([
                        'error' => $e,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);
            return array(
                  (object) [
                        'status' => 0,
                        'data' => 'Something went wrong!'
                  ]
            );
      }


}
