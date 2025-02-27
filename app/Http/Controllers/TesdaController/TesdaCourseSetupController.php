<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class TesdaCourseSetupController extends Controller
{
    public function tesda_courses_setup_get_course_type()
    {
        $course_type = DB::table('tesda_course_type')->get();
        return $course_type;
    }

    public function tesda_courses_setup_add_course_type(Request $request){
        
        $course_type = request('course_type');

        DB::table('tesda_course_type')->insert([
            'description' => $course_type,
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);
    }

    

    public function tesda_courses_setup_get_courses(Request $request)
    {
        $course_type = request('course_type');

        $courses = DB::table('tesda_courses')
                    ->join('tesda_course_type', 'tesda_courses.course_type', '=', 'tesda_course_type.id')
                    ->when($course_type, function ($query) use ($course_type) {
                        $query->where('tesda_courses.course_type', $course_type);
                    })
                    ->where('tesda_courses.deleted', 0)
                    ->select(
                        'tesda_courses.id',
                        'tesda_courses.course_name',
                        'tesda_courses.course_code',
                        'tesda_course_type.description',
                        'tesda_courses.course_duration',
                    )
                    ->get();
        return $courses;
    }

    public function tesda_courses_setup_add_course(Request $request){
        
        $request->validate([
            'course_type' => 'required',
            'course_code' => 'required',
            'course_name' => 'required',
            'course_duration' => 'required',
        ],
        [
            'course_type.required' => 'Course Type name is required.',
            'course_code.required' => 'Course Code is required.',
            'course_name.required' => 'Course Name is required.',
            'course_duration.required' => 'Course Duration is required.',
        ]);

        $course_type = request('course_type');
        $course_code = request('course_code');
        $course_name = request('course_name');
        $course_duration = request('course_duration');

        DB::table('tesda_courses')->insert([
            'course_name' => $course_name,
            'course_code' => $course_code,
            'course_type' => $course_type,
            'course_duration' => $course_duration,
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);
    }

    public function tesda_courses_setup_delete_courses(Request $request){

        $course_id = request('course_id');

        DB::table('tesda_courses')
            ->where('id', $course_id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function tesda_courses_setup_get_course_edit(Request $request){

        $course_id = request('course_id');

        $course = DB::table('tesda_courses')
            ->where('id', $course_id)
            ->select(
                'course_name',
                'course_code',
                'course_type',
                'course_duration'
            )
            ->first();
        return response()->json($course);
    }

    public function tesda_courses_setup_get_course_update(Request $request){

        $course_id = request('course_id');
        $course_name = request('course_name');
        $course_code = request('course_code');
        $course_type = request('course_type');
        $course_duration = request('course_duration');

        DB::table('tesda_courses')
            ->where('id', $course_id)
            ->update([
                'course_name' => $course_name,
                'course_code' => $course_code,
                'course_type' => $course_type,
                'course_duration' => $course_duration,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }

    public function tesda_courses_setup_add_series(Request $request){

        $course_id = request('course_id');
        $course_series = request('course_series');
        $series_active = request('series_active');
        
        if($series_active == 1){
            DB::table('tesda_course_series')
                    ->where('deleted', 0)
                    ->where('course_id', $course_id)
                    ->update([
                        'active' => 0,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
        }
        DB::table('tesda_course_series')
            ->insert([
                'course_id' => $course_id,
                'description' => $course_series,
                'active' => $series_active,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }
    public function tesda_courses_setup_get_series(Request $request){

        $course_id = request('course_id');
        $series = DB::table('tesda_course_series')
                    ->where('course_id', $course_id)
                    ->where('deleted', 0)
                    ->select(
                        'id',
                        'description',
                        'active'
                    )
                    ->get();
                
        return $series;
        
    }

    public function tesda_courses_setup_update_series_active(Request $request){

        $series_id = request('series_id');
        $series_active = request('series_active');
        $course_id = request('course_id');

        if($series_active == 1){
            DB::table('tesda_course_series')
                    ->where('deleted', 0)
                    ->where('course_id', $course_id)
                    ->update([
                        'active' => 0,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
        }
            DB::table('tesda_course_series')
                        ->where('id', $series_id)
                        ->where('deleted', 0)
                        ->update([
                            'active' => $series_active,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
    }

    public function tesda_courses_setup_update_series(Request $request){

        $series_id = request('series_id');
        $series_desc = request('series_desc');

            DB::table('tesda_course_series')
                        ->where('id', $series_id)
                        ->where('deleted', 0)
                        ->update([
                            'description' => $series_desc,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
    }

    public function tesda_courses_setup_add_competency(Request $request){

        $competency_code = request('competency_code');
        $competency_description = request('competency_description');
        $competency_hours = request('competency_hours');
        $competency_type = request('competency_type');
        $series_id = request('series_id');
        
        DB::table('tesda_course_competency')
            ->insert([
                'course_series_id' => $series_id,
                'competency_type' => $competency_type,
                'competency_code' => $competency_code,
                'hours' => $competency_hours,
                'competency_desc' => $competency_description,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
        
    }

    public function tesda_courses_setup_get_competencies(Request $request){

        $series_id = request('series_id');

        $competency_type = DB::table('tesda_course_competency')
                    ->where('course_series_id', $series_id)
                    ->where('deleted', 0)
                    ->select(
                        'id',
                        'competency_code',
                        'competency_desc',
                        'hours',
                        'competency_type'
                    )
                    ->get()
                    ->groupBy('competency_type')
                    ->sortBy(function ($items, $type) {
                        return $items->first()->id; // Sort by the smallest ID in each group
                    })
                    ->map(function ($items, $type) {
                        return [
                            'competency_type' => $type,
                            'other_data' => $items->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'competency_code' => $item->competency_code,
                                    'competency_desc' => $item->competency_desc,
                                    'hours' => $item->hours,
                                ];
                            })->toArray(),
                        ];
                    })
                    ->values();
        
        return $competency_type;
    }

    public function tesda_courses_setup_get_competency(Request $request){

        $competency_id = request('competency_id');

        $competency_type = DB::table('tesda_course_competency')
                    ->where('id', $competency_id)
                    ->where('deleted', 0)
                    ->select(
                        'id',
                        'competency_code',
                        'competency_desc',
                        'hours',
                        'competency_type'
                    )
                    ->first();

        return response()->json($competency_type);
    }

    public function tesda_courses_setup_update_competency(Request $request){

        $competency_id = request('competency_id');
        $competency_code = request('competency_code');
        $competency_description = request('competency_description');
        $competency_hours = request('competency_hours');
        $competency_type = request('competency_type');
        
        DB::table('tesda_course_competency')
            ->where('id', $competency_id)
            ->update([
                'competency_type' => $competency_type,
                'competency_code' => $competency_code,
                'hours' => $competency_hours,
                'competency_desc' => $competency_description,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        

    }

    public function tesda_courses_setup_delete_competency(Request $request){

        $competency_id = request('competency_id');

        DB::table('tesda_course_competency')
                    ->where('id', $competency_id)
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

    }
    

    public function tesda_courses_setup_get_competency_type(){

        
        $competency_type = DB::table('tesda_course_competency')
                    ->where('deleted', 0)
                    ->select(
                        'id',
                        'competency_type'
                    )
                    ->groupBy('competency_type')
                    ->get();
        
        return $competency_type;
    }

    public function tesda_courses_setup_update_signatories(Request $request){

        $signatories = request('signatories');
        $course_id = request('course_id');
        
        if($signatories){
            foreach($signatories as $signatory){

                if($signatory[0] == 0){
    
                    DB::table('tesda_course_signatories')
                        ->insert([
                            'course_id' => $course_id,
                            'signatory_name' => $signatory[1],
                            'signatory_title' => $signatory[2],
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
    
                }else{
    
                    DB::table('tesda_course_signatories')
                        ->where('id', $signatory[0])
                        ->update([
                            'signatory_name' => $signatory[1],
                            'signatory_title' => $signatory[2],
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
    
                }
            }
        }
        
    }

    public function tesda_courses_setup_get_signatories(Request $request){

        $course_id = request('course_id');

        $signatories = DB::table('tesda_course_signatories')
                    ->where('course_id', $course_id)
                    ->where('deleted', 0)
                    ->select(
                        'id',
                        'signatory_name',
                        'signatory_title'
                    )
                    ->get();
        
        return $signatories;

    }

    public function tesda_courses_setup_remove_signatories(Request $request){

        $signatory_id = request('signatory_id');

        DB::table('tesda_course_signatories')
                ->where('id', $signatory_id)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

    }
}
