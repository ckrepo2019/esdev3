<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class GradeTypeSetupController extends \App\Http\Controllers\Controller
{
    public static function gradetype_list(Request $request){
        $gradetype_list = DB::table('gradetype_setup')
                            ->where('deleted',0)
                            ->orderBy('gradetype_desc')
                            ->get();

        return $gradetype_list;
    }

    public function gradetype_activate(Request $request){
        $gradetype_id = $request->get('id');

        DB::table('gradetype_setup')
            ->where('id', $gradetype_id)
            ->update([
                'isactive' =>  1
            ]);

        DB::table('gradetype_setup')
            ->where('id', '!=', $gradetype_id)
            ->update([
                'isactive' =>  0
            ]);



        return array([
            'status' => 1,
            'message' => 'Activated Successfully!'
        ]);
    }
}
