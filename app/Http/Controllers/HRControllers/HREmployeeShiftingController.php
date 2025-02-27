<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
class HREmployeeShiftingController extends Controller
{
    public static function shifting(Request $request){
        return view('hr.payroll.shifting_setup');
    }

    public static function load_all_shift(Request $request){
        $data = DB::table('employee_shift')
            ->where('deleted', 0)
            ->get();

        return $data;
    }

    public static function store_shift(Request $request){
        $description = $request->get('description');
        $timepicker_first_in = $request->get('timepicker_first_in');
        $timepicker_first_out = $request->get('timepicker_first_out');
        $timepicker_second_in = $request->get('timepicker_second_in');
        $timepicker_second_out = $request->get('timepicker_second_out');

        $explodeamin = explode(':', $timepicker_first_in);
                
        if($explodeamin[0] == '00')
        {
            $amin = null;
        }else{
            $amin = $timepicker_first_in;
        }

        $explodeamout = explode(':', $timepicker_first_out);
        if($explodeamout[0] == '00')
        {
            $amout = null;
        }else{
            $amout = $timepicker_first_out;
        }

        $explodepmin = explode(':', $timepicker_second_in);
        if($explodepmin[0] == '00')
        {
            $pmin = null;
        }else{
            $pmin = $timepicker_second_in;
        }

        $explodepmout = explode(':', $timepicker_second_out);
        if($explodepmout[0] == '00')
        {
            $pmout = null;
        }else{
            $pmout = $timepicker_second_out;
        }

        $ifexist = DB::table('employee_shift')
            ->where('description', $description)
            ->where('deleted', 0)
            ->count();

        if ($ifexist) {
            return array((object)[
                'status'=>0,
                'message'=>'Already Exist!',
            ]);
        } else {
            $data = DB::table('employee_shift')
            ->insert([
                'description' => $description,
                'first_in' => $amin,
                'first_out' => $amout, 
                'second_in' => $pmin, 
                'second_out' => $pmout,
                'createdby'   => auth()->user()->id,
                'created_at'  => date('Y-m-d H:i:s')
            ]);
        
            return array((object)[
                'status'=>1,
                'message'=>'Added Successfully!',
            ]);
        }
       
    }

    public static function edit_shift(Request $request){
        $shiftid = $request->get('shiftid');

        $data = DB::table('employee_shift')
            ->where('deleted', 0)
            ->where('id', $shiftid)
            ->first();

        return response()->json($data);
    }

    public static function update_shift(Request $request){
        $shiftid = $request->get('shiftid');
        $description = $request->get('description');
        $timepicker_first_in = $request->get('timepicker_first_in');
        $timepicker_first_out = $request->get('timepicker_first_out');
        $timepicker_second_in = $request->get('timepicker_second_in');
        $timepicker_second_out = $request->get('timepicker_second_out');

        
        $explodeamin = explode(':', $timepicker_first_in);
                
        if($explodeamin[0] == '00')
        {
            $amin = null;
        }else{
            $amin = $timepicker_first_in;
        }

        $explodeamout = explode(':', $timepicker_first_out);
        if($explodeamout[0] == '00')
        {
            $amout = null;
        }else{
            $amout = $timepicker_first_out;
        }

        $explodepmin = explode(':', $timepicker_second_in);
        if($explodepmin[0] == '00')
        {
            $pmin = null;
        }else{
            $pmin = $timepicker_second_in;
        }

        $explodepmout = explode(':', $timepicker_second_out);
        if($explodepmout[0] == '00')
        {
            $pmout = null;
        }else{
            $pmout = $timepicker_second_out;
        }

        // $ifexist = DB::table('employee_shift')
        //     ->where('description', $description)
        //     ->where('deleted', 0)
        //     ->count();

      
        $data = DB::table('employee_shift')
        ->where('id', $shiftid)
        ->update([
            'description' => $description,
            'first_in' => $amin,
            'first_out' => $amout, 
            'second_in' => $pmin, 
            'second_out' => $pmout,
            'updatedby'   => auth()->user()->id,
            'updated_at'  => date('Y-m-d H:i:s')
        ]);
    
        return array((object)[
            'status'=>1,
            'message'=>'Updated Successfully!',
        ]);
       
    }
    
    public static function delete_shift(Request $request){
        $shiftid = $request->get('shiftid');

        $data = DB::table('employee_shift')
            ->where('id', $shiftid)
            ->update([
                'deleted' => 1,
                'deletedby'   => auth()->user()->id,
                'deleted_at'  => date('Y-m-d H:i:s')
            ]);
        
        return array((object)[
            'status'=>1,
            'message'=>'Deleted Successfully!',
        ]);
    }
    
    

}
