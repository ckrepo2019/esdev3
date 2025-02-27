<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class glvlGradelevelController extends \App\Http\Controllers\Controller
{
    public static function glvlList(Request $request){

        $glvlList = DB::table('gradelevel')
                        ->get();

        return $glvlList;

    }

    public static function glvlListDatatable(Request $request){

        $search = $request->get('search');
        $search = $search['value'];

        $glvlList = DB::table('gradelevel')
                        ->leftJoin('academicprogram',function($join){
                            $join->on('gradelevel.acadprogid','=','academicprogram.id');
                        })
                        ->where(function($query) use($search){
                            $query->orWhere('levelname','like','%'.$search.'%');
                            $query->orWhere('acadprogcode','like','%'.$search.'%');
                            $query->orWhere('progname','like','%'.$search.'%');
                        })
                        ->where('gradelevel.id','!=',8)
                        ->take($request->get('length'))
                        ->skip($request->get('start'))
                        ->orderBy('sortid')
                        ->select(
                            'progname',
                            'acadprogcode',
                            'academicprogram.nodp as acadnodp',
                            'gradelevel.*'
                        )
                        ->get();

        $glvlListCount = DB::table('gradelevel')
                        ->leftJoin('academicprogram',function($join){
                            $join->on('gradelevel.acadprogid','=','academicprogram.id');
                        })
                        ->where(function($query) use($search){
                            $query->orWhere('levelname','like','%'.$search.'%');
                            $query->orWhere('acadprogcode','like','%'.$search.'%');
                            $query->orWhere('progname','like','%'.$search.'%');
                        })
                        ->where('gradelevel.id','!=',8)
                        ->orderBy('sortid')
                        ->count();

        return @json_encode((object)[
            'data'=>$glvlList,
            'recordsTotal'=>$glvlListCount,
            'recordsFiltered'=>$glvlListCount
        ]);

    }

    public static function glvlUpdate(Request $request){

        try{

            $glvlID = $request->get('glvlID');
            $glvlActive = $request->get('glvlActive');
            $glvlNoDP = $request->get('glvlNoDP');
            $glvlESC = $request->get('glvlESC');
            $glvlVoucher = $request->get('glvlVoucher');
            $glvlLevelName = $request->get('glvlLevelName');

            if($glvlActive == 1){
                $studenList = DB::table('studinfo')
                                ->where('levelid',$glvlID)
                                ->where('deleted',0)
                                ->count();

                $gshsEnrolledList = DB::table('enrolledstud')
                                ->where('levelid',$glvlID)
                                ->where('deleted',0)
                                ->count();

                $shsEnrolledList = DB::table('sh_enrolledstud')
                                ->where('levelid',$glvlID)
                                ->where('deleted',0)
                                ->count();

                $collegeEnrolledList = DB::table('college_enrolledstud')
                                ->where('yearLevel',$glvlID)
                                ->where('deleted',0)
                                ->count();

                if($studenList > 0 || $gshsEnrolledList > 0 || $shsEnrolledList > 0 || $collegeEnrolledList > 0){
                    return array((object)[
                        'status'=>0,
                        'icon'=>'warning',
                        'message'=>'Grade Level is already used!',
                    ]);
                }


            }

            if($glvlID == 2 || $glvlID == 3 || $glvlID == 4){
                DB::table('gradelevel')
                    ->take(1)
                    ->where('id',$glvlID)
                    ->update([
                        'levelname'=>$glvlLevelName,
                        'deleted'=>$glvlActive,
                        'nodp'=>$glvlNoDP,
                        'esc'=>$glvlESC,
                        'voucher'=>$glvlVoucher,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }else{
                DB::table('gradelevel')
                    ->take(1)
                    ->where('id',$glvlID)
                    ->update([
                        'deleted'=>$glvlActive,
                        'nodp'=>$glvlNoDP,
                        'esc'=>$glvlESC,
                        'voucher'=>$glvlVoucher,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

            $glvlInfo = DB::table('gradelevel')
                                ->where('id',$glvlID)
                                ->select('acadprogid')
                                ->first();

            if($glvlNoDP == 0){
                
                DB::table('academicprogram')
                    ->where('id',$glvlInfo->acadprogid)
                    ->take(1)
                    ->update([
                        'nodp'=>0,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }else{

                $glvlNoDP = DB::table('gradelevel')
                                ->where('deleted',0)
                                ->where('acadprogid',$glvlInfo->acadprogid)
                                ->where('nodp',0)
                                ->count();

                if($glvlNoDP == 0){
                    DB::table('academicprogram')
                        ->where('id',$glvlInfo->acadprogid)
                        ->take(1)
                        ->update([
                            'nodp'=>1,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                }

            }


            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Grade Level updated!',
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
              'message'=>'Something went wrong!',
        ]);
  }
}
