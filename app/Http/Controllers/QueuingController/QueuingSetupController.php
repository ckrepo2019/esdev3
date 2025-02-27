<?php

namespace App\Http\Controllers\QueuingController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueuingSetupController extends Controller
{
    
    public function show(){

        // $department = DB::table('queuing_department')
        //     ->where('deleted', 0)
        //     ->select(

        //         'id',
        //         'departmentdesc as text'
        //     )
        //     ->get();


        $queuingsetup = DB::table('queuing_setup')
            ->where('deleted', 0)
            ->get();

        return view('superadmin.pages.queuing.queuing-setup',[

            // 'department' => $department,
            'queuingsetup' => $queuingsetup,
            
        ]);
        
    }

    public function setup_setactive(Request $request){
        
        if($request->isSetActiveStat == 1){

            DB::table('queuing_setup')
            ->where('deleted', 0)
            ->update([
                'isActivated' => 0,
            ]);

            DB::table('queuing_setup')
            ->where('id', $request->id)
            ->update([
                'isActivated' => $request->isSetActiveStat,
            ]);

            return array (
                (object)[
    
                'status'=>200,
                'statusCode'=>"success",
                'message'=>'Successfully Activated!'
            ]); 

        }else{

            DB::table('queuing_setup')
            ->where('id', $request->id)
            ->update([
                'isActivated' => $request->isSetActiveStat,
            ]); 

            return array (
                (object)[
    
                'status'=>200,
                'statusCode'=>"success",
                'message'=>'Successfully Deactivated!'
            ]);

        }

    }

    public function get_windows(Request $request){
   
        $windows = DB::table('queuing_window')
            ->where("departmentid", $request->id)
            ->where("que_setupid", 0)
            ->get();


        return $windows;
    }

    public function get_setup_windows(Request $request){
   
        $windows = DB::table('queuing_window')
            ->where("id", $request->id)
            ->where("deleted", 0)
            ->get();


        return $windows;
    }
    
    public function edit_window_label(Request $request){
   
        $windows = DB::table('queuing_window')
            ->where("id", $request->id)
            ->where("departmentid", $request->departmentid)
            ->where("que_setupid", 0)
            ->update([
                "windowdesc" => $request->window_label
            ]);


        return $windows;
    }

    public function assign_window_user(Request $request){
   
        $windows = DB::table('queuing_window')
            ->where("id", $request->id)
            ->where("departmentid", $request->departmentid)
            ->where("que_setupid", 0)
            ->update([
                "userid" => $request->user
            ]);


        return $windows;
    }

    public function edit_setup_window (Request $request){
   
        $windows = DB::table('queuing_window')
            ->where("id", $request->id)
            ->update([
                "windowdesc" => $request->windowlabel,
                "departmentid" => $request->department,
                "userid" => $request->userid
            ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Edit Successfully!'
        ]); 
    }

    public function delete_setup_window(Request $request){
   
        $windows = DB::table('queuing_window')
            ->where("id", $request->id)
            ->update([
                "deleted" => 1
            ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"warning",
            'message'=>'Window Deleted!'
        ]); 
    }

