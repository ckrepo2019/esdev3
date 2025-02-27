<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;


class DebuggerController extends Controller
{
    public function studentUserDebugger(){

        $students = DB::table('studinfo')
                    ->where('studstatus','1')
                    ->get();

        $studentsWithConflict = self::getStudentWithConflict($students);

        return view('adminPortal.pages.debugger.studentaccount')
                ->with('studentsWithConflict',$studentsWithConflict)
                ->with('numberofStudents',count($students));

    }

    public static function getStudentWithConflict($students){

        $studentsWithConflict = array();

        foreach($students as $item){

            $withConflict = false;

            $conflicts = (object)[
                                'id'=>$item->id,
                                'userid'=>$item->userid,
                                'studentid'=>$item->sid,
                                'studentname'=>$item->firstname.' '.$item->lastname,
                                'NPA'=>false,
                                'DPA'=>false,
                                'NSA'=>false,
                                'DSA'=>false];

            $parentAccountExist = DB::table('users')
                                    ->where('email','P'.$item->sid)
                                    ->where('deleted','0')
                                    ->count();

            $numOfAccountCount = DB::table('users')
                                        ->where('email','S'.$item->sid)
                                        ->where('deleted','0')
                                        ->count();


            if($numOfAccountCount>1){

                $withConflict = true;
                $conflicts->DSA = true;

            }

            if($numOfAccountCount==0){

                $withConflict = true;
                $conflicts->NSA = true;

            }

            if($parentAccountExist>1){

                $withConflict = true;
                $conflicts->DPA = true;
        

            }

            if($parentAccountExist==0){

                $withConflict = true;
                $conflicts->NPA = true;

            }

            if($withConflict){
                array_push($studentsWithConflict,$conflicts);
            }

        }

        return $studentsWithConflict;

    }

    public static function fixAccountConflict(){

        $students = DB::table('studinfo')
                        ->leftJoin('users',function($join){
                            $join->on( 'studinfo.userid','=','users.id');
                            $join->where('studinfo.deleted','0');
                        })
                        ->select('studinfo.*','users.email')
                        ->get();

        

        $studentsWithConflict = self::getStudentWithConflict($students);

        foreach($studentsWithConflict as $item){

            if($item->NSA){

                $userId = DB::table('users')
                    ->insertGetId([
                        'name'=>$item->studentname,
                        'email'=>'S'.$item->studentid,
                        'password'=>Hash::make('123456'),
                        'type'=>'7',
                        'deleted'=>'0'
                    ]);

                DB::table('studinfo')->where('id',$item->id)->update(['userid'=>$userId]);

            }
            if($item->NPA){

                $userId = DB::table('users')
                    ->insertGetId([
                        'name'=>$item->studentname,
                        'email'=>'P'.$item->studentid,
                        'password'=>Hash::make('123456'),
                        'type'=>'9',
                        'deleted'=>'0'
                    ]);

            }
            if($item->DSA){

                $duplicates = DB::table('users')
                        ->where('email','S'.$item->studentid)
                        ->where('deleted','0')
                        ->get();

                foreach($duplicates as $dupitem){

                    if($dupitem->id != $item->userid){
                        DB::table('users')
                            ->where('id',$dupitem->id)
                            ->update(['deleted'=>'1']);
                    }
                    
                }

            }

            if($item->DSA){

                $duplicates = DB::table('users')
                                ->where('email','P'.$item->studentid)
                                ->where('deleted','0')
                                ->get();

                $firstaccount = true;

                foreach($duplicates as $dupitem){

                    if(!$firstaccount){
                        
                        DB::table('users')
                            ->where('id',$dupitem->id)
                            ->update(['deleted'=>'1']);
                        
                    }

                    $firstaccount = false;
                    
                }

            }

        }

        return back();

    }
}
