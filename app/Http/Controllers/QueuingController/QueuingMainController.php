<?php

namespace App\Http\Controllers\QueuingController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class QueuingMainController extends Controller
{

    public function on_load(){
        $department = auth()->user()->type;
        $assigned_windowid = 0;
        $setupid = 0;
        $isQueuingAvailable = false;

        $que_setupid = DB::table('queuing_setup')
            ->where('isActivated', 1)
            ->where('deleted', 0)
            ->first();

        if($que_setupid == null){
             
            return array (
                (object)[
                    
                'status'=>401,
                'code'=>'warning',
                'message'=>'No Queuing Setup Available!',
   
            ]);
            
        }else {

            $setupid = $que_setupid->id;
        }


        $departments = DB::table('queuing_window')
            ->where('que_setupid', $setupid)
            ->where('departmentid', $department)
            ->where('deleted', 0)
            ->first();
        
        

        if($departments != null){

            $isQueuingAvailable = true;

        }else{

            $isQueuingAvailable = false;
        }


        $windows = DB::table('queuing_window')
            ->select(
                'id',
                'windowdesc as text'
            )
            ->where('que_setupid', $setupid)
            ->where('departmentid', $department)
            ->where('deleted', 0)
            ->whereIn('userid', [auth()->user()->id, 0])
            ->get();

        $assigned_window = DB::table('queuing_window')
            ->where('que_setupid', $que_setupid->id)
            ->where('departmentid', $department)
            ->where('deleted', 0)
            ->where('userid', auth()->user()->id)
            ->first();

        if($assigned_window != null){

            $assigned_windowid = $assigned_window->id;

        }else {
            
            $assigned_windowid = 0;
        }

        return array (
            (object)[

            'status'=>200,
            'department'=> $department,
            'windows'=> $windows,
            'assigned_windowid'=>$assigned_windowid,
            'isQueuingAvailable'=>$isQueuingAvailable,
        ]);
    }
    
    public function get_current_serving(Request $request){

        $departmentid = auth()->user()->type;

        $currentserving = DB::table('queuing_transaction')
            ->where('departmentid', $departmentid)
            ->where('window_number', $request->window_number)
            ->where('isDone', 0)
            ->where('deleted', 0)
            ->get();  

        return $currentserving;
    }

    public function get_next_que(Request $request){

        $departmentid = auth()->user()->type;

        $nextque = DB::table('queuing_transaction')
            ->where("departmentid", $departmentid)
            ->where("window_number", 0)
            ->where("isDone", 0)
            ->where("deleted", 0)
            ->first();


        if($nextque == null && $request->doneQue == null){

            return array (
                (object)[

                'status'=>401,
                'statusCode'=>"error",
                'message'=>'Queue is empty!'
            ]); 

        }else if($nextque == null && $request->doneQue != null){

            DB::table('queuing_transaction')
            ->where('que_number', $request->doneQue)
            ->update([
                'isDone' => $request->status
            ]);

            return array (
                (object)[

                'status'=>402,
                'lastquenumber'=>$request->doneQue
            ]); 
            
        }else{

            $windows = DB::table('queuing_window')
                ->where("id", $request->window_number)
                ->where("deleted", 0)
                ->first();

            DB::table('queuing_transaction')
                ->where('que_number', $request->doneQue)
                ->update([
                    'isDone' => $request->status
                ]);

            DB::table('queuing_transaction')
                ->where('que_number', $nextque->que_number)
                ->update([
                    'window_number' => $windows->id,
                ]);


            return array (
                (object)[

                'status'=>200,
                'data'=> $nextque,
                'window'=> $windows->windowdesc,
            ]);
        }   
    }

    public function get_waitlist(Request $request){

        $departmentid = auth()->user()->type;

        $waitlist = DB::table('queuing_transaction')
            ->where("departmentid", $departmentid)
            ->where("window_number", $request->windowid)
            ->where("isDone", 2)
            ->where("deleted", 0)
            ->get();

        return $waitlist;
    }

    public function waitlist_markdone(Request $request){

        $departmentid = auth()->user()->type;

        DB::table('queuing_transaction')
        ->where('que_number', $request->que_number)
        ->update([
            'isDone' => 1,
        ]);

        $waitlist = DB::table('queuing_transaction')
            ->where("departmentid", $departmentid)
            ->where("window_number", $request->windowid)
            ->where("isDone", 2)
            ->where("deleted", 0)
            ->get();

        return $waitlist;
    }

    public function annouce_next_que(Request $request){

        $departmentid = auth()->user()->type;

        $currentque = DB::table('queuing_transaction')
            ->where("departmentid", $departmentid)
            ->where("window_number", $request->window_number)
            ->where("isDone", 0)
            ->where("deleted", 0)
            ->first();

        if($currentque == null){

            return array (
                (object)[

                'status'=>401,
                'statusCode'=>"warning",
                'message'=>'Currently not serving!'
            ]); 
        }

        $windows = DB::table('queuing_window')
            ->where("id", $request->window_number)
            ->where("deleted", 0)
            ->first();

        return array (
            (object)[

            'status'=>200,
            'data'=> $currentque,
            'window'=> $windows->windowdesc,
        ]);
    }

    public function assign_window(Request $request){

        $checker = DB::table('queuing_window')
            ->where('userid', auth()->user()->id)
            ->first();

        if($checker != null){

            DB::table('queuing_window')
            ->where('id', $checker->id)
            ->where('deleted', 0)
            ->update([
                'userid' => 0,
            ]);
            
            DB::table('queuing_window')
            ->where('id', $request->windowid)
            ->update([
                'userid' => auth()->user()->id,
            ]);

        }else{

            DB::table('queuing_window')
            ->where('id', $request->windowid)
            ->update([
                'userid' => auth()->user()->id,
            ]);

        }

        if($request->windowid == 0){

            DB::table('queuing_window')
            ->where('userid', $request->windowid)
            ->update([
                'userid' => 0,
            ]);

            return array (
                (object)[
    
                'status'=>200,
                'statusCode'=>"warning",
                'message'=>'Window Number is set to Unassignned',
                'window'=>0
            ]); 

        } else{

            $updatedwindow = DB::table('queuing_window')
            ->where('id', $request->windowid)
            ->where('deleted', 0)
            ->select([
                'id',
                'windowdesc'
            ])
            ->first();
        }

        

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Window Number assigned to '.$updatedwindow->windowdesc,
            'window'=>$updatedwindow->id

        ]); 
    }
    
}
