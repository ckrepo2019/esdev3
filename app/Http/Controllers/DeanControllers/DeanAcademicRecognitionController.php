<?php

namespace App\Http\Controllers\DeanControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Controllers\CollegeSchedConflicts\SchedConflict;

class DeanAcademicRecognitionController extends Controller
{

    public static function college_studentListAcademicRecognition($syid = '', $semid = '', $college = '', $course = '', $academic = '')
    {

            $students = DB::table('studinfo')
            ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
            ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
            ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
            ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->join('college_sections', 'college_loadsubject.sectionID', '=', 'college_sections.id')
            ->join('college_classsched', 'college_loadsubject.subjectID', '=', 'college_classsched.subjectID')
            ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
            ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
            // ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
            ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
            // ->join('epermitdetails', 'college_enrolledstud.studid', '=', 'epermitdetails.studid')
            ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
            ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
            ->where('college_loadsubject.syid', $syid)
            ->where('college_loadsubject.semid', $semid)
            ->where('college_colleges.id', $college)
            ->where('college_courses.id', $course)
            ->where('college_enrolledstud.yearLevel', $academic)
            // ->where('college_loadsubject.schedid', $schedid)
            ->where('college_prospectus.deleted', 0)
            ->where('college_loadsubject.deleted', 0)
            ->where('college_enrolledstud.deleted', 0)
            ->select(
                DB::raw("CONCAT(studinfo.lastname, ', ', studinfo.firstname, ' ', IFNULL(studinfo.middlename, ''), ' ', IFNULL(studinfo.suffix, '')) AS student_name"),
                'studinfo.id',
                'studinfo.firstname',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.street',
                'studinfo.barangay',
                'studinfo.city',
                'studinfo.province',
                'studinfo.contactno',
                'studinfo.sid',
                'studinfo.semail',
                'college_sections.id AS sectionId',
                'college_courses.courseabrv',
                'college_courses.courseDesc',
                'college_courses.id AS courseid',
                'gradelevel.levelname',
                'studentstatus.description AS student_status',
                'college_enrolledstud.studstatus',
                'college_enrolledstud.yearLevel AS levelid',
                'college_subjects.subjCode',
                'college_subjects.subjDesc',
                'college_prospectus.subjectID',
            )
            ->groupBy(
                'college_loadsubject.studid'
            )
            ->get();





            $studentsgrades = DB::table('college_studentprospectus')

                    ->join('studinfo', 'college_studentprospectus.studid', '=', 'studinfo.sid')
                    ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                    ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
                    ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
                    ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
                    ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
                    // ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
                    ->where('college_loadsubject.syid', $syid)
                    ->where('college_loadsubject.semid', $semid)
                    // ->where('college_loadsubject.sectionID', $section)
                    ->where('college_courses.id', $course)
                    ->where('college_enrolledstud.yearLevel', $academic)
                    // ->where('college_prospectus.deleted', 0)
                    ->where('college_loadsubject.deleted', 0)
                    ->where('college_enrolledstud.deleted', 0)
                    ->select(               
                        'studinfo.id',   
                        DB::raw('COUNT(DISTINCT college_studentprospectus.prospectusID) as loadsubject_count_has_grades'),
                        // DB::raw('AVG(college_studentprospectus.fg) as avg_fg'),
                        DB::raw('SUM(college_studentprospectus.fg) / COUNT(DISTINCT college_loadsubject.id) as avg_fg'),
                        
                        'college_studentprospectus.prospectusID',  
                        DB::raw('COUNT(DISTINCT college_loadsubject.id) as loadsubject_count')
                    )  
                    ->groupBy(
                        'college_studentprospectus.studid'
                    )       
                    ->get();

        return response()->json([
            'students' => $students,
            'studentsgrades' => $studentsgrades
            // 'groupedSubjects' => $groupedSubjects
        ]);
    }


