<?php

namespace App\Models\ChairPerson;

use Illuminate\Database\Eloquent\Model;
use DB;

class ChairPersonProccess extends Model
{
    public static function approve_grades_status(
        $statid = null,
        $field = null
    ){
        $check = Db::table('college_grade_status')
                    ->where('id',$statid)
                    ->where('deleted',0)
                    ->first();

        if($check->$field == 1){

            Db::table('college_grade_status')
                    ->where('id',$statid)
                    ->update([
                        $field=>2,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return 1;

        }
        else{
            return 0;
        }

    }

    public static function pending_grade_status(
        $statid = null,
        $field = null
    ){
        $check = Db::table('college_grade_status')
                    ->where('id',$statid)
                    ->where('deleted',0)
                    ->first();

        if($check->$field == 2  || $check->$field == 1 || $check->$field == 3){

            Db::table('college_grade_status')
                    ->where('id',$statid)
                    ->update([
                        $field=>4,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return 1;

        }
        else{
            return 0;
        }

    }
    
    public static function post_grade_status(
        $statid = null,
        $field = null
    ){
        $check = Db::table('college_grade_status')
                    ->where('id',$statid)
                    ->where('deleted',0)
                    ->first();

        $posteddatetime = '';

        if($field == 'prelimstatus'){
            $posteddatetime = 'plpdatetime';
        }
        else if($field == 'midtermstatus'){
            $posteddatetime = 'mtpdatetime';
        }
        else if($field == 'prefistatus'){
            $posteddatetime = 'pfpdatetime';
        }
        else if($field == 'finalstatus'){
            $posteddatetime = 'fpdatetime';
        }

        if($check->$field == 2  || $check->$field == 1){

            Db::table('college_grade_status')
                    ->where('id',$statid)
                    ->update([
                        $field=>3,
                        $posteddatetime=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return 1;

        }
        else{
            return 0;
        }

    }
    
    public static function store_section(
        $syid = null, 
        $semid = null, 
        $courseid = null, 
        $curriculumid = null, 
        $specification = null, 
        $sectionname = null,
        $levelid = null
    ){ 

        try{

            if($specification == 1){

                $sectionID = DB::table('college_sections')
                                ->insertGetId([
                                    'syID'=>$syid,
                                    'semesterID'=>$semid,
                                    'courseID'=>$courseid,
                                    'yearID'=>$levelid,
                                    'curriculumid'=>$curriculumid,
                                    'section_specification'=>$specification,
                                    'sectionDesc'=>$sectionname,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);

                $prospectussubjects = DB::table('college_prospectus')
                                        ->where('college_prospectus.deleted','0')
                                        ->where('courseID',$courseid)
                                        ->where('yearID',$levelid)
                                        ->where('semesterID',$semid)
                                        ->where('curriculumID',$curriculumid)
                                        ->select('college_prospectus.id')
                                        ->get();

                foreach($prospectussubjects as $prospectussubject){

                    DB::table('college_classsched')->insert([
                        'syID'=>$syid,
                        'semesterID'=>$semid,
                        'sectionID'=> $sectionID,
                        'teacherID'=>null,
                        'subjectID'=>$prospectussubject->id,
                        ]);

                }


                return array((object)[
                    'status'=>1,
                    'data'=>$sectionID
                ]);

            }else{

                $sectionID = DB::table('college_sections')
                                ->insertGetId([
                                    'syID'=>$syid,
                                    'semesterID'=>$semid,
                                    'courseID'=>$courseid,
                                    'section_specification'=>$specification,
                                    'sectionDesc'=>$sectionname,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);

                return array((object)[
                    'status'=>1,
                    'data'=>$sectionID
                ]);
            }

           

        }catch(\Exception $e){

            return array((object)[
                'status'=>0,
                'data'=>'Somethin went wrong!'
            ]);


        }
    }

    public static function add_instructor($schedid = null , $teacherid = null){

        try{

            DB::table('college_classsched')
                ->where('deleted',0)
                ->where('id',$schedid)
                ->update([
                    'teacherID'=>$teacherid,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'updatedby'=>auth()->user()->id
                ]);

            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully'
            ]);


        }catch(\Exception $e){

            DB::table('zerrorlogs')
                    ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                'status'=>0,
                'data'=>'Something went wrong!'
            ]);
        }
        
    }

    public static function add_section_subject(
        $syid = null,
        $semid = null,
        $sectionid = null,
        $subjectid = null
    ){
        try{
            
            DB::table('college_classsched')
                    ->insert([
                        'syID'=>$syid,
                        'semesterID'=>$semid,
                        'sectionID'=>$sectionid,
                        'subjectID'=>$subjectid,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                'status'=>1,
                'data'=>'Created Successfully'
            ]);
        

        }catch(\Exception $e){

            DB::table('zerrorlogs')
                    ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                'status'=>0,
                'data'=>'Something went wrong!'
            ]);
      
        }
    }

    
    public static function update_section(
        $sectionid = null,
        $sectionname = null
    ){

        try{
            
            DB::table('college_sections')
                    ->where('id',$sectionid)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'sectionDesc'=>$sectionname
                    ]);

            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully'
            ]);
        

        }catch(\Exception $e){

            DB::table('zerrorlogs')
                    ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                'status'=>0,
                'data'=>'Something went wrong!'
            ]);
      
        }
    
    }

    public static function remove_section(
        $sectionid = null
    ){

        try{

            $check_studinfo = DB::table('studinfo')->where('sectionid',$sectionid)->count();
            $check_enrolledstud = DB::table('college_enrolledstud')->where('sectionID',$sectionid)->count();
            $check_studsched = DB::table('college_studsched')
                                    ->join('college_classsched',function($join) use($sectionid){
                                        $join->on('college_studsched.schedid','=','college_classsched.id');
                                        $join->where('college_classsched.deleted',0);
                                        $join->where('college_classsched.sectionID',$sectionid);
                                    })
                                    ->where('college_studsched.deleted',0)
                                    ->count();

            if($check_studinfo == 0 && $check_enrolledstud  == 0 && $check_studsched ==0){

                DB::table('college_sections')
                        ->where('id',$sectionid)
                        ->update([
                            'deleted'=>1,
                            'deletedby'=>auth()->user()->id,
                            'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);

                return array((object)[
                    'status'=>1,
                    'data'=>'Deleted Successfully'
                ]);

            }else{

                return array((object)[
                    'status'=>0,
                    'data'=>'Failed to remove section. Section is already used.'
                ]);

            }

            
           
           
        }catch(\Exception $e){
            DB::table('zerrorlogs')
                    ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            return array((object)[
                'status'=>0,
                'data'=>'Something went wrong!'
            ]);
        }
    }


}
