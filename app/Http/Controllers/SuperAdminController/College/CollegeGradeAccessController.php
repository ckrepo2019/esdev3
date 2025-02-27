<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;

class CollegeGradeAccessController extends \App\Http\Controllers\Controller
{
      public static function list(Request $request){

            $users = DB::table('college_gradepriv')
                  ->join('teacher',function($join){
                        $join->on('college_gradepriv.teacherid','=','teacher.id');
                        $join->where('teacher.deleted',0);
                  })
                  ->where('college_gradepriv.deleted',0)
                  ->select(
                        'college_gradepriv.*',
                        DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as username"),
                        'tid'
                  )
                  ->get();

            return $users;

      }


      public static function removeuser(Request $request){

            try{

                  $id = $request->get('id');
               
                  DB::table('college_gradepriv')
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Deleted Successfully!'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong'
                  ]);
            }

      }

      public static function adduser(Request $request){

            try{
                  $user = $request->get('user');

                  $check = DB::table('college_gradepriv')
                              ->where('deleted',0)
                              ->where('teacherid',$user)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'User Already Exist'
                        ]);
                  }else{
                        DB::table('college_gradepriv')
                              ->insert([
                                    'canedit'=>1,
                                    'canpending'=>1,
                                    'canapprove'=>1,
                                    'canpost'=>1,
                                    'canunpost'=>1,
                                    'cansetupdeadline'=>1,
                                    'canprint'=>1,
                                    'teacherid'=>$user,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                              
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Added Successfully!'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong'
                  ]);
            }

      }

      public static function updatestatus(Request $request){

            $tid = $request->get('tid');
            $access = $request->get('access');
            $status = $request->get('status');

            try{

                  if($access == "all"){

                        DB::table('college_gradepriv')
                              ->where('teacherid',$tid)
                              ->where('deleted',0)
                              ->take(1)
                              ->update([
                                    'canapprove'=>1,
                                    'canedit'=>1,
                                    'canpending'=>1,
                                    'canpost'=>1,
                                    'canprint'=>1,
                                    'cansetupdeadline'=>1,
                                    'canunpost'=>1,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }else{

                        DB::table('college_gradepriv')
                              ->where('teacherid',$tid)
                              ->where('deleted',0)
                              ->take(1)
                              ->update([
                                    $access=>$status,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }



                  return array((object)[
                        'status'=>1,
                        'message'=>'Updated Successfully!'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong'
                  ]);
            }

      }

      
     
      

}
