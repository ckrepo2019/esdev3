<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class SYSetupController extends \App\Http\Controllers\Controller
{
      public static function syEnd(Request $request)
      {

            try {

                  $syid = $request->get('syid');

                  $active_sy = Db::table('sy')
                        ->where('isactive', 1)
                        ->first();

                  $active_sy = explode('-', $active_sy->sydesc)[0];

                  $selectedSyInfo = DB::table('sy')
                        ->where('id', $syid)
                        ->first();

                  $selectedSyInfo = explode('-', $selectedSyInfo->sydesc)[0];

                  $selectSyDate = \Carbon\Carbon::create($selectedSyInfo);
                  $activeSyDate = \Carbon\Carbon::create($active_sy);

                  if (!$selectSyDate->gt($activeSyDate)) {
                        DB::table('sy')
                              ->where('id', $syid)
                              ->update([
                                    'ended' => 1,
                                    'updateddatetime' => \Carbon\Carbon::now()
                              ]);
                  }

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'School yead ended!',
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }

      }



      public static function schoolyear()
      {

            $sy = DB::table('sy')
                  ->orderBy('sydesc', 'desc')
                  ->get();

            $sort = 0;

            foreach ($sy as $item) {

                  $item->sdateorig = $item->sdate;
                  $item->edateorig = $item->edate;
                  $item->sdate = \Carbon\Carbon::create($item->sdate)->isoFormat('MMMM DD, YYYY');
                  $item->edate = \Carbon\Carbon::create($item->edate)->isoFormat('MMMM DD, YYYY');

                  $total_enrolled = 0;

                  $enrolled = DB::table('enrolledstud')
                        ->where('deleted', 0)
                        ->whereIn('studstatus', [1, 2, 4])
                        ->where('syid', $item->id)
                        ->distinct('studid')
                        ->count();

                  $total_enrolled += $enrolled;

                  $enrolled = DB::table('sh_enrolledstud')
                        ->where('deleted', 0)
                        ->where('syid', $item->id)
                        ->whereIn('studstatus', [1, 2, 4])
                        ->distinct('studid')
                        ->count();

                  $total_enrolled += $enrolled;

                  $enrolled = DB::table('college_enrolledstud')
                        ->where('deleted', 0)
                        ->where('syid', $item->id)
                        ->whereIn('studstatus', [1, 2, 4])
                        ->distinct('studid')
                        ->count();

                  $total_enrolled += $enrolled;

                  $item->enrolled = $total_enrolled;
                  $item->sort = $sort;
                  $sort += 1;

            }

            return $sy;

      }

      public static function enrollment_information(Request $request, $syid = null)
      {

            if ($syid == null) {
                  $syid = $request->get('syid');
            }


            $acadmicprogram = DB::table('academicprogram')
                  ->get();

            $temp_enrollment = array();

            foreach ($acadmicprogram as $item) {

                  $acadprogid = $item->id;

                  if ($item->id == 5) {

                        $acadname = $item->progname;

                        $enrolled = DB::table('sh_enrolledstud')
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->distinct('studid')
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('semid', 1)
                              ->count();

                        $promoted = DB::table('sh_enrolledstud')
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('promotionstatus', '!=', 0)
                              ->distinct('studid')
                              ->where('semid', 1)
                              ->count();

                        $temp_info = (object) [
                              'id' => 5,
                              'acadprogcode' => 'SHS',
                        ];

                        $temp_info->progname = $acadname . ' 1st Sem';
                        $temp_info->enrolled = $enrolled;
                        $temp_info->promoted = $promoted;
                        $temp_info->sort = 5;
                        $temp_info->not_promoted = $temp_info->enrolled - $temp_info->promoted;

                        array_push($temp_enrollment, $temp_info);



                        $enrolled_2 = DB::table('sh_enrolledstud')
                              ->where('deleted', 0)
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('syid', $syid)
                              ->distinct('studid')
                              ->where('semid', 2)
                              ->count();

                        $promoted_2 = DB::table('sh_enrolledstud')
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('promotionstatus', '!=', 0)
                              ->distinct('studid')
                              ->where('semid', 2)
                              ->count();

                        $temp_info_2 = (object) [
                              'id' => 5,
                              'acadprogcode' => 'SHS',
                        ];

                        $temp_info_2->progname = $acadname . ' 2nd Sem';
                        $temp_info_2->enrolled = $enrolled_2;
                        $temp_info_2->sort = 6;
                        $temp_info_2->promoted = $promoted_2;
                        $temp_info_2->not_promoted = $temp_info_2->enrolled - $temp_info_2->promoted;

                        array_push($temp_enrollment, $temp_info_2);


                  } else if ($item->id == 6) {

                        $enrolled = DB::table('college_enrolledstud')
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->where('semid', 1)
                              ->whereIn('studstatus', [1, 2, 4])
                              ->distinct('studid')
                              ->count();

                        $promoted = DB::table('college_enrolledstud')
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->where('semid', 1)
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('promotionstatus', '!=', 0)
                              ->distinct('studid')
                              ->count();



                        $temp_info = (object) [
                              'id' => 6,
                              'acadprogcode' => 'COLLEGE',
                        ];

                        $acadname = $item->progname;

                        $temp_info->progname = $acadname . ' 1st Sem';
                        $temp_info->sort = 7;
                        $temp_info->enrolled = $enrolled;
                        $temp_info->promoted = $promoted;
                        $temp_info->not_promoted = $temp_info->enrolled - $temp_info->promoted;
                        array_push($temp_enrollment, $temp_info);

                        $enrolled_2 = DB::table('college_enrolledstud')
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->where('semid', 2)
                              ->whereIn('studstatus', [1, 2, 4])
                              ->distinct('studid')
                              ->count();

                        $promoted_2 = DB::table('college_enrolledstud')
                              ->where('deleted', 0)
                              ->where('syid', $syid)
                              ->where('semid', 2)
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('promotionstatus', '!=', 0)
                              ->distinct('studid')
                              ->count();

                        $temp_info_2 = (object) [
                              'id' => 6,
                              'acadprogcode' => 'COLLEGE',
                        ];

                        $temp_info_2->progname = $acadname . ' 2nd Sem';
                        $temp_info_2->sort = 8;
                        $temp_info_2->enrolled = $enrolled_2;
                        $temp_info_2->promoted = $promoted_2;
                        $temp_info_2->not_promoted = $temp_info_2->enrolled - $temp_info_2->promoted;

                        array_push($temp_enrollment, $temp_info_2);

                  } else {


                        if ($item->id == 7) {
                              $item->sort = 9;
                        } else {
                              $item->sort = $item->id;
                        }

                        $enrolled = DB::table('enrolledstud')
                              ->join('gradelevel', function ($join) use ($acadprogid) {
                                    $join->on('enrolledstud.levelid', '=', 'gradelevel.id');
                                    $join->where('gradelevel.deleted', 0);
                                    $join->where('gradelevel.acadprogid', $acadprogid);
                              })
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('enrolledstud.deleted', 0)
                              ->where('enrolledstud.syid', $syid)
                              ->distinct('enrolledstud.studid')
                              ->count();

                        $promoted = DB::table('enrolledstud')
                              ->join('gradelevel', function ($join) use ($acadprogid) {
                                    $join->on('enrolledstud.levelid', '=', 'gradelevel.id');
                                    $join->where('gradelevel.deleted', 0);
                                    $join->where('gradelevel.acadprogid', $acadprogid);
                              })
                              ->whereIn('studstatus', [1, 2, 4])
                              ->where('enrolledstud.deleted', 0)
                              ->where('enrolledstud.syid', $syid)
                              ->where('promotionstatus', '!=', 0)
                              ->distinct('enrolledstud.studid')
                              ->count();

                        $item->enrolled = $enrolled;
                        $item->promoted = $promoted;
                        $item->not_promoted = $item->enrolled - $item->promoted;

                        array_push($temp_enrollment, $item);


                  }





            }

            return $temp_enrollment;

      }

      public static function semester_list(Request $request)
      {

            $semester = DB::table('semester')
                  ->where('id', '!=', 3)
                  ->where('deleted', 0)
                  ->get();

            return $semester;
      }

      public static function activatesem(Request $request, $semid = null)
      {
            $semid = $semid ?? $request->get('semid');

            try {
                  DB::table('semester')
                        ->whereNotIn('id', [$semid])
                        ->update(['isactive' => 0]);

                  DB::table('semester')
                        ->where('id', $semid)
                        ->update(['isactive' => 1]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'Semester Activated!',
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }

      public static function create_sy(Request $request)
      {

            $sdate = $request->get('sdate');
            $edate = $request->get('edate');

            $sy_start = \Carbon\Carbon::create($sdate)->isoFormat('YYYY');
            $sy_end = \Carbon\Carbon::create($edate)->isoFormat('YYYY');

            try {

                  $check = DB::table('sy')
                        ->where('sydesc', $sy_start . '-' . $sy_end)
                        ->count();

                  if ($check == 0) {


                        $isactive = 0;

                        $check = DB::table('sy')
                              ->count();

                        if ($check == 0) {
                              $isactive = 1;
                        }

                        DB::table('sy')
                              ->insert([
                                    'sydesc' => $sy_start . '-' . $sy_end,
                                    'sdate' => $sdate,
                                    'edate' => $edate,
                                    'isactive' => $isactive,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        self::activatesem($request, 1);

                        return array(
                              (object) [
                                    'status' => 1,
                                    'message' => 'S.Y. Created!',
                              ]
                        );
                  } else {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'S.Y. Already Exist!',
                              ]
                        );
                  }



            } catch (\Exception $e) {
                  return self::store_error($e);
            }

      }

      public static function activatesy(Request $request)
      {

            $id = $request->get('syid');

            try {

                  $activesy = DB::table('sy')
                        ->where('isactive', 1)
                        ->first()
                        ->id;

                  $enrollment_info = self::enrollment_information($request, $activesy);

                  $check_promotion = collect($enrollment_info)->where('id', '!=', 6)->where('not_promoted', '!=', 0)->count();

                  if ($check_promotion != 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Check active S.Y. promotion!',
                              ]
                        );
                  }

                  DB::table('sy')
                        ->where('id', $activesy)
                        ->take(1)
                        ->update([
                              'ended' => 1,
                              'isactive' => 0,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('sy')
                        ->where('id', $id)
                        ->take(1)
                        ->update([
                              'isactive' => 1,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('early_enrollment_setup')
                        ->where('isactive', 1)
                        ->update([
                              'isactive' => 0,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  self::activatesem($request, 1);

                  //copy academic program
                  $fasacad = DB::table('teacheracadprog')
                        ->where('deleted', 0)
                        ->where('syid', $activesy)
                        ->get();

                  foreach ($fasacad as $item) {
                        DB::table('teacheracadprog')
                              ->insert([
                                    'syid' => $id,
                                    'teacherid' => $item->teacherid,
                                    'acadprogid' => $item->acadprogid,
                                    'acadprogutype' => $item->acadprogutype,
                                    'deleted' => 0,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
                  //copy academic program


                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'S.Y. Activated!',
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }
      }


      public static function update_sy(Request $request)
      {

            $sdate = $request->get('sdate');
            $edate = $request->get('edate');
            $id = $request->get('id');

            try {

                  $sy_start = \Carbon\Carbon::create($sdate)->isoFormat('YYYY');
                  $sy_end = \Carbon\Carbon::create($edate)->isoFormat('YYYY');

                  if ($sy_start == $sy_end) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Start year and End year should not be the same!',
                              ]
                        );
                  }

                  if ($sy_start > $sy_end) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'End year is less than the start year!',
                              ]
                        );
                  }

                  $check = DB::table('sy')
                        ->where('id', '!=', $id)
                        ->where('sydesc', $sy_start . '-' . $sy_end)
                        ->count();


                  $getSYInfo = DB::table('sy')
                        ->where('id', $id)
                        ->first();

                  if ($getSYInfo->isactive == 1 || $getSYInfo->ended == 0) {
                        DB::table('sy')
                              ->where('id', $id)
                              ->where('ended', 0)
                              ->take(1)
                              ->update([
                                    'sydesc' => $sy_start . '-' . $sy_end,
                                    'sdate' => $sdate,
                                    'edate' => $edate,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return array(
                              (object) [
                                    'status' => 1,
                                    'message' => 'S.Y. Updated!',
                              ]
                        );
                  }


                  if ($check > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'S.Y. Already Exist!',
                              ]
                        );
                  }

                  $checkGSHSEnrollment = DB::table('enrolledstud')
                        ->where('syid', $id)
                        ->where('deleted', 0)
                        ->count();

                  if ($checkGSHSEnrollment > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Contains Enrollment',
                              ]
                        );
                  }

                  $checkGSHSEnrollment = DB::table('sh_enrolledstud')
                        ->where('syid', $id)
                        ->where('deleted', 0)
                        ->count();

                  if ($checkGSHSEnrollment > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Contains Enrollment',
                              ]
                        );
                  }


                  $checkGSHSEnrollment = DB::table('college_enrolledstud')
                        ->where('syid', $id)
                        ->where('deleted', 0)
                        ->count();

                  if ($checkGSHSEnrollment > 0) {
                        return array(
                              (object) [
                                    'status' => 0,
                                    'message' => 'Already Contains Enrollment',
                              ]
                        );
                  }


                  DB::table('sy')
                        ->where('id', $id)
                        ->where('ended', 0)
                        ->take(1)
                        ->update([
                              'sydesc' => $sy_start . '-' . $sy_end,
                              'sdate' => $sdate,
                              'edate' => $edate,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'S.Y. Updated!',
                        ]
                  );




            } catch (\Exception $e) {
                  return self::store_error($e);
            }

      }


      public static function delete_sy(Request $request)
      {

            $date_start = $request->get('date_start');
            $date_end = $request->get('date_end');
            $id = $request->get('id');

            try {

                  $enrolled = DB::table('enrolledstud')
                        ->where('deleted', 0)
                        ->where('syid', $id)
                        ->count();

                  if ($enrolled > 0) {
                        return array(
                              (object) [
                                    'status' => 1,
                                    'message' => 'S.Y. Contains Enrolled Students!',
                              ]
                        );
                  }

                  $enrolled = DB::table('sh_enrolledstud')
                        ->where('deleted', 0)
                        ->where('syid', $id)
                        ->count();

                  if ($enrolled > 0) {
                        return array(
                              (object) [
                                    'status' => 1,
                                    'message' => 'S.Y. Contains Enrolled Students!',
                              ]
                        );
                  }

                  $enrolled = DB::table('college_enrolledstud')
                        ->where('deleted', 0)
                        ->where('syid', $id)
                        ->count();

                  if ($enrolled > 0) {
                        return array(
                              (object) [
                                    'status' => 1,
                                    'message' => 'S.Y. Contains Enrolled Students!',
                              ]
                        );
                  }



                  DB::table('sy')
                        ->where('id', $id)
                        ->update([
                              'sdate' => $date_start,
                              'edate' => $date_end,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array(
                        (object) [
                              'status' => 1,
                              'message' => 'S.Y. Deleted!',
                        ]
                  );

            } catch (\Exception $e) {
                  return self::store_error($e);
            }

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
