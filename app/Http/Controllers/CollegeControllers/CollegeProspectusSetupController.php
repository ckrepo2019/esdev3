<?php

namespace App\Http\Controllers\CollegeControllers;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;

class CollegeProspectusSetupController extends \App\Http\Controllers\Controller
{

      public static function create_curriculum(Request $request){

            try{
                  $curriculum_name = $request->get('curriculumname');
                  $courseid = $request->get('courseid');

                  $check = DB::table('college_curriculum')
                              ->where('curriculumname',$curriculum_name)
                              ->where('courseID',$courseid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Curriculum Already Exist'
                        ]);
                  }

                  DB::table('college_curriculum')
                        ->insert([
                              'curriculumname'=>$curriculum_name,
                              'courseid'=>$courseid,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Curriculum Created'
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update_curriculum(Request $request){

            try{
                  $curid = $request->get('id');
                  $curriculum_name = $request->get('curriculumname');
                  $courseid = $request->get('courseid');

                  DB::table('college_curriculum')
                              ->where('curriculumname',$curriculum_name)
                              ->where('courseID',$courseid)
                              ->where('deleted',0)
                              ->count();

                 

                  // $check = DB::table('college_curriculum')
                  //       ->where('id',$curid)
                  //       ->where('courseid',$courseid)
                  //       ->take(1)
                  //       ->update([
                  //             'curriculumname'=>$curriculum_name,
                  //             'updatedby'=>auth()->user()->id,
                  //             'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  //       ]);

                  $checkExist = DB::table('college_curriculum')
                        ->where('id', '!=', $curid)
                        ->where('courseid', $courseid)
                        ->where('curriculumname', $curriculum_name)
                        ->where('deleted', 0)
                        ->count();

                  if ($checkExist > 0) {
                        return array((object)[
                              'status' => 2,
                              'message' => 'Curriculum Already Exists'
                        ]);
                  }

                  $updated = DB::table('college_curriculum')
                        ->where('id', $curid)
                        ->where('courseid', $courseid)
                        ->update([
                              'curriculumname' => $curriculum_name,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  if ($updated) {
                        return array((object)[
                              'status' => 1,
                              'message' => 'Curriculum Updated'
                        ]);
                  } else {
                        return array((object)[
                              'status' => 0,
                              'message' => 'No changes made or curriculum not found'
                        ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Curriculum Updated'
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function delete_curriculum(Request $request){

            try{
                  $curid = $request->get('id');
                  $courseid = $request->get('courseid');
                  
                  $check = DB::table('college_prospectus')
                              ->where('curriculumid',$curid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Contains Subject'
                        ]);
                  }

                  DB::table('college_curriculum')
                        ->where('id',$curid)
                        ->where('courseid',$courseid)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Curriculum Deleted'
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function curriculum_subjects(Request $request){

            $courseid = $request->get('courseid');
            $curriculumid = $request->get('curriculumid');

            $subjects = Db::table('college_prospectus')
                              ->where('deleted',0)
                              ->where('courseID',$courseid)
                              ->where('curriculumID',$curriculumid)
                              ->select(
                                    'id',
                                    'courseID',
                                    'subjectID',
                                    'semesterID',
                                    'yearID',
                                    'lecunits',
                                    'labunits',
                                    'subjDesc',
                                    'subjCode',
                                    'subjClass',
                                    'curriculumID',
                                    'psubjsort',
                                    DB::raw("CONCAT(college_prospectus.subjCode,' - ',college_prospectus.subjDesc) as text")
                              )
                              ->get();

            
            $prereq = Db::table('college_prospectus')
                              ->where('college_prospectus.deleted',0)
                              ->where('college_prospectus.courseID',$courseid)
                              ->where('college_prospectus.curriculumID',$curriculumid)
                              ->join('college_subjprereq',function($join){
                                    $join->on('college_prospectus.id','=','college_subjprereq.subjID');
                                    $join->where('college_subjprereq.deleted',0);
                              })
                              ->selecT(
                                    'subjID',
                                    'prereqsubjID'
                              )
                              ->get();



            return array([
                  'subjects'=>$subjects,
                  'prereq'=>$prereq
            ]);

      }



   
    
}
