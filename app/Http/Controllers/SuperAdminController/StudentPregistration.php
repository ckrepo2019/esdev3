<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;

class StudentPregistration extends \App\Http\Controllers\Controller
{

      public static function markActiveStatus(Request $request){
            
            //module 21 - student active inactive;

            try{
                  DB::table('studinfo')
                        ->where('id',$request->get('studid'))
                        ->take(1)
                        ->update([
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'studisactive'=>$request->get('status')
                        ]);


                  $studinfo = DB::table('studinfo')
                                    ->where('id',$request->get('studid'))
                                    ->first();

                  if($request->get('status') == 1){
                        $message = auth()->user()->name.' mark student '.$studinfo->firstname.' '.$studinfo->lastname.' as active';
                  }else{
                        $message = auth()->user()->name.' mark student '.$studinfo->firstname.' '.$studinfo->lastname.' as inactive';
                  }
                  
                  DB::table('logs') 
                        ->insert([
                             'dataid'=>$request->get('studid'),
                             'module'=>21,
                             'message'=>$message,
                             'createdby'=>auth()->user()->id,
                             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                       ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Marked Successfully!'
                  ]);  

            }catch(\Exception $e){
                  return self::store_error($e);
            }

           


      }


      public static function enrollment_summary(Request $request){

            $syid = $request->get('syid');
            $studstat = $request->get('studstat');
            $semid = $request->get('semid');
            $ghssemid = $semid == 3 ? [$semid] : [1,2];
            $all_acad = self::get_acad($syid);

            $studentstatus = DB::table('studentstatus')
                                    ->get();

            $all_students = array();

            foreach(collect($studentstatus)->where('id','!=',0)->values() as $item){

                  $gshs_enrolled = DB::table('enrolledstud')
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->where('enrolledstud.syid',$syid)
                              ->whereIn('ghssemid',$ghssemid)
                              ->where('enrolledstud.deleted',0)
                              ->where('studstatus',$item->id)
                              ->distinct('studid')
                              ->select('studid')
                              ->get();

                  if(count($all_students) == 0){
                        $all_students = $gshs_enrolled;
                  }
                

                  $sh_enrolled = DB::table('sh_enrolledstud')
                                    ->join('gradelevel',function($join) use($all_acad){
                                          $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                          $join->whereIn('gradelevel.acadprogid',$all_acad);
                                    })
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->where('sh_enrolledstud.semid',$semid)
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->where('studstatus',$item->id)
                                    ->select('studid')
                                    ->get();

                  $college_enrolled = DB::table('college_enrolledstud')
                                    ->where('studstatus',$item->id)
                                    ->join('gradelevel',function($join) use($all_acad){
                                          $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                          $join->whereIn('gradelevel.acadprogid',$all_acad);
                                    })
                                    ->where('college_enrolledstud.syid',$syid)
                                    ->where('college_enrolledstud.semid',$semid)
                                    ->where('college_enrolledstud.deleted',0)
                                    ->distinct('studid')
                                    ->select('studid')
                                    ->get();
                                    

                  $item->count = count($gshs_enrolled) + count($sh_enrolled) + count($college_enrolled);

                  $all_students = $all_students->merge($gshs_enrolled);
                  $all_students = $all_students->merge($sh_enrolled);
                  $all_students = $all_students->merge($college_enrolled);
            
            }

            $not_enrolled = DB::table('studinfo')
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->where('studinfo.deleted',0)
                              ->whereNotIn('studinfo.id',collect($all_students)->pluck('studid'))
                              ->count();

            foreach(collect($studentstatus)->where('id',0)->values() as $item){
                  $item->count = $not_enrolled;
            }

            return $studentstatus;

      }


