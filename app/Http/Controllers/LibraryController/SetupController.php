<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;
use DB;

class SetupController extends \App\Http\Controllers\Controller
{
    public function setup(Request $request)
    {
        $data = [];
        if ($request->action === 'lib') {
            $data = DB::table('libraries')
                ->leftJoin('users', 'libraries.library_manager', '=', 'users.id')
                ->where('libraries.deleted', 0)
                ->select(
                    'libraries.*',
                    'users.name'
                )
                ->get();
        } else if ($request->action === 'cat') {
            $data = DB::table('library_categories')
                ->where('deleted', 0)
                ->get();
        } else if ($request->action === 'genre') {
            $data = DB::table('library_genres')
                ->where('deleted', 0)
                ->get();
        } else if ($request->action === 'borrower') {
            $data = DB::table('library_circulation')
                ->where('circulation_deleted', 0)
                ->select(
                    DB::raw('MAX(id) as max_id'),
                    DB::raw('MAX(circulation_book_id) AS max_book_id'),
                    DB::raw('MAX(circulation_name) AS max_circulation_name'),
                    DB::raw('MAX(circulation_utype) AS max_circulation_utype'),
                    DB::raw('MAX(circulation_members_id) AS max_circulation_members_id'),
                    DB::raw('MAX(circulation_penalty) AS max_circulation_penalty'),
                    DB::raw('MAX(circulation_due_date) AS max_circulation_due_date'),
                    DB::raw('MAX(circulation_date_borrowed) AS max_circulation_date_borrowed'),
                    DB::raw('MAX(circulation_date_returned) AS max_circulation_date_returned'),
                    DB::raw('MAX(circulation_status) AS max_circulation_status'),
                    DB::raw('MAX(circulation_deleted) AS max_circulation_deleted')
                )
                ->groupBy('circulation_name')
                ->get();

            foreach ($data as $item) {
                if ($item->max_circulation_utype == 'STUDENT') {
                    $info = DB::table('studinfo')
                        ->where('studinfo.id', $item->max_circulation_members_id)
                        ->leftJoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                        ->select(
                            'studinfo.*',
                            'gradelevel.levelname'
                        )
                        ->first();

                    $item->email = $info->sid;
                    $item->cardid = 'S' . $info->sid;
                    $item->class = $info->levelname;
                    $item->phone = $info->contactno;
                } else {
                    $info = DB::table('teacher')
                        ->where('teacher.id', $item->max_circulation_members_id)
                        ->select(
                            'teacher.*',
                            'employee_personalinfo.*'
                        )
                        ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                        ->first();

                    $userdetail = DB::table('users')->where('id', $info->userid)->first();

                    $item->email = $info->email;
                    $item->cardid = $userdetail->email;
                    $item->class = $item->max_circulation_utype;
                    $item->phone = $info->contactnum;
                }
            }
            // dd($data);



        } else if ($request->action === 'usertype') {
            $data = DB::table('library_usertype')
                ->where('deleted', 0)
                ->get();
        } else {
            $data = DB::table('users')
                ->join('library_usertype', 'users.usertype', '=', 'library_usertype.id')
                ->select(
                    'users.*',
                    'library_usertype.usertype'
                )
                ->get();
        }

        return view(
            'library.pages.setup',
            [
                'jsonData' => $data,
            ]
        );
    }

}
