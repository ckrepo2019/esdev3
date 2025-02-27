<?php

namespace App\Http\Controllers\ClinicControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\SchoolClinic\SchoolClinic;
use PDF;



class ComplaintController extends Controller
{
    public function index()
    {
        $refid = DB::table('usertype')
            ->where('id', Session::get('currentPortal'))
            ->first();

        if ($refid->refid == '23') {
            $extends = 'clinic';
        } elseif ($refid->refid == '24') {

            $extends = 'clinic_nurse';
        } elseif ($refid->refid == '25') {

            $extends = 'clinic_doctor';
        }
        $users = SchoolClinic::users();

        return view('clinic_nurse.complaint.index')
            ->with('extends', $extends);
        //    ->with('users', $users);

        // return view('clinic_nurse.complaint.index');
    }
    public function getallusers()
    {
        $allusers = collect(SchoolClinic::users())->sortBy('lastname');

        $options = "";
        if (count($allusers) > 0) {
            foreach ($allusers as $user) {
                $options .= '<option value="' . $user->userid . '">' . $user->name_showlast . '</option>';
            }
        } else {
            $options .= '<option value="">No data shown</option>';
        }
        return $options;
    }
    public function add(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        $checkifexists = DB::table('clinic_complaints')
            ->where('userid', $request->get('addcomplainant'))
            ->where('cdate', $request->get('adddate'))
            ->where('deleted', '0')
            ->count();

        if ($checkifexists > 0) {
            return 0;
        } else {
            DB::table('clinic_complaints')
                ->insert([
                    'userid' => $request->get('addcomplainant'),
                    'description' => $request->get('adddescription'),
                    'cdate' => $request->get('adddate'),
                    'ctime' => $request->get('addtime'),
                    'actiontaken' => $request->get('addactiontaken'),
                    'benefeciaryname' => $request->get('beneficiary'),
                    'relationship' => $request->get('relationship'),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);

            return 1;
        }
    }
    public function getuserComplaints(Request $request)
    {


        $complaints = DB::table('clinic_complaints')
            ->select('clinic_complaints.*', 'users.type')
            ->leftJoin('users', 'clinic_complaints.userid', '=', 'users.id')
            ->where('clinic_complaints.deleted', '0')
            ->where('clinic_complaints.userid', $request->get('id'))
            ->get();


        if (count($complaints) > 0) {
            foreach ($complaints as $complaint) {
                if ($complaint->type == 7) {

                    $sid = DB::table('users')
                        ->where('id', $complaint->userid)
                        ->value('email');


                    $studid = DB::table('studinfo')->where('sid', str_replace('S', '', $sid))->value('id');


                    $info = Db::table('studinfo')
                        ->where('id', $studid)
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

                // $complaintmeds = DB::table('clinic_complaintmed')
                //     ->select('clinic_complaintmed.*','clinic_medicines.genericname','clinic_medicines.brandname')
                //     ->join('clinic_medicines','clinic_complaintmed.drugid','=','clinic_medicines.id')
                //     ->where('clinic_complaintmed.headerid', $complaint->id)
                //     ->where('clinic_complaintmed.deleted','0')
                //     ->where('clinic_complaintmed.quantity','!=',0)
                //     ->first();

                // $complaint->genericname = "";
                // $complaint->brandname = "";
                // $complaint->quantity = "";
                // if($complaintmeds)
                // {
                //     $complaint->complaintmed = 1;
                //     $complaint->genericname = $complaintmeds->genericname;
                //     $complaint->brandname =$complaintmeds->brandname;
                //     $complaint->quantity = $complaintmeds->quantity;
                // }else{
                //     $complaint->complaintmed = 0;
                // }

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
        // return($complaints);
        return view('clinic_nurse.complaint.result_complaints')
            ->with('complaints', $complaints);
    }
    public function getcomplaints(Request $request)
    {
        // return $request->all();
        $selecteddates = explode(' - ', $request->get('selecteddaterange'));
        $datefrom = date('Y-m-d', strtotime($selecteddates[0]));
        $dateto = date('Y-m-d', strtotime($selecteddates[1]));

        $complaints = DB::table('clinic_complaints')
            ->select('clinic_complaints.*', 'users.type')
            ->leftJoin('users', 'clinic_complaints.userid', '=', 'users.id')
            ->where('clinic_complaints.deleted', '0')
            ->whereBetween('clinic_complaints.cdate', [$datefrom, $dateto])
            ->get();


        if (count($complaints) > 0) {
            foreach ($complaints as $complaint) {
                if ($complaint->type == 7) {
                    $sid = DB::table('users')
                        ->where('id', $complaint->userid)
                        ->value('email');


                    $studid = DB::table('studinfo')->where('sid', str_replace('S', '', $sid))->value('id');


                    $info = Db::table('studinfo')
                        ->where('id', $studid)
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

                // $complaintmeds = DB::table('clinic_complaintmed')
                //     ->select('clinic_complaintmed.*','clinic_medicines.genericname','clinic_medicines.brandname')
                //     ->join('clinic_medicines','clinic_complaintmed.drugid','=','clinic_medicines.id')
                //     ->where('clinic_complaintmed.headerid', $complaint->id)
                //     ->where('clinic_complaintmed.deleted','0')
                //     ->where('clinic_complaintmed.quantity','!=',0)
                //     ->first();

                // $complaint->genericname = "";
                // $complaint->brandname = "";
                // $complaint->quantity = "";
                // if($complaintmeds)
                // {
                //     $complaint->complaintmed = 1;
                //     $complaint->genericname = $complaintmeds->genericname;
                //     $complaint->brandname =$complaintmeds->brandname;
                //     $complaint->quantity = $complaintmeds->quantity;
                // }else{
                //     $complaint->complaintmed = 0;
                // }

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
        // return($complaints);
        return view('clinic_nurse.complaint.result_complaints')
            ->with('complaints', $complaints);
    }
    public function getdrugs(Request $request)
    {
        $drugs = SchoolClinic::drugs();
        foreach ($drugs as $drug) {
            $drug->selected = 0;
            $drug->quantityadded = 0;
            $drug->remarks = "";
            $checkifmedicated = DB::table('clinic_complaintmed')
                ->where('createddatetime', $request->get('id'))
                ->where('drugid', $drug->id)
                ->where('deleted', '0')
                ->first();

            if ($checkifmedicated) {
                $drug->selected = 1;
                $drug->quantityadded = $checkifmedicated->quantity;
                $drug->remarks = $checkifmedicated->remarks;
            }
        }
        return $drugs;
    }
    public function addmed(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // return $request->all();


        try {
            DB::table('clinic_complaintmed')
                ->insert([
                    'headerid' => $request->get('complaintid'),
                    'drugid' => $request->get('drugid'),
                    'quantity' => $request->get('quantity'),
                    'remarks' => $request->get('remarks'),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        } catch (\Exception $error) {
            return 0;
        }
        // $checkifexists =DB::table('clinic_complaintmed')
        //     ->where('')
    }
    public function editmed(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        try {
            DB::table('clinic_complaintmed')
                ->where('createddatetime', $request->get('complaintid'))
                ->where('deleted', '0')
                ->update([
                    'drugid' => $request->get('drugid'),
                    'quantity' => $request->get('quantity'),
                    'remarks' => $request->get('remarks'),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        } catch (\Exception $error) {
            return 0;
        }
        // $checkifexists =DB::table('clinic_complaintmed')
        //     ->where('')
    }
    public function deletemed(Request $request)
    {
        try {
            DB::table('clinic_complaintmed')
                ->where('createddatetime', $request->get('id'))
                ->where('deleted', '0')
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        } catch (\Exception $error) {
            return 0;
        }
    }
    public function getinfo(Request $request)
    {
        $complaintinfo = DB::table('clinic_complaints')
            ->where('id', $request->get('id'))
            ->first();

        return collect($complaintinfo);
    }
    public function edit(Request $request)
    {
        try {
            DB::table('clinic_complaints')
                ->where('id', $request->get('complaintid'))
                ->update([
                    'userid' => $request->get('editcomplainant'),
                    'description' => $request->get('editdescription'),
                    'cdate' => $request->get('editdate'),
                    'ctime' => $request->get('edittime'),
                    'actiontaken' => $request->get('editactiontaken'),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        } catch (\Exception $error) {
            return $error;
        }
    }
    public function delete(Request $request)
    {
        DB::table('clinic_complaints')
            ->where('id', $request->get('id'))
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => date('Y-m-d H:i:s')
            ]);
    }
    public function statusUpdate(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        DB::table('clinic_prescription')
            ->where('complaintid', $request->get('id'))
            ->update([
                'Approve' => 0,
            ]);

        $complaint = DB::table('clinic_complaints')
            ->select('clinic_complaints.*', 'users.type')
            ->leftJoin('users', 'clinic_complaints.userid', '=', 'users.id')
            ->where('clinic_complaints.id', '=', $request->get('id'))
            ->first();

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
                $name_showfirst .= $info->middlename . '. ';
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
                $name_showlast .= $info->middlename . '. ';
            }
            $name_showlast .= $info->suffix . ' ';

            $complaint->name_showlast = $name_showlast;

        }

        $description = "You approved a prescription for ";
        $description .= $complaint->name_showlast;

        DB::table('clinic_notfication')
            ->where('complaintid', $request->get('id'))
            ->update([
                'descripton' => $description
            ]);
    }
    public function deletePres(Request $request)
    {



        DB::table('clinic_notfication')
            ->where('complaintid', $request->get('id'))
            ->update([
                'deleted' => 1,
            ]);


        DB::table('clinic_prescription')
            ->where('complaintid', $request->get('id'))
            ->update([
                'deleted' => 1,
            ]);
    }
    public function addPrescription(Request $request)
    {

        date_default_timezone_set('Asia/Manila');

        $notify = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->where('usertypeid', '34')
            ->where('deleted', '0')
            ->get();

        if (count($notify) == 0) {

            $complaint = DB::table('clinic_complaints')
                ->select('clinic_complaints.*', 'users.type')
                ->leftJoin('users', 'clinic_complaints.userid', '=', 'users.id')
                ->where('clinic_complaints.id', '=', $request->get('complaintid'))
                ->first();

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
                    $name_showfirst .= $info->middlename . '. ';
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
                    $name_showlast .= $info->middlename . '. ';
                }
                $name_showlast .= $info->suffix . ' ';

                $complaint->name_showlast = $name_showlast;

            }

            $description = "Pending prescription approval for ";
            if (isset($complaint->name_showlast)) {
                $description .= $complaint->name_showlast;
            } else {
                $description .= '';
            }


            DB::table('clinic_notfication')
                ->insert([
                    'headerid' => $request->get('docid'),
                    'complaintid' => $request->get('complaintid'),
                    'descripton' => $description,
                    'createddatetime' => date('Y-m-d H:i:s'),
                ]);

        }
        DB::table('clinic_prescription')
            ->insert([
                'complaintid' => $request->get('complaintid'),
                'doctorname' => $request->get('doctor'),
                'docid' => $request->get('docid'),
                'medicinename' => $request->get('medname'),
                'dosage' => $request->get('dosage'),
                'duration' => $request->get('duration'),
                'quantity' => $request->get('quantity'),
                'advice' => $request->get('advice'),
                'createdby' => auth()->user()->id,
                'createddatetime' => date('Y-m-d H:i:s'),
                'followup' => $request->get('adddate'),
                'Approve' => $request->get('approval')

            ]);

        return 1;
    }
    public function viewPrescription(Request $request)
    {
        $view = DB::table('clinic_prescription')
            ->select('clinic_prescription.*', 'clinic_complaints.benefeciaryname', 'clinic_complaints.relationship')
            ->leftJoin('clinic_complaints', 'clinic_prescription.complaintid', '=', 'clinic_complaints.id')
            ->where('clinic_prescription.complaintid', $request->get('complaintid'))
            ->where('clinic_prescription.deleted', '0')
            ->get();

        return $view;
    }
    public function getDoctor(Request $request)
    {
        $allusers = collect(SchoolClinic::doctor())->sortBy('lastname');

        $options = "";
        if (count($allusers) > 0) {
            foreach ($allusers as $user) {
                $options .= '<option value="' . $user->userid . '">' . $user->name_showlast . '</option>';
            }
        } else {
            $options .= '<option value="">No data shown</option>';
        }
        return $options;
    }

    public function generatePDF(Request $request)
    {

        $data = DB::table('clinic_prescription')
            ->where('complaintid', $request->get('complainid'))
            ->where('Approve', '0')
            ->where('deleted', '0')
            ->get();

        $complaint = DB::table('clinic_complaints')
            ->select('clinic_complaints.*', 'users.type')
            ->leftJoin('users', 'clinic_complaints.userid', '=', 'users.id')
            ->where('clinic_complaints.id', '=', $request->get('complainid'))
            ->first();

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
                $name_showfirst .= $info->middlename . '. ';
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
                $name_showlast .= $info->middlename . '. ';
            }
            $name_showlast .= $info->suffix . ' ';

            $complaint->name_showlast = $name_showlast;

        }



        $docid = $data[0]->docid;

        $doctor = DB::table('clinic_doctorsinfo')
            ->where('docid', $docid)
            ->first();

        $pdf = PDF::loadview('clinic_nurse.complaint.pdf', compact('data', 'doctor', 'complaint'))->setPaper('8.5x11');
        // $pdf = PDF::loadView('clinic_nurse.complaint.pdf', $data)->setPaper('8.5x13');


        return $pdf->stream('Prescription.pdf');
    }

    public function getallusertype(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('usertype.utype')
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->where('teacher.deleted', '0')
            ->where('teacher.userid', $request->get('id'))
            ->get();

        // return $employees;
        $students = DB::table('studinfo')
            ->select('studinfo.userid')
            ->where('studinfo.deleted', '0')
            ->where('studinfo.userid', $request->get('id'))
            ->whereIn('studinfo.studstatus', [1, 2, 4])
            ->get();

        if (count($students) > 0) {
            foreach ($students as $student) {
                $student->utype = 'STUDENT';
            }
        }

        $allusers = collect();
        $allusers = $allusers->merge($employees);
        $allusers = $allusers->merge($students);

        return $allusers;

    }



}
