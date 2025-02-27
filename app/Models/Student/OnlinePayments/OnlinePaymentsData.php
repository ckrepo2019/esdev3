<?php

namespace App\Models\Student\OnlinePayments;
use DB;

use Illuminate\Database\Eloquent\Model;

class OnlinePaymentsData extends Model
{

      public static function student_onlinepayment_list_dp($id = null, $studid = null, $syid = null, $semid = null){

            $preregistration = DB::table('onlinepayments');

            if($id != null){
                  $preregistration = $preregistration->where('id',$id);
            }
            if($studid != null){
                  $preregistration = $preregistration->where('queingcode',$studid);
            }
            if($syid != null){
                  $preregistration = $preregistration->where('syid',$syid);
            }
            if($semid != null){
                  $preregistration = $preregistration->where('semid',$semid);
            }

            $preregistration = $preregistration
                                   
                                    ->get();


            foreach($preregistration as $item){
                  if($item->isapproved == 0){
                        $item->isapproved = 'ON PROCESS';
                  }
                  else if($item->isapproved == 1){
                        $item->isapproved = 'APPROVED';
                  }
                  elseif($item->isapproved == 3){
                        $item->isapproved = 'CANCELED';
                  }
                  elseif($item->isapproved == 5){
                        $item->isapproved = 'PAID';
                  }
                  elseif($item->isapproved == 2){
                        $item->isapproved = 'NOT APPROVED';
                  }
                  
                  $item->paymentDate = \Carbon\Carbon::create($item->paymentDate)->isoFormat('MMMM DD, YYYY hh:mm A');
            }

            return $preregistration;

      }
     
     
      
      
}
