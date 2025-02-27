<?php

namespace App\Models\Grading;
use DB;
use Illuminate\Database\Eloquent\Model;

class FinalGrading extends Model
{

     public static function get_finalGrade($syid = null, $semid = null, $gradelevel = null, $sectionid = null, $subjid = null, $quarter = null){
           
      if($syid == null){
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
        }else{
            $semid = $semid;
        }

        if( $semid== null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }else{
            $syid = $syid;
        }




        $check_if_header_exist = DB::table('grades')
                            ->where('subjid',$subjid)
                            ->where('syid',$syid)
                            ->where('levelid',$gradelevel)
                            ->where('sectionid',$sectionid)
                            ->where('quarter',$quarter)
                            ->where('deleted',0)
                            ->select('status','id','submitted')
                            ->first();

        if(isset($check_if_header_exist->status)){

            if($gradelevel == 14 || $gradelevel == 15){

                $enrolled_students = DB::table('sh_enrolledstud')
                                        ->join('studinfo',function($join){
                                            $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                        })
                                        ->where('sh_enrolledstud.deleted',0)
                                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                        ->where('sh_enrolledstud.levelid',$gradelevel)
                                        ->where('sh_enrolledstud.sectionid',$sectionid)
                                        ->where('sh_enrolledstud.syid',$syid)
                                        ->where('sh_enrolledstud.semid',$semid)
                                        ->select('studid','gender','firstname','lastname')
                                        ->get();


            }else{

                $enrolled_students = DB::table('enrolledstud')
                                        ->join('studinfo',function($join){
                                            $join->on('enrolledstud.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                        })
                                        ->where('enrolledstud.deleted',0)
                                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                                        ->where('enrolledstud.levelid',$gradelevel)
                                        ->where('enrolledstud.sectionid',$sectionid)
                                        ->where('enrolledstud.syid',$syid)
                                        ->select('studid','gender','firstname','lastname')
                                        ->orderby('gender','desc')
                                        ->orderby('lastname')
                                        ->get();

            }
            
            foreach($enrolled_students as $item){

                $check_student_grades_exist = DB::table('gradesdetail')
                                                ->where('headerid',$check_if_header_exist->id)
                                                ->where('studid',$item->studid)
                                                ->select('id','qg')
                                                ->first();

                if(isset($check_student_grades_exist->qg)){

                    $item->qg = $check_student_grades_exist->qg;
                    $item->gdid = $check_student_grades_exist->id;

                }else{

                    DB::table('gradesdetail')
                        ->insert([
                            'headerid'=>$check_if_header_exist->id,
                            'studid'=>$item->studid,
                            'name'=>$item->lastname.', '.$item->firstname,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                    
                }

            }

            return $enrolled_students;

            }
            else{
                  
            }

       

      }

      public static function save_final_grade($gdid = null,  $studid = null,  $fg = null){
      
            DB::table('gradesdetail')
                  ->where('id',$gdid)
                  ->where('studid',$studid)
                  ->update([
                        'qg'=>$fg,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

      }

      public static function check_grade_status($syid = null, $semid = null, $gradelevel = null, $sectionid = null, $subjid = null, $quarter = null){
            
            $check_if_header_exist = DB::table('grades')
                                    ->where('subjid',$subjid)
                                    ->where('syid',$syid)
                                    ->where('levelid',$gradelevel)
                                    ->where('sectionid',$sectionid)
                                    ->where('quarter',$quarter)
                                    ->where('deleted',0)
                                    ->select('status','id','submitted','grade_type','quarter')
                                    ->first();

            if(!isset($check_if_header_exist->id)){
                  return (object)['id'=>0];
            }

            return $check_if_header_exist;
      }

      public static function submit_final_grade($id = null){
         
           DB::table('grades')
                  ->take(1)
                  ->where('id',$id)
                  ->update([
                        'submitted'=>1,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'date_submitted'=>\Carbon\Carbon::now('Asia/Manila'),
                  ]);

            DB::table('gradesdetail')
                        ->where('headerid',$id)
                        ->update([
                              'gdstatus'=>1,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);

            return array((object)[
                  'status'=>1,
                  'data'=>'Submitted Successfully'
            ]);
            
      }

      public static function update_grade_type($id = null, $type = null){

            $check_if_header_exist = DB::table('grades')
                                          ->take(1)
                                          ->where('id',$id)
                                          ->update([
                                                'updatedby'=>auth()->user()->id,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                'grade_type'=>$type
                                          ]);
            
      }





      
}
