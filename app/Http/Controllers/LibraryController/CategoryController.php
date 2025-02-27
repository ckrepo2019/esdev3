<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;

class CategoryController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return \DB::table('library_categories')
            ->where('deleted', 0)
            ->get();
    }

    public function store(Request $request)
    {
        $result = \DB::table('library_categories')->insertGetId([
            'category_name' => $request->cat_name,
            'category_reference' => $request->cat_reference,
        ]);

        if ($result) {
            return array(
                (object) [
                    'status' => 200,
                    'statusCode' => "success",
                    'message' => 'Added Successfully!',
                ]
            );
        } else {
            return array(
                (object) [
                    'status' => 400,
                    'statusCode' => "error",
                    'message' => 'Adding Failed!',
                ]
            );
        }
    }
    public function update(Request $request)
    {
        \DB::table('library_categories')
            ->where('id', $request->id)
            ->update([
                'category_name' => $request->cat_name,
                'category_reference' => $request->cat_reference,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Updated Successfully!',
            ]
        );
    }

    public function get_category(Request $request)
    {
        $cat = \DB::table('library_categories')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->first();

        return response()->json($cat);
    }

    public function destroy(Request $request)
    {
        \DB::table('library_categories')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Deleted Successfully!',
            ]
        );
    }
}
