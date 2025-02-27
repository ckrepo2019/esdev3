<?php

namespace App\Http\Controllers\ParentControllers;

use Illuminate\Http\Request;
use App\Models\Principal\Subject;
use App\Models\Principal\Section;
use App\Models\Principal\ClassSubject;
use App\Models\Principal\ClassSchedDetail;
use App\Models\Principal\ClassSched;
use App\Models\Principal\Room;
use \Carbon\Carbon;
use DB;
use App\Models\Principal\GenerateGrade;
use App\Models\Principal\LoadData;
use App\Models\Principal\AttendanceReport;
use Session;
use App\Models\Principal\SPP_SHClassSchedule;
use App\Models\Principal\SPP_Queries;
use App\Models\Principal\SPP_Subject;
use App\Models\Principal\SPP_Notification;
use App\Models\Principal\SPP_Attendance;
use Crypt;
use File;
use App\FilePath;
use Image;
use Illuminate\Support\Facades\Validator;
use App\Models\Principal\Billing;

use App\Models\Principal\SPP_Rooms;
use App\Models\Principal\SPP_Section;


class ParentsController extends \App\Http\Controllers\Controller
{
    public function loadSchedule()
    {

     

        if(Session::get('enrollmentstatus')) {

            $studendinfo = Session::get('studentInfo');

            $schedule  = SPP_Subject::getSchedule(null,null,$studendinfo->ensectid,$studendinfo->blockid);

            return view('parentsportal.pages.schedule')
                            ->with('scheds',ClassSched::dayswithbgcolors())
                            ->with('sampleScheds',$schedule)
                            ->with('enrollmentstatus','Enrolled');

        }
        else{

            return view('parentsportal.pages.grades');
            
        }

    }

    public function loadCalendar(){

        $schoolcalendar = DB::table('schoolcal')
                                ->where('deleted','0')
                                ->get();

        return view('parentsportal.pages.schoolCalendar')
                ->with('schoolcalendar',$schoolcalendar);

    }


    public function updategradenotification($id){
        
        DB::table('notifications')->where('id',$id)->update(['status'=>'1']);
        return $this->loadGrades();

    }

