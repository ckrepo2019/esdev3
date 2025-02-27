<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;
use DB;
class VirtualClassroomGetSections extends Model
{
    public static function sectionsunder($teacherid)
    {
        $assignsubjLower = DB::table('assignsubjdetail')
            ->select(
                'subjects.id as subjectid',
                'subjects.subjcode as subjcode',
                'sections.id as sectionid',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid'
                )
            ->join('assignsubj','assignsubjdetail.headerid','assignsubj.id')
            ->join('classsched',function($join)
                {
                    // $join->on('assignsubj.glevelid','=','classsched.glevelid');
                    $join->on('assignsubj.sectionid','=','classsched.sectionid');
                    $join->on('assignsubjdetail.subjid','=','classsched.subjid');
                })
            ->join('classscheddetail','classsched.id','=','classscheddetail.headerid')
            ->join('gradelevel','assignsubj.glevelid','gradelevel.id')
            ->join('sections','assignsubj.sectionid','sections.id')
            ->join('subjects','classsched.subjid','subjects.id')
            ->join('sy','assignsubj.syid','sy.id')
            ->join('days','classscheddetail.days','days.id')
            ->join('rooms','classscheddetail.roomid','rooms.id')
            ->where('assignsubjdetail.teacherid',$teacherid)
            ->where('assignsubjdetail.deleted','0')
            ->where('sy.isactive','1')
            ->distinct()
            ->get();
            
        $assignsubjHigher = DB::table('sh_classsched')
            ->select(
                'sh_subjects.id as subjectid',
                'sh_subjects.subjcode as subjcode',
                'sections.id as sectionid',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid'
                )
            ->join('sh_classscheddetail','sh_classsched.id','=','sh_classscheddetail.headerid')
            ->join('sh_subjects','sh_classsched.subjid','=','sh_subjects.id')
            ->join('gradelevel','sh_classsched.glevelid','=','gradelevel.id')
            ->join('sections','sh_classsched.sectionid','=','sections.id')
            ->join('days','sh_classscheddetail.day','=','days.id')
            ->join('rooms','sh_classscheddetail.roomid','=','rooms.id')
            ->where('sh_classsched.teacherid',$teacherid)
            ->join('sy','sh_classsched.syid','sy.id')
            ->where('sy.isactive','1')
            ->where('sh_classsched.deleted', '0')
            ->distinct()
            ->get();
        // $assignsubjCollege = DB::table('college_classsched')
        //     ->select(
        //         'college_prospectus.id as subjectid',
        //         'college_prospectus.subjCode as subjcode',
        //         'college_sections.id as sectionid',
        //         'college_sections.sectionDesc as sectionname',
        //         'gradelevel.id as levelid'
        //         )
        //     // ->join('sh_classscheddetail','sh_classsched.id','=','sh_classscheddetail.headerid')
        //     ->join('college_prospectus','college_classsched.subjectID','=','college_prospectus.id')
        //     ->join('college_sections','college_classsched.sectionID','=','college_sections.id')
        //     ->join('gradelevel','college_sections.yearID','=','gradelevel.id')
        //     ->join('college_scheddetail','college_classsched.id','=','college_scheddetail.headerID')
        //     ->join('days','college_scheddetail.day','=','days.id')
        //     ->join('rooms','college_scheddetail.roomID','=','rooms.id')
        //     ->where('college_classsched.teacherID',$teacherid)
        //     ->join('sy','college_classsched.syID','sy.id')
        //     ->where('sy.isactive','1')
        //     ->where('college_classsched.deleted', '0')
        //     ->distinct()
        //     ->get();
            
            // return $assignsubjCollege;
        $sectionsarry = array();
        if(count(collect($assignsubjLower->groupBy('sectionid')))>0)
        {
            foreach(collect($assignsubjLower->groupBy('sectionid')) as $seckey => $secvalue)
            {
                foreach($secvalue as $valsec)
                {
                    array_push($sectionsarry, $valsec);
                }
            }
        }
        if(count(collect($assignsubjHigher->groupBy('sectionid')))>0)
        {
            foreach(collect($assignsubjHigher->groupBy('sectionid')) as $seckey => $secvalue)
            {
                foreach($secvalue as $valsec)
                {
                    array_push($sectionsarry, $valsec);
                }
            }
        }
        // if(count(collect($assignsubjCollege->groupBy('sectionid')))>0)
        // {
        //     foreach(collect($assignsubjCollege->groupBy('sectionid')) as $seckey => $secvalue)
        //     {
        //         foreach($secvalue as $valsec)
        //         {
        //             array_push($sectionsarry, $valsec);
        //         }
        //     }
        // }

        return collect($sectionsarry)->unique();
    }
}
