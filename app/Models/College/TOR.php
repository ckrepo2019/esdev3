<?php

namespace App\Models\College;

use Illuminate\Database\Eloquent\Model;
use DB;
class TOR extends Model
{
    public static function getrecords($studentid,$schoolyears)
    {
    
        $records = array();
        // return $schoolyears;
        foreach($schoolyears as $schoolyear)
        {
            $enrolledinfo = DB::table('college_enrolledstud')
                ->select('college_enrolledstud.*','college_courses.courseDesc as coursename','college_courses.courseabrv as coursecode')
                ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                ->where('college_enrolledstud.syid', $schoolyear->syid)
                ->where('college_enrolledstud.studid', $studentid)
                ->where('college_enrolledstud.studstatus', '>','0')
                ->where('college_enrolledstud.deleted','0')
                ->get();
                
            if(count($enrolledinfo)>0)
            {
                foreach($enrolledinfo as $info)
                {
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                    {
                        $grades = DB::table('college_studentprospectus')
                            ->select(
                                'college_studentprospectus.id',
                                'college_subjects.subjCode as subjcode',
                                'college_subjects.subjDesc as subjdesc',
                                'finalgrade as subjgrade',
                                'college_subjects.lecunits',
                                'college_subjects.labunits',
                                DB::raw('SUM(college_subjects.lecunits)+SUM(college_subjects.labunits) as subjunit'),
                                'college_studentprospectus.semid'
                                )
                            ->join('schedulecoding','college_studentprospectus.prospectusID','=','schedulecoding.id')
                            ->join('college_subjects','schedulecoding.subjid','=','college_subjects.id')
                            ->where('college_studentprospectus.studid', $studentid)
                            ->where('college_studentprospectus.syid', $schoolyear->syid)
                            ->where('college_studentprospectus.semid', $info->semid)
                            //->where('college_prospectus.yearID', $info->yearLevel)
                            //->where('college_prospectus.courseID', $info->courseid)
                            ->where('college_studentprospectus.deleted','0')
                            ->where('college_subjects.deleted','0')
                            //->where('college_prospectus.deleted','0')
                            ->get();
                            
                    }else{
                        $syid =  $schoolyear->syid;
                        $semid = $info->semid;
                        
                        $grades = DB::table('college_studsched')
                            ->join('college_classsched',function($join)use($syid,$semid){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted',0);
                                $join->where('syID',$syid);
                                $join->where('semesterID',$semid);
                            })
                            ->join('college_prospectus',function($join)use($syid,$semid){
                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                $join->where('college_prospectus.deleted',0);
                            })
                            ->leftJoin('college_studentprospectus',function($join)use($syid,$semid,$studentid){
                                $join->on('college_prospectus.id','=','college_studentprospectus.prospectusID');
                                $join->where('college_studentprospectus.studid',$studentid);
                                $join->where('college_studentprospectus.semid',$semid);
                                $join->where('college_studentprospectus.syid',$syid);
                            })
                            ->where('schedstatus','!=','DROPPED')
                            ->where('college_studsched.deleted',0)
                            ->where('college_studsched.studid',$studentid)
                            // ->where('college_studentprospectus.studid',$studentid)
                            // ->leftJoin('college_studentprospectus','college_prospectus.id','=','college_studentprospectus.prospectusID')
                            ->select('subjCode as subjcode','subjDesc as subjdesc', 'labunits','lecunits','fg as subjgrade','college_studentprospectus.studid as studentprospectusstudid')
                            ->orderBy('subjCode')
                            ->distinct()
                            ->get();
                            
                        $grades = collect($grades)->unique('subjcode');
                        
                    }

                    if(count($grades)>0)
                    {
                        foreach($grades as $grade)
                        {
                            $grade->display = 0;
                            $grade->subjreex = 0;
                            $grade->subjcredit = ($grade->subjgrade > 0 && $grade->subjgrade < (3.1)) ? ($grade->lecunits+$grade->labunits) : 0;
                            $grade->subjunit = ($grade->lecunits+$grade->labunits);
                        }
                    }
                    
                    array_push($records,(object)array(
                        'id'            => 0,
                        'syid'          => $schoolyear->syid,
                        'sydesc'        => $schoolyear->sydesc,
                        'isactive'      => $schoolyear->isactive,
                        'semid'         => $info->semid,
                        'courseid'      => $info->courseid,
                        'coursename'    => $info->coursename,
                        'coursecode'    => $info->coursecode,
                        'schoolid'      => DB::table('schoolinfo')->first()->schoolid,
                        'schoolname'    => DB::table('schoolinfo')->first()->schoolname,
                        'schooladdress' => DB::table('schoolinfo')->first()->address,
                        'subjdata'      => collect($grades)->unique()->values()->all(),
                        'type'          => 'auto',
                        'display'          => 0
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
            ->where('studid',$studentid)
            ->where('deleted','0')
            ->get();
            
        if(count($manualrecords)>0)
        {
            foreach($manualrecords as $record)
            {
                $record->type = 'manual';
                $subjdata = DB::table('college_torgrades')
                        ->select('id','subjcode','subjdesc','subjgrade','subjreex','subjunit','subjcredit')
                        ->where('torid', $record->id)
                        ->where('deleted','0')
                        ->get();

                $record->display = 0;
                if(count($subjdata)>0)
                {
                    foreach($subjdata as $subj)
                    {
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