    public function loadGrades(){

        // $studendinfo = Session::get('studentInfo');


        // if(Session::get('enrollmentstatus')) {

        //     if($studendinfo->acadprogid == 2){

        //         return \App\Models\PreSchool\PSGradeStatus\PSGradeStatusData::ps_grade_status_list($studendinfo->id,$syid);

        //      }
        // }

        return view('parentsportal.pages.enrollment_report');
        
        if(Session::get('enrollmentstatus')) {

            

            $studendinfo = Session::get('studentInfo');

            $attSum = SPP_Attendance::getStudentAttendance($studendinfo->id);

            $finalGrades = GenerateGrade::reportCardV3($studendinfo,false,'sf9');

            $generalave = GenerateGrade::genAveV3($finalGrades);

          
       

            if($studendinfo->acadprogid != 5){
                return view('parentsportal.pages.grades')
                    ->with('finalGrades',$finalGrades)
                    ->with('enrollmentstatus','Enrolled')
                    ->with('generalave',$generalave)
                    ->with('attSum',$attSum);
            }
            else{

                return view('parentsportal.pages.grades.seniorhigh')
                    ->with('finalGrades',$finalGrades)
                    ->with('enrollmentstatus','Enrolled')
                    ->with('generalave',$generalave)
                    ->with('attSum',$attSum);

            }

        }
        else{

            return view('parentsportal.pages.grades');
            
        }


 
    }
    public function loadBilling(){

     

        if(Session::get('enrollmentstatus')) {

            $studendinfo = Session::get('studentInfo');



            if($studendinfo->acadprogid == 5)

                $billings = DB::table('studpayscheddetail')
                        ->where('studid',$studendinfo->id)
                        ->where('studpayscheddetail.deleted','0')
                        ->join('sy',function($join){
                                $join->on('studpayscheddetail.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                        ->join('semester',function($join){
                            $join->on('studpayscheddetail.semid','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->groupBy(DB::raw("MONTH(duedate)"))
                        ->select(
                            'studpayscheddetail.particulars',
                            'studpayscheddetail.duedate',
                            DB::raw("SUM(amountpay) as amountpay"),
                            DB::raw("SUM(amount) as amountdue"),
                            DB::raw("SUM(balance) as balance")
                            )
                            ->orderBy('duedate','asc')
                            ->get();

            else{

                $billings = DB::table('studpayscheddetail')
                            ->where('studid',$studendinfo->id)
                            ->where('deleted','0')
                            ->join('sy',function($join){
                                    $join->on('studpayscheddetail.syid','=','sy.id');
                                    $join->where('isactive','1');
                                })
                            ->groupBy(DB::raw("MONTH(duedate)"))
                            ->select(
                                'studpayscheddetail.particulars',
                                'studpayscheddetail.duedate',
                                DB::raw("SUM(amountpay) as amountpay"),
                                DB::raw("SUM(amount) as amountdue"),
                                DB::raw("SUM(balance) as balance")
                                )
                                ->orderBy('duedate','asc')
                                ->get();


            }
            
            $billingHistory = DB::table('studledger')
                                ->where('studid',$studendinfo->id)
                                ->where('studledger.deleted','0')
                                ->where('void','0')
                                ->join('sy',function($join){
                                    $join->on('studledger.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                });

             if($studendinfo->acadprogid == 5){

                $billingHistory = $billingHistory->join('semester',function($join){
                                        $join->on('studledger.semid','=','semester.id');
                                        $join->where('semester.isactive','1');
                                    });

             }
                               
            $billingHistory = $billingHistory->select('studledger.*')
                                ->orderBy('studledger.id','asc')
                                ->get();
            
            $runbal = 0;
            $withBalFor = false;
            $balForInfo = null;

            //  return collect($studendinfo);

            $prereg = (object)[
                'sid'=>$studendinfo->sid,
                'queing_code'=>0000000000000000,
                'lrn'=>0000000000000000,
            ];
            
            $assessment =  Billing::remBill($prereg);

       

            foreach($billingHistory as $item){

                if(!$withBalFor){

                    $checkBalFor = DB::table('balforwardsetup')->where('classid',$item->classid)->count();

                    if($checkBalFor > 0){
                       
                        $balForInfo = $item;
                        $withBalFor = true;

                    }
                }

            }

            foreach($billingHistory as $item){

                $runbal = $runbal + $item->amount - $item->payment;

                DB::table('studledger')->where('id',$item->id)->update(['runbal'=>$runbal]);

                $item->runbal = $runbal;
                
            }

            return view('parentsportal.pages.billingHistory')
                                ->with('billings',$billings)
                                ->with('withBalFor',$withBalFor)
                                ->with('balForInfo',$balForInfo)
                                ->with('billhis',$billingHistory)
                                ->with('assessment',$assessment)
                                ->with('enrollmentstatus','Enrolled');
        }
        else{

            return view('parentsportal.pages.billingHistory');
            
        }

    }

    public function parentgetevent(Request $request){

        return SPP_Calendar::getHoliday(null,null,$request->get('id'));

    }

    public function parentgeteventtype(Request $request){

        return SPP_Calendar::getEventType($request->get('id'));

    }

    public function parentschoolCalendar(){

        $schoolcalendar = DB::table('schoolcal')
                                ->where('deleted','0')
                                ->get();

        return view('studentPortal.pages.schoolCalendar')
                ->with('schoolcalendar',$schoolcalendar);

    }

    public function viewAnnouncement($id){

        try{

            $id = Crypt::decrypt($id);

        }
        catch (\Exception $e){

            $id = $id;

        }

        $content =  DB::table('notifications')
                    ->join('announcements','notifications.headerid','=','announcements.id')
                    ->join('users','announcements.createdby','=','users.id')
                    ->select('users.name','announcements.title','announcements.content','notifications.created_at')
                    ->where('notifications.headerid',$id)
                    ->get();


        DB::table('notifications')
                ->where('recieverid',auth()->user()->id)
                ->where('headerid',$id)
                ->update(['status'=>'1']);

        
        return view('parentsportal.pages.announcements')
                ->with('content',$content);
    }

    public function viewAllAnnouncement(){


        $notification =  SPP_Notification::viewNotifications(null,10,auth()->user()->id,null,null);

        return view('parentsportal.pages.viewallannouncements')
                ->with('data',$notification );

    }

    // gian 
    public function loadstudentprofile()
    {
        $studendinfo = Session::get('studentInfo');
        
        $studinformation = DB::table('studinfo')
                    ->leftJoin('religion','studinfo.religionid', '=', 'religion.id')
                    ->leftJoin('mothertongue','studinfo.mtid', '=', 'mothertongue.id')
                    ->leftJoin('ethnic','studinfo.egid', '=', 'ethnic.id')
                    ->where('studinfo.id',$studendinfo->id)
                    ->select(   'studinfo.*',
                                'religion.religionname',
                                'ethnic.egname',
                                'mothertongue.mtname'
                        )
                    ->first();

        if(isset($studinformation->id)){

            $municipality = null;
            $province = null;
            $region = null;
            $barangay = null;

            try{

                $municipality = DB::table('lib_municipality')->where('municipalityname',$studinformation->city)->first();

            }catch(\Exception $e){


            }

            try{

                $province = DB::table('lib_province')->where('provincename',$studinformation->province)->first();

            }catch(\Exception $e){

                
            }

            try{

                $region = DB::table('lib_region')->where('LHIO',$province->LHIO)->first();

            }catch(\Exception $e){

               
            }

            try{

                $barangay = DB::table('lib_barangay')
                                ->where('barangayname',$studinformation->barangay)
                                ->where('municipalitycode',$municipality->municipality)
                                ->first();

            }catch(\Exception $e){

            }
            
            
           

            $street = $studinformation->street;

           

            return view('parentsportal.pages.studentinformation')
                ->with('barangay',$barangay)
                ->with('municipality',$municipality)
                ->with('province',$province)
                ->with('region',$region)
                ->with('street',$street)
                ->with('sinfo',$studinformation);


        }

    
       
    }



    public function testing(){

        // $sections = DB::table('sections')
        //                 ->join('rooms','sections.roomid','=','rooms.id')
        //                 ->get();
        $sections = SPP_Section::with('rooms')->get();

        return $sections;

    }

    public function updateStudPic(Request $request){

        $message = [
     
            'image.required'=>'Student Picture is required',
        ];

        $validator = Validator::make($request->all(), [
            'image' => ['required']
        
        ], $message);

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            $data = array(
                (object)
              [
                'status'=>'0',
                'message'=>'Error',
                'errors'=>$validator->errors(),
                'inputs'=>$request->all()
            ]);

            return $data;
            
        }
        else{

            $studendinfo = Session::get('studentInfo');

            $urlFolder = str_replace('http://','',$request->root());

                if (! File::exists(public_path().'storage/STUDENT')) {

                    $path = public_path('storage/STUDENT');

                    if(!File::isDirectory($path)){

                        File::makeDirectory($path, 0777, true, true);
                    }
                }

                if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'storage/STUDENT')) {

                    $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT';

                    if(!File::isDirectory($cloudpath)){

                        File::makeDirectory($cloudpath, 0777, true, true);

                    }
                    
                }
            
                $data = $request->image;

                list($type, $data) = explode(';', $data);

                list(, $data)      = explode(',', $data);


                $data = base64_decode($data);

                $extension = 'png';

                $destinationPath = public_path('storage/STUDENT/'.$studendinfo->sid.'.'.$extension);
              
                $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT/'.$studendinfo->sid.'.'.$extension;
        
                file_put_contents($clouddestinationPath, $data);

                file_put_contents($destinationPath, $data);

                

                DB::table('studinfo')
                        ->where('id',$studendinfo->id)
                        ->update(['picurl'=>'storage/STUDENT/'.$studendinfo->sid.'.'.$extension ]);

                // $destinationPath = public_path('employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$request->lastname.'.'.$extension);
                
                // file_put_contents($destinationPath, $data);

                // DB::table('teacher')
                //     ->where('id',$request->employeeid)
                //     ->update([
                //         'picurl' => 'employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$request->lastname.'.'.$extension
                //     ]);

                // return response()->json(['success'=>'done']);
                // $file = $request->file('studpic');
            
                // $extension = $file->getClientOriginalExtension();
                
                // $img = Image::make($file->path());

                // $destinationPath = public_path('storage/STUDENT/'.$studendinfo->sid.'.'.$extension);

                // $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.'storage/STUDENT/'.$studendinfo->sid.'.'.$extension;

                // $img->resize(500, 500, function ($constraint) {
                //                         $constraint->aspectRatio();
                //                     })->resizeCanvas(500, 500,'center')->save($destinationPath);

                // $img->resize(500, 500, function ($constraint) {
                //                 $constraint->aspectRatio();
                //             })->resizeCanvas(500, 500,'center')->save($clouddestinationPath);

                

                // toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                // return back();

                $data = array(
                    (object)
                  [
                    'status'=>'1',
                ]);
    
                return $data;

            }

    }

    public static function surveyForm(){

        $sy = DB::table('sy')->where('isactive','1')->first();
        $sem = DB::table('semester')->where('isactive','1')->first();

        
        $student = DB::table('studinfo')
                        ->where('studinfo.id',Session::get('studentInfo')->id)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->leftJoin('sh_strand',function($join){
                            $join->on('studinfo.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted','0');
                        })
                        ->leftJoin('sh_track',function($join){
                            $join->on('sh_strand.trackid','=','sh_track.id');
                            $join->where('sh_track.deleted','0');
                        })
                        ->select(
                            // 'lrn',
                            // 'firstname',
                            // 'lastname',
                            // 'middlename',
                            // 'levelname',
                            // 'strandname',
                            // 'studinfo.id',
                            // 'suffix',
                            // 'dob',
                            // 'gender',
                            'studinfo.*',
                            'levelname',
                            'strandname',
                            'trackname'
                            )
                        ->first();

        $checkIfExist =  DB::table('leasf')
                            ->where('studid',$student->id)
                            // ->where('createdby',auth()->user()->id)
                            ->Where('deleted',0)
                            ->count();

        if( $checkIfExist > 0){
            $surveyAns = DB::table('leasf')
                            ->where('studid',$student->id)
                            // ->where('createdby',auth()->user()->id)
                            ->Where('deleted',0)
                            ->first();
        }
        else{

            $surveyAns = (object)[
                'a1'=>null,
                'a2'=>null,
                'a3'=>null,
                'a4'=>null,
                'a5'=>null,
                'a6'=>null,
                'a7'=>null,
                'a8'=>null,
                'a9'=>null,
                'a10'=>null,
                'a11'=>null,
                'a12'=>null,
                'a13'=>null,
                'a14'=>null,
                'a15'=>null,
                'a16'=>null,
                'b1'=>null,
                'b10'=>null,
                'b11'=>null,
                'b12'=>null,
                'b13'=>null,
                'b14'=>null,
                'b15'=>null,
                'b16'=>null,
                'b17'=>null,
                'b18'=>null,
                'b19'=>null,
                'b20'=>null,
                'b21'=>null,
                'b22'=>null,
                'c1'=>null,
                'c2'=>null,
                'c3'=>null,
                'c4'=>null,
                'c5'=>null,
                'c7'=>null,
                'c8'=>null,
                'c9'=>null,
                'c10'=>null,
                'c11'=>null,
                'c13'=>null,
                'c14'=>null,
                'c15'=>null,
                'c16'=>null,
                'c17'=>null,
                'd1'=>null,
                'd2'=>null,
                'd3'=>null,
                'd4'=>null,
                'd5'=>null,
                'd6'=>null,
                'd7'=>null,
                'd8'=>null,
                'd4others'=>null,
                'd7others'=>null,
                'd8others'=>null
            ];
            
        }

        return view('parentsportal.pages.studentSurveyForm')
                ->with('student',$student)
                ->with('sy',$sy)
                ->with('surveyAns',$surveyAns)
                ->with('sem',$sem);
        

    }


    public function submitSurvey(Request $request){

        $student = DB::table('studinfo')
                        ->where('studinfo.id',Session::get('studentInfo')->id)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->leftJoin('sh_strand',function($join){
                            $join->on('studinfo.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted','0');
                        })
                        ->select(
                            'lrn',
                            'firstname',
                            'lastname',
                            'middlename',
                            'levelname',
                            'strandname',
                            'studinfo.id',
                            'suffix',
                            'dob',
                            'gender'
                            )
                        ->first();

      
        
        $sy = DB::table('sy')->where('isactive','1')->first();
        $sem = DB::table('semester')->where('isactive','1')->first();
        
        $d1 = '';
        $d3 = '';
        $d6 = '';
        $d8 = '';
        $d4 = '';
        $d7 = '';

        if($request->get('d1') != null){
            foreach($request->get('d1') as $item){
                $d1 .=' '.$item;
            }
        }

        if($request->get('d3') != null){
            foreach($request->get('d3') as $item){
                $d3 .=' '.$item;
            }
        }

        if($request->get('d4') != null){
            foreach($request->get('d4') as $item){
                $d4 .=' '.$item;
            }
        }

        if($request->get('d6') != null){
            foreach($request->get('d6') as $item){
                $d6 .=' '.$item;
            }
        }

        if($request->get('d7') != null){
            foreach($request->get('d7') as $item){
                $d7 .=' '.$item;
            }
        }

        if($request->get('d8') != null){
            foreach($request->get('d8') as $item){
                $d8 .=' '.$item;
            }
        }

        $syid = DB::table('sy')->where('isactive',1)->first()->id;

        DB::table('leasf')
            ->where('deleted',0)
            ->where('studid',$student->id)
            ->take(1)
            ->update([
                'deleted'=>1,
                'updatedby'=>auth()->user()->id,
                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);

        DB::table('leasf')->insert([
                'studid'=>$student->id,
                'createdby'=>auth()->user()->id,
                'a1'=>$sy->id,
                'a2'=>$request->get('a2'),
                'a3'=>$request->get('a3'),
                'a4'=>$request->get('a4'),
                'a5'=>$request->get('a5'),
                'a6'=>$request->get('a6'),
                'a7'=>ucwords($request->get('a7')),
                'a8'=>$request->get('a8'),
                'a9'=>$request->get('a9'),
                'a10'=>$request->get('a10'),
                'a11'=>$request->get('a11'),
                'a12'=>ucwords($request->get('a12')),
                'a13'=>$request->get('a13'),
                'a14'=>$request->get('a14'),
                'a15'=>$request->get('a15'),
                'a16'=>$request->get('a16'),
                'b1'=>$request->get('b1'),
                'b10'=>$request->get('b10'),
                'b11'=>$request->get('b11'),
                'b12'=>$request->get('b12'),
                'b13'=>$request->get('b13'),
                'b14'=>$request->get('b14'),
                'b15'=>$request->get('b15'),
                'b16'=>$request->get('b16'),
                'b17'=>$request->get('b17'),
                'b18'=>$request->get('b18'),
                'b19'=>$request->get('b19'),
                'b20'=>$request->get('b20'),
                'b21'=>$request->get('b21'),
                'b22'=>$request->get('b22'),
                'c1'=>$request->get('c1'),
                'c2'=>$request->get('c2'),
                'c3'=>$request->get('c3'),
                'c4'=>$request->get('c4'),
                'c5'=>$request->get('c5'),
                'c7'=>$request->get('c7'),
                'c8'=>$request->get('c8'),
                'c9'=>$request->get('c9'),
                'c10'=>$request->get('c10'),
                'c11'=>$request->get('c11'),
                'c13'=>$request->get('c13'),
                'c14'=>$request->get('c14'),
                'c15'=>$request->get('c15'),
                'c16'=>$request->get('c16'),
                'c17'=>$request->get('c17'),
                'd1'=>$d1,
                'd2'=>$request->get('d2'),
                'd3'=>$d3,
                'd4'=>$d4,
                'd5'=>$request->get('d5'),
                'd6'=>$d6,
                'd7'=>$d7,
                'd8'=>$d8,
                'd4others'=>$request->get('d4others'),
                'd7others'=>$request->get('d7others'),
                'd8others'=>$request->get('d8others'),
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD'),
                'syid'=>$syid
            ]);

        
        return back();

    }

    public function onlinepayment(Request $request){

        $studendinfo = Session::get('studentInfo');

        $studendinfo = DB::table('studinfo')
                            ->where('id',  $studendinfo->id)
                            ->select(
                                'sid',
                                'qcode',
                                'lrn'
                            )
                            ->first();

        if(isset($studendinfo->sid)){

            $onlinepayments = DB::table('onlinepayments')
                                ->leftJoin('paymenttype',function($join){
                                    $join->on('onlinepayments.paymentType','=','paymenttype.id');
                                })
                                ->leftJoin('chrngtrans',function($join){
                                    $join->on('onlinepayments.chrngtransid','=','chrngtrans.id');
                                })
                                ->where(function($query) use($studendinfo){
                                    $query->where('queingcode',$studendinfo->sid);
                                    $query->orWhere('queingcode',$studendinfo->qcode);
                                    $query->orWhere('queingcode',$studendinfo->lrn);
                                })
                                ->orderBy('paymentDate','desc')
                                ->select(
                                    'picUrl',
                                    'description',
                                    'onlinepayments.bankName',
                                    'refNum',
                                    'onlinepayments.amount',
                                    'isapproved',
                                    'paymentDate',
                                    'description',
                                    'refNum',
                                    'ornum'
                                )
                                ->get();


            return view('parentsportal.pages.payments.onlinepaymenttable')->with('onlinepayments',$onlinepayments);

        }

    }


    public function updateStudentInfo(Request $request){

        try{

            $province = null;
            $barangay = null;
            $citymun = null;

            $studendinfo = Session::get('studentInfo');

            try{

                $province = DB::table('lib_province')
                                ->where('provincecode',$request->get('province'))
                                ->select('provincename','id')
                                ->first()->provincename;

            }
            catch(\Exception $e){

            }

            try{

                $barangay = DB::table('lib_barangay')->where('id',$request->get('barangay'))->select('barangayname','municipalitycode')->first()->barangayname;

            }
            catch(\Exception $e){

            }

            try{

                $citymun = DB::table('lib_municipality')
                                ->where('id',$request->get('citymun'))
                                ->first()->municipalityname;

            }
            catch(\Exception $e){

            }


          
            $street = $request->get('street');

            if(isset($studendinfo->id)){

                DB::enableQueryLog();

                    DB::table('studinfo')
                            ->where('id',$studendinfo->id)
                            ->take(1)
                            ->update([
                                'barangay'=>$barangay,
                                'city'=>$citymun,
                                'province'=>$province,
                                'street'=>strtoupper($street),
                                'fathername'=>strtoupper($request->get('fname')),
                                'mothername'=>strtoupper($request->get('mname')),
                                'guardianname'=>strtoupper($request->get('gname')),
                                'foccupation'=>strtoupper($request->get('foccu')),
                                'moccupation'=>strtoupper($request->get('moccu')),
                                'guardianrelation'=>strtoupper($request->get('grelation')),
                                'fcontactno'=>strtoupper($request->get('fcontactno')),
                                'mcontactno'=>strtoupper($request->get('mcontactno')),
                                'gcontactno'=>strtoupper($request->get('gcontactno')),
                            ]);

                    $logs = json_encode(DB::getQueryLog());

                    DB::table('updatelogs')
                            ->insert([
                                'type'=>1,
                                'sql'=> $logs,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                    
                    DB::disableQueryLog();
                    
                    return 1;

            }else{

                DB::table('zerrorlogs')->insert([
                    'error'=>'Student ID not found',
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

                return 0;

            }

        }catch (\Exception $e){
         
            DB::table('zerrorlogs')->insert([
                'error'=>$e,
                'createdby'=>auth()->user()->id,
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
            
            return 0;

        }

    }

    public function parentEnterAmount(Request $request){


        $studendinfo = Session::get('studentInfo');

        $perItems = explode('||,', $request->get('info'));

        $validator = Validator::make($request->all(), [
                'paymentType' => ['required'],
                'recieptImage' => ['required','mimes:jpeg,png'],
                'amount' => ['required'],
                'studid' => ['required'],
                'studid' => ['required'],
                'transDate' => ['required'],
                'refNum' => ['required'],
        ],[
            'refNum.required'=>'Reference Number is required.'
        ]);

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            $data = array(
                (object)
              [
                'status'=>'0',
                'message'=>'Error',
                'errors'=>$validator->errors(),
                'inputs'=>$request->all()
            ]);

            return $data;

        }

            if($request->get('paymentType') == 3){

                $validator = Validator::make($request->all(), [
                        'paymentType' => ['required'],
                        'recieptImage' => ['required'],
                        'amount' => ['required'],
                        'studid' => ['required'],
                        'bankName' => ['required'],
                        'transDate' => ['required'],
                ],[
                    'refNum.required'=>'Reference Number is required.'
                ]);

                if ($validator->fails()) {

                    $data = array(
                        (object)
                    [
                        'status'=>'0',
                        'message'=>'Error',
                        'errors'=>$validator->errors(),
                        'inputs'=>$request->all()
                    ]);
        

                    toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
                    return $data;
        
                }

            }

            if($request->get('paymentType') != 3 && $request->get('paymentType') != null){

                $validator = Validator::make($request->all(), [
                    'paymentType' => ['required'],
                    'recieptImage' => ['required'],
                    'amount' => ['required'],
                    'studid' => ['required'],
                    'refNum' => ['required'],
                    'transDate' => ['required'],
                ],[
                    'refNum.required'=>'Reference Number is required.'
                ]);

                if ($validator->fails()) {

                    $data = array(
                        (object)
                    [
                        'status'=>'0',
                        'message'=>'Error',
                        'errors'=>$validator->errors(),
                        'inputs'=>$request->all()
                    ]);

                    toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
                    return $data;

                }

            }

            $countExistRefNum = DB::table('onlinepayments')
                                    ->where('refNum',$request->get('refNum'))
                                    ->where('isapproved','!=',3)
                                    ->count();
      
            if($countExistRefNum > 0){

                $validator->errors()->add('refNum', 'Reference number already exist!');

                $data = array((object)
                [
                    'status'=>'0',
                    'message'=>'Error',
                    'errors'=>$validator->errors(),
                    'inputs'=>$request->all()
                ]);

                return $data;

            }



            $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');

            $file = $request->file('recieptImage');
            
            $extension = $file->getClientOriginalExtension();


            $studinfo = DB::table('studinfo')
                                ->where('studinfo.id',$studendinfo->id)
                                ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted','=','0');
                                })
                                ->select(
                                    'gradelevel.acadprogid',
                                    'studinfo.sid'
                                    )
                                ->first();


            if($studinfo->acadprogid != 5 ){

                $sy = DB::table('sy')->where('isactive','1')->first();


                $paymentId = DB::table('onlinepayments')
                        ->insertGetID(
                            [
                                'picUrl'=>'onlinepayments/'.$studinfo->sid.'/'.$studinfo->sid.'-payment-'.$time.'.'.$extension,
                                'queingcode'=>$studinfo->sid,
                                'paymentType'=>$request->get('paymentType'),
                                'amount'=>str_replace(',','',$request->get('amount')),
                                'refNum'=>$request->get('refNum'),
                                'bankName'=>$request->get('bankName'),
                                'TransDate'=>$request->get('transDate'),
                                'paymentDate'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss'),
                                'syid'=> $sy->id,
                                'semid'=>1
                            ]
                        );
            
            }
            else{

                $sy = DB::table('sy')->where('isactive','1')->first();
                $sem = DB::table('semester')->where('isactive','1')->first();

                $paymentId = DB::table('onlinepayments')
                        ->insertGetID(
                            [
                                'picUrl'=>'onlinepayments/'.$studinfo->sid.'/'.$studinfo->sid.'-payment-'.$time.'.'.$extension,
                                'queingcode'=>$studinfo->sid,
                                'paymentType'=>$request->get('paymentType'),
                                'amount'=>str_replace(',','',$request->get('amount')),
                                'refNum'=>$request->get('refNum'),
                                'bankName'=>$request->get('bankName'),
                                'TransDate'=>$request->get('transDate'),
                                'paymentDate'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss'),
                                'syid'=> $sy->id,
                                'semid'=>$sem->id
                            ]
                        );


            }
 
                   
            foreach($perItems as $item){

                $itemDetails = explode(',', $item);

                $pointers =  explode(' ', $itemDetails[2]);

                $paymentDate = 0;

                if($pointers[0] == 2){

                    if($pointers[3] == 0){

                        $paymentDate = null;

                    }else{

                        $paymentDate =  $pointers[3];

                    }

                }

                DB::table('onlinepaymentdetails')
                        ->insert(
                            [
                                'headerid'=> $paymentId,
                                'description'=> $itemDetails[1],
                                'amount'=> $itemDetails[0],
                                'paykind'=>$pointers[0],
                                'classid'=>$pointers[1],
                                'payscheddetailid'=>$pointers[2],
                                'tuitionMonth'=> $paymentDate,
                                'quantity'=> $itemDetails[3]
                            ]
                        );
    
            }

            $paymenInfo = DB::table('onlinepayments')
                            ->join('preregistration',function($join){
                                $join->on('onlinepayments.queingcode','=','preregistration.queing_code');
                            })
                            ->select(
                                'onlinepayments.*',
                                'preregistration.first_name',
                                'preregistration.last_name'
                            )
                            ->where('onlinepayments.id',$paymentId)->first();

            $urlFolder = str_replace('http://','',$request->root());

            if (! File::exists(public_path().'onlinepayments/'.$studinfo->sid)) {

                $path = public_path('onlinepayments/'.$studinfo->sid);

                if(!File::isDirectory($path)){

                    File::makeDirectory($path, 0777, true, true);
                }
                
            }
     
            if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studinfo->sid)) {

                $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studinfo->sid;

                if(!File::isDirectory($cloudpath)){

                    File::makeDirectory($cloudpath, 0777, true, true);

                }
                
            }
          

            $img = Image::make($file->path());

            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studinfo->sid.'/'.$studinfo->sid.'-payment-'.$time.'.'.$extension;

            $img->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
            })->save($clouddestinationPath);

            $destinationPath = public_path('onlinepayments/'.$studinfo->sid.'/'.$studinfo->sid.'-payment-'.$time.'.'.$extension);

            $img->save($destinationPath);

            

            $data = array(
                (object)
                [
                'status'=>'1',
                'message'=>'SUCCESS',
            ]);

            return $data;

      

    }


    public function getremBill(){

        $studendinfo = Session::get('studentInfo');
        
        $prereg = (object)[
            'sid'=>$studendinfo->sid,
            'queing_code'=>0000000000000000,
            'lrn'=>0000000000000000,
        ];

        $assessment =  Billing::remBill($prereg);

        return view('parentsportal.pages.payments.paymentselection')->with('assessment',$assessment);


    }

    public function enrollment_record(){
       
        $studendinfo = Session::get('studentInfo');
        if(!isset($studendinfo->id)){
            Auth::logout();
            Session::flush();
            return redirect('/login');
        }
        $studid = $studendinfo->id;
        $syid = null;
        $semid = null;
        return  \App\Models\SuperAdmin\SuperAdminData::enrollment_record($syid, $semid, $studid);
    }

    public function enrollment_grades(Request $request){

        $studendinfo = Session::get('studentInfo');
        
        if(!isset($studendinfo->id)){
           return false;
        }

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $blockid = $request->get('blockid');
        $levelid = $request->get('levelid');
        $studinfo = DB::table('studinfo')->where('deleted',0)->where('id',$studendinfo->id)->first();
        $acad = DB::table('gradelevel')->where('id',$levelid)->select('acadprogid')->first()->acadprogid;
        $strand = $studinfo->strandid;
        $studid = $studinfo->id;

        $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
        if($levelid == 14 || $levelid == 15){
            if($grading_version->version == 'v2'){
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $levelid,$studid,$syid,$strand,null,$sectionid);
            }else{
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $levelid,$studid,$syid,$strand,null,$sectionid);
            }
            $studgrades = collect($studgrades)->where('semid',$semid)->sortBy('sortid')->values();
        }else{
            if($grading_version->version == 'v2'){
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $levelid,$studid,$syid,null,null,$sectionid);
            }else{
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $levelid,$studid,$syid,null,null,$sectionid);
            }
            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($levelid);
            $grades = $studgrades;
            $grades = collect($grades)->sortBy('sortid')->values();
            $finalgrade = collect($grades)->where('id','G1')->values();
            $studgrades = collect($grades)->where('isVisible','1')->values();
        } 
        
        return $studgrades;
       
    }

