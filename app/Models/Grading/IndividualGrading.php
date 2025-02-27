<?php

namespace App\Models\Grading;
use DB;

use Illuminate\Database\Eloquent\Model;

class IndividualGrading extends Model
{
      
      public static function unpost_subject_grade($gdid = null){

            DB::table('grades')
                        ->where('id',$gdid)
                        ->take(1)
                        ->update([
                              'status'=>2,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


            DB::table('gradesdetail')
                        ->where('gradesdetail.headerid',$gdid)
                        ->update([
                              'gdstatus' => 2,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            DB::table('gradelogs')
                        ->insert(
                              [
                                    'user_id' => auth()->user()->id,
                                    'gradeid'=>$gdid,
                                    'action'=>'5',
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]
                        );


      }

      public static function post_subject_grade($gdid = null){

            DB::table('grades')
                  ->where('id',$gdid)
                  ->take(1)
                  ->update([
                        'status'=>4,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('gradesdetail')
                  ->where('gradesdetail.headerid',$gdid)
                  ->update([
                        'gdstatus' => 4,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('gradelogs')
                  ->insert(
                        [
                              'user_id' => auth()->user()->id,
                              'gradeid'=>$gdid,
                              'action'=>'4',
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]
                  );

      }

      public static function pending_subject_grade($gdid = null){

            DB::table('grades')
                  ->where('id',$gdid)
                  ->take(1)
                  ->update([
                        'status'=>3,
                        'submitted'=>0,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('gradesdetail')
                  ->where('gradesdetail.headerid',$gdid)
                  ->update([
                        'gdstatus' => 3,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('gradelogs')
                  ->insert(
                        [
                              'user_id' => auth()->user()->id,
                              'gradeid'=>$gdid,
                              'action'=>'3',
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]
                  );

      }

      //approve grade
      public static function approve_subject_grade($gdid = null){

            DB::table('grades')
                  ->where('id',$gdid)
                  ->take(1)
                  ->update([
                        'status'=>2,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('gradesdetail')
                  ->where('gradesdetail.headerid',$gdid)
                  ->update([
                        'gdstatus' => 2,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);
            
            DB::table('gradelogs')
                  ->insert(
                        [
                              'user_id' => auth()->user()->id,
                              'gradeid'=>$gdid,
                              'action'=>'2',
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]
                  );
      }
    

      public static function get_message_v2($gdid, $status){

            $gradinfo =  DB::table('grading_sytem_gradestatus')
                              ->where('grading_sytem_gradestatus.id',$gdid)
                              ->select('sectionid','subjid','levelid')  
                              ->first();
                              
            if( $gradinfo->levelid == 14 || $gradinfo->levelid == 15){
                  $subjects = DB::table('sh_subjects')
                        ->where('id', $gradinfo->subjid)
                        ->select('subjtitle as subjdesc')
                        ->first();
            }else{
                  $subjects = DB::table('subjects')
                        ->where('id', $gradinfo->subjid)
                        ->select('subjdesc')
                        ->first();
            }

            $section = DB::table('sections')
                        ->where('id', $gradinfo->sectionid)
                        ->select('sectionname')
                        ->first();

            if($status == 1){
                  $message = 'Submitted Grade : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was submitted';
            }elseif($status == 2){
                  $message = 'Approved Grade : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was approved';
            }
            elseif($status == 3){
                  $message = 'Posted Grade : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was added posted';
            }
            elseif($status == 4){
                  $message = 'Pending Grade : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was added to pending';
            }
            elseif($status == 5){
                  $message = 'Unposted Grade : '.$section->sectionname.' - '.$subjects->subjdesc.' grades was unposted';
            }

            return $message;

      }

      public static function resubmit_grades($pid = null){


            DB::table('grading_system_pending_grade')
                  ->where('id',$pid)
                  ->update([
                        'isactive'=>0,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);


      }


      


      public static function grade_notification_logs(
            $gdid = null, 
            $status = null){

            DB::table('grading_system_gradestatus_logs')
                  ->insert([
                        'headerid'=>$gdid,
                        'status'=>$status,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

      }

      public static function grade_notification($receiverid = null, $message = null){

            DB::table('znotification')
            ->insert([
                  'link'=>'#',
                  'reciever'=> $receiverid,
                  'message'=> $message,
                  'createdby'=>auth()->user()->id,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);



      }


}
