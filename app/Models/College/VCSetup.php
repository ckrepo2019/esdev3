<?php

namespace App\Models\College;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Teacher\VirtualClassroomCodeGenerator;
class VCSetup extends Model
{
    public static function sectionsunder()
    {
        $teacherid = Db::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first()
            ->id;

        $syid = DB::table('sy')
            ->where('isactive','1')
            ->first()->id;

        $sections = Db::table('college_classsched')
            ->select('college_classsched.*','college_prospectus.subjCode','college_sections.sectionDesc','college_prospectus.yearID')
            ->join('college_prospectus','college_classsched.subjectID','=','college_prospectus.id')
            ->join('college_sections','college_classsched.sectionID','=','college_sections.id')
            ->where('college_classsched.deleted','0')
            ->where('college_prospectus.deleted','0')
            ->where('college_classsched.syID',$syid)
            ->where('college_classsched.teacherID',$teacherid)
            ->distinct()
            ->get();
            
            
        $classrooms = DB::table('virtualclassrooms')
            ->where('userid', auth()->user()->id)
            // ->where('deleted','0')
            // ->where('acadprogid','6')
            ->get();
            
            
        if(count($classrooms)>0)
        {
            foreach($classrooms as $classroom)
            {
                // return count(collect($sections)->where('sectionID', $classroom->sectionid)->contains('subjectID', $classroom->subjectid));
                if(!collect(collect($sections)->where('sectionID', $classroom->sectionid)->contains('subjectID', $classroom->subjectid)))
                {
                    DB::table('virtualclassrooms')
                        ->where('id', $classroom->id)
                        ->update([
                                'deleted' => 1,
                                'deletedby' => auth()->user()->id,
                                'deleteddatetime' => date('Y-m-d H:i:s')
                            ]);
                }
            }
        }
        // return count($sections);
        if(count($sections)>0)
        {
            foreach($sections as $sectionclassroom)
            {
                $checkifexists = DB::table('virtualclassrooms')
                    ->where('sectionid', $sectionclassroom->sectionID)
                    ->where('subjectid', $sectionclassroom->subjectID)
                    ->where('userid', auth()->user()->id)
                    ->where('deleted','0')
                    ->where('acadprogid','6')
                    ->first();
                    
                $students = DB::table('studinfo')
                    ->select('studinfo.userid')
                    ->leftJoin('gradelevel','studinfo.levelid','=','gradelevel.id')
                    ->where('gradelevel.acadprogid','6')
                    ->where('studinfo.sectionid',$sectionclassroom->sectionID)
                    ->where('studinfo.studstatus','!=','0')
                    ->where('studinfo.mol','1')
                    ->get();
                    
                if(count(collect($checkifexists))==0)
                {
                    $levelname = DB::table('college_sections')
                        ->select('college_year.yearDesc','college_sections.sectionDesc')
                        ->leftJoin('college_year', 'college_sections.yearID','=','college_year.levelid')
                        ->where('college_sections.id', $sectionclassroom->sectionID)
                        ->where('college_sections.deleted','0')
                        ->where('college_year.deleted','0')
                        ->first();
                        
                    // $subjectcode = DB::table('college_prospectus')
                    //     ->where('id', $sectionclassroom->subjectID)
                    //     ->where('deleted','0')
                    //     ->first();
                        
                    // if(count(collect($subjectcode)) > 0)
                    // {
                        if(count(collect($levelname)) >0)
                        {
                            $classroomid = Db::table('virtualclassrooms')
                                ->insertGetId([
                                    'userid'            => auth()->user()->id,
                                    'classroomname'     => $levelname->yearDesc.' - '.$levelname->sectionDesc.' - '.$sectionclassroom->subjCode,
                                    'sectionid'         => $sectionclassroom->sectionID,
                                    'subjectid'         => $sectionclassroom->subjectID,
                                    'code'              => VirtualClassroomCodeGenerator::codegeneration(),
                                    'password'          => VirtualClassroomCodeGenerator::passwordgeneration(),
                                    'acadprogid'        => '6',
                                    'createddatetime'   => date('Y-m-d H:i:s')
                                ]);
        
                            if(count($students)>0)
                            {
                                foreach($students as $student)
                                {
                                    DB::table('virtualclassroomstud')
                                        ->insert([
                                            'classroomid'       => $classroomid,
                                            'studid'            => $student->userid,
                                            'createddatetime'   => date('Y-m-d H:i:s')
                                        ]);
                                }
                            }
                        }
                    // }
                    // return VirtualClassroomCodeGenerator::codegeneration();
                    
                }else{
                    $levelname = DB::table('gradelevel')
                        ->where('id', $sectionclassroom->yearID)
                        ->first();
                        
                    DB::table('virtualclassrooms')
                        ->where('sectionid', $sectionclassroom->sectionID)
                        ->where('subjectid', $sectionclassroom->subjectID)
                        ->where('userid', auth()->user()->id)
                        ->where('deleted','0')
                        ->where('acadprogid','6')
                        ->update([
                            'classroomname' => $levelname->levelname.' - '.$sectionclassroom->sectionDesc.' - '.$sectionclassroom->subjCode
                            ]);
                    if(count($students)>0)
                    {
                        foreach($students as $student)
                        {
                            $checkstudentifexists = DB::table('virtualclassroomstud')
                                ->where('studid', $student->userid)
                                ->where('classroomid', $checkifexists->id)
                                ->count();

                            if($checkstudentifexists == 0)
                            {
                                DB::table('virtualclassroomstud')
                                    ->insert([
                                        'classroomid'       => $checkifexists->id,
                                        'studid'            => $student->userid,
                                        'createddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
