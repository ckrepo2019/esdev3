<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use DB;
use DateTime;
use DateInterval;
class HREmployeeAttendance extends Model
{
    public static function getattendance($date,$employee)
    {
        date_default_timezone_set('Asia/Manila');
        
        // $taphistory = DB::table('taphistory')
        //     ->where('tdate', $date)
        //     ->where('studid', $employee->id)
        //     ->where('utype', '!=','7')
        //     ->orderBy('ttime')
        //     ->where('deleted','0')
        //     ->get();

            
        $taphistory = DB::table('taphistory')
            ->where('tdate', $date)
            ->where('studid', $employee->id)
            ->where('utype', '!=', 7)
            ->orderBy('ttime')
            ->where('deleted','0')
            ->get();

        if(count($taphistory)>0)
        {
            foreach($taphistory as $tapatt)
            {
                $tapatt->mode = 0;
            }
        }

        $hr_attendance = DB::table('hr_attendance')
            ->where('tdate', $date)
            ->where('studid', $employee->id)
            ->where('deleted',0)
            ->orderBy('ttime','asc')
            ->get();

        if(count($hr_attendance)>0)
        {
            foreach($hr_attendance as $hratt)
            {
                $hratt->mode = 1;
            }
        }


        $logs = collect();
        $logs = $logs->merge($taphistory);
        $logs = $logs->merge($hr_attendance);
        $logs = $logs->sortBy('ttime');
        $logs = $logs->unique('ttime');
        $status = 1;

        $lastactivity = '';
        if(count($logs) == 0)
        {
            
            $detailamin     = '00:00:00';
            $detailamout    = '00:00:00';
            $detailpmin     = '00:00:00';
            $detailpmout    = '00:00:00';

            // $status         = 0;

        }elseif(count($logs) == 1){
            
            if(date('A', strtotime($logs[0]->ttime)) == 'AM')
            {
                $lastactivity = 'AM IN';
                $detailamin     = $logs[0]->ttime;
                $detailamout    = '00:00:00';
                $detailpmin     = '00:00:00';
                $detailpmout    = '00:00:00';
            }else{
                $lastactivity = 'PM IN';
                $detailamin     = '00:00:00';
                $detailamout    = '00:00:00';
                $detailpmin     = date('h:i:s',strtotime($logs[0]->ttime));
                $detailpmout    = '00:00:00';
            }

            // $status         = 1;
            
        }else{
            
            $customtimesched = Db::table('employee_customtimesched')
                ->where('employeeid', $employee->id)
                ->first();
            if(!$customtimesched){

                DB::table('employee_customtimesched')
                    ->insert([
                        'amin'          =>  '08:00:00',
                        'amout'         =>  '12:00:00',
                        'pmin'          =>  '13:00:00',
                        'pmout'         =>  '17:00:00',
                        'employeeid'    =>  $employee->id,
                        // 'createdby'     =>  auth()->user()->id,
                        'createdon'     =>  date('Y-m-d H:i:s')
                    ]);
                
                $customtimesched = Db::table('employee_customtimesched')
                    ->where('employeeid', $employee->id)
                    ->first();

            }else{
                if($customtimesched->amin == "00:00:00")
                {
                    DB::table('employee_customtimesched')
                        ->where('employeeid', $employee->id)
                        ->where('deleted', 0)
                        ->update([
                                'amin'      => '08:00:00'
                            ]);
                }
                if($customtimesched->amout == "00:00:00")
                {
                    DB::table('employee_customtimesched')
                        ->where('employeeid', $employee->id)
                        ->where('deleted', 0)
                        ->update([
                                'amout'      => '12:00:00'
                            ]);
                }
                if($customtimesched->pmin == "00:00:00")
                {
                    DB::table('employee_customtimesched')
                        ->where('employeeid', $employee->id)
                        ->where('deleted', 0)
                        ->update([
                                'pmin'      => '13:00:00'
                            ]);
                }
                if($customtimesched->pmout == "00:00:00")
                {
                    DB::table('employee_customtimesched')
                        ->where('employeeid', $employee->id)
                        ->where('deleted', 0)
                        ->update([
                                'pmout'      => '17:00:00'
                            ]);
                }
                if(strtolower(date('A', strtotime($customtimesched->pmin))) == 'am')
                {
                    $customtimesched->pmin = date('H:i:s',strtotime($customtimesched->pmin.' PM'));
                }
                
                if(strtolower(date('A', strtotime($customtimesched->pmout))) == 'am')
                {
                    $customtimesched->pmout = date('H:i:s',strtotime($customtimesched->pmout.' PM'));
                }
            }
            // return collect($customtimesched);
            
            // $custom_amin = $customtimesched->amin;
            // $custom_amout = $customtimesched->amout;

            $detailamintimes   = collect($logs->where('ttime','<',$customtimesched->amout)->where('tapstate','IN'))->values()->sortby('ttime');
            
            if(count($detailamintimes) == 0)
            {

                $detailamin     =   "00:00:00";

            }else{
                
                $lastactivity = 'AM IN';
                $detailamin     = date('h:i:s', strtotime(collect($detailamintimes)->sortBy('ttime')->first()->ttime));
                
                $key            = $logs->search(function($item) use($detailamintimes){
                                    return $item->id == collect($detailamintimes)->first()->id;
                                });
                                
                $logs->pull($key);
            }
            
            
            // $detailamin =  reset($taphistory)[0]->ttime;
            // unset($taphistory[0]);
            // $taphistory = collect($taphistory)->values();
            
            $detailamouttimes   = collect($logs->where('ttime','<=',$customtimesched->pmin)->where('tapstate','OUT'))->values()->sortby('ttime');
            
            // $detailamouttimes   = collect($taphistory->whereBetween('ttime',[$customtimesched->amout,$customtimesched->pmin])->where('tapstate','OUT'))->values()->sortby('ttime');

            if(count($detailamouttimes) == 0)
            {

                $detailamout    =   "00:00:00";

            }else{
                $lastactivity = 'AM OUT';
                
                $detailamout        = date('h:i:s',strtotime(collect($detailamouttimes)->last()->ttime));
                
                if(count($logs)>0)
                {   
                    foreach($logs as $removekey => $removevalue)
                    {
                        if($removevalue->ttime <= $detailamout)
                        {
                            unset($logs[$removekey]);
                        }
                        
                    }


                }
                
            }
            // return $detailamout;
            
            $detailpmouttimes    = collect($logs->where('ttime','>',$customtimesched->pmin)->where('tapstate','OUT'))->values()->sortBy('ttime');

            if(count($detailpmouttimes) == 0)
            {

                $detailpmout     =   "00:00:00";

            }else{
                $lastactivity = 'PM OUT';
                // $detailpmout     = date('h:i:s', strtotime(collect($detailpmouttimes)->sortBy('ttime')->last()->ttime));
                $detailpmout     = collect($detailpmouttimes)->sortBy('ttime')->last()->ttime;
            }
            $detailpmintimes    = collect($logs->where('tapstate','IN')->where('ttime','>=',$detailamout)->where('ttime','<=',$detailpmout))->values()->sortBy('ttime');
            
            if(count($detailpmintimes) == 0)
            {

                $detailpmin     =   "00:00:00";

            }else{
                $lastactivity = 'PM IN';
                
                $detailpmin     = date('h:i:s', strtotime(collect($detailpmintimes)->sortBy('ttime')->first()->ttime));
                
                $key            = $logs->search(function($item) use($detailpmintimes){
                                    return $item->id == collect($detailpmintimes)->first()->id;
                                });

                $logs->pull($key);
            }
            $detailpmout     = date('h:i:s', strtotime($detailpmout));

        }
        // return $detailpmin;
        // return $date.' '.$detailamin;
        // if(date('Y-m-d H:i:s') < date('Y-m-d H:i:s',strtotime($date.' '.$detailamin.' AM')))
        // {
        //     $detailamin = '00:00:00';
        // }
        // if(date('Y-m-d H:i:s') < date('Y-m-d H:i:s',strtotime($date.' '.$detailamin.' AM')))
        // {
        //     $detailamin = '00:00:00';
        // }

        // if(collect($logs)->count() > 0)
        // {
        //     $lastactivity = collect($logs)->last()->tapstate;
        // }
        return (object)array(
            'amin'  => $detailamin,
            'amout' => $detailamout,
            'pmin'  => $detailpmin,
            'pmout' => $detailpmout,
            'customamin'  => $customtimesched->amin ?? '08:00:00',
            'customamout' => $customtimesched->amout ?? '12:00:00',
            'custompmin'  => $customtimesched->pmin ?? '13:00:00',
            'custompmout' => $customtimesched->pmout ?? '17:00:00',
            'lastactivity' => $lastactivity,
            'status' => $status
        );
    }
    public static function payrollattendancev2($date,$employee,$hourlyrate,$basicsalaryinfo)
    {
        $dailyrate = $hourlyrate*$basicsalaryinfo->hoursperday;
        date_default_timezone_set('Asia/Manila');
        // $date = '2020-10-07';
        // return collect($employee);
        $customtimesched = Db::table('employee_customtimesched')
            ->where('employeeid', $employee->employeeid)
            ->first();

        // return collect($customtimesched);
        if($customtimesched){
            
            if(strtolower(date('A', strtotime($customtimesched->pmin))) == 'am')
            {
                $customtimesched->pmin = date('H:i:s',strtotime($customtimesched->pmin.' PM'));
            }
            
            if(strtolower(date('A', strtotime($customtimesched->pmout))) == 'am')
            {
                $customtimesched->pmout = date('H:i:s',strtotime($customtimesched->pmout.' PM'));
            }

        }else{
            
            DB::table('employee_customtimesched')
                ->insert([
                    'amin'          =>  '08:00:00',
                    'amout'         =>  '12:00:00',
                    'pmin'          =>  '13:00:00',
                    'pmout'         =>  '17:00:00',
                    'employeeid'    =>  $employee->employeeid,
                    // 'createdby'     =>  auth()->user()->id,
                    'createdon'     =>  date('Y-m-d H:i:s')
                ]);
            
            $customtimesched = Db::table('employee_customtimesched')
                ->where('employeeid', $employee->employeeid)
                ->first();
        }
        // return collect($customtimesched);

        // if($date>date('Y-m-d'))
        // {
        //     $status = 0;
        // }
        // $taphistory = DB::table('taphistory')
        //     ->where('tdate', $date)
        //     ->where('studid', $employee->employeeid)
        //     ->where('utype', '!=','7')
        //     ->orderBy('ttime','asc')
        //     ->where('deleted','0')
        //     ->get();
        $taphistory = DB::table('taphistory')
            ->where('tdate', $date)
            ->where('studid', $employee->employeeid)
            ->where('utype', '!=','7')
            ->orderBy('ttime','asc')
            ->where('deleted','0')
            ->get();

        if(count($taphistory)>0)
        {
            foreach($taphistory as $tapatt)
            {
                $tapatt->mode = 0;
            }
        }

        $hr_attendance = DB::table('hr_attendance')
            ->where('tdate', $date)
            ->where('studid', $employee->employeeid)
            ->where('deleted',0)
            ->orderBy('ttime','asc')
            ->get();
            
        if(count($hr_attendance)>0)
        {
            foreach($hr_attendance as $hratt)
            {
                $hratt->mode = 1;
            }
        }


        $logs = collect();
        $logs = $logs->merge($taphistory);
        $logs = $logs->merge($hr_attendance); 
        $logs = $logs->sortBy('ttime');
        $logs = $logs->unique('ttime');
		
        if(count($logs) > 0)
        {
            $status = 1;
        }else{
            $status = 2;
        }

        $attendance = array();
		
        if(count($logs) == 1)
        {
            
            if(date('A', strtotime($logs[0]->ttime)) == 'AM')
            {
                $detailamin     = $logs[0]->ttime;
                $detailamout    = null;
                $detailpmin     = null;
                $detailpmout    = null;
            }else{
                $detailamin     = null;
                $detailamout    = null;
                $detailpmin     = date('h:i:s',strtotime($logs[0]->ttime));
                $detailpmout    = null;
            }
            $attendance = (object)array(
                'amin'          => $detailamin,
                'amout'         =>  $detailamout,
                'pmin'          => $detailpmin,
                'pmout'         =>  $detailpmout
            );
            
        }else if(count($logs) > 1){
            // $status = 1;
            
            $logs = collect($logs)->values();

            $detailamintimes   = collect($logs->where('ttime','<',$customtimesched->amout)->where('tapstate','IN'))->values()->sortby('ttime');
			// return $detailamintimes;
            if(count($detailamintimes) == 0)
            {

                $detailamin     =   "00:00:00";

            }else{
                // dd($detailamintimes);
                $detailamin     = date('h:i:s', strtotime(collect($detailamintimes)->sortBy('ttime')->first()->ttime));
                
                $key            = $logs->search(function($item) use($detailamintimes){
                                    return $item->id == collect($detailamintimes)->first()->id;
                                });
                                
                $logs->pull($key);
            }
            // return $detailamin;

            $detailamouttimes   = collect($logs->where('ttime','<=',$customtimesched->pmin)->where('tapstate','OUT'))->values()->sortby('ttime');
            // $detailamouttimes   = collect($taphistory->whereBetween('ttime',[$customtimesched->amout,$customtimesched->pmin])->where('tapstate','OUT'))->values()->sortby('ttime');
            // return $detailamouttimes;
            if(count($detailamouttimes) == 0)
            {
		
                $detailamout    =   "12:00:00";
				// $detailamout    =   "00:00:00"; edited sept 5 2023
            }else{
                
                // $detailamout        = collect($detailamouttimes)->last()->ttime;
                
                $detailamout        = date('h:i:s',strtotime(collect($detailamouttimes)->last()->ttime));
                
                if(count($logs)>0)
                {   
                    foreach($logs as $removekey => $removevalue)
                    {
                        if($removevalue->ttime <= $detailamout)
                        {
                            unset($logs[$removekey]);
                        }
                        
                    }


                }
                
            }
            
            
            $detailpmintimes    = collect($logs->where('tapstate','IN'))->values()->sortBy('ttime');
            // return $taphistory;
			// return $detailpmintimes;
            if(count($detailpmintimes) == 0)
            {
				$detailamout    =   "12:00:00";
				$detailpmin    =   "13:00:00";
                // $detailamout    =   "00:00:00"; edited sept 5 2023
				//return $detailamout;
            }else{
                
                $detailpmin     = date('H:i:s', strtotime(collect($detailpmintimes)->sortBy('ttime')->first()->ttime));
                
				
					
				
                $key            = $logs->search(function($item) use($detailpmintimes){
                                    return $item->id == collect($detailpmintimes)->first()->id;
                                });
                $logs->pull($key);
            }
            $detailpmouttimes    = collect($logs->where('tapstate','OUT'))->values()->sortBy('ttime');

            if(count($detailpmouttimes) == 0)
            {

                $detailpmout     =   null;

            }else{
                $detailpmout     = date('H:i:s', strtotime(collect($detailpmouttimes)->sortBy('ttime')->last()->ttime));
            }
            $attendance = (object)array(
                'amin'      => $detailamin,
                'amout'      =>  $detailamout,
                'pmin'      => $detailpmin,
                'pmout'      =>  $detailpmout
            );


        }
        //return $attendance;
        $latedeductionamount    = 0;

        $lateminutes            = 0;

        $presentminutes         = 0;

        $undertimeminutes       = 0;
        
        $holidaypay             = 0;

        $dailynumofhours        = 0;

        $absentdeduction        = 0;
        
        $noabsentdays           = 0;

        $minuteslate            = 0;
        $customlateduration     = 0;
        $customlateallowance    = 0;
        $customlateamount       = 0;

        $minuteslatehalfday     = 0;

        $lateamin               = 0;
        $undertimeamout         = 0;
        $latepmin               = 0;
        $undertimepmout         = 0;

        $hoursperday = 0;
        
        $taphistories = self::gethours(array($date),$employee->employeeid)->first();
        $tardinesscompsetup       = DB::table('hr_tardinesscomp')
            ->where('isactive','1')
            ->where('deleted','0')
            ->get();
            
        $activetardinesscompsetup = collect($tardinesscompsetup)->unique('departmentid')->values();
        
        $activetardinesscompsetup = collect($activetardinesscompsetup)->where('departmentid', $employee->departmentid)->values();
        if(count($activetardinesscompsetup) == 0)
        {
            $timebrackets = collect($tardinesscompsetup)->where('departmentid', 0)->values();
        }else{
            $timebrackets = collect($tardinesscompsetup)->where('departmentid', $employee->departmentid)->values();
        }
        // return collect($employee);
        if(strtolower($employee->ratetype) == 'hourly')
        {
            
            if($date<=date('Y-m-d'))
            {
                $selectedday = strtolower(date('D', strtotime($date)));
                if(strtolower($selectedday) == 'mon')
                {
                    $hoursperday = $basicsalaryinfo->mondayhours;
                }
                elseif(strtolower($selectedday) == 'tue')
                {
                    $hoursperday = $basicsalaryinfo->tuesdayhours;
                }
                elseif(strtolower($selectedday) == 'wed')
                {
                    $hoursperday = $basicsalaryinfo->wednesdayhours;
                }
                elseif(strtolower($selectedday) == 'thu')
                {
                    $hoursperday = $basicsalaryinfo->thursdayhours;
                }
                elseif(strtolower($selectedday) == 'fri')
                {
                    $hoursperday = $basicsalaryinfo->fridayhours;
                }
                elseif(strtolower($selectedday) == 'sat')
                {
                    $hoursperday = $basicsalaryinfo->saturdayhours;
                }
                elseif(strtolower($selectedday) == 'sun')
                {
                    $hoursperday = $basicsalaryinfo->sundayhours;
                }
                else
                {
                    $hoursperday = 0;
                }
    
                $customtimesched = DB::table('employee_basishourly')
                    ->select('timein','timeout','timeshift')
                    ->where('deleted','0')
                    ->where('employeeid',$employee->employeeid)
                    ->where('day', $selectedday)
                    ->get();
                    
                if(count($customtimesched) > 0 && count($taphistories->totalworkinghours) > 0)
                {
                    
                    foreach($customtimesched as $schedkey=>$schedvalue)
                    {
                        $enteredsched = collect($taparray)->where('timein', '<=',$schedvalue->timein);
                        if(count($enteredsched) == 0)
                        {
                            $enteredsched = collect($taparray)->where('timein', '<=',$schedvalue->timeout);
                        }
                        else{
                            if(array_key_exists($schedkey+1, $customtimesched))
                            {
                                $enteredsched = collect($enteredsched)->where('timeout', '<=',$customtimesched[$schedkey+1]->timeout);
                            }else{
                                $enteredsched = collect($enteredsched);
                            }
                        }
                        
                        if(count($enteredsched) == 0)
                        {
                            
                            $lateconfig = strtotime($schedvalue->timeout) - strtotime($schedvalue->timein);
                            if($lateconfig > 0)
                            {
    
                                if($schedvalue->timeshift == 'mor')
                                {
                                    $lateamin += ($lateconfig/60);
                                }else{
                                    $latepmin += ($lateconfig/60);
                                }
                            } 
                        }else{
                            
                            $enteredsched =  collect($enteredsched)->sortByDesc('timein')->values();
                            
                            $lateconfig = strtotime($enteredsched[0]->timein) - strtotime($schedvalue->timein);
                            if($lateconfig > 0)
                            {
    
                                if($schedvalue->timeshift == 'mor')
                                {
                                    $lateamin += ($lateconfig/60);
                                }else{
                                    $latepmin += ($lateconfig/60);
                                }
                            } 
    
                            $undertimeconfig = strtotime($schedvalue->timeout) - strtotime($enteredsched[0]->timeout);
                            if($undertimeconfig > 0)
                            {
                                if($schedvalue->timeshift == 'mor')
                                {
                                    $undertimeamout += ($undertimeconfig/60);
                                }else{
                                    $undertimepmout += ($undertimeconfig/60);
                                }
                            } 
                            // return $undertimeconfig/60;
                        }
                           
                        // return $enteredsched;
                    }
                }
            }

            
        }else{
            $hoursperday = $basicsalaryinfo->hoursperday;
            $attendance = $taphistories;
            
            if($attendance->totalworkinghours>0)
            {
                
                $logintimeamin = $attendance->amin;
                $logintimeamout = $attendance->amout;
                $logintimepmin = $attendance->pmin;
                $logintimepmout = $attendance->pmout;
                if($basicsalaryinfo->attendancebased == 1)
                {
                    if(count($timebrackets)>0)
                    {
                        
                        $customtimeamin = $customtimesched->amin;
                        $customtimeamout = $customtimesched->amout;
                        if(strtolower(date('A', strtotime($customtimesched->pmin))) == 'am')
                        {
                            $customtimepmin = date('H:i:s',strtotime($customtimesched->pmin.' PM'));
                        }else{
                            $customtimepmin = $customtimesched->pmin;
                        }
                        
                        if(strtolower(date('A', strtotime($customtimesched->pmout))) == 'am')
                        {
                            $customtimepmout = date('H:i:s',strtotime($customtimesched->pmout.' PM'));
                        }else{
                            $customtimepmout = $customtimesched->pmout;
                        }
                    }
                    else{
                        $customtimeamin = '08:00';
                        $customtimeamout = '12:00';
                        $customtimepmin = '13:00';
                        $customtimepmout = '17:00';
                    }
                    
                    
                            
                    if($basicsalaryinfo->shiftid == 0 || $basicsalaryinfo->shiftid == 1)
                    {
                        $lateam = 0;
                        if($logintimeamin == null)
                        {
                            if($logintimeamout == null)
                            {
                                $lateam =  strtotime($customtimeamout) - strtotime($customtimeamin);
                                
                                if($basicsalaryinfo->shiftid == 1)
                                {
                                    $noabsentdays+=1;
                                    
                                    $absentdeduction+= ($dailyrate);
                                }
                            }else{
                                $lateam =  strtotime($logintimeamout) - strtotime($customtimeamin);
                                
                                $dailynumofhours += $basicsalaryinfo->hoursperday;
    
                            }
                            
                            if($lateam <= 0){
    
                                $lateam = 0;
    
                            }else{
                                
                                $lateam = $lateam/60;
                                
                            }
                            $lateamin = $lateam;
                            
                        }else{
                            $late =  strtotime($logintimeamin) - strtotime($customtimeamin);
                            $dailynumofhours += $basicsalaryinfo->hoursperday;
                            
                            if($late <= 0){
    
                                $late = 0;
    
                            }else{
                                $late = ($late/60);
                                
                            }
    
                            $lateamin = $late;
                            
                        }
    
                    }
                    // return $timebrackets;
                    if($lateamin > 0){
                        $basedbracket = collect($timebrackets)->where('latefrom','<=', $lateamin)->where('lateto','>=', $lateamin)->first();
                        // if($date == '2021-11-09')
                        // {
                        //     return $basedbracket;
                        // }
                        if($basedbracket)
                        {
                            if($basedbracket->deducttype == 1)
                            {
                                $latedeductionamount+=$basedbracket->amount;
                            }else{
                                $amountperday = number_format($basicsalaryinfo->hoursperday*$hourlyrate,2);
                                $multiplier = ($basedbracket->amount/100);
                                $latedeductionamount+=number_format($amountperday*$multiplier,2);
                                
                            }

                        }

                    }


                    if($basicsalaryinfo->shiftid == 0 || $basicsalaryinfo->shiftid == 2)
                    {
                        $latepm = 0;
                        
                        if($logintimepmin == null)
                        {
    
                            if($logintimepmout == null)
                            {
    
                                if(date('Y-m-d H:i:s')>= date('Y-m-d', strtotime($date.' '.$customtimepmout)))
                                {
                                    // $late =  strtotime($customtimepmout) - strtotime($customtimepmin);
                                }
                                else{
                                    $latepm = 0;
                                }
                                
                                if($basicsalaryinfo->shiftid == 2)
                                {
                                    $noabsentdays+=1;
                                    
                                    $absentdeduction+= ($dailyrate);
                                }
                                
                            }else{
                                // $late =  strtotime($logintimepmout) - strtotime($customtimepmin);
                                $latepm = 0;
    
                                $dailynumofhours += $basicsalaryinfo->hoursperday;
    
                            }
                            
                            if($latepm <= 0){
    
                                $latepm = 0;
    
                            }else{
                                
                                $latepm = $latepm/60;
    
                            }
                            
                        }else{  
    
                            $latepm =  strtotime($logintimepmin) - strtotime($customtimepmin);
                            
                            $dailynumofhours += $basicsalaryinfo->hoursperday;
                            
                            if($latepm <= 0){
    
                                $latepm = 0;
    
                            }else{
    
                                $latepm = $latepm/60;
    
                            }
    
                        }
    
                        $latepmin = $latepm;
    
                    }
                    if($latepmin > 0){
                        $basedbracket = collect($timebrackets)->where('latefrom','<=', $latepmin)->where('lateto','>=', $latepmin)->first();
                        if($basedbracket)
                        {
                            if($basedbracket->deducttype == 1)
                            {
                                $latedeductionamount+=$basedbracket->amount;
                            }else{
                                $amountperday = number_format($basicsalaryinfo->hoursperday*$hourlyrate,2);
                                $multiplier = ($basedbracket->amount/100);
                                $latedeductionamount+=number_format($amountperday*$multiplier,2);
                                
                            }

                        }

                    }
                    
                    
                    if($basicsalaryinfo->shiftid == 0 || $basicsalaryinfo->shiftid == 1)
                    {
                        // return date('H:i:s');
                        // return $logintimeamout;
                            if($logintimeamout == null)
                            { 
                                if($customtimeamout<=date('H:i:s')){
                                    $lateundertimeam =  strtotime($customtimeamout) - strtotime($customtimeamin);
                                }else{
                                    $lateundertimeam = 0;
                                }
                            }else{ 
                                $lateundertimeam =  strtotime($customtimeamout) - strtotime($logintimeamout);
                                // return $lateundertime/60;
                            }
                            if($lateundertimeam>0)
                            {
                                $undertimeamout+=($lateundertimeam/60);
                                $undertimeminutes+=($lateundertimeam/60);
                            }
                                             
                    }
                    // return $logintimepmout;
                    
                    if($basicsalaryinfo->shiftid == 0 || $basicsalaryinfo->shiftid == 2)
                    {
                        if($logintimepmout == null)
                        { 
                            if($customtimepmout<=date('H:i:s'))
                            {
                                $lateundertimepm =  strtotime($customtimepmout) - strtotime($customtimepmin);
                            }else{
                                $lateundertimepm = 0;
                            }
                        }else{ 
                            $lateundertimepm =  strtotime($customtimepmout) - strtotime($logintimepmout);
                            // return $logintimepmout;
                        }   
                        
                        // if($lateundertime>0 && $customtimepmout<=date('H:i:s'))
                        // {
                        //     $undertimepmout+=$lateundertime/60;
                        // }  
                        if($lateundertimepm>0)
                        {
                            $undertimepmout+=($lateundertimepm/60);
                            $undertimeminutes+=($lateundertimepm/60);
                        }
                    }
                }
            }
            
          
        }
        // if($date == '2021-11-15')
        // {
        //     return $latepmin;
        // }
        // $customtimeamin = '08:00';
        // $customtimeamout = '12:00';
        // $customtimepmin = '13:00';
        // $customtimepmout = '17:00';
        // $logintimeamin = $attendance->amin;
        // $logintimeamout = $attendance->amout;
        // $logintimepmin = $attendance->pmin;
        // $logintimepmout = $attendance->pmout;
        // return $logintimepmin;
        // return $customlateamount;
        // $lateamin = ($lateamin )-$customlateallowance;
        // $latepmin = ($latepmin)-$customlateallowance;
        // if($latedeductionamount>0)
        // {
            $lateminutes = ($lateamin + $latepmin);
        // }
        // return $later
        
        // if($lateminutes>0)
        // {
        //     if(count($deductioncomputation)>0)
        //     {
        //         $minutes = $lateminutes;
        //         // return $customlateamount;
        //         if($deductioncomputation[0]->deductfromrate == 1){
                    
        //             for($x= 1; $minutes >= $customlateduration; $x++)
        //             {
        //                 // return 'ad';
        //                 $minutes = $minutes-$customlateduration;
        //                 // return $customlateamount;
        //                 $latedeductionamount+=$customlateamount;
        //             }
    
        //         }else{
        //             for($x= 1; $minutes >= $customlateduration; $x++)
        //             {
        //                 // return 'ad';
        //                 $minutes = $minutes-$customlateduration;
        //                 // return $customlateamount;
        //                 $latedeductionamount+=$customlateamount;
        //             }
        //             // if($minutes>=$customlateduration)
        //             // {
        //             //     $latedeductionamount+=$customlateamount;
        //             // }
        //             // $minutes = $minutes-$customlateduration;
        //         }
                
        //     }
        //     // return $customlateamount;
        // }else{
        //     $lateminutes = 0;
        // }
        // return $latedeductionamount;
        // $presentminutes=($hoursperday*60) - ($lateminutes+$undertimeamout+$undertimepmout);
        
        $presentminutes=($hoursperday*60);
        $hoursrendered = ($presentminutes-$lateminutes)/60;
        // return 300/60;
        // if($date == '2023-05-05')
        // {
        //     return $undertimeminutes;

        // }
        $presentdaysamount=($hoursperday*$basicsalaryinfo->amount);
        return (object)array(
            'status'   => $status,
            // 'deductioncomputation'   => $deductioncomputation,
            'customlateduration'   => $customlateduration,
            'latedeductionamount'   => $latedeductionamount,
            'lateminutes'   => $lateminutes,
            'customlateamount'   => $customlateamount,
            'hoursrendered'   => $hoursrendered,
            'presentminutes'   => $presentminutes,
            'presentdaysamount'   => $presentdaysamount,
            'undertimeminutes'   => $undertimeminutes,
            'holidaypay'   => $holidaypay,
            'dailynumofhours'   => $dailynumofhours,
            'absentdeduction'   => $absentdeduction,
            'noabsentdays'   => $noabsentdays,
            'minuteslate'   => $minuteslate,
            'minuteslatehalfday'   => $minuteslatehalfday,
            'lateamin'   => $lateamin,
            'undertimeamout'   => $undertimeamout,
            'latepmin'   => $latepmin,
            'undertimepmout'   => $undertimepmout,
            'brackets'   => $timebrackets
        );
    }
    public static function gethours($days,$employeeid){
        $customworkinghours = 0;
        
        $customtimesched = DB::table('employee_customtimesched')
            ->where('employeeid', $employeeid)
            ->where('deleted','0')
            ->first();
        // return collect($customtimesched);
        if(!$customtimesched)
        {
            $customtimesched = (object)array(
                'amin'      => '08:00:00',
                'amout'      => '12:00:00',
                'pmin'      => '13:00:00',
                'pmout'      => '17:00:00',
            );
        }
		
		if($customtimesched->amin == '00:00:00'){
			$customtimesched->amin = '08:00:00';
		}
		if($customtimesched->amout == '00:00:00'){
			$customtimesched->amout = '12:00:00';
		}
		if($customtimesched->pmin == '00:00:00'){
			$customtimesched->pmin = '13:00:00';
		}
		if($customtimesched->pmout == '00:00:00'){
			$customtimesched->pmout = '17:00:00';
		}

        
        $customtimeamin = strtotime($customtimesched->amin);
        $customtimeamout = strtotime($customtimesched->amout);
        $customdifferenceam = round(abs($customtimeamout - $customtimeamin) / 3600,2);

        $customtimepmin = strtotime($customtimesched->pmin);
        $customtimepmout = strtotime($customtimesched->pmout);
        $customdifferencepm = round(abs($customtimepmout - $customtimepmin) / 3600,2);
        
        $customworkinghours += $customdifferenceam;
        $customworkinghours += $customdifferencepm;
        
        // $totalworkinghours = 0;
        // $totallate = 0;
        // $totalundertime = 0;
        $daysabsent = 0;
        
        $data = array();
        foreach($days as $day)
        {
            $attrecords = collect();

            $atttap = DB::table('taphistory')
                // ->select('tdate','ttime','tapstate')
                ->where('studid', $employeeid)
                ->where('deleted', 0)
                ->where('tdate', $day)
                ->get();
            
            $atthr = DB::table('hr_attendance')
                // ->select('tdate','ttime','tapstate')
                ->where('studid', $employeeid)
                ->where('deleted', 0)
                ->where('tdate', $day)
                ->get();

            $attrecords = $attrecords->merge($atttap);
            
            $attrecords = $attrecords->merge($atthr);
            if(count($attrecords)>0)
            {
                foreach($attrecords as $attrecord)
                {
                    $hour = explode(':', $attrecord->ttime);
                    if($hour[0] == '00')
                    {
                        $attrecord->ttime = '12:'.$hour[1].':'.$hour[2];
                    }
                    if($hour[0] == '01')
                    {
                        $attrecord->ttime = '13:'.$hour[1].':'.$hour[2];
                    }
                }
            }
            $attrecords = $attrecords->sortBy('ttime');
            $attrecords = $attrecords->values();
            
            // if($day == '2022-04-30')
            // {
            //     return $attrecords;
            // }
            $dailytotalworkinghours = 0;
            $dailytotalworkinghoursflexi = 0;
            $latehours = 0;
            $undertimehours = 0;
            $lateamhours = 0;
            $latepmhours = 0;
            $undertimeamhours = 0;
            $undertimepmhours = 0;
            $tapamtimein = null;
            $tapamtimeout = null;
            $tappmtimein = null;
            $tappmtimeout = null;
            // return 'dasdad';
            
            if(count($attrecords) == 0)
            {
                
                $daysabsent += 1;
            }else{
                if(collect($attrecords)->where('ttime','<', $customtimesched->amout)->where('tapstate','IN')->first())
                {
                    $tapamtimein = collect($attrecords)->where('ttime','<', $customtimesched->amout)->where('tapstate','IN')->first()->ttime;
                }else{
                    // $tapamtimein = $customtimesched->amin;
                }

                if(collect($attrecords)->where('ttime','<', $customtimesched->pmin)->where('ttime','>',$tapamtimein)->where('tapstate','OUT')->first())
                {
					
                    $tapamtimeout = collect($attrecords)->where('ttime','<', $customtimesched->pmin)->where('ttime','>',$tapamtimein)->where('tapstate','OUT')->first()->ttime;
                }else{
					
                    // $tapamtimeout = '12:00:00';
                    $tapamtimeout = $customtimesched->amout;
                }

                if(collect($attrecords)->where('ttime','>', $customtimesched->amout)->where('ttime','<', $customtimesched->pmout)->where('ttime','>',$tapamtimeout)->where('tapstate','IN')->first())
                {
                    $tappmtimein = collect($attrecords)->where('ttime','>', $customtimesched->amout)->where('ttime','>',$tapamtimeout)->where('tapstate','IN')->first()->ttime;

                }else{
                    // if($day == '2021-11-15')
                    // {
                    //     return 'asdad';
                    // }
                    $tappmtimein = $customtimesched->pmin;
					// $tappmtimein = '13:00:00';
                }

                if(collect($attrecords)->where('ttime','>', $customtimesched->pmin)->where('tapstate','OUT')->last())
                {
                    $tappmtimeout = collect($attrecords)->where('ttime','>', $customtimesched->pmin)->where('tapstate','OUT')->last()->ttime;
                }else{
                    // $tappmtimeout = $customtimesched->pmout;
                }
                if($tapamtimein>0)
                {
                    //$difftapamtimein = strtotime($tapamtimein);
                    //if($tapamtimeout == null)
                    //{
                        //$difftapamtimeout = strtotime($customtimesched->amout);
                    //}else{
						//$customschedout =  $customtimesched->amout;
						//$customschedoutTimestamp = strtotime($customschedout);
						//$difftapamtimeouttap = strtotime($tapamtimeout);
						//return $tapamtimeout;
						//return $difftapamtimeout;
						//if ($difftapamtimeouttap < $customschedoutTimestamp) {
							// Calculate the difference between $customschedout and $difftapamtimeout
							//$difftapamtimeout = $difftapamtimeouttap; // Convert to hours
						//} else {
							// Maintain 4 hours
							//$difftapamtimeout = $customschedoutTimestamp;
						//}
                    //} 
                    //$differenceam = round(abs($difftapamtimeout - $difftapamtimein) / 3600,2);
                    //$dailytotalworkinghours += $differenceam;
					
					$difftapamtimein = new DateTime($tapamtimein);
					
                    if($tapamtimeout == null)
                    {
                        $difftapamtimeout = new DateTime($customtimesched->amout);
                    }else{
						$customschedout =  new DateTime($customtimesched->amout);
						$difftapamtimeouttap = new DateTime($tapamtimeout);
						if ($difftapamtimeouttap < $customschedout) {
							$difftapamtimeout = $difftapamtimeouttap;
                            $difftapamtimeoutflexi = $difftapamtimeouttap;
						} else {
							$difftapamtimeout = $customschedout;
                            $difftapamtimeoutflexi = $customschedout;
						}
                        
                    } 
                    $differenceam = $difftapamtimein->diff($difftapamtimeout);
                    $differenceamflexi = $difftapamtimein->diff($difftapamtimeoutflexi);

					$hours = $differenceam->h;
					$minutes = $differenceam->i;
					$totalHoursam = $hours + ($minutes / 60);

                    //flexi
                    $hoursflexi = $differenceamflexi->h;
					$minutesflexi = $differenceamflexi->i;
					$totalHoursamflexi = $hoursflexi + ($minutesflexi / 60);

                    $dailytotalworkinghours += $totalHoursam;
                    $dailytotalworkinghoursflexi = $totalHoursamflexi;
					// return $dailytotalworkinghours;
                }
                if($tappmtimein>0)
                {
                    //$difftappmtimeintap = strtotime($tappmtimein);
					//$customschedpmin =  $customtimesched->pmin;
					//$customschedpminTimestamp = strtotime($customschedpmin);
					//return $tappmtimein;
					//return $customtimesched->pmin;
					//if($difftappmtimeintap < $customschedpminTimestamp){
						//$difftappmtimein = $customschedpminTimestamp;
					//} else {
						//$difftappmtimein = $difftappmtimeintap;
					//}
					
                    //if($tapamtimeout == null)
                    //{
                        //$difftappmtimeout = strtotime($customtimesched->pmout);
                    //}else{
                        //$difftappmtimeout = strtotime($tappmtimeout);
                    //}
                    //$differencepm = round(abs($difftappmtimeout - $difftappmtimein) / 3600,2);
                    //$dailytotalworkinghours += $differencepm;
					
					$difftappmtimeintap = new DateTime($tappmtimein);
					$customschedpmin =  $customtimesched->pmin;
					$customschedpminTimestamp = new DateTime($customschedpmin);
					
					//return $customschedpminTimestamp;
					if($difftappmtimeintap > $customschedpminTimestamp){
						//return 'dako';
						$difftappmtimein = $difftappmtimeintap;
					} else {
						//return 'gamay';
						$difftappmtimein = $customschedpminTimestamp;
					}
					//return collect($difftappmtimein);
					$difftappmtimeoutflexi = null;
					// return $tapamtimeout;
                    if($tapamtimeout == null)
                    {
                        $difftappmtimeout = new DateTime($customtimesched->pmout);
                    }else{
						$difftappmtimeouttap = new DateTime($tappmtimeout);
						$customtimepmout = new DateTime($customtimesched->pmout);
						if($difftappmtimeouttap > $customtimepmout){
							$difftappmtimeout = $customtimepmout;
                            $difftappmtimeoutflexi = $difftappmtimeouttap;
						} else {
							$difftappmtimeout = $difftappmtimeouttap;
                            $difftappmtimeoutflexi = $difftappmtimeouttap;
						}
                        
                    }
					
                    $differencepm = $difftappmtimein->diff($difftappmtimeout);
					$differencepmflexi = null;
					
					
					// return collect($difftappmtimeoutflexi);
                    if ($difftappmtimein instanceof DateTime && $difftappmtimeoutflexi instanceof DateTime) {
						$differencepmflexi = $difftappmtimein->diff($difftappmtimeoutflexi);
						
						
					} else {
						// Handle the case where either $difftappmtimein or $difftappmtimeoutflexi is not a valid DateTime object
						// You may want to log an error, provide default values, or take appropriate action based on your use case.
						//echo "Invalid DateTime objects";
					}
					// return collect($differencepmflexi);
					$hours = $differencepm->h;
					$minutes = $differencepm->i;
					$totalHourspm = $hours + ($minutes / 60);
					$totalHourspmflexi = 0;
					
					if($differencepmflexi != null){
						//flexi
						$hoursflexi = $differencepmflexi->h;
						$minutesflexi = $differencepmflexi->i;
						$totalHourspmflexi = $hoursflexi + ($minutesflexi / 60);
					}
					
                  

					// return $hoursflexi;
                    $dailytotalworkinghours += $totalHourspm;
					$dailytotalworkinghoursflexi += $totalHourspmflexi;

					// return $dailytotalworkinghoursflexi;
					
                }
        
                // return $dailytotalworkinghours;
                

                // $latehours = 0;

                if($customtimesched->amin < $tapamtimein)
                {                    
                    $basetimeinam = strtotime($customtimesched->amin);
                    $intimeam = strtotime($tapamtimein);
                    $differencelateam = round(abs($intimeam - $basetimeinam) / 3600,2);
                    $latehours += $differencelateam;
                    $lateamhours += $differencelateam;
					
					
					
                }
				//return $customtimesched->pmin;
                if($customtimesched->pmin < $tappmtimein)
                {                    
                    $basetimeinpm = strtotime($customtimesched->pmin);
                    $intimepm = strtotime($tappmtimein);
                    $differencelatepm = round(abs($intimepm - $basetimeinpm) / 3600,2);
                    $latehours += $differencelatepm;
                    $latepmhours += $differencelatepm;
					// return $latepmhours;
				
                }
				
                // $undertimehours = 0;

                if($customtimesched->amout > $tapamtimeout && $tapamtimeout != null)
                {                    
                    $outtimeam = strtotime($tapamtimeout);
                    $basetimeoutam = strtotime($customtimesched->amout);
                    $differenceundertimeam = round(abs($basetimeoutam - $outtimeam) / 3600,2);
                    $undertimehours += $differenceundertimeam;
                    $undertimeamhours += $differenceundertimeam;
                }

                if($customtimesched->pmout > $tappmtimeout && $tappmtimeout != null)
                {                    
                    $outtimeam = strtotime($tappmtimeout);
                    $basetimeoutpm = strtotime($customtimesched->pmout);
                    $differenceundertimepm = round(abs($basetimeoutpm - $outtimeam) / 3600,2);
                    $undertimehours += $differenceundertimepm;
                    $undertimepmhours += $differenceundertimepm;
                }
            }
            
            $checkifexists = DB::table('hr_attendanceremarks')
                ->where('tdate',$day)
                ->where('employeeid', $employeeid)
                ->where('deleted','0')
                ->first();

            $remarks = '';
            if($checkifexists)
            {
                $remarks = $checkifexists->remarks;
            }
            if(count($attrecords) > 0)
            {
                $status = 1;
            }else{
                $status = 2;
            }

            
            $customlateallowance    = 0;
            
            $getlatedeductionsetup = Db::table('deduction_tardinesssetup')
            ->where('status','1')
            ->first();
            
            $departmentid = DB::table('teacher')
            ->select(
                'hr_departments.id as departmentid'
                )
            ->leftJoin('employee_personalinfo','teacher.id','employee_personalinfo.employeeid')
            ->leftJoin('civilstatus','employee_personalinfo.maritalstatusid','civilstatus.civilstatus')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('hr_departments','teacher.schooldeptid','hr_departments.id')
            ->where('teacher.id', $employeeid)
            ->first()->departmentid;

            if($getlatedeductionsetup)
            {
                if(strtolower($getlatedeductionsetup->type) == 'custom'){
                        
                    $deductiontardinessapplication = Db::table('deduction_tardinessapplication')
                        ->where('departmentid',$departmentid)
                        ->where('deleted','0')
                        ->get();
                        
                    if(count($deductiontardinessapplication) == 0)
                    {
                        $deductioncomputation = Db::table('deduction_tardinessdetail')
                            ->where('all',1)
                            ->where('deleted','0')
                            ->get();

                        // $deductiontardinessapplication = Db::table('deduction_tardinessapplication')
                        //     ->where('all',1)
                        //     ->where('deleted','0')
                        //     ->get();


                    }else{
                        
                        $deductioncomputation = Db::table('deduction_tardinessdetail')
                            ->where('id',$deductiontardinessapplication[0]->tardinessdetailid)
                            ->where('deleted','0')
                            ->get();
                    }
                    
                }
            }
			
			// return $dailytotalworkinghours;
            $timeinam = collect($attrecords)->where('tapstate','IN')->first()->ttime ?? '';
            $timeoutam = collect($attrecords)
                        ->where('ttime','<=',$customtimesched->pmin)
                        ->where('tapstate','OUT')
                        ->last()->ttime ?? '12:00:00';
                        // ->last()->ttime ?? $customtimesched->amout;
                        
            $timeinpm = collect($attrecords)
                        ->where('ttime','>=',$customtimesched->amout)
                        ->where('ttime','<=',$customtimesched->pmout)
                        ->where('tapstate','IN')
                        // ->first()->ttime ?? '14:00:00';
                        ->first()->ttime ?? $customtimesched->pmin;
            $timeoutpm = collect($attrecords)->where('ttime','>',$timeinpm)->where('ttime','>',$timeoutam)->where('tapstate','OUT')->last()->ttime ?? '';
            array_push($data, (object) array(
                'date'              => $day,
                'daystring'         => date('M d', strtotime($day)),
                'day'              => date('l', strtotime($day)),
                'dayint'              => date('d', strtotime($day)),
                'lateamhours'              => $lateamhours,
                'latepmhours'              => $latepmhours,
                'undertimeamhours'              => $undertimeamhours,
                'undertimepmhours'              => $undertimepmhours,
                'amtimein'              => $tapamtimein,
                'amtimeout'              => $tapamtimeout,
                'pmtimein'              => $tappmtimein,
                'pmtimeout'              => $tappmtimeout,
                'timeinam'              => $timeinam,
                'timeoutam'              => $timeoutam,
                'timeinpm'              => $timeinpm,
                'timeoutpm'              => $timeoutpm,
                'amin'              => $tapamtimein,
                'amout'              => $tapamtimeout,
                'pmin'              => $tappmtimein,
                'pmout'              => $tappmtimeout,
                'totalworkinghours' => ($dailytotalworkinghours > 0) ? floor($dailytotalworkinghours * 100) / 100 : 0,
                'totalworkinghoursflexi' => ($dailytotalworkinghoursflexi > 0) ? floor($dailytotalworkinghoursflexi * 100) / 100 : 0,
                'latehours'         => $latehours,
                'undertimehours'    => $undertimehours,
                'logs'    => $attrecords,
                'remarks'    => $remarks,
                'status'    => $status
            ));
        }
        return collect($data);
    }
}
