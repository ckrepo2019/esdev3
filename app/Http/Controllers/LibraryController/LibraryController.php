<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;

class LibraryController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        return \DB::table('libraries')
            ->leftJoin('users', 'libraries.library_manager', '=', 'users.id')
            ->where('libraries.deleted', 0)
            ->select(
                'libraries.*',
                'users.name'
            )
            ->get();
    }

    public function store(Request $request)
    {
        $result = \DB::table('libraries')->insertGetId([
            'library_incharge' => $request->lib_incharge,
            'library_asst' => $request->lib_asst,
            'library_name' => $request->lib_name,
            'library_orgname' => $request->lib_orgname,
            'library_email' => $request->lib_email,
            'library_phone' => $request->lib_phone,
            'library_website' => $request->lib_website,
            'library_department' => $request->lib_department,
            'library_manager' => $request->lib_manager,
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
        \DB::table('libraries')
            ->where('id', $request->id)
            ->update([
                'library_incharge' => $request->lib_incharge,
                'library_asst' => $request->lib_asst,
                'library_name' => $request->lib_name,
                'library_orgname' => $request->lib_orgname,
                'library_email' => $request->lib_email,
                'library_phone' => $request->lib_phone,
                'library_website' => $request->lib_website,
                'library_department' => $request->lib_department,
                'library_manager' => $request->lib_manager,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Updated Successfully!',
            ]
        );
    }

    public function get_library(Request $request)
    {
        $lib = \DB::table('libraries')
            ->leftJoin('users', 'libraries.library_manager', '=', 'users.id')
            ->where('libraries.deleted', 0)
            ->where('libraries.id', $request->id)
            ->select(
                'libraries.*',
                'users.name'
            )
            ->first();

        return response()->json($lib);
    }


    public function destroy(Request $request)
    {
        \DB::table('libraries')
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
