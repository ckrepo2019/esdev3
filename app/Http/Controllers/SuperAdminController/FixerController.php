<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;

class FixerController extends \App\Http\Controllers\Controller
{
    
      public static function get_acad($syid = null){

            if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15 || Session::get('currentPortal') == 17){
                  $acadprog = DB::table('academicprogram')
                                          ->select('id')
                                          ->get();
            }elseif(Session::get('currentPortal') == 14){
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

      public static function contact_printable(Request $request){

            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $semid = $request->get('semid');
            $datatype = $request->get('datatype');
            $gradelevel = array();
            $sections = array();
            $acad = self::get_acad($syid);

            $schoolinfo = DB::table('schoolinfo')->first();
            $contact = self::contact_number_list($syid,$levelid,$semid);

            $syinfo = DB::table('sy')
                              ->where('id',$syid)
                              ->select('sydesc')
                              ->first();

            if($datatype == 2){
                  $gradelevel = DB::table('gradelevel')
                                    ->whereIn('acadprogid',$acad)
                                    ->select(
                                          'id',
                                          'levelname'
                                    )
                                    ->orderBy('sortid')
                                    ->get();
            }
            
            if($datatype == 3){
                  $sections = DB::table('sectiondetail')
                                    ->where('sectiondetail.deleted',0)
                                    ->where('syid',$syid)
                                    ->join('sections',function($join) use($acad){
                                          $join->on('sectiondetail.sectionid','=','sections.id');
                                          $join->where('sections.deleted',0);
                                    })
                                    ->join('gradelevel',function($join) use($acad){
                                          $join->on('sections.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                          $join->whereIn('gradelevel.acadprogid',$acad);
                                    })
                                    ->orderBy('sortid')
                                    ->select(
                                          'levelname',
                                          'sectionname',
                                          'sections.id'
                                    )
                                    ->get();
            }

            // return $contact;

            $pdf = PDF::loadView('superadmin.pages.reports.contact',compact('datatype','contact','syinfo','gradelevel','sections','schoolinfo'))->setPaper('legal');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();


      }


      public static function contact_number_list_ajax(Request $request){

            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $semid = $request->get('semid');
            $studentstatus = $request->get('studentstatus');
            return self::contact_number_list($syid,$levelid,$semid,$studentstatus);
      }

      public static function contact_number_list($syid = null, $levelid = null, $semid = null, $studentstatus = 'enrolled'){

            $temp_students = array();

            if($studentstatus == 'all'){

                  $temp_students = DB::table('studinfo')
                                          ->where('deleted',0);

                  if($levelid != null){
                        $temp_students = $temp_students->where('studinfo.levelid',$levelid);
                  }
                  
                  $temp_students = $temp_students->select(
                                                'studinfo.sectionname',
                                                'studinfo.levelid',
                                                'studinfo.sectionid',
                                                'id as studid',
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
                                                'sid'
                                          )
                                          ->get();

            }else{

                  
                  $students =  DB::table('enrolledstud');

                  if($levelid != null){
                        $students = $students->where('enrolledstud.levelid',$levelid);
                  }

                  $students =  $students->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,3])
                                    ->where('enrolledstud.syid',$syid)
                                    ->join('studinfo',function($join){
                                          $join->on('enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('sections',function($join){
                                          $join->on('enrolledstud.sectionid','=','sections.id');
                                          $join->where('sections.deleted',0);
                                    })
                                    ->select(
                                          'sections.sectionname',
                                          'enrolledstud.levelid',
                                          'enrolledstud.sectionid',
                                          'studid',
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
                                          'sid'
                                    )
                                    ->get();

                  foreach($students as $item){
                        array_push($temp_students, $item);
                  }

                  $students =  DB::table('sh_enrolledstud');

                  if($levelid != null){
                        $students = $students->where('sh_enrolledstud.levelid',$levelid);
                  }
                  if($semid != null){
                        $students = $students->where('sh_enrolledstud.semid',$semid);
                  }

                  $students =  $students->where('sh_enrolledstud.deleted',0)
                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,3])
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->join('studinfo',function($join){
                                          $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('sections',function($join){
                                          $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                          $join->where('sections.deleted',0);
                                    })
                                    ->select(
                                          'sections.sectionname',
                                          'sh_enrolledstud.levelid',
                                          'sh_enrolledstud.sectionid',
                                          'studid',
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
                                          'sid'
                                    )
                                    ->get();

                  foreach($students as $item){
                        array_push($temp_students, $item);
                  }

                  $students =  DB::table('college_enrolledstud');

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
                                          $join->on('college_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->select(
                                          'studid',
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
                                          'sid'
                                    )
                                    ->get();

                  foreach($students as $item){
                        array_push($temp_students, $item);
                  }
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
                  if($stud_item->ismothernum == null || $stud_item->ismothernum == ""){
                        $stud_item->ismothernum = 0;
                  }
                  if($stud_item->isfathernum == null || $stud_item->isfathernum == ""){
                        $stud_item->isfathernum = 0;
                  }
                  if($stud_item->isguardannum == null || $stud_item->isguardannum == ""){
                        $stud_item->isguardannum = 0;
                  }
                  $stud_item->student = $stud_item->lastname.', '.$stud_item->firstname.' '.$stud_item->suffix.' '.$temp_middle;
              }

            return $temp_students;

      }

      // public static function fix_student_contact(Request $request){

      //       $syid = $request->get('syid');

      //       $contacts =  self::contact_number_list($syid);

      //       foreach($contacts as $item){

      //             $contactno = $item->contactno;
      //             $mcontactno = $item->mcontactno;
      //             $fcontactno = $item->fcontactno;
      //             $gcontactno = $item->gcontactno;

      //             $valid = true;

      //             if($contactno != null){
      //                   if(str_contains($contactno,'-')){
      //                         $contactno = str_replace("-","",$contactno);
      //                         $valid = false;
      //                   }
      //             }
      //             if($mcontactno != null){
      //                   if(str_contains($mcontactno,'-')){
      //                         $mcontactno = str_replace("-","",$mcontactno);
      //                         $valid = false;
      //                   }
      //             }
      //             if($fcontactno != null){
      //                   if(str_contains($fcontactno,'-')){
      //                         $fcontactno = str_replace("-","",$fcontactno);
      //                         $valid = false;
      //                   }
      //             }
      //             if($gcontactno != null){
                
      //                   if(str_contains($gcontactno,'-')){
      //                         $gcontactno = str_replace("-","",$gcontactno);
      //                         $valid = false;
      //                   }
      //             }

      //             if(!$valid){

      //                   DB::table('studinfo')
      //                         ->where('id',$item->id)
      //                         ->take(1)
      //                         ->update([
      //                               'contactno'=>$contactno,
      //                               'mcontactno'=>$mcontactno,
      //                               'fcontactno'=>$fcontactno,
      //                               'gcontactno'=>$gcontactno,
      //                         ]);

      //             }
                  

      //       }

      //       return self::contact_number_list($syid);


      // }

      public static function student_credentials(){

            $enrolled = DB::table('studinfo')
                              ->join('users',function($join){
                                    $join->on('studinfo.userid','=','users.id');
                                    $join->where('users.deleted',0);
                              })
                              ->where('studinfo.studstatus',1)
                              ->where('studinfo.deleted',0)
                              ->select(
                                          'sid',
                                          'userid',
                                          'studinfo.id',
                                          'lastname',
                                          'firstname',
                                          'email'
                                    )
                              ->get();

            $conflict_creds = array();

            foreach($enrolled as $item){
                  if(str_replace('S','',$item->email) != $item->sid){
                        $item->status = 'not same credentials';
                        array_push($conflict_creds, $item);
                  }
            }

            $multiple_student = DB::table('users')
                              ->where('type',7)
                              ->where('deleted',0)
                              ->groupBy('email')
                              ->select(
                                    DB::raw('count(id) as accountnumber, email','id')
                              )
                              ->get();

            $multiple_student = collect($multiple_student)->where('accountnumber','>',1)->values();

            foreach($multiple_student as $item){
                  $item->status = 'Multiple student account';
                  array_push($conflict_creds, $item);
            }

            $multiple_parent = DB::table('users')
                        ->where('type',9)
                        ->where('deleted',0)
                        ->groupBy('email')
                        ->select(
                              DB::raw('count(id) as accountnumber, email','lastname','firstname','sid')
                        )
                        ->get();

            $multiple_parent = collect($multiple_parent)->where('accountnumber','>',1)->values();

            foreach($multiple_parent as $item){
                  $item->status = 'multiple parent account';
                  array_push($conflict_creds, $item);
            }

            $not_default_pass = DB::table('users')
                                    ->whereIn('type',[7,9])
                                    ->where('deleted',0)
                                    ->where('isDefault',1)
                                    ->where('loggedIn',0)
                                    ->select(
                                          DB::raw('count(id) as accountnumber, email')
                                    )
                                    ->get();

            foreach($not_default_pass as $item){
                  $item->status = 'not default pass';
                  array_push($conflict_creds, $item);
            }                 

            return $conflict_creds;

      }

      public static function fix_credentials(Request $request){

            //type 1 = not matching credentials
            //type 2 = mutiple student credentials
            //type 3 = multiple parent account

            $type = $request->get('type');
            $email = $request->get('email');

            if($type == 3){

                  $parent_users = DB::table('users')
                                    ->where('email',$email)
                                    ->where('deleted',0)
                                    ->get();

                  if(count($parent_users) > 0){
                        foreach($parent_users as $key=>$item){
                              if($key != 0){

                                    DB::table('users')  
                                          ->where('id',$item->id)
                                          ->take(1)
                                          ->update([
                                                'deleted'=>1
                                          ]);
                              }
                        }

                        return array((object)[
                              'status'=>'Credentials Fixed',
                              'data'=>self::student_credentials()
                        ]);

                  }
                  

            }

      }

      public static function update_contact(Request $request){

            try{
                  $studid = $request->get('studid');
                  $contactno = $request->get('contactno');
                  $gcontactno = $request->get('gcontactno');
                  $fcontactno = $request->get('fcontactno');
                  $mcontactno = $request->get('mcontactno');
                  $ismothernum = $request->get('ismothernum');
                  $isfathernum = $request->get('isfathernum');
                  $isguardiannum = $request->get('isguardiannum');

                  DB::table('studinfo')
                        ->where('id',$studid)
                        ->take(1)
                        ->update([
                              'contactno'=>str_replace('-','',$contactno),
                              'gcontactno'=>str_replace('-','',$gcontactno),
                              'fcontactno'=>str_replace('-','',$fcontactno),
                              'mcontactno'=>str_replace('-','',$mcontactno),
                              'ismothernum'=>$ismothernum,
                              'isfathernum'=>$isfathernum,
                              'isguardannum'=>$isguardiannum,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Contact Information Updated!'
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

}
