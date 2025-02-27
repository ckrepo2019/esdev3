<?php

namespace App\Models\SchoolClinic;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;

class SchoolClinic extends Model
{
    public static function personnel()
    {



        $usertypeid = DB::table('usertype')
            ->whereIn('refid', [23, 24, 25])
            ->where('deleted', '0')
            ->first();

        if ($usertypeid) {

            $faspriv = DB::table('faspriv')
                ->where('usertype', $usertypeid->id)
                ->where('deleted', 0)
                ->get();

            if (count($faspriv) > 0) {
                $personnel = DB::table('teacher')
                    ->where('teacher.usertypeid', $usertypeid->id)
                    ->orWhereIn('teacher.userid', collect($faspriv)->pluck('userid'))
                    ->where('teacher.deleted', '0')
                    ->where('teacher.isactive', '1')
                    ->select(
                        'teacher.id',
                        'teacher.userid',
                        'title',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'employee_personalinfo.gender',
                        'employee_personalinfo.address',
                        'employee_personalinfo.contactnum',
                        'teacher.picurl',
                        'usertype.utype',
                        'users.loggedIn',
                        'users.loggedOut'
                    )
                    ->join('users', 'teacher.userid', '=', 'users.id')
                    ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                    ->get();

            } else {
                $personnel = DB::table('teacher')
                    ->where('teacher.usertypeid', $usertypeid->id)
                    ->where('teacher.deleted', '0')
                    ->where('teacher.isactive', '1')
                    ->select(
                        'teacher.id',
                        'teacher.userid',
                        'title',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'employee_personalinfo.gender',
                        'employee_personalinfo.address',
                        'employee_personalinfo.contactnum',
                        'teacher.picurl',
                        'usertype.utype',
                        'users.loggedIn',
                        'users.loggedOut'
                    )
                    ->join('users', 'teacher.userid', '=', 'users.id')
                    ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                    ->get();
            }

        }

        if (count($personnel) > 0) {
            foreach ($personnel as $person) {
                $name = "";

                if ($person->title != null) {
                    $name .= $person->title . ' ';
                }
                $name .= $person->firstname . ' ';

                if ($person->middlename != null) {
                    $name .= $person->middlename[0] . '. ';
                }
                $name .= $person->lastname . ' ';
                $name .= $person->suffix . ' ';

                $person->name = $name;
                $person->address = strtolower($person->address);
            }
        }

        return $personnel;
    }
    public static function users()
    {

        $employees = DB::table('teacher')
            ->select('teacher.id', 'teacher.userid', 'teacher.title', 'teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'teacher.suffix', 'employee_personalinfo.gender', 'usertype.utype', 'teacher.usertypeid')
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->where('teacher.deleted', '0')
            ->get();

        // return $employees;
        $students = DB::table('studinfo')
            ->select('studinfo.sid', 'studinfo.id', 'studinfo.userid', 'studinfo.firstname', 'studinfo.middlename', 'studinfo.lastname', 'studinfo.suffix', 'studinfo.gender', 'lrn')
            ->where('studinfo.deleted', '0')
            ->whereIn('studinfo.studstatus', [1, 2, 4])
            ->get();

        if (count($students) > 0) {
            foreach ($students as $student) {
                $email = 'S' . $student->sid;

                $userid = DB::table('users')->where('email', $email)->value('id');

                $student->userid = $userid;

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
            }
        }
        return $allusers->sortBy('lastname')->all();
    }
    public static function doctor()
    {


        $usertypeid = DB::table('usertype')
            ->where('refid', '25')
            ->where('deleted', '0')
            ->first();

        if ($usertypeid) {

            $faspriv = DB::table('faspriv')
                ->where('usertype', $usertypeid->id)
                ->where('deleted', 0)
                ->get();

            if (count($faspriv) > 0) {
                $employees = DB::table('teacher')
                    ->where('usertypeid', $usertypeid->id)
                    ->orWhereIn('userid', collect($faspriv)->pluck('userid'))
                    ->where('deleted', '0')
                    ->where('isactive', '1')
                    ->select('teacher.id', 'teacher.userid', 'teacher.title', 'teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'teacher.suffix', 'teacher.usertypeid')
                    ->get();

            } else {
                $employees = DB::table('teacher')
                    ->where('usertypeid', $usertypeid->id)
                    ->where('deleted', '0')
                    ->where('isactive', '1')
                    ->select('teacher.id', 'teacher.userid', 'teacher.title', 'teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'teacher.suffix', 'teacher.usertypeid')
                    ->get();
            }

        }

        $allusers = collect();
        $allusers = $allusers->merge($employees);

        if (count($allusers) > 0) {
            foreach ($allusers as $alluser) {

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
            }
        }
        return $allusers->sortBy('lastname')->all();

    }
    public static function drugs()
    {
        $drugs = DB::table('clinic_medicines')
            ->where('deleted', '0')
            ->orderBy('genericname', 'asc')
            ->get();

        $alldrugsleft = array();

        if (count($drugs) > 0) {
            foreach ($drugs as $drug) {

                $medicated = DB::table('clinic_complaintmed')
                    ->where('drugid', $drug->id)
                    ->where('deleted', '0')
                    ->get();

                $drug->quantityleft = $drug->quantity;
                $number = 0;
                foreach ($medicated as $med) {
                    $number += $med->quantity;
                }

                if ($medicated) {
                    // $drug->quantityleft = $drug->quantity-$medicated->quantity;
                    $drug->quantityleft = $drug->quantity - $number;
                }
                $drug->condition = 'BEST';
                if ($drug->expirydate < date('Y-m-d')) {
                    $drug->condition = 'EXP';
                } elseif ($drug->expirydate > date("Y-m-d", strtotime('sunday last  week')) && $drug->expirydate < date("Y-m-d", strtotime('sunday this week'))) {
                    $drug->condition = 'EXPW';
                }
            }
        }

        return $drugs;

    }
    public function getuserComplaints(Request $request)
    {

        $now = Carbon::now();
        $comparedDate = $now->toDateString();


        $complaints1 = DB::table('clinic_complaints')
            ->select('clinic_complaints.*', 'users.type')
            ->leftJoin('users', 'clinic_complaints.userid', '=', 'users.id')
            ->where('clinic_complaints.deleted', '0')
            ->where('clinic_complaints.cdate', date('Y-m-d'))
            ->get();

        $complaints = collect();
        $complaints = $allusers->merge($complaints1);


        if (count($complaints) > 0) {
            foreach ($complaints as $complaint) {
                if ($complaint->type == 7) {
                    $info = Db::table('studinfo')
                        ->where('userid', $complaint->userid)
                        ->where('deleted', '0')
                        ->first();

                    $info->title = null;
                    $info->utype = 'STUDENT';
                } else {
                    $info = Db::table('teacher')
                        ->select('teacher.*', 'usertype.utype', 'employee_personalinfo.gender')
                        ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                        ->leftJoin('employee_personalinfo', 'teacher.usertypeid', '=', 'usertype.id')
                        ->where('userid', $complaint->userid)
                        ->where('teacher.deleted', '0')
                        ->first();
                }


                if (isset($info)) {

                    $complaint->picurl = $info->picurl;
                    $complaint->gender = $info->gender;
                    $complaint->utype = $info->utype;

                    $name_showfirst = "";
                    $name_showlast = "";

                    if ($info->title != null) {
                        $name_showfirst .= $info->title . ' ';
                    }
                    $name_showfirst .= $info->firstname . ' ';

                    if ($info->middlename != null) {
                        $name_showfirst .= $info->middlename[0] . '. ';
                    }
                    $name_showfirst .= $info->lastname . ' ';
                    $name_showfirst .= $info->suffix . ' ';

                    $complaint->name_showfirst = $name_showfirst;

                    $name_showlast = "";

                    if ($info->title != null) {
                        $name_showlast .= $info->title . ' ';
                    }
                    $name_showlast .= $info->lastname . ', ';
                    $name_showlast .= $info->firstname . ' ';

                    if ($info->middlename != null) {
                        $name_showlast .= $info->middlename[0] . '. ';
                    }
                    $name_showlast .= $info->suffix . ' ';

                    $complaint->name_showlast = $name_showlast;
                } else {

                }
            }
        }
        return complaints;
    }
}