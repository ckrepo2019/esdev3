<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use PDF;


class TesdaStudentInformationController extends Controller
{

    public function generateHash(Request $request)
    {
        // Generate the hash
        $hashedString = \Illuminate\Support\Facades\Hash::make('ckpub@2015');

        // Return the hashed string as JSON
        return response()->json(['hash' => $hashedString]);
    }

    public function AddStudentInformation(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // 'studentIdNo' => 'nullable|unique:studinfo,sid',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'mobileNumber' => 'nullable|numeric',
            'emailAddress' => 'nullable|email',
            'religion' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'fContactNumber' => 'nullable|numeric',
            'fOccupation' => 'nullable|string|max:255',
            'mContactNumber' => 'nullable|numeric',
            'mOccupation' => 'nullable|string|max:255',
            'gContactNumber' => 'nullable|numeric',
            'gOccupation' => 'nullable|string|max:255',
            'lastSchoolAttended' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'age' => 'nullable|numeric',
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'specialization' => 'nullable|exists:tesda_courses,id',  // Assuming specialization is a foreign key
            'placeOfBirth' => 'nullable|string|max:255',
            // Add more validation rules as necessary
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        // Check if student already exist
        $existingStudent = DB::table('studinfo')
            ->where('lastname', $request->lastName)
            ->where('firstname', $request->firstName)
            // ->where('middlename', $request->middleName)
            // ->where('dob', $request->dateOfBirth)
            ->first();

        if ($existingStudent) {
            return response()->json(['success' => false, 'message' => 'Student already exist']);
        }

        // Start a database transaction
        try {
            DB::transaction(function () use ($request) {
                $isfather = 0;
                $ismother = 0;
                $isguardian = 0;
                $incase = $request->incase;
                if($incase == 1){
                    $isfather = 1;
                }else if($incase == 2){
                    $ismother = 1;
                }else if($incase == 3){
                    $isguardian = 1;
                }
                // Insert data into the studinfo table
                $studentData = [
                    'sid' => $request->studentIdNo,
                    'firstname' => $request->firstName,
                    'middlename' => $request->middleName,
                    'lastname' => $request->lastName,
                    'suffix' => $request->suffix,
                    'dob' => $request->dateOfBirth,
                    'gender' => $request->gender,
                    'contactno' => $request->mobileNumber,
                    'semail' => $request->emailAddress,
                    'religionid' => $request->religion,
                    'nationality' => $request->nationality,
                    'fcontactno' => $request->fContactNumber,
                    'foccupation' => $request->fOccupation,
                    'mcontactno' => $request->mContactNumber,
                    'moccupation' => $request->mOccupation,
                    'gcontactno' => $request->gContactNumber,
                    'goccupation' => $request->gOccupation,
                    'lastschoolatt' => $request->lastSchoolAttended,
                    'address' => $request->address,
                    'age' => $request->age,
                    'street' => $request->street,
                    'barangay' => $request->barangay,
                    'city' => $request->city,
                    'province' => $request->province,
                    'courseid' => $request->specialization,
                    'pob' => $request->placeOfBirth,
                    'isfathernum' => $isfather,
                    'ismothernum' => $ismother,
                    'isguardannum' => $isguardian,
                    'acadprogid' => 7,
                    'levelid' => 26
                     
                ];

                $studentId = DB::table('studinfo')->insertGetId($studentData);

                $sid = 'AY7' . date('y') . sprintf('%05d', $studentId);

                DB::table('studinfo')
                    ->where('id', $studentId)
                    ->update(['sid' => $sid]);


                // Insert data into the studinfo_more table
                $moreInfoData = [
                    'studid' => $studentId,
                    'ffname' => $request->fatherFirstName,
                    'fmname' => $request->fatherMiddleName,
                    'flname' => $request->fatherLastName,
                    'fsuffix' => $request->fatherSuffix,
                    'fethnicity' => $request->fEthnicity,
                    'fea' => $request->fHAE,
                    'mfname' => $request->motherFirstName,
                    'mmname' => $request->motherMiddleName,
                    'mlname' => $request->motherLastName,
                    'msuffix' => $request->motherSuffix,
                    'methnicity' => $request->fEthnicity,
                    'mea' => $request->mHAE,
                    'gfname' => $request->guardianFirstName,
                    'gmname' => $request->guardianMiddleName,
                    'glname' => $request->guardianLastName,
                    'gsuffix' => $request->guardianSuffix,
                    'gethnicity' => $request->fEthnicity,
                    'gea' => $request->gHAE,
                    'glits' => $request->lastGradeLevelCompleted,
                    'scn' => $request->schoolContactNo,
                    'cmaosla' => $request->schoolMailingAddress,
                    'psschoolname' => $request->preSchoolName,
                    'pssy' => $request->preSchoolYearGraduated,
                    'psschooltype' => $request->preSchoolType,
                    'gsschoolname' => $request->gradeSchoolName,
                    'gssy' => $request->gradeSchoolYearGraduated,
                    'gsschooltype' => $request->gradeSchoolType,
                    'jhsschoolname' => $request->juniorHighSchoolName,
                    'jhssy' => $request->juniorHighSchoolYearGraduated,
                    'jhsschooltype' => $request->juniorHighSchoolType,
                    'shsschoolname' => $request->seniorHighSchoolName,
                    'shssy' => $request->seniorHighSchoolYearGraduated,
                    'shsschooltype' => $request->seniorHighSchoolType,
                    'height' => $request->Height,
                    'weight' => $request->Weight,
                    'ACM' => $request->otherMed,
                    'Allergy' => $request->anyAllergies,
                    'MedAllergy' => $request->medAllergies,
                    'MedicalHistory' => $request->medHistory,
                    'OtherMedInfo' => $request->otherMedInfo
                ];

                DB::table('studinfo_more')->insert($moreInfoData);

                $sname = $request->get('firstName') . ' ' . $request->get('middleName') . ' ' . $request->get('lastName') . ' ' . $request->get('suffix');

                $studuser = db::table('users')
                    ->insertGetId([
                        'name' => $sname,
                        'email' => 'S' . $sid,
                        'type' => 7,
                        'password' => \Illuminate\Support\Facades\Hash::make('123456')
                    ]);

                $studpword = \App\RegistrarModel::generatepassword($studuser);

                $putUserid = db::table('studinfo')
                    ->where('id', $studentId)
                    ->update([
                        'userid' => $studuser,
                        'updateddatetime' => \App\RegistrarModel::getServerDateTime(),
                    ]);

            });

            return response()->json(['success' => true, 'message' => 'Student added successfully.']);
        } catch (QueryException $e) {
            // Catch any query exceptions (e.g., database errors)
            return response()->json(['success' => false, 'message' => 'An error occurred while adding student. ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // Catch any other exceptions
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. ' . $e->getMessage()]);
        }
    }

    public function GetStudentInformation(Request $request)
    {
        $students = DB::table('studinfo')
            // ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->leftjoin('tesda_enrolledstud', 'studinfo.id', '=', 'tesda_enrolledstud.studid')
            ->join('tesda_courses', 'tesda_enrolledstud.courseid', '=', 'tesda_courses.id')
            ->leftjoin('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            // ->where('studinfo.deleted', 0)
            ->leftjoin('studentstatus', 'tesda_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->where('tesda_enrolledstud.deleted', 0)
            ->select(
                'tesda_enrolledstud.studstatus',
                'studinfo.id as student_id',
                'tesda_courses.id as courseid',
                'tesda_batches.id as batchid',
                'studinfo.firstName',
                'studinfo.middleName',
                'studinfo.lastName',
                'studinfo.suffix',
                'studinfo.gender',
                'tesda_courses.course_name',
                'tesda_batches.batch_desc',
                // 'studentstatus.studstatus',
                'studentstatus.description as studentstatus',
                'studentstatus.id as studentstatusid',
            )
            ->get();

        return response()->json(['success' => true, 'students' => $students]);
    }

    public function GetStudentInformationToEnroll(Request $request, $student_id)
    {
        $student = DB::table('studinfo')
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->leftjoin('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->leftjoin('tesda_enrolledstud', 'studinfo.id', '=', 'tesda_enrolledstud.studid')
            ->leftjoin('studentstatus', 'tesda_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->where('studinfo.id', $student_id)
            ->select(
                'studinfo.id as student_id',
                'tesda_courses.course_name',
                'tesda_batches.batch_desc',
                'studinfo.firstName',
                'studinfo.middleName',
                'studinfo.lastName',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.studstatus',
                // 'studentstatus.id ',
            )

            ->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }
        return response()->json(['success' => true, 'student' => $student]);
    }

    public function StudentInformation(Request $request, $student_id)
    {
        $student = DB::table('studinfo')
            ->leftJoin('studinfo_more', 'studinfo.id', '=', 'studinfo_more.studid')
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->leftJoin('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->leftJoin('tesda_enrolledstud', 'studinfo.id', '=', 'tesda_enrolledstud.studid')
            ->leftJoin('studentstatus', 'tesda_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->leftJoin('religion', 'studinfo.religionid', '=', 'religion.id')
            ->leftJoin('nationality', 'studinfo.nationality', '=', 'nationality.id')
            ->where('studinfo.id', $student_id)
            ->select(
                'studinfo.id as student_id',
                'tesda_courses.course_name',
                'tesda_batches.batch_desc',
                'studinfo.firstName',
                'studinfo.middleName',
                'studinfo.lastName',
                'studinfo.suffix',
                'studinfo.gender',
                'studentstatus.description',
                'studinfo.sid as studentIDNo.',
                'studinfo_more.*',
                'studinfo.*',
                'religion.religionname',
                'nationality.nationality',
                
            )
            ->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }
        return response()->json(['success' => true, 'student' => $student]);
    }

    public function studentCOR($student_id)
    {
        // Fetch the student and related course, batch, and schedule details
        $student = DB::table('studinfo')
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->leftJoin('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            ->where('tesda_course_series.active', 1)
            ->leftJoin('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            ->leftJoin('tesda_course_competency', 'tesda_courses.id', '=', 'tesda_course_competency.course_series_id')
            ->leftJoin('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            ->leftJoin('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            ->where('studinfo.id', $student_id)
            ->select(
                'studinfo.id as student_id',
                'tesda_course_series.id as series_id',
                'tesda_courses.course_name',
                'tesda_courses.id as course_id',
                'tesda_batches.id as batch_id',
                'tesda_batches.batch_desc',
                'rooms.roomname'
            )
            ->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }

            $schedules = DB::table('tesda_batch_schedule')
            ->join('tesda_course_competency', 'tesda_batch_schedule.competency_id', '=', 'tesda_course_competency.id')
            ->where('tesda_batch_schedule.batch_id',$student->batch_id)
            ->where('tesda_batch_schedule.deleted', 0)
            ->select(
                'tesda_batch_schedule.id',
                'tesda_course_competency.competency_desc',
                'tesda_course_competency.id as competency_id',
                'tesda_course_competency.hours',
            )
            ->get();
                    
            foreach($schedules as $sched){
                
                $sched->scheddetails = DB::table('tesda_schedule_details')
                                        ->leftjoin('building', 'tesda_schedule_details.buildingID', '=', 'building.id')
                                        ->leftjoin('rooms', 'tesda_schedule_details.roomID', '=', 'rooms.id')
                                        ->leftjoin('tesda_trainers', 'tesda_schedule_details.trainer_id', '=', 'tesda_trainers.id')
                                        ->leftjoin('users', 'tesda_trainers.user_id', '=', 'users.id')
                                        ->where('tesda_schedule_details.batch_schedule_id', $sched->id)
                                        ->where('tesda_schedule_details.deleted', 0)
                                        ->select(
                                            'tesda_schedule_details.id',
                                            DB::raw("DATE_FORMAT(tesda_schedule_details.stime, '%h:%i %p') AS stime"),
                                            DB::raw("DATE_FORMAT(tesda_schedule_details.etime, '%h:%i %p') AS etime"),
                                            'tesda_schedule_details.date_from',
                                            'tesda_schedule_details.date_to',
                                            'building.description',
                                            'rooms.roomname',
                                            'users.name as trainer_name'
                                        )
                                        ->get();
            
            }
        return response()->json(['success' => true, 'student' => $student, 'schedules' => $schedules]);
    }


    public function tesdaPrintCor($studentId = null)
    {
        // // dd($studentId);
        // $schoolInfo = DB::table('schoolinfo')->select('schoolinfo.*')->first();

        // $student = DB::table('studinfo')
        //     ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
        //     ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
        //     ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
        //     ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
        //     ->where('tesda_course_series.active', 1)
        //     ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
        //     ->leftJoin('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
        //     ->where('studinfo.id', $studentId)
        //     ->where('tesda_schedule_details.deleted', 0)
        //     ->select(
        //         'studinfo.id as student_id',
        //         'studinfo.firstname',
        //         'studinfo.lastname',
        //         'studinfo.middlename',
        //         'studinfo.gender',
        //         'tesda_courses.course_name',
        //         'tesda_courses.course_duration',
        //         // 'tesda_course_competency.competency_desc',
        //         // 'tesda_course_competency.hours',
        //         'tesda_course_series.description as series_desc',
        //         'tesda_course_series.id as series_id',
        //         'tesda_courses.id as course_id',
        //         'tesda_batches.batch_desc',
        //         'rooms.roomname',
        //         'tesda_schedule_details.stime',
        //         'tesda_schedule_details.etime',
        //         'tesda_schedule_details.date_from',
        //         'tesda_schedule_details.date_to'
        //     )

        //     ->groupBy('course_id')
        //     // ->groupBy('tesda_course_competency.id')

        //     ->first();

        // // dd($student);


        // $schedules = DB::table('tesda_course_competency')
        //     ->where('tesda_course_competency.deleted', 0)
        //     ->where('tesda_course_competency.course_series_id', $student->series_id)
        //     ->join('tesda_batch_schedule', function ($query) {
        //         $query->on('tesda_course_competency.id', '=', 'tesda_batch_schedule.batch_id')
        //             ->where('tesda_batch_schedule.deleted', 0);
        //     })
        //     ->join('tesda_schedule_details', function ($query) {
        //         $query->on('tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id');
        //     })
        //     ->join('rooms', function ($query) {
        //         $query->on('tesda_schedule_details.roomID', '=', 'rooms.id');
        //     })
        //     ->select(
        //         'tesda_course_competency.competency_desc',
        //         'tesda_course_competency.hours',
        //         'tesda_schedule_details.date_from',
        //         'tesda_schedule_details.date_to',
        //         'tesda_schedule_details.stime',
        //         'tesda_schedule_details.etime',
        //         'rooms.roomname'
        //     )
        //     ->get()
        //     ->groupBy('competency_desc');
                // return $schedules;

        // return response()->json(['success' => true, 'student' => $student, 'schedules' => $schedules]);

        $schoolinfo = DB::table('schoolinfo')->first();
        $students = DB::table('studinfo')
            ->where('studinfo.deleted', 0)
            ->where(function ($query) use ($studentId) {
                if (isset($studentId)) {
                    $query->where('studinfo.id', $studentId);
                }
            })
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            // ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            // ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            // ->join('tesda_course_competency', 'tesda_course_series.id', '=', 'tesda_course_competency.course_series_id')
            ->where('tesda_course_series.active', 1)
            // ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            // ->join('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            // ->where('studinfo.id', '54654654677')
            // ->where('tesda_schedule_details.deleted', 0)
            ->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'studinfo.suffix',
                'studinfo.sid',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                // 'tesda_course_competency.competency_desc',
                // 'tesda_course_competency.hours',
                // 'tesda_course_series.description as series_desc',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                // 'tesda_batches.batch_desc',
                // 'rooms.roomname',
                // 'tesda_schedule_details.stime',
                // 'tesda_schedule_details.etime',
                // 'tesda_schedule_details.date_from',
                // 'tesda_schedule_details.date_to'
            )
            // ->take(2)
            // ->groupBy('studinfo.id')
            // // ->groupBy('tesda_course_competency.id')

            ->get();

        // dd($students);

        foreach ($students as $student) {
            // $compArr = [];

            // Get competencies for the current student's series_id
            $competencies = DB::table('tesda_course_competency')
                ->where('tesda_course_competency.deleted', 0)
                ->where('course_series_id', $student->series_id)
                ->leftJoin('tesda_batch_schedule', function($query){
                    $query->on('tesda_course_competency.id', '=', 'tesda_batch_schedule.competency_id');
                })
                ->select(
                    'tesda_course_competency.*',
                    'tesda_batch_schedule.id as batch_schedid'
                )
                ->get();
                // ->groupBy('competency_type');

            foreach ($competencies as $comp) {
               $scheddetails = DB::table('tesda_schedule_details')
                ->where('batch_schedule_id', $comp->batch_schedid)
                ->leftJoin('tesda_trainers', function($query){
                    $query->on('tesda_schedule_details.trainer_id', '=', 'tesda_trainers.user_id');
                })
                ->leftJoin('teacher', function($query){
                    $query->on('tesda_trainers.user_id', '=', 'teacher.id');
                })
                ->get();
                
                $comp->scheddetails = $scheddetails;
            }

            $competencies = collect($competencies)->groupBy('competency_type');
            
            $student->competencies = $competencies;
        }



        // dd($students);


        // $pdf = PDF::loadview('tesda/pages/pdf/national_certificate', compact('students', 'schoolinfo'))->setPaper('legal', 'portrait');
        // $pdf->getDomPDF()->set_option("enable_php", true);
        // return $pdf->stream('National Certificate.pdf');

        $pdf = PDF::loadView('tesda.pages.student.tesdaPrintCOR', compact('students', 'schoolinfo'))->setPaper('legal');
        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf->stream('Certificate_of_Registration.pdf');
    }

    // public function tesdaPrintCorAll(Request $request)
    // {
    //     $schoolInfo = DB::table('schoolinfo')->select('schoolinfo.*')->first();

    //     $student = DB::table('studinfo')
    //         ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
    //         ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
    //         ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
    //         ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
    //         ->where('tesda_course_series.active', 1)
    //         ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
    //         ->join('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
    //         ->where('tesda_schedule_details.deleted', 0)
    //         ->select(
    //             'studinfo.id as student_id',
    //             'studinfo.firstname',
    //             'studinfo.lastname',
    //             'studinfo.middlename',
    //             'studinfo.gender',
    //             'tesda_courses.course_name',
    //             'tesda_courses.course_duration',
    //             // 'tesda_course_competency.competency_desc',
    //             // 'tesda_course_competency.hours',
    //             'tesda_course_series.description as series_desc',
    //             'tesda_course_series.id as series_id',
    //             'tesda_courses.id as course_id',
    //             'tesda_batches.batch_desc',
    //             'rooms.roomname',
    //             'tesda_schedule_details.stime',
    //             'tesda_schedule_details.etime',
    //             'tesda_schedule_details.date_from',
    //             'tesda_schedule_details.date_to'
    //         )

    //         ->groupBy('course_id')
    //         // ->groupBy('tesda_course_competency.id')

    //         ->first();

    //     // dd($studentData);


    //     $schedules = DB::table('tesda_course_competency')
    //         ->where('tesda_course_competency.deleted', 0)
    //         ->where('tesda_course_competency.course_series_id', $student->series_id)
    //         ->join('tesda_batch_schedule', function ($query) {
    //             $query->on('tesda_course_competency.id', '=', 'tesda_batch_schedule.batch_id')
    //                 ->where('tesda_batch_schedule.deleted', 0);
    //         })
    //         ->join('tesda_schedule_details', function ($query) {
    //             $query->on('tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id');
    //         })
    //         ->join('rooms', function ($query) {
    //             $query->on('tesda_schedule_details.roomID', '=', 'rooms.id');
    //         })
    //         ->select(
    //             'tesda_course_competency.competency_desc',
    //             'tesda_course_competency.hours',
    //             'tesda_schedule_details.date_from',
    //             'tesda_schedule_details.date_to',
    //             'tesda_schedule_details.stime',
    //             'tesda_schedule_details.etime',
    //             'rooms.roomname'
    //         )
    //         ->get()
    //         ->groupBy('competency_desc');


    //     // return response()->json(['success' => true, 'student' => $student, 'schedules' => $schedules]);

    //     $pdf = PDF::loadView('tesda.pages.student.tesdaPrintCOR', compact('student', 'schoolInfo', 'schedules'))->setPaper('legal');

    //     return $pdf->stream('Certificate_of_Registration.pdf');
    // }

    public function tesdaPrintCorAll(Request $request)
    {
        $schoolInfo = DB::table('schoolinfo')->first();

        $students = DB::table('studinfo')
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->join('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            ->join('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            ->where('tesda_course_series.active', 1)
            ->where('tesda_schedule_details.deleted', 0)
            ->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                'tesda_course_series.description as series_desc',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                'tesda_batches.batch_desc',
                'rooms.roomname',
                'tesda_schedule_details.stime',
                'tesda_schedule_details.etime',
                'tesda_schedule_details.date_from',
                'tesda_schedule_details.date_to'
            )
            ->groupBy('studinfo.id') // Ensure it groups by student
            ->get(); 

        if ($students->isEmpty()) {
            return abort(404, 'No students found');
        }

        $schedules = DB::table('tesda_course_competency')
            ->where('tesda_course_competency.deleted', 0)
            ->join('tesda_batch_schedule', 'tesda_course_competency.id', '=', 'tesda_batch_schedule.batch_id')
            ->where('tesda_batch_schedule.deleted', 0)
            ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            ->join('rooms', 'tesda_schedule_details.roomID', '=', 'rooms.id')
            ->select(
                'tesda_course_competency.competency_desc',
                'tesda_course_competency.hours',
                'tesda_schedule_details.date_from',
                'tesda_schedule_details.date_to',
                'tesda_schedule_details.stime',
                'tesda_schedule_details.etime',
                'rooms.roomname',
                'tesda_course_competency.course_series_id'
            )
            ->get()
            ->groupBy('course_series_id');

        $pdf = PDF::loadView('tesda.pages.student.tesdaPrintAllCOR', compact('students', 'schoolInfo', 'schedules'))
            ->setPaper('legal');

        return $pdf->stream('All_Certificates_of_Registration.pdf');
    }


    public function tesda_student_enrollment(Request $request)
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
                ->where('courseid', $request->courseid)
                ->where('deleted', 0)
                ->first();

            if ($existingEnrollment) {
                // Delete the old enrollment
                DB::table('tesda_enrolledstud')
                    ->where('id', $existingEnrollment->id)
                    ->update(['deleted' => 1]);
            }

            // Save the new enrollment
            $enrollment = DB::table('tesda_enrolledstud')
                ->insert([
                    'studid' => $request->studid,
                    'courseid' => $request->courseid,
                    'batchid' => $request->batchid,
                    'studstatus' => 1,
                    'dateenrolled' => now(),
                    'createdby' => auth()->id(),
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

    public function student_enrollment_history($studentId)
    {
        $history = DB::table('tesda_enrolledstud')
            ->join('studinfo', 'tesda_enrolledstud.studid', '=', 'studinfo.id')
            ->leftjoin('studentstatus', 'tesda_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->leftjoin('tesda_courses', 'tesda_enrolledstud.courseid', '=', 'tesda_courses.id')
            ->leftjoin('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->leftjoin('tesda_course_series', 'tesda_batches.batch_series_id', '=', 'tesda_course_series.id')
            ->where('studinfo.id', $studentId)
            ->select(
                'tesda_enrolledstud.studid',
                'tesda_enrolledstud.studstatus',
                'tesda_enrolledstud.dateenrolled',
                'tesda_enrolledstud.studid',
                'tesda_enrolledstud.dateenrolled',
                'tesda_courses.id as course_id',
                'tesda_courses.course_name',
                'tesda_courses.course_code',
                'tesda_courses.course_duration',
                'tesda_batches.id as batch_id',
                'tesda_batches.batch_desc',
                'tesda_batches.date_from',
                'tesda_batches.date_to'
            )
            ->get();

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    public function delete_student($studentId)
    {
        try {
            $affected = DB::table('studinfo')
                ->where('id', $studentId)
                ->update(['deleted' => 1]);

            if ($affected) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No student found with this ID.',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Delete student error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student. Please try again.',
            ]);
        }
    }

    public function update_student_enrolled(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:studinfo,id',
            'course_id' => 'required|exists:tesda_courses,id',
            'batch_id' => 'nullable|exists:tesda_batches,id',
            'status_id' => 'required|integer'
        ]);

        try {
            $affected = DB::table('tesda_enrolledstud')
                ->where('studid', $request->student_id)
                ->update([
                    'courseid' => $request->course_id,
                    'batchid' => $request->batch_id,
                    'studstatus' => $request->status,
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            if ($affected) {
                return response()->json(['success' => true, 'message' => 'Student enrollment updated successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'No enrollment record found for the given student']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating student enrollment', 'error' => $e->getMessage()]);
        }
    }

    public function signatories(Request $request)
    {
        $signatory = DB::table('tesda_course_signatories')
        // ->join('tesda_courses', 'tesda_course_signatories.course_id', '=', 'tesda_courses.id')
            ->where('tesda_course_signatories.deleted', 0)
            ->where('tesda_course_signatories.course_id', $request->course_id) // Use course_id here
            ->get();

        return response()->json([
            'success' => true,
            'signatory' => $signatory
        ]);
    }

    public function delete_signatory($id)
    {   
        $signatory = DB::table('tesda_course_signatories')
            ->where('id', $id)
            ->update(['deleted' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Signatory deleted successfully'
        ]);
    }

    public function updateOrAddSignatories(Request $request)
    {
    // $signatories = $request->input('signatories');
    $signatories = request('signatories');
    $courseId = $request->input('course_id');

    // Process each signatory
    foreach ($signatories as $signatoryData) {
        $id = $signatoryData[0];
        $name = $signatoryData[1];
        $title = $signatoryData[2];
        $description = $signatoryData[3];

        if ($id == 0) {
            DB::table('tesda_course_signatories')->insert([
                'course_id' => $courseId,
                'signatory_name' => $name,
                'signatory_title' => $title,
                'description' => $description,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
        } else {
            DB::table('tesda_course_signatories')
                ->where('id', $id)
                ->update([
                    'signatory_name' => $name,
                    'signatory_title' => $title,
                    'description' => $description,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        }
    }
    return response()->json(['success' => true, 'message' => 'Signatories updated successfully!']);
    }

    public function tesdaPrintTor($studentId)
    {
        $schoolInfo = DB::table('schoolinfo')->select('schoolinfo.*')->first();

        

        // Fetch the student and related course, batch, and schedule details
        $students = DB::table('studinfo')
            ->join('tesda_enrolledstud', 'studinfo.id', '=', 'tesda_enrolledstud.studid')
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->leftJoin('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            ->where('tesda_course_series.active', 1)
            ->leftJoin('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            ->leftJoin('tesda_course_competency', 'tesda_courses.id', '=', 'tesda_course_competency.course_series_id')
            ->leftJoin('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            ->leftJoin('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            ->where('studinfo.id', $studentId)
            ->select(
                'studinfo.id as student_id',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                'tesda_courses.course_code',
                'tesda_courses.id as course_id',
                'tesda_batches.id as batch_id',
                'tesda_batches.batch_desc',
                'tesda_batches.date_from',
                'tesda_batches.date_to',
                'rooms.roomname',
                'studinfo.*',
                'tesda_enrolledstud.*',
            )
            ->first();

        if (!$students) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }

        $signatory = DB::table('tesda_course_signatories')
        // ->join('tesda_courses', 'tesda_course_signatories.course_id', '=', 'tesda_courses.id')
            ->where('tesda_course_signatories.deleted', 0)
            ->where('tesda_course_signatories.course_id', $students->course_id) // Use course_id here
            ->get();

            $schedules = DB::table('tesda_batch_schedule')
            ->join('tesda_course_competency', 'tesda_batch_schedule.competency_id', '=', 'tesda_course_competency.id')
            ->where('tesda_batch_schedule.batch_id',$students->batch_id)
            ->where('tesda_batch_schedule.deleted', 0)
            ->select(
                'tesda_batch_schedule.id',
                'tesda_course_competency.competency_desc',
                'tesda_course_competency.competency_type',
                'tesda_course_competency.id as competency_id',
                'tesda_course_competency.hours',
            )
            ->get();

            foreach($schedules as $sched){
                
                $sched->scheddetails = DB::table('tesda_schedule_details')
                ->join('tesda_batches', 'tesda_schedule_details.batch_schedule_id', '=', 'tesda_batches.id')
                ->leftjoin('building', 'tesda_schedule_details.buildingID', '=', 'building.id')
                ->leftjoin('rooms', 'tesda_schedule_details.roomID', '=', 'rooms.id')
                ->leftjoin('tesda_trainers', 'tesda_schedule_details.trainer_id', '=', 'tesda_trainers.id')
                ->leftjoin('users', 'tesda_trainers.user_id', '=', 'users.id')
                ->where('tesda_schedule_details.batch_schedule_id', $sched->id)
                ->where('tesda_schedule_details.deleted', 0)
                ->select(
                    'tesda_schedule_details.id',
                    DB::raw("DATE_FORMAT(tesda_schedule_details.stime, '%h:%i %p') AS stime"),
                    DB::raw("DATE_FORMAT(tesda_schedule_details.etime, '%h:%i %p') AS etime"),
                    'tesda_schedule_details.date_from',
                    'tesda_schedule_details.date_to',
                    'building.description',
                    'rooms.roomname',
                    'users.name as trainer_name'
                )
                ->get();
            }

            // return $signatory;
            $pdf = PDF::loadView('tesda.pages.student.tesdaprintTOR', compact('students', 'schoolInfo', 'schedules', 'signatory'))
            ->setPaper('legal');

        return $pdf->stream('All_Transcript_of_Records.pdf');  
          
    }

    public function tesdaPrintTorAll(Request $request)
    {
        $schoolInfo = DB::table('schoolinfo')->select('schoolinfo.*')->first();

        

        // Fetch the student and related course, batch, and schedule details
        $students = DB::table('studinfo')
            ->join('tesda_enrolledstud', 'studinfo.id', '=', 'tesda_enrolledstud.studid')
            ->join('tesda_courses', 'studinfo.courseid', '=', 'tesda_courses.id')
            ->leftJoin('tesda_batches', 'tesda_courses.id', '=', 'tesda_batches.course_id')
            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
            ->where('tesda_course_series.active', 1)
            ->leftJoin('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
            ->leftJoin('tesda_course_competency', 'tesda_courses.id', '=', 'tesda_course_competency.course_series_id')
            ->leftJoin('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
            ->leftJoin('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
            ->where('studinfo.deleted', 0)
            ->select(
                'studinfo.id as student_id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.gender',
                'studinfo.suffix',
                'tesda_course_series.id as series_id',
                'tesda_courses.id as course_id',
                'tesda_courses.course_name',
                'tesda_courses.course_duration',
                'tesda_courses.course_code',
                'tesda_batches.id as batch_id',
                'tesda_batches.batch_desc',
                'tesda_batches.date_from',
                'tesda_batches.date_to',
                'rooms.roomname',
                'studinfo.*',
                'tesda_enrolledstud.*'
            )
            ->groupBy('studinfo.id')
            ->get();
    }
}