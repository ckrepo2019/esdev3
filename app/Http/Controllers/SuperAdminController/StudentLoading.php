<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;

class StudentLoading extends \App\Http\Controllers\Controller
{

      public static function printable(Request $request)
      {

            $schoolinfo = DB::table('schoolinfo')->first();
            $search = json_decode($request->get('search'));
            $request->request->add(['search' => collect($search)->toArray()]);
            $schedlist = self::all_sched($request);

            $schedlist = @json_decode($schedlist);

            $syinfo = DB::table('sy')
                  ->where('id', $request->get('syid'))
                  ->first();

            $seminfo = DB::table('semester')
                  ->where('id', $request->get('semid'))
                  ->first();


            $pdf = PDF::loadView('superadmin.pages.college.schedlistpdf', compact('schedlist', 'schoolinfo', 'syinfo', 'seminfo'))->setPaper('legal');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();

      }

      public static function getActiveEnrollmentSetup(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $activeEnrollmentSetup = DB::table('early_enrollment_setup')
                  ->where('deleted', 0)
                  ->where('syid', $syid)
                  ->where('semid', $semid)
                  ->where('isactive', 1)
                  ->where('acadprogid', 6)
                  ->get();

            foreach ($activeEnrollmentSetup as $item) {
                  if ($item->collegeentype == 2) {
                        $item->collegeentypedes = 'Adding/Dropping';
                  } else {
                        $item->collegeentypedes = 'Regular';
                  }

                  $item->enrollmentstartdesc = \Carbon\Carbon::create($item->enrollmentstart)->isoFormat('MMMM DD, YYYY');
                  $item->enrollmentenddesc = \Carbon\Carbon::create($item->enrollmentend)->isoFormat('MMMM DD, YYYY');
            }

            return $activeEnrollmentSetup;

      }

      // public static function enroll_colleges(Request $request){

      //       if(Session::get('currentPortal') == 16){

      //             $teacher = DB::table('teacher')
      //                               ->where('tid',auth()->user()->email)
      //                               ->first();

      //             $colleges = DB::table('college_courses')
      //                         ->join('college_colleges',function($join){
      //                               $join->on('college_courses.collegeid','=','college_colleges.id');
      //                               $join->where('college_colleges.deleted',0);
      //                         })
      //                         ->where('courseChairman',$teacher->id)
      //                         ->where('college_courses.deleted',0)
      //                         ->select('college_colleges.*')
      //                         ->get();

      //       }else if(Session::get('currentPortal') == 14){

      //             $teacher = DB::table('teacher')
      //                               ->where('tid',auth()->user()->email)
      //                               ->first();

      //             $programhead = DB::table('teacherprogramhead')
      //                               ->where('teacherprogramhead.deleted',0)
      //                               ->where('teacherprogramhead.syid',$syid)
      //                               ->where('teacherprogramhead.semid',$semid)
      //                               ->where('courseid',$item->id)
      //                               ->join('teacher',function($join){
      //                                     $join->on('teacherprogramhead.teacherid','=','teacher.id');
      //                                     $join->where('teacher.deleted',0);
      //                               })
      //                               ->select(
      //                                     'teacher.id',
      //                                     'firstname',
      //                                     'lastname',
      //                                     'middlename',
      //                                     'title',
      //                                     'suffix'
      //                               )
      //                               ->get();

      //       }else{
      //             $colleges = DB::table('college_colleges')->where('deleted',0)->get();
      //       }



      // }

