<?php

namespace App\Http\Controllers\DeanControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\DeanControllers\DeanSectionController;

class GradingpointEquivalencyController extends Controller
{

    // public function fetch_grade_point_equivalence()
    // {
    //     $gradePointEquivalence = DB::table('college_grade_point_equivalence')
    //         ->join('college_termgrading', 'college_grade_point_equivalence.terms', '=', 'college_termgrading.id')
    //         ->where('college_grade_point_equivalence.deleted', 0)
    //         ->select(
    //             'college_grade_point_equivalence.id',
    //              'college_grade_point_equivalence.grade_description',
    //              'college_grade_point_equivalence.terms', 
    //              'college_grade_point_equivalence.isactive',
    //              'college_termgrading.description',
    //         )
    //         ->get();

    //     return response()->json($gradePointEquivalence);
    // }

    public function fetch_grade_point_equivalence()
    {

        $defaultGradePointEquivalency = [
            // ['terms' => 1, 'grade_description' => 'GRADE POINT SCALE (1.00 - 5.00 )'],
               ['grade_description' => 'GRADE POINT SCALE (1.00 - 5.00)', 'terms' => '43,44,45', 'isactive' => 1],
               ['grade_description' => 'GRADE POINT SCALE (4.00 - 0.00)', 'terms' => '43,44,45', 'isactive' => 0],

        
        ];

        // Check if setup_subjgroups table is empty
        $count = DB::table('college_grade_point_equivalence')->count();

        if ($count == 0) {
            // Insert default groups
            foreach ($defaultGradePointEquivalency as $group) {
                DB::table('college_grade_point_equivalence')->insert([
                    'grade_description' => $group['grade_description'],
                    'terms' => $group['terms'],
                    'isactive' => $group['isactive'],
                    'deleted' => 0
                ]);
            }
        }


        $gradePointEquivalence = DB::table('college_grade_point_equivalence')
            ->where('college_grade_point_equivalence.deleted', 0)
            ->select(
                'college_grade_point_equivalence.id',
                'college_grade_point_equivalence.grade_description',
                // 'college_grade_point_equivalence.terms',
                'college_grade_point_equivalence.isactive',
                // DB::raw('(SELECT GROUP_CONCAT(college_termgrading.description SEPARATOR ", ") 
                //           FROM college_termgrading 
                //           WHERE FIND_IN_SET(college_termgrading.id, college_grade_point_equivalence.terms)) as description')
                DB::raw('(SELECT GROUP_CONCAT(description SEPARATOR ", ") FROM college_termgrading WHERE FIND_IN_SET(id, college_grade_point_equivalence.terms)) as terms')
            )
            ->get();
    
        return response()->json($gradePointEquivalence);
    }
    


    public static function create_gradepointEquivalency(Request $request)
    {
        // Retrieve and validate inputs
        $gradePointDesc = $request->input('gradePointDesc');
        $gradePointScaleData = $request->input('gradePointScaleData');
        $setActive = $request->input('setActive');
        $setCategory = $request->input('setCategory');
        $termApplied = $request->input('termApplied');

        // Check if gradePointDesc is provided
        if (empty($gradePointDesc)) {
            return response()->json([
                ['status' => 2, 'message' => 'Grade Point Description is required']
            ]);
        }

        // Check if termApplied is provided and is an array
        if (empty($termApplied) || !is_array($termApplied)) {
            return response()->json([
                ['status' => 2, 'message' => 'Terms should not be empty']
            ]);
        }

        $id = DB::table('college_grade_point_equivalence')->insertGetId([
            'grade_description' => $gradePointDesc,
            'terms' => implode(',', $termApplied), // Store terms as a comma-separated string
            'isactive' => $setActive,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
        ]);

        // Insert grade point data into the database
        foreach ($gradePointScaleData as $entry) {

            DB::table('college_grade_point_scale')->insert([
                'grade_point_equivalency' => $id,
                'grade_point' => $entry['gradePointEquivalency'],
                'letter_equivalence' => $entry['letterGradeEquivalency'],
                'percent_equivalence' => $entry['percentEquivalency'],
                'grade_remarks' => $entry['gradingRemarks'],
                'is_failed' => $entry['isFailed'],
                'createdby' => auth()->user()->id,
                'createddatetime' => now(),
            ]);
        }

        return response()->json([
            ['status' => 1, 'message' => 'Grade Point Equivalency Scale Created Successfully']
        ]);
    }
    
    // public static function create_gradepointEquivalency(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $gradePointDesc = $request->input('gradePointDesc');
    //     $gradePointScaleData = $request->input('gradePointScaleData');
    //     $setActive = $request->input('setActive');
    //     $termApplied = $request->input('termApplied');

