<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class TesdaBatchSetupController extends Controller
{
    public function tesda_batch_setup_add_batch(Request $request){

        $request->validate([
            'batch_name' => 'required',
            'batch_course' => 'required',
            'batch_capacity' => 'required',
            'batch_duration' => 'required',
            'batch_building' => 'required',
            'batch_room' => 'required',
            'batch_series' => 'required',
        ],
        [
            'batch_name.required' => 'Batch Type name is required.',
            'batch_course.required' => 'Batch Course is required.',
            'batch_capacity.required' => 'Batch Capacity is required.',
            'batch_duration.required' => 'Batch Duration is required.',
            'batch_building.required' => 'Batch Building is required.',
            'batch_room.required' => 'Batch Room is required.',
            'batch_series.required' => 'Batch Series is required.',
        ]);


        $batch_desc = request('batch_name');
        $batch_course = request('batch_course');
        $batch_capacity = request('batch_capacity');
        $batch_duration = request('batch_duration');
        $batch_building = request('batch_building');
        $batch_room = request('batch_room');
        $batch_series = request('batch_series');
        $grade_template = request('grade_template');
        $is_active = request('is_active') ? 1 : 0;

        $dates = explode(' - ', $batch_duration );
        $date_from = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->format('Y-m-d');
        $date_to = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->format('Y-m-d');

        $batch_id = DB::table('tesda_batches')
            ->insertGetId([
                'batch_desc' => $batch_desc,
                'course_id' => $batch_course,
                'batch_series_id' => $batch_series,
                'batch_capacity' => $batch_capacity,
                'date_to' => $date_to,
                'date_from' => $date_from,
                'buildingID' => $batch_building,
                'roomID' => $batch_room,
                'isactive' => $is_active,
                'ecr_template' => $grade_template,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
        
        self::tesda_batch_setup_add_competency_schedule($batch_id);
        
    }

    public function tesda_batch_setup_add_competency_schedule($batch_id){

        $competencies = DB::table('tesda_batches')
                            ->join('tesda_courses', 'tesda_batches.course_id', '=', 'tesda_courses.id')
                            ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
                            ->join('tesda_course_competency', 'tesda_batches.batch_series_id', '=', 'tesda_course_competency.course_series_id')
                            ->where('tesda_batches.id', $batch_id)
                            ->where('tesda_course_competency.deleted', 0)
                            ->select(
                                'tesda_course_competency.id',
                            )
                            ->groupBy('tesda_course_competency.id')
                            ->get();

        $batch_schedule = DB::table('tesda_batch_schedule')
                            ->where('batch_id', $batch_id)
                            ->select(
                                'competency_id',
                            )
                            ->groupBy('competency_id')
                            ->get();


        $comp_array = [];
        $batch_array = [];
        foreach($competencies as $competency){
            array_push($comp_array, $competency->id);
        }

        foreach($batch_schedule as $schedule){
            array_push($batch_array, $schedule->competency_id);
        }

        $new_array = array_values(array_diff($comp_array, $batch_array));
        
        // return $comp_array;

        if($new_array){
            foreach($new_array as $new_arrays){
                $schedid = DB::table('tesda_batch_schedule')
                    ->insertGetId([
                        'batch_id' => $batch_id,
                        'competency_id' => $new_arrays,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
                
                DB::table('tesda_schedule_details')
                    ->insert([
                        'batch_schedule_id' => $schedid,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            }
        }

    }

    public function tesda_batch_setup_get_batches(Request $request){

        $course_id = request('course_id');
        $course_type = request('course_type');
        $course_duration = request('course_duration');
        $date_from = null;
        $date_to = null;

        if($course_duration){
            $dates = explode(' - ', $course_duration );
            $date_from = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->format('Y-m-d');
            $date_to = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->format('Y-m-d');
        }
       

        $batches = DB::table('tesda_batches')
                    ->join('tesda_courses', 'tesda_batches.course_id', '=', 'tesda_courses.id')
                    ->when($course_id, function ($query) use ($course_id) {
                        $query->where('tesda_batches.course_id', $course_id);
                    })
                    ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                        $query->where('tesda_batches.date_from', $date_from);
                        $query->where('tesda_batches.date_to', $date_to);
                    })
                    ->when($course_type, function ($query) use ($course_type) {
                        $query->where('tesda_courses.course_type', $course_type);
                    })
                    ->where('tesda_batches.deleted', 0)
                    ->select(
                        'tesda_batches.id',
                        'tesda_batches.batch_desc',
                        'tesda_courses.course_name',
                        'tesda_batches.batch_capacity',
                        DB::raw("CONCAT(CAST(DATE_FORMAT(tesda_batches.date_from, '%m/%d/%Y') AS CHAR), ' - ', CAST(DATE_FORMAT(tesda_batches.date_to, '%m/%d/%Y') AS CHAR)) as date_range"),
                        'tesda_batches.isactive'
                    )
                    ->get();
        
        foreach($batches as $batch){
            $batch->enrolled = DB::table('tesda_enrolledstud')
                                ->join('tesda_batches','tesda_enrolledstud.batchid', '=', 'tesda_batches.id')
                                ->where('tesda_enrolledstud.deleted', 0)
                                ->where('tesda_enrolledstud.batchid', $batch->id)
                                ->count();
        }

        return $batches;
        
    }
    public function tesda_batch_setup_get_batches_duration(Request $request){

        $duration = DB::table('tesda_batches')
                    ->where('deleted', 0)
                    ->select(
                        DB::raw("CONCAT(CAST(DATE_FORMAT(tesda_batches.date_from, '%m/%d/%Y') AS CHAR), ' - ', CAST(DATE_FORMAT(tesda_batches.date_to, '%m/%d/%Y') AS CHAR)) as date_range"),
                    )
                    ->get();
        return $duration;
        
    }

    public function tesda_batch_setup_get_batch(Request $request){
        $batch_id = request('batch_id');

        $batch = DB::table('tesda_batches')
                    ->join('tesda_courses', 'tesda_batches.course_id', '=', 'tesda_courses.id')
                    ->leftjoin('rooms', 'tesda_batches.roomID', '=', 'rooms.id')
                    ->leftjoin('building', 'tesda_batches.buildingID', '=', 'building.id')
                    ->where('tesda_batches.id', $batch_id)
                    ->where('tesda_batches.deleted', 0)
                    ->select(
                        'tesda_batches.id',
                        'tesda_batches.batch_desc',
                        'tesda_courses.course_name',
                        'tesda_batches.batch_capacity',
                        'tesda_batches.course_id',
                        DB::raw("CONCAT(CAST(DATE_FORMAT(tesda_batches.date_from, '%m/%d/%Y') AS CHAR), ' - ', CAST(DATE_FORMAT(tesda_batches.date_to, '%m/%d/%Y') AS CHAR)) as date_range"),
                        'tesda_batches.buildingID',
                        'tesda_batches.roomID',
                        'tesda_batches.isactive',
                        'building.description as building_name',
                        'rooms.roomname as room_name'
                    )
                    ->first();

        return response()->json($batch);
    }


    public function tesda_batch_setup_edit_batch(Request $request){
        $id = request('id');
        $batch_desc = request('batch_name');
        $batch_course = request('batch_course');
        $batch_capacity = request('batch_capacity');
        $batch_duration = request('batch_duration');
        $batch_building = request('batch_building');
        $batch_room = request('batch_room');
        $grade_template = request('grade_template');
        $is_active = request('is_active') ? 1 : 0;

        $dates = explode(' - ', $batch_duration );
        $date_from = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->format('Y-m-d');
        $date_to = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->format('Y-m-d');
        
        DB::table('tesda_batches')
            ->where('id' , $id)
            ->update([
                'batch_desc' => $batch_desc,
                'course_id' => $batch_course,
                'batch_capacity' => $batch_capacity,
                'date_to' => $date_to,
                'date_from' => $date_from,
                'buildingID' => $batch_building,
                'roomID' => $batch_room,
                'isactive' => $is_active,
                'ecr_template' => $grade_template,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function tesda_batch_setup_delete_batch(){
        $id = request('id');
        
        DB::table('tesda_batches')
            ->where('id' , $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        DB::table('tesda_batch_schedule')
            ->where('batch_id' , $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
        
        DB::table('tesda_schedule_details')
            ->join('tesda_batch_schedule', 'tesda_schedule_details.batch_schedule_id', '=', 'tesda_batch_schedule.id')
            ->where('tesda_batch_schedule.batch_id', $id)
            ->update([
                'tesda_schedule_details.deleted' => 1,
                'tesda_schedule_details.deletedby' => auth()->user()->id,
                'tesda_schedule_details.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function tesda_batch_setup_update_batches_building(){
        $batch_id = request('batch_id');
        $buildingid = request('buildingid');

        DB::table('tesda_batches')
            ->where('id' , $batch_id)
            ->update([
                'buildingID' => $buildingid,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function tesda_batch_setup_update_batches_room(){
        $batch_id = request('batch_id');
        $roomid = request('roomid');

        DB::table('tesda_batches')
            ->where('id' , $batch_id)
            ->update([
                'roomID' => $roomid,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function tesda_batch_setup_update_batches_duration(){
        $batch_id = request('batch_id');
        $duration = request('duration');

        $dates = explode(' - ', $duration );
        $date_from = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[0])->format('Y-m-d');
        $date_to = \Carbon\Carbon::createFromFormat('m/d/Y', $dates[1])->format('Y-m-d');
        
        DB::table('tesda_batches')
            ->where('id', $batch_id)
            ->update([
                'date_to' => $date_to,
                'date_from' => $date_from,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function tesda_batch_setup_update_batches_capacity(){
        $batch_id = request('batch_id');
        $capacity = request('capacity');

        DB::table('tesda_batches')
            ->where('id' , $batch_id)
            ->update([
                'batch_capacity' => $capacity,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function tesda_batch_setup_get_competency_schedule(){
        $batch_id = request('batch_id');

        $schedule = DB::table('tesda_batch_schedule')
                        ->join('tesda_course_competency', 'tesda_batch_schedule.competency_id', '=', 'tesda_course_competency.id')
                        ->where('tesda_batch_schedule.batch_id', $batch_id)
                        ->where('tesda_batch_schedule.deleted', 0)
                        ->select(
                            'tesda_batch_schedule.id',
                            'tesda_course_competency.competency_desc',
                            'tesda_course_competency.id as competency_id',
                            'tesda_course_competency.hours',
                        )
                        ->get();
                            
        foreach($schedule as $sched){
            
            $sched->scheddetails = DB::table('tesda_schedule_details')
                                    ->leftjoin('building', 'tesda_schedule_details.buildingID', '=', 'building.id')
                                    ->leftjoin('rooms', 'tesda_schedule_details.roomID', '=', 'rooms.id')
                                    ->leftjoin('tesda_trainers', 'tesda_schedule_details.trainer_id', '=', 'tesda_trainers.id')
                                    ->leftjoin('users', 'tesda_trainers.user_id', '=', 'users.id')
                                    ->where('tesda_schedule_details.batch_schedule_id', $sched->id)
                                    ->where('tesda_schedule_details.deleted', 0)
                                    ->select(
                                        'tesda_schedule_details.id',
                                        DB::raw("DATE_FORMAT(tesda_schedule_details.stime, '%h:%i %p') AS stime"),
                                        DB::raw("DATE_FORMAT(tesda_schedule_details.etime, '%h:%i %p') AS etime"),
                                        'tesda_schedule_details.date_from',
                                        'tesda_schedule_details.date_to',
                                        'building.description',
                                        'rooms.roomname',
                                        'users.name as trainer_name'
                                    )
                                    ->get();
            
        }

        return $schedule;
    }
    
    public function tesda_batch_setup_add_competency_schedule_detail(Request $request){

        $comp_schedid = $request->get('comp_schedid');
        $duration = $request->get('duration');
        $stime = $request->get('stime');
        $etime = $request->get('etime');
        $buildingid = $request->get('buildingid');
        $roomid = $request->get('roomid');
        $trainer = $request->get('trainer');

        $date = explode(' - ', $duration );
        $date_from = \Carbon\Carbon::createFromFormat('m/d/Y', $date[0])->format('Y-m-d');
        $date_to = \Carbon\Carbon::createFromFormat('m/d/Y', $date[1])->format('Y-m-d');

        DB::table('tesda_schedule_details')
            ->insert([
                'batch_schedule_id' => $comp_schedid,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'stime' => $stime,
                'etime' => $etime,
                'buildingID' => $buildingid,
                'roomID' => $roomid,
                'trainer_id' => $trainer,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

    }

    public function tesda_batch_setup_get_competency_schedule_detail(Request $request){

        $schedid = $request->get('schedid');

        $schedule = DB::table('tesda_schedule_details')
            ->where('id', $schedid)
            ->select(
                DB::raw("CONCAT(CAST(DATE_FORMAT(date_from, '%m/%d/%Y') AS CHAR), ' - ', CAST(DATE_FORMAT(date_to, '%m/%d/%Y') AS CHAR)) as date_range"),
                'stime',
                'etime',
                'buildingID',
                'roomID',
                'trainer_id'
            )
            ->first();

        return response()->json($schedule);
    }

    public function tesda_batch_setup_update_competency_schedule_detail(Request $request){

        $schedid = $request->get('schedid');
        $duration = $request->get('duration');
        $stime = $request->get('stime');
        $etime = $request->get('etime');
        $buildingid = $request->get('buildingid');
        $roomid = $request->get('roomid');
        $trainer = $request->get('trainer');

        $date = explode(' - ', $duration );
        $date_from = \Carbon\Carbon::createFromFormat('m/d/Y', $date[0])->format('Y-m-d');
        $date_to = \Carbon\Carbon::createFromFormat('m/d/Y', $date[1])->format('Y-m-d');

        DB::table('tesda_schedule_details')
            ->where('id', $schedid)
            ->update([
                'date_from' => $date_from,
                'date_to' => $date_to,
                'stime' => $stime,
                'etime' => $etime,
                'buildingID' => $buildingid,
                'trainer_id' => $trainer,
                'roomID' => $roomid,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

    }

    public function tesda_batch_setup_delete_competency_schedule_detail(Request $request){

        $schedid = $request->get('schedid');

        DB::table('tesda_schedule_details')
            ->where('id', $schedid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

    }

    public function tesda_batch_setup_get_trainers(){

        $trainers = DB::table('tesda_trainers')
                        ->join('users', 'tesda_trainers.user_id', '=', 'users.id')
                        ->where('tesda_trainers.deleted', 0)
                        ->select(
                            'tesda_trainers.id',
                            'users.name'
                        )
                        ->get();
        return $trainers;
    }

    public function tesda_batch_setup_get_trainer_schedule(Request $request){

        $userid = $request->get('userid');

        $trainers = DB::table('tesda_trainers')
                        ->join('tesda_schedule_details', 'tesda_trainers.id', '=', 'tesda_schedule_details.trainer_id')
                        ->join('tesda_batch_schedule', 'tesda_schedule_details.batch_schedule_id', '=', 'tesda_batch_schedule.id')
                        ->join('tesda_course_competency', 'tesda_batch_schedule.competency_id', '=', 'tesda_course_competency.id')
                        ->join('tesda_batches', 'tesda_batch_schedule.batch_id', '=', 'tesda_batches.id')
                        ->where('tesda_trainers.deleted', 0)
                        ->where('tesda_trainers.user_id', $userid)
                        ->select(
                            'tesda_batches.batch_desc',
                            'tesda_course_competency.competency_code',
                            'tesda_course_competency.competency_desc',
                        )
                        ->get();

        return $trainers;
    }

    public function get_ecr_templates(){
        $ecr_template = DB::table('tesda_grading_setup')
                        ->where('deleted', 0)
                        ->select(
                            'gradDesc',
                            'id'
                        )
                        ->get();
        return $ecr_template;

    }
}
