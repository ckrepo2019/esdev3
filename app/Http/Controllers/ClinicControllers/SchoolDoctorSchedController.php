<?php

namespace App\Http\Controllers\ClinicControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SchoolDoctorSchedController extends Controller
{
    public function index(Request $request)
    {
        return view('clinic_doctor.schedavailability.index');
    }
    public function getschedavailability(Request $request)
    {
        // return $request->all();
        $docid = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id;
        $weekid = $request->get('weekid');


        $days = array();
        $monthstart = date('Y-m-d', strtotime($request->get('weekid')));
        $monthend = date('Y-m-t', strtotime($request->get('weekid')));
        
        array_push($days,$monthstart);
        $initdate = $monthstart;
        for($x=0; $x<6; $x++)
        {
            array_push($days,date('Y-m-d', strtotime($initdate . ' +1 day')));
            $initdate = date('Y-m-d', strtotime($initdate . ' +1 day'));
        }
        
        $timeavailabilities = array();
        foreach($days as $eachday)
        {
            $checkifexists = DB::table('clinic_schedavailability')
                ->where('docid', $docid)
                ->where('scheddate',$eachday)
                ->where('deleted','0')
                ->get();
    
            if(count($checkifexists)> 0)
            {
                foreach($checkifexists as $checkifexist)
                {
                    $checkadmittedpatients = DB::table('clinic_appointments')
                        ->where('docavailabilityid', $checkifexist->id)
                        ->where('deleted','0')
                        ->get();

                    
                    $checkifexist->appointments = $checkadmittedpatients;
                    array_push($timeavailabilities, $checkifexist);
                }
            }
        }        

        return view('clinic_doctor.schedavailability.timeavailability')
            ->with('timeavailabilities', $timeavailabilities)
            ->with('days', $days);
    }
    public function submittime(Request $request)
    {
        
        try{
            DB::table('clinic_schedavailability')
                ->insert([
                    'docid'         => $request->get('docids'),
                    'scheddate'         => $request->get('scheddate'),
                    'schedday'         => date('l', strtotime($request->get('scheddate'))),
                    'timefrom'      => $request->get('timefrom'),
                    'timeto'      => $request->get('timeto'),
                    'createdby'     => auth()->user()->id,
                    'createddatetime'     => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $e)
        {
            return 0;
        }
        
    }
    public function deletetime(Request $request)
    {
        try{
            DB::table('clinic_schedavailability')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'     => auth()->user()->id,
                    'deleteddatetime'     => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $e)
        {
            return 0;
        }
    }
    public function getappointments (Request $request)
    {
        return $request->all();
    }

    public function loadNotifications (Request $request)
    {
        try{
            DB::table('clinic_notfication')
                ->where('headerid', auth()->user()->id)
                ->update([
                    'status'           => 1
                ]);
            return 1;
        }catch(\Exception $e)
        {
            return 0;
        }
    }

}
