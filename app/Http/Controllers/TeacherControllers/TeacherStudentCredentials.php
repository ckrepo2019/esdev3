<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use Session;

class TeacherStudentCredentials extends \App\Http\Controllers\Controller
{

      public static function update_all_password(Request $request){
       

            try{

                  $sectionid = $request->get('sectionid');
                  $levelid = $request->get('levelid');
                  $syid = $request->get('syid');
                  $gentype = $request->get('gentype');
                  $usertype = $request->get('usertype');
            
                  if($levelid == 14 || $levelid == 15){
                        $students =     DB::table('sh_enrolledstud')  
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->where('sh_enrolledstud.sectionid',$sectionid)
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->where('sh_enrolledstud.semid',1)
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
                                          'sid'
                                    )
                                    ->get();
                  }else{

                        $students =     DB::table('enrolledstud')  
                                          ->where('enrolledstud.deleted',0)
                                          ->where('enrolledstud.sectionid',$sectionid)
                                          ->where('enrolledstud.syid',$syid)
                                          ->join('studinfo',function($join){
                                                $join->on('studinfo.id','=','enrolledstud.studid');
                                                $join->where('studinfo.deleted',0);
                                          })
                                          ->select(
                                                'studinfo.id',
                                                'studinfo.lastname',
                                                'studinfo.firstname',
                                                'studinfo.middlename',
                                                'suffix',
                                                'sid'
                                          )
                                          ->get();

                  }

                  foreach($students as $item){

                        $email = 'S'.$item->sid;

                        if($usertype == 'parent'){
                              $email = 'P'.$item->sid;
                        }

                        $check_user = DB::table('users')
                                          ->where('email','like','%'.$email.'%')
                                          ->where('deleted',0)
                                          ->first();

                        if(isset($check_user->id)){

                              if($gentype == 'default'){

                                    DB::enableQueryLog();
                                    DB::table('users')
                                        ->take(1)
                                        ->where('id', $check_user->id)
                                        ->update([
                                            'syncstat'=>2,
                                            'passwordstr'   => null,
                                            'password'      => Hash::make('123456'),
                                            //'updatedby'   => auth()->user()->id,
                                            'updateddatetime'=> \Carbon\Carbon::now('Asia/Manila'),
                                            'isDefault'=>1
                                        ]);
            
                                    DB::disableQueryLog();
                                    $logs = json_encode(DB::getQueryLog());
                                    
                                    DB::table('updatelogs')
                                        ->insert([
                                            'type'=>1,
                                            'sql'=> $logs,
                                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                        ]);

                              }else{
                                    $userid =  $check_user->id;
                                    $data = self::generatepassword($userid);
                                   
                              }


                        }else{

                              if($usertype == 'parent'){
                                    $request->request->add(['sid' => $item->sid]);
                                    $request->request->add(['student' => $item->lastname.', '.$item->firstname]);
                                    $request->request->add(['studid' => $item->id]);
                                    $data = self::generate_parent_account($request);
                              }else{
                                    $request->request->add(['sid' => $item->sid]);
                                    $request->request->add(['student' => $item->lastname.', '.$item->firstname]);
                                    $request->request->add(['studid' => $item->id]);
                                    $data = self::generate_student_account($request);
                              }
      

                        }
                  }

                  return array((object)[
                        'status' => 1,
                        'message' => 'Account Updated!'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status' => 0,
                        'message' => 'Something went wrong!'
                  ]);
            }
           


      }


    
    public static function teacher_class($syid = null,Request $request){

        if($syid == null){
              if($request->get('syid') != null){
                    $syid = $request->get('syid');
              }
              else{
                    $syid = DB::table('sy')
                                ->where('isactive',1)
                                ->first()
                                ->id;
              }
        }

        if(Session::get('currentPortal') == 1){
            
            $teacherid = DB::table('teacher')
                          ->where('userid',auth()->user()->id)
                          ->where('deleted',0)
                          ->select('id')
                          ->first()
                          ->id;

            $sections = DB::table('sectiondetail')
                    ->join('sections',function($join){
                          $join->on('sectiondetail.sectionid','=','sections.id');
                          $join->where('sections.deleted',0);
                    })
                    ->where('sectiondetail.syid',$syid)
                    ->where('sectiondetail.deleted',0)
                    ->where('sectiondetail.teacherid', $teacherid)
                    ->orderBy('sectionname')
                    ->select(
                          'levelid',
                          'sectionname',
                          'sectionname as text',
                          'sections.id'
                    )
                    ->get();

        }else if(Session::get('currentPortal') == 3){

            $all_acad = self::get_acad($syid);

            $sections = DB::table('sectiondetail')
                              ->join('sections',function($join){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->where('sectiondetail.syid',$syid)
                              ->where('sectiondetail.deleted',0)
                              ->orderBy('sectionname')
                              ->select(
                                    'levelid',
                                    'sectionname',
                                    'sectionname as text',
                                    'sections.id'
                              )
                              ->get();

        }
        else if(Session::get('currentPortal') == 17 || Session::get('currentPortal') == 6){

            $sections = DB::table('sectiondetail')
                    ->join('sections',function($join){
                          $join->on('sectiondetail.sectionid','=','sections.id');
                          $join->where('sections.deleted',0);
                    })
                    ->where('sectiondetail.syid',$syid)
                    ->where('sectiondetail.deleted',0)
                    ->orderBy('sectionname')
                    ->select(
                          'levelid',
                          'sectionname',
                          'sectionname as text',
                          'sections.id'
                    )
                    ->get();

        }else{

            $sections = DB::table('sectiondetail')
                  ->join('sections',function($join){
                        $join->on('sectiondetail.sectionid','=','sections.id');
                        $join->where('sections.deleted',0);
                  })
                  ->where('sectiondetail.syid',$syid)
                  ->where('sectiondetail.deleted',0)
                  ->orderBy('sectionname')
                  ->select(
                        'levelid',
                        'sectionname',
                        'sectionname as text',
                        'sections.id'
                  )
                  ->get();

        }

        

        $all_info = array();

        foreach($sections as $item){

              array_push($all_info,(object)[
                    'levelid'=>$item->levelid,
                    'id'=>$item->id,
                    'sectionname'=>$item->sectionname,
                    'text'=>$item->sectionname,
              ]);

        }

        return $all_info;

    }

    public static function get_acad($syid = null){

            if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15 || Session::get('currentPortal') == 17){
                  $acadprog = DB::table('academicprogram')
                                          ->select('id')
                                          ->get();
            }elseif(auth()->user()->type == 14 || Session::get('currentPortal') == 14){
                  $acadprog = DB::table('academicprogram')
                                    ->where('id',6)
                                    ->select('id')
                                    ->get();
            }
            else{

                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->select('id')
                                    ->first()
                                    ->id;

                  if(Session::get('currentPortal') == 2){

                        $acadprog = DB::table('academicprogram')
                                          ->where('principalid',$teacherid)
                                          ->get();

                  }else{

                        $acadprog = DB::table('teacheracadprog')
                                    ->where('teacherid',$teacherid)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('acadprogutype',Session::get('currentPortal'))
                                    ->select('acadprogid as id')
                                    ->distinct('acadprogid')
                                    ->get();
                  }
            }


            $acadprog_list = array();
            foreach($acadprog as $item){
                  array_push($acadprog_list,$item->id);
            }

            return $acadprog_list;

      }

      public static function student_creadentials(Request $request, $sectionid = null, $levelid = null, $syid = null){




            if($sectionid == null){
                  if($request->get('sectionid') != null){
                        $sectionid = array($request->get('sectionid'));
                  }
            }
            if($levelid == null){
                  $levelid = $request->get('levelid');
            }
            if($syid == null){
                  $syid = $request->get('syid');
            }


            
            if(Session::get('currentPortal') == 1){
                  if($sectionid == null){
                        $sectionid = collect(self::teacher_class($syid,$request))->pluck('id');
                  }
            }

            if(Session::get('currentPortal') == 3){
                  if($sectionid == null){
                        $sectionid = collect(self::teacher_class($syid,$request))->pluck('id');
                  }
            }

            $search = $request->get('search');
            $search = $search['value'];

            $enrolledstud = collect(array())->values(); 

            $students =     DB::table('sh_enrolledstud');

            if($syid != null){
                  $students = $students->where('syid',$syid);
            } 
            
            if($sectionid != null){
                  $students = $students->whereIn('sectionid',$sectionid);
            } 

            $students = $students->where('sh_enrolledstud.deleted',0)
                                    ->whereIn('studstatus',[1,2,4])
                                    ->select('studid')
                                    ->distinct('studid')
                                    ->get();

            $enrolledstud = $enrolledstud->merge(collect($students)->values());

            $students =     DB::table('enrolledstud') ;

            if($syid != null){
                  $students = $students->where('syid',$syid);
            }

            if($sectionid != null){
                  $students = $students->whereIn('sectionid',$sectionid);
            } 

            $students = $students->where('enrolledstud.deleted',0)
                                    ->whereIn('studstatus',[1,2,4])
                                    ->select('studid')
                                    ->distinct('studid')
                                    ->get();
                                    

            $enrolledstud = $enrolledstud->merge(collect($students)->values());

            if(Session::get('currentPortal') != 1){

                  $students =     DB::table('college_enrolledstud') ;

                  if($syid != null){
                        $students = $students->where('syid',$syid);
                  }

                  // if($sectionid != null){
                  //       $students = $students->whereIn('sectionID',$sectionid);
                  // } 

                  $students = $students->where('college_enrolledstud.deleted',0)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->select('studid')
                                          ->distinct('studid')
                                          ->get();

                  $enrolledstud = $enrolledstud->merge(collect($students)->values());
                  
            }

            $students = dB::table('studinfo');

            if($search != null){
                  $students = $students->where(function($query) use($search){
                                    $query->orWhere('firstname','like','%'.$search.'%');
                                    $query->orWhere('lastname','like','%'.$search.'%');
                                    $query->orWhere('sid','like','%'.$search.'%');
                              });
            }

            $students = $students->whereIn('id',collect($enrolledstud)->pluck('studid'))
                              ->take($request->get('length'))
                              ->skip($request->get('start'))
                              ->orderBy('studentname','asc')
                              ->select(
                                    'studinfo.id',
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.middlename',
                                    'suffix',
                                    'sid',
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                              )
                              ->get();
          

            $student_count = dB::table('studinfo');

            if($search != null){
                  $student_count = $student_count->where(function($query) use($search){
                                    $query->orWhere('firstname','like','%'.$search.'%');
                                    $query->orWhere('lastname','like','%'.$search.'%');
                                    $query->orWhere('sid','like','%'.$search.'%');
                              });
            }

            $student_count = $student_count->whereIn('id',collect($enrolledstud)->pluck('studid'))
                              ->select(
                                    'studinfo.id',
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.middlename',
                                    'suffix',
                                    'sid'
                              )
                              ->count();

            foreach($students as $item){

                  $student_email = array();
                  array_push($student_email,'S'.$item->sid);
                  array_push($student_email,'P'.$item->sid);

                  $users = DB::table('users')
                                    ->whereIn('email',$student_email)
                                    ->select('email','passwordstr','id','isDefault')
                                    ->where('deleted',0)
                                    ->get();

                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  $suffix = '';

                  if($item->middlename != null){
                  $temp_middle = $item->middlename[0].'.';
                  }
                  if($suffix != null){
                  $suffix = $temp_middle[0].' ';
                  }

                  $item->student = $item->lastname.', '.$item->firstname.' '.$suffix.$temp_middle;

                  $parent_creds = collect($users)->where('email','P'.$item->sid)->values();
                  $student_creds = collect($users)->where('email','S'.$item->sid)->values();


                  $item->parent_credentials = $parent_creds;
                  $item->student_credentials = $student_creds;

                  

            }


            return @json_encode((object)[
                  'data'=>$students,
                  'recordsTotal'=>$student_count,
                  'recordsFiltered'=>$student_count
            ]);
                  

            return $enrolledstud;

            

      }

//     public static function student_creadentials(Request $request, $sectionid = null, $levelid = null, $syid = null){

       
//         if($sectionid == null){
//             $sectionid = $request->get('sectionid');
//         }
//         if($levelid == null){
//             $levelid = $request->get('levelid');
//         }
//         if($syid == null){
//             $syid = $request->get('syid');
//         }

//         if($levelid == 14 || $levelid == 15){
//             $students =     DB::table('sh_enrolledstud')  
//                             ->where('sh_enrolledstud.deleted',0)
//                             ->where('sh_enrolledstud.sectionid',$sectionid)
//                             ->where('sh_enrolledstud.syid',$syid)
//                             ->where('sh_enrolledstud.semid',1)
//                             ->join('studinfo',function($join){
//                                 $join->on('studinfo.id','=','sh_enrolledstud.studid');
//                                 $join->where('studinfo.deleted',0);
//                             })
//                             ->select(
//                                 'studinfo.id',
//                                 'studinfo.lastname',
//                                 'studinfo.firstname',
//                                 'studinfo.middlename',
//                                 'suffix',
//                                 'sid'
//                           )
//                             ->get();
//         }else{

//             $students =     DB::table('enrolledstud')  
//                               ->where('enrolledstud.deleted',0)
//                               ->where('enrolledstud.sectionid',$sectionid)
//                               ->where('enrolledstud.syid',$syid)
//                               ->join('studinfo',function($join){
//                                     $join->on('studinfo.id','=','enrolledstud.studid');
//                                     $join->where('studinfo.deleted',0);
//                               })
//                               ->select(
//                                     'studinfo.id',
//                                     'studinfo.lastname',
//                                     'studinfo.firstname',
//                                     'studinfo.middlename',
//                                     'suffix',
//                                     'sid'
//                               )
//                               ->get();

//         }

//         foreach($students as $item){

//               $student_email = array();
//               array_push($student_email,'S'.$item->sid);
//               array_push($student_email,'P'.$item->sid);

//               $users = DB::table('users')
//                                 ->whereIn('email',$student_email)
//                                 ->select('email','passwordstr','id','isDefault')
//                                 ->where('deleted',0)
//                                 ->get();

//               $middlename = explode(" ",$item->middlename);
//               $temp_middle = '';
//               $suffix = '';

//               if($item->middlename != null){
//                 $temp_middle = $item->middlename[0].'.';
//               }
//               if($suffix != null){
//                 $suffix = $temp_middle[0].' ';
//               }

//               $item->student = $item->lastname.', '.$item->firstname.' '.$suffix.$temp_middle;

//               $parent_creds = collect($users)->where('email','P'.$item->sid)->values();
//               $student_creds = collect($users)->where('email','S'.$item->sid)->values();


//               $item->parent_credentials = $parent_creds;
//               $item->student_credentials = $student_creds;


//               $item->search = $item->student.' '.$item->sid;
              
              

//         }


//        return $students;

//   }

      public static function update_password(Request $request){
            try{

                  $userid = $request->get('id');
                  $passwordtype = $request->get('passwordtype');

                  if($passwordtype == 3){
                        $data = self::generatepassword($userid);
                  }else{

                        DB::enableQueryLog();
                        DB::table('users')
                            ->where('id', $userid)
                            ->update([
                                'syncstat'=>2,
                                'passwordstr'   => null,
                                'password'      => Hash::make('123456'),
                                //'updatedby'   => auth()->user()->id,
                                'updateddatetime'=> \Carbon\Carbon::now('Asia/Manila'),
                                'isDefault'=>1
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

                 

                  return array((object)[
                        // 'data'=>$data,
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
                      'syncstat'=>2,
                      'passwordstr'   => $random_string,
                      'password'      => $hashed,
                      //'updatedby'   => auth()->user()->id,
                      'updateddatetime'=> \Carbon\Carbon::now('Asia/Manila'),
                      'isDefault'=>3
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

      public static function generate_student_account(Request $request){

            $sid = $request->get('sid');
            $student = $request->get('student');
            $studid = $request->get('id');

            try{

                  $userid = db::table('users')
                              ->insertGetId([
                                    'syncstat'=>0,
                                    'name' => $student,
                                    'email' => 'S'.$sid,
                                    'type' => 7,
                                    'password' => Hash::make('123456')
                              ]);

                  $data = self::generatepassword($userid);

                  self::update_studinfo($studid, $userid);

                  return array((object)[
                              'email'=>'S'.$sid,
                              'passwordstr'=>null,
                              'userid'=>$userid,
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

            try{

                  $check = DB::table('users')
                              ->where('email','P'.$sid)
                              ->where('deleted',0)
                              ->count();

                  if($check == 0){

                        $userid = db::table('users')
                                          ->insertGetId([
                                                'syncstat'=>0,
                                                'name' => 'P'.$sid,
                                                'email' => 'P'.$sid,
                                                'type' => 9,
                                                'password' => Hash::make('123456')
                                          ]);

                        $data = self::generatepassword($userid);

                        return array((object)[
                              'email'=>'P'.$sid,
                              'passwordstr'=>null,
                              'userid'=>$userid,
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
          
                  return array((object)[
                        
                        'status' => 0,
                        'message' => 'Something went wrong!'
                  ]);

            }

      }

      public static function update_studinfo($studid = null, $studuser = null){

            // DB::enableQueryLog();
            //       $putUserid = db::table('studinfo')
            //             ->where('id', $studid)
            //             ->take(1)
            //             ->update([
            //                   'userid' => $studuser,
            //                   'updateddatetime' => \App\RegistrarModel::getServerDateTime()
            //             ]);
            // DB::disableQueryLog();
            
            // $logs = json_encode(DB::getQueryLog());

            // DB::table('updatelogs')
            //       ->insert([
            //             'type'=>1,
            //             'sql'=> $logs,
            //             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            //       ]);
      
      }
    


}


     