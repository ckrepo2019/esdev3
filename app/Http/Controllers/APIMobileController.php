<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use File;
use Image;
use Session;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Finance\FinanceUtilityModel;

class APIMobileController extends Controller
{

    public static function api_schoolinfo(Request $request){
        return DB::table('schoolinfo')->get();
    }

    public static function attendance_setup_list($syid = null, $levelid = null){

        $attendance_setup = DB::table('studattendance_setup')
                          ->where('deleted',0);

        if($syid != null){
              $attendance_setup = $attendance_setup->where('syid',$syid);
        }
        if($levelid != null){
              $attendance_setup = $attendance_setup->where('levelid',$levelid);
        }else{
              $attendance_setup = $attendance_setup->whereNull('levelid');
        }

        $attendance_setup = $attendance_setup
                                ->join('sy',function($join){
                                      $join->on('studattendance_setup.syid','=','sy.id');
                                })
                                ->select(
                                      'studattendance_setup.id',
                                      'studattendance_setup.syid',
                                      'studattendance_setup.month',
                                      'studattendance_setup.days',
                                      'studattendance_setup.year',
                                      'sydesc',
                                      'sort'
                                )
                                ->get();

        foreach( $attendance_setup as $item){
              $item->monthdesc = \Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM');
        }

        return $attendance_setup;

  }


    public static function api_sf9attendance(Request $request){

        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $levelid = $request->get('gradelevel');
        $sectionid = $request->get('sectionid');
        $strand = $request->get('strand');


        $attendance_setup = self::attendance_setup_list($syid,$levelid);
        $attendance_setup = collect($attendance_setup)->sortBy('sort')->values();

        // return $attendance_setup;

        foreach( $attendance_setup as $item){

            if($item->levelid == 14 || $item->levelid == 15){

                $sf2_setup = DB::table('sf2_setup')
                                ->where('month',$item->month)
                                ->where('year',$item->year)
                                ->where('sectionid',$sectionid)
                                ->where('sf2_setup.deleted',0)
                                ->where('strandid',$strand)
                                ->join('sf2_setupdates',function($join){
                                    $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                    $join->where('sf2_setupdates.deleted',0);
                                })
                                ->select('dates')
                                ->get();

                if(count($sf2_setup) == 0){

                    $sf2_setup = DB::table('sf2_setup')
                                ->where('month',$item->month)
                                ->where('year',$item->year)
                                ->where('sectionid',$sectionid)
                                ->where('sf2_setup.deleted',0)
                                ->where('strandid',$strand)
                                ->join('sf2_setupdates',function($join){
                                    $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                    $join->where('sf2_setupdates.deleted',0);
                                })
                                ->select('dates')
                                ->get();

                    }
            }else{

                $sf2_setup = DB::table('sf2_setup')
                                ->where('month',$item->month)
                                ->where('year',$item->year)
                                ->where('sectionid',$sectionid)
                                ->where('sf2_setup.deleted',0)
                                ->join('sf2_setupdates',function($join){
                                    $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                    $join->where('sf2_setupdates.deleted',0);
                                })
                                ->select('dates')
                                ->get();

                if(count($sf2_setup) == 0){

                    $sf2_setup = DB::table('sf2_setup')
                                ->where('month',$item->month)
                                ->where('year',$item->year)
                                ->where('sectionid',$sectionid)
                                ->where('sf2_setup.deleted',0)
                                ->join('sf2_setupdates',function($join){
                                    $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                    $join->where('sf2_setupdates.deleted',0);
                                })
                                ->select('dates')
                                ->get();

                }


            }



            $temp_days = array();

            foreach($sf2_setup as $sf2_setup_item){
                array_push($temp_days,$sf2_setup_item->dates);
            }

            $student_attendance = DB::table('studattendance')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->whereIn('tdate',$temp_days)
                                    // ->where('syid',$syid)
                                    ->distinct('tdate')
                                    ->distinct()
                                    ->select([
                                        'present',
                                        'absent',
                                        'tardy',
                                        'cc',
                                        'tdate'
                                    ])
                                    ->get();

            $student_attendance = collect($student_attendance)->unique('tdate')->values();

            $item->present = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count();
            $item->absent = collect($student_attendance)->where('absent',1)->count();

        }

        return $attendance_setup;

    }


