<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\SPP_SHClassSchedule;
use App\SPP_Blocks;

class SPP_Teacher extends Model
{
    public static function getTeacherAcadProgIfPrincipal($teacherid){

        return DB::table('teacher')
                    ->join('academicprogram','teacher.id','=','academicprogram.principalid')
                    ->where('deleted','0')
                    ->where('teacher.id',$teacherid)
                    ->select('academicprogram.*')
                    ->get();

    }

    public static function getAllTeacher(){

        return DB::table('teacher')
                    ->leftJoin('users',function($join){
                        $join->on('teacher.userid','=','users.id');
                    })
                    ->select('teacher.*')
                    ->whereIn('users.type',['1',null])
                    ->where('deleted','0')
                    ->where('isactive','1')
                    ->get();

    }

    public static function getAllTeacherSHSSubject($teacherid){

        return SPP_SHClassSchedule::getAllTeacherSHSSubject($teacherid);

    }

    public static function getTeacherBlockSubjects($teacherid){

        return SPP_Blocks::getTeacherBlockSubjects($teacherid);

    }

    public static function getAllTeacherSHSSchedule($teacherid){

        return SPP_SHClassSchedule::getAllTeacherSHSSchedule($teacherid);

    }

    public static function getAllTeacherBlockSchedule($teacherid){

        return SPP_Blocks::getAllTeacherBlockSchedule($teacherid);

    }

    public static function teacherStoreAnnouncement(){

    }

    public static function getVacantTeacher($exceptTeacher = null){

        
        $teacher = self::filterTeacherFaculty(null,null,null,'1',null);

        $teachers = $teacher[0]->data;

        foreach($teachers as $key=>$item){

            $teacherisnotavailable=null;
            
            if($item->id!=$exceptTeacher){

                $teacherisnotavailable = Section::getSectionByTeacher($item->id);
            }
            
            if($teacherisnotavailable != null){
                if(count($teacherisnotavailable)>0){
                    unset($teachers[$key]);
                }
            }
            

        }
        
        return $teachers;
        

    }

    public static function filterTeacherFaculty(
                $skip = null, 
                $take = null, 
                $teacherid = null, 
                $type = null, 
                $teachername = null,
                $gettype = 'all'
              
            ){

        $data = array();

    
        $teachers = DB::table('teacher');


        if($teacherid != null){
            
            $teachers->where('teacher.id',$teacherid);
        }

        if($type != null){

            $teachers->where('teacher.usertypeid',$type);

        }

        $teachers->join('users','teacher.userid','=','users.id');
        $teachers->join('usertype','teacher.usertypeid','=','usertype.id');
        
        $teachers->where('teacher.deleted','0');
        $teachers->where('teacher.isactive','1');

        if($teachername != null){

            $teachers->where(function($query) use($teachername){
                $query->where('teacher.firstname','like',$teachername.'%');
                $query->orWhere('teacher.lastname','like',$teachername.'%');
                $query->orWhere('usertype.utype','like',$teachername.'%');
            });
        }
        

        
        
       

        $count = count($teachers->distinct()->get());


        if($take!=null){

            $teachers->take($take);

        }

        if($skip!=null){

            $teachers->skip(($skip-1)*$take);
        }


        $teacher = $teachers->get();
    
        
        array_push($data,(object)['data'=>$teacher,'count'=>$count]);

        return $data;                                            



    }                                                                    



}
      