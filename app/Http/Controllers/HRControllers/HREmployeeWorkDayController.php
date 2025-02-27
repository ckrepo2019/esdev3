<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
class HREmployeeWorkdayController extends Controller
{
    public static function workday(Request $request)
    {
        return view('hr.payroll.workday_setup');
    }

    public static function load_all_workday(Request $request)
    {
        $workdays = DB::table('hr_workdays')
            ->where('deleted', '0')
            ->get();

        return $workdays;
    }

    public static function store_workday(Request $request)
    {
        $schedule = $request->get('schedule');
        $workdesc = $request->get('description');
        $currentUserId = auth()->user()->id;
        $currentDatetime = date('Y-m-d H:i:s');

        // Check if description already exists in the hr_workdays table
        $existingDescription = DB::table('hr_workdays')
            ->where('description', $workdesc)
            ->exists();

        // If description already exists, return a toast notification
        if ($existingDescription) {
            return response()->json([
                'status' => '0',
                'message' => 'Workday description already exists.',
                'toast' => true,
            ]);
        }

        // Base data to be inserted (createdby and createddatetime)
        $data = [
            'createdby' => $currentUserId,
            'createddatetime' => $currentDatetime,
        ];

        // Loop through each day and its schedule
        foreach ($schedule as $day => $sched) {
            // Set the description and day-specific data
            $data['description'] = $workdesc;
            $data[$day] = 1;
            $data[$day . '_amin'] = $sched['amin'];
            $data[$day . '_amout'] = $sched['amout'];
            $data[$day . '_pmin'] = $sched['pmin'];
            $data[$day . '_pmout'] = $sched['pmout'];
            $data[$day] = $sched['dayType'];
        }

        // Insert the data into the database
        DB::table('hr_workdays')->insert($data);

        // Return a success response with a toast notification
        return response()->json([
            'status' => '1',
            'message' => 'Workday saved successfully.',
            'toast' => true,
        ]);
    }

    public static function edit_workday(Request $request)
    {
        $workday = DB::table('hr_workdays')
            ->where('id', $request->get('id'))
            ->where('deleted', '0')
            ->first();

        return response()->json($workday);
    }

    public static function update_workday(Request $request)
    {
        $schedule = $request->get('schedule');
        $workdesc = $request->get('description');
        $workdayid = $request->get('workdayid');
        $currentUserId = auth()->user()->id;
        $currentDatetime = date('Y-m-d H:i:s');

        // Check if description already exists in the hr_workdays table
        $existingDescription = DB::table('hr_workdays')
            ->where('description', $workdesc)
            ->where('id', '!=', $workdayid)
            ->exists();

        // If description already exists, return a toast notification
        if ($existingDescription) {
            return response()->json([
                'status' => '0',
                'message' => 'Workday description already exists.',
                'toast' => true,
            ]);
        }

        // Base data to be inserted (createdby and createddatetime)
        $data = [
            'updatedby' => $currentUserId,
            'updateddatetime' => $currentDatetime,
        ];

        // Loop through each day and its schedule
        foreach ($schedule as $day => $sched) {
            // Set the description and day-specific data
            $data['description'] = $workdesc;
            $data[$day] = 1;
            $data[$day . '_amin'] = $sched['amin'];
            $data[$day . '_amout'] = $sched['amout'];
            $data[$day . '_pmin'] = $sched['pmin'];
            $data[$day . '_pmout'] = $sched['pmout'];
            $data[$day] = $sched['dayType'];
        }

        // Insert the data into the database
        DB::table('hr_workdays')->where('id', $workdayid)->update($data);

        // Return a success response with a toast notification
        return response()->json([
            'status' => '1',
            'message' => 'Workday saved successfully.',
            'toast' => true,
        ]);
    }

    public static function delete_workday(Request $request)
    {
        $workdayid = $request->get('id');

        DB::table('hr_workdays')->where('id', $workdayid)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => date('Y-m-d H:i:s')
        ]);
        return response()->json([
            'status' => '1',
            'message' => 'Workday deleted successfully.',
            'toast' => true,
        ]);
    }


    public static function foremployee_store_workday(Request $request)
    {
        $schedule = $request->get('schedule');
        // $workdesc = $request->get('description');
        $employeeid = $request->get('employeeid');
        $currentUserId = auth()->user()->id;
        $currentDatetime = date('Y-m-d H:i:s');

        // Check if description already exists in the hr_workdays table
        $existingDescription = DB::table('employee_workdays')
            ->where('employeeid', $employeeid)
            ->exists();

        // If description already exists, return a toast notification
        if ($existingDescription) {
            return response()->json([
                'status' => '0',
                'message' => 'Employee Workday already exists.',
                'toast' => true,
            ]);
        }

        // Base data to be inserted (createdby and createddatetime)
        $data = [
            'createdby' => $currentUserId,
            'createddatetime' => $currentDatetime,
        ];

        // Loop through each day and its schedule
        foreach ($schedule as $day => $sched) {
            // Set the description and day-specific data
            $data['general_workdaysid'] = 1;
            $data['employeeid'] = $employeeid;
            $data[$day] = 1;
            $data[$day . '_amin'] = $sched['amin'];
            $data[$day . '_amout'] = $sched['amout'];
            $data[$day . '_pmin'] = $sched['pmin'];
            $data[$day . '_pmout'] = $sched['pmout'];
            $data[$day] = $sched['dayType'];
        }

        // Insert the data into the database
        DB::table('employee_workdays')->insert($data);

        // Return a success response with a toast notification
        return response()->json([
            'status' => '1',
            'message' => 'Workday saved successfully.',
            'toast' => true,
        ]);
    }

}
