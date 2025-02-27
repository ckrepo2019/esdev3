<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;
use DB;
class SchoolForm2Model extends Model
{
    public static function attendance($students,$currentdays,$levelid,$sectionid,$selectedmonth,$selectedyear,$teacherid,$selectedlact,$setup, $syid,$semid, $strandid)
    {
        $currentdays = collect($currentdays)->where('dates','!=','0000-00-00')->values();
        
        $acadprog = DB::table('gradelevel')
            ->select('academicprogram.acadprogcode')
            ->where('gradelevel.id', $levelid)
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->first()->acadprogcode;
            
        $classsubjects = array();
        if(strtolower($acadprog) == 'shs')
        {
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mcs' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'bct')
            {
                $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid, $syid, $sectionid, $semid, $strandid);
                
                if(count($subjects)>0)
                {
                    foreach($subjects as $subject)
                    {
                        if(count($subject->schedule)> 0)
                        {
                            
                            array_push($classsubjects, (object)array(
                                'id'        => $subject->id,
                                'subjectname'     => $subject->subjcode,
                                'subjdesc'        => $subject->subjdesc,
                                'day'             => str_replace(' ', '', $subject->schedule[0]->day),
                                'starttime'       => $subject->schedule[0]->start,
                                'shift'           => date('A', strtotime($subject->schedule[0]->start)),
                            ));
                        }
                    }
                }
            }            
        }else{
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mcs' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'bct')
            {
                $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid, $syid, $sectionid, null, null);
                
                if(count($subjects)>0)
                {
                    foreach($subjects as $subject)
                    {
                        if(count($subject->schedule)> 0)
                        {
                            array_push($classsubjects, (object)array(
                                'id'              => $subject->subjid,
                                'subjectname'     => $subject->subjcode,
                                'subjdesc'        => $subject->subjdesc,
                                'day'             => str_replace(' ', '', $subject->schedule[0]->day),
                                'starttime'       => $subject->schedule[0]->start,
                                'shift'           => date('A', strtotime($subject->schedule[0]->start)),
                            ));
                        }
                    }
                }

            }
        }
        $classsubjectsunique = collect($classsubjects)->unique('id')->values()->all();
        if(count($currentdays)>0)
        {
            $studattendance = Db::table('studattendance')
                ->select('studid','present','absent','tardy','cc','tdate')
                ->whereBetween('tdate', [collect($currentdays)->first()->dates, collect($currentdays)->last()->dates])
                ->whereIn('studid', collect($students)->pluck('id'))
                ->where('deleted','0')
                // ->where('syid',$syid)
                ->get();

                
            $studentsubjectattendance = DB::table('studentsubjectattendance')
                ->whereIn('student_id', collect($students)->pluck('id'))
                ->where('section_id', $sectionid)
                ->whereIn('subject_id', collect($classsubjectsunique)->pluck('id'))
                ->whereBetween('date',  [collect($currentdays)->first()->dates, collect($currentdays)->last()->dates])
                ->where('deleted','0')
                ->get();
                
        

        }else{
            $studattendance = array();
            $studentsubjectattendance = [];
        }
        // return $classsubjectsunique;
        // return collect($studattendance)->where('studid',260);
        
        $countconsecutive_male = 0;
        $countconsecutive_female = 0;
        $nlpamale = 0;
        $nlpafemale = 0;
        // $students = collect($students)->where('id',1620)->values();
        $students = collect($students)->unique('id')->all();
        $equivalence = DB::table('sf2_lact')
            ->where('teacherid', $teacherid)
            ->where('year',$selectedyear)
            ->where('month',$selectedmonth)
            ->where('sectionid',$sectionid)
            ->where('lact',3)
            ->where('deleted','0')
            ->get();
        // return $studattendance;
        // return collect($studattendance)->where('studid',260);
            
        if(count($students) > 0)
        {
            foreach($students as $student)
            {
                $studentatt = array();
                $consecutive = 0;
                
                foreach($currentdays as $day)
                {
                    
                    $status = null; //1 = absent; 2 = present; 3 = late; 4 = cc; 10 = absentam; 11 = absentpm; 30 = lateam; 31 = latepm;
                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                    // {
                    //     $status =2;
                    // }
                    $studatt = collect($studattendance)->where('studid',$student->id)->where('tdate',$day->daydate)->values();
                    // if($student->id == 260 && $day->daydate == '2021-09-01')
                    // {
                    //     return collect($studatt);
                    // }
                    // return collect($studatt)->count();
                    
                // if($student->id == 548)
                // {
                //     // && date('m', strtotime($day->daydate)) == 11
                //     return $student->display;
                // }
                    if($student->display == 1)
                    {
                        if(collect($studatt)->count() > 0)
                        {                        
                            if($studatt[0]->present == 1)
                            {
                                $status = 2;
                            }
                            if($studatt[0]->absent == 1)
                            {
                                $status = 1;
                                $consecutive+=1;
                            }
                            if($studatt[0]->tardy == 1)
                            {
                                $status = 3;
                            }
                            if($studatt[0]->cc == 1)
                            {
                                $status = 4;
                            }
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' && $status == 4)
                            {
                                $status = 3;
                            }
                        }
                        elseif(collect($studatt)->count() == 0)
                        {
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            {
                                $status = 1;
                                $consecutive+=1;
                            }
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                            {
                                if($student->display == 1)
                                {
                                    $status = 2;
                                }
                            }
                        }
                    }else{
                        if(collect($studatt)->count() > 0)
                        {                        
                            if($studatt[0]->present == 1)
                            {
                                $status = 2;
                            }
                            if($studatt[0]->absent == 1)
                            {
                                $status = 1;
                                $consecutive+=1;
                            }
                            if($studatt[0]->tardy == 1)
                            {
                                $status = 3;
                            }
                            if($studatt[0]->cc == 1)
                            {
                                $status = 4;
                            }
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' && $status == 4)
                            {
                                if($student->display == 1)
                                {
                                    $status = 3;
                                }
                            }
                        }
                        elseif(collect($studatt)->count() == 0)
                        {
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            {
                                $status = 1;
                                $consecutive+=1;
                            }
                        }
                    }
                    
                    
                    $subjattabsent = 0;
                    $subjattpresent = 0;
                    $subjattlate = 0;

                    
                    $subjamlate = 0;
                    $subjpmlate = 0;
                    $subjamabsent = 0;
                    $subjpmabsent = 0;

                    $amsubjects = array();
                    $pmsubjects = array();
                    
                    // return collect($classsubjectsunique);
                    
                    $subjectsfortoday = collect($classsubjectsunique)->filter(function ($item) use ($day) {
                        return false !== stripos($item->day, $day->daystr);
                    });;
                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' && count($studatt) == 0 && count($subjectsfortoday) > 0)
                    // {
                    //     $status = 1;
                    // }
                    if($selectedlact != 3)
                    {
                        $subjatttoday = collect($studentsubjectattendance)->whereIn('subject_id', collect($subjectsfortoday)->pluck('id'))->where('date', $day->daydate)->where('student_id', $student->id)->values();

                        if(count($subjectsfortoday)>0 && count($subjatttoday)>0)
                        {
                            foreach($subjectsfortoday as $subjtoday)
                            {
                                $getsubjectatt = collect($subjatttoday)->where('subject_id', $subjtoday->id)->first();

                                if($getsubjectatt)
                                {
                                    if($subjtoday->shift == 'AM')
                                    {  
                                        array_push($amsubjects, (object)$getsubjectatt);
                                        if($getsubjectatt->status == 'present')
                                        {
                                            $subjattpresent += 1;
                                        }
                                        elseif($getsubjectatt->status == 'late')
                                        {
                                            $subjattlate += 1;
                                            $subjamlate += 1;
                                        }
                                        elseif($getsubjectatt->status == 'absent')
                                        {
                                            $subjattabsent += 1;
                                            $subjamabsent += 1;
                                        }
                                        elseif($getsubjectatt->status == 'absentam')
                                        {
                                            $subjattabsent += 1;
                                            $subjamabsent += 1;
                                        }
                                    }
                                    if($subjtoday->shift == 'PM')
                                    {  
                                        array_push($pmsubjects, (object)$getsubjectatt);
                                        if($getsubjectatt->status == 'present')
                                        {
                                            $subjattpresent += 1;
                                        }
                                        elseif($getsubjectatt->status == 'late')
                                        {
                                            $subjattlate += 1;
                                            $subjpmlate += 1;
                                        }
                                        elseif($getsubjectatt->status == 'absent')
                                        {
                                            $subjattabsent += 1;
                                            $subjpmabsent += 1;
                                        }
                                        elseif($getsubjectatt->status == 'absentpm')
                                        {
                                            $subjattabsent += 1;
                                            $subjpmabsent += 1;
                                        }
                                    }
                                }  
                                
                            }
                        }else{
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                            {
                                if($student->display == 1)
                                {
                                    $status = 2;
                                    $subjattpresent += 1;
                                }
                            }
                            
                        }
                        
                        $highest = array(
                            // 'absent' => $subjattabsent,
                            'present' => $subjattpresent,
                            // 'late'      => $subjattlate,
                            'lateam'    => $subjamlate, 
                            'latepm'    => $subjpmlate,
                            'absentam'  => $subjamabsent,
                            'absentpm'  => $subjpmabsent
                        );
                        $keyvar = array_search(max($highest), $highest);
                        $highestval = collect($highest)->sort()->reverse()->first();
                        $dayvalue = 0;
                        
                        if(count($subjatttoday)>0)
                        {
                            if($status == 1)
                            {
                                if($subjattpresent>0 || $subjattlate > 0)
                                {
                                    $status = 4;
                                    $dayvalue =1;
                                }
                            }elseif($status == 2){
                                
                                if($subjattlate > 0)
                                {
                                    $status = 3;
                                    $dayvalue =1;
                                }
                                if($subjattabsent > 0)
                                {
                                    $status = 4;
                                }
                            }elseif($status == 3){
                                if($subjattabsent > 0)
                                {
                                    $status = 4;
                                }
                            }
                        }else{
                            $dayvalue = 1;
                        }
                        // return $status;
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjhs')
                        {
                            if($status == null)
                            {
                                $status = 2;
                            }
                            array_push($studentatt,(object)array(
                                'value'   => $dayvalue,
                                'day'   => $day->daynum,
                                'keystatus'   => $keyvar,
                                'status'    => $status
                            ));

                        }else{
                            array_push($studentatt,(object)array(
                                'value'   => $dayvalue,
                                'day'   => $day->daynum,
                                'keystatus'   => $keyvar,
                                'status'    => $status
                            ));
                        }
                    }else{
                        // if($status == 1)
                        // {
                        //     $dayvalue = 1;
                        // }
                        // elseif($status == 2)
                        // {
                        //     $dayvalue = 1;
                        // }
                        // elseif($status == 3)
                        // {
                        //     $dayvalue = 1;
                        // }
                        // elseif($status == 4)
                        // {
                        //     $dayvalue = 1;
                        // }
                        $dayvalue = 1;
                        array_push($studentatt,(object)array(
                            'value'   => $dayvalue,
                            'day'   => $day->daynum,
                            'keystatus'   => $day->daynum,
                            'status'    => $status
                        ));
                    }

                }
                
                $student->attendance = $studentatt;
                
                if($selectedlact == 3)
                {
            
                    if(count($students)>0)
                    {
                        $student->submitted = null;
                        $student->required = null;
                        $student->dayspresent = null;
                        $student->daysabsent = null;
                        $student->remarks = '';
        
                        if(count($setup) > 0)
                        {                        
                            $remark = DB::table('sf2_setupremarks')
                                ->where('setupid', $setup[0]->id)
                                ->where('studentid',$student->id)
                                ->where('deleted','0')
                                ->first();
                
                            if($remark)
                            {
                                $student->remarks = $remark->remarks;
                            }
                        }
                        
                        if(count($equivalence)>0)
                        {
                            $checkifexists = DB::table('sf2_lact3detail')
                                ->where('headerid', $equivalence[0]->id)
                                ->where('studid', $student->id)
                                ->where('deleted','0')
                                ->first();
        
                            if($checkifexists)
                            {
                                $student->submitted = $checkifexists->submitted;
                                $student->required = $checkifexists->required;
                                $student->dayspresent = $checkifexists->dayspresent;
                                $student->daysabsent = $checkifexists->daysabsent;
                            }
                        }
                        if(count($student->attendance)>0)
                        {
                            $presentdays = 0;
                            foreach($student->attendance as $studatt)
                            {
                                if($student->dayspresent > $presentdays)
                                {
                                    $studatt->status = 2;
                                    $presentdays+=1;
                                }else{
                                    $studatt->status = 1;
                                }
                            }
                        }
                    }
                }else{
                    if($consecutive >= 5)
                    {
                        if(strtolower($student->gender) == 'male')
                        {
                            $countconsecutive_male+=1;
                        }
                        if(strtolower($student->gender) == 'female')
                        {
                            $countconsecutive_female+=1;
                        }
                    // }elseif($consecutive >= 10)
                    // {
                        if(strtolower($student->gender) == 'male')
                        {
                            $nlpamale+=1;
                        }
                        if(strtolower($student->gender) == 'female')
                        {
                            $nlpafemale+=1;
                        }
                    }
                }
                // if($student->id == 1620)
                // {
                //     return $student->attendance;
                // }
                   
            }

        }
        return array($students,array((object)[
            'countconsecutive_male' => $countconsecutive_male,
            'countconsecutive_female'   => $countconsecutive_female ,
            'nlpamale' => $nlpamale,
            'nlpafemale'   => $nlpafemale 
        ]));
    }
    public static function maletotalperday($students, $currentdays)
    {
        $maletotalperday = array();

        if(count($currentdays)>0)
        {
            $studattendance = Db::table('studattendance')
                ->select('studid','present','absent','tardy','cc','tdate')
                // ->where('studid',$student->id)
                // ->whereIn('studid', collect($students)->pluck('id'))
                ->whereBetween('tdate', [collect($currentdays)->first()->dates, collect($currentdays)->last()->dates])
                ->where('deleted','0')
                ->get();

            $studsubjattendance = Db::table('studentsubjectattendance')
                ->select('student_id as studid','status')
                // ->where('studid',$student->id)
                // ->whereIn('studid', collect($students)->pluck('id'))
                ->whereBetween('tdate', [collect($currentdays)->first()->dates, collect($currentdays)->last()->dates])
                ->where('deleted','0')
                ->get();
        }else{
            $studattendance = array();
            $studsubjattendance = array();
        }
        foreach($currentdays as $day)
        {
            $withrecords = 0;
            foreach(collect($students)->where('display','1')->all() as $stud)
            {
                if(strtolower($stud->gender) == 'male')
                {
                    $att = collect($studattendance)->where('studid',$stud->id)->where('absent',0)->where('tdate',$day->daydate)->count();
                    $subjatt = collect($studsubjattendance)->where('studid',$stud->id)->where('status','!=','absent')->where('tdate',$day->daydate)->count();
                    if($att>0 || $subjatt>0)
                    {
                        $withrecords+=$att;
                    }
                        
                }
            }
            array_push($maletotalperday,(object)array(
                'day'   => $day->daynum,
                'total' =>$withrecords
            ));
        }
        return $maletotalperday;
    }
    public static function femaletotalperday($students, $currentdays)
    {
        $femaletotalperday = array();
        if(count($currentdays)>0)
        {
            $studattendance = Db::table('studattendance')
                ->select('studid','present','absent','tardy','cc','tdate')
                // ->where('studid',$student->id)
                // ->whereIn('studid', collect($students)->pluck('id'))
                ->whereBetween('tdate', [collect($currentdays)->first()->dates, collect($currentdays)->last()->dates])
                ->where('deleted','0')
                ->get();
        }else{
            $studattendance = array();
        }
        foreach($currentdays as $day)
        {
            $withrecords = 0;
            foreach(collect($students)->where('display','1')->all() as $stud)
            {
                if(strtolower($stud->gender) == 'female')
                {
                        $att = collect($studattendance)->where('studid',$stud->id)->where('absent',0)->where('tdate',$day->daydate)->count();
                        if($att>0)
                        {
                            $withrecords+=$att;
                        }
                        
                }
            }
            array_push($femaletotalperday,(object)array(
                'day'   => $day->daynum,
                'total' =>$withrecords
            ));
        }
        return $femaletotalperday;
    }
    public static function studentstotalperday($students, $currentdays,$levelid,$sectionid,$selectedmonth,$selectedyear,$teacherid,$selectedlact,$setup, $syid,$semid, $strandid)
    {
        $equivalence = DB::table('sf2_lact')
            ->where('teacherid', $teacherid)
            ->where('year',$selectedyear)
            ->where('month',$selectedmonth)
            ->where('sectionid',$sectionid)
            ->where('lact',3)
            ->where('deleted','0')
            ->get();
        $studenttotalperday = array();
        
        if(count($currentdays)>0)
        {
            $studattendance = Db::table('studattendance')
                ->select('studid','present','absent','tardy','cc','tdate')
                // ->where('studid',$student->id)
                // ->whereIn('studid', collect($students)->pluck('id'))
                ->whereBetween('tdate', [collect($currentdays)->first()->dates, collect($currentdays)->last()->dates])
                ->whereIn('studid', collect($students)->pluck('id'))
                ->where('deleted','0')
                ->get();

            $studsubjattendance = Db::table('studentsubjectattendance')
                ->select('student_id as studid','status','date as tdate')
                // ->where('studid',$student->id)
                // ->whereIn('studid', collect($students)->pluck('id'))
                ->whereBetween('date', [collect($currentdays)->first()->dates, collect($currentdays)->last()->dates])
                ->whereIn('student_id', collect($students)->pluck('id'))
                ->where('deleted','0')
                ->get();
        }else{
            $studattendance = array();
            $studsubjattendance = array();
        }
        
        foreach($currentdays as $day)
        {
            $withrecords = 0;
            $withrecordsfemale = 0;
            $withrecordsmale = 0;
            foreach(collect($students)->where('display','1')->all() as $stud)
            {
                if($selectedlact == 3)
                {

                    $todayatt = collect($stud->attendance)->where('day', $day->daynum)->first();
                    if($todayatt)
                    {
                        if(strtolower($stud->gender) == 'male')
                        {
                            if($todayatt->status != 1)
                            {
                                $withrecords+=1;
                                $withrecordsmale+=1;
                            }
                        }
                        if(strtolower($stud->gender) == 'female')
                        {
                            if($todayatt->status != 1)
                            {
                                $withrecords+=1;
                                $withrecordsfemale+=1;
                            }
                        }
                    }

                }else{
                    $att = collect($studattendance)->where('studid',$stud->id)->where('absent',0)->where('tdate',$day->daydate)->count();
                    $subjatt = collect($studsubjattendance)->where('studid',$stud->id)->where('status','!=','absent')->where('tdate',$day->daydate)->count();
    
                    if(strtolower($stud->gender) == 'male')
                    {
                        if($att>0)
                        {
                            $withrecords+=$att;
                            $withrecordsmale+=$att;
                        }
                        
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mcs' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'bct')
                        {
                            if($subjatt>0)
                            {
                                $withrecords+=1;
                                $withrecordsmale+=1;
                            }
                        }
                    }
                    elseif(strtolower($stud->gender) == 'female')
                    {
                        if($att>0)
                        {
                            $withrecords+=$att;
                            $withrecordsfemale+=$att;
                        }
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mcs' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'bct')
                        {
                            if($subjatt>0)
                            {
                                $withrecords+=1;
                                $withrecordsfemale+=1;
                            }
                        }
                    }
                }
                        
            }
            array_push($studenttotalperday,(object)array(
                'day'   => $day->daynum,
                'withrecordsmale' =>$withrecordsmale,
                'withrecordsfemale' =>$withrecordsfemale,
                'total' =>$withrecords
            ));
        }
        return $studenttotalperday;
    }
    public static function attendance_hccsi($students,$currentdays,$levelid,$sectionid,$selectedmonth,$selectedyear,$teacherid,$selectedlact,$setup, $syid,$semid, $strandid)
    {
        
        $currentdays = collect($currentdays)->where('dates','!=','0000-00-00')->values();
        
        $acadprog = DB::table('gradelevel')
            ->select('academicprogram.acadprogcode')
            ->where('gradelevel.id', $levelid)
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->first()->acadprogcode;
            
        $classsubjects = array();

        if(strtolower($acadprog) == 'shs')
        {
            $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid, $syid, $sectionid, $semid, $strandid);
            
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    if(count($subject->schedule)> 0)
                    {
                        
                        array_push($classsubjects, (object)array(
                            'id'        => $subject->subjid,
                            'subjectname'     => $subject->subjcode,
                            'subjdesc'        => $subject->subjdesc,
                            'day'             => str_replace(' ', '', $subject->schedule[0]->day),
                            'starttime'       => $subject->schedule[0]->start,
                            'shift'           => date('A', strtotime($subject->schedule[0]->start)),
                        ));
                    }
                }
            }
            
        }else{
            $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid, $syid, $sectionid, null, null);
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    if(count($subject->schedule)> 0)
                    {
                        
                        array_push($classsubjects, (object)array(
                            'id'              => $subject->subjid,
                            'subjectname'     => $subject->subjcode,
                            'subjdesc'        => $subject->subjdesc,
                            'day'             => str_replace(' ', '', $subject->schedule[0]->day),
                            'starttime'       => $subject->schedule[0]->start,
                            'shift'           => date('A', strtotime($subject->schedule[0]->start)),
                        ));
                    }
                }
            }
        }
        $classsubjects = collect($classsubjects)->unique('id')->values()->all();
        
        if(count($currentdays)>0)
        {
            foreach($currentdays as $currentday)
            {
                $currentday->amsubjects = collect($classsubjects)->where('day', $currentday->daystr)->where('shift','AM')->all();
                $currentday->pmsubjects = collect($classsubjects)->where('day', $currentday->daystr)->where('shift','PM')->all();
                // if($currentday->daystr == 'Mon')
                // {
                //     return $currentday->amsubjects;
                // }
            }
            $studattendance = Db::table('studattendance')
                ->select('studid','present','absent','tardy','cc','tdate','presentam','presentpm','absentam','absentpm','lateam','latepm','ccam','ccpm','tdate')
                ->whereIn('tdate',  collect($currentdays)->pluck('dates'))
                ->whereIn('studid', collect($students)->pluck('id'))
                ->where('deleted','0')
                // ->where('syid',$syid)
                ->get();

                
            $studentsubjectattendance = DB::table('studentsubjectattendance')
                ->whereIn('student_id', collect($students)->pluck('id'))
                ->where('section_id', $sectionid)
                ->whereIn('subject_id', collect($classsubjects)->pluck('id'))
                ->whereIn('date',  collect($currentdays)->pluck('dates'))
                ->where('deleted','0')
                ->get();

        }else{
            $studattendance = array();
            $studentsubjectattendance = [];
        }

        
        $countconsecutive_male = 0;
        $countconsecutive_female = 0;
        $nlpamale = 0;
        $nlpafemale = 0;
        // null = no attendance;
        // 0 = absent;
        // 1 = present;
        // 2 = late;
        // 3 = cc;
        
        if(count($students)>0)
        {
            foreach($students as $student)
            {
                $consecutive = 0;
                $eachstudattendance = array();
                foreach($currentdays as $eachday)
                {
                    $attinfo = collect($studattendance)->where('studid', $student->id)->where('tdate', $eachday->dates)->first();
                    $value =NULL;
                    $keystatus =NULL;
                    $status =NULL;
                    if($attinfo)
                    {
                        if($attinfo->present == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='PRESENT';
                            $status =1;
                        }
                        elseif($attinfo->presentam == 1)
                        {
                            $consecutive = 0;
                            $value = 0.5;
                            $keystatus ='AM PRESENT';
                            $status ='presentam';
                        }
                        elseif($attinfo->presentpm == 1)
                        {
                            $consecutive = 0;
                            $value =0.5;
                            $keystatus ='PM PRESENT';
                            $status ='presentpm';
                        }
                        elseif($attinfo->tardy == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='LATE';
                            $status =2;
                        }
                        elseif($attinfo->lateam == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='AM LATE';
                            $status ='lateam';
                        }
                        elseif($attinfo->latepm == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='PM LATE';
                            $status ='latepm';
                        }
                        elseif($attinfo->absent == 1)
                        {
                            $consecutive+=1;
                            $value =0;
                            $keystatus ='ABSENT';
                            $status =0;
                        }
                        elseif($attinfo->absentam == 1)
                        {
                            $consecutive = 0;
                            $value =0.5;
                            $keystatus ='AM ABSENT';
                            $status ='absentam';
                        }
                        elseif($attinfo->absentpm == 1)
                        {
                            $consecutive = 0;
                            $value =0.5;
                            $keystatus ='PM ABSENT';
                            $status ='absentpm';
                        }
                        elseif($attinfo->cc == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='CC';
                            $status =3;
                        }
                        elseif($attinfo->ccam == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='AM CC';
                            $status ='ccam';
                        }
                        elseif($attinfo->ccpm == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='PM CC';
                            $status ='ccpm';
                        }
                    }


                    $amattstatus= null;
                    $pmattstatus= null;

                    $amsubjpresent = 0;
                    $amsubjlate = 0;
                    $amsubjabsent = 0;
                    $subjects_amatt = array();
                    $subjects_pmatt = array();
                    if(count($eachday->amsubjects)>0)
                    {
                        foreach($eachday->amsubjects as $eachamsubjatt)
                        {
                            $subjamattinfo = collect($studentsubjectattendance)->where('student_id', $student->id)->where('subject_id', $eachamsubjatt->id)->where('date', $eachday->dates)->first();
                            if($subjamattinfo)
                            {
                                if($subjamattinfo->status == 'absent')
                                {
                                    $amsubjabsent+=1;
                                    array_push($subjects_amatt,(object)array(
                                        'subjdesc'  => $eachamsubjatt->subjdesc,
                                        'status'  => $subjamattinfo->status
                                    ));
                                }elseif($subjamattinfo->status == 'present')
                                {
                                    $amsubjpresent+=1;
                                }
                                elseif($subjamattinfo->status == 'late')
                                {
                                    $amsubjlate+=1;
                                    array_push($subjects_amatt,(object)array(
                                        'subjdesc'  => $eachamsubjatt->subjdesc,
                                        'status'  => $subjamattinfo->status
                                    ));
                                }
                            }
                        }
                        if($amsubjpresent == count($eachday->amsubjects))
                        {
                            $amattstatus = 'present';
                        }else{
                            if($amsubjabsent > 0)
                            {
                                $amattstatus = 'cc';
                            }else{
                                if($amsubjlate > 0)
                                {
                                    $amattstatus = 'late';
                                }else{
                                    
                                }
                            }
                        }
                    }



                    $pmsubjpresent = 0;
                    $pmsubjlate = 0;
                    $pmsubjabsent = 0;

                    if(count($eachday->pmsubjects)>0)
                    {
                        foreach($eachday->pmsubjects as $eachpmsubjatt)
                        {
                            $subjpmattinfo = collect($studentsubjectattendance)->where('student_id', $student->id)->where('subject_id', $eachpmsubjatt->id)->where('date', $eachday->dates)->first();
                            if($subjpmattinfo)
                            {
                                if($subjpmattinfo->status == 'absent')
                                {
                                    $pmsubjabsent+=1;
                                    array_push($subjects_pmatt,(object)array(
                                        'subjdesc'  => $eachamsubjatt->subjdesc,
                                        'status'  => $subjamattinfo->status
                                    ));
                                }elseif($subjpmattinfo->status == 'present')
                                {
                                    $pmsubjpresent+=1;
                                }
                                elseif($subjpmattinfo->status == 'late')
                                {
                                    $pmsubjlate+=1;
                                    array_push($subjects_pmatt,(object)array(
                                        'subjdesc'  => $eachamsubjatt->subjdesc,
                                        'status'  => $subjamattinfo->status
                                    ));
                                }
                            }
                        }
                        if($pmsubjpresent == count($eachday->pmsubjects))
                        {
                            $pmattstatus = 'present';
                        }else{
                            if($pmsubjabsent > 0)
                            {
                                $pmattstatus = 'cc';
                            }else{
                                if($pmsubjlate > 0)
                                {
                                    $pmattstatus = 'late';
                                }else{
                                    
                                }
                            }
                        }
                    }

                    $halfdaypresent = null;
                    $halfdayabsent = null;
                    $halfdaylate = null;
                    $halfdaycc = null;
                    
                    if($amattstatus == 'present')
                    {
                        $halfdaypresent +=1;
                    }
                    if($amattstatus == 'late' )
                    {
                        $halfdaylate +=1;
                    }
                    if($amattstatus == 'cc' )
                    {
                        $halfdaycc += 1;
                    }
                    if($amattstatus == 'absent' )
                    {
                        $halfdayabsent += 1;
                    }

                    if($pmattstatus == 'present')
                    {
                        $halfdaypresent +=1;
                    }
                    if($pmattstatus == 'late' )
                    {
                        $halfdaylate +=1;
                    }
                    if($pmattstatus == 'cc' )
                    {
                        $halfdaycc += 1;
                    }
                    if($pmattstatus == 'absent' )
                    {
                        $halfdayabsent += 1;
                    }

                    $combinedstatus = null;
                    $combinedvalue = null;
                    $combinedstatsarray = array(
                        '1'   =>    $halfdaypresent,
                        '0'   =>    $halfdayabsent,
                        '2'   =>    $halfdaylate,
                        '3'   =>    $halfdaycc,
                    );
                    if($keystatus == 'ABSENT')
                    {
                        $combinedstatus = 0;
                        $combinedvalue = 1;
                    }else{
                        if((count($eachday->amsubjects) == 0 && count($eachday->pmsubjects)==0) || (count($subjects_amatt) == 0 && count($subjects_pmatt) == 0))
                        {
                            $combinedstatus = $status;
                            $combinedvalue = $value;
                        }else{
                            $maxvalue = max($combinedstatsarray);
                            if($maxvalue != null)
                            {
                                // return $maxvalue;
                                if(count($eachday->amsubjects) > 0 && count($eachday->pmsubjects)>0)
                                {
                                    $combinedvalue = $value;
                                    if($status === 0){
                                        $consecutive = 1;
                                        // $combinedstatus = 'ABSENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 1){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PRESENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 2){
                                        $consecutive = 0;
                                        // $combinedstatus = 'LATE';
                                        $combinedstatus = $status;
                                    }elseif($status === 3){
                                        $consecutive = 0;
                                        // $combinedstatus = 'CC';
                                        $combinedstatus = $status;
                                    }elseif($status === 'presentam'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'AM PRESENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 'presentpm'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PM PRESENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 'absentam'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'AM ABSENT';
                                        $combinedstatus = $status;
                                    }elseif($status ==='absentpm'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PM ABSENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 'lateam'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'AM LATE';
                                        $combinedstatus = $status;
                                    }elseif($status === 'latepm'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PM LATE';
                                        $combinedstatus = $status;
                                    }elseif($status === 'ccam'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'AM CC';
                                        $combinedstatus = $status;
                                    }elseif($status === 'ccpm'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PM CC';
                                        $combinedstatus = $status;
                                    }
                                }else{
                                    $consecutive = 0;
                                    $combinedvalue = $value;
                                    if(count($eachday->amsubjects) > 0)
                                    {
                                        if($keystatus == null)
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'PRESENT' || $keystatus == 'ABSENT')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                if($keystatus == 'PRESENT')
                                                {
                                                    // $combinedstatus = 'absentam';
                                                    $combinedstatus = 0;
                                                    // $combinedvalue = 0.5;
                                                }else{
                                                    $combinedstatus = 0;
                                                }
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'LATE' || $keystatus == 'CC')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                if($keystatus == 'LATE')
                                                {
                                                    $combinedstatus = 0;
                                                }else{
                                                    $combinedstatus = 0;
                                                }
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM PRESENT')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM LATE')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM ABSENT')
                                        {
                                            // if($eachday->dates == '2022-09-06' && $student->id == 2396)
                                            // {
                                            //     // return $eachday->amsubjects;
                                            //     return $amattstatus;
                                            // }
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM CC')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                    }
                                    if(count($eachday->pmsubjects) > 0)
                                    {
                                        if($keystatus == null)
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'PRESENT' || $keystatus == 'ABSENT')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                if($keystatus == 'PRESENT')
                                                {
                                                    // $combinedstatus = 'absentam';
                                                    $combinedstatus = 0;
                                                    // $combinedvalue = 0.5;
                                                }else{
                                                    $combinedstatus = 0;
                                                }
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'LATE' || $keystatus == 'CC')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                if($keystatus == 'LATE')
                                                {
                                                    $combinedstatus = 0;
                                                }else{
                                                    $combinedstatus = 0;
                                                }
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM PRESENT')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM LATE')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM CC')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // if($halfdaypresent == 1)
                    // {
                    //     if($amattstatus == 'present')
                    //     {
                    //         $combinedstatus = 'presentam';
                    //     }elseif($pmattstatus = 'present')
                    //     {
                    //         $combinedstatus = 'presentpm';
                    //     }
                    // }
                    // elseif($halfdaypresent == 2)
                    // {
                    //     $combinedstatus = 1;
                    // }else{
                        
                    // }

                    array_push($eachstudattendance, (object)array(
                        'value'     => $value,
                        'day'     => $eachday->daynum,
                        'keystatus'     => $keystatus,
                        'status'     => $status,
                        'combinedstatus'     => $combinedstatus,
                        'combinedvalue'     => $combinedvalue,
                        'amstatus'     => $amattstatus,
                        'pmstatus'     => $pmattstatus,
                        'amsubjects'     => $subjects_amatt,
                        'pmsubjects'     => $subjects_pmatt
                    ));
                }
                if($consecutive >= 5)
                {
                    if(strtolower($student->gender) == 'male')
                    {
                        $countconsecutive_male+=1;
                    }
                    if(strtolower($student->gender) == 'female')
                    {
                        $countconsecutive_female+=1;
                    }
                    if(strtolower($student->gender) == 'male')
                    {
                        $nlpamale+=1;
                    }
                    if(strtolower($student->gender) == 'female')
                    {
                        $nlpafemale+=1;
                    }
                }
                $student->attendance = $eachstudattendance;
            }
        }
        return array($students,array((object)[
            'countconsecutive_male' => $countconsecutive_male,
            'countconsecutive_female'   => $countconsecutive_female ,
            'nlpamale' => $nlpamale,
            'nlpafemale'   => $nlpafemale 
        ]));
        
    }
    public static function attendance_sjchssi($students,$currentdays,$levelid,$sectionid,$selectedmonth,$selectedyear,$teacherid,$selectedlact,$setup, $syid,$semid, $strandid)
    {
        
        $currentdays = collect($currentdays)->where('dates','!=','0000-00-00')->values();
        
        $acadprog = DB::table('gradelevel')
            ->select('academicprogram.acadprogcode')
            ->where('gradelevel.id', $levelid)
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->first()->acadprogcode;
            
        $classsubjects = array();

        if(strtolower($acadprog) == 'shs')
        {
            $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid, $syid, $sectionid, $semid, $strandid);
            
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    if(count($subject->schedule)> 0)
                    {
                        
                        array_push($classsubjects, (object)array(
                            'id'        => $subject->subjid,
                            'subjectname'     => $subject->subjcode,
                            'subjdesc'        => $subject->subjdesc,
                            'day'             => str_replace(' ', '', $subject->schedule[0]->day),
                            'starttime'       => $subject->schedule[0]->start,
                            'shift'           => date('A', strtotime($subject->schedule[0]->start)),
                        ));
                    }
                }
            }
            
        }else{
            $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid, $syid, $sectionid, null, null);
            if(count($subjects)>0)
            {
                foreach($subjects as $subject)
                {
                    if(count($subject->schedule)> 0)
                    {
                        
                        array_push($classsubjects, (object)array(
                            'id'              => $subject->subjid,
                            'subjectname'     => $subject->subjcode,
                            'subjdesc'        => $subject->subjdesc,
                            'day'             => str_replace(' ', '', $subject->schedule[0]->day),
                            'starttime'       => $subject->schedule[0]->start,
                            'shift'           => date('A', strtotime($subject->schedule[0]->start)),
                        ));
                    }
                }
            }
        }
        $classsubjects = collect($classsubjects)->unique('id')->values()->all();
        // return $classsubjects;
        if(count($currentdays)>0)
        {
            foreach($currentdays as $currentday)
            {
                
                $hasthissubjectam = array();
                $hasthissubjectpm = array();
                if(count($classsubjects)>0)
                {
                    foreach($classsubjects as $classsubject)
                    {
                        if(strpos($classsubject->day, $currentday->daystr) !== false){
                            if($classsubject->shift == 'AM')
                            {
                                array_push($hasthissubjectam, $classsubject);
                            }else{
                                array_push($hasthissubjectpm, $classsubject);
                            }
                        } 
                    }
                }
                // $currentday->amsubjects = collect($classsubjects)->where('day', $currentday->daystr)->where('shift','AM')->all();
                // $currentday->pmsubjects = collect($classsubjects)->where('day', $currentday->daystr)->where('shift','PM')->all();
                $currentday->amsubjects = $hasthissubjectam;
                $currentday->pmsubjects = $hasthissubjectpm;
                // if($currentday->daystr == 'Mon')
                // {
                //     return $currentday->amsubjects;
                // }
            }
            $studattendance = Db::table('studattendance')
                ->select('studid','present','absent','tardy','cc','tdate','presentam','presentpm','absentam','absentpm','lateam','latepm','ccam','ccpm','tdate')
                ->whereIn('tdate',  collect($currentdays)->pluck('dates'))
                ->whereIn('studid', collect($students)->pluck('id'))
                ->where('deleted','0')
                // ->where('syid',$syid)
                ->get();

                
            $studentsubjectattendance = DB::table('studentsubjectattendance')
                ->whereIn('student_id', collect($students)->pluck('id'))
                ->where('section_id', $sectionid)
                ->whereIn('subject_id', collect($classsubjects)->pluck('id'))
                ->whereIn('date',  collect($currentdays)->pluck('dates'))
                ->where('deleted','0')
                ->get();

        }else{
            $studattendance = array();
            $studentsubjectattendance = [];
        }

        
        $countconsecutive_male = 0;
        $countconsecutive_female = 0;
        $nlpamale = 0;
        $nlpafemale = 0;
        // null = no attendance;
        // 0 = absent;
        // 1 = present;
        // 2 = late;
        // 3 = cc;
        
        if(count($students)>0)
        {
            foreach($students as $student)
            {
                $consecutive = 0;
                $eachstudattendance = array();
                foreach($currentdays as $eachday)
                {
                    $attinfo = collect($studattendance)->where('studid', $student->id)->where('tdate', $eachday->dates)->first();
                    $value =NULL;
                    $keystatus =NULL;
                    $status =NULL;
                    if($attinfo)
                    {
                        if($attinfo->present == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='PRESENT';
                            $status =1;
                        }
                        elseif($attinfo->presentam == 1)
                        {
                            $consecutive = 0;
                            $value = 0.5;
                            $keystatus ='AM PRESENT';
                            $status ='presentam';
                        }
                        elseif($attinfo->presentpm == 1)
                        {
                            $consecutive = 0;
                            $value =0.5;
                            $keystatus ='PM PRESENT';
                            $status ='presentpm';
                        }
                        elseif($attinfo->tardy == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='LATE';
                            $status =2;
                        }
                        elseif($attinfo->lateam == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='AM LATE';
                            $status ='lateam';
                        }
                        elseif($attinfo->latepm == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='PM LATE';
                            $status ='latepm';
                        }
                        elseif($attinfo->absent == 1)
                        {
                            $consecutive+=1;
                            $value =0;
                            $keystatus ='ABSENT';
                            $status =0;
                        }
                        elseif($attinfo->absentam == 1)
                        {
                            $consecutive = 0;
                            $value =0.5;
                            $keystatus ='AM ABSENT';
                            $status ='absentam';
                        }
                        elseif($attinfo->absentpm == 1)
                        {
                            $consecutive = 0;
                            $value =0.5;
                            $keystatus ='PM ABSENT';
                            $status ='absentpm';
                        }
                        elseif($attinfo->cc == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='CC';
                            $status =3;
                        }
                        elseif($attinfo->ccam == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='AM CC';
                            $status ='ccam';
                        }
                        elseif($attinfo->ccpm == 1)
                        {
                            $consecutive = 0;
                            $value =1;
                            $keystatus ='PM CC';
                            $status ='ccpm';
                        }
                    }
                    
                    $amattstatus= null;
                    $pmattstatus= null;

                    $amsubjpresent = 0;
                    $amsubjlate = 0;
                    $amsubjabsent = 0;
                    $subjects_amatt = array();
                    $subjects_pmatt = array();
                    
                    if(count($eachday->amsubjects)>0)
                    {
                        foreach($eachday->amsubjects as $eachamsubjatt)
                        {
                            $subjamattinfo = collect($studentsubjectattendance)->where('student_id', $student->id)->where('subject_id', $eachamsubjatt->id)->where('date', $eachday->dates)->first();
                            if($subjamattinfo)
                            {
                                if($subjamattinfo->status == 'absent')
                                {
                                    $amsubjabsent+=1;
                                    array_push($subjects_amatt,(object)array(
                                        'subjdesc'  => $eachamsubjatt->subjdesc,
                                        'status'  => $subjamattinfo->status
                                    ));
                                }elseif($subjamattinfo->status == 'present')
                                {
                                    $amsubjpresent+=1;
                                }
                                elseif($subjamattinfo->status == 'late')
                                {
                                    $amsubjlate+=1;
                                    array_push($subjects_amatt,(object)array(
                                        'subjdesc'  => $eachamsubjatt->subjdesc,
                                        'status'  => $subjamattinfo->status
                                    ));
                                }
                            }
                        }
                        if($amsubjpresent == count($eachday->amsubjects))
                        {
                            $amattstatus = 'present';
                        }else{
                            if($amsubjabsent > 0)
                            {
                                $amattstatus = 'cc';
                            }else{
                                if($amsubjlate > 0)
                                {
                                    $amattstatus = 'late';
                                }else{
                                    
                                }
                            }
                        }
                    }
                    // return $subjects_amatt;



                    $pmsubjpresent = 0;
                    $pmsubjlate = 0;
                    $pmsubjabsent = 0;

                    if(count($eachday->pmsubjects)>0)
                    {
                        foreach($eachday->pmsubjects as $eachpmsubjatt)
                        {
                            $subjpmattinfo = collect($studentsubjectattendance)->where('student_id', $student->id)->where('subject_id', $eachpmsubjatt->id)->where('date', $eachday->dates)->first();
                            if($subjpmattinfo)
                            {
                                if($subjpmattinfo->status == 'absent')
                                {
                                    $pmsubjabsent+=1;
                                    array_push($subjects_pmatt,(object)array(
                                        'subjdesc'  => $eachamsubjatt->subjdesc,
                                        'status'  => $subjpmattinfo->status ?? null
                                    ));
                                }elseif($subjpmattinfo->status == 'present')
                                {
                                    $pmsubjpresent+=1;
                                }
                                elseif($subjpmattinfo->status == 'late')
                                {
                                    $pmsubjlate+=1;
                                    array_push($subjects_pmatt,(object)array(
                                        'subjdesc'  => $eachamsubjatt->subjdesc,
                                        'status'  => $subjpmattinfo->status ?? null
                                    ));
                                }
                            }
                        }
                        if($pmsubjpresent == count($eachday->pmsubjects))
                        {
                            $pmattstatus = 'present';
                        }else{
                            if($pmsubjabsent > 0)
                            {
                                $pmattstatus = 'cc';
                            }else{
                                if($pmsubjlate > 0)
                                {
                                    $pmattstatus = 'late';
                                }else{
                                    
                                }
                            }
                        }
                    }

                    $halfdaypresent = null;
                    $halfdayabsent = null;
                    $halfdaylate = null;
                    $halfdaycc = null;
                    
                    if($amattstatus == 'present')
                    {
                        $halfdaypresent +=1;
                    }
                    if($amattstatus == 'late' )
                    {
                        $halfdaylate +=1;
                    }
                    if($amattstatus == 'cc' )
                    {
                        $halfdaycc += 1;
                    }
                    if($amattstatus == 'absent' )
                    {
                        $halfdayabsent += 1;
                    }

                    if($pmattstatus == 'present')
                    {
                        $halfdaypresent +=1;
                    }
                    if($pmattstatus == 'late' )
                    {
                        $halfdaylate +=1;
                    }
                    if($pmattstatus == 'cc' )
                    {
                        $halfdaycc += 1;
                    }
                    if($pmattstatus == 'absent' )
                    {
                        $halfdayabsent += 1;
                    }

                    $combinedstatus = null;
                    $combinedvalue = null;
                    $combinedstatsarray = array(
                        '1'   =>    $halfdaypresent,
                        '0'   =>    $halfdayabsent,
                        '2'   =>    $halfdaylate,
                        '3'   =>    $halfdaycc,
                    );
                    if($keystatus == 'ABSENT')
                    {
                        $combinedstatus = 0;
                        $combinedvalue = 1;
                    }else{
                        if((count($eachday->amsubjects) == 0 && count($eachday->pmsubjects)==0) || (count($subjects_amatt) == 0 && count($subjects_pmatt) == 0))
                        {
                            $combinedstatus = $status;
                            $combinedvalue = $value;
                        }else{
                            $maxvalue = max($combinedstatsarray);
                            if($maxvalue != null)
                            {
                                // return $maxvalue;
                                if(count($eachday->amsubjects) > 0 && count($eachday->pmsubjects)>0)
                                {
                                    $combinedvalue = $value;
                                    if($status === 0){
                                        $consecutive = 1;
                                        // $combinedstatus = 'ABSENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 1){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PRESENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 2){
                                        $consecutive = 0;
                                        // $combinedstatus = 'LATE';
                                        $combinedstatus = $status;
                                    }elseif($status === 3){
                                        $consecutive = 0;
                                        // $combinedstatus = 'CC';
                                        $combinedstatus = $status;
                                    }elseif($status === 'presentam'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'AM PRESENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 'presentpm'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PM PRESENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 'absentam'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'AM ABSENT';
                                        $combinedstatus = $status;
                                    }elseif($status ==='absentpm'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PM ABSENT';
                                        $combinedstatus = $status;
                                    }elseif($status === 'lateam'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'AM LATE';
                                        $combinedstatus = $status;
                                    }elseif($status === 'latepm'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PM LATE';
                                        $combinedstatus = $status;
                                    }elseif($status === 'ccam'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'AM CC';
                                        $combinedstatus = $status;
                                    }elseif($status === 'ccpm'){
                                        $consecutive = 0;
                                        // $combinedstatus = 'PM CC';
                                        $combinedstatus = $status;
                                    }
                                }else{
                                    $consecutive = 0;
                                    $combinedvalue = $value;
                                    if(count($eachday->amsubjects) > 0)
                                    {
                                        if($keystatus == null)
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'PRESENT' || $keystatus == 'ABSENT')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                if($keystatus == 'PRESENT')
                                                {
                                                    // $combinedstatus = 'absentam';
                                                    $combinedstatus = 0;
                                                    // $combinedvalue = 0.5;
                                                }else{
                                                    $combinedstatus = 0;
                                                }
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'LATE' || $keystatus == 'CC')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                if($keystatus == 'LATE')
                                                {
                                                    $combinedstatus = 0;
                                                }else{
                                                    $combinedstatus = 0;
                                                }
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM PRESENT')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM LATE')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM ABSENT')
                                        {
                                            // if($eachday->dates == '2022-09-06' && $student->id == 2396)
                                            // {
                                            //     // return $eachday->amsubjects;
                                            //     return $amattstatus;
                                            // }
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM CC')
                                        {
                                            if($amattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($amattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($amattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                    }
                                    if(count($eachday->pmsubjects) > 0)
                                    {
                                        if($keystatus == null)
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'PRESENT' || $keystatus == 'ABSENT')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                if($keystatus == 'PRESENT')
                                                {
                                                    // $combinedstatus = 'absentam';
                                                    $combinedstatus = 0;
                                                    // $combinedvalue = 0.5;
                                                }else{
                                                    $combinedstatus = 0;
                                                }
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'LATE' || $keystatus == 'CC')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                if($keystatus == 'LATE')
                                                {
                                                    $combinedstatus = 0;
                                                }else{
                                                    $combinedstatus = 0;
                                                }
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM PRESENT')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM LATE')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                        if($keystatus == 'AM CC')
                                        {
                                            if($pmattstatus == 'late')
                                            {
                                                $combinedstatus = 2;
                                                $combinedvalue = 1;
                                            }
                                            elseif($pmattstatus == 'absent')
                                            {
                                                $combinedstatus = 0;
                                                $combinedvalue = 0;
                                            }elseif($pmattstatus == 'cc'){
                                                $combinedstatus = 3;
                                                $combinedvalue = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // if($halfdaypresent == 1)
                    // {
                    //     if($amattstatus == 'present')
                    //     {
                    //         $combinedstatus = 'presentam';
                    //     }elseif($pmattstatus = 'present')
                    //     {
                    //         $combinedstatus = 'presentpm';
                    //     }
                    // }
                    // elseif($halfdaypresent == 2)
                    // {
                    //     $combinedstatus = 1;
                    // }else{
                        
                    // }

                    array_push($eachstudattendance, (object)array(
                        'value'     => $value,
                        'day'     => $eachday->daynum,
                        'keystatus'     => $keystatus,
                        'status'     => $status,
                        'combinedstatus'     => $combinedstatus,
                        'combinedvalue'     => $combinedvalue,
                        'amstatus'     => $amattstatus,
                        'pmstatus'     => $pmattstatus,
                        'amsubjects'     => $subjects_amatt,
                        'pmsubjects'     => $subjects_pmatt
                    ));
                    if($consecutive >= 5)
                    {
                        if(strtolower($student->gender) == 'male')
                        {
                            $countconsecutive_male+=1;
                        }
                        if(strtolower($student->gender) == 'female')
                        {
                            $countconsecutive_female+=1;
                        }
                        if(strtolower($student->gender) == 'male')
                        {
                            $nlpamale+=1;
                        }
                        if(strtolower($student->gender) == 'female')
                        {
                            $nlpafemale+=1;
                        }
                    }
                }
                $student->attendance = $eachstudattendance;
            }
        }
        return array($students,array((object)[
            'countconsecutive_male' => $countconsecutive_male,
            'countconsecutive_female'   => $countconsecutive_female ,
            'nlpamale' => $nlpamale,
            'nlpafemale'   => $nlpafemale 
        ]));
        
    }
}
