<?php

namespace App\Http\Controllers\SuperAdminController\Teacher;

use Illuminate\Http\Request;
use DB;

class FASAcadProgController extends \App\Http\Controllers\Controller
{

      public static function teachers(Request $request){

            $usertype = $request->get('usertype');

            $teacher = DB::table('teacher')
                              ->where('usertypeid',$usertype)
                              ->where('isactive',1)
                              ->select(
                                    DB::raw("CONCAT(teacher.tid, ' - ',teacher.lastname,' ',teacher.firstname) as text"),
                                    'id'
                              )
                              ->get();

            
            $faspriv = DB::table('faspriv')
                              ->where('usertype',$usertype)
                              ->where('faspriv.deleted',0)
                              ->where('isactive',1)
                              ->join('teacher',function($join){
                                    $join->on('faspriv.userid','=','teacher.userid');
                                    $join->where('teacher.deleted',0);
                              })
                              ->select(
                                    DB::raw("CONCAT(teacher.tid, ' - ',teacher.lastname,' ',teacher.firstname) as text"),
                                    'teacher.id'
                              )
                              ->get();

        


            $teacheracadprog = DB::table('teacheracadprog')
                                    ->where('syid',$request->get('syid'))
                                    ->where('teacheracadprog.deleted',0)
                                    ->where('teacheracadprog.acadprogutype',$usertype)
                                    ->distinct('teacher.id')
                                    ->join('teacher',function($join){
                                          $join->on('teacheracadprog.teacherid','=','teacher.id');
                                          $join->where('teacher.deleted',0);
                                    })
                                    ->select(
                                          DB::raw("CONCAT(teacher.tid, ' - ',teacher.lastname,' ',teacher.firstname) as text"),
                                          'teacher.id'
                                    )
                                    ->get();


            $all_teacher = collect($teacher);
            $all_teacher = $all_teacher->merge(collect($faspriv));

            return collect($all_teacher)->whereNotIn('id',collect($teacheracadprog)->pluck('id'))->unique('id')->values();

      }

