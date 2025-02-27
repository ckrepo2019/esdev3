<?php

namespace App\Http\Controllers\IDPrintingController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends \App\Http\Controllers\Controller
{
    public function teachers()
    {
        return DB::table('teacher')
            ->leftJoin('printing_teachers_info', 'teacher.userid', '=', 'printing_teachers_info.userid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
			 ->leftJoin('templates', 'printing_teachers_info.template', '=', 'templates.id')
            ->where('teacher.deleted', 0)
            ->select(
                'teacher.*',
                'usertype.utype',
                'printing_teachers_info.template',
                'printing_teachers_info.status',
			 DB::raw('templates.name AS templatename'),
            )
            ->get();
    }

    public function get_teacher(Request $request)
    {
        $info = DB::table('teacher')
            ->leftJoin('printing_teachers_info', 'teacher.userid', '=', 'printing_teachers_info.userid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->leftJoin('templates', 'printing_teachers_info.template', '=', 'templates.id')
            ->where('teacher.deleted', 0)
            ->where('teacher.userid', $request->id)
            ->select(
                'teacher.*',
                'usertype.utype',
                'printing_teachers_info.template',
                'printing_teachers_info.status',
                DB::raw('templates.name AS templatename'),
            )
            ->get();

        $middlename = '';
        $suffix = '';
        $title = '';

        if (isset($info[0]->middlename)) {
            $middlename = ' ' . $info[0]->middlename;
        }

        if (isset($info[0]->suffix)) {
            $suffix = ' ' . $info[0]->suffix;
        }

        if (isset($info[0]->title)) {
            $title = $info[0]->title;
        }

        $info[0]->name = $title . ' ' . $info[0]->lastname . ', ' . $info[0]->firstname . $middlename . $suffix;

        $template = DB::table('templates')
            ->where('name', $info[0]->templatename)
            ->get();

        return response()->json([
            'teacher' => $info,
            'templates' => $template
        ]);
    }

    public function update_teacher(Request $request)
    {
        $hasExist = DB::table('printing_teachers_info')
            ->where('userid', $request->id)
            ->first();

        if (isset($hasExist->id)) {
            DB::table('printing_teachers_info')
                ->where('userid', $hasExist->userid)
                ->update([
                    'template' => $request->templateid,
                ]);


        } else {
            DB::table('printing_teachers_info')
                ->insert([
                    'userid' => $request->id,
                    'template' => $request->templateid,
                ]);
        }

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Updated Successfully!',
            ]
        );
    }
}