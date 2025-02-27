<?php

namespace App\Http\Controllers\CollegeControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use \Carbon\Carbon;
use App\Models\Principal\Billing;

class CollegeGradingController extends Controller
{


    // public static function get_grades(Request $request){


    //     $syid = $request->get('syid');
    //     $semid = $request->get('semid');
    //     $courseid = $request->get('courseid');
    //     $sectionid = $request->get('sectionid');
    //     $pid = $request->get('pid');
    //     $schoolinfo = DB::table('schoolinfo')->first();
    //     $schedid = $request->get('schedid');

    //     // if(strtoupper($schoolinfo->abbreviation) == 'GBBC'){
    //         $grades = DB::table('college_studentprospectus')
    //                         ->where('semid',$semid)
    //                         ->where('syid',$syid)
    //                         ->where('schedid', $schedid)
    //                         ->where('deleted',0)
    //                         ->when(isset($request->pid), function($query) use ($request) {
    //                             return $query->whereIn('prospectusid', $request->pid);
    //                         })
    //                         //->whereIn('sectionid',$sectionid)
    //                         ->select(
    //                             'id',
    //                             'studid',
    //                             'prelemgrade',
    //                             'midtermgrade',
    //                             'prefigrade',
    //                             'finalgrade',
    //                             'prospectusID',
    //                             'prelemstatus',
    //                             'midtermstatus',
    //                             'prefistatus',
    //                             'finalstatus',
    //                             'fg',
    //                             'fgremarks'
    //                         )
    //                         ->distinct('studid')
    //                         ->get();


    //     // }else{
    //     //     $grades = DB::table('college_studentprospectus')
    //     //                 ->where('semid',$semid)
    //     //                 ->where('syid',$syid)
    //     //                 ->where('deleted',0)
    //     //                 ->where('prospectusid',$pid)
    //     //                 ->where('sectionid',$sectionid)
    //     //                 ->select(
    //     //                     'id',
    //     //                     'studid',
    //     //                     'prelemgrade',
    //     //                     'midtermgrade',
    //     //                     'prefigrade',
    //     //                     'finalgrade',
    //     //                     'prospectusID',
    //     //                     'prelemstatus',
    //     //                     'midtermstatus',
    //     //                     'prefistatus',
    //     //                     'finalstatus'
    //     //                 )
    //     //                 ->distinct('studid')
    //     //                 ->get();
    //     // }

    //     // dd($grades);

    //     return $grades;

    // }

    //v2
    // public static function get_grades(Request $request){


    //     $syid = $request->get('syid');
    //     $semid = $request->get('semid');
    //     $courseid = $request->get('courseid');
    //     $sectionid = $request->get('sectionid');
    //     $schedid = $request->get('schedid');
    //     $pid = $request->get('pid');

    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     // if(strtoupper($schoolinfo->abbreviation) == 'GBBC'){
    //         $grades = DB::table('college_studentprospectus')
    //                         ->where('semid',$semid)
    //                         ->where('syid',$syid)
    //                         ->where('schedid',$schedid)
    //                         ->where('deleted',0)
    //                         // ->when(isset($request->pid), function($query) use ($request) {
    //                         //     return $query->whereIn('prospectusid', $request->pid);
    //                         // })  N
    //                         //->whereIn('sectionid',$sectionid)
    //                         ->select(
    //                             'id',
    //                             'studid',
    //                             'prelim_excel_status',
    //                             'midterm_excel_status',
    //                             'prefi_excel_status',
    //                             'final_excel_status',
    //                             'prelemgrade',
    //                             'midtermgrade',
    //                             'prefigrade',
    //                             'finalgrade',
    //                             'prospectusID',
    //                             'prelemstatus',
    //                             'midtermstatus',
    //                             'prefistatus',
    //                             'finalstatus',
    //                             'fg',
    //                             'fgremarks'
    //                         )
    //                         ->distinct('studid')
    //                         ->get();


    //     // }else{
    //     //     $grades = DB::table('college_studentprospectus')
    //     //                 ->where('semid',$semid)
    //     //                 ->where('syid',$syid)
    //     //                 ->where('deleted',0)
    //     //                 ->where('prospectusid',$pid)
    //     //                 ->where('sectionid',$sectionid)
    //     //                 ->select(
    //     //                     'id',
    //     //                     'studid',
    //     //                     'prelemgrade',
    //     //                     'midtermgrade',
    //     //                     'prefigrade',
    //     //                     'finalgrade',
    //     //                     'prospectusID',
    //     //                     'prelemstatus',
    //     //                     'midtermstatus',
    //     //                     'prefistatus',
    //     //                     'finalstatus'
    //     //                 )
    //     //                 ->distinct('studid')
    //     //                 ->get();
    //     // }

    //     // dd($grades);

    //     return $grades;

    // }

    public static function get_grades(Request $request){


        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        $schedid = $request->get('schedid');
        $pid = $request->get('pid');

        $schoolinfo = DB::table('schoolinfo')->first();

        // if(strtoupper($schoolinfo->abbreviation) == 'GBBC'){
            $grades = DB::table('college_studentprospectus')
                            ->where('semid',$semid)
                            ->where('syid',$syid)
                            ->where('schedid',$schedid)
                            ->where('deleted',0)
                            // ->when(isset($request->pid), function($query) use ($request) {
                            //     return $query->whereIn('prospectusid', $request->pid);
                            // })  N
                            //->whereIn('sectionid',$sectionid)
                            ->select(
                                'id',
                                'studid',
                                'prelim_excel_status',
                                'midterm_excel_status',
                                'prefi_excel_status',
                                'final_excel_status',
                                'prelemgrade',
                                'midtermgrade',
                                'prefigrade',
                                'finalgrade',
                                'prospectusID',
                                'prelemstatus',
                                'midtermstatus',
                                'prefistatus',
                                'finalstatus',
                                'fg',
                                'fgremarks'
                            )
                            ->distinct('studid')
                            ->get();


        // }else{
        //     $grades = DB::table('college_studentprospectus')
        //                 ->where('semid',$semid)
        //                 ->where('syid',$syid)
        //                 ->where('deleted',0)
        //                 ->where('prospectusid',$pid)
        //                 ->where('sectionid',$sectionid)
        //                 ->select(
        //                     'id',
        //                     'studid',
        //                     'prelemgrade',
        //                     'midtermgrade',
        //                     'prefigrade',
        //                     'finalgrade',
        //                     'prospectusID',
        //                     'prelemstatus',
        //                     'midtermstatus',
        //                     'prefistatus',
        //                     'finalstatus'
        //                 )
        //                 ->distinct('studid')
        //                 ->get();
        // }

        // dd($grades);

        return $grades;

    }

    //v3 working
    // public static function get_gradesv3(Request $request){


    //     $syid = $request->get('syid');
    //     $semid = $request->get('semid');
    //     $courseid = $request->get('courseid');
    //     $sectionid = $request->get('sectionid');
    //     $schedid = $request->get('schedid');
    //     $pid = $request->get('pid');

    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     // if(strtoupper($schoolinfo->abbreviation) == 'GBBC'){
    //         // $grades = DB::table('college_studentprospectus')
    //         //                 ->where('semid',$semid)
    //         //                 ->where('syid',$syid)
    //         //                 ->where('schedid',$schedid)
    //         //                 ->where('deleted',0)
    //         //                 // ->when(isset($request->pid), function($query) use ($request) {
    //         //                 //     return $query->whereIn('prospectusid', $request->pid);
    //         //                 // })  N
    //         //                 //->whereIn('sectionid',$sectionid)
    //         //                 ->select(
    //         //                     'id',
    //         //                     'studid',
    //         //                     'prelim_excel_status',
    //         //                     'midterm_excel_status',
    //         //                     'prefi_excel_status',
    //         //                     'final_excel_status',
    //         //                     'prelemgrade',
    //         //                     'midtermgrade',
    //         //                     'prefigrade',
    //         //                     'finalgrade',
    //         //                     'prospectusID',
    //         //                     'prelemstatus',
    //         //                     'midtermstatus',
    //         //                     'prefistatus',
    //         //                     'finalstatus',
    //         //                     'fg',
    //         //                     'fgremarks'
    //         //                 )
    //         //                 ->distinct('studid')
    //         //                 ->get();

