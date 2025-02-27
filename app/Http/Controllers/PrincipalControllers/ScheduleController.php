<?php

namespace App\Http\Controllers\PrincipalControllers;
use Illuminate\Http\Request;
use DB;
use PDF;

class ScheduleController extends \App\Http\Controllers\Controller
{

    public static function getsubjects(Request $request)
    {

        try {

            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $sections = $request->get('sections');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $sectioninfo = DB::table('sectiondetail')
                ->where('syid', $syid)
                ->where('sectionid', $sectionid)
                ->where('deleted', 0)
                ->first();

            if ($levelid == 14 || $levelid == 15) {

                $sectionblockass = DB::table('sh_sectionblockassignment')
                    ->join('sh_block', function ($join) {
                        $join->on('sh_sectionblockassignment.blockid', '=', 'sh_block.id');
                        $join->where('sh_block.deleted', 0);
                    })
                    ->where('sh_sectionblockassignment.syid', $syid)
                    ->where('sh_sectionblockassignment.deleted', 0)
                    ->where('sh_sectionblockassignment.sectionid', $sectionid)
                    ->select('strandid')
                    ->get();


                $subjects = DB::table('subject_plot')
                    ->where('subject_plot.deleted', 0)
                    ->where('subject_plot.syid', $syid)
                    ->where('subject_plot.levelid', $levelid)
                    ->whereIn('subject_plot.strandid', collect($sectionblockass)->pluck('strandid'))
                    ->where('subject_plot.semid', $semid)
                    ->join('sh_subjects', function ($join) {
                        $join->on('subject_plot.subjid', '=', 'sh_subjects.id');
                        $join->where('sh_subjects.deleted', 0);
                    })
                    ->select(
                        'subject_plot.strandid',
                        'subjtitle as text',
                        'subjid as id',
                        DB::raw("CONCAT(sh_subjects.subjCode,' - ',sh_subjects.subjTitle) as text")
                    )
                    ->get();

                foreach ($subjects as $item) {
                    $item->subjCom = null;
                }

                $subjects = collect($subjects)->unique('id')->values();

            } else {

                $subjects = DB::table('subject_plot')
                    ->where('subject_plot.deleted', 0)
                    ->where('subject_plot.syid', $syid)
                    ->where('subject_plot.levelid', $levelid);

                if ($sectioninfo->sd_issp == 1) {
                    $subjects = $subjects->whereIn('isforsp', [0, 1]);
                } else {
                    $subjects = $subjects->where('isforsp', 0);
                }

                $subjects = $subjects->join('subjects', function ($join) {
                    $join->on('subject_plot.subjid', '=', 'subjects.id');
                    $join->where('subjects.deleted', 0);
                    $join->where('subjects.isCon', 0);
                })
                    ->select(
                        'subjdesc as text',
                        'subjid as id',
                        'subjCom',
                        DB::raw("CONCAT(subjects.subjCode,' - ',subjects.subjDesc) as text")
                    )
                    ->get();
            }

            return array(
                (object) [
                    'status' => 1,
                    'data' => $subjects
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }

    }


    public static function removesched(Request $request)
    {


        try {

            $levelid = $request->get('levelid');
            $dataid = $request->get('dataid');
            $days = $request->get('days');
            $applycom = $request->get('applycom');

            if ($levelid == 14 || $levelid == 15) {

                $getinfo = DB::table('sh_classscheddetail')
                    ->where('sh_classscheddetail.id', $dataid)
                    ->select(
                        'stime',
                        'etime',
                        'headerid',
                        'classification'
                    )
                    ->first();


                //remove sched
                DB::table('sh_classscheddetail')
                    ->where('stime', $getinfo->stime)
                    ->Where('etime', $getinfo->etime)
                    ->Where('headerid', $getinfo->headerid)
                    ->where('classification', $getinfo->classification)
                    ->whereIn('day', $days)
                    ->where('deleted', 0)
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                //check to remove header
                $schedcount = DB::table('sh_classscheddetail')
                    ->Where('headerid', $getinfo->headerid)
                    ->where('deleted', 0)
                    ->count();

                if ($schedcount == 0) {
                    DB::table('sh_classsched')
                        ->Where('id', $getinfo->headerid)
                        ->where('deleted', 0)
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }

                return array(
                    (object) [
                        'status' => 1,
                        'message' => 'Deleted Successfully!'
                    ]
                );

            } else {

                $getinfo = DB::table('classscheddetail')
                    ->where('classscheddetail.id', $dataid)
                    ->join('classsched', function ($join) {
                        $join->on('classscheddetail.headerid', '=', 'classsched.id');
                        $join->where('classsched.deleted', 0);
                    })
                    ->select(
                        'classscheddetail.stime',
                        'classscheddetail.etime',
                        'classscheddetail.classification',
                        'classscheddetail.headerid',
                        'glevelid as levelid',
                        'syid',
                        'sectionid',
                        'subjid'
                    )
                    ->first();

                //remove sched
                DB::table('classscheddetail')
                    ->where('stime', $getinfo->stime)
                    ->Where('etime', $getinfo->etime)
                    ->Where('headerid', $getinfo->headerid)
                    ->where('classification', $getinfo->classification)
                    ->whereIn('days', $days)
                    ->where('deleted', 0)
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                //check_existing schedule
                $countHeader = DB::table('classscheddetail')
                    ->where('deleted', 0)
                    ->where('headerid', $getinfo->headerid)
                    ->count();

                //remove header information if all schedule is removed
                if ($countHeader == 0) {

                    DB::table('classsched')
                        ->where('id', $getinfo->headerid)
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                    self::checkteachersubject($getinfo->levelid, $getinfo->sectionid, $getinfo->syid, $getinfo->subjid);

                }

                if ($applycom == 1) {

                    $subjinfo = DB::table('subjects')
                        ->where('id', $getinfo->subjid)
                        ->select('subjCom')
                        ->first();

                    $compsubj = DB::table('subjects')
                        ->where('subjCom', $subjinfo->subjCom)
                        ->where('id', '!=', $getinfo->subjid)
                        ->get();

                    foreach ($compsubj as $compitem) {

                        //remove sched
                        DB::table('classscheddetail')
                            ->join('classsched', function ($join) use ($getinfo, $compitem) {
                                $join->on('classscheddetail.headerid', '=', 'classsched.id');
                                $join->where('classsched.deleted', 0);
                                $join->where('classsched.syid', $getinfo->syid);
                                $join->where('classsched.glevelid', $getinfo->levelid);
                                $join->where('classsched.sectionid', $getinfo->sectionid);
                                $join->where('classsched.subjid', $compitem->id);
                            })
                            ->where('classscheddetail.stime', $getinfo->stime)
                            ->Where('classscheddetail.etime', $getinfo->etime)
                            ->where('classscheddetail.classification', $getinfo->classification)
                            ->whereIn('classscheddetail.days', $days)
                            ->where('classscheddetail.deleted', 0)
                            ->update([
                                'classscheddetail.deleted' => 1,
                                'classscheddetail.deletedby' => auth()->user()->id,
                                'classscheddetail.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);


                        //check_existing schedule
                        $countHeader = DB::table('classscheddetail')
                            ->join('classsched', function ($join) use ($getinfo, $compitem) {
                                $join->on('classscheddetail.headerid', '=', 'classsched.id');
                                $join->where('classsched.deleted', 0);
                                $join->where('classsched.syid', $getinfo->syid);
                                $join->where('classsched.glevelid', $getinfo->levelid);
                                $join->where('classsched.sectionid', $getinfo->sectionid);
                                $join->where('classsched.subjid', $compitem->id);
                            })
                            ->where('classscheddetail.stime', $getinfo->stime)
                            ->Where('classscheddetail.etime', $getinfo->etime)
                            ->where('classscheddetail.classification', $getinfo->classification)
                            ->whereIn('classscheddetail.days', $days)
                            ->where('classscheddetail.deleted', 0)
                            ->count();

                        //remove header information if all schedule is removed
                        if ($countHeader == 0) {

                            DB::table('classsched')
                                ->where('classsched.deleted', 0)
                                ->where('classsched.syid', $getinfo->syid)
                                ->where('classsched.glevelid', $getinfo->levelid)
                                ->where('classsched.sectionid', $getinfo->sectionid)
                                ->where('classsched.subjid', $compitem->id)
                                ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);

                            self::checkteachersubject($getinfo->levelid, $getinfo->sectionid, $getinfo->syid, $getinfo->subjid);

                        }



                    }

                }


                return array(
                    (object) [
                        'status' => 1,
                        'message' => 'Deleted Successfully!'
                    ]
                );


            }


        } catch (\Exception $e) {

            return self::store_error($e);

        }



    }


    public static function get_schedule_2(Request $request)
    {

        $schedtype = $request->get('schedtype');
        $roomid = $request->get('roomid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $teacherid = $request->get('teacherid');
        $subjects = [];

        // return $request->all();

        if ($schedtype == 'section') {
            $levelid = DB::table('sections')
                ->where('id', $sectionid)
                ->first();

            $subjects = DB::table('subject_plot')
                ->where('levelid', $levelid->levelid)
                ->where('syid', $syid)
                ->where('deleted', 0)
                ->select('subjid')
                ->get();
        }

        if ($schedtype == 'teacher') {

            $asssubj = DB::table('assignsubj')
                ->where('assignsubj.syid', $syid)
                ->where('assignsubj.deleted', 0)
                ->join('assignsubjdetail', function ($join) use ($teacherid) {
                    $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid');
                    $join->where('assignsubjdetail.deleted', 0);
                    $join->where('assignsubjdetail.teacherid', $teacherid);
                })
                ->select(
                    'sectionid',
                    'subjid'
                )
                ->get();

        }

        $sched = DB::table('classsched')
            ->where('syid', $syid)
            ->where('classsched.deleted', 0);

        if ($schedtype == 'section') {
            $sched = $sched->where('sectionid', $sectionid);
            $sched = $sched->whereIn('classsched.subjid', collect($subjects)->pluck('subjid'));
            ;
        }

        if ($schedtype == 'teacher') {
            $sched = $sched->where(function ($query) use ($asssubj) {
                $query->whereIn('subjid', collect($asssubj)->pluck('subjid'));
                $query->whereIn('sectionid', collect($asssubj)->pluck('sectionid'));
            });
        }

        $sched = $sched->join('classscheddetail', function ($join) use ($roomid, $schedtype) {
            $join->on('classsched.id', '=', 'classscheddetail.headerid');
            $join->where('classscheddetail.deleted', 0);
            if ($schedtype == 'room') {
                $join->where('classscheddetail.roomid', $roomid);
            }
        })
            ->leftJoin('rooms', function ($join) {
                $join->on('classscheddetail.roomid', '=', 'rooms.id');
                $join->where('rooms.deleted', 0);
            })
            ->leftJoin('days', function ($join) {
                $join->on('classscheddetail.days', '=', 'days.id');
            })
            ->leftJoin('schedclassification', function ($join) {
                $join->on('classscheddetail.classification', '=', 'schedclassification.id');
            })
            ->leftJoin('subjects', function ($join) {
                $join->on('classsched.subjid', '=', 'subjects.id');
            })
            ->leftJoin('sections', function ($join) {
                $join->on('classsched.sectionid', '=', 'sections.id');
            })
            ->select(
                'sections.levelid',
                'sectionname',
                'subjid',
                'sectionid',
                'classscheddetail.roomid',
                'classsched.id',
                'roomname',
                'stime',
                'etime',
                'days.description',
                'classscheddetail.id as detailid',
                'schedclassification.description as classification',
                'classscheddetail.roomid',
                'days.id as daysid',
                'days',
                'schedclassification.id as schedclassid',
                'headerid',
                'subjdesc'

            )
            ->get();

        $sectionlist = collect($sched)->unique('sectionid')->pluck('sectionid');

        $asssubj = DB::table('assignsubj')
            ->where('assignsubj.syid', $syid)
            ->whereIn('assignsubj.sectionid', $sectionlist)
            ->where('assignsubj.deleted', 0)
            ->join('assignsubjdetail', function ($join) {
                $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid');
                $join->where('assignsubjdetail.deleted', 0);
            })
            ->leftJoin('teacher', function ($join) {
                $join->on('assignsubjdetail.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->select(
                'subjid',
                'sectionid',
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'title',
                'teacherid',
                'tid'
            )
            ->get();

        $time_list = array();

        foreach ($sched as $sched_item) {
            $sched_item->sort = $sched_item->stime;
            $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
            $sched_item->stime = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
            $sched_item->etime = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');

            $teacher = collect($asssubj)->where('sectionid', $sched_item->sectionid)->where('subjid', $sched_item->subjid)->first();
            if (isset($teacher->lastname)) {
                $sched_item->teacher = $teacher->lastname . ', ' . $teacher->firstname;
                $sched_item->teacherid = $teacher->teacherid;
            } else {
                $sched_item->teacher = 'Not Assigned';
                $sched_item->teacherid = null;
            }
        }



        $sched_sh = DB::table('sh_classsched');

        if ($schedtype == 'section') {
            $sched_sh = $sched_sh->where('sh_classsched.sectionid', $sectionid);
        }

        if ($schedtype == 'teacher') {
            $sched_sh = $sched_sh->where('sh_classsched.teacherid', $teacherid);
        }

        $sched_sh = $sched_sh->where('sh_classsched.syid', $syid);

        if ($semid != '' && $semid != null) {
            $sched_sh = $sched_sh->where('sh_classsched.semid', $semid);

        }


        $sched_sh = $sched_sh->where('sh_classsched.deleted', 0)
            ->join('sh_classscheddetail', function ($join) use ($roomid, $schedtype) {
                $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                $join->where('sh_classscheddetail.deleted', 0);
                if ($schedtype == 'room') {
                    $join->where('sh_classscheddetail.roomid', $roomid);
                }
            })
            ->leftJoin('rooms', function ($join) {
                $join->on('sh_classscheddetail.roomid', '=', 'rooms.id');
                $join->where('rooms.deleted', 0);
            })
            ->join('days', function ($join) {
                $join->on('sh_classscheddetail.day', '=', 'days.id');
            })
            ->join('schedclassification', function ($join) {
                $join->on('sh_classscheddetail.classification', '=', 'schedclassification.id');
            })
            ->leftJoin('sh_subjects', function ($join) {
                $join->on('sh_classsched.subjid', '=', 'sh_subjects.id');
            })
            ->leftJoin('sections', function ($join) {
                $join->on('sh_classsched.sectionid', '=', 'sections.id');
            })
            ->leftJoin('teacher', function ($join) {
                $join->on('sh_classsched.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->select(
                'sh_classsched.glevelid as levelid',
                'sectionname',
                'subjid',
                'sh_classsched.sectionid',
                'day',
                'day as days',
                'sh_classscheddetail.roomid',
                'sh_classscheddetail.id as detailid',
                'sh_classsched.id',
                'roomname',
                'stime',
                'etime',
                'days.description',
                'sh_classsched.teacherid',
                'schedclassification.description as classification',
                'schedclassification.id as schedclassid',
                'headerid',
                'subjtitle as subjdesc',
                'subjid',
                'sectionid',
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'title',
                'sh_classsched.teacherid',
                'tid'
            )
            ->get();



        foreach ($sched_sh as $sched_item) {
            $sched_item->sort = $sched_item->stime;
            $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
            $sched_item->stime = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
            $sched_item->etime = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');

            if ($sched_item->teacherid != null) {
                $sched_item->teacher = $sched_item->lastname . ', ' . $sched_item->firstname;
            } else {
                $sched_item->teacher = 'Not Assigned';
            }
        }

        $sched = collect($sched)->merge($sched_sh);

        $sched = collect($sched)->sortBy('days')->values();


        $day_list = collect(DB::table('days')->get())->pluck('description');
        $sched = collect($sched)->sortBy('time')->values();
        if ($request->get('timetemp') != "" && $request->get('timetemp') != null) {
            $timetemplate = DB::table('schedtimetemplate')
                ->where('schedtimetemplate.id', $request->get('timetemp'))
                ->where('schedtimetemplate.deleted', 0)
                ->join('schedtimetemplate_detail', function ($join) {
                    $join->on('schedtimetemplate.id', '=', 'schedtimetemplate_detail.headerid');
                    $join->where('schedtimetemplate_detail.deleted', 0);
                })
                ->select(
                    'stime',
                    'etime'
                )
                ->get();
        } else {
            $timetemplate = array();

        }


        // return $timetemplate;
        $arrTemp = [];

        foreach ($timetemplate as $sched_item) {
            $sched_item->sort = $sched_item->stime;
            $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
            $sched_item->stime = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
            $sched_item->etime = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
            $arrTemp[] = $sched_item->time;
        }

        // return $timetemplate;
        // return $arrTemp;
        $sched = collect(collect($sched)->merge($timetemplate))->sortBy('sort')->values();


        // Filter the schedules based on the time ranges
        // $sched = $sched->filter(function ($item) use ($arrTemp) {
        //     // Split the item's time into start and end time
        //     [$schedStartTime, $schedEndTime] = explode(' - ', $item->time);

        //     // Iterate through $arrTemp ranges and check if the schedule fits within any range
        //     foreach ($arrTemp as $range) {
        //         // Split the range into start and end time
        //         [$rangeStart, $rangeEnd] = explode(' - ', $range);

        //         // Check if the schedule time falls within the range
        //         if (
        //             \Carbon\Carbon::createFromFormat('h:i A', $schedStartTime)->between(
        //                 \Carbon\Carbon::createFromFormat('h:i A', $rangeStart),
        //                 \Carbon\Carbon::createFromFormat('h:i A', $rangeEnd),
        //                 true
        //             )
        //             ||
        //             \Carbon\Carbon::createFromFormat('h:i A', $schedEndTime)->between(
        //                 \Carbon\Carbon::createFromFormat('h:i A', $rangeStart),
        //                 \Carbon\Carbon::createFromFormat('h:i A', $rangeEnd),
        //                 true
        //             )
        //         ) {
        //             return true; // The schedule matches the range
        //         }
        //     }
        //     return false; // No match
        // });

        // Return or use the filtered schedules
        // return $sched->values();


        $time_list = collect($sched)->unique('time')->sortBy('etime')->pluck('time');
        // return $time_list;
        $time_list = collect($time_list)->sortBy(function ($timeRange) {
            $startTime = explode(" - ", $timeRange)[0];
            return \Carbon\Carbon::createFromFormat('h:i A', $startTime)->format('H:i');
        })->values()->all();


        // dd($sched);
        return array(
            (object) [
                'sched' => $sched->values(),
                'day_list' => $day_list,
                'time_list' => $time_list,
                'time_temp' => $timetemplate
            ]
        );

    }

    public static function print_sched(Request $request)
    {

        $schedtype = $request->get('schedtype');
        $roomid = $request->get('roomid');
        $syid = $request->get('syid');
        $sectionid = $request->get('sectionid');
        $teacherid = $request->get('teacherid');


        $temp_sched = self::get_schedule_2($request);
        $sched = $temp_sched[0]->sched;
        $day_list = $temp_sched[0]->day_list;
        $time_list = $temp_sched[0]->time_list;
        $sy = DB::table('sy')
            ->where('id', $syid)
            ->select('sydesc')
            ->first();

        $schoolinfo = DB::table('schoolinfo')->first();

        $sectioninfo = DB::table('sections')
            ->where('id', $sectionid)
            ->first();

        if ($schedtype == 'room') {
            $roominfo = DB::table('rooms')
                ->where('id', $roomid)
                ->select(
                    'roomname'
                )
                ->first();
        } else {
            $roominfo = null;
        }

        if ($schedtype == 'teacher') {
            $adviser = DB::table('teacher')
                ->where('id', $teacherid)
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'title',
                    'tid'
                )
                ->first();
        } else {

            $adviser = DB::table('sectiondetail')
                ->where('sectionid', $sectionid)
                ->where('syid', $syid)
                ->where('sectiondetail.deleted', 0)
                ->leftJoin('teacher', function ($join) {
                    $join->on('sectiondetail.teacherid', '=', 'teacher.id');
                    $join->where('teacher.deleted', 0);
                })
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'title',
                    'teacherid',
                    'tid'
                )
                ->first();
        }

        $pdf = PDF::loadView('principalsportal.pages.section.schedplot_pdf', compact('day_list', 'time_list', 'sched', 'schedtype', 'sy', 'schoolinfo', 'adviser', 'sectioninfo', 'roominfo'))->setPaper('legal');
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
        return $pdf->stream();

    }
    public static function print_sched_format1(Request $request)
    {
        $syid = $request->get('syid');
        $section = $request->get('section');
        $semid = $request->get('semid');
        $strand = $request->get('strand');
        $levelid = DB::table('sections')
            ->where('id', $section)
            ->select('levelid')
            ->first();
        $temp_sched = self::get_schedule($levelid->levelid, $syid, $section, $semid, $strand, $subjid = null, $teacherid = null, $roomid = null);

        $schoolinfo = DB::table('schoolinfo')->first();

        $sy = DB::table('sy')
            ->where('id', $syid)
            ->select('sydesc')
            ->first();

        $adviser = DB::table('sectiondetail')
            ->where('sectionid', $section)
            ->where('syid', $syid)
            ->where('sectiondetail.deleted', 0)
            ->leftJoin('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'title',
                'teacherid',
                'tid'
            )
            ->first();
        $pdf = PDF::loadView('principalsportal.pages.section.schedplot_pdf2', compact('temp_sched', 'adviser', 'schoolinfo', 'sy'))->setPaper('legal');
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
        return $pdf->stream();
    }

    public static function get_schedule(
        $levelid = null,
        $syid = null,
        $sectionid = null,
        $semid = null,
        $strandid = null,
        $subjid = null,
        $teacherid = null,
        $roomid = null
    ) {

        $levelinfo = DB::table('gradelevel')
            ->where('id', $levelid)
            ->select(
                'id',
                'acadprogid'
            )
            ->first();


        $schoolinfo = DB::table('schoolinfo')->select('abbreviation')->first();

        if (strtolower($schoolinfo->abbreviation) == 'apmc' && $levelinfo->acadprogid == 5) {
            $blockassignment = DB::table('sh_sectionblockassignment')
                ->join('sh_block', function ($join) {
                    $join->on('sh_sectionblockassignment.blockid', '=', 'sh_block.id');
                    $join->where('sh_block.deleted', '0');
                })
                ->leftJoin('sh_strand', function ($join) {
                    $join->on('sh_block.strandid', '=', 'sh_strand.id');
                    $join->where('sh_strand.deleted', 0);
                })
                ->where('sh_sectionblockassignment.sectionid', $sectionid)
                ->where('sh_sectionblockassignment.deleted', '0')
                ->select(
                    'strandname',
                    'strandcode',
                    'sh_block.*',
                    'sh_sectionblockassignment.blockid'
                )
                ->get();

            $all_subjects = array();
            foreach ($blockassignment as $item) {
                $subject = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid, $semid, $item->strandid);

                foreach ($subject as $subject_item) {

                    $check = collect($all_subjects)->where('subjid', $subject_item->subjid)->values();

                    if (count($check) == 0) {
                        $subject_item->subj_strand = array(
                            (object) [
                                'strand' => $item->strandcode,
                                'strandid' => $item->strandid,
                                'plotid' => $subject_item->id
                            ]
                        );
                        array_push($all_subjects, $subject_item);

                    } else {

                        $check = collect($all_subjects)->where('subjid', $subject_item->subjid)->keys();

                        $strand_obj = (object) [
                            'strand' => $item->strandcode,
                            'strandid' => $item->strandid,
                            'plotid' => $subject_item->id
                        ];

                        array_push($all_subjects[$check[0]]->subj_strand, $strand_obj);

                    }
                }
            }

            $subject = collect($all_subjects)->sortBy('sortid')->values();

        } else {
            $isforsp = false;

            $sectioninfo = DB::table('sectiondetail')
                ->where('syid', $syid)
                ->where('sectionid', $sectionid)
                ->where('deleted', 0)
                ->first();

            if ($sectioninfo->sd_issp == 1) {
                $isforsp = true;
            }


            $subject = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, $subjid, $levelid, null, $syid, $semid, $strandid, array(), $isforsp);
            $subject = collect($subject)->sortBy('sortid')->values();
        }



        foreach ($subject as $item) {

            if ($levelinfo->acadprogid == 5) {

                $sched = DB::table('sh_classsched')
                    ->where('sh_classsched.syid', $syid)
                    ->where('sh_classsched.subjid', $item->subjid)
                    ->where('sh_classsched.sectionid', $sectionid)
                    ->where('sh_classsched.deleted', 0)
                    ->join('sh_classscheddetail', function ($join) {
                        $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                        $join->where('sh_classscheddetail.deleted', 0);
                    })
                    ->leftJoin('rooms', function ($join) {
                        $join->on('sh_classscheddetail.roomid', '=', 'rooms.id');
                        $join->where('rooms.deleted', 0);
                    })
                    ->join('days', function ($join) {
                        $join->on('sh_classscheddetail.day', '=', 'days.id');
                    })
                    ->join('schedclassification', function ($join) {
                        $join->on('sh_classscheddetail.classification', '=', 'schedclassification.id');
                    })
                    ->select(
                        'day',
                        'roomid',
                        'sh_classscheddetail.id as detailid',
                        'sh_classsched.id',
                        'roomname',
                        'stime',
                        'etime',
                        'days.description',
                        'teacherid',
                        'schedclassification.description as classification',
                        'schedclassification.id as schedclassid'
                    )
                    ->get();

                if (count($sched) == 0) {

                    $sched = DB::table('sh_blocksched')
                        ->where('sh_blocksched.syid', $syid)
                        ->where('sh_blocksched.subjid', $item->subjid)

                        ->where('sh_blocksched.deleted', 0)
                        ->join('sh_blockscheddetail', function ($join) {
                            $join->on('sh_blocksched.id', '=', 'sh_blockscheddetail.headerid');
                            $join->where('sh_blockscheddetail.deleted', 0);
                        })
                        ->leftJoin('rooms', function ($join) {
                            $join->on('sh_blockscheddetail.roomid', '=', 'rooms.id');
                            $join->where('rooms.deleted', 0);
                        })
                        ->join('days', function ($join) {
                            $join->on('sh_blockscheddetail.day', '=', 'days.id');
                        })
                        ->join('sh_block', function ($join) use ($strandid) {
                            $join->on('sh_blocksched.blockid', '=', 'sh_block.id');
                            $join->where('sh_block.deleted', 0);
                            $join->where('sh_block.strandid', $strandid);
                        })
                        ->join('sh_sectionblockassignment', function ($join) use ($sectionid) {
                            $join->on('sh_block.id', '=', 'sh_sectionblockassignment.blockid');
                            $join->where('sh_sectionblockassignment.deleted', 0);
                            $join->where('sh_sectionblockassignment.sectionid', $sectionid);
                        })
                        ->join('schedclassification', function ($join) {
                            $join->on('sh_blockscheddetail.classification', '=', 'schedclassification.id');
                        })
                        ->select(
                            'day',
                            'sh_blockscheddetail.id as detailid',
                            'roomid',
                            'sh_blocksched.id',
                            'roomname',
                            'stime',
                            'etime',
                            'teacherid',
                            'days.description',
                            'schedclassification.description as classification',
                            'schedclassification.id as schedclassid'
                        )
                        ->get();

                }

                if (count($sched) > 1) {
                    $sched = DB::table('sh_classsched')
                        ->where('sh_classsched.syid', $syid)
                        ->where('sh_classsched.semid', $semid)
                        ->where('sh_classsched.subjid', $item->subjid)
                        ->where('sh_classsched.sectionid', $sectionid)
                        ->where('sh_classsched.deleted', 0)
                        ->join('sh_classscheddetail', function ($join) {
                            $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                            $join->where('sh_classscheddetail.deleted', 0);
                        })
                        ->leftJoin('rooms', function ($join) {
                            $join->on('sh_classscheddetail.roomid', '=', 'rooms.id');
                            $join->where('rooms.deleted', 0);
                        })
                        ->join('days', function ($join) {
                            $join->on('sh_classscheddetail.day', '=', 'days.id');
                        })
                        ->join('schedclassification', function ($join) {
                            $join->on('sh_classscheddetail.classification', '=', 'schedclassification.id');
                        })
                        ->select(
                            'day',
                            'roomid',
                            'sh_classscheddetail.id as detailid',
                            'sh_classsched.id',
                            'roomname',
                            'stime',
                            'etime',
                            'days.description',
                            'teacherid',
                            'schedclassification.description as classification',
                            'schedclassification.id as schedclassid'
                        )
                        ->get();


                }

                $teacher = null;
                $tid = null;
                $teacherid = null;

                if (isset($sched[0]->teacherid)) {

                    $temp_teacher = DB::table('teacher')
                        ->where('id', $sched[0]->teacherid)
                        ->first();

                    if (isset($temp_teacher->firstname)) {
                        $teacher = $temp_teacher->firstname . ' ' . $temp_teacher->middlename . ' ' . $temp_teacher->lastname;
                        $tid = $temp_teacher->tid;
                        $teacherid = $temp_teacher->id;
                    }

                }


                foreach ($sched as $sched_item) {
                    $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                }


                $starting = collect($sched)->groupBy('time');

                $sched_list = array();
                $sched_count = 1;

                foreach ($starting as $sched_item) {

                    $byclassification = collect($sched_item)->groupBy('schedclassid');

                    foreach ($byclassification as $byclassification_item) {


                        $dayString = '';
                        $days = array();

                        foreach ($byclassification_item as $new_item) {
                            $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                            $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                            $dayString .= substr($new_item->description, 0, 3) . ' / ';
                            $detailid = $new_item->detailid;
                            $roomname = $new_item->roomname;
                            $roomid = $new_item->roomid;
                            $classification = $new_item->classification;
                            $schedclassid = $new_item->schedclassid;
                            $time = $new_item->time;
                            array_push($days, $new_item->day);
                        }

                        $dayString = substr($dayString, 0, -2);

                        array_push($sched_list, (object) [
                            'day' => $dayString,
                            'start' => $start,
                            'end' => $end,
                            'roomid',
                            'detailid' => $detailid,
                            'roomname' => $roomname,
                            'roomid' => $roomid,
                            'classification' => $classification,
                            'teacher' => $teacher,
                            'tid' => $tid,
                            'teacherid' => $teacherid,
                            'sched_count' => $sched_count,
                            'time' => $time,
                            'days' => $days,
                            'schedclassid' => $schedclassid,
                            'sort' => \Carbon\Carbon::create($start)->isoFormat('HH'),
                        ]);


                        $sched_count += 1;

                    }
                }

                $item->datatype = 'seniorhigh';
                $item->schedule = $sched_list;

            } else {



                $sched = DB::table('classsched')
                    ->where('classsched.syid', $syid)
                    ->where('classsched.subjid', $item->subjid)
                    ->where('classsched.sectionid', $sectionid)
                    ->where('classsched.deleted', 0)
                    ->leftJoin('classscheddetail', function ($join) {
                        $join->on('classsched.id', '=', 'classscheddetail.headerid');
                        $join->where('classscheddetail.deleted', 0);
                    })
                    ->leftJoin('rooms', function ($join) {
                        $join->on('classscheddetail.roomid', '=', 'rooms.id');
                        $join->where('rooms.deleted', 0);
                    })
                    ->leftJoin('days', function ($join) {
                        $join->on('classscheddetail.days', '=', 'days.id');
                    })
                    ->leftJoin('schedclassification', function ($join) {
                        $join->on('classscheddetail.classification', '=', 'schedclassification.id');
                    })
                    ->select(
                        'roomid',
                        'classsched.id',
                        'roomname',
                        'stime',
                        'etime',
                        'days.description',
                        'classscheddetail.id as detailid',
                        'schedclassification.description as classification',
                        'roomid',
                        'days',
                        'schedclassification.id as schedclassid'
                    )
                    ->get();

                $temp_subj = $item->subjid;

                $asssubj = DB::table('assignsubj')
                    ->where('assignsubj.syid', $syid)
                    ->where('assignsubj.sectionid', $sectionid)
                    ->where('assignsubj.deleted', 0)
                    ->join('assignsubjdetail', function ($join) use ($temp_subj) {
                        $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.deleted', 0);
                        $join->where('assignsubjdetail.subjid', $temp_subj);
                    })
                    ->leftJoin('teacher', function ($join) {
                        $join->on('assignsubjdetail.teacherid', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                    })
                    ->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'title',
                        'teacherid',
                        'tid'
                    )
                    ->first();

                $temp_teacher = null;

                if (!isset($asssubj->tid)) {
                    $asssubj = (object) [
                        'lastname' => null,
                        'firstname' => null,
                        'middlename' => null,
                        'suffix' => null,
                        'title' => null,
                        'teacherid' => null,
                        'tid' => null
                    ];

                }

                $temp_sched = array();

                foreach ($sched as $sched_item) {
                    try {
                        $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                        array_push($temp_sched, $sched_item);
                    } catch (\Exception $e) {

                    }
                }

                $sched = $temp_sched;

                $starting = collect($sched)->groupBy('time');
                $sched_list = array();
                $sched_count = 1;

                foreach ($starting as $sched_item) {


                    //group by classification

                    $byclassification = collect($sched_item)->groupBy('schedclassid');

                    foreach ($byclassification as $byclassification_item) {

                        $dayString = '';
                        $days = array();

                        foreach ($byclassification_item as $new_item) {
                            $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                            $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                            $dayString .= substr($new_item->description, 0, 3) . ' / ';
                            $detailid = $new_item->detailid;
                            $roomname = $new_item->roomname;
                            $roomid = $new_item->roomid;
                            $classification = $new_item->classification;
                            $schedclassid = $new_item->schedclassid;
                            $time = $new_item->time;
                            array_push($days, $new_item->days);
                        }

                        $dayString = substr($dayString, 0, -2);

                        array_push($sched_list, (object) [
                            'day' => $dayString,
                            'start' => $start,
                            'end' => $end,
                            'roomid',
                            'detailid' => $detailid,
                            'roomname' => $roomname,
                            'roomid' => $roomid,
                            'classification' => $classification,
                            'teacher' => $asssubj->firstname . ' ' . $asssubj->middlename . ' ' . $asssubj->lastname,
                            'tid' => $asssubj->tid,
                            'schedclassid' => $schedclassid,
                            'teacherid' => $asssubj->teacherid,
                            'sched_count' => $sched_count,
                            'sort' => \Carbon\Carbon::create($start)->isoFormat('HH'),
                            'time' => $time,
                            'days' => $days
                        ]);

                        $sched_count += 1;

                    }

                }
                $item->datatype = 'juniorhigh';
                $item->schedule = $sched_list;

            }


        }

        return collect($subject)->sortBy('sortid')->values();

    }

    public static function get_schedule_ajax(Request $request)
    {

        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $sectionid = $request->get('sectionid');
        $semid = $request->get('semid');
        $strandid = $request->get('strandid');
        $subjid = $request->get('subjid');
        $teacherid = $request->get('teacherid');

        $schedule = self::get_schedule($levelid, $syid, $sectionid, $semid, $strandid, $subjid, $teacherid);
        return $schedule;

    }


    public static function sched_plot(Request $request)
    {
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $sectionid = $request->get('sectionid');
        $semid = $request->get('semid');
        $strandid = $request->get('strandid');
        $subjid = $request->get('subjid');
        $isactive = DB::table('sy')->where('id', $syid)->select('isactive')->first();

        $schedule = self::get_schedule($levelid, $syid, $sectionid, $semid, $strandid, $subjid);

        $dayOrder = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

        foreach ($schedule as $item) {
            foreach ($item->schedule as $sched) {
                // Split the string into an array by the delimiter " / "
                $daysArray = array_unique(array_map('trim', explode(' / ', $sched->day)));

                // Sort the days based on the custom dayOrder
                usort($daysArray, function ($a, $b) use ($dayOrder) {
                    return array_search($a, $dayOrder) - array_search($b, $dayOrder);
                });

                // Join the unique and sorted values back into a string with " / " as the delimiter
                $sortedDayString = implode(' / ', $daysArray);
                $sched->day = $sortedDayString;
            }
        }

        // dd($schedule);

        return view('principalsportal.pages.section.schedplot')
            ->with('schedule', $schedule)
            ->with('isactive', $isactive);
    }


    public static function conflict_info_gshs($schedheader, $scheditem, $day, $conflicttype)
    {

        $headerinfo = collect($schedheader)->where('id', $scheditem->headerid)->first();

        $sectinfo = self::get_sectioninfo($headerinfo->sectionid);

        if ($scheditem->levelid == 14 || $scheditem->levelid == 15) {
            $subjinfo = self::get_subjinfo_shs($headerinfo->subjid);
        } else {
            $subjinfo = self::get_subjinfo_gshs($headerinfo->subjid);
        }

        return array(
            (object) [
                'conflicttype' => $conflicttype,
                'data' => 'conflict',
                'status' => 0,
                'time' => $scheditem->time,
                'section' => $sectinfo->sectionname,
                'subjcode' => $subjinfo->subjcode,
                'subjdesc' => $subjinfo->subjdesc,
                'day' => $day
            ]
        );
    }

    public static function get_sectioninfo($sectionid)
    {
        $sectioninfo = DB::table('sections')
            ->where('id', $sectionid)
            ->select(
                'sectionname'
            )
            ->first();

        return $sectioninfo;
    }

    public static function get_subjinfo_gshs($subjid)
    {

        $subjinfo = DB::table('subjects')
            ->where('id', $subjid)
            ->select(
                'subjcode',
                'subjdesc'
            )
            ->first();

        return $subjinfo;
    }

    public static function get_roominfo($roomid)
    {

        $subjinfo = DB::table('rooms')
            ->where('id', $roomid)
            ->select(
                'roomname'
            )
            ->first();

        return $subjinfo;
    }

    public static function get_subjinfo_shs($subjid)
    {

        $subjinfo = DB::table('sh_subjects')
            ->where('id', $subjid)
            ->select(
                'subjcode',
                'subjtitle as subjdesc'
            )
            ->first();

        return $subjinfo;
    }

    public static function checkteachersubject($levelid = null, $sectionid = null, $syid = null, $subjid = null)
    {

        $check_classched = DB::table('assignsubj')
            ->where('assignsubj.glevelid', $levelid)
            ->where('assignsubj.sectionid', $sectionid)
            ->where('assignsubj.syid', $syid)
            ->where('assignsubj.deleted', 0)
            ->join('assignsubjdetail', function ($join) use ($subjid) {
                $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid');
                $join->where('assignsubjdetail.deleted', 0);
                $join->where('subjid', $subjid);
            })
            ->update([
                'assignsubjdetail.deleted' => 1,
                'assignsubjdetail.deletedby' => auth()->user()->id,
                'assignsubjdetail.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);


        //check if details is available
        $check_detail = DB::table('assignsubj')
            ->where('assignsubj.glevelid', $levelid)
            ->where('assignsubj.sectionid', $sectionid)
            ->where('assignsubj.syid', $syid)
            ->where('assignsubj.deleted', 0)
            ->join('assignsubjdetail', function ($join) {
                $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid');
                $join->where('assignsubjdetail.deleted', 0);
            })
            ->count();

        if ($check_detail == 0) {
            $check_classched = DB::table('assignsubj')
                ->where('assignsubj.glevelid', $levelid)
                ->where('assignsubj.sectionid', $sectionid)
                ->where('assignsubj.syid', $syid)
                ->where('assignsubj.deleted', 0)
                ->update([
                    'assignsubj.deleted' => 1,
                    'assignsubj.deletedby' => auth()->user()->id,
                    'assignsubj.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        }

    }

    public static function assignteachersubject($levelid = null, $sectionid = null, $syid = null, $subjid = null, $teacherid = null)
    {


        $check_classched = DB::table('assignsubj')
            ->where('glevelid', $levelid)
            ->where('sectionid', $sectionid)
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->get();

        if (count($check_classched) == 0) {

            $headerid = DB::table('assignsubj')
                ->insertGetId([
                    'glevelid' => $levelid,
                    'sectionid' => $sectionid,
                    'syid' => $syid,
                    'deleted' => '0',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

        } else {
            if (isset($check_classched[0]->ID)) {
                $headerid = $check_classched[0]->ID;
            } else {
                $headerid = $check_classched[0]->id;
            }
        }

        $check_detail = DB::table('assignsubjdetail')
            ->where('headerid', $headerid)
            ->where('subjid', $subjid)
            ->where('deleted', 0)
            ->get();

        if (count($check_detail) == 0) {
            DB::table('assignsubjdetail')->insert([
                'headerid' => $headerid,
                'subjid' => $subjid,
                'teacherid' => $teacherid,
                'deleted' => '0',
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
        } else {
            DB::table('assignsubjdetail')
                ->where('headerid', $headerid)
                ->where('subjid', $subjid)
                ->where('deleted', 0)
                ->take(1)
                ->update([
                    'teacherid' => $teacherid,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        }

    }

    public static function gshs_insert_sched(Request $request)
    {

        $sectionid = $request->get('section');
        $subjid = $request->get('s');
        $teacherid = $request->get('tea');
        $roomid = $request->get('r');
        $class = $request->get('class');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $time = explode(" - ", $request->get('t'));
        $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
        $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');
        $levelid = DB::table('sections')->where('id', $sectionid)->select('levelid')->first()->levelid;
        $days = $request->get('days');
        $allowconflict = $request->get('allowconflict');
        $applycom = $request->get('applycom');
        $conflict_list = array();


        if ($allowconflict == 0) {

            if ($teacherid != '') {

                $teachersubj = DB::table('assignsubj')
                    ->where('syid', $syid)
                    ->join('assignsubjdetail', function ($join) use ($teacherid) {
                        $join->on('assignsubj.ID', '=', 'assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.deleted', 0);
                        $join->where('assignsubjdetail.teacherid', $teacherid);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select('subjid', 'sectionid')
                    ->where('assignsubj.deleted', 0)
                    ->get();

                $teachersched = DB::table('classsched')
                    ->where('syid', $syid)
                    ->whereIn('sectionid', collect($teachersubj)->pluck('sectionid'))
                    ->whereIn('subjid', collect($teachersubj)->pluck('subjid'))
                    ->where('classsched.deleted', 0)
                    ->join('classscheddetail', function ($join) {
                        $join->on('classsched.id', '=', 'classscheddetail.headerid');
                        $join->where('classscheddetail.deleted', 0);
                    })
                    ->select(
                        'roomid',
                        'classsched.glevelid as levelid',
                        'classsched.id',
                        'classsched.sectionid',
                        'classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'days as day',
                        DB::raw("CONCAT(classscheddetail.stime,' - ',classscheddetail.etime) as time")
                    )
                    ->get();

                $teachersched_sh = DB::table('sh_classsched')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('teacherid', $teacherid)
                    ->where('sh_classsched.deleted', 0)
                    ->join('sh_classscheddetail', function ($join) {
                        $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                        $join->where('sh_classscheddetail.deleted', 0);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'sh_classsched.glevelid as levelid',
                        'sh_classsched.id',
                        'sh_classsched.sectionid',
                        'sh_classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(sh_classscheddetail.stime,' - ',sh_classscheddetail.etime) as time")
                    )
                    ->get();

                $teachersched = $teachersched->merge($teachersched_sh);

            } else {
                $teachersched = [];
            }

            if ($roomid != '') {

                $roomsched = DB::table('classsched')
                    ->where('syid', $syid)
                    ->where('classsched.deleted', 0)
                    ->join('classscheddetail', function ($join) use ($roomid) {
                        $join->on('classsched.id', '=', 'classscheddetail.headerid');
                        $join->where('classscheddetail.deleted', 0);
                        $join->where('classscheddetail.roomid', $roomid);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'classsched.glevelid as levelid',
                        'classsched.id',
                        'classsched.sectionid',
                        'classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'days as day',
                        DB::raw("CONCAT(classscheddetail.stime,' - ',classscheddetail.etime) as time")
                    )
                    ->get();

                $roomsched_sh = DB::table('sh_classsched')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('sh_classsched.deleted', 0)
                    ->join('sh_classscheddetail', function ($join) use ($roomid) {
                        $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                        $join->where('sh_classscheddetail.deleted', 0);
                        $join->where('sh_classscheddetail.roomid', $roomid);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'sh_classsched.glevelid as levelid',
                        'sh_classsched.id',
                        'sh_classsched.sectionid',
                        'sh_classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(sh_classscheddetail.stime,' - ',sh_classscheddetail.etime) as time")
                    )
                    ->get();

                $roomsched = $roomsched->merge($roomsched_sh);



            } else {
                $roomsched = [];
            }


            if ($sectionid != '') {

                $sectionsched = DB::table('classsched')
                    ->where('syid', $syid)
                    ->where('sectionid', $sectionid)
                    ->where('classsched.deleted', 0)
                    ->join('classscheddetail', function ($join) use ($sectionid) {
                        $join->on('classsched.id', '=', 'classscheddetail.headerid');
                        $join->where('classscheddetail.deleted', 0);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'classsched.glevelid as levelid',
                        'classsched.id',
                        'classsched.sectionid',
                        'classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'days as day',
                        DB::raw("CONCAT(classscheddetail.stime,' - ',classscheddetail.etime) as time")
                    )
                    ->get();


                $sectionsched_sh = DB::table('sh_classsched')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('sectionid', $sectionid)
                    ->where('sh_classsched.deleted', 0)
                    ->join('sh_classscheddetail', function ($join) {
                        $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                        $join->where('sh_classscheddetail.deleted', 0);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'sh_classsched.glevelid as levelid',
                        'sh_classsched.id',
                        'sh_classsched.sectionid',
                        'sh_classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(sh_classscheddetail.stime,' - ',sh_classscheddetail.etime) as time")
                    )
                    ->get();

                $sectionsched = $sectionsched->merge($sectionsched_sh);

            } else {
                $sectionsched = [];
            }

            if ($sectionid != '') {

                $sectionsched = DB::table('classsched')
                    ->where('syid', $syid)
                    ->where('sectionid', $sectionid)
                    ->where('classsched.deleted', 0)
                    ->join('classscheddetail', function ($join) use ($sectionid) {
                        $join->on('classsched.id', '=', 'classscheddetail.headerid');
                        $join->where('classscheddetail.deleted', 0);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'classsched.glevelid as levelid',
                        'classsched.id',
                        'classsched.sectionid',
                        'classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'days as day',
                        DB::raw("CONCAT(classscheddetail.stime,' - ',classscheddetail.etime) as time")
                    )
                    ->get();


                $sectionsched_sh = DB::table('sh_classsched')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('sectionid', $sectionid)
                    ->where('sh_classsched.deleted', 0)
                    ->join('sh_classscheddetail', function ($join) {
                        $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                        $join->where('sh_classscheddetail.deleted', 0);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'sh_classsched.glevelid as levelid',
                        'sh_classsched.id',
                        'sh_classsched.sectionid',
                        'sh_classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(sh_classscheddetail.stime,' - ',sh_classscheddetail.etime) as time")
                    )
                    ->get();

                $sectionsched = $sectionsched->merge($sectionsched_sh);

            } else {
                $sectionsched = [];
            }


            $conflict_count = 0;
            $conflict_list = array();

            foreach ($days as $item) {

                $day = '';
                if ($item == 1) {
                    $day = 'Mon';
                } else if ($item == 2) {
                    $day = 'Tue';
                } else if ($item == 3) {
                    $day = 'Wed';
                } else if ($item == 4) {
                    $day = 'Thu';
                } else if ($item == 5) {
                    $day = 'Fri';
                } else if ($item == 6) {
                    $day = 'Sat';
                } else if ($item == 7) {
                    $day = 'Sun';
                }

                // //collect day sched
                $temp_day_sched = collect($teachersched)->where('day', $item)->values();

                //check teacher conflict
                foreach ($temp_day_sched as $sched_item) {
                    $sched_stime = $sched_item->stime;
                    $sched_etime = $sched_item->etime;
                    if ($stime >= $sched_stime && $stime <= $sched_etime) {
                        if ($stime != $sched_etime) {
                            $sched_item->conflict = "TSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                        if ($etime != $sched_stime) {
                            $sched_item->conflict = "TSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                        $sched_item->conflict = "TSC";
                        array_push($conflict_list, $sched_item);
                    }
                }

                //collect day sched
                $temp_day_sched = collect($sectionsched)->where('day', $item)->values();

                //check section conflict
                foreach ($temp_day_sched as $sched_item) {
                    $sched_stime = $sched_item->stime;
                    $sched_etime = $sched_item->etime;
                    if ($stime >= $sched_stime && $stime <= $sched_etime) {
                        if ($stime != $sched_etime) {
                            $sched_item->conflict = "SSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                        if ($etime != $sched_stime) {
                            $sched_item->conflict = "SSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                        $sched_item->conflict = "SSC";
                        array_push($conflict_list, $sched_item);
                    }
                }


                //collect day sched
                $temp_day_sched = collect($roomsched)->where('day', $item)->values();

                //check room conflict
                foreach ($temp_day_sched as $sched_item) {
                    $sched_stime = $sched_item->stime;
                    $sched_etime = $sched_item->etime;
                    if ($stime >= $sched_stime && $stime <= $sched_etime) {
                        if ($stime != $sched_etime) {
                            $sched_item->conflict = "RSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                        if ($etime != $sched_stime) {
                            $sched_item->conflict = "RSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                        $sched_item->conflict = "RSC";
                        array_push($conflict_list, $sched_item);
                    }
                }




            }

        }



        foreach ($conflict_list as $item) {
            if ($item->day == 1) {
                $item->description = 'M';
            } else if ($item->day == 2) {
                $item->description = 'T';
            } else if ($item->day == 3) {
                $item->description = 'W';
            } else if ($item->day == 4) {
                $item->description = 'Th';
            } else if ($item->day == 5) {
                $item->description = 'F';
            } else if ($item->day == 6) {
                $item->description = 'S';
            } else if ($item->day == 7) {
                $item->description = 'Sun';
            }
        }

        $grouped_conflict_list = collect($conflict_list)->groupBy('conflict');
        $conflict_info = array();




        // return $grouped_conflict_list;

        foreach ($grouped_conflict_list as $key => $item) {

            $header_groups = collect($item)->groupBy('headerid');

            // return $header_groups;
            foreach ($header_groups as $header_group) {
                $dayString = '';
                $temp_info = $header_group[0];
                foreach ($header_group as $header_group_item) {
                    $dayString .= $header_group_item->description;
                }
                $sectioninfo = self::get_sectioninfo($temp_info->sectionid);

                if ($temp_info->roomid != null) {
                    $roominfo = self::get_roominfo($temp_info->roomid);
                } else {
                    $roominfo = (object) [
                        'roomname' => null
                    ];
                }

                if ($temp_info->levelid == 14 || $temp_info->levelid == 15) {
                    $subjinfo = self::get_subjinfo_shs($temp_info->subjid);
                } else {
                    $subjinfo = self::get_subjinfo_gshs($temp_info->subjid);
                }


                array_push($conflict_info, (object) [
                    'subject' => $subjinfo->subjdesc,
                    'section' => $sectioninfo->sectionname,
                    'room' => $roominfo->roomname,
                    'days' => $dayString,
                    'conflict' => $temp_info->conflict
                ]);
            }

        }

        if (count($conflict_info) > 0) {

            return array(
                (object) [
                    'status' => 0,
                    'data' => 'conflict',
                    'details' => $conflict_info
                ]
            );
        }

        $check_classched = DB::table('classsched')
            ->where('syid', $syid)
            ->where('sectionid', $sectionid)
            ->where('subjid', $subjid)
            ->where('deleted', 0)
            ->get();

        if (count($check_classched) == 0) {
            $headerid = DB::table('classsched')
                ->insertGetId([
                    'glevelid' => $levelid,
                    'sectionid' => $sectionid,
                    'subjid' => $subjid,
                    'syid' => $syid,
                    'deleted' => '0',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

        } else {
            $headerid = $check_classched[0]->id;
        }

        $allSched = DB::table('classscheddetail')
            ->where('headerid', $headerid)
            ->where('deleted', 0)
            ->get();

        foreach ($allSched as $sched) {
            if (!in_array($sched->days, $days)) {
                DB::table('classscheddetail')
                    ->where('id', $sched->id)
                    ->update([
                        'deleted' => 1
                    ]);
            }
        }


        foreach ($days as $item) {

            if ($request->get('iscreate') == 'false') {


                $schedinfo = $request->get('schedinfo') ?? null;

                if ($schedinfo !== null && $subjid != $schedinfo[0]['subjid']) {
                    //if subject is updated
                    //insert new sched
                    DB::table('classscheddetail')
                        ->insert([
                            'headerid' => $headerid,
                            'days' => $item,
                            'stime' => $stime,
                            'etime' => $etime,
                            'roomid' => $roomid,
                            'deleted' => '0',
                            'createdby' => auth()->user()->id,
                            'classification' => $class,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                    //remove the previous subject sched
                    DB::table('classscheddetail')
                        ->where('headerid', $schedinfo[0]['headerid'])
                        ->where('classification', $schedinfo[0]['schedclassid'])
                        ->where('deleted', 0)
                        ->where('days', $item)
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);


                } else {
                    //if other information is updated
                    // dd($request->all());
                    $check_if_exist = DB::table('classscheddetail')
                        ->where('headerid', $headerid)
                        ->where('classification', $schedinfo[0]['schedclassid'])
                        ->where('deleted', 0)
                        ->where('days', $item)
                        ->count();

                    if ($check_if_exist == 0) {

                        DB::table('classscheddetail')
                            ->insert([
                                'headerid' => $headerid,
                                'days' => $item,
                                'stime' => $stime,
                                'etime' => $etime,
                                'roomid' => $roomid,
                                'deleted' => '0',
                                'createdby' => auth()->user()->id,
                                'classification' => $class,
                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);

                    } else {

                        DB::table('classscheddetail')
                            ->where('headerid', $headerid)
                            ->where('classification', $schedinfo[0]['schedclassid'])
                            ->where('deleted', 0)
                            ->where('days', $item)
                            ->take(1)
                            ->update([
                                'stime' => $stime,
                                'etime' => $etime,
                                'roomid' => $roomid,
                                'classification' => $class,
                                'classification' => $class,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);
                        // dd($request->all());
                    }
                }

            } else {

                $check_detail = Db::table('classscheddetail')
                    ->where('headerid', $headerid)
                    ->where('days', $item)
                    ->where('stime', $stime)
                    ->where('etime', $etime)
                    ->where('classification', $class)
                    ->where('deleted', 0)
                    ->get();

                if (count($check_detail) > 0) {
                    DB::table('classscheddetail')
                        ->where('id', $check_detail[0]->id)
                        ->where('deleted', 0)
                        ->take(1)
                        ->update([
                            'roomid' => $roomid,
                            'classification' => $class,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                } else {
                    DB::table('classscheddetail')
                        ->insert([
                            'headerid' => $headerid,
                            'days' => $item,
                            'stime' => $stime,
                            'etime' => $etime,
                            'roomid' => $roomid,
                            'deleted' => '0',
                            'createdby' => auth()->user()->id,
                            'classification' => $class,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }
            }
        }

        // Db::table('classscheddetail')
        //     ->where('headerid',$headerid)
        //     ->whereNotIn('days',$days)
        //     ->where('stime',$stime)
        //     ->where('etime',$etime)
        //     ->where('deleted',0)
        //     ->where('classification',$class)
        //     ->update([
        //         'deleted'=>'1',
        //         'deletedby'=>auth()->user()->id,
        //         'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        //     ]);


        if ($teacherid != null) {
            self::assignteachersubject($levelid, $sectionid, $syid, $subjid, $teacherid);
        } else {
            self::checkteachersubject($levelid, $sectionid, $syid, $subjid);
        }


        //apply to component subjects
        if ($applycom == 1) {

            $subjinfo = DB::table('subjects')
                ->where('id', $subjid)
                ->select('subjCom')
                ->first();

            $compsubj = DB::table('subjects')
                ->where('subjCom', $subjinfo->subjCom)
                ->where('id', '!=', $subjid)
                ->get();

            foreach ($compsubj as $compitem) {

                $check_classched = DB::table('classsched')
                    ->where('syid', $syid)
                    ->where('sectionid', $sectionid)
                    ->where('subjid', $compitem->id)
                    ->where('deleted', 0)
                    ->get();

                if (count($check_classched) == 0) {
                    $headerid = DB::table('classsched')
                        ->insertGetId([
                            'glevelid' => $levelid,
                            'sectionid' => $sectionid,
                            'subjid' => $compitem->id,
                            'syid' => $syid,
                            'deleted' => '0',
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                } else {
                    $headerid = $check_classched[0]->id;
                }

                foreach ($days as $item) {
                    // $check_detail = Db::table('classscheddetail')
                    //         ->where('headerid',$headerid)
                    //         ->where('days',$item)
                    //         ->where('stime',$stime)
                    //         ->where('etime',$etime)
                    //         ->where('deleted',0)
                    //         ->get();

                    // if(count($check_detail) > 0){
                    //     DB::table('classscheddetail')
                    //         ->where('id',$check_detail[0]->id)
                    //         ->where('deleted',0)
                    //         ->take(1)
                    //         ->update([
                    //             'roomid'=>$roomid,
                    //             'classification'=>$class,
                    //             'updatedby'=>auth()->user()->id,
                    //             'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    //         ]);
                    // }else{
                    //     DB::table('classscheddetail')
                    //         ->insert([
                    //             'headerid'=> $headerid,
                    //             'days'=>$item,
                    //             'stime'=>$stime,
                    //             'etime'=>$etime,
                    //             'roomid'=>$roomid,
                    //             'deleted'=>'0',
                    //             'createdby'=>auth()->user()->id,
                    //             'classification'=>$class,
                    //             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    //         ]);
                    // }
                    if ($request->get('iscreate') == 'false') {

                        $schedinfo = $request->get('schedinfo');

                        if ($subjid != $schedinfo[0]['subjid']) {
                            //if subject is updated
                            //insert new sched
                            DB::table('classscheddetail')
                                ->insert([
                                    'headerid' => $headerid,
                                    'days' => $item,
                                    'stime' => $stime,
                                    'etime' => $etime,
                                    'roomid' => $roomid,
                                    'deleted' => '0',
                                    'createdby' => auth()->user()->id,
                                    'classification' => $class,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);

                            //remove the previous subject sched
                            DB::table('classscheddetail')
                                ->where('headerid', $schedinfo[0]['headerid'])
                                ->where('classification', $schedinfo[0]['schedclassid'])
                                ->where('deleted', 0)
                                ->where('days', $item)
                                ->update([
                                    'deleted' => 1,
                                    'deletedby' => auth()->user()->id,
                                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);


                        } else {
                            //if other information is updated

                            $check_if_exist = DB::table('classscheddetail')
                                ->where('headerid', $headerid)
                                ->where('classification', $schedinfo[0]['schedclassid'])
                                ->where('deleted', 0)
                                ->where('days', $item)
                                ->count();

                            if ($check_if_exist == 0) {

                                DB::table('classscheddetail')
                                    ->insert([
                                        'headerid' => $headerid,
                                        'days' => $item,
                                        'stime' => $stime,
                                        'etime' => $etime,
                                        'roomid' => $roomid,
                                        'deleted' => '0',
                                        'createdby' => auth()->user()->id,
                                        'classification' => $class,
                                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                            } else {

                                DB::table('classscheddetail')
                                    ->where('headerid', $headerid)
                                    ->where('classification', $schedinfo[0]['schedclassid'])
                                    ->where('deleted', 0)
                                    ->where('days', $item)
                                    ->take(1)
                                    ->update([
                                        'stime' => $stime,
                                        'etime' => $etime,
                                        'roomid' => $roomid,
                                        'classification' => $class,
                                        'classification' => $class,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                            }
                        }
                    } else {

                        $check_detail = Db::table('classscheddetail')
                            ->where('headerid', $headerid)
                            ->where('days', $item)
                            ->where('stime', $stime)
                            ->where('etime', $etime)
                            ->where('classification', $class)
                            ->where('deleted', 0)
                            ->get();

                        if (count($check_detail) > 0) {
                            DB::table('classscheddetail')
                                ->where('id', $check_detail[0]->id)
                                ->where('deleted', 0)
                                ->take(1)
                                ->update([
                                    'roomid' => $roomid,
                                    'classification' => $class,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                        } else {
                            DB::table('classscheddetail')
                                ->insert([
                                    'headerid' => $headerid,
                                    'days' => $item,
                                    'stime' => $stime,
                                    'etime' => $etime,
                                    'roomid' => $roomid,
                                    'deleted' => '0',
                                    'createdby' => auth()->user()->id,
                                    'classification' => $class,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                        }
                    }
                }

                // Db::table('classscheddetail')
                //     ->where('headerid',$headerid)
                //     ->whereNotIn('days',$days)
                //     ->where('stime',$stime)
                //     ->where('etime',$etime)
                //     ->where('deleted',0)
                //     ->where('classification',$class)
                //     ->update([
                //         'deleted'=>'1',
                //         'deletedby'=>auth()->user()->id,
                //         'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                //     ]);

                if ($teacherid != null) {
                    self::assignteachersubject($levelid, $sectionid, $syid, $compitem->id, $teacherid);
                } else {
                    self::checkteachersubject($levelid, $sectionid, $syid, $subjid);
                }





            }

        }


        return array(
            (object) [
                'status' => 1,
                'data' => 'success'
            ]
        );

    }

    public static function sh_insert_sched(Request $request)
    {

        $sectionid = $request->get('section');
        $subjid = $request->get('s');
        $teacherid = $request->get('tea');
        $roomid = $request->get('r');
        $class = $request->get('class');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $time = explode(" - ", $request->get('t'));
        $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
        $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');
        $levelid = DB::table('sections')->where('id', $sectionid)->select('levelid')->first()->levelid;
        $days = $request->get('days');
        $allowconflict = $request->get('allowconflict');
        $conflict_list = array();

        if ($allowconflict == 0) {


            if ($teacherid != '') {

                $teachersubj = DB::table('assignsubj')
                    ->where('syid', $syid)
                    ->join('assignsubjdetail', function ($join) use ($teacherid) {
                        $join->on('assignsubj.ID', '=', 'assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.deleted', 0);
                        $join->where('assignsubjdetail.teacherid', $teacherid);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select('subjid', 'sectionid')
                    ->where('assignsubj.deleted', 0)
                    ->get();

                $teachersched = DB::table('classsched')
                    ->where('syid', $syid)
                    ->whereIn('sectionid', collect($teachersubj)->pluck('sectionid'))
                    ->whereIn('subjid', collect($teachersubj)->pluck('subjid'))
                    ->where('classsched.deleted', 0)
                    ->join('classscheddetail', function ($join) {
                        $join->on('classsched.id', '=', 'classscheddetail.headerid');
                        $join->where('classscheddetail.deleted', 0);
                    })
                    ->select(
                        'roomid',
                        'classsched.glevelid as levelid',
                        'classsched.id',
                        'classsched.sectionid',
                        'classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'days as day',
                        DB::raw("CONCAT(classscheddetail.stime,' - ',classscheddetail.etime) as time")
                    )
                    ->get();

                $teachersched_sh = DB::table('sh_classsched')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('teacherid', $teacherid)
                    ->where('sh_classsched.deleted', 0)
                    ->join('sh_classscheddetail', function ($join) {
                        $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                        $join->where('sh_classscheddetail.deleted', 0);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'sh_classsched.glevelid as levelid',
                        'sh_classsched.id',
                        'sh_classsched.sectionid',
                        'sh_classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(sh_classscheddetail.stime,' - ',sh_classscheddetail.etime) as time")
                    )
                    ->get();

                $teachersched = $teachersched->merge($teachersched_sh);

            } else {
                $teachersched = [];
            }

            if ($roomid != '') {

                $roomsched = DB::table('classsched')
                    ->where('syid', $syid)
                    ->where('classsched.deleted', 0)
                    ->join('classscheddetail', function ($join) use ($roomid) {
                        $join->on('classsched.id', '=', 'classscheddetail.headerid');
                        $join->where('classscheddetail.deleted', 0);
                        $join->where('classscheddetail.roomid', $roomid);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'classsched.glevelid as levelid',
                        'classsched.id',
                        'classsched.sectionid',
                        'classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'days as day',
                        DB::raw("CONCAT(classscheddetail.stime,' - ',classscheddetail.etime) as time")
                    )
                    ->get();

                $roomsched_sh = DB::table('sh_classsched')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('sh_classsched.deleted', 0)
                    ->join('sh_classscheddetail', function ($join) use ($roomid) {
                        $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                        $join->where('sh_classscheddetail.deleted', 0);
                        $join->where('sh_classscheddetail.roomid', $roomid);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'sh_classsched.glevelid as levelid',
                        'sh_classsched.id',
                        'sh_classsched.sectionid',
                        'sh_classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(sh_classscheddetail.stime,' - ',sh_classscheddetail.etime) as time")
                    )
                    ->get();

                $roomsched = $roomsched->merge($roomsched_sh);



            } else {
                $roomsched = [];
            }


            if ($sectionid != '') {

                $sectionsched = DB::table('classsched')
                    ->where('syid', $syid)
                    ->where('sectionid', $sectionid)
                    ->where('classsched.deleted', 0)
                    ->join('classscheddetail', function ($join) use ($sectionid) {
                        $join->on('classsched.id', '=', 'classscheddetail.headerid');
                        $join->where('classscheddetail.deleted', 0);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'classsched.glevelid as levelid',
                        'classsched.id',
                        'classsched.sectionid',
                        'classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'days as day',
                        DB::raw("CONCAT(classscheddetail.stime,' - ',classscheddetail.etime) as time")
                    )
                    ->get();


                $sectionsched_sh = DB::table('sh_classsched')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('sectionid', $sectionid)
                    ->where('sh_classsched.deleted', 0)
                    ->join('sh_classscheddetail', function ($join) {
                        $join->on('sh_classsched.id', '=', 'sh_classscheddetail.headerid');
                        $join->where('sh_classscheddetail.deleted', 0);
                    })
                    ->where(function ($query) use ($subjid, $sectionid) {
                        $query->where('subjid', '!=', $subjid);
                        $query->orWhere('sectionid', '!=', $sectionid);
                    })
                    ->select(
                        'roomid',
                        'sh_classsched.glevelid as levelid',
                        'sh_classsched.id',
                        'sh_classsched.sectionid',
                        'sh_classsched.subjid',
                        'stime',
                        'etime',
                        'headerid',
                        'day',
                        DB::raw("CONCAT(sh_classscheddetail.stime,' - ',sh_classscheddetail.etime) as time")
                    )
                    ->get();

                $sectionsched = $sectionsched->merge($sectionsched_sh);

            } else {
                $sectionsched = [];
            }

            $conflict_count = 0;
            $conflict_list = array();

            foreach ($days as $item) {

                $day = '';
                if ($item == 1) {
                    $day = 'Mon';
                } else if ($item == 2) {
                    $day = 'Tue';
                } else if ($item == 3) {
                    $day = 'Wed';
                } else if ($item == 4) {
                    $day = 'Thu';
                } else if ($item == 5) {
                    $day = 'Fri';
                } else if ($item == 6) {
                    $day = 'Sat';
                } else if ($item == 7) {
                    $day = 'Sun';
                }

                // //collect day sched
                $temp_day_sched = collect($teachersched)->where('day', $item)->values();

                //check teacher conflict
                foreach ($temp_day_sched as $sched_item) {
                    $sched_stime = $sched_item->stime;
                    $sched_etime = $sched_item->etime;
                    if ($stime >= $sched_stime && $stime <= $sched_etime) {
                        if ($stime != $sched_etime) {
                            $sched_item->conflict = "TSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                        if ($etime != $sched_stime) {
                            $sched_item->conflict = "TSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                        $sched_item->conflict = "TSC";
                        array_push($conflict_list, $sched_item);
                    }
                }

                //collect day sched
                $temp_day_sched = collect($sectionsched)->where('day', $item)->values();

                //check section conflict
                foreach ($temp_day_sched as $sched_item) {
                    $sched_stime = $sched_item->stime;
                    $sched_etime = $sched_item->etime;
                    if ($stime >= $sched_stime && $stime <= $sched_etime) {
                        if ($stime != $sched_etime) {
                            $sched_item->conflict = "SSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                        if ($etime != $sched_stime) {
                            $sched_item->conflict = "SSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                        $sched_item->conflict = "SSC";
                        array_push($conflict_list, $sched_item);
                    }
                }


                //collect day sched
                $temp_day_sched = collect($roomsched)->where('day', $item)->values();

                //check room conflict
                foreach ($temp_day_sched as $sched_item) {
                    $sched_stime = $sched_item->stime;
                    $sched_etime = $sched_item->etime;
                    if ($stime >= $sched_stime && $stime <= $sched_etime) {
                        if ($stime != $sched_etime) {
                            $sched_item->conflict = "RSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($etime >= $sched_stime && $etime <= $sched_etime) {
                        if ($etime != $sched_stime) {
                            $sched_item->conflict = "RSC";
                            array_push($conflict_list, $sched_item);
                        }
                    } else if ($sched_stime >= $stime && $sched_etime <= $etime) {
                        $sched_item->conflict = "RSC";
                        array_push($conflict_list, $sched_item);
                    }
                }




            }

        }



        foreach ($conflict_list as $item) {
            if ($item->day == 1) {
                $item->description = 'M';
            } else if ($item->day == 2) {
                $item->description = 'T';
            } else if ($item->day == 3) {
                $item->description = 'W';
            } else if ($item->day == 4) {
                $item->description = 'Th';
            } else if ($item->day == 5) {
                $item->description = 'F';
            } else if ($item->day == 6) {
                $item->description = 'S';
            } else if ($item->day == 7) {
                $item->description = 'Sun';
            }
        }

        $grouped_conflict_list = collect($conflict_list)->groupBy('conflict');
        $conflict_info = array();


        foreach ($grouped_conflict_list as $key => $item) {

            $header_groups = collect($item)->groupBy('headerid');

            foreach ($header_groups as $header_group) {
                $dayString = '';
                $temp_info = $header_group[0];
                foreach ($header_group as $header_group_item) {
                    $dayString .= $header_group_item->description;
                }
                $sectioninfo = self::get_sectioninfo($temp_info->sectionid);

                if ($temp_info->roomid != null) {
                    $roominfo = self::get_roominfo($temp_info->roomid);
                } else {
                    $roominfo = (object) [
                        'roomname' => null
                    ];
                }

                if ($temp_info->levelid == 14 || $temp_info->levelid == 15) {
                    $subjinfo = self::get_subjinfo_shs($temp_info->subjid);
                } else {
                    $subjinfo = self::get_subjinfo_gshs($temp_info->subjid);
                }


                array_push($conflict_info, (object) [
                    'subject' => $subjinfo->subjdesc,
                    'section' => $sectioninfo->sectionname,
                    'room' => $roominfo->roomname,
                    'days' => $dayString,
                    'conflict' => $temp_info->conflict
                ]);
            }

        }

        if (count($conflict_info) > 0) {

            return array(
                (object) [
                    'status' => 0,
                    'data' => 'conflict',
                    'details' => $conflict_info
                ]
            );
        }

        $check_classched = DB::table('sh_classsched')
            ->where('sh_classsched.sectionid', $sectionid)
            ->where('sh_classsched.semid', $semid)
            ->where('sh_classsched.syid', $syid)
            ->where('sh_classsched.deleted', '0')
            ->where('sh_classsched.subjid', $subjid)
            ->get();

        if (count($check_classched) == 0) {

            $headerid = DB::table('sh_classsched')->insertGetID([
                'sectionid' => $sectionid,
                'teacherid' => $teacherid,
                'syid' => $syid,
                'semid' => $semid,
                'deleted' => 0,
                'subjid' => $subjid,
                'glevelid' => $levelid,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        } else {

            $headerid = $check_classched[0]->id;

            DB::table('sh_classsched')
                ->where('id', $headerid)
                ->take(1)
                ->update([
                    'teacherid' => $teacherid,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

        }


        foreach ($days as $item) {

            if ($request->get('iscreate') == 'false') {
                $schedinfo = $request->get('schedinfo');

                if ($subjid != $schedinfo[0]['subjid']) {

                    //if subject is updated
                    //insert new sched
                    DB::table('sh_classscheddetail')
                        ->insert([
                            'headerid' => $headerid,
                            'day' => $item,
                            'stime' => $stime,
                            'etime' => $etime,
                            'roomid' => $roomid,
                            'deleted' => '0',
                            'createdby' => auth()->user()->id,
                            'classification' => $class,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                    //remove the previous subject sched
                    DB::table('sh_classscheddetail')
                        ->where('headerid', $schedinfo[0]['headerid'])
                        ->where('classification', $schedinfo[0]['schedclassid'])
                        ->where('deleted', 0)
                        ->where('day', $item)
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                } else {

                    //if other information is updated
                    $check_if_exist = DB::table('sh_classscheddetail')
                        ->where('headerid', $headerid)
                        ->where('classification', $schedinfo[0]['schedclassid'])
                        ->where('deleted', 0)
                        ->where('day', $item)
                        ->count();

                    if ($check_if_exist == 0) {
                        DB::table('sh_classscheddetail')
                            ->insert([
                                'headerid' => $headerid,
                                'day' => $item,
                                'stime' => $stime,
                                'etime' => $etime,
                                'roomid' => $roomid,
                                'deleted' => '0',
                                'createdby' => auth()->user()->id,
                                'classification' => $class,
                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);

                    } else {

                        DB::table('sh_classscheddetail')
                            ->where('headerid', $headerid)
                            ->where('classification', $schedinfo[0]['schedclassid'])
                            ->where('deleted', 0)
                            ->where('day', $item)
                            ->take(1)
                            ->update([
                                'stime' => $stime,
                                'etime' => $etime,
                                'roomid' => $roomid,
                                'classification' => $class,
                                'classification' => $class,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }

                }
            } else {

                $check_detail = Db::table('sh_classscheddetail')
                    ->where('headerid', $headerid)
                    ->where('day', $item)
                    ->where('stime', $stime)
                    ->where('etime', $etime)
                    ->where('deleted', 0)
                    ->get();

                if (count($check_detail) > 0) {
                    DB::table('sh_classscheddetail')
                        ->where('id', $check_detail[0]->id)
                        ->where('deleted', 0)
                        ->take(1)
                        ->update([
                            'roomid' => $roomid,
                            'classification' => $class,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                } else {
                    DB::table('sh_classscheddetail')
                        ->insert([
                            'headerid' => $headerid,
                            'day' => $item,
                            'stime' => $stime,
                            'etime' => $etime,
                            'roomid' => $roomid,
                            'deleted' => '0',
                            'createdby' => auth()->user()->id,
                            'classification' => $class,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }

            }


        }

        // Db::table('sh_classscheddetail')
        //     ->where('headerid',$headerid)
        //     ->whereNotIn('day',$days)
        //     ->where('stime',$stime)
        //     ->where('etime',$etime)
        //     ->where('classification',$class)
        //     ->where('deleted',0)
        //     ->update([
        //         'deleted'=>'1',
        //         'deletedby'=>auth()->user()->id,
        //         'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        //     ]);

        return array(
            (object) [
                'status' => 1,
                'data' => 'success'
            ]
        );

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
                'message' => 'Something went wrong!'
            ]
        );
    }

}
