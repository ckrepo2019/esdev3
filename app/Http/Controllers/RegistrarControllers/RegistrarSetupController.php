<?php

namespace App\Http\Controllers\RegistrarControllers;

use Illuminate\Http\Request;
use DB;
use Session;

class RegistrarSetupController extends \App\Http\Controllers\Controller
{

      //track

      public static function list_sh_track(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $with_enrollment_count = false;

            if ($request->has('withEnrollmentCount')) {
                  $with_enrollment_count = true;
            }

            $track = DB::table('sh_track')
                  ->where('deleted', 0)
                  ->select(
                        'id',
                        'trackname',
                        'trackname as text',
                        'trackabrv'
                  )
                  ->get();

            if ($with_enrollment_count) {

                  foreach ($track as $item) {
                        $item->enrolled = DB::table('sh_track')
                              ->join('sh_strand', function ($join) {
                                    $join->on('sh_track.id', '=', 'sh_strand.trackid');
                                    $join->where('sh_strand.deleted', 0);
                              })
                              ->join('sh_enrolledstud', function ($join) use ($syid, $semid) {
                                    $join->on('sh_strand.id', '=', 'sh_enrolledstud.strandid');
                                    $join->where('sh_enrolledstud.deleted', 0);
                                    $join->whereIn('studstatus', [1, 2, 4]);
                                    $join->where('syid', $syid);
                                    $join->where('semid', $semid);
                              })
                              ->where('sh_track.deleted', 0)
                              ->where('sh_track.id', $item->id)
                              ->distinct('studid')
                              ->count();
                  }

            }

            foreach ($track as $item) {
                  $item->strandcount = DB::table('sh_track')
                        ->join('sh_strand', function ($join) {
                              $join->on('sh_track.id', '=', 'sh_strand.trackid');
                              $join->where('sh_strand.deleted', 0);
                        })
                        ->where('sh_track.deleted', 0)
                        ->where('sh_track.id', $item->id)
                        ->count();
            }


            return $track;
      }