    public function enrollment_attendance(Request $request){
        $syid = $request->get('syid');
        $studendinfo = Session::get('studentInfo');
        $studid = $studendinfo->id;
        $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($syid);
        foreach( $attendance_setup as $item){
            $month_count = \App\Models\Attendance\AttendanceData::monthly_attendance_count($syid,$item->month,$studid,$item->year);
            $item->present = collect($month_count)->where('present',1)->count() + collect($month_count)->where('tardy',1)->count() + collect($month_count)->where('cc',1)->count();
            $item->absent = collect($month_count)->where('absent',1)->count();
            if($item->present > $item->days){
                $item->present = $item->days;
            }
        }
        return $attendance_setup;
    }

    
    public function enrollment_subjects(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $blockid = $request->get('blockid');
        $levelid = $request->get('levelid');

        if($levelid == 14 || $levelid == 15){
            $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule_ajax($request);
        }
        else{
            $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule_ajax($request);
        }

        return view('parentsportal.pages.schedplot')->with('schedule',$schedule);
    }

    public function student_billing(Request $request){
       
        $studendinfo = Session::get('studentInfo');
        $studid = $studendinfo->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        if($semid == null){
            $semid = 1;
        }
        return  \App\Models\SuperAdmin\SuperAdminData::student_billing($syid, $semid, $studid);
   
    }