    //         $grades = DB::table('college_stud_term_grades')
    //                         // ->where('semid',$semid)
    //                         // ->where('syid',$syid)
    //                         ->where('schedid',$schedid)
    //                         ->where('deleted',0)
    //                         // ->when(isset($request->pid), function($query) use ($request) {
    //                         //     return $query->whereIn('prospectusid', $request->pid);
    //                         // })  N
    //                         //->whereIn('sectionid',$sectionid)
    //                         ->select(
    //                             'id',
    //                             'studid',
    //                             'prelim_transmuted',
    //                             'midterm_transmuted',
    //                             'prefinal_transmuted',
    //                             'final_transmuted',
    //                             'prelim_status',
    //                             'midterm_status',
    //                             'prefinal_status',
    //                             'final_status'
    //                             // 'prospectusID',
    //                             // 'prelemstatus',
    //                             // 'midtermstatus',
    //                             // 'prefistatus',
    //                             // 'finalstatus',
    //                             // 'fg',
    //                             // 'fgremarks'
    //                         )
    //                         ->distinct('studid')
    //                         ->get();


    //     // }else{
    //     //     $grades = DB::table('college_studentprospectus')
    //     //                 ->where('semid',$semid)
    //     //                 ->where('syid',$syid)
    //     //                 ->where('deleted',0)
    //     //                 ->where('prospectusid',$pid)
    //     //                 ->where('sectionid',$sectionid)
    //     //                 ->select(
    //     //                     'id',
    //     //                     'studid',
    //     //                     'prelemgrade',
    //     //                     'midtermgrade',
    //     //                     'prefigrade',
    //     //                     'finalgrade',
    //     //                     'prospectusID',
    //     //                     'prelemstatus',
    //     //                     'midtermstatus',
    //     //                     'prefistatus',
    //     //                     'finalstatus'
    //     //                 )
    //     //                 ->distinct('studid')
    //     //                 ->get();
    //     // }

    //     // dd($grades);

    //     return $grades;

    // }

    public static function get_gradesv3(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        $schedid = $request->get('schedid');
        $pid = $request->get('pid');

        $schoolinfo = DB::table('schoolinfo')->first();
        $grades = DB::table('college_stud_term_grades')

                            ->where('schedid',$schedid)
                            ->where('deleted',0)
                            ->select(
                                'id',
                                'studid',
                                'prelim_transmuted',
                                'midterm_transmuted',
                                'prefinal_transmuted',
                                'final_transmuted',
                                'final_grade_transmuted',
                                'final_remarks',
                                'prelim_status',
                                'midterm_status',
                                'prefinal_status',
                                'final_status'
                            )
                            ->distinct('studid')
                            ->get();

        // $equivalence = DB::table("college_grade_point_scale")
        //                     ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
        //                     ->where('college_grade_point_equivalence.isactive', 1)
        //                     ->select(
        //                           'college_grade_point_scale.grade_point',
        //                           'college_grade_point_scale.letter_equivalence',
        //                           'college_grade_point_scale.percent_equivalence',
        //                           'college_grade_point_scale.grade_remarks'
        //                           )
        //                     ->get();
    
        // foreach($grades as $grade){
        //     if($grade->prelim_transmuted == null || $grade->prelim_transmuted == 0){
        //             $grade->prelim_remarks = 0;
        //     }else{
        //             $grade->prelim_remarks = $equivalence->where('  ', $grade->prelim_transmuted)->first()->grade_remarks;
        //     }

        //     if($grade->midterm_transmuted == null || $grade->midterm_transmuted == 0){
        //             $grade->midterm_remarks = 0;
        //     }else{
        //             $grade->midterm_remarks = $equivalence->where('grade_point', $grade->midterm_transmuted)->first()->grade_remarks;
        //     }

        //     if($grade->prefinal_transmuted == null || $grade->prefinal_transmuted == 0){
        //             $grade->prefinal_remarks = 0;
        //     }else{
        //             $grade->prefinal_remarks = $equivalence->where('grade_point', $grade->prefinal_transmuted)->first()->grade_remarks;
        //     }

        //     if($grade->final_transmuted == null || $grade->final_transmuted == 0){
        //             $grade->final_remarks = 0;
        //     }else{
        //             $grade->final_remarks = $equivalence->where('grade_point', $grade->final_transmuted)->first()->grade_remarks;
        //     }
        // }
        return $grades;

    }