    public static function student_attendance(Request $request)
    {

        $studid = $request->get('studid');
		$levelid = $request->get('levelid');
		$syid = $request->get('syid');
		$semid = $request->get('semid');

        if($levelid >= 17 && $levelid <= 21){

            $studentattendance = array();

            $attendance = DB::table('college_attendance')
            ->where('deleted',0)
            ->where('studid',$studid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->get();

            foreach($attendance as $attndc){

                for($i=1; $i < 33; $i++){

                $colval = 'day'.$i;

                if($attndc->$colval != 0){

                    if($attndc->monthid <= 9){

                        $month = '0'.$attndc->monthid;
                    }else{
                        $month = $attndc->monthid;
                    }

                    if($i <= 9){

                        $day = '0'.$i;
                    }else{
                        $day = $i;
                    }

                    array_push($studentattendance, (object)[
                        'tdate'=> $attndc->yearid.'-'.$month.'-'.$day
                    ]);
                }
            }
            }

        }else{

            if(isset($semid)){

                $studentattendance = DB::table('studattendance')
                ->where('deleted',0)
                ->where('studid',$studid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->get();

            }else{

                $studentattendance = DB::table('studattendance')
                ->where('deleted',0)
                ->where('studid',$studid)
                ->where('syid', $syid)
                ->get();
            }


        }

        return $studentattendance;
    }

    public static function observedvalues_list(
        $description = null ,
        $sort = null ,
        $group = null ,
        $syid = null ,
        $gradelevel = null
  ){

        $list = DB::table('grading_system')
                    ->where('grading_system.type',3)
                    ->where('grading_system.specification',2)
                    ->where('grading_system.deleted',0);

        if($gradelevel != null){
              $list = $list->where('levelid',$gradelevel);
        }
        if($syid != null){
              $list = $list->where('syid',$syid);
        }

        $list = $list->join('grading_system_detail',function($join){
                                $join->on('grading_system.id','=','grading_system_detail.headerid');
                                $join->where('grading_system_detail.deleted',0);
                          })
                    ->select(
                          'grading_system_detail.description',
                          'grading_system_detail.id',
                          'grading_system_detail.group',
                          'grading_system_detail.sort',
                          'grading_system.syid',
                          'grading_system.levelid',
                          'headerid',
                          'value'
                    )
                    ->orderBy('grading_system_detail.sort')
                    ->get();

        return $list;

  }

  public static function observedvalues_list_v1(){
    $list = DB::table('grading_system')
                ->where('grading_system.type',3)
                ->where('grading_system.specification',2)
                ->where('grading_system.deleted',0)
                ->where('grading_system.syid',null)
                ->where('grading_system.levelid',null)
                ->join('grading_system_detail',function($join){
                            $join->on('grading_system.id','=','grading_system_detail.headerid');
                            $join->where('grading_system_detail.deleted',0);
                      })
                ->select(
                      'grading_system_detail.description',
                      'grading_system_detail.id',
                      'grading_system_detail.group',
                      'grading_system_detail.sort',
                      'grading_system.syid',
                      'grading_system.levelid',
                      'headerid',
                      'value'
                )
                ->orderBy('grading_system_detail.sort')
                ->get();
    return $list;
}

    public static function student_observedvalues(Request $request)
    {

        $syid = $request->get('syid');
        $studid = $request->get('studid');
        $gradelevel = $request->get('levelid');

        $checkGrades = [];
        $rv = [];
        $ob = [];

        $ob = self::observedvalues_list( null,null,null,$syid,$gradelevel);


        $checkGrades = DB::table('grading_system_grades_cv')
                ->where('grading_system_grades_cv.deleted',0)
            ->where('grading_system_grades_cv.studid',$studid)
            ->where('grading_system_grades_cv.syid',$syid)
            ->select(
                'grading_system_grades_cv.gsdid',
                'grading_system_grades_cv.q1eval',
                'grading_system_grades_cv.q2eval',
                'grading_system_grades_cv.q3eval',
                'grading_system_grades_cv.q4eval'
            )
            ->get();

        foreach($ob as $item){
            $item->q1eval = null;
            $item->q2eval = null;
            $item->q3eval = null;
            $item->q4eval = null;
            $obgrades = collect($checkGrades)->where('gsdid',$item->id)->first();

            if(isset($obgrades->gsdid)){
                $item->q1eval = $obgrades->q1eval;
                $item->q2eval = $obgrades->q2eval;
                $item->q3eval = $obgrades->q3eval;
                $item->q4eval = $obgrades->q3eval;
            }
        }

        $checkGrades = $ob;

        if(count($ob) > 0){
            $rv = DB::table('grading_system_ratingvalue')
                ->where('deleted',0)
                ->where('gsid',$ob[0]->headerid)
                ->orderBy('sort')
                ->get();
        }

        return array((object)[
                'grades'=>$checkGrades,
                'ob'=>$ob,
                'rv'=>$rv
            ]);

    }


    public function api_get_events(Request $request)
    {
        $monthName = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $syid = $request->get('syid');

        $schoolInfo = DB::table('schoolinfo')->first();
        $abbreviation = $schoolInfo->abbreviation;

        $calendarTable = (in_array($abbreviation, ['DCC', 'SBC', 'APMC', 'SPCT', 'HCB'])) ? 'schoolcal' : 'school_calendar';


        if ($calendarTable == 'school_calendar') {

            $events = DB::table('school_calendar')
                ->select(
                    'id',
                    'title',
                    'venue',
                    DB::raw("CONCAT(DATE(start), ' ', TIME_FORMAT(stime, '%H:%i:%s')) as startTime"),
                    DB::raw("CONCAT(DATE(end), ' ', TIME_FORMAT(etime, '%H:%i:%s')) as endTime"),
                    DB::raw("CONCAT(DATE_FORMAT(stime, '%h:%i %p'), '-', DATE_FORMAT(etime, '%h:%i %p')) as time")
                )
                ->where('deleted', 0)
                ->where('syid', $syid)
                ->whereIn('type', [0, 1])
                ->get();

                foreach ($events as $event) {
                    $event->time = date('h:i A', strtotime($event->startTime)) . '-' . date('h:i A', strtotime($event->endTime));
                }
        } else {

            $sy = DB::table('sy')->where('id', $syid)->first();

            $events = DB::table('schoolcal')
            ->select(
                'id',
                'description as title',
                'title as venue',
                DB::raw("DATE(datefrom) as startTime"),
                DB::raw("DATE(dateto) as endTime"),
                DB::raw("CONCAT(DATE(datefrom), ' - ', DATE(dateto)) as time")

            )
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->whereIn('type', [13, 14, 15, 17, 19, 20])
            ->get();



        }




        return $events;
    }



    public function api_get_taphistory(Request $request)
    {
		$studid = $request->get('studid');


		$taphistory = DB::table('taphistory')
            ->where('studid', $studid)
            ->get();

        return $taphistory;

    }

    public function api_sysem(Request $request)
    {
        $sy = DB::table('sy')
            ->get();

        $semester = DB::table('semester')
            ->where('deleted', 0)
            ->get();

        return response()->json([
            'sy' => $sy,
            'semester' => $semester,
        ]);
    }

	public function api_get_smsbunker(Request $request)
    {
        $studid = $request->get('studid');

        $smsbunkerstudent = DB::table('smsbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.contactno');
            })
            ->where('studinfo.id', $studid)
            ->select('smsbunker.id', 'studinfo.id as studid', 'smsbunker.pushstatus', 'smsbunker.message', 'smsbunker.receiver', 'smsbunker.createddatetime')
            ->get();


        $smsbunkerparents = DB::table('smsbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                    ->orOn(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                    ->orOn(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');
            })
            ->where('studinfo.id', $studid)
            ->select('smsbunker.id', 'studinfo.id as studid', 'smsbunker.pushstatus', 'smsbunker.message', 'smsbunker.receiver', 'smsbunker.createddatetime')
            ->get();

        $tapbunkerparents = DB::table('tapbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                    ->orOn(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                    ->orOn(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');
            })
            ->where('studinfo.id', $studid)
            ->select('tapbunker.id', 'studinfo.id as studid', 'tapbunker.pushstatus', 'tapbunker.message', 'tapbunker.receiver', 'tapbunker.createddatetime')
            ->get();

        $smsbunkertextblaststudent = DB::table('smsbunkertextblast')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.contactno');
            })
            ->where('studinfo.id', $studid)
            ->select('smsbunkertextblast.id', 'studinfo.id as studid', 'smsbunkertextblast.pushstatus', 'smsbunkertextblast.message', 'smsbunkertextblast.receiver', 'smsbunkertextblast.createddatetime')
            ->get();

        $smsbunkertextblastsparents = DB::table('smsbunkertextblast')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                    ->orOn(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                    ->orOn(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');
            })
            ->where('studinfo.id', $studid)
            ->select('smsbunkertextblast.id', 'studinfo.id as studid', 'smsbunkertextblast.pushstatus', 'smsbunkertextblast.message', 'smsbunkertextblast.receiver', 'smsbunkertextblast.createddatetime')
            ->get();

        return response()->json([
            'smsbunkerstudent' => $smsbunkerstudent,
            'smsbunkerparents' => $smsbunkerparents,
            'tapbunkerparents' => $tapbunkerparents,
            'smsbunkertextblaststudent' => $smsbunkertextblaststudent,
            'smsbunkertextblastsparents' => $smsbunkertextblastsparents
        ]);
    }

	public function api_update_pushstatus(Request $request)
	{
		$studid = $request->get('studid');
		$id = $request->get('id');
		$pushstatus = $request->get('pushstatus');


		$updated = DB::table('smsbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.contactno');
            })
            ->where('studinfo.id', $studid)
			->where('smsbunker.id', $id)
			->update([
				'pushstatus' => $pushstatus,
			]);

        $updatedparents = DB::table('smsbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                        ->orOn(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                        ->orOn(DB::raw("REPLACE(smsbunker.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');
            })
            ->where('studinfo.id', $studid)
			->where('smsbunker.id', $id)
			->update([
				'pushstatus' => $pushstatus,
			]);

        $updatedtapbunker = DB::table('tapbunker')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                        ->orOn(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                        ->orOn(DB::raw("REPLACE(tapbunker.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');
            })
            ->where('studinfo.id', $studid)
			->where('tapbunker.id', $id)
			->update([
				'pushstatus' => $pushstatus,
			]);

        $updatedsmsbunkertextblaststudent = DB::table('smsbunkertextblast')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.contactno');
            })
            ->where('studinfo.id', $studid)
			->where('smsbunkertextblast.id', $id)
			->update([
				'pushstatus' => $pushstatus,
			]);

        $updatedsmsbunkertextblastparents = DB::table('smsbunkertextblast')
            ->join('studinfo', function ($join) {
                $join->on(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.fcontactno')
                        ->orOn(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.mcontactno')
                        ->orOn(DB::raw("REPLACE(smsbunkertextblast.receiver, '+63', '0')"), '=', 'studinfo.gcontactno');

            })
            ->where('studinfo.id', $studid)
			->where('smsbunkertextblast.id', $id)
			->update([
				'pushstatus' => $pushstatus,
			]);

		return response()->json([
            'studentsmsbunker' => $updated > 0,
            'parentssmsbunker' => $updatedparents > 0,
            'parentstapbunker' => $updatedtapbunker > 0,
            'studentsmsbunkertextblast' => $updatedsmsbunkertextblaststudent > 0,
            'parentssmsbunkertextblast' => $updatedsmsbunkertextblastparents > 0
        ]);
	}


	public function api_get_transactions(Request $request)
	{
		 $studid = $request->get('studid');

		$transactions = DB::table('chrngtrans')
		->where('studid', $studid)
		->get();

		return $transactions;
	}

	public function api_get_onlinepaymentoptions (Request $request)
	{
		$onlinepaymentoptions = DB::table('paymenttype')
            ->where('isonline',1)
            ->where('deleted',0)
		    ->get();

        $bank = DB::table('onlinepaymentoptions')
            ->where('paymenttype',3)
            ->get();

        $sy = DB::table('sy')
            ->orderBy('sydesc')
            ->get();

        $semester = DB::table('semester')
            ->get();

        $contact = DB::table('studinfo')
            ->where('id', $request->get('id'))
            ->select('contactno', 'mcontactno', 'fcontactno', 'gcontactno')
            ->get();

        return response()->json([
            'onlinepaymentoptions' => $onlinepaymentoptions,
            'bank' => $bank,
            'sy' => $sy,
            'semester' => $semester,
            'contact' => $contact,
        ]);

	}


    public function api_get_onlinepayments (Request $request) {
        $sid = $request->get('sid');

        $onlinepayments = DB::table('onlinepayments')
            ->where('queingcode', $sid)
            ->join('paymenttype', 'onlinepayments.paymentType', '=', 'paymenttype.id')
            ->select('onlinepayments.*', 'paymenttype.description')
            ->get();

        return $onlinepayments;
    }


    public static function api_send_payment(Request $request) {
        $studid = $request->get('studid');
        $payment_type = $request->get('paymentType');
        $amount = str_replace(',', '', $request->get('amount'));
        $transDate = $request->get('transDate');
        $refNum = $request->get('refNum');
        $contact = $request->get('opcontact');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $file = $request->file('recieptImage');

        $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');
        $extension = $file->getClientOriginalExtension();

        $studsid = DB::table('studinfo')
            ->where('deleted', 0)
            ->where('id', $studid)
            ->first()
            ->sid;

        $check_refnum = DB::table('onlinepayments')
            ->where('refNum', $refNum)
            ->count();

        if ($check_refnum > 0) {
            return response()->json([
                'status' => 0,
                'message' => 'Reference Number already exists'
            ]);
        }


        DB::table('onlinepayments')
            ->insert([
                'queingcode' => $studsid,
                'paymentType' => $payment_type,
                'amount' => $amount,
                'isapproved' => 0,
                'TransDate' => $transDate,
                'refNum' => $refNum,
                'paymentDate' => \Carbon\Carbon::now('Asia/Manila'),
                'semid' => $semid,
                'syid' => $syid,
                'picUrl' => 'onlinepayments/' . $studsid . '/' . $studsid . '-payment-' . $time . '.' . $extension,
                'opcontact' => str_replace('-', '', $contact)
            ]);


        // $localPath = public_path('onlinepayments/' . $studsid);
        // if (!File::exists($localPath)) {
        //     File::makeDirectory($localPath, 0777, true, true);
        // }


        // $destinationPath = $localPath . '/' . $studsid . '-payment-' . $time . '.' . $extension;
        // $img = Image::make($file->path());
        // $img->resize(1000, 1000, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save($destinationPath);

        $urlFolder = str_replace('http://','',$request->root());
        $urlFolder = str_replace('https://','',$urlFolder);

        if (! File::exists(public_path().'onlinepayments/'.$studsid)) {

            $path = public_path('onlinepayments/'.$studsid);

            if(!File::isDirectory($path)){

                File::makeDirectory($path, 0777, true, true);
            }

        }

        if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studsid)) {
            $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studsid;
            if(!File::isDirectory($cloudpath)){
                File::makeDirectory($cloudpath, 0777, true, true);
            }
        }


        $img = Image::make($file->path());

        $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studsid.'/'.$studsid.'-payment-'.$time.'.'.$extension;

        $img->resize(1000, 1000, function ($constraint) {
            $constraint->aspectRatio();
        })->save($clouddestinationPath);

        $destinationPath = public_path('onlinepayments/'.$studsid.'/'.$studsid.'-payment-'.$time.'.'.$extension);

        $img->save($destinationPath);

        return array((object)[
            'status'=>1,
        ]);


    }

    public static function collegestudentsched_plot($studid = null, $syid = null, $semid = null, $all = false)
    {

        $schoolInfo = DB::table('schoolinfo')->first();
        $abbreviation = $schoolInfo->abbreviation;

        if (!in_array($abbreviation, ['DCC'])) {
          try {

                $subjects = DB::table('college_studsched')
                      ->join('college_classsched', function ($join) use ($syid, $semid) {
                            $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                            $join->where('college_classsched.deleted', 0);
                            $join->where('college_classsched.syid', $syid);
                            $join->where('college_classsched.semesterID', $semid);
                      })
                      ->join('college_prospectus', function ($join) {
                            $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                            $join->where('college_prospectus.deleted', 0);
                      })
                      ->join('college_sections', function ($join) {
                            $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                            $join->where('college_sections.deleted', 0);
                      });

                if (!$all) {
                      $subjects = $subjects->where('college_studsched.schedstatus', '!=', 'DROPPED');
                }

                $subjects = $subjects->where('college_studsched.deleted', 0)
                      ->where('college_studsched.studid', $studid)
                      ->select(
                            'lecunits',
                            'labunits',
                            'college_prospectus.subjectID as main_subjid',
                            'college_classsched.*',
                            'schedid',
                            'subjDesc',
                            'subjCode',
                            'sectionDesc',
                            'schedstatus'
                      )
                      ->get();

                foreach ($subjects as $item) {

                      $item->units = $item->lecunits + $item->labunits;

                      $sched = DB::table('college_scheddetail')
                            ->where('college_scheddetail.headerid', $item->id)
                            ->where('college_scheddetail.deleted', 0)
                            ->leftJoin('rooms', function ($join) {
                                  $join->on('college_scheddetail.roomid', '=', 'rooms.id');
                                  $join->where('rooms.deleted', 0);
                            })
                            ->join('days', function ($join) {
                                  $join->on('college_scheddetail.day', '=', 'days.id');
                            })
                            ->select(
                                  'day',
                                  'roomid',
                                  'college_scheddetail.id as detailid',
                                  'roomname',
                                  'stime',
                                  'etime',
                                  'days.description',
                                  'schedotherclass'
                            )
                            ->get();

                      $item->teacher = null;
                      $item->teacherid = null;

                      if (isset($item->teacherID)) {
                            $temp_teacher = DB::table('teacher')
                                  ->where('id', $item->teacherID)
                                  ->first();
                            $item->teacher = $temp_teacher->firstname . ' ' . $temp_teacher->middlename . ' ' . $temp_teacher->lastname;
                            $item->teacherid = $temp_teacher->tid;
                      }



                      foreach ($sched as $sched_item) {
                            $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                      }

                      $starting = collect($sched)->groupBy('time');

                      $sched_list = array();
                      $sched_count = 1;

                      foreach ($starting as $sched_item) {

                            $dayString = '';
                            $days = array();
                            $schedstat = '';

                            foreach ($sched_item as $new_item) {
                                  $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                                  $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                                  $dayString .= substr($new_item->description, 0, 3) . ' / ';
                                  $detailid = $new_item->detailid;
                                  $roomname = $new_item->roomname;
                                  $roomid = $new_item->roomid;
                                  $time = $new_item->time;
                                  // $schedstat =  $item->schedstatus;
                                  $schedotherclass = $new_item->schedotherclass;
                                  array_push($days, $new_item->day);
                            }

                            $dayString = substr($dayString, 0, -2);

                            array_push($sched_list, (object) [
                                  'day' => $dayString,
                                  'start' => $start,
                                  'end' => $end,
                                  'roomid',
                                  'detailid' => $detailid,
                                  'roomname' => $roomname,
                                  'roomid' => $roomid,
                                  // 'teacher'=>$teacher,
                                  // 'tid'=>$tid,
                                  // 'teacherid'=>$teacherid,
                                  'sched_count' => $sched_count,
                                  // 'schedstat'=>$schedstat,
                                  'time' => $time,
                                  'days' => $days,
                                  'classification' => $schedotherclass
                            ]);


                            $sched_count += 1;

                      }
                      $item->schedule = $sched_list;


                }

                return array(
                      (object) [
                            'status' => 1,
                            'data' => 'Successfull.',
                            'info' => $subjects
                      ]
                );

          } catch (\Exception $e) {
                return self::store_error($e);
          }

        } else {

            try {

                $subjects = DB::table('college_studsched')
                      ->join('college_classsched', function ($join) use ($syid, $semid) {
                            $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                            $join->where('college_classsched.deleted', 0);
                            $join->where('college_classsched.syid', $syid);
                            $join->where('college_classsched.semesterID', $semid);
                      })
                      ->join('college_prospectus', function ($join) {
                            $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                            $join->where('college_prospectus.deleted', 0);
                      })
                      ->join('college_sections', function ($join) {
                            $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                            $join->where('college_sections.deleted', 0);
                      });

                if (!$all) {
                      $subjects = $subjects->where('college_studsched.schedstatus', '!=', 'DROPPED');
                }

                $subjects = $subjects->where('college_studsched.deleted', 0)
                      ->where('college_studsched.studid', $studid)
                      ->select(
                            'lecunits',
                            'labunits',
                            'college_prospectus.subjectID as main_subjid',
                            'college_classsched.*',
                            'schedid',
                            'subjDesc',
                            'subjCode',
                            'sectionDesc',
                            'schedstatus'
                      )
                      ->get();

                foreach ($subjects as $item) {

                      $item->units = $item->lecunits + $item->labunits;

                      $sched = DB::table('college_scheddetail')
                            ->where('college_scheddetail.headerid', $item->id)
                            ->where('college_scheddetail.deleted', 0)
                            ->leftJoin('rooms', function ($join) {
                                  $join->on('college_scheddetail.roomid', '=', 'rooms.id');
                                  $join->where('rooms.deleted', 0);
                            })
                            ->join('days', function ($join) {
                                  $join->on('college_scheddetail.day', '=', 'days.id');
                            })
                            ->select(
                                  'day',
                                  'roomid',
                                  'college_scheddetail.id as detailid',
                                  'roomname',
                                  'stime',
                                  'etime',
                                  'days.description',
                                  'schedotherclass'
                            )
                            ->get();

                      $item->teacher = null;
                      $item->teacherid = null;

                      if (isset($item->teacherID)) {
                            $temp_teacher = DB::table('teacher')
                                  ->where('id', $item->teacherID)
                                  ->first();
                            $item->teacher = $temp_teacher->firstname . ' ' . $temp_teacher->middlename . ' ' . $temp_teacher->lastname;
                            $item->teacherid = $temp_teacher->tid;
                      }



                      foreach ($sched as $sched_item) {
                            $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A') . ' - ' . \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                      }

                      $starting = collect($sched)->groupBy('time');

                      $sched_list = array();
                      $sched_count = 1;

                      foreach ($starting as $sched_item) {

                            $dayString = '';
                            $days = array();
                            $schedstat = '';

                            foreach ($sched_item as $new_item) {
                                  $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                                  $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                                  $dayString .= substr($new_item->description, 0, 3) . ' / ';
                                  $detailid = $new_item->detailid;
                                  $roomname = $new_item->roomname;
                                  $roomid = $new_item->roomid;
                                  $time = $new_item->time;
                                  // $schedstat =  $item->schedstatus;
                                  $schedotherclass = $new_item->schedotherclass;
                                  array_push($days, $new_item->day);
                            }

                            $dayString = substr($dayString, 0, -2);

                            array_push($sched_list, (object) [
                                  'day' => $dayString,
                                  'start' => $start,
                                  'end' => $end,
                                  'roomid',
                                  'detailid' => $detailid,
                                  'roomname' => $roomname,
                                  'roomid' => $roomid,
                                  // 'teacher'=>$teacher,
                                  // 'tid'=>$tid,
                                  // 'teacherid'=>$teacherid,
                                  'sched_count' => $sched_count,
                                  // 'schedstat'=>$schedstat,
                                  'time' => $time,
                                  'days' => $days,
                                  'classification' => $schedotherclass
                            ]);


                            $sched_count += 1;

                      }
                      $item->schedule = $sched_list;


                }

                return array(
                      (object) [
                            'status' => 1,
                            'data' => 'Successfull.',
                            'info' => $subjects
                      ]
                );

          } catch (\Exception $e) {
                return self::store_error($e);
          }


        }


    }




    public static function college_grade($studid = null,$syid = null,$semid = null){


        $grades = DB::table('college_studentprospectus')
                    ->leftjoin('college_prospectus',function($join){
                        $join->on('college_studentprospectus.prospectusID','=','college_prospectus.id');
                        $join->where('college_prospectus.deleted',0);
                    })
                    ->where('college_studentprospectus.deleted',0)
                    ->where('college_studentprospectus.studid',$studid)
                    ->where('college_studentprospectus.syid',$syid)
                    ->where('college_studentprospectus.semid',$semid)
                    ->select(
                            'college_studentprospectus.*',
                            'subjectID'
                        )
                    ->get();


        $schedule = self::collegestudentsched_plot($studid,$syid,$semid);

        $schedule = collect($schedule[0]->info)->where('schedstatus','!=','DROPPED')->values();

        $temp_grades = array();

        foreach($schedule as $item){
            $check = collect($grades)->where('prospectusID',$item->subjectID)->first();
            $item->finalgrade = null;
            if(isset($check->id)){

                if($check->prelemstatus != 4){
                    $check->prelemgrade = null;
                }
                if($check->midtermstatus != 4){
                    $check->midtermgrade = null;
                }
                if($check->prefistatus != 4){
                    $check->prefigrade = null;
                }
                if($check->finalstatus != 4){
                    $check->finalgrade = null;
                    $check->fg = null;
                    $check->fgremarks = null;
                }

                $check->subjDesc = $item->subjDesc;
                $check->subjCode = $item->subjCode;
                array_push($temp_grades,$check);
            }else{
                $check = collect($grades)->where('subjectID',$item->main_subjid)->first();
                if(isset($check->id)){

                    if($check->prelemstatus != 4){
                        $check->prelemgrade = null;
                    }
                    if($check->midtermstatus != 4){
                        $check->midtermgrade = null;
                    }
                    if($check->prefistatus != 4){
                        $check->prefigrade = null;
                    }
                    if($check->finalstatus != 4){
                        $check->finalgrade = null;
                    }

                    $check->subjDesc = $item->subjDesc;
                    $check->subjCode = $item->subjCode;
                    array_push($temp_grades,$check);
                }else{
                    array_push($temp_grades,$item);
                }
            }
        }

        return $temp_grades;

    }


    public static function get_student_grades_data($studid = null, $syid = null){

        $student_grade = DB::table('grading_system_pgrades')
                          ->where('grading_system_pgrades.studid',$studid)
                          ->where('grading_system_pgrades.syid',$syid)
                          ->where('grading_system_pgrades.deleted',0)
                          ->join('grading_system_detail',function($join){
                                $join->on('grading_system_pgrades.gsdid','=','grading_system_detail.id');
                                $join->where('grading_system_detail.deleted',0);
                          })
                          ->join('grading_system',function($join){
                                $join->on('grading_system_detail.headerid','=','grading_system.id');
                                $join->where('grading_system.deleted',0);
                          })
                          ->select(
                                'grading_system_pgrades.*',
                                'grading_system_detail.group',
                                'grading_system.description'
                          )
                          ->get();

        return $student_grade;


    }

    public static function get_preschool_setup(
        $syid = null
    ){

        $preschool_setup = DB::table('grading_system')
                                ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                                })
                                ->where('acadprogid',2)
                                ->where('levelid',2)
                                ->where('syid',$syid)
                                ->where('grading_system.description','Pre-school Compentencies')
                                ->where('grading_system.deleted',0)
                                ->select(
                                    'grading_system_detail.*'
                                )
                                ->orderBy('sort')
                                ->orderBy('group')
                                ->get();

        return $preschool_setup;

    }


    public static function get_preschool_summary_setup(
        $syid = null,
        Request $request
    ){

        if($syid == null){
            $syid = $request->get('syid');
        }

        $preschool_setup = DB::table('grading_system')
                                ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                                })
                                ->where('acadprogid',2)
                                ->where('levelid',2)
                                ->where('grading_system.description','Prekinder Summary')
                                ->where('syid',$syid)
                                ->where('grading_system.deleted',0)
                                ->select(
                                    'grading_system_detail.*'
                                )
                                ->orderBy('sort')
                                ->orderBy('group')
                                ->get();

        return $preschool_setup;

    }

    public static function get_preschool_cl_setup(
        $syid = null,
        Request $request
    ){

            if($syid == null){
                $syid = $request->get('syid');
            }

            $preschool_setup = DB::table('grading_system')
                                    ->join('grading_system_detail',function($join){
                                        $join->on('grading_system.id','=','grading_system_detail.headerid');
                                        $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->where('acadprogid',2)
                                    ->where('levelid',2)
                                    ->where('grading_system.description','Prekinder CL')
                                    ->where('syid',$syid)
                                    ->where('grading_system.deleted',0)
                                    ->select(
                                        'grading_system_detail.*'
                                    )
                                    ->orderBy('sort')
                                    ->orderBy('group')
                                    ->get();

            return $preschool_setup;

    }

    public static function get_preschool_ageevaldate_setup(
        $syid = null,
        Request $request
  ){

        if($syid == null){
              $syid = $request->get('syid');
        }

        $preschool_setup = DB::table('grading_system')
                                ->join('grading_system_detail',function($join){
                                      $join->on('grading_system.id','=','grading_system_detail.headerid');
                                      $join->where('grading_system_detail.deleted',0);
                                })
                                ->where('acadprogid',2)
                                ->where('levelid',2)
                                ->where('grading_system.description','Perkinder Age/Date')
                                ->where('syid',$syid)
                                ->where('grading_system.deleted',0)
                                ->select(
                                      'grading_system_detail.*'
                                )
                                ->orderBy('sort')
                                ->orderBy('group')
                                ->get();

        return $preschool_setup;

  }

    public static function get_preschool_student_grades_data($studid = null, $syid = null, $detailid = array(), $type = null){


        $student_grade = DB::table('grading_system_pgrades');

        if(count($detailid) > 0){
            $student_grade = $student_grade->whereIn('gsdid', collect($detailid)->pluck('id'));
        }

        $student_grade = $student_grade->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->get();



        if($type == 5){

            $studinfo = DB::table('studinfo')
                            ->where('id',$studid)
                            ->first();

            $enrollment_info = DB::table('enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->select(
                                        'sectionid'
                                    )
                                    ->first();

            $sectioninfo = DB::table('sectiondetail')
                                ->where('sectionid',$enrollment_info->sectionid)
                                ->where('syid',$syid)
                                ->join('teacher',function($join){
                                    $join->on('sectiondetail.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                                })
                                ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'teacherid',
                                    'title',
                                    'acadtitle'

                                )
                                ->get();

            $adviser = '';
            $teacherid = null;
            foreach($sectioninfo as $item){
                $temp_middle = '';
                $temp_suffix = '';
                $temp_title = '';
                $temp_acadtitle = '';
                if(isset($item->middlename)){
                    $temp_middle = $item->middlename[0].'.';
                }
                if(isset($item->title)){
                    $temp_title = $item->title.'. ';
                }
                if(isset($item->suffix)){
                    $temp_suffix = ', '.$item->suffix;
                }
                if(isset($item->acadtitle)){
                    $temp_acadtitle = ', '.$item->acadtitle;
                }
                $adviser = $temp_title.$item->firstname.' '.$temp_middle.' '.$item->lastname.$temp_suffix.$temp_acadtitle;
            }

            $middlename = explode(" ",$studinfo->middlename);
            $temp_middle = '';

            if($middlename != null){
                    foreach ($middlename as $middlename_item) {
                        if(strlen($middlename_item) > 0){
                                $temp_middle .= $middlename_item[0].'. ';
                        }
                    }
            }

            if(isset($studinfo->suffix)){
                    foreach ($middlename as $middlename_item) {
                        if(strlen($middlename_item) > 0){
                                $temp_middle .= $middlename_item[0].'. ';
                        }
                    }
            }

            $principal = null;

            $signatory = DB::table('signatory')
                            ->where('form','report_card')
                            ->where('syid',$syid)
                            ->where('acadprogid',2)
                            ->where('deleted',0)
                            ->select(
                                'name',
                                'title'
                            )
                            ->first();

            if(isset($signatory->name)){
                $principal = $signatory->name;
            }

            $dob = \Carbon\Carbon::create($studinfo->dob)->isoFormat('MMMM DD, YYYY');
            $gender = $studinfo->gender;
            $studname = $studinfo->firstname.' '.$temp_middle.$studinfo->lastname.' '.$studinfo->suffix;

            $studinfodetail = self::studentinfo_list();

            $address = '';
            if(strlen($studinfo->street) > 0){
                $address .= $studinfo->street;
            }
            if(strlen($studinfo->barangay) > 0){
                if($address != ''){
                    $address .=', '.$studinfo->barangay;
                }else{
                    $address .=$studinfo->barangay;
                }
            }
            if(strlen($studinfo->city) > 0){
                if($address != ''){
                    $address .= ', '.$studinfo->city;
                }else{
                    $address .=$studinfo->city;
                }
            }
            if(strlen($studinfo->province) > 0){
                if($address != ''){
                    $address .= ', '.$studinfo->province;
                }else{
                    $address .=$studinfo->province;
                }
            }



            $student_grade = collect($student_grade)->toArray();

            foreach($studinfodetail as $item){
                $get_detail = collect($detailid)->where('description',$item->desc)->first();

                if(isset($get_detail)){

                    $get_grades = collect($student_grade)->where('gsdid',$get_detail->id)->first();

                    if(isset($get_grades)){

                    }else{
                        $tempstring = $item->def;
                        array_push($student_grade,(object)[
                            "syid"=> $syid,
                            "studid"=> $studid,
                            "gsdid"=> $get_detail->id,
                            "q1evaltext"=> $$tempstring
                        ]);
                    }
                }

            }

        }

        return $student_grade;


    }


    public static function get_preschool_preschool_setup(
        $syid = null,
        $levelid = null,
        $headerid = null
    ){


        $preschool_setup = DB::table('grading_system')
                        ->join('grading_system_detail',function($join) use($headerid){
                            $join->on('grading_system.id','=','grading_system_detail.headerid');
                            $join->where('grading_system_detail.deleted',0);
                            $join->where('grading_system_detail.headerid',$headerid);
                        })
                        ->where('acadprogid',2)
                        ->where('levelid',$levelid)
                        ->where('syid',$syid)
                        ->where('grading_system.deleted',0)
                        ->select(
                            'grading_system_detail.*'
                        )
                        ->orderBy('sort')
                        ->orderBy('group')
                        ->get();

        $cellvalue = Db::table('grading_system_celldatail')
                        ->where('deleted',0)
                        ->whereIn('gsdid',collect($preschool_setup)->pluck('id'))
                        ->get();

        foreach($cellvalue as $item){
            $item->q1cellval = $item->q1cellval != null ? $item->q1cellval : '';
            $item->q2cellval = $item->q2cellval != null ? $item->q2cellval : '';
            $item->q3cellval = $item->q3cellval != null ? $item->q3cellval : '';
            $item->q4cellval = $item->q4cellval != null ? $item->q4cellval : '';
        }


        $ratingvalue = DB::table('grading_system_ratingvalue')
                                ->where('gsid',$headerid)
                                ->where('deleted',0)
                                ->get();

        $studinfodetail = self::studentinfo_list();

        foreach($preschool_setup as $item){
            $check = collect( $studinfodetail)->where('desc',$item->description)->first();
            if(isset($check)){
                    $item->withdefault = $check->withdefault;
            }
        }

        return array((object)[
            'detail'=>$preschool_setup,
            'ratingvalue'=>$ratingvalue,
            'cellvalue'=>$cellvalue
        ]);

    }


    public static function get_preschool_setup_age_ajax(
            $syid = null,
            Request $request
    ){

            $syid = $request->get('syid');
            $preschool_setup = DB::table('grading_system')
                                    ->join('grading_system_detail',function($join){
                                        $join->on('grading_system.id','=','grading_system_detail.headerid');
                                        $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->where('acadprogid',2)
                                    ->where('levelid',3)
                                    ->where('syid',3)
                                    ->where('grading_system.description','Kinder Age')
                                    ->where('grading_system.deleted',0)
                                    ->select(
                                        'grading_system_detail.*'
                                    )
                                    ->orderBy('sort')
                                    ->orderBy('group')
                                    ->get();


            return $preschool_setup;

    }

    public static function get_preschool_setup_remarks_ajax(
            $syid = null,
            Request $request
    ){


            $preschool_setup = DB::table('grading_system')
                                    ->join('grading_system_detail',function($join){
                                        $join->on('grading_system.id','=','grading_system_detail.headerid');
                                        $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->where('acadprogid',2)
                                    ->where('levelid',3)
                                    ->where('syid',3)
                                    ->where('grading_system.description','Kinder Comments')
                                    ->where('grading_system.deleted',0)
                                    ->select(
                                        'grading_system_detail.*'
                                    )
                                    ->orderBy('sort')
                                    ->orderBy('group')
                                    ->get();


            return $preschool_setup;

    }







    public static function api_enrollment_reportcard(Request $request){

        $syid = $request->get('syid');
        $studid = $request->get('studid');
        $semid = null;
        $strandid = null;
        $studgrades = array();
        $setup = array();
        $sumsetup = array();
        $clsetup = array();
        $ageevaldate = array();
        $remarks_setup = array();


        $schoolInfo = DB::table('schoolinfo')->first();
        $abbreviation = $schoolInfo->abbreviation;

        $check_enrollment = DB::table('enrolledstud')
                                ->where('studid',$studid)
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->first();

        if(!isset($check_enrollment->id)){
            $check_enrollment = DB::table('sh_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();

            if(isset($check_enrollment->id)){
                $semid = $check_enrollment->semid;
                $strandid = $check_enrollment->strandid;
            }
        }
        if(!isset($check_enrollment->id)){
            $check_enrollment = DB::table('college_enrolledstud')
                ->where('studid',$studid)
                ->where('syid',$syid)
                ->where('deleted',0)
                ->select(
                    '*',
                    'yearLevel as levelid',
                    'sectionID as sectionid'
                )
                ->first();

            if(isset($check_enrollment->id)){
                $semid = $check_enrollment->semid;
            }
        }

        if(!isset($check_enrollment->id)){
            return array((object)[
                'levelid'=>null,
                'grades'=>array()
            ]);
        }

        $levelid = $check_enrollment->levelid;
        $sectionid = $check_enrollment->sectionid;



        if($levelid == 14 || $levelid == 15){


					$temp_grades = array();
					$finalgrade = array();

					$temp_sect = DB::table('sh_enrolledstud')->where('studid',$studid)->where('semid',1)->where('syid',$syid)->where('deleted',0)->first();

					if(isset($temp_sect)){
						$studgrades = \App\Http\Controllers\APIMobileGradesController::sf9_grades( $levelid,$studid,$syid,$temp_sect->strandid,1,$temp_sect->sectionid);

						foreach($studgrades as $item){
							if($item->id == 'G1'){
								if($item->semid == 1){
									 array_push($finalgrade,$item);
								}
							}else{
								if($item->strandid == $temp_sect->strandid){
									array_push($temp_grades,$item);
								}
								if($item->strandid == null){
									array_push($temp_grades,$item);
								}
							}
						}
					}

					$temp_sect = DB::table('sh_enrolledstud')->where('studid',$studid)->where('semid',2)->where('syid',$syid)->where('deleted',0)->first();

					if(!isset($temp_sect)){
						$temp_sect = DB::table('sh_enrolledstud')->where('studid',$studid)->where('semid',1)->where('syid',$syid)->where('deleted',0)->first();
					}



				    if(isset($temp_sect)){


						$studgrades = \App\Http\Controllers\APIMobileGradesController::sf9_grades( $levelid,$studid,$syid,$temp_sect->strandid,2,$temp_sect->sectionid);
						foreach($studgrades as $item){
							if($item->id == 'G1'){
								if($item->semid == 2){
									 array_push($finalgrade,$item);
								}
							}else{
								if($item->strandid == $temp_sect->strandid){
									array_push($temp_grades,$item);
								}
								if($item->strandid == null){
									array_push($temp_grades,$item);
								}
							}
						}
					}
					$studgrades = $temp_grades;

            $studgrades = collect($studgrades)->where('isVisible','1')->sortBy('sortid')->values();
            if(strtoupper($school) == 'BCT'){
                foreach($studgrades as $item){
                    $item->quarter1  = $item->quarter1 < 75 &&  $item->q1status == 4 ? 'NG' :$item->quarter1;
                    $item->quarter2  = $item->quarter2 < 75 &&  $item->q2status == 4 ? 'NG' :$item->quarter2;
                    $item->quarter3  = $item->quarter3 < 75 &&  $item->q3status == 4 ? 'NG' :$item->quarter3;
                    $item->quarter4  = $item->quarter4 < 75 &&  $item->q4status == 4 ? 'NG' :$item->quarter4;
                    $item->q1  = $item->q1 < 75 &&  $item->q1status == 4  ? 'NG' :$item->q1;
                    $item->q2  = $item->q2 < 75 &&  $item->q2status == 4  ? 'NG' :$item->q2;
                    $item->q3  = $item->q3 < 75 &&  $item->q3status == 4  ? 'NG' :$item->q3;
                    $item->q4  = $item->q4 < 75 &&  $item->q4status == 4  ? 'NG' :$item->q4;
                }
            }elseif(strtoupper($school) == 'GBBC'){
                foreach($studgrades as $item){
                    $item->quarter1  = $item->quarter1 < 75 &&  $item->q1status == 4 ? '' :$item->quarter1;
                    $item->quarter2  = $item->quarter2 < 75 &&  $item->q2status == 4 ? '' :$item->quarter2;
                    $item->quarter3  = $item->quarter3 < 75 &&  $item->q3status == 4 ? '' :$item->quarter3;
                    $item->quarter4  = $item->quarter4 < 75 &&  $item->q4status == 4 ? '' :$item->quarter4;
                    $item->q1  = $item->q1 < 75 &&  $item->q1status == 4  ? '' :$item->q1;
                    $item->q2  = $item->q2 < 75 &&  $item->q2status == 4  ? '' :$item->q2;
                    $item->q3  = $item->q3 < 75 &&  $item->q3status == 4  ? '' :$item->q3;
                    $item->q4  = $item->q4 < 75 &&  $item->q4status == 4  ? '' :$item->q4;
                }
            }
        }elseif ($levelid >= 17 && $levelid <= 20) {

            if (!in_array($abbreviation, ['DCC', 'APMC'])) {
            $semid = $request->get('semid');
            $studgrades = self::college_grade($studid,$syid,$semid);

                $setup = DB::table('semester_setup')
                            ->where('sy',$syid)
                            ->where('semester',$semid)
                            ->where('activestatus',1)
                            ->where('deleted',0)
                            ->get();


            return array((object)[
                'levelid'=>$levelid,
                'grades'=>$studgrades,
                'setup'=>$setup,
            ]);

        } else {
            $semid = $request->get('semid');
            $studgrades = self::college_grade($studid,$syid,$semid);

                $setup = DB::table('semester_setup')
                            ->where('sy',$syid)
                            ->where('semester',$semid)
                            ->where('activestatus',1)
                            ->where('deleted',0)
                            ->get();


            return array((object)[
                'levelid'=>$levelid,
                'grades'=>$studgrades,
                'setup'=>$setup,
            ]);
        }



        }
        else{

            if(strtoupper($school) == 'SPCT' && $levelid == 2){

                $studgrades = self::get_student_grades_data($studid,$syid);
                $setup = self::get_preschool_setup($syid);
                $sumsetup = self::get_preschool_summary_setup($syid,$request);
                $clsetup = self::get_preschool_cl_setup($syid,$request);
                $ageevaldate = self::get_preschool_ageevaldate_setup($syid,$request);

                $section = Db::table('enrolledstud')
                                  ->where('studid',$studid)
                                  ->join('sections',function($join){
                                        $join->on('enrolledstud.sectionid','=','sections.id');
                                        $join->where('sections.deleted',0);
                                  })
                                  ->where('enrolledstud.deleted',0)
                                  ->select('sectionname')
                                  ->first();

                foreach($setup as $item){
                      $item->q1grade=0;
                      $item->q2grade=0;
                      $item->q3grade=0;
                      $item->q4grade=0;
                      $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                      if(count($row_grade) > 0){
                            $item->q1grade= $row_grade[0]->q1evaltext == 0 || $row_grade[0]->q1evaltext == null ? 0 : $row_grade[0]->q1evaltext;
                            // $item->q2grade= $row_grade[0]->q2evaltext == 0 || $row_grade[0]->q2evaltext == null ? 0 : $row_grade[0]->q2evaltext;
                            // $item->q3grade= $row_grade[0]->q3evaltext == 0 || $row_grade[0]->q3evaltext == null ? 0 : $row_grade[0]->q3evaltext;
                            // $item->q4grade= $row_grade[0]->q4evaltext == 0 || $row_grade[0]->q4evaltext == null ? 0 : $row_grade[0]->q4evaltextt;
                      }
                }

                foreach($sumsetup as $item){
                      $item->q1grade=0;
                      $item->q2grade=0;
                      $item->q3grade=0;
                      $item->q4grade=0;
                      $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                      if(count($row_grade) > 0){
                            $item->q1grade= $row_grade[0]->q1evaltext == null ? 0 : $row_grade[0]->q1evaltext;
                            // $item->q2grade= $row_grade[0]->q2evaltext == null ? 0 : $row_grade[0]->q2evaltext;
                            // $item->q3grade= $row_grade[0]->q3evaltext == null ? 0 : $row_grade[0]->q3evaltext;
                            // $item->q4grade= $row_grade[0]->q4evaltext == null ? 0 : $row_grade[0]->q4evaltextt;
                      }
                }

                foreach($ageevaldate as $item){
                      $item->q1grade=null;
                      $item->q2grade=null;
                      $item->q3grade=null;
                      $item->q4grade=null;
                      $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                      if(count($row_grade) > 0){

                            if($item->group == 'B'){
                                  $item->q1grade= $row_grade[0]->q1evaltext == null ? null : \Carbon\Carbon::create($row_grade[0]->q1evaltext)->isoFormat('MMMM DD, YYYY');
                                //   $item->q2grade= $row_grade[0]->q2evaltext == null ? null : \Carbon\Carbon::create($row_grade[0]->q2evaltext)->isoFormat('MMMM DD, YYYY');
                                //   $item->q3grade= $row_grade[0]->q3evaltext == null ? null : \Carbon\Carbon::create($row_grade[0]->q3evaltext)->isoFormat('MMMM DD, YYYY');
                                //   $item->q4grade= $row_grade[0]->q4evaltext == null ? null : \Carbon\Carbon::create($row_grade[0]->q4evaltext)->isoFormat('MMMM DD, YYYY');
                            }else{
                                  $item->q1grade= $row_grade[0]->q1evaltext == null ? null : $row_grade[0]->q1evaltext;
                                //   $item->q2grade= $row_grade[0]->q2evaltext == null ? null : $row_grade[0]->q2evaltext;
                                //   $item->q3grade= $row_grade[0]->q3evaltext == null ? null : $row_grade[0]->q3evaltext;
                                //   $item->q4grade= $row_grade[0]->q4evaltext == null ? null : $row_grade[0]->q4evaltextt;
                            }
                      }
                }

                foreach($clsetup as $item){
                      $item->q1grade=null;
                      $item->q2grade=null;
                      $item->q3grade=null;
                      $item->q4grade=null;
                      $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                      if(count($row_grade) > 0){
                            $item->q1grade= $row_grade[0]->q1evaltext == null ? null : $row_grade[0]->q1evaltext;
                            // $item->q2grade= $row_grade[0]->q2evaltext == null ? null : $row_grade[0]->q2evaltext;
                            // $item->q3grade= $row_grade[0]->q3evaltext == null ? null : $row_grade[0]->q3evaltext;
                            // $item->q4grade= $row_grade[0]->q4evaltext == null ? null : $row_grade[0]->q4evaltextt;
                      }
                }

            }else if(strtoupper($school) == 'SPCT' && $levelid == 3){

                $studgrades =  self::get_preschool_student_grades_data($studid,$syid);
                $setup = self::get_preschool_preschool_setup($syid);
                $ageevaldate = self::get_preschool_setup_age_ajax($syid,$request);
                $remarks_setup = self::get_preschool_setup_remarks_ajax($syid,$request);

                foreach($setup as $item){
                    $item->q1grade = '';
                    $item->q2grade = '';
                    $item->q3grade = '';
                    $item->q4grade = '';
                    $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                    if(count($row_grade) > 0){
                        $item->q1grade= $row_grade[0]->q1evaltext == null ? '' : $row_grade[0]->q1evaltext;
                        // $item->q2grade= $row_grade[0]->q2evaltext == null ? '' : $row_grade[0]->q2evaltext;
                        // $item->q3grade= $row_grade[0]->q3evaltext == null ? '' : $row_grade[0]->q3evaltext;
                        // $item->q4grade= $row_grade[0]->q4evaltext == null ? '' : $row_grade[0]->q4evaltext;
                    }
              }

              foreach($ageevaldate as $item){
                    $item->q1grade='';
                    $item->q2grade='';
                    $item->q3grade='';
                    $item->q4grade='';
                    $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                    if(count($row_grade) > 0){
                          $item->q1grade= $row_grade[0]->q1evaltext == null ? '' : $row_grade[0]->q1evaltext;
                        //   $item->q2grade= $row_grade[0]->q2evaltext == null ? '' : $row_grade[0]->q2evaltext;
                        //   $item->q3grade= $row_grade[0]->q3evaltext == null ? '' : $row_grade[0]->q3evaltext;
                        //   $item->q4grade= $row_grade[0]->q4evaltext == null ? '' : $row_grade[0]->q4evaltext;
                    }
              }

              foreach($remarks_setup as $item){
                    $item->q1grade='';
                    $item->q2grade='';
                    $item->q3grade='';
                    $item->q4grade='';
                    $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                    if(count($row_grade) > 0){
                          $item->q1grade= $row_grade[0]->q1evaltext == null ? '' : $row_grade[0]->q1evaltext;
                        //   $item->q2grade= $row_grade[0]->q2evaltext == null ? '' : $row_grade[0]->q2evaltext;
                        //   $item->q3grade= $row_grade[0]->q3evaltext == null ? '' : $row_grade[0]->q3evaltext;
                        //   $item->q4grade= $row_grade[0]->q4evaltext == null ? '' : $row_grade[0]->q4evaltext;
                    }
              }

            }
            else if( ( strtoupper($school) == 'BCT' && $levelid == 3 ) || ( strtoupper($school) == 'BCT' && $levelid == 2 )  || ( strtoupper($school) == 'BCT' && $levelid == 4 )){

                $studgrades =  self::get_preschool_student_grades_data($studid,$syid);
                $setup = self::get_preschool_preschool_setup($syid);
                // $ageevaldate = \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_preschool_setup_age_ajax($syid,$request);
                // $remarks_setup = \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_preschool_setup_remarks_ajax($syid,$request);

                foreach($setup as $item){
                    $item->q1grade = '';
                    $item->q2grade = '';
                    $item->q3grade = '';
                    $item->q4grade = '';
                    $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                    if(count($row_grade) > 0){
                        $item->q1grade= $row_grade[0]->q1evaltext == null ? '' : $row_grade[0]->q1evaltext;
                        // $item->q2grade= $row_grade[0]->q2evaltext == null ? '' : $row_grade[0]->q2evaltext;
                        // $item->q3grade= $row_grade[0]->q3evaltext == null ? '' : $row_grade[0]->q3evaltext;
                        // $item->q4grade= $row_grade[0]->q4evaltext == null ? '' : $row_grade[0]->q4evaltext;
                    }
              }
            }
            else{
                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                if($grading_version->version == 'v2' && $syid == 2){
                    $studgrades = \App\Http\Controllers\APIMobileGradesController::sf9_grades_gv2( $levelid,$studid,$syid,null,null,$sectionid);
                }else{
                    $studgrades = \App\Http\Controllers\APIMobileGradesController::sf9_grades( $levelid,$studid,$syid,null,null,$sectionid);
                }
                $studgrades = collect($studgrades)->where('isVisible','1')->sortBy('sortid')->values();
                if(strtoupper($school) == 'BCT'){
                    foreach($studgrades as $item){
                        $item->quarter1  = $item->quarter1 < 75 &&  $item->q1status == 4 ? 'NG' :$item->quarter1;
                        $item->quarter2  = $item->quarter2 < 75 &&  $item->q2status == 4 ? 'NG' :$item->quarter2;
                        $item->quarter3  = $item->quarter3 < 75 &&  $item->q3status == 4 ? 'NG' :$item->quarter3;
                        $item->quarter4  = $item->quarter4 < 75 &&  $item->q4status == 4 ? 'NG' :$item->quarter4;
                        $item->q1  = $item->q1 < 75 &&  $item->q1status == 4  ? 'NG' :$item->q1;
                        $item->q2  = $item->q2 < 75 &&  $item->q2status == 4  ? 'NG' :$item->q2;
                        $item->q3  = $item->q3 < 75 &&  $item->q3status == 4  ? 'NG' :$item->q3;
                        $item->q4  = $item->q4 < 75 &&  $item->q4status == 4  ? 'NG' :$item->q4;
                    }
                }elseif(strtoupper($school) == 'GBBC'){
                    foreach($studgrades as $item){
                        $item->quarter1  = $item->quarter1 < 75 &&  $item->q1status == 4 ? '' :$item->quarter1;
                        $item->quarter2  = $item->quarter2 < 75 &&  $item->q2status == 4 ? '' :$item->quarter2;
                        $item->quarter3  = $item->quarter3 < 75 &&  $item->q3status == 4 ? '' :$item->quarter3;
                        $item->quarter4  = $item->quarter4 < 75 &&  $item->q4status == 4 ? '' :$item->quarter4;
                        $item->q1  = $item->q1 < 75 &&  $item->q1status == 4  ? '' :$item->q1;
                        $item->q2  = $item->q2 < 75 &&  $item->q2status == 4  ? '' :$item->q2;
                        $item->q3  = $item->q3 < 75 &&  $item->q3status == 4  ? '' :$item->q3;
                        $item->q4  = $item->q4 < 75 &&  $item->q4status == 4  ? '' :$item->q4;
                    }
                }
            }

        }

        return array((object)[
            'levelid'=>$levelid,
            'grades'=>$studgrades,
            'setup'=>$setup,
            'sumsetup'=>$sumsetup,
            'clsetup'=>$clsetup,
            'agevaldate'=>$ageevaldate,
            'remarks_setup'=>$remarks_setup
        ]);

    }

    public static function school_days_attendance_setup_list($syid = null, $levelid = null, $attclass = 0 , $month = null){



        $attendance_setup = DB::table('studattendance_setup')
                          ->where('deleted',0);
        if($syid != null){
              $attendance_setup = $attendance_setup->where('syid',$syid);
        }

        $schoolInfo = DB::table('schoolinfo')->first();
        $abbreviation = $schoolInfo->abbreviation;

        if (in_array($abbreviation, ['DCC', 'SBC', 'APMC', 'SPCT', 'HCB'])) {

            if($levelid != null){
                $attendance_setup = $attendance_setup->where('levelid',$levelid);
            }else{
                $attendance_setup = $attendance_setup->whereNull('levelid');
            }

            if($month != null){
                $attendance_setup = $attendance_setup->where('month',$month);
            }



            $attendance_setup = $attendance_setup
                                    ->join('sy',function($join){
                                        $join->on('studattendance_setup.syid','=','sy.id');
                                    })
                                    ->select(
                                        'studattendance_setup.id',
                                        'studattendance_setup.syid',
                                        'studattendance_setup.month',
                                        'studattendance_setup.days',
                                        'studattendance_setup.year',
                                        'studattendance_setup.semid',
                                        'studattendance_setup.levelid',
                                        'sydesc',
                                        'sort'

                                    )
                                    ->get();




            foreach( $attendance_setup as $item){
                $item->monthdesc = \Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM');

            }
            return $attendance_setup;
    } else {

            if($attclass != null){
                $attendance_setup = $attendance_setup->where('attclass',$attclass);
            }


            if($levelid != null){
                    $attendance_setup = $attendance_setup->where('levelid',$levelid);
            }else{
                    $attendance_setup = $attendance_setup->whereNull('levelid');
            }

            if($month != null){
                    $attendance_setup = $attendance_setup->where('month',$month);
            }



            $attendance_setup = $attendance_setup
                                    ->join('sy',function($join){
                                            $join->on('studattendance_setup.syid','=','sy.id');
                                    })
                                    ->select(
                                            'studattendance_setup.id',
                                            'studattendance_setup.syid',
                                            'studattendance_setup.month',
                                            'studattendance_setup.days',
                                            'studattendance_setup.year',
                                            'studattendance_setup.semid',
                                            'studattendance_setup.levelid',
                                            'sydesc',
                                            'sort',
                                            'attclass',
                                            'studattendance_setup.isactive'
                                    )
                                    ->get();


            $dates = DB::table('studattendance_setup_dates')
                        ->whereIn('headerid',collect($attendance_setup)->pluck('id'))
                        ->where('deleted',0)
                        ->orderBy('date')
                        ->get();

            foreach( $attendance_setup as $item){
                    $item->monthdesc = \Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM');
                    $item->dates = collect( $dates)->where('headerid',$item->id)->pluck('date');
            }
            return $attendance_setup;
        }

  }

  public static function monthly_attendance_count($syid = null, $month = null, $studid = null, $year = null){

    $attendance_setup = DB::table('studattendance')
                            ->where('deleted',0);

    if($syid == null){
          $syid = DB::table('sy')->where('isactive',1)->first()->id;
    }
    else if($syid != null){
          $attendance_setup = $attendance_setup->where('syid',$syid);
    }

    $attendance_setup = $attendance_setup->where('studid',$studid)
                      ->whereMonth('tdate','=',$month)
                      ->whereYear('tdate','=',$year)
                      ->get();


    return collect($attendance_setup)->unique('attdate')->values();;

}


    public static function api_attendance(Request $request){

        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $sectionid = null;

        // $syid = DB::table('sy')->where('isactive', 1)->first()->id;

        $check_enrollment = DB::table('enrolledstud')
                                ->where('studid',$studid)
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->first();

        if(!isset($check_enrollment->id)){

            $check_enrollment = DB::table('sh_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();

        }

        if(!isset($check_enrollment->sectionid)){
            return array((object)[
                'att_setup'=>array(),
            ]);
        }

        $sectionid = $check_enrollment->sectionid;

        $sectioninfo = DB::table('sectiondetail')
                            ->where('sectionid',$sectionid)
                            ->where('syid',$syid)
                            ->join('teacher',function($join){
                                $join->on('sectiondetail.teacherid','=','teacher.id');
                                $join->where('teacher.deleted',0);
                            })
                            ->select(
                                'teacherid'
                            )
                            ->first();

        $teacherid  = null;
        if(isset($sectioninfo->teacherid)){
            $teacherid = $sectioninfo->teacherid;
        }

        $attendance_setup = self::school_days_attendance_setup_list($syid,$check_enrollment->levelid);
        $temp_schoolinfo = DB::table('schoolinfo')->first();

        if($syid == 2 && (
            strtoupper($temp_schoolinfo->abbreviation) == 'FMC MA SCH' ||
            strtoupper($temp_schoolinfo->abbreviation) == 'VNBC'
        )){
            if(
                strtoupper($temp_schoolinfo->abbreviation) == 'FMC MA SCH' ||
                strtoupper($temp_schoolinfo->abbreviation) == 'VNBC' ||
                strtoupper($temp_schoolinfo->abbreviation) == 'ZPS'
            ){
                foreach( $attendance_setup as $item){
                    $month_count = self::monthly_attendance_count($syid,$item->month,$studid,$item->year);
                    $item->present = collect($month_count)->where('present',1)->count() + collect($month_count)->where('tardy',1)->count() + collect($month_count)->where('cc',1)->count();
                    $item->absent = $item->days - $item->present;
                    if($item->present >= $item->days){
                        $item->present = $item->days;
                        $item->absent = 0;
                    }
                }
            }
        }else{
            foreach( $attendance_setup as $item){

                $sf2_setup = DB::table('sf2_lact')
                                    ->where('month',$item->month)
                                    ->where('year',$item->year)
                                    ->where('lact',3)
                                    ->where('sectionid',$sectionid)
                                    ->where('sf2_lact.deleted',0)
                                    ->join('sf2_lact3detail',function($join) use($studid){
                                        $join->on('sf2_lact.id','=','sf2_lact3detail.headerid');
                                        $join->where('sf2_lact3detail.deleted',0);
                                        $join->where('sf2_lact3detail.studid',$studid);
                                    })
                                    ->get();

                if(count($sf2_setup) > 0){
                    $item->present = $sf2_setup[0]->dayspresent >= $item->days ? $item->days :  $sf2_setup[0]->dayspresent;
                    $item->absent = $sf2_setup[0]->daysabsent ;
                }else{

                    $sf2_setup = DB::table('sf2_setup')
                            ->where('month',$item->month)
                            ->where('year',$item->year)
                            ->where('sectionid',$sectionid)
                            ->where('sf2_setup.deleted',0)
                            ->join('sf2_setupdates',function($join){
                                $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                $join->where('sf2_setupdates.deleted',0);
                            })
                            ->groupBy('dates')
                            ->select('dates')
                            ->get();



                    $temp_days = array();

                    foreach($sf2_setup as $sf2_setup_item){
                        array_push($temp_days,$sf2_setup_item->dates);
                    }

                    $student_attendance = DB::table('studattendance')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->whereIn('tdate',$temp_days)
                                    // ->where('syid',$syid)
                                    ->distinct('tdate')
                                    ->distinct()
                                    ->select([
                                        'present',
                                        'absent',
                                        'tardy',
                                        'cc',
                                        'tdate'
                                    ])
                                    ->get();

                    $student_attendance = collect($student_attendance)->unique('tdate')->values();

                    $item->present = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count();
                    $item->absent = collect($student_attendance)->where('absent',1)->count();

                }

            }
        }

        return response()->json([
            'attendance_setup' => $attendance_setup,
            'syid' => $syid,
        ]);

    }


    public static function api_observedvalues(Request $request){

        $studid = $request->get('studid');
        $syid = $request->get('syid');

        $check_enrollment = DB::table('enrolledstud')
                                ->where('studid',$studid)
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->first();

        if(!isset($check_enrollment->id)){
            $check_enrollment = DB::table('sh_enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();
        }

        if(!isset($check_enrollment->id)){
            return array((object)[
                 'ob_rv'=>array(),
                 'ob_setup'=>array(),
                 'student_ob'=>array(),
             ]);
         }


        $temp_schoolinfo = DB::table('schoolinfo')->first();
        $student_ob = [];
        $ob_rv = [];
        $ob_setup = [];

        if($syid == 2){
            if( strtoupper($temp_schoolinfo->abbreviation) == 'MCS' ||
                strtoupper($temp_schoolinfo->abbreviation) == 'FMC MA SCH' ||
                strtoupper($temp_schoolinfo->abbreviation) == 'VNBC'
            ){
                 $ob_setup = self::observedvalues_list_v1();
            }else{
                $ob_setup = self::observedvalues_list( null,null,null,$syid,$check_enrollment->levelid);
            }
        }else{
            $ob_setup = self::observedvalues_list( null,null,null,$syid,$check_enrollment->levelid);
        }

        $student_ob = DB::table('grading_system_grades_cv')
            ->where('grading_system_grades_cv.deleted',0)
            ->where('grading_system_grades_cv.studid',$studid)
            ->where('grading_system_grades_cv.syid',$syid)
            ->select(
                'grading_system_grades_cv.gsdid',
                'grading_system_grades_cv.q1eval',
                'grading_system_grades_cv.q2eval',
                'grading_system_grades_cv.q3eval',
                'grading_system_grades_cv.q4eval'
            )
            ->get();

        if(count($student_ob) > 0){
            $ob_rv = DB::table('grading_system_ratingvalue')
                ->where('deleted',0)
                ->where('gsid',$ob_setup[0]->headerid)
                ->orderBy('sort')
                ->get();
        }


        return response()->json([
            'ob_rv' => $ob_rv,
            'ob_setup' => $ob_setup,
            'student_ob' => $student_ob
        ]);

    }

    public static function list(
        $id = null,
        $subjid = null,
        $levelid = null,
        $sort = null,
        $syid = null,
        $semid = null,
        $strandid = null,
        $subjlist = array(),
        $issp = false,
        $acadprog = null
  ){

        $subjectplot = DB::table('subject_plot')
                          ->leftJoin('subject_gradessetup',function($join){
                                $join->on('subject_plot.gradessetup','=','subject_gradessetup.id');
                                $join->where('subject_gradessetup.deleted',0);
                          })
                          ->leftJoin('teacher',function($join){
                                $join->on('subject_plot.subjcoor','=','teacher.id');
                                $join->where('teacher.deleted',0);
                          })
                          ->where('subject_plot.deleted',0);

        if($id != null){
              $subjectplot = $subjectplot->where('subject_plot.id',$id);
        }
        if($subjid != null){
              $subjectplot = $subjectplot->where('subject_plot.subjid',$subjid);
        }
        if($levelid != null){
              $subjectplot = $subjectplot->where('subject_plot.levelid',$levelid);
        }
        if($sort != null){
              $subjectplot = $subjectplot->where('subject_plot.sort',$sort);
        }
        if($syid != null){
              $subjectplot = $subjectplot->where('subject_plot.syid',$syid);
        }
        if($semid != null){
              $subjectplot = $subjectplot->where('subject_plot.semid',$semid);
        }
        if($strandid != null){
              $subjectplot = $subjectplot->where('subject_plot.strandid',$strandid);
        }

        if(!$issp){
              $subjectplot = $subjectplot->where('subject_plot.isforsp',0);
        }else if($issp){
              $subjectplot = $subjectplot->whereIn('subject_plot.isforsp',[0,1]);
        }else{
              $subjectplot = $subjectplot->whereIn('subject_plot.isforsp',[0,1]);
        }

        if($levelid == 14 || $levelid == 15 || $acadprog == 5){
              $subjectplot = $subjectplot->join('sh_subjects',function($join){
                    $join->on('subject_plot.subjid','=','sh_subjects.id');
                    $join->where('sh_subjects.deleted',0);
              })
              ->select(
                    'subjtitle as subjdesc',
                    'subjcode',
                    'inSF9',
                    'type',
                    'sh_subjCom as subjCom',
                    'sh_subjects.sh_isVisible as isVisble'
              );
        }
        else{
              $subjectplot = $subjectplot->join('subjects',function($join){
                    $join->on('subject_plot.subjid','=','subjects.id');
                    $join->where('subjects.deleted',0);
              })
              ->orderBy('plotsort')
              ->select(
                    'subjdesc',
                    'subjcode',
                    'isSP',
                    'isCon',
                    'subjCom',
                    'isVisible',
                    'inSF9',
                    'subj_per'
              );
        }

        $schoolInfo = DB::table('schoolinfo')->first();
        $abbreviation = $schoolInfo->abbreviation;


        $subjectplot = $subjectplot
                          ->addSelect(
                                'isforsp',
                                'lastname',
                                'firstname',
                                'tid',
                                'subject_plot.id',
                                'subject_plot.plotsort',
                                'subject_plot.plotsort as sortid',
                                'subject_plot.subjid',
                                'subject_plot.levelid',
                                'subject_plot.syid',
                                'subject_plot.semid',
                                'subject_plot.strandid',
                                'subject_gradessetup.ww',
                                'subject_gradessetup.pt',
                                'subject_gradessetup.qa',
                                'gradessetup',
                                'subject_plot.subjcoor'
                          );


        if (!in_array($abbreviation, ['DCC', 'SBC', 'APMC', 'SPCT', 'HCB'])) {
            $subjectplot = $subjectplot->addSelect(
                'subject_gradessetup.comp4',
                'subjunit'
            );
        }


        $subjectplot = $subjectplot->get();


        foreach($subjectplot as $item){

              $item->search = $item->subjid.' '.$item->subjcode.' '.$item->subjdesc.' '.$item->lastname.' '.$item->firstname;

        }

        return $subjectplot;
  }

    public static function get_schedule(
        $levelid = null,
        $syid = null,
        $sectionid = null,
        $semid = null,
        $strandid = null,
        $subjid = null,
        $teacherid = null,
        $roomid = null
    ){

    $levelinfo = DB::table('gradelevel')
                    ->where('id',$levelid)
                    ->select(
                        'id',
                        'acadprogid'
                    )
                    ->first();



    $schoolinfo = DB::table('schoolinfo')->select('abbreviation')->first();

    if(strtolower($schoolinfo->abbreviation) == 'apmc' && $levelinfo->acadprogid == 5){

        $blockassignment = DB::table('sh_sectionblockassignment')
                                ->join('sh_block',function($join){
                                    $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                    $join->where('sh_block.deleted','0');
                                })
                                ->leftJoin('sh_strand',function($join){
                                    $join->on('sh_block.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                                })
                                ->where('sh_sectionblockassignment.sectionid',$sectionid)
                                ->where('sh_sectionblockassignment.deleted','0')
                                ->select(
                                    'strandname',
                                    'strandcode',
                                    'sh_block.*',
                                    'sh_sectionblockassignment.blockid'
                                )
                                ->get();

        $all_subjects = array();

        foreach($blockassignment as $item){
            $subject = self::list(null, null, $levelid, null, $syid, $semid , $item->strandid);

            foreach($subject as $subject_item){

                $check = collect($all_subjects)->where('subjid',$subject_item->subjid)->values();

                if(count($check) == 0){
                    $subject_item->subj_strand = array((object)[
                                                        'strand'=>$item->strandcode,
                                                        'strandid'=>$item->strandid,
                                                        'plotid'=>$subject_item->id
                    ]);
                    array_push($all_subjects,$subject_item);

                }else{

                    $check = collect($all_subjects)->where('subjid',$subject_item->subjid)->keys();

                    $strand_obj = (object)[
                            'strand'=>$item->strandcode,
                            'strandid'=>$item->strandid,
                            'plotid'=>$subject_item->id
                        ];

                    array_push($all_subjects[$check[0]]->subj_strand,$strand_obj);

                }
            }
        }

        $subject = collect($all_subjects)->sortBy('sortid')->values();

    }else{

        $isforsp = false;

        $sectioninfo = DB::table('sectiondetail')
                                    ->where('syid',$syid)
                                    ->where('sectionid',$sectionid)
                                    ->where('deleted',0)
                                    ->first();

        if($sectioninfo->sd_issp == 1){
            $isforsp = true;
        }


        $subject = self::list(null, $subjid, $levelid, null, $syid, $semid , $strandid, array() , $isforsp);
        $subject = collect($subject)->sortBy('sortid')->values();
    }



    foreach($subject as $item){

        if($levelinfo->acadprogid == 5){

            $sched = DB::table('sh_classsched')
                        ->where('sh_classsched.syid',$syid)
                        ->where('sh_classsched.subjid',$item->subjid)
                        ->where('sh_classsched.sectionid',$sectionid)
                        ->where('sh_classsched.deleted',0)
                        ->join('sh_classscheddetail',function($join){
                            $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                            $join->where('sh_classscheddetail.deleted',0);
                        })
                        ->leftJoin('rooms',function($join){
                            $join->on('sh_classscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted',0);
                        })
                        ->join('days',function($join){
                            $join->on('sh_classscheddetail.day','=','days.id');
                        })
                        ->join('schedclassification',function($join){
                            $join->on('sh_classscheddetail.classification','=','schedclassification.id');
                        })
                        ->select(
                            'day',
                            'roomid',
                            'sh_classscheddetail.id as detailid',
                            'sh_classsched.id',
                            'roomname',
                            'stime',
                            'etime',
                            'days.description',
                            'teacherid',
                            'schedclassification.description as classification',
                            'schedclassification.id as schedclassid'
                        )
                        ->get();

                if(count($sched) == 0){

                    $sched = DB::table('sh_blocksched')
                        ->where('sh_blocksched.syid',$syid)
                        ->where('sh_blocksched.subjid',$item->subjid)

                        ->where('sh_blocksched.deleted',0)
                        ->join('sh_blockscheddetail',function($join){
                            $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                            $join->where('sh_blockscheddetail.deleted',0);
                        })
                        ->leftJoin('rooms',function($join){
                            $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted',0);
                        })
                        ->join('days',function($join){
                            $join->on('sh_blockscheddetail.day','=','days.id');
                        })
                        ->join('sh_block',function($join) use($strandid){
                            $join->on('sh_blocksched.blockid','=','sh_block.id');
                            $join->where('sh_block.deleted',0);
                            $join->where('sh_block.strandid',$strandid);
                        })
                        ->join('sh_sectionblockassignment',function($join) use($sectionid){
                            $join->on('sh_block.id','=','sh_sectionblockassignment.blockid');
                            $join->where('sh_sectionblockassignment.deleted',0);
                            $join->where('sh_sectionblockassignment.sectionid',$sectionid);
                        })
                        ->join('schedclassification',function($join){
                            $join->on('sh_blockscheddetail.classification','=','schedclassification.id');
                        })
                        ->select(
                            'day',
                            'sh_blockscheddetail.id as detailid',
                            'roomid',
                            'sh_blocksched.id',
                            'roomname',
                            'stime',
                            'etime',
                            'teacherid',
                            'days.description',
                            'schedclassification.description as classification',
                            'schedclassification.id as schedclassid'
                        )
                        ->get();

                }

            if(count($sched) > 1){
                $sched = DB::table('sh_classsched')
                            ->where('sh_classsched.syid',$syid)
                            ->where('sh_classsched.semid',$semid)
                            ->where('sh_classsched.subjid',$item->subjid)
                            ->where('sh_classsched.sectionid',$sectionid)
                            ->where('sh_classsched.deleted',0)
                            ->join('sh_classscheddetail',function($join){
                                $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                                $join->where('sh_classscheddetail.deleted',0);
                            })
                            ->leftJoin('rooms',function($join){
                                $join->on('sh_classscheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted',0);
                            })
                            ->join('days',function($join){
                                $join->on('sh_classscheddetail.day','=','days.id');
                            })
                            ->join('schedclassification',function($join){
                                $join->on('sh_classscheddetail.classification','=','schedclassification.id');
                            })
                            ->select(
                                'day',
                                'roomid',
                                'sh_classscheddetail.id as detailid',
                                'sh_classsched.id',
                                'roomname',
                                'stime',
                                'etime',
                                'days.description',
                                'teacherid',
                                'schedclassification.description as classification',
                                'schedclassification.id as schedclassid'
                            )
                            ->get();


            }

            $teacher = null;
            $tid = null;
            $teacherid = null;

            if(isset($sched[0]->teacherid)){

                $temp_teacher = DB::table('teacher')
                                    ->where('id',$sched[0]->teacherid)
                                    ->first();

                if(isset($temp_teacher->firstname)){
                    $teacher = $temp_teacher->firstname.' '.$temp_teacher->middlename.' '.$temp_teacher->lastname;
                    $tid = $temp_teacher->tid;
                    $teacherid = $temp_teacher->id;
                }

            }


            foreach($sched as $sched_item){
                $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
            }


            $starting = collect($sched)->groupBy('time');

            $sched_list = array();
            $sched_count = 1;

            foreach($starting as $sched_item){

                $byclassification = collect($sched_item)->groupBy('schedclassid');

                foreach($byclassification as $byclassification_item){


                    $dayString = '';
                    $days = array();

                    foreach($byclassification_item as $new_item){
                        $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                        $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                        $dayString.= substr($new_item->description, 0,3).' / ';
                        $detailid = $new_item->detailid;
                        $roomname = $new_item->roomname;
                        $roomid = $new_item->roomid;
                        $classification = $new_item->classification;
                        $schedclassid = $new_item->schedclassid;
                        $time = $new_item->time;
                        array_push($days,$new_item->day);
                    }

                    $dayString = substr($dayString, 0 , -2);

                    array_push($sched_list,(object)[
                        'day'=>$dayString,
                        'start'=>$start,
                        'end'=>$end,
                        'roomid',
                        'detailid'=>$detailid,
                        'roomname'=>$roomname,
                        'roomid'=>$roomid,
                        'classification'=>$classification,
                        'teacher'=>$teacher,
                        'tid'=>$tid,
                        'teacherid'=>$teacherid,
                        'sched_count'=>$sched_count,
                        'time'=>$time,
                        'days'=>$days,
                        'schedclassid'=>$schedclassid,
                        'sort'=>\Carbon\Carbon::create($start)->isoFormat('HH'),
                    ]);


                    $sched_count += 1;

                }
            }

            $item->datatype = 'seniorhigh';
            $item->schedule = $sched_list;

        }else{



            $sched = DB::table('classsched')
                        ->where('classsched.syid',$syid)
                        ->where('classsched.subjid',$item->subjid)
                        ->where('classsched.sectionid',$sectionid)
                        ->where('classsched.deleted',0)
                        ->leftJoin('classscheddetail',function($join){
                            $join->on('classsched.id','=','classscheddetail.headerid');
                            $join->where('classscheddetail.deleted',0);
                        })
                        ->leftJoin('rooms',function($join){
                            $join->on('classscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted',0);
                        })
                        ->leftJoin('days',function($join){
                            $join->on('classscheddetail.days','=','days.id');
                        })
                        ->leftJoin('schedclassification',function($join){
                            $join->on('classscheddetail.classification','=','schedclassification.id');
                        })
                        ->select(
                            'roomid',
                            'classsched.id',
                            'roomname',
                            'stime',
                            'etime',
                            'days.description',
                            'classscheddetail.id as detailid',
                            'schedclassification.description as classification',
                            'roomid',
                            'days',
                            'schedclassification.id as schedclassid'
                        )
                        ->get();

            $temp_subj = $item->subjid;

            $asssubj = DB::table('assignsubj')
                        ->where('assignsubj.syid',$syid)
                        ->where('assignsubj.sectionid',$sectionid)
                        ->where('assignsubj.deleted',0)
                        ->join('assignsubjdetail',function($join) use($temp_subj){
                            $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                            $join->where('assignsubjdetail.deleted',0);
                            $join->where('assignsubjdetail.subjid',$temp_subj);
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('assignsubjdetail.teacherid','=','teacher.id');
                            $join->where('teacher.deleted',0);
                        })
                        ->select(
                            'lastname',
                            'firstname',
                            'middlename',
                            'suffix',
                            'title',
                            'teacherid',
                            'tid'
                        )
                        ->first();

            $temp_teacher = null;

            if(!isset($asssubj->tid)){
                $asssubj = (object)[
                            'lastname'=>null,
                            'firstname'=>null,
                            'middlename'=>null,
                            'suffix'=>null,
                            'title'=>null,
                            'teacherid'=>null,
                            'tid'=>null
                ];

            }

            $temp_sched = array();

            foreach($sched as $sched_item){
                try{
                     $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                    array_push($temp_sched,$sched_item);
                }catch(\Exception $e){

                }
            }

            $sched = $temp_sched;

            $starting = collect($sched)->groupBy('time');
            $sched_list = array();
            $sched_count = 1;

            foreach($starting as $sched_item){


                //group by classification

                $byclassification = collect($sched_item)->groupBy('schedclassid');

                foreach($byclassification as $byclassification_item){

                    $dayString = '';
                    $days = array();

                    foreach($byclassification_item as $new_item){
                        $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                        $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                        $dayString.= substr($new_item->description, 0,3).' / ';
                        $detailid = $new_item->detailid;
                        $roomname = $new_item->roomname;
                        $roomid = $new_item->roomid;
                        $classification = $new_item->classification;
                        $schedclassid = $new_item->schedclassid;
                        $time = $new_item->time;
                        array_push($days,$new_item->days);
                    }

                    $dayString = substr($dayString, 0 , -2);

                    array_push($sched_list,(object)[
                        'day'=>$dayString,
                        'start'=>$start,
                        'end'=>$end,
                        'roomid',
                        'detailid'=>$detailid,
                        'roomname'=>$roomname,
                        'roomid'=>$roomid,
                        'classification'=>$classification,
                        'teacher'=> $asssubj->firstname.' '.$asssubj->middlename.' '.$asssubj->lastname,
                        'tid'=>$asssubj->tid,
                        'schedclassid'=> $schedclassid,
                        'teacherid'=>$asssubj->teacherid,
                        'sched_count'=>$sched_count,
                        'sort'=>\Carbon\Carbon::create($start)->isoFormat('HH'),
                        'time'=>$time,
                        'days'=>$days
                    ]);

                    $sched_count += 1;

                }

            }
            $item->datatype = 'juniorhigh';
            $item->schedule = $sched_list;

        }


    }

    return collect($subject)->sortBy('sortid')->values();

}


    public function api_clearancedata(Request $request)
    {
        $clerstudid = $request->get('clerstudid');
        $syid = $request->get('syid');
        $termid = $request->get('termid');
        $getassignsubj = "";
        $getadviser = "";
        $studid = $request->get('studid');

        try {
            $getsection = DB::table('clearance_studinfo')
                ->join('enrolledstud', function ($join) use ($clerstudid, $syid) {
                    $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                        ->where('enrolledstud.syid', $syid)
                        ->where('enrolledstud.deleted', 0);
                })
                ->where('clearance_studinfo.id', $clerstudid)
                ->select('enrolledstud.sectionid', 'enrolledstud.levelid')
                ->get();

            if (count($getsection) == 0) {
                $getsection = DB::table('clearance_studinfo')
                    ->join('sh_enrolledstud', function ($join) use ($clerstudid, $syid) {
                        $join->on('clearance_studinfo.studid', '=', 'sh_enrolledstud.studid')
                            ->where('sh_enrolledstud.syid', $syid)
                            ->where('clearance_studinfo.id', $clerstudid);
                    })
                    ->select('sh_enrolledstud.sectionid', 'sh_enrolledstud.levelid')
                    ->get();

            } else {
                $section = $getsection[0]->sectionid;
                $levelid = $getsection[0]->levelid;
            }



            if ($getsection->isEmpty()) {
                $getsection = DB::table('clearance_studinfo')
                    ->join('college_enrolledstud', function ($join) use ($clerstudid, $syid) {
                        $join->on('clearance_studinfo.studid', '=', 'college_enrolledstud.studid')
                            ->where('college_enrolledstud.syid', $syid)
                            ->where('clearance_studinfo.id', $clerstudid);
                    })
                    ->select('college_enrolledstud.sectionID', 'college_enrolledstud.yearLevel')
                    ->get();

                $section = $getsection[0]->sectionID;
                $levelid = $getsection[0]->yearLevel;
            } else {
                $section = $getsection[0]->sectionid;
                $levelid = $getsection[0]->levelid;
            }


            $getacadprog = DB::table('gradelevel')
                ->where('id', $levelid)
                ->select('acadprogid')
                ->get();

            $acadprogid = $getacadprog[0]->acadprogid;

            $check_subteacher_status = DB::table('clearance_signatory')
                ->where('termid', $termid)
                ->where('syid', $syid)
                ->where('departmentid', 'SUBJECT TEACHER')
                ->whereIn('acadprogid', [0, $acadprogid])
                ->select('acadprogid', 'isactive')
                ->get();

            $check_subteacher_status = DB::table('clearance_acadterm_acadprog')
                ->where('termid', $termid)
                ->where('deleted', 0)
                ->where('acadprogid', $acadprogid)
                ->get();

            //return $check_subteacher_status;

            $subteacher_isactive = false;
            $classadviser_isactive = false;

            foreach ($check_subteacher_status as $status) {

                if ($status->subjteacher == 1) {
                    $subteacher_isactive = true;
                }
                if ($status->classadviser == 1) {
                    $classadviser_isactive = true;
                }
            }




            if ($subteacher_isactive) {
                // $getassignsubj = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$section);
                $getassignsubj = DB::table('assignsubj')
                    ->where('assignsubj.syid', $syid)
                    ->where('assignsubj.deleted', 0)
                    ->join('assignsubjdetail', function ($join) use ($levelid, $section, $syid) {
                        $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid')
                            ->where('assignsubj.deleted', 0)
                            ->where('assignsubjdetail.deleted', 0)
                            ->where('assignsubj.glevelid', $levelid)
                            ->where('assignsubj.sectionid', $section);

                    })
                    ->join('subjects', function ($join) {
                        $join->on('assignsubjdetail.subjid', '=', 'subjects.id')
                            ->where('subjects.deleted', 0)
                            ->where('assignsubjdetail.deleted', 0);
                    })
                    ->join('teacher', function ($join) {
                        $join->on('assignsubjdetail.teacherid', '=', 'teacher.id')
                            ->where('teacher.deleted', 0)
                            ->where('assignsubjdetail.deleted', 0);
                    })
                    ->leftJoin('clearance_stud_status', function ($join) use ($clerstudid) {
                        $join->on('assignsubjdetail.subjid', '=', 'clearance_stud_status.subject_id')
                            ->where('clearance_stud_status.deleted', '!=', 1)
                            ->where('clearance_stud_status.clearance_studid', '=', $clerstudid);
                    })

                    ->distinct('clearance_stud_status.subject_id')
                    ->select(
                        'assignsubjdetail.subjid',
                        'subjects.subjdesc',
                        'teacher.title',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'clearance_stud_status.subject_id',
                        'clearance_stud_status.clearance_type',
                        'clearance_stud_status.status',
                        'clearance_stud_status.remarks',
                        'clearance_stud_status.approveddatetime',
                        'clearance_stud_status.updateddatetime'
                    )
                    ->get();


                $getassignsubj = self::get_schedule($levelid, $syid, $section);

                //return $getassignsubj;

            }



            //$check_classadviser_status = DB::table('clearance_signatory')
            //->where('termid', $termid)
            //->where('syid', $syid)
            //->where('departmentid', 'CLASS ADVISER')
            //->whereIn('acadprogid', [0,$acadprogid])
            //->select('acadprogid','isactive')
            //->get();

            //$classadviser_isactive = false;
            //foreach ($check_classadviser_status as $status) {
            //if ($status->acadprogid == $acadprogid && $status->isactive == 0) {
            //$classadviser_isactive = true;
            //break;
            //}
            //if ($status->acadprogid == 0 && $status->isactive == 0) {
            //$classadviser_isactive = true;
            //break;
            //}
            //}

            if ($classadviser_isactive) {



                $getadviser = DB::table('sectiondetail')
                    ->join('teacher', function ($join) use ($section) {
                        $join->on('sectiondetail.teacherid', '=', 'teacher.id')
                            ->where('sectionid', '=', $section)
                            ->where('teacher.deleted', 0);
                    })
                    ->leftJoin('clearance_stud_status', function ($join) use ($clerstudid) {
                        $join->on('teacher.id', '=', 'clearance_stud_status.teacher_id')
                            ->where('subject_id', '=', 'CLASS ADVISER')
                            ->where('teacher.deleted', 0)
                            ->where('clearance_stud_status.clearance_studid', '=', $clerstudid);
                    })
                    ->distinct('clearance_stud_status.subject_id')
                    ->select(
                        'teacher.title',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'clearance_stud_status.subject_id',
                        'clearance_stud_status.clearance_type',
                        'clearance_stud_status.status',
                        'clearance_stud_status.remarks',
                        'clearance_stud_status.approveddatetime',
                        'clearance_stud_status.updateddatetime'
                    )
                    ->get();
            }


            $checksignatories = DB::table('clearance_signatory')
                ->join('teacher', function ($join) use ($syid, $termid) {
                    $join->on('clearance_signatory.teacherid', '=', 'teacher.id')
                        ->where('teacher.deleted', 0)
                        ->where('clearance_signatory.deleted', 0)
                        ->where('clearance_signatory.syid', $syid)
                        ->where('clearance_signatory.termid', $termid)
                        ->where('clearance_signatory.isactive', 0);
                })
                ->leftJoin('clearance_stud_status', function ($join) use ($clerstudid) {
                    $join->on('clearance_signatory.id', '=', 'clearance_stud_status.clearance_type')
                        ->where('clearance_stud_status.deleted', '=', 0)
                        ->where('clearance_stud_status.clearance_studid', '=', $clerstudid);
                })
                ->whereRaw("json_extract(clearance_signatory.acadprogid, '$[*]') LIKE ?", ["%$acadprogid%"])
                ->select(
                    'clearance_signatory.id',
                    'clearance_signatory.departmentid',
                    'teacher.title',
                    'teacher.lastname',
                    'teacher.firstname',
                    'teacher.middlename',
                    'clearance_stud_status.subject_id',
                    'clearance_stud_status.clearance_type',
                    'clearance_stud_status.status',
                    'clearance_stud_status.remarks',
                    'clearance_stud_status.approveddatetime',
                    'clearance_stud_status.updateddatetime'
                )
                ->distinct('clearance_signatory.title')
                ->get();

            if ($getassignsubj == null && $getadviser == null || $getassignsubj == "" && $getadviser == "") {
                $data = $checksignatories;
            } else if ($getassignsubj == null || $getassignsubj == "") {
                $data = $getadviser->concat($checksignatories);
            } else if ($getadviser == null || $getadviser == "") {
                $data = $getassignsubj->concat($checksignatories);
            } else {
                $data1 = $getassignsubj->concat($getadviser);
                $data = $data1->concat($checksignatories);
            }

            $getclearedstatus = DB::table('clearance_studinfo')
                ->select('iscleared')
                ->where('id', $clerstudid)
                ->where('termid', $termid)
                ->get();

            return [$data, $getclearedstatus];

        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }



    public function api_scholarship(Request $request)
    {

        $studid = $request->get('studid');


        $studid = DB::table('studinfo')
            ->where('id', $studid)
            ->select('id', 'levelid')
            ->first();

        $data = DB::table('scholarship_applicants')
            ->where('studid', $studid->id)
            ->join('scholarship_setup', function ($join) {
                $join->on('scholarship_setup.id', '=', 'scholarship_applicants.scholarship_setup_id');
                $join->where('scholarship_setup.deleted', 0);
            })
            ->when($request->get('id'), function ($query) use ($request) {
                $query->where('scholarship_applicants.id', $request->get('id'));
            })
            ->select('scholarship_setup.description', 'scholarship_applicants.*')
            ->where('scholarship_applicants.deleted', 0)
            ->get();


        foreach ($data as $item) {

            if ($studid->levelid >= 17 && $studid->levelid <= 22) {

                $item->studstatus = DB::table('college_enrolledstud')
                    ->where('studid', $studid->id)
                    ->value('regStatus');

            } else {

                $item->studstatus = 'N/A';

            }

        }
        return $data;
    }

    public static function api_remedial_class(Request $request){


        $month = $request->get('month');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $studid = $request->get('studid');

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }

        $check_enrollment = DB::table('enrolledstud')
                ->where('studid',$studid)
                ->where('syid',$syid)
                ->where('deleted',0)
                ->first();

        if(!isset($check_enrollment->id)){

            $check_enrollment = DB::table('sh_enrolledstud')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->first();

            if(isset($check_enrollment->id)){
                $semid = $request->get('semid');
                $strandid = $check_enrollment->strandid;
            }
        }

        $remedial_class = DB::table('student_specsubj')
                            ->join('sections',function($join){
                                $join->on('student_specsubj.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                            })
                            ->join('gradelevel',function($join){
                                $join->on('student_specsubj.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->where('student_specsubj.studid',$studid)
                            ->where('student_specsubj.syid',$syid)
                            ->where('student_specsubj.deleted',0)
                            ->get();


        foreach($remedial_class as $item){

            $levelid = $item->levelid;
            $sectionid = $item->sectionid;
            $subjid = $item->subjid;


            if($levelid == 14 || $levelid == 15){

                $subject = DB::table('sh_subjects')
                                ->where('id',$item->subjid)
                                ->where('deleted',0)
                                ->select(
                                    'subjtitle as subjdesc',
                                    'subjcode'
                                )
                                ->first();

                $schedule = self::get_schedule($levelid,$syid,$sectionid,null,null);
            }
            else{

                $subject = DB::table('subjects')
                            ->where('id',$item->subjid)
                            ->where('deleted',0)
                            ->select(
                                'subjdesc',
                                'subjcode'
                            )
                            ->first();

                $schedule = self::get_schedule($levelid,$syid,$sectionid,null,null,$subjid);
            }


            $item->subjdesc = $subject->subjdesc;
            $item->subjcode = $subject->subjcode;
            $item->sched = $schedule[0]->schedule;

            $grades = DB::table('grades')
                        ->join('gradesdetail',function($join) use($studid){
                            $join->on('grades.id','=','gradesdetail.headerid');
                            $join->where('gradesdetail.studid',$studid);
                            $join->where('gradesdetail.gdstatus',4);
                        })
                        ->where('syid',$item->syid)
                        ->where('sectionid',$sectionid)
                        ->where('subjid',$subjid)
                        ->where('levelid',$levelid)
                        ->select(
                            'qg',
                            'quarter'
                        )->get();

            $item->q1 = '';
            $item->q2 = '';
            $item->q3 = '';
            $item->q4 = '';

            foreach($grades as $gradeitem){
                if($gradeitem->quarter == 1 ){
                    $item->q1 = $gradeitem->qg;
                }
                else if($gradeitem->quarter == 2 ){
                    $item->q2 = $gradeitem->qg;
                }
                else if($gradeitem->quarter == 3 ){
                    $item->q3 = $gradeitem->qg;
                }
                else if($gradeitem->quarter == 4 ){
                    $item->q4 = $gradeitem->qg;
                }
            }
        }
        return $remedial_class;
    }

    public static function dcc_college_grade($studid = null,$syid = null,$semid = null){

        $grades = DB::table('college_studentprospectus')
                    ->where('deleted',0)
                    ->where('studid',$studid)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->get();

                    $schedule = \App\Models\StudentScheduleCode\StudentScheduleCodeData::student_schedule_code($syid, $semid, $studid);

                    $temp_grades = array();


                    $transmutation = array(
                        (object)['gd'=>0.0,'gdto'=>number_format(73.99,2),'eq'=>number_format(5.0,1)],
                        (object)['gd'=>74.0,'gdto'=>number_format(74.49,2),'eq'=>number_format(5.0,1)],
                        (object)['gd'=>74.5,'gdto'=>number_format(75.99,2),'eq'=>number_format(3.5,1)],
                        (object)['gd'=>76.0,'gdto'=>number_format(76.99,2),'eq'=>number_format(3.4,1)],
                        (object)['gd'=>77.0,'gdto'=>number_format(77.99,2),'eq'=>number_format(3.3,1)],
                        (object)['gd'=>78.0,'gdto'=>number_format(78.99,2),'eq'=>number_format(3.2,1)],
                        (object)['gd'=>79.0,'gdto'=>number_format(79.99,2),'eq'=>number_format(3.1,1)],
                        (object)['gd'=>80.0,'gdto'=>number_format(80.99,2),'eq'=>number_format(3.0,1)],
                        (object)['gd'=>81.0,'gdto'=>number_format(81.99,2),'eq'=>number_format(2.9,1)],
                        (object)['gd'=>82.0,'gdto'=>number_format(82.99,2),'eq'=>number_format(2.8,1)],
                        (object)['gd'=>83.0,'gdto'=>number_format(83.99,2),'eq'=>number_format(2.7,1)],
                        (object)['gd'=>84.0,'gdto'=>number_format(84.99,2),'eq'=>number_format(2.6,1)],
                        (object)['gd'=>85.0,'gdto'=>number_format(85.99,2),'eq'=>number_format(2.5,1)],
                        (object)['gd'=>86.0,'gdto'=>number_format(86.99,2),'eq'=>number_format(2.4,1)],
                        (object)['gd'=>87.0,'gdto'=>number_format(87.99,2),'eq'=>number_format(2.3,1)],
                        (object)['gd'=>88.0,'gdto'=>number_format(88.99,2),'eq'=>number_format(2.2,1)],
                        (object)['gd'=>89.0,'gdto'=>number_format(89.99,2),'eq'=>number_format(2.1,1)],
                        (object)['gd'=>90.0,'gdto'=>number_format(90.99,2),'eq'=>number_format(2.0,1)],
                        (object)['gd'=>91.0,'gdto'=>number_format(91.99,2),'eq'=>number_format(1.9,1)],
                        (object)['gd'=>92.0,'gdto'=>number_format(92.99,2),'eq'=>number_format(1.8,1)],
                        (object)['gd'=>93.0,'gdto'=>number_format(93.99,2),'eq'=>number_format(1.7,1)],
                        (object)['gd'=>94.0,'gdto'=>number_format(94.99,2),'eq'=>number_format(1.6,1)],
                        (object)['gd'=>95.0,'gdto'=>number_format(95.99,2),'eq'=>number_format(1.5,1)],
                        (object)['gd'=>96.0,'gdto'=>number_format(96.99,2),'eq'=>number_format(1.4,1)],
                        (object)['gd'=>97.0,'gdto'=>number_format(97.99,2),'eq'=>number_format(1.3,1)],
                        (object)['gd'=>98.0,'gdto'=>number_format(98.99,2),'eq'=>number_format(1.2,1)],
                        (object)['gd'=>99.0,'gdto'=>number_format(99.99,2),'eq'=>number_format(1.1,1)],
                        (object)['gd'=>100.0,'gdto'=>number_format(100,2),'eq'=>number_format(1.0,1)]
                    );

                    foreach($schedule as $item){
                        $check = collect($grades)->where('prospectusID',$item->codeid)->first();
                        if(isset($check->id)){

                            if($check->prelemstatus != 4){
                                $check->prelemgrade = null;
                            }
                            if($check->midtermstatus != 4){
                                $check->midtermgrade = null;
                            }
                            if($check->prefistatus != 4){
                                $check->prefigrade = null;
                            }
                            if($check->finalstatus != 4){
                                $check->finalgrade = null;
                            }

                            foreach (['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'] as $gradePeriod) {
                                if (!is_null($check->$gradePeriod)) {
                                    $tem_transmuatation = collect($transmutation)
                                        ->where('gd', '<=', number_format($check->$gradePeriod, 2))
                                        ->where('gdto', '>=', number_format($check->$gradePeriod, 2))
                                        ->first();

                                    if (isset($tem_transmuatation->gd)) {
                                        $check->$gradePeriod = $tem_transmuatation->eq;

                                    } else {
                                        $check->$gradePeriod = null;

                                    }
                                }
                            }

                            $check->subjdesc = $item->subjDesc;
                            $check->subjcode = $item->subjCode;
                            $check->code = $item->code;

                            $tem_transmuatation = collect($transmutation)->where('gd','<=',number_format($check->finalgrade,2))->where('gdto','>=',number_format($check->finalgrade,2))->first();


                            if(isset($tem_transmuatation->gd) && $check->finalgrade != null){


                                $check->grade = $tem_transmuatation->eq;

                                $check->fgremarks = $tem_transmuatation->eq <= 3.5 ? 'FAILED' : 'PASSED';
                            }else{
                                 $check->grade = null;
                                 $check->remarks = null;
                            }

                            array_push($temp_grades,$check);
                        }else{
                            $item->finalgrade = null;
                            array_push($temp_grades,$item);
                        }
                    }

                    return $temp_grades;

    }

    public static function spct_college_schedule($syid = null, $semid = null, $studid = null){
        $schedule = DB::table('college_studsched')
        ->join('college_classsched',function($join){
            $join->on('college_studsched.schedid','=','college_classsched.id');
            $join->where('college_classsched.deleted',0);
        })
        ->join('college_sections',function($join){
            $join->on('college_classsched.sectionID','=','college_sections.id');
            $join->where('college_sections.deleted',0);
        })
        ->join('college_prospectus',function($join){
            $join->on('college_classsched.subjectID','=','college_prospectus.id');
            $join->where('college_prospectus.deleted',0);
        })
        ->leftJoin('teacher',function($join){
            $join->on('college_classsched.teacherID','=','teacher.id');
            $join->where('teacher.deleted','0');
        })
        ->leftJoin('college_scheddetail',function($join){
            $join->on('college_classsched.id','=','college_scheddetail.headerid');
            $join->where('college_scheddetail.deleted','0');
        })
        ->leftJoin('rooms',function($join){
            $join->on('college_scheddetail.roomID','=','rooms.id');
            $join->where('rooms.deleted','0');
        })
        ->leftJoin('days',function($join){
            $join->on('college_scheddetail.day','=','days.id');
        })
        ->where('studid',$studid)
        ->where('college_studsched.deleted',0)
        ->where('college_classsched.syID',$syid)
        ->where('college_classsched.semesterID',$semid)
        ->select(
            'days.description',
            'rooms.roomname',
            'college_scheddetail.etime',
            'college_scheddetail.stime',
            'teacher.firstname',
            'teacher.lastname',
            'college_classsched.subjectUnit',
            'college_prospectus.subjDesc',
            'college_prospectus.subjCode',
            'college_prospectus.lecunits',
            'college_prospectus.labunits',
            'college_classsched.id',
            'college_sections.sectionDesc',
            'college_sections.id as sectionid',
            'college_prospectus.id as subjID',
            'college_prospectus.subjectID',
            'college_scheddetail.scheddetialclass'
        )
        ->get();

        $scheduleByDay = [];

        foreach ($schedule as $item) {
            $item->start = \Carbon\Carbon::createFromTimeString($item->stime)->isoFormat('hh:mm A');
            $item->end = \Carbon\Carbon::createFromTimeString($item->etime)->isoFormat('hh:mm A');
            $item->teacher = $item->firstname . ' ' . $item->lastname;


            $scheduleItem = [
                'start' => $item->start,
                'end' => $item->end,
                'subject' => $item->subjDesc,
                'room' => $item->roomname,
                'teacher' => $item->teacher,
            ];


            $day = strtolower($item->description);


            if (!isset($scheduleByDay[$day])) {
                $scheduleByDay[$day] = [
                    'day' => $day,
                    'sched' => []
                ];
            }


            $scheduleByDay[$day]['sched'][] = $scheduleItem;
        }


        $dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        usort($scheduleByDay, function($a, $b) use ($dayOrder) {
            return array_search($a['day'], $dayOrder) - array_search($b['day'], $dayOrder);
        });

        return $scheduleByDay;

        // return $schedule;
    }


    public static function student_schedule_code($syid = null, $semid = null, $studid = null){


        $student_schedcoding = DB::table('college_studsched')
            ->whereNotNull('schedcodeid')
            ->join('schedulecoding',function($join) use($semid,$syid){
                $join->on('college_studsched.schedcodeid','=','schedulecoding.id');
                $join->where('schedulecoding.deleted',0);
                $join->where('schedulecoding.syid',$syid);
                $join->where('schedulecoding.semid',$semid);
            })
            ->leftJoin('schedulecodingdetails',function($join){
                $join->on('schedulecoding.id','=','schedulecodingdetails.headerid');
                $join->where('schedulecodingdetails.deleted',0);
            })
            ->leftJoin('college_subjects',function($join){
                $join->on('schedulecoding.subjid','=','college_subjects.id');
                $join->where('college_subjects.deleted',0);
            })
            ->leftJoin('days',function($join){
                $join->on('schedulecodingdetails.day','=','days.id');
            })
            ->leftJoin('sy',function($join){
                $join->on('schedulecoding.syid','=','sy.id');
            })
            ->leftJoin('rooms',function($join){
                $join->on('schedulecoding.roomid','=','rooms.id');
            })
            ->leftJoin('semester',function($join){
                $join->on('schedulecoding.semid','=','semester.id');
            })
            ->leftJoin('teacher',function($join){
                $join->on('schedulecoding.teacherid','=','teacher.id');
            })
            ->leftJoin('teacher as encoder',function($join){
                $join->on('college_studsched.createdby','=','encoder.userid');
            })
            ->where('college_studsched.studid',$studid)
            ->whereNotNull('schedulecoding.syid')
            ->whereNotNull('schedulecoding.semid')
            ->where('college_studsched.deleted',0)
            ->select(
                'roomname',
                'teacher.lastname',
                'teacher.firstname',
                'sydesc',
                'semester',
                'code',
                'syid',
                'semid',
                'schedulecoding.subjid',
                'college_studsched.id',
                'college_studsched.schedid',
                'college_studsched.schedcodeid as codeid',
                'timestart',
                'timeend',
                'day',
                'description',
                'subjDesc',
                'subjCode',
                'lecunits',
                'labunits',
                'day as days'
            )
            ->get();

            $scheduleByDay = [];

        foreach ($student_schedcoding as $item) {
            $item->start = \Carbon\Carbon::createFromTimeString($item->timestart)->isoFormat('hh:mm A');
            $item->end = \Carbon\Carbon::createFromTimeString($item->timeend)->isoFormat('hh:mm A');
            $item->teacher = $item->firstname . ' ' . $item->lastname;


            $scheduleItem = [
                'start' => $item->start,
                'end' => $item->end,
                'subject' => $item->subjDesc,
                'room' => $item->roomname,
                'teacher' => $item->teacher,
            ];


            $day = strtolower($item->description);


            if (!isset($scheduleByDay[$day])) {
                $scheduleByDay[$day] = [
                    'day' => $day,
                    'sched' => []
                ];
            }


            $scheduleByDay[$day]['sched'][] = $scheduleItem;
        }


        $dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        usort($scheduleByDay, function($a, $b) use ($dayOrder) {
            return array_search($a['day'], $dayOrder) - array_search($b['day'], $dayOrder);
        });

        return $scheduleByDay;

    }

     public function get_sched(Request $request)
        {
        $studid = $request->get('studid');
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $strandid = $request->get('strand');

        $schoolInfo = DB::table('schoolinfo')->first();
        $abbreviation = $schoolInfo->abbreviation;

        if (!in_array($abbreviation, ['DCC', 'SPCT'])) {

        if ($levelid == 14 || $levelid == 15) {
            $schedule = self::get_schedule($levelid, $syid, $sectionid, $semid, $strandid);
        } elseif ($levelid >= 17 && $levelid <= 20) {
            $schedule = self::collegestudentsched_plot($studid, $syid, $semid);

            if ($schedule[0]->status == 1) {
                $schedule = $schedule[0]->info;
                $schedule = collect($schedule)->where('schedstatus', '!=', 'DROPPED')->values();

                foreach ($schedule as $item) {
                    $item->subjdesc = $item->subjDesc;
                    foreach ($item->schedule as $sched_item) {
                        $sched_item->teacher = $item->teacher;
                    }
                }
            } else {
                $schedule = array();
            }
        } else {
            $schedule = self::get_schedule($levelid, $syid, $sectionid);
        }

        $days = DB::table('days')->get();
        $schedule_holder = array();

        $daycount = 1;

        foreach ($days as $item) {
            $sched = array();
            $uniqueSched = [];

            foreach ($schedule as $scheditem) {
                foreach ($scheditem->schedule as $scheddetail) {
                    if (in_array($daycount, $scheddetail->days)) {

                        $key = $scheddetail->start . '_' . $scheddetail->end . '_' . $scheditem->subjdesc . '_' . $scheddetail->roomname . '_' . $scheddetail->teacher;

                        if (!isset($uniqueSched[$key])) {
                            $uniqueSched[$key] = (object)[
                                'start' => $scheddetail->start,
                                'end' => $scheddetail->end,
                                'subject' => $scheditem->subjdesc,
                                'room' => $scheddetail->roomname,
                                'teacher' => $scheddetail->teacher
                            ];
                        }
                    }
                }

                $scheditem->subject = $scheditem->subjdesc;
            }
            $sched = array_values($uniqueSched);

            $daydescription = strtolower($item->description);

            array_push($schedule_holder, (object)[
                'day' => $daydescription,
                'sched' => $sched
            ]);

            $daycount += 1;
        }

        $studinfo = DB::table('studinfo')
            ->select('sid', 'lastname', 'firstname')
            ->where('id', $studid)
            ->first();

        $api_sched = array((object)[
            'name' => $studinfo->firstname . ' ' . $studinfo->lastname,
            'id' => $studinfo->sid,
            'schedule' => $schedule_holder,
            'allsched' => $schedule
        ]);

        return $api_sched;

    } else {

        if (in_array($abbreviation, ['DCC'])) {

            if ($levelid == 14 || $levelid == 15) {
                $schedule = self::get_schedule($levelid, $syid, $sectionid, $semid, $strandid);

                $days = DB::table('days')->get();
                $schedule_holder = array();

                $daycount = 1;

                foreach ($days as $item) {
                    $sched = array();
                    $uniqueSched = [];

                    foreach ($schedule as $scheditem) {
                        foreach ($scheditem->schedule as $scheddetail) {
                            if (in_array($daycount, $scheddetail->days)) {

                                $key = $scheddetail->start . '_' . $scheddetail->end . '_' . $scheditem->subjdesc . '_' . $scheddetail->roomname . '_' . $scheddetail->teacher;

                                if (!isset($uniqueSched[$key])) {
                                    $uniqueSched[$key] = (object)[
                                        'start' => $scheddetail->start,
                                        'end' => $scheddetail->end,
                                        'subject' => $scheditem->subjdesc,
                                        'room' => $scheddetail->roomname,
                                        'teacher' => $scheddetail->teacher
                                    ];
                                }
                            }
                        }

                        $scheditem->subject = $scheditem->subjdesc;
                    }
                    $sched = array_values($uniqueSched);

                    $daydescription = strtolower($item->description);

                    array_push($schedule_holder, (object)[
                        'day' => $daydescription,
                        'sched' => $sched
                    ]);

                    $daycount += 1;
                }

                $studinfo = DB::table('studinfo')
                    ->select('sid', 'lastname', 'firstname')
                    ->where('id', $studid)
                    ->first();

                $api_sched = array((object)[
                    'name' => $studinfo->firstname . ' ' . $studinfo->lastname,
                    'id' => $studinfo->sid,
                    'schedule' => $schedule_holder,
                    'allsched' => $schedule
                ]);

                return $api_sched;


            } elseif ($levelid >= 17 && $levelid <= 20) {

                    $schedule = self::student_schedule_code($syid, $semid, $studid);

                    $days = DB::table('days')->get();
                    $schedule_holder = array();

                    $studinfo = DB::table('studinfo')
                    ->select('sid', 'lastname', 'firstname')
                    ->where('id', $studid)
                    ->first();

                    $api_sched = array((object)[
                        'name' => $studinfo->firstname . ' ' . $studinfo->lastname,
                        'id' => $studinfo->sid,
                        'schedule' => $schedule
                    ]);

                return $api_sched;

            } else {
                $schedule = self::get_schedule($levelid, $syid, $sectionid);

                $days = DB::table('days')->get();
                $schedule_holder = array();

                $daycount = 1;

                foreach ($days as $item) {
                    $sched = array();
                    $uniqueSched = [];

                    foreach ($schedule as $scheditem) {
                        foreach ($scheditem->schedule as $scheddetail) {
                            if (in_array($daycount, $scheddetail->days)) {

                                $key = $scheddetail->start . '_' . $scheddetail->end . '_' . $scheditem->subjdesc . '_' . $scheddetail->roomname . '_' . $scheddetail->teacher;

                                if (!isset($uniqueSched[$key])) {
                                    $uniqueSched[$key] = (object)[
                                        'start' => $scheddetail->start,
                                        'end' => $scheddetail->end,
                                        'subject' => $scheditem->subjdesc,
                                        'room' => $scheddetail->roomname,
                                        'teacher' => $scheddetail->teacher
                                    ];
                                }
                            }
                        }

                        $scheditem->subject = $scheditem->subjdesc;
                    }
                    $sched = array_values($uniqueSched);

                    $daydescription = strtolower($item->description);

                    array_push($schedule_holder, (object)[
                        'day' => $daydescription,
                        'sched' => $sched
                    ]);

                    $daycount += 1;
                }

                $studinfo = DB::table('studinfo')
                    ->select('sid', 'lastname', 'firstname')
                    ->where('id', $studid)
                    ->first();

                $api_sched = array((object)[
                    'name' => $studinfo->firstname . ' ' . $studinfo->lastname,
                    'id' => $studinfo->sid,
                    'schedule' => $schedule_holder,
                    'allsched' => $schedule
                ]);

                return $api_sched;
                }
            }

            else

            {
                if ($levelid == 14 || $levelid == 15) {
                    $schedule = self::get_schedule($levelid, $syid, $sectionid, $semid, $strandid);

                    $days = DB::table('days')->get();
                    $schedule_holder = array();

                    $daycount = 1;

                    foreach ($days as $item) {
                        $sched = array();
                        $uniqueSched = [];

                        foreach ($schedule as $scheditem) {
                            foreach ($scheditem->schedule as $scheddetail) {
                                if (in_array($daycount, $scheddetail->days)) {

                                    $key = $scheddetail->start . '_' . $scheddetail->end . '_' . $scheditem->subjdesc . '_' . $scheddetail->roomname . '_' . $scheddetail->teacher;

                                    if (!isset($uniqueSched[$key])) {
                                        $uniqueSched[$key] = (object)[
                                            'start' => $scheddetail->start,
                                            'end' => $scheddetail->end,
                                            'subject' => $scheditem->subjdesc,
                                            'room' => $scheddetail->roomname,
                                            'teacher' => $scheddetail->teacher
                                        ];
                                    }
                                }
                            }

                            $scheditem->subject = $scheditem->subjdesc;
                        }
                        $sched = array_values($uniqueSched);

                        $daydescription = strtolower($item->description);

                        array_push($schedule_holder, (object)[
                            'day' => $daydescription,
                            'sched' => $sched
                        ]);

                        $daycount += 1;
                    }

                    $studinfo = DB::table('studinfo')
                        ->select('sid', 'lastname', 'firstname')
                        ->where('id', $studid)
                        ->first();

                    $api_sched = array((object)[
                        'name' => $studinfo->firstname . ' ' . $studinfo->lastname,
                        'id' => $studinfo->sid,
                        'schedule' => $schedule_holder,
                        'allsched' => $schedule
                    ]);

                    return $api_sched;


                } elseif ($levelid >= 17 && $levelid <= 20) {

                        $schedule = self::spct_college_schedule($syid, $semid, $studid);

                        $days = DB::table('days')->get();
                        $schedule_holder = array();

                        $studinfo = DB::table('studinfo')
                        ->select('sid', 'lastname', 'firstname')
                        ->where('id', $studid)
                        ->first();

                        $api_sched = array((object)[
                            'name' => $studinfo->firstname . ' ' . $studinfo->lastname,
                            'id' => $studinfo->sid,
                            'schedule' => $schedule
                        ]);

                    return $api_sched;


                } else {
                    $schedule = self::get_schedule($levelid, $syid, $sectionid);

                    $days = DB::table('days')->get();
                    $schedule_holder = array();

                    $daycount = 1;

                    foreach ($days as $item) {
                        $sched = array();
                        $uniqueSched = [];

                        foreach ($schedule as $scheditem) {
                            foreach ($scheditem->schedule as $scheddetail) {
                                if (in_array($daycount, $scheddetail->days)) {

                                    $key = $scheddetail->start . '_' . $scheddetail->end . '_' . $scheditem->subjdesc . '_' . $scheddetail->roomname . '_' . $scheddetail->teacher;

                                    if (!isset($uniqueSched[$key])) {
                                        $uniqueSched[$key] = (object)[
                                            'start' => $scheddetail->start,
                                            'end' => $scheddetail->end,
                                            'subject' => $scheditem->subjdesc,
                                            'room' => $scheddetail->roomname,
                                            'teacher' => $scheddetail->teacher
                                        ];
                                    }
                                }
                            }

                            $scheditem->subject = $scheditem->subjdesc;
                        }
                        $sched = array_values($uniqueSched);

                        $daydescription = strtolower($item->description);

                        array_push($schedule_holder, (object)[
                            'day' => $daydescription,
                            'sched' => $sched
                        ]);

                        $daycount += 1;
                    }

                    $studinfo = DB::table('studinfo')
                        ->select('sid', 'lastname', 'firstname')
                        ->where('id', $studid)
                        ->first();

                    $api_sched = array((object)[
                        'name' => $studinfo->firstname . ' ' . $studinfo->lastname,
                        'id' => $studinfo->sid,
                        'schedule' => $schedule_holder,
                        'allsched' => $schedule
                    ]);

                    return $api_sched;
                    }
            }
        }
    }

//     public static function spct_collegestudentsched_plot($studid = null , $syid = null ,$semid = null){


//               $subjects = DB::table('college_studsched')
//                                 ->join('college_classsched',function($join) use($syid,$semid){
//                                       $join->on('college_studsched.schedid','=','college_classsched.id');
//                                       $join->where('college_classsched.deleted',0);
//                                       $join->where('college_classsched.syID',$syid);
//                                       $join->where('college_classsched.semesterID',$semid);
//                                 })
//                                 ->join('college_prospectus',function($join){
//                                       $join->on('college_classsched.subjectID','=','college_prospectus.id');
//                                       $join->where('college_prospectus.deleted',0);
//                                 })
//                                 ->join('college_sections',function($join){
//                                       $join->on('college_classsched.sectionID','=','college_sections.id');
//                                       $join->where('college_sections.deleted',0);
//                                 })
//                                 ->where('college_studsched.deleted',0)
//                                 ->where('college_studsched.studid',$studid)
//                                 ->select(
//                                       'lecunits',
//                                       'labunits',
//                                       'college_prospectus.subjectID as main_subjid',
//                                       'college_classsched.*',
//                                       'schedid',
//                                       'subjDesc',
//                                       'subjCode',
//                                       'sectionDesc',
//                                       'schedstatus'
//                                 )
//                                 ->get();

//               foreach($subjects as $item){

//                     $item->units = $item->lecunits + $item->labunits;

//                     $sched = DB::table('college_scheddetail')
//                                       ->where('college_scheddetail.headerid',$item->id)
//                                       ->where('college_scheddetail.deleted',0)
//                                       ->leftJoin('rooms',function($join){
//                                             $join->on('college_scheddetail.roomid','=','rooms.id');
//                                             $join->where('rooms.deleted',0);
//                                       })
//                                       ->join('days',function($join){
//                                             $join->on('college_scheddetail.day','=','days.id');
//                                       })
//                                       ->select(
//                                             'day',
//                                             'roomid',
//                                             'college_scheddetail.id as detailid',
//                                             'roomname',
//                                             'stime',
//                                             'etime',
//                                             'days.description',
//                                             'schedotherclass'
//                                       )
//                                       ->get();

//                     $item->teacher = null;
//                     $item->teacherid = null;

//                     if(isset($item->teacherID)){
//                           $temp_teacher = DB::table('teacher')
//                                             ->where('id',$item->teacherID)
//                                             ->first();
//                           $item->teacher = $temp_teacher->firstname.' '.$temp_teacher->middlename.' '.$temp_teacher->lastname;
//                           $item->teacherid = $temp_teacher->tid;
//                     }



//                     foreach($sched as $sched_item){
//                           $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
//                     }

//                     $starting = collect($sched)->groupBy('time');

//                     $sched_list = array();
//                     $sched_count = 1;

//                     foreach($starting as $sched_item){

//                           $dayString = '';
//                           $days = array();
//                           $schedstat = '';

//                           foreach($sched_item as $new_item){
//                                 $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
//                                 $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
//                                 $dayString.= substr($new_item->description, 0,3).' / ';
//                                 $detailid = $new_item->detailid;
//                                 $roomname = $new_item->roomname;
//                                 $roomid = $new_item->roomid;
//                                 $time = $new_item->time;
//                                 // $schedstat =  $item->schedstatus;
//                                 $schedotherclass = $new_item->schedotherclass;
//                                 array_push($days,$new_item->day);
//                           }

//                           $dayString = substr($dayString, 0 , -2);

//                           array_push($sched_list,(object)[
//                                 'day'=>$dayString,
//                                 'start'=>$start,
//                                 'end'=>$end,
//                                 'roomid',
//                                 'detailid'=>$detailid,
//                                 'roomname'=>$roomname,
//                                 'roomid'=>$roomid,
//                                 // 'teacher'=>$teacher,
//                                 // 'tid'=>$tid,
//                                 // 'teacherid'=>$teacherid,
//                                 'sched_count'=>$sched_count,
//                                 // 'schedstat'=>$schedstat,
//                                 'time'=>$time,
//                                 'days'=>$days,
//                                 'classification'=>$schedotherclass
//                           ]);


//                           $sched_count += 1;

//                     }
//                     $item->schedule = $sched_list;


//               }

//               return array((object)[
//                     'status'=>1,
//                     'data'=>'Successfull.',
//                     'info'=>$subjects
//               ]);




//   }


//     public static function spct_college_grade($studid = null,$syid = null,$semid = null){

//         $grades = DB::table('college_studentprospectus')
//                     ->where('deleted',0)
//                     ->where('studid',$studid)
//                     ->where('syid',$syid)
//                     ->where('semid',$semid)
//                     ->get();


//         $schedule = self::spct_collegestudentsched_plot($studid,$syid,$semid);

//         $temp_grades = array();

//         $transmutation = array(
//             (object)['gd'=>0.0,'gdto'=>number_format(73.99,2),'eq'=>number_format(5.0,1)],
//             (object)['gd'=>74.0,'gdto'=>number_format(74.49,2),'eq'=>number_format(5.0,1)],
//             (object)['gd'=>74.5,'gdto'=>number_format(75.99,2),'eq'=>number_format(3.5,1)],
//             (object)['gd'=>76.0,'gdto'=>number_format(76.99,2),'eq'=>number_format(3.4,1)],
//             (object)['gd'=>77.0,'gdto'=>number_format(77.99,2),'eq'=>number_format(3.3,1)],
//             (object)['gd'=>78.0,'gdto'=>number_format(78.99,2),'eq'=>number_format(3.2,1)],
//             (object)['gd'=>79.0,'gdto'=>number_format(79.99,2),'eq'=>number_format(3.1,1)],
//             (object)['gd'=>80.0,'gdto'=>number_format(80.99,2),'eq'=>number_format(3.0,1)],
//             (object)['gd'=>81.0,'gdto'=>number_format(81.99,2),'eq'=>number_format(2.9,1)],
//             (object)['gd'=>82.0,'gdto'=>number_format(82.99,2),'eq'=>number_format(2.8,1)],
//             (object)['gd'=>83.0,'gdto'=>number_format(83.99,2),'eq'=>number_format(2.7,1)],
//             (object)['gd'=>84.0,'gdto'=>number_format(84.99,2),'eq'=>number_format(2.6,1)],
//             (object)['gd'=>85.0,'gdto'=>number_format(85.99,2),'eq'=>number_format(2.5,1)],
//             (object)['gd'=>86.0,'gdto'=>number_format(86.99,2),'eq'=>number_format(2.4,1)],
//             (object)['gd'=>87.0,'gdto'=>number_format(87.99,2),'eq'=>number_format(2.3,1)],
//             (object)['gd'=>88.0,'gdto'=>number_format(88.99,2),'eq'=>number_format(2.2,1)],
//             (object)['gd'=>89.0,'gdto'=>number_format(89.99,2),'eq'=>number_format(2.1,1)],
//             (object)['gd'=>90.0,'gdto'=>number_format(90.99,2),'eq'=>number_format(2.0,1)],
//             (object)['gd'=>91.0,'gdto'=>number_format(91.99,2),'eq'=>number_format(1.9,1)],
//             (object)['gd'=>92.0,'gdto'=>number_format(92.99,2),'eq'=>number_format(1.8,1)],
//             (object)['gd'=>93.0,'gdto'=>number_format(93.99,2),'eq'=>number_format(1.7,1)],
//             (object)['gd'=>94.0,'gdto'=>number_format(94.99,2),'eq'=>number_format(1.6,1)],
//             (object)['gd'=>95.0,'gdto'=>number_format(95.99,2),'eq'=>number_format(1.5,1)],
//             (object)['gd'=>96.0,'gdto'=>number_format(96.99,2),'eq'=>number_format(1.4,1)],
//             (object)['gd'=>97.0,'gdto'=>number_format(97.99,2),'eq'=>number_format(1.3,1)],
//             (object)['gd'=>98.0,'gdto'=>number_format(98.99,2),'eq'=>number_format(1.2,1)],
//             (object)['gd'=>99.0,'gdto'=>number_format(99.99,2),'eq'=>number_format(1.1,1)],
//             (object)['gd'=>100.0,'gdto'=>number_format(100,2),'eq'=>number_format(1.0,1)]
//         );

//         foreach($schedule[0]->info as $item){
//             $check = collect($grades)->where('prospectusID',$item->subjectID)->first();
//             if(isset($check->id)){

//                 if($check->prelemstatus != 4){
//                     $check->prelemgrade = null;
//                 }
//                 if($check->midtermstatus != 4){
//                     $check->midtermgrade = null;
//                 }
//                 if($check->prefistatus != 4){
//                     $check->prefigrade = null;
//                 }
//                 if($check->finalstatus != 4){
//                     $check->finalgrade = null;
//                 }

//                 foreach (['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'] as $gradePeriod) {
//                     if (!is_null($check->$gradePeriod)) {
//                         $tem_transmuatation = collect($transmutation)
//                             ->where('gd', '<=', number_format($check->$gradePeriod, 2))
//                             ->where('gdto', '>=', number_format($check->$gradePeriod, 2))
//                             ->first();

//                         if (isset($tem_transmuatation->gd)) {
//                             $check->$gradePeriod = $tem_transmuatation->eq;

//                         } else {
//                             $check->$gradePeriod = null;

//                         }
//                     }
//                 }

//                 $check->subjdesc = $item->subjDesc;
//                 $check->subjcode = $item->subjCode;


//                 $tem_transmuatation = collect($transmutation)->where('gd','<=',number_format($check->finalgrade,2))->where('gdto','>=',number_format($check->finalgrade,2))->first();


//                 if(isset($tem_transmuatation->gd) && $check->finalgrade != null){


//                     $check->fgremarks = $tem_transmuatation->eq <= 3.5 ? 'FAILED' : 'PASSED';


//                 }else{
//                      $check->remarks = null;
//                 }

//                 array_push($temp_grades,$check);
//             }else{
//                 $item->finalgrade = null;
//                 array_push($temp_grades,$item);
//             }
//         }

//         return $temp_grades;

//     }


    public static function spct_collegestudentsched_plot($studid = null , $syid = null ,$semid = null){


        $subjects = DB::table('college_studsched')
                        ->join('college_classsched',function($join) use($syid,$semid){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted',0);
                                $join->where('college_classsched.syID',$syid);
                                $join->where('college_classsched.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join){
                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                $join->where('college_prospectus.deleted',0);
                        })
                        ->join('college_sections',function($join){
                                $join->on('college_classsched.sectionID','=','college_sections.id');
                                $join->where('college_sections.deleted',0);
                        })
                        ->where('college_studsched.deleted',0)
                        ->where('college_studsched.studid',$studid)
                        ->select(
                                'lecunits',
                                'labunits',
                                'college_prospectus.subjectID as main_subjid',
                                'college_classsched.*',
                                'schedid',
                                'subjDesc',
                                'subjCode',
                                'sectionDesc',
                                'schedstatus'
                        )
                        ->get();

            return $subjects;


    }


    public static function spct_college_grade($studid = null,$syid = null,$semid = null){

    $grades = DB::table('college_studentprospectus')
            ->where('deleted',0)
            ->where('studid',$studid)
            ->where('syid',$syid)
            ->where('semid',$semid)
            ->get();


    $schedule = self::spct_collegestudentsched_plot($studid,$syid,$semid);

    $temp_grades = array();

    $transmutation = array(
            (object)['gd'=>0.0,'gdto'=>number_format(73.99,2),'eq'=>number_format(5.0,1)],
            (object)['gd'=>74.0,'gdto'=>number_format(74.49,2),'eq'=>number_format(5.0,1)],
            (object)['gd'=>74.5,'gdto'=>number_format(75.99,2),'eq'=>number_format(3.5,1)],
            (object)['gd'=>76.0,'gdto'=>number_format(76.99,2),'eq'=>number_format(3.4,1)],
            (object)['gd'=>77.0,'gdto'=>number_format(77.99,2),'eq'=>number_format(3.3,1)],
            (object)['gd'=>78.0,'gdto'=>number_format(78.99,2),'eq'=>number_format(3.2,1)],
            (object)['gd'=>79.0,'gdto'=>number_format(79.99,2),'eq'=>number_format(3.1,1)],
            (object)['gd'=>80.0,'gdto'=>number_format(80.99,2),'eq'=>number_format(3.0,1)],
            (object)['gd'=>81.0,'gdto'=>number_format(81.99,2),'eq'=>number_format(2.9,1)],
            (object)['gd'=>82.0,'gdto'=>number_format(82.99,2),'eq'=>number_format(2.8,1)],
            (object)['gd'=>83.0,'gdto'=>number_format(83.99,2),'eq'=>number_format(2.7,1)],
            (object)['gd'=>84.0,'gdto'=>number_format(84.99,2),'eq'=>number_format(2.6,1)],
            (object)['gd'=>85.0,'gdto'=>number_format(85.99,2),'eq'=>number_format(2.5,1)],
            (object)['gd'=>86.0,'gdto'=>number_format(86.99,2),'eq'=>number_format(2.4,1)],
            (object)['gd'=>87.0,'gdto'=>number_format(87.99,2),'eq'=>number_format(2.3,1)],
            (object)['gd'=>88.0,'gdto'=>number_format(88.99,2),'eq'=>number_format(2.2,1)],
            (object)['gd'=>89.0,'gdto'=>number_format(89.99,2),'eq'=>number_format(2.1,1)],
            (object)['gd'=>90.0,'gdto'=>number_format(90.99,2),'eq'=>number_format(2.0,1)],
            (object)['gd'=>91.0,'gdto'=>number_format(91.99,2),'eq'=>number_format(1.9,1)],
            (object)['gd'=>92.0,'gdto'=>number_format(92.99,2),'eq'=>number_format(1.8,1)],
            (object)['gd'=>93.0,'gdto'=>number_format(93.99,2),'eq'=>number_format(1.7,1)],
            (object)['gd'=>94.0,'gdto'=>number_format(94.99,2),'eq'=>number_format(1.6,1)],
            (object)['gd'=>95.0,'gdto'=>number_format(95.99,2),'eq'=>number_format(1.5,1)],
            (object)['gd'=>96.0,'gdto'=>number_format(96.99,2),'eq'=>number_format(1.4,1)],
            (object)['gd'=>97.0,'gdto'=>number_format(97.99,2),'eq'=>number_format(1.3,1)],
            (object)['gd'=>98.0,'gdto'=>number_format(98.99,2),'eq'=>number_format(1.2,1)],
            (object)['gd'=>99.0,'gdto'=>number_format(99.99,2),'eq'=>number_format(1.1,1)],
            (object)['gd'=>100.0,'gdto'=>number_format(100,2),'eq'=>number_format(1.0,1)]
        );

    foreach ($schedule as $item) {
        $check = collect($grades)->where('prospectusID',$item->subjectID)->first();
        if(isset($check->id)){
            $check->subjdesc = $item->subjDesc;
            $check->subjcode = $item->subjCode;
            array_push($temp_grades,$check);

            foreach (['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'] as $gradePeriod) {
                if (!is_null($check->$gradePeriod)) {
                    $tem_transmuatation = collect($transmutation)
                        ->where('gd', '<=', number_format($check->$gradePeriod, 2))
                        ->where('gdto', '>=', number_format($check->$gradePeriod, 2))
                        ->first();

                        if (isset($tem_transmuatation->gd)) {
                            $check->$gradePeriod = $tem_transmuatation->eq;

                        } else {
                            $check->$gradePeriod = null;

                        }
                    }
                }

                $check->subjdesc = $item->subjDesc;
                $check->subjcode = $item->subjCode;


                $tem_transmuatation = collect($transmutation)->where('gd','<=',number_format($check->finalgrade,2))->where('gdto','>=',number_format($check->finalgrade,2))->first();


                if(isset($tem_transmuatation->gd) && $check->finalgrade != null){


                    $check->fgremarks = $tem_transmuatation->eq <= 3.5 ? 'FAILED' : 'PASSED';


                }else{
                     $check->remarks = null;
                }
        }else{
            $item->finalgrade = null;
            array_push($temp_grades,$item);
        }
    }

    return $temp_grades;

    }

    public static function apmc_college_grade($studid = null,$syid = null,$semid = null){

        $grades = DB::table('college_studentprospectus')
                    ->where('deleted',0)
                    ->where('studid',$studid)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->get();


        $schedule = \App\Http\Controllers\SuperAdminController\StudentLoading::collegestudentsched_plot($studid,$syid,$semid);

        $temp_grades = array();

        $transmutation = array(
            (object)['gd'=>0.0,'gdto'=>number_format(73.99,2),'eq'=>number_format(5.0,1)],
            (object)['gd'=>74.0,'gdto'=>number_format(74.49,2),'eq'=>number_format(5.0,1)],
            (object)['gd'=>74.5,'gdto'=>number_format(75.99,2),'eq'=>number_format(3.5,1)],
            (object)['gd'=>76.0,'gdto'=>number_format(76.99,2),'eq'=>number_format(3.4,1)],
            (object)['gd'=>77.0,'gdto'=>number_format(77.99,2),'eq'=>number_format(3.3,1)],
            (object)['gd'=>78.0,'gdto'=>number_format(78.99,2),'eq'=>number_format(3.2,1)],
            (object)['gd'=>79.0,'gdto'=>number_format(79.99,2),'eq'=>number_format(3.1,1)],
            (object)['gd'=>80.0,'gdto'=>number_format(80.99,2),'eq'=>number_format(3.0,1)],
            (object)['gd'=>81.0,'gdto'=>number_format(81.99,2),'eq'=>number_format(2.9,1)],
            (object)['gd'=>82.0,'gdto'=>number_format(82.99,2),'eq'=>number_format(2.8,1)],
            (object)['gd'=>83.0,'gdto'=>number_format(83.99,2),'eq'=>number_format(2.7,1)],
            (object)['gd'=>84.0,'gdto'=>number_format(84.99,2),'eq'=>number_format(2.6,1)],
            (object)['gd'=>85.0,'gdto'=>number_format(85.99,2),'eq'=>number_format(2.5,1)],
            (object)['gd'=>86.0,'gdto'=>number_format(86.99,2),'eq'=>number_format(2.4,1)],
            (object)['gd'=>87.0,'gdto'=>number_format(87.99,2),'eq'=>number_format(2.3,1)],
            (object)['gd'=>88.0,'gdto'=>number_format(88.99,2),'eq'=>number_format(2.2,1)],
            (object)['gd'=>89.0,'gdto'=>number_format(89.99,2),'eq'=>number_format(2.1,1)],
            (object)['gd'=>90.0,'gdto'=>number_format(90.99,2),'eq'=>number_format(2.0,1)],
            (object)['gd'=>91.0,'gdto'=>number_format(91.99,2),'eq'=>number_format(1.9,1)],
            (object)['gd'=>92.0,'gdto'=>number_format(92.99,2),'eq'=>number_format(1.8,1)],
            (object)['gd'=>93.0,'gdto'=>number_format(93.99,2),'eq'=>number_format(1.7,1)],
            (object)['gd'=>94.0,'gdto'=>number_format(94.99,2),'eq'=>number_format(1.6,1)],
            (object)['gd'=>95.0,'gdto'=>number_format(95.99,2),'eq'=>number_format(1.5,1)],
            (object)['gd'=>96.0,'gdto'=>number_format(96.99,2),'eq'=>number_format(1.4,1)],
            (object)['gd'=>97.0,'gdto'=>number_format(97.99,2),'eq'=>number_format(1.3,1)],
            (object)['gd'=>98.0,'gdto'=>number_format(98.99,2),'eq'=>number_format(1.2,1)],
            (object)['gd'=>99.0,'gdto'=>number_format(99.99,2),'eq'=>number_format(1.1,1)],
            (object)['gd'=>100.0,'gdto'=>number_format(100,2),'eq'=>number_format(1.0,1)]
        );

        foreach($schedule[0]->info as $item){
            $check = collect($grades)->where('prospectusID',$item->subjectID)->first();
            if(isset($check->id)){

                if($check->prelemstatus != 4){
                    $check->prelemgrade = null;
                }
                if($check->midtermstatus != 4){
                    $check->midtermgrade = null;
                }
                if($check->prefistatus != 4){
                    $check->prefigrade = null;
                }
                if($check->finalstatus != 4){
                    $check->finalgrade = null;
                }

                foreach (['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'] as $gradePeriod) {
                    if (!is_null($check->$gradePeriod)) {
                        $tem_transmuatation = collect($transmutation)
                            ->where('gd', '<=', number_format($check->$gradePeriod, 2))
                            ->where('gdto', '>=', number_format($check->$gradePeriod, 2))
                            ->first();

                        if (isset($tem_transmuatation->gd)) {
                            $check->$gradePeriod = $tem_transmuatation->eq;

                        } else {
                            $check->$gradePeriod = null;

                        }
                    }
                }

                $check->subjdesc = $item->subjDesc;
                $check->subjcode = $item->subjCode;


                $tem_transmuatation = collect($transmutation)->where('gd','<=',number_format($check->finalgrade,2))->where('gdto','>=',number_format($check->finalgrade,2))->first();


                if(isset($tem_transmuatation->gd) && $check->finalgrade != null){


                    $check->fgremarks = $tem_transmuatation->eq <= 3.5 ? 'FAILED' : 'PASSED';


                }else{
                     $check->grade = null;
                     $check->remarks = null;
                }

                array_push($temp_grades,$check);
            }else{
                $item->finalgrade = null;
                array_push($temp_grades,$item);
            }
        }

        return $temp_grades;

    }


    public function api_get_grades(Request $request)
    {

        $token = $request->get('token');
		$studid = $request->get('studid');
		$gradelevel = $request->get('gradelevel');
		$syid = $request->get('syid');
		$sectionid = $request->get('sectionid');
	    $strand = $request->get('strand');
	    $semid = $request->get('semid');

        $schoolInfo = DB::table('schoolinfo')->first();
        $abbreviation = $schoolInfo->abbreviation;

	    if($gradelevel == 14 || $gradelevel == 15){

            $studgrades = \App\Http\Controllers\APIMobileGradesController::sf9_grades( $gradelevel,$studid,$syid,$strand,$semid,$sectionid,true, null, 7);
            // return $studgrades;
            $temp_grades = array();
            $finalgrade = array();
            foreach($studgrades as $item){
                if($item->id == 'G1'){
                    array_push($finalgrade,$item);
                }else{
                    if($item->strandid == $strand){
                        array_push($temp_grades,$item);
                    }
                    if($item->strandid == null){
                        array_push($temp_grades,$item);
                    }
                }
            }
            $studgrades = $temp_grades;
            $studgrades = collect($studgrades)->where('isVisible','1')->values();
            $studgrades = collect($studgrades)->sortBy('sortid')->values();
            $subjects = \App\Http\Controllers\APIMobileGradesController::sf9_subjects_sh($gradelevel,$syid);

            return array((object)[
                'grades'=>$studgrades,
                'subjects'=>$subjects ?? [],
                'finalgrade'=>$finalgrade ?? [],
            ]);

        }elseif ($gradelevel >= 17 && $gradelevel <= 21) {

            if (!in_array($abbreviation, ['DCC', 'APMC', 'SPCT'])) {
            $semid = $request->get('semid');
            $studgrades = self::college_grade($studid,$syid,$semid);


            $setup = DB::table('semester_setup')
            ->where('deleted',0)
            ->get();



            $subjects = array();

            foreach($studgrades as $grade){

                $grade->subjdesc = $grade->subjDesc;
                $grade->subjcode = $grade->subjCode;

                array_push($subjects, (object)[
                    'subjdesc'=> $grade->subjDesc,
                    'subjcode'=> $grade->subjCode
                ]);
            }

            return array((object)[
                'grades'=>$studgrades,
                'subjects'=>$subjects,
                'setup'=>$setup,
                'finalgrade'=>[],
            ]);

        } else {

            if (in_array($abbreviation, ['DCC'])) {

                $studgrades = array();
                $setup = array();
                $sumsetup = array();
                $clsetup = array();
                $ageevaldate = array();
                $remarks_setup = array();

                $check_enrollment = DB::table('enrolledstud')
                                        ->where('studid',$studid)
                                        ->where('syid',$syid)
                                        ->where('deleted',0)
                                        ->first();

                if(!isset($check_enrollment->id)){
                    $check_enrollment = DB::table('sh_enrolledstud')
                                            ->where('studid',$studid)
                                            ->where('syid',$syid)
                                            ->where('deleted',0)
                                            ->first();

                    if(isset($check_enrollment->id)){
                        $semid = $check_enrollment->semid;
                        $strandid = $check_enrollment->strandid;
                    }
                }
                if(!isset($check_enrollment->id)){
                    $check_enrollment = DB::table('college_enrolledstud')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->select(
                            '*',
                            'yearLevel as levelid',
                            'sectionID as sectionid'
                        )
                        ->first();

                    if(isset($check_enrollment->id)){
                        $semid = $check_enrollment->semid;
                    }
                }

                if(!isset($check_enrollment->id)){
                    return array((object)[
                        'levelid'=>null,
                        'grades'=>array()
                    ]);
                }

                $levelid = $check_enrollment->levelid;
                $sectionid = $check_enrollment->sectionid;

                $semid = $request->get('semid');
                $studgrades = self::dcc_college_grade($studid,$syid,$semid);
                $setup = DB::table('college_gradesetup')
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->get();

                return array((object)[
                    'levelid'=>$levelid,
                    'grades'=>$studgrades,
                    'setup'=>$setup,
                ]);

            } elseif (in_array($abbreviation, ['APMC'])) {

                $semid = null;
                $strandid = null;
                $studgrades = array();
                $setup = array();
                $sumsetup = array();
                $clsetup = array();
                $ageevaldate = array();
                $remarks_setup = array();

                $school = DB::table('schoolinfo')->first()->abbreviation;

                $check_enrollment = DB::table('enrolledstud')
                                        ->where('studid',$studid)
                                        ->where('syid',$syid)
                                        ->where('deleted',0)
                                        ->first();

                if(!isset($check_enrollment->id)){
                    $check_enrollment = DB::table('sh_enrolledstud')
                                            ->where('studid',$studid)
                                            ->where('syid',$syid)
                                            ->where('deleted',0)
                                            ->first();

                    if(isset($check_enrollment->id)){
                        $semid = $check_enrollment->semid;
                        $strandid = $check_enrollment->strandid;
                    }
                }
                if(!isset($check_enrollment->id)){
                    $check_enrollment = DB::table('college_enrolledstud')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->select(
                            '*',
                            'yearLevel as levelid',
                            'sectionID as sectionid'
                        )
                        ->first();

                    if(isset($check_enrollment->id)){
                        $semid = $check_enrollment->semid;
                    }
                }


                if(!isset($check_enrollment->id)){
                    return array((object)[
                        'levelid'=>null,
                        'grades'=>array()
                    ]);
                }

                $levelid = $check_enrollment->levelid;
                $sectionid = $check_enrollment->sectionid;

                $semid = $request->get('semid');
                $studgrades = self::apmc_college_grade($studid,$syid,$semid);
                $setup = DB::table('college_gradesetup')
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->get();

                return array((object)[
                    'levelid'=>$levelid,
                    'grades'=>$studgrades,
                    'setup'=>$setup,
                    'sumsetup'=>$sumsetup,
                    'clsetup'=>$clsetup,
                    'agevaldate'=>$ageevaldate,
                    'remarks_setup'=>$remarks_setup
                ]);



            } else {
                $semid = null;
                $strandid = null;
                $studgrades = array();


                $school = DB::table('schoolinfo')->first()->abbreviation;

                $check_enrollment = DB::table('enrolledstud')
                                        ->where('studid',$studid)
                                        ->where('syid',$syid)
                                        ->where('deleted',0)
                                        ->first();

                if(!isset($check_enrollment->id)){
                    $check_enrollment = DB::table('sh_enrolledstud')
                                            ->where('studid',$studid)
                                            ->where('syid',$syid)
                                            ->where('deleted',0)
                                            ->first();

                    if(isset($check_enrollment->id)){
                        $semid = $check_enrollment->semid;
                        $strandid = $check_enrollment->strandid;
                    }
                }
                if(!isset($check_enrollment->id)){
                    $check_enrollment = DB::table('college_enrolledstud')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->select(
                            '*',
                            'yearLevel as levelid',
                            'sectionID as sectionid'
                        )
                        ->first();

                    if(isset($check_enrollment->id)){
                        $semid = $check_enrollment->semid;
                    }
                }


                if(!isset($check_enrollment->id)){
                    return array((object)[
                        'levelid'=>null,
                        'grades'=>array()
                    ]);
                }

                $levelid = $check_enrollment->levelid;
                $sectionid = $check_enrollment->sectionid;

                $semid = $request->get('semid');
                $studgrades = self::spct_college_grade($studid,$syid,$semid);
                $setup = DB::table('college_gradesetup')
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->get();

                return array((object)[
                    'levelid'=>$levelid,
                    'grades'=>$studgrades,



                ]);
            }



        }



        }else{

            $studgrades = \App\Http\Controllers\APIMobileGradesController::sf9_grades( $gradelevel,$studid,$syid,null,null,$sectionid,true, null, 7);
            $subjects = \App\Http\Controllers\APIMobileGradesController::sf9_subjects($gradelevel,$syid);

            $grades = $studgrades;
            $grades = collect($grades)->sortBy('sortid')->values();
            $finalgrade = collect($grades)->where('id','G1')->values();
            unset($grades[count($grades)-1]);
            $studgrades = collect($grades)->where('isVisible','1')->values();

            return array((object)[
                'grades'=>$studgrades,
                'subjects'=>$subjects ?? [],
                'finalgrade'=>$finalgrade ?? [],
            ]);

        }

    }


	public function api_login(Request $request)
	{
	  $user = $request->get('username');
	  $pword = $request->get('pword');
	  $token = $request->get('token');

	  $user = db::table('users')
		  ->where('email', $user)
		  ->where('deleted', 0)
		  ->first();

	  if($user)
	  {
		  if(Hash::check($pword, $user->password))
		  {
			  db::table('users')
				  ->where('id', $user->id)
				  ->update([
					  'remember_token' => $token
				  ]);

			  $studsid = str_replace('P','', $request->get('username'));
			  $studsid = str_replace('S','',$studsid);

			  $studid = DB::table('studinfo')->where('sid',$studsid)->first()->id;


			  $stud = db::table('studinfo')
                  ->leftJoin('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
                  ->leftJoin('nationality', 'studinfo.nationality', '=', 'nationality.id')
				  ->where('studinfo.id', $studid)
                  ->select('studinfo.*','nationality.nationality as nationalityDesc', 'college_courses.courseDesc')
				  ->first();


			//   $stud = db::table('studinfo')
            //       ->join('nationality', 'studinfo.nationality', '=', 'nationality.id')
			// 	  ->where('studinfo.id', $studid)
            //       ->select('studinfo.*','nationality.nationality as nationalityDesc')
			// 	  ->first();

            // $stud = db::table('studinfo')
			// 	  ->where('id', $studid)
			// 	  ->first();

			  $sy = db::table('sy')
				  ->where('isactive', 1)
				  ->orderBy('sydesc')
				  ->get();

			  $semester = db::table('semester')
				  ->where('isactive', 1)
				  ->get();

			  if($stud)
			  {
				  $data = array(
					  'stud' => $stud,
					  'sy' => $sy,
					  'sem' => $semester,
					  'userlogin' => $user,
					  'user_id'=>$user->id,
					  'token' => $token

				  );

				  return json_encode($data);
			  }



		  }
	  }
	}

    public function api_enrolledstud(Request $request)
    {
        $studid = $request->get('studid');

        $enrolledstud = DB::table('enrolledstud')
            ->leftJoin('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->leftJoin('sections', 'enrolledstud.sectionid', '=', 'sections.id')
            ->leftJoin('studentstatus', 'enrolledstud.studstatus', '=', 'studentstatus.id')
            ->leftJoin('sy', 'enrolledstud.syid', '=', 'sy.id')
            ->where('studid', $studid)
            ->where('enrolledstud.deleted', 0)
            ->select('enrolledstud.*', 'gradelevel.levelname', 'gradelevel.acadprogid', 'studentstatus.description', 'sections.sectionname', 'sy.sydesc')
            ->get();

        $sh_enrolledstud = DB::table('sh_enrolledstud')
            ->leftJoin('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
            ->leftJoin('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
            ->leftJoin('studentstatus', 'sh_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->leftJoin('semester', 'sh_enrolledstud.semid', '=', 'semester.id')
            ->leftJoin('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
            ->where('studid', $studid)
            ->where('sh_enrolledstud.deleted', 0)
            ->select('sh_enrolledstud.*', 'gradelevel.levelname', 'gradelevel.acadprogid', 'sh_strand.strandname', 'studentstatus.description', 'semester.semester', 'sy.sydesc')
            ->get();

        $college_enrolledstud = DB::table('college_enrolledstud')
            ->leftJoin('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->leftJoin('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
            ->leftJoin('studentstatus', 'college_enrolledstud.studstatus', '=', 'studentstatus.id')
            ->leftJoin('semester', 'college_enrolledstud.semid', '=', 'semester.id')
            ->leftJoin('sy', 'college_enrolledstud.syid', '=', 'sy.id')
            ->where('studid', $studid)
            ->where('college_enrolledstud.deleted', 0)
            ->select('college_enrolledstud.*', 'college_enrolledstud.yearLevel as levelid', 'gradelevel.acadprogid', 'college_courses.courseDesc', 'gradelevel.levelname', 'studentstatus.description', 'semester.semester' , 'sy.sydesc')
            ->get();



        $enrolledstud_info = collect($enrolledstud)->merge($sh_enrolledstud)->merge($college_enrolledstud)->sortBy('syid')->values();






        return response()->json([
            'enrolledstud_info' => $enrolledstud_info

        ]);
    }

    public static function enrollment_history(Request $request){

        $studid = $request->get('studid');
        $courseid = $request->get('courseid');

        $enrollment_list = array();

        $get_enrollment = DB::table('enrolledstud')
            ->where('studid',$studid)
            ->where('enrolledstud.deleted',0)
            ->join('sections',function($join){
                    $join->on('enrolledstud.sectionid','=','sections.id');
            })
            ->join('gradelevel',function($join){
                    $join->on('enrolledstud.levelid','=','gradelevel.id');
            })
            ->join('sy',function($join){
                    $join->on('enrolledstud.syid','=','sy.id');
            })
            ->select(
                    'acadprogid',
                    'dateenrolled',
                    'enrolledstud.levelid',
                    'syid',
                    'sydesc',
                    'levelname',
                    'sectionname',
                    'sy.isactive',
                    'sectionid'
            )
            ->get();

        foreach($get_enrollment as $item){

              $item->semid = 1;
              $item->dateenrolled = \Carbon\Carbon::create($item->dateenrolled)->isoFormat('MMM DD, YYYY');
              array_push($enrollment_list,$item);
        }

        $get_enrollment = DB::table('sh_enrolledstud')
                                ->where('studid',$studid)
                                ->where('sh_enrolledstud.deleted',0)
                                ->join('sections',function($join){
                                      $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                })
                                ->join('gradelevel',function($join){
                                      $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                })
                                ->join('sh_strand',function($join){
                                      $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                      $join->where('sh_enrolledstud.deleted',0);
                                })
                                ->join('sy',function($join){
                                      $join->on('sh_enrolledstud.syid','=','sy.id');
                                })
                                ->join('semester',function($join){
                                      $join->on('sh_enrolledstud.semid','=','semester.id');
                                })
                                ->select(
                                      'semid',
                                      'acadprogid',
                                      'dateenrolled',
                                      'sh_enrolledstud.levelid',
                                      'strandcode',
                                      'syid',
                                      'sydesc',
                                      'levelname',
                                      'sectionname',
                                      'semester',
                                      'sy.isactive',
                                      'sectionid',
                                      'sh_enrolledstud.strandid'
                                )
                                ->get();

        foreach($get_enrollment as $item){
              $item->semester = str_replace(" Semester"," Sem",$item->semester);
              $item->dateenrolled = \Carbon\Carbon::create($item->dateenrolled)->isoFormat('MMM DD, YYYY');
              array_push($enrollment_list,$item);
        }


        $get_enrollment = DB::table('college_enrolledstud')
                                ->where('studid',$studid)
                                ->where('college_enrolledstud.deleted',0)
                                ->join('college_sections',function($join){
                                      $join->on('college_enrolledstud.sectionid','=','college_sections.id');
                                })
                                ->join('college_classsched',function($join){
                                      $join->on('college_sections.id','=','college_classsched.sectionid');
                                })
                                ->join('gradelevel',function($join){
                                      $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                })
                                ->join('sy',function($join){
                                      $join->on('college_enrolledstud.syid','=','sy.id');
                                })
                                ->join('college_courses',function($join){
                                      $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                      $join->where('college_courses.deleted',0);
                                })
                                ->join('semester',function($join){
                                      $join->on('college_enrolledstud.semid','=','semester.id');
                                })
                                ->select(
                                      'semid',
                                      'semester',
                                      'acadprogid',
                                      'courseabrv',
                                      'date_enrolled as dateenrolled',
                                      'college_enrolledstud.yearLevel as levelid',
                                      'college_enrolledstud.syid',
                                      'sydesc',
                                      'levelname',
                                      'sectionDesc as sectionname',
                                      'college_classsched.id as schedid',
                                      'sy.isactive'
                                )
                                ->get();


        $collegesection = DB::table('college_schedgroup_detail')
                                ->where('college_schedgroup_detail.deleted',0)
                                ->whereIn('schedid',collect($get_enrollment)->pluck('schedid'))
                                ->join('college_schedgroup',function($join){
                                      $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                      $join->where('college_schedgroup.deleted',0);
                                })
                                ->leftJoin('college_courses',function($join){
                                      $join->on('college_schedgroup.courseid','=','college_courses.id');
                                      $join->where('college_courses.deleted',0);
                                })
                                ->leftJoin('gradelevel',function($join){
                                      $join->on('college_schedgroup.levelid','=','gradelevel.id');
                                      $join->where('gradelevel.deleted',0);
                                })
                                ->leftJoin('college_colleges',function($join){
                                      $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                                      $join->where('college_colleges.deleted',0);
                                })
                                ->select(
                                      'college_schedgroup.courseid',
                                      'college_schedgroup.levelid',
                                      'college_schedgroup.collegeid',
                                      'courseDesc',
                                      'collegeDesc',
                                      'levelname',
                                      'courseabrv',
                                      'collegeabrv',
                                      'college_schedgroup.id',
                                      'college_schedgroup.schedgroupdesc',
                                      'schedgroupdesc as text',
                                      'schedid'
                                )
                                ->get();

        $collegeid = DB::table('college_courses')
                                ->where('id',$courseid)
                                ->select('collegeid')
                                ->first();

        foreach($get_enrollment as $item){


              $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('courseid',$courseid)->values();

              if(count($checkcoursegroup) != 0){
                    $text = $checkcoursegroup[0]->courseabrv;
                    $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                    $item->sectionname = $text;
              }else{
                    if(isset($collegeid->id)){
                          $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('collegeid',$collegeid->id)->values();
                          if(count($checkcoursegroup) != 0){
                                $text = $checkcoursegroup[0]->collegeabrv;
                                $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                $item->sectionname = $text;
                          }else{
                                $item->sectionname = 'Not Found';
                          }
                    }else{
                          $item->sectionname = 'Not Found';
                    }
              }


              $item->semester = str_replace(" Semester"," Sem",$item->semester);
              $item->levelname = str_replace(" COLLEGE","",$item->levelname);
              $item->dateenrolled = \Carbon\Carbon::create($item->dateenrolled)->isoFormat('MMM DD, YYYY');
              array_push($enrollment_list,$item);
        }

        return $enrollment_list;

  }

  	public function api_enrollmentinfo(Request $request)
	{
		$studid = $request->get('studid');


// 		$enrollmentinfo = collect();

// 		$einfo1 = db::table('enrolledstud')
// 			->select(db::raw('enrolledstud.`id`, sy.`sydesc`, enrolledstud.syid, ghssemid as semid, semester.semester as semdesc, acadprogid, sy.isactive as syactive, semester.isactive as semactive'))
// 			->join('sy', 'enrolledstud.syid', '=', 'sy.id')
// 			->join('semester', 'enrolledstud.ghssemid', '=', 'semester.id')
// 			->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
// 			->where('studid', $studid)
// 			->where('enrolledstud.deleted', 0)
// 			->groupBy('syid')
// 			->get();

// 		$einfo2 = db::table('sh_enrolledstud')
// 			->select(db::raw('sh_enrolledstud.id, sy.sydesc, semester.semester, sh_enrolledstud.syid, sh_enrolledstud.semid, semester.semester as semdesc, acadprogid, sy.isactive as syactive, semester.isactive as semactive'))
// 			->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
// 			->join('semester', 'sh_enrolledstud.semid', '=', 'semester.id')
// 			->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
// 			->where('sh_enrolledstud.deleted', 0)
// 			->where('sh_enrolledstud.studid', $studid)
// 			->groupBy('syid', 'semid')
// 			->get();

// 		$einfo3 = db::table('college_enrolledstud')
// 			->select(db::raw('college_enrolledstud.`id`, sy.`sydesc`, semester.`semester`, college_enrolledstud.syid, college_enrolledstud.semid, semester.semester as semdesc, acadprogid, sy.isactive as syactive, semester.isactive as semactive'))
// 			->join('sy', 'college_enrolledstud.syid', '=', 'sy.id')
// 			->join('semester', 'college_enrolledstud.semid', '=', 'semester.id')
// 			->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
// 			->where('college_enrolledstud.deleted', 0)
// 			->where('college_enrolledstud.studid', $studid)
// 			->groupBy('syid', 'semid')
// 			->get();

// 		$enrollmentinfo = $enrollmentinfo->merge($einfo1);
// 		$enrollmentinfo = $enrollmentinfo->merge($einfo2);
// 		$enrollmentinfo = $enrollmentinfo->merge($einfo3);


            $enrollmentinfo = self::enrollment_history($request);


        if ($enrollmentinfo == null)
        {
            $studinfo = DB::table('studinfo')
                ->select(db::raw('studinfo.id as studid, lastname, firstname, middlename, levelid, levelname, suffix'))
                ->innerJoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->where('id', $studid)
                ->first();

            $levelname = '';
            $coursesection = '';

            if($studinfo != null)
            {
                if($studinfo->levelid >= 17 && $studinfo->levelid <= 21){
                    $courses = db::table('college_courses')
                        ->where('id', $studinfo->courseid)
                        ->first();

                    if($courses != null)
                    {
                        $coursesection = $courses->courseabrv;
                    }
                }
            }

            $enrollmentinfo = [
                'studid' => $studid,
                'lastname' => $studinfo->lastname,
                'firstname' => $studinfo->firstname,
                'middlename' => $studinfo->middlename,
                'suffix' => $studinfo->suffix,
                'levelid' => $studinfo->levelid,
                'levelname' => $levelname,
                'coursesection' => $coursesection
            ];
        }

        return $enrollmentinfo;
	}

	public function api_enrollmentdata(Request $request)
	{
		$studid = $request->get('studid');
		$syid = $request->get('syid');
		$semid = $request->get('semid');

		$einfo = db::table('enrolledstud')
			->select(db::raw('enrolledstud.`id`, levelname, sections.id as sectionid, sections.sectionname, grantee.`description` AS grantee, studentstatus.`description` as studstatus, teacher.lastname as teacherlastname, teacher.firstname as teacherfirstname, teacher.middlename as teachermiddlename, title as teachertitle, enrolledstud.levelid, sydesc, enrolledstud.syid, semester.semester as semdesc, ghssemid as semid, nationality.`nationality`'))
			->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
			->join('nationality', 'studinfo.nationality', '=', 'nationality.id')
			->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
			->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
			->join('sectiondetail', 'sections.id', '=', 'sectiondetail.sectionid')
			->join('teacher', 'sectiondetail.teacherid', '=', 'teacher.id')
			->join('grantee', 'enrolledstud.grantee', 'grantee.id')
			->join('studentstatus', 'enrolledstud.studstatus', '=', 'studentstatus.id')
			->join('sy', 'enrolledstud.syid', '=', 'sy.id')
			->join('semester', 'enrolledstud.ghssemid', '=', 'semester.id')
			->where('studid', $studid)
			->where('enrolledstud.deleted', 0)
			->where('enrolledstud.syid', $syid)
			->where(function($q) use($semid){
				if($semid == 3)
				{
					$q->where('ghssemid', 3);
				}
				else
				{
					$q->where('ghssemid', '!=', 3);
				}
			})
			->first();

		if($einfo)
		{
			$info = collect($einfo);
			return $info;
		}
		else
		{
			$einfo = db::table('sh_enrolledstud')
				->select(db::raw('sh_enrolledstud.`id`, levelname, sections.sectionname, sections.id as sectionid, grantee.`description` AS grantee, studentstatus.`description`, sh_strand.id as strandid, strandcode, teacher.lastname as teacherlastname, teacher.firstname as teacherfirstname, teacher.middlename as teachermiddlename, title as teachertitle, sh_enrolledstud.levelid, sydesc, sh_enrolledstud.syid, sh_enrolledstud.semid, semester.semester as semdesc, nationality.`nationality`'))
				->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
				->join('nationality', 'studinfo.nationality', '=', 'nationality.id')
				->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
				->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
				->join('sectiondetail', 'sections.id', '=', 'sectiondetail.sectionid')
				->join('teacher', 'sectiondetail.teacherid', '=', 'teacher.id')
				->join('grantee', 'sh_enrolledstud.grantee', 'grantee.id')
				->join('studentstatus', 'sh_enrolledstud.studstatus', '=', 'studentstatus.id')
				->join('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
				->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
				->join('semester', 'sh_enrolledstud.semid', 'semester.id')
				->where('studid', $studid)
				->where('sh_enrolledstud.deleted', 0)
				->where('sh_enrolledstud.syid', $syid)
				->where(function($q) use($semid){
					if($semid == 3)
					{
						$q->where('sh_enrolledstud.semid', 3);
					}
					else
					{
						if(db::table('schoolinfo')->first()->shssetup == 0)
						{
							$q->where('sh_enerolledstud.semid', $semid);
						}
						else
						{
							$q->where('sh_enrolledstud.semid','!=', 3);
						}
					}
				})
				->first();

			if($einfo)
			{
				$info = collect($einfo);
				return $info;
			}
			else
			{
				$einfo = db::table('college_enrolledstud')
					->select(db::raw('college_enrolledstud.id, levelname, college_courses.`courseabrv`, college_courses.`courseDesc`, college_enrolledstud.id as sectionid, studentstatus.`description` AS studstatus, yearLevel as levelid, sydesc, college_enrolledstud.syid, college_enrolledstud.semid, semester.semester as semdesc, nationality.`nationality`'))
					->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
					->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
					->join('nationality', 'studinfo.nationality', '=', 'nationality.id')
					->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
					->join('studentstatus', 'college_enrolledstud.studstatus', '=', 'studentstatus.id')
					->join('sy', 'college_enrolledstud.syid', '=', 'sy.id')
					->join('semester', 'college_enrolledstud.semid', '=', 'semester.id')
					->where('studid', $studid)
					->where('nationality.deleted', 0)
					->where('syid', $syid)
					->where('college_enrolledstud.semid', $semid)
					->where('college_enrolledstud.deleted', 0)
					->first();

				if($einfo)
				{
					$info = collect($einfo);
					return $info;
				}
			}
		}

	}

    public function api_billinginfo(Request $request)
	{
		$studid = $request->get('studid');
		$syid = $request->get('syid');
		$semid = $request->get('semid');
		$month = $request->get('monthid');

		$billing = FinanceUtilityModel::assessment_gen($studid, $syid, $semid, $month);

		return $billing;
	}

	public function api_studledger(Request $request)
	{
		$studid = $request->get('studid');
		$syid = $request->get('syid');
		$semid = $request->get('semid');
		$levelid = 0;

		$einfo = db::table('enrolledstud')
			->select('levelid')
			->where('studid', $studid)
			->where('syid', $syid)
			->where(function($q) use($semid){
				if($semid == 3)
				{
					$q->where('ghssemid', 3);
				}
				else
				{
					$q->where('ghssemid', '!=', 3);
				}
			})
			->where('deleted', 0)
			->where('studstatus', '>', 0)
			->first();

		if($einfo)
		{
			$levelid = $einfo->levelid;
		}
		else
		{
			$einfo = db::table('sh_enrolledstud')
				->where('levelid')
				->where('studid', $studid)
				->where('deleted', 0)
				->where('studstatus', '>', 0)
				->where('syid', $syid)
				->where(function($q) use($semid){
					if($semid == 3)
					{
						$q->where('semid', 3);
					}
					else
					{
						if(db::table('schoolinfo')->first()->shssetup == 0)
						{
							$q->where('semid', $semid);
						}
						else
						{
							$q->where('semid', '!=', 3);
						}
					}
				})
				->first();

			if($einfo)
			{
				$levelid = $einfo->levelid;
			}
			else
			{
				$einfo = db::table('college_enrolledstud')
					->select('yearLevel as levelid')
					->where('studid', $studid)
					->where('syid', $syid)
					->where('semid', $semid)
					->where('deleted', 0)
					->where('studstatus', '>', 0)
					->first();

				if($einfo)
				{
					$levelid = $einfo->levelid;
				}
				else
				{
					$levelid = db::table('studinfo')->where('id', $studid)->first()->levelid;
				}
			}
		}


		$studledger = db::table('studledger')
			->select('createddatetime as transdate', 'particulars', 'amount', 'payment')
			->where('studid', $studid)
			->where('syid', $syid)
			->where(function($q) use($semid, $levelid){
				if($levelid == 14 || $levelid == 15)
				{
					if($semid == 3)
					{
						$q->where('semid', 3);
					}
					else
					{
						if(db::table('schoolinfo')->first()->shssetup == 0)
						{
							$q->where('semid', $semid);
						}
						else
						{
							$q->where('semid', '!=', 3);
						}
					}
				}
				elseif($levelid >= 17 && $levelid <= 21)
				{
					$q->where('semid', $semid);
				}
				else
				{
					if($semid == 3)
					{
						$q->where('semid', 3);
					}
					else
					{
						if(db::table('schoolinfo')->first()->shssetup == 0)
						{
							$q->where('semid', $semid);
						}
						else
						{
							$q->where('semid', '!=', 3);
						}
					}
				}
			})
			->where('deleted', 0)
			->where('void', 0)
			->get();

		$ledeger_array = array();
		$totalamount = 0;
		$totalpayment = 0;
		$balance = 0;

		foreach($studledger as $ledger)
		{
			$balance += $ledger->amount - $ledger->payment;
			array_push($ledeger_array, (object)[
				'date' => date_format(date_create($ledger->transdate), 'm-d-Y'),
				'particulars' => $ledger->particulars,
				'amount' => number_format($ledger->amount, 2),
				'payment' => number_format($ledger->payment, 2),
				'balance' => number_format($balance, 2)
			]);

			$totalamount += $ledger->amount;
			$totalpayment += $ledger->payment;

		}

		array_push($ledeger_array, (object)[
			'date' => '',
			'particulars' => 'TOTAL: ',
			'amount' => number_format($totalamount, 2),
			'payment' => number_format($totalpayment, 2),
			'balance' => number_format($balance, 2)
		]);

		return $ledeger_array;
	}

	public function api_monthsetup()
	{
	    $monthsetup = db::table('monthsetup')
	        ->get();

	   return $monthsetup;
	}

	public function api_picurl(Request $request)
	{
		$studid = $request->get('studid');

		$stud = db::table('studinfo')
			->select('picurl')
			->where('id', $studid)
			->first();

		$data = array(
			'picurl' => $stud->picurl
		);

		return $data;
	}

//     public function updateProfile(Request $request){
//         // if(!$request->hasFile('file') && !$request->file('file')->isValid()){

//         //     return response()->json('{"errror": "Please provide an image');
//         // }

//         // try {
//         //     $imageName = $request->file('file')->hashName();
//         //     Storage::disk('local')->put($imageName, file_get_contents($request->file('file')));
//         //     return response()->json($imageName);

//         // } catch (\Exception $e) {

//         //     return response()->json($e);
//         // }

//         $message = [
//             'image.required'=>'Student Picture is required',
//         ];

//         $validator = Validator::make($request->all(), [
//             'image' => ['required']
//         ], $message);

//         if ($validator->fails()) {

//             toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

//             $data = array(
//                 (object)
//               [
//                 'status'=>'0',
//                 'message'=>'Error',
//                 'errors'=>$validator->errors(),
//                 'inputs'=>$request->all()
//             ]);

//             return $data;

//         }
//         else{

//             try{



//             $session_student = $request->studid;


//             $studendinfo = DB::table('studinfo')
//                         ->where('deleted',0)
//                         ->where('id', $session_student)
//                         ->select('sid','id')
//                         ->first();


//             $link = DB::table('schoolinfo')
//                             ->select('essentiellink')
//                             ->first()
//                             ->essentiellink;

//             if($link == null){
//                 return array( (object)[
//                     'status'=>'0',
//                     'message'=>'Error',
//                     'errors'=>array(),
//                     'inputs'=>$request->all()
//                 ]);
//             }

//             $urlFolder = str_replace('http://','',$link);
// 			$urlFolder = str_replace('https://','',$urlFolder);

//                 if (! File::exists(public_path().'storage/STUDENT')) {
//                     $path = public_path('storage/STUDENT');
//                     if(!File::isDirectory($path)){
//                         File::makeDirectory($path, 0777, true, true);
//                     }
//                 }

//                 if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'storage/STUDENT')) {
//                     $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT';
//                     if(!File::isDirectory($cloudpath)){
//                         File::makeDirectory($cloudpath, 0777, true, true);
//                     }
//                 }

//                 $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHmmss');
//                 $data = $request->file('image');

//                 $extension = 'png';
//                 $destinationPath = public_path('storage/STUDENT/'.$studendinfo->sid.$date.'.'.$extension);
//                 $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT/'.$studendinfo->sid.$date.'.'.$extension;
//                 file_put_contents($clouddestinationPath, file_get_contents($data));
//                 file_put_contents($destinationPath, file_get_contents($data));

//                 DB::table('studinfo')
//                         ->where('id',$studendinfo->id)
//                         ->take(1)
//                         ->update(['picurl'=>'storage/STUDENT/'.$studendinfo->sid.$date.'.'.$extension ]);



//                 $data = array(
//                     (object)
//                   [
//                     'success'=>'200',
//                 ]);

//                 return $data;

//             }catch(\Exception $e){
//                  DB::table('zerrorlogs')
//                 ->insert([
//                     'error'=>$e,
//                     // 'createdby'=>auth()->user()->id,
//                     'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
//                 ]);
//             }

//         }



//     }

//     public function download(){

//         $path = storage_path("app/public/apk/essentiel.apk");

//         // return response()->file($path,
//         // [
//         //     'Content-Type'=>'application/vnd.android.package-archive',
//         //     'Content-Disposition'=> 'attachment; filename="essentiel.apk"',
//         // ]) ;

//         // return response()->download($path, 'Essentiel', 'application/vnd.essentiel.package-archive');
//         return \Response::download($path, 'essentiel.apk',
//             [
//                 'Content-Type'=>'application/vnd.android.package-archive'
//             ]);

//     }

    public function api_getscholarshipsetup(Request $request)
    {
        $scholarship_setup = DB::table('scholarship_setup')
            ->where('deleted', 0)
            ->where('isactive', 1)
            ->get();

        return $scholarship_setup;
    }


    public function api_getscholarship(Request $request)
    {
        $studid = request()->get('studid');

        $studid = DB::table('studinfo')
            ->where('id', $studid)
            ->select('id', 'levelid')
            ->first();

        $data = DB::table('scholarship_applicants')
            ->where('studid', $studid->id)
            ->join('scholarship_setup', function ($join) {
                $join->on('scholarship_setup.id', '=', 'scholarship_applicants.scholarship_setup_id');
                $join->where('scholarship_setup.deleted', 0);
            })
            ->when($request->get('id'), function ($query) use ($request) {
                $query->where('scholarship_applicants.id', $request->get('id'));
            })
            ->select('scholarship_setup.description', 'scholarship_applicants.*')
            ->where('scholarship_applicants.deleted', 0)
            ->get();

        foreach ($data as $item) {
            if ($studid->levelid >= 17 && $studid->levelid <= 22) {
                $item->studstatus = DB::table('college_enrolledstud')
                    ->where('studid', $studid->id)
                    ->value('regStatus');
            } else {
                $item->studstatus = 'N/A';
            }
        }
        return $data;
    }


    public function api_getrequirement(Request $request)
    {
        $requirement = DB::table('scholarship_setup_details')
            ->where('scholarship_setup_id', $request->get('id'))
            ->where('deleted', 0)
            ->get();

        return $requirement;
    }

    public function api_delscholarship(Request $request)
    {
        $id = $request->get('id');


        DB::table('scholarship_applicants')
            ->where('id', $id)
            ->update([
                'deleted' => 1
            ]);

        return 1;
    }



    public function api_savescholarship(Request $request)
    {

        $scholarshipid = $request->get('scholarship_id');
        $semesterid = $request->get('semesterid');
        $studid = $request->get('studid');

        $activesy = DB::table('sy')
            ->where('isactive', 1)
            ->value('id');


        $checkifexist = DB::table('scholarship_applicants')
            ->where('studid', $studid)
            ->where('scholarship_setup_id', $scholarshipid)
            ->where('semid', $request->get('semesterid'))
            ->where('syid', $activesy)
            ->where('deleted', 0)
            ->get();


        if (count($checkifexist) == 0) {


            $applicationid = DB::table('scholarship_applicants')
                ->insertGetId([
                    'studid' => $studid,
                    'scholarship_setup_id' => $scholarshipid,
                    'semid' => $semesterid,
                    'syid' => $activesy,
                    'createddatetime' => now()

                ]);
        } else {



            $applicationid = $checkifexist[0]->id;


        }





        $requirementsArray = json_decode($request->get('requirementsArray'));


        foreach ($requirementsArray as $requirement) {

            $checkifexist = DB::table('scholarship_applicant_details')
                ->where('requirement_id', $requirement->dataId)
                ->where('deleted', 0)
                ->get();

            if (count($checkifexist) == 0) {

                DB::table('scholarship_applicant_details')
                    ->insert([
                        'scholarship_applicant_id' => $applicationid,
                        'requirement_id' => $requirement->dataId,
                        'fileurl' => $requirement->value,
                    ]);

            } else {

                DB::table('scholarship_applicant_details')
                    ->where('id', $checkifexist[0]->id)
                    ->update([
                        'fileurl' => $requirement->value,
                    ]);
            }


        }

        $remarks = json_decode($request->get('remarks'));

        // foreach ($remarks as $remark) {

        //     $checkifexist = DB::table('scholarship_applicant_details')
        //         ->where('requirement_id', $remark->dataId)
        //         ->where('deleted', 0)
        //         ->get();

        //     if (count($checkifexist) == 0) {

        //         DB::table('scholarship_applicant_details')
        //             ->insert([
        //                 'scholarship_applicant_id' => $applicationid,
        //                 'requirementid' => $requirement->dataId,
        //                 'fileurl' => $remark->value,
        //             ]);

        //     } else {

        //         DB::table('scholarship_applicant_details')
        //             ->where('id', $checkifexist[0]->id)
        //             ->update([
        //                 'fileurl' => $requirement->value,
        //             ]);
        //     }


        // }

        return 1;
    }




    public function api_uploadrequirement(Request $request)
    {

        $file = $request->file('file');

        $newFileName = time() . '.' . $file->getClientOriginalExtension();
        ;

        $destinationPath = public_path('scholarship/');
        $file->move($destinationPath, $newFileName);
        $path = '/scholarship/' . $newFileName;

        return response()->json(['url' => $path], 200);
    }

}