    public function student_student_ledger(Request $request){
       
        $studendinfo = Session::get('studentInfo');
        $studid = $studendinfo->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        if($semid == null){
            $semid = 1;
        }
        return  \App\Models\SuperAdmin\SuperAdminData::student_student_ledger($syid, $semid, $studid);
   
    }

    public function previous_balance(Request $request){
       
        $studendinfo = Session::get('studentInfo');
        $studid = $studendinfo->id;
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        return  \App\Models\SuperAdmin\SuperAdminData::previous_balance($syid, $semid, $studid);
   
    }


    public function ps_grades(Request $request){
       
        $student = Session::get('studentInfo');
        $syid = $request->get('syid');
       
        $checkGrades = DB::table('grading_system_pgrades')
                              ->join('grading_system_detail',function($join){
                                    $join->on('grading_system_pgrades.gsdid','=','grading_system_detail.id');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                              ->join('grading_system',function($join){
                                    $join->on('grading_system_detail.headerid','=','grading_system.id');
                                    $join->where('grading_system.deleted',0);
                                    $join->where('grading_system.id',2);
                              })
                              ->where('grading_system_pgrades.deleted',0)
                              ->where('studid',$student->id)
                              ->where('syid',$syid)
                              ->select(
                                    'grading_system_pgrades.id',
                                    'grading_system_pgrades.q1eval',
                                    'grading_system_pgrades.q2eval',
                                    'grading_system_pgrades.q3eval',
                                    'grading_system_pgrades.q4eval',
                                    'grading_system_detail.description',
                                    'value',
                                    'sort',
                                    'type',
                                    'group'
                              )
                              ->orderBy('sort')
                              ->get();

        $rv = DB::table('grading_system_ratingvalue')
                            ->where('deleted',0)
                            ->where('gsid',2)
                            ->orderBy('sort')
                            ->get();
                            // return $rv;
        $status = \App\Models\PreSchool\PSGradeStatus\PSGradeStatusData::ps_grade_status_list($student->id,$student->ensectid,$syid);
  
        if(count($status) > 0){
            foreach($checkGrades as $item){
                if($status[0]->q1status != null){
                    foreach($rv as $rv_item){
                        if($item->q1eval == $rv_item->id){
                            $item->q1eval = $rv_item->value;
                        }
                    }
                }else{
                    $item->q1eval = null;
                }
                if($status[0]->q2status != null){
                    $item->q2eval = null;
                    foreach($rv as $rv_item){
                        if($item->q2eval == $rv_item->id){
                            $item->q2eval = $rv_item->value;
                        }
                    }
                }else{
                    $item->q2eval = null;
                }
                if($status[0]->q3status != null){
                    $item->q3eval = null;
                        foreach($rv as $rv_item){
                        if($item->q3eval == $rv_item->id){
                            $item->q3eval = $rv_item->value;
                        }
                    }
                }else{
                    $item->q3eval = null;
                }
                if($status[0]->q4status != null){
                    $item->q4eval = null;
                    foreach($rv as $rv_item){
                        if($item->q4eval == $rv_item->id){
                            $item->q4eval = $rv_item->value;
                        }
                    }
                }else{
                    $item->q4eval = null;
                }



            }
        }else{
            foreach($checkGrades as $item){
                $item->q1eval = null;
                $item->q2eval = null;
                $item->q3eval = null;
                $item->q4eval = null;
            }
        }

        return $checkGrades;

    }
    
    

}
