<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Hash;

class FixerCredsController extends \App\Http\Controllers\Controller
{


      public static function send_credentials(Request $request){
            
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $info = self::student_list($syid,$studid,null,$semid);
            $student_count = 0;
            $parent_count = 0;

            if(count($info) == 0){
                  array_push($temp_students, (object)[
                        'message'=>'Student no found.',
                        'status'=>0
                  ]);
            }

            $schoolinfo = DB::table('schoolinfo')->first();

            $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYY HH:mm');

            //student send
            if(count($info[0]->student_credentials) == 1){


//do not touch

$message = strtoupper($schoolinfo->abbreviation).' Message:

Portal Credentials:

Username: '.$info[0]->student_credentials[0]->email.
'
Password: '.$info[0]->student_credentials[0]->passwordstr.'

'.$date;

//do not touch

                  if($info[0]->contactno != null && strlen($info[0]->contactno) == 11){

                        $contactno = '+63' . substr($info[0]->contactno, 1);

                        DB::table('smsbunkertextblast')
                              ->insert([
                              'message'=> $message,
                              'receiver'=>$contactno,
                              'smsstatus'=>0,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'studid'=>$studid,
                              'receivertype'=>1
                              ]);

                        $student_count += 1;

                  }

            }

            //send parent
            if(count($info[0]->parent_credentials) == 1){

                  $with_parent_contact = false;

                  if($info[0]->ismothernum == 1){
                        if($info[0]->mcontactno != null && strlen($info[0]->mcontactno) == 11){
                              $contactno = '+63' . substr($info[0]->mcontactno, 1);
                              $with_parent_contact = true;
                        }
                  }elseif($info[0]->isfathernum == 1){
                        if($info[0]->fcontactno != null && strlen($info[0]->fcontactno) == 11){
                              $contactno = '+63' . substr($info[0]->fcontactno, 1);
                              $with_parent_contact = true;
                        }
                  }elseif($info[0]->isguardannum == 1){
                        if($info[0]->gcontactno != null && strlen($info[0]->gcontactno) == 11){
                              $contactno = '+63' . substr($info[0]->gcontactno, 1);
                              $with_parent_contact = true;
                        }
                  }

//do not touch

$message = strtoupper($schoolinfo->abbreviation).' Message:

Portal Credentials:

Username: '.$info[0]->parent_credentials[0]->email.
'
Password: '.$info[0]->parent_credentials[0]->passwordstr.'

'.$date;

//do not touch

                  if($info[0]->levelid < 17){
                        if($with_parent_contact){

                              DB::table('smsbunkertextblast')
                                    ->insert([
                                    'message'=> $message,
                                    'receiver'=>$contactno,
                                    'smsstatus'=>0,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'studid'=>$studid,
                                    'receivertype'=>2
                                    ]);

                              $parent_count += 1;

                        }
                  }
              

            }
            

            return array((object)[
                  'student_count'=>$student_count,
                  'parent_count'=>$parent_count
            ]);

            //student parent

           
          

      }

      public static function student_list_ajax(Request $request){
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $semid = $request->get('semid');
            return self::student_list($syid,null,$levelid,$semid);
      }

