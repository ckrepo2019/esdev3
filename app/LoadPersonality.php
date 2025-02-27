<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class LoadPersonality extends Model
{
    public static function teacherQuery(){

        return DB::table('teacher')
                ->select('teacher.*')
                ->join('users',function($join){
                    $join->on('teacher.userid','=','users.id');
                    $join->where('type','1');
                })
                ->orderBy('teacher.firstname');
    }

    public static function studentQuery(){

        $students = DB::table('academicprogram')
                        ->where('academicprogram.id',Session::get('principalInfo')[0]->id)
                        ->join('gradelevel',function($join){
                            $join->on('academicprogram.id','=','gradelevel.acadprogid');
                            $join->where('deleted','0');
                        })
                        ->join('enrolledstud',function($join){
                            $join->on('gradelevel.id','=','enrolledstud.levelid');
                            $join->whereIn('enrolledstud.studstatus',['1','2','4']);
                        })
                        ->join('sy',function($join){
                            $join->on('enrolledstud.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('sections',function($join){
                            $join->on('enrolledstud.sectionid','=','sections.id');
                            $join->where('sections.deleted','0');
                            })
                        ->join('studinfo',function($join){
                            $join->on('enrolledstud.studid','=','studinfo.id');
                        })
                        ->orderBy('studinfo.lastname');
                        
        return $students;
    }



    public static function loadTeachers(){

        $data = array();

        $teacherCount = count(self::teacherQuery()->get())/6;

        if(round($teacherCount) < $teacherCount){
            $teacherCount = round($teacherCount)+1;
        }
        else{
            $teacherCount = round($teacherCount);
        }
        
        array_push($data, (object) array(
            'teachers'=>self::teacherQuery()->take(6)->get(),
            'teachersCount'=> $teacherCount
            ));

        

        return $data;

    }

    public static function loadStudents(){

        $data = array();

        $studentCount = self::studentQuery()->count()/6;

        if(round($studentCount) < $studentCount){
            $studentCount = round($studentCount)+1;
        }
        else{
            $studentCount = round($studentCount);
        }
        
        array_push($data, (object) array(
            'students'=>self::studentQuery()->take(6)->get(),
            'studentcount'=> $studentCount
            ));

        return $data;
    }

    public static function filterTeacher($filtername,$pagenum){

        $dataString = '';

        $teachers =  self::teacherQuery()
                    ->where('teacher.firstname','like',$filtername.'%')
                    ->orWhere('teacher.lastname','like',$filtername.'%')
                    ->skip(($pagenum-1)*6)
                    ->take(6)
                    ->get();

        $teachersCount =  count(self::teacherQuery()
                    ->where('teacher.firstname','like',$filtername.'%')
                    ->orWhere('teacher.lastname','like',$filtername.'%')
                    ->get())/6;

        if(round($teachersCount) < $teachersCount){
            $teachersCount = round($teachersCount)+1;
        }
        else{
            $teachersCount = round($teachersCount);
        }

        if(count($teachers)>0){
            $dataString .='<div class="row d-flex align-items-stretch p-4">';
            foreach($teachers as $teacher){
                $dataString .= '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                                    <div class="card bg-light w-100">
                                        <div class="card-header text-muted border-bottom-0">
                                        Math Teacher
                                        </div>
                                        <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-7">
                                            <h2 class="lead"><b> '.strtoupper(explode(' ',trim($teacher->lastname))[0]).'<span class="h6"><br>'.strtoupper($teacher->firstname).'</span></b></h2>
                                            <p class="text-muted text-sm"><b>Teacher ID</b><br>'.$teacher->tid.'</p>
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="small"><span class="fa-li"></span></li>
                                            </ul>
                                            </div>
                                            <div class="col-5 text-center">
                                            <img src="../../dist/img/user2-160x160.jpg" alt="" class="img-circle img-fluid">
                                            </div>
                                        </div>
                                        </div>
                                        <a href="/principalPortalTeacherProfile/'.$teacher->id.'" class="card-footer bg-info text-center"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>';
                }
                $dataString .=' </div>';

                $dataString .='<div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>';
                    for($x=1 ; $x <=$teachersCount;$x++){
                        if($x==$pagenum){
                            $dataString .='<li class="page-item"><a  id="'.$x.'" class="page-link page-link-active" href="#">'.$x.'</a></li>'; 
                        }
                        else{
                            $dataString .='<li class="page-item"><a class="page-link" id="'.$x.'" href="#">'.$x.'</a></li>'; 
                        }
                    }
                $dataString .='<li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>';
            
            }
            else{
                $dataString .='<div class="row d-flex align-items-stretch p-4">';
                $dataString .= '<a class="w-100 text-center">No Teacher Found</a>';
                $dataString .='<div">';
            }

        return  $dataString;
    }

    public static function filterstudent($filtername,$pagenum){

        $dataString = '';

        $students =  self::studentQuery()
                    ->where('studinfo.firstname','like',$filtername.'%')
                    ->orWhere('studinfo.lastname','like',$filtername.'%')
                    ->skip(($pagenum-1)*6)
                    ->take(6)
                    ->get();


        $studentCount = count(self::studentQuery()
                        ->where('studinfo.firstname','like',$filtername.'%')
                        ->orWhere('studinfo.lastname','like',$filtername.'%')
                        ->get())/6;


        if(round($studentCount) < $studentCount){
            $studentCount = round($studentCount)+1;
        }
        else{
            $studentCount = round($studentCount);
        }


        if(count($students)>0){
             $dataString .='<div class="row d-flex align-items-stretch p-4">';
            foreach($students as $student){
                $dataString .= '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                    <div class="card bg-light">
                        <div class="card-header text-muted border-bottom-0">
                
                        </div>
                        <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-7">
                            <h2 class="lead"><b> '.strtoupper(explode(' ',trim($student->lastname))[0]).'<span class="h6"><br>'.strtoupper($student->firstname).'</span></b></h2>
                            <p class="text-muted text-sm"><b>License No. </b><br>'.$student->sid.'</p>
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>'.$student->contactno.'</li>
                            </ul>
                            </div>
                            <div class="col-5 text-center">
                            <img src="../../dist/img/user2-160x160.jpg" alt="" class="img-circle img-fluid">
                            </div>
                        </div>
                        </div>
                        <a href="/principalPortalStudentProfile/'.$student->id.'" class="card-footer bg-info text-center"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>';
            }
            $dataString .=' </div>';

            $dataString .='<div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">«</a></li>';
                for($x=1 ; $x <=$studentCount;$x++){
                    if($x==$pagenum){
                        $dataString .='<li class="page-item"><a  id="'.$x.'" class="page-link page-link-active" href="#">'.$x.'</a></li>'; 
                    }
                    else{
                        $dataString .='<li class="page-item"><a class="page-link" id="'.$x.'" href="#">'.$x.'</a></li>'; 
                    }
                }
            $dataString .='<li class="page-item"><a class="page-link" href="#">»</a></li>
                </ul>
            </div>';
        }
        
        else{
            $dataString .='<div class="row d-flex align-items-stretch p-4">';
            $dataString .= '<a class="w-100 text-center">No Student Found</a>';
            $dataString .='<div">';
        }

        return  $dataString;

    }

    public static function loadTeacherProfile($teacherid){

        return DB::table('teacher')
                    ->leftJoin('sections',function($join){
                        $join->on('teacher.id','=','sections.teacherid');
                        $join->where('sections.deleted','0');
                    })
                    ->leftJoin('gradelevel',function($join){
                        $join->on('sections.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->select(
                            'gradelevel.levelname',
                            'teacher.firstname',
                            'teacher.lastname',
                            'sections.sectionname',
                            'teacher.id as id')
                    ->where('teacher.id',$teacherid)
                    ->where('teacher.deleted','0')
                    ->where('teacher.isactive','1')
                    ->first();
    }



  
}
