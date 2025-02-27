<?php

namespace App\Http\Controllers\TesdaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class TesdaGradingSetupController extends Controller
{
    public function tesda_gradeStatus(Request $request)
    {
        $gradingSetup = DB::table('tesda_grading_setup')
            ->insertGetId([
                'gradDesc' => $request->gradingDescription,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        if ($request->has('gradingSetupList')) {
            foreach ($request->gradingSetupList as $gradingData) {
                $mainDescription = $gradingData['mainDescription'];
                $componentPercentage = $gradingData['componentPercentage'];
                $numColumns = $gradingData['numColumns'];

                // Insert component
                $componentId = DB::table('tesda_ecr_component_gradesetup')->insertGetId([
                    'gradID' => $gradingSetup,
                    'descriptionComp' => $mainDescription,
                    'component' => $componentPercentage,
                    'column_ECR' => $numColumns
                ]);

                if (isset($gradingData['subComponents']) && !empty($gradingData['subComponents'])) {
                    foreach ($gradingData['subComponents'] as $subComponent) {
                        DB::table('tesda_subgrading')->insert([
                            'ecrID' => $componentId,
                            'subDescComponent' => $subComponent['subgradingDescription'],
                            'subComponent' => $subComponent['subgradingcomponent'],
                            'subColumnECR' => $subComponent['subcolumnNumber']
                        ]);
                    }
                }
            }
        }
        return response()->json(['message' => 'Grading setup saved successfully.'], 200);
    }      


    public function displayGradingSetup()
    {
        $gradingSetups = DB::table('tesda_grading_setup')
            ->select('id', 'gradDesc')
            ->where('deleted', 0)
            ->get();

        $data = $gradingSetups->map(function ($gradingSetup) {
            $components = DB::table('tesda_ecr_component_gradesetup')
                ->where('gradID', $gradingSetup->id)
                ->where('deleted', 0)
                ->get();

            $components = $components->map(function ($component) {
                $subGrading = DB::table('tesda_subgrading')
                    ->where('ecrID', $component->id)
                    ->where('deleted', 0)
                    ->get();

                $component->subGrading = $subGrading;
                return $component;
            });

            $gradingSetup->components = $components;
            return $gradingSetup;
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function deleteGradingSetup(Request $request)
    {
        $grading = $request->get('id');

        DB::table('tesda_grading_setup')
        ->where('id', $grading)
        ->update([
            'deleted' => 1,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            'deletedby' => auth()->user()->id
        ]);

        DB::table('tesda_ecr_component_gradesetup')
        ->where('gradID', $grading)
        ->update([
            'deleted' => 1,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            'deletedby' => auth()->user()->id,
        ]);

        DB::table('tesda_subgrading')
        ->whereIn('ecrID', function ($query) use ($grading) {
            $query->select('id')
                ->from('tesda_ecr_component_gradesetup')
                ->where('gradID', $grading);
        })
        ->update([
            'deleted' => 1,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            'deletedby' => auth()->user()->id,
        ]);
    }

    public function getGradingDetails($id)
    {
        $gradingSetup = DB::table('tesda_grading_setup')
                        ->where('id', $id)
                        ->where('deleted', 0)
                        ->select('id', 'gradDesc')
                        ->first();
                        
        $components = DB::table('tesda_ecr_component_gradesetup')
                        ->where('gradID', $gradingSetup->id)
                        ->where('deleted', 0)
                        ->select('id', 'gradID', 'descriptionComp', 'component', 'column_ECR')
                        ->get();

        $gradingData = [];

        foreach ($components as $component) {
            $componentData = [
                'id' => $component->id,
                'mainDescription' => $component->descriptionComp,
                'component' => $component->component,
                'numColumns' => $component->column_ECR,
                'subComponents' => []
            ];

            $subComponents = DB::table('tesda_subgrading')
                            ->where('ecrID', $component->id)
                            ->where('deleted', 0)
                            ->select('id', 'ecrID', 'subDescComponent', 'subComponent', 'subColumnECR')
                            ->get();

            foreach ($subComponents as $subComponent) {
                $componentData['subComponents'][] = [
                    'id' => $subComponent->id,
                    'subgradingDescription' => $subComponent->subDescComponent,
                    'subgradingcomponent' => $subComponent->subComponent,
                    'subcolumnNumber' => $subComponent->subColumnECR
                ];
            }

            $gradingData[] = $componentData;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' =>  $gradingSetup->id,
                'gradDesc' => $gradingSetup->gradDesc,
                'components' => $gradingData,
            ]
        ]);
    }

    public function updateGradingDetails(Request $request, $id)
    {

        if ($request->input('components')) {
            foreach ($request->input('components') as $component) {
                $componentId = $component['id'] ?? null;
                if ($componentId) {
                    DB::table('tesda_ecr_component_gradesetup')
                        ->where('id', $componentId)
                        ->update([
                            'descriptionComp' => $component['mainComponentDescription'],
                            'component' => $component['componentPercentage'],
                            'column_ECR' => $component['numColumns'],
                        ]);
                } else {
                    $componentId = DB::table('tesda_ecr_component_gradesetup')->insertGetId([
                        'gradID' => $id,
                        'descriptionComp' => $component['mainComponentDescription'],
                        'component' => $component['componentPercentage'],
                        'column_ECR' => $component['numColumns'],
                    ]);
                }
                if (!empty($component['subComponents'])) {
                    foreach ($component['subComponents'] as $subComponent) {
                        $subComponentId = $subComponent['id'] ?? null;
                        if ($subComponentId) {
                            DB::table('tesda_subgrading')
                                ->where('id', $subComponentId)
                                ->update([
                                    'subDescComponent' => $subComponent['subDescription'],
                                    'subComponent' => $subComponent['subPercentage'],
                                    'subColumnECR' => $subComponent['subColumnNumber'],
                                ]);
                        } else {
                            DB::table('tesda_subgrading')->insert([
                                'ecrID' => $componentId,
                                'subDescComponent' => $subComponent['subDescription'],
                                'subComponent' => $subComponent['subPercentage'],
                                'subColumnECR' => $subComponent['subColumnNumber'],
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Grading setup updated successfully.'
        ]);
    }

    public function updateGradingDesc(Request $request, $id)
    {
        DB::table('tesda_grading_setup')
            ->where('id', $id)
            ->update([
                'gradDesc' => $request->gradingDescription,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        // Check if there are grading components
        if ($request->has('gradingSetupList')) {
            foreach ($request->gradingSetupList as $gradingData) {
                $mainDescription = $gradingData['mainDescription'];
                $componentPercentage = $gradingData['componentPercentage'];
                $numColumns = $gradingData['numColumns'];

                // Check if component already exists
                $existingComponent = DB::table('tesda_ecr_component_gradesetup')
                    ->where('gradID', $id)
                    ->where('descriptionComp', $mainDescription)
                    ->first();

                if ($existingComponent) {
                    // Update existing component
                    DB::table('tesda_ecr_component_gradesetup')
                        ->where('id', $existingComponent->id)
                        ->update([
                            'component' => $componentPercentage,
                            'column_ECR' => $numColumns
                        ]);

                    $componentId = $existingComponent->id;
                } else {
                    // Insert new component
                    $componentId = DB::table('tesda_ecr_component_gradesetup')->insertGetId([
                        'gradID' => $id,
                        'descriptionComp' => $mainDescription,
                        'component' => $componentPercentage,
                        'column_ECR' => $numColumns
                    ]);
                }

                // Handle subcomponents
                if (isset($gradingData['subComponents']) && !empty($gradingData['subComponents'])) {
                    foreach ($gradingData['subComponents'] as $subComponent) {
                        // Check if subcomponent exists
                        $existingSub = DB::table('tesda_subgrading')
                            ->where('ecrID', $componentId)
                            ->where('subDescComponent', $subComponent['subgradingDescription'])
                            ->first();

                        if ($existingSub) {
                            // Update existing subcomponent
                            DB::table('tesda_subgrading')
                                ->where('id', $existingSub->id)
                                ->update([
                                    'subComponent' => $subComponent['subgradingcomponent'],
                                    'subColumnECR' => $subComponent['subcolumnNumber']
                                ]);
                        } else {
                            // Insert new subcomponent
                            DB::table('tesda_subgrading')->insert([
                                'ecrID' => $componentId,
                                'subDescComponent' => $subComponent['subgradingDescription'],
                                'subComponent' => $subComponent['subgradingcomponent'],
                                'subColumnECR' => $subComponent['subcolumnNumber']
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Grading description updated successfully.',
        ]);
    }


    public function deleteGradingSetupRow(Request $request, $id)
    {
        DB::table('tesda_ecr_component_gradesetup')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                'deletedby' => auth()->user()->id
            ]);

        DB::table('tesda_subgrading')
            ->where('ecrID', $id)
            ->update([
                'deleted' => 1,
                'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                'deletedby' => auth()->user()->id
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Grading setup deleted successfully.',
        ]);
    }

    public function deleteAddedComponent(Request $request, $subId)
    {
        try {
            DB::table('tesda_subgrading')
                ->where('id', $subId)
                ->update(['deleted' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Subgrading component deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subgrading component.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}