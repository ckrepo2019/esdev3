<?php

namespace App\Http\Controllers\IDPrintingController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends \App\Http\Controllers\Controller
{
    public function students(Request $request)
    {
        $userType = auth()->user()->type;
        $check_refid = DB::table('usertype')
            ->where('id', \Session::get('currentPortal'))
            ->select('refid', 'resourcepath')
            ->first();

        if (isset($check_refid->refid) && $check_refid->refid == 29 || \Session::get('currentPortal') == 1) {

            if ($request->levelid == 14 || $request->levelid == 15) {
                return DB::table('sh_enrolledstud')
                    ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
                    ->leftjoin('id_system_studinfo', 'sh_enrolledstud.studid', '=', 'id_system_studinfo.studid')
                    ->leftJoin('templates', 'id_system_studinfo.template', '=', 'templates.id')
                    ->leftJoin('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->leftJoin('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                    ->where('sh_enrolledstud.syid', $request->syid)
                    ->where('sh_enrolledstud.deleted', 0)
                    ->whereIn('sh_enrolledstud.studstatus', [1, 2, 4])
                    ->when($request->filled('levelid'), function ($query) use ($request) {
                        return $query->where('sh_enrolledstud.levelid', $request->levelid);
                    })
                    ->when($request->filled('semid'), function ($query) use ($request) {
                        return $query->where('sh_enrolledstud.semid', $request->semid);
                    })
                    ->when($request->filled('section'), function ($query) use ($request) {
                        return $query->where('sh_enrolledstud.sectionid', $request->section);
                    })
                    ->select(
                        'id_system_studinfo.status',
                        'id_system_studinfo.fullname',
                        'studinfo.*',
                        'sy.sydesc',
                        'sh_enrolledstud.grantee',
                        'sh_enrolledstud.syid',
                        'sh_enrolledstud.levelid',
                        'gradelevel.levelname',
                        // DB::raw('sh_enrolledstud.id AS id'),
                        DB::raw('studinfo.id AS studid'),
                        DB::raw('templates.name AS template'),
                        DB::raw('CONCAT(studinfo.street, " ", studinfo.barangay, " ", studinfo.city, " ", studinfo.province ) AS address')
                    )->get();
            } else if ($request->levelid >= 17 && $request->levelid <= 21) {
                return DB::table('college_enrolledstud')
                    ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                    ->leftjoin('id_system_studinfo', 'college_enrolledstud.studid', '=', 'id_system_studinfo.studid')
                    ->leftJoin('templates', 'id_system_studinfo.template', '=', 'templates.id')
                    ->leftJoin('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->leftJoin('sy', 'college_enrolledstud.syid', '=', 'sy.id')
                    ->where('college_enrolledstud.syid', $request->syid)
                    ->where('college_enrolledstud.deleted', 0)
                    ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                    ->when($request->filled('levelid'), function ($query) use ($request) {
                        return $query->where('college_enrolledstud.yearLevel', $request->yearLevel);
                    })
                    ->when($request->filled('semid'), function ($query) use ($request) {
                        return $query->where('college_enrolledstud.semid', $request->semid);
                    })
                    ->when($request->filled('section'), function ($query) use ($request) {
                        return $query->where('college_enrolledstud.sectionid', $request->section);
                    })
                    ->select(
                        'id_system_studinfo.status',
                        'id_system_studinfo.fullname',
                        'studinfo.*',
                        'sy.sydesc',
                        // 'college_enrolledstud.grantee',
                        'college_enrolledstud.syid',
                        'college_enrolledstud.yearLevel',
                        'gradelevel.levelname',
                        DB::raw('college_enrolledstud.yearLevel as levelid'),
                        DB::raw('templates.name AS template'),
                        DB::raw('studinfo.id AS studid'),
                        DB::raw('CONCAT(studinfo.street, " ", studinfo.barangay, " ", studinfo.city, " ", studinfo.province ) AS address')
                    )->get();
            } else {

                return DB::table('enrolledstud')
                    ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
                    ->leftjoin('id_system_studinfo', 'enrolledstud.studid', '=', 'id_system_studinfo.studid')
                    ->leftJoin('templates', 'id_system_studinfo.template', '=', 'templates.id')
                    ->leftJoin('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->leftJoin('sy', 'enrolledstud.syid', '=', 'sy.id')
                    ->where('enrolledstud.syid', $request->syid)
                    ->where('enrolledstud.deleted', 0)
                    ->whereIn('enrolledstud.studstatus', [1, 2, 4])
                    ->when($request->filled('levelid'), function ($query) use ($request) {
                        // Add the condition only if $request->levelid is not empty
                        return $query->where('enrolledstud.levelid', $request->levelid);
                    })
                    ->when($request->filled('section'), function ($query) use ($request) {
                        return $query->where('enrolledstud.sectionid', $request->section);
                    })
                    ->select(
                        'id_system_studinfo.status',
                        'id_system_studinfo.fullname',
                        'studinfo.*',
                        'sy.sydesc',
                        'enrolledstud.grantee',
                        'enrolledstud.syid',
                        'enrolledstud.levelid',
                        'gradelevel.levelname',
                        // DB::raw('enrolledstud.id AS id'),
                        DB::raw('studinfo.id AS studid'),
                        DB::raw('templates.name AS template'),
                        DB::raw('CONCAT(studinfo.street, " ", studinfo.barangay, " ", studinfo.city, " ", studinfo.province ) AS address')
                    )
                    ->get();
            }
        } else {
            if ($userType === 17) {
                $studList = [];
                $idSystemStudInfoTable = 'id_system_studinfo';
                $enrolledStudTable = 'enrolledstud';
                $shEnrolledStudTable = 'sh_enrolledstud';
                $collegeEnrolledStudTable = 'college_enrolledstud';

                $data = DB::table($idSystemStudInfoTable)->get();

                foreach ($data as $item) {
                    if ($item->syid == $request->syid && $item->levelid == $request->levelid) {
                        $stud = [];
                        $levelId = $item->levelid;
                        // $studid = $item->studid;

                        switch ($levelId) {
                            case 14:
                            case 15:
                                $currentTable = $shEnrolledStudTable;
                                break;
                            case ($levelId >= 17 && $levelId <= 21):
                                $currentTable = $collegeEnrolledStudTable;
                                break;
                            default:
                                $currentTable = $enrolledStudTable;
                        }

                        $selectColumns = [
                            $idSystemStudInfoTable . '.status',
                            $idSystemStudInfoTable . '.fullname',
                            'studinfo.*',
                            'sy.sydesc',
                            $currentTable !== 'college_enrolledstud' && \Schema::hasColumn($currentTable, 'grantee')
                            ? $currentTable . '.grantee'
                            : null, // Include 'grantee' only if the column exists
                            $currentTable . '.syid',
                            $idSystemStudInfoTable . '.firstname',
                            $idSystemStudInfoTable . '.lastname',
                            $idSystemStudInfoTable . '.middlename',
                            'gradelevel.levelname',
                            $currentTable === 'college_enrolledstud' ? $currentTable . '.yearLevel' : $currentTable . '.levelid',
                            DB::raw('studinfo.id AS studid'),
                            DB::raw('templates.name AS template'),
                            DB::raw('CONCAT(studinfo.street, " ", studinfo.barangay, " ", studinfo.city, " ", studinfo.province ) AS address')
                        ];


                        // Remove null values from the array
                        $selectColumns = array_filter($selectColumns, function ($column) {
                            return !is_null($column);
                        });

                        $stud = DB::table($currentTable)
                            ->select($selectColumns)
                            ->where($currentTable . '.studid', $item->studid)
                            ->where($currentTable . '.syid', $request->syid)
                            ->where($idSystemStudInfoTable . '.status', 1)
                            ->where($idSystemStudInfoTable . '.deleted', 0)
                            ->when($request->filled('section'), function ($query) use ($request, $currentTable) {
                                return $query->where($currentTable . '.sectionid', $request->section);
                            })
                            ->when($request->filled('semid'), function ($query) use ($request, $currentTable) {
                                if ($currentTable !== 'enrolledstud') {
                                    return $query->where($currentTable . '.semid', $request->semid);
                                }
                            })
                            ->whereIn($currentTable . '.studstatus', [1, 2, 4])
                            ->join('studinfo', $currentTable . '.studid', '=', 'studinfo.id')
                            ->join($idSystemStudInfoTable, $currentTable . '.studid', '=', $idSystemStudInfoTable . '.studid')
                            ->leftJoin('templates', $idSystemStudInfoTable . '.template', '=', 'templates.id')

                            ->join('gradelevel', function ($join) use ($currentTable) {
                                if ($currentTable === 'college_enrolledstud') {
                                    $join->on($currentTable . '.yearLevel', '=', 'gradelevel.id');
                                } else {
                                    $join->on($currentTable . '.levelid', '=', 'gradelevel.id');
                                }
                            })
                            ->leftJoin('sy', $currentTable . '.syid', '=', 'sy.id')
                            ->first();

                        if (isset($stud->id)) {
                            $studList[] = $stud;
                        }
                    }
                }
                if (empty($studList)) {
                    return [ // Return an empty array
                        (object) [
                            // You can include any additional properties if needed
                        ]
                    ];
                } else {
                    return response()->json($studList);
                }

            }
        }
    }

    public function update_student(Request $request)
    {
        $hastemp = DB::table('id_system_studinfo')->where('studid', $request->id)->first();
        if ($hastemp) {
            DB::table('id_system_studinfo')
                ->where('studid', $request->id)
                ->where('levelid', $request->levelid)
                ->update([
                    'template' => $request->template,
                    'studid' => $request->id,
                    'firstname' => $request->firstname,
                    'middlename' => $request->middlename,
                    'lastname' => $request->lastname,
                    'dob' => $request->dob,
                    'contactno' => $request->contactno,
                    'level' => $request->level,
                    'levelid' => $request->levelid,
                    'syid' => $request->syid,
                    'deleted' => 0,
                ]);

            return array(
                (object) [
                    'status' => 200,
                    'statusCode' => "success",
                    'message' => 'Updated Successfully!',
                ]
            );

        } else {
            DB::table('id_system_studinfo')
                ->insert([
                    'template' => $request->template,
                    'studid' => $request->id,
                    'firstname' => $request->firstname,
                    'middlename' => $request->middlename,
                    'lastname' => $request->lastname,
                    'dob' => $request->dob,
                    'contactno' => $request->contactno,
                    'level' => $request->level,
                    'levelid' => $request->levelid,
                    'syid' => $request->syid,
                ]);

            return array(
                (object) [
                    'status' => 200,
                    'statusCode' => "success",
                    'message' => 'Added Successfully!',
                ]
            );
        }
    }


    public function get_student(Request $request)
    {
        $stud = [];

        if ($request->levelid == 14 || $request->levelid == 15) {
            $stud = DB::table('sh_enrolledstud')
                // ->where('sh_enrolledstud.levelid', $request->levelid)
                // ->when($request->filled('semid'), function ($query) use ($request) {
                //     return $query->where('sh_enrolledstud.semid', $request->semid);
                // })
                ->where('sh_enrolledstud.syid', $request->syid)
                ->where('sh_enrolledstud.levelid', $request->levelid)
                ->where('sh_enrolledstud.semid', $request->semid)
                ->where('sh_enrolledstud.studid', $request->id)
                ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
                ->leftjoin('id_system_studinfo', 'sh_enrolledstud.studid', '=', 'id_system_studinfo.studid')
                ->leftJoin('templates', 'id_system_studinfo.template', '=', 'templates.id')
                ->leftJoin('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->leftJoin('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                ->select(
                    'id_system_studinfo.fullname',
                    'studinfo.*',
                    'sy.sydesc',
                    'sh_enrolledstud.grantee',
                    'sh_enrolledstud.syid',
                    'sh_enrolledstud.levelid',
                    'gradelevel.levelname',
                    'id_system_studinfo.status',
                    // 'id_system_studinfo.firstname',
                    // 'id_system_studinfo.lastname',
                    // 'id_system_studinfo.middlename',
                    DB::raw('templates.id AS tempid'),
                    DB::raw('templates.name AS template'),
                    DB::raw('CONCAT(studinfo.street, " ", studinfo.barangay, " ", studinfo.city, " ", studinfo.province ) AS address')
                )
                ->get();

        } else if ($request->levelid >= 17 && $request->levelid <= 21) {
            $stud = DB::table('college_enrolledstud')
                // ->where('college_enrolledstud.syid', $request->syid)
                // ->where('college_enrolledstud.yearLevel', $request->levelid)
                // ->when($request->filled('semid'), function ($query) use ($request) {
                //     return $query->where('college_enrolledstud.semid', $request->semid);
                // })
                ->where('college_enrolledstud.syid', $request->syid)
                ->where('college_enrolledstud.yearLevel', $request->levelid)
                ->where('college_enrolledstud.semid', $request->semid)
                ->where('college_enrolledstud.studid', $request->id)
                ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                ->leftjoin('id_system_studinfo', 'college_enrolledstud.studid', '=', 'id_system_studinfo.studid')
                ->leftJoin('templates', 'id_system_studinfo.template', '=', 'templates.id')
                ->leftJoin('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                ->leftJoin('sy', 'college_enrolledstud.syid', '=', 'sy.id')
                ->select(
                    'id_system_studinfo.status',
                    'id_system_studinfo.fullname',
                    'studinfo.*',
                    'sy.sydesc',
                    // 'college_enrolledstud.grantee',
                    'college_enrolledstud.syid',
                    'college_enrolledstud.yearLevel',
                    'gradelevel.levelname',
                    // 'id_system_studinfo.firstname',
                    // 'id_system_studinfo.lastname',
                    // 'id_system_studinfo.middlename',
                    DB::raw('templates.id AS tempid'),
                    DB::raw('templates.name AS template'),
                    DB::raw('CONCAT(studinfo.street, " ", studinfo.barangay, " ", studinfo.city, " ", studinfo.province ) AS address')
                )
                ->get();
        } else {
            $stud = DB::table('enrolledstud')
                ->where('enrolledstud.syid', $request->syid)
                ->where('enrolledstud.studid', $request->id)
                ->where('enrolledstud.levelid', $request->levelid)
                // ->when($request->filled('levelid'), function ($query) use ($request) {
                //     // Add the condition only if $request->levelid is not empty
                //     return $query->where('enrolledstud.levelid', $request->levelid);
                // })
                ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
                ->leftjoin('id_system_studinfo', 'enrolledstud.studid', '=', 'id_system_studinfo.studid')
                ->leftJoin('templates', 'id_system_studinfo.template', '=', 'templates.id')
                ->leftJoin('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->leftJoin('sy', 'enrolledstud.syid', '=', 'sy.id')
                ->select(
                    'id_system_studinfo.status',
                    'id_system_studinfo.fullname',
                    'studinfo.*',
                    'sy.sydesc',
                    'enrolledstud.grantee',
                    'enrolledstud.syid',
                    'enrolledstud.levelid',
                    'gradelevel.levelname',
                    // 'id_system_studinfo.firstname',
                    // 'id_system_studinfo.lastname',
                    // 'id_system_studinfo.middlename',
                    DB::raw('templates.id AS tempid'),
                    DB::raw('templates.name AS template'),
                    DB::raw('CONCAT(studinfo.street, " ", studinfo.barangay, " ", studinfo.city, " ", studinfo.province ) AS address')
                )
                ->get();
        }
        $isexist = DB::table('id_system_studinfo')
            ->where('enrollment_id', $request->id)
            ->first();
        if ($isexist) {
            if ($isexist->dob) {
                $stud[0]->dob = $isexist->dob;
            }
            if ($isexist->firstname) {
                $stud[0]->firstname = $isexist->firstname;
            }
            if ($isexist->middlename) {
                $stud[0]->middlename = $isexist->middlename;
            }
            if ($isexist->lastname) {
                $stud[0]->lastname = $isexist->lastname;
            }
            if ($isexist->contactno) {
                $stud[0]->contactno = $isexist->contactno;
            }
        }

        if ($stud[0]->isfathernum) {
            $stud[0]->emergencyperson = $stud[0]->fathername;
            $stud[0]->emergencyno = $stud[0]->fcontactno;
            $stud[0]->emergencyaddress = $stud[0]->parentaddress;
        }
        if ($stud[0]->ismothernum) {
            $stud[0]->emergencyperson = $stud[0]->mothername;
            $stud[0]->emergencyno = $stud[0]->mcontactno;
            $stud[0]->emergencyaddress = $stud[0]->parentaddress;
        }
        if ($stud[0]->isguardannum) {
            $stud[0]->emergencyperson = $stud[0]->guardianname;
            $stud[0]->emergencyno = $stud[0]->gcontactno;
            $stud[0]->emergencyaddress = $stud[0]->guardianaddress;
        }

        $middlename = '';
        $suffix = '';

        if (isset($stud[0]->middlename)) {
            $middlename = ' ' . $stud[0]->middlename;
        }

        if (isset($stud[0]->suffix)) {
            $suffix = ' ' . $stud[0]->suffix;
        }

        $stud[0]->name = $stud[0]->lastname . ', ' . $stud[0]->firstname . $middlename . $suffix;

        $template = DB::table('templates')
            ->where('name', $stud[0]->template)
            ->get();

        return response()->json([
            'stud' => $stud,
            'templates' => $template
        ]);
    }


    public function addfullname(Request $request)
    {
        DB::table('id_system_studinfo')
            ->where('studid', $request->id)
            ->update([
                'fullname' => $request->fullname,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Name Format Set!',
            ]
        );
    }

    public function update_status(Request $request)
    {
        $status = $request->status;

        $originalDeleted = DB::table('id_system_studinfo')
            ->where('studid', $request->id)
            ->value('deleted');

        DB::table('id_system_studinfo')
            ->where('studid', $request->id)
            ->update([
                'status' => $status,
                'deleted' => ($status === 1) ? 0 : $originalDeleted,
            ]);

        return [
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Status Changed!',
            ]
        ];
    }


    public function destroy(Request $request)
    {
        DB::table('id_system_studinfo')
            ->where('studid', $request->id)
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