    public static function create_recognitionType(Request $request)
    {
        // Retrieve and validate inputs
        $recognitionTypeDesc = $request->input('recognitionTypeDesc');
        $minGradeRequirement = $request->input('minGradeRequirement');
        $setActive = $request->input('setActive');

        // Check if recognitionTypeDesc is provided
        if (empty($recognitionTypeDesc)) {
            return response()->json([
                ['status' => 2, 'message' => 'Recognition Type Description is required']
            ]);
        }

            DB::table('academic_recognition')->insert([
            'academic_recognition_desc' => $recognitionTypeDesc,
            'minimum_grade' => $minGradeRequirement,
            'isactive' => $setActive,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
        ]);

        // Insert grade point data into the database
      

        return response()->json([
            ['status' => 1, 'message' => 'Academic Recognition Type Created Successfully']
        ]);
    }

    public static function update_recognitionType(Request $request)
    {
        // Retrieve and validate inputs
        $recognitionTypeId = $request->input('recognitionTypeId');
        $recognitionTypeDesc = $request->input('recognitionTypeDesc');
        $minGradeRequirement = $request->input('minGradeRequirement');
        $setActive = $request->input('setActive');

        // Check if recognitionTypeDesc is provided
        if (empty($recognitionTypeDesc)) {
            return response()->json([
                ['status' => 2, 'message' => 'Recognition Type Description is required']
            ]);
        }

            DB::table('academic_recognition')->where('id', $recognitionTypeId)->update([
            'academic_recognition_desc' => $recognitionTypeDesc,
            'minimum_grade' => $minGradeRequirement,
            'isactive' => $setActive,
            'updatedby' => auth()->user()->id,
            'updateddatetime' => now(),
        ]);

        // Insert grade point data into the database
      

        return response()->json([
            ['status' => 1, 'message' => 'Academic Recognition Type Updated Successfully']
        ]);
    }

    public static function delete_recognitionType(Request $request)
    {
        // Retrieve and validate inputs
        $recognitionTypeId = $request->input('recognitionTypeId');

            DB::table('academic_recognition')->where('id', $recognitionTypeId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Academic Recognition Type Deleted Successfully']
        ]);
    }


    public function fetch_academicrecognition()
    {
        $academicRecognition = DB::table('academic_recognition')
            ->where('deleted', 0)
            ->select(
                'id',
                'academic_recognition_desc',
                'minimum_grade',
                'isactive',
            )
            ->get();
    
        return response()->json($academicRecognition);
    }

    public static function fetch_select_academicrecognition(Request $request){

        $academicRecognitionID = $request->get('academic_recognition_id');


        $academicRecognition = DB::table('academic_recognition')
        ->where('deleted', 0)
        ->where('id',$academicRecognitionID)
        ->select(
            'id',
            'academic_recognition_desc',
            'minimum_grade',
            'isactive',
        )
        ->get();

        return response()->json([
            'academicRecognition' => $academicRecognition
           
        ]);
    }




            // $studentsgrades = DB::table('college_studentprospectus')

            // ->join('studinfo', 'college_studentprospectus.studid', '=', 'studinfo.sid')
            // ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
            // ->join('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
            // ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            // ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
            // ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
            // ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
            // // ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
            // ->where('college_loadsubject.syid', $syid)
            // ->where('college_loadsubject.semid', $semid)
            // // ->where('college_loadsubject.sectionID', $section)
            // ->where('college_courses.id', $course)
            // ->where('college_enrolledstud.yearLevel', $academic)
            // // ->where('college_prospectus.deleted', 0)
            // ->where('college_loadsubject.deleted', 0)
            // ->where('college_enrolledstud.deleted', 0)
            // ->select(               
            //     'studinfo.id',   
            //     'college_studentprospectus.finalstatus', 
            //     // 'college_studentprospectus.fg',
            //     DB::raw('AVG(college_studentprospectus.fg) as avg_fg'),
            //     'college_studentprospectus.prospectusID',  
            //     'college_subjects.subjCode',
            //     'college_subjects.subjDesc',  

                        
            // )         
            // ->get();

    
}