    public function delete_windows(Request $request){
   
        $windows = DB::table('queuing_window')
            ->where("departmentid", $request->departmentid)
            ->where("que_setupid", 0)
            ->where("id", $request->id)
            ->delete();
        
        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Removed!'
        ]); 
    }

    public function create_setup(Request $request){
   
        $checker = DB::table('queuing_setup')
            ->where("quedesc", $request->que_desc)
            ->first();

        $windowChecker = DB::table('queuing_window')
            ->where("que_setupid", 0)
            ->where("deleted", 0)
            ->get();

        if($checker != null){

            return array (
                (object)[
    
                'status'=>505,
                'statusCode'=>"error",
                'message'=>'Setup Name is already taken!'
            ]); 

        }else if(count($windowChecker) == 0){

            return array (
                (object)[
    
                'status'=>505,
                'statusCode'=>"error",
                'message'=>'Please include atleast 1 department!'

            ]); 
        }

        $que_setup_id = DB::table('queuing_setup')
        ->insertGetId(
           [
            "quedesc" => $request->que_desc,
           ]
        );

        DB::table('queuing_window')
            ->where("que_setupid", 0)
            ->where("deleted", 0)
            ->update([
                'que_setupid' => $que_setup_id,
            ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Created Setup!'
        ]); 
        
    }

    public function delete_queuing_setup(Request $request){
   
        $checker = DB::table('queuing_setup')
            ->where("id", $request->id)
            ->first();

        if($checker->isActivated == 1){

            return array (
                (object)[
    
                'status'=>400,
                'statusCode'=>"error",
                'message'=>'Failed to Delete. Setup is currently active!'
            ]); 

        }

        DB::table('queuing_setup')
            ->where("id", $request->id)
            ->update([
                'deleted' => 1,
            ]);

        DB::table('queuing_window')
            ->where("que_setupid", $request->id)
            ->update([
                'deleted' => 1,
            ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Deleted Setup!'
        ]); 
        
    }

    public function edit_queuing_setup(Request $request){

        DB::table('queuing_setup')
        ->where('id', $request->id)
        ->update([
            'quedesc' => $request->value,
    
        ]);

    }

    public function add_new_window(Request $request){

        $checker = DB::table('queuing_window')
            ->where('que_setupid', $request->que_setup)
            ->where('departmentid', $request->departmentid)
            ->where('windowdesc', $request->windowlabel)
            ->where('deleted', 0)
            ->first();
        if($checker != null){

            return array (
                (object)[
    
                'status'=>404,
                'statusCode'=>"error",
                'message'=>'Window Label already exist!'
            ]); 
        }

        DB::table('queuing_window')
            ->insert(
            [
            "que_setupid" => $request->que_setup, 
            "departmentid" => $request->departmentid, 
            "windowdesc" => $request->windowlabel,
            "userid" => $request->user,
            ]
        );

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Added Window!'
        ]); 

    }


    // public function create_department(Request $request){

    //     $checker = DB::table('queuing_department')
    //         ->where("departmentdesc", $request->depart_desc)
    //         ->first();

    //     if($checker != null){

    //         return array (
    //             (object)[
    
    //             'status'=>505,
    //             'statusCode'=>"error",
    //             'message'=>'Setup Name is already taken!'
    //         ]); 
    //     }

        
    //     DB::table('queuing_department')
    //         ->insertGetId(
    //            [
    //             "departmentdesc" => $request->depart_desc,
    //            ]
    //         );


        
    //     return array (
    //         (object)[

    //         'status'=>200,
    //         'statusCode'=>"success",
    //         'message'=>'Succesfully Created Department!'
    //     ]); 
        
    // }

    public function get_department(Request $request){

        $department =  DB::table('usertype')

            ->where('deleted', 0)
            ->where('constant', 1)
            ->select(
                'id',
                'utype as text'
            )
            ->get();

        
        return $department; 
        
    }

    public function create_window(Request $request){

        // for ($i=0; $i < count($request->formData); $i++) { 
            
        //     DB::table('queuing_window')
        //         ->insert(
        //             [
        //             "que_setupid" => 0, 
        //             "departmentid" => $request->departmentid, 
        //             "windowdesc" => $request->formData[$i]['value'],
        //             ]
        //     );

        // }

        DB::table('queuing_window')
                ->insert(
                    [
                    "que_setupid" => 0, 
                    "departmentid" => $request->departmentid, 
                    "windowdesc" => " ",
                    ]
            );

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Included!'
        ]); 
    }
    
    public function get_included_window(Request $request){

        $included = DB::table('queuing_window')
            ->join('usertype', 'queuing_window.departmentid', '=', 'usertype.id')
            ->where("queuing_window.que_setupid", 0)
            ->where("queuing_window.deleted", 0)
            ->select('queuing_window.*', 'usertype.utype')
            ->get();
            
        return $included; 
    }
    
    public function included_window_revert(Request $request){

        DB::table('queuing_window')
        ->where("que_setupid", 0)
        ->where("deleted", 0)
        ->delete();

    }
    
    public function get_queuingsetup(Request $request){

        $queuing_setup = DB::table('queuing_setup')
            ->where("deleted", 0)
            ->get();
            
        return $queuing_setup; 
    }

    public function get_queuingsetup_data(Request $request){

        $setupwindow = DB::table('queuing_window')
        ->join('usertype', 'queuing_window.departmentid', '=', 'usertype.id')
            ->where("queuing_window.que_setupid", $request->id)
            ->where("queuing_window.deleted", 0)
            ->select('queuing_window.*', 'usertype.utype')
            ->get();
            
        return $setupwindow; 
    }

    public function get_users(Request $request){

        $users = DB::table('users')
            ->where("type", $request->usertype)
            ->where("deleted", 0)
            ->select(
                'id',
                'name as text'
            )
            ->get();
            
        return $users; 
    }

    
}
