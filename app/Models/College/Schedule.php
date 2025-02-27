<?php

namespace App\Models\College;
use DB;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public static function getschedcollege(
          $studid = null,
          $day = null
      )
    {
            $schedules = DB::table('college_studsched')
                        ->join('college_classsched',function($join){
                              $join->on('college_studsched.schedid','=','college_classsched.id');
                        })
                        ->join('college_sections',function($join){
                              $join->on('college_classsched.sectionID','=','college_sections.id');
                        })
                        ->join('college_scheddetail',function($join){
                              $join->on('college_classsched.id','=','college_scheddetail.headerid');
                              $join->where('college_scheddetail.deleted','0');
                        })
                        ->join('college_prospectus',function($join){
                              $join->on('college_classsched.subjectID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted','0');
                        })
                        ->leftJoin('teacher',function($join){
                              $join->on('college_classsched.teacherID','=','teacher.id');
                              $join->where('teacher.deleted','0');
                        })
                        ->leftJoin('rooms',function($join){
                              $join->on('college_scheddetail.roomID','=','rooms.id');
                              $join->where('rooms.deleted','0');
                        })
                        ->leftJoin('days',function($join){
                              $join->on('college_scheddetail.day','=','days.id');
                        })
                        ->join('sy',function($join){
                              $join->on('college_classsched.syID','=','sy.id');
                              $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                              $join->on('college_classsched.semesterID','=','semester.id');
                              $join->where('semester.isactive','1');
                        })
                        
                        ->where('college_studsched.deleted',0)
                        ->select(
                              'days.description',
                              'college_classsched.id',
                              'college_scheddetail.id as schedid',
                              'rooms.roomname',
                              'college_scheddetail.etime',
                              'college_scheddetail.stime',
                              'teacher.firstname',
                              'teacher.lastname',
                              'college_classsched.subjectUnit',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode',
                              'college_prospectus.lecunits',
                              'college_prospectus.labunits',
                              'college_scheddetail.scheddetialclass',
                              'teacher.firstname',
                              'teacher.lastname'
                        );

            if($studid != null){

                  $schedules->where('college_studsched.studid',$studid);

            }
            if($day != null){

                  $schedules->where('days.id',$day);

            }

            return $schedules->get();
      
                        
    }

   

}
