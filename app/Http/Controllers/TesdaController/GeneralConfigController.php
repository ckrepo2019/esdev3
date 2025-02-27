<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class GeneralConfigController extends Controller
{
    public function tesda_courses_setup()
    {
        return view('tesda.pages.general_config.courses_setup');
    }
    public function tesda_grading_setup()
    {
        return view('tesda.pages.general_config.grading_setup');
    }
    public function tesda_grade_transmutation()
    {
        return view('tesda.pages.general_config.grade_transmutation');
    }
    public function tesda_batches()
    {
        return view('tesda.pages.general_config.batches');
    }
    public function tesda_gradeStatus()
    {
        return view('tesda.pages.grade_status.grade_status');
    }

    public function tesda_create_gradepointEquivalency(Request $request)
    {
         // Retrieve and validate inputs
         $gradePointDesc = $request->input('gradePointDesc');
         $gradePointScaleData = $request->input('gradePointScaleData');
 
         // Check if gradePointDesc is provided
         if (empty($gradePointDesc)) {
             return response()->json([
                 ['status' => 2, 'message' => 'Grade Point Description is required']
             ]);
         }
 
         // Check if termApplied is provided and is an array
        //  if (empty($termApplied) || !is_array($termApplied)) {
        //      return response()->json([
        //          ['status' => 2, 'message' => 'Terms should not be empty']
        //      ]);
        //  }
 
         $id = DB::table('tesda_grade_point_equivalence')->insertGetId([
             'grading_equivalence_desc' => $gradePointDesc,
             'createdby' => auth()->user()->id,
             'createddatetime' => now(),
         ]);
 
         // Insert grade point data into the database
         foreach ($gradePointScaleData as $entry) {
             DB::table('tesda_grade_point_scale')->insert([
                 'grade_point_equivalency' => $id,
                 'grade_point' => $entry['gradePointEquivalency'],
                 'letter_equivalence' => $entry['letterGradeEquivalency'],
                 'percent_equivalence' => $entry['percentEquivalency'],
                 'grade_remarks' => $entry['gradingRemarks'],
                 'createdby' => auth()->user()->id,
                 'createddatetime' => now(),
             ]);
         }
 
         return response()->json([
             ['status' => 1, 'message' => 'Grade Point Equivalency Scale Created Successfully']
         ]);
    }

    public function tesda_fetch_gradepointEquivalency()
    {

        $defaultGradePointEquivalency = [
            // ['terms' => 1, 'grade_description' => 'GRADE POINT SCALE (1.00 - 5.00 )'],
               ['grading_equivalence_desc' => 'GRADE POINT SCALE (1.00 - 5.00)'],
               ['grading_equivalence_desc' => 'GRADE POINT SCALE (4.00 - 0.00)'],

        
        ];

        // Check if setup_subjgroups table is empty
        $count = DB::table('tesda_grade_point_equivalence')->count();

        if ($count == 0) {
            // Insert default groups
            foreach ($defaultGradePointEquivalency as $group) {
                DB::table('tesda_grade_point_equivalence')->insert([
                    'grading_equivalence_desc' => $group['grading_equivalence_desc'],
                    'createdby' => auth()->user()->id,
                    'createddatetime' => now(),
                ]);
            }
        }


        $gradePointEquivalence = DB::table('tesda_grade_point_equivalence')
            ->where('tesda_grade_point_equivalence.deleted', 0)
            ->select(
                'tesda_grade_point_equivalence.id',
                'tesda_grade_point_equivalence.grading_equivalence_desc'
            )
            ->get();
    
        return response()->json($gradePointEquivalence);
    }

    // public static function tesda_fetch_selected_grade_point_equivalence(Request $request){

    //     $grade_point_equivalency = $request->get('point_equivalency_id');

    //     $grade_point_scale = DB::table('tesda_grade_point_equivalence')
    //                 ->join('college_termgrading', function($join) {
    //                     $join->on(DB::raw('FIND_IN_SET(college_termgrading.id, tesda_grade_point_equivalence.terms)'), '>', DB::raw('0'));
    //                 })
    //                 ->where('tesda_grade_point_equivalence.deleted', 0)
    //                 ->where('tesda_grade_point_equivalence.id', $grade_point_equivalency)
    //                 ->select(
    //                     'tesda_grade_point_equivalence.id',
    //                     'tesda_grade_point_equivalence.grading_equivalence_desc',
    //                     'tesda_grade_point_equivalence.id as grade_point_equivalency_id',
    //                     'tesda_grade_point_equivalence.terms as equivalency_terms',
    //                     'tesda_grade_point_equivalence.isactive',
    //                     // 'college_termgrading.description as overallterms',
    //                     DB::raw('GROUP_CONCAT(college_termgrading.description ORDER BY college_termgrading.id SEPARATOR ", ") as terms')
    //                 )
    //                 ->groupBy('tesda_grade_point_equivalence.id')
    //                 ->get();

    //                 $grade_point_equivalency_terms = DB::table('college_termgrading')
    //                 ->where('college_termgrading.deleted', 0)
    //                 ->select(
    //                     // 'college_termgrading.id',
    //                     DB::raw('GROUP_CONCAT(college_termgrading.id ORDER BY college_termgrading.id SEPARATOR ", ") as termsid'),
    //                     DB::raw('GROUP_CONCAT(college_termgrading.description ORDER BY college_termgrading.id SEPARATOR ", ") as terms')
    //                 )
    //                 ->get();
                    
    //     return response()->json([
    //         'grade_point_scale' => $grade_point_scale,
    //         'grade_point_equivalency_terms' => $grade_point_equivalency_terms
    //     ]);
    // }
    public static function tesda_fetch_selected_grade_point_equivalence(Request $request)
    {
        $grade_point_equivalency_id = $request->get('point_equivalency_id');

        $grade_point_equivalency = DB::table('tesda_grade_point_equivalence')
            ->where('tesda_grade_point_equivalence.deleted', 0)
            ->where('tesda_grade_point_equivalence.id', $grade_point_equivalency_id)
            ->select(
                'tesda_grade_point_equivalence.grading_equivalence_desc',
                'tesda_grade_point_equivalence.id as grade_point_equivalency_id'
            )
            ->first(); // Use first() instead of get() since we're fetching a single record

        $grade_point_scale = DB::table('tesda_grade_point_scale')
            ->where('tesda_grade_point_scale.deleted', 0)
            ->where('tesda_grade_point_scale.grade_point_equivalency', $grade_point_equivalency_id)
            ->select(
                'tesda_grade_point_scale.id',
                'tesda_grade_point_scale.grade_point_equivalency',
                'tesda_grade_point_scale.grade_point',
                'tesda_grade_point_scale.letter_equivalence',
                'tesda_grade_point_scale.percent_equivalence',
                'tesda_grade_point_scale.grade_remarks'
            )
            ->get();

        return response()->json([
            'grade_point_equivalency' => $grade_point_equivalency,
            'grade_point_scale' => $grade_point_scale
        ]);
    }

    public static function tesda_update_gradepointEquivalency(Request $request)
    {
        // Retrieve and validate inputs
        $gradePointDesc = $request->input('gradePointDesc');
        $gradePointScaleData = $request->input('gradePointScaleData');
        $gradePointEquivalencyID = $request->input('gradePointEquivalencyID');

        


        // Check if gradePointDesc is provided
        if (empty($gradePointDesc)) {
            return response()->json([
                ['status' => 2, 'message' => 'Grade Point Description is required']
            ]);
        }

       
        try {
            DB::table('tesda_grade_point_equivalence')
                ->where('id', $gradePointEquivalencyID)
                ->update([
                    'grading_equivalence_desc' => $gradePointDesc,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => now()
                ]);

                // Insert grade point data into the database
                DB::table('tesda_grade_point_scale')
                    ->where('grade_point_equivalency', $gradePointEquivalencyID)
                    ->delete();

                foreach ($gradePointScaleData as $entry) {
                    DB::table('tesda_grade_point_scale')->insert([
                        'grade_point_equivalency' => $gradePointEquivalencyID,
                        'grade_point' => $entry['gradePointEquivalency'],
                        'letter_equivalence' => $entry['letterGradeEquivalency'],
                        'percent_equivalence' => $entry['percentEquivalency'],
                        'grade_remarks' => $entry['gradingRemarks'],
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

    public static function tesda_delete_gradepointEquivalency(Request $request){

        try{

              $grade_point_equivalency_id = $request->get('grade_point_equivalency_id');

            //   $check_usage = DB::table('college_prospectus')
            //                     ->where('subjectID',$subjid)
            //                     ->where('deleted',0)
            //                     ->count();

            //   if($check_usage > 0){
            //         return array((object)[
            //               'status'=>0,
            //               'message'=>'Subject is already used in prospectus!'
            //         ]);
            //   }

              DB::table('tesda_grade_point_equivalence')
                    ->where('id',$grade_point_equivalency_id)
                    ->take(1)
                    ->update([
                          'deleted'=>1,
                          'deletedby'=>auth()->user()->id,
                          'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            
              return array((object)[
                    'status'=>1,
                    'message'=>'Deleted Successfully!'
              ]);
              

        }catch(\Exception $e){
              return self::store_error($e);
        }
        
  }

  public function tesda_create_gradepointTransmutation(Request $request)
  {
       // Retrieve and validate inputs
       $gradePointDesc = $request->input('gradePointDesc');
       $gradePointScaleData = $request->input('gradePointScaleData');

       // Check if gradePointDesc is provided
       if (empty($gradePointDesc)) {
           return response()->json([
               ['status' => 2, 'message' => 'Grade Point Description is required']
           ]);
       }

       // Check if termApplied is provided and is an array
      //  if (empty($termApplied) || !is_array($termApplied)) {
      //      return response()->json([
      //          ['status' => 2, 'message' => 'Terms should not be empty']
      //      ]);
      //  }

       $id = DB::table('tesda_grading_transmutation')->insertGetId([
           'grade_transmutation_desc' => $gradePointDesc,
           'createdby' => auth()->user()->id,
           'createddatetime' => now(),
       ]);

       // Insert grade point data into the database
       foreach ($gradePointScaleData as $entry) {
           DB::table('tesda_grade_transmutation_scale')->insert([
               'grade_transmutation' => $id,
               'initial_grade' => $entry['gradePointEquivalency'],
               'transmutated_grade' => $entry['percentEquivalency'],
               'createdby' => auth()->user()->id,
               'createddatetime' => now(),
           ]);
       }

       return response()->json([
           ['status' => 1, 'message' => 'Grade Point Transmutation Created Successfully']
       ]);
  }

  public function tesda_fetch_gradepointTransmutation()
  {

      $defaultGradePointTransmutation= [
          // ['terms' => 1, 'grade_description' => 'GRADE POINT SCALE (1.00 - 5.00 )'],
             ['tesda_grading_transmutation_desc' => 'Based 60 Transmutation Table'],
             ['tesda_grading_transmutation_desc' => 'Based 0 Transmutation Table'],

      
      ];

      // Check if setup_subjgroups table is empty
      $count = DB::table('tesda_grading_transmutation')->count();

      if ($count == 0) {
          // Insert default groups
          foreach ($defaultGradePointTransmutation as $group) {
              DB::table('tesda_grading_transmutation')->insert([
                  'grade_transmutation_desc' => $group['tesda_grading_transmutation_desc'],
                  'createdby' => auth()->user()->id,
                  'createddatetime' => now(),
              ]);
          }
      }


      $gradeTransmutation = DB::table('tesda_grading_transmutation')
          ->where('tesda_grading_transmutation.deleted', 0)
          ->select(
              'tesda_grading_transmutation.id',
              'tesda_grading_transmutation.grade_transmutation_desc'
          )
          ->get();
  
      return response()->json($gradeTransmutation);
  }

  public static function tesda_fetch_selected_grade_transmutation(Request $request)
  {
      $grade_transmutation_id = $request->get('grade_transmutation_id');

      $grade_transmutation = DB::table('tesda_grading_transmutation')
          ->where('tesda_grading_transmutation.deleted', 0)
          ->where('tesda_grading_transmutation.id', $grade_transmutation_id)
          ->select(
              'tesda_grading_transmutation.grade_transmutation_desc',
              'tesda_grading_transmutation.id as grade_transmutation_id'
          )
          ->first(); // Use first() instead of get() since we're fetching a single record

      $grade_transmutation_scale = DB::table('tesda_grade_transmutation_scale')
          ->where('tesda_grade_transmutation_scale.deleted', 0)
          ->where('tesda_grade_transmutation_scale.grade_transmutation', $grade_transmutation_id)
          ->select(
              'tesda_grade_transmutation_scale.id',
              'tesda_grade_transmutation_scale.grade_transmutation',
              'tesda_grade_transmutation_scale.initial_grade',
              'tesda_grade_transmutation_scale.transmutated_grade'
          )
          ->orderBy('tesda_grade_transmutation_scale.id', 'asc')
          ->get();

      return response()->json([
          'grade_transmutation' => $grade_transmutation,
          'grade_transmutation_scale' => $grade_transmutation_scale
      ]);
  }


  public static function tesda_update_grade_transmutation(Request $request)
  {
      // Retrieve and validate inputs
      $gradePointDesc = $request->input('gradePointDesc');
      $gradePointScaleData = $request->input('gradePointScaleData');
      $gradePointEquivalencyID = $request->input('gradePointEquivalencyID');

      


      // Check if gradePointDesc is provided
      if (empty($gradePointDesc)) {
          return response()->json([
              ['status' => 2, 'message' => 'Grade Transmutation Description is required']
          ]);
      }

     
      try {
          DB::table('tesda_grading_transmutation')
              ->where('id', $gradePointEquivalencyID)
              ->update([
                  'grade_transmutation_desc' => $gradePointDesc,
                  'updatedby' => auth()->user()->id,
                  'updateddatetime' => now()
              ]);

              // Insert grade point data into the database
              DB::table('tesda_grade_transmutation_scale')
                  ->where('grade_transmutation', $gradePointEquivalencyID)
                  ->delete();

              foreach ($gradePointScaleData as $entry) {
                  DB::table('tesda_grade_transmutation_scale')->insert([
                      'grade_transmutation' => $gradePointEquivalencyID,
                      'initial_grade' => $entry['gradePointEquivalency'],
                      'transmutated_grade' => $entry['percentEquivalency'],
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

  public static function tesda_delete_gradepointTransmutation(Request $request){

    try{

          $grade_point_transmutation_id = $request->get('grade_point_transmutation_id');

        //   $check_usage = DB::table('college_prospectus')
        //                     ->where('subjectID',$subjid)
        //                     ->where('deleted',0)
        //                     ->count();

        //   if($check_usage > 0){
        //         return array((object)[
        //               'status'=>0,
        //               'message'=>'Subject is already used in prospectus!'
        //         ]);
        //   }

          DB::table('tesda_grading_transmutation')
                ->where('id',$grade_point_transmutation_id)
                ->take(1)
                ->update([
                      'deleted'=>1,
                      'deletedby'=>auth()->user()->id,
                      'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
        
          return array((object)[
                'status'=>1,
                'message'=>'Deleted Successfully!'
          ]);
          

    }catch(\Exception $e){
          return self::store_error($e);
    }
    
}


}