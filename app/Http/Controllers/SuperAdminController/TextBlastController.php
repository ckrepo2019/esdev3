<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class TextBlastController extends \App\Http\Controllers\Controller
{

      public static function textblastmonitoringDatatable(Request $request){

            try{

                  $search = $request->get('search');
                  $search = $request->get('search');
                  $smsstatus = $request->get('smsstatus');
                  $receivertype = $request->get('receivertype');
                  $textblast = DB::table('smsbunkertextblast');

                  if($smsstatus !== "" && $smsstatus !== null){
                        $textblast = $textblast->where('smsstatus',$smsstatus);
                  }
                  
                  if($receivertype !== "" && $receivertype !== null){
                        $textblast = $textblast->where('receivertype',$receivertype);
                  }
                  
                  
                  $textblast = $textblast->join('studinfo',function($join){
                                                $join->on('smsbunkertextblast.studid','=','studinfo.id');
                                                $join->where('studinfo.deleted',0);
                                          })
                              
                              ->take($request->get('length'))
                              ->skip($request->get('start'))
                              ->select(
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                                    'message',
                                    'receiver',
                                    'smsstatus',
                                    'receivertype',
                                    'smsbunkertextblast.createddatetime'
                              )
                              ->orderBy('createddatetime','desc')
                              ->get();

                  $textblast_count = DB::table('smsbunkertextblast')
                                          ->join('studinfo',function($join){
                                                $join->on('smsbunkertextblast.studid','=','studinfo.id');
                                                $join->where('studinfo.deleted',0);
                                          });

                  if($smsstatus !== "" && $smsstatus !== null){
                        $textblast_count = $textblast_count
                                          ->orderBy('createddatetime','desc')
                                          ->where('smsstatus',$smsstatus);
                  }
                        
                  $textblast_count = $textblast_count->count();

                  foreach($textblast as $item){
                        $item->createddatetime = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:MM A');
                  }

                  return @json_encode((object)[
                        'data'=>$textblast,
                        'recordsTotal'=>$textblast_count,
                        'recordsFiltered'=>$textblast_count
                  ]);
        
            }catch(\Exception $e){
                  return $e;
                  return self::store_error($e);
            }
    
      }
      
      public static function message_list(){
            $anouncement = DB::table('announcements')->where('deleted',0)->get();
            return $anouncement;
      }

      public static function send_message(Request $request){

            try{
                  

                  $message = $request->get('message');
                  $type = $request->get('type');
                  $syid = $request->get('syid');

                  $schoolinfo = DB::table('schoolinfo')->first();
                  $students = self::contact_number_list($request);

                  foreach($students as $stud_item){

                        $contactno = null;
                        $studid = $stud_item->studid;
                        $valid_contact = false;
                        $receivertype = 1;

                        if($type == 'student'){
                              if($stud_item->contactno != null && strlen($stud_item->contactno) == 11){
                                    $contactno = $stud_item->contactno;
                                    $valid_contact = true;
                              }
                        }else{
                              if($stud_item->contactNumber != null && strlen($stud_item->contactNumber) == 11 ){
                                    $contactno = $stud_item->contactNumber;
                                    $valid_contact = true;
                                    $receivertype = 2;
                              }
                        }

                        $contactno = '+63' . substr($contactno, 1);

                        if($valid_contact){
                              $message_count = 1;
                              foreach($message as $item){

$temp_message = $schoolinfo->abbreviation.' '.$message_count.':

'.$item;

                                    $check = DB::table('smsbunkertextblast')
                                                ->where('message',$temp_message)
                                                ->where('receiver',$contactno)
                                                ->where('studid',$studid)
                                                ->where('receivertype',$receivertype)
                                                ->count();

                                    if($check == 0){
                                          // return array((object)[
                                          //       'status'=>0,
                                          //       'message'=>'Message Already Exist!'
                                          // ]);
                                          DB::table('smsbunkertextblast')
                                                ->insert([
                                                      'message'=> $temp_message,
                                                      'receiver'=>$contactno,
                                                      'smsstatus'=>0,
                                                      'createdby'=>$request->get('userid'),
                                                      'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                      'studid'=>$studid,
                                                      'receivertype'=>$receivertype
                                                ]);
                        
                                          $message_count += 1;

                                    }

                                    
                              }
                        }


                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Message Submitted'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);

            }

      }

      public static function contact_number_list_ajax(Request $request){

            return self::contact_number_list($request);

      }

      public static function contact_number_list(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');

            $temp_students = array();

            $students =  DB::table('enrolledstud')
                              ->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.syid',$syid)
                              ->join('studinfo',function($join){
                                    $join->on('studinfo.id','=','enrolledstud.studid');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->whereIn('enrolledstud.levelid',$levelid)
                              ->select(
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
                                    'enrolledstud.studid',
                                    'guardianname',
                                    'sid'
                              )
                              ->get();

            foreach($students as $item){
                  array_push($temp_students, $item);
            }

            $students =  DB::table('sh_enrolledstud')
                              ->where('sh_enrolledstud.deleted',0)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->whereIn('sh_enrolledstud.levelid',$levelid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                              ->join('studinfo',function($join){
                                    $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->select(
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
                                    'sh_enrolledstud.studid',
                                    'sid'
                              )
                              ->get();

            foreach($students as $item){
                  array_push($temp_students, $item);
            }

            $students =  DB::table('college_enrolledstud')
                              ->where('college_enrolledstud.deleted',0)
                              ->where('college_enrolledstud.syid',$syid)
                              ->where('college_enrolledstud.semid',$semid)
                              ->whereIn('college_enrolledstud.yearLevel',$levelid)
                              ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                              ->join('studinfo',function($join){
                                    $join->on('studinfo.id','=','college_enrolledstud.studid');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->select(
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
                                    'college_enrolledstud.studid',
                                    'sid'
                              )
                              ->get();

            foreach($students as $item){
                  array_push($temp_students, $item);
            }

            foreach($temp_students as $stud_item){
                  
                  $middlename = explode(" ",$stud_item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                      foreach ($middlename as $middlename_item) {
                          if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                          } 
                      }
                  }
                  $stud_item->student = $stud_item->lastname.', '.$stud_item->firstname.' '.$stud_item->suffix.' '.$temp_middle;

                  if($stud_item->ismothernum == 1 && $stud_item->mcontactno != null){
                        $stud_item->contactRelation = 'Mother';
                        $stud_item->contactNumber = $stud_item->mcontactno;
                        $stud_item->contactPerson = $stud_item->mothername;
                  }else if($stud_item->isfathernum == 1 && $stud_item->fcontactno != null){
                        $stud_item->contactRelation = 'Father';
                        $stud_item->contactNumber = $stud_item->fcontactno;
                        $stud_item->contactPerson = $stud_item->fathername;
                  }else if($stud_item->isguardannum == 1 && $stud_item->gcontactno != null){
                        $stud_item->contactRelation = 'Guardian';
                        $stud_item->contactNumber = $stud_item->gcontactno;
                        $stud_item->contactPerson = $stud_item->guardianname;
                  }else{
                        $stud_item->contactRelation = '';
                        $stud_item->contactNumber = '';
                        $stud_item->contactPerson = '';
                  }

                  if($stud_item->contactno == null){
                        $stud_item->contactno = "";
                  }

              }

            return $temp_students;

      }
     
      

}
