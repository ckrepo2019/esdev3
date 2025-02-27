<?php

namespace App\Http\Controllers\SuperAdminController\Printables;

use Illuminate\Http\Request;
use DB;
use Hash;
use PDF;

class EnrollmentPrintables extends \App\Http\Controllers\Controller
{
	
	 public static function print_fess(Request $request){

            $studid = $request->get('studid');

            $studinfo = DB::table('studinfo')
                              ->where('id',$studid)
                              ->first();

            $schoolinfo = DB::table('schoolinfo')->first();

            
            $middlename = '';
            $suffix = '';

            if(isset($studinfo->middlename)){
                  $middlename =  ', '.$studinfo->middlename[0].'.';
            }
           
            if(isset($studinfo->suffix)){
                $suffix =  ' '.$studinfo->suffix;
            }

            $studinfo->fullname = $studinfo->lastname.', '.$studinfo->firstname.$middlename.$suffix;
                              
            $request->request->add(['feesid' => $studinfo->feesid]);

            //$feesinfo = \App\Http\Controllers\FinanceControllers\UtilityController::u_viewtuitiondetails($request);

            $tuition = db::table('tuitionheader')
				->select(db::raw('tuitionheader.`description`, itemclassification.`description` AS classname,  tuitiondetail.id AS detailid'))
				->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
				->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
				->where('tuitionheader.id', $studinfo->feesid)
				->where('tuitionheader.deleted', 0)
				->where('tuitiondetail.deleted', 0)
				->groupBy('itemclassification.id')
				->get();

			$list = '';
			$grandtotal = 0;

			foreach($tuition as $tui)
			{
				$details = db::table('tuitionitems')
					->select(db::raw('itemclassification.description AS classname, items.description AS itemname, tuitionitems.amount'))
					->join('tuitiondetail', 'tuitionitems.tuitiondetailid', '=', 'tuitiondetail.id')
					->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
					->join('items', 'tuitionitems.itemid', '=', 'items.id')
					->where('tuitiondetailid', $tui->detailid)
					->where('tuitionitems.deleted', 0)
					->get();
				$tui->details = $details;
			}
			
			$tuitionheader = db::table('tuitionheader')
								->where('tuitionheader.id', $studinfo->feesid)
								->where('deleted', 0)
								->first();

            $pdf = PDF::loadView('superadmin.pages.printable.printfees',compact('tuition','schoolinfo','studinfo','tuitionheader'))->setPaper('legal');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream('Student Fees.pdf');                       

      }

	
      
