<?php

namespace App\Http\Controllers\ClinicControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\SchoolClinic\SchoolClinic;
use Carbon\Carbon;
class PersonnelController extends Controller
{
    public function index()
    {
        // $usertypeids = DB::table('usertype')
        //     ->select('id')
        //     ->whereIn('refid',[23,24,25])
        //     ->where('deleted','0')
        //     ->get();

        // $usertypeids = collect($usertypeids)->pluck('id');

         // Get the start (Monday) and end (Sunday) of the current week
        $startOfWeek = Carbon::now('Asia/Manila')->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now('Asia/Manila')->endOfWeek(Carbon::SUNDAY);
        
        $personnel = SchoolClinic::personnel();

        foreach ($personnel as  $person) {
           $person->weeklySchedule = DB::table('clinic_schedavailability')
           ->where('docid', $person->userid)
           ->whereBetween('scheddate', [$startOfWeek, $endOfWeek])
           ->get();
        }

    //    dd($personnel);
        return view('clinic.personnel.index')
            ->with('personnel', $personnel);
    }



    // public function getemployees()
    // {
    //     $users = DB::table('teacher')
    //         ->join('usertype','teacher.usertypeid','=','usertype.id')
    //         ->where('teacher.deleted','0')
    //         ->get();

        
    //         return $users;
    // }
}
