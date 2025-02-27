<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class TeacherRequestController extends Controller
{
    public function leave($id, Request $request){
        
        date_default_timezone_set('Asia/Manila');
        if($id == 'dashboard'){

            $myleavesheader = array();
            $noofdaysleave = 0;
            $mynoofleavesremaining = 0;
            $mynoofleavespending = 0;
            $checkifExistpayrollid = DB::table('payroll')
                ->get();
            
            if(count($checkifExistpayrollid) == 0){
                
                array_push($myleavesheader, (object)array(
                    'mynoofleavesremaining' => $mynoofleavesremaining,
                    'mynoofleavespending' => $mynoofleavespending,
                    'noofdaysleave' => $noofdaysleave
                ));
                return view('teacher.teacherleaves')
                    ->with('currentdate', date('Y-m-d'))
                    ->with('myleavesheader', $myleavesheader)
                    ->with('message','Leave application form is not yet available!');
            }
            else{
                $payroll_id = DB::table('payroll')
                    ->where('status','1')
                    ->first();
            }

            $leaves = DB::table('job_leaves')
                ->where('job_leaves.deleted','0')
                ->where('job_leaves.isactive','1')
                ->get();
            // return $leaves;

            foreach($leaves as $leave){

                $noofdaysleave+=$leave->days;

            }
            // $my_info = DB::table('teacher')
            //     ->select('gender')
            //     ->where('userid', auth()->user()->id)
            //     ->where('isactive','1')
            //     ->get();
            // return $my_info; 
            $myleaves = DB::table('payrollleavesdetail')
                ->select('payrollleavesdetail.id','payrollleavesdetail.datesubmitted','job_leaves.leave_type','payrollleavesdetail.datefrom','payrollleavesdetail.dateto','payrollleavesdetail.reason','payrollleavesdetail.status','job_leaves.id as leaveid')
                ->join('teacher','payrollleavesdetail.employeeid','=','teacher.id')
                ->join('job_leaves','payrollleavesdetail.leaveid','=','job_leaves.id')
                ->where('job_leaves.deleted','0')
                // ->where('job_leavesdetail.approved','1')
                ->where('teacher.userid',auth()->user()->id)
                ->where('payrollleavesdetail.deleted','0')
                ->get();

// return $myleaves;
            if(count($leaves) == 0){

                array_push($myleavesheader, (object)array(
                    'mynoofleavesremaining' => $mynoofleavesremaining,
                    'mynoofleavespending' => $mynoofleavespending,
                    'noofdaysleave' => $noofdaysleave
                ));
                // return $leaves;
                return view('teacher.teacherleaves')
                ->with('currentdate', date('Y-m-d'))
                    ->with('leaves', $leaves)
                    ->with('myleavesheader', $myleavesheader);

            }
            else{
                
                foreach($myleaves as $myleave){

                    foreach($myleave as $key => $value){

                        if($key == 'datesubmitted'){

                            $myleave->date_submitted = date('M d, Y',strtotime($value));

                        }

                        if($key == 'datefrom'){

                            $myleave->date_from = date('M d, Y',strtotime($value));
                            $myleave->date_from_int = $value;

                        }

                        if($key == 'dateto'){

                            $myleave->date_to = date('M d, Y',strtotime($value));

                            $myleave->date_to_int = $value;
                        }

                    }

                    $datediff  = strtotime($myleave->date_to_int) - strtotime($myleave->date_from_int);

                    $myleave->numdays = round($datediff / (60 * 60 * 24));

                    if($myleave->status == 'approved'){

                        $mynoofleavesremaining+=$myleave->numdays;

                    }
                    
                }
                // return $myleaves;
                $leavesaarray = array();

                foreach($leaves as $leave){

                    $count = DB::table('payrollleavesdetail')
                        ->join('teacher','payrollleavesdetail.employeeid','=','teacher.id')
                        ->where('payrollleavesdetail.payrollid',$payroll_id->id)
                        ->where('teacher.userid',auth()->user()->id)
                        ->where('payrollleavesdetail.leaveid',$leave->id)
                        ->where('payrollleavesdetail.deleted','0')
                        ->get();
                        
                    if(count($count) == 0){

                        array_push($leavesaarray,(object)array(
                            'id' => $leave->id,
                            'description' => $leave->leave_type,
                            'requests' => '('.$leave->days.')'
                        ));

                    }
                    else{
                        // return $count;
                        $datesconsumed = 0;
                        foreach($count as $cnt){

                                // return $cnt->date_from;
                            if($cnt->dateto == $cnt->datefrom){
                                $datesconsumed+= 1;
                            }else{
                                $datescon = strtotime($cnt->dateto) - strtotime($cnt->datefrom);
                                // return $datescon;
                                $datesconsumed += floor($datescon / (60 * 60 * 24)) + 1;
                            }

                        }
                        
                        // return $leave->days;
                        array_push($leavesaarray,(object)array(
                            'id' => $leave->id,
                            'description' => $leave->leave_type,
                            'requests' => '('.((int)$leave->days - (int)$datesconsumed).')'
                        ));

                    }
                    
                }
                
                $mynoofleavespending = count($myleaves->where('status','pending'));
                
                array_push($myleavesheader, (object)array(
                    'mynoofleavesremaining' => $mynoofleavesremaining,
                    'mynoofleavespending' => $mynoofleavespending,
                    'noofdaysleave' => $noofdaysleave
                ));

                return view('teacher.teacherleaves')
                    ->with('currentdate', date('Y-m-d'))
                    ->with('leaves', $leavesaarray)
                    ->with('myleaves', $myleaves)
                    ->with('myleavesheader', $myleavesheader);

            }

        }
        elseif($id == 'applyleave'){
            
            date_default_timezone_set('Asia/Manila');
            // return $request->all();
            $payroll_id = DB::table('payroll')
                ->where('status','1')
                ->first();

            $date_submitted = date('Y-m-d H:i:s');
            
            $date = explode(' - ',$request->get('date'));

            $teacherid = Db::table('teacher')
                ->select('id')
                ->where('userid', auth()->user()->id)
                ->first();
                
            $checkifExist = DB::table('payrollleavesdetail')
                ->select('payrollleavesdetail.id')
                ->join('job_leaves','payrollleavesdetail.leaveid','=','job_leaves.id')
                ->where('job_leaves.deleted','0')
                ->where('payrollleavesdetail.deleted','0')
                ->where('payrollleavesdetail.status','pending')
                // ->where('job_leavesdetail.date_from',$date[0])
                ->where('payrollleavesdetail.employeeid',$teacherid->id)
                ->get();
            // return $checkifExist;
            if(count($checkifExist) == 0){
                Db::table('payrollleavesdetail')
                    ->insert([
                        'payrollid' => $payroll_id->id,
                        'leaveid' => $request->get('applyleavetype'),
                        'employeeid' => $teacherid->id,
                        'datefrom' => $date[0],
                        'dateto' => $date[1],
                        'reason' => strip_tags($request->get('content')),
                        'datesubmitted' => $date_submitted,
                        'status' => 'pending'
                    ]);
                // DB::insert('insert into job_leavesdetail (headerid,employee_id,payroll_date_id,date_from,date_to,reason,date_submitted,status,deleted,approved) values(?,?,?,?,?,?,?,?,?,?)',[$request->get('leavetype'),$teacherid->id,$payroll_id->id,$date[0],$date[1],$request->get('content'),$date_submitted,'pending',0,0]);

                return redirect()->back()->with("messageAdd", 'Leave application form submitted succesfully!');

            }
            else{

                return redirect()->back()->with("messageExists", 'You already applied for a leave!');

            }
            
        }
        elseif($id == 'editleave'){
            date_default_timezone_set('Asia/Manila');
            // return $request->all();

            $payroll_id = DB::table('payroll')
                ->where('status','1')
                ->first();

            
            $date = explode(' - ',$request->get('date'));

            $teacherid = Db::table('teacher')
                ->select('id')
                ->where('userid', auth()->user()->id)
                ->first();
        
            Db::table('payrollleavesdetail')
                ->where('id',$request->get('requestid'))
                ->update([
                    'leaveid'   =>  $request->get('requestid'),
                    'datefrom'  =>  $date[0],
                    'dateto'    =>  $date[1],
                    'reason'    =>  strip_tags($request->get('content'))
                ]);

            // DB::update('update job_leavesdetail set headerid = ?, date_from = ?, date_to = ?, reason = ? where id = ?',[$request->get('leavetype'),$date[0],$date[1],$request->get('content'),$request->get('requestid')]);

            return redirect()->back()->with("messageAdd", 'Leave application form updated succesfully!');
            
        }
        elseif($id == 'deleteleave'){
            date_default_timezone_set('Asia/Manila');
            // return $request->all();

            Db::table('payrollleavesdetail')
                ->where('id',$request->get('requestid'))
                ->update([
                    'deleted'    =>  '1'
                ]);
            // DB::update('update job_leavesdetail set deleted = ? where id = ?',['1',$request->get('requestid')]);

            return redirect()->back()->with("messageAdd", 'Leave application form deleted succesfully!');

            
        }

    }
    public function overtime($action, Request $request){

        
        date_default_timezone_set('Asia/Manila');
        $payroll_id = DB::table('payroll')
            ->where('status','1')
            ->get();
        if(count($payroll_id) == 0){
            return view('teacher.teacherovertime')
                ->with('currentdate',date('Y-m-d'))
                ->with('message','Request for overtime is not yet availabale!');
        }else{
            $checkifrateexists = Db::table('job_description')
                ->join('teacher','job_description.usertype_id','teacher.usertypeid')
                ->where('teacher.userid', auth()->user()->id)
                ->where('job_description.overtime_rate', '!=','0')
                ->where('job_description.overtime_rate', '!=',null)
                ->get();
            // return $checkifrateexists;
            if(count($checkifrateexists) == 0){
                return view('teacher.teacherovertime')
                    ->with('currentdate',date('Y-m-d'))
                    ->with('message','Request for overtime is not yet availabale!')
                    ->with('payrolldateexists','1')
                    ->with('rateexists','0');
            }
            $payroll_id = DB::table('payroll')
                ->where('status','1')
                ->first();
        }

        $teacherid = Db::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->first();
        if($action == 'dashboard'){
            // return "sdfsdfsdfsdfsdfsdfsdfsdf";
            // return 'asd';
            // return date('Y-m-d');
            
            $overtimes = Db::table('job_overtime')
                ->select('id','time_from','time_to','date_request','reason','status')
                ->where('payroll_history_id',$payroll_id->id)
                ->where('employee_id',$teacherid->id)
                ->where('deleted','0')
                ->get();

            foreach($overtimes as $overtime){
                foreach($overtime as $key => $value){
                    if($key == 'date_request'){
                        $overtime->date_req = $value;
                        $overtime->date_request = date('l - M d, Y', strtotime($value));
                    }
                    if($key == 'time_from'){
                        $overtime->time_from = date('h:m:s A', strtotime($value));
                    }
                    if($key == 'time_to'){
                        $overtime->time_to = date('h:m:s A', strtotime($value));
                    }
                }
            }
            return view('teacher.teacherovertime')
                ->with('overtimes',$overtimes)
                ->with('currentdate',date('Y-m-d'));
        }
        elseif($action == 'request'){
            // return "sdfsdfsdfsdfsdfsdfsdfsdf";
            // return $request->all();
            
            $time = explode(' - ',$request->get('overtimerange'));
            $time_start = date('H:m:s',strtotime($time[0]));
            $time_end = date('H:m:s',strtotime($time[1]));
            // return $time_end;
            $checkifExist = Db::table('job_overtime')
                ->where('payroll_history_id',$payroll_id->id)
                ->where('employee_id',$teacherid->id)
                ->where('date_request',$request->get('overtimeon'))
                ->get();
            // return $checkifExist;
            if(count($checkifExist)==0){
                Db::table('job_overtime')
                    ->insert([
                        'payroll_history_id' => $payroll_id->id,
                        'employee_id' => $teacherid->id,
                        'time_from' => $time_start,
                        'time_to' => $time_end,
                        'date_request' => $request->get('overtimeon'),
                        'reason' => $request->get('reason'),
                        'date_submitted' => date('Y-m-d H:m:s'),
                        'status' => 'pending'
                    ]);
                    return redirect()->back()->with("requested", 'Request for overtime submitted succesfully!');
            }else{

            }
            return view('teacher.teacherovertime');
        }
        elseif($action == 'editrequest'){
            // return $request->all();
            $time = explode(' - ',$request->get('overtimerange'));
            // return date('H:i:s', strtotime($time[1]));
            DB::update('update job_overtime set reason = ?, date_request = ?, time_from = ?, time_to = ? where id = ?',[$request->get('reason'),$request->get('overtimeon'),date('H:i:s', strtotime($time[0])),date('H:i:s', strtotime($time[1])),$request->get('overtimeid')]);
            return redirect()->back()->with("requested", 'Request for overtime updated succesfully!');
            // return view('teacher.teacherovertime');
        }
        elseif($action == 'deleterequest'){
            // return $request->all();
            $time = explode(' - ',$request->get('overtimerange'));
            // return date('H:i:s', strtotime($time[1]));
            DB::update('update job_overtime set deleted = ? where id = ?',[1,$request->get('overtimeid')]);
            return redirect()->back()->with("requested", 'Request for overtime deleted succesfully!');
            // return view('teacher.teacherovertime');
        }

    }

}
