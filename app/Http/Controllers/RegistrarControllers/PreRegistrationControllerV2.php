<?php

namespace App\Http\Controllers\RegistrarControllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use File;
use Image;
use \Carbon\Carbon;
use Hash;


class PreRegistrationControllerV2 extends \App\Http\Controllers\Controller
{

    public function submitPrereg(Request $request)
    {

        $isGuardian = 0;
        $isFather = 0;
        $isMother = 0;

        $placeofbirth = $request->pobirth;
        $nativename = $request->nativename;
        $languagefluent = $request->languagelist;
        $birthorder = $request->birthorder;
        $numberofsister = $request->numberofsister;
        $numberofbrother = $request->numberofbrother;
        $hasbrotherinschool = $request->hasbrother ?? 0;
        $hasbrothergradelevel = $request->hasbrothergradelevel;
        $placeofbaptism = $request->placeofbaptism;
        $dateofbaptism = $request->dateofbaptism;
        $churchofbaptism = $request->churchofbaptism;
        $howdidyoulearnourschool = $request->medialist;
        $whystudyinourschool = $request->reasonlist;

        $listextracurricular = $request->listextracurricular;
        $listawards = $request->listawards;
        $listcommunity = $request->listcommunity;
        $reasonfordismissal = $request->reasonfordismissal;
        $reasonforrepeating = $request->reasonforrepeating;
        $reasonforprobation = $request->reasonforprobation;

        // dd($howdidyoulearnourschool, $whystudyinourschool);
        // dd($numberofbrother, $numberofsister, $hasbrotherinschool);
        // dd($birthorder, $languagefluent, $nativename, $placeofbirth);


        if ($request->get('incase') == 1) {
            $isFather = 1;
        } else if ($request->get('incase') == 2) {
            $isMother = 1;
        } else if ($request->get('incase') == 3) {
            $isGuardian = 1;
        }

        if ($request->get('studtype') == 2) {
            $studtype = ' transferee';
        } else {
            $studtype = 'new';
        }

        $grantee = 1;

        if ($request->has('withESC') && $request->get('withESC') != null) {
            $grantee = $request->get('withESC');
        }

        $schoolinfo = DB::table('schoolinfo')->first();
        $request->request->add(['syid' => $request->get('input_syid')]);



        if ($request->get('studtype') == 3) {

            $getStudentInformation = DB::table('studinfo')
                ->where('sid', $request->get('studid'))
                ->where('studinfo.deleted', '0')
                ->first();

            if (!isset($getStudentInformation->id)) {
                return "Student record not found! Please contact your school registrar";
            }

            self::upload_requirements($request->get('studid'), $request);

            DB::table('earlybirds')
                ->insert([
                    'studid' => $getStudentInformation->id,
                    'syid' => $request->get('input_syid'),
                    'semid' => $request->get('input_semid'),
                    'levelid' => $request->get('gradelevelid'),
                    'strandid' => $request->get('studstrand'),
                    'courseid' => $request->get('courseid'),
                    'createddatetime' => Carbon::now('Asia/Manila')
                ]);

            return redirect('/preregistration/get/qcode/' . $request->get('studid') . '/' . $request->get('last_name') . ', ' . $request->get('first_name') . '/' . 'Pre-enrolled');


        } else if ($request->get('studtype') == 2 || $request->get('studtype') == 1) {

            $ffname = $request->get('ffname');
            $flname = $request->get('flname');
            $fmname = $request->filled('fmname') ? $request->get('fmname') : '';
            $fsuffix = $request->filled('fsuffix') ? $request->get('fsuffix') : '';

            $fatherfullname = implode(' ', array_filter([$flname, $ffname, $fmname, $fsuffix]));

            $mfname = $request->get('mfname');
            $mlname = $request->get('mlname');
            $mmname = $request->filled('mmname') ? $request->get('mmname') : '';
            $msuffix = $request->filled('msuffix') ? $request->get('msuffix') : '';

            $motherfullname = implode(' ', array_filter([$mlname, $mfname, $mmname, $msuffix]));

            $gfname = $request->get('gfname');
            $glname = $request->get('glname');
            $gmname = $request->filled('gmname') ? $request->get('gmname') : '';
            $gsuffix = $request->filled('gsuffix') ? $request->get('gsuffix') : '';

            $guardianfullname = implode(' ', array_filter([$glname, $gfname, $gmname, $gsuffix]));

            $studid = DB::table('studinfo')->insertGetId([
                'lrn' => strtoupper($request->get('lrn')),
                'lastname' => strtoupper($request->get('last_name')),
                'firstname' => strtoupper($request->get('first_name')),
                'middlename' => strtoupper($request->get('middle_name')),
                'street' => strtoupper($request->get('street')),
                'barangay' => strtoupper($request->get('barangay')),
                'city' => strtoupper($request->get('city')),
                'province' => strtoupper($request->get('province')),
                'userid' => 0,
                'suffix' => strtoupper($request->get('suffix')),
                'gender' => strtoupper($request->get('gender')),
                'dob' => $request->get('dob'),
                'contactno' => str_replace('-', '', $request->get('contact_number')),
                'mothername' => $motherfullname,
                // 'moccupation' => $request->get('moccupaton'),
                'foccupation' => strtoupper($request->get('foccupation')),
                'moccupation' => strtoupper($request->get('moccupation')),
                'goccupation' => strtoupper($request->get('goccupation')),
                'guardianrelation' => strtoupper($request->get('relation')),

                'mcontactno' => str_replace('-', '', $request->get('mcontactno')),
                'fathername' => $fatherfullname,
                // 'foccupation' => $request->get('foccupation'),
                'fcontactno' => str_replace('-', '', $request->get('fcontactno')),
                'guardianname' => $guardianfullname,
                'gcontactno' => str_replace('-', '', $request->get('gcontactno')),
                // 'guardianrelation' => $request->get('relation'),
                'semail' => $request->get('email'),

                'pob' => $request->get('pobirth'),
                'mtid' => $request->get('motherTongue'),
                'egid' => $request->get('ethnicity'),
                'religionid' => $request->get('religion'),
                'religionname' => DB::table('religion')->where('id', $request->get('religion'))->value('religionname'),
                'bloodtype' => $request->get('bloodtype'),

                'studtype' => $studtype,
                'levelid' => $request->get('gradelevelid'),
                'createddatetime' => Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss'),
                'deleted' => '0',
                'ismothernum' => $isMother,
                'isfathernum' => $isFather,
                'isguardannum' => $isGuardian,
                'strandid' => $request->get('studstrand'),
                'courseid' => $request->get('courseid'),
                'lastschoolatt' => $request->get('lastschoolatt'),
                'mol' => $request->get('withMOL'),
                'grantee' => $grantee,
                'nationality' => $request->get('nationality'),
                'preEnrolled' => 1
            ]);

            DB::table('studinfo_more')->insert([
                'studid' => $studid,
                'glits' => $request->get('glits'),
                'scn' => $request->get('scn'),
                'cmaosla' => $request->get('cmaosla'),

                'psschoolname' => strtoupper($request->get('psschoolname')),
                'pssy' => $request->get('pssy'),
                'gsschoolname' => strtoupper($request->get('gsschoolname')),
                'gssy' => $request->get('gssy'),
                'jhsschoolname' => strtoupper($request->get('jhsschoolname')),
                'jhssy' => $request->get('jhssy'),
                'shsschoolname' => strtoupper($request->get('shsschoolname')),
                'shsstrand' => strtoupper($request->get('shsstrand')),
                'shssy' => $request->get('shssy'),
                'collegeschoolname' => strtoupper($request->get('collegeschoolname')),
                'collegecourse' => strtoupper($request->get('collegecourse')),
                'collegesy' => $request->get('collegesy'),

                'psschooltype' => $request->get('psschooltype'),
                'gsschooltype' => $request->get('gsschooltype'),
                'jhsschooltype' => $request->get('jhsschooltype'),
                'shsschooltype' => $request->get('shsschooltype'),
                'collegeschooltype' => $request->get('collegeschooltype'),

                'ffname' => $request->get('ffname'),
                'fmname' => $request->get('fmname'),
                'flname' => $request->get('flname'),
                'fsuffix' => $request->get('fsuffix'),
                // 'fcontactno' => $request->get('fcontactno'),
                // 'fcontactno' => str_replace('-', '', $request->get('fcontactno')),
                'fha' => $request->get('fha'),

                'mfname' => $request->get('mfname'),
                'mmname' => $request->get('mmname'),
                'mlname' => $request->get('mlname'),
                'msuffix' => $request->get('msuffix'),
                // 'mcontactno' => $request->get('mcontactno'),
                // 'mcontactno' => str_replace('-', '', $request->get('mcontactno')),
                'mha' => $request->get('mha'),

                'mothermaiden' => strtoupper($request->get('maidenname')),

                'gfname' => $request->get('gfname'),
                'gmname' => $request->get('gmname'),
                'glname' => $request->get('glname'),
                'gsuffix' => $request->get('gsuffix'),
                // 'gcontactno' => str_replace('-', '', $request->get('gcontactno')),
                'gha' => $request->get('gha'),

                // 'foccupation' => strtoupper($request->get('foccupation')),
                // 'moccupation' => strtoupper($request->get('moccupation')),
                // 'guardianrelation' => strtoupper($request->get('relation')),

                'fea' => $request->get('fea'),
                'mea' => $request->get('mea'),
                'gea' => $request->get('gea'),

                'fmi' => $request->get('fmi'),
                'mmi' => $request->get('mmi'),
                'gmi' => $request->get('gmi'),

                'fosoi' => $request->get('fosoi'),
                'mosoi' => $request->get('mosoi'),
                'gosoi' => $request->get('gosoi'),

                'fethnicity' => $request->get('fethnicity'),
                'methnicity' => $request->get('methnicity'),
                'gethnicity' => $request->get('gethnicity'),

                // 'bmi' => $request->get('bmi'),
                // 'height' => $request->get('height'),
                // 'weight' => $request->get('weight'),

                // ADDITIONAL INFO
                // 'placeofbirth' => $placeofbirth,
                // 'nativename' => $nativename,
                // 'languagefluent' => $languagefluent,
                // 'birthorder' => $birthorder,
                // 'numberofsister' => $numberofsister,
                // 'numberofbrother' => $numberofbrother,
                // 'hasbrotherinschool' => $hasbrotherinschool,
                // 'hasbrothergradelevel' => $hasbrothergradelevel,
                // 'placeofbaptism' => $placeofbaptism,
                // 'dateofbaptism' => $dateofbaptism,
                // 'churchofbaptism' => $churchofbaptism,
                // 'howdidyoulearnourschool' => $howdidyoulearnourschool,
                // 'whystudyinourschool' => $whystudyinourschool,

                // 'listextracurricular' => $listextracurricular,
                // 'listawards' => $listawards,
                // 'listcommunity' => $listcommunity,
                // 'reasonfordismissal' => $reasonfordismissal,
                // 'reasonforrepeating' => $reasonforrepeating,
                // 'reasonforprobation' => $reasonforprobation,


            ]);



            $request->request->add(['studid' => $studid]);

            // \App\Http\Controllers\SuperAdminController\StudentMedInfoController::create($request);

            $vaccine = DB::table('vaccine_type')
                ->where('deleted', 0)
                ->select(
                    'id',
                    'vaccinename',
                    'vaccinename as text'
                )
                ->orderBy('vaccinename')
                ->get();

            $vacc_type_1st = collect($vaccine)->where('id', $request->get('vacc_type_1st'))->first();
            $vacc_type_2nd = collect($vaccine)->where('id', $request->get('vacc_type_2nd'))->first();
            $vacc_type_booster = collect($vaccine)->where('id', $request->get('vacc_type_booster'))->first();

            DB::table('apmc_midinfo')
                ->insert([
                    'studid' => $studid,
                    'vacc' => $request->get('vacc'),

                    'vacc_type_id' => $request->get('vacc_type_1st'),
                    'vacc_type_2nd_id' => $request->get('vacc_type_2nd'),
                    'booster_type_id' => $request->get('vacc_type_booster'),

                    'dose_date_booster' => $request->get('dose_date_booster'),



                    'vacc_type' => isset($vacc_type_1st->text) ? $vacc_type_1st->text : null,
                    'vacc_type_2nd' => isset($vacc_type_2nd->text) ? $vacc_type_2nd->text : null,
                    'vacc_type_booster' => isset($vacc_type_booster->text) ? $vacc_type_booster->text : null,

                    'vacc_card_id' => $request->get('vacc_card_id'),
                    'dose_date_1st' => $request->get('dose_date_1st'),
                    'dose_date_2nd' => $request->get('dose_date_2nd'),
                    'philhealth' => $request->get('philhealth'),
                    'bloodtype' => $request->get('bloodtype'),
                    'allergies' => $request->get('allergies'),
                    'med_prog' => $request->get('med_prog'),
                    'allergy_to_med' => $request->get('allergy_to_med'),
                    'med_his' => $request->get('med_his'),
                    'other_med_info' => $request->get('other_med_info'),
                    'createddatetime' => Carbon::now('Asia/Manila')
                ]);

            $acadprog = DB::table('gradelevel')
                ->where('id', $request->get('gradelevelid'))
                ->where('deleted', 0)
                ->first()
                ->acadprogid;


            $sid = \App\RegistrarModel::idprefix($acadprog, $studid);

            $upd = db::table('studinfo')
                ->where('id', $studid)
                ->take(1)
                ->update([
                    'sid' => $sid
                ]);

            $message = strtoupper($request->get('first_name')) . ' ' . strtoupper($request->get('last_name')) . ' submitted a pre-enrollment / pre-registration.';
            $link = '/registrar/preenrolled';
            $createdby = 0;
            $registrar = DB::table('teacher')->where('usertypeid', 3)->select('userid')->get();

            foreach ($registrar as $item) {
                \App\Models\Notification\NotificationProccess::notification_create($message, $link, $item->userid, $createdby);
            }

            $syid = $request->get('input_syid');
            $semid = $request->get('input_semid');

            $gradelevel = $request->get('gradelevelid');
            $admission_type = $request->get('input_acadprog');
            \App\Models\Student\PreRegistration\PreRegistrationProccess::submit_preregistration($studid, $syid, $semid, $gradelevel, $admission_type);
            self::upload_requirements($sid, $request, $syid, $semid);

            $sname = $request->get('first_name') . ' ' . $request->get('middle_name') . ' ' . $request->get('last_name') . ' ' . $request->get('suffix');

            $studuser = db::table('users')
                ->insertGetId([
                    'name' => $sname,
                    'email' => 'S' . $sid,
                    'type' => 7,
                    'password' => Hash::make('123456')
                ]);

            $studpword = \App\RegistrarModel::generatepassword($studuser);

            $putUserid = db::table('studinfo')
                ->where('id', $studid)
                ->update([
                    'userid' => $studuser,
                    'updateddatetime' => \App\RegistrarModel::getServerDateTime(),
                ]);


            if ($request->get('input_setup_type') == 2) {

                DB::table('earlybirds')
                    ->insert([
                        'studid' => $studid,
                        'syid' => $request->get('input_syid'),
                        'semid' => $request->get('input_semid'),
                        'levelid' => $request->get('gradelevelid'),
                        'strandid' => $request->get('studstrand'),
                        'courseid' => $request->get('courseid'),
                        'createddatetime' => Carbon::now('Asia/Manila')
                    ]);

            }

            if ($schoolinfo->withMOL == 1) {
                DB::table('modeoflearning_student')
                    ->insert([
                        'studid' => $studid,
                        'mol' => $request->get('withMOL'),
                        'syid' => $request->get('input_syid'),
                        'createddatetime' => Carbon::now('Asia/Manila'),
                        'createdby' => $studuser,
                    ]);
            }

            DB::table('student_updateinformation')
                ->insert([
                    'studid' => $studid,
                    'createddatetime' => Carbon::now('Asia/Manila'),
                    'createdby' => $studuser,
                    'syid' => $request->get('input_syid'),
                    'semid' => $request->get('input_semid')
                ]);


            return redirect('/preregistration/get/qcode/' . $sid . '/' . $request->get('last_name') . ', ' . $request->get('first_name') . '/' . 'Pre-registered');

        }

    }

