<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\FinanceModel;
class FinanceReportController extends Controller
{
    public function reportbalanceforwarding($id){

        date_default_timezone_set('Asia/Manila');

        $balforwardclassid = db::table('balforwardsetup')->first()->classid;

        $studentledgers = array();

        // $students = DB::table('studinfo')
        //     ->select(
        //         'studinfo.id',
        //         'studinfo.lastname',
        //         'studinfo.firstname',
        //         'studinfo.middlename',
        //         'studinfo.suffix'
        //     )
        //     ->distinct()
        //     ->where('deleted', 0)
        //     ->get();

        // if(count($students) > 0){
        //     foreach($students as $stud){
        //         // $getledger = DB::table('studledger')
        //         //     ->select(
        //         //         'studledger.particulars',
        //         //         'studledger.amount',
        //         //         'studledger.payment',
        //         //         'studledger.ornum'
        //         //     )
        //         //     ->join('sy','studledger.syid','=','sy.id')
        //         //     ->where('studledger.classid', $balforwardclassid)
        //         //     ->where('studledger.studid',$student->id)
        //         //     ->where('sy.isactive','1')
        //         //     ->distinct()
        //         //     ->get();

        //         $paysched = db::table('studpayscheddetail')
        //             ->select('particulars', 'amount', 'amountpay', 'balance')
        //             ->where('studid', $stud->id)
        //             ->where('')

        //         if(count($getledger) > 0){

        //             array_push($studentledgers, (object)array(
        //                 'studentinfo'   => $stud,
        //                 'ledgers'       => $getledger
        //             ));

        //         }

        //     }

        // }

        $balforwardlist = db::table('studpayscheddetail')
            ->select(db::raw('lastname, firstname, middlename, levelname, particulars, amount, amountpay, balance'))
            ->join('studinfo', 'studpayscheddetail.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('classid', $balforwardclassid)
            ->where('syid', FinanceModel::getSYID())
            ->where('studpayscheddetail.deleted', 0)
            // ->orderBy('gradelevel.sortid')
            // ->orderBy('lastname')
            // ->orderBy('firstname')
            ->get();

        // return $balforwardlist;

        if($id == 'view'){

            // return $studentledgers;

            return view('finance.reports.studentsbalanceforwarding')
                ->with('balforwardlist', $balforwardlist);

        }elseif($id == 'print'){

            $schoolinfo = Db::table('schoolinfo')
                ->get();

            $sy = DB::table('sy')
                ->where('isactive', '1')
                ->get();

            $printedby = DB::table('teacher')
                ->select(
                    'teacher.lastname',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.suffix'
                )
                ->where('userid', auth()->user()->id)
                ->first();

            $printeddatetime = date('F d, Y h:i:s A');
            
            $pdf = PDF::loadview('finance/reports/pdf/pdf_studentsbalanceforwarding',compact('balforwardlist','schoolinfo','sy','printedby','printeddatetime'))->setPaper('a4');

            return $pdf->stream('Balance Forwarding Report - '.$sy[0]->sydesc.'.pdf');
        }

    }
    public function reportonlinepayments($id, Request $request){

        date_default_timezone_set('Asia/Manila');

        $studentonlinepayment = array();

        $onlinepayments = Db::table('onlinepayments')
            ->select(
                'onlinepayments.refnum',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix'
            )
            ->join('studinfo','onlinepayments.queingcode','=','studinfo.sid')
            ->where('syid', FinanceModel::getSYID())
            ->distinct()
            ->get();
            
        if(count($onlinepayments) > 0){

            foreach($onlinepayments as $onlinepayment){

                if($id == 'view'){

                    $status = 'all';

                    $getpayments = Db::table('onlinepayments')
                        ->select(
                            'amount',
                            'isapproved',
                            'paymentDate',
                            'bankName',
                            'TransDate',
                            'remarks'
                        )
                        ->where('refnum', $onlinepayment->refnum)
                        ->get();

                    // return $getpayments;

                }
                elseif($id == 'changestatus'){
                    
                    if($request->get('status') == 'all'){

                        $status = 'all';

                        $getpayments = Db::table('onlinepayments')
                            ->select(
                                'amount',
                                'isapproved',
                                'paymentDate',
                                'bankName',
                                'TransDate',
                                'remarks'
                            )
                            ->where('refnum', $onlinepayment->refnum)
                            ->get();

                    }
                    elseif($request->get('status') == '0'){

                        $status = '0';

                        $getpayments = Db::table('onlinepayments')
                            ->select(
                                'amount',
                                'isapproved',
                                'paymentDate',
                                'bankName',
                                'TransDate',
                                'remarks'
                            )
                            ->where('refnum', $onlinepayment->refnum)
                            ->where('isapproved', '0')
                            ->get();

                    }
                    elseif($request->get('status') == '1'){

                        $status = '1';

                        $getpayments = Db::table('onlinepayments')
                            ->select(
                                'amount',
                                'isapproved',
                                'paymentDate',
                                'bankName',
                                'TransDate',
                                'remarks'
                            )
                            ->where('refnum', $onlinepayment->refnum)
                            ->where('isapproved', '1')
                            ->get();
                        
                    }
                    elseif($request->get('status') == '2'){

                        $status = '2';

                        $getpayments = Db::table('onlinepayments')
                            ->select(
                                'amount',
                                'isapproved',
                                'paymentDate',
                                'bankName',
                                'TransDate',
                                'remarks'
                            )
                            ->where('refnum', $onlinepayment->refnum)
                            ->where('isapproved', '2')
                            ->get();
                        
                    }
                    elseif($request->get('status') == '5'){

                        $status = '5';

                        $getpayments = Db::table('onlinepayments')
                            ->select(
                                'amount',
                                'isapproved',
                                'paymentDate',
                                'bankName',
                                'TransDate',
                                'remarks'
                            )
                            ->where('refnum', $onlinepayment->refnum)
                            ->where('isapproved', '5')
                            ->get();
                        
                    }

                }
                
                if(count($getpayments) > 0){


                    array_push($studentonlinepayment,(object)array(
                        'studinfo'      => $onlinepayment,
                        'paymentinfo'   => $getpayments,
                    ));

                }
               

            }

        }
        

        if($id == 'view'){
            // return $studentonlinepayment;
            return view('finance.reports.studentsonlinepayment')
                ->with('status', 'all')
                ->with('studentonlinepayments', $studentonlinepayment);

        }
        elseif($id == 'changestatus'){

            if($request->get('action') == '0'){

                return view('finance.reports.studentsonlinepayment')
                    ->with('status', $request->get('status'))
                    ->with('studentonlinepayments', $studentonlinepayment);

            }else{

                $schoolinfo = Db::table('schoolinfo')
                    ->get();
    
                $sy = DB::table('sy')
                    ->where('isactive', '1')
                    ->get();
    
                $printedby = DB::table('teacher')
                    ->select(
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.suffix'
                    )
                    ->where('userid', auth()->user()->id)
                    ->first();
    
                $printeddatetime = date('F d, Y h:i:s A');
                $status = $request->get('status');
                $pdf = PDF::loadview('finance/reports/pdf/pdf_studentsonlinepayments',compact('status','studentonlinepayment','schoolinfo','sy','printedby','printeddatetime'))->setPaper('a4');
    
                return $pdf->stream('Online Payments Report - '.$sy[0]->sydesc.'.pdf');

            }
            
        }

    }

}
