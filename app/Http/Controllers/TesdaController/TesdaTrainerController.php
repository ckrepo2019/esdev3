<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class TesdaTrainerController extends Controller
{
      public function get_trainer_schedule(Request $request){
            $courseid = $request->get('courseid');
            $userid = auth()->user()->id;

            $schedules = DB::table('tesda_schedule_details')
                  ->join('tesda_batch_schedule', 'tesda_schedule_details.batch_schedule_id', '=', 'tesda_batch_schedule.id')
                  ->join('tesda_batches', 'tesda_batch_schedule.batch_id', '=', 'tesda_batches.id')
                  ->join('tesda_trainers', 'tesda_schedule_details.trainer_id', '=', 'tesda_trainers.id')
                  ->join('tesda_courses', 'tesda_batches.course_id', 'tesda_courses.id')
                  ->join('tesda_course_competency', 'tesda_batch_schedule.competency_id', '=', 'tesda_course_competency.id')
                  ->leftjoin('building', 'tesda_schedule_details.buildingID', '=', 'building.id')
                  ->leftjoin('rooms', 'tesda_schedule_details.roomID', '=', 'rooms.id')
                  ->where('tesda_trainers.user_id', $userid)
                  ->where('tesda_schedule_details.deleted', 0)
                  ->when($courseid, function ($query) use ($courseid) {
                  $query->where('tesda_courses.id', $courseid);
                  })
                  ->where('tesda_schedule_details.deleted', 0)
                  ->where('tesda_batches.deleted', 0)
                  ->select(
                  'tesda_batches.batch_desc',
                  'tesda_batches.id as batch_id',
                  'tesda_courses.id as course_id',
                  'tesda_courses.course_code',
                  'tesda_courses.course_name',
                  'tesda_course_competency.competency_type',
                  'tesda_course_competency.competency_code',
                  'tesda_course_competency.competency_desc',
                  'tesda_course_competency.hours',
                  DB::raw("CONCAT(DATE_FORMAT(tesda_schedule_details.date_from, '%m/%d/%Y'), ' - ', DATE_FORMAT(tesda_schedule_details.date_to, '%m/%d/%Y')) as date_range"),
                  DB::raw("CONCAT(DATE_FORMAT(tesda_schedule_details.stime, '%h:%i %p'), ' - ', DATE_FORMAT(tesda_schedule_details.etime, '%h:%i %p')) as time_range"),
                  'rooms.roomname',
                  'building.description',
                  'tesda_schedule_details.id as schedid'
                  )
                  ->get();
            
            foreach($schedules as $schedule){
                  $schedule->enrolled = DB::table('tesda_enrolledstud')
                                    ->where('batchid',$schedule->batch_id)
                                    ->where('courseid',$schedule->course_id)
                                    ->where('deleted',0)
                                    ->where('studstatus','!=', 0)
                                    ->count();
            }
            
            
            return $schedules;
            
      }

      public function view_system_grading($schedid){
            return view('tesda_trainer.pages.systemgrading', compact('schedid'));
      }

      public function get_sched_details(Request $request){
            $schedid = $request->get('schedid');

            $schedule_details = DB::table('tesda_schedule_details')
                                    ->join('tesda_batch_schedule', 'tesda_schedule_details.batch_schedule_id', '=', 'tesda_batch_schedule.id')
                                    ->join('tesda_batches', 'tesda_batch_schedule.batch_id', '=', 'tesda_batches.id')
                                    ->join('tesda_trainers', 'tesda_schedule_details.trainer_id', '=', 'tesda_trainers.id')
                                    ->join('tesda_courses', 'tesda_batches.course_id', 'tesda_courses.id')
                                    ->join('tesda_course_competency', 'tesda_batch_schedule.competency_id', '=', 'tesda_course_competency.id')
                                    ->join('users', 'tesda_trainers.user_id', '=', 'users.id')
                                    ->where('tesda_schedule_details.id', $schedid)
                                    ->select(
                                          'users.name',
                                          'tesda_course_competency.competency_type',
                                          'tesda_course_competency.competency_code',
                                          'tesda_course_competency.competency_desc',
                                          'tesda_batches.batch_desc',
                                          'tesda_courses.course_name',
                                          'tesda_courses.course_code',
                                    )
                                    ->first();
            
            return response()->json($schedule_details);
      }

      public function display_ecr_template(Request $request){

            $schedid = $request->get('schedid');

            $grade_info = DB::table('tesda_enrolledstud')
                              ->join('tesda_batches', 'tesda_enrolledstud.batchid', '=', 'tesda_batches.id')
                              ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
                              ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
                              ->join('tesda_studinfo', 'tesda_enrolledstud.id', '=', 'tesda_studinfo.id')
                              ->where('tesda_enrolledstud.studstatus', '!=', 0)
                              ->where('tesda_schedule_details.id', $schedid)
                              ->where('tesda_enrolledstud.deleted', 0)
                              ->groupBy('tesda_enrolledstud.studid')
                              ->select(
                                    'tesda_enrolledstud.studid',
                                    DB::raw("CONCAT(tesda_studinfo.lastname,', ',tesda_studinfo.firstname,' ',IFNULL(SUBSTRING(tesda_studinfo.middlename,1,1),''),'.') as studname"),
                                    'tesda_studinfo.gender'
                                    )
                              ->get();

            $ecr_template = DB::table('tesda_grading_setup')
                        ->join('tesda_batches', 'tesda_grading_setup.id','=' ,'tesda_batches.ecr_template')
                        ->join('tesda_batch_schedule', 'tesda_batches.id', '=', 'tesda_batch_schedule.batch_id')
                        ->join('tesda_schedule_details', 'tesda_batch_schedule.id', '=', 'tesda_schedule_details.batch_schedule_id')
                        ->join('tesda_ecr_component_gradesetup', 'tesda_grading_setup.id', '=', 'tesda_ecr_component_gradesetup.gradID')
                        ->where('tesda_schedule_details.id', $schedid)
                        ->select(
                              'tesda_grading_setup.id as ecrid',
                              'tesda_ecr_component_gradesetup.id as componentid',
                              'tesda_ecr_component_gradesetup.descriptionComp as componentname',
                              'tesda_ecr_component_gradesetup.component',
                              'tesda_ecr_component_gradesetup.column_ECR'
                        )
                        ->get();
            
            $component_ids = $ecr_template->pluck('componentid')->filter();

            $subgrading_components = DB::table('tesda_subgrading')
                  ->whereIn('ecrID', $component_ids)
                  ->where('deleted', 0)
                  ->get()
                  ->groupBy('ecrID');

            $final_data = [];

            foreach($ecr_template as $ecr){

                  $components = [
                        'componentid' => $ecr->componentid,
                        'componentname' => $ecr->componentname,
                        'component_percentage' => $ecr->component,
                        'component_column' => $ecr->column_ECR,
                        'subgrading' => []
                  ];

                  if(isset($subgrading_components[$ecr->componentid])){
                        foreach($subgrading_components[$ecr->componentid] as $subgrading){
                              $components['subgrading'][] = [
                                    'subcompid' => $subgrading->id,
                                    'subcompname' => $subgrading->subDescComponent,
                                    'subcomp_percentage' => $subgrading->subComponent,
                                    'subcomponent_column' => $subgrading->subColumnECR
                              ];
                        }
                  }

                  if(!isset($final_data[$ecr->ecrid])){
                        $final_data[$ecr->ecrid] = [
                              'ecrid' => $ecr->ecrid,
                              'components' => []
                        ];
                  }

                  $final_data[$ecr->ecrid]['components'][] = $components;
            }
            
            $final_data[$ecr->ecrid]['studinfo'] = $grade_info;
            

            $final_data = array_values($final_data);
            // return $final_data;
            return view ('tesda_trainer.pages.ecrtable',[
                  'component' => $final_data[0]['components'],
                  'students' => $final_data[0]['studinfo']
            ]);
      }

      public function save_grades(Request $request){
            $highest_scores = $request->get('highest_scores');
            $grades = $request->get('scores');
            $term_averages = $request->get('term_averages');


            // $equivalence = DB::table("tesda_grade_point_scale")
            //                         ->join('tesda_grade_point_equivalence', 'tesda_grade_point_scale.grade_point_equivalency', '=', 'tesda_grade_point_equivalence.id')
            //                         ->where('tesda_grade_point_equivalence.isactive', 1)
            //                         ->select(
            //                               'tesda_grade_point_scale.grade_point',
            //                               'tesda_grade_point_scale.letter_equivalence',
            //                               'tesda_grade_point_scale.percent_equivalence',
            //                               'tesda_grade_point_scale.grade_remarks'
            //                               )
            //                         ->get();
                                          
                                          
            foreach($highest_scores as $scores){

                  $exist = DB::table('tesda_highest_score')
                        ->where('schedid', $scores['schedid'])
                        ->where('component_id', $scores['component_id'])
                        ->where('subcomponent_id', $scores['subid'])
                        ->where('term', $scores['term'])
                        ->where('column_number', $scores['sort'])
                        ->first();

                  if($exist){
                        DB::table('tesda_highest_score')
                              ->where('schedid', $scores['schedid'])
                              ->where('component_id', $scores['component_id'])
                              ->where('subcomponent_id', $scores['subid'])
                              ->where('term', $scores['term'])
                              ->where('column_number', $scores['sort'])
                              ->update([
                                    'score' => $scores['highest_score'],
                                    'date' => $scores['date'],
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        DB::table('tesda_highest_score')
                              ->insert([
                                    'schedid'=> $scores['schedid'],
                                    'component_id'=> $scores['component_id'],
                                    'subcomponent_id' => $scores['subid'],
                                    'score' => $scores['highest_score'],
                                    'term' => $scores['term'],
                                    'date' => $scores['date'],
                                    'column_number' => $scores['sort'],
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

            }

            foreach($grades as $grade){

                  $exist = DB::table('tesda_grading_scores')
                        ->where('schedid', $grade['schedid'])
                        ->where('studid', $grade['studid'])
                        ->where('component_id', $grade['component_id'])
                        ->where('subcomponent_id', $grade['subid'])
                        ->where('term', $grade['term'])
                        ->where('column_number', $grade['sort'])
                        ->first();
                  
                  if($exist){
                        DB::table('tesda_grading_scores')
                              ->where('schedid', $grade['schedid'])
                              ->where('studid', $grade['studid'])
                              ->where('component_id', $grade['component_id'])
                              ->where('subcomponent_id', $grade['subid'])
                              ->where('term', $grade['term'])
                              ->where('column_number', $grade['sort'])
                              ->update([
                                    'score' => $grade['score'],
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        DB::table('tesda_grading_scores')
                              ->insert([
                                    'schedid'=> $grade['schedid'],
                                    'studid'=> $grade['studid'],
                                    'component_id'=> $grade['component_id'],
                                    'subcomponent_id' => $grade['subid'],
                                    'score' => $grade['score'],
                                    'term' => $grade['term'],
                                    'column_number' => $grade['sort'],
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  } 
                  
            }

            foreach($term_averages as $average){
                  //   $ave_equivalence = null;
                  //   foreach ($equivalence as $eq) {
                  //         // Remove '%' and extract min/max values as floats
                  //         $percentRange = array_map('trim', explode('-', str_replace('%', '', $eq->percent_equivalence)));
                  
                  //         $minPercent = isset($percentRange[0]) ? floatval($percentRange[0]) : null;
                  //         $maxPercent = isset($percentRange[1]) ? floatval($percentRange[1]) : null;
                  
                  //         if ($average['term_average'] !== 'INC' && $average['term_average'] !== null) {
                  //             $termAverage = round(floatval($average['term_average'])); // Round off to whole number
                  //             if (!is_null($minPercent) && !is_null($maxPercent)) {
                  //                 if ($termAverage >= $minPercent && $termAverage <= $maxPercent) {
                  //                     $ave_equivalence = $eq->grade_point;
                  //                     break; 
                  //                 }
                  //             }
                  //         }else if($average['term_average'] === 'INC'){
                  //             $ave_equivalence = 'INC';
                  //         }
                  //     }

                  $exist = DB::table('tesda_stud_term_grades')
                  ->where('schedid', $average['schedid'])
                  ->where('studid', $average['studid'])
                  ->first();


            
                  if($exist){
                        if($average['term'] == 'first'){
                              DB::table('tesda_stud_term_grades')
                                    ->where('schedid', $average['schedid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'prelim_grade' => $average['term_average'],
                                          //   'prelim_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 'second'){
                              DB::table('tesda_stud_term_grades')
                                    ->where('schedid', $average['schedid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'midterm_grade' => $average['term_average'],
                                          //   'midterm_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        // if($average['term'] == 'Pre-Final'){
                        //       DB::table('tesda_stud_term_grades')
                        //             ->where('schedid', $average['schedid'])
                        //             ->where('studid', $average['studid'])
                        //             ->update([
                        //                   'schedid'=> $average['schedid'],
                        //                   'studid'=> $average['studid'],
                        //                   'prefinal_grade' => $average['term_average'],
                        //                 //   'prefinal_transmuted' => $ave_equivalence,
                        //                   'updatedby' => auth()->user()->id,
                        //                   'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        //             ]);
                        // }
                        if($average['term'] == 'third'){
                              DB::table('tesda_stud_term_grades')
                                    ->where('schedid', $average['schedid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'final_grade' => $average['term_average'],
                                          //   'final_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        
                  }else{
                        if($average['term'] == 'first'){
                              DB::table('tesda_stud_term_grades')
                                    ->insert([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'prelim_grade' => $average['term_average'],
                                          //   'prelim_transmuted' => $ave_equivalence,
                                          'prelim_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 'second'){
                              DB::table('tesda_stud_term_grades')
                                    ->insert([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'midterm_grade' => $average['term_average'],
                                          //   'midterm_transmuted' => $ave_equivalence,
                                          'midterm_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        // if($average['term'] == 'Pre-Final'){
                        //       DB::table('tesda_stud_term_grades')
                        //             ->insert([
                        //                   'schedid'=> $average['schedid'],
                        //                   'studid'=> $average['studid'],
                        //                   'prefinal_grade' => $average['term_average'],
                        //                   'prefinal_transmuted' => $ave_equivalence,
                        //                   'prefinal_status' => 0,
                        //                   'createdby' => auth()->user()->id,
                        //                   'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        //             ]);
                        // }
                        if($average['term'] == 'third'){
                              DB::table('tesda_stud_term_grades')
                                    ->insert([
                                          'schedid'=> $average['schedid'],
                                          'studid'=> $average['studid'],
                                          'final_grade' => $average['term_average'],
                                          //   'final_transmuted' => $ave_equivalence,
                                          'final_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }
            }
      }
    
      public function display_term_grades(Request $request){
            $term = request()->get('term');
            $schedid = request()->get('schedid');
            $status = request()->get('status');

            $highest_scores = DB::table('tesda_highest_score')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    ->select('component_id', 'subcomponent_id', 'score', 'column_number','date')
                                    ->get();

            $grade_scores = DB::table('tesda_grading_scores')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    // ->when($status, function ($query, $status) {
                                    //       return $query->where('status_flag', $status);
                                    // })
                                    ->select('component_id', 'subcomponent_id', 'score', 'column_number','studid')
                                    ->get();
            
            $grade_status = DB::table('tesda_grading_scores')
                                    ->where('schedid', $schedid)
                                    ->where('term', $term)
                                    ->select('studid', 'status_flag')
                                    ->groupBy('studid')
                                    ->get();                       
            
            return [
                  'highest_scores' => $highest_scores,
                  'grade_scores' => $grade_scores,
                  'grade_status' => $grade_status
            ];

      }

      public function submit_grades(Request $request){
            $grades = request()->get('grades');
            $students = request()->get('students');

            // $data = DB::table('college_grade_point_scale')
            //       ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
            //       ->where('college_grade_point_equivalence.isactive', 1)
            //       ->select(
            //             'college_grade_point_scale.grade_point',
            //             'college_grade_point_scale.letter_equivalence',
            //             'college_grade_point_scale.percent_equivalence',
            //             )
            //       ->get();
            // return $students;
            foreach($grades as $grade){
                  DB::table('tesda_grading_scores')
                        ->where('studid', $grade['studid'])
                        ->where('component_id', $grade['component_id'])
                        ->where('subcomponent_id', $grade['subid'])
                        ->where('term', $grade['term'])
                        ->where('column_number', $grade['sort'])
                        ->update([
                              'status_flag' => 1,
                        ]);
            
            };
            foreach($students as $student){
                  
                  if($student['term'] == 'first'){
                        if($student['term_average'] == 'INC'){
                              DB::table('tesda_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'prelim_status' => 7,
                                    ]);   
                        }else{
                              DB::table('tesda_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'prelim_status' => 1,
                                    ]);   
                        }
                  }else if($student['term'] == 'second'){
                        if($student['term_average'] == 'INC'){
                              DB::table('tesda_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'midterm_status' => 7,
                                    ]);
                        }else{
                              DB::table('tesda_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'midterm_status' => 1,
                                    ]);
                        }
                  // }else if($student['term'] == 'Pre-Final'){
                  //       if($student['term_average'] == 'INC'){
                  //             DB::table('college_stud_term_grades')
                  //                   ->where('studid', $student['studid'])
                  //                   ->where('schedid', $student['schedid'])
                  //                   ->update([
                  //                         'pre-final_status' => 7,
                  //                   ]);
                  //       }else{
                  //             DB::table('college_stud_term_grades')
                  //                   ->where('studid', $student['studid'])
                  //                   ->where('schedid', $student['schedid'])
                  //                   ->update([
                  //                         'pre-final_status' => 1,
                  //                   ]);
                  //       }
                        
                  }else if($student['term'] == 'third'){
                        if($student['term_average'] == 'INC'){
                              DB::table('tesda_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'final_status' => 7,
                                    ]);  
                        }else{
                              DB::table('tesda_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('schedid', $student['schedid'])
                                    ->update([
                                          'final_status' => 1,
                                    ]);     
                        }
                  }
            };


      }
}
