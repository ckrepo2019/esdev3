<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Hash;
use Auth;
use Session;

class SuperAdminController extends \App\Http\Controllers\Controller
{
 
      public static function updatemodulestatus(Request $request){
            try{
                  $module = $request->get('module');
                  $status = $request->get('status');
                  DB::table('schoolinfo')
                        ->update([
                              $module => $status
                        ]);
                  return array((object)[
                        'status'=>1,
                        'message'=>'Updated Successfully!'
                  ]);    
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function store_error($e){
            DB::table('zerrorlogs')
                  ->insert([
                        'error'=>$e,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'message'=>'Something went wrong!'
            ]);
      }

      public function viewschoolinfo(){

            $schoolinfo = DB::table('schoolinfo')
                              ->first();
                              
            $admin_pass = DB::table('users')
                              ->whereIn('type',[6,12])
                              ->where('deleted',0)
                              ->get();
            $databaseName = \DB::connection()->getDatabaseName();

            return view('superadmin.pages.schoolinfo.viewschoolinfo')
                        ->with('schoolinfo',$schoolinfo)
                        ->with('admin_pass',$admin_pass)
                        ->with('databasename',$databaseName);
      }

      public function changeUser($id){
            if (!Auth::check()) {
                  Auth::logout();
                  Session::flush();
                  return redirect('/login');
            }
            if(auth()->user()->type == 17 ){
                  Auth::loginUsingId($id);
                  $userInfo = DB::table('users')->where('id',$id)->first();
                  Session::put('currentPortal',$userInfo->type);
                  Session::put('imSuperAdmin',true);
                  \App\Http\Controllers\Auth\LoginController::checkUserType();
                  return redirect('home');
            }
            else if(Session::get('imSuperAdmin')){
                  Auth::loginUsingId($id);
                  $userInfo = DB::table('users')->where('id',$id)->first();
                  Session::put('currentPortal',$userInfo->type);
                  \App\Http\Controllers\Auth\LoginController::checkUserType();
                  return redirect('home');
            }
            else{
                  return  back();
            }
      }


      public static function generate_hash(){
            $lowcaps = 'abcdefghijklmnopqrstuvwxyz';
            $permitted_chars = '0123456789'.$lowcaps;
            $input_length = strlen($permitted_chars);
            $random_string = '';
            for($i = 0; $i < 10; $i++) {
                  $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
                  $random_string .= $random_character;
            }
            $hashed = Hash::make($random_string);
            return array($random_string,$hashed);
      }

      public function generateAdminAdminPass(){

            do{
                  $generated = self::generate_hash();
                  $hashed =  $generated[1];
                  $random_string =  $generated[0];
            }while(strpos($hashed,'/') || strpos($hashed,'\'') );

            DB::table('users')->updateOrInsert([
                        'name'=>'adminadmin',
                        'email'=>'adminadmin',
                        'type'=>'12',
                  ],
                  [
                        'password'=>$hashed,
                        'passwordstr'=>$random_string
                  ]);

            $data = array((object)[
                  'code'=>$random_string,
                  'hash'=>$hashed
            ]);

            return $data;
      }

      public function generateAdminPass(){

            do{
					$generated = self::generate_hash();
                  $hashed =  $generated[1];
                  $random_string =  $generated[0];
            }while(strpos($hashed,'/') || strpos($hashed,'\'') );
                  DB::table('users')
                        ->updateOrInsert(
                              [
                                    'name'=>'admin',
                                    'email'=>'admin',
                                    'type'=>'6',
                              ],
                              [
                                    'password'=>$hashed,
                                    'passwordstr'=>$random_string
                              ]
                        );

            $data = array((object)[
                  'code'=>$random_string,
                  'hash'=>$hashed
            ]);

            return $data;
      }

      public static function validate_student_name(Request $request){
            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $check_studentinfo = DB::table('studinfo')->where('lastname',$lastname)->where('firstname',$firstname)->count();
            $check_prereg = DB::table('preregistration')->where('last_name',$lastname)->where('first_name',$firstname)->count();

            if($check_studentinfo > 0){
                  return array((object)[
                        'status'=>1,
                        'message'=>'*Student name information already exist. Please contact the school registrar.'
                  ]);
            }
            if($check_prereg > 0){
                  return array((object)[
                        'status'=>1,
                        'message'=>'*Student name information already exist. Please contact the school registrar.'
                  ]);
            }
            return array((object)[
                        'status'=>0,
                        'message'=>'No duplication'
                  ]);
      }

}
