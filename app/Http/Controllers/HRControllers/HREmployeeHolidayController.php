<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
class HREmployeeHolidayController extends Controller
{
    public function index(Request $request){
        return view('hr.payroll.overtimesetup');
    }

    // load all Holiday Types
    public function loadallholidaytype(Request $request){
        $data = DB::table('hr_holidaytype')
            ->where('deleted', 0)
            ->get();

        return $data;
    }

    // when saving the added Holiday Setup
    public function  addholidaytype(Request $request){
        $description = $request->get('description');
        $ifwork = $request->get('ifwork');
        $ifnotwork = $request->get('ifnotwork');
        $restdayifwork = $request->get('restdayifwork');
        $restdayifnotwork = $request->get('restdayifnotwork');
        
        $ifexist = DB::table('hr_holidaytype')
            ->where('description', $description)
            ->where('deleted', 0)
            ->count();

        if ($ifexist) {
            return array((object)[
                'status' => 0,
                'message' => 'Already Exist!',
            ]);
        } else {
            $data = DB::table('hr_holidaytype')
                ->insert([
                    'description' => $description,
                    'ifwork' => $ifwork,
                    'ifnotwork' => $ifnotwork,
                    'restdayifwork' => $restdayifwork,
                    'restdayifnotwork' => $restdayifnotwork,
                    'status' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);

            return array((object)[
                'status' => 1,
                'message' => 'Added Successfully!',
            ]);
        }
    }


    // When Trash Icon is Click to delete
    public function  deleteholidaytype(Request $request){
        $holidaytypeid = $request->get('holidaytypeid');
      
        $data = DB::table('hr_holidaytype')
            ->where('id', $holidaytypeid)
            ->where('deleted', 0)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Deleted Successfully!',
        ]);
    }

    // When Trash Icon is Click to delete
    public function  updateholidaytype(Request $request){
        $holidaytypeid = $request->get('holidaytypeid');
        $description = $request->get('description');
        $ifwork = $request->get('ifwork');
        $ifnotwork = $request->get('ifnotwork');
        $restdayifwork = $request->get('restdayifwork');
        $restdayifnotwork = $request->get('restdayifnotwork');
        
        // return $request->all();
        $ifexist = DB::table('hr_holidaytype')
            ->where('id', '!=', $holidaytypeid)
            ->where('description', $description)
            ->where('deleted', 0)
            ->count();
        
        if ($ifexist) {
            return array((object)[
                'status' => 0,
                'message' => 'Already Exist!',
            ]);
        } else {
            $data = DB::table('hr_holidaytype')
                ->where('id', $holidaytypeid)
                ->where('deleted', 0)
                ->update([
                    'description' => $description,
                    'ifwork' => $ifwork,
                    'ifnotwork' => $ifnotwork,
                    'restdayifwork' => $restdayifwork,
                    'restdayifnotwork' => $restdayifnotwork,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);

            return array((object)[
                'status' => 1,
                'message' => 'Updated Successfully!',
            ]);
        }
        
    }

    // Load all Holiday
    public static function loadallholiday(Request $request){
        $data = DB::table('school_calendar')
            ->select('school_calendar.*', 'schoolcaltype.typename')
            ->leftJoin('schoolcaltype', 'school_calendar.holiday', '=', 'schoolcaltype.id')
            ->where('schoolcaltype.type', 1)
            ->where('school_calendar.deleted', 0)
            ->get();

        return $data;
    }

    // Check Unchecked Checkbox
    public static function statuswithpay(Request $request){
        $holidayid = $request->get('holidayid');
        $withpay = $request->get('withpay');

        $data = DB::table('school_calendar')
            ->where('id', $holidayid)
            ->where('deleted', 0)
            ->update([
                'withpay' => $withpay
            ]);
        
        return array((object)[
            'status' => 1,
            'message' => 'With Pay Status Updated Successfully!',
        ]);
    }
    

    
}
