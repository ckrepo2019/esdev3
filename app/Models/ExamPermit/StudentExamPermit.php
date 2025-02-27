<?php



namespace App\Models\ExamPermit;

use App\Models\Principal\Billing;
use DB;
use Illuminate\Database\Eloquent\Model;

class StudentExamPermit extends Model
{

      public static function getStudentPermit($studid, $monthreq){


            // self::check_grade_level_quarter_status();

            // $billingMonth = self::billingDate();

            $month = \Carbon\Carbon::create($monthreq)->isoFormat('MM');

            $studentAssesment = Billing::monthlyAssessment($studid,  $month);

            $balance = collect($studentAssesment)->sum('balance');

            $status = 0;

            if($balance == 0 || $balance < 0){

                  $status = 1;

            }
            
            $data = array((object)[
                  'status'=>1,
                  'month'=>$monthreq,
                  'status'=>$status,
                  'balance'=>$balance
            ]);

            return  $data;

      }

      public static function get_exam_status($levelid = null){

            $activeQuarter = DB::table('quarter_setup')
                              ->join('quarter_setup_exampermit',function($join) use($levelid){
                                    $join->on('quarter_setup.id','=','quarter_setup_exampermit.quarter_id');
                                    $join->where('quarter_setup.deleted',0);
                                    $join->where('levelid',$levelid);
                                    $join->where('quarter_setup_exampermit.isactive',1);
                              })
                              ->where('quarter_setup.deleted',0)
                              ->select('quarter_setup.id','monthreq','description')
                              ->get();
                              

            if(count($activeQuarter) == 0){

                  return $data = array((object)[
                        'status'=>0,
                        'data'=>'Quarter Exam permit is not yet available'
                  ]);

            }else{

                  return $data = array((object)[
                        'status'=>1,
                        'data'=>$activeQuarter
                  ]);
            }


      }


      public static function check_promisory_permit($studid = null, $quarterid = null){
            

            $permittoexam = DB::table('permittoexam')
                              ->where('studid',$studid)
                              ->where('quarterid',$quarterid)
                              ->where('syid',self::activeSy()->id)
                              ->where('deleted',0)
                              ->get();
                                    
            if(count($permittoexam) == 0){

                  return $data = array((object)[
                        'status'=>0,
                        'data'=>'No promisory exam permit'
                  ]);

            }else{

                  return $data = array((object)[
                        'status'=>1,
                        'data'=>'With promisory exam permit'
                  ]);
            }

      }


      public static function activeSy(){
            return DB::table('sy')->where('isactive',1)->first();
      }
      public static function activeSem(){
            return DB::table('semester')->where('isactive',1)->first();
      }

      

      
}
