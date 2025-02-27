<?php

namespace App\Http\Controllers\DeanControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\DeanControllers\DeanSectionController;

class GradingsetupController extends Controller
{
    public function termIndex(Request $request)
    {
        // Default terms to be inserted if they don't exist
        $defaultTerms = [
            ['description' => 'Prelim', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0],
            ['description' => 'Midterm', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0],
            ['description' => 'Prefinal', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0],
            ['description' => 'Final', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0],
        ];

        // Insert only if term does not exist
        // foreach ($defaultTerms as $term) {
        //     DB::table('college_termgrading')->where('description', $term['description'])->where('deleted', 0)->doesntExist() && 
        //         DB::table('college_termgrading')->insert($term);
        // }

        // Fetch all terms, including default ones
        $terms = DB::table('college_termgrading')
            ->where('deleted', 0)
            ->get();

        return response()->json($terms);
    }



public function addTerm(Request $request)
{
    $term = DB::table('college_termgrading')->insertGetId([
        'description' => $request->termName,
        'term_frequency' => $request->termFrequency,
        'grading_perc' => $request->gradingPercentage,
        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
    ]);

    return response()->json(['success' => 'Term added successfully!', 'term' => (object) [
        'id' => $term,
        'termName' => $request->termName,
        'termFrequency' => $request->termFrequency,
        'gradingPercentage' => $request->gradingPercentage,
    ]]);
}

public function termshow($termId)
{
    $term = DB::table('college_termgrading')
        ->where('id', $termId)
        ->where('deleted', 0)
        ->first();

    if ($term) {
        return response()->json($term);
    }

    return response()->json(['error' => 'Term not found'], 404);
}



public function editTerm(Request $request)
{
    $term = DB::table('college_termgrading')
        ->where('id', $request->id)
        ->where('college_termgrading.deleted', 0)
        ->first();

    return response()->json($term);
}

public function updateTerm(Request $request)
{
    // $defaultTerms = [ 
    //     ['description' => 'Prelim', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0],
    //     ['description' => 'Midterm', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0],
    //     ['description' => 'Prefinal', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0],
    //     ['description' => 'Final', 'term_frequency' => 1, 'grading_perc' => 25, 'deleted' => 0],
    // ];

    // foreach ($defaultTerms as $term) {
    //     DB::table('college_termgrading')
    //         ->where('description', $term['description'])
    //         ->where('deleted', 0)
    //         ->update([
    //             'term_frequency' => $term['term_frequency'],
    //             'grading_perc' => $term['grading_perc'],
    //             'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //         ]);
    // }

    return DB::table('college_termgrading')
        ->where('id', $request->id)
        ->update([
            'description' => $request->termName,
            'term_frequency' => $request->termFrequency,
            'grading_perc' => $request->gradingPercentage,
            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
        ]);
}

public function deleteTerm(Request $request)
{
    return DB::table('college_termgrading')
        ->where('id', $request->id)
        ->update([
            'deleted' => 1,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            'deletedby' => auth()->user()->id
        ]);
}
            
    public function ECRDisplay()
    {
        return DB::table('college_component_gradesetup')
            ->get();
    }
    
    public function gradingComponentEdit($id)
    {
        return DB::table('college_component_gradesetup')
            ->where('id', $id)
            ->first();
    }
    
    public function deleteECRComponent(Request $request, $id)
    {
        DB::table('college_component_gradesetup')
            ->where('ecrID', $id)
            ->update(['deleted' => 1]);

        return response()->json(['success' => true, 'message' => 'Component deleted successfully.']);
    }
    