      public static function student_list($syid = null, $studid = null, $levelid = null, $semid = null){

            $temp_students = array();

            $students =  DB::table('enrolledstud');

            if($studid != null){
                  $students = $students->where('enrolledstud.studid',$studid);
            }
            if($levelid != null){
                  $students = $students->where('enrolledstud.levelid',$levelid);
            }

            $students =  $students->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.deleted',0)
                              ->whereIn('enrolledstud.studstatus',[1,2,3])
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->select(
                                    'studinfo.id',
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.middlename',
                                    'suffix',
                                    'contactno',
                                    'mcontactno',
                                    'fcontactno',
                                    'gcontactno',
                                    'ismothernum',
                                    'isfathernum',
                                    'isguardannum',
                                    'mothername',
                                    'fathername',
                                    'guardianname',
                                    'enrolledstud.levelid',
                                    'sid'
                              )
                              ->get();

            foreach($students as $item){
                  array_push($temp_students, $item);
            }

            $students =  DB::table('sh_enrolledstud');

            if($studid != null){
                  $students = $students->where('sh_enrolledstud.studid',$studid);
            }
            if($levelid != null){
                  $students = $students->where('sh_enrolledstud.levelid',$levelid);
            }
            if($semid != null){
                  $students = $students->where('sh_enrolledstud.semid',$semid);
            }

            $students =  $students->where('sh_enrolledstud.deleted',0)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,3])
                              ->join('studinfo',function($join){
                                    $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->select(
                                    'studinfo.id',
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.middlename',
                                    'suffix',
                                    'contactno',
                                    'mcontactno',
                                    'fcontactno',
                                    'gcontactno',
                                    'ismothernum',
                                    'isfathernum',
                                    'isguardannum',
                                    'mothername',
                                    'fathername',
                                    'guardianname',
                                    'sh_enrolledstud.levelid',
                                    'sid'
                              )
                              ->get();

            foreach($students as $item){
                  array_push($temp_students, $item);
            }
            
            $students =  DB::table('college_enrolledstud');

            if($studid != null){
                  $students = $students->where('college_enrolledstud.studid',$studid);
            }
            if($levelid != null){
                  $students = $students->where('college_enrolledstud.yearLevel',$levelid);
            }
            if($semid != null){
                  $students = $students->where('college_enrolledstud.semid',$semid);
            }

            $students =  $students->where('college_enrolledstud.deleted',0)
                              ->where('college_enrolledstud.syid',$syid)
                              ->whereIn('college_enrolledstud.studstatus',[1,2,3])
                              ->join('studinfo',function($join){
                                    $join->on('studinfo.id','=','college_enrolledstud.studid');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->select(
                                    'studinfo.id',
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.middlename',
                                    'suffix',
                                    'contactno',
                                    'mcontactno',
                                    'fcontactno',
                                    'gcontactno',
                                    'ismothernum',
                                    'isfathernum',
                                    'isguardannum',
                                    'mothername',
                                    'fathername',
                                    'guardianname',
                                    'college_enrolledstud.yearLevel as levelid',
                                    'sid'
                              )
                              ->get();

          
            foreach($students as $item){
                  array_push($temp_students, $item);
            }

            $unmatched_parent_account = array();
            $multiple_parent_account = array();
            $no_parent_account = array();
            $no_parent_password = array();

            $unmatched_student_account = array();
            $multiple_student_account = array();
            $no_student_account = array();
            $no_student_password = array();

            if(collect($temp_students)->count() == 0){
                  return $temp_students;
            }

            if($levelid != null){
                  $temp_students = collect($temp_students)->where('levelid',$levelid)->values()->toArray();
            }

            
           

            foreach($temp_students as $item){

                  $student_email = array();
                  array_push($student_email,'S'.$item->sid);
                  array_push($student_email,'P'.$item->sid);

                  $users = DB::table('users')
                                    ->whereIn('email',$student_email)
                                    ->select('email','passwordstr','id')
                                    ->where('deleted',0)
                                    ->get();

                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';

                  if($middlename != null){
                      foreach ($middlename as $middlename_item) {
                          if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                          } 
                      }
                  }

                  $item->student = $item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;

                  $parent_creds = collect($users)->where('email','P'.$item->sid)->values();
                  $student_creds = collect($users)->where('email','S'.$item->sid)->values();

                  $pstatus = '';
                  $sstatus = '';

                  $item->parent_credentials = $parent_creds;
                  $item->student_credentials = $student_creds;

                  if(count($parent_creds) > 1){
                        array_push($multiple_parent_account,$item);
                  }
                  if(count($student_creds) > 1){
                        array_push($multiple_student_account,$item);
                  }

                  if(count($parent_creds) == 0){
                        array_push($no_parent_account,$item);
                  }
                  if(count($student_creds) == 0){
                        array_push($no_student_account,$item);
                  }

                  if(count($parent_creds) == 1){
                        if($parent_creds[0]->passwordstr == null){
                              $item->userid = $parent_creds[0]->id;
                              array_push($no_parent_password,$item);
                        }
                        if($parent_creds[0]->email != 'P'.$item->sid){
                              $item->userid = $parent_creds[0]->id;
                              array_push($unmatched_parent_account,$item);
                        }
                  }
                  if(count($student_creds) == 1){
                        if($student_creds[0]->passwordstr == null){
                              $item->userid = $student_creds[0]->id;
                              array_push($no_student_password,$item);
                        }
                        if($student_creds[0]->email != 'S'.$item->sid){
                              $item->userid = $parent_creds[0]->id;
                              array_push($unmatched_student_account,$item);
                        }
                  }

                  $item->search = $item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle.' '.$item->sid;
                  
                  $users = collect($users)->where('email','!=','S'.$item->sid)->values();
                  $users = collect($users)->where('email','!=','P'.$item->sid)->values();

            }

            $status = array((object)[
                  'unmatched_parent_account' => $unmatched_parent_account,
                  'multiple_parent_account' => $multiple_parent_account,
                  'no_parent_account' => $no_parent_account,
                  'no_parent_password' => $no_parent_password,
                  'unmatched_student_account' => $unmatched_student_account,
                  'multiple_student_account' => $multiple_student_account,
                  'no_student_account' => $no_student_account,
                  'no_student_password' => $no_student_password
            ]);

          
            if($studid == null ){
                  array_push($temp_students, (object)[
                        'student'=>'status',
                        'status'=>$status
                  ]);
            }

           

           return $temp_students;

      }

      public static function generate_student_account(Request $request){

            $sid = $request->get('sid');
            $student = $request->get('student');
            $studid = $request->get('id');

            try{

                  $check = DB::table('users')
                              ->where('deleted',0)
                              ->where('email','S'.$sid)
                              ->count();

                  if($check > 0 ){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Account Already Generated'
                        ]);
                  }

                  $userid = db::table('users')
                              ->insertGetId([
                                    'name' => $student,
                                    'email' => 'S'.$sid,
                                    'type' => 7,
                                    'password' => Hash::make('123456')
                              ]);

                  $studpword = self::generatepassword($userid);

                  self::update_studinfo($studid, $userid);

                  return array((object)[
                              'data'=>(object)[
                                    'id'=>$userid,
                                    'email'=>'S'.$sid,
                                    'passwordstr'=>$studpword->code
                              ],
                              'status' => 1,
                              'message' => 'Created Successfully'
                        ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status' => 0,
                        'message' => 'Something went wrong!'
                  ]);
            }

      }

      public static function generate_parent_account(Request $request){

            $sid = $request->get('sid');


            $ismothernum = $request->get('ismothernum');
            $isathernum = $request->get('isfathernum');
            $isguardiannum = $request->get('isguardannum');

            $mothername = $request->get('mothername');
            $fathername = $request->get('fathername');
            $guardianname = $request->get('guardianname');

            $name = $sid;

            if($ismothernum == 1){
                  if($mothername != null){
                        $name = $mothername;
                  }
            }elseif($isathernum == 1){
                  if($fathername != null){
                        $name = $fathername;
                  }
               
            }elseif($isguardiannum == 1){
                  if($guardianname != null){
                        $name = $guardianname;
                  }
            }

            try{


                  $check = DB::table('users')
                              ->where('email','P'.$sid)
                              ->where('deleted',0)
                              ->count();


                  if($check == 0){

                        $userid = db::table('users')
                                          ->insertGetId([
                                                'name' => $name,
                                                'email' => 'P'.$sid,
                                                'type' => 9,
                                                'password' => Hash::make('123456')
                                          ]);

                        $studpword = self::generatepassword($userid);

                        return array((object)[
                              'data'=>(object)[
                                    'id'=>$userid,
                                    'email'=>'P'.$sid,
                                    'passwordstr'=>$studpword->code
                              ],
                              'status' => 1,
                              'message' => 'Created Successfully'
                        ]);

                  }else{

                        return array((object)[
                              'status' => 0,
                              'message' => 'Already Exist'
                        ]);

                  }

               
            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status' => 0,
                        'message' => 'Something went wrong!'
                  ]);

            }

      }

      public static function update_password(Request $request){
            try{

                  $userid = $request->get('userid');

                  self::generatepassword($userid);

                  return array((object)[
                        'status' => 1,
                        'message' => 'Updated Successfully'
                  ]);
            
            }catch(\Exception $e){
                  return array((object)[
                        'status' => 0,
                        'message' => 'Something went wrong!'
                  ]);
            }
      }
      

      public static function remove_duplicate_student(Request $request){
            try{

                  $sid = $request->get('sid');
                  $id = $request->get('id');

                  $student_userid = DB::table('studinfo')
                                          ->where('id',$id)
                                          ->where('deleted',0)
                                          ->select('userid')
                                          ->first();

                  Db::table('users')
                              ->where('deleted',0)
                              ->where('id','!=',$student_userid->userid)
                              ->where('email','like','%S'.$sid.'%')
                              ->update([
                                    'deleted'=>1
                              ]);

                  return array((object)[
                        'status' => 1,
                        'message' => 'Deleted Successfully'
                  ]);
            
            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status' => 0,
                        'message' => 'Something went wrong!'
                  ]);
            }
      }

      public static function remove_duplicate_parent(Request $request){
            try{

                  $sid = $request->get('sid');

                  $userid = Db::table('users')
                              ->where('deleted',0)
                              ->where('email','like','%P'.$sid.'%')
                              ->select('id')
                              ->first();

                  

                  Db::table('users')
                              ->where('deleted',0)
                              ->where('id','!=',$userid->id)
                              ->where('email','like','%P'.$sid.'%')
                              ->update([
                                    'deleted'=>1
                              ]);

                  return array((object)[
                        'status' => 1,
                        'message' => 'Deleted Successfully'
                  ]);
            
            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status' => 0,
                        'message' => 'Something went wrong!'
                  ]);
            }
      }



      


      public static function generatepassword($userid)
      {
          
          $lowcaps = 'abcdefghijklmnopqrstuvwxyz';
          $permitted_chars = '0123456789'.$lowcaps;
          $input_length = strlen($permitted_chars);
  
          $random_string = '';
          for($i = 0; $i < 10; $i++) {
              $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
              $random_string .= $random_character;
          }
  
          $checkifexists = DB::table('users')
              ->where('passwordstr','like','%'.$random_string.'%')
              ->first();
  
          if($checkifexists)
          {
              self::generatepassword($userid);
  
          }else{
  
              $hashed = Hash::make($random_string);
              $data = (object)[
                  'code'=>$random_string,
                  'hash'=>$hashed
              ];
  
              DB::enableQueryLog();
              DB::table('users')
                  ->where('id', $userid)
                  ->update([
                      'passwordstr'   => $random_string,
                      'password'      => $hashed
                  ]);
              DB::disableQueryLog();
              $logs = json_encode(DB::getQueryLog());
              
              DB::table('updatelogs')
                  ->insert([
                      'type'=>1,
                      'sql'=> $logs,
                      'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);
          }
          
          return $data;
      }

      
      public static function update_studinfo($studid = null, $studuser = null){

            DB::enableQueryLog();
                  $putUserid = db::table('studinfo')
                        ->where('id', $studid)
                        ->take(1)
                        ->update([
                              'userid' => $studuser,
                              'updateddatetime' => \App\RegistrarModel::getServerDateTime()
                        ]);
            DB::disableQueryLog();
            
            $logs = json_encode(DB::getQueryLog());

            DB::table('updatelogs')
                  ->insert([
                        'type'=>1,
                        'sql'=> $logs,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

      }


      

}