      public static function get_course(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            if (Session::get('currentPortal') == 16) {

                  $teacher = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();

                  $courses = DB::table('teacherprogramhead')
                        ->where('teacherprogramhead.deleted', 0)
                        ->where('teacherprogramhead.syid', $syid)
                        //->where('teacherprogramhead.semid',$semid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_courses', function ($join) {
                              $join->on('teacherprogramhead.courseid', '=', 'college_courses.id');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->select(
                              // 'collegeid',
                              'college_courses.id',
                              'college_courses.courseDesc'
                        )
                        ->get();


                  return $courses;

            } else if (Session::get('currentPortal') == 14) {

                  $teacher = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();

                  $courses = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        //->where('teacherdean.semid',$semid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_colleges', function ($join) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                        })
                        ->join('college_courses', function ($join) {
                              $join->on('college_colleges.id', '=', 'college_courses.collegeid');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->select(
                              // 'collegeid',
                              'college_courses.id',
                              'college_courses.courseDesc'
                        )
                        ->get();

                  return $courses;

            } else {
                  $courses = DB::table('college_courses')
                        ->where('deleted', 0)
                        ->select(
                              // 'collegeid',
                              'college_courses.id',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv'
                        )
                        ->get();

                  return $courses;

            }
      }


      public static function all_sched_filter(Request $request)
      {


            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $search = $request->get('search');
            $type = $request->get('type');

            $college_classched = DB::table('college_classsched')
                  ->join('college_prospectus', function ($join) {
                        $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                        $join->where('college_prospectus.deleted', 0);
                  })
                  ->leftJoin('teacher', function ($join) {
                        $join->on('college_classsched.teacherID', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                  })
                  ->join('college_sections', function ($join) {
                        $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                        $join->where('college_sections.deleted', 0);
                  })
                  ->where(function ($query) use ($search) {
                        $query->orWhere('subjCode', 'like', '%' . $search . '%');
                        $query->orWhere('subjDesc', 'like', '%' . $search . '%');
                        $query->orWhere('sectionDesc', 'like', '%' . $search . '%');
                  })
                  ->take(10)
                  ->skip($request->get('page') * 10)
                  ->where('college_classsched.deleted', 0)
                  ->where('college_classsched.syid', $syid)
                  ->where('college_classsched.semesterID', $semid);
            if ($type == 'section') {
                  $college_classched = $college_classched->select(
                        'sectionID as id',
                        'sectionDesc'
                  );
            } else if ($type == 'subjcode') {
                  $college_classched = $college_classched->select(
                        'college_classsched.subjectID as id',
                        'subjCode'
                  );
            } else if ($type == 'subjdesc') {
                  $college_classched = $college_classched->select(
                        'college_classsched.subjectID as id',
                        'subjDesc'
                  );
            }


            $college_classched = $college_classched
                  ->distinct()
                  ->get();


            $college_classched_count = DB::table('college_classsched')
                  ->join('college_prospectus', function ($join) {
                        $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                        $join->where('college_prospectus.deleted', 0);
                  })
                  ->leftJoin('teacher', function ($join) {
                        $join->on('college_classsched.teacherID', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                  })
                  ->join('college_sections', function ($join) {
                        $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                        $join->where('college_sections.deleted', 0);
                  })
                  ->where(function ($query) use ($search) {
                        $query->orWhere('subjCode', 'like', '%' . $search . '%');
                        $query->orWhere('subjDesc', 'like', '%' . $search . '%');
                        $query->orWhere('sectionDesc', 'like', '%' . $search . '%');
                  })
                  ->where('college_classsched.deleted', 0)
                  ->where('college_classsched.syid', $syid)
                  ->where('college_classsched.semesterID', $semid);

            if ($type == 'section') {
                  $college_classched_count = $college_classched_count
                        ->count(DB::raw('DISTINCT sectionID'));
            } else if ($type == 'subjcode') {
                  $college_classched_count = $college_classched_count
                        ->count(DB::raw('DISTINCT subjCode'));
            } else if ($type == 'subjdesc') {
                  $college_classched_count = $college_classched_count
                        ->count(DB::raw('DISTINCT subjDesc'));
            }


            foreach ($college_classched as $item) {
                  if ($type == 'section') {
                        $item->text = $item->sectionDesc;
                  } else if ($type == 'subjcode') {
                        $item->text = $item->subjCode;
                  } else if ($type == 'subjdesc') {
                        $item->text = $item->subjDesc;
                  }
            }

            return @json_encode((object) [
                  "results" => $college_classched,
                  "pagination" => (object) [
                        "more" => ($request->get('page') * 10) < $college_classched_count ? true : false
                  ],
                  "count_filtered" => $college_classched_count
            ]);


      }


      public static function all_sched(Request $request)
      {

            // return $request->all();

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $search = $request->get('search');
            $filtersubjdesc = $request->get('filtersubjdesc');
            $filtersection = $request->get('filtersection');
            $filtersubjcode = $request->get('filtersubjcode');
            $filtersubjgroup = $request->get('filtersubjgroup');
            $filterroom = $request->get('filterroom');
            $filterteacher = $request->get('filterteacher');
            $filterclasstype = $request->get('filterclasstype');
            $search = $search['value'];

            if ($filtersubjgroup != null && $filtersubjgroup != "") {

                  $schedid = DB::table('college_schedgroup_detail')
                        ->where('groupid', $filtersubjgroup)
                        ->where('deleted', 0)
                        ->select(
                              'schedid'
                        )
                        ->get();

            }

            if ($filterroom != null && $filterroom != "") {

                  $filterroomdetail = DB::table('college_scheddetail')
                        ->where('roomID', $filterroom)
                        ->join('college_classsched', function ($join) use ($syid, $semid) {
                              $join->on('college_scheddetail.headerid', '=', 'college_classsched.id');
                              $join->where('college_classsched.deleted', 0);
                              $join->where('college_classsched.syid', $syid);
                              $join->where('college_classsched.semesterID', $semid);
                        })
                        ->where('college_scheddetail.deleted', 0)
                        ->select(
                              'college_scheddetail.headerID as schedid'
                        )
                        ->get();

            }

            $college_classched = DB::table('college_classsched')
                  ->join('college_prospectus', function ($join) {
                        $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                        $join->where('college_prospectus.deleted', 0);
                  })
                  ->join('users', function ($join) {
                        $join->on('college_classsched.createdby', '=', 'users.id');

                  })
                  ->leftJoin('teacher', function ($join) {
                        $join->on('college_classsched.teacherID', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                  })
                  ->join('college_sections', function ($join) use ($filterclasstype) {
                        $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                        $join->where('college_sections.deleted', 0);

                        if ($filterclasstype != null && $filterclasstype != "") {
                              $join->where('college_sections.section_specification', $filterclasstype);
                        }

                  })
                  ->leftJoin('college_schedgroup', function ($join) {
                        $join->on('college_classsched.schedgroup', '=', 'college_schedgroup.id');
                        $join->where('college_schedgroup.deleted', 0);
                  })
                  ->where(function ($query) use ($search) {
                        $query->orWhere('subjCode', 'like', '%' . $search . '%');
                        $query->orWhere('subjDesc', 'like', '%' . $search . '%');
                        $query->orWhere('sectionDesc', 'like', '%' . $search . '%');
                  });

            if ($filtersection != null && $filtersection != "") {
                  $college_classched = $college_classched->where('college_classsched.sectionID', $filtersection);
            }




            if ($filtersubjgroup != null && $filtersubjgroup != "") {
                  $college_classched = $college_classched->whereIn('college_classsched.id', collect($schedid)->pluck('schedid'));
            }

            if ($filterroom != null && $filterroom != "") {
                  $college_classched = $college_classched->whereIn('college_classsched.id', collect($filterroomdetail)->pluck('schedid'));
            }

            if ($filterteacher != null && $filterteacher != "") {
                  $college_classched = $college_classched->where('college_classsched.teacherID', $filterteacher);
            }

            if ($filtersubjdesc != null && $filtersubjdesc != "") {
                  $college_classched = $college_classched->where('college_classsched.subjectID', $filtersubjdesc);
            }
            if ($filtersubjcode != null && $filtersubjcode != "") {
                  $college_classched = $college_classched->where('college_classsched.subjectID', $filtersubjcode);
            }
            if ($request->get('length') != null && $request->get('length')) {
                  $college_classched = $college_classched->take($request->get('length'));
            }
            if ($request->get('start') != null && $request->get('start') != "") {
                  $college_classched = $college_classched->skip($request->get('start'));
            }

            $college_classched = $college_classched
                  ->where('college_classsched.deleted', 0)
                  ->where('college_classsched.syid', $syid)
                  ->where('college_classsched.semesterID', $semid)
                  ->select(
                        'name',
                        'section_specification',
                        'college_sections.yearID',
                        'college_classsched.syID',
                        'college_classsched.semesterID',
                        'lastname',
                        'firstname',
                        'subjCode',
                        'subjDesc',
                        'sectionID',
                        'teacherID',
                        'subjDesc as text',
                        'college_prospectus.subjectID as prospectusSubj',
                        'college_classsched.subjectID',
                        'college_classsched.id',
                        'college_classsched.id as dataid',
                        'lecunits',
                        'labunits',
                        'capacity',
                        'sectionDesc',
                        'schedgroup',
                        'schedgroupdesc',
                        'tid'
                  )
                  ->get();

            $college_classched_count = DB::table('college_classsched')
                  ->join('college_prospectus', function ($join) {
                        $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                        $join->where('college_prospectus.deleted', 0);
                  })
                  ->join('college_sections', function ($join) use ($filterclasstype) {
                        $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                        $join->where('college_sections.deleted', 0);

                        if ($filterclasstype != null && $filterclasstype != "") {
                              $join->where('college_sections.section_specification', $filterclasstype);
                        }
                  });

            if ($filtersection != null && $filtersection != "") {
                  $college_classched_count = $college_classched_count->where('college_classsched.sectionID', $filtersection);
            }
            if ($filtersubjdesc != null && $filtersubjdesc != "") {
                  $college_classched_count = $college_classched_count->where('college_classsched.subjectID', $filtersubjdesc);
            }
            if ($filtersubjcode != null && $filtersubjcode != "") {
                  $college_classched_count = $college_classched_count->where('college_classsched.subjectID', $filtersubjcode);
            }
            if ($filterteacher != null && $filterteacher != "") {
                  $college_classched_count = $college_classched_count->where('college_classsched.teacherID', $filterteacher);
            }
            if ($filtersubjgroup != null && $filtersubjgroup != "") {
                  $college_classched_count = $college_classched_count->whereIn('college_classsched.id', collect($schedid)->pluck('schedid'));
            }
            if ($filterroom != null && $filterroom != "") {
                  $college_classched_count = $college_classched_count->whereIn('college_classsched.id', collect($filterroomdetail)->pluck('schedid'));
            }


            $college_classched_count = $college_classched_count->where(function ($query) use ($search) {
                  $query->orWhere('subjCode', 'like', '%' . $search . '%');
                  $query->orWhere('subjDesc', 'like', '%' . $search . '%');
                  $query->orWhere('sectionDesc', 'like', '%' . $search . '%');
            })
                  ->where('college_classsched.deleted', 0)
                  ->where('college_classsched.syid', $syid)
                  ->where('college_classsched.semesterID', $semid)
                  ->count();



            $sched_array = collect($college_classched)->pluck('id');

            $college_scheddetail = DB::table('college_scheddetail')
                  ->whereIn('headerID', $sched_array)
                  ->where('college_scheddetail.deleted', 0)
                  ->orderBy('stime')
                  ->leftJoin('rooms', function ($join) {
                        $join->on('college_scheddetail.roomID', '=', 'rooms.id');
                        $join->where('rooms.deleted', 0);
                  })
                  ->select(
                        'college_scheddetail.id',
                        'roomid',
                        'headerID',
                        'stime',
                        'etime',
                        'day',
                        'roomname',
                        'schedotherclass'
                  )
                  ->get();


            $enrollment = DB::table('college_enrolledstud')
                  ->where('college_enrolledstud.syid', $syid)
                  ->where('college_enrolledstud.semid', $semid)
                  ->whereIn('studstatus', [1, 2, 4])
                  ->where('deleted', 0)
                  ->select('college_enrolledstud.studid')
                  ->distinct('studid')
                  ->get();

            $stud_sched = DB::table('college_studsched')
                  ->whereIn('schedid', $sched_array)
                  ->whereIn('studid', collect($enrollment)->pluck('studid'))
                  ->where('college_studsched.deleted', 0)
                  ->where('college_studsched.schedstatus', '!=', 'DROPPED')
                  ->select(
                        db::raw('count(college_studsched.`studid`) AS enrolled'),
                        'college_studsched.schedid'
                  )
                  ->groupBy('college_studsched.schedid')
                  ->get();

            $all_stud_sched = DB::table('college_studsched')
                  ->whereIn('schedid', $sched_array)
                  ->where('college_studsched.deleted', 0)
                  ->where('college_studsched.schedstatus', '!=', 'DROPPED')
                  ->groupBy('schedid')
                  ->select(
                        db::raw('count(college_studsched.`studid`) AS enrolled'),
                        'schedid'
                  )
                  ->get();


            $sched_group_detail = DB::table('college_schedgroup_detail')
                  ->leftJoin('college_schedgroup', function ($join) {
                        $join->on('college_schedgroup_detail.groupid', '=', 'college_schedgroup.id');
                        $join->where('college_schedgroup.deleted', 0);
                  })
                  ->leftJoin('college_courses', function ($join) {
                        $join->on('college_schedgroup.courseid', '=', 'college_courses.id');
                        $join->where('college_courses.deleted', 0);
                  })
                  ->leftJoin('gradelevel', function ($join) {
                        $join->on('college_schedgroup.levelid', '=', 'gradelevel.id');
                        $join->where('gradelevel.deleted', 0);
                  })
                  ->leftJoin('college_colleges', function ($join) {
                        $join->on('college_schedgroup.collegeid', '=', 'college_colleges.id');
                        $join->where('college_colleges.deleted', 0);
                  })
                  ->where('college_schedgroup_detail.deleted', 0)
                  ->whereIn('college_schedgroup_detail.schedid', $sched_array)
                  ->select(
                        'schedid',
                        'college_schedgroup.courseid',
                        'college_schedgroup.levelid',
                        'college_schedgroup.collegeid',
                        'courseDesc',
                        'collegeDesc',
                        'levelname',
                        'courseabrv',
                        'collegeabrv',
                        'college_schedgroup.id',
                        'college_schedgroup.schedgroupdesc'
                  )
                  ->get();

            foreach ($sched_group_detail as $item) {
                  $text = '';
                  if ($item->courseid != null) {
                        $text = $item->courseabrv;
                  } else {
                        $text = $item->collegeabrv;
                  }
                  $text .= '-' . $item->levelname[0] . ' ' . $item->schedgroupdesc;
                  $item->text = $text;
            }

            $studsched = array();

            // return $request->all();


            if ($request->get('url') == "studentloading") {

                  $studsched = DB::table('college_studsched')
                        ->where('studid', $request->get('studid'))
                        ->whereIn('schedid', $sched_array)
                        ->where('deleted', 0)
                        ->select(
                              'schedstatus',
                              'schedid'
                        )->get();

            }

            foreach ($college_classched as $item) {
                  $check = collect($studsched)->where('schedid', $item->id)->first();
                  $item->selected = 0;
                  if (isset($check->schedid)) {
                        if ($check->schedstatus == 'REGULAR' || $check->schedstatus == 'ADDITIONAL') {
                              $item->selected = 1;
                        } else {
                              $item->selected = 0;
                        }

                  }
            }


            $data = array(
                  (object) [
                        'college_classsched' => $college_classched,
                        'enrolled' => $stud_sched,
                        'all_stud_sched' => $all_stud_sched,
                        'sched_group_detail' => $sched_group_detail,
                        // 'section'=>$college_sections,
                        'scheddetail' => $college_scheddetail
                  ]
            );

            return @json_encode((object) [
                  'data' => $data,
                  'recordsTotal' => $college_classched_count,
                  'recordsFiltered' => $college_classched_count
            ]);

            // return array((object)[
            //       'college_classsched'=>$college_classched,
            //       // 'enrolled'=>$stud_sched,
            //       // 'all_stud_sched'=>$all_stud_sched,
            //       // 'section'=>$college_sections,
            //       'scheddetail'=>$college_scheddetail
            // ]);


      }


      public static function enrollment_info(Request $request)
      {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $studid = $request->get('studid');

            $check = DB::table('college_enrolledstud')
                  ->join('studentstatus', function ($join) {
                        $join->on('college_enrolledstud.studstatus', '=', 'studentstatus.id');
                  })
                  ->where('college_enrolledstud.syid', $syid)
                  ->where('college_enrolledstud.deleted', 0)
                  ->where('semid', $semid)
                  ->where('studid', $studid)
                  ->select(
                        'college_enrolledstud.*',
                        'description',
                        'sectionid'
                  )
                  ->first();

            $student_curriculum = DB::table('college_studentcurriculum')
                  ->join('college_curriculum', function ($join) {
                        $join->on('college_studentcurriculum.curriculumid', '=', 'college_curriculum.id');
                        $join->where('college_curriculum.deleted', 0);
                  })
                  ->where('college_studentcurriculum.studid', $studid)
                  ->where('college_studentcurriculum.deleted', 0)
                  ->select(
                        'college_curriculum.id',
                        'college_curriculum.curriculumname'
                  )
                  ->first();


            $curriculum = null;
            if (isset($student_curriculum->id)) {
                  $curriculum = $student_curriculum;
            }

            if (isset($check->id)) {
                  return array(
                        (object) [
                              'sectionid' => $check->sectionid,
                              'studstatus' => $check->studstatus,
                              'description' => $check->description,
                              'curriculum' => $curriculum
                        ]
                  );
            } else {
                  return array(
                        (object) [
                              'studstatus' => 0,
                              'description' => 'NOT ENROLLED',
                              'curriculum' => $curriculum,
                        ]
                  );
            }





      }

      public static function courses(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            if (Session::get('currentPortal') == 16) {

                  $teacher = DB::table('teacher')
                        ->where('tid', auth()->user()->email)
                        ->first();

                  $courses = DB::table('teacherprogramhead')
                        ->where('teacherprogramhead.deleted', 0)
                        ->where('teacherprogramhead.syid', $syid)
                        // ->where('teacherprogramhead.semid',$semid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_courses', function ($join) {
                              $join->on('teacherprogramhead.courseid', '=', 'college_courses.id');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->select(
                              'college_courses.collegeid',
                              'college_courses.id',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv'
                        )
                        ->get();

            } else if (Session::get('currentPortal') == 14) {

                  $teacher = DB::table('teacher')
                        ->where('tid', auth()->user()->email)
                        ->first();

                  $courses = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        //->where('teacherdean.semid',$semid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_colleges', function ($join) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                        })
                        ->join('college_courses', function ($join) {
                              $join->on('college_colleges.id', '=', 'college_courses.collegeid');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->select(
                              'college_courses.collegeid',
                              'college_courses.id',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv'
                        )
                        ->get();
            } else {
                  $courses = DB::table('college_courses')
                        ->where('deleted', 0)
                        ->select(
                              'college_courses.collegeid',
                              'college_courses.id',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv'
                        )
                        ->get();
            }

            $temp_courses = array();

            foreach ($courses as $item) {

                  $item->text = $item->courseabrv . ' - ' . $item->courseDesc;

                  $curriculum = DB::table('college_curriculum')
                        ->where('courseID', $item->id)
                        ->where('deleted', 0)
                        ->select(
                              'id',
                              'curriculumname',
                              'curriculumname as text'
                        )
                        ->get();
                  $item->curriculum = $curriculum;
            }

            return $courses;
      }

      public static function set_course(Request $request)
      {

            $course = $request->get('courseid');
            $curriculum = $request->get('curriculum');
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');

            try {


                  DB::table('studinfo')
                        ->where('id', $studid)
                        ->where('deleted', 0)
                        ->take(1)
                        ->update([
                              'courseid' => $course,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  // if(auth()->user()->type == 17){

                  $enrollment_info = DB::table('college_enrolledstud')
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->first();

                  if (isset($enrollment_info->id)) {
                        DB::table('college_enrolledstud')
                              ->where('id', $enrollment_info->id)
                              ->where('deleted', 0)
                              ->take(1)
                              ->update([
                                    'courseid' => $course,
                                    'yearLevel' => $levelid,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  // }

                  DB::table('college_studentcurriculum')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->take(1)
                        ->update([
                              'deleted' => 1,
                              'deletedby' => auth()->user()->id,
                              'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('college_studentcurriculum')
                        ->insert([
                              'studid' => $studid,
                              'curriculumid' => $curriculum,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  if ($levelid != null) {
                        DB::table('studinfo')
                              ->where('id', $studid)
                              ->take(1)
                              ->update([
                                    'levelid' => $levelid
                              ]);
                  }



                  return array(
                        (object) [
                              'status' => 1,
                              'data' => 'Updated Successfully'
                        ]
                  );

            } catch (\Exception $e) {
                  return $e;
                  return self::store_error($e);
            }



      }




      public static function students(Request $request)
      {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            
            if (Session::get('currentPortal') == 16) {

                  $teacher = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();

                  // $courses = DB::table('teacher')
                  //               ->where('courseChairman',$teacher->id)
                  //               ->where('deleted',0)
                  //               ->select('id','courseDesc')
                  //               ->get();

                  $courses = DB::table('teacherprogramhead')
                        ->where('teacherprogramhead.deleted', 0)
                        ->where('teacherprogramhead.syid', $syid)
                        // ->where('teacherprogramhead.semid',$semid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_courses', function ($join) {
                              $join->on('teacherprogramhead.courseid', '=', 'college_courses.id');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->select(
                              'college_courses.*'
                        )
                        ->get();
                              
                  $temp_course = array();

                  foreach ($courses as $item) {
                        array_push($temp_course, $item->id);
                  }

                  if (count($temp_course) == 0) {
                        return array();
                  }

            } else if (Session::get('currentPortal') == 14) {

                  $teacher = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();

                  // $courses = DB::table('college_colleges')
                  //                   ->join('college_courses',function($join){
                  //                         $join->on('college_colleges.id','=','college_courses.collegeid');
                  //                         $join->where('college_courses.deleted',0);
                  //                   })
                  //                   ->where('dean',$teacher->id)
                  //                   ->where('college_colleges.deleted',0)
                  //                   ->select('college_courses.*')
                  //                   ->get();

                  $courses = DB::table('teacherdean')
                        ->where('teacherdean.deleted', 0)
                        ->where('teacherdean.syid', $syid)
                        //->where('teacherdean.semid',$semid)
                        ->where('teacherid', $teacher->id)
                        ->join('college_colleges', function ($join) {
                              $join->on('teacherdean.collegeid', '=', 'college_colleges.id');
                              $join->where('college_colleges.deleted', 0);
                        })
                        ->join('college_courses', function ($join) {
                              $join->on('college_colleges.id', '=', 'college_courses.collegeid');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->select(
                              'college_courses.*'
                        )
                        ->get();
                              
                  $temp_course = array();

                  foreach ($courses as $item) {
                        array_push($temp_course, $item->id);
                  }

                  if (count($temp_course) == 0) {
                        return array();
                  }

            } else {
                  $courses = DB::table('college_courses')
                        ->where('deleted', 0)
                        ->select(
                              'college_courses.id',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv'
                        )
                        ->get();
            }

            $students = DB::table('studinfo')
                  ->join('gradelevel', function ($join) {
                        $join->on('studinfo.levelid', '=', 'gradelevel.id');
                        $join->where('gradelevel.deleted', 0);
                        $join->whereIn('gradelevel.id', [17, 18, 19, 20, 21]);
                  })
                  ->leftJoin('college_courses', function ($join) {
                        $join->on('studinfo.courseid', '=', 'college_courses.id');
                        $join->where('college_courses.deleted', 0);
                  })
                  ->leftJoin('college_colleges', function ($join) {
                        $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                        $join->where('college_courses.deleted', 0);
                  })
                  ->leftJoin('studentstatus', function ($join) {
                        $join->on('studinfo.studstatus', '=', 'studentstatus.id');
                  })
                  ->where('studinfo.deleted', 0)
                  ->where('studinfo.studisactive', 1);

            if (Session::get('currentPortal') == 16 || Session::get('currentPortal') == 14) {
                  $students = $students->where(function ($query) use ($temp_course) {
                        $query->whereIn('courseid', $temp_course);
                        $query->orWhere('courseid', 0);
                        $query->orWhere('courseid', null);
                  });
            }


            $students = $students->select(
                  'sectionid',
                  'studinfo.levelid',
                  'studinfo.id',
                  'lastname',
                  'firstname',
                  'middlename',
                  'contactno',
                  'sid',
                  'suffix',
                  'courseDesc',
                  'courseabrv',
                  'collegeDesc',
                  'levelname',
                  'description',
                  'courseid',
                  'ismothernum',
                  'isfathernum',
                  'isguardannum',
                  'fcontactno',
                  'mcontactno',
                  'gcontactno',
                  'studstatus'
            )
                  ->get();
            
            foreach ($students as $item) {

                  $middlename = explode(" ", $item->middlename);
                  $temp_middle = '';
                  if ($middlename != null) {
                        foreach ($middlename as $middlename_item) {
                              if (strlen($middlename_item) > 0) {
                                    $temp_middle .= $middlename_item[0] . '.';
                              }
                        }
                  }
                  $item->student = $item->lastname . ', ' . $item->firstname . ' ' . $item->suffix . ' ' . $temp_middle;
                  $item->text = $item->sid . ' - ' . $item->student;
            }

            return $students;

      }


      public static function unload_all(Request $request)
      {

            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $schedule = self::collegestudentsched_plot($studid, $syid, $semid)[0]->info;

            foreach ($schedule as $item) {
                  self::remove_sched($item->id, $studid, $syid, $semid, $request);
            }

            return array(
                  (object) [
                        'status' => 1,
                        'data' => 'Removed Successfully'
                  ]
            );


      }



      public static function remove_shedule_ajax(Request $request)
      {
            $schedid = $request->get('schedid');
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            return self::remove_sched($schedid, $studid, $syid, $semid, $request);
      }

      public static function remove_sched(
            $schedid = null,
            $studid = null,
            $syid = null,
            $semid = null,
            Request $request
      ) {

            try {

                  $check_enrollment_setup = DB::table('early_enrollment_setup')
                        ->where('acadprogid', 6)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('deleted', 0)
                        ->where('isactive', 1)
                        ->select(
                              'enrollmentstart',
                              'collegeentype'
                        )
                        ->first();

                  if (!isset($check_enrollment_setup->enrollmentstart)) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'data' => 'No Enrollment Setup.',
                              ]
                        );
                  }

                  // return collect($check_enrollment_setup);

                  $status = null;

                  if ($check_enrollment_setup->collegeentype == 2) {
                        $status = 'Adding/Droppping';
                  } else {
                        $status = 'Regular';
                  }

                  //check enrollment 
                  $student_enrollment = DB::table('college_enrolledstud')
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->first();

                  if ($status == 'Regular') {

                        if (isset($student_enrollment->id)) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'data' => 'Unable to remove schedule. Student is already enrolled. Please unenroll learner or wait for adding/dropping period.'
                                    ]
                              );
                        }


                        DB::table('college_studsched')
                              ->where('studid', $studid)
                              ->where('schedid', $schedid)
                              ->where('deleted', 0)
                              ->take(1)
                              ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $message = auth()->user()->name . ' remove schedule ' . $schedid . ' from student ' . $studid;

                        DB::table('logs')
                              ->insert([
                                    'dataid' => $schedid,
                                    'module' => 26,
                                    'message' => $message,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                  } else {




                        DB::table('college_studsched')
                              ->where('studid', $studid)
                              ->where('schedid', $schedid)
                              ->where('deleted', 0)
                              ->take(1)
                              ->update([
                                    'schedstatus' => 'DROPPED',
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $message = auth()->user()->name . ' Dropped student schedule ' . $schedid . ' from student ' . $studid;

                        DB::table('logs')
                              ->insert([
                                    'dataid' => $schedid,
                                    'module' => 27,
                                    'message' => $message,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

                  $check = DB::table('college_enrolledstud')
                        ->where('deleted', 0)
                        ->where('syid', $syid)
                        ->where('studid', $studid)
                        ->where('semid', $semid)
                        ->count();

                  $status = $request->get('status');

                  if ($check > 0) {

                        if ($status == 'Regular') {
                              $studinfo = DB::table('studinfo')
                                    ->where('id', $studid)
                                    ->select(
                                          'feesid'
                                    )
                                    ->first();
                              $request->request->add(['feesid' => $studinfo->feesid]);

                              try {
                                    \App\Http\Controllers\FinanceControllers\UtilityController::resetpayment_v3($request);
                              } catch (\Exception $e) {
                                    return $e;
                              }

                              return array(
                                    (object) [
                                          'status' => 1,
                                          'data' => 'Schedule Deleted!'
                                    ]
                              );

                        } else {

                              $sched_info = DB::table('college_classsched')
                                    ->join('college_prospectus', function ($join) {
                                          $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                          $join->where('college_prospectus.deleted', 0);
                                    })
                                    ->where('college_classsched.id', $schedid)
                                    ->first();

                              $request->request->add(['process' => 'drop']);
                              $request->request->add(['units' => $sched_info->lecunits]);
                              $request->request->add(['subjcode' => $sched_info->subjCode]);

                              $studinfo = DB::table('studinfo')
                                    ->where('id', $studid)
                                    ->select(
                                          'feesid'
                                    )
                                    ->first();

                              $request->request->add(['feesid' => $studinfo->feesid]);
                              try {
                                    \App\Http\Controllers\FinanceControllers\UtilityController::resetpayment_v3($request);
                              } catch (\Exception $e) {
                              }

                              return array(
                                    (object) [
                                          'status' => 1,
                                          'data' => 'Subject marked as dropped'
                                    ]
                              );

                        }

                  }

                  return array(
                        (object) [
                              'status' => 1,
                              'data' => 'Removed Successfully'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }

      }


      public static function add_all(Request $request)
      {

            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $sectionid = $request->get('sectionid');

            $available_sched = self::availablesched($sectionid, null, $syid, $semid)[0]->info;

            foreach ($available_sched as $item) {
                  self::add_shedule($item->id, $studid, $syid, $semid, $request);
            }

            return array(
                  (object) [
                        'status' => 1,
                        'data' => 'Added Successfully'
                  ]
            );

      }

      public static function get_sectioninfo($sectionid)
      {
            $sectioninfo = DB::table('college_sections')
                  ->where('id', $sectionid)
                  ->select(
                        'sectionDesc'
                  )
                  ->first();

            return $sectioninfo;
      }

      public static function get_subjinfo($subjid)
      {

            $subjinfo = DB::table('college_prospectus')
                  ->where('id', $subjid)
                  ->select(
                        'subjCode',
                        'subjDesc'
                  )
                  ->first();

            return $subjinfo;
      }

      public static function conflict_info($schedheader, $scheditem, $day, $conflicttype)
      {

            $headerinfo = collect($schedheader)->where('id', $scheditem->headerid)->first();
            $sectinfo = self::get_sectioninfo($headerinfo->sectionID);
            $subjinfo = self::get_subjinfo($headerinfo->subjectID);

            return array(
                  (object) [
                        'conflicttype' => $conflicttype,
                        'data' => 'conflict',
                        'status' => 0,
                        'time' => \Carbon\Carbon::create($scheditem->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::create($scheditem->etime)->isoFormat('hh:mm A'),
                        'section' => $sectinfo->sectionDesc,
                        'subjcode' => $subjinfo->subjCode,
                        'subjdesc' => $subjinfo->subjDesc,
                        'day' => $day
                  ]
            );
      }

      public static function add_shedule_ajax(Request $request)
      {

            $schedid = $request->get('schedid');
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            return self::add_shedule($schedid, $studid, $syid, $semid, $request);

      }

      public static function add_shedule(
            $schedid = null,
            $studid = null,
            $syid = null,
            $semid = null,
            $request
      ) {

            try {

                  $check_enrollment_setup = DB::table('early_enrollment_setup')
                        ->where('acadprogid', 6)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('deleted', 0)
                        ->where('isactive', 1)
                        ->select(
                              'enrollmentstart',
                              'enrollmentend',
                              'collegeentype'
                        )
                        ->first();

                  if (!isset($check_enrollment_setup->enrollmentstart)) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'data' => 'No Enrollment Setup.',
                              ]
                        );
                  } else {

                        $checkIfAlreadyStarted = true;
                        $currentDay = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MM/DD/YYYY');
                        $dateStart = \Carbon\Carbon::create($check_enrollment_setup->enrollmentstart)->isoFormat('MM/DD/YYYY');
                        $dateEnd = \Carbon\Carbon::create($check_enrollment_setup->enrollmentend)->isoFormat('MM/DD/YYYY');

                        $date1 = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $currentDay . ' 00:00:00');
                        $date2 = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dateStart . ' 00:00:00');
                        $date3 = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dateEnd . ' 00:00:00');

                        if ($date1->lt($date2)) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'data' => "Will start on " . $currentDay,
                                    ]
                              );
                        }

                        if ($date1->gt($date3)) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'data' => "Ended last " . $dateEnd,
                                    ]
                              );
                        }

                  }

                  $loaded_count = DB::table('college_studsched')
                        ->where('schedid', $schedid)
                        ->where('schedstatus', '!=', 'DROPPED')
                        ->where('deleted', 0)
                        ->count();

                  $sched_capcity = DB::table('college_classsched')
                        ->where('id', $schedid)
                        ->select('capacity')
                        ->first();

                  if ($loaded_count >= $sched_capcity->capacity) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'data' => 'Schedule Capacity reached.',
                              ]
                        );
                  }

                  $check = DB::table('college_studsched')
                        ->where('schedid', $schedid)
                        ->where('deleted', 0)
                        ->where('schedstatus', '!=', 'DROPPED')
                        ->where('studid', $studid)
                        ->count();

                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'data' => 'Schedule already exist.',
                              ]
                        );
                  }




                  $scheddetaildetail = DB::table('college_scheddetail')
                        ->where('headerID', $schedid)
                        ->where('college_scheddetail.deleted', 0)
                        ->select(
                              'day',
                              'stime',
                              'etime'
                        )
                        ->get();

                  $group_sched = array();

                  foreach ($scheddetaildetail as $item) {
                        $check = collect($group_sched)
                              ->where('stime', $item->stime)
                              ->where('etime', $item->etime)
                              ->count();

                        if ($check == 0) {
                              array_push($group_sched, (object) [
                                    'stime' => $item->stime,
                                    'etime' => $item->etime
                              ]);
                        }
                  }

                  $allowconflict = $request->get('allowconflict');

                  if ($allowconflict == 0) {

                        $student_sched = DB::table('college_studsched')
                              ->join('college_classsched', function ($join) use ($syid, $semid) {
                                    $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                                    $join->where('college_classsched.deleted', 0);
                                    $join->where('college_classsched.syid', $syid);
                                    $join->where('college_classsched.semesterID', $semid);
                              })
                              ->join('college_prospectus', function ($join) {
                                    $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                    $join->where('college_prospectus.deleted', 0);
                              })
                              ->where('college_studsched.studid', $studid)
                              ->where('college_studsched.deleted', 0)
                              ->where('college_studsched.schedstatus', '!=', 'DROPPED')
                              ->select(
                                    'college_classsched.id',
                                    'college_classsched.sectionID',
                                    'college_classsched.subjectID'
                              )
                              ->get();

                        $studsched_detail = DB::table('college_scheddetail')
                              ->whereIn('headerid', collect($student_sched)->pluck('id'))
                              ->where('college_scheddetail.deleted', 0)
                              ->select(
                                    'stime',
                                    'etime',
                                    'headerid',
                                    'day',
                                    DB::raw("CONCAT(college_scheddetail.stime,' - ',college_scheddetail.etime) as time")
                              )
                              ->get();

                        foreach ($group_sched as $item) {
                              $get_days = collect($scheddetaildetail)
                                    ->where('stime', $item->stime)
                                    ->where('etime', $item->etime)
                                    ->values();

                              $detailstime = $item->stime;
                              $detailetime = $item->etime;

                              foreach ($get_days as $dayitem) {

                                    $day = '';
                                    if ($dayitem->day == 1) {
                                          $day = 'Mon';
                                    } else if ($dayitem->day == 2) {
                                          $day = 'Tue';
                                    } else if ($dayitem->day == 3) {
                                          $day = 'Wed';
                                    } else if ($dayitem->day == 4) {
                                          $day = 'Thu';
                                    } else if ($dayitem->day == 5) {
                                          $day = 'Fri';
                                    } else if ($dayitem->day == 6) {
                                          $day = 'Sat';
                                    }

                                    //collect day sched
                                    $temp_day_sched = collect($studsched_detail)->where('day', $dayitem->day)->values();

                                    //check section conflict
                                    foreach ($temp_day_sched as $sched_item) {

                                          $sched_stime = $sched_item->stime;
                                          $sched_etime = $sched_item->etime;
                                          if ($detailstime >= $sched_stime && $detailstime <= $sched_etime) {
                                                if ($detailstime != $sched_etime) {
                                                      return self::conflict_info($student_sched, $sched_item, $day, 'Schedule Conflict');
                                                }
                                          } else if ($detailetime >= $sched_stime && $detailetime <= $sched_etime) {
                                                if ($detailetime != $sched_stime) {
                                                      return self::conflict_info($student_sched, $sched_item, $day, 'Schedule Conflict');
                                                }
                                          } else if ($sched_stime >= $detailstime && $sched_etime <= $detailetime) {
                                                return self::conflict_info($student_sched, $sched_item, $day, 'Schedule Conflict');
                                          }
                                    }
                              }
                        }
                  }

                  if ($check_enrollment_setup->collegeentype == 2) {
                        $status = 'ADDITIONAL';
                  } else {
                        $status = 'REGULAR';
                  }


                  $checkForDroppedSched = DB::table('college_studsched')
                        ->where('schedid', $schedid)
                        ->where('deleted', 0)
                        ->where('schedstatus', 'DROPPED')
                        ->where('studid', $studid)
                        ->select('id')
                        ->first();

                  if (isset($checkForDroppedSched->id)) {

                        $dataid = $checkForDroppedSched->id;

                        DB::table('college_studsched')
                              ->where('studid', $studid)
                              ->where('id', $checkForDroppedSched->id)
                              ->where('deleted', 0)
                              ->update([
                                    'schedstatus' => $status,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $message = auth()->user()->name . ' updated schedule ' . $schedid . ' from dropped to ' . $status;

                        DB::table('logs')
                              ->insert([
                                    'dataid' => $dataid,
                                    'module' => 30,
                                    'message' => $message,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                  } else {

                        $dataid = DB::table('college_studsched')
                              ->where('studid', $studid)
                              ->where('schedid', $schedid)
                              ->where('deleted', 0)
                              ->insertGetId([
                                    'schedstatus' => $status,
                                    'studid' => $studid,
                                    'schedid' => $schedid,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $message = auth()->user()->name . ' add schedule ' . $schedid . ' to student ' . $studid;

                        DB::table('logs')
                              ->insert([
                                    'dataid' => $dataid,
                                    'module' => 22,
                                    'message' => $message,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }





                  $check_enrollment = DB::table('college_enrolledstud')
                        ->where('deleted', 0)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('studid', $studid)
                        ->count();

                  if ($check_enrollment > 0) {
                        if ($status == 'ADDITIONAL') {
                              $studinfo = DB::table('studinfo')
                                    ->where('id', $studid)
                                    ->select(
                                          'feesid'
                                    )
                                    ->first();
                              $request->request->add(['feesid' => $studinfo->feesid]);
                              try {
                                    \App\Http\Controllers\FinanceControllers\UtilityController::resetpayment_v3($request);


                                    $message = auth()->user()->name . ' reloaded student ' . $studid . ' ledger';
                                    DB::table('logs')
                                          ->insert([
                                                'dataid' => null,
                                                'module' => 23,
                                                'message' => $message,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);

                              } catch (\Exception $e) {
                                    return $e;
                              }
                        }
                  }

                  // $sched_info = DB::table('college_classsched')
                  //                   ->join('college_prospectus',function($join){
                  //                         $join->on('college_classsched.subjectID','=','college_prospectus.id');
                  //                         $join->where('college_prospectus.deleted',0);
                  //                   })
                  //                   ->where('college_classsched.id',$schedid)
                  //                   ->first();


                  // $request->request->add(['process' => 'add']);
                  // $request->request->add(['units' => $sched_info->lecunits]);
                  // $request->request->add(['subjcode' => $sched_info->subjCode]);

                  // $holder = \App\Http\Controllers\FinanceControllers\UtilityController::api_adjunits($request);

                  $check_count = DB::table('college_studsched')
                        ->join('college_classsched', function ($join) use ($syid, $semid) {
                              $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                              $join->where('college_classsched.deleted', 0);
                              $join->where('syid', $syid);
                              $join->where('semesterID', $semid);
                        })
                        ->where('college_studsched.deleted', 0)
                        ->where('studid', $studid)
                        ->select('sectionID')
                        ->get();

                  if (count($check_count) == 1) {
                        $request->request->add(['section' => $check_count[0]->sectionID]);
                        $section = $check_count[0]->sectionID;
                        self::set_section($request);
                  } else {
                        $section = array();
                  }

                  return array(
                        (object) [
                              'section' => $section,
                              'status' => 1,
                              'data' => 'Added Successfully',
                        ]
                  );

            } catch (\Exception $e) {
                  return $e;
                  return self::store_error($e);
            }

      }

      public static function set_section(Request $request)
      {

            $section = $request->get('section');
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            try {

                  $section = DB::table('college_sections')
                        ->where('id', $section)
                        ->where('deleted', 0)
                        ->first();

                  DB::table('studinfo')
                        ->where('id', $studid)
                        ->take(1)
                        ->update([
                              'sectionid' => $section->id,
                              'sectionname' => $section->sectionDesc,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $message = auth()->user()->name . ' updated student ' . $studid . ' studinfo section';
                  DB::table('logs')
                        ->insert([
                              'dataid' => $studid,
                              'module' => 25,
                              'message' => $message,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $enrollment_info = DB::table('college_enrolledstud')
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->select('id')
                        ->first();

                  if (isset($enrollment_info->id)) {
                        DB::table('college_enrolledstud')
                              ->where('id', $enrollment_info->id)
                              ->where('deleted', 0)
                              ->take(1)
                              ->update([
                                    'sectionid' => $section->id,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $message = auth()->user()->name . ' updated student ' . $studid . ' enrolled section';
                        DB::table('logs')
                              ->insert([
                                    'dataid' => $enrollment_info->id,
                                    'module' => 24,
                                    'message' => $message,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }



                  return array(
                        (object) [
                              'status' => 1,
                              'data' => 'Marked as student section',
                        ]
                  );

            } catch (\Exception $e) {
                  return $e;
                  return self::store_error($e);
            }

      }


      public static function sections(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $courseid = $request->get('courseid');

            $sections = DB::table('college_sections')
                  ->where('yearID', $levelid)
                  ->where('semesterID', $semid)
                  ->where('syID', $syid)
                  ->where('deleted', 0)
                  ->where('courseid', $courseid)
                  ->select(
                        'college_sections.*',
                        'sectionDesc as text'
                  )
                  ->get();



            return $sections;


      }

      public static function availablesched_plot_ajax(Request $request)
      {

            $sectionid = $request->get('sectionid');
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $schedule = self::availablesched($sectionid, $subjid, $syid, $semid);
            return view('superadmin.pages.student.collegeschedplot')->with('schedule', $schedule[0]->info);
      }


      public static function prospectus_subjects(Request $request)
      {

            try {

                  $courseid = $request->get('courseid');
                  $curriculum = $request->get('curriculum');

                  $subjects = DB::table('college_prospectus')
                        ->where('courseID', $courseid)
                        ->where('curriculumID', $curriculum)
                        ->where('deleted', 0)
                        ->select(
                              'college_prospectus.*',
                              DB::raw("CONCAT(college_prospectus.subjCode,' - ',college_prospectus.subjDesc) as text"),
                              'college_prospectus.id as prospectusid',
                              'subjectID as id'
                        )
                        ->get();

                  return $subjects;

            } catch (\Exception $e) {

                  return self::store_error($e);

            }

      }

      public static function all_subjects(Request $request)
      {

            try {

                  $subjects = DB::table('college_subjects')
                        ->where('deleted', 0)
                        ->select(
                              'college_subjects.*',
                              DB::raw("CONCAT(college_subjects.subjCode,' - ',college_subjects.subjDesc) as text")
                        )
                        ->get();

                  return $subjects;

            } catch (\Exception $e) {

                  return self::store_error($e);

            }

      }



      public static function availablesched(
            $sectionid = null,
            $subjid = null,
            $syid = null,
            $semid = null
      ) {

            try {

                  $subjects = DB::table('college_classsched')
                        ->join('college_prospectus', function ($join) use ($subjid) {
                              $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                              $join->where('college_prospectus.deleted', 0);
                              if ($subjid != null) {
                                    $join->where('college_prospectus.subjectID', $subjid);
                              }

                        })
                        ->join('college_sections', function ($join) {
                              $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                              $join->where('college_sections.deleted', 0);
                        })
                        ->where('college_classsched.deleted', 0);


                  if ($sectionid != null) {
                        $subjects = $subjects->where('sectionID', $sectionid);
                  }

                  if ($syid != null) {
                        $subjects = $subjects->where('college_classsched.syID', $syid);
                  }

                  if ($semid != null) {
                        $subjects = $subjects->where('college_classsched.semesterID', $semid);
                  }

                  $subjects = $subjects
                        ->select(
                              'college_classsched.*',
                              'subjDesc',
                              'subjCode',
                              'sectionDesc',
                              'lecunits',
                              'labunits'
                        )
                        ->get();

                  foreach ($subjects as $item) {


                        $student_count = DB::table('college_studsched')
                              ->where('schedid', $item->id)
                              ->where('deleted', 0)
                              ->count();

                        $item->studentcount = $student_count;

                        $item->units = $item->lecunits + $item->labunits;

                        $sched = DB::table('college_scheddetail')
                              ->where('college_scheddetail.headerid', $item->id)
                              ->where('college_scheddetail.deleted', 0)
                              ->leftJoin('rooms', function ($join) {
                                    $join->on('college_scheddetail.roomid', '=', 'rooms.id');
                                    $join->where('rooms.deleted', 0);
                              })
                              ->join('days', function ($join) {
                                    $join->on('college_scheddetail.day', '=', 'days.id');
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

                        $item->teacher = null;
                        $item->teacherid = null;

                        if (isset($item->teacherID)) {
                              $temp_teacher = DB::table('teacher')
                                    ->where('id', $item->teacherID)
                                    ->first();
                              $item->teacher = $temp_teacher->firstname . ' ' . $temp_teacher->middlename . ' ' . $temp_teacher->lastname;
                              $item->teacherid = $temp_teacher->tid;
                        }

                        foreach ($sched as $sched_item) {
                              $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                        }

                        $starting = collect($sched)->groupBy('time');

                        $sched_list = array();
                        $sched_count = 1;

                        foreach ($starting as $sched_item) {

                              $dayString = '';
                              $days = array();

                              foreach ($sched_item as $new_item) {
                                    $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                                    $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                                    $dayString .= substr($new_item->description, 0, 3) . ' / ';
                                    $detailid = $new_item->detailid;
                                    $roomname = $new_item->roomname;
                                    $roomid = $new_item->roomid;
                                    $time = $new_item->time;
                                    $schedotherclass = $new_item->schedotherclass;
                                    array_push($days, $new_item->day);
                              }

                              $dayString = substr($dayString, 0, -2);

                              array_push($sched_list, (object) [
                                    'day' => $dayString,
                                    'start' => $start,
                                    'end' => $end,
                                    'roomid',
                                    'detailid' => $detailid,
                                    'roomname' => $roomname,
                                    'roomid' => $roomid,
                                    // 'teacher'=>$teacher,
                                    // 'tid'=>$tid,
                                    // 'teacherid'=>$teacherid,
                                    'sched_count' => $sched_count,
                                    'classification' => $schedotherclass,
                                    'time' => $time,
                                    'days' => $days
                              ]);


                              $sched_count += 1;

                        }
                        $item->schedule = $sched_list;


                  }

                  return array(
                        (object) [
                              'status' => 1,
                              'data' => 'Successfull.',
                              'info' => $subjects
                        ]
                  );

            } catch (\Exception $e) {
                  return $e;
                  return self::store_error($e);
            }

      }

      public static function collegestudentsched_plot_ajax(Request $request)
      {

            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $courseid = $request->get('courseid');

            $schedule = self::collegestudentsched_plot($studid, $syid, $semid, true);

            $sections = DB::table('college_schedgroup_detail')
                  ->where('college_schedgroup_detail.deleted', 0)
                  ->join('college_schedgroup', function ($join) {
                        $join->on('college_schedgroup_detail.groupid', '=', 'college_schedgroup.id');
                        $join->where('college_schedgroup.deleted', 0);
                  })

                  ->leftJoin('college_courses', function ($join) {
                        $join->on('college_schedgroup.courseid', '=', 'college_courses.id');
                        $join->where('college_courses.deleted', 0);
                  })
                  ->leftJoin('gradelevel', function ($join) {
                        $join->on('college_schedgroup.levelid', '=', 'gradelevel.id');
                        $join->where('gradelevel.deleted', 0);
                  })
                  ->leftJoin('college_colleges', function ($join) {
                        $join->on('college_schedgroup.collegeid', '=', 'college_colleges.id');
                        $join->where('college_colleges.deleted', 0);
                  })
                  ->whereIn('schedid', collect($schedule[0]->info)->pluck('schedid'))
                  ->select(
                        'college_schedgroup.courseid',
                        'college_schedgroup.levelid',
                        'college_schedgroup.collegeid',
                        'courseDesc',
                        'collegeDesc',
                        'levelname',
                        'courseabrv',
                        'collegeabrv',
                        'college_schedgroup.id',
                        'college_schedgroup.schedgroupdesc',
                        'schedgroupdesc as text',
                        'schedid'
                  )
                  ->get();


            // return $schedule[0]->info;

            $collegeid = DB::table('college_courses')
                  ->where('id', $courseid)
                  ->select('collegeid')
                  ->first()
                  ->collegeid;

            foreach ($schedule[0]->info as $item) {

                  $check_group = collect($sections)->where('schedid', $item->schedid)->values();

                  if (count($check_group) == 1) {
                        $validSection = true;
                        if ($check_group[0]->courseid != null) {
                              if ($check_group[0]->courseid != $courseid) {
                                    $validSection = false;
                              }
                        } else if ($check_group[0]->collegeid != null) {
                              if ($check_group[0]->collegeid != $collegeid) {
                                    $validSection = false;
                              }
                        }

                        if ($validSection) {
                              $text = '';
                              if ($check_group[0]->courseid != null) {
                                    $text = $check_group[0]->courseabrv;
                              } else {
                                    $text = $check_group[0]->collegeabrv;
                              }
                              $text .= '-' . $check_group[0]->levelname[0] . ' ' . $check_group[0]->schedgroupdesc;
                              $item->sectionDesc = $text;
                        } else {
                              $item->sectionDesc = 'Schedule is not assigned to a section.';
                        }



                        // return $check_group;
                  } else if (count($check_group) > 1) {
                        $text = '';
                        $tempgroup = array();

                        $checkcoursegroup = collect($check_group)->where('courseid', $courseid)->values();


                        if (count($checkcoursegroup) != 0) {
                              $text = $checkcoursegroup[0]->courseabrv;
                              $text .= '-' . $checkcoursegroup[0]->levelname[0] . ' ' . $checkcoursegroup[0]->schedgroupdesc;
                              $item->sectionDesc = $text;
                        } else {
                              $checkcoursegroup = collect($check_group)->where('collegeid', $collegeid)->values();
                              if (count($checkcoursegroup) != 0) {
                                    $text = $checkcoursegroup[0]->collegeabrv;
                                    $text .= '-' . $checkcoursegroup[0]->levelname[0] . ' ' . $checkcoursegroup[0]->schedgroupdesc;
                                    $item->sectionDesc = $text;
                              } else {
                                    $item->sectionDesc = 'Schedule is not assigned to a section.';
                              }
                        }
                  } else {
                        $item->sectionDesc = 'Schedule is not assigned to a section.';
                  }

                  // return collect($item);
            }

            return view('superadmin.pages.student.collegestudentschedplot')->with('schedule', $schedule[0]->info);
      }

      public static function collegestudentsched_plot($studid = null, $syid = null, $semid = null, $all = false)
      {

            try {

                  $subjects = DB::table('college_studsched')
                        ->join('college_classsched', function ($join) use ($syid, $semid) {
                              $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                              $join->where('college_classsched.deleted', 0);
                              $join->where('college_classsched.syid', $syid);
                              $join->where('college_classsched.semesterID', $semid);
                        })
                        ->join('college_prospectus', function ($join) {
                              $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                              $join->where('college_prospectus.deleted', 0);
                        })
                        ->join('college_sections', function ($join) {
                              $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                              $join->where('college_sections.deleted', 0);
                        });

                  if (!$all) {
                        $subjects = $subjects->where('college_studsched.schedstatus', '!=', 'DROPPED');
                  }

                  $subjects = $subjects->where('college_studsched.deleted', 0)
                        ->where('college_studsched.studid', $studid)
                        ->select(
                              'lecunits',
                              'labunits',
                              'college_prospectus.subjectID as main_subjid',
                              'college_classsched.*',
                              'schedid',
                              'subjDesc',
                              'subjCode',
                              'sectionDesc',
                              'schedstatus'
                        )
                        ->get();

                  foreach ($subjects as $item) {

                        $item->units = $item->lecunits + $item->labunits;

                        $sched = DB::table('college_scheddetail')
                              ->where('college_scheddetail.headerid', $item->id)
                              ->where('college_scheddetail.deleted', 0)
                              ->leftJoin('rooms', function ($join) {
                                    $join->on('college_scheddetail.roomid', '=', 'rooms.id');
                                    $join->where('rooms.deleted', 0);
                              })
                              ->join('days', function ($join) {
                                    $join->on('college_scheddetail.day', '=', 'days.id');
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

                        $item->teacher = null;
                        $item->teacherid = null;

                        if (isset($item->teacherID)) {
                              $temp_teacher = DB::table('teacher')
                                    ->where('id', $item->teacherID)
                                    ->first();
                              $item->teacher = $temp_teacher->firstname . ' ' . $temp_teacher->middlename . ' ' . $temp_teacher->lastname;
                              $item->teacherid = $temp_teacher->tid;
                        }



                        foreach ($sched as $sched_item) {
                              $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                        }

                        $starting = collect($sched)->groupBy('time');

                        $sched_list = array();
                        $sched_count = 1;

                        foreach ($starting as $sched_item) {

                              $dayString = '';
                              $days = array();
                              $schedstat = '';

                              foreach ($sched_item as $new_item) {
                                    $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                                    $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                                    $dayString .= substr($new_item->description, 0, 3) . ' / ';
                                    $detailid = $new_item->detailid;
                                    $roomname = $new_item->roomname;
                                    $roomid = $new_item->roomid;
                                    $time = $new_item->time;
                                    // $schedstat =  $item->schedstatus;
                                    $schedotherclass = $new_item->schedotherclass;
                                    array_push($days, $new_item->day);
                              }

                              $dayString = substr($dayString, 0, -2);

                              array_push($sched_list, (object) [
                                    'day' => $dayString,
                                    'start' => $start,
                                    'end' => $end,
                                    'roomid',
                                    'detailid' => $detailid,
                                    'roomname' => $roomname,
                                    'roomid' => $roomid,
                                    // 'teacher'=>$teacher,
                                    // 'tid'=>$tid,
                                    // 'teacherid'=>$teacherid,
                                    'sched_count' => $sched_count,
                                    // 'schedstat'=>$schedstat,
                                    'time' => $time,
                                    'days' => $days,
                                    'classification' => $schedotherclass
                              ]);


                              $sched_count += 1;

                        }
                        $item->schedule = $sched_list;


                  }

                  return array(
                        (object) [
                              'status' => 1,
                              'data' => 'Successfull.',
                              'info' => $subjects
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }


      }

      public static function logs($syid = null)
      {
            return DB::table('logs')->where('module', 1)->get();
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

      public static function create_logs($message = null, $id = null)
      {
            DB::table('logs')
                  ->insert([
                        'dataid' => $id,
                        'module' => 3,
                        'message' => $message,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);
      }


      public static function enrollment_report(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $college = $request->get('college');

            $dean_info = DB::table('college_colleges')
                  ->where('college_colleges.id', $college)
                  ->join('teacher', function ($join) use ($semid, $syid) {
                        $join->on('college_colleges.dean', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                  })
                  ->get();

            $dean = null;

            foreach ($dean_info as $dean_item) {
                  $middlename = explode(" ", $dean_item->middlename);
                  $temp_middle = '';
                  if ($middlename != null) {
                        foreach ($middlename as $middlename_item) {
                              if (strlen($middlename_item) > 0) {
                                    $temp_middle .= $middlename_item[0] . '.';
                              }
                        }
                  }
                  $dean = $dean_item->firstname . ' ' . $temp_middle . ' ' . $dean_item->lastname . ' ' . $dean_item->suffix;
            }

            $courses = DB::table('college_courses')
                  ->where('collegeid', $college)
                  ->where('deleted', 0)
                  ->where('cisactive', 1)
                  ->select(
                        'id',
                        'courseabrv',
                        'courseDesc'
                  )
                  ->get();

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load("ENROLLMENT LIST.xlsx");

            $schoolinfo = DB::table('schoolinfo')->first();
            $semester = DB::table('semester')->where('id', $semid)->first();
            $sy = DB::table('sy')->where('id', $syid)->first();


            $sheet_count = 0;

            foreach ($courses as $course_item) {


                  try {

                        $row_count = 13;
                        $student_count = 1;

                        $sheet = $spreadsheet->setActiveSheetIndex($sheet_count);
                        $sheet->setTitle($course_item->courseabrv);
                        $sheet->setCellValue('C7', $course_item->courseDesc);

                        $semesterText = $semester->semester . ', SHOOL YEAR ' . $sy->sydesc . '';

                        $sheet->setCellValue('A1', $schoolinfo->schoolname);
                        $sheet->setCellValue('A1', $schoolinfo->address);
                        $sheet->setCellValue('A5', $semesterText);

                        $course = $course_item->id;

                        $studsched = DB::table('college_studsched')
                              ->join('college_classsched', function ($join) use ($semid, $syid) {
                                    $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                                    $join->where('college_classsched.syid', $syid);
                                    $join->where('college_classsched.semesterID', $semid);
                                    $join->where('college_classsched.deleted', 0);
                              })
                              ->join('college_prospectus', function ($join) use ($semid, $syid) {
                                    $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                    $join->where('college_classsched.deleted', 0);
                              })
                              ->join('college_enrolledstud', function ($join) use ($course, $syid, $semid) {
                                    $join->on('college_studsched.studid', '=', 'college_enrolledstud.studid');
                                    $join->where('college_enrolledstud.deleted', 0);
                                    $join->whereIn('college_enrolledstud.studstatus', [1, 2, 4]);
                                    $join->where('college_enrolledstud.courseid', $course);
                                    $join->where('college_enrolledstud.syid', $syid);
                                    $join->where('college_enrolledstud.semid', $semid);
                              })
                              ->where('college_studsched.deleted', 0)
                              ->where('college_studsched.schedstatus', '!=', 'DROPPED')
                              ->select(
                                    'college_studsched.studid',
                                    'subjDesc',
                                    'subjCode',
                                    'lecunits',
                                    'labunits'
                              )
                              ->get();

                        $students = DB::table('college_enrolledstud')
                              ->join('studinfo', function ($join) {
                                    $join->on('college_enrolledstud.studid', '=', 'studinfo.id');
                                    $join->where('studinfo.deleted', 0);
                              })
                              ->where('college_enrolledstud.courseid', $course)
                              ->where('college_enrolledstud.syid', $syid)
                              ->where('college_enrolledstud.semid', $semid)
                              ->where('college_enrolledstud.deleted', 0)
                              ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                              ->select(
                                    'studid',
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'suffix',
                                    'gender',
                                    'yearLevel'
                              )
                              ->orderBy('yearLevel', 'desc')
                              ->orderBy('lastname')
                              ->get();

                        $male_count = 0;
                        $female_count = 0;

                        $male_count_g1 = 0;
                        $female_count_g1 = 0;
                        $male_count_g2 = 0;
                        $female_count_g2 = 0;
                        $male_count_g3 = 0;
                        $female_count_g3 = 0;
                        $male_count_g4 = 0;
                        $female_count_g4 = 0;

                        $total_units = 0;

                        foreach ($students as $student_item) {

                              $gender = $student_item->gender == 'MALE' ? 'M' : 'F';
                              $male_count += $student_item->gender == 'MALE' ? 1 : 0;
                              $female_count += $student_item->gender == 'MALE' ? 0 : 1;
                              $glevel = null;

                              if ($student_item->yearLevel == 17) {
                                    $glevel = 1;
                                    $male_count_g1 += $student_item->gender == 'MALE' ? 1 : 0;
                                    $female_count_g1 += $student_item->gender == 'MALE' ? 0 : 1;
                              } elseif ($student_item->yearLevel == 18) {
                                    $glevel = 2;
                                    $male_count_g2 += $student_item->gender == 'MALE' ? 1 : 0;
                                    $female_count_g2 += $student_item->gender == 'MALE' ? 0 : 1;
                              } elseif ($student_item->yearLevel == 19) {
                                    $glevel = 3;
                                    $male_count_g3 += $student_item->gender == 'MALE' ? 1 : 0;
                                    $female_count_g3 += $student_item->gender == 'MALE' ? 0 : 1;
                              } elseif ($student_item->yearLevel == 20) {
                                    $glevel = 4;
                                    $male_count_g4 += $student_item->gender == 'MALE' ? 1 : 0;
                                    $female_count_g4 += $student_item->gender == 'MALE' ? 0 : 1;
                              }

                              $sheet->setCellValue('A' . $row_count, $student_count);
                              $sheet->setCellValue('D' . $row_count, $gender);
                              $sheet->setCellValue('E' . $row_count, $glevel);
                              $sheet->setCellValue('C' . $row_count, $student_item->lastname . ', ' . $student_item->firstname . ' ' . $student_item->middlename);

                              $temp_sched = collect($studsched)->where('studid', $student_item->studid)->values();

                              foreach ($temp_sched as $sched_item) {
                                    $sheet->setCellValue('F' . $row_count, $sched_item->subjCode);
                                    $sheet->setCellValue('G' . $row_count, $sched_item->subjDesc);
                                    if (strlen($sched_item->subjDesc) > 39) {
                                          $sheet->getStyle('G' . $row_count)->getAlignment()->setWrapText(true);
                                    }
                                    $sheet->setCellValue('H' . $row_count, $sched_item->lecunits);
                                    $total_units += $sched_item->lecunits;
                                    $row_count += 1;
                              }


                              $sheet->setCellValue('G' . $row_count, 'Total No. Units:');
                              $sheet->setCellValue('H' . $row_count, collect($temp_sched)->sum('lecunits'));
                              $sheet->getStyle('G' . $row_count)->getFont()->setBold(true);
                              $sheet->getStyle('G' . $row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                              $row_count += 1;
                              $student_count += 1;

                        }

                        for ($x = $row_count + 6; $x <= 4999; $x++) {
                              $spreadsheet->getActiveSheet()->getRowDimension($x)->setVisible(false);
                        }


                        $sheet->getPageSetup()->setPrintArea('A1:I5018');


                        $sheet->setCellValue('C5001', $male_count);
                        $sheet->setCellValue('C5002', $female_count);
                        $sheet->setCellValue('C5003', $male_count + $female_count);

                        $sheet->setCellValue('C5004', $female_count_g1);
                        $sheet->setCellValue('C5005', $male_count_g1);
                        $sheet->setCellValue('C5006', $male_count_g1 + $female_count_g1);

                        $sheet->setCellValue('C5007', $female_count_g2);
                        $sheet->setCellValue('C5008', $male_count_g2);
                        $sheet->setCellValue('C5009', $male_count_g2 + $female_count_g2);

                        $sheet->setCellValue('C5010', $female_count_g3);
                        $sheet->setCellValue('C5011', $male_count_g3);
                        $sheet->setCellValue('C5012', $male_count_g3 + $female_count_g3);

                        $sheet->setCellValue('C5013', $female_count_g4);
                        $sheet->setCellValue('C5014', $male_count_g4);
                        $sheet->setCellValue('C5015', $male_count_g4 + $female_count_g4);

                        $sheet->setCellValue('C5016', $male_count + $female_count);
                        $sheet->setCellValue('C5017', $total_units);

                        $sheet->getHeaderFooter()->setOddFooter('&L&9  Prepared by: CHRISTOPHER D. SALICANAN, Encoder ' . ' &C&9 Verified Correct: ' . $dean . ', Dean ' . '&R&9 MERLIE S. SABUELO, Registrar ');
                        $sheet_count += 1;

                  } catch (\Exception $e) {

                  }


            }

            for ($x = 7; $x >= $sheet_count; $x--) {
                  $spreadsheet->removeSheetByIndex($x);

            }


            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="ENROLLMENT.xlsx"');
            $writer->save("php://output");
            exit();

      }

      public static function pre_enrolled(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            if (auth()->user()->type == 16) {

                  $teacher = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();

                  $courses = DB::table('college_courses')
                        ->where('courseChairman', $teacher->id)
                        ->where('deleted', 0)
                        ->select('id', 'courseDesc')
                        ->get();

                  $temp_course = array();

                  foreach ($courses as $item) {
                        array_push($temp_course, $item->id);
                  }

                  if (count($temp_course) == 0) {
                        return array();
                  }

            } else if (auth()->user()->type == 14) {

                  $teacher = DB::table('teacher')
                        ->where('userid', auth()->user()->id)
                        ->first();

                  $courses = DB::table('college_colleges')
                        ->join('college_courses', function ($join) {
                              $join->on('college_colleges.id', '=', 'college_courses.collegeid');
                              $join->where('college_courses.deleted', 0);
                        })
                        ->where('dean', $teacher->id)
                        ->where('college_colleges.deleted', 0)
                        ->select('college_courses.*')
                        ->get();

                  $temp_course = array();

                  foreach ($courses as $item) {
                        array_push($temp_course, $item->id);
                  }

                  if (count($temp_course) == 0) {
                        return array();
                  }

            }

            // $students = DB::table('student_pregistration')
            //                   ->join('studinfo',function($join){
            //                         $join->on('student_pregistration.studid','=','studinfo.id');
            //                         $join->where('studinfo.deleted',0);
            //                   })
            //                   ->join('gradelevel',function($join){
            //                         $join->on('studinfo.levelid','=','gradelevel.id');
            //                         $join->where('gradelevel.deleted',0);
            //                         $join->where('gradelevel.acadprogid',6);
            //                   })
            //                   ->leftJoin('college_enrolledstud',function($join) use($syid,$semid){
            //                         $join->on('student_pregistration.studid','=','college_enrolledstud.studid');
            //                         $join->where('college_enrolledstud.deleted',0);
            //                         $join->where('college_enrolledstud.syid',$syid);
            //                         $join->where('college_enrolledstud.semid',$semid);
            //                   })
            //                   ->leftJoin('college_courses',function($join){
            //                         $join->on('studinfo.courseid','=','college_courses.id');
            //                         $join->where('college_courses.deleted',0);
            //                   })
            //                   ->leftJoin('college_colleges',function($join){
            //                         $join->on('college_courses.collegeid','=','college_colleges.id');
            //                         $join->where('college_courses.deleted',0);
            //                   })
            //                   ->leftJoin('studentstatus',function($join){
            //                         $join->on('studinfo.studstatus','=','studentstatus.id');
            //                   })
            //                   ->where('student_pregistration.deleted',0)
            //                   ->where('student_pregistration.syid',$syid)
            //                   ->where('student_pregistration.semid',$semid);


            // if(auth()->user()->type == 16 || auth()->user()->type == 14){
            //       $students = $students->where(function($query) use($temp_course){
            //             $query->whereIn('studinfo.courseid',$temp_course);
            //             $query->orWhere('studinfo.courseid',0);
            //             $query->orWhere('studinfo.courseid',null);
            //       });
            // }

            // $students = $students->select(
            //                         'studinfo.*',
            //                         'courseDesc',
            //                         'courseabrv',
            //                         'collegeDesc',
            //                         'levelname',
            //                         'description',
            //                         'college_enrolledstud.studstatus as e_status',
            //                         'student_pregistration.createddatetime as preenrollmentdatetime'
            //                   )
            //                   ->get();

            // $temp_students = array();

            // foreach($students as $item){

            //       $middlename = explode(" ",$item->middlename);
            //       $temp_middle = '';
            //       if($middlename != null){
            //             foreach ($middlename as $middlename_item) {
            //                   if(strlen($middlename_item) > 0){
            //                   $temp_middle .= $middlename_item[0].'.';
            //                   } 
            //             }
            //       }
            //       $item->student= $item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
            //       $item->text = $item->sid.' - '.$item->student;

            //       if($item->e_status == null){
            //             array_push($temp_students,$item);
            //       }

            //       $unit_count = DB::table('college_studsched')
            //                         ->join('college_classsched',function($join) use($syid,$semid){
            //                               $join->on('college_studsched.schedid','=','college_classsched.id');
            //                               $join->where('college_classsched.deleted',0);
            //                               $join->where('college_classsched.syid',$syid);
            //                               $join->where('college_classsched.semesterID',$semid);
            //                         })
            //                         ->where('studid',$item->id)
            //                         ->where('college_studsched.deleted',0)
            //                         ->count();

            //       $item->with_sched = false;

            //       if($unit_count > 0){
            //             $item->with_sched = true;
            //       }

            //       $item->preenrollmentdatetime = \Carbon\Carbon::create($item->preenrollmentdatetime)->isoFormat('MMMM DD, YYYY hh:mm a');


            // }

            // return $students = $temp_students;

      }


}
