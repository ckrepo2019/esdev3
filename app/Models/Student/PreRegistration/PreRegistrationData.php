<?php

namespace App\Models\Student\PreRegistration;
use DB;

use Illuminate\Database\Eloquent\Model;

class PreRegistrationData extends Model
{

      public static function preregistration_list($studid = null, $syid = null, $semid = null){

            $preregistration = DB::table('student_pregistration');

            if($studid != null){
                  $preregistration = $preregistration->where('studid',$studid);
            }
            if($syid != null){
                  $preregistration = $preregistration->where('syid',$syid);
            }
            // if($semid != null){
            //       $preregistration = $preregistration->where('semid',$semid);
            // }

            $preregistration = $preregistration->where('student_pregistration.deleted',0)
                                    ->join('sy',function($join){
                                          $join->on('student_pregistration.syid','=','sy.id');
                                    })
                                    // ->join('semester',function($join){
                                    //       $join->on('student_pregistration.semid','=','semester.id');
                                    // })
                                    ->select(
                                          'studid',
                                          'student_pregistration.id',
                                          'sydesc',
                                          'status',
                                          // 'semester',
                                          'student_pregistration.createddatetime',
                                          'statusdatetime',
                                          'remarks',
                                          'finance_remarks',
                                          'finance_statusdatetime',
                                          'finance_status'
                                    )
                                    ->get();


            foreach($preregistration as $item){
                  
                  $item->createddatetime = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMMM DD, YYYY hh:mm A');
                  $item->statusdatetime = \Carbon\Carbon::create($item->statusdatetime)->isoFormat('MMMM DD, YYYY hh:mm A');
                  $item->finance_statusdatetime = \Carbon\Carbon::create($item->finance_statusdatetime)->isoFormat('MMMM DD, YYYY hh:mm A');

                  $all_remarks = array();
                  $remarks_array = explode(';',$item->remarks);
                  foreach($remarks_array as $item_data){
                        array_push($all_remarks, trim($item_data));
                  }
                  $item->all_remarks = $all_remarks;

                  $finance_all_remarks = array();
                  $remarks_array = explode(';',$item->finance_remarks);
                  foreach($remarks_array as $item_data){
                        array_push($finance_all_remarks, trim($item_data));
                  }
                  $item->finance_all_remarks = $finance_all_remarks;

            }

            return $preregistration;

      }
     
     
      
      
}