      public static function enrollment_by_religious_affiliation(Request $request){
            
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $gradelevel = $request->get('gradelevel');

            $gradelevel = explode(',',$gradelevel);

            $gs = DB::table('enrolledstud')
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.deleted',0)
                        ->whereIn('enrolledstud.levelid',$gradelevel)
                        ->join('studinfo',function($join){
                              $join->on('enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->select(
                            'studid',
                              'religionid',
                              'enrolledstud.levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                       ->distinct('studid')
                        ->get();
                        
            $shs = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.deleted',0)
                        ->join('studinfo',function($join){
                              $join->on('sh_enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->whereIn('sh_enrolledstud.levelid',$gradelevel)
                        ->select(
                             'studid',
                              'religionid',
                              'sh_enrolledstud.levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                       ->distinct('studid')
                        ->get();

            $college = DB::table('college_enrolledstud')
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->where('college_enrolledstud.deleted',0)
                        ->join('studinfo',function($join){
                              $join->on('college_enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('college_enrolledstud.yearLevel',$gradelevel)
                        ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                        ->select(
                            'studid',
                              'religionid',
                              'college_enrolledstud.yearLevel as levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                        ->distinct('studid')
                        ->get();

            $students = collect($gs)->merge($shs);
            $students = collect($students)->merge($college);

            $schoolinfo = DB::table('schoolinfo')->first();

            $gradelevel = Db::table('gradelevel')
                        ->orderBy('sortid')
                        ->whereIn('id',$gradelevel)
                        ->select(
                              'id',
                              'levelname',
                              'acadprogid'
                        )
                        ->get();

            $academicprogram = Db::table('academicprogram')
                        ->whereIn('id',collect($gradelevel)->pluck('acadprogid'))
                        ->select(
                              'id',
                              'progname'
                        )
                        ->get();

      $syinfo = DB::table('sy')
                  ->where('id',$syid)
                  ->select('sydesc')
                  ->first();
     
            $syinfo = DB::table('sy')
                              ->where('id',$syid)
                              ->select('sydesc')
                              ->first();
                              
            // $size = 50; 
            // $arrayOfArrays = array();
            // for ($x = 0; $x< count($students); $x+=$size) {
            //      array_push($arrayOfArrays,array_slice(collect($students)->toArray(),$x,$x+$size));
            //     //  array_push($arrayOfArrays,(object)[$x]);
            // }
            
            // return ;

            $pdf = PDF::loadView('superadmin.pages.printable.enrollmentbyreligiousaffiliation',compact('syinfo','schoolinfo','students','academicprogram','gradelevel'))->setPaper('legal');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();
      }


      public static function enrollmentbyethnicgroup(Request $request){
            
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $gradelevel = $request->get('gradelevel');

            $gradelevel = explode(',',$gradelevel);

            $gs = DB::table('enrolledstud')
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.deleted',0)
                        ->whereIn('enrolledstud.levelid',$gradelevel)
                        ->join('studinfo',function($join){
                              $join->on('enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->select(
                            'studid',
                              'egid',
                              'enrolledstud.levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                       ->distinct('studid')
                        ->get();
                        
            $shs = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.deleted',0)
                        ->join('studinfo',function($join){
                              $join->on('sh_enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->whereIn('sh_enrolledstud.levelid',$gradelevel)
                        ->select(
                             'studid',
                              'egid',
                              'sh_enrolledstud.levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                       ->distinct('studid')
                        ->get();

            $college = DB::table('college_enrolledstud')
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->where('college_enrolledstud.deleted',0)
                        ->join('studinfo',function($join){
                              $join->on('college_enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('college_enrolledstud.yearLevel',$gradelevel)
                        ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                        ->select(
                            'studid',
                              'egid',
                              'college_enrolledstud.yearLevel as levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                        ->distinct('studid')
                        ->get();

            $students = collect($gs)->merge($shs);
            $students = collect($students)->merge($college);

            // return $students;

            $schoolinfo = DB::table('schoolinfo')->first();

            $gradelevel = Db::table('gradelevel')
                              ->orderBy('sortid')
                              ->whereIn('id',$gradelevel)
                              ->select(
                                    'id',
                                    'levelname',
                                    'acadprogid'
                              )
                              ->get();


                              
            $ethnic = Db::table('ethnic')
                              ->where('deleted',0)
                              ->select(
                                    'id',
                                    'egname'
                              )
                              ->get();
            
            $academicprogram = Db::table('academicprogram')
                              ->whereIn('id',collect($gradelevel)->pluck('acadprogid'))
                              ->select(
                                    'id',
                                    'progname'
                              )
                              ->get();
     
            $syinfo = DB::table('sy')
                              ->where('id',$syid)
                              ->select('sydesc')
                              ->first();

            $pdf = PDF::loadView('superadmin.pages.printable.enrollmentbyethnicgroup',compact('ethnic','syinfo','schoolinfo','students','academicprogram','gradelevel'))->setPaper('legal');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();
      }


      public static function enrollmentunenrolled(Request $request){
            
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $gradelevel = $request->get('gradelevel');

            $gradelevel = explode(',',$gradelevel);

            $gs = DB::table('enrolledstud')
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.deleted',0)
                        ->whereIn('enrolledstud.levelid',$gradelevel)
                        ->join('studinfo',function($join){
                              $join->on('enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        // ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->select(
                              'enrolledstud.studstatus',
                            'studid',
                              'enrolledstud.levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                       ->distinct('studid')
                        ->get();
                        
            $shs = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.deleted',0)
                        ->join('studinfo',function($join){
                              $join->on('sh_enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        // ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->whereIn('sh_enrolledstud.levelid',$gradelevel)
                        ->select(
                              'sh_enrolledstud.studstatus',
                             'studid',
                              'sh_enrolledstud.levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                       ->distinct('studid')
                        ->get();

            $college = DB::table('college_enrolledstud')
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->where('college_enrolledstud.deleted',0)
                        ->join('studinfo',function($join){
                              $join->on('college_enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('college_enrolledstud.yearLevel',$gradelevel)
                        // ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                        ->select(
                              'college_enrolledstud.studstatus',
                            'studid',
                              'egid',
                              'college_enrolledstud.courseid',
                              'college_enrolledstud.yearLevel as levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                        ->distinct('studid')
                        ->get();


            $students = collect($gs)->merge($shs);
            $students = collect($students)->merge($college);

            $gs_unenrolled = DB::table('enrolledstud')
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.deleted',1)
                        ->whereIn('enrolledstud.levelid',$gradelevel)
                        ->join('studinfo',function($join){
                              $join->on('enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('enrolledstud.studstatus',[0])
                        ->select(
                              'enrolledstud.studstatus',
                            'studid',
                              'egid',
                              'enrolledstud.levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                       ->distinct('studid')
                        ->get();
                        
            $shs_unenrolled = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.deleted',1)
                        ->join('studinfo',function($join){
                              $join->on('sh_enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('sh_enrolledstud.studstatus',[0])
                        ->whereIn('sh_enrolledstud.levelid',$gradelevel)
                        ->select(
                              'sh_enrolledstud.studstatus',
                             'studid',
                              'egid',
                              'sh_enrolledstud.levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                       ->distinct('studid')
                        ->get();

            $college_unenrolled = DB::table('college_enrolledstud')
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->where('college_enrolledstud.deleted',1)
                        ->join('studinfo',function($join){
                              $join->on('college_enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->whereIn('college_enrolledstud.yearLevel',$gradelevel)
                        ->whereIn('college_enrolledstud.studstatus',[0])
                        ->select(
                              'college_enrolledstud.studstatus',
                              'studid',
                              'egid',
                              'college_enrolledstud.courseid',
                              'college_enrolledstud.yearLevel as levelid',
                              DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('studentname')
                        ->distinct('studid')
                        ->get();


            $students_unenrolled = collect($gs_unenrolled)->merge($shs_unenrolled);
            $students_unenrolled = collect($students_unenrolled)->merge($college_unenrolled);

            foreach($students_unenrolled as $item){

                  $item->en_enrollment_status = 'Not Enrolled';
                  $item->en_course = '';
                  $item->en_gradelevel = '';
                  $item->en_studstatus = 0;
                  
                  $check_if_enrolled = collect($students)->where('studid',$item->studid)->values();

                  if(count($check_if_enrolled) > 0){
                        $check_if_enrolled = $check_if_enrolled[0];
                        if($check_if_enrolled->levelid >= 17 && $check_if_enrolled->levelid <= 21){
                              $item->en_course = $check_if_enrolled->courseid;
                        }
                        $item->en_enrollment_status = 'Enrolled';
                        $item->en_gradelevel = $check_if_enrolled->levelid;
                        $item->en_studstatus  = $check_if_enrolled->studstatus;
                  }


            }


            $schoolinfo = DB::table('schoolinfo')->first();

            $gradelevel = Db::table('gradelevel')
                              ->orderBy('sortid')
                              ->whereIn('id',$gradelevel)
                              ->select(
                                    'id',
                                    'levelname',
                                    'acadprogid'
                              )
                              ->get();

            $courses = Db::table('college_courses')
                              ->select(
                                    'id',
                                    'courseabrv'
                              )
                              ->get();
     
            $academicprogram = Db::table('academicprogram')
                              ->whereIn('id',collect($gradelevel)->pluck('acadprogid'))
                              ->select(
                                    'id',
                                    'progname'
                              )
                              ->get();
     
            $syinfo = DB::table('sy')
                              ->where('id',$syid)
                              ->select('sydesc')
                              ->first();

            $seminfo = DB::table('semester')
                              ->where('id',$semid)
                              ->select('semester')
                              ->first();

            $pdf = PDF::loadView('superadmin.pages.printable.enrollmentbyunenrolled',compact('syinfo','schoolinfo','students_unenrolled','academicprogram','gradelevel','courses','seminfo'))->setPaper('legal');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();


      }

}
