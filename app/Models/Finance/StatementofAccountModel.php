<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use DB;
class StatementofAccountModel extends Model
{
    public static function allstudents($levelid, $syid, $semid, $sectionid, $courseid)
    {
        $studinfo_1 = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'studinfo.feesid',
                'gradelevel.levelname',
                'mcontactno',
                'fcontactno',
                'gcontactno',
                'ismothernum',
                'isfathernum',
                'isguardannum',
                'contactno'
                
            )
            ->join('enrolledstud', 'studinfo.id','=','enrolledstud.studid')
            ->join('sections', 'enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel', 'enrolledstud.levelid','=','gradelevel.id')
            ->where('sections.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('enrolledstud.deleted','0')
            ->where('enrolledstud.studstatus','!=','0')
            ->where('syid', $syid)
            ->where('enrolledstud.levelid', $levelid)
            ->where(function($q) use($sectionid){
                if($sectionid > 0)
                {
                    $q->where('enrolledstud.sectionid', $sectionid);
                }
            })
            ->distinct()
            // ->take(5)
            ->get();
            
        $studinfo_2 = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'studinfo.feesid',
                'gradelevel.levelname'
            )
            ->join('sh_enrolledstud', 'studinfo.id','=','sh_enrolledstud.studid')
            ->join('sections', 'sh_enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel', 'sh_enrolledstud.levelid','=','gradelevel.id')
            ->where('sections.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('sh_enrolledstud.deleted','0')
            ->where('sh_enrolledstud.levelid', $levelid)
            ->where(function($q) use($sectionid){
                if($sectionid > 0)
                {
                    $q->where('sh_enrolledstud.sectionid', $sectionid);
                }
            })
            ->where('sh_enrolledstud.studstatus','!=','0')
            ->where('syid', $syid)
            ->where(function($q) use($semid){
                if($semid == 3)
                {
                    $q->where('sh_enrolledstud.semid', 3);
                }
                else
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('sh_enrolledstud.semid', $semid);
                    }
                    else
                    {
                        $q->where('sh_enrolledstud.semid', '!=', 3);
                    }
                }
            })
            ->distinct()
            // ->take(5)
            ->get();
            
        
        $studinfo_3 = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'college_enrolledstud.sectionID as sectionid',
                'college_enrolledstud.courseid',
                'college_sections.id as sectionid',
                'college_sections.sectionDesc as sectionname',
                'gradelevel.levelname',
                'gradelevel.id as levelid',
                'studinfo.feesid',
                'college_courses.courseabrv as coursename'
            )
            ->join('college_enrolledstud', 'studinfo.id','=','college_enrolledstud.studid')
            ->leftJoin('college_sections', 'college_enrolledstud.sectionID','=','college_sections.id')
            ->leftJoin('gradelevel', 'college_enrolledstud.yearLevel','=','gradelevel.id')
            ->leftJoin('college_courses', 'college_enrolledstud.courseid','=','college_courses.id')
            ->join('sy', 'college_enrolledstud.syid','=','sy.id')
            ->where('studinfo.deleted','0')
            ->where('college_enrolledstud.deleted','0')
            ->where('college_enrolledstud.studstatus','!=','0')
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.semid', $semid)
            ->where('yearLevel', $levelid)
            ->where(function($q) use($courseid){
                if($courseid > 0)
                {
                    $q->where('college_enrolledstud.courseid', $courseid);
                }
            })
            ->distinct()
            // ->take(5)
            ->get();
            $allItems = collect();
            $allItems = $allItems->merge($studinfo_1);
            $allItems = $allItems->merge($studinfo_2);
            $allItems = $allItems->merge($studinfo_3);
            $allItems = $allItems->unique('id');
        
        return $allItems->sortBy('lastname');
    }
}
