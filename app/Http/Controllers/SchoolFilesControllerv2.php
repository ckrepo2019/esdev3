<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Session;
class SchoolFilesControllerv2 extends Controller
{

    public function getUsertypes(Request $request)
    {
        $search = $request->get('search');


        $usertypes = DB::table('usertype')
            ->when($search != '', function ($query) use ($search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('usertype.utype', 'like', '%' . $search . '%');
                });
            })
            ->where('deleted', 0)
            ->get();

        foreach ($usertypes as $usertype) {
            $checkifexist = DB::table('school_folder_setup')->where('usertypeid', $usertype->id)->where('deleted', 0)->get();
            if (count($checkifexist) == 0) {
                $usertype->isChecked = 0;
            } else {
                $usertype->isChecked = 1;
            }
        }

        return $usertypes;
    }

    public function setasCreator(Request $request)
    {
        $ischecked = $request->input('isChecked');
        $checkifexist = DB::table('school_folder_setup')->where('usertypeid', $request->input('id'))->get();

        if (count($checkifexist) == 0) {


            DB::table('school_folder_setup')->insert([
                'usertypeid' => $request->input('id'),
                'createddatetime' => date('Y-m-d H:i:s')
            ]);

        } else {

            if ($ischecked == 'true') {
                DB::table('school_folder_setup')
                    ->where('usertypeid', $request->input('id'))
                    ->update([
                        'deleted' => 0,
                    ]);

                return 'retrieved';

            } else {


                DB::table('school_folder_setup')
                    ->where('usertypeid', $request->input('id'))
                    ->update([
                        'deleted' => 1,
                    ]);
                return 'deleted';


            }

        }


    }
}