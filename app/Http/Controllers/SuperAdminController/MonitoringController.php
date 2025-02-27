<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;

class MonitoringController extends \App\Http\Controllers\Controller
{

      public static function gradeLogsMonitoring(Request $request){

            $search = $request->get('search');
            $syid = $request->get('syid');
            $action = $request->get('action');
            $quarter = $request->get('quarter');
            $date = $request->get('date');
           
            
            $search = $search['value'];

            $gradeid = DB::table('grades')
                              ->where('syid',$syid);

            if($quarter != "" && $quarter != null){
                  $gradeid = $gradeid->where('quarter',$quarter);
            }

            $gradeid = $gradeid->select('id')
                              ->get();

            $subjects = DB::table('subjects')
                              ->where('deleted',0)
                              ->select(
                                    'id',
                                    'subjcode'
                                    )
                              ->get();

            $sh_subjects = DB::table('sh_subjects')
                              ->where('deleted',0)
                              ->select(
                                    'id',
                                    'subjcode'
                                    )
                              ->get();

            $gradelogs = DB::table('gradelogs')
                              ->join('users',function($join){
                                    $join->on('gradelogs.createdby','=','users.id');
                              })
                              ->join('grades',function($join){
                                    $join->on('gradelogs.gradeid','=','grades.id');
                              });

            if($action != '' && $action != null){
                  if($action == 'System Grading'){
                        $action = NULL;
                  }
                  $gradelogs =  $gradelogs->where('actiontext',$action);
            }

            if($date != '' && $date != null){
                  $date = explode(' - ',$date);
                  $startdate = \Carbon\Carbon::create($date[0].' 00:00')->isoFormat('YYYY-MM-DD');
                  $enddate = \Carbon\Carbon::create($date[1].' 24:00')->isoFormat('YYYY-MM-DD');
                  $gradelogs =  $gradelogs->whereBetween('gradelogs.createddatetime',[$startdate,$enddate]);
            }
                              
            $gradelogs =  $gradelogs->where(function($query) use($search){
                                    if($search != null){
                                        $query->where('actiontext','like','%'.$search.'%');
                                    }
                              })
                              ->whereIn('gradeid',collect($gradeid)->pluck('id'))
                              ->take($request->get('length'))
                              ->skip($request->get('start'))
                              ->select(
                                    'gradelogs.createddatetime',
                                    'actiontext',
                                    'email',
                                    'name',
                                    'quarter',
                                    'levelid',
                                    'subjid'
                              )
                              ->orderBy('createddatetime','desc')
                              ->get();

            $gradelogs_count = DB::table('gradelogs')
                              ->join('users',function($join){
                                    $join->on('gradelogs.createdby','=','users.id');
                              })
                              ->where(function($query) use($search){
                                    if($search != null){
                                        $query->where('actiontext','like','%'.$search.'%');
                                    }
                              });

            if($action != '' && $action != null){
                  if($action == 'System Grading'){
                        $action = NULL;
                  }
                  $gradelogs_count =  $gradelogs_count->where('actiontext',$action);
            }

            
            if($date != '' && $date != null){
                  $gradelogs_count =  $gradelogs_count->whereBetween('gradelogs.createddatetime',[$startdate,$enddate]);
            }

            $gradelogs_count = $gradelogs_count->whereIn('gradeid',collect($gradeid)->pluck('id'))
                              ->count();

            foreach($gradelogs as $item){
                  $item->date = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:MM A');

                  if($item->levelid == 14 || $item->levelid == 15){
                        $item->subjcode = collect($sh_subjects)
                                                ->where('id',$item->subjid)
                                                ->first()->subjcode;

                  }else{
                        $item->subjcode = collect($subjects)
                                          ->where('id',$item->subjid)
                                          ->first()->subjcode;
                  }

              
                  if($item->actiontext == ""){
                        $item->actiontext = 'System Grading';
                  }

            }

            return @json_encode((object)[
                  'data'=>$gradelogs,
                  'recordsTotal'=>$gradelogs_count,
                  'recordsFiltered'=>$gradelogs_count
            ]);

     }

     public static function gradeHPSMonitoring(Request $request){

            $search = $request->get('search');
            $search = $search['value'];
            $syid = $request->get('syid');
            $quarter = $request->get('quarter');

            $gradeid = DB::table('grades')
                        ->where('syid',$syid);

            if($quarter != "" && $quarter != null){
                  $gradeid = $gradeid->where('quarter',$quarter);
            }

            $gradeid = $gradeid->select('id')
                        ->get();

            $gradeHPS = DB::table('grades')
                              ->join('users',function($join){
                                    $join->on('grades.createdby','=','users.id');
                              })
                              ->where('grades.deleted',0)
                              ->where('syid',$syid)
                              ->take($request->get('length'))
                              ->skip($request->get('start'))
                              ->whereIn('grades.id',collect($gradeid)->pluck('id'))
                              ->select(
                                    'grades.createddatetime',
                                    'grades.updateddatetime',
                                    'email',
                                    'name'
                              )
                              ->orderBy('updateddatetime','desc')
                              ->get();

            $gradeHPS_count = DB::table('grades')
                              ->where('grades.deleted',0)
                              ->whereIn('id',collect($gradeid)->pluck('id'))
                              ->count();

            foreach($gradeHPS as $item){
                  $item->createddate = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:MM A');
                  $item->date = \Carbon\Carbon::create($item->updateddatetime)->isoFormat('MMM DD, YYYY hh:MM A');
            }

            return @json_encode((object)[
                  'data'=>$gradeHPS,
                  'recordsTotal'=>$gradeHPS_count,
                  'recordsFiltered'=>$gradeHPS_count
            ]);

      }


      public static function gradeDetailMonitoring(Request $request){

            $search = $request->get('search');
            $search = $search['value'];
            $syid = $request->get('syid');
            $quarter = $request->get('quarter');

            $gradeid = DB::table('grades')
                        ->where('syid',$syid);

            if($quarter != "" && $quarter != null){
                  $gradeid = $gradeid->where('quarter',$quarter);
            }

            $gradeid = $gradeid->select('id')
                        ->get();

            $gradeDetail = DB::table('gradesdetail')
                              ->leftJoin('users',function($join){
                                    $join->on('gradesdetail.createdby','=','users.id');
                              })
                              ->whereIn('headerid',collect($gradeid)->pluck('id'))
                              ->take($request->get('length'))
                              ->skip($request->get('start'))
                              ->select(
                                    'gradesdetail.createddatetime',
                                    'gradesdetail.updateddatetime',
                                    'email',
                                    'name'
                              )
                              ->orderBy('updateddatetime','desc')
                              ->get();

            $gradeDetail_count = DB::table('gradesdetail')
                              ->whereIn('headerid',collect($gradeid)->pluck('id'))
                              ->count();

            foreach($gradeDetail as $item){
                  $item->createddate = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:MM A');
                  $item->date = \Carbon\Carbon::create($item->updateddatetime)->isoFormat('MMM DD, YYYY hh:MM A');
            }

            return @json_encode((object)[
                  'data'=>$gradeDetail,
                  'recordsTotal'=>$gradeDetail_count,
                  'recordsFiltered'=>$gradeDetail_count
            ]);


      }

}
