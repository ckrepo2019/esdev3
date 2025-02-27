<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use DB;
class HREmployeeAttendance extends Model
{
    public static function getattendance($date,$employee)
    {
        
        $taphistory = DB::table('taphistory')
            ->where('tdate', $date)
            ->where('studid', $employee->id)
            ->where('utype', $employee->usertypeid)
            ->orderBy('ttime')
            ->where('deleted','0')
            ->get();
            
        $status = 1;

        if(count($taphistory) == 0)
        {
            
            $detailamin     = '00:00:00';
            $detailamout    = '00:00:00';
            $detailpmin     = '00:00:00';
            $detailpmout    = '00:00:00';

            // $status         = 0;

        }elseif(count($taphistory) == 1){
            
            if(date('A', strtotime($taphistory[0]->ttime)) == 'AM')
            {
                $detailamin     = $taphistory[0]->ttime;
                $detailamout    = '00:00:00';
                $detailpmin     = '00:00:00';
                $detailpmout    = '00:00:00';
            }else{
                $detailamin     = '00:00:00';
                $detailamout    = '00:00:00';
                $detailpmin     = date('h:i:s',strtotime($taphistory[0]->ttime));
                $detailpmout    = '00:00:00';
            }

            // $status         = 1;
            
        }else{
            // $status = 1;
            $detailamin =  reset($taphistory)[0]->ttime;
            unset($taphistory[0]);
            $taphistory = collect($taphistory)->values();
            $customtimesched = Db::table('employee_customtimesched')
                ->where('employeeid', $employee->id)
                ->first();

            if(count(collect($customtimesched)) == 0){

                DB::table('employee_customtimesched')
                    ->insert([
                        'amin'          =>  '08:00:00',
                        'amout'         =>  '12:00:00',
                        'pmin'          =>  '13:00:00',
                        'pmout'         =>  '17:00:00',
                        'employeeid'    =>  $employee->id,
                        'createdby'     =>  auth()->user()->id,
                        'createdon'     =>  date('Y-m-d H:i:s')
                    ]);
                
                $customtimesched = Db::table('employee_customtimesched')
                    ->where('employeeid', $employee->id)
                    ->first();

            }
            
            $detailamouttimes   = collect($taphistory->where('ttime','<=',$customtimesched->amout))->values()->sortby('ttime');

            if(count($detailamouttimes) == 0)
            {

                $detailamout    =   "00:00:00";

            }else{
                
                $detailamout        = collect($detailamouttimes)->last()->ttime;
                
                if(count($taphistory)>0)
                {   
                    foreach($taphistory as $removekey => $removevalue)
                    {
                        if($removevalue->ttime <= $detailamout)
                        {
                            unset($taphistory[$removekey]);
                        }
                        
                    }


                }
                
            }
            
            
            $detailpmintimes    = collect($taphistory)->values()->sortBy('ttime');

            if(count($detailpmintimes) == 0)
            {

                $detailpmin     =   "00:00:00";

            }else{
                $detailpmin     = date('h:i:s', strtotime(collect($detailpmintimes)->sortBy('ttime')->first()->ttime));
                $key            = $taphistory->search(function($item) use($detailpmintimes){
                                    return $item->id == collect($detailpmintimes)->first()->id;
                                });
                $taphistory->pull($key);
            }

            if(count($taphistory) == 0)
            {

                $detailpmout     =   "00:00:00";

            }else{
                $detailpmout     = date('h:i:s', strtotime(collect($taphistory)->sortBy('ttime')->last()->ttime));
            }

        }
        // return $detailamin;
        // return $date.' '.$detailamin;
        // if(date('Y-m-d H:i:s') < date('Y-m-d H:i:s',strtotime($date.' '.$detailamin.' AM')))
        // {
        //     $detailamin = '00:00:00';
        // }
        // if(date('Y-m-d H:i:s') < date('Y-m-d H:i:s',strtotime($date.' '.$detailamin.' AM')))
        // {
        //     $detailamin = '00:00:00';
        // }

        return (object)array(
            'amin'  => $detailamin,
            'amout' => $detailamout,
            'pmin'  => $detailpmin,
            'pmout' => $detailpmout,
            'status' => $status
        );
    }
    public static function payrollattendance($date,$employee)
    {
        
        $taphistory = DB::table('taphistory')
            ->where('tdate', $date)
            ->where('studid', $employee->id)
            ->where('utype', $employee->usertypeid)
            ->orderBy('ttime')
            ->get();

        $status = 1;

        $attendance = array();
        if(count($taphistory) == 0)
        {
            

        }elseif(count($taphistory) == 1){
            if(date('A', strtotime($taphistory[0]->ttime)) == 'AM')
            {
                $detailamin     = $taphistory[0]->ttime;
                $detailamout    = null;
                $detailpmin     = null;
                $detailpmout    = null;
            }else{
                $detailamin     = null;
                $detailamout    = null;
                $detailpmin     = $taphistory[0]->ttime;
                $detailpmout    = null;
            }
            $attendance = (object)array(
                'amin'      => $detailamin,
                'amout'      =>  $detailamout,
                'pmin'      => $detailpmin,
                'pmout'      =>  $detailpmout
            );
            // $status         = 1;
            
        }else{
            
            $detailamin =  reset($taphistory)[0]->ttime;
            unset($taphistory[0]);
            $taphistory = collect($taphistory)->values();
            $customtimesched = Db::table('employee_customtimesched')
                ->where('employeeid', $employee->id)
                ->first();

            if(count(collect($customtimesched)) == 0){

                DB::table('employee_customtimesched')
                    ->insert([
                        'amin'          =>  '08:00:00',
                        'amout'         =>  '12:00:00',
                        'pmin'          =>  '13:00:00',
                        'pmout'         =>  '17:00:00',
                        'employeeid'    =>  $employee->id,
                        'createdby'     =>  auth()->user()->id,
                        'createdon'     =>  date('Y-m-d H:i:s')
                    ]);
                
                $customtimesched = Db::table('employee_customtimesched')
                    ->where('employeeid', $employee->id)
                    ->first();

            }
            
            $detailamouttimes   = collect($taphistory->where('ttime','<=',$customtimesched->amout))->values()->sortby('ttime');

            if(count($detailamouttimes) == 0)
            {

                $detailamout    =   null;

            }else{
                
                $detailamout        = collect($detailamouttimes)->last()->ttime;
                
                if(count($taphistory)>0)
                {   
                    foreach($taphistory as $removekey => $removevalue)
                    {
                        if($removevalue->ttime <= $detailamout)
                        {
                            unset($taphistory[$removekey]);
                        }
                        
                    }


                }
                
            }
            
            $detailpmintimes    = collect($taphistory)->values()->sortBy('ttime');

            if(count($detailpmintimes) == 0)
            {

                $detailpmin     =   null;

            }else{

                $detailpmin     = collect($detailpmintimes)->sortBy('ttime')->first()->ttime;
                $key            = $taphistory->search(function($item) use($detailpmintimes){
                                    return $item->id == collect($detailpmintimes)->first()->id;
                                });
                $taphistory->pull($key);

            }

            if(count($taphistory) == 0)
            {

                $detailpmout     =   null;

            }else{

                $detailpmout     = collect($taphistory)->sortBy('ttime')->last()->ttime;

            }

            $attendance = (object)array(
                'amin'      => $detailamin,
                'amout'      =>  $detailamout,
                'pmin'      => $detailpmin,
                'pmout'      =>  $detailpmout
            );

        }

        return $attendance;
    }
}
