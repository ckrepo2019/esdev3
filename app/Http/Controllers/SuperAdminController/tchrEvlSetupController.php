<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class tchrEvlSetupController extends \App\Http\Controllers\Controller
{

      public static function tchrEvlgnrteSetup(Request $request){

            try{

                  $data = self::gettchrEvlDefSetup();

                  $headers = $data[0]->header;
                  $details = $data[0]->detail;
                  $rv = $data[0]->rv;

                  foreach($headers as $header){

                        $check = DB::table('grading_system')
                                    ->where('description',$header->description)
                                    ->where('type',$header->type)
                                    ->where('specification',$header->specification)
                                    ->where('isactive',$header->isactive)
                                    ->where('deleted',0)
                                    ->first();

                        if(!isset($check)){

                              $headerid = DB::table('grading_system')
                                          ->insertGetId([
                                                "description" => $header->description,
                                                "type" => $header->type,
                                                "specification" => $header->specification,
                                                "isactive" => $header->isactive,
                                                "deleted"=>0
                                          ]);

                        }else{
                              $headerid =   $check->id;
                        }

                        foreach($details as $detail){

                              $check = DB::table('grading_system_detail')
                                          ->where('headerid',$headerid)
                                          ->where('description',$detail->description)
                                          ->where('deleted',0)
                                          ->first();

                              if(!isset($check)){
                                    DB::table('grading_system_detail')
                                          ->insertGetId([
                                                "headerid"=>$headerid,
                                                "description" => $detail->description,
                                                "value" => $detail->value,
                                                "sort" => $detail->sort,
                                                "group" => $detail->group,
                                                "deleted"=>0
                                          ]);
                              }

                        }



                        foreach($rv as $detail){

                              $check = DB::table('grading_system_ratingvalue')
                                          ->where('gsid',$headerid)
                                          ->where('description',$detail->description)
                                          ->where('deleted',0)
                                          ->first();

                              if(!isset($check)){



                                    DB::table('grading_system_ratingvalue')
                                          ->insertGetId([
                                                "gsid"=>$headerid,
                                                "description" => $detail->description,
                                                "value" => $detail->value,
                                                "sort" => $detail->sort,
                                                "deleted"=>0
                                          ]);
                              }
                              
                        }

                  }

                  return  array((object)[
                        'status'=>1,
                        'message'=>'Setup Generated'
                  ]);

            }catch(\Exception $e){
                  return $e;
                  return  array((object)[
                      'status'=>0,
                      'message'=>'Something went wrong!'
                  ]);
            }


      }


      public static function gettchrEvlSetup(Request $request){

            

            $details = DB::table('grading_system')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->where('grading_system.description','Teacher Evaluation')
                              ->where('grading_system.type',3)
                              ->where('grading_system.specification',3)
                              ->where('grading_system.deleted',0)
                              ->select(
                                    'grading_system.id',
                                    'grading_system_detail.id as detailid',
                                    'grading_system_detail.description',
                                    'grading_system_detail.sort',
                                    'grading_system_detail.group',
                                    'grading_system_detail.value'
                                    )
                              ->get();


            return $details;

      }

      public static function gettchrEvlDefSetup(){

            $header = array((object)
                  [
                    "description"=> "Teacher Evaluation",
                    "type"=> 3,
                    "specification"=> 3,
                    "isactive"=> 1
                    ]
            );


            $rv = array((object)
                  [
                        "description"=> "6",
                        "value"=> 6,
                        'sort'=>'A7'
                  ],(object)[
                        "description"=> "5",
                        "value"=> 5,
                        'sort'=>'A6'
                  ],(object)[
                        "description"=> "4",
                        "value"=> 4,
                        'sort'=>'A5'
                  ],(object)[
                        "description"=> "3",
                        "value"=> 3,
                        'sort'=>'A4'
                  ],(object)[
                        "description"=> "2",
                        "value"=> 2,
                        'sort'=>'A3'
                  ],(object)[
                        "description"=> "1",
                        "value"=> 1,
                        'sort'=>'A2'
                  ],(object)[
                        "description"=> "1",
                        "value"=> 1,
                        'sort'=>'A1'
                  ]
            );


            $details = array(
                        (object)[
                              "description"=>"1. Explains the purpose or objectives of the learning unit",
                              "value"=>6,
                              "sort"=>"A1",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"2. Connects new lesson to your previous knowledge",
                              "value"=>6,
                              "sort"=>"A2",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"3. Implements learning activities that promote your understanding of the subject matter",
                              "value"=>6,
                              "sort"=>"A3",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"4. Encourages your participation in class.",
                              "value"=>6,
                              "sort"=>"A4",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"5. Facilitates your questions, inquiry and discussions",
                              "value"=>6,
                              "sort"=>"A5",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"6. Encourages higher order thinking in class.",
                              "value"=>6,
                              "sort"=>"A6",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"7. Communicates in a clear and understandable way.",
                              "value"=>6,
                              "sort"=>"A7",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"8. Shows mastery of the subject matter.",
                              "value"=>6,
                              "sort"=>"A8",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"9. Connects lessons to real life problems/situations.",
                              "value"=>6,
                              "sort"=>"A9",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"10. Encourages you to attain higher standards of learning.",
                              "value"=>6,
                              "sort"=>"B1",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"11. Promotes the value of effort and learning.",
                              "value"=>6,
                              "sort"=>"B2",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"12. Integrates values in the lessons.",
                              "value"=>6,
                              "sort"=>"B3",
                              "group"=>"A. Instructional Processes Average"
                        ],(object)[
                              "description"=>"13. Meets classes as scheduled.",
                              "value"=>6,
                              "sort"=>"B4",
                              "group"=>"B. Classroom Management Average"
                        ],(object)[
                              "description"=>"14. Maintains courtesy and respect in class.",
                              "value"=>6,
                              "sort"=>"B5",
                              "group"=>"B. Classroom Management Average"
                        ],(object)[
                              "description"=>"15. Maintains a conducive learning environment.",
                              "value"=>6,
                              "sort"=>"B6",
                              "group"=>"B. Classroom Management Average"
                        ],(object)[
                              "description"=>"16. Enforces school rules and policies.",
                              "value"=>6,
                              "sort"=>"B7",
                              "group"=>"B. Classroom Management Average"
                        ],(object)[
                              "description"=>"17. Gives appropriate feedback to student performance or behavior.",
                              "value"=>6,
                              "sort"=>"B8",
                              "group"=>"B. Classroom Management Average"
                        ],(object)[
                              "description"=>"18. How satisfied are you with your teacher’s performance?",
                              "value"=>6,
                              "sort"=>"C1",
                              "group"=>"C. Outcomes"
                        ],(object)[
                              "description"=>"19. How much did you learn from your teacher?",
                              "value"=>6,
                              "sort"=>"C2",
                              "group"=>"C. Outcomes"
                        ]
                  );

            return array((object)[
                  'header'=>$header,
                  'detail'=>$details,
                  'rv'=>$rv
            ]);

      }
      

}
