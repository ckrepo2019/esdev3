<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Session;
use Auth;
use App\Models\Principal\LoadData;
use App\Models\Principal\SPP_Student;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Teacher;
use App\Models\Principal\SPP_Subject;
use App\Models\Principal\SPP_GradeSetup;
use App\Models\Principal\SPP_Blocks;
use App\Models\Principal\Section;
use App\Models\Principal\SPP_SchoolYear;
use Crypt;

class SPP_Session extends Model
{
   
    public static function principalSession(){
        
        $principalInfo = DB::table('teacher')
                            ->join('academicprogram','teacher.id','=','academicprogram.principalid')
                            ->where('userid',auth()->user()->id)
                            ->select( 'teacher.*',
                                        'academicprogram.progname',
                                        'academicprogram.id as acadid'
                                        )
                            ->get();

        $prinInfo = DB::table('teacher')
                        ->where('userid',auth()->user()->id)
                        ->where('deleted','0')
                        ->first();

        $isSeniorHighPrincipal = false;
        $isJuniorHighPrinicpal = false;
        $isPreSchoolPrinicpal = false;
        $isGradeSchoolPrinicpal = false;

        foreach($principalInfo as $item){

        if($item->acadid=='5'){

            $isSeniorHighPrincipal = true;

        }
        else if($item->acadid=='2'){

            $isPreSchoolPrinicpal = true;

        }
        else if($item->acadid=='3'){

            $isGradeSchoolPrinicpal = true;

        }
        else if($item->acadid=='4'){

            $isJuniorHighPrinicpal = true;

        }


        }

        Session::put('isSeniorHighPrincipal',$isSeniorHighPrincipal);
        Session::put('isPreSchoolPrinicpal', $isPreSchoolPrinicpal);
        Session::put('isGradeSchoolPrinicpal', $isGradeSchoolPrinicpal);
        Session::put('isJuniorHighPrinicpal', $isJuniorHighPrinicpal);

        Session::put('principalInfo', $principalInfo);
        Session::put('prinInfo', $prinInfo);


        $teacherCount = SPP_Teacher::filterTeacherFaculty(null,1,null,1,null)[0]->count;
        Session::put('teachercount', $teacherCount);

        if($isSeniorHighPrincipal){

            $shStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,5,null,null,null,Session::get('schoolYear')->id)[0]->count;
            Session::put('shstudentcount', $shStudents);

            $shSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(5))[0]->count;
            Session::put('shsubjectcount', $shSubjects);

            $shGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,5)[0]->count;
            
            Session::put('shgradesetup', $shGradeSetup);

        }
        if($isJuniorHighPrinicpal){

            $jhStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,4,null,null,null,Session::get('schoolYear')->id)[0]->count;
            Session::put('jhstudentcount', $jhStudents);

            $jsSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(4))[0]->count;
            Session::put('jhsubjectcount', $jsSubjects);

            $jhGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,4)[0]->count;
            Session::put('jhgradesetup', $jhGradeSetup);


        }
        if($isPreSchoolPrinicpal){

            $psStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,2,null,null,null,Session::get('schoolYear')->id)[0]->count;
            Session::put('psstudentcount', $psStudents);

            $psSubjects = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(2))[0]->count;
            Session::put('pssubjectcount', $psSubjects);

            $psGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,2)[0]->count;
            Session::put('psgradesetup', $psGradeSetup);

        }
        if($isGradeSchoolPrinicpal){

            $gsStudents = SPP_EnrolledStudent::getStudent(null,1,null,null,3,null,null,null,Session::get('schoolYear')->id)[0]->count;
            Session::put('gsstudentcount', $gsStudents);

            $gsSubject = SPP_Subject::getAllSubject(null,1,null,null,Crypt::encrypt(3))[0]->count;
            Session::put('gssubjectcount', $gsSubject);

            $gsGradeSetup = SPP_GradeSetup::getAllGradeSetup(null,10,null,null,3)[0]->count;
            Session::put('gsgradesetup', $gsGradeSetup);

        }

        $sections = Section::getSections(null,1,null,null,null, $prinInfo->id)[0]->count;
        Session::put('sectionCount', $sections);

        $blocks = SPP_Blocks::getBlock(null,6,null,null)[0]->count;
        Session::put('blockCount', $blocks);

    }


}