    public static function save_gradesv2(Request $request){

        $studid = $request->get('studid');
        $termgrade = $request->get('termgrade');
        $term = $request->get('term');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        $schedid = $request->get('schedid');
        $pid = $request->get('pid');
        $isinc = false;
        $isdropped = false;

        // return $request->all();

        $check = DB::table('college_studentprospectus')
                    ->where('studid',$studid)
                    ->where('semid',$semid)
                    ->where('syid',$syid)
                    ->where('deleted',0)
                    ->where('sectionid',$sectionid)
                    ->where('prospectusid',$pid)
                    ->get();



        if($termgrade == 'INC'){
            $termgrade = null;
            $isinc = true;
        }
        if($termgrade == 'DROPPED'){
            $termgrade = null;
            $isdropped = true;
        }

        if(count($check) == 0){

            DB::table('college_studentprospectus')
                ->insert([
                    $term=>$termgrade,
                    'studid'=>$studid,
                    'syid'=>$syid,
                    'semid'=>$semid,
                    'sectionid'=>$sectionid,
                    'courseid'=>$courseid,
                    'prospectusID'=>$pid,
                    'schedid'=>$schedid,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                ]);

        }else{


            DB::table('college_studentprospectus')
                ->where('id',$check[0]->id)
                ->take(1)
                ->update([
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    $term=>$termgrade,
                ]);


            if($term == 'prelemgrade'){
                $term = 'prelemstatus';
            }else if($term == 'midtermgrade'){
                $term = 'midtermstatus';
            }else if($term == 'prefigrade'){
                $term = 'prefistatus';
            }else if($term == 'finalgrade'){
                $term = 'finalstatus';
            }

            if($check[0]->prelemstatus  == 9 || $check[0]->midtermgrade  == 9 || $check[0]->prefigrade  == 9 || $check[0]->finalgrade  == 9){
                DB::table('college_studentprospectus')
                    ->where('id',$check[0]->id)
                    ->take(1)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'prelemstatus'=>null,
                        'midtermstatus'=>null,
                        'prefistatus'=>null,
                        'finalstatus'=>null,
                    ]);
            }
            if($check[0]->$term  == 8){
                DB::table('college_studentprospectus')
                    ->where('id',$check[0]->id)
                    ->take(1)
                    ->update([
                        'fgremarks'=>null,
                        // 'fgremarks'=>'INC',
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        $term=>null,
                    ]);
            }

        }

        $check = DB::table('college_studentprospectus')
                    ->where('studid',$studid)
                    ->where('semid',$semid)
                    ->where('syid',$syid)
                    ->where('deleted',0)
                    ->where('sectionid',$sectionid)
                    ->where('prospectusid',$pid)
                    ->get();


        if($isinc){

            if($term == 'prelemgrade'){
                $term = 'prelemstatus';
            }else if($term == 'midtermgrade'){
                $term = 'midtermstatus';
            }else if($term == 'prefigrade'){
                $term = 'prefistatus';
            }else if($term == 'finalgrade'){
                $term = 'finalstatus';
            }

            DB::table('college_studentprospectus')
                    ->where('id',$check[0]->id)
                    ->take(1)
                    ->update([
                        'fgremarks'=>'INC',
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        $term=>8
                    ]);

        }

        if($isdropped){

            DB::table('college_studentprospectus')
                    ->where('id',$check[0]->id)
                    ->take(1)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        // 'fg'=>'',
                        // 'fgremarks'=>'',
                        'prelemstatus'=>9,
                        'midtermstatus'=>9,
                        'prefistatus'=>9,
                        'finalstatus'=>9,
                    ]);

        }


    }

 
    //v2
    // public static function submit_grades(Request $request){
    //     try {
    //         $syid = $request->get('syid');
    //         $semid = $request->get('semid');
    //         $term = $request->get('term');
    //         $selected = $request->get('selected', []); // Default to an empty array if null
    //         $termholder = $term;

    //         if (!is_array($selected)) {
    //             $selected = []; // Ensure $selected is an array
    //         }

    //         // Map term to status column
    //         if ($term === 'prelemgrade') {
    //             $statusColumn = 'prelemstatus';
    //             $gradeColumn = 'prelemgrade';
    //         } elseif ($term === 'midtermgrade') {
    //             $statusColumn = 'midtermstatus';
    //             $gradeColumn = 'midtermgrade';
    //         } elseif ($term === 'prefigrade') {
    //             $statusColumn = 'prefistatus';
    //             $gradeColumn = 'prefigrade';
    //         } elseif ($term === 'finalgrade') {
    //             $statusColumn = 'finalstatus';
    //             $gradeColumn = 'finalgrade';
    //         } elseif ($term === 'submitall') {
    //             // Handle `submitall` to update all terms
    //             $statusColumns = [
    //                 'prelemgrade' => ['status' => 'prelemstatus', 'grade' => 'prelemgrade'],
    //                 'midtermgrade' => ['status' => 'midtermstatus', 'grade' => 'midtermgrade'],
    //                 'prefigrade' => ['status' => 'prefistatus', 'grade' => 'prefigrade'],
    //                 'finalgrade' => ['status' => 'finalstatus', 'grade' => 'finalgrade']
    //             ];
    //         } else {
    //             throw new \Exception('Invalid term');
    //         }

    //         $alreadySubmitted = false;
    //         // if ($term === 'submitall') {
    //         //     $alreadySubmitted = DB::table('college_studentprospectus')
    //         //         ->whereIn('id', $selected)
    //         //         ->where('syid', $syid)
    //         //         ->where('semid', $semid)
    //         //         ->where(function($query) {
    //         //             // $query->where('prelemstatus', '==', 2, 4)
    //         //             // ->orWhere('midtermstatus', '==', 2,4)
    //         //             // ->orWhere('prefistatus', '==', 2,4)
    //         //             // ->orWhere('finalstatus', '==', 2,4);
    //         //             $query->whereIn('prelemstatus', [2, 4])
    //         //                   ->orWhereIn('midtermstatus', [2, 4])
    //         //                   ->orWhereIn('prefistatus', [2, 4])
    //         //                   ->orWhereIn('finalstatus', [2, 4]);
    //         //         })
    //         //         ->exists();
    //         // } 
    //         if($term === 'prelemgrade'){
    //             $alreadySubmitted = DB::table('college_studentprospectus')
    //                 ->whereIn('id', $selected)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 // ->whereNotNull('prelemstatus')
    //                 ->where('prelemstatus', '!=', 3)
    //                 ->exists();
    //         }
    //         else if($term === 'midtermgrade'){
    //             $alreadySubmitted = DB::table('college_studentprospectus')
    //                 ->whereIn('id', $selected)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 // ->whereNotNull('midtermstatus')
    //                 ->where('midtermstatus', '!=', 3)
    //                 ->exists();
    //         }
    //         else if($term === 'prefigrade'){
    //             $alreadySubmitted = DB::table('college_studentprospectus')
    //                 ->whereIn('id', $selected)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 // ->whereNotNull('prefistatus')
    //                 ->where('prefistatus', '!=', 3)
    //                 ->exists();
    //         }
    //         else if($term === 'finalgrade'){
    //             $alreadySubmitted = DB::table('college_studentprospectus')
    //                 ->whereIn('id', $selected)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 // ->whereNotNull('finalstatus')
    //                 ->where('finalstatus', '!=', 3)
    //                 ->exists();
    //         }

    //         if ($alreadySubmitted) {
    //             return response()->json(['status' => 0, 'message' => 'Already Submitted']);
    //         }

    //         // if ($alreadySubmitted) {
    //         //     return response()->json(['status' => 0, 'message' => 'Already Submitted']);
    //         // }

    //         // Check if any of the selected students have already submitted grades
    //         // $alreadySubmitted = DB::table('college_studentprospectus')
    //         //     ->whereIn('id', $selected)
    //         //     ->where('syid', $syid)
    //         //     ->where('semid', $semid)
    //         //     ->where(function($query) {
    //         //         $query->whereNotNull('prelemstatus')
    //         //               ->orWhereNotNull('midtermstatus')
    //         //               ->orWhereNotNull('prefistatus')
    //         //               ->orWhereNotNull('finalstatus');
    //         //     })
    //         //     ->exists();

    //         // if ($alreadySubmitted) {
    //         //     return response()->json(['status' => 0, 'message' => 'Already Submitted']);
    //         // }

    //         if ($term === 'submitall') {
    //             foreach ($statusColumns as $key => $value) {
    //                 DB::table('college_studentprospectus')
    //                     ->whereIn('id', $selected)
    //                     ->where('syid', $syid)
    //                     ->where('semid', $semid)
    //                     ->whereNotNull($value['grade']) // Ensure the grade is not null or empty
    //                     ->where($value['grade'], '!=', '') // Ensure the grade is not an empty string
    //                     ->where(function($query) use($value) {
    //                         $query->whereNull($value['status'])
    //                               ->orWhere($value['status'], 3);
    //                     })
    //                     ->update([$value['status'] => 1]);
    //             }
    //         } else {
    //             DB::table('college_studentprospectus')
    //                 ->whereIn('id', $selected)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->whereNotNull($gradeColumn) // Ensure the grade is not null or empty
    //                 ->where($gradeColumn, '!=', '') // Ensure the grade is not an empty string
    //                 ->where(function($query) use($statusColumn) {
    //                     $query->whereNull($statusColumn)
    //                           ->orWhere($statusColumn, 3);
    //                 })
    //                 ->update([$statusColumn => 1]);
    //         }

    //         foreach ($selected as $item) {
    //             $refid = DB::table('college_studentprosstat')
    //                 ->insertGetId([
    //                     'term' => str_replace('grade', '', $termholder),
    //                     'substat' => 1,
    //                     'subby' => auth()->user()->id,
    //                     'subjstatdatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'headerid' => $item
    //                 ]);

    //             // Map termholder to reference term column
    //             if ($termholder === 'prelemgrade') {
    //                 $refTermColumn = 'prelimstatref';
    //             } elseif ($termholder === 'midtermgrade') {
    //                 $refTermColumn = 'midstatref';
    //             } elseif ($termholder === 'prefigrade') {
    //                 $refTermColumn = 'prefistatref';
    //             } elseif ($termholder === 'finalgrade') {
    //                 $refTermColumn = 'finaltermstatref';
    //             } elseif ($termholder === 'submitall') {
    //                 // Update all reference term columns
    //                 $refTermColumns = ['prelimstatref', 'midstatref', 'prefistatref', 'finaltermstatref'];
    //                 foreach ($refTermColumns as $refTermColumn) {
    //                     DB::table('college_studentprospectus')
    //                         ->where('id', $item)
    //                         ->where('syid', $syid)
    //                         ->where('semid', $semid)
    //                         ->update([$refTermColumn => $refid]);
    //                 }
    //                 continue;
    //             } else {
    //                 throw new \Exception('Invalid term holder');
    //             }

    //             DB::table('college_studentprospectus')
    //                 ->where('id', $item)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->update([$refTermColumn => $refid]);
    //         }

    //         return array((object)[
    //             'status'=>1,
    //         ]);
    //     } catch(\Exception $e){
    //         return array((object)[
    //             'status'=>0
    //         ]);
    //     }
    // }

    public static function submit_grades(Request $request){
        try {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $term = $request->get('term');
            $selected = $request->get('selected', []); // Default to an empty array if null
            $termholder = $term;

            if (!is_array($selected)) {
                $selected = []; // Ensure $selected is an array
            }

            // Map term to status column
            if ($term === 'prelim_transmuted') {
                $statusColumn = 'prelim_status';
                $gradeColumn = 'prelim_transmuted';
            } elseif ($term === 'midterm_transmuted') {
                $statusColumn = 'midterm_status';
                $gradeColumn = 'midterm_transmuted';
            } elseif ($term === 'prefinal_transmuted') {
                $statusColumn = 'prefinal_status';
                $gradeColumn = 'prefinal_transmuted';
            } elseif ($term === 'final_transmuted') {
                $statusColumn = 'final_status';
                $gradeColumn = 'final_transmuted';
            } elseif ($term === 'submitall') {
                // Handle `submitall` to update all terms
                $statusColumns = [
                    'prelim_transmuted' => ['status' => 'prelim_status', 'grade' => 'prelim_transmuted'],
                    'midterm_transmuted' => ['status' => 'midterm_status', 'grade' => 'midterm_transmuted'],
                    'prefinal_transmuted' => ['status' => 'prefinal_status', 'grade' => 'prefinal_transmuted'],
                    'final_transmuted' => ['status' => 'final_status', 'grade' => 'final_transmuted']
                ];
            } else {
                throw new \Exception('Invalid term');
            }

            $alreadySubmitted = false;
            // if ($term === 'submitall') {
            //     $alreadySubmitted = DB::table('college_studentprospectus')
            //         ->whereIn('id', $selected)
            //         ->where('syid', $syid)
            //         ->where('semid', $semid)
            //         ->where(function($query) {
            //             // $query->where('prelemstatus', '==', 2, 4)
            //             // ->orWhere('midtermstatus', '==', 2,4)
            //             // ->orWhere('prefistatus', '==', 2,4)
            //             // ->orWhere('finalstatus', '==', 2,4);
            //             $query->whereIn('prelemstatus', [2, 4])
            //                   ->orWhereIn('midtermstatus', [2, 4])
            //                   ->orWhereIn('prefistatus', [2, 4])
            //                   ->orWhereIn('finalstatus', [2, 4]);
            //         })
            //         ->exists();
            // } 
            if($term === 'prelim_transmuted'){
                $alreadySubmitted = DB::table('college_stud_term_grades')
                    ->whereIn('id', $selected)
                    // ->where('syid', $syid)
                    // ->where('semid', $semid)
                    // ->whereNotNull('prelemstatus')
                    ->where('prelim_status', '!=', 3)
                    ->exists();
            }
            else if($term === 'midterm_transmuted'){
                $alreadySubmitted = DB::table('college_stud_term_grades')
                    ->whereIn('id', $selected)
                    // ->where('syid', $syid)
                    // ->where('semid', $semid)
                    // ->whereNotNull('midtermstatus')
                    ->where('midterm_status', '!=', 3)
                    ->exists();
            }
            else if($term === 'prefinal_transmuted'){
                $alreadySubmitted = DB::table('college_stud_term_grades')
                    ->whereIn('id', $selected)
                    // ->where('syid', $syid)
                    // ->where('semid', $semid)
                    // ->whereNotNull('prefistatus')
                    ->where('prefinal_status', '!=', 3)
                    ->exists();
            }
            else if($term === 'final_transmuted'){
                $alreadySubmitted = DB::table('college_stud_term_grades')
                    ->whereIn('id', $selected)
                    // ->where('syid', $syid)
                    // ->where('semid', $semid)
                    // ->whereNotNull('finalstatus')
                    ->where('finalstatus', '!=', 3)
                    ->exists();
            }

            if ($alreadySubmitted) {
                return response()->json(['status' => 0, 'message' => 'Already Submitted']);
            }

            // if ($alreadySubmitted) {
            //     return response()->json(['status' => 0, 'message' => 'Already Submitted']);
            // }

            // Check if any of the selected students have already submitted grades
            // $alreadySubmitted = DB::table('college_studentprospectus')
            //     ->whereIn('id', $selected)
            //     ->where('syid', $syid)
            //     ->where('semid', $semid)
            //     ->where(function($query) {
            //         $query->whereNotNull('prelemstatus')
            //               ->orWhereNotNull('midtermstatus')
            //               ->orWhereNotNull('prefistatus')
            //               ->orWhereNotNull('finalstatus');
            //     })
            //     ->exists();

            // if ($alreadySubmitted) {
            //     return response()->json(['status' => 0, 'message' => 'Already Submitted']);
            // }

            if ($term === 'submitall') {
                foreach ($statusColumns as $key => $value) {
                    DB::table('college_stud_term_grades')
                        ->whereIn('id', $selected)
                        // ->where('syid', $syid)
                        // ->where('semid', $semid)
                        ->whereNotNull($value['grade']) // Ensure the grade is not null or empty
                        ->where($value['grade'], '!=', '') // Ensure the grade is not an empty string
                        ->where(function($query) use($value) {
                            $query->whereNull($value['status'])
                                  ->orWhere($value['status'], 3);
                        })
                        ->update([$value['status'] => 1]);
                }
            } else {
                DB::table('college_stud_term_grades')
                    ->whereIn('id', $selected)
                    // ->where('syid', $syid)
                    // ->where('semid', $semid)
                    ->whereNotNull($gradeColumn) // Ensure the grade is not null or empty
                    ->where($gradeColumn, '!=', '') // Ensure the grade is not an empty string
                    ->where(function($query) use($statusColumn) {
                        $query->whereNull($statusColumn)
                              ->orWhere($statusColumn, 3);
                    })
                    ->update([$statusColumn => 1]);
            }

            foreach ($selected as $item) {
                $refid = DB::table('college_studentprosstat')
                    ->insertGetId([
                        'term' => str_replace('grade', '', $termholder),
                        'substat' => 1,
                        'subby' => auth()->user()->id,
                        'subjstatdatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        'headerid' => $item
                    ]);

                // Map termholder to reference term column
                if ($termholder === 'prelim_transmuted') {
                    $refTermColumn = 'prelimstatref';
                } elseif ($termholder === 'midterm_transmuted') {
                    $refTermColumn = 'midstatref';
                } elseif ($termholder === 'prefinal_transmuted') {
                    $refTermColumn = 'prefistatref';
                } elseif ($termholder === 'final_transmuted') {
                    $refTermColumn = 'finaltermstatref';
                } elseif ($termholder === 'submitall') {
                    // Update all reference term columns
                    $refTermColumns = ['prelimstatref', 'midstatref', 'prefistatref', 'finaltermstatref'];
                    foreach ($refTermColumns as $refTermColumn) {
                        DB::table('college_studentprospectus')
                            ->where('id', $item)
                            ->where('syid', $syid)
                            ->where('semid', $semid)
                            ->update([$refTermColumn => $refid]);
                    }
                    continue;
                } else {
                    throw new \Exception('Invalid term holder');
                }

                DB::table('college_studentprospectus')
                    ->where('id', $item)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->update([$refTermColumn => $refid]);
            }

            return array((object)[
                'status'=>1,
            ]);
        } catch(\Exception $e){
            return array((object)[
                'status'=>0
            ]);
        }
    }
    //working code
    // public static function submit_grades(Request $request){
    //     try {
    //         $syid = $request->get('syid');
    //         $semid = $request->get('semid');
    //         $term = $request->get('term');
    //         $selected = $request->get('selected', []); // Default to an empty array if null
    //         $termholder = $term;

    //         if (!is_array($selected)) {
    //             $selected = []; // Ensure $selected is an array
    //         }

    //         // Map term to status column
    //         if ($term === 'prelemgrade') {
    //             $statusColumn = 'prelemstatus';
    //             $gradeColumn = 'prelemgrade';
    //         } elseif ($term === 'midtermgrade') {
    //             $statusColumn = 'midtermstatus';
    //             $gradeColumn = 'midtermgrade';
    //         } elseif ($term === 'prefigrade') {
    //             $statusColumn = 'prefistatus';
    //             $gradeColumn = 'prefigrade';
    //         } elseif ($term === 'finalgrade') {
    //             $statusColumn = 'finalstatus';
    //             $gradeColumn = 'finalgrade';
    //         } elseif ($term === 'submitall') {
    //             // Handle `submitall` to update all terms
    //             $statusColumns = [
    //                 'prelemgrade' => ['status' => 'prelemstatus', 'grade' => 'prelemgrade'],
    //                 'midtermgrade' => ['status' => 'midtermstatus', 'grade' => 'midtermgrade'],
    //                 'prefigrade' => ['status' => 'prefistatus', 'grade' => 'prefigrade'],
    //                 'finalgrade' => ['status' => 'finalstatus', 'grade' => 'finalgrade']
    //             ];
    //         } else {
    //             throw new \Exception('Invalid term');
    //         }

    //         if ($term === 'submitall') {
    //             foreach ($statusColumns as $key => $value) {
    //                 DB::table('college_studentprospectus')
    //                     ->whereIn('id', $selected)
    //                     ->where('syid', $syid)
    //                     ->where('semid', $semid)
    //                     ->whereNotNull($value['grade']) // Ensure the grade is not null or empty
    //                     ->where($value['grade'], '!=', '') // Ensure the grade is not an empty string
    //                     ->where(function($query) use($value) {
    //                         $query->whereNull($value['status'])
    //                               ->orWhere($value['status'], 3);
    //                     })
    //                     ->update([$value['status'] => 1]);
    //             }
    //         } else {
    //             DB::table('college_studentprospectus')
    //                 ->whereIn('id', $selected)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->whereNotNull($gradeColumn) // Ensure the grade is not null or empty
    //                 ->where($gradeColumn, '!=', '') // Ensure the grade is not an empty string
    //                 ->where(function($query) use($statusColumn) {
    //                     $query->whereNull($statusColumn)
    //                           ->orWhere($statusColumn, 3);
    //                 })
    //                 ->update([$statusColumn => 1]);
    //         }

    //         foreach ($selected as $item) {
    //             $refid = DB::table('college_studentprosstat')
    //                 ->insertGetId([
    //                     'term' => str_replace('grade', '', $termholder),
    //                     'substat' => 1,
    //                     'subby' => auth()->user()->id,
    //                     'subjstatdatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'headerid' => $item
    //                 ]);

    //             // Map termholder to reference term column
    //             if ($termholder === 'prelemgrade') {
    //                 $refTermColumn = 'prelimstatref';
    //             } elseif ($termholder === 'midtermgrade') {
    //                 $refTermColumn = 'midstatref';
    //             } elseif ($termholder === 'prefigrade') {
    //                 $refTermColumn = 'prefistatref';
    //             } elseif ($termholder === 'finalgrade') {
    //                 $refTermColumn = 'finaltermstatref';
    //             } elseif ($termholder === 'submitall') {
    //                 // Update all reference term columns
    //                 $refTermColumns = ['prelimstatref', 'midstatref', 'prefistatref', 'finaltermstatref'];
    //                 foreach ($refTermColumns as $refTermColumn) {
    //                     DB::table('college_studentprospectus')
    //                         ->where('id', $item)
    //                         ->where('syid', $syid)
    //                         ->where('semid', $semid)
    //                         ->update([$refTermColumn => $refid]);
    //                 }
    //                 continue;
    //             } else {
    //                 throw new \Exception('Invalid term holder');
    //             }

    //             DB::table('college_studentprospectus')
    //                 ->where('id', $item)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->update([$refTermColumn => $refid]);
    //         }

    //         return array((object)[
    //             'status'=>1,
    //         ]);
    //     } catch(\Exception $e){
    //         return array((object)[
    //             'status'=>0
    //         ]);
    //     }
    // }

    // public static function submit_grades(Request $request){
    //     try {
    //         $syid = $request->get('syid');
    //         $semid = $request->get('semid');
    //         $term = $request->get('term');
    //         $selected = $request->get('selected', []); // Default to an empty array if null
    //         $termholder = $term;

    //         if (!is_array($selected)) {
    //             $selected = []; // Ensure $selected is an array
    //         }

    //         // Map term to status column
    //         if ($term === 'prelemgrade') {
    //             $statusColumn = 'prelemstatus';
    //         } elseif ($term === 'midtermgrade') {
    //             $statusColumn = 'midtermstatus';
    //         } elseif ($term === 'prefigrade') {
    //             $statusColumn = 'prefistatus';
    //         } elseif ($term === 'finalgrade') {
    //             $statusColumn = 'finalstatus';
    //         } elseif ($term === 'submitall') {
    //             // Handle `submitall` to update all terms
    //             $statusColumns = [
    //                 'prelemgrade' => 'prelemstatus',
    //                 'midtermgrade' => 'midtermstatus',
    //                 'prefigrade' => 'prefistatus',
    //                 'finalgrade' => 'finalstatus'
    //             ];
    //         }else {
    //             throw new \Exception('Invalid term');
    //         }

    //         if ($term === 'submitall') {
    //             foreach ($statusColumns as $key => $col) {
    //                 DB::table('college_studentprospectus')
    //                     ->whereIn('id', $selected)
    //                     ->where('syid', $syid)
    //                     ->where('semid', $semid)
    //                     ->where(function($query) use($col) {
    //                         $query->whereNull($col)
    //                               ->orWhere($col, 3);
    //                     })
    //                     ->update([$col => 1]);
    //             }
    //         } else {
    //             DB::table('college_studentprospectus')
    //                 ->whereIn('id', $selected)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->where(function($query) use($statusColumn) {
    //                     $query->whereNull($statusColumn)
    //                           ->orWhere($statusColumn, 3);
    //                 })
    //                 ->update([$statusColumn => 1]);
    //         }

    //         // // Update college_studentprospectus table
    //         // DB::table('college_studentprospectus')
    //         //     ->whereIn('id', $selected)
    //         //     ->where('syid', $syid)
    //         //     ->where('semid', $semid)
    //         //     ->where(function($query) use($statusColumn) {
    //         //         $query->whereNull($statusColumn)
    //         //               ->orWhere($statusColumn, 3);
    //         //     })
    //         //     ->update([$statusColumn => 1]);

    //         foreach ($selected as $item) {
    //             $refid = DB::table('college_studentprosstat')
    //                 ->insertGetId([
    //                     'term' => str_replace('grade', '', $termholder),
    //                     'substat' => 1,
    //                     'subby' => auth()->user()->id,
    //                     'subjstatdatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'headerid' => $item
    //                 ]);

    //             // Map termholder to reference term column
    //             if ($termholder === 'prelemgrade') {
    //                 $refTermColumn = 'prelimstatref';
    //             } elseif ($termholder === 'midtermgrade') {
    //                 $refTermColumn = 'midstatref';
    //             } elseif ($termholder === 'prefigrade') {
    //                 $refTermColumn = 'prefistatref';
    //             } elseif ($termholder === 'finalgrade') {
    //                 $refTermColumn = 'finaltermstatref';
    //             } elseif ($termholder === 'submitall') {
    //                 $refTermColumn = 'prelimstatref';
    //                 $refTermColumn = 'midstatref';
    //                 $refTermColumn = 'prefistatref';
    //                 $refTermColumn = 'finaltermstatref';
    //             }
    //              else {
    //                 throw new \Exception('Invalid term holder');
    //             }

    //             DB::table('college_studentprospectus')
    //                 ->where('id', $item)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->update([$refTermColumn => $refid]);
    //         }

    //     //     return response()->json(['status' => 1]);
    //     // } catch (\Exception $e) {
    //     //     return response()->json(['status' => 0, 'message' => $e->getMessage()]);
    //     // }
    //           return array((object)[
    //                     'status'=>1,
    //                 ]);
    //             }catch(\Exception $e){
    //                 return array((object)[
    //                     'status'=>0
    //                 ]);
    //             }
    // }

    // public static function submit_grades(Request $request){
    //     try {
    //         $syid = $request->get('syid');
    //         $semid = $request->get('semid');
    //         $term = $request->get('term');
    //         $selected = $request->get('selected', []); // Default to an empty array if null
    //         $termholder = $term;

    //         if (!is_array($selected)) {
    //             $selected = []; // Ensure $selected is an array
    //         }

    //         // Map term to status column
    //         if ($term === 'prelemgrade') {
    //             $statusColumn = 'prelemstatus';
    //         } elseif ($term === 'midtermgrade') {
    //             $statusColumn = 'midtermstatus';
    //         } elseif ($term === 'prefigrade') {
    //             $statusColumn = 'prefistatus';
    //         } elseif ($term === 'finalgrade') {
    //             $statusColumn = 'finalstatus';
    //         } elseif ($term === 'submitall') {
    //             // Handle `submitall` to update all terms
    //             $statusColumns = [
    //                 'prelemgrade' => 'prelemstatus',
    //                 'midtermgrade' => 'midtermstatus',
    //                 'prefigrade' => 'prefistatus',
    //                 'finalgrade' => 'finalstatus'
    //             ];
    //         }else {
    //             throw new \Exception('Invalid term');
    //         }

    //         if ($term === 'submitall') {
    //             foreach ($statusColumns as $key => $col) {
    //                 DB::table('college_studentprospectus')
    //                     ->whereIn('id', $selected)
    //                     ->where('syid', $syid)
    //                     ->where('semid', $semid)
    //                     ->where(function($query) use($col) {
    //                         $query->whereNull($col)
    //                               ->orWhere($col, 3);
    //                     })
    //                     ->update([$col => 1]);
    //             }
    //         } else {
    //             DB::table('college_studentprospectus')
    //                 ->whereIn('id', $selected)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->where(function($query) use($statusColumn) {
    //                     $query->whereNull($statusColumn)
    //                           ->orWhere($statusColumn, 3);
    //                 })
    //                 ->update([$statusColumn => 1]);
    //         }

    //         // // Update college_studentprospectus table
    //         // DB::table('college_studentprospectus')
    //         //     ->whereIn('id', $selected)
    //         //     ->where('syid', $syid)
    //         //     ->where('semid', $semid)
    //         //     ->where(function($query) use($statusColumn) {
    //         //         $query->whereNull($statusColumn)
    //         //               ->orWhere($statusColumn, 3);
    //         //     })
    //         //     ->update([$statusColumn => 1]);

    //         foreach ($selected as $item) {
    //             $refid = DB::table('college_studentprosstat')
    //                 ->insertGetId([
    //                     'term' => str_replace('grade', '', $termholder),
    //                     'substat' => 1,
    //                     'subby' => auth()->user()->id,
    //                     'subjstatdatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'headerid' => $item
    //                 ]);

    //             // Map termholder to reference term column
    //             if ($termholder === 'prelemgrade') {
    //                 $refTermColumn = 'prelimstatref';
    //             } elseif ($termholder === 'midtermgrade') {
    //                 $refTermColumn = 'midstatref';
    //             } elseif ($termholder === 'prefigrade') {
    //                 $refTermColumn = 'prefistatref';
    //             } elseif ($termholder === 'finalgrade') {
    //                 $refTermColumn = 'finaltermstatref';
    //             } elseif ($termholder === 'submitall') {
    //                 $refTermColumn = 'prelimstatref';
    //                 $refTermColumn = 'midstatref';
    //                 $refTermColumn = 'prefistatref';
    //                 $refTermColumn = 'finaltermstatref';
    //             }
    //              else {
    //                 throw new \Exception('Invalid term holder');
    //             }

    //             DB::table('college_studentprospectus')
    //                 ->where('id', $item)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->update([$refTermColumn => $refid]);
    //         }

    //     //     return response()->json(['status' => 1]);
    //     // } catch (\Exception $e) {
    //     //     return response()->json(['status' => 0, 'message' => $e->getMessage()]);
    //     // }
    //           return array((object)[
    //                     'status'=>1,
    //                 ]);
    //             }catch(\Exception $e){
    //                 return array((object)[
    //                     'status'=>0
    //                 ]);
    //             }
    // }


    public static function grade_subjects_ajax(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $teacherid = $request->get('teacherid');

        $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->first()
                        ->id;

        $subjects = self::subjects($syid,$semid,$teacherid);

        foreach($subjects as $item){

            $schedotherclass = DB::table('college_scheddetail')
                                    ->where('headerid',$item->schedid)
                                    ->select('schedotherclass')
                                    ->first();

            $item->schedotherclass = isset($schedotherclass->schedotherclass) ? $schedotherclass->schedotherclass : null;
            $item->students = array();
            $item->students = self::enrolled_learners($syid,$semid,$item->schedid,$item->subjectID);

            $schedule = Db::table('college_scheddetail')
                            ->leftJoin('rooms',function($join){
                                $join->on('college_scheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted',0);
                            })
                            ->leftJoin('days',function($join){
                                $join->on('college_scheddetail.day','=','days.id');
                            })
                            ->where('college_scheddetail.headerID',$item->schedid)
                            ->where('college_scheddetail.deleted',0)
                            ->select(
                                'college_scheddetail.stime',
                                'college_scheddetail.etime',
                                'college_scheddetail.day',
                                'days.description',
                                'rooms.roomname',
                                'schedotherclass'
                            )
                            ->get();

            $sched_list = array();
            $dayString = '';
            $schedotherclass = '';
            $days = [];
            $room = null;

            foreach($schedule as $sched_item){
                $start = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
                $end = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                $time = $start.' - '.$end;
                $dayString.= substr($sched_item->description, 0,3).'/';
                $sort = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('HH:mm A');
                array_push($days,$sched_item->day);
                $schedotherclass = $sched_item->schedotherclass;
                $room = $sched_item->roomname;





            }





            $check_group = DB::table('college_schedgroup_detail')
                                    ->where('college_schedgroup_detail.deleted',0)
                                    ->join('college_schedgroup',function($join){
                                          $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                          $join->where('college_schedgroup.deleted',0);
                                    })
                                    ->where('schedid',$item->schedid)
                                        ->leftJoin('college_courses',function($join){
                                            $join->on('college_schedgroup.courseid','=','college_courses.id');
                                            $join->where('college_courses.deleted',0);
                                    })
                                    ->leftJoin('gradelevel',function($join){
                                            $join->on('college_schedgroup.levelid','=','gradelevel.id');
                                            $join->where('gradelevel.deleted',0);
                                    })
                                    ->leftJoin('college_colleges',function($join){
                                            $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                                            $join->where('college_colleges.deleted',0);
                                    })
                                    ->select(
                                        'college_schedgroup.courseid',
                                        'college_schedgroup.levelid',
                                        'college_schedgroup.collegeid',
                                        'courseDesc',
                                        'collegeDesc',
                                        'levelname',
                                        'courseabrv',
                                        'collegeabrv',
                                        'college_schedgroup.id',
                                        'college_schedgroup.schedgroupdesc'
                                    )
                                    ->get();

            foreach($check_group as $schedgroupitem){
                $text = '';
                if($schedgroupitem->courseid != null){
                        $text = $schedgroupitem->courseabrv;
                }else{
                        $text = $schedgroupitem->collegeabrv;
                }
                $text .= '-'.$schedgroupitem->levelname[0] . ' '.$schedgroupitem->schedgroupdesc;
                $schedgroupitem->schedgroupdesc = $text;
                $item->sectionDesc = $text;

            }

            array_push($sched_list,(object)[
                'day'=>substr($dayString,0, -3),
                'start'=>$start,
                'end'=>$end,
                'time'=>$time,
                'days'=>$days
            ]);

            if(count($check_group) == 0){
                $item->sectionDesc = '';
            }

            $item->schedule = $sched_list;
            $item->levelanme = '';
            $item->courseabrv = '';
            $item->sections = $check_group;
            $item->room = $room;

        }

        return $subjects;

    }

