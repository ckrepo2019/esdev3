<?php

namespace App\Http\Controllers\PrincipalControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class HomeroomConductController extends Controller
{
    public function create_conduct(Request $request){
        $sort = $request->get('sort');
        $conduct_name = $request->get('conduct_name');
        $conduct_description = $request->get('conduct_description');
        $conduct_ispercentage = $request->get('conduct_ispercentage');
        $conduct_percentage = $request->get('conduct_percentage');

        DB::table('conduct')->insert([
            'sortid' => $sort,
            'conductname' => $conduct_name,
            'description' => $conduct_description,
            'ispercentage' => $conduct_ispercentage,
            'percentage' => $conduct_percentage,
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function get_conducts(){
        $conducts = DB::table('conduct')
        ->where('deleted', 0)
        ->orderBy('sortid', 'asc')
        ->get();
        return $conducts;
    }

    public function get_conduct(Request $request){
        $id = $request->get('id');
        $conducts = DB::table('conduct')
        ->where('id', $id)
        ->select('sortid', 'conductname', 'description', 'ispercentage', 'percentage')
        ->first();
        return response()->json($conducts);
    }

    public function edit_conduct(Request $request){
        $id = $request->get('id');
        $sort = $request->get('sort');
        $conduct_name = $request->get('conduct_name');
        $conduct_description = $request->get('conduct_description');
        $conduct_ispercentage = $request->get('conduct_ispercentage');
        $conduct_percentage = $request->get('conduct_percentage');

        DB::table('conduct')
        ->where('id', $id)
        ->update([
            'sortid' => $sort,
            'conductname' => $conduct_name,
            'description' => $conduct_description,
            'ispercentage' => $conduct_ispercentage,
            'percentage' => $conduct_percentage,
            'updatedby' => auth()->user()->id,
            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function delete_conduct(Request $request){
        $id = $request->get('id');
        DB::table('conduct')
        ->where('id', $id)
        ->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function create_conduct_grade(Request $request){
        $description = $request->get('description');
        $nonnumeric = $request->get('nonnumeric');
        $numeric = $request->get('numeric');
        $remarks = $request->get('remarks');
        $sortid = $request->get('sortid');

        DB::table('conduct_grade')->insert([
            'description' => $description,
            'nonnumeric' => $nonnumeric,
            'numeric' => $numeric,
            'remarks' => $remarks,
            'sortid' => $sortid,
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function get_conduct_grades(){
        $grades = DB::table('conduct_grade')
        ->where('deleted', 0)
        ->orderBy('sortid', 'asc')
        ->get();
        return $grades;
    }

    public function get_conduct_grade(Request $request){
        $id = $request->get('id');
        $grades = DB::table('conduct_grade')
        ->where('id', $id)
        ->select('description', 'nonnumeric', 'numeric', 'remarks', 'sortid')
        ->first();
        return response()->json($grades);
    }

    public function edit_conduct_grade(Request $request){
        $id = $request->get('id');
        $description = $request->get('description');
        $nonnumeric = $request->get('nonnumeric');
        $numeric = $request->get('numeric');
        $remarks = $request->get('remarks');
        $sortid = $request->get('sortid');

        DB::table('conduct_grade')
        ->where('id', $id)
        ->update([
            'description' => $description,
            'nonnumeric' => $nonnumeric,
            'numeric' => $numeric,
            'remarks' => $remarks,
            'sortid' => $sortid,
            'updatedby' => auth()->user()->id,
            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function delete_conduct_grade(Request $request){
        $id = $request->get('id');
        DB::table('conduct_grade')
        ->where('id', $id)
        ->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function create_traits(Request $request){
        $traits = $request->get('traits');
        $description = $request->get('description');
        $trait_ispercentage = $request->get('trait_ispercentage');
        $trait_percentage = $request->get('trait_percentage');
        $sortid = $request->get('sortid');

        DB::table('behavioral_traits')->insert([
            'traits' => $traits,
            'description' => $description,
            'ispercentage' => $trait_ispercentage,
            'percentage' => $trait_percentage,
            'sortid' => $sortid,
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function get_traits(){
        $traits = DB::table('behavioral_traits')
        ->where('deleted', 0)
        ->orderBy('sortid', 'asc')
        ->get();
        return $traits;
    }

    public function get_trait(Request $request){
        $id = $request->get('id');
        $traits = DB::table('behavioral_traits')
        ->where('id', $id)
        ->select('traits', 'description', 'ispercentage', 'percentage', 'sortid')
        ->first();
        return response()->json($traits);
    }

    public function edit_trait(Request $request){
        $id = $request->get('id');
        $traits = $request->get('traits');
        $description = $request->get('description');
        $trait_ispercentage = $request->get('trait_ispercentage');
        $trait_percentage = $request->get('trait_percentage');
        $sortid = $request->get('sortid');

        DB::table('behavioral_traits')
        ->where('id', $id)
        ->update([
            'traits' => $traits,
            'description' => $description,
            'ispercentage' => $trait_ispercentage,
            'percentage' => $trait_percentage,
            'sortid' => $sortid,
            'updatedby' => auth()->user()->id,
            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }


    public function delete_trait(Request $request){
        $id = $request->get('id');
        DB::table('behavioral_traits')
        ->where('id', $id)
        ->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function create_trait_grade(Request $request){
        $description = $request->get('description');
        $nonnumeric = $request->get('nonnumeric');
        $numeric = $request->get('numeric');
        $remarks = $request->get('remarks');
        $sortid = $request->get('sortid');

        DB::table('behavioral_grades')->insert([ 
            'description' => $description,
            'nonnumeric' => $nonnumeric,
            'numeric' => $numeric,
            'remarks' => $remarks,
            'sortid' => $sortid,
            'createdby' => auth()->user()->id,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function get_trait_grades(){
        $grades = DB::table('behavioral_grades')
        ->where('deleted', 0)
        ->orderBy('sortid', 'asc')
        ->get();
        return $grades;
    }

    public function get_trait_grade(Request $request){
        $id = $request->get('id');
        $grades = DB::table('behavioral_grades')
        ->where('id', $id)
        ->select('description', 'nonnumeric', 'numeric', 'remarks', 'sortid')
        ->first();
        return response()->json($grades);
    }

    public function edit_trait_grade(Request $request){
        $id = $request->get('id');
        $description = $request->get('description');
        $nonnumeric = $request->get('nonnumeric');
        $numeric = $request->get('numeric');
        $remarks = $request->get('remarks');
        $sortid = $request->get('sortid');

        DB::table('behavioral_grades')
        ->where('id', $id)
        ->update([
            'description' => $description,
            'nonnumeric' => $nonnumeric,
            'numeric' => $numeric,
            'remarks' => $remarks,
            'sortid' => $sortid,
            'updatedby' => auth()->user()->id,
            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function delete_trait_grade(Request $request){
        $id = $request->get('id');
        DB::table('behavioral_grades')
        ->where('id', $id)
        ->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
    }
}