    public function addECRGrading(Request $request)
    {
        $ECRgradingadd = DB::table('college_ecr')->insertGetId([
            'syid' => $request->syid,
            'semid' => $request->semid,
            'ecrDesc' => $request->ecrDescription,
            'is_active' => $request->setActive,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);

        $components = [];
        if (!empty($request->components)) {
            foreach ($request->components as $component) {
                $componentID = DB::table('college_component_gradesetup')
                    ->insertGetId([
                        'ecrID' => $ECRgradingadd,
                        'descriptionComp' => $component['description'],
                        'component' => $component['percentage'], 
                        'column_ECR' => $component['columns'],
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                if (!empty($component['subcomponents']) && is_array($component['subcomponents'])) {
                    foreach ($component['subcomponents'] as $subComponent) {
                        DB::table('college_subgradingcomponent')
                            ->insert([
                                'componentID' => $componentID,
                                'subDescComponent' => $subComponent['description'] ?? '',
                                'subComponent' => $subComponent['percentage'] ?? 0,
                                'subColumnECR' => $subComponent['columns'] ?? 0,
                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }
                }
                $components[] = $component;
            }
        }

        if (!empty($request->termID) && is_array($request->termID)) {
            foreach ($request->termID as $termID) {
                DB::table('college_ecr_term')
                    ->insert([
                        'ecrID' => $ECRgradingadd,
                        'termID' => (int) $termID,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ecrgrading' => $ECRgradingadd,
                'components' => $components
            ]
        ]);
    }

    public function gradingSetupDisplay(Request $request)
    {
        $semid = $request->input('semester');
        $sy = $request->input('sy');

        $ecrsQuery = DB::table('college_ecr')
            ->join('semester', 'college_ecr.semid', '=', 'semester.id')
            ->where('college_ecr.deleted', 0)
            ->when($sy, function ($query) use($sy) {
                $query->where('college_ecr.syid', $sy);
            })
            ->when($sy, function ($query) use($semid) {
                $query->where('college_ecr.semid', $semid);
            })
            ->select('college_ecr.id as ecrID', 'college_ecr.ecrDesc', 'semester.semester as semesterName', 'college_ecr.is_active');

       

        $ecrs = $ecrsQuery->get();

        $terms = DB::table('college_ecr_term')
            ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
            ->where('college_ecr_term.deleted', 0)
            ->select(
                'college_ecr_term.id as termID',
                'college_ecr_term.ecrID',
                'college_termgrading.grading_perc',
                'college_termgrading.description'
            )
            ->get();

        $components = DB::table('college_component_gradesetup')
            ->where('deleted', 0)
            ->get();

        $subComponents = DB::table('college_subgradingcomponent')
            ->where('deleted', 0)
            ->get();

        // return $subcomponents;

        $groupedTerms = $ecrs->map(function ($ecr) use ($terms, $components, $subComponents) {
            $filteredTerms = $terms->where('ecrID', $ecr->ecrID);

            $filteredComponents = $components->where('ecrID', $ecr->ecrID);

            $filteredSubComponents = $subComponents->groupBy('componentID');

            return [
                'ecrID' => $ecr->ecrID,
                'ecrDesc' => $ecr->ecrDesc,
                'semesterName' => $ecr->semesterName,
                'is_active' => $ecr->is_active,
                'terms' => $filteredTerms->map(function ($term) {
                    return [
                        'termID' => $term->termID,
                        'termDesc' => $term->description,
                        'gradingPerc' => $term->grading_perc,
                    ];
                })->values(),
                'components' => $filteredComponents->map(function ($component) use ($filteredSubComponents) {
                    return [
                        'id' => $component->id,
                        'descriptionComp' => $component->descriptionComp,
                        'component' => $component->component,
                        'subComponents' => $filteredSubComponents->get($component->id, [])
                    ];
                })->values()
            ];
        })->values();

        return response()->json($groupedTerms);
    }


    public function gradingDisplayDelete(Request $request)
    {
        $ecrID = $request->id;

        DB::table('college_ecr')
            ->where('id', $ecrID)
            ->where('deleted', 0)
            ->update([
                'deleted' => 1,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                'deletedby' => auth()->user()->id
            ]);

        return response()->json(['success' => true, 'id' => $ecrID]);
    }

    public function editECR(Request $request)
    {
        $ecr = DB::table('college_ecr')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->first();

        $components = DB::table('college_component_gradesetup')
            ->where('ecrID', $request->id)
            ->where('deleted', 0)
            ->get();

        $subcomponents = DB::table('college_subgradingcomponent')
            ->whereIn('componentID', $components->pluck('id'))
            ->where('deleted', 0)
            ->get()
            ->groupBy('componentID');

        $terms = DB::table('college_termgrading')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->get();

        $term = DB::table('college_ecr_term')
            ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
            ->join('college_ecr', 'college_ecr_term.ecrID', '=', 'college_ecr.id')
            ->where('college_ecr_term.ecrID', $request->id)
            ->where('college_ecr_term.deleted', 0)
            ->where('college_termgrading.deleted', 0)
            ->select('college_termgrading.description', 'college_ecr_term.ecrID', 'college_termgrading.id')
            ->get();

        $componentsWithSubcomponents = $components->map(function ($component) use ($subcomponents) {
            return [
                'id' => $component->id,
                'descriptionComp' => $component->descriptionComp,
                'component' => $component->component,
                'column_ECR' => $component->column_ECR,
                'subComponents' => $subcomponents->get($component->id, [])
            ];
        });

        return response()->json([
            'ecr' => $ecr,
            'components' => $componentsWithSubcomponents,
            'terms' => $terms,
            'term' => $term
        ]);
    }


    public function updateECR(Request $request, $id)
    {
        $ecrID = $id;
        $components = $request->components;

        // Update the main ECR record
        DB::table('college_ecr')
            ->where('id', $ecrID)
            ->update([
                'ecrDesc' => $request->ecrDesc,
                'is_active' => $request->get('is_active', 0),
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            ]);

        // Update terms in `college_ecr_term`
        DB::table('college_ecr_term')
            ->where('ecrID', $ecrID)
            ->update(['deleted' => 1]);

        if (is_array($request->terms)) {
            foreach ($request->terms as $termID) {
                DB::table('college_ecr_term')
                    ->updateOrInsert(
                        ['ecrID' => $ecrID, 
                        'termID' => $termID],
                        [
                            'deleted' => 0,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        ]
                    );
            }
        }

        // Process components and subcomponents
        foreach ($components as $component) {
            $componentID = $component['id'] ?? null;
            $existingComponent = DB::table('college_component_gradesetup')
                ->where('id', $componentID)
                ->first();
    
            if ($existingComponent) {
                DB::table('college_component_gradesetup')
                    ->where('id', $componentID)
                    ->update([
                        'descriptionComp' => $component['descriptionComp'],
                        'component' => $component['component'],
                        'column_ECR' => $component['column_ECR'],
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    ]);
            } else {
                $componentID = DB::table('college_component_gradesetup')
                    ->insertGetId([
                        'ecrID' => $ecrID,
                        'descriptionComp' => $component['descriptionComp'],
                        'component' => $component['component'],
                        'column_ECR' => $component['column_ECR'],
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    ]);
            }
    
            if (!empty($component['subComponents'])) {
                foreach ($component['subComponents'] as $sub) {
                    $subComponentID = $sub['id'] ?? null; // Check if subComponentID exists
                    
                    if ($subComponentID) {
                        // Update existing subgrading component
                        DB::table('college_subgradingcomponent')
                            ->where('id', $subComponentID)
                            ->update([
                                'componentID' => $componentID,
                                'SubDescComponent' => $sub['SubDescComponent'],
                                'subComponent' => $sub['subComponent'],
                                'subColumnECR' => $sub['subColumnECR'],
                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                            ]);
                    } else {
                        // Insert new subgrading component
                        DB::table('college_subgradingcomponent')
                            ->insert([
                                'componentID' => $componentID,
                                'SubDescComponent' => $sub['SubDescComponent'],
                                'subComponent' => $sub['subComponent'],
                                'subColumnECR' => $sub['subColumnECR'],
                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                            ]);
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'ECR updated successfully',
        ]);
    }

    public function displayEditGradingComponent(Request $request) {
        $component = DB::table('college_component_gradesetup')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->first();

        $subcomponents = DB::table('college_subgradingcomponent')
            ->where('componentID', $request->id)
            ->where('deleted', 0)
            ->get();

        return response()->json([
            'component' => $component,
            'subcomponents' => $subcomponents,
        ]);
    }

    public static function updateECRComponent(Request $request)
{
    $ecrID = $request->id;
    $components = $request->components ?? []; // Ensure it's always an array

    foreach ($components as $component) {
        $componentID = $component['id'] ?? null;

        if ($componentID) {
            // Update existing component
            DB::table('college_component_gradesetup')
                ->where('id', $componentID)
                ->update([
                    'descriptionComp' => $component['descriptionComp'],
                    'component' => $component['component'],
                    'column_ECR' => $component['column_ECR'],
                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        } else {
            // Insert new component
            $componentID = DB::table('college_component_gradesetup')
                ->insertGetId([
                    'ecrID' => $ecrID,
                    'descriptionComp' => $component['descriptionComp'],
                    'component' => $component['component'],
                    'column_ECR' => $component['column_ECR'],
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        }

        // Handle subcomponents
        $submittedSubComponents = $component['subComponents'] ?? []; // Ensure it's always an array

        foreach ($submittedSubComponents as $sub) {
            $subComponentID = $sub['id'] ?? null; 

            if ($subComponentID) {
                // Update subcomponent
                DB::table('college_subgradingcomponent')
                    ->where('id', $subComponentID)
                    ->update([
                        'componentID' => $componentID,
                        'SubDescComponent' => $sub['SubDescComponent'],
                        'subComponent' => $sub['subComponent'],
                        'subColumnECR' => $sub['subColumnECR'],
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    ]);
            } else {
                // Insert new subcomponent
                DB::table('college_subgradingcomponent')
                    ->insert([
                        'componentID' => $componentID,
                        'SubDescComponent' => $sub['SubDescComponent'],
                        'subComponent' => $sub['subComponent'],
                        'subColumnECR' => $sub['subColumnECR'],
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    ]);
            }
        }
    }

    // Fetch updated components to return
    $updatedComponents = DB::table('college_component_gradesetup')
        ->where('ecrID', $ecrID)
        ->get();

    return response()->json([
        'message' => 'ECR component updated successfully.',
        'components' => $updatedComponents
    ]);
}


    public function deleteSubgradingComponents(Request $request) 
    {
        $componentID = $request->input('id');

            DB::table('college_component_gradesetup')
                ->where('id', $componentID)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    'deletedby' => auth()->id(),
                ]);

            DB::table('college_subgradingcomponent')
                ->where('componentID', $componentID)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    'deletedby' => auth()->id(),
                ]);

        return response()->json(['success' => true, 'message' => 'Component and associated subgrading components deleted successfully.']);
    }

    public function removeSubgradingFromComponents(Request $request) 
    {
        $SubcomponentID = $request->input('id');

            DB::table('college_subgradingcomponent')
                ->where('id', $SubcomponentID)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    'deletedby' => auth()->id(),
                ]);

        return response()->json(['success' => true, 'message' => 'Component and associated subgrading components deleted successfully.']);
    }

    public static function displayEcr(Request $request)
    {
        // Fetch the main ECR details
        $ecr = DB::table('college_ecr')
            ->where('id', $request->id)
            ->first();

        if (!$ecr) {
            return response()->json(['error' => 'ECR not found'], 404);
        }

        // Fetch students enrolled in the ECR
        $students = DB::table('college_enrolledstud')
            ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
            ->select(
                'studinfo.id as studentID',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename'
            )
            ->get();

        // Fetch components with their subcomponents
        $components = DB::table('college_component_gradesetup')
            ->where('ecrID', $request->id)
            ->where('deleted', 0)
            ->get()
            ->map(function ($component) {
                $component->subComponents = DB::table('college_subgradingcomponent')
                    ->where('componentID', $component->id)
                    ->where('deleted', 0)
                    ->get();
                return $component;
            });

        // Fetch terms and their grading percentages
        $terms = DB::table('college_ecr_term')
            ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
            ->where('college_ecr_term.ecrID', $request->id)
            ->where('college_ecr_term.deleted', 0)
            ->select(
                'college_termgrading.description',
                'college_termgrading.grading_perc'
            )
            ->get();

        $studentScores = [
            1 => [
                11 => ['score' => 85],
            ],
            2 => [
                11 => ['score' => 90],
            ],
        ];

        $studentTotals = [];
        $studentAverages = [];

        foreach ($studentScores as $studentID => $scores) {
            foreach ($scores as $componentID => $data) {
                $studentTotals[$studentID][$componentID] = $data['score'];
                $studentAverages[$studentID][$componentID] = $data['score'];
            }
        }
        
        return view('deanportal.pages.grades.printECR', [
            'ecr' => $ecr,
            'components' => $components,
            'terms' => $terms,
            'students' => $students,
            'studentScores' => $studentScores,
            'studentTotals' => $studentTotals,
            'studentAverages' => $studentAverages,
        ]);
    }

    // public function filter_semester(Request $request)
    // {
    //     $semid = $request->input('semester');

    //     if ($semid) {
    //         // If semester is selected, filter by the semester ID
    //         $filteredData = DB::table('college_ecr')
    //             ->join('semester', 'college_ecr.semid', '=', 'semester.id')
    //             ->select(
    //                 'college_ecr.id as ecrID',
    //                 'college_ecr.ecrDesc',
    //                 'semester.semester as semesterName'
    //             )
    //             ->where('college_ecr.semid', $semid)
    //             ->get();
    //     } else {
    //         $filteredData = DB::table('college_ecr')
    //             ->join('semester', 'college_ecr.semid', '=', 'semester.id')
    //             ->select(
    //                 'college_ecr.id as ecrID',
    //                 'college_ecr.ecrDesc',
    //                 'semester.semester as semesterName'
    //             )
    //             ->get();
    //     }

    //     return response()->json($filteredData);
    // }

    // public function filter_sy(Request $request)
    // {
    //     $syid = $request->input('sy');

    //     if ($syid) {
    //         $filteredData = DB::table('college_ecr')
    //             ->join('sy', 'college_ecr.syid', '=', 'sy.id')
    //             ->select(
    //                 'college_ecr.id as ecrID',
    //                 'college_ecr.ecrDesc',
    //                 'sy.sydesc'
    //             )
    //             ->where('college_ecr.syid', $syid)
    //             ->get();
    //     } else {
    //         $filteredData = DB::table('college_ecr')
    //             ->join('sy', 'college_ecr.syid', '=', 'sy.id')
    //             ->select(
    //                 'college_ecr.id as ecrID',
    //                 'college_ecr.ecrDesc',
    //                 'sy.sydesc'
    //             )
    //             ->get();
    //     }

    //     return response()->json($filteredData);
    // }

    // public function filter_sy(Request $request)
    // {
    //     $syid = $request->input('sy');

    //     if (!$syid) {
    //         return response()->json(['message' => 'School Year ID is required'], 400);
    //     }

    //     $filteredData = DB::table('college_ecr')
    //         ->join('sy', 'college_ecr.syid', '=', 'sy.id')
    //         ->select(
    //             'college_ecr.id as ecrID',
    //             'college_ecr.ecrDesc',
    //             'sy.sydesc'
    //         )
    //         ->where('college_ecr.syid', $syid)
    //         ->get();

    //     return response()->json($filteredData);
    // }
}