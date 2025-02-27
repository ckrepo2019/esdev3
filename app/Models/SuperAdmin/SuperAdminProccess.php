<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Model;
use DB;

class SuperAdminProccess extends Model
{
    public static function student_promote(
        $syid = null,
        $semid = null,
        $studid = null
    ){

        try{

            // $gradelevel = DB::table('studinfo')
            //                 ->where('id',$studid)
            //                 ->select('levelid')
            //                 ->first();

            
            // if(isset($gradelevel->levelid)){
            //     $gradelevel = DB::table('gradelevel')->where('id',$studinfo->levelid)->first();

            //     $next_grade_level = DB::table('gradelevel')
            //                             ->where('sortid',$gradelevel->sortid+1)
            //                             ->where('deleted',0)
            //                             ->skip(1)
            //                             ->take(1);

              
            // }

            // return $gradelevel;
          
            $studinfo = self::get_student_academiprogram($studid);

            if($studinfo->acadprogid == 5){

                if($semid == 1){

                    $current_enrollment = DB::table('sh_enrolledstud')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('studid',$studid)
                                    ->first();

                    if(isset($current_enrollment->studid)){

                        $next_enrollment = DB::table('sh_enrolledstud')
                                                ->where('deleted',0)
                                                ->where('syid',$syid)
                                                ->where('semid',2)
                                                ->where('studid',$studid)
                                                ->count();

                        if($next_enrollment == 0){

                        DB::table('sh_enrolledstud')
                                ->insert([
                                    'studid'=>$current_enrollment->studid,
                                    'syid'=>$syid,
                                    'semid'=>2,
                                    'levelid'=>$current_enrollment->levelid,
                                    'sectionid'=>$current_enrollment->sectionid,
                                    'strandid'=>$current_enrollment->strandid,
                                    'blockid'=>$current_enrollment->blockid,
                                    'teacherid'=>$current_enrollment->teacherid,
                                    'dateenrolled'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'createdby'=>$current_enrollment->createdby,
                                    'createddatetime'=>$current_enrollment->createddatetime,
                                    'studstatus'=>$current_enrollment->studstatus,
                                    'promotionstatus'=>$current_enrollment->promotionstatus,
                                ]);

                        }

                        $current_enrollment = DB::table('sh_enrolledstud')
                            ->where('deleted',0)
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->where('studid',$studid)
                            ->take(1)
                            ->update([
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'promotionstatus'=>1
                            ]);
                    }

                }

                $schoolinfo = DB::table('schoolinfo')->first();

             

            }
            else{
                
                
            //     // DB::table('studinfo')
            //     //         ->where('id',$studid)
            //     //         ->where('deleted',0)
            //     //         ->update([
            //     //             'studstatus'=>0,
            //     //             'sectionid'=>null,
            //     //             'sectionname'=>null,
            //     //             'updatedby'=>auth()->user()->id,
            //     //             'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
            //     //             'feesid'=>null
            //     //         ]);


            //     // DB::table('college_enrolledstud')
            //     //         ->where('deleted',0)
            //     //         ->where('syid',$syid)
            //     //         ->where('semid',$semid)
            //     //         ->where('studid',$studid)
            //     //         ->update([
            //     //             'updatedby'=>auth()->user()->id,
            //     //             'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
            //     //             'promotionstatus'=>1
            //     //         ]);
            
            }

          

            return array((object)[
                'status'=>1,
                'data'=>'Promoted Successfully'
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
    

    public static function get_student_academiprogram($studid = null){

        $student_info = DB::table('studinfo')
                        ->where('studinfo.id',$studid)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->select('acadprogid')
                        ->first();

        return $student_info;

    }
    
    public static function add_student_sched($studid = null, $schedid = null, $syid = null, $semid = null, $codeid = null){


        

        try{
            
            $id = DB::table('college_studsched')
                    ->insertGetID([
                        'studid'=>$studid,
                        'schedid'=>$schedid,
                        'schedcodeid'=>$codeid,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            
        

            return array((object)[
                'status'=>1,
                'id'=>$id,
                'data'=>'Added Successfully!'
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


    public static function remove_student_sched($studid = null, $id = null){
        

        try{

            DB::table('college_studsched')
                    ->where('studid',$studid)
                    ->where('schedid',$id)
					->where('deleted',0)
                    ->take(1)
                    ->update([
                        'deleted'=>1,
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                'status'=>1,
                'data'=>'Deleted Successfully!'
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


    //01-12-2020
    public static function set_section($syid = null, $semid = null, $studid = null, $sectionid = null, $section_desc = null){

        try{

            DB::table('studinfo')
                    ->where('deleted',0)
                    ->where('id',$studid)
                    ->take(1)
                    ->update([
                        'sectionid'=> $sectionid,
                        'sectionname'=> $section_desc,
                    ]);

            DB::table('college_enrolledstud')
                    ->where('deleted',0)
                    ->where('studid',$studid)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->take(1)
                    ->update([
                        'sectionid'=> $sectionid,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully!'
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

    public static function shift_course($studid = null, $courseid = null, $curriculumid = null){

        try{

            $check_enrollment = DB::table('studinfo')
                                    ->where('id',$studid)
                                    ->whereIn('studstatus',[1,2,4])
                                    ->where('deleted',0)
                                    ->count();

            if($check_enrollment > 0){

                return array((object)[
                    'status'=>0,
                    'data'=>'Student is already Enrolled!'
                ]);
                
            }

            DB::table('studinfo')
                ->where('id',$studid)
                ->where('deleted',0)
                ->take(1)
                ->update([
                    'courseid'=>$courseid,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('college_studentcurriculum')
                ->join('college_curriculum',function($join) use($courseid){
                    $join->on('college_studentcurriculum.curriculumid','=','college_curriculum.id');
                    $join->where('college_curriculum.deleted',0);
                    $join->where('college_curriculum.courseID',$courseid);
                })
                ->where('college_studentcurriculum.deleted',0)
                ->where('college_studentcurriculum.studid',$studid)
                ->update([
                    'college_studentcurriculum.deleted'=>1,
                    'college_studentcurriculum.deletedby'=>auth()->user()->id,
                    'college_studentcurriculum.deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('college_studentcurriculum')
                ->insert([
                    'studid'=>$studid,
                    'curriculumid'=>$curriculumid,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'data'=>'Transfered Successfully!'
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



}