public static function formativeHighestScore(Request $request)
{
    $F1HighestScore = (float) $request->input('F1HighestScore');
    $F2HighestScore = (float) $request->input('F2HighestScore');
    $F3HighestScore = (float) $request->input('F3HighestScore');
    $F4HighestScore = (float) $request->input('F4HighestScore');

    $S1HighestScore = (float) $request->input('S1HighestScore');
    $S2HighestScore = (float) $request->input('S2HighestScore');
    $S3HighestScore = (float) $request->input('S3HighestScore');

    $F1Score = (float) $request->input('F1Score');
    $F2Score = (float) $request->input('F2Score');
    $F3Score = (float) $request->input('F3Score');
    $F4Score = (float) $request->input('F4Score');

    $S1Score = (float) $request->input('S1Score');
    $S2Score = (float) $request->input('S2Score');
    $S3Score = (float) $request->input('S3Score');

    $UR1HighestScore = (float) $request->input('UR1HighestScore');
    $UR2HighestScore = (float) $request->input('UR2HighestScore');
    $UR3HighestScore = (float) $request->input('UR3HighestScore');
    $UR4HighestScore = (float) $request->input('UR4HighestScore');

    $TR1HighestScore = (float) $request->input('TR1HighestScore');
    $TR2HighestScore = (float) $request->input('TR2HighestScore');
    $TR3HighestScore = (float) $request->input('TR3HighestScore');

    $term_exam = (float) $request->input('term_exam');

    $uR1Score = (float) $request->input('uR1Score');
    $uR2Score = (float) $request->input('uR2Score');
    $uR3Score = (float) $request->input('uR3Score');
    $uR4Score = (float) $request->input('uR4Score');

    $TR1Score = (float) $request->input('TR1Score');
    $TR2Score = (float) $request->input('TR2Score');
    $TR3Score = (float) $request->input('TR3Score');

    $term_examHighestScore = (float) $request->input('term_examHighestScore');

    // Helper function to safely divide
    $safeDivide = function($numerator, $denominator) {
        return $denominator != 0 ? $numerator / $denominator : 0;
    };

    // Calculate the maximum possible scores for formative and summative assessments
    $maxFormativeScore = $F1HighestScore + $F2HighestScore + $F3HighestScore + $F4HighestScore;
    $maxSummativeScore = $S1HighestScore + $S2HighestScore + $S3HighestScore;

    // Calculate the actual scores for formative and summative assessments
    $actualFormativeScore = $F1HighestScore + $F2HighestScore + $F3HighestScore + $F4HighestScore;
    $actualSummativeScore = $S1HighestScore + $S2HighestScore + $S3HighestScore;

    // Calculate percentage representation of formative and summative scores
    $percentageFormative = $safeDivide($actualFormativeScore, $maxFormativeScore) * 50;
    $percentageSummative = $safeDivide($actualSummativeScore, $maxSummativeScore) * 50;

    // Calculate the total percentage for formative and summative scores combined
    $totalPercentage = $percentageFormative + $percentageSummative;

    $other_requirements_geHighestScorePercent = $safeDivide($totalPercentage, 100) * 20;

    $maxUnitRequirementScore = $UR1HighestScore + $UR2HighestScore + $UR3HighestScore + $UR4HighestScore;
    $maxTerminalRequirementScore = $TR1HighestScore + $TR2HighestScore + $TR3HighestScore;

    $actualUnitRequirementScore = $UR1HighestScore + $UR2HighestScore + $UR3HighestScore + $UR4HighestScore;
    $actualTerminalRequirementScore = $TR1HighestScore + $TR2HighestScore + $TR3HighestScore;

    $percentageUnitRequirement = $safeDivide($actualUnitRequirementScore, $maxUnitRequirementScore) * 50;
    $percentageTerminalRequirement = $safeDivide($actualTerminalRequirementScore, $maxTerminalRequirementScore) * 50;

    $totalPercentage3 = $percentageUnitRequirement + $percentageTerminalRequirement;

    $performance_tasks_geHighestScorePercent = $safeDivide($totalPercentage3, 100) * 60;

    $maxTermExamScore = $term_examHighestScore;
    $actualTermExamScore = $term_examHighestScore;

    $percentageTermExam = $safeDivide($actualTermExamScore, $maxTermExamScore) * 100;

    $term_examination_geHighestScorePercent = $safeDivide($percentageTermExam, 100) * 20;

    // Calculate the total possible scores for formative and summative assessments
    $totalFormativeHighestScore = $maxFormativeScore;
    $totalSummativeHighestScore = $maxSummativeScore;

    // Calculate actual scores for formative and summative assessments
    $actualFormativeScore2 = $F1Score + $F2Score + $F3Score + $F4Score;
    $actualSummativeScore2 = $S1Score + $S2Score + $S3Score;

    // Calculate percentage representation of formative and summative scores
    $percentageFormative2 = $safeDivide($actualFormativeScore2, $totalFormativeHighestScore) * 50;
    $percentageSummative2 = $safeDivide($actualSummativeScore2, $totalSummativeHighestScore) * 50;

    // Calculate the total percentage for formative and summative scores combined
    $totalPercentage2 = $percentageFormative2 + $percentageSummative2;

    $other_requirements_geHighestScorePercent2 = $safeDivide($totalPercentage2, 100) * 20;

    $totalUnitRequirementHighestScore = $maxUnitRequirementScore;
    $totalTerminalRequirementHighestScore = $maxTerminalRequirementScore;

    // Calculate actual scores for unit and terminal requirements
    $actualUnitRequirementScore2 = $uR1Score + $uR2Score + $uR3Score + $uR4Score;
    $actualTerminalRequirementScore2 = $TR1Score + $TR2Score + $TR3Score;

    // Calculate percentage representation of unit and terminal requirement scores
    $percentageUnitRequirement2 = $safeDivide($actualUnitRequirementScore2, $totalUnitRequirementHighestScore) * 50;
    $percentageTerminalRequirement2 = $safeDivide($actualTerminalRequirementScore2, $totalTerminalRequirementHighestScore) * 50;

    // Calculate the total percentage for unit and terminal requirement scores combined
    $totalPercentage4 = $percentageUnitRequirement2 + $percentageTerminalRequirement2;

    $performance_tasks_geHighestScorePercent2 = $safeDivide($totalPercentage4, 100) * 60;

    $TotalmaxTermExamScore = $maxTermExamScore;
    $actualTermExamScore2 = $term_exam;

    $percentageTermExam2 = $safeDivide($actualTermExamScore2, $TotalmaxTermExamScore) * 100;

    $term_examination_geHighestScorePercent2 = $safeDivide($percentageTermExam2, 100) * 20;

    $total_highest_scorepercentage = $other_requirements_geHighestScorePercent +
                        $performance_tasks_geHighestScorePercent +
                        $term_examination_geHighestScorePercent;

    $total_scorepercentage = $other_requirements_geHighestScorePercent2 +
                        $performance_tasks_geHighestScorePercent2 +
                        $term_examination_geHighestScorePercent2;

    return response()->json([
        'totalHighestScore1' => $maxFormativeScore,
        'generalAverageHighestScore1' => $percentageFormative,
        'totalHighestScore2' => $maxSummativeScore,
        'generalAverageHighestScore2' => $percentageSummative,
        'other_requirements_geHighestScoreGE' => $totalPercentage,
        'other_requirements_geHighestScorePercent' => $other_requirements_geHighestScorePercent,
        'totalScore' => $actualFormativeScore2,
        'generalAverageScore' => $percentageFormative2,
        'totalScore2' => $actualSummativeScore2,
        'generalAverageScore2' => $percentageSummative2,
        'other_requirements_geHighestScoreGE2' => $totalPercentage2,
        'other_requirements_geHighestScorePercent2' => $other_requirements_geHighestScorePercent2,
        'maxUnitRequirementScore' => $maxUnitRequirementScore,
        'percentageUnitRequirement' => $percentageUnitRequirement,
        'maxTerminalRequirementScore' => $maxTerminalRequirementScore,
        'percentageTerminalRequirement' => $percentageTerminalRequirement,
        'performance_tasks_geHighestScoreGE' => $totalPercentage3,
        'performance_tasks_geHighestScorePercent' => $performance_tasks_geHighestScorePercent,
        'totalUnitRequirementHighestScore' => $actualUnitRequirementScore2,
        'UnitRequirementgeneralAverageScore' => $percentageUnitRequirement2,
        'totalTerminalRequirementHighestScore' => $actualTerminalRequirementScore2,
        'TerminalRequirementgeneralAverageScore' => $percentageTerminalRequirement2,
        'performance_tasks_geHighestScoreGE2' => $totalPercentage4,
        'performance_tasks_geHighestScorePercent2' => $performance_tasks_geHighestScorePercent2,
        'totalExamScore' => $maxTermExamScore,
        'term_exam_generalAverageHighestScore' => $percentageTermExam,
        'term_exam_percentage' => $term_examination_geHighestScorePercent,
        'totalExamScore2' => $actualTermExamScore2,
        'term_exam_generalAverageHighestScore2' => $percentageTermExam2,
        'term_exam_percentage2' => $term_examination_geHighestScorePercent2,
        'total_highest_scorepercentage' => $total_highest_scorepercentage,
        'total_scorepercentage' => $total_scorepercentage,
    ]);
}


