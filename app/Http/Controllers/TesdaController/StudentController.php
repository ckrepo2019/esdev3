<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function tesda_enrollment()
    {
        return view('tesda.pages.student.enrollment');
    }
    public function tesda_studentInfo(Request $request)
    {
        $id = $request->get('id');
        return view('tesda.pages.student.student_info', compact('id'));
    }

    public static function tesda_allownodp(Request $request){

        try{

              $studid = $request->get('studid');
              $syid =  DB::table('sy')->where('isactive',1)->first()->id;
              $semid =  DB::table('semester')->where('isactive',1)->first()->id;

              $check = DB::table('tesda_student_allownodp')
                          ->where('studid',$studid)
                          ->where('syid',$syid)
                          ->where('semid',$semid)
                          ->where('deleted',0)
                          ->count();

              if($check == 0){
                    DB::table('tesda_student_allownodp')
                          ->insert([
                                'studid'=>$studid,
                                'syid'=>$syid,
                                'semid'=>$semid,
                                'created_by'=>auth()->user()->id,
                                'created_at'=>\Carbon\Carbon::now('Asia/Manila')
                          ]);


                    return array((object)[
                          'status'=>1,
                          'message'=>'No DP Approved!'
                    ]);

              }else{

                    
              $check = DB::table('tesda_student_allownodp')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('deleted',0)
                        ->update([
                            'deleted'=>1
                        ]);

                    return array((object)[
                          'status'=>1,
                          'message'=>'No DP Cancelled!'
                    ]);

              }



        }catch(\Exception $e){

              return array((object)[
                    'status'=>0,
                    'message'=>'Something went wrong! '.$e
              ]);
        }
    }

    public function tesda_enrollment_get(Request $request)
    {
        $syid = DB::table('sy')->where('isactive', 1)->first();
        $course = $request->get('course_id');
        $type = $request->get('course_type');
        $batch = $request->get('batch_id');
        $status = $request->get('status');

        $students = DB::table('studinfo')
            ->where('studinfo.deleted', 0)
            ->where('studinfo.acadprogid', 7)
            ->when($course, function ($query, $course) {
                return $query->where('studinfo.courseid', $course);
            })
            ->when($type, function ($query, $type) {
                return $query->where('tesda_initialcourse.course_type', $type);
            })
            ->when($status, function ($query, $status) {
                return $query->where('studinfo.studstatus', $status);
            })
            ->when($batch, function ($query, $batch) {
                return $query->where('tesda_enrolledstud.batchid', $batch);
            })
            ->leftJoin('tesda_enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'tesda_enrolledstud.studid')
                    ->where('tesda_enrolledstud.deleted', 0);
            })
            ->leftJoin('studentstatus', 'tesda_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->leftJoin('tesda_courses', function ($join) {
                $join->on('tesda_enrolledstud.courseid', '=', 'tesda_courses.id')
                    ->where('tesda_courses.deleted', 0);
            })
            ->leftJoin('tesda_courses as tesda_initialcourse', function ($join) {
                $join->on('studinfo.courseid', '=', 'tesda_initialcourse.id')
                    ->where('tesda_initialcourse.deleted', 0);
            })
            ->leftJoin('tesda_batches', function ($join) {
                $join->on('tesda_enrolledstud.batchid', '=', 'tesda_batches.id')
                    ->where('tesda_batches.deleted', 0);
            })
            ->select(
                'studinfo.*',
                'tesda_enrolledstud.*',
                'tesda_courses.course_name',
                'tesda_courses.course_code',
                'studentstatus.description as studentstatus',
                'studentstatus.id as studentstatusid',
                'studinfo.courseid as initialcourseid',
                'tesda_initialcourse.course_name as initialcoursename',
                'tesda_batches.batch_desc',
                'studinfo.id',
                'studinfo.levelid'
            )
            ->get();


        $chngtrans = DB::table('chrngtrans')
            ->where('syid', $syid->id)
            ->whereIn('studid', collect($students)->pluck('id'))
            ->where('cancelled', 0)
            ->where('progid', 7)
            ->select(
                'transno',
                'syid',
                'studid',
                'semid'
            )
            ->get();

            // return $chngtrans;



        $no_dp = DB::table('tesda_student_allownodp')
            ->whereIn('studid', collect($students)->pluck('id')->toArray()) // Convert to array
            ->where('syid', $syid->id)
            ->where('deleted', 0)
            ->get();

        // dd($no_dp);


        foreach ($students as $item) {
            
            $temp_chrngrans = collect($chngtrans)
                ->where('studid', $item->id)
                ->whereIn('semid', [1, 2])
                ->values();

            // return $temp_chrngrans;

            $check_nodp = collect($no_dp)->where('studid',$item->id)->count();

            if($check_nodp == 0){
                $item->nodp = 0;
            }else{
                $item->nodp = 1;
            }


            if (count($temp_chrngrans) > 0) {
                $item->withpayment = 1;
            } else {
                $item->withpayment = 0;
            }

            if ($item->withpayment == 1 || $item->nodp == 1) {
                $item->can_enroll = 1;
            } else {
                $item->can_enroll = 0;
            }
        }

        // //grade level no dp
        // $no_dp_gradelevel = collect($all_gradelevel)->where('id',$item->levelid)->first();
        // if($no_dp_gradelevel->nodp == 1){
        //     $item->nodp = 1;
        // }




        return response()->json($students);
    }


    public function tesda_studinfo_get(Request $request)
    {

        $studinfo = DB::table('studinfo')
            ->where('studinfo.id', $request->get('id'))
            ->where('studinfo.deleted', 0)
            ->leftJoin('tesda_enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'tesda_enrolledstud.studid')
                    ->where('tesda_enrolledstud.deleted', 0);
            })
            ->leftJoin('studentstatus', function ($join) {
                $join->on('tesda_enrolledstud.studstatus', '=', 'studentstatus.id');
            })
            ->leftJoin('tesda_courses', function ($join) {
                $join->on('tesda_enrolledstud.courseid', '=', 'tesda_courses.id')
                    ->where('tesda_courses.deleted', 0);
            })
            ->leftJoin('tesda_courses as tesda_initialcourse', function ($join) {
                $join->on('studinfo.courseid', '=', 'tesda_initialcourse.id')
                    ->where('tesda_initialcourse.deleted', 0);
            })
            ->leftJoin('tesda_batches', function ($join) {
                $join->on('tesda_enrolledstud.batchid', '=', 'tesda_batches.id')
                    ->where('tesda_batches.deleted', 0);
            })
            ->select(
                'studinfo.*',
                'tesda_enrolledstud.*',
                'tesda_courses.course_name',
                'tesda_courses.course_code',
                'studentstatus.description as studentstatus',
                'studentstatus.id as studentstatusid',
                'studinfo.courseid as initialcourseid',
                'tesda_initialcourse.course_name as initialcoursename',
                'studinfo.id',
                'tesda_enrolledstud.dateenrolled as enrollmentdate',
                // 'tesda_enrolledstud.updateddatetime as updateEnrollmentdate',
                'tesda_batches.batch_desc',
            )
            ->orderBy('tesda_enrolledstud.createddatetime', 'desc')
            ->first();

        $history = DB::table('tesda_enrolledstud')
            ->where('studid', $request->get('id'))
            ->whereIn('studstatus', [1, 2, 4])
            ->where('deleted', 1)
            ->orderBy('createddatetime', 'asc')
            ->first();

        $studinfo->first_enrollment = $history ? $history->dateenrolled : null;



        return response()->json($studinfo);

    }

    public function tesda_enrollment_save(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'studid' => 'required|integer|exists:studinfo,id',
            'courseid' => 'required|integer|exists:tesda_courses,id',
            'batchid' => 'required|integer|exists:tesda_batches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if enrollment already exist
            $existingEnrollment = DB::table('tesda_enrolledstud')
                ->where('studid', $request->studid)
                // ->where('courseid', $request->courseid)
                // ->where('batchid', $request->batchid)
                ->where('studstatus', $request->status)
                ->where('deleted', 0)
                ->first();


            if ($existingEnrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enrollment already exist!',
                    'data' => $existingEnrollment
                ]);

            } else {
                $existingEnrollment = DB::table('tesda_enrolledstud')
                    ->where('studid', $request->studid)
                    // ->where('courseid', $request->courseid)
                    // ->where('batchid', $request->batchid)
                    ->where('studstatus', '!=', $request->status)
                    ->where('deleted', 0)
                    ->orderBy('createddatetime', 'desc')
                    ->first();

                if ($existingEnrollment) {
                    // Delete the old enrollment
                    DB::table('tesda_enrolledstud')
                        ->where('id', $existingEnrollment->id)
                        ->update(['deleted' => 1]);
                }

            }
            // Save the new enrollment
            $enrollment = DB::table('tesda_enrolledstud')
                ->insert([
                    'studid' => $request->studid,
                    'courseid' => $request->courseid,
                    'batchid' => $request->batchid,
                    'studstatus' => $request->status,
                    'dateenrolled' => now(),
                    // 'updateddatetime' => now(),
                    'createdby' => auth()->id(), // Assuming you're using authentication
                ]);

            DB::table('studinfo')
                ->where('id', $request->studid)
                ->update([
                    'courseid' => $request->courseid,
                    'studstatus' => $request->status
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment saved successfully!',
                'data' => $enrollment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the enrollment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function tesda_enrollment_delete(Request $request)
    {
        DB::table('tesda_enrolledstud')
            ->where('studid', $request->id)
            ->update([
                'deleted' => 1
            ]);


        DB::table('studinfo')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1
            ]);


        return response()->json([
            'success' => true,
            'message' => 'Enrollment deleted successfully!'
        ]);
    }

    public function tesda_enrollment_summary(Request $request)
    {
        $course = $request->get('course_id');
        $type = $request->get('course_type');
        $batch = $request->get('batch_id');
        // $status = $request->get('status');

        $students = DB::table('studinfo')
            ->where('studinfo.deleted', 0)
            ->when($course, function ($query, $course) {
                return $query->where('studinfo.courseid', $course);
            })
            ->when($type, function ($query, $type) {
                return $query->where('tesda_initialcourse.course_type', $type);
            })
            ->when($batch, function ($query, $batch) {
                return $query->where('tesda_enrolledstud.batchid', $batch);
            })
            ->leftJoin('tesda_enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'tesda_enrolledstud.studid')
                    ->where('tesda_enrolledstud.deleted', 0);
            })
            ->leftJoin('studentstatus', 'tesda_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->leftJoin('tesda_courses', function ($join) {
                $join->on('tesda_enrolledstud.courseid', '=', 'tesda_courses.id')
                    ->where('tesda_courses.deleted', 0);
            })
            ->leftJoin('tesda_courses as tesda_initialcourse', function ($join) {
                $join->on('studinfo.courseid', '=', 'tesda_initialcourse.id')
                    ->where('tesda_initialcourse.deleted', 0);
            })
            ->leftJoin('tesda_course_type', function ($join) {
                $join->on('tesda_courses.course_type', '=', 'tesda_course_type.id')
                    ->where('tesda_course_type.deleted', 0);
            })
            ->leftJoin('tesda_batches', function ($join) {
                $join->on('tesda_enrolledstud.batchid', '=', 'tesda_batches.id')
                    ->where('tesda_batches.deleted', 0);
            })
            ->select(
                'studinfo.*',
                'tesda_enrolledstud.*',
                'tesda_courses.course_name',
                'tesda_courses.course_type',
                'tesda_courses.course_code',
                'studentstatus.description as studentstatus',
                'studentstatus.id as studentstatusid',
                'studinfo.courseid as initialcourseid',
                'tesda_initialcourse.course_name as initialcoursename',
                'tesda_batches.batch_desc',
                'studinfo.id',
                'tesda_course_type.description'
            )
            ->get()
            ->groupBy('course_name');

        $courseList = [];


        foreach ($students as $key => $stud) {
            $male = 0;
            $female = 0;

            foreach ($stud as $s) {
                if ($s->gender == "Male") {
                    $male++;
                } else {
                    $female++;
                }
            }

            $courseList[$key] = (object) [
                'male' => $male,
                'female' => $female,
                'total' => $male + $female,
                'type' => $stud[0]->description,
                'batch' => $stud[0]->batch_desc
            ];

        }

        $courses = DB::table('tesda_courses')
            ->where('tesda_courses.deleted', 0)
            ->when($course, function ($query, $course) {
                return $query->where('tesda_courses.id', $course);
            })
            ->when($type, function ($query, $type) {
                return $query->where('tesda_courses.course_type', $type);
            })
            ->when($request->get('duration'), function ($query) use ($request) {
                return $query->where('tesda_courses.course_duration', $request->get('duration'));
            })
            ->join('tesda_course_type', function ($join) {
                $join->on('tesda_courses.course_type', '=', 'tesda_course_type.id')
                    ->where('tesda_course_type.deleted', 0);
            })
            ->select(
                'tesda_courses.id',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                'tesda_course_type.description'
            )
            ->get();


        foreach ($courses as $key => $course) {
            $obj = array_key_exists($course->course_name, $courseList) ? $courseList[$course->course_name] : null;

            if ($obj) {
                $course->male = $obj->male;
                $course->female = $obj->female;
                $course->total = $obj->total;
                $course->batch = $obj->batch;
            }
        }


        return $courses;

    }

}