<?php

namespace App\Models\Principal;
use DB;
use \Carbon\Carbon;
use App\Models\Principal\LoadData;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    public static function billingDetails($studentid){

        $currentMonth = Carbon::now();

        if($studentid->acadprogid == 5){

            $billdet = DB::table('studpayscheddetail')
                ->where('studid',$studentid->id)
                ->where('studpayscheddetail.deleted','0')
                ->join('sy',function($join){
                    $join->on('studpayscheddetail.syid','=','sy.id');
                    $join->where('isactive','1');
                })
                ->join('semester',function($join){
                    $join->on('studpayscheddetail.semid','=','semester.id');
                    $join->where('semester.isactive','1');
                })
                ->groupBy(DB::raw("MONTH(duedate)"))
                ->select(
                    'studpayscheddetail.particulars',
                    'studpayscheddetail.duedate',
                    'studpayscheddetail.paymentno',
                    DB::raw("SUM(amountpay) as amountpay"),
                    DB::raw("SUM(amount) as amountdue"),
                    DB::raw("SUM(balance) as balance")
                )
                ->orderBy('duedate','asc')
                ->get();


        }else{

            $billdet = DB::table('studpayscheddetail')
                        ->where('studid',$studentid->id)
                        ->where('studpayscheddetail.deleted','0')
                        ->join('sy',function($join){
                            $join->on('studpayscheddetail.syid','=','sy.id');
                            $join->where('isactive','1');
                        })
                        ->groupBy(DB::raw("MONTH(duedate)"))
                        ->select(
                            'studpayscheddetail.particulars',
                            'studpayscheddetail.duedate',
                            'studpayscheddetail.paymentno',
                            DB::raw("SUM(amountpay) as amountpay"),
                            DB::raw("SUM(amount) as amountdue"),
                            DB::raw("SUM(balance) as balance")
                        )
                        ->orderBy('duedate','asc')
                        ->get();

           

        }

       
        $month = Carbon::now('Asia/Manila')->isoFormat('YYYY-MM');

        $scheddetail    = collect($billdet);

        $scheddetail = $scheddetail->sortBy('duedate')->values()->all();
        $duemonth = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MM');

        $monthduedate = collect($scheddetail)->filter(function ($item) use ($duemonth) {
            if(date('m', strtotime(data_get($item, 'duedate'))) == $duemonth)
            {
                return $item;
            }
        });

        if(count( $monthduedate)>0){

            $duedate = $monthduedate->flatten()[0]->duedate;

            $scheddetail = collect($scheddetail)->filter(function ($item) use ($duedate) {
                if(data_get($item, 'duedate') == null || data_get($item, 'duedate') <= $duedate)
                {
                    return $item;
                }
            });

            foreach($scheddetail as $key=>$item){

                if($item->balance == 0){
                    $scheddetail->pull($key);
                }

                if( $item->duedate == null){

                    $item->particulars = 'TUITION/BOOKS/OTH FEE';

                }else{

                    $item->particulars = strtoupper(Carbon::create($item->duedate)->isoFormat('MMMM').' PAYABLE');
                
                }

               
            }

            return $scheddetail;

        }
        else{
            
            return [];

        }

      

      

    }

    public static function remBill($prereg){


        $studinfo = DB::table('studinfo')
                            ->where(function($query) use($prereg){
                                    $query->where('qcode',$prereg->queing_code);
                                    $query->where('sid',$prereg->sid);
                                    $query->where('lrn',$prereg->lrn);
                                }
                            )
                            ->join('gradelevel',function($join){
                                $join->on('studinfo.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted','=','0');
                            })
                            ->join('studentstatus',function($join){
                                $join->on('studinfo.studstatus','=','studentstatus.id');
                                $join->where('gradelevel.deleted','=','0');
                            })
                            ->select(
                                'studinfo.*',
                                'gradelevel.levelname',
                                'gradelevel.acadprogid',
                                'studentstatus.description'
                                )
                            ->first();

        if($studinfo->acadprogid != 5 ){


            $onlinePayments = DB::table('onlinepayments')
                                    ->where(function($query) use($prereg){
                                            $query->where('queingcode',$prereg->queing_code);
                                            $query->orwhere('queingcode',$prereg->sid);
                                            $query->orwhere('queingcode',$prereg->lrn);
                                        }
                                    )
                                    ->whereNotIn('isapproved',['3','2','5'])
                                    ->join('onlinepaymentdetails',function($join){
                                        $join->on('onlinepayments.id','=','onlinepaymentdetails.headerid');
                                        $join->where('onlinepaymentdetails.deleted','0');
                                    })
                                    ->join('sy',function($join){
                                        $join->on('onlinepayments.syid','=','sy.id');
                                        $join->where('sy.isactive','1');
                                    })
                                    ->where('paykind','2')
                                    ->select(
                                        'onlinepayments.amount',
                                        'onlinepaymentdetails.amount as detailamount',
                                        'onlinepaymentdetails.tuitionMonth',
                                        'onlinepaymentdetails.headerid'
                                    )
                                    ->get();


        }
        else{

            $onlinePayments = DB::table('onlinepayments')
                                    ->where(function($query) use($prereg){
                                            $query->where('queingcode',$prereg->queing_code);
                                            $query->orwhere('queingcode',$prereg->sid);
                                            $query->orwhere('queingcode',$prereg->lrn);
                                        }
                                    )
                                    ->whereNotIn('isapproved',['3','2','5'])
                                    ->join('onlinepaymentdetails',function($join){
                                        $join->on('onlinepayments.id','=','onlinepaymentdetails.headerid');
                                        $join->where('onlinepaymentdetails.deleted','0');
                                    })
                                    ->join('sy',function($join){
                                        $join->on('onlinepayments.syid','=','sy.id');
                                        $join->where('sy.isactive','1');
                                    })
                                    ->join('semester',function($join){
                                        $join->on('onlinepayments.semid','=','semester.id');
                                        $join->where('semester.isactive','1');
                                    })
                                    ->where('paykind','2')
                                    ->select(
                                        'onlinepayments.amount',
                                        'onlinepaymentdetails.amount as detailamount',
                                        'onlinepaymentdetails.tuitionMonth',
                                        'onlinepaymentdetails.headerid'
                                    )
                                    ->get();
        }

      

        $studentid = DB::table('studinfo')->where('sid',$prereg->sid)->select('id')->first();

        if(!isset($studentid->id)){

            $studentid = DB::table('studinfo')->where('qcode',$prereg->queing_code)->select('id')->first();
            
        }

        if($studinfo->acadprogid != 5 ){

            $billdet = DB::table('studpayscheddetail')
                    ->where('studid',$studentid->id)
                    ->where('studpayscheddetail.deleted','0')
                    ->join('sy',function($join){
                        $join->on('studpayscheddetail.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                    ->groupBy(DB::raw("MONTH(duedate)"))
                    ->select(
                        'studpayscheddetail.classid',
                            'studpayscheddetail.id',
                            'studpayscheddetail.particulars',
                            'studpayscheddetail.duedate',
                            DB::raw("SUM(amountpay) as amountpay"),
                            DB::raw("SUM(amount) as amountdue"),
                            DB::raw("SUM(balance) as balance")
                        )
                        ->orderBy('duedate','asc')
                        ->get();

            

        }
        else{

            $billdet = DB::table('studpayscheddetail')
                            ->where('studid',$studentid->id)
                            ->where('studpayscheddetail.deleted','0')
                            ->join('sy',function($join){
                                $join->on('studpayscheddetail.syid','=','sy.id');
                                $join->where('isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('studpayscheddetail.semid','=','semester.id');
                                $join->where('semester.isactive','1');
                            })
                            ->groupBy(DB::raw("MONTH(duedate)"))
                            ->select(
                                'studpayscheddetail.classid',
                                    'studpayscheddetail.id',
                                    'studpayscheddetail.particulars',
                                    'studpayscheddetail.duedate',
                                    DB::raw("SUM(amountpay) as amountpay"),
                                    DB::raw("SUM(amount) as amountdue"),
                                    DB::raw("SUM(balance) as balance")
                                )
                                ->orderBy('duedate','asc')
                                ->get();

        }
        if(count($onlinePayments) > 0){

            $over = collect($onlinePayments)->unique('headerid')->sum('amount');

            foreach($billdet as $key=>$item){
   
                if($item->balance == 0){
               
                    $billdet->pull($key);

                }
               
                $matched = collect($onlinePayments)->where('tuitionMonth',Carbon::create($item->duedate)->isoFormat('M'))->toArray();

                if(count($matched) > 0 && $item->balance == 0){
                    // return $matched[0]->detailamount;

                    try{
                        $over = (float) $over - $matched[0]->detailamount;
                        $billdet->pull($key);
                    }
                    catch (\Exception $e) {
                     
                            $over = (float) $over - $matched[array_keys($matched)[0]]->detailamount;
                            $billdet->pull($key);
                    }

                }
                else{

                    // return $over;
                    if( number_format($over, 2 , '.' , '') >= number_format($item->balance, 2 , '.' , '') ){

                        $over = (float) $over - $item->balance;
                        $billdet->pull($key);
                    
                    }
                    else{
                        
                        $item->balance = (float) $item->balance - $over;
                        $over = 0;

                    }

                }
                
            }

        }
        else{

            foreach($billdet as $key=>$item){

                if($item->balance == 0){

                    $billdet->pull($key);

                }
            
            }

        }

        return  $billdet;

    }


    public static function  monthlyAssessment($student = null, $month = null){

        // $activeSy = DB::table('sy')->where('isactive',1)->select('id')->first();
        // $activeSem = DB::table('semester')->where('isactive',1)->select('id')->first();

        // $currentYear = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY');

        $student = DB::table('studinfo')    
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        })
                        ->where('studinfo.deleted',0)
                        ->where('studinfo.id',$student)
                        ->select('studinfo.id','acadprogid')
                        ->first();

       

        // if($student->acadprogid != 5 ){

        //     $billdet = DB::table('studpayscheddetail')
        //                 ->where('studid',$student->id)
        //                 ->where('studpayscheddetail.deleted','0')
        //                 ->where('syid',$activeSy->id)
        //                 ->orWhere('duedate',null)
        //                 ->whereMonth('duedate', '<=', $month)
        //                 ->whereYear('duedate', '<=', $currentYear)
        //                 ->groupBy(DB::raw("MONTH(duedate)"))
        //                 ->select(
        //                 'studpayscheddetail.classid',
        //                     'studpayscheddetail.id',
        //                     'studpayscheddetail.particulars',
        //                     'studpayscheddetail.duedate',
        //                     DB::raw("SUM(amountpay) as amountpay"),
        //                     DB::raw("SUM(amount) as amountdue"),
        //                     DB::raw("SUM(balance) as balance")
        //                 )
        //                 ->orderBy('duedate','asc')
        //                 ->get();

        // }
        // else{

        //     $billdet = DB::table('studpayscheddetail')
        //                     ->where('studid',$student->id)
        //                     ->where('studpayscheddetail.deleted','0')
        //                     ->where('syid',$activeSy->id)
                        

        //                     ->where('semid',$activeSem->id)
        //                     ->whereMonth('duedate', '<=', $month)
        //                     ->whereYear('duedate', '<=', $currentYear)
        //                     ->orWhere('duedate',null)
        //                     ->groupBy(DB::raw("MONTH(duedate)"))
        //                     ->select(
        //                         'studpayscheddetail.classid',
        //                         'studpayscheddetail.id',
        //                         'studpayscheddetail.particulars',
        //                         'studpayscheddetail.duedate',
        //                         DB::raw("SUM(amountpay) as amountpay"),
        //                         DB::raw("SUM(amount) as amountdue"),
        //                         DB::raw("SUM(balance) as balance")
        //                     )
        //                     ->orderBy('duedate','asc')
        //                     ->get();

        // }

        // return $billdet;

            // $currentMonth = $month;

            if($student->acadprogid == 5){

                $billdet = DB::table('studpayscheddetail')
                    ->where('studid',$student->id)
                    ->where('studpayscheddetail.deleted','0')
                    ->join('sy',function($join){
                        $join->on('studpayscheddetail.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                    ->join('semester',function($join){
                        $join->on('studpayscheddetail.semid','=','semester.id');
                        $join->where('semester.isactive','1');
                    })
                    ->groupBy(DB::raw("MONTH(duedate)"))
                    ->select(
                        'studpayscheddetail.particulars',
                        'studpayscheddetail.duedate',
                        'studpayscheddetail.paymentno',
                        DB::raw("SUM(amountpay) as amountpay"),
                        DB::raw("SUM(amount) as amountdue"),
                        DB::raw("SUM(balance) as balance")
                    )
                    ->orderBy('duedate','asc')
                    ->get();


            }else{

                $billdet = DB::table('studpayscheddetail')
                            ->where('studid',$student->id)
                            ->where('studpayscheddetail.deleted','0')
                            ->join('sy',function($join){
                                $join->on('studpayscheddetail.syid','=','sy.id');
                                $join->where('isactive','1');
                            })
                            ->groupBy(DB::raw("MONTH(duedate)"))
                            ->select(
                                'studpayscheddetail.particulars',
                                'studpayscheddetail.duedate',
                                'studpayscheddetail.paymentno',
                                DB::raw("SUM(amountpay) as amountpay"),
                                DB::raw("SUM(amount) as amountdue"),
                                DB::raw("SUM(balance) as balance")
                            )
                            ->orderBy('duedate','asc')
                            ->get();

            

            }

    
            $scheddetail    = collect($billdet);

            $scheddetail = $scheddetail->sortBy('duedate')->values()->all();
            $duemonth = \Carbon\Carbon::now($month)->isoFormat('MM');

            $monthduedate = collect($scheddetail)->filter(function ($item) use ($duemonth) {
                if(date('m', strtotime(data_get($item, 'duedate'))) == $duemonth)
                {
                    return $item;
                }
            });

            if(count( $monthduedate)>0){

                $duedate = $monthduedate->flatten()[0]->duedate;

                $scheddetail = collect($scheddetail)->filter(function ($item) use ($duedate) {
                    if(data_get($item, 'duedate') == null || data_get($item, 'duedate') <= $duedate)
                    {
                        return $item;
                    }
                });

                foreach($scheddetail as $key=>$item){

                    if($item->balance == 0){
                        $scheddetail->pull($key);
                    }

                    $item->particulars = strtoupper(Carbon::create($item->duedate)->isoFormat('MMMM').' PAYABLE');
                    
                }

                return $scheddetail;

            }
            else{
                
                return [];

            }

    }


    public static function checkExamPermitStatus($student = null, $month = null){

        $checkStatus = DB::table('quarter_setup')
                        ->join('permittoexam',function($join) use ($student){
                            $join->on('quarter_setup.id','=','permittoexam.quarterid');
                            $join->where('permittoexam.studid',$student);
                            $join->where('permittoexam.deleted','0');
                        })
                        ->where('isactive',1)
                        ->select('description')
                        ->get();


        return  $checkStatus;


        // $currentYear = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY');

        // return DB::table('studinfo')
        //                 ->where('id',$student)
        //                 ->whereMonth('allowdate', '<=', $month)
        //                 ->whereYear('allowdate', '<=', $currentYear)
        //                 ->select('allowtoexam')
        //                 ->first();


    }
   

}