public function getExistingGrades(Request $request)
    {
        // Validate incoming request data
        // $request->validate([
        //     'sid' => 'required|integer',
        //     'syid' => 'required|integer',
        //     'semid' => 'required|integer',
        //     'sectionid' => 'required|integer',
        //     'sched' => 'required|integer',
        // ]);

        // Retrieve grade data from the request
        $gradeData = $request->only(['syid', 'semid', 'sectionid', 'sched']);

        // Query the database for existing grades
        $existingGrades = DB::table('college_studentprospectus')
            // ->where('studid', $gradeData['sid'])
            ->where('syid', $gradeData['syid'])
            ->where('semid', $gradeData['semid'])
            ->where('sectionid', $gradeData['sectionid'])
            ->where('schedid', $gradeData['sched'])
            ->first(['prelemgrade']);

        // Check if grades were found and return appropriate response
        if ($existingGrades) {
            return response()->json([
                'success' => true,
                'data' => $existingGrades
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Grades not found.'
            ], 404);
        }
    }

    //working v3 code
    // public static function save_grades(Request $request){

    //     $studid = $request->get('studid');
    //     $termgrade = number_format(floatval($request->get('termgrade')), 2, '.', '');
    //     $term = $request->get('term');
    //     $schedid = $request->get('schedid');
    //     $isinc = false;
    //     $isdropped = false;

    //    // return $request->all();

    //     $check = DB::table('college_stud_term_grades')
    //                 ->where('studid', $studid)
    //                 ->where('deleted', 0)
    //                 ->get();

    //     if ($request->get('termgrade') == 'INC') {
    //         $termgrade = null;
    //         $isinc = true;
    //     }
    //     if ($request->get('termgrade') == 'DROPPED') {
    //         $termgrade = null;
    //         $isdropped = true;
    //     }

    //     if (count($check) == 0) {

    //         DB::table('college_stud_term_grades')
    //             ->insert([
    //                 $term => $termgrade,
    //                 'studid' => $studid,
    //                 'schedid' => $schedid,
    //                 'createdby' => auth()->user()->id,
    //                 'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //             ]);

    //     } else {

    //         DB::table('college_stud_term_grades')
    //             ->where('id', $check[0]->id)
    //             ->take(1)
    //             ->update([
    //                 'updatedby' => auth()->user()->id,
    //                 'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                 $term => $termgrade,
    //             ]);

    //         if ($term == 'prelim_transmuted') {
    //             $term = 'prelim_status';
    //         } else if ($term == 'midterm_transmuted') {
    //             $term = 'midterm_status';
    //         } else if ($term == 'prefinal_transmuted') {
    //             $term = 'prefinal_status';
    //         } else if ($term == 'final_transmuted') {
    //             $term = 'final_status';
    //         }

    //         if ($check[0]->prelim_status == 9 || $check[0]->midterm_transmuted == 9 || $check[0]->prefinal_transmuted == 9 || $check[0]->final_transmuted == 9) {
    //             DB::table('college_stud_term_grades')
    //                 ->where('id', $check[0]->id)
    //                 ->take(1)
    //                 ->update([
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'prelim_status' => null,
    //                     'midterm_status' => null,
    //                     'prefinal_status' => null,
    //                     'final_status' => null,
    //                 ]);
    //         }
    //         if ($check[0]->$term == 8) {
    //             DB::table('college_stud_term_grades')
    //                 ->where('id', $check[0]->id)
    //                 ->take(1)
    //                 ->update([
    //                     'fgremarks' => null,
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     $term => null,
    //                 ]);
    //         }
    //     }

    //     $check = DB::table('college_stud_term_grades')
    //                 ->where('studid', $studid)
    //                 ->where('deleted', 0)
    //                 ->get();
    // }


    //not final
    //     public static function save_grades(Request $request){

    //     $studid = $request->get('studid');
    //     $termgrade = number_format(floatval($request->get('termgrade')), 2, '.', '');
    //     $term = $request->get('term');
    //     $schedid = $request->get('schedid');
    //     $isinc = false;
    //     $isdropped = false;
        

    //     $check = DB::table('college_stud_term_grades')
    //                 ->where('studid', $studid)
    //                 ->where('deleted', 0)
    //                 ->get();

    //     if ($request->get('termgrade') == 'INC') {
    //         $termgrade = null;
    //         $isinc = true;
    //     }
    //     if ($request->get('termgrade') == 'DROPPED') {
    //         $termgrade = null;
    //         $isdropped = true;
    //     }

    //     if (count($check) == 0) {
    //         DB::table('college_stud_term_grades')
    //             ->insert([
    //                 $term => $termgrade,
    //                 'studid' => $studid,
    //                 'schedid' => $schedid,
    //                 'createdby' => auth()->user()->id,
    //                 'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //             ]);
    //     } else {
    //         DB::table('college_stud_term_grades')
    //             ->where('id', $check[0]->id)
    //             ->take(1)
    //             ->update([
    //                 'updatedby' => auth()->user()->id,
    //                 'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                 $term => $termgrade,
    //             ]);

    //         if ($term == 'prelim_transmuted') {
    //             $term = 'prelim_status';
    //         } else if ($term == 'midterm_transmuted') {
    //             $term = 'midterm_status';
    //         } else if ($term == 'prefinal_transmuted') {
    //             $term = 'prefinal_status';
    //         } else if ($term == 'final_transmuted') {
    //             $term = 'final_status';
    //         }

    //         if ($check[0]->prelim_status == 9 || $check[0]->midterm_transmuted == 9 || $check[0]->prefinal_transmuted == 9 || $check[0]->final_transmuted == 9) {
    //             DB::table('college_stud_term_grades')
    //                 ->where('id', $check[0]->id)
    //                 ->take(1)
    //                 ->update([
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     'prelim_status' => null,
    //                     'midterm_status' => null,
    //                     'prefinal_status' => null,
    //                     'final_status' => null,
    //                 ]);
    //         }
    //         if ($check[0]->$term == 8) {
    //             DB::table('college_stud_term_grades')
    //                 ->where('id', $check[0]->id)
    //                 ->take(1)
    //                 ->update([
    //                     'fgremarks' => null,
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                     $term => null,
    //                 ]);
    //         }
    //     }

    //     if ($term === "final_grade_transmuted") {
    //         $equivalence = DB::table("college_grade_point_scale")
    //             ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
    //             ->where('college_grade_point_equivalence.isactive', 1)
    //             ->select(
    //                 'college_grade_point_scale.grade_point',
    //                 'college_grade_point_scale.letter_equivalence',
    //                 'college_grade_point_scale.percent_equivalence',
    //                 'college_grade_point_scale.grade_remarks'
    //             )
    //             ->get();

    //         foreach ($check as $grade) {
    //             if ($grade->final_grade_transmuted != null && $grade->final_grade_transmuted != 0) {
    //                 $final_remarks = $equivalence->firstWhere('grade_point', $grade->final_grade_transmuted)->grade_remarks;
    //             } else {
    //                 $final_remarks = 0;
    //             }

    //             DB::table('college_stud_term_grades')
    //                 ->where('id', $grade->id)
    //                 ->update([
    //                     'final_remarks' => $final_remarks,
    //                     'updatedby' => auth()->user()->id,
    //                     'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //                 ]);
    //         }
    //     }

    //     $check = DB::table('college_stud_term_grades')
    //                 ->where('studid', $studid)
    //                 ->where('deleted', 0)
    //                 ->get();
    // }


    public static function save_grades(Request $request){

        $studid = $request->get('studid');
        $termgrade = number_format(floatval($request->get('termgrade')), 2, '.', '');
        $term = $request->get('term');
        $schedid = $request->get('schedid');
        $isinc = false;
        $isdropped = false;
        
    
        $check = DB::table('college_stud_term_grades')
                    ->where('studid', $studid)
                    ->where('deleted', 0)
                    ->get();
    
        if ($request->get('termgrade') == 'INC') {
            $termgrade = null;
            $isinc = true;
        }
        if ($request->get('termgrade') == 'DROPPED') {
            $termgrade = null;
            $isdropped = true;
        }
    
        if (count($check) == 0) {
            DB::table('college_stud_term_grades')
                ->insert([
                    $term => $termgrade,
                    'studid' => $studid,
                    'schedid' => $schedid,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                ]);
        } else {
            DB::table('college_stud_term_grades')
                ->where('id', $check[0]->id)
                ->take(1)
                ->update([
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    $term => $termgrade,
                ]);
    
            if ($term == 'prelim_transmuted') {
                $term = 'prelim_status';
            } else if ($term == 'midterm_transmuted') {
                $term = 'midterm_status';
            } else if ($term == 'prefinal_transmuted') {
                $term = 'prefinal_status';
            } else if ($term == 'final_transmuted') {
                $term = 'final_status';
            }
    
            if ($check[0]->prelim_status == 9 || $check[0]->midterm_transmuted == 9 || $check[0]->prefinal_transmuted == 9 || $check[0]->final_transmuted == 9) {
                DB::table('college_stud_term_grades')
                    ->where('id', $check[0]->id)
                    ->take(1)
                    ->update([
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        'prelim_status' => null,
                        'midterm_status' => null,
                        'prefinal_status' => null,
                        'final_status' => null,
                    ]);
            }
            if ($check[0]->$term == 8) {
                DB::table('college_stud_term_grades')
                    ->where('id', $check[0]->id)
                    ->take(1)
                    ->update([
                        'fgremarks' => null,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        $term => null,
                    ]);
            }
        }
    
        if ($term === "final_remarks") {

            $equivalence = DB::table("college_grade_point_scale")
            ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
            ->where('college_grade_point_equivalence.isactive', 1)
            ->select(
                'college_grade_point_scale.grade_point',
                'college_grade_point_scale.letter_equivalence',
                'college_grade_point_scale.percent_equivalence',
                'college_grade_point_scale.grade_remarks'
            )
            ->get();

        foreach ($check as $grade) {
            $final_remarks = 0;
            if ($grade->final_grade_transmuted !== null && $grade->final_grade_transmuted != 0) {
                $equivalenceEntry = $equivalence->firstWhere('grade_point', $grade->final_grade_transmuted);
                if ($equivalenceEntry) {
                    $final_remarks = $equivalenceEntry->grade_remarks;
                }
                else{
                    $final_remarks = "---";
                }
            }

            DB::table('college_stud_term_grades')
                ->where('id', $grade->id)
                ->update([
                    // 'final_grade_transmuted' =>$termgrade,
                    'final_remarks' => $final_remarks,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                ]);
        }

        }
          
        
      
    
        $check = DB::table('college_stud_term_grades')
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->get();

    }

    public function get_terms(Request $request){
        // $schedid = request()->get('schedid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $terms = DB::table('college_ecr_term')
                    ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
                    ->join('college_ecr', 'college_ecr_term.ecrID', '=', 'college_ecr.id')
                    // ->join('college_classsched', 'college_ecr_term.ecrID', '=', 'college_classsched.ecr_template')
                    ->where('college_ecr_term.deleted', 0)
                    ->where('college_ecr.syid', $syid)
                    ->where('college_ecr.semid', $semid)
                    ->where('college_ecr.is_active', 1)
                    ->where('college_ecr.deleted', 0)
                    // ->where('college_classsched.id', $schedid)
                    ->select('college_termgrading.description', 'college_termgrading.quarter', 'college_ecr_term.ecrID')
                    ->groupBy('college_termgrading.id')
                    ->get();

        return $terms;
  }


}
 