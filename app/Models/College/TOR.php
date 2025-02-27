<?php

namespace App\Models\College;

use Illuminate\Database\Eloquent\Model;
use DB;
class TOR extends Model
{
    public static function getrecords($studentid, $schoolyears, $semid = null, $gradelevel = null)
    {

        $records = array();
        // return $schoolyears;
        foreach ($schoolyears as $schoolyear) {
            $enrolledinfo = DB::table('college_enrolledstud')
                ->where(function ($query) use ($gradelevel) {
                    if ($gradelevel != null && $gradelevel != 0) {
                        $query->where('college_enrolledstud.yearlevel', $gradelevel);
                    }
                })
                ->where(function ($query) use ($semid) {
                    if ($semid != null && $semid != 0) {
                        $query->where('college_enrolledstud.semid', $semid);
                    }
                })
                ->select('college_enrolledstud.*', 'college_courses.courseDesc as coursename', 'college_courses.courseabrv as coursecode')
                ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                ->where('college_enrolledstud.syid', $schoolyear->syid)
                ->where('college_enrolledstud.studid', $studentid)
                ->where('college_enrolledstud.studstatus', '>', '0')
                ->where('college_enrolledstud.deleted', '0')
                ->get();

            if (count($enrolledinfo) > 0) {
                foreach ($enrolledinfo as $info) {
                   
                
               

                    $syid = $schoolyear->syid;
                    $semid = $info->semid;

                    $grades = DB::table('college_loadsubject')
                        ->join('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
                        ->join('college_classsched', function ($join) use ($syid, $semid) {
                            $join->on('college_loadsubject.schedid', '=', 'college_classsched.id');
                            $join->where('college_classsched.deleted', 0);
                            $join->where('college_classsched.syID', $syid);
                            $join->where('college_classsched.semesterID', $semid);
                        })
                        ->join('college_prospectus', function ($join) use ($syid, $semid) {
                            $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                            $join->where('college_prospectus.deleted', 0);
                        })
                        ->leftJoin('college_stud_term_grades', function ($join) use ($syid, $semid, $studentid) {
                            $join->on('college_classsched.id', '=', 'college_stud_term_grades.schedid');
                        })
                        ->where('college_loadsubject.deleted', 0)
                        ->where('college_loadsubject.studid', $studentid)
                        ->where('college_loadsubject.semid', $semid)
                        ->where('college_loadsubject.syid', $syid)
                        ->select(
                                'college_prospectus.subjCode as subjcode',
                                'college_prospectus.subjDesc as subjdesc',
                                'college_prospectus.labunits', 'college_prospectus.lecunits',
                                'college_stud_term_grades.final_grade_transmuted as subjgrade',
                                'college_stud_term_grades.studid as studentprospectusstudid',
                                'college_stud_term_grades.final_grade_average as subjgradeave',
                                'college_classsched.id as schedid',

                            )
                        ->orderBy('college_prospectus.subjCode')
                        ->distinct()
                        ->get();
                    // $grades = collect($grades)->unique('subjcode');
                    // return $grades;
                    if (count($grades) > 0) {
                        foreach ($grades as $grade) {
                            $grade->display = 0;
                            $grade->subjreex = 0;
                            $grade->subjcredit = ($grade->subjgrade > 0 && $grade->subjgrade < (3.1)) ? ($grade->lecunits + $grade->labunits) : 0;
                            $grade->subjunit = ($grade->lecunits + $grade->labunits);
                        }
                    }

                    array_push($records, (object) array(
                        'id' => 0,
                        'syid' => $schoolyear->syid,
                        'sydesc' => $schoolyear->sydesc,
                        'isactive' => $schoolyear->isactive,
                        'semid' => $info->semid,
                        'courseid' => $info->courseid,
                        'coursename' => $info->coursename,
                        'coursecode' => $info->coursecode,
                        'schoolid' => DB::table('schoolinfo')->first()->schoolid,
                        'schoolname' => DB::table('schoolinfo')->first()->schoolname,
                        'schooladdress' => DB::table('schoolinfo')->first()->address,
                        'subjdata' => collect($grades)->unique()->values()->all(),
                        'type' => 'auto',
                        'display' => 0
                    ));
                }
            }
            $schoolyear->id = 0;
        }

        $records = collect($records);
        $manualrecords = DB::table('college_tor')
            ->select(
                'id',
                'syid',
                'sydesc',
                'semid',
                'courseid',
                'coursename',
                'schoolid',
                'schoolname',
                'schooladdress'
            )
            ->where('studid', $studentid)
            ->where('deleted', '0')
            ->get();

        if (count($manualrecords) > 0) {
            foreach ($manualrecords as $record) {
                $record->type = 'manual';
                $subjdata = DB::table('college_torgrades')
                    ->select('id', 'subjcode', 'subjdesc', 'subjgrade', 'subjreex', 'subjunit', 'subjcredit')
                    ->where('torid', $record->id)
                    ->where('deleted', '0')
                    ->get();

                $record->display = 0;
                if (count($subjdata) > 0) {
                    foreach ($subjdata as $subj) {
                        // $subj->display = 1;
                        $subj->display = 0;
                        $subj->semid = $record->semid;
                    }
                }
                $record->subjdata = $subjdata;
            }
        }
        // return $manualrecords;

        $records = $records->merge($manualrecords);

        return collect($records)->sortBy('sydesc')->values();
    }
}
