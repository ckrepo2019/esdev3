<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;
use DB;

class BorrowerController extends \App\Http\Controllers\Controller
{

    public function index()
    {

        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.userid',
                'teacher.title',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'employee_personalinfo.gender',
                'employee_personalinfo.email',
                'usertype.utype',
                'teacher.usertypeid',
                DB::raw('teacher.phonenumber AS phone'),
                DB::raw('usertype.utype AS class')
            )
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->where('teacher.deleted', '0')
            ->where('teacher.userid', '>', 0)
            ->get();


        // return $employees;
        $students = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.userid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.sid',
                DB::raw('studinfo.contactno AS phone'),
                DB::raw('studinfo.semail AS email'),
                'lrn',
                DB::raw('gradelevel.levelname AS class')
            )
            ->where('studinfo.deleted', '0')
            ->where('studinfo.userid', '>', 0)
            ->whereIn('studinfo.studstatus', [1, 2, 4])
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->get();

        if (count($students) > 0) {
            foreach ($students as $student) {
                $student->title = null;
                $student->utype = 'STUDENT';
                $student->usertypeid = 7;
            }
        }

        $allusers = collect();
        $allusers = $allusers->merge($employees);
        $allusers = $allusers->merge($students);



        if (count($allusers) > 0) {
            foreach ($allusers as $alluser) {
                if ($alluser->usertypeid != 7) {
                    $alluser->lrn = "";
                }
                $name_showfirst = "";

                if ($alluser->title != null) {
                    $name_showfirst .= $alluser->title . ' ';
                }
                $name_showfirst .= $alluser->firstname . ' ';

                if ($alluser->middlename != null) {
                    $name_showfirst .= $alluser->middlename[0] . '. ';
                }
                $name_showfirst .= $alluser->lastname . ' ';
                $name_showfirst .= $alluser->suffix . ' ';

                $alluser->name_showfirst = $name_showfirst;

                $name_showlast = "";

                if ($alluser->title != null) {
                    $name_showlast .= $alluser->title . ' ';
                }
                $name_showlast .= $alluser->lastname . ', ';
                $name_showlast .= $alluser->firstname . ' ';

                if ($alluser->middlename != null) {
                    $name_showlast .= $alluser->middlename[0] . '. ';
                }
                $name_showlast .= $alluser->suffix . ' ';

                $alluser->name_showlast = $name_showlast;
                $alluser->text = $name_showlast;
            }
        }
        return $allusers->sortBy('id')->values()->all();
    }

    public function store(Request $request)
    {
        $userExist = DB::table('library_borrowers')->where('borrower_username', $request->borrower_username)->exists();
        if ($userExist) {
            return array(
                (object) [
                    'status' => 400,
                    'statusCode' => "warning",
                    'message' => 'Username already exists!',
                ]
            );
        }

        $result = DB::table('library_borrowers')->insertGetId([
            'borrower_cardno' => $request->borrower_cardno,
            'borrower_name' => $request->borrower_name,
            'borrower_class' => $request->borrower_class,
            'borrower_email' => $request->borrower_email,
            'borrower_phone' => $request->borrower_phone,
            'borrower_status' => $request->borrower_status,
            'borrower_username' => $request->borrower_username,
            'borrower_password' => \Hash::make($request->borrower_password ?? $request->borrower_cardno),
        ]);

        $isExist = DB::table('users')->where('email', strtolower($request->borrower_username))->exists();
        if ($request->borrower_status) {
            if (!$isExist) {
                DB::table('users')
                    ->insert(
                        [
                            'name' => $request->borrower_name,
                            'usertype' => 7,
                            'email' => $request->borrower_username,
                            'password' => \Hash::make($request->borrower_password ?? $request->borrower_cardno),
                        ]
                    );
            }
        } else {
            if ($isExist) {
                DB::table('users')
                    ->where('email', $request->borrower_username)
                    ->delete();
            }
        }

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
        DB::table('library_borrowers')
            ->where('id', $request->id)
            ->update([
                'borrower_cardno' => $request->borrower_cardno,
                'borrower_name' => $request->borrower_name,
                'borrower_class' => $request->borrower_class,
                'borrower_email' => $request->borrower_email,
                'borrower_phone' => $request->borrower_phone,
                'borrower_status' => $request->borrower_status,
                // 'borrower_username' => $request->borrower_username,
            ]);



        $isExist = DB::table('users')->where('email', strtolower($request->borrower_username))->exists();
        if ($request->borrower_status) {
            if (!$isExist) {
                DB::table('users')
                    ->insert(
                        [
                            'name' => $request->borrower_name,
                            'usertype' => 7,
                            'email' => $request->borrower_username,
                            'password' => \Hash::make($request->borrower_password),
                        ]
                    );
            }
        } else {
            if ($isExist) {
                DB::table('users')
                    ->where('email', $request->borrower_username)
                    ->delete();
            }
        }

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Updated Successfully!',
            ]
        );
    }

    public function get_borrower(Request $request)
    {
        $lib = DB::table('library_borrowers')
            ->where('deleted', 0)
            ->where('library_borrowers.id', $request->id)
            ->first();

        return response()->json($lib);
    }


    public function destroy(Request $request)
    {
        DB::table('library_borrowers')
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