    //     // Check if gradePointDesc is provided
    //     if (empty($gradePointDesc)) {
    //         return response()->json([
    //             ['status' => 2, 'message' => 'Grade Point Description is required']
    //         ]);
    //     }
    //     $id = DB::table('college_grade_point_equivalence')->insertGetId([
    //         'grade_description' => $gradePointDesc,
    //         'terms' => $termApplied,
    //         'isactive' => $setActive,
    //         'createdby' => auth()->user()->id,
    //         'createddatetime' => now(),
    //     ]);

    //         // Insert grade point data into the database
    //         foreach ($gradePointScaleData as $entry) {
    //             DB::table('college_grade_point_scale')->insert([
    //                 'grade_point_equivalency' => $id,
    //                 'grade_point' => $entry['gradePointEquivalency'],
    //                 'letter_equivalence' => $entry['letterGradeEquivalency'],
    //                 'percent_equivalence' => $entry['percentEquivalency'],
    //                 'grade_remarks' => $entry['gradingRemarks'],
    //                 // 'description' => $gradePointDesc,
    //                 'createdby' => auth()->user()->id,
    //                 'createddatetime' => now(),
    //             ]);
    //         }

    //         return response()->json([
    //             ['status' => 1, 'message' => 'Grade Point Equivalency Scale Created Successfully']
    //         ]);
    
    // }

    public static function fetch_grade_point_scale(Request $request){

        $grade_point_equivalency = $request->get('point_equivalency_id');


        $grade_point_scale = DB::table('college_grade_point_equivalence')

                    ->join('college_grade_point_scale', 'college_grade_point_equivalence.id', '=', 'college_grade_point_scale.grade_point_equivalency')
                    ->where('college_grade_point_scale.deleted',0)
                    ->where('college_grade_point_equivalence.deleted',0)
                    ->where('college_grade_point_equivalence.id',$grade_point_equivalency)
                    ->select(
                          'college_grade_point_scale.id',
                          'college_grade_point_scale.grade_point_equivalency',
                          'college_grade_point_scale.grade_point',
                          'college_grade_point_scale.letter_equivalence',
                          'college_grade_point_scale.percent_equivalence',
                          'college_grade_point_scale.grade_remarks',
                          'college_grade_point_equivalence.grade_description',
                          'college_grade_point_equivalence.id as grade_point_equivalency_id',
                          'college_grade_point_scale.is_failed',
                    )
                    ->get();

        return response()->json($grade_point_scale); 
    }

    public static function fetch_selected_grade_point_equivalence(Request $request){

        $grade_point_equivalency = $request->get('point_equivalency_id');

        $grade_point_scale = DB::table('college_grade_point_equivalence')
                    ->join('college_termgrading', function($join) {
                        $join->on(DB::raw('FIND_IN_SET(college_termgrading.id, college_grade_point_equivalence.terms)'), '>', DB::raw('0'));
                    })
                    ->where('college_grade_point_equivalence.deleted', 0)
                    ->where('college_grade_point_equivalence.id', $grade_point_equivalency)
                    ->select(
                        'college_grade_point_equivalence.id',
                        'college_grade_point_equivalence.grade_description',
                        'college_grade_point_equivalence.id as grade_point_equivalency_id',
                        'college_grade_point_equivalence.terms as equivalency_terms',
                        'college_grade_point_equivalence.isactive',
                        // 'college_grade_point_equivalence.is_failed',
                        // 'college_termgrading.description as overallterms',
                        DB::raw('GROUP_CONCAT(college_termgrading.description ORDER BY college_termgrading.id SEPARATOR ", ") as terms')
                    )
                    ->groupBy('college_grade_point_equivalence.id')
                    ->get();

                    $grade_point_equivalency_terms = DB::table('college_termgrading')
                    ->where('college_termgrading.deleted', 0)
                    ->select(
                        // 'college_termgrading.id',
                        DB::raw('GROUP_CONCAT(college_termgrading.id ORDER BY college_termgrading.id SEPARATOR ", ") as termsid'),
                        DB::raw('GROUP_CONCAT(college_termgrading.description ORDER BY college_termgrading.id SEPARATOR ", ") as terms')
                    )
                    ->get();
                    
        return response()->json([
            'grade_point_scale' => $grade_point_scale,
            'grade_point_equivalency_terms' => $grade_point_equivalency_terms
        ]);
    }


    // public function fetch_grade_point_equivalences()
    // {
    //     $gradePointEquivalence = DB::table('college_grade_point_equivalence')
    //         ->where('college_grade_point_equivalence.deleted', 0)
    //         ->select(
    //             'college_grade_point_equivalence.id',
    //             'college_grade_point_equivalence.grade_description',
    //             // 'college_grade_point_equivalence.terms',
    //             'college_grade_point_equivalence.isactive',
    //             // DB::raw('(SELECT GROUP_CONCAT(college_termgrading.description SEPARATOR ", ") 
    //             //           FROM college_termgrading 
    //             //           WHERE FIND_IN_SET(college_termgrading.id, college_grade_point_equivalence.terms)) as description')
    //             DB::raw('(SELECT GROUP_CONCAT(description SEPARATOR ", ") FROM college_termgrading WHERE FIND_IN_SET(id, college_grade_point_equivalence.terms)) as terms')
    //         )
    //         ->get();
    
