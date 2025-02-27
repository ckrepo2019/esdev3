<?php

namespace App\Http\Controllers\CTController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class tchrCtTeacherSchedulingController extends Controller
{
    public function tchrCollegeViewSchedules(){

        $userid = auth()->user()->id;
        $teacher = DB::table('teacher')
            ->select('id', 
            DB::raw("CONCAT(lastname,', ',firstname,' ',middlename,'.') as teachername")
            )
            ->where('userid', $userid)
            ->first();

        // return $teacher;
        // return collect($teacher);
        return view('ctportal.tchrcollegescheduling.index')
            ->with('teacher', $teacher);
    }
}