    public static function upload_requirements($queuecode = null, $request = null, $syid = null, $semid = null)
    {

        $extension = 'png';

        foreach (DB::table('preregistrationreqlist')->get() as $item) {
            if ($request->has('req' . $item->id) != null) {
                $urlFolder = str_replace('http://', '', $request->root());
                $urlFolder = str_replace('https://', '', $urlFolder);
                if (!File::exists(public_path() . 'preregrequirements/' . $queuecode . '/')) {
                    $path = public_path('preregrequirements/' . $queuecode . '');
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }
                }
                if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/preregrequirements/' . $queuecode . '/')) {
                    $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/preregrequirements/' . $queuecode . '/';
                    if (!File::isDirectory($cloudpath)) {
                        File::makeDirectory($cloudpath, 0777, true, true);
                    }
                }
            }
            if ($request->has('req' . $item->id)) {
                $img = Image::make($request->file('req' . $item->id)->path());
                $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');
                $destinationPath = public_path('preregrequirements/' . $queuecode . '/' . 'requirement' . $item->id . '-' . $time . '.' . $extension);
                $clouddestinationPath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . 'preregrequirements/' . $queuecode . '/' . 'requirement' . $item->id . '-' . $time . '.' . $extension;
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath);
                $img->save($clouddestinationPath);
                $datetime = Carbon::now('Asia/Manila');
                DB::table('preregistrationrequirements')
                    ->insert([
                        'picurl' => 'preregrequirements/requirement' . $item->id . '-' . $time . '.' . $extension,
                        'qcode' => $queuecode,
                        'preregreqtype' => $item->id,
                        'createddatetime' => $datetime,
                        'syid' => $syid,
                        'semid' => $semid,
                    ]);

            }
        }


    }

    public function getqcode($qcode, $name)
    {

        $queuecode = array((object) ['queing_code' => $qcode]);

        $users = DB::table('users')
            ->where('email', 'like', '%' . $qcode . '%')
            ->where('deleted', 0)
            ->first();

        $explodedname = explode(', ', $name);

        $studinfo = DB::table('studinfo')
            ->where('sid', $qcode)
            ->where('lastname', $explodedname[0])
            ->where('firstname', $explodedname[1])
            ->first();

        if (!isset($studinfo->id)) {
            return "No record found";
        }


        return view('registrar.preregistrationgetcode')
            ->with('fullname', $name)
            ->with('user', $users)
            ->with('code', $queuecode);

    }


    public function preenrollmentinfo($name, $levelname, $status = null)
    {

        return view('othertransactions.preenrollment.prerenrollmentsubmitted')
            ->with('fullname', $name)
            ->with('status', $status)
            ->with('levelname', $levelname);

    }

    public function early_enrollment_submit(Request $request)
    {

        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = $request->get('levelid');

        $gradelevel_to_enroll = DB::table('gradelevel')
            ->where('id', $levelid)
            ->select('sortid')
            ->where('deleted', 0)
            ->first()
            ->sortid;

        $studinfo = DB::table('studinfo')
            ->where('id', $studid)
            ->where('deleted', 0)
            ->first();

        if ($studinfo->studstatus == 1) {

            $gradelevel_to_enroll = DB::table('gradelevel')
                ->where('deleted', 0)
                ->select('sortid', 'id')
                ->where('sortid', '>', $gradelevel_to_enroll)
                ->first()
                ->id;
        }

        try {

            $check_if_exist = DB::table('earlybirds')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->where('levelid', $levelid)
                ->count();

            if ($check_if_exist == 0) {

                DB::table('earlybirds')
                    ->insert([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'levelid' => $gradelevel_to_enroll,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

            }

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Submitted Successfully'
                ]
            );

        } catch (\Exception $e) {

            DB::table('zerrorlogs')
                ->insert([
                    'error' => $e,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            return array(
                (object) [
                    'status' => 0,
                    'data' => 'Something went wrong!'
                ]
            );
        }

    }


    public function pre_enrollment_submit(Request $request)
    {

        $studid = $request->get('studid');

        try {

            DB::table('studinfo')
                ->where('id', $studid)
                ->take(1)
                ->update([
                    'preEnrolled' => 1
                ]);

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Submitted Successfully!'
                ]
            );

        } catch (\Exception $e) {

            DB::table('zerrorlogs')
                ->insert([
                    'error' => $e,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            return array(
                (object) [
                    'status' => 0,
                    'data' => 'Something went wrong!'
                ]
            );

        }




    }

}
