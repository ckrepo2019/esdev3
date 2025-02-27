<?php

namespace App\Http\Controllers\StudentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Student\StudentProccess;

class StudentInformation extends Controller
{

    //super admin

    public static function student_profile(Request $request){

        $studid = $request->get('studid');

        $student_profile = Student::student_profile($studid);
        $student_enrollment = Student::student_enrollment($studid);

        return view('superadmin.pages.studentinformation.student_info')
                ->with('student_profile', $student_profile)
                ->with('student_enrollment', $student_enrollment);
        
    }

    

    public static function upload_student_id_picture(Request $request){

        $studid = $request->get('studid');
        $sid = $request->get('sid');
        return StudentProccess::upload_student_pic($studid,$sid,$request);
    }


    
}
