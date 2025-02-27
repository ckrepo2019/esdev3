<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;

class StudentEnrollmentReport extends \App\Http\Controllers\Controller
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

      public static function mol_report(Request $request){
            
            $syid = $request->get('syid');
            $datatype =  $request->get('datatype');
            $mol = array();
            $gradelevel = array();
            $sections = array();

            $acad = self::get_acad($syid);
            $all_enrolled = collect(array());

            $enrolled = DB::table('enrolledstud')
                              ->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.syid',$syid)
                              ->join('gradelevel',function($join) use($acad){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$acad);
                              })
                              ->join('studinfo',function($join) use($acad){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->leftJoin('modeoflearning',function($join) use($acad){
                                    $join->on('enrolledstud.studmol','=','modeoflearning.id');
                                    $join->where('modeoflearning.deleted',0);
                              })
                              ->join('sections',function($join) use($acad){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->select(
                                    'sections.sectionname',
                                    'enrolledstud.studmol',
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as student"),
                                    'sid',
                                    'enrolledstud.sectionid',
                                    'enrolledstud.levelid',
                                    'gradelevel.levelname',
                                    'description',
                                    'sections.sectionname'
                              )
                              ->distinct('studid')
                              ->get();

            $all_enrolled = $all_enrolled->merge(collect($enrolled));

            $enrolled = DB::table('sh_enrolledstud')
                              ->where('sh_enrolledstud.deleted',0)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->join('gradelevel',function($join) use($acad){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('gradelevel.acadprogid',$acad);
                              })
                              ->join('studinfo',function($join) use($acad){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('sections',function($join) use($acad){
                                    $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->leftJoin('modeoflearning',function($join) use($acad){
                                    $join->on('sh_enrolledstud.studmol','=','modeoflearning.id');
                                    $join->where('modeoflearning.deleted',0);
                              })
                              ->select(
                                    'sections.sectionname',
                                    'sh_enrolledstud.studmol',
                                    'description',
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as student"),
                                    'sid',
                                    'sh_enrolledstud.sectionid',
                                    'sh_enrolledstud.levelid',
                                    'gradelevel.levelname'
                              )
                              ->distinct('studid')
                              ->get();

            $all_enrolled = $all_enrolled->merge(collect($enrolled));

            $mol = DB::table('modeoflearning')
                        ->where('deleted',0)
                        ->where('syid',$syid)
                        ->select(
                              'id',
                              'description'
                        )
                        ->orderBy('description')
                        ->get();

            $syinfo = DB::table('sy')
                              ->where('id',$syid)
                              ->select('sydesc')
                              ->first();

            if($datatype == 2){
                  $gradelevel = DB::table('gradelevel')
                                    ->where('deleted', 0)
                                    ->whereIn('acadprogid',$acad)
                                    ->select(
                                          'id',
                                          'levelname'
                                    )
                                    ->orderBy('sortid', 'ASC')
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

         
       

            $schoolinfo = DB::table('schoolinfo')->first();

            $pdf = PDF::loadView('superadmin.pages.reports.molsummary',compact('datatype','all_enrolled','mol','syinfo','gradelevel','sections','schoolinfo'))->setPaper('legal');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();



      }
      

}