      public static function vac_info(Request $request){

            $syid = $request->get('syid');
            $studid = $request->get('studid');
            $semid = $request->get('semid');
            $ghssemid = $semid == 3 ? [$semid] : [1,2];
            $all_acad = self::get_acad($syid);
            $schoolinfo = DB::table('schoolinfo')->first();
            $all_enrolledstudents = array();

            $vac_info = DB::table('apmc_midinfo')
                              ->where('deleted',0)
                              ->distinct('studid');
                              
            if($studid != null){
                  $vac_info = $vac_info->where('studid',$studid);
            }

            $vac_info = $vac_info->get();

            $enrolled = DB::table('enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              });

            if($studid != null){
                  $enrolled = $enrolled->where('studid',$studid);
            }

            $enrolled =  $enrolled->where('enrolledstud.syid',$syid)
                              ->whereIn('ghssemid',$ghssemid)
                              ->where('enrolledstud.deleted',0)
                              ->select(
                                    'dateenrolled',
                                    'enrolledstud.levelid',
                                    'levelname',
                                    'studid',
                                    'enrolledstud.sectionid',
                                    'sections.sectionname',
                                    'lastname',
                                    'firstname',
                                    'suffix',
                                    'middlename',
                                    'description'
                              )
                              ->distinct('studid')
                              ->get();

        
            foreach($enrolled as $item){

                  $temp_middle = '';
                  $temp_suffix = '';

                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }

                  $item->student = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;

                  $collect_vac = collect($vac_info)->where('studid',$item->studid)->first();
                  if(isset($collect_vac)){
                        $item->vacc = $collect_vac->vacc;
                        $item->vacc_type = $collect_vac->vacc_type;
                        $item->vacc_type_2nd = $collect_vac->vacc_type_2nd;
                        $item->vacc_card_id = $collect_vac->vacc_card_id;
                        $item->dose_date_1st = $collect_vac->dose_date_1st;
                        $item->dose_date_2nd = $collect_vac->dose_date_2nd;
                        $item->philhealth = $collect_vac->philhealth;
                        $item->bloodtype = $collect_vac->bloodtype;
                        $item->allergy = $collect_vac->allergy;
                        $item->allergy_to_med = $collect_vac->allergy_to_med;
                        $item->med_his = $collect_vac->med_his;
                        $item->other_med_info = $collect_vac->other_med_info;

                        $item->vacc_type_id = $collect_vac->vacc_type_id;
                        $item->vacc_type_2nd_id = $collect_vac->vacc_type_2nd_id;
                        $item->booster_type_id = $collect_vac->booster_type_id;
                        $item->dose_date_booster = $collect_vac->dose_date_booster;
                        $item->vacc_type_booster = $collect_vac->vacc_type_booster;
                  }else{
                        $item->vacc = 0;
                        $item->vacc_type = null;
                        $item->vacc_type_2nd = null;
                        $item->vacc_card_id = null;
                        $item->dose_date_1st = null;
                        $item->dose_date_2nd =null;
                        $item->philhealth = null;
                        $item->bloodtype = null;
                        $item->allergy = null;
                        $item->allergy_to_med = null;
                        // $item->med_his = null;
                        $item->other_med_info = null;


                        $item->vacc_type_id = null;
                        $item->vacc_type_2nd_id = null;
                        $item->booster_type_id = null;
                        $item->dose_date_booster = null;
                        $item->vacc_type_booster = null;
                  }

                  array_push($all_enrolledstudents,$item);
            }

            $enrolled = DB::table('sh_enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              });

            if($studid != null){
                  $enrolled = $enrolled->where('studid',$studid);
            }

            $enrolled =  $enrolled->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->select(
                                    'sh_enrolledstud.levelid',
                                    'levelname',
                                    'sh_enrolledstud.studstatus',
                                    'sh_enrolledstud.sectionid',
                                    'studid',
                                    'sections.sectionname',
                                    'lastname',
                                    'firstname',
                                    'suffix',
                                    'middlename',
                                    'description'
                              )
                              ->distinct('studid')
                              ->get();

            foreach($enrolled as $item){

                  $temp_middle = '';
                  $temp_suffix = '';

                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }

                  $item->student = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;

                  $collect_vac = collect($vac_info)->where('studid',$item->studid)->first();
                  if(isset($collect_vac)){
                        $item->vacc = $collect_vac->vacc;
                        $item->vacc_type = $collect_vac->vacc_type;
                        $item->vacc_type_2nd = $collect_vac->vacc_type_2nd;
                        $item->vacc_card_id = $collect_vac->vacc_card_id;
                        $item->dose_date_1st = $collect_vac->dose_date_1st;
                        $item->dose_date_2nd = $collect_vac->dose_date_2nd;
                        $item->philhealth = $collect_vac->philhealth;
                        $item->bloodtype = $collect_vac->bloodtype;
                        $item->allergy = $collect_vac->allergy;
                        $item->allergy_to_med = $collect_vac->allergy_to_med;
                        $item->med_his = $collect_vac->med_his;
                        $item->other_med_info = $collect_vac->other_med_info;

                        $item->vacc_type_id = $collect_vac->vacc_type_id;
                        $item->vacc_type_2nd_id = $collect_vac->vacc_type_2nd_id;
                        $item->booster_type_id = $collect_vac->booster_type_id;
                        $item->dose_date_booster = $collect_vac->dose_date_booster;
                        $item->vacc_type_booster = $collect_vac->vacc_type_booster;
                  }else{
                        $item->vacc = 0;
                        $item->vacc_type = null;
                        $item->vacc_type_2nd = null;
                        $item->vacc_card_id = null;
                        $item->dose_date_1st = null;
                        $item->dose_date_2nd =null;
                        $item->philhealth = null;
                        $item->bloodtype = null;
                        $item->allergy = null;
                        $item->allergy_to_med = null;
                        $item->med_his = null;
                        $item->other_med_info = null;
                        $item->vacc_type_id = null;
                        $item->vacc_type_2nd_id = null;
                        $item->booster_type_id = null;
                        $item->dose_date_booster = null;
                        $item->vacc_type_booster = null;
                  }

                  array_push($all_enrolledstudents,$item);
            }

            $enrolled = DB::table('college_enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('college_enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->join('college_sections',function($join){
                                    $join->on('college_enrolledstud.sectionID','=','college_sections.id');
                                    $join->where('college_sections.deleted',0);
                              })
                              ->join('studinfo',function($join){
                                    $join->on('college_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              });

            if($studid != null){
                  $enrolled = $enrolled->where('studid',$studid);
            }

            $enrolled = $enrolled->where('college_enrolledstud.syid',$syid)
                              ->where('college_enrolledstud.semid',$semid)
                              ->where('college_enrolledstud.deleted',0)
                              ->select(
                                    'date_enrolled as dateenrolled',
                                    'yearLevel as levelid',
                                    'college_enrolledstud.sectionID as sectionid',
                                    'levelname',
                                    'studid',
                                    'college_enrolledstud.studstatus',
                                    'sectionDesc as sectionname',
                                    'lastname',
                                    'firstname',
                                    'suffix',
                                    'middlename',
                                    'description'
                              )
                              ->distinct('studid')
                              ->get();

            foreach($enrolled as $item){

                  $temp_middle = '';
                  $temp_suffix = '';

                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }

                  $item->student = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                  
                  $collect_vac = collect($vac_info)->where('studid',$item->studid)->first();
                  if(isset($collect_vac)){
                        $item->vacc = $collect_vac->vacc;
                        $item->vacc_type = $collect_vac->vacc_type;
                        $item->vacc_type_2nd = $collect_vac->vacc_type_2nd;
                        $item->vacc_card_id = $collect_vac->vacc_card_id;
                        $item->dose_date_1st = $collect_vac->dose_date_1st;
                        $item->dose_date_2nd = $collect_vac->dose_date_2nd;
                        $item->philhealth = $collect_vac->philhealth;
                        $item->bloodtype = $collect_vac->bloodtype;
                        $item->allergy = $collect_vac->allergy;
                        $item->allergy_to_med = $collect_vac->allergy_to_med;
                        $item->med_his = $collect_vac->med_his;
                        $item->other_med_info = $collect_vac->other_med_info;
                        $item->vacc_type_id = $collect_vac->vacc_type_id;
                        $item->vacc_type_2nd_id = $collect_vac->vacc_type_2nd_id;
                        $item->booster_type_id = $collect_vac->booster_type_id;
                        $item->dose_date_booster = $collect_vac->dose_date_booster;
                        $item->vacc_type_booster = $collect_vac->vacc_type_booster;
                  }else{
                        $item->vacc = 0;
                        $item->vacc_type = null;
                        $item->vacc_type_2nd = null;
                        $item->vacc_card_id = null;
                        $item->dose_date_1st = null;
                        $item->dose_date_2nd =null;
                        $item->philhealth = null;
                        $item->bloodtype = null;
                        $item->allergy = null;
                        $item->allergy_to_med = null;
                        $item->med_his = null;
                        $item->other_med_info = null;
                        $item->vacc_type_id = null;
                        $item->vacc_type_2nd_id = null;
                        $item->booster_type_id = null;
                        $item->dose_date_booster = null;
                        $item->vacc_type_booster = null;
                  }

                  array_push($all_enrolledstudents,$item);
            }



            return $all_enrolledstudents;


      }


      //student information
      public static function get_student_information(Request $request){

            $studid = $request->get('studid');

            $studinfo = DB::table('studinfo')
                              ->where('id',$studid)
                              ->where('deleted',0)
                              ->get();

            $studinfo_more = DB::table('studinfo_more')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->get();

            $vaccine_info = DB::table('apmc_midinfo')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->take(1)
                              ->get();

            $curriculum = DB::table('college_studentcurriculum')
                              ->where('studid',$studid)
                              ->get();

            return array((object)[
                  'studinfo'=>$studinfo,
                  'studinfomore'=>$studinfo_more,
                  'vaccineinfo'=>$vaccine_info,
                  'curriculum'=>$curriculum
            ]);

      }

      public static function get_tuition_header(Request $request){

            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $semid = $request->get('semid');

            if($levelid == 14 || $levelid == 15 || ( $levelid >= 17 || $levelid <= 20  )){
                  $temp_fees = DB::table('tuitionheader')
                                    ->where('levelid',$levelid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->where('semid',$semid)
                                    ->get();
            }else{
                  $temp_fees = DB::table('tuitionheader')
                                    ->where('levelid',$levelid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->get();
            }
          

            return $temp_fees;

      }


      public static function collegesubjectload(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $units = DB::table('college_classsched')
                  ->where('college_classsched.syid',$syid)
                  ->where('college_classsched.semesterID',$semid)
                  ->where('college_classsched.deleted',0)
                  ->join('college_studsched',function($join) use($studid){
                        $join->on('college_classsched.id','=','college_studsched.schedid');
                        $join->where('college_studsched.studid',$studid);
                        $join->where('college_studsched.deleted',0);
                        $join->where('college_studsched.schedstatus','!=','DROPPED');
                  })
                  ->join('college_prospectus',function($join) use($studid){
                        $join->on('college_classsched.subjectID','=','college_prospectus.id');
                        $join->where('college_prospectus.deleted',0);
                  })
                  ->select('lecunits','labunits')
                  ->get();

            $totaluntis = 0;

            foreach($units as $item){
                  $temp_units = $item->lecunits + $item->labunits;
                  $totaluntis += $temp_units;
            }

            return array((object)[
                  'units'=>$totaluntis,
                  'subjcount'=>count($units),
                  'subjects'=>$units,
            ]);

      }


      public static function student_balance_info(Request $request){

            $studid = $request->get('studid');

            $studledger = DB::table('studledger')
                              ->where('studid',$studid)
                              ->join('sy',function($join){
                                    $join->on('studledger.syid','=','sy.id');
                              })
                              ->distinct('syid')
                              ->select('syid','sydesc')
                              ->orderBy('sydesc')
                              ->get();

            foreach($studledger as $item){
                  $item->balance = 0;     
                  
                  $ledger = db::table('studledger')
					->select(DB::raw('SUM(amount) - SUM(payment) as balance'))
					->where('studid', $studid)
					->where('syid',$item->syid)
					// ->where(function($q) use($levelid){
		  			// 	if($levelid == 14 || $levelid ==15)
		  			// 	{
		  			// 		$q->where('semid', FinanceModel::getSemID());
		  			// 	}
		  			// 	if($levelid >= 17 && $levelid <=20)
		  			// 	{
		  			// 		$q->where('semid', FinanceModel::getSemID());
		  			// 	}
		  			// })
					->where('deleted', 0)
					->groupBy('studid')
					->first();

                  if(isset($ledger->balance)){
                        if($ledger->balance > 0){
                              $item->balance = $ledger->balance;     
                        }
                  }


            }

            return $studledger;


      }



      public static function cancel_readytoenroll(Request $request){
            try{
                  
                  $studid = $request->get('studid');
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');

                  $utype = auth()->user()->type;

                  $check = DB::table('student_prereginfo')
                              ->where('syid',$syid)
                              ->where('studid',$studid)
                              ->where('semid',$semid)
                              ->first();

                 
                  $prereg_id = $check->id;

                  if($utype == 4 || $utype == 15  || Session::get('currentPortal') == 4  || Session::get('currentPortal') == 15){
                        
                        DB::table('student_prereginfo')
                              ->where('id',$prereg_id)
                              ->where('deleted',0)
                              ->take(1)
                              ->update([
                                    'finance_app'=>'PENDING',
                                    'finance_appdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

                  if($utype == 8  || Session::get('currentPortal') == 8){
                        
                        DB::table('student_prereginfo')
                              ->where('id',$prereg_id)
                              ->where('deleted',0)
                              ->take(1)
                              ->update([
                                    'admission_app'=>'SUBMITTED',
                                    'admission_appdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }


                  return array((object)[
                        'status'=>1,
                        'message'=>'Approved Removed!'
                  ]);

            }catch(\Exception $e){
                  
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }
      }

      public static function readytoenroll(Request $request){
            try{

                  $semid = $request->get('semid');
                  $syid = $request->get('syid');
                  $studid = $request->get('studid');
                  $utype = auth()->user()->type;

                  $check = DB::table('student_prereginfo')
                              ->where('syid',$syid)
                              ->where('studid',$studid)
                              ->where('semid',$semid)
                              ->first();


                  if(!isset($check)){
                        $id = DB::table('student_prereginfo')
                              ->insertGetID([
                                    'syid'=>$syid,
                                    'semid'=>$semid,
                                    'studid'=>$studid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        $id = $check->id;
                  }


                  if($utype == 4 || $utype == 15  || Session::get('currentPortal') == 4  || Session::get('currentPortal') == 15){
                        
                        DB::table('student_prereginfo')
                              ->where('id',$id)
                              ->where('deleted',0)
                              ->take(1)
                              ->update([
                                    'finance_app'=>'APPROVED',
                                    'finance_appdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

                  if($utype == 8  || Session::get('currentPortal') == 8){
                        
                        DB::table('student_prereginfo')
                              ->where('id',$id)
                              ->where('deleted',0)
                              ->take(1)
                              ->update([
                                    'admission_app'=>'APPROVED',
                                    'admission_appdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }


                  return array((object)[
                        'status'=>1,
                        'message'=>'Approved!'
                  ]);

            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }
      }


      public static function allownodp(Request $request){

            try{

                  $studid = $request->get('studid');
                  $syid =  $request->get('syid');
                  $semid =  $request->get('semid');

                  $check = DB::table('student_allownodp')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('deleted',0)
                              ->count();
                  
                  if($check == 0){
                        DB::table('student_allownodp')
                              ->insert([
                                    'studid'=>$studid,
                                    'syid'=>$syid,
                                    'semid'=>$semid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);


                        return array((object)[
                              'status'=>1,
                              'message'=>'No DP Approved!'
                        ]);
                        
                  }else{

                        return array((object)[
                              'status'=>1,
                              'message'=>'No DP Already Approved!'
                        ]);

                  }

                  

            }catch(\Exception $e){

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }
      }

      public static function allownodp_cancel(Request $request){

            try{

                  $studid = $request->get('studid');
                  $syid =  $request->get('syid');
                  $semid =  $request->get('semid');

                  DB::table('student_allownodp')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('deleted',0)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'No DP allowed Cancelled!'
                  ]);

            }catch(\Exception $e){

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }
      

      }


      public static function get_sections(Request $request){

            $syid = $request->get('syid');

            $acadprog = [];
            $utype = auth()->user()->type;

            $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
            $refid = $check_refid->refid;

            if(auth()->user()->type == 17 || $utype == 4 || $utype == 15  || Session::get('currentPortal') == 4  || Session::get('currentPortal') == 15 || Session::get('currentPortal') == 6 || $refid  == 28){

                  $acadprog = DB::table('academicprogram')
                                          ->select('id')
                                          ->get();
      
            }
            else{
      
                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->select('id')
                                    ->first()
                                    ->id;
      
                  if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){
      
                        $acadprog = DB::table('academicprogram')
                                          ->where('principalid',$teacherid)
                                          ->get();
      
                  }else{
      
                        $acadprog = DB::table('teacheracadprog')
                                    ->where('teacherid',$teacherid)
                                    ->where('deleted',0)
                                    ->select('acadprogid as id')
                                    ->distinct('acadprogid')
                                    ->get();
                  }
            }

            $acadprog_list = array();
            foreach($acadprog as $item){
                  array_push($acadprog_list,$item->id);
            }

            $sections = DB::table('sectiondetail')
                              ->join('sections',function($join){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($acadprog_list){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$acadprog_list);
                              })
                              ->leftJoin('rooms',function($join) use($acadprog_list){
                                    $join->on('sectiondetail.sd_roomid','=','rooms.id');
                                    $join->where('rooms.deleted',0);
                              })
                              ->where('sectiondetail.syid',$syid)
                              ->where('sectiondetail.deleted',0)
                              ->select(
                                    'levelid',
                                    'sections.id',
                                    'sectionname',
                                    'acadprogid',
                                    'capacity'
                              )
                              ->get();

            $temp_sections = array();

            foreach($sections as $item){
                  if($item->acadprogid == 5){
                        $enrolled  = DB::table('sh_enrolledstud')
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->where('sectionid',$item->id)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->distinct('studid')
                                          ->count();
                  }else{
                        $enrolled  = DB::table('enrolledstud')
                                         
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->where('sectionid',$item->id)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->distinct('studid')
                                          ->count();
                  }
                  $item->enrolled = $enrolled;
                  $capacity = $item->capacity  == null ? 0 : $item->capacity;
                  $item->text = $item->sectionname . ' (' .$item->enrolled.'/'.$capacity.')';
                  
                  array_push($temp_sections,$item);
            }

            $college_sections = DB::table('college_classsched')
                              ->join('college_sections',function($join) use($acadprog_list){
                                    $join->on('college_classsched.sectionID','=','college_sections.id');
                                    $join->where('college_sections.deleted',0);
                              })
                              ->leftJoin('gradelevel',function($join) use($acadprog_list){
                                    $join->on('college_sections.yearID','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('college_classsched.deleted',0)
                              ->where('college_classsched.syID',$syid)
                              ->select(
                                    'college_sections.yearID as levelid',
                                    'college_sections.id',
                                    'college_sections.sectionDesc as sectionname',
                                    'acadprogid',
                                    'college_sections.semesterID as semid',
                                    'college_sections.courseID',
                                    'college_classsched.id as schedid'
                              )
                              ->get();

            


           // $sections = collect($sections)->values();

      //      $collegeSections = DB::table('college_schedgroup_detail')
      //                               ->leftJoin('college_schedgroup',function($join){
      //                                     $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
      //                                     $join->where('college_schedgroup.deleted',0);
      //                               })
      //                               ->where('college_schedgroup_detail.deleted',0)
      //                               ->where('syid',$syid)
      //                               ->select(
      //                                     'schedid',
      //                                     'schedgroupdesc'
      //                               )
      //                               ->get();

      // $sections = DB::table('college_schedgroup_detail')
      //                   ->where('college_schedgroup_detail.deleted',0)
      //                   ->join('college_schedgroup',function($join){
      //                         $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
      //                         $join->where('college_schedgroup.deleted',0);
      //                   })
      //                   ->leftJoin('college_courses',function($join){
      //                         $join->on('college_schedgroup.courseid','=','college_courses.id');
      //                         $join->where('college_courses.deleted',0);
      //                   })
      //                   ->leftJoin('gradelevel',function($join){
      //                         $join->on('college_schedgroup.levelid','=','gradelevel.id');
      //                         $join->where('gradelevel.deleted',0);
      //                   })
      //                   ->leftJoin('college_colleges',function($join){
      //                         $join->on('college_schedgroup.collegeid','=','college_colleges.id');
      //                         $join->where('college_colleges.deleted',0);
      //                   })
      //                   ->where('syid',$syid)
      //                   ->select(
      //                         'college_schedgroup.courseid',
      //                         'college_schedgroup.levelid',
      //                         'college_schedgroup.collegeid',
      //                         'courseDesc',
      //                         'collegeDesc',
      //                         'levelname',
      //                         'courseabrv',
      //                         'collegeabrv',
      //                         'college_schedgroup.id',
      //                         'college_schedgroup.schedgroupdesc',
      //                         'schedgroupdesc as text',
      //                         'schedid'
      //                   )
      //                   ->get();

            // $collegesection = DB::table('college_classsched')
            //                         ->join('college_schedgroup_detail',function($join){
            //                               $join->on('college_classsched.id','=','college_schedgroup_detail.schedid');
            //                               $join->where('college_schedgroup_detail.deleted',0);
            //                         })
            //                         ->join('college_schedgroup',function($join){
            //                               $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
            //                               $join->where('college_schedgroup.deleted',0);
            //                         })
            //                         ->leftJoin('college_courses',function($join){
            //                               $join->on('college_schedgroup.courseid','=','college_courses.id');
            //                               $join->where('college_courses.deleted',0);
            //                         })
            //                         ->leftJoin('gradelevel',function($join){
            //                               $join->on('college_schedgroup.levelid','=','gradelevel.id');
            //                               $join->where('gradelevel.deleted',0);
            //                         })
            //                         ->leftJoin('college_colleges',function($join){
            //                               $join->on('college_schedgroup.collegeid','=','college_colleges.id');
            //                               $join->where('college_colleges.deleted',0);
            //                         })
            //                         ->select(
            //                               'college_schedgroup.courseid',
            //                               'college_schedgroup.levelid',
            //                               'college_schedgroup.collegeid',
            //                               'courseDesc',
            //                               'collegeDesc',
            //                               'levelname',
            //                               'courseabrv',
            //                               'collegeabrv',
            //                               'college_classsched.sectionID as ',
            //                               'college_schedgroup.schedgroupdesc',
            //                               'schedgroupdesc as text',
            //                               'schedid'
            //                         )
            //                         ->where('college_classsched.syid',$syid)
            //                         ->where('college_classsched.deleted',0)
            //                         ->get();

            

            // foreach($college_sections as $item){

            //       $item->capacity = 50;
            //       $enrolled  = DB::table('college_enrolledstud')
            //                         ->where('deleted',0)
            //                         ->where('syid',$syid)
            //                         ->where('sectionid',$item->id)
            //                         ->whereIn('studstatus',[1,2,4])
            //                         ->distinct('studid')
            //                         ->count();
                 
            //       $item->enrolled = $enrolled;
            //       $capacity = $item->capacity  == null ? 0 : $item->capacity;
            //       $item->text = $item->sectionname . ' (' .$item->enrolled.'/'.$capacity.')';

            //       array_push($temp_sections,$item);
            // }

            return $temp_sections;

      }

      public static function getstudentsection(Request $request){
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $sectionlist = DB::table('college_studsched')
                              ->join('college_classsched',function($join) use($syid){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                    $join->where('college_classsched.syid',$syid);
                                    $join->where('college_classsched.deleted',0);
                              })
                              ->join('college_schedgroup_detail',function($join){
                                    $join->on('college_classsched.id','=','college_schedgroup_detail.schedid');
                                    $join->where('college_schedgroup_detail.deleted',0);
                              })
                              ->join('college_schedgroup',function($join){
                                    $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                    $join->where('college_schedgroup.deleted',0);
                              })
                              ->leftJoin('college_courses',function($join){
                                    $join->on('college_schedgroup.courseid','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->leftJoin('gradelevel',function($join){
                                    $join->on('college_schedgroup.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->leftJoin('college_colleges',function($join){
                                    $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                                    $join->where('college_colleges.deleted',0);
                              })
                              ->select(
                                    'college_schedgroup.courseid',
                                    'college_schedgroup.levelid',
                                    'college_schedgroup.collegeid',
                                    'courseDesc',
                                    'collegeDesc',
                                    'levelname',
                                    'courseabrv',
                                    'collegeabrv',
                                    'college_classsched.sectionID as id',
                                    'college_schedgroup.schedgroupdesc',
                                    'schedgroupdesc as text',
                                    'college_studsched.schedid',
                                    'college_schedgroup_detail.groupid'
                              )
                              ->distinct('groupid')
                              ->where('college_studsched.studid',$studid)
                              ->where('college_studsched.schedstatus','!=','DROPPED')
                              ->where('college_studsched.deleted',0)
                              ->get();

            foreach($sectionlist as $item){
                  $text = '';
                  if($item->courseid != null){
                              $text = $item->courseabrv;
                  }else{
                              $text = $item->collegeabrv;
                  }
                  $text .= '-'.$item->levelname[0] . ' '.$item->schedgroupdesc;
                  $item->text = $text;
            }

            return $sectionlist;
            // return collect($sectionlist)->unique('text')->values();

      }


      public static function get_gradelevel(Request $request, $syid = null){

            if($syid == null){
                  $syid = $request->get('syid');
            }

            $acad = self::get_acad($syid);

            $gradelevel = DB::table('gradelevel')
                              ->where('deleted',0)
                              ->whereIn('acadprogid',$acad)
                              ->orderBy('sortid')
                              ->select(
                                    'id',
                                    'levelname as text',
                                    'levelname',
                                    'acadprogid'
                              )
                              ->get();

            return $gradelevel;

      }


      public static function get_acad($syid = null){

            $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
            $refid = $check_refid->refid;

            if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15 || Session::get('currentPortal') == 17 ||  $refid == 28 || auth()->user()->type == 6 || Session::get('currentPortal') == 6){
                  $acadprog = DB::table('academicprogram')
                                          ->select('id')
                                          ->get();
            }elseif(auth()->user()->type == 14 || Session::get('currentPortal') == 14 ){
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


      public static function preregistration_admission_type(){

            $admission_type = DB::table('early_enrollment_setup_type')
                              ->where('deleted',0)
                              ->select(
                                    'id',
                                    'description as text',
                                    'description'
                              )
                              ->get();

            return $admission_type;

      }


      public static function preregistration_admission_setup(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            //shs and college
            $admission_type = DB::table('early_enrollment_setup')
                              ->join('early_enrollment_setup_type',function($join){
                                    $join->on('early_enrollment_setup.type','=','early_enrollment_setup_type.id');
                                    $join->where('early_enrollment_setup_type.deleted',0);
                              })
                              ->where('early_enrollment_setup.syid',$syid)
                              ->where('early_enrollment_setup.deleted',0)
                              ->where('isactive',1)
                              // ->where('semid',$semid)
                              ->select(
                                    'early_enrollment_setup.id',
                                    'acadprogid',
                                    'description as text'
                              )
                              ->get();

            // $temp_type = DB::table('early_enrollment_setup')
            //                               ->join('early_enrollment_setup_type',function($join){
            //                                     $join->on('early_enrollment_setup.type','=','early_enrollment_setup_type.id');
            //                                     $join->where('early_enrollment_setup_type.deleted',0);
            //                               })
            //                               ->whereIn('acadprogid',[5,6])
            //                               ->where('early_enrollment_setup.syid',$syid)
            //                               ->where('early_enrollment_setup.deleted',0)
            //                               ->where('isactive',1)
            //                               ->where('semid',$semid)
            //                               ->select(
            //                                     'early_enrollment_setup.id',
            //                                     'acadprogid',
            //                                     'description as text'
            //                               )
            //                               ->get();

            return $admission_type;

      }

      public static function preenrolledstudents(Request $request){



            $syid = $request->get('syid');
            $studstat = $request->get('studstat');
            $paystat = $request->get('paystat');
            $activestatus = $request->get('activestatus');
            $fillevelid = $request->get('fillevelid');
            $fillsectionid = $request->get('fillsectionid');

            $semid = $request->get('semid');
            $ghssemid = $semid == 3 ? [$semid] : [1,2];
            $all_acad = self::get_acad($syid);
            $schoolinfo = DB::table('schoolinfo')->first();
            $all_enrolledstudents = array();

            $search = $request->get('search');
            $search = isset($search['value']) ? $search['value'] : '';
            $transdate = $request->get('transdate');
            $processtype = $request->get('procctype');
            $enrollmentdate = $request->get('enrollmentdate');

            $levelacad = null;
            if($fillevelid != 0 && $fillevelid != ''){
                  $levelacad = DB::table('gradelevel')
                                    ->where('id',$fillevelid)
                                    ->first()
                                    ->acadprogid;
            }

     

            if($processtype != ""){

                  $basiced = DB::table('student_pregistration')
                                    ->where('student_pregistration.syid',$syid)
                                    ->join('gradelevel',function($join){
                                          $join->on('student_pregistration.gradelvl_to_enroll','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                          $join->whereIn('acadprogid',[2,3,4]);
                                    })
                                    ->join('early_enrollment_setup',function($join) use($processtype){
                                          $join->on('student_pregistration.admission_type','=','early_enrollment_setup.id');
                                          $join->where('early_enrollment_setup.deleted',0);
                                          $join->where('early_enrollment_setup.type',$processtype);
                                    })
                                    ->select('studid')
                                    ->where('student_pregistration.deleted',0)
                                    ->get();


                  $withsem = DB::table('student_pregistration')
                                    ->where('student_pregistration.syid',$syid)
                                    ->where('student_pregistration.semid',$semid)
                                    ->join('gradelevel',function($join){
                                          $join->on('student_pregistration.gradelvl_to_enroll','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                          $join->whereIn('acadprogid',[5,6]);
                                    })
                                    ->join('early_enrollment_setup',function($join) use($processtype){
                                          $join->on('student_pregistration.admission_type','=','early_enrollment_setup.id');
                                          $join->where('early_enrollment_setup.deleted',0);
                                          $join->where('early_enrollment_setup.type',$processtype);
                                    })
                                    ->select('studid')
                                    ->where('student_pregistration.deleted',0)
                                    ->get();

                  $preenrolled = $basiced->merge($withsem);
                
            }

            $all_gradelevel = DB::table('gradelevel')
                                    ->whereIn('acadprogid',$all_acad)
                                    ->where('deleted',0)
                                    ->select(
                                          'acadprogid',
                                          'levelname',
                                          'id',
                                          'nodp'
                                    )
                                    ->get();

            $enrolled = DB::table('enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->join('sections',function($join){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              });

            if($studstat != 0 && $studstat != ''){
                  $enrolled  =  $enrolled->where('studstatus',$studstat);

                  if($transdate != 0 && $transdate != ''){
                        $temptransdate = explode(' - ',$transdate);
                        $startdate = \Carbon\Carbon::create($temptransdate[0].' 00:00')->isoFormat('YYYY-MM-DD');
                        $enddate = \Carbon\Carbon::create($temptransdate[1].' 24:00')->isoFormat('YYYY-MM-DD');
                        $enrolled  =  $enrolled->whereBetween('enrolledstud.createddatetime', [$startdate,$enddate]);
                  }

                  if($enrollmentdate != 0 && $enrollmentdate != ''){
                        $temptransdate = explode(' - ',$enrollmentdate);
                        $startdate = \Carbon\Carbon::create($temptransdate[0])->isoFormat('YYYY-MM-DD');
                        $enddate = \Carbon\Carbon::create($temptransdate[1])->isoFormat('YYYY-MM-DD');
                        $enrolled  =  $enrolled->whereBetween('enrolledstud.dateenrolled', [$startdate,$enddate]);
                  }
                  

            }

            if($fillevelid != 0 && $fillevelid != '' ){
                  $enrolled  =  $enrolled->where('enrolledstud.levelid',$fillevelid);
            }

            if($processtype != ""){
                  $enrolled  =  $enrolled->whereIn('enrolledstud.studid',collect($preenrolled)->pluck('studid'));
            }

            if($fillsectionid != 0 && $fillsectionid != ''){
                  if($levelacad == 2 || $levelacad == 3 || $levelacad == 4){
                        $enrolled  =  $enrolled->where('enrolledstud.sectionid',$fillsectionid);
                  }
            }

            $enrolled = $enrolled->join('gradelevel',function($join) use($all_acad){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->leftJoin('early_enrollment_setup',function($join){
                                    $join->on('enrolledstud.admissiontype','=','early_enrollment_setup.id');
                                    $join->where('early_enrollment_setup.deleted',0);
                              })
                              ->where('enrolledstud.syid',$syid)
                              ->whereIn('ghssemid',$ghssemid)
                              ->where('enrolledstud.deleted',0)
                              ->select(
                                    'dateenrolled',
                                    'enrolledstud.levelid',
                                    'levelname',
                                    'studid',
                                    'sectionid',
                                    'description',
                                    'studstatus',
                                    'studstatdate',
                                    'sectionname',
                                    // 'isearly',
                                    'sortid',
                                    'remarks',
                                    'promotionstatus',
                                    'studmol',
                                    'early_enrollment_setup.type'
                              )
                              ->distinct('studid')
                              ->get();

            foreach($enrolled as $item){
                  array_push($all_enrolledstudents,$item);
            }
            

            $enrolled = DB::table('sh_enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                              });
                              
            if($studstat != 0 && $studstat != ''){
                  $enrolled  =  $enrolled->where('studstatus',$studstat);

                  if($transdate != 0 && $transdate != ''){
                        $temptransdate = explode(' - ',$transdate);
                        $startdate = \Carbon\Carbon::create($temptransdate[0].' 00:00')->isoFormat('YYYY-MM-DD');
                        $enddate = \Carbon\Carbon::create($temptransdate[1].' 24:00')->isoFormat('YYYY-MM-DD');
                        $enrolled  =  $enrolled->whereBetween('sh_enrolledstud.createddatetime', [$startdate,$enddate]);
                  }

                  if($enrollmentdate != 0 && $enrollmentdate != ''){
                        $temptransdate = explode(' - ',$enrollmentdate);
                        $startdate = \Carbon\Carbon::create($temptransdate[0])->isoFormat('YYYY-MM-DD');
                        $enddate = \Carbon\Carbon::create($temptransdate[1])->isoFormat('YYYY-MM-DD');
                        $enrolled  =  $enrolled->whereBetween('sh_enrolledstud.dateenrolled', [$startdate,$enddate]);
                  }
            }

            if($fillevelid != 0 && $fillevelid != '' ){
                  $enrolled  =  $enrolled->where('sh_enrolledstud.levelid',$fillevelid);
            }

            if($processtype != ""){
                  $enrolled  =  $enrolled->whereIn('sh_enrolledstud.studid',collect($preenrolled)->pluck('studid'));
            }

            if($fillsectionid != 0 && $fillsectionid != ''){
                  if($levelacad == 5){
                        $enrolled  =  $enrolled->where('sh_enrolledstud.sectionid',$fillsectionid);
                  }
            }


            $enrolled = $enrolled->join('sections',function($join){
                                    $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->leftJoin('early_enrollment_setup',function($join){
                                    $join->on('sh_enrolledstud.admissiontype','=','early_enrollment_setup.id');
                                    $join->where('early_enrollment_setup.deleted',0);
                              })
                              ->leftJoin('sh_strand',function($join) use($all_acad){
                                    $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                              })
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->select(
                                    'dateenrolled',
                                    'sh_enrolledstud.levelid',
                                    'levelname',
                                    'sectionid',
                                    'studid',
                                    'description',
                                    'studstatus',
                                    'sectionname',
                                    'studstatdate',
                                    // 'isearly',
                                    'sortid',
                                    'strandid',
                                    'promotionstatus',
                                    'studmol',
                                    'remarks',
                                    'early_enrollment_setup.type',
                                    'strandcode'
                              )
                              ->distinct('studid')
                              ->get();

            foreach($enrolled as $item){
                  array_push($all_enrolledstudents,$item);
            }

            $enrolled = DB::table('college_enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('college_enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->leftJoin('college_sections',function($join){
                                    $join->on('college_enrolledstud.sectionID','=','college_sections.id');
                                    $join->where('college_sections.deleted',0);
                              })
                              ->leftJoin('college_classsched',function($join){
                                    $join->on('college_sections.id','=','college_classsched.sectionid');
                                    $join->where('college_classsched.deleted',0);
                              });

            if($studstat != 0 && $studstat != ''){
                  $enrolled  =  $enrolled->where('studstatus',$studstat);
                  if($transdate != 0 && $transdate != ''){
                        $temptransdate = explode(' - ',$transdate);
                        $startdate = \Carbon\Carbon::create($temptransdate[0].' 00:00')->isoFormat('YYYY-MM-DD');
                        $enddate = \Carbon\Carbon::create($temptransdate[1].' 24:00')->isoFormat('YYYY-MM-DD');
                        $enrolled  =  $enrolled->whereBetween('college_enrolledstud.createddatetime', [$startdate,$enddate]);
                  }

                  if($enrollmentdate != 0 && $enrollmentdate != ''){
                        $temptransdate = explode(' - ',$enrollmentdate);
                        $startdate = \Carbon\Carbon::create($temptransdate[0])->isoFormat('YYYY-MM-DD');
                        $enddate = \Carbon\Carbon::create($temptransdate[1])->isoFormat('YYYY-MM-DD');
                        $enrolled  =  $enrolled->whereBetween('college_enrolledstud.date_enrolled', [$startdate,$enddate]);
                  }
            }

            if($fillevelid != 0 && $fillevelid != '' ){
                  $enrolled  =  $enrolled->where('college_enrolledstud.yearLevel',$fillevelid);
            }

            if($processtype != ""){
                  $enrolled  =  $enrolled->whereIn('college_enrolledstud.studid',collect($preenrolled)->pluck('studid'));
            }

            if($fillsectionid != 0 && $fillsectionid != ''){
                  if($levelacad == 5){
                        $enrolled  =  $enrolled->where('college_enrolledstud.sectionID',$fillsectionid);
                  }
            }

            $enrolled  =  $enrolled->join('gradelevel',function($join) use($all_acad){
                                    $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->leftJoin('early_enrollment_setup',function($join){
                                    $join->on('college_enrolledstud.admissiontype','=','early_enrollment_setup.id');
                                    $join->where('early_enrollment_setup.deleted',0);
                              })
                              ->where('college_enrolledstud.syid',$syid)
                              ->where('college_enrolledstud.syid',$syid)
                              ->where('college_enrolledstud.semid',$semid)
                              ->where('college_enrolledstud.deleted',0)
                              ->select(
                                    'college_enrolledstud.date_enrolled as dateenrolled',
                                    'college_enrolledstud.yearLevel as levelid',
                                    'college_enrolledstud.sectionID as sectionid',
                                    'levelname',
                                    'studid',
                                    'description',
                                    'studstatus',
                                    'sectionDesc as sectionname',
                                    // 'studstatdate',
                                    // 'isearly',
                                    'remarks',
                                    'sortid',
                                    'promotionstatus',
                                    'studmol',
                                    'early_enrollment_setup.type',
                                    'college_enrolledstud.courseid',
                                    'regStatus',
                                    'college_classsched.id as schedid'
                              )
                              ->distinct('studid')
                              ->get();

            if(count($enrolled) > 0){

                  $collegesection = DB::table('college_schedgroup_detail')
                                    ->where('college_schedgroup_detail.deleted',0)
                                    ->whereIn('schedid',collect($enrolled)->pluck('schedid'))
                                    ->join('college_schedgroup',function($join){
                                          $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                          $join->where('college_schedgroup.deleted',0);
                                    })
                                    ->leftJoin('college_courses',function($join){
                                          $join->on('college_schedgroup.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->leftJoin('gradelevel',function($join){
                                          $join->on('college_schedgroup.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->leftJoin('college_colleges',function($join){
                                          $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                                          $join->where('college_colleges.deleted',0);
                                    })
                                    ->select(
                                          'college_schedgroup.courseid',
                                          'college_schedgroup.levelid',
                                          'college_schedgroup.collegeid',
                                          'courseDesc',
                                          'collegeDesc',
                                          'levelname',
                                          'courseabrv',
                                          'collegeabrv',
                                          'college_schedgroup.id',
                                          'college_schedgroup.schedgroupdesc',
                                          'schedgroupdesc as text',
                                          'schedid'
                                    )
                                    ->get();

                        foreach($enrolled as $item){
                              $courseid = $item->courseid;
                              $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('courseid',$courseid)->values();
                              if(count($checkcoursegroup) != 0){
                                    $text = $checkcoursegroup[0]->courseabrv;
                                    $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                    $item->sectionname = $text;   
                              }else{
                                    $collegeid = DB::table('college_courses')
                                                      ->where('id',$courseid)
                                                      ->select('collegeid')
                                                      ->first();
                                    if(isset($collegeid)){
                                          $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('collegeid',$collegeid->collegeid)->values();
                                          if(count($checkcoursegroup) != 0){
                                                $text = $checkcoursegroup[0]->collegeabrv;
                                                $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                                $item->sectionname = $text;  
                                          }else{
                                                $item->sectionname = 'Not Found';
                                          }
                                    }else{
                                          $item->sectionname = null;
                                    }
                              }
                        }
            }
                                    
            // $enrolled = DB::table('college_enrolledstud')
            //              ->whereIn('yearLevel',collect($all_gradelevel)->pluck('id'));

            // if($studstat != 0 && $studstat != ''){
            //       $enrolled  =  $enrolled->where('studstatus',$studstat);
            // }

            // if($fillevelid != 0 && $fillevelid != ''){
            //       $enrolled  =  $enrolled->where('college_enrolledstud.yearLevel',$fillevelid);
            // }

            // $enrolled = $enrolled->where('college_enrolledstud.syid',$syid)
            //                         ->where('college_enrolledstud.semid',$semid)
            //                         ->where('college_enrolledstud.deleted',0)
            //                         ->select(
            //                               'date_enrolled as dateenrolled',
            //                               'yearLevel as levelid',
            //                               'sectionID as sectionid',
            //                               'studid',
            //                               'studstatus',
            //                               'promotionstatus',
            //                               'studmol',
            //                               'admissiontype'
            //                         )
            //                         ->distinct('studid')
            //                         ->get();

            foreach($enrolled as $item){
                  array_push($all_enrolledstudents,$item);
            }

            $student_array = collect($all_enrolledstudents)->pluck('studid');


            $gshs_levelid = collect($all_gradelevel)->whereIn('acadprogid',[2,3,4])->values();
            $sh_levelid = collect($all_gradelevel)->whereIn('acadprogid',[5])->values();
            $college_levelid = collect($all_gradelevel)->whereIn('acadprogid',[6])->values();
            $shs_semid = [$semid];

            if($schoolinfo->shssetup == 1){
                  $shs_semid = $semid != 3 ? [1,2] : [$semid];
            }

            $chrng_students = array();


            if($paystat != "" && $paystat != null && ($studstat == 0 || $studstat == '')){

                  $gshs_chrngtrans = DB::table('chrngtrans');

                  if($studstat != 0 && $studstat != ''){
                        $gshs_chrngtrans = $gshs_chrngtrans->whereIn('chrngtrans.studid',$student_array);
                  }else if($studstat == 0 && $studstat != ''){
                        $gshs_chrngtrans = $gshs_chrngtrans->whereNotIn('chrngtrans.studid',$student_array);
                  }

                  $gshs_chrngtrans = $gshs_chrngtrans->join('studinfo',function($join){
                                          $join->on('chrngtrans.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('tuitionheader',function($join) use($gshs_levelid){
                                          $join->on('studinfo.feesid','=','tuitionheader.id');
                                          $join->where('tuitionheader.deleted',0);
                                          $join->whereIn('tuitionheader.levelid',collect($gshs_levelid)->pluck('id'));
                                    })
                                    ->where('chrngtrans.syid',$syid)
                                    ->where('cancelled',0)
                                    ->whereIn('chrngtrans.semid',$ghssemid)
                                    ->select(
                                          'transno'
                                    )
                                    ->get();

        

                  $college_chrngtrans = DB::table('chrngtrans');

                  if($studstat != 0 && $studstat != ''){
                        $college_chrngtrans = $college_chrngtrans->whereIn('chrngtrans.studid',$student_array);
                  }else if($studstat == 0 && $studstat != ''){
                        $college_chrngtrans = $college_chrngtrans->whereNotIn('chrngtrans.studid',$student_array);
                  }

                  $college_chrngtrans = $college_chrngtrans->join('studinfo',function($join){
                                                $join->on('chrngtrans.studid','=','studinfo.id');
                                                $join->where('studinfo.deleted',0);
                                          })
                                          ->join('tuitionheader',function($join) use($college_levelid){
                                                $join->on('studinfo.feesid','=','tuitionheader.id');
                                                $join->where('tuitionheader.deleted',0);
                                                $join->whereIn('tuitionheader.levelid',collect($college_levelid)->pluck('id'));
                                          })
                                          ->where('chrngtrans.syid',$syid)
                                          ->where('cancelled',0)
                                          ->where('chrngtrans.semid',$semid)
                                          ->select(
                                                'studid',
                                                'transno'
                                          )
                                          ->get();
                                         
             

                  $sh_chrngtrans = DB::table('chrngtrans')
                                          ->join('studinfo',function($join){
                                                $join->on('chrngtrans.studid','=','studinfo.id');
                                                $join->where('studinfo.deleted',0);
                                          });

                  if($studstat != 0 && $studstat != ''){
                        $sh_chrngtrans = $sh_chrngtrans->whereIn('chrngtrans.studid',$student_array);
                  }else if($studstat == 0 && $studstat != ''){
                        $sh_chrngtrans = $sh_chrngtrans->whereNotIn('chrngtrans.studid',$student_array);
                  }

                  $sh_chrngtrans = $sh_chrngtrans->join('tuitionheader',function($join) use($sh_levelid){
                                                $join->on('studinfo.feesid','=','tuitionheader.id');
                                                $join->where('tuitionheader.deleted',0);
                                                $join->whereIn('tuitionheader.levelid',collect($sh_levelid)->pluck('id'));
                                          })
                                          ->where('chrngtrans.syid',$syid)
                                          ->where('cancelled',0)
                                          ->where('chrngtrans.semid',$shs_semid)
                                          ->select(
                                                'transno'
                                          )
                                          ->get();

                  $data = $gshs_chrngtrans->merge($sh_chrngtrans);
                  $data = $data->merge($college_chrngtrans);               

                  $cashtrans = DB::table('chrngcashtrans')
                                    ->where('syid',$syid)
                                    ->WhereIn ('transno',collect($data)->pluck('transno'))
                                    ->where('deleted',0)
                                    ->where(function($query){
                                          $query->where('kind','!=','item');
                                          $query->where('kind','!=','old');
                                    })
                                    ->groupBy('studid')
                                    ->select(
                                          'studid'
                                    )
                                    ->get();


                  //filter no dp
                  $no_dp_gshs = DB::table('student_allownodp')
                                    ->where('syid',$syid)
                                    ->where('semid',$ghssemid)
                                    ->select(
                                          'studid'
                                    )
                                    ->where('deleted',0)
                                    ->get();

                  $no_dp_sh = DB::table('student_allownodp')
                                    ->where('syid',$syid)
                                    ->where('semid',$shs_semid)
                                    ->where('deleted',0)
                                    ->select(
                                          'studid'
                                    )
                                    ->get();

                  $no_dp_college = DB::table('student_allownodp')
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('deleted',0)
                                    ->select(
                                          'studid'
                                    )
                                    ->get();

                  $no_dp_data = $no_dp_gshs->merge($no_dp_sh);
                  $no_dp_data = $no_dp_data->merge($no_dp_college);     


                  //merge cashtrans and no dp
                  $cashtrans = $cashtrans->merge($no_dp_data);     

            }
            

            $all_students = DB::table('studinfo')
                              ->where('studinfo.deleted',0)
                              ->join('gradelevel',function($join) use($all_acad){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$all_acad);
                              })
                              ->leftJoin('sh_strand',function($join){
                                    $join->on('studinfo.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                              })->leftJoin('college_courses',function($join){
                                    $join->on('studinfo.courseid','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                              });

            if($search != null){
                  $all_students = $all_students->where(function($query) use($search){
                                    $query->orWhere('firstname','like','%'.$search.'%');
                                    $query->orWhere('lastname','like','%'.$search.'%');
                                    $query->orWhere('sid','like','%'.$search.'%');
                              });
            }

          

            if($studstat != 0 && $studstat != ''){
                  $all_students = $all_students->whereIn('studinfo.id',$student_array);
            }else if($studstat == 0 && $studstat != ''){
                  $all_students = $all_students->whereNotIn('studinfo.id',$student_array);
            }

            if($fillevelid != 0 && $fillevelid != '' &&  ($studstat == 0 || $studstat == '')){
                  $all_students  =  $all_students->where('studinfo.levelid',$fillevelid);
            }

            if($processtype != ""){
                  $all_students  =  $all_students->whereIn('studinfo.id',collect($preenrolled)->pluck('studid'));
            }

            if($activestatus != null && $activestatus != ""){
                  $all_students  =  $all_students->where('studinfo.studisactive',$activestatus);
            }

            if($paystat != "" && $paystat != null && ($studstat == 0 || $studstat == '') ){
                  if($paystat == 1){
                        $all_students  =  $all_students->whereIn('studinfo.id',collect($cashtrans)->pluck('studid'));
                  }else if($paystat == 2){
                        $all_students  =  $all_students->whereNotIn('studinfo.id',collect($cashtrans)->pluck('studid'));
                  }
            }

            // Check if 'length' and 'start' parameters are present in the request
            if ($request->has('length') && $request->has('start')) {
                  $all_students = $all_students->take($request->get('length'))
                                          ->skip($request->get('start'));
            }
            
            // Continue with the rest of the query
            $all_students = $all_students->orderBy('lastname')
                                          ->select(
                                                'collegeid',
                                                'contactno',
                                                'ismothernum',
                                                'isfathernum',
                                                'isguardannum',
                                                'mcontactno',
                                                'fcontactno',
                                                'gcontactno',
                                                'mothername',
                                                'fathername',
                                                'guardianname',
                                                'gradelevel.levelname',
                                                'gradelevel.levelname as curlevelname',
                                                'studinfo.levelid as curlevelid',
                                                'studinfo.levelid',
                                                'studinfo.sectionid',
                                                'studinfo.grantee',
                                                'studinfo.pantawid',
                                                'studinfo.mol',
                                                'lastname',
                                                'firstname',
                                                'middlename',
                                                'suffix',
                                                'sid',
                                                'strandname',
                                                'strandid',
                                                'gradelevel.sortid',
                                                'studstatus',
                                                'courseid',
                                                'studinfo.id as studid',
                                                'studisactive'
                                          )->get();

          

            $student_count = DB::table('studinfo')
                                    ->join('gradelevel',function($join) use($all_acad){
                                          $join->on('studinfo.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                          $join->whereIn('gradelevel.acadprogid',$all_acad);
                                    })
                                    ->where('studinfo.deleted',0);

            if($search != null){
                  $student_count = $student_count->where(function($query) use($search){
                                    $query->orWhere('firstname','like','%'.$search.'%');
                                    $query->orWhere('lastname','like','%'.$search.'%');
                                    $query->orWhere('sid','like','%'.$search.'%');
                              });
            }
            
            if($activestatus != null && $activestatus != ""){
                  $student_count  =  $student_count->where('studinfo.studisactive',$activestatus);
            }

            if($studstat != 0 && $studstat != ''){
                  $student_count = $student_count->whereIn('studinfo.id',$student_array);
            }else if($studstat == 0 && $studstat != ''){
                  $student_count = $student_count->whereNotIn('studinfo.id',$student_array);
            }  
            
            if($processtype != ""){
                  $student_count  =  $student_count->whereIn('studinfo.id',collect($preenrolled)->pluck('studid'));
            }
            
            if($paystat != "" && $paystat != null && ($studstat == 0 || $studstat == '')){
                  if($paystat == 1){
                        $student_count  =  $student_count->whereIn('studinfo.id',collect($cashtrans)->pluck('studid'));
                  }else if($paystat == 2){
                        $student_count  =  $student_count->whereNotIn('studinfo.id',collect($cashtrans)->pluck('studid'));
                  }
            }
            
            if($fillevelid != 0 && $fillevelid != '' &&  ($studstat == 0 || $studstat == '')){
                  $student_count  =  $student_count->where('studinfo.levelid',$fillevelid);
            }
            
            $student_count = $student_count->count();

            $students = DB::table('student_pregistration')
                              ->join('early_enrollment_setup',function($join){
                                    $join->on('student_pregistration.admission_type','=','early_enrollment_setup.id');
                                    $join->where('early_enrollment_setup.deleted',0);
                              })
                              ->join('early_enrollment_setup_type',function($join){
                                    $join->on('early_enrollment_setup.type','=','early_enrollment_setup_type.id');
                                    $join->where('early_enrollment_setup_type.deleted',0);
                              })
                              ->whereNotNull('student_pregistration.admission_type')
                              ->join('gradelevel as lvltoenroll',function($join) use($all_acad){
                                    $join->on('student_pregistration.gradelvl_to_enroll','=','lvltoenroll.id');
                                    $join->whereIn('lvltoenroll.acadprogid',$all_acad);
                              })
                              ->whereIn('studid',collect($all_students)->pluck('studid'))
                              ->select(
                                    'gradelvl_to_enroll',
                                    'student_pregistration.studid',
                                    'student_pregistration.status',
                                    'admission_type',
                                    'description as admission_type_desc',
                                    'type',
                                    'student_pregistration.id',
                                    'lvltoenroll.levelname as lvltoenrollname',
                                    'student_pregistration.id',
                                    'student_pregistration.syid',
                                    'student_pregistration.semid',
                                    'student_pregistration.createddatetime',
                                    'finance_status',
                                    'finance_statusdatetime',
                                    'admission_status',
                                    'admission_statusdatetime',
                                    'admission_strand',
                                    'admission_course',
                                    'allownodp',
                                    'transtype'
                              )
                              ->distinct('studid')
                              ->where('student_pregistration.syid',$syid)
                              ->where('student_pregistration.semid',$semid)
                              ->where('student_pregistration.deleted',0)
                              ->get();


                        

                              
            $modeoflearning = DB::table('modeoflearning_student')
                                    ->where('deleted',0)
                                    ->whereIn('studid',collect($all_students)->pluck('studid'))
                                    ->where('syid',$syid)
                                    ->select(
                                          'studid',
                                          'mol'
                                    )
                                    ->get();

                                    
          
                                
            $school_info = DB::table('schoolinfo')->select('abbreviation')->first();
            
            if($school_info->abbreviation == 'BCT'){
                  $payment_info = db::table('chrngtrans')
                                    ->select('chrngtrans.id', 'chrngtransdetail.amount', 'items','studid','semid')
                                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                                    ->where('cancelled', 0)
                                    ->where('classid', 106)
                                    ->get();
            }else if($school_info->abbreviation == 'VNBC' || $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){


                  $chngtrans = DB::table('chrngtransdetail')				
                                    ->select('studid', 'chrngtrans.syid', 'chrngtrans.semid', 'itemkind', 'payschedid', 'items.isdp',db::raw('SUM(chrngtrans.`amountpaid`) AS amount'))
                                    ->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
                                    ->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
                                    ->where('chrngtrans.syid', $syid)
                                    ->where('chrngtrans.semid', $semid)
                                    ->where('itemkind', 1)
                                    ->where('items.isdp', 1)
                                    ->where('cancelled', 0)
                                    ->groupBy('studid')
                                    ->get();


                                   

            }else if($school_info->abbreviation == 'SAIT'){

                  $payment_info_sh = [];
			$payment_info_gshs = [];
			$payment_info_college = [];
                  $chngtrans = [];
                  
            }
            else if($school_info->abbreviation == 'HCCSI' ){
                  $chngtrans = [];
                  $cashtrans = [];


                 

            }else{

                  // if($paystat == "" || $paystat == null){

                        $chngtrans = DB::table('chrngtrans')
                                          ->where('syid',$syid)
                                          ->whereIn('studid',collect($all_students)->pluck('studid'))
                                          ->where('cancelled',0)
                                          ->select(
                                                'transno',
                                                'syid',
                                                'studid',
                                                'semid'
                                          )
                                          ->get();

                      

                        $cashtrans = DB::table('chrngcashtrans')
                                          ->where('syid',$syid)
                                          ->where('deleted',0)
                                          ->whereIn('transno',collect($chngtrans)->pluck('transno'))
                                          ->where(function($query){
                                                $query->where('kind','!=','item');
                                                $query->where('kind','!=','old');
                                          })
                                          ->select(
                                                'transno',
                                                'syid',
                                                'studid',
                                                'amount',
                                                'semid'
                                          )
                                          ->get();


                  // }

            }

            $enrollment_approval = DB::table('student_prereginfo')
                              ->whereIn('studid',collect($all_students)->pluck('studid'))
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('deleted',0)
                              ->select(
                                    'finance_app',
                                    'finance_appdatetime',
                                    'admission_appdatetime',
                                    'dean_appdatetime',
                                    'dean_app',
                                    'admission_app',
                                    'studid',
                                    'syid',
                                    'semid'
                              )
                              ->get();

            $no_dp = DB::table('student_allownodp')
                              ->whereIn('studid',collect($all_students)->pluck('studid'))
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->get();

            foreach($all_students as $item){

                  $check_approval =  collect($enrollment_approval)->where('studid',$item->studid)->first();
                  $enrollment_approval = collect($enrollment_approval)->where('studid','!=',$item->studid);
                  if(isset($check_approval->studid)){
                        $item->finance_status = $check_approval->finance_app == 'PENDING' ? 'SUBMITTED' : 'APPROVED' ;
                        $item->finance_statusdatetime = $check_approval->finance_app == 'PENDING' ? null : $check_approval->finance_appdatetime;
                        $item->admission_status = $check_approval->admission_app == 'PENDING' ? 'SUBMITTED' : 'APPROVED';
                        $item->admission_statusdatetime = $check_approval->admission_app == 'PENDING' ? null : $check_approval->admission_appdatetime;
                  }else{
                        $item->finance_status = 'SUBMITTED';
                        $item->finance_statusdatetime = null;
                        $item->admission_status = 'SUBMITTED';
                        $item->admission_statusdatetime = null;
                  }


                  $check_preg = collect($students)->where('studid',$item->studid)->first();

                  if(isset($check_preg->studid)){
                        $item->withprereg = 1;
                        $item->gradelvl_to_enroll = $check_preg->gradelvl_to_enroll;
                        $item->status = $check_preg->status ;
                        $item->admission_type = $check_preg->admission_type;
                        $item->admission_type_desc = $check_preg->admission_type_desc;
                        $item->type = $check_preg->type;
                        $item->id = $check_preg->id;
                        $item->lvltoenrollname = $check_preg->lvltoenrollname;
                        $item->syid = $check_preg->syid;
                        $item->semid = $check_preg->semid;
                        $item->createddatetime = $check_preg->createddatetime;
                        $item->admission_strand = $check_preg->admission_strand;
                        $item->admission_course = $check_preg->admission_course;
                        $item->transtype = $check_preg->transtype;
                  }else{
                        $item->withprereg = 0;
                        $item->gradelvl_to_enroll = null;
                        $item->status = 'SUBMITTED';
                        $item->admission_type = null;
                        $item->admission_type_desc = null;
                        $item->type = null;
                        $item->id = null;
                        $item->lvltoenrollname = null;
                        $item->syid = $syid;
                        $item->semid = $semid;
                        $item->createddatetime = null;
                        $item->admission_strand = $item->strandid;
                        $item->admission_course = $item->courseid;
                        $item->transtype = 'WALK IN';
                  }

                  $temp_middle = '';
                  $temp_suffix = '';

                  if(isset($item->middlename)){
                        // if(strlen($item->middlename) > 0){
                        //       $temp_middle = ' '.$item->middlename[0].'.';

                        // }
                        $temp_middle = ' '.$item->middlename;
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix.' ';
                  }

                  $item->student = $item->lastname.', '.$item->firstname.$temp_suffix.$temp_middle;

                  $item->submission = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY');

                  //check enrollment
                  $check = collect($all_enrolledstudents)->where('studid',$item->studid)->first();
                  $item->strandcode = null;
                  
                  if(isset($check->studid)){
                  
                        if($check->dateenrolled == null){
                              $item->dateenrolled = '';
                              $item->enrollment = '';
                        }else{
                              $item->dateenrolled = \Carbon\Carbon::create($check->dateenrolled)->isoFormat('YYYY-MM-DD');
                              $item->enrollment = \Carbon\Carbon::create($check->dateenrolled)->isoFormat('MMM DD, YYYY');
                        }
                        $item->mol = $check->studmol;
                        $item->ensectionid = $check->sectionid;
                        $item->enlevelid = $check->levelid;
                        $item->promotionstatus = $check->promotionstatus;
                        $item->description = $check->description;
                        $item->studstatus = $check->studstatus;
                       
                        $item->sectionname = $check->sectionname;
                        $item->levelid = $check->levelid;
                        $item->levelname = $check->levelname;
                        $item->remarks = $check->remarks;
                        if($check->levelid == 14 || $check->levelid == 15){
                              $item->strandid = $check->strandid;
                              $item->strandcode = $check->strandcode;
                        }else{
                              $item->strandid = null;
                        }

                        if($check->levelid >= 17 && $check->levelid <= 21){
                              $item->courseid = $check->courseid;
                              $item->regStatus = $check->regStatus;
                              $item->studstatdate = null;
                        }else{
                              $item->courseid = null;
                              $item->regStatus = null;
                              $item->studstatdate = $check->studstatdate;
                        }
                        $item->type = $check->type;
                        $all_enrolledstudents = collect($all_enrolledstudents)
                                                      ->where('studid','!=',$check->studid)
                                                      ->values();

                  }else{
                  
                        $item->promotionstatus = 0;
                        $item->enrollment = '';
                        $item->description = 'NOT ENROLLED';
                        $item->sectionname = '--';
                        $item->studstatus = 0;
                        $item->remarks = null;
                        if($item->gradelvl_to_enroll != null && $item->gradelvl_to_enroll != ""){
                              $item->levelid = $item->gradelvl_to_enroll;
                              $item->levelname = $item->lvltoenrollname;
                        }
                        $check_mol = collect($modeoflearning)->where('studid',$item->studid)->first();
                        if(isset($check_mol->mol)){
                              $item->mol = $check_mol->mol;
                        }else{
                              $item->mol = $item->mol;
                        }
                  }
                 

                  $item->levelname = str_replace(' COLLEGE','',$item->levelname);
                  $item->curlevelname = str_replace(' COLLEGE','',$item->curlevelname);
                  $item->search =  $item->sid.' - '.$item->student.' - '.$item->levelname.' - '.$item->sectionname;

                  if(( $item->levelid >= 17 && $item->levelid <= 21 )){
                        // $check_payment = collect($payment_info_college)->where('studid',$item->studid)->where('semid',$semid)->values();
                        $temp_chrngrans = collect($chngtrans)
                                                ->where('studid',$item->studid)
                                                ->where('semid',$semid)
                                                ->values();
                        
                        $check_nodp = collect($no_dp)->where('studid',$item->studid)->where('semid',$semid)->count();
                        $item->semid = $semid;
                  }
                  else if($item->levelid == 14 || $item->levelid == 15){
                        if($schoolinfo->shssetup == 1){
                              $temp_chrngrans = collect($chngtrans)
                                                      ->where('studid',$item->studid)
                                                      ->whereIn('semid',$shs_semid)
                                                      ->values();
                        }else{

                              if($school_info->abbreviation == 'VNBC' || $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){
                                    $temp_chrngrans = collect($chngtrans)
                                                            ->where('studid',$item->studid)
                                                            ->where('semid',$semid)
                                                            ->values();
                              }else{
                                    $temp_chrngrans = collect($chngtrans)
                                                            ->where('studid',$item->studid)
                                                            ->where('semid',$semid)
                                                            ->values();
                              }
                        }
                        
                        $check_nodp = collect($no_dp)->where('studid',$item->studid)->where('semid',$semid)->count();
                        $item->semid = $semid;
                  }else{
                        if($school_info->abbreviation == 'VNBC' || $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){
                              if($semid == 3){
                                    $item->semid = 3;
                                    $temp_chrngrans = collect($chngtrans)
                                                            ->where('studid',$item->studid)
                                                            ->whereIn('semid',[3])
                                                            ->values();
                              }else{
                                    $item->semid = 1;
                                    $temp_chrngrans = collect($chngtrans)
                                                      ->where('studid',$item->studid)
                                                      ->whereIn('semid',[1,2])
                                                      ->values();
                              }

                        }else{
                              if($semid == 3){
                                    $item->semid = 3;
                                    $temp_chrngrans = collect($chngtrans)
                                                            ->where('studid',$item->studid)
                                                            ->whereIn('semid',[3])
                                                            ->values();
                              }else{
                                    $item->semid = 1;
                                    $temp_chrngrans = collect($chngtrans)
                                                      ->where('studid',$item->studid)
                                                      ->whereIn('semid',[1,2])
                                                      ->values();
                              }
                        }
                        
                        $check_nodp = collect($no_dp)->where('studid',$item->studid)->count();
                  }
                  
                  //no_dp
                  if($check_nodp == 0){
                        $item->nodp = 0;
                  }else{
                        $item->nodp = 1;
                  }

                  //grade level no dp
                  $no_dp_gradelevel = collect($all_gradelevel)->where('id',$item->levelid)->first();
                  if($no_dp_gradelevel->nodp == 1){
                        $item->nodp = 1;
                  }

                  if($school_info->abbreviation == 'SAIT'){
                        $item->nodp = 1;
                  }
				  
                  if(count($temp_chrngrans) > 0){
                        $item->withpayment = 1;
                       
                        if($school_info->abbreviation == 'VNBC' && $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){
                              $item->payment  = collect($chngtrans)
                                                      ->where('studid',$item->studid)
                                                      ->sum('amount');
                        }else{
                              $temp_cashtrans = collect($cashtrans)
                                                      ->whereIn('transno', collect($temp_chrngrans)->pluck('transno'))
                                                      ->where('studid',$item->studid)
                                                      ->count();

                              if($temp_cashtrans > 0){
                                    $item->payment  = collect($cashtrans)
                                                            ->whereIn('transno', collect($temp_chrngrans)->pluck('transno'))
                                                            ->where('studid',$item->studid)
                                                            ->sum('amount');
                              }else{
                                    $item->withpayment = 0;
                                    $item->payment = 0;
                              }
                             
                        }
                        
                        if($school_info->abbreviation == 'VNBC' || $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){
                              $chngtrans = collect($chngtrans)->where('studid','!=' , $item->studid)->values();
                        }else{
                              $chngtrans = collect($chngtrans)->whereNotIn('transno', collect($temp_chrngrans)->pluck('transno'))->values();
                              $cashtrans = collect($cashtrans)->whereNotIn('transno', collect($temp_chrngrans)->pluck('transno'))->values();
                        }
                     
                  }else{
                         $item->withpayment = 0;
                         $item->payment = 0;
                  }

                  if( $item->withpayment ==  1 || $item->nodp == 1){
                        $item->can_enroll = 1;
                  }else{
                        $item->can_enroll = 0;
                  }

            }
            // return $all_students;
            if ($request->get('action') == "export") {
                  $pdf = PDF::loadView('superadmin.pages.student.studentinfoprint', compact('all_students', 'student_count'));
                  return $pdf->stream('Student Information.pdf');
            }

            return @json_encode((object)[
                  'data'=>$all_students,
                  'recordsTotal'=>$student_count,
                  'recordsFiltered'=>$student_count
            ]);

            // foreach($students as $item){
                  
            //       $temp_middle = '';
            //       $temp_suffix = '';
            //       if(isset($item->middlename)){
            //             if(strlen($item->middlename) > 0){
            //                   $temp_middle = ' '.$item->middlename[0].'.';
            //             }
            //       }
            //       if(isset($item->suffix)){
            //             $temp_suffix = ' '.$item->suffix;
            //       }
            //       $item->student = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
               

            //       $check = collect($all_enrolledstudents)->where('studid',$item->studid)
            //                         ->first();
            //       $item->submission = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY');

            //       $item->isearly = 0;

            //       if(isset($check->studid)){
                       
            //             if($check->dateenrolled == null){
            //                   $item->dateenrolled = '';
            //                   $item->enrollment = '';
            //             }else{
            //                   $item->dateenrolled = \Carbon\Carbon::create($check->dateenrolled)->isoFormat('YYYY-MM-DD');
            //                   $item->enrollment = \Carbon\Carbon::create($check->dateenrolled)->isoFormat('MMM DD, YYYY');
            //             }
            //             $item->mol = $check->studmol;
            //             $item->ensectionid = $check->sectionid;
            //             $item->enlevelid = $check->levelid;
            //             $item->promotionstatus = $check->promotionstatus;
            //             $item->isearly = $check->isearly;
            //             $item->description = $check->description;
            //             $item->studstatus = $check->studstatus;
            //             $item->sectionname = $check->sectionname;
            //             $item->levelname = $check->levelname;
            //             if($check->levelid == 14 || $check->levelid == 15){
            //                   $item->strandid = $check->strandid;
            //             }else{
            //                   $item->strandid = null;
            //             }
                       
            //             $all_enrolledstudents = collect($all_enrolledstudents)
            //                                           ->where('studid','!=',$check->studid)
            //                                           // ->where('admissiontype',$item->admission_type)
            //                                           ->values();

            //       }else{
            //             $item->promotionstatus = 0;
            //             $item->enrollment = '';
            //             $item->description = 'NOT ENROLLED';
            //             $item->sectionname = '--';
            //             $item->studstatus = 0;
            //             if($item->gradelvl_to_enroll != null && $item->gradelvl_to_enroll != ""){
            //                   $item->levelid = $item->gradelvl_to_enroll;
            //                   $item->levelname = $item->lvltoenrollname;
            //             }
            //             $check_mol = collect($modeoflearning)->where('studid',$item->studid)->first();
            //             if(isset($check_mol->mol)){
            //                   $item->mol = $check_mol->mol;
            //             }else{
            //                   $item->mol = $item->mol;
            //             }
            //       }

                  

            //       $item->levelname = str_replace(' COLLEGE','',$item->levelname);
            //       $item->curlevelname = str_replace(' COLLEGE','',$item->curlevelname);
            //       $item->search =  $item->sid.' - '.$item->student.' - '.$item->levelname.' - '.$item->sectionname;

            //       if(( $item->levelid >= 17 && $item->levelid >= 21 )){
            //             $check_payment = collect($payment_info)->where('studid',$item->studid)->where('semid',$semid)->values();
            //             $item->semid = $semid;
            //       }
            //       else if($item->levelid == 14 || $item->levelid == 15){
            //             if($schoolinfo->shssetup == 1){
            //                   $check_payment = collect($payment_info)->where('studid',$item->studid)->values();
            //             }else{
            //                   $check_payment = collect($payment_info)->where('studid',$item->studid)->where('semid',$semid)->values();
            //             }
                       
            //             $item->semid = $semid;
            //       }else{
            //             $item->semid = 1;
            //             $check_payment = collect($payment_info)->where('studid',$item->studid)->values();
            //       }
                 
				  
            //       if(count($check_payment) > 0){
            //              $item->withpayment = 1;
            //              $item->payment = collect($check_payment)->sum('amount');
                         
            //       }else{
            //              $item->withpayment = 0;
            //              $item->payment = 0;
            //       }

            //       if( $item->withpayment ==  1 || $item->nodp == 1){
            //             $item->can_enroll = 1;
            //       }else{
            //             $item->can_enroll = 0;
            //       }
                  
            // }

            $students = $all_students;
           
            return $students;
      }

      public static function enrollment_history(Request $request){

            $studid = $request->get('studid');
            $courseid = $request->get('courseid');

            $enrollment_list = array();

            $get_enrollment = DB::table('enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('enrolledstud.deleted',0)
                                    ->join('sections',function($join){
                                          $join->on('enrolledstud.sectionid','=','sections.id');
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    })
                                    ->join('sy',function($join){
                                          $join->on('enrolledstud.syid','=','sy.id');
                                    })
                                    ->select(
                                          'acadprogid',
                                          'dateenrolled',
                                          'enrolledstud.levelid',
                                          'syid',
                                          'sydesc',
                                          'levelname',
                                          'sectionname',
                                          'sy.isactive',
                                          'sectionid'
                                    )
                                    ->get();

            foreach($get_enrollment as $item){

                  $item->semid = 1;
                  $item->dateenrolled = \Carbon\Carbon::create($item->dateenrolled)->isoFormat('MMM DD, YYYY');
                  array_push($enrollment_list,$item);      
            }

            $get_enrollment = DB::table('sh_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->join('sections',function($join){
                                          $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    })
                                    ->join('sh_strand',function($join){
                                          $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                          $join->where('sh_enrolledstud.deleted',0);
                                    })
                                    ->join('sy',function($join){
                                          $join->on('sh_enrolledstud.syid','=','sy.id');
                                    })
                                    ->join('semester',function($join){
                                          $join->on('sh_enrolledstud.semid','=','semester.id');
                                    })
                                    ->select(
                                          'semid',
                                          'acadprogid',
                                          'dateenrolled',
                                          'sh_enrolledstud.levelid',
                                          'strandcode',
                                          'syid',
                                          'sydesc',
                                          'levelname',
                                          'sectionname',
                                          'semester',
                                          'sy.isactive',
                                          'sectionid',
                                          'sh_enrolledstud.strandid'
                                    )
                                    ->get();

            foreach($get_enrollment as $item){
                  $item->semester = str_replace(" Semester"," Sem",$item->semester);
                  $item->dateenrolled = \Carbon\Carbon::create($item->dateenrolled)->isoFormat('MMM DD, YYYY');
                  array_push($enrollment_list,$item);      
            }
                        

            $get_enrollment = DB::table('college_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('college_enrolledstud.deleted',0)
                                    ->join('college_sections',function($join){
                                          $join->on('college_enrolledstud.sectionid','=','college_sections.id');
                                    })
                                    ->join('college_classsched',function($join){
                                          $join->on('college_sections.id','=','college_classsched.sectionid');
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                    })
                                    ->join('sy',function($join){
                                          $join->on('college_enrolledstud.syid','=','sy.id');
                                    })
                                    ->join('college_courses',function($join){
                                          $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->join('semester',function($join){
                                          $join->on('college_enrolledstud.semid','=','semester.id');
                                    })
                                    ->select(
                                          'semid',
                                          'semester',
                                          'acadprogid',
                                          'courseabrv',
                                          'date_enrolled as dateenrolled',
                                          'college_enrolledstud.yearLevel as levelid',
                                          'college_enrolledstud.syid',
                                          'sydesc',
                                          'levelname',
                                          'sectionDesc as sectionname',
                                          'college_classsched.id as schedid',
                                          'sy.isactive'
                                    )
                                    ->get();

            
            $collegesection = DB::table('college_schedgroup_detail')
                                    ->where('college_schedgroup_detail.deleted',0)
                                    ->whereIn('schedid',collect($get_enrollment)->pluck('schedid'))
                                    ->join('college_schedgroup',function($join){
                                          $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                          $join->where('college_schedgroup.deleted',0);
                                    })
                                    ->leftJoin('college_courses',function($join){
                                          $join->on('college_schedgroup.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->leftJoin('gradelevel',function($join){
                                          $join->on('college_schedgroup.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->leftJoin('college_colleges',function($join){
                                          $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                                          $join->where('college_colleges.deleted',0);
                                    })
                                    ->select(
                                          'college_schedgroup.courseid',
                                          'college_schedgroup.levelid',
                                          'college_schedgroup.collegeid',
                                          'courseDesc',
                                          'collegeDesc',
                                          'levelname',
                                          'courseabrv',
                                          'collegeabrv',
                                          'college_schedgroup.id',
                                          'college_schedgroup.schedgroupdesc',
                                          'schedgroupdesc as text',
                                          'schedid'
                                    )
                                    ->get();

            $collegeid = DB::table('college_courses')
                                    ->where('id',$courseid)
                                    ->select('collegeid')
                                    ->first();

            foreach($get_enrollment as $item){


                  $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('courseid',$courseid)->values();

                  if(count($checkcoursegroup) != 0){
                        $text = $checkcoursegroup[0]->courseabrv;
                        $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                        $item->sectionname = $text;   
                  }else{
                        if(isset($collegeid->id)){
                              $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('collegeid',$collegeid->id)->values();
                              if(count($checkcoursegroup) != 0){
                                    $text = $checkcoursegroup[0]->collegeabrv;
                                    $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                    $item->sectionname = $text;  
                              }else{
                                    $item->sectionname = 'Not Found';
                              }
                        }else{
                              $item->sectionname = 'Not Found';
                        }
                  }


                  $item->semester = str_replace(" Semester"," Sem",$item->semester);
                  $item->levelname = str_replace(" COLLEGE","",$item->levelname);
                  $item->dateenrolled = \Carbon\Carbon::create($item->dateenrolled)->isoFormat('MMM DD, YYYY');
                  array_push($enrollment_list,$item);      
            }

            return $enrollment_list;

      }

      public static function documents_list(Request $request){

            $levelid = $request->get('levelid');
            $syid = $request->get('syid');
            $studid = $request->get('studid');
            $studinfo = DB::table('studinfo')->where('id',$studid)->select('sid','levelid')->first();


            $sid = $studinfo->sid;

            // $levelid = DB::table('studinfo')->where('id',$studid)->select('levelid')->first()->levelid;
            // $documents = DB::table('preregistrationreqlist')
            //                   ->where('levelid',$levelid)
            //                   ->where('deleted',0)
            //                   ->where('isactive',1)
            //                   ->get();

            // $levelid = $sid->levelid;


            // return collect($documents);

         

            // $schoolinfo = DB::table('schoolinfo')->first();

            // $submitted = DB::table('preregistrationrequirements')
            //                   ->where('qcode',$sid)
            //                   ->where('deleted',0)
            //                   ->where('syid',$syid)
            //                   ->get();

            // // if(count($submitted) > 0){
            // //       $levelid =  DB::table('preregistrationreqlist')
            // //                         ->where('id',$submitted[0]->preregreqtype)
            // //                         ->where('deleted',0)
            // //                         ->select('levelid')
            // //                         ->first()
            // //                         ->levelid;
            // // }else{
            // //       return array((object)[
            // //             'status'=>0,
            // //             'message'=>'No documents submitted!'
            // //       ]);
            // // }


            // foreach($submitted as $item){
            //       $date = \Carbon\Carbon::now('Asia/Manila');
            //       $explode_picurl = explode('/',$item->picurl);
            //       if($schoolinfo->setup == 1){
            //             $item->picurl = $schoolinfo->es_cloudurl.$explode_picurl[0]."/".$sid.'/'.$explode_picurl[1]."?random='".$date."'";
            //       }else{
            //             $item->picurl = asset($explode_picurl[0]."/".$sid.'/'.$explode_picurl[1])."?random='".$date."'";
            //       }
            // }

            // $documents = DB::table('preregistrationreqlist')
            //                   ->where('levelid',$levelid)
            //                   ->where('deleted',0)
            //                   ->where('isactive',1)
            //                   ->get();

            // foreach($documents as $item){
            //       $date = \Carbon\Carbon::now('Asia/Manila');
            //       $check = collect($submitted)->where('preregreqtype',$item->id)->first();
            //       if(isset($check->id)){
            //             $item->submitted = 1;
            //             $item->picurl = $check->picurl;
            //       }else{
            //             $item->submitted = 0;
            //             $item->picurl = asset("no-file.png")."?random='".$date."'";
            //       }
            // }

            // // return $documents;

            $request->request->add(['sid' => $studinfo->sid]);

             $documents = \App\Http\Controllers\SuperAdminController\SubmittedRequirements::uploadeddocs($request);
            // return $documents;

            $tempdocs = array();
            foreach($documents as $document){
                  foreach($document->uploaded as $uploadeditem){
                        array_push($tempdocs,$uploadeditem);
                  }
            }



            return array((object)[
                  'status'=>1,
                  'message'=>'Documents found',
                  'data'=>$documents
            ]);


      }

      public static function update_history(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');

            $update_history = DB::table('student_updateinformation')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('studid',$studid)
                                    ->get();

            $update_list = array();

            if(count($update_history) > 0){

                  if(!isset($update_history[0]->updatequery)){
                        return array((object)[
                              'status'=>2,
                              'message'=>'No update available'
                        ]);
                  }

                  $updates = json_decode($update_history[0]->updatequery)[0]->bindings;

                  

                  $student_info = Db::table('studinfo')
                                    ->where('id',$studid)
                                    ->first();

                  

                  if($student_info->semail != $updates[3]){
                        array_push($update_list,(object)[
                              'field'=>'Email',
                              'old'=>$student_info->semail,
                              'new'=>$updates[3]
                        ]);
                  }
                  if($student_info->contactno != $updates[2]){
                        array_push($update_list,(object)[
                              'field'=>'Studen Contact #',
                              'old'=>$student_info->contactno,
                              'new'=>$updates[2]
                        ]);
                  }
                  if($student_info->street != $updates[14]){
                        array_push($update_list,(object)[
                              'field'=>'Street',
                              'old'=>$student_info->street,
                              'new'=>$updates[14]
                        ]);
                  }
                  if($student_info->barangay != $updates[15]){
                        array_push($update_list,(object)[
                              'field'=>'Barangay',
                              'old'=>$student_info->barangay,
                              'new'=>$updates[15]
                        ]);
                  }
                  if($student_info->city != $updates[16]){
                        array_push($update_list,(object)[
                              'field'=>'City',
                              'old'=>$student_info->city,
                              'new'=>$updates[16]
                        ]);
                  }
                  if($student_info->province != $updates[17]){
                        array_push($update_list,(object)[
                              'field'=>'Province',
                              'old'=>$student_info->province,
                              'new'=>$updates[17]
                        ]);
                  }
                  if($student_info->fathername != $updates[20]){
                        array_push($update_list,(object)[
                              'field'=>'Father Name',
                              'old'=>$student_info->fathername,
                              'new'=>$updates[20]
                        ]);
                  }
                  if($student_info->fcontactno != $updates[6]){
                        array_push($update_list,(object)[
                              'field'=>'Father Contact #',
                              'old'=>$student_info->fcontactno,
                              'new'=>$updates[6]
                        ]);
                  }
                  if($student_info->foccupation != $updates[7]){
                        array_push($update_list,(object)[
                              'field'=>'Father Occupation',
                              'old'=>$student_info->foccupation,
                              'new'=>$updates[7]
                        ]);
                  }
                  if($student_info->mothername != $updates[21]){
                        array_push($update_list,(object)[
                              'field'=>'Mother Name',
                              'old'=>$student_info->mothername,
                              'new'=>$updates[21]
                        ]);
                  }
                  if($student_info->mcontactno != $updates[4]){
                        array_push($update_list,(object)[
                              'field'=>'Mother Contact #',
                              'old'=>$student_info->mcontactno,
                              'new'=>$updates[4]
                        ]);
                  }
                  if($student_info->guardianname != $updates[10]){
                        array_push($update_list,(object)[
                              'field'=>'Guardian Name',
                              'old'=>$student_info->guardianname,
                              'new'=>$updates[10]
                        ]);
                  }
                  if($student_info->guardianrelation != $updates[9]){
                        array_push($update_list,(object)[
                              'field'=>'Guardian Relation',
                              'old'=>$student_info->guardianrelation,
                              'new'=>$updates[9]
                        ]);
                  }
                  if($student_info->gcontactno != $updates[8]){
                        array_push($update_list,(object)[
                              'field'=>'Guardian Contact #',
                              'old'=>$student_info->gcontactno,
                              'new'=>$updates[8]
                        ]);
                  }


                  if($student_info->strandid != $updates[18]){

                        $strand = DB::table('sh_strand')
                                    ->where('id',$updates[18])
                                    ->first();

                        array_push($update_list,(object)[
                              'field'=>'Strand',
                              'old'=>$student_info->strandid,
                              'new'=>$strand->strandname
                        ]);
                  }

                  if($student_info->courseid != $updates[19]){

                        $course = DB::table('college_courses')
                                    ->where('id',$updates[19])
                                    ->first();

                        array_push($update_list,(object)[
                              'field'=>'Course',
                              'old'=>$student_info->courseid,
                              'new'=>$course->courseDesc
                        ]);
                  }


                  $cur_incase = '';
                  if($student_info->isfathernum == 1){
                        $cur_incase = 'Father';
                  }else if($student_info->ismothernum == 1){
                        $cur_incase = 'Mother';
                  }else if($student_info->isguardannum == 1){
                        $cur_incase = 'Guardian';
                  }

                  $up_incase = '';
                  if($updates[11] == 1){
                        $up_incase = 'Father';
                  }else if($updates[13] == 1){
                        $up_incase = 'Mother';
                  }else if($updates[12]== 1){
                        $up_incase = 'Guardian';
                  }

                  if($cur_incase != $up_incase){
                        array_push($update_list,(object)[
                              'field'=>'In Case of Emergendcy',
                              'old'=>$cur_incase,
                              'new'=>$up_incase
                        ]);
                  }


                  
            }

            if(count($update_list) == 0){
                  return array((object)[
                        'status'=>2,
                        'message'=>'No update available',
                        'data'=>$update_list
                  ]);
            }

            return array((object)[
                  'status'=>1,
                  'message'=>'Updates Available',
                  'data'=>$update_list
            ]);

      }

      public static function update_info(Request $request){

            try{
                  $syid = $request->get('syid');
                  $studid = $request->get('studid');
                  $new_info = array();
      
                  $update_detail = DB::table('student_updateinformation')
                                    ->where('syid',$syid)
                                    ->where('studid',$studid)
                                    ->where('updatedone',0)
                                    ->where('deleted',0)
                                    ->first();
                                    
      
                  if(isset($update_detail->updatequery)){

                        $fields = json_decode($update_detail->updatequery);
      
                        if(count($fields) > 0){

                            
      
                              $query = $fields[0]->query;
                              $binding = $fields[0]->bindings;
      
                              DB::update($query,$binding);
      
                                    
                              $update_detail = DB::table('student_updateinformation')
                                    ->where('syid',$syid)
                                    ->where('studid',$studid)
                                    ->where('updatedone',0)
                                    ->where('deleted',0)
                                    ->take(1)
                                    ->update([
                                          'updatedone'=>1,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
      
                              $new_info = $fields[0]->bindings;
                        }
      
                  }
                  
                  return array((object)[
                        'status'=>1,
                        'message'=>'Information Updated!'
                  ]);
                  
            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }

            

      }

      public static function enroll_student(Request $request){

            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $studid = $request->get('studid');
                  $levelid = $request->get('levelid');
                  $sectionid = $request->get('sectionid');
                  $courseid = $request->get('courseid');
                  $studstatus = $request->get('studstatus');
                  $strandid = $request->get('strandid');
                  // $isearly = $request->get('isearly');
                  $enrollmentdate = $request->get('enrollmentdate');
                  $mol = $request->get('mol');
                  $grantee = $request->get('grantee');
                  $pantawid = $request->get('pantawid');
                  $preregid = $request->get('preregid');
                  $remarks = $request->get('remarks');
                  $regStatus = $request->get('regStatus');
                  $studstatdate = $request->get('studstatdate');

                  

                  if($mol == null || $mol == ""){
                        $mol = 0;
                  }

                  $acadprogid = DB::table('gradelevel')
                                    ->where('id',$levelid)
                                    ->where('deleted',0)
                                    ->select('acadprogid')
                                    ->first()
                                    ->acadprogid;

                  if($preregid  != null || $preregid  != ""){

                        $admission_type = DB::table('student_pregistration')
                                                ->where('id',$preregid)
                                                ->select('admission_type')
                                                ->first();

                  }else{

                        if($levelid == 14 || $levelid == 15 || ($levelid >= 17 && $levelid <= 20)){
                              $admission_type = DB::table('early_enrollment_setup')
                                                ->join('early_enrollment_setup_type',function($join){
                                                      $join->on('early_enrollment_setup.type','=','early_enrollment_setup_type.id');
                                                      $join->where('early_enrollment_setup.deleted',0);
                                                })
                                                ->where('early_enrollment_setup.isactive',1)
                                                ->whereIn('early_enrollment_setup.acadprogid',[5,6])
                                                ->where('early_enrollment_setup.syid',$syid)
                                                ->where('early_enrollment_setup.semid',$semid)
                                                ->select('early_enrollment_setup.id as admission_type')
                                                ->first();
                        }else{
      
                              $admission_type = DB::table('early_enrollment_setup')
                                                      ->join('early_enrollment_setup_type',function($join) use($acadprogid){
                                                            $join->on('early_enrollment_setup.type','=','early_enrollment_setup_type.id');
                                                            $join->where('early_enrollment_setup.deleted',0);
                                                      })
                                                      ->where('early_enrollment_setup.isactive',1)
                                                      ->whereIn('early_enrollment_setup.acadprogid',[2,3,4])
                                                      ->where('early_enrollment_setup.syid',$syid)
                                                      ->select('early_enrollment_setup.id as admission_type')
                                                      ->first();
                        }

                  }

                 

                  // if(!isset($admission_type->admission_type)){
                  //       return array((object)[
                  //             'status'=>2,
                  //             'message'=>'No Active Enrollment Setup!'
                  //       ]);
                  // }else{
                  //       $admission_type = $admission_type->admission_type;
                  // }
                  $admission_type = null;
                 

                  $acadprogid = DB::table('gradelevel')
                                    ->where('id',$levelid)
                                    ->where('deleted',0)
                                    ->select('acadprogid')
                                    ->first()
                                    ->acadprogid;

                  $semester = DB::table('semester')
                                    ->where('id',$semid)
                                    ->select(
                                          'semester'
                                    )
                                    ->first()
                                    ->semester;

                  if($acadprogid != 5 && $acadprogid != 6){
                        if($semid == 2){
                              $semid = 1;
                        }
                  }
                 
                  if($acadprogid == 5){
                        $check = DB::table('sh_enrolledstud')
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('studid',$studid)
                                    //->where('levelid',$levelid)
                                    ->where('deleted',0)
                                   // ->where('promotionstatus',0)
                                    ->count();

                  }else if($acadprogid == 6){

                        $check = DB::table('college_enrolledstud')
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->where('studid',$studid)
                                          //->where('yearLevel',$levelid)
                                          ->where('deleted',0)
                                         // ->where('promotionstatus',0)
                                          ->count();
                  }
                  else{
                        $check = DB::table('enrolledstud')
                                    ->where('ghssemid',$semid)
                                    ->where('syid',$syid)
                                    ->where('studid',$studid)
                                   // ->where('levelid',$levelid)
                                    ->where('deleted',0)
                                   // ->where('promotionstatus',0)
                                    ->count();
                  }

               
                  
                  if($check > 0){
                      
                        $sydesc = DB::table('sy')->where('id',$syid)->first()->sydesc;
                        if($acadprogid == 5 || $acadprogid == 6){
                              return array((object)[
                                    'status'=>2,
                                    'message'=>'Enrollment Exist for S.Y. '.$sydesc.'-'.$semester.'!'
                              ]);
                        }else{
                              if($semid == 3){
                                    return array((object)[
                                          'status'=>2,
                                          'message'=>'Enrollment Exist for S.Y. '.$sydesc.'-'.$semester.'!'
                                    ]);
                              }else{
                                    return array((object)[
                                          'status'=>2,
                                          'message'=>'Enrollment Exist for S.Y. '.$sydesc.'!'
                                    ]);
                              }
                        }
                       
                  }

                  if($acadprogid == 5){
                        $check = DB::table('sh_enrolledstud')
                                    ->where('levelid',$levelid)
                                    ->where('semid',$semid)
                                    ->where('studid',$studid)
                                    ->where('levelid',$levelid)
                                    ->where('promotionstatus',0)
                                    ->where('deleted',0)
                                    ->count();
                  }else if($acadprogid == 6){
                        $check = DB::table('college_enrolledstud')
                                          ->where('semid',$semid)
                                          ->where('studid',$studid)
                                          ->where('yearLevel',$levelid)
                                          ->where('promotionstatus',0)
                                          ->where('deleted',0)
                                          ->count();
                  }
                  else{
                        $check = DB::table('enrolledstud')
                                    ->where('ghssemid',$semid)
                                    ->where('levelid',$levelid)
                                    ->where('studid',$studid)
                                    ->where('levelid',$levelid)
                                    ->where('promotionstatus',0)
                                    ->where('deleted',0)
                                    ->count();
                  }

                  if($check > 0){

                        $gradelevel = DB::table('gradelevel')->where('id',$levelid)->first()->levelname;
                       

                        if($acadprogid == 5 || $acadprogid == 6){
                              return array((object)[
                                    'status'=>2,
                                    'message'=>$gradelevel.'-'.$semester. ' enrollment already exist!'
                              ]);
                        }else{
                              if($semid == 3){
                                    return array((object)[
                                          'status'=>2,
                                          'message'=>$gradelevel.'-'.$semester. ' enrollment already exist!'
                                    ]);
                              }else{
                                    return array((object)[
                                          'status'=>2,
                                          'message'=>$gradelevel.' enrollment already exist!'
                                    ]);
                              }
                        }

                       
                  }

                  $sectionname = null;
      
                  if($acadprogid == 5){

                        $sectionname = DB::table('sections')
                                          ->where('id',$sectionid)
                                          ->select('sectionname')
                                          ->first()
                                          ->sectionname;

                        DB::table('sh_enrolledstud')
                                    ->insert([
                                          'studstatdate'=>$studstatdate,
                                          'studmol'=>$mol,
                                          // 'isearly'=>$isearly,
                                          'remarks'=>$remarks,
                                          'grantee'=>$grantee,
                                          'studid'=>$studid,
                                          'syid'=>$syid,
                                          'levelid'=>$levelid,
                                          'sectionid'=>$sectionid,
                                          'strandid'=>$strandid,
                                          'studstatus'=>$studstatus,
                                          'semid'=>$semid,
                                          'dateenrolled'=>$enrollmentdate,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'admissiontype'=>$admission_type
                                    ]);

                  }else if($acadprogid == 6){

                        DB::table('college_enrolledstud')
                              ->insert([
                                    'studmol'=>$mol,
                                    // 'isearly'=>$isearly,
                                    'remarks'=>$remarks,
                                    'studid'=>$studid,
                                    'syid'=>$syid,
                                    'yearLevel'=>$levelid,
                                    'sectionid'=>$sectionid,
                                    'courseid'=>$courseid,
                                    'studstatus'=>$studstatus,
                                    'semid'=>$semid,
                                    'date_enrolled'=>$enrollmentdate,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'admissiontype'=>$admission_type,
                                    'deleted'=>0,
                                    'promotionstatus'=>0,
                                    'regStatus'=>$regStatus
                              ]);
                  }else{
                        $sectionname = DB::table('sections')
                                    ->where('id',$sectionid)
                                    ->select('sectionname')
                                    ->first()
                                    ->sectionname;

                        DB::table('enrolledstud')
                                    ->insert([
                                          'studmol'=>$mol,
                                          'ghssemid'=>$semid,
                                          // 'isearly'=>$isearly,
                                          'remarks'=>$remarks,
                                          'grantee'=>$grantee,
                                          'studid'=>$studid,
                                          'syid'=>$syid,
                                          'deleted'=>0,
                                          'levelid'=>$levelid,
                                          'sectionid'=>$sectionid,
                                          'studstatus'=>$studstatus,
                                          'dateenrolled'=>$enrollmentdate,
                                          'createdby'=>auth()->user()->id,
                                          'studstatdate'=>$studstatdate,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'admissiontype'=>$admission_type
                                    ]);
                  }

                  $check_sy = DB::table('sy')
                                    ->where('id',$syid)
                                    ->first();

                 
                  $studinfo = DB::table('studinfo')  
                                    ->where('id',$studid)
                                    ->select(
                                          'contactno',
                                          'ismothernum',
                                          'isfathernum',
                                          'isguardannum',
                                          'mcontactno',
                                          'fcontactno',
                                          'gcontactno',
                                          'mothername',
                                          'fathername',
                                          'guardianname',
                                          'studinfo.levelid',
                                          'studinfo.sectionid',
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'sid',
                                          'feesid',
                                          'studstatus'
                                    )
                                    ->first();


                  if($check_sy->isactive == 1 || $studinfo->studstatus == 0){
                        DB::table('studinfo')
                              ->where('id',$studid)
                              ->take(1)
                              ->update([
                                    'mol'=>$mol,
                                    'studstatdate'=>$enrollmentdate,
                                    'grantee'=>$grantee,
                                    'pantawid'=>$pantawid,
                                    'sectionname'=>$sectionname,
                                    'levelid'=>$levelid,
                                    'sectionid'=>$sectionid,
                                    'studstatus'=>$studstatus,
                                    'strandid'=>$strandid,
                                    'courseid'=>$courseid,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        
                        DB::table('studinfo')
                              ->where('id',$studid)
                              ->take(1)
                              ->update([
                                    'courseid'=>$courseid,
                                    'grantee'=>$grantee,
                                    'pantawid'=>$pantawid,
                                    'mol'=>$mol,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              ]);

                  }

                  if($preregid != null || $preregid != ""){
                        DB::table('student_pregistration')
                              ->where('id',$preregid)
                              ->take(1)
                              ->update([
                                    'status'=>'ENROLLED',
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        DB::table('studinfo')
                              ->where('id',$studid)
                              ->take(1)
                              ->update([
                                    'grantee'=>$grantee,
                                    'pantawid'=>$pantawid,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              ]);
                  }

                 
                  

                  // if($mol != ""){

                  //       $check_mol = DB::table('modeoflearning_student')
                  //                         ->where('studid',$studid)
                  //                         ->where('syid',$syid)
                  //                         ->where('deleted',0)
                  //                         ->get();

                  //       if(count($check_mol) > 1){

                  //             DB::table('modeoflearning_student')
                  //                         ->where('studid',$studid)
                  //                         ->where('syid',$syid)
                  //                         ->where('deleted',0)
                  //                         ->update([
                  //                               'deleted'=>1,
                  //                               'deletedby'=>auth()->user()->id,
                  //                               'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  //                         ]);

                  //             DB::table('modeoflearning_student')
                  //                   ->insert([
                  //                         'studid'=>$studid,
                  //                         'mol'=>$mol,
                  //                         'syid'=>$syid,
                  //                         'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                  //                         'createdby'=>auth()->user()->id,
                  //                   ]);
                                          
                  //       }else if(count($check_mol) == 0){
                  //             DB::table('modeoflearning_student')
                  //                   ->insert([
                  //                         'studid'=>$studid,
                  //                         'mol'=>$mol,
                  //                         'syid'=>$syid,
                  //                         'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                  //                         'createdby'=>auth()->user()->id,
                  //                   ]);
                  //       }else{
                  //             DB::table('modeoflearning_student')
                  //                   ->where('id',$check_mol[0]->id)
                  //                   ->take(1)
                  //                   ->update([
                  //                         'mol'=>$mol,
                  //                         'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                  //                         'updatedby'=>auth()->user()->id,
                  //                   ]);
                  //       }

                       
                  // }

                  $parent_contact = null;
                  $student_contact = $studinfo->contactno != null && strlen($studinfo->contactno) ? $studinfo->contactno : null;
                  $sydesc = DB::table('sy')->where('id',$syid)->first()->sydesc;
                  $abbr = DB::table('schoolinfo')->first()->abbreviation;
                  $schoolinfo = DB::table('schoolinfo')->first();

                  if($studinfo->ismothernum == 1){
                        if($studinfo->mcontactno != null && strlen($studinfo->mcontactno) == 11){
                              $parent_contact = $studinfo->mcontactno;
                        }
                  }else if($studinfo->isfathernum == 1){
                        if($studinfo->fcontactno != null && strlen($studinfo->fcontactno) == 11){
                              $parent_contact = $studinfo->fcontactno;
                        }
                  }else if($studinfo->isguardannum == 1){
                        if($studinfo->gcontactno != null && strlen($studinfo->gcontactno) == 11){
                              $parent_contact = $studinfo->gcontactno;
                        }
                  }

                  if($schoolinfo->schoolid == '410400'){
                        if($parent_contact != null){
                              DB::table('smsbunker')
                                    ->insert([
                                          'message' => 'Welcome to CENTERPHIL BACOLOD. CONGRATULATIONS! '. $studinfo->firstname .' you are officially enrolled for S.Y ' . $sydesc.'.',
                                          'receiver' => '+63' . substr($parent_contact,1),
                                          'smsstatus' => 0
                                    ]);
                        }
      
                        if($student_contact != null){
                              DB::table('smsbunker')
                                    ->insert([
                                          'message' => 'Welcome to CENTERPHIL BACOLOD. CONGRATULATIONS! You student'. $studinfo->firstname .' is now officially enrolled for S.Y ' . $sydesc.'.',
                                          'receiver' => '+63' . substr($student_contact,1),
                                          'smsstatus' => 0
                                    ]);
                        }
      

                  }else{
                        if($parent_contact != null){
                              DB::table('smsbunker')
                                    ->insert([
                                          'message' => $abbr . ' message: CONGRATULATIONS! Your student '. $studinfo->firstname .' is now officially enrolled for S.Y ' . $sydesc,
                                          'receiver' => '+63' . substr($parent_contact,1),
                                          'smsstatus' => 0
                                    ]);
                        }
      
                        if($student_contact != null){
                              DB::table('smsbunker')
                                    ->insert([
                                          'message' =>$abbr . ' message: CONGRATULATIONS! ' . $studinfo->firstname .' you are officially enrolled for S.Y ' . $sydesc,
                                          'receiver' => '+63' . substr($student_contact,1),
                                          'smsstatus' => 0
                                    ]);
                        }
      
                  }

                  
                  if(isset($studinfo->feesid)){
                        $request->request->add(['feesid' => $studinfo->feesid]);
                  }else{
                        $request->request->add(['feesid' => null]);
                  }
                
                  $request->request->add(['feesid' => $studinfo->feesid]);
            
                  if($abbr == 'SAIT' || $abbr == 'VNBC' || $abbr == 'MAC'){
                        try{
                              \App\Http\Controllers\FinanceControllers\UtilityController::resetpayment_v2($request);
                              }catch(\Exception $e){}
                  }else{
                        try{
                              \App\Http\Controllers\FinanceControllers\UtilityController::resetpayment_v3($request);
                        }catch(\Exception $e){}
                  }

                  
                  

      
                  if($check == 0){
                        return array((object)[
                              'status'=>1,
                              'message'=>'Successfully Enrolled!'
                        ]);
                  }
            }catch(\Exception $e){
                  return self::store_error($e);
            }
           

      }

      public static function update_enroll_student(Request $request){

            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $studid = $request->get('studid');
                  $levelid = $request->get('levelid');
                  $sectionid = $request->get('sectionid');
                  $studstatus = $request->get('studstatus');
                  $strandid = $request->get('strandid');
                  // $isearly = $request->get('isearly');
                  $dateenrolled = $request->get('enrollmentdate');
                  $mol = $request->get('mol');
                  $grantee = $request->get('grantee');
                  $pantawid = $request->get('pantawid');
                  $courseid = $request->get('courseid');
                  $preregid = $request->get('preregid');
                  $remarks = $request->get('remarks');
                  $regStatus = $request->get('regStatus');
                  $enrollmentdate = $request->get('enrollmentdate');
                  $studstatdate = $request->get('studstatdate');

                  if($mol == null || $mol == ""){
                        $mol = 0;
                  }


                  $check_sy = DB::table('sy')
                                    ->where('id',$syid)
                                    ->select('ended','sydesc')
                                    ->first();

                  $seminfo = DB::table('semester')
                                    ->where('id',$semid)
                                    ->first();

                  if($check_sy->ended == 1){
                        return array((object)[
                              'status'=>2,
                              'message'=>'S.Y. already ended!'
                        ]);
                  }

                  if($levelid == 14 || $levelid == 15){
                        $check = DB::table('sh_enrolledstud')
                                    ->where('syid','!=',$syid)
                                    ->where('semid',$semid)
                                    ->where('levelid',$levelid)
                                    ->where('studid',$studid)
                                    ->where('promotionstatus',0)
                                    ->where('deleted',0)
                                    ->count();
                  }else if($levelid == 17 || $levelid == 18 || $levelid == 19 || $levelid == 20 || $levelid == 21){
                        $check = DB::table('college_enrolledstud')
                                          ->where('syid','!=',$syid)
                                          ->where('semid',$semid)
                                          ->where('yearLevel',$levelid)
                                          ->where('studid',$studid)
                                          ->where('promotionstatus',0)
                                          ->where('deleted',0)
                                          ->count();
                  }
                  else{
                        $check = DB::table('enrolledstud')
                                    ->where('levelid',$levelid)
                                    ->where('syid','!=',$syid)
                                    ->where('studid',$studid)
                                    ->where('promotionstatus',0)
                                    ->where('deleted',0)
                                    ->count();
                  }
                  
                  if($check > 0 &&  $studstatus != 0){
                        $gradelevel = DB::table('gradelevel')->where('id',$levelid)->first()->levelname;
                        return array((object)[
                              'status'=>2,
                              'message'=>$gradelevel. ' enrollment already exist!'
                        ]);
                  }

                  // if($levelid == 14 || $levelid == 15){

                  //       $check = DB::table('sh_enrolledstud')
                  //                   ->where('syid',$syid)
                  //                   ->where('semid',$semid)
                  //                   ->where('studid',$studid)
                  //                   ->where('promotionstatus',1)
                  //                   ->where('deleted',0)
                  //                   ->count();

                  // }else if($levelid == 17 || $levelid == 18 || $levelid == 19 || $levelid == 20){
                  //       $check = DB::table('college_enrolledstud')
                  //                         ->where('syid',$syid)
                  //                         ->where('semid',$semid)
                  //                         ->where('studid',$studid)
                  //                         ->where('promotionstatus',1)
                  //                         ->where('deleted',0)
                  //                         ->count();
                  // }
                  // else{
                  //       $check = DB::table('enrolledstud')
                  //                   ->where('syid',$syid)
                  //                   ->where('studid',$studid)
                  //                   ->where('promotionstatus',1)
                  //                   ->where('deleted',0)
                  //                   ->count();
                  // }
                  
                  // if($check > 0 &&  $studstatus != 0){
                  //       $sydesc = DB::table('sy')->where('id',$syid)->first()->sydesc;
                  //       return array((object)[
                  //             'status'=>2,
                  //             'message'=>'Student is already promoted for S.Y. '.$sydesc
                  //       ]);
                  // }

                  $sectionname = null;

                  if($levelid == 14 || $levelid == 15){

                        //check if already promotion for selected sy, semd and yearlevel
                        $check = DB::table('sh_enrolledstud')
                                          ->where('studid',$studid)
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->where('promotionstatus',1)
                                          ->count();


                        if($check > 0){
                              return array((object)[
                                    'status'=>2,
                                    'message'=>'Already promoted for S.Y.:'.$check_sy->sydesc.' : '.$seminfo->semester
                              ]);
                        }


                        //remove enrollment from other acad
                        DB::table('enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('promotionstatus',0)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('college_enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('promotionstatus',0)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                        //remove enrollment from other acad
                        

                        //check ernrollment 
                        $check = DB::table('sh_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('promotionstatus',0)
                                    ->count();
                        //check ernrollment 

                        //if no enrollment record
                        if($check == 0 ){
                              DB::table('sh_enrolledstud')
                                    ->insert([
                                          'studmol'=>$mol,
                                          'semid'=>$semid,
                                          // 'studstatdate'=>$dateenrolled,
                                          'dateenrolled'=>$dateenrolled,
                                          'studid'=>$studid,
                                          'syid'=>$syid,
                                          'levelid'=>$levelid,
                                          'sectionid'=>$sectionid,
                                          'strandid'=>$strandid,
                                          'studstatus'=>$studstatus,
                                          'grantee'=>$grantee,
                                          'studstatdate'=>$studstatdate,
                                          // 'isearly'=> $isearly,
                                          'remarks'=>$remarks,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('sh_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    // ->where('promotionstatus',0)
                                    ->take(1)
                                    ->update([
                                          'studmol'=>$mol,
                                          'semid'=>$semid,
                                          'grantee'=>$grantee,
                                          // 'studstatdate'=>$dateenrolled,
                                          'levelid'=>$levelid,
                                          'sectionid'=>$sectionid,
                                          'strandid'=>$strandid,
                                          'studstatus'=>$studstatus,
                                          'studstatdate'=>$studstatdate,
                                          // 'isearly'=> $isearly,
                                          'remarks'=>$remarks,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }

                        $sectionname = DB::table('sections')
                              ->where('id',$sectionid)
                              ->select('sectionname')
                              ->first()
                              ->sectionname;

                  }else if($levelid == 17 || $levelid == 18 || $levelid == 19 || $levelid == 20 || $levelid == 21){

                        $check = DB::table('college_enrolledstud')
                                          ->where('studid',$studid)
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->where('promotionstatus',1)
                                          ->count();


                        if($check > 0){
                              return array((object)[
                                    'status'=>2,
                                    'message'=>'Already promoted for S.Y.:'.$check_sy->sydesc.' : '.$seminfo->semester
                              ]);
                        }


                        //remove enrollment from other acad
                        DB::table('enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('promotionstatus',0)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('sh_enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('promotionstatus',0)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                        //remove enrollment from other acad

                         //check ernrollment 
                         $check = DB::table('college_enrolledstud')
                                          ->where('studid',$studid)
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->where('promotionstatus',0)
                                          ->count();
                        //check ernrollment

                        if($check == 0 ){
                              $check = DB::table('college_enrolledstud')
                                          ->where('studid',$studid)
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->where('promotionstatus',0)
                                          ->count();
                        }

                        //if no enrollment record
                        if($check == 0 ){
                              DB::table('college_enrolledstud')
                                    ->insert([
                                          'studmol'=>$mol,
                                          'remarks'=>$remarks,
                                          'studid'=>$studid,
                                          'syid'=>$syid,
                                          'yearLevel'=>$levelid,
                                          'sectionid'=>$sectionid,
                                          'courseid'=>$courseid,
                                          'studstatus'=>$studstatus,
                                          'semid'=>$semid,
                                          'promotionstatus'=>0,
                                          'date_enrolled'=>$enrollmentdate,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'admissiontype'=>$admission_type,
                                          'deleted'=>0,
                                          'regStatus'=>$regStatus
                                    ]);
                        }else{
                              DB::table('college_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('promotionstatus',0)
                                    ->take(1)
                                    ->update([
                                          'studmol'=>$mol,
                                          'courseid'=>$courseid,
                                          'yearLevel'=>$levelid,
                                          'remarks'=>$remarks,
                                          'sectionid'=>$sectionid,
                                          'date_enrolled'=>$dateenrolled,
                                          'studstatus'=>$studstatus,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'regStatus'=>$regStatus
                                    ]);
                        }

                        $sectionname = DB::table('college_sections')
                              ->where('id',$sectionid)
                              ->select('sectionDesc as sectionname')
                              ->first()
                              ->sectionname;
                              
                  }else{

                        $check = DB::table('enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('promotionstatus',1)
                                    ->count();


                        if($check > 0){
                              return array((object)[
                                    'status'=>2,
                                    'message'=>'Already promoted for selected year level'
                              ]);
                        }

                        //remove enrollment from other acad
                        DB::table('sh_enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('promotionstatus',0)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('college_enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('promotionstatus',0)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                        //remove enrollment from other acad

                        //check ernrollment 
                        $check = DB::table('enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('promotionstatus',0)
                                    ->count();
                        //check ernrollment 

                        //if no enrollment record
                        if($check == 0 ){
                              DB::table('enrolledstud')
                                    ->insert([
                                          'grantee'=>$grantee,
                                          'studmol'=>$mol,
                                          // 'studstatdate'=>$dateenrolled,
                                          'studstatdate'=>$studstatdate,
                                          'dateenrolled'=>$dateenrolled,
                                          'studid'=>$studid,
                                          'syid'=>$syid,
                                          'levelid'=>$levelid,
                                          'sectionid'=>$sectionid,
                                          'studstatus'=>$studstatus,
                                          'remarks'=>$remarks,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('promotionstatus',0)
                                    ->take(1)
                                    ->update([
                                          'grantee'=>$grantee,
                                          'studmol'=>$mol,
                                          // 'studstatdate'=>$dateenrolled,
                                          'studstatdate'=>$studstatdate,
                                          'levelid'=>$levelid,
                                          'sectionid'=>$sectionid,
                                          'studstatus'=>$studstatus,
                                           'remarks'=>$remarks,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }

                        $sectionname = DB::table('sections')
                              ->where('id',$sectionid)
                              ->select('sectionname')
                              ->first()
                              ->sectionname;

                  }
                 
                  $check_sy = DB::table('sy')
                                    ->where('id',$syid)
                                    ->first();

                 
                  $studinfo = DB::table('studinfo')  
                                    ->where('id',$studid)
                                    ->select(
                                          'contactno',
                                          'ismothernum',
                                          'isfathernum',
                                          'isguardannum',
                                          'mcontactno',
                                          'fcontactno',
                                          'gcontactno',
                                          'mothername',
                                          'fathername',
                                          'guardianname',
                                          'studinfo.levelid',
                                          'studinfo.sectionid',
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'sid',
                                          'feesid',
                                          'studstatus'
                                    )
                                    ->first();


                  if($check_sy->isactive == 1 ||  $studinfo->studstus == 0){
                        DB::table('studinfo')
                              ->where('id',$studid)
                              ->take(1)
                              ->update([
                                    'studstatdate'=>$dateenrolled,
                                    'mol'=>$mol,
                                    'grantee'=>$grantee,
                                    'pantawid'=>$pantawid,
                                    'sectionname'=>$sectionname,
                                    'levelid'=>$levelid,
                                    'sectionid'=>$sectionid,
                                    'studstatus'=>$studstatus,
                                    'strandid'=>$strandid,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              ]);
                  }else{
                        DB::table('studinfo')
                              ->where('id',$studid)
                              ->take(1)
                              ->update([
                                    'courseid'=>$courseid,
                                    'grantee'=>$grantee,
                                    'pantawid'=>$pantawid,
                                    'mol'=>$mol,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              ]);
                  }

            

                  if($studstatus == 0){
                        if($levelid == 14 || $levelid == 15){
                             DB::table('sh_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->where('semid',$semid)
                                    ->where('syid',$syid)
                                    ->where('promotionstatus',0)
                                    ->take(1)
                                    ->update([
                                          'deleted'=>1,
                                          'deletedby'=>auth()->user()->id,
                                          'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else if($levelid == 17 || $levelid == 18 || $levelid == 19 || $levelid == 20 || $levelid == 21){

                              DB::table('college_enrolledstud')
                                                ->where('semid',$semid)
                                                ->where('studid',$studid)
                                                ->where('syid',$syid)
                                                ->where('deleted',0)
                                                ->take(1)
                                                ->update([
                                                      'deleted'=>1,
                                                      'deletedby'=>auth()->user()->id,
                                                      'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);
                        }else{
                              $semid = 1;
                              DB::table('enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('promotionstatus',0)
                                    ->take(1)
                                    ->update([
                                          'deleted'=>1,
                                          'deletedby'=>auth()->user()->id,
                                          'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }


                        $balforwardsetup = DB::table('balforwardsetup')
                                                ->select('classid')
                                                ->first();

                        DB::table('studpayscheddetail')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('classid','!=',$balforwardsetup->classid)
                              ->update([
                                    'deleted'=>1,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('studledger')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereNull('ornum')
                              ->where('classid','!=',$balforwardsetup->classid)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('studledgeritemized')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('classificationid','!=',$balforwardsetup->classid)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('student_pregistration')
                              ->where('id',$preregid)
                              ->take(1)
                              ->update([
                                    'status'=>'SUBMITTED',
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                              
                        $check_mol = DB::table('modeoflearning_student')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                            
                  }


                  if($mol != "" && $studstatus != 0){

                        $check_mol = DB::table('modeoflearning_student')
                                          ->where('studid',$studid)
                                          ->where('syid',$syid)
                                          ->where('deleted',0)
                                          ->get();

                        if(count($check_mol) > 1){

                              DB::table('modeoflearning_student')
                                          ->where('studid',$studid)
                                          ->where('syid',$syid)
                                          ->where('deleted',0)
                                          ->update([
                                                'deleted'=>1,
                                                'deletedby'=>auth()->user()->id,
                                                'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                              DB::table('modeoflearning_student')
                                    ->insert([
                                          'studid'=>$studid,
                                          'mol'=>$mol,
                                          'syid'=>$syid,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'createdby'=>auth()->user()->id,
                                    ]);
                                          
                        }else if(count($check_mol) == 0){
                              DB::table('modeoflearning_student')
                                    ->insert([
                                          'studid'=>$studid,
                                          'mol'=>$mol,
                                          'syid'=>$syid,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'createdby'=>auth()->user()->id,
                                    ]);
                        }else{
                              DB::table('modeoflearning_student')
                                    ->where('id',$check_mol[0]->id)
                                    ->take(1)
                                    ->update([
                                          'mol'=>$mol,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                    ]);
                        }

                       
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Enrollment Updated!'
                  ]);
                
            }catch(\Exception $e){
                  return self::store_error($e);
            }
           

      }

      public static function update_student_contact_info(Request $request){

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
                        ->where('deleted',0)
                        ->take(1)
                        ->update([
                              'contactno'=>str_replace('-','',$contactno),
                              'gcontactno'=>str_replace('-','',$gcontactno),
                              'fcontactno'=>str_replace('-','',$fcontactno),
                              'mcontactno'=>str_replace('-','',$mcontactno),
                              'ismothernum'=>$ismothernum,
                              'isfathernum'=>$isfathernum,
                              'isguardannum'=>$isguardiannum,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Contact Information Updated!'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }
            


      }

      public static function create_student_information(Request $request){


            try{

                  $userid = $request->get('userid');
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $courseid = $request->get('courseid');
                  $strandid = $request->get('strandid');
                  $firstname = $request->get('firstname');
                  $lastname = $request->get('lastname');
                  $middlename = $request->get('middlename');
                  $suffix = $request->get('suffix');
                  $dob = $request->get('dob');
                  $semail = $request->get('semail');
                  $gender = $request->get('gender');
                  $levelid = $request->get('levelid');
                  $admissiontype = $request->get('admissiontype');
                  $mol = $request->get('mol');
                  $contactno = $request->get('contactno');
                  $gcontactno = $request->get('gcontactno');
                  $fcontactno = $request->get('fcontactno');
                  $mcontactno = $request->get('mcontactno');
                  $ismothernum = $request->get('ismothernum');
                  $isfathernum = $request->get('isfathernum');
                  $isguardiannum = $request->get('isguardiannum');
                  $altsid = $request->get('altsid');

                  $acadprogid = DB::table('gradelevel')
                                    ->where('id',$levelid)
                                    ->where('deleted',0)
                                    ->select('acadprogid')
                                    ->first()
                                    ->acadprogid;

                  if($acadprogid != 5 && $acadprogid != 6){
                        if($semid == 2){
                              $semid = 1;
                        }
                  }

                  if($userid == ''){
                        $userid = auth()->user()->id;
                  }

                  $check = DB::table('studinfo')
                                    ->where('lastname',$lastname)
                                    ->where('firstname',$firstname)
                                    ->where('deleted',0)
                                    ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Student Already Exist!'
                        ]);
                  }else{

                        $fathername = '';
                   
                        $fathername .= $request->get('flname') != '' ?  $request->get('flname') : '';
                        $fathername .= $request->get('ffname') != '' ?   $request->get('flname') != '' ? ', '.$request->get('ffname') : $request->get('ffname') : '';
                        $fathername .= $request->get('fsuffix') != '' ?  ', '.$request->get('fsuffix') : '';
                        $fathername .= $request->get('fmname') != '' ?  ', '.$request->get('fmname') : '';
                       
                        $mothername = '';
                        $mothername .= $request->get('mlname') != '' ?  $request->get('mlname') : '';
                        $mothername .= $request->get('mfname') != '' ?   $request->get('mlname') != '' ? ', '.$request->get('mfname') : $request->get('mfname') : '';
                        $mothername .= $request->get('msuffix') != '' ?  ', '.$request->get('msuffix') : '';
                        $mothername .= $request->get('mmname') != '' ?  ', '.$request->get('mmname') : '';

                        $guardianname = '';
                        $guardianname .= $request->get('glname') != '' ?  $request->get('glname') : '';
                        $guardianname .= $request->get('gfname') != '' ?   $request->get('glname') != '' ? ', '.$request->get('gfname') : $request->get('gfname') : '';
                        $guardianname .= $request->get('gsuffix') != '' ?  ', '.$request->get('gsuffix') : '';
                        $guardianname .= $request->get('gmname') != '' ?  ', '.$request->get('gmname') : '';

                        $studid = DB::table('studinfo')
                                    ->insertGetId([
                                          'firstname'=>strtoupper($firstname) == '' ? null : strtoupper($firstname),
                                          'lastname'=>strtoupper($lastname) == '' ? null : strtoupper($lastname),
                                          'middlename'=>strtoupper($middlename) == '' ? null : strtoupper($middlename),
                                          'suffix'=>$suffix,
                                          'dob'=>$dob,
                                          'semail'=>$semail,
                                          'lrn'=>$request->get('lrn'),
                                          'gender'=>$gender,
                                          'levelid'=>$levelid,
                                          'strandid'=>$strandid,
                                          'courseid'=>$courseid,
                                          'street'=>strtoupper($request->get('street')),

                                          'fathername'=>strtoupper($fathername),
                                          'mothername'=>strtoupper($mothername),
                                          'guardianname'=>strtoupper($guardianname),

                                          'foccupation'=>strtoupper($request->get('foccupation')),
                                          'moccupation'=>strtoupper($request->get('moccupation')),
                                          'guardianrelation'=>strtoupper($request->get('relation')),

                                          'mtname'=>strtoupper($request->get('mtname')),
                                          'egname'=>strtoupper($request->get('egname')),
                                          'religionname'=>strtoupper($request->get('religionname')),

                                          'religionid'=>$request->get('religionid'),
                                          'egid'=>$request->get('egid'),
                                          'mtid'=>$request->get('mtid'),
                                          'bloodtype'=>$request->get('bloodtype'),
                                          'barangay'=>strtoupper($request->get('barangay')),
                                          'city'=>strtoupper($request->get('city')),
                                          'province'=>strtoupper($request->get('province')),
                                          'district'=>strtoupper($request->get('district')),
                                          'region'=>strtoupper($request->get('region')),
                                          'nationality'=>strtoupper($request->get('nationality')),
                                          'studtype'=>$request->get('studtype'),
                                          'grantee'=>$request->get('studgrantee'),
                                          'pantawid'=>$request->get('pantawid'),
                                          'deleted'=>0,
                                          'createdby'=>$userid,
                                          'contactno'=>str_replace('-','',$contactno),
                                          'gcontactno'=>str_replace('-','',$gcontactno),
                                          'fcontactno'=>str_replace('-','',$fcontactno),
                                          'mcontactno'=>str_replace('-','',$mcontactno),
                                          'ismothernum'=>$ismothernum,
                                          'isfathernum'=>$isfathernum,
                                          'isguardannum'=>$isguardiannum,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'lastschoolatt'=>$request->get('lastschoolatt'),
                                          'pob'=>$request->get('pob'),
                                          'civilstatus'=>$request->get('maritalstatus'),
                                          'altsid'=>$altsid,
                                          'mol'=>$mol
                                    ]);

                              DB::table('studinfo_more')
                                    ->insert([
                                          'studid'=>$studid,
                                          'ffname'=>strtoupper($request->get('ffname')),
                                          'fmname'=>strtoupper($request->get('fmname')),
                                          'flname'=>strtoupper($request->get('flname')),
                                          'fsuffix'=>strtoupper($request->get('fsuffix')),
                                          
                                          'mfname'=>strtoupper($request->get('mfname')),
                                          'mmname'=>strtoupper($request->get('mmname')),
                                          'mlname'=>strtoupper($request->get('mlname')),
                                          'msuffix'=>strtoupper($request->get('msuffix')),

                                          'gfname'=>strtoupper($request->get('gfname')),
                                          'gmname'=>strtoupper($request->get('gmname')),
                                          'glname'=>strtoupper($request->get('glname')),
                                          'gsuffix'=>strtoupper($request->get('gsuffix')),

                                          'psschoolname'=>strtoupper($request->get('psschoolname')),
                                          'pssy'=>$request->get('pssy'),
                                          'gsschoolname'=>strtoupper($request->get('gsschoolname')),
                                          'gssy'=>$request->get('gssy'),
                                          'jhsschoolname'=>strtoupper($request->get('jhsschoolname')),
                                          'jhssy'=>$request->get('jhssy'),
                                          'shsschoolname'=>strtoupper($request->get('shsschoolname')),
                                          'shssy'=>$request->get('shssy'),
                                          'collegeschoolname'=>strtoupper($request->get('collegeschoolname')),
                                          'collegesy'=>$request->get('collegesy'),

                                          'nocitf'=>$request->get('nocitf'),
                                          'noce'=>$request->get('noce'),
                                          'oitfitf'=>$request->get('oitf'),
                                          'glits'=>$request->get('glits'),
                                          'scn'=>$request->get('scn'),
                                          'cmaosla'=>$request->get('cmaosla'),
                                          'lsah'=>$request->get('lsah'),

                                          'fea'=>$request->get('fea'),
                                          'mea'=>$request->get('mea'),
                                          'gea'=>$request->get('gea'),
                                          
                                          'fmi'=>$request->get('fmi'),
                                          'mmi'=>$request->get('mmi'),
                                          'gmi'=>$request->get('gmi'),

                                          'fosoi'=>$request->get('fosoi'),
                                          'mosoi'=>$request->get('mosoi'),
                                          'gosoi'=>$request->get('gosoi'),
                                          
                                          'fethnicity'=>$request->get('fethnicity'),
                                          'methnicity'=>$request->get('methnicity'),
                                          'gethnicity'=>$request->get('gethnicity'),

                                          'bec_cell'=>$request->get('bec_cell'),
                                          'chapelzone'=>$request->get('chapelzone'),

                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'mothermaiden'=>strtoupper($request->get('maidenname')),

                                          'mfi'=>$request->get('mfi'),
                                          'psschooltype'=>$request->get('psschooltype'),
                                          'gsschooltype'=>$request->get('gsschooltype'),
                                          'jhsschooltype'=>$request->get('jhsschooltype'),
                                          'shsschooltype'=>$request->get('shsschooltype'),
                                          'collegeschooltype'=>$request->get('collegeschooltype')

                                    ]);


                              DB::table('apmc_midinfo')
                                    ->insert([
                                          'studid'=>$studid,
                                          'vacc'=>$request->get('vacc'),
                                          'vacc_type_id'=>$request->get('vacc_type_1st'),
                                          'vacc_type_2nd_id'=>$request->get('vacc_type_2nd'),
                                          'vacc_type_booster'=>$request->get('vacc_type_booster') != null ?  $request->get('vacc_type_text_booster') : null,
                                          'vacc_type'=>$request->get('vacc_type_1st') != null ? $request->get('vacc_type_text_1st') : null,
                                          'vacc_type_2nd'=>$request->get('vacc_type_2nd') != null ?  $request->get('vacc_type_text_2nd') : null,
                                          'vacc_card_id'=>$request->get('vacc_card_id'),
                                          'dose_date_1st'=>$request->get('dose_date_1st'),
                                          'dose_date_2nd'=>$request->get('dose_date_2nd'),
                                          'philhealth'=>$request->get('philhealth'),
                                          'bloodtype'=>$request->get('bloodtype'),
                                          'allergy_to_med'=>$request->get('allergy_to_med'),
                                          'med_his'=>$request->get('med_his'),
                                          'other_med_info'=>$request->get('other_med_info'),

                                          
                                          'createdby'=>$userid,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);



                        $acadprog = DB::table('gradelevel')
                                          ->where('id',$levelid)
                                          ->where('deleted',0)
                                          ->first()
                                          ->acadprogid;

                        if($acadprogid == 6){
                  
                              $curriculum = $request->get('curriculum');

                              DB::table('college_studentcurriculum')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->take(1)
                                    ->update([
                                          'deleted'=>1,
                                          'deletedby'=>$userid,
                                          'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              DB::table('college_studentcurriculum')
                                    ->insert([
                                          'studid'=>$studid,
                                          'curriculumid'=>$curriculum,
                                          'createdby'=>$userid,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }

                        $sid = \App\RegistrarModel::idprefix($acadprog,$studid);

                        DB::table('studinfo')
                              ->where('id', $studid)
                              ->take(1)
                              ->update([
                                    'sid' => $sid,
                                    'picurl'=>'storage/STUDENT/'.$sid.'.jpg',
                              ]);

                        // DB::table('student_pregistration')
                        //       ->insert([
                        //             'transtype'=>'WALK-IN',
                        //             'admission_type'=>$admissiontype,
                        //             'semid'=>$semid,
                        //             'studid'=>$studid,
                        //             'syid'=>$syid,
                        //             'deleted'=>0,
                        //             'status'=>'SUBMITTED',
                        //             'gradelvl_to_enroll'=>$levelid,
                        //             'createdby'=>$userid,
                        //             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        //       ]);


                        return array((object)[
                              'status'=>1,
                              'message'=>'Student Created!'
                        ]);
                  }

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function update_student_information(Request $request){
            try{

                  $userid = $request->get('userid');
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $courseid = $request->get('courseid');
                  $strandid = $request->get('strandid');
                  $firstname = $request->get('firstname');
                  $lastname = $request->get('lastname');
                  $middlename = $request->get('middlename');
                  $suffix = $request->get('suffix');
                  $dob = $request->get('dob');
                  $semail = $request->get('semail');
                  $gender = $request->get('gender');
                  $levelid = $request->get('levelid');
                  $admissiontype = $request->get('admissiontype');
                  $contactno = $request->get('contactno');
                  $gcontactno = $request->get('gcontactno');
                  $fcontactno = $request->get('fcontactno');
                  $mcontactno = $request->get('mcontactno');
                  $ismothernum = $request->get('ismothernum');
                  $isfathernum = $request->get('isfathernum');
                  $isguardiannum = $request->get('isguardiannum');
                  $studid = $request->get('studid');
                  $altsid = $request->get('altsid');

                  $acadprogid = DB::table('gradelevel')
                                    ->where('id',$levelid)
                                    ->where('deleted',0)
                                    ->select('acadprogid')
                                    ->first()
                                    ->acadprogid;

                  if($acadprogid != 5 && $acadprogid != 6){
                        if($semid == 2){
                              $semid = 1;
                        }
                  }

                  if($userid == ''){
                        $userid = auth()->user()->id;
                  }

                  $check = DB::table('studinfo')
                                    ->where('id','!=',$studid)
                                    ->where('lastname',$lastname)
                                    ->where('firstname',$firstname)
                                    ->where('deleted',0)
                                    ->where('studisactive',1)
                                    ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Student Already Exist!'
                        ]);
                  }else{


                        $fathername = '';
                   
                        $fathername .= $request->get('flname') != '' ?  $request->get('flname') : '';
                        $fathername .= $request->get('ffname') != '' ?   $request->get('flname') != '' ? ', '.$request->get('ffname') : $request->get('ffname') : '';
                        $fathername .= $request->get('fsuffix') != '' ?  ', '.$request->get('fsuffix') : '';
                        $fathername .= $request->get('fmname') != '' ?  ', '.$request->get('fmname') : '';

                       
                        $mothername = '';
                        $mothername .= $request->get('mlname') != '' ?  $request->get('mlname') : '';
                        $mothername .= $request->get('mfname') != '' ?   $request->get('mlname') != '' ? ', '.$request->get('mfname') : $request->get('mfname') : '';
                        $mothername .= $request->get('msuffix') != '' ?  ', '.$request->get('msuffix') : '';
                        $mothername .= $request->get('mmname') != '' ?  ', '.$request->get('mmname') : '';

                        $guardianname = '';
                        $guardianname .= $request->get('glname') != '' ?  $request->get('glname') : '';
                        $guardianname .= $request->get('gfname') != '' ?   $request->get('glname') != '' ? ', '.$request->get('gfname') : $request->get('gfname') : '';
                        $guardianname .= $request->get('gsuffix') != '' ?  ', '.$request->get('gsuffix') : '';
                        $guardianname .= $request->get('gmname') != '' ?  ', '.$request->get('gmname') : '';


                        DB::table('studinfo')
                                    ->where('id',$studid)
                                    ->take(1)
                                    ->update([
                                          'firstname'=>strtoupper($firstname) == '' ? null : strtoupper($firstname),
                                          'lastname'=>strtoupper($lastname) == '' ? null : strtoupper($lastname),
                                          'middlename'=>strtoupper($middlename) == '' ? null : strtoupper($middlename),
                                          'suffix'=>$suffix,
                                          'dob'=>$dob,
                                          'semail'=>$semail,
                                          'lrn'=>$request->get('lrn'),
                                          'gender'=>$gender,
                                          'levelid'=>$levelid,
                                          'strandid'=>$strandid,
                                          'courseid'=>$courseid,
                                          'street'=>strtoupper($request->get('street')),

                                          'fathername'=>strtoupper($fathername),
                                          'mothername'=>strtoupper($mothername),
                                          'guardianname'=>strtoupper($guardianname),

                                          'foccupation'=>strtoupper($request->get('foccupation')),
                                          'moccupation'=>strtoupper($request->get('moccupation')),
                                          'guardianrelation'=>strtoupper($request->get('relation')),
                                          'bloodtype'=>$request->get('bloodtype'),
                                          'barangay'=>strtoupper($request->get('barangay')),
                                          'city'=>strtoupper($request->get('city')),
                                          'province'=>strtoupper($request->get('province')),
                                          'district'=>strtoupper($request->get('district')),
                                          'region'=>strtoupper($request->get('region')),
                                          'nationality'=>strtoupper($request->get('nationality')),
                                          'studtype'=>$request->get('studtype'),
                                          'grantee'=>$request->get('studgrantee'),
                                          'pantawid'=>$request->get('pantawid'),
                                          'deleted'=>0,
                                          'createdby'=>$userid,
                                          'contactno'=>str_replace('-','',$contactno),
                                          'gcontactno'=>str_replace('-','',$gcontactno),
                                          'fcontactno'=>str_replace('-','',$fcontactno),
                                          'mcontactno'=>str_replace('-','',$mcontactno),
                                          'ismothernum'=>$ismothernum,
                                          'isfathernum'=>$isfathernum,
                                          'isguardannum'=>$isguardiannum,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),

                                          'mtname'=>strtoupper($request->get('mtname')),
                                          'egname'=>strtoupper($request->get('egname')),
                                          'religionname'=>strtoupper($request->get('religionname')),

                                          'religionid'=>$request->get('religionid'),
                                          'egid'=>$request->get('egid'),
                                          'mtid'=>$request->get('mtid'),
                                          'lastschoolatt'=>$request->get('lastschoolatt'),
                                          'pob'=>$request->get('pob'),
                                          'civilstatus'=>$request->get('maritalstatus'),
                                          'altsid'=>$altsid,

                                          'mol'=>$request->get('mol')
                                    ]);

                              

                              if($acadprogid == 6){

                                    $curriculum = $request->get('curriculum');

                                    DB::table('college_studentcurriculum')
                                          ->where('studid',$studid)
                                          ->where('deleted',0)
                                          ->take(1)
                                          ->update([
                                                'deleted'=>1,
                                                'deletedby'=>auth()->user()->id,
                                                'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                                    DB::table('college_studentcurriculum')
                                          ->insert([
                                                'studid'=>$studid,
                                                'curriculumid'=>$curriculum,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }

                              $check_moreinfo = DB::table('studinfo_more')
                                    ->where('deleted',0)
                                    ->where('studid',$studid)
                                    ->count();

                              if($check_moreinfo == 0 ){

                                    DB::table('studinfo_more')
                                          ->insert([
                                                'studid'=>$studid,
                                                'ffname'=>strtoupper($request->get('ffname')),
                                                'fmname'=>strtoupper($request->get('fmname')),
                                                'flname'=>strtoupper($request->get('flname')),
                                                'fsuffix'=>strtoupper($request->get('fsuffix')),
                                                
                                                'mfname'=>strtoupper($request->get('mfname')),
                                                'mmname'=>strtoupper($request->get('mmname')),
                                                'mlname'=>strtoupper($request->get('mlname')),
                                                'msuffix'=>strtoupper($request->get('msuffix')),
      
                                                'gfname'=>strtoupper($request->get('gfname')),
                                                'gmname'=>strtoupper($request->get('gmname')),
                                                'glname'=>strtoupper($request->get('glname')),
                                                'gsuffix'=>strtoupper($request->get('gsuffix')),
                                                'mothermaiden'=>strtoupper($request->get('maidenname')),
                                                'psschoolname'=>strtoupper($request->get('psschoolname')),
                                                'pssy'=>$request->get('pssy'),
                                                'gsschoolname'=>strtoupper($request->get('gsschoolname')),
                                                'gssy'=>$request->get('gssy'),
                                                'jhsschoolname'=>strtoupper($request->get('jhsschoolname')),
                                                'jhssy'=>$request->get('jhssy'),
                                                'shsschoolname'=>strtoupper($request->get('shsschoolname')),
                                                'shssy'=>$request->get('shssy'),
                                                'collegeschoolname'=>strtoupper($request->get('collegeschoolname')),
                                                'collegesy'=>$request->get('collegesy'),

                                                'nocitf'=>$request->get('nocitf'),
                                                'noce'=>$request->get('noce'),
                                                'oitfitf'=>$request->get('oitf'),
                                                'glits'=>$request->get('glits'),
                                                'scn'=>$request->get('scn'),
                                                'cmaosla'=>$request->get('cmaosla'),
                                                'lsah'=>$request->get('lsah'),

                                                'fea'=>$request->get('fea'),
                                                'mea'=>$request->get('mea'),
                                                'gea'=>$request->get('gea'),

                                                'fha'=>$request->get('fha'),
                                                'mha'=>$request->get('mha'),
                                                'gha'=>$request->get('gha'),

                                                'bec_cell'=>$request->get('bec_cell'),
                                                'chapelzone'=>$request->get('chapelzone'),

                                                'createdby'=>$userid,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                 
                                                'mfi'=>$request->get('mfi'),
                                                'psschooltype'=>$request->get('psschooltype'),
                                                'gsschooltype'=>$request->get('gsschooltype'),
                                                'jhsschooltype'=>$request->get('jhsschooltype'),
                                                'shsschooltype'=>$request->get('shsschooltype'),
                                                'collegeschooltype'=>$request->get('collegeschooltype')
                                          ]);

                              }else{
                                    DB::table('studinfo_more')
                                          ->where('studid',$studid)
                                          ->take(1)
                                          ->update([
                                                'ffname'=>strtoupper($request->get('ffname')),
                                                'fmname'=>strtoupper($request->get('fmname')),
                                                'flname'=>strtoupper($request->get('flname')),
                                                'fsuffix'=>strtoupper($request->get('fsuffix')),
                                                
                                                'mfname'=>strtoupper($request->get('mfname')),
                                                'mmname'=>strtoupper($request->get('mmname')),
                                                'mlname'=>strtoupper($request->get('mlname')),
                                                'msuffix'=>strtoupper($request->get('msuffix')),
      
                                                'gfname'=>strtoupper($request->get('gfname')),
                                                'gmname'=>strtoupper($request->get('gmname')),
                                                'glname'=>strtoupper($request->get('glname')),
                                                'gsuffix'=>strtoupper($request->get('gsuffix')),
                                                'mothermaiden'=>strtoupper($request->get('maidenname')),
                                                'psschoolname'=>strtoupper($request->get('psschoolname')),
                                                'pssy'=>$request->get('pssy'),
                                                'gsschoolname'=>strtoupper($request->get('gsschoolname')),
                                                'gssy'=>$request->get('gssy'),
                                                'jhsschoolname'=>strtoupper($request->get('jhsschoolname')),
                                                'jhssy'=>$request->get('jhssy'),
                                                'shsschoolname'=>strtoupper($request->get('shsschoolname')),
                                                'shssy'=>$request->get('shssy'),
                                                'collegeschoolname'=>strtoupper($request->get('collegeschoolname')),
                                                'collegesy'=>$request->get('collegesy'),

                                                'nocitf'=>$request->get('nocitf'),
                                                'noce'=>$request->get('noce'),
                                                'oitfitf'=>$request->get('oitf'),
                                                'glits'=>$request->get('glits'),
                                                'scn'=>$request->get('scn'),
                                             
                                                'cmaosla'=>$request->get('cmaosla'),
                                                'lsah'=>$request->get('lsah'),

                                                'fea'=>$request->get('fea'),
                                                'mea'=>$request->get('mea'),
                                                'gea'=>$request->get('gea'),
                                                
                                                'fmi'=>$request->get('fmi'),
                                                'mmi'=>$request->get('mmi'),
                                                'gmi'=>$request->get('gmi'),

                                                'fosoi'=>$request->get('fosoi'),
                                                'mosoi'=>$request->get('mosoi'),
                                                'gosoi'=>$request->get('gosoi'),
                                                
                                                'fethnicity'=>$request->get('fethnicity'),
                                                'methnicity'=>$request->get('methnicity'),
                                                'gethnicity'=>$request->get('gethnicity'),
                                                
                                                'fha'=>$request->get('fha'),
                                                'mha'=>$request->get('mha'),
                                                'gha'=>$request->get('gha'),

                                                'bec_cell'=>$request->get('bec_cell'),
                                                'chapelzone'=>$request->get('chapelzone'),

                                                'updatedby'=>$userid,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),

                                                'mfi'=>$request->get('mfi'),
                                                'psschooltype'=>$request->get('psschooltype'),
                                                'gsschooltype'=>$request->get('gsschooltype'),
                                                'jhsschooltype'=>$request->get('jhsschooltype'),
                                                'shsschooltype'=>$request->get('shsschooltype'),
                                                'collegeschooltype'=>$request->get('collegeschooltype')
                                          ]);
                              }
                              //$check vac info

                              $check_vaccine = DB::table('apmc_midinfo')
                                                ->where('deleted',0)
                                                ->where('studid',$studid)
                                                ->count();

                              if($check_vaccine == 0 ){
                                    DB::table('apmc_midinfo')
                                          ->insert([
                                                'studid'=>$studid,
                                                'vacc'=>$request->get('vacc'),
                                                'vacc_type_id'=>$request->get('vacc_type_1st'),
                                                'vacc_type_id'=>$request->get('vacc_type_1st'),

                                                'booster_type_id'=>$request->get('vacc_type_booster'),
                                                'vacc_type_booster'=>$request->get('vacc_type_booster') != null ?  $request->get('vacc_type_text_booster') : null,
                                                'dose_date_booster'=>$request->get('dose_date_booster'),
                                                
                                                'vacc_type_2nd_id'=>$request->get('vacc_type_2nd'),
                                                'vacc_type'=>$request->get('vacc_type_1st') != null ? $request->get('vacc_type_text_1st') : null,
                                                'vacc_type_2nd'=>$request->get('vacc_type_2nd') != null ?  $request->get('vacc_type_text_2nd') : null,
                                                'vacc_card_id'=>$request->get('vacc_card_id'),
                                                'dose_date_1st'=>$request->get('dose_date_1st'),
                                                'dose_date_2nd'=>$request->get('dose_date_2nd'),
                                                'philhealth'=>$request->get('philhealth'),
                                                'bloodtype'=>$request->get('bloodtype'),
                                                'allergy_to_med'=>$request->get('allergy_to_med'),
                                                'med_his'=>$request->get('med_his'),
                                                'other_med_info'=>$request->get('other_med_info'),

                                               

                                                'createdby'=>$userid,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }else{
                                    DB::table('apmc_midinfo')
                                          ->where('studid',$studid)
                                          ->take(1)
                                          ->update([
                                                'vacc'=>$request->get('vacc'),
                                                'vacc_type_id'=>$request->get('vacc_type_1st'),
                                                'vacc_type_2nd_id'=>$request->get('vacc_type_2nd'),
                                                'vacc_type'=>$request->get('vacc_type_1st') != null ? $request->get('vacc_type_text_1st') : null,
                                                'vacc_type_2nd'=>$request->get('vacc_type_2nd') != null ?  $request->get('vacc_type_text_2nd') : null,
                                                'booster_type_id'=>$request->get('vacc_type_booster'),
                                                'vacc_type_booster'=>$request->get('vacc_type_booster') != null ?  $request->get('vacc_type_text_booster') : null,
                                                'vacc_card_id'=>$request->get('vacc_card_id'),
                                                'dose_date_1st'=>$request->get('dose_date_1st'),
                                                'dose_date_2nd'=>$request->get('dose_date_2nd'),
                                                'dose_date_booster'=>$request->get('dose_date_booster'),
                                                'philhealth'=>$request->get('philhealth'),
                                                'bloodtype'=>$request->get('bloodtype'),
                                                'allergy_to_med'=>$request->get('allergy_to_med'),

                                                'med_his'=>$request->get('med_his'),
                                                'other_med_info'=>$request->get('other_med_info'),

                                                

                                                'updatedby'=>$userid,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }
      

                              

                        return array((object)[
                              'status'=>1,
                              'message'=>'Student Updated!'
                        ]);
                  }

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }



      public static function add_student_to_prereg(Request $request){

            try{

                  $studid = $request->get('studid');
                  $syid  = $request->get('syid');
                  $levelid  = $request->get('levelid');
                  $userid = $request->get('userid');
                  $semid = $request->get('semid');
                  $strand = $request->get('strand');
                  $course = $request->get('course');
                  $admissiontype = $request->get('admissiontype');

                  $acadprogid = DB::table('gradelevel')
                                    ->where('id',$levelid)
                                    ->where('deleted',0)
                                    ->select('acadprogid')
                                    ->first()
                                    ->acadprogid;

                  if($acadprogid != 5 && $acadprogid != 6){
                        if($semid == 2){
                              $semid = 1;
                        }
                  }

                  $check = DB::table('student_pregistration')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('studid',$studid)
                                    ->where('admission_type',$admissiontype)
                                    ->count();

                 

                  if($userid == ''){
                        $userid = auth()->user()->id;
                  }

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Student Already Exist!'
                        ]);
                  }else{

                        $id = DB::table('student_pregistration')
                              ->insertGetId([
                                    'transtype'=>'WALK-IN',
                                    'studid'=>$studid,
                                    'syid'=>$syid,
                                    'deleted'=>0,
                                    'semid'=>$semid,
                                    'status'=>'SUBMITTED',
                                    'createdby'=>$userid,
                                    'gradelvl_to_enroll'=>$levelid,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'admission_type'=>$admissiontype,
                                    'admission_strand'=>$strand,
                                    'admission_course'=>$course
                              ]);

                        return array((object)[
                              'id'=>$id,
                              'status'=>1,
                              'message'=>'Student Added!'
                        ]);
                  }

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function all_students(Request $request){

        
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $ghssemid = $semid == 3 ? $semid : 1;

            $acadprog_list = self::get_acad($syid);

            
            $all_enrolledstudents = array();

            $enrolled = DB::table('enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->join('sections',function($join){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('syid',$syid)
                              ->where('ghssemid',$ghssemid)
                              ->where('enrolledstud.deleted',0)
                              ->select(
                                    'dateenrolled',
                                    'enrolledstud.levelid',
                                    'levelname',
                                    'studid',
                                    'sectionid',
                                    'description',
                                    'studstatus',
                                    'sectionname',
                                    // 'isearly',
                                    'sortid',
                                    'promotionstatus'
                              )
                              ->distinct('studid')
                              ->get();

        
            foreach($enrolled as $item){
                  array_push($all_enrolledstudents,$item);
            }

            $enrolled = DB::table('sh_enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->select(
                                    'dateenrolled',
                                    'sh_enrolledstud.levelid',
                                    'levelname',
                                    'sectionid',
                                    'studid',
                                    'description',
                                    'studstatus',
                                    'sectionname',
                                    // 'isearly',
                                    'sortid',
                                    'strandid',
                                    'promotionstatus'
                              )
                              ->distinct('studid')
                              ->get();

            foreach($enrolled as $item){
                  array_push($all_enrolledstudents,$item);
            }

            $enrolled = DB::table('college_enrolledstud')
                              ->join('studentstatus',function($join){
                                    $join->on('college_enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->join('college_sections',function($join){
                                    $join->on('college_enrolledstud.sectionID','=','college_sections.id');
                                    $join->where('college_sections.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('college_enrolledstud.syid',$syid)
                              ->where('semid',$semid)
                              ->where('college_enrolledstud.deleted',0)
                              ->select(
                                    'date_enrolled as dateenrolled',
                                    'yearLevel as levelid',
                                    'sectionID as sectionid',
                                    'levelname',
                                    'studid',
                                    'description',
                                    'studstatus',
                                    'sectionDesc as sectionname',
                                    // 'isearly',
                                    'sortid',
                                    'promotionstatus'
                              )
                              ->distinct('studid')
                              ->get();

            foreach($enrolled as $item){
                  array_push($all_enrolledstudents,$item);
            }


            $students = Db::table('studinfo')
                              ->where('studinfo.deleted',0)
                              ->join('gradelevel',function($join) use($acadprog_list){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('acadprogid',$acadprog_list);
                              })
                              ->select(
                                    'levelid',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'sid',
                                    'studinfo.id',
                                    'strandid',
                                    'courseid',
                                    'studinfo.nodp'
                              )
                              ->get();

            $all_students =  array();
            foreach($students as $item){
                  $check = collect($all_enrolledstudents)->where('studid',$item->id)->count();
                  if($check == 0){
                        $temp_middle = '';
                        $temp_suffix = '';
                        if(isset($item->middlename)){
                              if(strlen($item->middlename) > 0){
                                    $temp_middle = ' '.$item->middlename[0].'.';
                              }
                        }
                        if(isset($item->suffix)){
                              $temp_suffix = ' '.$item->suffix;
                        }
                        $item->student = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                        $item->search =  $item->sid.' - '.$item->student;
                        $item->text =  $item->sid.' - '.$item->student;
                        array_push($all_students,$item);
                  }
                 
            }

            return $all_students;
            
      }

      public static function student_college_info(Request $request){

            $syid = $request->get('syid');
            $semid  = $request->get('syid');
            $studid = $request->get('studid');

            $section = DB::table('college_enrolledstud')
                        ->join('college_sections',function($join){
                              $join->on('college_enrolledstud.sectionID','=','college_sections.id');
                              $join->where('college_sections.deleted',0);
                        })
                        ->where('college_enrolledstud.studid',$studid)
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->select(
                              'sectionDesc as text',
                              'college_sections.id'
                        )
                        ->first();
                  

            return array((object)[
                  'section'=>$section
            ]);  
           

      }

      public static function remove_student(Request $request){

            try{

                  $studid = $request->get('studid');

                  $check = DB::table('sh_enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Contains enrollment information'
                        ]);
                  }

                  $check = DB::table('enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Contains enrollment information'
                        ]);
                  }


                  $check = DB::table('college_enrolledstud')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Contains enrollment information'
                        ]);
                  }

                  $check = DB::table('college_studsched')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'College Schedule information'
                        ]);
                  }

                  $check = DB::table('gradesdetail')
                              ->where('studid',$studid)
                              ->count();
                              

                  if($check > 0){

                  
                        return array((object)[
                              'status'=>0,
                              'message'=>'Contains grades'
                        ]);
                  }

                  $check = DB::table('college_studentprospectus')
                              ->where('studid',$studid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return "sdsf";
                        return array((object)[
                              'status'=>0,
                              'message'=>'Contains grades'
                        ]);
                  }

                  $check = DB::table('chrngtrans')
                              ->where('studid',$studid)
                              ->where('cancelled',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Contains payment'
                        ]);
                  }

                  DB::table('studinfo')
                        ->where('id',$studid)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Deleted Successfully'
                  ]);
            
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function store_error($e){
            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        //'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'data'=>'Something went wrong!'
            ]);
      }

}
