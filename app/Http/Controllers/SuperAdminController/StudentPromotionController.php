<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class StudentPromotionController extends \App\Http\Controllers\Controller
{

      public static function correctstudent_enrollmentinfo(Request $request)
      {

            $syid = 12;

            // $enrolled = DB::table('enrolledstud')
            //               ->where('enrolledstud.syid',$syid)
            //               ->where('enrolledstud.deleted',0)
            //               ->join('sections',function($join){
            //                   $join->on('enrolledstud.sectionid','=','sections.id');
            //                   $join->where('sections.deleted',0);
            //             })
            //             ->select(
            //                   'enrolledstud.*',
            //                   'sectionname'
            //             )
            //               ->get();


            // foreach($enrolled as $item){

            //       DB::table('studinfo')
            //             ->take(1)
            //             ->where('id',$item->studid)
            //             ->where('deleted',0)
            //             ->update([
            //                   'studstatus'=>$item->studstatus,
            //                   'sectionid'=>$item->sectionid,
            //                   'levelid'=>$item->levelid,
            //                   'grantee'=>$item->grantee,
            //                   'sectionname'=>$item->sectionname
            //             ]);

            // }

            // $enrolled = DB::table('sh_enrolledstud')
            //               ->where('sh_enrolledstud.syid',$syid)
            //               ->where('sh_enrolledstud.semid',2)
            //               ->where('sh_enrolledstud.deleted',0)
            //               ->join('sections',function($join){
            //                   $join->on('sh_enrolledstud.sectionid','=','sections.id');
            //                   $join->where('sections.deleted',0);
            //             })
            //             ->select(
            //                   'sh_enrolledstud.*',
            //                   'sectionname'
            //             )
            //               ->get();

            $enrolled = DB::table('coollege_enrolledsdtud')
                  ->where('coollege_enrolledsdtud.syid', 12)
                  ->where('coollege_enrolledsdtud.semid', 2)
                  ->where('coollege_enrolledsdtud.deleted', 0)
                  ->select(
                        'sh_enrolledstud.*'
                  )
                  ->get();


            foreach ($enrolled as $item) {

                  DB::table('studinfo')
                        ->take(1)
                        ->where('id', $item->studid)
                        ->where('deleted', 0)
                        ->update([
                              'courseid' => $item->courseid
                        ]);

            }

            return "done";


      }




      public static function list(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $gradelevel = $request->get('levelid');
            $studid = $request->get('studid');
            $condition = $request->get('condition');
            $students = self::enrolled_students($syid, $semid, $gradelevel, $studid);

            if ($condition == 1) {
                  self::check_grades($syid, $semid, $students, $gradelevel);
            } else {
                  foreach ($students as $item) {
                        $item->genave = null;
                        $item->actiontaken = null;
                        $item->fail_subj = null;
                  }
            }

            foreach ($students as $item) {
                  $item->promotedtonextgradelevel = false;
                  if ($item->levelname != $item->curlevelname) {
                        $item->promotedtonextgradelevel = true;
                  }
            }

            return $students;

      }

      public static function enrolled_students($syid = null, $semid = null, $gradelevel = null, $studid = null)
      {

            $acadprog = DB::table('gradelevel')->where('id', $gradelevel)->first()->acadprogid;
            $enrolled = [];

            if ($acadprog == 4 || $acadprog == 3 || $acadprog == 2) {

                  $enrolled = DB::table('enrolledstud')
                        ->where('enrolledstud.deleted', 0)
                        ->whereNotIn('enrolledstud.studstatus', [0, 3, 5, 6, 7])
                        ->join('studinfo', function ($join) {
                              $join->on('enrolledstud.studid', '=', 'studinfo.id');
                              $join->where('studinfo.deleted', 0);
                        })
                        ->join('gradelevel', function ($join) {
                              $join->on('enrolledstud.levelid', '=', 'gradelevel.id');
                              $join->where('gradelevel.deleted', 0);
                        })
                        ->join('gradelevel as studlevel', function ($join) {
                              $join->on('studinfo.levelid', '=', 'studlevel.id');
                              $join->where('gradelevel.deleted', 0);
                        })
                        ->join('studentstatus', function ($join) {
                              $join->on('enrolledstud.studstatus', '=', 'studentstatus.id');
                        })
                        ->join('studentstatus as curdesription', function ($join) {
                              $join->on('studinfo.studstatus', '=', 'curdesription.id');
                        });

                  if ($syid != null) {
                        $enrolled = $enrolled->where('enrolledstud.syid', $syid);
                  }
                  if ($gradelevel != null) {
                        $enrolled = $enrolled->where('enrolledstud.levelid', $gradelevel);
                  }
                  if ($studid != null) {
                        $enrolled = $enrolled->where('enrolledstud.studid', $studid);
                  }

                  $enrolled = $enrolled->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'gradelevel.levelname',
                        'studlevel.levelname as curlevelname',
                        'curdesription.description as curdesription',
                        'studentstatus.description',
                        'sid',
                        'gender',
                        'lrn',
                        'enrolledstud.id',
                        'studid',
                        'promotionstatus',
                        'enrolledstud.levelid',
                        'enrolledstud.sectionid',
                        'enrolledstud.studstatus'
                  )
                        ->get();

                  //type 2
                  //   $enrolled = DB::table('studinfo')
                  //                   ->where('studinfo.deleted',0)
                  //                   ->leftJoin('enrolledstud',function($join){
                  //                         $join->on('studinfo.id','=','enrolledstud.studid');
                  //                         $join->where('enrolledstud.deleted',0);
                  //                   })
                  //                   ->join('gradelevel',function($join){
                  //                         $join->on('studinfo.levelid','=','gradelevel.id');
                  //                         $join->where('gradelevel.deleted',0);
                  //                   })
                  //                   ->join('gradelevel as studlevel',function($join){
                  //                         $join->on('studinfo.levelid','=','studlevel.id');
                  //                         $join->where('gradelevel.deleted',0);
                  //                   })
                  //                   ->leftJoin('studentstatus',function($join){
                  //                         $join->on('enrolledstud.studstatus','=','studentstatus.id');
                  //                   })
                  //                   ->leftJoin('studentstatus as curdesription',function($join){
                  //                         $join->on('studinfo.studstatus','=','curdesription.id');
                  //                   });
                  //             if($gradelevel != null){
                  //                   $enrolled = $enrolled->where('studinfo.levelid',$gradelevel);
                  //             }

                  // $enrolled = $enrolled->select(
                  //                   'lastname',
                  //                   'firstname',
                  //                   'middlename',
                  //                   'suffix',
                  //                   'gradelevel.levelname',
                  //                   'studlevel.levelname as curlevelname',
                  //                   'curdesription.description as curdesription',
                  //                   'studentstatus.description',
                  //                   'sid',
                  //                   'enrolledstud.id',
                  //                   'studinfo.id as studid',
                  //                   'promotionstatus',
                  //                   'enrolledstud.levelid',
                  //                   'enrolledstud.sectionid'
                  //              )
                  //             ->get();

            } else if ($acadprog == 5) {

                  $enrolled = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.deleted', 0)
                        ->whereNotIn('sh_enrolledstud.studstatus', [0, 3, 5, 6, 7])
                        ->join('studinfo', function ($join) {
                              $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                              $join->where('studinfo.deleted', 0);
                        })
                        ->join('gradelevel', function ($join) {
                              $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id');
                              $join->where('gradelevel.deleted', 0);
                        })
                        ->join('gradelevel as studlevel', function ($join) {
                              $join->on('studinfo.levelid', '=', 'studlevel.id');
                              $join->where('gradelevel.deleted', 0);
                        })
                        ->join('studentstatus', function ($join) {
                              $join->on('sh_enrolledstud.studstatus', '=', 'studentstatus.id');
                        })
                        ->join('studentstatus as curdesription', function ($join) {
                              $join->on('studinfo.studstatus', '=', 'curdesription.id');
                        });

                  if ($syid != null) {
                        $enrolled = $enrolled->where('sh_enrolledstud.syid', $syid);
                  }
                  if ($semid != null) {
                        $enrolled = $enrolled->where('sh_enrolledstud.semid', $semid);
                  }
                  if ($studid != null) {
                        $enrolled = $enrolled->where('sh_enrolledstud.studid', $studid);
                  }
                  if ($gradelevel != null) {
                        $enrolled = $enrolled->where('sh_enrolledstud.levelid', $gradelevel);
                  }

                  $enrolled = $enrolled->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'gradelevel.levelname',
                        'studlevel.levelname as curlevelname',
                        'curdesription.description as curdesription',
                        'studentstatus.description',
                        'sid',
                        'gender',
                        'lrn',
                        'studinfo.strandid',
                        'sh_enrolledstud.id',
                        'studid',
                        'promotionstatus',
                        'sh_enrolledstud.levelid',
                        'sh_enrolledstud.sectionid',
                        'sh_enrolledstud.strandid',
                        'sh_enrolledstud.studstatus'
                  )
                        ->get();
                  //type 2
                  // $enrolled = DB::table('studinfo')
                  //                   ->where('studinfo.deleted',0)
                  //                   ->leftJoin('sh_enrolledstud',function($join) use($syid){
                  //                         $join->on('studinfo.id','=','sh_enrolledstud.studid');
                  //                         // if($syid != null){
                  //                         //       $join->where('sh_enrolledstud.syid',$syid);
                  //                         // }
                  //                         $join->where('sh_enrolledstud.deleted',0);
                  //                   })
                  //                   ->join('gradelevel',function($join){
                  //                         $join->on('studinfo.levelid','=','gradelevel.id');
                  //                         $join->where('gradelevel.deleted',0);
                  //                   })
                  //                   ->join('gradelevel as studlevel',function($join){
                  //                         $join->on('studinfo.levelid','=','studlevel.id');
                  //                         $join->where('gradelevel.deleted',0);
                  //                   })
                  //                   ->leftJoin('studentstatus',function($join){
                  //                         $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                  //                   })
                  //                   ->leftJoin('studentstatus as curdesription',function($join){
                  //                         $join->on('studinfo.studstatus','=','curdesription.id');
                  //                   });

                  //                   // if($syid != null){
                  //                   //       $enrolled = $enrolled->where('sh_enrolledstud.syid',$syid);
                  //                   // }
                  //                   // if($semid != null){
                  //                   //       $enrolled = $enrolled->where('sh_enrolledstud.semid',$semid);
                  //                   // }
                  //                   if($studid != null){
                  //                         $enrolled = $enrolled->where('studinfo.studid',$studid);
                  //                   }
                  //                   if($gradelevel != null){
                  //                         $enrolled = $enrolled->where('studinfo.levelid',$gradelevel);
                  //                   }

                  // $enrolled = $enrolled->select(
                  //                         'lastname',
                  //                         'firstname',
                  //                         'middlename',
                  //                         'suffix',
                  //                         'gradelevel.levelname',
                  //                         'studlevel.levelname as curlevelname',
                  //                         'curdesription.description as curdesription',
                  //                         'studentstatus.description',
                  //                         'sid',
                  //                         'sh_enrolledstud.id',
                  //                         'studinfo.id as studid',
                  //                         'promotionstatus',
                  //                         'sh_enrolledstud.levelid',
                  //                         'sh_enrolledstud.sectionid',
                  //                         'sh_enrolledstud.strandid'
                  //                    )
                  //                   ->get();
            } else if ($acadprog == 6) {

                  $enrolled = DB::table('college_enrolledstud')
                        ->whereNotIn('college_enrolledstud.studstatus', [0, 3, 5, 6, 7])
                        ->where('college_enrolledstud.deleted', 0)
                        ->join('studinfo', function ($join) {
                              $join->on('college_enrolledstud.studid', '=', 'studinfo.id');
                              $join->where('studinfo.deleted', 0);
                        })
                        ->join('gradelevel', function ($join) {
                              $join->on('college_enrolledstud.yearLevel', '=', 'gradelevel.id');
                              $join->where('gradelevel.deleted', 0);
                        })
                        ->join('studentstatus', function ($join) {
                              $join->on('studinfo.studstatus', '=', 'studentstatus.id');
                        })
                        ->join('gradelevel as studlevel', function ($join) {
                              $join->on('studinfo.levelid', '=', 'studlevel.id');
                              $join->where('gradelevel.deleted', 0);
                        })
                        ->join('studentstatus as curdesription', function ($join) {
                              $join->on('studinfo.studstatus', '=', 'curdesription.id');
                        });

                  if ($syid != null) {
                        $enrolled = $enrolled->where('college_enrolledstud.syid', $syid);
                  }
                  if ($semid != null) {
                        $enrolled = $enrolled->where('college_enrolledstud.semid', $semid);
                  }
                  if ($studid != null) {
                        $enrolled = $enrolled->where('college_enrolledstud.studid', $studid);
                  }
                  if ($gradelevel != null) {
                        $enrolled = $enrolled->where('college_enrolledstud.yearLevel', $gradelevel);
                  }


                  $enrolled = $enrolled->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'gender',
                        'lrn',
                        'suffix',
                        'gradelevel.levelname',
                        'sid',
                        'college_enrolledstud.id',
                        'studid',
                        'promotionstatus',
                        'studentstatus.description',
                        'yearLevel as yearLevel',
                        'studlevel.levelname as curlevelname',
                        'curdesription.description as curdesription',
                        'college_enrolledstud.studstatus',
                        'college_enrolledstud.sectionid',
                        'college_enrolledstud.yearLevel as levelid'
                  )
                        ->get();

            }


            foreach ($enrolled as $item) {

                  $item->actiontaken = null;

                  $middlename = explode(" ", $item->middlename);
                  $temp_middle = '';
                  if ($middlename != null) {
                        foreach ($middlename as $middlename_item) {
                              if (strlen($middlename_item) > 0) {
                                    $temp_middle .= $middlename_item[0] . '.';
                              }
                        }
                  }
                  $item->student = $item->lastname . ', ' . $item->firstname . ' ' . $item->suffix . ' ' . $temp_middle;

                  if ($item->promotionstatus == 1) {
                        $item->promotiondesc = 'Promoted';
                  } else if ($item->promotionstatus == 3) {
                        $item->promotiondesc = 'Retained';
                  } else {
                        $item->promotiondesc = 'Not Promoted';
                  }

                  $item->checked = 0;

            }

            return $enrolled;

      }


      public static function check_grades($syid = null, $semid = null, $enrolled = null, $gradelevel = null)
      {

            $acadprog = DB::table('gradelevel')->where('id', $gradelevel)->first()->acadprogid;

            $grading_version = DB::table('zversion_control')->where('module', 1)->where('isactive', 1)->first();

            foreach ($enrolled as $item) {

                  $item->ess1 = 'COMPLETE';
                  $item->ess2 = 'COMPLETE';
                  $item->esys = 'REGULAR';

                  $item->promotedtonextgradelevel = false;

                  if ($acadprog == 5) {
                        if ($grading_version->version == 'v2' && $syid == 2) {
                              $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2($item->levelid, $item->id, $syid, $item->strandid, null, $item->sectionid);
                        } else {
                              $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($item->levelid, $item->studid, $syid, $item->strandid, null, $item->sectionid);
                        }

                        $temp_grades = array();
                        $finalgrade = array();

                        foreach ($studgrades as $grade_item) {
                              if ($grade_item->id == 'G1') {
                                    array_push($finalgrade, $grade_item);
                              } else {
                                    if ($grade_item->strandid == $item->strandid) {
                                          array_push($temp_grades, $grade_item);
                                    }
                                    if ($grade_item->strandid == null) {
                                          array_push($temp_grades, $grade_item);
                                    }
                              }
                        }

                        $studgrades = $temp_grades;
                        $studgrades = collect($studgrades)->sortBy('sortid')->values();


                        $final_grade = collect($finalgrade)->where('semid', $semid)->first();
                        $fail_subj = '';
                        $first = true;
                        $item->fail_subj = '';
                        $item->genave = null;

                        if ($final_grade->actiontaken != null) {
                              $failing_mark_1 = collect($studgrades)->where('semid', 1)->where('finalrating', '<', 75)->count();

                              if ($failing_mark_1 > 0) {
                                    $item->ess1 = 'INCOMPLETE';
                              }

                              $failing_mark_2 = collect($studgrades)->where('semid', 2)->where('finalrating', '<', 75)->count();

                              if ($failing_mark_2 > 0) {
                                    $item->ess2 = 'INCOMPLETE';
                              }
                              if ($failing_mark_1 > 0 || $failing_mark_2 > 0) {
                                    $item->esys = 'IRREGULAR';
                              }
                        } else {
                              if ($semid == 1) {
                                    $item->ess1 = '';
                              } elseif ($semid == 2) {
                                    $item->ess2 = '';
                              }

                              $item->esys = '';
                        }

                        if ($final_grade->actiontaken != null) {
                              $failing_mark = collect($studgrades)->where('semid', $semid)->where('finalrating', '<', 75)->count();
                              if ($failing_mark == 2 || $failing_mark == 1) {
                                    $item->actiontaken = 'CONDITIONAL';
                                    foreach (collect($studgrades)->where('semid', $semid)->where('finalrating', '<', 75)->values() as $fail_item) {
                                          if ($first) {
                                                $fail_subj .= $fail_item->subjcode;
                                                $first = false;
                                          } else {
                                                $fail_subj .= ', ' . $fail_item->subjcode;
                                          }
                                    }
                                    $item->fail_subj = $fail_subj;
                              } elseif ($failing_mark >= 3) {
                                    $item->actiontaken = 'RETAINED';
                                    foreach (collect($studgrades)->where('semid', $semid)->where('finalrating', '<', 75)->values() as $fail_item) {
                                          if ($first) {
                                                $fail_subj .= $fail_item->subjcode;
                                                $first = false;
                                          } else {
                                                $fail_subj .= ', ' . $fail_item->subjcode;
                                          }
                                    }
                                    $item->fail_subj = $fail_subj;
                              } else {
                                    $item->actiontaken = 'PROMOTED';
                              }
                              $item->genave = collect($finalgrade)->where('semid', $semid)->first()->finalrating;
                        } else {
                              $item->actiontaken = null;
                              $item->genave = null;
                        }


                  } else if ($acadprog == 2 || $acadprog == 3 || $acadprog == 4) {
                        if ($grading_version->version == 'v2') {
                              $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2($item->levelid, $item->id, $syid, null, null, $item->sectionid);
                        } else {
                              $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($item->levelid, $item->studid, $syid, null, null, $item->sectionid);
                        }


                        // return $studgrades;
                        $grades = $studgrades;
                        $grades = collect($grades)->sortBy('sortid')->values();
                        $finalgrade = collect($grades)->where('id', 'G1')->values();
                        unset($grades[count($grades) - 1]);
                        $studgrades = collect($grades)->where('isVisible', '1')->values();

                        $final_grade = collect($finalgrade)->first();
                        $fail_subj = '';
                        $first = true;
                        $item->fail_subj = '';
                        $item->genave = null;

                        if ($final_grade->actiontaken != null) {
                              $failing_mark = collect($studgrades)->whereNull('subjCom')->where('finalrating', '<', 75)->count();
                              if ($failing_mark == 2 || $failing_mark == 1) {
                                    $item->actiontaken = 'CONDITIONAL';
                                    foreach (collect($studgrades)->whereNull('subjCom')->where('finalrating', '<', 75)->values() as $fail_item) {
                                          if ($first) {
                                                $fail_subj .= $fail_item->subjcode;
                                                $first = false;
                                          } else {
                                                $fail_subj .= ', ' . $fail_item->subjcode;
                                          }
                                    }
                                    $item->fail_subj = $fail_subj;
                              } elseif ($failing_mark >= 3) {
                                    $item->actiontaken = 'RETAINED';
                                    foreach (collect($studgrades)->whereNull('subjCom')->where('finalrating', '<', 75)->values() as $fail_item) {
                                          if ($first) {
                                                $fail_subj .= $fail_item->subjcode;
                                                $first = false;
                                          } else {
                                                $fail_subj .= ', ' . $fail_item->subjcode;
                                          }
                                    }
                                    $item->fail_subj = $fail_subj;
                              } else {
                                    $item->actiontaken = 'PROMOTED';
                              }
                              $item->genave = collect($finalgrade)->first()->finalrating;
                        } else {
                              $item->actiontaken = null;
                              $item->genave = null;
                        }
                  }

            }

            return $enrolled;

      }


      public static function unpromote_student(Request $request)
      {

            try {
                  $syid = $request->get('syid');
                  $studid = $request->get('studid');
                  $semid = $request->get('semid');
                  $gradelevel = $request->get('levelid');
                  $studid = $request->get('studid');
                  $condition = $request->get('condition');
                  $auto_enroll = $request->get('auto_enroll');

                  $students = self::enrolled_students($syid, $semid, $gradelevel, $studid, $condition);

                  $sy_desc = DB::table('sy')
                        ->where('id', $syid)
                        ->first();


                  $acadprog = DB::table('gradelevel')
                        ->where('id', $gradelevel)
                        ->where('deleted', 0)
                        ->select('acadprogid')
                        ->first()
                        ->acadprogid;

                  if ($sy_desc->ended == 1) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'info' => array(),
                                    'data' => 'S.Y. ended!'
                              ]
                        );
                  }

                  foreach ($students as $item) {
                        if ($item->promotionstatus != 0) {

                              $sectionname = null;

                              if ($acadprog != 6) {
                                    $sectionname = DB::table('sections')
                                          ->where('id', $item->sectionid)
                                          ->first()
                                          ->sectionname;
                              }


                              DB::table('studinfo')
                                    ->where('id', $item->studid)
                                    ->take(1)
                                    ->update([
                                          'studstatus' => $item->studstatus,
                                          'sectionid' => $item->sectionid,
                                          'levelid' => $item->levelid,
                                          'sectionname' => $sectionname,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              if ($acadprog == 2 || $acadprog == 3 || $acadprog == 4) {
                                    DB::table('enrolledstud')
                                          ->where('id', $item->id)
                                          ->take(1)
                                          ->update([
                                                'promotionstatus' => 0,
                                                'updatedby' => auth()->user()->id,
                                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              } elseif ($acadprog == 5) {

                                    $enrollinfo = DB::table('sh_enrolledstud')
                                          ->where('id', $item->id)
                                          ->first();

                                    if ($semid == 1) {

                                          DB::table('sh_enrolledstud')
                                                ->where('studid', $enrollinfo->studid)
                                                ->where('syid', $enrollinfo->syid)
                                                ->where('semid', 2)
                                                ->where('deleted', 0)
                                                ->take(1)
                                                ->update([
                                                      'deleted' => 1,
                                                      'deletedby' => auth()->user()->id,
                                                      'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                                ]);

                                    }

                                    DB::table('sh_enrolledstud')
                                          ->where('id', $item->id)
                                          ->take(1)
                                          ->update([
                                                'promotionstatus' => 0,
                                                'updatedby' => auth()->user()->id,
                                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);

                                    DB::table('sh_enrolledstud')
                                          ->where('id', $item->id)
                                          ->take(1)
                                          ->update([
                                                'promotionstatus' => 0,
                                                'updatedby' => auth()->user()->id,
                                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);

                              } elseif ($acadprog == 6) {
                                    DB::table('college_enrolledstud')
                                          ->where('id', $item->id)
                                          ->take(1)
                                          ->update([
                                                'studstatus' => 1,
                                                'promotionstatus' => 0,
                                                'updatedby' => auth()->user()->id,
                                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }
                        }
                  }


                  $students = self::enrolled_students($syid, $semid, $gradelevel, null, $condition);

                  if ($condition == 1) {
                        self::check_grades($syid, $semid, $students, $gradelevel);
                  } else {
                        foreach ($students as $item) {
                              $item->genave = null;
                              $item->actiontaken = null;
                              $item->fail_subj = null;
                        }
                  }
                  foreach ($students as $item) {
                        $item->promotedtonextgradelevel = false;
                        if ($item->levelname != $item->curlevelname) {
                              $item->promotedtonextgradelevel = true;
                        }
                  }


                  return array(
                        (object) [
                              'status' => 1,
                              'info' => $students,
                              'data' => 'Unpromoted Successfully!'
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }

      }



      public static function promote_student(Request $request)
      {

            $syid = $request->get('syid');
            $studid = $request->get('studid');
            $semid = $request->get('semid');
            $gradelevel = $request->get('levelid');
            $studid = $request->get('studid');
            $condition = $request->get('condition');
            $auto_enroll = $request->get('auto_enroll');

            $students = self::enrolled_students($syid, $semid, $gradelevel, $studid, $condition);
            $sy_desc = DB::table('sy')
                  ->where('id', $syid)
                  ->first();

            $next_sy = DB::table('sy')
                  ->where('sydesc', '>', $sy_desc->sydesc)
                  ->orderBy('sydesc')
                  ->select('id')
                  ->first();

            $acadprog = DB::table('gradelevel')
                  ->where('id', $gradelevel)
                  ->where('deleted', 0)
                  ->select('acadprogid')
                  ->first()
                  ->acadprogid;



            if ($acadprog == 5 || $acadprog == 6) {
                  if ($semid == 2) {
                        if (!isset($next_sy->id)) {
                              return array(
                                    (object) [
                                          'status' => 0,
                                          'info' => array(),
                                          'data' => 'Please create next S.Y. first!'
                                    ]
                              );
                        }
                  }
            } else {
                  if (!isset($next_sy->id)) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'info' => array(),
                                    'data' => 'Please create next S.Y. first!'
                              ]
                        );
                  }
            }



            if ($condition == 1) {
                  self::check_grades($syid, $semid, $students, $gradelevel);
            }

            foreach ($students as $item) {

                  //with grades
                  if ($condition == 1 && ($item->promotionstatus == 0 || $item->promotionstatus == null)) {


                        if ($item->actiontaken == null && $studid != null) {
                              //  $students = self::enrolled_students($syid, $semid, $gradelevel, $studid);
                              $students = self::enrolled_students($syid, $semid, $gradelevel, null, $condition);
                              if ($condition == 1) {
                                    self::check_grades($syid, $semid, $students, $gradelevel);
                              } else {
                                    foreach ($students as $item) {
                                          $item->genave = null;
                                          $item->actiontaken = null;
                                          $item->fail_subj = null;
                                    }
                              }
                              foreach ($students as $item) {
                                    $item->promotedtonextgradelevel = false;
                                    if ($item->levelname != $item->curlevelname) {
                                          $item->promotedtonextgradelevel = true;
                                    }
                              }

                              return array(
                                    (object) [
                                          'status' => 0,
                                          'info' => $students,
                                          'data' => 'Student does not contain grades!'
                                    ]
                              );
                        }

                        if ($acadprog == 5) {

                              $promstat = 0;
                              $promotion_gradelevel = $item->levelid;

                              $gradelevel_sort = DB::table('gradelevel')
                                    ->where('id', $gradelevel)
                                    ->where('deleted', 0)
                                    ->select('sortid', 'levelname', 'id')
                                    ->first();

                              $next_gradelevel = DB::table('gradelevel')
                                    ->where('sortid', '>', $gradelevel_sort->sortid)
                                    ->orderBy('sortid')
                                    ->where('deleted', 0)
                                    ->select('sortid', 'levelname', 'id')
                                    ->first()
                                    ->id;

                              if ($item->actiontaken == 'PROMOTED') {
                                    $promstat = 1;
                                    $promotion_gradelevel = $next_gradelevel;
                              } elseif ($item->actiontaken == 'CONDITIONAL') {
                                    $promstat = 2;
                              } elseif ($item->actiontaken == 'RETAINED') {
                                    $promstat = 3;
                              }



                              if ($semid == 1) {

                                    if ($auto_enroll == 'true') {

                                          $current_enrollment = DB::table('sh_enrolledstud')
                                                ->where('deleted', 0)
                                                ->where('syid', $syid)
                                                ->where('semid', $semid)
                                                ->where('studid', $item->studid)
                                                ->whereIn('studstatus',[1,2,4])
                                                ->first();


                                          if ($current_enrollment->promotionstatus == 0) {

                                                DB::table('sh_enrolledstud')
                                                      ->insert([
                                                            'studid' => $current_enrollment->studid,
                                                            'syid' => $syid,
                                                            'semid' => 2,
                                                            'levelid' => $current_enrollment->levelid,
                                                            'sectionid' => $current_enrollment->sectionid,
                                                            'strandid' => $current_enrollment->strandid,
                                                            'blockid' => $current_enrollment->blockid,
                                                            'teacherid' => $current_enrollment->teacherid,
                                                            'dateenrolled' => \Carbon\Carbon::now('Asia/Manila'),
                                                            'createdby' => $current_enrollment->createdby,
                                                            'createddatetime' => $current_enrollment->createddatetime,
                                                            'studstatus' => $current_enrollment->studstatus,
                                                            'promotionstatus' => 0,
                                                            'studmol' => $current_enrollment->studmol,
                                                            'admissiontype' => $current_enrollment->admissiontype
                                                      ]);

                                          }

                                    } else {

                                          DB::table('studinfo')
                                                ->where('id', $item->studid)
                                                ->whereIn('studstatus',[1,2,4])
                                                ->where('deleted', 0)
                                                ->take(1)
                                                ->update([
                                                      'studstatus' => 0,
                                                      'sectionid' => null,
                                                      'sectionname' => null,
                                                      'updatedby' => auth()->user()->id,
                                                      'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                                      'feesid' => null,
                                                      'nodp' => 0,
                                                      'allownodpby' => null,
                                                      'allownodpdatetime' => null
                                                ]);
                                    }

                              } else {

                                    $sectionid = null;
                                    $studstatus = 0;
                                    $sectionname = null;
                                    $mol = null;
                                    $levelid = $promotion_gradelevel;
                                    $strandid = $item->strandid;
                                    $courseid = null;

                                    if ($promotion_gradelevel == 15) {

                                          $check_enrollment = DB::table('sh_enrolledstud')
                                                ->join('sections', function ($join) {
                                                      $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                                                      $join->where('sections.deleted', 0);
                                                })
                                                ->where('sh_enrolledstud.deleted', 0)
                                                ->where('sh_enrolledstud.syid', $next_sy->id)
                                                ->where('sh_enrolledstud.studid', $item->studid)
                                                ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                                ->select(
                                                      'sh_enrolledstud.sectionid',
                                                      'sh_enrolledstud.studstatus',
                                                      'sh_enrolledstud.studmol',
                                                      'sections.sectionname',
                                                      'sh_enrolledstud.levelid',
                                                      'sh_enrolledstud.strandid'
                                                )
                                                ->first();

                                          if (isset($check_enrollment)) {
                                                $strandid = $check_enrollment->strandid;
                                                $sectionid = $check_enrollment->sectionid;
                                                $studstatus = $check_enrollment->studstatus;
                                                $sectionname = $check_enrollment->sectionname;
                                                $mol = $check_enrollment->studmol;
                                                $levelid = $check_enrollment->levelid;
                                          }

                                    } else if ($promotion_gradelevel == 17) {

                                          $check_enrollment = DB::table('college_enrolledstud')
                                                ->join('college_sections', function ($join) {
                                                      $join->on('college_enrolledstud.sectionID', '=', 'college_sections.id');
                                                      $join->where('college_sections.deleted', 0);
                                                })
                                                ->where('college_enrolledstud.deleted', 0)
                                                ->where('college_enrolledstud.syid', $next_sy->id)
                                                ->where('college_enrolledstud.studid', $item->studid)
                                                ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                                ->select(
                                                      'college_enrolledstud.sectionID as sectionid',
                                                      'college_enrolledstud.studstatus',
                                                      'college_enrolledstud.studmol',
                                                      'college_enrolledstud.courseid',
                                                      'college_sections.sectionDesc as sectionname',
                                                      'college_enrolledstud.yearLevel as levelid'
                                                )
                                                ->first();

                                          if (isset($check_enrollment)) {
                                                $strandid = null;
                                                $courseid = $check_enrollment->courseid;
                                                $sectionid = $check_enrollment->sectionid;
                                                $studstatus = $check_enrollment->studstatus;
                                                $sectionname = $check_enrollment->sectionname;
                                                $mol = $check_enrollment->studmol;
                                                $levelid = $check_enrollment->levelid;
                                          }
                                    }



                                    DB::table('studinfo')
                                          ->where('id', $item->studid)
                                          ->where('deleted', 0)
                                          ->take(1)
                                          ->update([
                                                'studtype' => 'old',
                                                'studstatus' => $studstatus,
                                                'sectionid' => $sectionid,
                                                'sectionname' => $sectionname,
                                                'levelid' => $levelid,
                                                'updatedby' => auth()->user()->id,
                                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                                'feesid' => null,
                                                'nodp' => 0,
                                                'strandid' => $strandid,
                                                'courseid' => $courseid,
                                                'allownodpby' => null,
                                                'allownodpdatetime' => null
                                          ]);


                              }

                              DB::table('sh_enrolledstud')
                                    ->where('deleted', 0)
                                    ->where('syid', $syid)
                                    ->where('semid', $semid)
                                    ->where('studid', $item->studid)
                                    ->take(1)
                                    ->update([
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'promotionstatus' => $promstat
                                    ]);

                        } else if ($acadprog == 6) {

                              
                        } else {

                              $promstat = 0;
                              $promotion_gradelevel = $item->levelid;

                              $gradelevel_sort = DB::table('gradelevel')
                                    ->where('id', $gradelevel)
                                    ->where('deleted', 0)
                                    ->select('sortid', 'levelname', 'id')
                                    ->first();

                              $next_gradelevel = DB::table('gradelevel')
                                    ->where('sortid', '>', $gradelevel_sort->sortid)
                                    ->orderBy('sortid')
                                    ->where('deleted', 0)
                                    ->select('sortid', 'levelname', 'id')
                                    ->first()
                                    ->id;

                              if ($item->actiontaken == 'PROMOTED') {
                                    $promstat = 1;
                                    $promotion_gradelevel = $next_gradelevel;
                              } elseif ($item->actiontaken == 'CONDITIONAL') {
                                    $promstat = 2;
                              } elseif ($item->actiontaken == 'RETAINED') {
                                    $promstat = 3;
                              }

                              $sectionid = null;
                              $studstatus = 0;
                              $sectionname = null;
                              $mol = null;
                              $strandid = null;
                              $levelid = $promotion_gradelevel;

                              if ($promotion_gradelevel == 14) {
                                    $check_enrollment = DB::table('sh_enrolledstud')
                                          ->join('sections', function ($join) {
                                                $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                                                $join->where('sections.deleted', 0);
                                          })
                                          ->where('sh_enrolledstud.deleted', 0)
                                          ->where('sh_enrolledstud.syid', $next_sy->id)
                                          ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                          ->where('sh_enrolledstud.studid', $item->studid)
                                          ->select(

                                                'strandid',
                                                'sh_enrolledstud.sectionid',
                                                'sh_enrolledstud.studstatus',
                                                'sh_enrolledstud.studmol',
                                                'sections.sectionname',
                                                'sh_enrolledstud.levelid'
                                          )
                                          ->first();

                                    if (isset($check_enrollment)) {
                                          $strandid = $check_enrollment->strandid;
                                    }

                              } else {
                                    $check_enrollment = DB::table('enrolledstud')
                                          ->join('sections', function ($join) {
                                                $join->on('enrolledstud.sectionid', '=', 'sections.id');
                                                $join->where('sections.deleted', 0);
                                          })
                                          ->where('enrolledstud.deleted', 0)
                                          ->where('enrolledstud.syid', $next_sy->id)
                                          ->where('enrolledstud.studid', $item->studid)
                                          ->whereIn('enrolledstud.studstatus',[1,2,4])
                                          ->select(
                                                'enrolledstud.sectionid',
                                                'enrolledstud.studstatus',
                                                'enrolledstud.studmol',
                                                'sections.sectionname',
                                                'enrolledstud.levelid'
                                          )
                                          ->first();
                              }

                              if (isset($check_enrollment)) {
                                    $sectionid = $check_enrollment->sectionid;
                                    $studstatus = $check_enrollment->studstatus;
                                    $sectionname = $check_enrollment->sectionname;
                                    $mol = $check_enrollment->studmol;
                                    $levelid = $check_enrollment->levelid;
                              }

                              DB::table('enrolledstud')
                                    ->where('deleted', 0)
                                    ->where('syid', $syid)
                                    ->where('studid', $item->studid)
                                    ->take(1)
                                    ->update([
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'promotionstatus' => $promstat
                                    ]);



                              DB::table('studinfo')
                                    ->where('id', $item->studid)
                                    ->where('deleted', 0)
                                    ->take(1)
                                    ->update([
                                          'studtype' => 'old',
                                          'studstatus' => $studstatus,
                                          'sectionid' => $sectionid,
                                          'sectionname' => $sectionname,
                                          'levelid' => $levelid,
                                          'mol' => $mol,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'feesid' => null,
                                          'nodp' => 0,
                                          'allownodpby' => null,
                                          'allownodpdatetime' => null
                                    ]);

                              // DB::table('studinfo')
                              //       ->where('id',$item->studid)
                              //       ->where('deleted',0)
                              //       ->take(1)
                              //       ->update([
                              //             'studstatus'=>0,
                              //             'sectionid'=>null,
                              //             'sectionname'=>null,
                              //             'levelid'=>$promotion_gradelevel,
                              //             'updatedby'=>auth()->user()->id,
                              //             'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              //             'feesid'=>null,
                              //             'nodp'=>0,
                              //             'allownodpby'=>null,
                              //             'allownodpdatetime'=>null
                              //       ]);


                        }

                  } else {

                        //without grades
                        if ($item->promotionstatus == 0) {

                              $gradelevel_sort = DB::table('gradelevel')
                                    ->where('id', $gradelevel)
                                    ->where('deleted', 0)
                                    ->select('sortid', 'levelname', 'id')
                                    ->first();

                              $next_gradelevel = DB::table('gradelevel')
                                    ->where('sortid', '>', $gradelevel_sort->sortid)
                                    ->orderBy('sortid')
                                    ->where('deleted', 0)
                                    ->select('sortid', 'levelname', 'id')
                                    ->first()
                                    ->id;

                              $promotion_gradelevel = $next_gradelevel;

                              if ($acadprog == 5) {

                                    self::shs_without_grades($syid, $semid, $item, $auto_enroll, $promotion_gradelevel, $next_sy);

                              } else if ($acadprog == 6) {

                                    DB::table('college_enrolledstud')
                                          ->where('deleted', 0)
                                          ->where('syid', $syid)
                                          ->where('semid', $semid)
                                          ->where('studid', $item->studid)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->take(1)
                                          ->update([
                                                'updatedby' => auth()->user()->id,
                                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                                'promotionstatus' => 1,
                                                'studstatus' => 0
                                          ]);

                                    if ($semid == 1) {

                                          DB::table('studinfo')
                                                ->where('id', $item->studid)
                                                ->where('deleted', 0)
                                                ->whereIn('studstatus',[1,2,4])
                                                ->take(1)
                                                ->update([
                                                      'studstatus' => 0,
                                                      'sectionid' => null,
                                                      'sectionname' => null,
                                                      'updatedby' => auth()->user()->id,
                                                      'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                                      'feesid' => null,
                                                      'nodp' => 0,
                                                      'allownodpby' => null,
                                                      'allownodpdatetime' => null
                                                ]);

                                    } else {

                                          $sectionid = null;
                                          $studstatus = 0;
                                          $sectionname = null;
                                          $mol = null;
                                          $levelid = $promotion_gradelevel;
                                          $courseid = null;

                                          $check_enrollment = DB::table('college_enrolledstud')
                                                ->join('college_sections', function ($join) {
                                                      $join->on('college_enrolledstud.sectionID', '=', 'college_sections.id');
                                                      $join->where('college_sections.deleted', 0);
                                                })
                                                ->where('college_enrolledstud.deleted', 0)
                                                ->where('college_enrolledstud.syid', $next_sy->id)
                                                ->where('college_enrolledstud.studid', $item->studid)
                                                ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                                ->select(
                                                      'college_enrolledstud.sectionID as sectionid',
                                                      'college_enrolledstud.studstatus',
                                                      'college_enrolledstud.studmol',
                                                      'college_enrolledstud.courseid',
                                                      'college_sections.sectionDesc as sectionname',
                                                      'college_enrolledstud.yearLevel as levelid'
                                                )
                                                ->first();

                                          if (isset($check_enrollment)) {
                                                $strandid = null;
                                                $courseid = $check_enrollment->courseid;
                                                $sectionid = $check_enrollment->sectionid;
                                                $studstatus = $check_enrollment->studstatus;
                                                $sectionname = $check_enrollment->sectionname;
                                                $mol = $check_enrollment->studmol;
                                                $levelid = $check_enrollment->levelid;
                                          } else {
                                                $enrollmentcourse = DB::table('college_enrolledstud')
                                                      ->where('college_enrolledstud.syid', $syid)
                                                      ->where('college_enrolledstud.studid', $item->studid)
                                                      ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                                      ->where('deleted', 0)
                                                      ->where('semid', $semid)
                                                      ->select('courseid')
                                                      ->first();

                                                $courseid = $enrollmentcourse->courseid;

                                          }

                                          DB::table('studinfo')
                                                ->where('id', $item->studid)
                                                ->where('deleted', 0)
                                                ->take(1)
                                                ->update([
                                                      'studtype' => 'old',
                                                      'studstatus' => $studstatus,
                                                      'sectionid' => $sectionid,
                                                      'courseid' => $courseid,
                                                      'sectionname' => $sectionname,
                                                      'levelid' => $promotion_gradelevel,
                                                      'updatedby' => auth()->user()->id,
                                                      'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                                      'feesid' => null,
                                                      'mol' => $mol,
                                                      'nodp' => 0,
                                                      'allownodpby' => null,
                                                      'allownodpdatetime' => null
                                                ]);
                                    }

                              } else {

                                    $check_status = DB::table('enrolledstud')
                                          ->where('deleted', 0)
                                          ->where('syid', $syid)
                                          ->where('studid', $item->studid)
                                          ->first();

                                    $promstat = 1;

                                    if ($check_status->studstatus == 3 || $check_status->studstatus == 5 || $check_status->studstatus == 6) {
                                          $promstat = 3;
                                    }



                                    DB::table('enrolledstud')
                                          ->where('deleted', 0)
                                          ->where('syid', $syid)
                                          ->where('studid', $item->studid)
                                          ->take(1)
                                          ->update([
                                                'updatedby' => auth()->user()->id,
                                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                                'promotionstatus' => $promstat
                                          ]);



                                    $sectionid = null;
                                    $studstatus = 0;
                                    $sectionname = null;
                                    $mol = null;
                                    $strandid = null;

                                    if ($promstat == 1) {

                                          $levelid = $promotion_gradelevel;

                                          if ($promotion_gradelevel == 14) {

                                                $check_enrollment = DB::table('sh_enrolledstud')
                                                      ->join('sections', function ($join) {
                                                            $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                                                            $join->where('sections.deleted', 0);
                                                      })
                                                      ->where('sh_enrolledstud.deleted', 0)
                                                      ->where('sh_enrolledstud.syid', $next_sy->id)
                                                      ->where('sh_enrolledstud.studid', $item->studid)
                                                      ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                                      ->select(
                                                            'strandid',
                                                            'sh_enrolledstud.sectionid',
                                                            'sh_enrolledstud.studstatus',
                                                            'sh_enrolledstud.studmol',
                                                            'sections.sectionname',
                                                            'sh_enrolledstud.levelid'
                                                      )
                                                      ->first();

                                                if (isset($check_enrollment)) {
                                                      $strandid = $check_enrollment->strandid;
                                                }

                                          } else {
                                                $check_enrollment = DB::table('enrolledstud')
                                                      ->join('sections', function ($join) {
                                                            $join->on('enrolledstud.sectionid', '=', 'sections.id');
                                                            $join->where('sections.deleted', 0);
                                                      })
                                                      ->where('enrolledstud.deleted', 0)
                                                      ->where('enrolledstud.syid', $next_sy->id)
                                                      ->where('enrolledstud.studid', $item->studid)
                                                      ->whereIn('enrolledstud.studstatus',[1,2,4])
                                                      ->select(
                                                            'enrolledstud.sectionid',
                                                            'enrolledstud.studstatus',
                                                            'enrolledstud.studmol',
                                                            'sections.sectionname',
                                                            'enrolledstud.levelid'
                                                      )
                                                      ->first();
                                          }

                                          if (isset($check_enrollment)) {
                                                $sectionid = $check_enrollment->sectionid;
                                                $studstatus = $check_enrollment->studstatus;
                                                $sectionname = $check_enrollment->sectionname;
                                                $mol = $check_enrollment->studmol;
                                                $levelid = $check_enrollment->levelid;
                                          }
                                    } else {
                                          $levelid = $check_status->levelid;
                                    }

                                    DB::table('studinfo')
                                          ->where('id', $item->studid)
                                          ->where('deleted', 0)
                                          ->take(1)
                                          ->update([
                                                'studtype' => 'old',
                                                'studstatus' => $studstatus,
                                                'sectionid' => $sectionid,
                                                'sectionname' => $sectionname,
                                                'levelid' => $levelid,
                                                'strandid' => $strandid,
                                                'mol' => $mol,
                                                'updatedby' => auth()->user()->id,
                                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                                'feesid' => null,
                                                'nodp' => 0,
                                                'allownodpby' => null,
                                                'allownodpdatetime' => null
                                          ]);

                              }


                        }
                  }

            }


            //clear studinfo enrollment information
            if ($acadprog == 5) {
                  if ($semid == 2) {
                        DB::table('studinfo')
                              ->whereIn('levelid', [14, 15])
                              ->where('deleted', 0)
                              ->get();
                  }
            }


            //  $students = self::enrolled_students($syid, $semid, $gradelevel, $studid);
            $students = self::enrolled_students($syid, $semid, $gradelevel, null, $condition);

            if ($condition == 1) {
                  self::check_grades($syid, $semid, $students, $gradelevel);
            } else {
                  foreach ($students as $item) {
                        $item->genave = null;
                        $item->actiontaken = null;
                        $item->fail_subj = null;
                  }
            }
            foreach ($students as $item) {
                  $item->promotedtonextgradelevel = false;
                  if ($item->levelname != $item->curlevelname) {
                        $item->promotedtonextgradelevel = true;
                  }
            }

            return array(
                  (object) [
                        'status' => 1,
                        'info' => $students,
                        'data' => 'Promoted Successfully!'
                  ]
            );

      }


      public static function shs_without_grades($syid, $semid, $item, $auto_enroll, $promotion_gradelevel, $next_sy)
      {


            $check_status = DB::table('sh_enrolledstud')
                  ->where('deleted', 0)
                  ->where('syid', $syid)
                  ->where('semid', $semid)
                  ->where('studid', $item->studid)
                  ->first();

            $promstat = 1;

            if ($check_status->studstatus == 3 || $check_status->studstatus == 5 || $check_status->studstatus == 6) {
                  $promstat = 3;
            }

            if ($semid == 1) {

                  if ($auto_enroll == 'true') {

                        $current_enrollment = DB::table('sh_enrolledstud')
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->where('studid', $item->studid)
                              ->first();

                        if ($current_enrollment->promotionstatus == 0 && $promstat == 1) {
                              DB::table('sh_enrolledstud')
                                    ->insert([
                                          'studid' => $current_enrollment->studid,
                                          'syid' => $syid,
                                          'semid' => 2,
                                          'levelid' => $current_enrollment->levelid,
                                          'sectionid' => $current_enrollment->sectionid,
                                          'strandid' => $current_enrollment->strandid,
                                          'blockid' => $current_enrollment->blockid,
                                          'teacherid' => $current_enrollment->teacherid,
                                          'dateenrolled' => \Carbon\Carbon::now('Asia/Manila'),
                                          'createdby' => $current_enrollment->createdby,
                                          'createddatetime' => $current_enrollment->createddatetime,
                                          'studstatus' => 1,
                                          'studmol' => $current_enrollment->studmol,
                                          'admissiontype' => $current_enrollment->admissiontype,
                                          'promotionstatus' => 0
                                    ]);
                        } else {

                              DB::table('studinfo')
                                    ->where('id', $item->studid)
                                    ->where('deleted', 0)
                                    ->take(1)
                                    ->update([
                                          'studstatus' => 0,
                                          'sectionid' => null,
                                          'sectionname' => null,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'feesid' => null,
                                          'nodp' => 0,
                                          'allownodpby' => null,
                                          'allownodpdatetime' => null
                                    ]);
                        }

                  } else {

                        DB::table('studinfo')
                              ->where('id', $item->studid)
                              ->where('deleted', 0)
                              ->take(1)
                              ->update([
                                    'studstatus' => 0,
                                    'sectionid' => null,
                                    'sectionname' => null,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'feesid' => null,
                                    'nodp' => 0,
                                    'allownodpby' => null,
                                    'allownodpdatetime' => null
                              ]);
                  }

            } else {


                  $sectionid = null;
                  $studstatus = 0;
                  $sectionname = null;
                  $mol = null;
                  $levelid = $promotion_gradelevel;
                  $strandid = $item->strandid;
                  $courseid = null;

                  if ($promstat == 1) {
                        if ($promotion_gradelevel == 15) {
                              $check_enrollment = DB::table('sh_enrolledstud')
                                    ->join('sections', function ($join) {
                                          $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                                          $join->where('sections.deleted', 0);
                                    })
                                    ->where('sh_enrolledstud.deleted', 0)
                                    ->where('sh_enrolledstud.syid', $next_sy->id)
                                    ->where('sh_enrolledstud.studid', $item->studid)
                                    ->select(
                                          'sh_enrolledstud.sectionid',
                                          'sh_enrolledstud.studstatus',
                                          'sh_enrolledstud.studmol',
                                          'sections.sectionname',
                                          'sh_enrolledstud.levelid',
                                          'sh_enrolledstud.strandid'
                                    )
                                    ->first();

                              if (isset($check_enrollment)) {
                                    $strandid = $check_enrollment->strandid;
                                    $sectionid = $check_enrollment->sectionid;
                                    $studstatus = $check_enrollment->studstatus;
                                    $sectionname = $check_enrollment->sectionname;
                                    $mol = $check_enrollment->studmol;
                                    $levelid = $check_enrollment->levelid;
                              }

                        } else if ($promotion_gradelevel == 17) {

                              $check_enrollment = DB::table('college_enrolledstud')
                                    ->join('college_sections', function ($join) {
                                          $join->on('college_enrolledstud.sectionID', '=', 'college_sections.id');
                                          $join->where('college_sections.deleted', 0);
                                    })
                                    ->where('college_enrolledstud.deleted', 0)
                                    ->where('college_enrolledstud.syid', $next_sy->id)
                                    ->where('college_enrolledstud.studid', $item->studid)
                                    ->select(
                                          'college_enrolledstud.sectionID as sectionid',
                                          'college_enrolledstud.studstatus',
                                          'college_enrolledstud.studmol',
                                          'college_enrolledstud.courseid',
                                          'college_sections.sectionDesc as sectionname',
                                          'college_enrolledstud.yearLevel as levelid'
                                    )
                                    ->first();

                              if (isset($check_enrollment)) {
                                    $strandid = null;
                                    $courseid = $check_enrollment->courseid;
                                    $sectionid = $check_enrollment->sectionid;
                                    $studstatus = $check_enrollment->studstatus;
                                    $sectionname = $check_enrollment->sectionname;
                                    $mol = $check_enrollment->studmol;
                                    $levelid = $check_enrollment->levelid;
                              }
                        }
                  } else {
                        $levelid = $check_status->levelid;
                  }





                  DB::table('studinfo')
                        ->where('id', $item->studid)
                        ->where('deleted', 0)
                        ->take(1)
                        ->update([
                              'studtype' => 'old',
                              'studstatus' => $studstatus,
                              'sectionid' => $sectionid,
                              'sectionname' => $sectionname,
                              'levelid' => $levelid,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                              'feesid' => null,
                              'nodp' => 0,
                              'strandid' => $strandid,
                              'courseid' => $courseid,
                              'allownodpby' => null,
                              'allownodpdatetime' => null
                        ]);

            }




            DB::table('sh_enrolledstud')
                  ->where('deleted', 0)
                  ->where('syid', $syid)
                  ->where('semid', $semid)
                  ->where('studid', $item->studid)
                  ->take(1)
                  ->update([
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        'promotionstatus' => $promstat
                  ]);


      }

      public static function store_error($e)
      {
            DB::table('zerrorlogs')
                  ->insert([
                        'error' => $e,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);
            return array(
                  (object) [
                        'status' => 0,
                        'data' => 'Something went wrong!'
                  ]
            );
      }
}