      public static function create_sh_track(Request $request)
      {
            try {

                  $trackname = $request->get('trackname');
                  $trackabrv = $request->get('trackabrv');

                  $check = DB::table('sh_track')
                        ->where('trackname', $trackname)
                        ->where('deleted', 0)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Exists!'
                              ]
                        );
                  }

                  DB::table('sh_track')
                        ->insert([
                              'trackname' => $trackname,
                              'trackabrv' => $trackabrv,
                              'deleted' => 0
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Track Created!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function update_sh_track(Request $request)
      {
            try {

                  $trackname = $request->get('trackname');
                  $trackabrv = $request->get('trackabrv');
                  $id = $request->get('id');

                  $check = DB::table('sh_track')
                        ->where('id', '!=', $id)
                        ->where('trackname', $trackname)
                        ->where('deleted', 0)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Exists!'
                              ]
                        );
                  }

                  DB::table('sh_track')
                        ->where('id', $id)
                        ->update([
                              'trackname' => $trackname,
                              'trackabrv' => $trackabrv,
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Track Updated!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function delete_sh_track(Request $request)
      {
            try {

                  $id = $request->get('id');

                  $check = DB::table('sh_strand')
                        ->where('trackid', $id)
                        ->where('deleted', 0)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Used: SHS Strand'
                              ]
                        );
                  }

                  DB::table('sh_track')
                        ->where('id', $id)
                        ->update([
                              'deleted' => 1
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Track Deleted!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      //track

      //strand

      public static function list_sh_strand(Request $request)
      {


            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $with_enrollment_count = false;

            if ($request->has('withEnrollmentCount')) {
                  $with_enrollment_count = true;
            }

            $strand = DB::table('sh_strand')
                  ->join('sh_track', function ($join) {
                        $join->on('sh_strand.trackid', '=', 'sh_track.id');
                        $join->where('sh_track.deleted', 0);
                  })
                  ->where('sh_strand.deleted', 0)
                  ->select(
                        'sh_strand.id',
                        'strandname',
                        'trackname',
                        'strandcode',
                        'active',
                        'trackid'
                  )
                  ->get();

            if ($with_enrollment_count) {
                  foreach ($strand as $item) {

                        $item->enrolled = DB::table('sh_enrolledstud')
                              ->where('strandid', $item->id)
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->distinct('studid')
                              ->count();
                  }
            }




            return $strand;
      }

      public static function create_sh_strand(Request $request)
      {


            try {

                  $strandname = $request->get('strandname');
                  $strandcode = $request->get('strandcode');
                  $active = $request->get('active');
                  $trackid = $request->get('trackid');

                  $check = DB::table('sh_strand')
                        ->where('deleted', 0)
                        ->where('strandname', $strandname)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Exist!'
                              ]
                        );
                  }

                  DB::table('sh_strand')
                        ->insert([
                              'strandname' => $strandname,
                              'strandcode' => $strandcode,
                              'active' => $active,
                              'trackid' => $trackid,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                              'deleted' => 0
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Strand Created!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function update_sh_strand(Request $request)
      {
            try {

                  $id = $request->get('id');
                  $strandname = $request->get('strandname');
                  $strandcode = $request->get('strandcode');
                  $active = $request->get('active');
                  $trackid = $request->get('trackid');

                  $check = DB::table('sh_strand')
                        ->where('id', '!=', $id)
                        ->where('deleted', 0)
                        ->where('strandname', $strandname)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Exist!'
                              ]
                        );
                  }

                  DB::table('sh_strand')
                        ->where('id', $id)
                        ->update([
                              'strandname' => $strandname,
                              'strandcode' => $strandcode,
                              'active' => $active,
                              'trackid' => $trackid,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Strand Updated!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function delete_sh_strand(Request $request)
      {
            try {

                  $id = $request->get('id');

                  $check1 = DB::table('studinfo')
                        ->where('strandid', $id)
                        ->where('deleted', 0)
                        ->count();

                  $check2 = DB::table('sh_enrolledstud')
                        ->where('strandid', $id)
                        ->where('deleted', 0)
                        ->count();

                  $check3 = DB::table('sh_block')
                        ->where('strandid', $id)
                        ->where('deleted', 0)
                        ->count();

                  $check4 = DB::table('subject_plot')
                        ->where('strandid', $id)
                        ->where('deleted', 0)
                        ->count();

                  if ($check1 > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Used: Student Information'
                              ]
                        );
                  }

                  if ($check2 > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Used: SHS Enrollment'
                              ]
                        );
                  }

                  if ($check2 > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Used: Section Block'
                              ]
                        );
                  }

                  if ($check2 > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Used: Subject Plot'
                              ]
                        );
                  }

                  DB::table('sh_strand')
                        ->where('id', $id)
                        ->update([
                              'deleted' => 1,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Strand Deleted!'
                        ]
                  );


            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }




      public static function courses_select(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $search = $request->get('search');

            if (Session::get('currentPortal') == 16) {

                  $teacher = DB::table('teacher')
                        ->where('tid', auth()->user()->email)
                        ->first();

                  $courses = DB::table('teacherprogramhead')
                        ->where('college_courses.collegeid', $request->get('collegeid'))
                        ->where('teacherprogramhead.deleted', 0)
                        ->where('teacherprogramhead.syid', $syid)
                        ->take(10)
                        ->skip($request->get('page') * 10)
                        ->where('teacherid', $teacher->id)
                        ->join('college_courses', function ($join) use ($search) {
                              $join->on('teacherprogramhead.courseid', '=', 'college_courses.id');
                              $join->where('college_courses.deleted', 0);
                              $join->where(function ($query) use ($search) {
                                    if ($search != null && $search != "") {
                                          $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                          $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                                    }
                              });
                        })
                        ->select(
                              'college_courses.id',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'courseDesc as text'
                        )
                        ->get();

                  $courses_count = DB::table('teacherprogramhead')
                        ->where('teacherprogramhead.deleted', 0)
                        ->where('teacherprogramhead.syid', $syid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_courses', function ($join) use ($search) {
                              $join->on('teacherprogramhead.courseid', '=', 'college_courses.id');
                              $join->where('college_courses.deleted', 0);
                              $join->where(function ($query) use ($search) {
                                    if ($search != null && $search != "") {
                                          $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                          $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                                    }
                              });
                        })
                        ->select(
                              'college_courses.id',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'courseDesc as text'
                        )
                        ->count();

            } else if (Session::get('currentPortal') == 14) {

                  $teacher = DB::table('teacher')
                        ->where('tid', auth()->user()->email)
                        ->first();

                  $courses = DB::table('teacherdean')
                        ->where('college_courses.collegeid', $request->get('collegeid'))
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        ->where('teacherid', $teacher->id)
                        ->take(10)
                        ->skip($request->get('page') * 10)
                        ->join('college_colleges', function ($join) use ($search) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                              $join->where(function ($query) use ($search) {
                                    if ($search != null && $search != "") {
                                          $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                          $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                                    }
                              });
                        })
                        ->join('college_courses', function ($join) {
                              $join->on('college_colleges.id', '=', 'college_courses.collegeid');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->select(
                              'college_courses.id',
                              'college_courses.collegeid',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'courseDesc as text'
                        )
                        ->get();

                  $courses_count = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('college_courses.collegeid', $request->get('collegeid'))
                        ->where('teacherdean.syid', $syid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_colleges', function ($join) use ($search) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                              $join->where(function ($query) use ($search) {
                                    if ($search != null && $search != "") {
                                          $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                          $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                                    }
                              });
                        })
                        ->join('college_courses', function ($join) {
                              $join->on('college_colleges.id', '=', 'college_courses.collegeid');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->select(
                              'college_courses.id',
                              'college_courses.collegeid',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'courseDesc as text'
                        )
                        ->count();
            } else {


                  $courses = DB::table('college_courses')
                        ->where('deleted', 0)
                        ->where(function ($query) use ($search) {
                              if ($search != null && $search != "") {
                                    $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                    $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                              }
                        })
                        ->take(10)
                        ->skip($request->get('page') * 10)
                        ->select(
                              'college_courses.id',
                              'college_courses.collegeid',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'courseDesc as text'
                        )
                        ->get();


                  $courses_count = DB::table('college_courses')
                        ->where('deleted', 0)
                        ->where('college_courses.collegeid', $request->get('collegeid'))
                        ->where(function ($query) use ($search) {
                              if ($search != null && $search != "") {
                                    $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                    $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                              }
                        })
                        ->select(
                              'college_courses.id',
                              'college_courses.collegeid',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'courseDesc as text'
                        )
                        ->count();

            }

            // (params.page * 10) < data.count_filtered

            return @json_encode((object) [
                  "results" => $courses,
                  "pagination" => (object) [
                        "more" => ($request->get('page') * 10) < $courses_count ? true : false
                  ],
                  "count_filtered" => $courses_count
            ]);

            return $courses;
      }

      public static function colleges_select2(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $search = $request->get('search');


            if (Session::get('currentPortal') == 16) {

                  $teacher = DB::table('teacher')
                        ->where('tid', auth()->user()->email)
                        ->first();

                  $colleges = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        ->where('teacherid', $teacher->id)
                        ->take(10)
                        ->skip($request->get('page') * 10)
                        ->join('college_colleges', function ($join) use ($search) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                              $join->where(function ($query) use ($search) {
                                    if ($search != null && $search != "") {
                                          $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                          $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                                    }
                              });
                        })
                        ->select(
                              'college_colleges.id',
                              'college_colleges.collegeDesc',
                              'college_colleges.collegeabrv',
                              'collegeDesc as text'
                        )
                        ->get();

                  $colleges_count = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_colleges', function ($join) use ($search) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                              $join->where(function ($query) use ($search) {
                                    if ($search != null && $search != "") {
                                          $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                          $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                                    }
                              });
                        })
                        ->count();

            } else if (Session::get('currentPortal') == 14) {

                  $teacher = DB::table('teacher')
                        ->where('tid', auth()->user()->email)
                        ->first();

                  $colleges = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        ->where('teacherid', $teacher->id)
                        ->take(10)
                        ->skip($request->get('page') * 10)
                        ->join('college_colleges', function ($join) use ($search) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                              $join->where(function ($query) use ($search) {
                                    if ($search != null && $search != "") {
                                          $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                          $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                                    }
                              });
                        })
                        ->select(
                              'college_colleges.id',
                              'college_colleges.collegeDesc',
                              'college_colleges.collegeabrv',
                              'collegeDesc as text'
                        )
                        ->get();

                  $colleges_count = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_colleges', function ($join) use ($search) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                              $join->where(function ($query) use ($search) {
                                    if ($search != null && $search != "") {
                                          $query->orWhere('courseDesc', 'like', '%' . $search . '%');
                                          $query->orWhere('courseabrv', 'like', '%' . $search . '%');
                                    }
                              });
                        })
                        ->count();

            } else {


                  $colleges = DB::table('college_colleges')
                        ->where('deleted', 0)
                        ->where(function ($query) use ($search) {
                              if ($search != null && $search != "") {
                                    $query->orWhere('collegeDesc', 'like', '%' . $search . '%');
                                    $query->orWhere('collegeabrv', 'like', '%' . $search . '%');
                              }
                        })
                        ->take(10)
                        ->skip($request->get('page') * 10)
                        ->select(
                              'college_colleges.id',
                              'college_colleges.collegeDesc',
                              'college_colleges.collegeabrv',
                              'collegeDesc as text'
                        )
                        ->get();


                  $colleges_count = DB::table('college_colleges')
                        ->where('deleted', 0)
                        ->where(function ($query) use ($search) {
                              if ($search != null && $search != "") {
                                    $query->orWhere('collegeDesc', 'like', '%' . $search . '%');
                                    $query->orWhere('collegeabrv', 'like', '%' . $search . '%');
                              }
                        })
                        ->count();

            }


            return @json_encode((object) [
                  "results" => $colleges,
                  "pagination" => (object) [
                        "more" => ($request->get('page') * 10) < $colleges_count ? true : false
                  ],
                  "count_filtered" => $colleges_count
            ]);

            return $courses;
      }

      public static function list_college(Request $request)
      {
            $teacherid = auth()->user()->id;
            $teacheracad = DB::table('teacher')
                  ->join('teacheracadprog', function ($join) use ($teacherid) {
                        $join->on('teacher.id', '=', 'teacheracadprog.teacherid');
                        $join->where('teacher.userid', $teacherid);
                        $join->where('teacheracadprog.deleted', 0);
                  })
                  ->where('teacher.deleted', 0)
                  ->select('teacheracadprog.acadprogid')
                  ->get();

            $syid = $request->get('syid');
            // $semid = $request->get('semid');
            $with_enrollment_count = false;

            if ($syid == null || $syid == "") {
                  $syid = DB::table('sy')
                        ->where('isactive', 1)
                        ->first()->id;
            }

            if ($request->has('withEnrollmentCount')) {
                  $with_enrollment_count = true;
            }
            if($teacheracad->contains('acadprogid', 8)){
                  $colleges = DB::table('college_colleges')
                  ->where('college_colleges.deleted', 0)
                  ->leftJoin('teacher', function ($join) {
                        $join->on('college_colleges.dean', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                  })
                  ->select(
                        'dean as deanid',
                        'college_colleges.id',
                        'collegeabrv',
                        'collegeDesc',
                        'collegeDesc as text',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'title',
                        'cisactive',
                        'acadprogid'
                  )
                  ->get();
            }else{
                  $colleges = DB::table('college_colleges')
                  ->where('college_colleges.deleted', 0)
                  ->where('college_colleges.acadprogid', '!=', 8)
                  ->leftJoin('teacher', function ($join) {
                        $join->on('college_colleges.dean', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                  })
                  ->select(
                        'dean as deanid',
                        'college_colleges.id',
                        'collegeabrv',
                        'collegeDesc',
                        'collegeDesc as text',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'title',
                        'cisactive',
                        'acadprogid'
                  )
                  ->get();
            }

            

            foreach ($colleges as $item) {

                  $enrolledcount = DB::table('college_colleges')
                        ->join('college_courses', function ($join) {
                              $join->on('college_colleges.id', '=', 'college_courses.collegeid');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->join('college_enrolledstud', function ($join) {
                              $join->on('college_courses.id', '=', 'college_enrolledstud.courseid');
                              $join->where('college_enrolledstud.deleted', 0);
                              $join->whereIn('college_enrolledstud.studstatus', [1, 2, 4]);
                        })
                        ->where('college_colleges.id', $item->id)
                        ->where('college_colleges.deleted', 0)
                        ->count();

                  if ($enrolledcount > 0) {
                        $item->withenrollees = true;
                  } else {
                        $item->withenrollees = false;
                  }

                  $temp_middle = '';
                  $temp_suffix = '';
                  $temp_title = '';
                  if (isset($item->middlename)) {
                        $temp_middle = $item->middlename[0] . '.';
                  }
                  if (isset($item->title)) {
                        $temp_title = $item->title;
                  }
                  if (isset($item->suffix)) {
                        $temp_suffix = ', ' . $item->suffix;
                  }

                  $dean_text = $item->firstname . ' ' . $temp_middle . ' ' . $item->lastname . $temp_suffix . ', ' . $temp_title;
                  $item->dean = $dean_text;
            }


            foreach ($colleges as $item) {


                  $search = '';

                  $dean = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        //->where('teacherdean.semid',$semid)
                        ->where('collegeid', $item->id)
                        ->join('teacher', function ($join) {
                              $join->on('teacherdean.teacherid', '=', 'teacher.id');
                              $join->where('teacher.deleted', 0);
                        })
                        ->select(
                              'teacher.id',
                              'firstname',
                              'lastname',
                              'middlename',
                              'acadtitle',
                              'suffix',
                              'type',
                              'tid'
                        )
                        ->get();

                  foreach ($dean as $dea_item) {
                        $temp_title = '';
                        $temp_middle = '';
                        $temp_suffix = '';
                        if (isset($dea_item->middlename)) {
                              $temp_middle = ' ' . $dea_item->middlename[0] . '.';
                        }
                        if (isset($dea_item->acadtitle)) {
                              $temp_title = ', ' . $dea_item->acadtitle . '. ';
                        }
                        if (isset($dea_item->suffix)) {
                              $temp_suffix = ', ' . $dea_item->suffix;
                        }
                        $dea_item->text = $dea_item->tid . ' - ' . $dea_item->firstname . $temp_middle . ' ' . $dea_item->lastname . $temp_suffix . $temp_title;
                        $deanname = $dea_item->firstname . $temp_middle . ' ' . $dea_item->lastname . $temp_suffix . $temp_title;
                        $dea_item->deanname = $deanname;
                        $search .= $deanname . ' ';
                  }

                  $item->dean = $dean;


                  $item->courses = DB::table('college_colleges')
                        ->join('college_courses', function ($join) {
                              $join->on('college_colleges.id', '=', 'college_courses.collegeid');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->where('college_colleges.id', $item->id)
                        ->where('college_colleges.deleted', 0)
                        ->count();

                  $item->search = $search;

            }


            return $colleges;
      }

      public static function create_college(Request $request)
      {
            try {
                  $higher = $request->get('ishigher');
                  $collegedesc = $request->get('collegedesc');
                  $collegeabrv = $request->get('collegeabrv');
                  $dean = $request->get('dean');


                  // $syid= $request->get('syid');
                  $syid = DB::table('sy')
                        ->where('isactive', 1)
                        ->first()->id;


                  $semid = $request->get('semid');
                  $headdean = $request->get('headdean');

                  $check = DB::table('college_colleges')
                        ->where('deleted', 0)
                        ->where('collegedesc', $collegedesc)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Exist!'
                              ]
                        );
                  }

                  $id = DB::table('college_colleges')
                        ->insertGetId([
                              // 'dean'=>$dean,
                              'collegedesc' => $collegedesc,
                              'collegeabrv' => $collegeabrv,
                              'acadprogid' =>$higher,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                              'deleted' => 0
                        ]);

                  if ($dean != "" && $dean != null) {

                        DB::table('teacherdean')
                              ->where('syid', $syid)
                              //->where('semid',$semid)
                              ->where('collegeid', $id)
                              ->where('deleted', 0)
                              ->whereNotIn('teacherid', $dean)
                              ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        foreach ($dean as $item) {

                              $check = DB::table('teacherdean')
                                    ->where('syid', $syid)
                                    //->where('semid',$semid)
                                    ->where('collegeid', $id)
                                    ->where('teacherid', $item)
                                    ->where('deleted', 0)
                                    ->count();

                              if ($check == 0) {
                                    DB::table('teacherdean')
                                          ->insert([
                                                'type' => 'assistant',
                                                'syid' => $syid,
                                                'semid' => 1,
                                                'collegeid' => $id,
                                                'teacherid' => $item,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }


                        }
                  }

                  if ($headdean != null) {

                        DB::table('college_colleges')
                              ->where('id', $id)
                              ->take(1)
                              ->where('deleted', 0)
                              ->update([
                                    'dean' => $headdean,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $check = DB::table('teacherdean')
                              ->where('syid', $syid)
                              ->where('collegeid', $id)
                              ->where('teacherid', $headdean)
                              ->where('deleted', 0)
                              ->count();

                        if ($check == 0) {
                              DB::table('teacherdean')
                                    ->insert([
                                          'type' => 'head',
                                          'syid' => $syid,
                                          'semid' => 1,
                                          'collegeid' => $id,
                                          'teacherid' => $headdean,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }


                  } else {
                        DB::table('college_colleges')
                              ->where('id', $id)
                              ->take(1)
                              ->where('deleted', 0)
                              ->update([
                                    'dean' => null,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'College Created!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function update_college(Request $request)
      {
            try {

                  $id = $request->get('id');
                  $collegedesc = $request->get('collegedesc');
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $collegeabrv = $request->get('collegeabrv');
                  $dean = $request->get('dean');
                  $headdean = $request->get('headdean');
                  $updatetype = $request->get('updatetype');
                  $isactive = $request->get('isactive');
                  $higher = $request->get('ishigher');

                  $check = DB::table('college_colleges')
                        ->where('id', '!=', $id)
                        ->where('deleted', 0)
                        ->where('collegedesc', $collegedesc)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Exist!'
                              ]
                        );
                  }

                  if ($updatetype == 'collegeinfo') {


                        $check_update_info = Db::table('college_colleges')
                              ->where('id', $id)
                              ->first();

                        if ($check_update_info->cisactive != $isactive) {

                              DB::table('college_colleges')
                                    ->where('id', $id)
                                    ->update([
                                          'cisactive' => $isactive,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    ]);

                              return array(
                                    (object) [
                                          'status' => 1,
                                          'message' => 'Status Updated!'
                                    ]
                              );
                        }

                        $check = DB::table('college_courses')
                              ->where('collegeid', $id)
                              ->where('deleted', 0)
                              ->count();


                        if ($check > 0) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'message' => 'Contains Courses!'
                                    ]
                              );
                        }

                        DB::table('college_colleges')
                              ->where('id', $id)
                              ->update([
                                    'cisactive' => $isactive,
                                    'collegeDesc' => $collegedesc,
                                    'collegeabrv' => $collegeabrv,
                                    'acadprogid' => $higher,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                              ]);
                  }


                  if ($updatetype == 'deaninfo') {

                        $check_sy = DB::table('sy')->where('id', $syid)->first();

                        if ($check_sy->ended == 1) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'message' => 'S.Y. already ended!'
                                    ]
                              );
                        }


                        if ($dean != "" && $dean != null) {


                              DB::table('teacherdean')
                                    ->where('syid', $syid)
                                    //->where('semid',$semid)
                                    ->where('collegeid', $id)
                                    ->where('deleted', 0)
                                    ->whereNotIn('teacherid', $dean)
                                    ->update([
                                          'deleted' => 1,
                                          'deletedby' => auth()->user()->id,
                                          'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              foreach ($dean as $item) {

                                    $check = DB::table('teacherdean')
                                          ->where('syid', $syid)
                                          //->where('semid',$semid)
                                          ->where('collegeid', $id)
                                          ->where('teacherid', $item)
                                          ->where('deleted', 0)
                                          ->count();

                                    if ($check == 0) {
                                          DB::table('teacherdean')
                                                ->insert([
                                                      'type' => 'assistant',
                                                      'syid' => $syid,
                                                      'semid' => 1,
                                                      'collegeid' => $id,
                                                      'teacherid' => $item,
                                                      'createdby' => auth()->user()->id,
                                                      'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                                ]);
                                    }


                              }
                        } else {
                              DB::table('teacherdean')
                                    ->where('syid', $syid)
                                    //->where('semid',$semid)
                                    ->where('collegeid', $id)
                                    ->where('deleted', 0)
                                    ->update([
                                          'deleted' => 1,
                                          'deletedby' => auth()->user()->id,
                                          'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }

                        if ($headdean != null) {

                              DB::table('college_colleges')
                                    ->where('id', $id)
                                    ->take(1)
                                    ->where('deleted', 0)
                                    ->update([
                                          'dean' => $headdean,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              $check = DB::table('teacherdean')
                                    ->where('syid', $syid)
                                    ->where('collegeid', $id)
                                    ->where('teacherid', $headdean)
                                    ->where('deleted', 0)
                                    ->count();

                              if ($check == 0) {
                                    DB::table('teacherdean')
                                          ->insert([
                                                'type' => 'head',
                                                'syid' => $syid,
                                                'semid' => 1,
                                                'collegeid' => $id,
                                                'teacherid' => $headdean,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }


                        } else {
                              DB::table('college_colleges')
                                    ->where('id', $id)
                                    ->take(1)
                                    ->where('deleted', 0)
                                    ->update([
                                          'dean' => null,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'College Updated!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function delete_college(Request $request)
      {
            try {

                  $id = $request->get('id');

                  $check = DB::table('college_courses')
                        ->where('collegeid', $id)
                        ->where('deleted', 0)
                        ->count();


                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Used: Courses'
                              ]
                        );
                  }

                  DB::table('college_colleges')
                        ->where('id', $id)
                        ->update([
                              'deleted' => 1,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);


                  DB::table('teacherdean')
                        ->where('collegeid', $id)
                        ->where('deleted', 0)
                        ->update([
                              'deleted' => 1,
                              'deletedby' => auth()->user()->id,
                              'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'College Deleted!'
                        ]
                  );


            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      //courses
      public static function list_course(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $with_enrollment_count = false;


            if ($syid == null || $syid == "") {
                  $syid = DB::table('sy')
                        ->where('isactive', 1)
                        ->first()->id;
            }

            if ($request->has('withEnrollmentCount')) {
                  $with_enrollment_count = true;
            }

            $courses = DB::table('college_courses')
                  ->where('college_courses.deleted', 0)
                  ->join('college_colleges', function ($join) {
                        $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                        $join->where('college_colleges.deleted', 0);
                  })
                  ->leftJoin('teacher', function ($join) {
                        $join->on('college_courses.courseChairman', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                  })->select(
                        'courseChairman',
                        'collegeabrv',
                        'college_courses.id',
                        'courseabrv',
                        'courseDesc',
                        'collegeid',
                        'courseDesc as text',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'title'
                  )
                  ->get();

            foreach ($courses as $item) {

                  $item->enrolled = DB::table('college_courses')
                        ->join('college_enrolledstud', function ($join) use ($syid, $semid) {
                              $join->on('college_courses.id', '=', 'college_enrolledstud.courseid');
                              $join->where('college_enrolledstud.deleted', 0);
                              $join->whereIn('college_enrolledstud.studstatus', [1, 2, 4]);
                              $join->where('college_enrolledstud.semid', $semid);
                              $join->where('college_enrolledstud.syid', $syid);
                        })
                        ->where('college_courses.id', $item->id)
                        ->where('college_courses.deleted', 0)
                        ->count();

                  $item->enrolled = 0;

                  $programhead = DB::table('teacherprogramhead')
                        ->where('teacherprogramhead.deleted', 0)
                        ->where('teacherprogramhead.syid', $syid)
                        //->where('teacherprogramhead.semid',$semid)
                        ->where('courseid', $item->id)
                        ->join('teacher', function ($join) {
                              $join->on('teacherprogramhead.teacherid', '=', 'teacher.id');
                              $join->where('teacher.deleted', 0);
                        })
                        ->select(
                              'teacher.id',
                              'firstname',
                              'lastname',
                              'middlename',
                              'acadtitle',
                              'suffix',
                              'type'
                        )
                        ->get();

                  foreach ($programhead as $programhead_item) {
                        $temp_title = '';
                        $temp_middle = '';
                        $temp_suffix = '';
                        if (isset($programhead_item->middlename)) {
                              $temp_middle = ' ' . $programhead_item->middlename[0] . '.';
                        }
                        if (isset($programhead_item->acadtitle)) {
                              $temp_title = ', ' . $programhead_item->acadtitle . '. ';
                        }
                        if (isset($programhead_item->suffix)) {
                              $temp_suffix = ', ' . $programhead_item->suffix;
                        }
                        $programhead_item->text = $programhead_item->firstname . $temp_middle . ' ' . $programhead_item->lastname . $temp_suffix . $temp_title;
                  }


                  $item->programhead = $programhead;

            }


            return $courses;
      }

      public static function create_course(Request $request)
      {
            try {

                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $coursedesc = $request->get('coursedesc');
                  $courseabrv = $request->get('courseabrv');
                  $collegeid = $request->get('collegeid');
                  $cphid = $request->get('cphid');
                  $cphidhead = $request->get('cphidhead');

                  $check = DB::table('college_courses')
                        ->where('deleted', 0)
                        ->where('courseDesc', $coursedesc)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Exist!'
                              ]
                        );
                  }

                  $id = DB::table('college_courses')
                        ->insertGetId([
                              // 'courseChairman'=>$cphid,
                              'coursedesc' => $coursedesc,
                              'courseabrv' => $courseabrv,
                              'collegeid' => $collegeid,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                              'deleted' => 0
                        ]);

                  $message = auth()->user()->name . ' created ' . $coursedesc . ' course';
                  $tempnew_info = DB::table('college_schedgroup')
                        ->where('id', $id)
                        ->get();

                  DB::table('logs')
                        ->insert([
                              'dataid' => $id,
                              'module' => 28,
                              'message' => $message,
                              'currentdata' => $tempnew_info,
                              'updateddata' => $tempnew_info,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  if ($cphid != "" && $cphid != null) {
                        foreach ($cphid as $item) {

                              $check = DB::table('teacherprogramhead')
                                    ->where('syid', $syid)
                                    ->where('courseid', $id)
                                    ->where('deleted', 0)
                                    ->where('teacherid', $item)
                                    ->count();

                              if ($check == 0) {

                                    DB::table('teacherprogramhead')
                                          ->insert([
                                                'syid' => $syid,
                                                'semid' => 1,
                                                'type' => 'assistant',
                                                'courseid' => $id,
                                                'teacherid' => $item,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }
                        }
                  }

                  if ($cphidhead != null) {

                        DB::table('college_courses')
                              ->where('id', $id)
                              ->take(1)
                              ->where('deleted', 0)
                              ->update([
                                    'courseChairman' => $cphidhead,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $check = DB::table('teacherprogramhead')
                              ->where('syid', $syid)
                              //->where('semid',$semid)
                              ->where('courseid', $id)
                              ->where('deleted', 0)
                              ->where('teacherid', $cphidhead)
                              ->count();

                        if ($check == 0) {

                              DB::table('teacherprogramhead')
                                    ->insert([
                                          'syid' => $syid,
                                          'semid' => 1,
                                          'type' => 'head',
                                          'courseid' => $id,
                                          'teacherid' => $cphidhead,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }


                  } else {
                        DB::table('college_courses')
                              ->where('id', $id)
                              ->take(1)
                              ->where('deleted', 0)
                              ->update([
                                    'courseChairman' => null,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }



                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Course Created!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function update_course(Request $request)
      {
            try {

                  $id = $request->get('id');
                  $cphid = $request->get('cphid');
                  $coursedesc = $request->get('coursedesc');
                  $courseabrv = $request->get('courseabrv');
                  $collegeid = $request->get('collegeid');
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $cphidhead = $request->get('cphidhead');
                  $dataprocess = $request->get('dataprocess');


                  $check_sy = DB::table('sy')
                        ->where('id', $syid)
                        ->first();

                  if ($check_sy->ended == 1) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'S.Y. Already Ended!'
                              ]
                        );
                  }


                  if ($dataprocess == 'courseinfo') {

                        $check = DB::table('college_courses')
                              ->where('id', '!=', $id)
                              ->where('deleted', 0)
                              ->where('courseDesc', $coursedesc)
                              ->count();

                        if ($check > 0) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'message' => 'Already Exist!'
                                    ]
                              );
                        }

                        $check = DB::table('college_enrolledstud')
                              ->where('courseid', $id)
                              ->where('deleted', 0)
                              ->count();

                        if ($check > 0) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'message' => 'Used: Enrolled Students'
                                    ]
                              );
                        }

                        DB::table('college_courses')
                              ->where('id', $id)
                              ->update([
                                    'courseChairman' => $cphid,
                                    'coursedesc' => $coursedesc,
                                    'courseabrv' => $courseabrv,
                                    'collegeid' => $collegeid,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                              ]);
                        DB::table('college_sections')
                        ->where('courseID', $id)
                        ->update([
                              'collegeID' => $collegeid,
                        ]);
                  }



                  if ($dataprocess == 'progheadinfo') {


                        if ($cphid != "" && $cphid != null) {

                              DB::table('teacherprogramhead')
                                    ->where('syid', $syid)
                                    ->where('courseid', $id)
                                    ->whereNotIn('teacherid', $cphid)
                                    ->where('deleted', 0)
                                    ->update([
                                          'deleted' => 1,
                                          'deletedby' => auth()->user()->id,
                                          'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              foreach ($cphid as $item) {

                                    $check = DB::table('teacherprogramhead')
                                          ->where('syid', $syid)
                                          ->where('courseid', $id)
                                          ->where('deleted', 0)
                                          ->where('teacherid', $item)
                                          ->count();

                                    if ($check == 0) {

                                          DB::table('teacherprogramhead')
                                                ->insert([
                                                      'type' => 'assistant',
                                                      'syid' => $syid,
                                                      'semid' => 1,
                                                      'courseid' => $id,
                                                      'teacherid' => $item,
                                                      'createdby' => auth()->user()->id,
                                                      'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                                ]);
                                    }


                              }

                        } else {

                              DB::table('teacherprogramhead')
                                    ->where('courseid', $id)
                                    ->where('deleted', 0)
                                    ->update([
                                          'deleted' => 1,
                                          'deletedby' => auth()->user()->id,
                                          'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        }

                        if ($cphidhead != null) {

                              DB::table('college_courses')
                                    ->where('id', $id)
                                    ->take(1)
                                    ->where('deleted', 0)
                                    ->update([
                                          'courseChairman' => $cphidhead,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              $check = DB::table('teacherprogramhead')
                                    ->where('syid', $syid)
                                    ->where('courseid', $id)
                                    ->where('deleted', 0)
                                    ->where('teacherid', $cphidhead)
                                    ->count();

                              if ($check == 0) {

                                    DB::table('teacherprogramhead')
                                          ->insert([
                                                'type' => 'head',
                                                'syid' => $syid,
                                                'semid' => 1,
                                                'courseid' => $id,
                                                'teacherid' => $cphidhead,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }


                        } else {
                              DB::table('college_courses')
                                    ->where('id', $id)
                                    ->take(1)
                                    ->where('deleted', 0)
                                    ->update([
                                          'courseChairman' => null,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }


                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Course Updated!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function delete_course(Request $request)
      {
            try {

                  $id = $request->get('id');

                  $check = DB::table('college_enrolledstud')
                        ->where('courseid', $id)
                        ->where('deleted', 0)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Used: Enrolled Students'
                              ]
                        );
                  }

                  $check = DB::table('studinfo')
                        ->where('courseid', $id)
                        ->where('deleted', 0)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Used: Student Information'
                              ]
                        );
                  }

                  DB::table('college_courses')
                        ->where('id', $id)
                        ->update([
                              'deleted' => 1,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'College Deleted!'
                        ]
                  );


            } catch (\Exception $e) {
                  return self::store_error($e);
            }
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
                        'message' => 'Something went wrong!'
                  ]
            );
      }

      public function get_sections_registrar(Request $request)
      {
          $syid = $request->get('syid');
          $semid = $request->get('semester');
          $course = $request->get('course');
          $academic = $request->get('academic');
  
          return DB::table('college_sections')
              ->where('college_sections.syID', $syid)
              // ->where('college_sections.semesterID', $semid)
              ->when($academic, function ($query) use ($academic) {
                  return $query->where('college_sections.yearID', $academic);
              })
              ->when ($course, function ($query) use ($course) {
                  return $query->where('college_sections.courseID', $course);
              })
              ->join('college_courses', 'college_sections.courseID', 'college_courses.id')
              ->join('college_year', 'college_sections.yearID', 'college_year.levelid')
              ->where('college_sections.deleted', 0)
              ->select(
                  'college_sections.id',
                  'college_sections.sectionDesc',
                  'college_sections.courseID',
                  'college_courses.courseDesc',
                  'college_courses.courseabrv',
                  'college_year.levelid as yearID',
                  'college_year.yearDesc',
              )
              ->get();
           
      }

      public function getAddedStudentLoadingAllSections(Request $request)
      {
          $studentId = $request->input('studentId');
          $syid = $request->input('syid');
          $semid = $request->input('semid');
  
          $studentLoading = DB::table('college_loadsubject')
              ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
              ->where('college_loadsubject.studid', $studentId)
              ->where('college_loadsubject.syid', $syid)
              ->where('college_loadsubject.semid', $semid)
              ->where('college_loadsubject.deleted', 0)
              ->select(
                  'college_prospectus.subjCode',
                  'college_prospectus.subjDesc',
                  'college_prospectus.lecunits',
                  'college_prospectus.labunits',
                  'college_prospectus.credunits'
              )
              ->groupBy('college_loadsubject.id')
              ->get();

            foreach ($studentLoading as $item) {
                  $item->credunits = is_null($item->credunits) ? $item->lecunits + $item->labunits : $item->credunits;
            }
  
          $totalSubjects = $studentLoading->count();
          $totalCreditUnits = $studentLoading->sum('credunits');
          $totalLecUnits = $studentLoading->sum('lecunits');
          $totalLabUnits = $studentLoading->sum('labunits');
          
          return response()->json([
              'studentLoading' => $studentLoading,
              'totalSubjects' => $totalSubjects,
              'totalCreditUnits' => $totalCreditUnits,
              'totalLecUnits' => $totalLecUnits,
              'totalLabUnits' => $totalLabUnits
          ], 200);
      }

      // public function getAddedStudentLoadingAllSections(Request $request)
      // {
      //     $studentId = $request->input('studentId');
      //     $syid = $request->input('syid');
      //     $semid = $request->input('semid');
  
      //     $studentLoading = DB::table('college_loadsubject')
      //         ->join('college_subjects', 'college_loadsubject.subjectID', '=', 'college_subjects.id')
      //         ->where('college_loadsubject.studid', $studentId)
      //         ->where('college_loadsubject.syid', $syid)
      //         ->where('college_loadsubject.semid', $semid)
      //         ->where('college_loadsubject.deleted', 0)
      //         ->select(
      //             'college_subjects.subjCode',
      //             'college_subjects.subjDesc',
      //             'college_subjects.lecunits',
      //             'college_subjects.labunits'
      //         )
      //         ->get();
  
      //     $totalSubjects = $studentLoading->count();
      //     $totalCreditUnits = $studentLoading->sum('lecunits') + $studentLoading->sum('labunits');
          
  
      //     return response()->json([
      //         'studentLoading' => $studentLoading,
      //         'totalSubjects' => $totalSubjects,
      //         'totalCreditUnits' => $totalCreditUnits
      //     ], 200);
      // }

}
