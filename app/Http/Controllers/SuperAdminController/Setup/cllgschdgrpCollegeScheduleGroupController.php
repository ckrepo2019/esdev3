<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class cllgschdgrpCollegeScheduleGroupController extends \App\Http\Controllers\Controller
{
    public static function cllgschdgrpList(Request $request){

        $schedgroup = DB::table('college_schedgroup')
                          ->where('deleted',0)
                          ->select(
                                'id',
                                'schedgroupdesc',
                                'schedgroupdesc as text'
                          )
                          ->get();

        return $schedgroup;

    }

    public static function cllgschdgrpSelect(Request $request){

        $search = $request->get('search');
    
        $schedgroup = DB::table('college_schedgroup')
                    ->where('college_schedgroup.deleted',0)
                    ->where(function($query) use($search){
                          if($search != null && $search != ""){
                                $query->orWhere('schedgroupdesc','like','%'.$search.'%');
                                $query->orWhere('courseabrv','like','%'.$search.'%');
                          }
                    })
                    ->leftJoin('college_courses',function($join) use($search){
                        $join->on('college_schedgroup.courseid','=','college_courses.id');
                        $join->where('college_courses.deleted',0);
                    //     if($search != null && $search != ""){
                    //         $join->orWhere('courseabrv','like','%'.$search.'%');
                    //   }
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
                            'schedgroupdesc as text'
                    )
                    ->take(10)
                    ->skip($request->get('page')*10)
                    ->get();

        foreach($schedgroup as $item){

            $text = '';

            if($item->courseid != null){
                    $text = $item->courseabrv;
            }else{
                    $text = $item->collegeabrv;
            }

            $text .= ' - '.$item->levelname[0] . ' '.$item->schedgroupdesc;
            $item->text = $text;

        }

        $schedgroup_count = DB::table('college_schedgroup')
                    ->where('deleted',0)
                    ->where(function($query) use($search){
                          if($search != null && $search != ""){
                                $query->orWhere('schedgroupdesc','like','%'.$search.'%');
                          }
                    })
                    ->count();

        return @json_encode((object)[
              "results"=>$schedgroup,
              "pagination"=>(object)[
                    "more"=> ( $request->get('page')*10 )  < $schedgroup_count  ? true :false
              ],
              "count_filtered"=>$schedgroup_count
        ]);
        
    }


    public static function cllgschdgrpDatatable(Request $request){

        $search = $request->get('search');
        $search = $search['value'];

        $schedgroup = DB::table('college_schedgroup')
                            ->where(function($query) use($search){
                                $query->orWhere('schedgroupdesc','like','%'.$search.'%');
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
                            ->take($request->get('length'))
                            ->skip($request->get('start'))
                            ->where('college_schedgroup.deleted',0)
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
                                    'college_schedgroup.schedgroupdesc'
                            )
                          ->get();

        $schedgroup_count = DB::table('college_schedgroup')
                            ->where(function($query) use($search){
                              $query->orWhere('schedgroupdesc','like','%'.$search.'%');
                            })
                           ->where('deleted',0)
                           ->count();

        foreach($schedgroup as $item){

            $text = '';

            if($item->courseid != null){
                    $text = $item->courseabrv;
            }else{
                    $text = $item->collegeabrv;
            }

            $text .= '-'.$item->levelname[0] . ' '.$item->schedgroupdesc;
            $item->text = $text;

        }


        return @json_encode((object)[
            'data'=>$schedgroup,
            'recordsTotal'=>$schedgroup_count,
            'recordsFiltered'=>$schedgroup_count
        ]);

    }


    public static function cllgschdgrpCreate(Request $request){

        try{

            $schedgroupdesc = $request->get('schedgroupdesc');
            $sglevelid = $request->get('sglevelid');
            $sgcollege = $request->get('sgcollege');
            $sgcourse = $request->get('sgcourse');

            $check = DB::table('college_schedgroup')
                        ->where('schedgroupdesc',$schedgroupdesc)
                        ->where('levelid',$sglevelid)
                        ->where('collegeid',$sgcollege)
                        ->where('courseid',$sgcourse)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Section Already Exist!',
                ]);
            }

            $dataid = DB::table('college_schedgroup')
                        ->insertGetId([
                            'schedgroupdesc'=>$schedgroupdesc,
                            'levelid'=>$sglevelid,
                            'collegeid'=>$sgcollege,
                            'courseid'=>$sgcourse,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                            'createdby'=>auth()->user()->id,
                            'deleted'=>0
                        ]);


            $message = auth()->user()->name.' created '.$schedgroupdesc.' schedule group';
            $tempnew_info = DB::table('college_schedgroup')
                            ->where('id',$dataid)
                            ->get();

            DB::table('logs') 
                ->insert([
                    'dataid'=>$dataid,
                    'module'=>28,
                    'message'=>$message,
                    'currentdata'=>$tempnew_info,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Schedule Group Created!'
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function cllgschdgrpUpdate(Request $request){
        try{

            $schedgroupdesc = $request->get('schedgroupdesc');
            $id = $request->get('id');
            $sglevelid = $request->get('sglevelid');
            $sgcollege = $request->get('sgcollege');
            $sgcourse = $request->get('sgcourse');

            $tempcurrent_info = DB::table('college_schedgroup')
                            ->where('id',$id)
                            ->get();

            $check = DB::table('college_schedgroup')
                        ->where('schedgroupdesc',$schedgroupdesc)
                        ->where('levelid',$sglevelid)
                        ->where('collegeid',$sgcollege)
                        ->where('courseid',$sgcourse)
                        ->where('id','!=',$id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!'
                ]);
            }

            DB::table('college_schedgroup')
                    ->take(1)
                    ->where('id',$id)
                    ->update([
                        'schedgroupdesc'=>$schedgroupdesc,
                        'levelid'=>$sglevelid,
                        'collegeid'=>$sgcollege,
                        'courseid'=>$sgcourse,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            $message = auth()->user()->name.' updated '.$schedgroupdesc.' schedule group';
            $tempnew_info = DB::table('college_schedgroup')
                                ->where('id',$id)
                                ->get();

            DB::table('logs') 
                ->insert([
                    'dataid'=>$id,
                    'module'=>28,
                    'message'=>$message,
                    'currentdata'=>$tempcurrent_info,
                    'updateddata'=>$tempnew_info,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Schedule Group Updated!'
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function cllgschdgrpDelete(Request $request){
        try{

            $id = $request->get('id');

            $check = DB::table('college_schedgroup_detail')
                        ->where('groupid',$id)
                        ->where('deleted',0)
                        ->count();

            if($check > 0){
                return array((object)[
                    'status'=>1,
                    'icon'=>'warning',
                    'message'=>'Already Used!'
                ]);
            }

            DB::table('college_schedgroup')
                    ->take(1)
                    ->where('id',$id)
                    ->update([
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'deletedby'=>auth()->user()->id,
                        'deleted'=>1
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Schedule Group Deleted!'
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
              'icon'=>'error',
              'message'=>'Something went wrong!'
        ]);
    }
      
}
