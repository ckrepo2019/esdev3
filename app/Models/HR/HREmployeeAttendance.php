<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use DB;
use DateTime;
use DateInterval;
use Carbon\Carbon;

class HREmployeeAttendance extends Model
{
    public static function getattendance($date,$employee, $employeeleavesappr=null, $holidaydates=null)
    {   
        date_default_timezone_set('Asia/Manila');
            
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

        $customtimesched = Db::table('employee_customtimesched')
                ->where('employeeid', $employee->id)
                ->first();
        
        if(!$customtimesched){
            $customtimesched = (object)array(
                'amin'      => '08:00:00',
                'amout'      => '12:00:00',
                'pmin'      => '13:00:00',
                'pmout'      => '17:00:00',
            );
        }

        if(count($hr_attendance)>0)
        {
            foreach($hr_attendance as $hratt)
            {
                $hratt->mode = 1;
            }
        }


        $attrecords = collect();
        $attrecords = $attrecords->merge($taphistory);
        $attrecords = $attrecords->merge($hr_attendance);
        $attrecords = $attrecords->sortBy('ttime');
        $attrecords = $attrecords->unique('ttime');
        $status = 1;
        
        $logs = $attrecords;
        
        $lastactivity = '';
        $tapamtimein = null;
        $tapamtimeout = null;
        $tappmtimein = null;
        $tappmtimeout = null;
        
        $amtimein = '00:00:00';
        $amtimeout = '00:00:00';
        $pmtimein = '00:00:00';
        $pmtimeout = '00:00:00';

        $daysabsent = 0;

        $leavesapplied = collect($employeeleavesappr)->where('ldate', $date)->first();
        $holidays = collect($holidaydates)->where('date', $date)->first();


        if(count($attrecords) == 0){
            if ($leavesapplied && $leavesapplied->halfday == 1) {
                $tapamtimein = $customtimesched->amin;
                $tapamtimeout = $customtimesched->amout;
            } else if($leavesapplied && $leavesapplied->halfday == 2) {
                $tappmtimein = $customtimesched->pmin;
                $tappmtimeout = $customtimesched->pmout;
            } else if($leavesapplied && $leavesapplied->halfday == 0) {
                $tapamtimein = $customtimesched->amin;
                $tapamtimeout = $customtimesched->amout;
                $tappmtimein = $customtimesched->pmin;
                $tappmtimeout = $customtimesched->pmout;
            } else if($holidays){
                $tapamtimein = $customtimesched->amin;
                $tapamtimeout = $customtimesched->amout;
                $tappmtimein = $customtimesched->pmin;
                $tappmtimeout = $customtimesched->pmout;
            } else {
                $daysabsent += 1;
            }
        } else {
            $amintap = collect($attrecords)->where('ttime','<', $customtimesched->amout)->where('deleted', 0)->first();
            
            if ($leavesapplied && $leavesapplied->halfday == 1) {
                $tapamtimein = $customtimesched->amin;
            } else if($holidays) {
                $tapamtimein = $customtimesched->amin;
            } else {
                if ($amintap) {
                    $tapamtimein = $amintap->ttime;
                } else {
                    $tapamtimein = null;
                }
            }
            
            
            $amouttap = collect($attrecords)->where('ttime','>', $customtimesched->amin)->where('ttime','<=', $customtimesched->amout)->where('ttime', '!=', $tapamtimein)->where('deleted', 0)->last();
            // if wala siya nag tapout or tap less than or equal sa iyang customtime sched amout
            $amouttap2 = collect($attrecords)->where('ttime','>', $customtimesched->amout)->where('ttime', '<', $customtimesched->pmin)->where('deleted', 0)->first();

            if ($leavesapplied && $leavesapplied->halfday == 1) {
                $tapamtimeout = $customtimesched->amout;
            } else if($holidays) {
                $tapamtimeout = $customtimesched->amout;
            } else {
                if (!$amouttap) {
                    if ($amouttap2 && $tapamtimein != null) {
                        $tapamtimeout = $amouttap2->ttime;
                    } else {
                        $tapamtimeout = null;
                    }
                } else {
                    if ($amouttap) {
                        $tapamtimeout = $amouttap->ttime;
                    } else {
                        $tapamtimeout = null;
                    }
                }
            }

            $pmintapcount = collect($attrecords)->where('ttime','>', $customtimesched->amout)->where('deleted', 0)->count();
            $pmintap = collect($attrecords)->where('ttime','>', $customtimesched->amout)->where('ttime','<', $customtimesched->pmout)->where('ttime', '!=', $tapamtimeout)->where('deleted', 0)->first();
            // possible the result of $pmintap kay isa ra kabook so it means maglibog kong out ba or in sa pm pero kong wala siyay amout og pmin as is 
            
            if ($leavesapplied && $leavesapplied->halfday == 2) {
                $tappmtimein = $customtimesched->pmin;
            } else if($holidays) {
                $tappmtimein = $customtimesched->pmin;
            } else {
                if ($pmintap) {
                    if ($tapamtimein != null && $tapamtimeout == null && $pmintapcount == 1) {
                        $tappmtimein = null;
                    } else {
                        $tappmtimein = $pmintap->ttime;
                    }
                } else {
                    $tappmtimein = null;
                }
            }
            
            
            $pmouttap = collect($attrecords)->where('ttime','>', $customtimesched->pmin)->where('ttime', '!=', $tappmtimein)->where('deleted', 0)->last();
            if ($leavesapplied && $leavesapplied->halfday == 2) {
                $tappmtimeout = $customtimesched->pmout;
            } else if($holidays) {
                $tappmtimeout = $customtimesched->pmout;
            } else {
                if ($pmouttap) {
                    $tappmtimeout = $pmouttap->ttime;
                } else {
                    $tappmtimeout = null;
                }
            }
            
        }
            
            $checkifexists = DB::table('hr_attendanceremarks')
                ->where('tdate',$date)
                ->where('employeeid', $employee->id)
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
            // this is to convert times is sayu ra kaayu ex. 6 am nag in so dapat kong unsa ang ting in like 8am dapat, mao to dapat ma assume na 8 am jud lisod 
            if ($tapamtimein != null && $tapamtimein < $customtimesched->amin) {
                $amtimein = $customtimesched->amin;
            } else {
                $amtimein = $tapamtimein;
            }
            if ($tapamtimeout != null && $tapamtimeout > $customtimesched->amout) {
                $amtimeout = $customtimesched->amout;
            } else {
                $amtimeout = $tapamtimeout;
            }      
            if ($tappmtimein != null && $tappmtimein < $customtimesched->pmin) {
                $pmtimein = $customtimesched->pmin;
            } else {
                $pmtimein = $tappmtimein;
            }      
            if ($tappmtimeout != null && $tappmtimeout > $customtimesched->pmout) {
                $pmtimeout = $customtimesched->pmout;
            } else {
                $pmtimeout = $tappmtimeout;
            }      

        return (object)array(
            'amin'  => $tapamtimein,
            'amout' => $tapamtimeout,
            'pmin'  => $tappmtimein,
            'pmout' => $tappmtimeout,
            'customamin'  => $customtimesched->amin ?? '08:00:00',
            'customamout' => $customtimesched->amout ?? '12:00:00',
            'custompmin'  => $customtimesched->pmin ?? '13:00:00',
            'custompmout' => $customtimesched->pmout ?? '17:00:00',
            'lastactivity' => $lastactivity,
            'status' => $status,
            'holiday' => $holidays,
            'leavesapplied' => $leavesapplied,
        );
    }
    public static function payrollattendancev2($date,$employee,$hourlyrate,$basicsalaryinfo)
    {

        if ($hourlyrate == 0 && $basicsalaryinfo == null) {
            $dailyrate = 0;
        } else {
            $dailyrate = $hourlyrate*$basicsalaryinfo->hoursperday;
        }
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
            if(count($detailpmintimes) == 0)
            {
				$detailamout    =   "12:00:00";
				$detailpmin    =   "13:00:00";
                // $detailamout    =   "00:00:00"; edited sept 5 2023
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
            if ($basicsalaryinfo) {
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
                                if($logintimeamout == null)
                                { 
                                    if($customtimeamout<=date('H:i:s')){
                                        $lateundertimeam =  strtotime($customtimeamout) - strtotime($customtimeamin);
                                    }else{
                                        $lateundertimeam = 0;
                                    }
                                }else{ 
                                    $lateundertimeam =  strtotime($customtimeamout) - strtotime($logintimeamout);
                                }
                                if($lateundertimeam>0)
                                {
                                    $undertimeamout+=($lateundertimeam/60);
                                    $undertimeminutes+=($lateundertimeam/60);
                                }
                                                 
                        }
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
                            }  
                            if($lateundertimepm>0)
                            {
                                $undertimepmout+=($lateundertimepm/60);
                                $undertimeminutes+=($lateundertimepm/60);
                            }
                        }
                    }
                }
            } else {
                $hoursperday = 0;
            }
        }

        $lateminutes = ($lateamin + $latepmin);
        
        $presentminutes=($hoursperday*60);
        $hoursrendered = ($presentminutes-$lateminutes)/60;
   
        if ($basicsalaryinfo) {
            $presentdaysamount=($hoursperday*$basicsalaryinfo->amount);

        } else {
            $presentdaysamount=0;

        }
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
    public static function gethours($days,$employeeid, $employeeleavesappr=null, $holidaydates=null){

        $customworkinghours = 0;
        $attendanceb = DB::table('employee_basicsalaryinfo')
            // ->select('attendancebased')
            ->where('employeeid', $employeeid)
            ->where('deleted','0')
            ->first();
        
        if ($attendanceb) {
            if ($attendanceb->attendancebased == 1) {
                $customtimesched = DB::table('employee_customtimesched')
                    ->where('employeeid', $employeeid)
                    ->where('deleted','0')
                    ->first();
            } else {
                $customtimesched = [];
            }
        } else {
            $customtimesched = [];
        }
        
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
        $daysabsent = 0;
        
        $data = array();
        foreach($days as $day)
        {
            $leavesapplied = collect($employeeleavesappr)->where('ldate', $day)->first();
            $holidays = collect($holidaydates)->where('date', $day)->first();
                
            $attrecords = collect();

            $atttap = DB::table('taphistory')
                ->where('studid', $employeeid)
                ->where('deleted', 0)
                ->where('tdate', $day)
                ->where('utype', 1)
                ->get();
            
            $atthr = DB::table('hr_attendance')
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
           
            $dailytotalworkinghours = 0;
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
            
            $amtimein = '00:00:00';
            $amtimeout = '00:00:00';
            $pmtimein = '00:00:00';
            $pmtimeout = '00:00:00';

            $lateam = 0;
            $latepm = 0;
            $undertimeam = 0;
            $undertimepm = 0;

            $amtotalminutes = 0;
            $pmtotalminutes = 0;
            $appliedleave = 0;

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

            
            if(count($attrecords) == 0)
            {
                if ($leavesapplied && $leavesapplied->halfday == 1) {
                    $tapamtimein = $customtimesched->amin;
                    $tapamtimeout = $customtimesched->amout;
                    $appliedleave = 1;
                    $status = 1;
                } else if($leavesapplied && $leavesapplied->halfday == 2) {
                    $tappmtimein = $customtimesched->pmin;
                    $tappmtimeout = $customtimesched->pmout;
                    $appliedleave = 1;
                    $status = 1;
                } else if($leavesapplied && $leavesapplied->halfday == 0) {
                    $tapamtimein = $customtimesched->amin;
                    $tapamtimeout = $customtimesched->amout;
                    $tappmtimein = $customtimesched->pmin;
                    $tappmtimeout = $customtimesched->pmout;
                    $appliedleave = 1;
                    $status = 1;
                } else if($holidays) {
                    $tapamtimein = $customtimesched->amin;
                    $tapamtimeout = $customtimesched->amout;
                    $tappmtimein = $customtimesched->pmin;
                    $tappmtimeout = $customtimesched->pmout;
                    $status = 1;
                } else {
                    $daysabsent += 1;
                }
            } else {
                $amintap = collect($attrecords)->where('ttime','<', $customtimesched->amout)->where('deleted', 0)->first();
                
                if ($leavesapplied && $leavesapplied->halfday == 1) {
                    $tapamtimein = $customtimesched->amin;
                } else if($holidays) {
                    $tapamtimein = $customtimesched->amin;
                } else {
                    if ($amintap) {
                        $tapamtimein = $amintap->ttime;
                    } else {
                        $tapamtimein = null;
                    }
                }
                
                
                $amouttap = collect($attrecords)->where('ttime','>', $customtimesched->amin)->where('ttime','<=', $customtimesched->amout)->where('ttime', '!=', $tapamtimein)->where('deleted', 0)->last();
                // if wala siya nag tapout or tap less than or equal sa iyang customtime sched amout
                $amouttap2 = collect($attrecords)->where('ttime','>', $customtimesched->amout)->where('ttime', '<', $customtimesched->pmin)->where('deleted', 0)->first();

                if ($leavesapplied && $leavesapplied->halfday == 1) {
                    $tapamtimeout = $customtimesched->amout;
                } else if($holidays) {
                    $tapamtimeout = $customtimesched->amout;
                } else {
                    if (!$amouttap) {
                        if ($amouttap2 && $tapamtimein != null) {
                            $tapamtimeout = $amouttap2->ttime;
                        } else {
                            $tapamtimeout = null;
                        }
                    } else {
                        if ($amouttap) {
                            $tapamtimeout = $amouttap->ttime;
                        } else {
                            $tapamtimeout = null;
                        }
                    }
                }

                $pmintapcount = collect($attrecords)->where('ttime','>', $customtimesched->amout)->where('deleted', 0)->count();
                $pmintap = collect($attrecords)->where('ttime','>', $customtimesched->amout)->where('ttime','<', $customtimesched->pmout)->where('ttime', '!=', $tapamtimeout)->where('deleted', 0)->first();
                // possible the result of $pmintap kay isa ra kabook so it means maglibog kong out ba or in sa pm pero kong wala siyay amout og pmin as is 
                
                if ($leavesapplied && $leavesapplied->halfday == 2) {
                    $tappmtimein = $customtimesched->pmin;
                } else if($holidays) {
                    $tappmtimein = $customtimesched->pmin;
                } else {
                    if ($pmintap) {
                        if ($tapamtimein != null && $tapamtimeout == null && $pmintapcount == 1) {
                            $tappmtimein = null;
                        } else {
                            $tappmtimein = $pmintap->ttime;
                        }
                    } else {
                        $tappmtimein = null;
                    }
                }
                
                
                $pmouttap = collect($attrecords)->where('ttime','>', $customtimesched->pmin)->where('ttime', '!=', $tappmtimein)->where('deleted', 0)->last();
                if ($leavesapplied && $leavesapplied->halfday == 2) {
                    $tappmtimeout = $customtimesched->pmout;
                } else if($holidays) {
                    $tappmtimeout = $customtimesched->pmout;
                } else {
                    if ($pmouttap) {
                        $tappmtimeout = $pmouttap->ttime;
                    } else {
                        $tappmtimeout = null;
                    }
                }
                
            }

            // this is to convert times is sayu ra kaayu ex. 6 am nag in so dapat kong unsa ang ting in like 8am dapat, mao to dapat ma assume na 8 am jud lisod 
            if ($tapamtimein != null && $tapamtimein < $customtimesched->amin) {
                $amtimein = $customtimesched->amin;
            } else {
                $amtimein = $tapamtimein;
            }
            if ($tapamtimeout != null && $tapamtimeout > $customtimesched->amout) {
                $amtimeout = $customtimesched->amout;
            } else {
                $amtimeout = $tapamtimeout;
            }      
            if ($tappmtimein != null && $tappmtimein < $customtimesched->pmin) {
                $pmtimein = $customtimesched->pmin;
            } else {
                $pmtimein = $tappmtimein;
            }      
            if ($tappmtimeout != null && $tappmtimeout > $customtimesched->pmout) {
                $pmtimeout = $customtimesched->pmout;
            } else {
                $pmtimeout = $tappmtimeout;
            }      

            if ($amtimein && $amtimein > $customtimesched->amin) {
                $amtimein = Carbon::createFromFormat('H:i:s', $amtimein); 
                $baseTime = Carbon::createFromFormat('H:i:s', $customtimesched->amin);
                $lateam = $amtimein->diffInMinutes($baseTime);
            }

            if (($amtimein == null && $amtimeout == null) && ($pmtimein != null || $pmtimeout != null)) {
                $lateam = Carbon::createFromFormat('H:i:s', $customtimesched->amin)->diffInMinutes(Carbon::createFromFormat('H:i:s', $customtimesched->amout));
            }
            
            if ($amtimeout && $amtimeout < $customtimesched->amout) {
                $amtimeout = Carbon::createFromFormat('H:i:s', $amtimeout); 
                $baseTime = Carbon::createFromFormat('H:i:s', $customtimesched->amout);
                $undertimeam = $baseTime->diffInMinutes($amtimeout);
            }

            if ($pmtimein && $pmtimein > $customtimesched->pmin) {
                $pmtimein = Carbon::createFromFormat('H:i:s', $pmtimein); 
                $baseTime = Carbon::createFromFormat('H:i:s', $customtimesched->pmin);
                $latepm = $pmtimein->diffInMinutes($baseTime);
            }
            
            if ($pmtimeout && $pmtimeout < $customtimesched->pmout) {
                $pmtimeout = Carbon::createFromFormat('H:i:s', $pmtimeout); 
                $baseTime = Carbon::createFromFormat('H:i:s', $customtimesched->pmout);
                $undertimepm = $baseTime->diffInMinutes($pmtimeout);
            }

            if (($pmtimein == null && $pmtimeout == null) && ($amtimein != null || $amtimeout != null)) {
                $undertimepm = Carbon::createFromFormat('H:i:s', $customtimesched->pmin)->diffInMinutes(Carbon::createFromFormat('H:i:s', $customtimesched->pmout));
            }
           
            // If there's no AM timeout and both AM time-in and PM time-in are present, set $amtimeout to the scheduled amout
            if (!$amtimeout) {
                if ($amtimein != null || $pmtimein != null) {
                    $amtimeout = $customtimesched->amout; // Set to custom amout if both time-ins are present
                } 
            }

            // If there's no PM time-in and both AM time-in and PM timeout are present, set $pmtimein to the scheduled pmin
            if (!$pmtimein) {
                if ($amtimein !== null && $pmtimeout !== null) {
                    $pmtimein = Carbon::parse($customtimesched->pmin); // Set to custom pmin if AM time-in and PM timeout are present
                } 
            }

            // get am total hours
            if ($amtimein) {
                // Tan-awon kung ang $amtimein mas sayo pa sa custom schedule nga amin ug i-adjust kung kinahanglan
                if ($amtimein < Carbon::parse($customtimesched->amin)) {
                    $amtimein = Carbon::parse($customtimesched->amin); // Use parse for flexible parsing
                } else {
                    $amtimein = Carbon::parse($amtimein); // Parse to ensure it's a Carbon object
                }
            
                // Tan-awon kung naa ba'y $amtimeout ug i-adjust kung mas ulahi pa sa custom schedule nga amout
                if ($amtimeout) {
                    if ($amtimeout > Carbon::parse($customtimesched->amout)) {
                        $amtimeout = Carbon::parse($customtimesched->amout); // Parse to ensure it's a Carbon object
                    } else {
                        $amtimeout = Carbon::parse($amtimeout); // Parse the timeout string
                    }
                } 
                // Kung walay timeout pero naa'y PM time-in, gamiton ang custom schedule nga amout isip timeout
                else if (!$amtimeout && $pmtimein != null) {
                    $amtimeout = Carbon::parse($customtimesched->amout); // Parse for a safe conversion
                }
                
                // Kwentahon ang total nga minuto nga kalahi-an sa $amtimein ug $amtimeout
                $amtotalminutes = $amtimein->diffInMinutes($amtimeout);
            }
            
            // get pm total hours
            if ($pmtimeout) {
                if ($pmtimeout >  Carbon::parse($customtimesched->pmout)) {
                    $pmtimeout = Carbon::parse($customtimesched->pmout); // Parse to ensure it's a Carbon object
                } else {
                    $pmtimeout = Carbon::parse($pmtimeout); // Parse the timeout string
                }

                if ($pmtimein) {
                    if ($pmtimein < Carbon::parse($customtimesched->pmin)) {
                        $pmtimein = Carbon::parse($customtimesched->pmin); // Parse to ensure it's a Carbon object
                    } else {
                        $pmtimein = Carbon::parse($pmtimein); // Parse the timeout string
                    }
                } else if (!$pmtimein && $amtimeout != null || $amtimein != null) {
                    $pmtimein = Carbon::parse($customtimesched->pmin);
                }

                if ($pmtimein && $pmtimeout) {
                    $pmtotalminutes = $pmtimein->diffInMinutes($pmtimeout);
                } else {
                    // Handle case where either time-in or time-out is missing
                    $pmtotalminutes = 0;
                }
            }
            
            array_push($data, (object) array(
                'date'              => $day,
                'daystring'         => date('M d', strtotime($day)),
                'day'              => date('l', strtotime($day)),
                'dayint'              => date('d', strtotime($day)),
                'lateamhours'              => $lateam,
                'latepmhours'              => $latepm,
                'undertimeamhours'              => $undertimeam,
                'undertimepmhours'              => $undertimepm,
                'amtimein'              => $tapamtimein,
                'amtimeout'              => $tapamtimeout,
                'pmtimein'              => $tappmtimein,
                'pmtimeout'              => $tappmtimeout,
                'timeinam'              => $tapamtimein,
                'timeoutam'              => $tapamtimeout,
                'timeinpm'              => $tappmtimein,
                'timeoutpm'              => $tappmtimeout,
                'amin'              => $tapamtimein,
                'amout'              => $tapamtimeout,
                'pmin'              => $tappmtimein,
                'pmout'              => $tappmtimeout,
                'amtotalminutes'        => $amtotalminutes,
                'pmtotalminutes'        => $pmtotalminutes,
                'totalworkinghours' => $amtotalminutes + $pmtotalminutes,
                'totalworkinghoursflexi' => 0,
                'customworkinghours' => $customworkinghours,
                'latehours'         => $lateam + $latepm,
                'undertimehours'    => $undertimeam + $undertimepm,
                'logs'    => $attrecords,
                'remarks'    => $remarks,
                'holiday' => $holidays,
                'leavesapplied' => $leavesapplied,
                'appliedleave' => $appliedleave,
                'status'    => $status
            ));
        }
        return collect($data);
    }
}