      public static function fasaacadprog_copy(Request $request){

            try{

                  $sy_to = $request->get('syid_to');
                  $sy_from = $request->get('syid_from');
                  $utype = $request->get('utype');
      
                  $acad_from = DB::table('teacheracadprog')
                              ->where('teacheracadprog.syid',$sy_from)
                              ->where('teacheracadprog.deleted',0)
                              ->where('acadprogutype',$utype)
                              ->get();

                  $acad_to = DB::table('teacheracadprog')
                              ->where('teacheracadprog.syid',$sy_to)
                              ->where('teacheracadprog.deleted',0)
                              ->where('acadprogutype',$utype)
                              ->get();
                              

                  foreach($acad_from as $item){
                        $check  = collect($acad_to)
                                    ->where('acadprogid',$item->acadprogid)
                                    ->where('teacherid',$item->teacherid)
                                    ->where('acadprogutype',$item->acadprogutype)
                                    ->where('syid',$sy_to)
                                    ->where('deleted',0)
                                    ->count();
                        if($check == 0){
                              DB::table('teacheracadprog')
                                    ->insert([
                                          'acadprogid'=>$item->acadprogid,
                                          'teacherid'=>$item->teacherid,
                                          'deleted'=>0,
                                          'syid'=>$sy_to,
                                          'acadprogutype'=>$item->acadprogutype,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Academic Program Copied!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
           

      }



      public static function fasaacadprog(Request $request){

            $syid = $request->get('syid');
            $usertype = $request->get('usertype');
           
            $search = $request->get('search');
            $search = $search['value'];

            $check = DB::table('usertype')
                        ->where('with_acad',1)
                        ->count();

            if($check == 0){
                  return @json_encode((object)[
                        'data'=>array(),
                        'acadprog'=>array(),
                        'recordsTotal'=>array(),
                        'recordsFiltered'=>array()
                  ]);
            }

            $teachers = DB::table('teacheracadprog')
                        ->where('teacheracadprog.syid',$syid)
                        ->where('teacheracadprog.deleted',0)
                        ->take($request->get('length'))
                        ->skip($request->get('start'))
                        ->join('teacher',function($join){
                              $join->on('teacheracadprog.teacherid','=','teacher.id');
                              $join->where('teacher.deleted',0);
                        });

            if($usertype != null && $usertype != ''){
                  $teachers = $teachers->where('acadprogutype',$usertype);
            }

           
            if($search != null){
                  $teachers = $teachers->where(function($query) use($search){
                                    $query->orWhere('firstname','like','%'.$search.'%');
                                    $query->orWhere('lastname','like','%'.$search.'%');
                                    $query->orWhere('tid','like','%'.$search.'%');
                              });
            }


            $teachers = $teachers->select(
                              DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as teachername"),
                              'lastname',
                              'firstname',
                              'tid',
                              'teacherid'
                        )
                        ->orderBy('tid')
                        ->distinct('teacherid')
                        ->get();


            $teacher_count = DB::table('teacheracadprog')
                        ->where('teacheracadprog.syid',$syid)
                        ->where('teacheracadprog.deleted',0)
                        ->join('teacher',function($join){
                              $join->on('teacheracadprog.teacherid','=','teacher.id');
                              $join->where('teacher.deleted',0);
                        });

            if($usertype != null && $usertype != ''){
                  $teacher_count = $teacher_count->where('acadprogutype',$usertype);
            }

            $teacher_count = $teacher_count->select(
                              'teacherid'
                        )
                        ->distinct('teacherid')
                        ->count();

            $acadprog = DB::table('teacheracadprog')
                              ->leftJoin('usertype',function($join){
                                    $join->on('teacheracadprog.acadprogutype','=','usertype.id');
                                    $join->where('usertype.deleted',0);
                              })
                              ->join('academicprogram',function($join){
                                    $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                              })
                              ->where('acadprogutype',$usertype)
                              ->whereIn('teacherid',collect($teachers)->pluck('teacherid'))
                              ->where('teacheracadprog.syid',$syid)
                              ->where('teacheracadprog.deleted',0)
                              ->select(
                                    'teacherid',
                                    'acadprogcode',
                                    'acadprogid',
                                    'utype'
                              )
                              ->get();

            return @json_encode((object)[
                  'data'=>$teachers,
                  'acadprog'=>$acadprog,
                  'recordsTotal'=>$teacher_count,
                  'recordsFiltered'=>$teacher_count
            ]);

      }

      public static function add_fas(Request $request){

            try{
                  $teacherid = $request->get('teacherid');
                  $acadprog = $request->get('acadprog');
                  $syid = $request->get('syid');
                  $usertype = $request->get('usertype');

                  if($acadprog == null || $acadprog == ""){
                        $acadprog = array();
                  }
                  
                  $sectiondetail = [];
                  
                  if($usertype == 1){
                        $sectiondetail = DB::table('sectiondetail')
                                                ->join('sections',function($join){
                                                      $join->on('sectiondetail.sectionid','=','sections.id');
                                                      $join->where('sections.deleted',0);
                                                })
                                                ->join('gradelevel',function($join){
                                                      $join->on('sections.levelid','=','gradelevel.id');
                                                      $join->where('gradelevel.deleted',0);
                                                })
                                                ->where('sectiondetail.teacherid',$teacherid)
                                                ->where('sectiondetail.syid',$syid)
                                                ->where('sectiondetail.deleted',0)
                                                ->select('acadprogid')
                                                ->get();
                  }

                  

                  //remove no acad

                  //update synced data
                  DB::table('teacheracadprog')
                        ->whereNotIn('acadprogid',$acadprog)
                        ->whereNotIn('acadprogid',collect($sectiondetail)->pluck('acadprogid'))
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->where('acadprogutype',$usertype)
                        ->where('teacherid',$teacherid)
                        ->where('syncstat','!=',0)
                        ->update([
                              'syncstat'=>2,
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  //update not synced data
                  DB::table('teacheracadprog')
                        ->whereNotIn('acadprogid',$acadprog)
                        ->whereNotIn('acadprogid',collect($sectiondetail)->pluck('acadprogid'))
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->where('acadprogutype',$usertype)
                        ->where('teacherid',$teacherid)
                        ->where('syncstat',0)
                        ->update([
                              'syncstat'=>0,
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $get_all_acad = DB::table('teacheracadprog')
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->where('acadprogutype',$usertype)
                                    ->where('teacherid',$teacherid)
                                    ->get();

                  foreach($acadprog as $item){
                        $check  = collect($get_all_acad)->where('acadprogid',$item)->count();
                        if($check == 0){
                              DB::table('teacheracadprog')
                                    ->insert([
                                          'acadprogid'=>$item,
                                          'teacherid'=>$teacherid,
                                          'deleted'=>0,
                                          'syid'=>$syid,
                                          'acadprogutype'=>$usertype,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($usertype == 2){

                              DB::table('academicprogram')
                                    ->where('id',$item)
                                    ->update([
                                          'principalid'=>$teacherid,
                                          'updatedby'=>$usertype,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        }
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Academic Program Updated!'
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
                  'data'=>'Something went wrong!'
            ]);
      }

    


      
     
      

}
