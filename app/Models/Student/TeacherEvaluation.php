<?php

namespace App\Models\Student;
use DB;


use Illuminate\Database\Eloquent\Model;

class TeacherEvaluation extends Model
{

      public static function getTeacherEvaluationSetup(){

            $evautionSetup = DB::table('grading_system')
                                    ->where('specification',3)
                                    ->where('isactive',1)
                                    ->where('deleted',0)
                                    ->get();
                        
            if(count($evautionSetup) > 1){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Multiple evaluation setup"
                  ]);

                  return $data;

            }else if(count($evautionSetup) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"No evaluation setup"
                  ]);

                  return $data;


            }else {
                  
                  $data = array((object)[
                        'status'=>1,
                        'data'=>$evautionSetup
                  ]);

                  return $data;

            }
            

      }

      public static function evaluate_teacher_evaluation_setup($id = null){

            $setupDetail = DB::table('grading_system_detail')
                                    ->where('headerid',$id)
                                    ->orderBy('sort')
                                    ->where('deleted',0)
                                    ->get();

              
            if(count($setupDetail) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Teacher evaluation does not contain any detail."
                  ]);

                  return $data;

            }
            else {
                  
                  $data = array((object)[
                        'status'=>1,
                        'data'=>$setupDetail
                  ]);

                  return $data;

            }

      }


      public static function evaluate_teacher_evaluation_rating_value($id = null){

            $setupDetail = DB::table('grading_system_ratingvalue')
                                    ->where('gsid',$id)
                                    ->where('deleted',0)
                                    ->orderBy('sort')
                                    ->get();

              
            if(count($setupDetail) == 0){

                  $data = array((object)[
                        'status'=>0,
                        'data'=>"Teacher evaluation does not contain any rating value."
                  ]);

                  return $data;

            }
            else {
                  
                  $data = array((object)[
                        'status'=>1,
                        'data'=>$setupDetail
                  ]);

                  return $data;

            }

      }


     

      
}