    //     return response()->json($gradePointEquivalence);
    // }

    // public static function fetch_grade_point_scale(Request $request){

    //     $grade_point_equivalency = $request->get('point_equivalency_id');


    //     $grade_point_scale = DB::table('college_grade_point_scale')

    //                 ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
    //                 ->where('college_grade_point_scale.deleted',0)
    //                 ->where('college_grade_point_equivalence.deleted',0)
    //                 ->where('college_grade_point_scale.grade_point_equivalency',$grade_point_equivalency)
    //                 ->select(
    //                       'college_grade_point_scale.id',
    //                       'college_grade_point_scale.grade_point_equivalency',
    //                       'college_grade_point_scale.grade_point',
    //                       'college_grade_point_scale.letter_equivalence',
    //                       'college_grade_point_scale.percent_equivalence',
    //                       'college_grade_point_scale.grade_remarks',
    //                       'college_grade_point_equivalence.grade_description',
    //                       'college_grade_point_equivalence.id as grade_point_equivalency_id'
    //                 )
    //                 ->get();

    //     return response()->json($grade_point_scale); 
    // }




    public static function update_gradepointEquivalency(Request $request)
    {
        // Retrieve and validate inputs
        $gradePointDesc = $request->input('gradePointDesc');
        $gradePointScaleData = $request->input('gradePointScaleData');
        $gradePointEquivalencyID = $request->input('gradePointEquivalencyID');
        $gradePointEquivalencyTerms = $request->input('gradePointEquivalencyTerms');
        $isactive = $request->input('setActivePointEquivalency');
        


        // Check if gradePointDesc is provided
        if (empty($gradePointDesc)) {
            return response()->json([
                ['status' => 2, 'message' => 'Grade Point Description is required']
            ]);
        }

         // Check if termApplied is provided and is an array
         if (empty($gradePointEquivalencyTerms) || !is_array($gradePointEquivalencyTerms)) {
            return response()->json([
                ['status' => 2, 'message' => 'Terms should not be empty']
            ]);
        }

        try {
            DB::table('college_grade_point_equivalence')
                ->where('id', $gradePointEquivalencyID)
                ->update([
                    'grade_description' => $gradePointDesc,
                    'terms' => implode(',', $gradePointEquivalencyTerms),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => now(),
                    'isactive' => $isactive
                ]);

                // Insert grade point data into the database
                DB::table('college_grade_point_scale')
                    ->where('grade_point_equivalency', $gradePointEquivalencyID)
                    ->delete();

                foreach ($gradePointScaleData as $entry) {
                    DB::table('college_grade_point_scale')->insert([
                        'grade_point_equivalency' => $gradePointEquivalencyID,
                        'grade_point' => $entry['gradePointEquivalency'],
                        'letter_equivalence' => $entry['letterGradeEquivalency'],
                        'percent_equivalence' => $entry['percentEquivalency'],
                        'grade_remarks' => $entry['gradingRemarks'],
                        'is_failed' => $entry['isFailed'],
                        // 'description' => $gradePointDesc,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => now(),
                    ]);
                }

                return response()->json([
                    ['status' => 1, 'message' => 'Grade Point Equivalency Scale Updated Successfully']
                ]);
        } catch (\Exception $e) {
            return response()->json([
                // ['status' => 2, 'message' => 'All records are cleared ' . $e->getMessage()]
                ['status' => 3, 'message' => 'Successfully updated ']
            ]);
        }
    
    }




    // public static function create_gradepointEquivalency(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $gradePointDesc = $request->input('gradePointDesc');
    //     $gradePointScaleData = $request->input('gradePointScaleData');

    //     // Check if gradePointDesc is provided
    //     if (empty($gradePointDesc)) {
    //         return response()->json([
    //             ['status' => 2, 'message' => 'Grade Point Description is required']
    //         ]);
    //     }
    //     DB::table('college_grade_point_equivalence')->insert([
    //         'grade_description' => $gradePointDesc,
    //         'createdby' => auth()->user()->id,
    //         'createddatetime' => now(),
    //     ]);

    //         // Insert grade point data into the database
    //         foreach ($gradePointScaleData as $entry) {
    //             DB::table('college_grade_point_scale')->insert([
    //                 'grade_point' => $entry['gradePointEquivalency'],
    //                 'letter_equivalence' => $entry['letterGradeEquivalency'],
    //                 'percent_equivalence' => $entry['percentEquivalency'],
    //                 'grade_remarks' => $entry['gradingRemarks'],
    //                 // 'description' => $gradePointDesc,
    //                 'createdby' => auth()->user()->id,
    //                 'createddatetime' => now(),
    //             ]);
    //         }

    //         return response()->json([
    //             ['status' => 1, 'message' => 'Grade Point Equivalency Scale Created Successfully']
    //         ]);
    
    // }


    
}