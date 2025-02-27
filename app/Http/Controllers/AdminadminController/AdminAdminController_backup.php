<?php

namespace App\Http\Controllers\AdminAdminController;

use Illuminate\Http\Request;
use DB;
// use App\Models\Principal\SPP_SchoolYear;
// use App\Models\Principal\SPP_AcademicProg;
// use App\Models\Principal\SPP_Rooms;
// use App\Models\Principal\SPP_Calendar;
// use App\Models\Principal\SPP_Privelege;
// use App\Models\Principal\SPP_Truncanator;
// use App\Models\Principal\SPP_PermissionRequest;
// use App\Models\Principal\SPP_Announcement;
use App\Models\Principal\SPP_Gradelevel;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Teacher;
use File;
use Session;
use Crypt;
use App\FilePath;
use Image;
use Redirect;
use Hash;
use PDF;
use DateTime;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;

class AdminAdminController extends \App\Http\Controllers\Controller
{
    public function viewadmiadmin($id)
    {

        $schoolInfo = DB::table('schoollist')->where('id',$id)->first();

        if(isset($schoolInfo->id)){
            Session::put('schoolinfo',$schoolInfo);
            Session::put('schoolid',$id);
        }
        else{

            return back();

        }   

        

        $infoCount = DB::table('schoolinfo')->count();
            $teachersinfo = SPP_Teacher::filterTeacherFaculty(null,null,null,null,null);

            $glevel = SPP_Gradelevel::getGradeLevel(null,null,null,null,null)[0]->data;
            
            $sydetails = DB::table('sy')
                ->where('isactive',1)
                ->get();

            foreach($glevel as $item){

                $gsStudents = SPP_EnrolledStudent::getStudent(null,null,null,null,null,null,null,$item->id)[0]->count;

                $item->studCount = $gsStudents;
            }
            return view('adminITPortal.pages.home')
                ->with('fsinfo', $teachersinfo)
                ->with('sydetails', $sydetails)
                ->with('glevel', $glevel);
    }

    public function studentmasterlist(Request $request){


        $students = DB::table('studinfo')
                        ->where('studinfo.deleted',0);

        $data = (object)[];

        if($request->has('enrolled') && $request->get('enrolled') == 'enrolled'  ){

            $students = $students->where('studinfo.studstatus',1);

       

            $activeSy = DB::table('sy')->where('isactive',1)->first()->id;
            $activeSem = DB::table('semester')->where('isactive',1)->first()->id;



            if($request->has('sy') && $request->get('sy') != null  ){
                $activeSy = $request->get('sy');
            }
            if($request->has('sem') && $request->get('sem') != null  ){

                $activeSem = $request->get('sem');
            }



            if( ( $request->has('announcement') && $request->get('announcement') != null ) ){

                if(( $request->has('annID') && $request->get('annID') != null )){

                    $students = $students
                                ->whereIn('studinfo.levelid',$request->get('gradelevel'))
                                ->leftJoin('notifications',function($join) use($request){
                                    $join->on('studinfo.userid','=','notifications.recieverid');
                                    $join->where('notifications.type','=',1);
                                    $join->where('notifications.deleted',0);
                                    $join->where('notifications.headerid',$request->get('annID'));
                                })
                                ->select('studinfo.*','notifications.status','notifications.created_at as createddatetime')
                                ->distinct()
                                ->get();

                    if( ( $request->has('parents') && $request->get('parents') != null ) ){

                        foreach($students as $item){

                            if($item->ismothernum == 1){

                                $item->parentName = ucwords(strtolower($item->mothername));

                            }
                            else if($item->isfathernum == 1){

                                $item->parentName = ucwords(strtolower($item->fathername));

                            }
                            else if($item->isguardannum == 1){

                                $item->parentName = ucwords(strtolower($item->guardianname));
                            }

                            try{

                                

                                $user = DB::table('users')
                                            ->where('email','P'.$item->sid)
                                            ->leftJoin('notifications',function($join) use($request){
                                                $join->on('users.id','=','notifications.recieverid');
                                                $join->where('notifications.type','=',1);
                                                $join->where('notifications.deleted',0);
                                                $join->where('notifications.headerid',$request->get('annID'));
                                            })
                                            ->select('users.id','notifications.status','notifications.created_at as createddatetime')
                                            ->first();
                                            
                                $item->parentUserid = $user->id;
                                $item->status = $user->status;

                            } catch (\Exception $e) {

                               

                            }
                        }

                    }


                    return $students;

                }
                else{

                    return "Announcement ID is required!";

                }

            }


            if( ( $request->has('textblast') && $request->get('textblast') != null ) ){

                if(( $request->has('annID') && $request->get('annID') != null )){

                    $currentDay = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD');

                    $students = $students
                                ->whereIn('studinfo.levelid',$request->get('gradelevel'))
                                ->leftJoin('smsbunkertextblast',function($join) use($request,$currentDay) {
                                    $join->on('studinfo.userid','=','smsbunkertextblast.studid');
                                    // $join->where('smsbunkertextblast.messageid',$request->get('annID'));
                                    $join->whereDate('smsbunkertextblast.createddatetime', '=', \Carbon\Carbon::today()->toDateString());
                                })
                                ->select('studinfo.*','smsbunkertextblast.smsstatus','smsbunkertextblast.createddatetime')
                                ->distinct()
                                ->get();

                    if( ( $request->has('parents') && $request->get('parents') != null ) ){

                        foreach($students as $item){

                            if($item->ismothernum == 1){

                                $item->parentName = ucwords(strtolower($item->mothername));

                            }
                            else if($item->isfathernum == 1){

                                $item->parentName = ucwords(strtolower($item->fathername));

                            }
                            else if($item->isguardannum == 1){

                                $item->parentName = ucwords(strtolower($item->guardianname));
                            }

                            try{

                                

                                $user = DB::table('users')
                                            ->where('email','P'.$item->sid)
                                            ->leftJoin('smsbunkertextblast',function($join) use($request){
                                                $join->on('users.id','=','smsbunkertextblast.studid');
                                                $join->where('smsbunkertextblast.messageid',$request->get('annID'));
                                            })
                                            ->select(
                                                'users.id',
                                                'smsbunkertextblast.smsstatus',
                                                'smsbunkertextblast.createddatetime')
                                            ->first();
                                            
                                $item->parentUserid = $user->id;
                                $item->status = $user->smsstatus;

                            } catch (\Exception $e) {

                               

                            }
                        }

                    }


                    return $students;

                }
                else{

                    return "Announcement ID is required!";

                }

            }
            
            
            $students = $students->leftJoin('enrolledstud',function($join) use($activeSy){
                            $join->on('studinfo.id','=','enrolledstud.studid');
                            $join->where('enrolledstud.deleted','0');
                            $join->where('enrolledstud.syid',$activeSy);
                        })
                        ->leftJoin('sh_enrolledstud',function($join) use($activeSy,$activeSem){
                            $join->on('studinfo.id','=','sh_enrolledstud.studid');
                            $join->where('sh_enrolledstud.deleted','0');
                            $join->where('sh_enrolledstud.syid',$activeSy);
                            $join->where('sh_enrolledstud.semid',$activeSem);
                        });

            if( ( $request->has('datefrom') && $request->get('datefrom') != null ) ){
            
                $datefrom = \Carbon\Carbon::create($request->get('datefrom'))->isoFormat('YYYY-MM-DD');

                $students =  $students->where(function($query)use($datefrom){
                    $query->whereDate('enrolledstud.dateenrolled','>=',$datefrom);
                    $query->orWhereDate('sh_enrolledstud.dateenrolled','>=',$datefrom);
                });

            }
            
            if( ( $request->has('dateto') && $request->get('dateto') != null ) ){

                $dateTo = \Carbon\Carbon::create($request->get('dateto'))->isoFormat('YYYY-MM-DD');

                $students =  $students->where(function($query)use($dateTo){

                    $query->whereDate('enrolledstud.dateenrolled','<=',$dateTo);
                    $query->orWhereDate('sh_enrolledstud.dateenrolled','<=',$dateTo);

                });

            }

            $enrolledstud = $students
                            ->join('gradelevel',function($join){
                                $join->on('studinfo.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted','0');
                            })
                            ->select('studinfo.*','gradelevel.acadprogid')
                            ->get();

            if($request->has('withacadprog') && $request->get('withacadprog') == 'withacadprog'  ){

                $data->enrrolledseniorhigh = collect($enrolledstud)->where('acadprogid',5)->count();
                $data->nursery = collect($enrolledstud)->where('acadprogid',2)->count();
                $data->gradeschool =  collect($enrolledstud)->where('acadprogid',3)->count();
                $data->juniorhigh =  collect($enrolledstud)->where('acadprogid',4)->count();
                $data->college =  collect($enrolledstud)->where('acadprogid',6)->count();
            }
            
            if($request->has('withgradelevel') && $request->get('withgradelevel') == 'withgradelevel'){
                $data->n = collect($enrolledstud)->where('levelid',4)->count();
                $data->k1 = collect($enrolledstud)->where('levelid',2)->count();
                $data->k2 = collect($enrolledstud)->where('levelid',3)->count();
                $data->g1 = collect($enrolledstud)->where('levelid',1)->count();
                $data->g2 = collect($enrolledstud)->where('levelid',5)->count();
                $data->g3 = collect($enrolledstud)->where('levelid',6)->count();
                $data->g4 = collect($enrolledstud)->where('levelid',7)->count();
                $data->g5 = collect($enrolledstud)->where('levelid',16)->count();
                $data->g6 = collect($enrolledstud)->where('levelid',9)->count();
                $data->g7 = collect($enrolledstud)->where('levelid',10)->count();
                $data->g8 = collect($enrolledstud)->where('levelid',11)->count();
                $data->g9 = collect($enrolledstud)->where('levelid',12)->count();
                $data->g10 = collect($enrolledstud)->where('levelid',13)->count();
                $data->g11 = collect($enrolledstud)->where('levelid',14)->count();
                $data->g12 = collect($enrolledstud)->where('levelid',15)->count();
                $data->year1 = collect($enrolledstud)->where('levelid',17)->count();
                $data->year2 = collect($enrolledstud)->where('levelid',18)->count();
                $data->year3 = collect($enrolledstud)->where('levelid',19)->count();
                $data->year4 = collect($enrolledstud)->where('levelid',20)->count();
            }

            if($request->has('students') && $request->get('students') == 'students'  ){

                if($request->has('gradelevel') && $request->get('gradelevel') != null){

                    $students->whereIn('studinfo.levelid',$request->get('gradelevel'));

                }

                $enrolledstud = $students->get();

                return $enrolledstud;

            }
           
            if($request->has('count') && $request->get('count') == 'count'  ){

                $data->totalenrolledstudents = $enrolledstud->count();

            }

        }

        return collect($data);

    }

   


    public function adminviewenrolledstudents(){

        $client = new \GuzzleHttp\Client();
        $result = $client->request('GET', 'http://essentielv2.ck/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel');
        $students = $result->getBody()->getContents();

        $students =  json_decode($students, true);

        return $students;




        return view('adminITPortal.pages.students');

    }


    public function chrngtrans(Request $request){

        $date = Carbon::create('2020-06-11')->isoFormat('YYYY-MM-DD');

        $transdate = DB::table('chrngtrans')
                ->select(
                    'ornum',
                    'totalamount',
                    'amountpaid',
                    'cancelled',
                    'transdate'
                    )
                ->where('cancelled','0')
                ->where('syid',DB::table('sy')->where('isactive','1')->first()->id)
                ->where('semid',DB::table('semester')->where('isactive','1')->first()->id);


        $activeSy = DB::table('sy')->where('isactive',1)->first()->id;
        $activeSem = DB::table('semester')->where('isactive',1)->first()->id;

        if($request->has('sy') && $request->get('sy') != null  ){
            $activeSy = $request->get('sy');
        }
        if($request->has('sem') && $request->get('sem') != null  ){

            $activeSem = $request->has('sem');
        }


        if( ( $request->has('datefrom') && $request->get('datefrom') != null ) ){

            $datefrom = \Carbon\Carbon::create($request->get('datefrom'))->isoFormat('YYYY-MM-DD');

            $transdate =  $transdate->where('transdate','>=',$datefrom);

        }
        
        if( ( $request->has('dateto') && $request->get('dateto') != null ) ){

            $dateTo = \Carbon\Carbon::create($request->get('dateto'))->isoFormat('YYYY-MM-DD');

            $transdate =  $transdate->where('transdate','<=',$dateTo);

        }

        if( ( $request->has('skip') && $request->get('skip') != null ) ){

            $transdate =  $transdate->skip(( $request->get('skip') - 1 ) * 10);

        }

        if($request->has('detail') && $request->get('detail') == 'detail'){

            return $transdate->take(10)->get();

        }

        if($request->has('count') && $request->get('count') == 'count'){

            return array( (object)['count'=>$transdate->count()]);

        }
            
        if($request->has('total') && $request->get('total') == 'total'){

            return array( (object)[
                                'totalAmountPaid'=> $transdate->sum('amountpaid'), 
                                'totalAmount'=>$transdate->sum('totalamount')
                            ]);

        }   
    }

    public static function targetcollection(){

        $transdate = DB::table('chrngtrans')
                            ->select(
                                'ornum',
                                'totalamount',
                                'amountpaid',
                                'cancelled',
                                'transdate'
                                )
                            ->join('studinfo',function($join){
                                $join->on('chrngtrans.studid','=','studinfo.id');
                                $join->where('studinfo.studstatus','1');
                            })
                            ->where('cancelled','0')
                            ->where('chrngtrans.syid',DB::table('sy')->where('isactive','1')->first()->id)
                            ->where('chrngtrans.semid',DB::table('semester')->where('isactive','1')->first()->id)
                            ->sum('amountpaid');

        $studpaysched = DB::table('studpayscheddetail')
                    ->where('studpayscheddetail.syid',DB::table('sy')->where('isactive','1')->first()->id)
                    ->where('studpayscheddetail.semid',DB::table('semester')->where('isactive','1')->first()->id)
                    ->join('studinfo',function($join){
                        $join->on('studpayscheddetail.studid','=','studinfo.id');
                        $join->where('studinfo.studstatus','1');
                    })
                    ->get();

        $countUnbalance = 0;
        $id = array();

        foreach($studpaysched as $item){

            if($item->amountpay + $item->balance != $item->amount){
               
                array_push($id,(object)[
                    'studid'=>$item->studid,
                    'balance'=>$item->balance,
                    'amountpay'=>$item->amountpay,
                    'amount'=>$item->amount
                ]
                );

                $item->balance = $item->amount - $item->amountpay;

            }

        }

        foreach($studpaysched as $item){

            if($item->amountpay + $item->balance != $item->amount){

                $countUnbalance += 1;

            }
        }


        return collect($studpaysched)->sum('amountpay');

        
        return $billings;

    }

    public function enrollmentReport(){

        try{

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel');
            $students = $result->getBody()->getContents();
            $students =  (object)json_decode($students, true);

            return view('adminITPortal.pages.reports.enrollment')->with('students',$students);

        } catch (\Exception $e) {

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel');
            $students = $result->getBody()->getContents();
            $students =  (object)json_decode($students, true);

            return view('adminITPortal.pages.reports.enrollment')->with('students',$students); 

        }

    }

    public function filterEnrollmentReport(Request $request){

        try{

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto'));
            $students = $result->getBody()->getContents();
            $students =  (object)json_decode($students, true);

            return view('adminITPortal.pages.reports.enrolledsection')
            ->with('students',$students);

        } catch (\Exception $e) {

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/studentmasterlist?enrolled=enrolled&count=count&withacadprog=withacadprog&withgradelevel=withgradelevel&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto'));
            $students = $result->getBody()->getContents();
            $students =  (object)json_decode($students, true);

            return view('adminITPortal.pages.reports.enrolledsection')
            ->with('students',$students);
        }

       

    }

    public function cashtransReport(Request $request){


        try{

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/cashtransaction?detail=detail');
            $cashtrans = $result->getBody()->getContents();
            $cashtrans =  (object)json_decode($cashtrans, true);

            $amountpaidresult = $client->request('GET',$url->eslink.'/cashtransaction?total=total');
            $amountpaid = $amountpaidresult->getBody()->getContents();
            $amountpaid =  json_decode($amountpaid, true);
        
            $countresult = $client->request('GET',$url->eslink.'/cashtransaction?count=count');
            $count = $countresult->getBody()->getContents();
            $count =  json_decode($count, true);

            return  view('adminITPortal.pages.reports.cashtrans')
                            ->with('cashtrans',$cashtrans)
                            ->with('amountpaid',$amountpaid)
                            ->with('count',$count);

        } catch (\Exception $e) {

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/cashtransaction?detail=detail');
            $cashtrans = $result->getBody()->getContents();
            $cashtrans =  (object)json_decode($cashtrans, true);

            $amountpaidresult = $client->request('GET',$url->eslink.'/cashtransaction?total=total');
            $amountpaid = $amountpaidresult->getBody()->getContents();
            $amountpaid =  json_decode($amountpaid, true);
        
            $countresult = $client->request('GET',$url->eslink.'/cashtransaction?count=count');
            $count = $countresult->getBody()->getContents();
            $count =  json_decode($count, true);

            return  view('adminITPortal.pages.reports.cashtrans')
                            ->with('cashtrans',$cashtrans)
                            ->with('amountpaid',$amountpaid)
                            ->with('count',$count);

        }
        
    }

    public function filtercashtrans(Request $request){

        try{

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/cashtransaction?detail=detail&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto').'&skip='.$request->get('pagenum'));
            $cashtrans = $result->getBody()->getContents();
            $cashtrans =  (object)json_decode($cashtrans, true);

            $amountpaidresult = $client->request('GET',$url->eslink.'/cashtransaction?total=total');
            $amountpaid = $amountpaidresult->getBody()->getContents();
            $amountpaid =  json_decode($amountpaid, true);
        
            $countresult = $client->request('GET',$url->eslink.'/cashtransaction?count=count'.'&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto'));
            $count = $countresult->getBody()->getContents();
            $count =  json_decode($count, true);

            return view('adminITPortal.pages.reports.cashtranssection')
                            ->with('count',$count)
                            ->with('amountpaid',$amountpaid)
                            ->with('cashtrans',$cashtrans);

        } catch (\Exception $e) {

            $client = new \GuzzleHttp\Client();

            $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();

            $result = $client->request('GET',$url->eslink.'/cashtransaction?detail=detail&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto').'&skip='.$request->get('pagenum'));
            $cashtrans = $result->getBody()->getContents();
            $cashtrans =  (object)json_decode($cashtrans, true);

            $amountpaidresult = $client->request('GET',$url->eslink.'/cashtransaction?total=total');
            $amountpaid = $amountpaidresult->getBody()->getContents();
            $amountpaid =  json_decode($amountpaid, true);
        
            $countresult = $client->request('GET',$url->eslink.'/cashtransaction?count=count'.'&datefrom='.$request->get('datefrom').'&dateto='.$request->get('dateto'));
            $count = $countresult->getBody()->getContents();
            $count =  json_decode($count, true);

            return view('adminITPortal.pages.reports.cashtranssection')
                            ->with('count',$count)
                            ->with('amountpaid',$amountpaid)
                            ->with('cashtrans',$cashtrans);

        }

    }


    
    public function academicindex(Request $request)
    {
        if($request->has('syid'))
        {
            $syid = $request->get('syid');
        }else{
            $syid = DB::table('sy')
                ->where('isactive','1')
                ->first()
                ->id;
        }
        if($request->has('semid'))
        {
            $semid = $request->get('semid');
        }else{
            $semid = DB::table('semester')
                ->where('isactive','1')
                ->first()
                ->id;
        }
            
        $gradelevels = DB::table('gradelevel')
            ->select('id','levelname','acadprogid','sortid')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();
        if($request->get('action') == 'getenrollmentresults')
        {
            foreach($gradelevels as $gradelevel)
            {
                if($gradelevel->acadprogid == 6)
                {
                    $enrollees = DB::table('college_enrolledstud')
                        ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','college_enrolledstud.yearLevel as levelid','college_enrolledstud.sectionID as sectionid','college_enrolledstud.studstatus')
                        ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                        ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                        ->where('college_enrolledstud.deleted','0')
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->where('college_enrolledstud.yearLevel',$gradelevel->id)
                        ->get();

                }elseif($gradelevel->acadprogid == 5)
                {
                    $enrollees = DB::table('sh_enrolledstud')
                        ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','sh_enrolledstud.levelid','sh_enrolledstud.sectionid','sh_enrolledstud.studstatus')
                        ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->where('sh_enrolledstud.deleted','0')
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.semid',$semid)
                        ->where('sh_enrolledstud.levelid',$gradelevel->id)
                        ->get();
                }else{
                    $enrollees = DB::table('enrolledstud')
                        ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','enrolledstud.levelid','enrolledstud.sectionid','enrolledstud.studstatus')
                        ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->where('enrolledstud.deleted','0')
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.levelid',$gradelevel->id)
                        ->get();
                }
                $gradelevel->enrollees = $enrollees;
                $gradelevel->enrolleesmalecount = collect($enrollees)->whereIn('gender',['male','Male','MALE'])->count();
                $gradelevel->enrolleesfemalecount = collect($enrollees)->whereIn('gender',['female','Female','FEMALE'])->count();
                $gradelevel->enrolleescount = count($enrollees);
            }
            return view('adminITPortal.pages.academic.filterresults.enrollees')
                ->with('gradelevels', $gradelevels);
        }
        elseif($request->get('action') == 'getteachingloadsresults')
        {
            $getteachers1 = DB::table('teacher')
                ->select('id','userid','firstname','middlename','lastname','suffix',DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as sortname"))
                ->where('usertypeid','1')
                ->where('deleted','0')
                ->where('isactive','1')
                ->get();
            $getteachers2 = DB::table('faspriv')
                ->select('teacher.id','teacher.userid','firstname','middlename','lastname','suffix',DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as sortname"))
                ->join('teacher','faspriv.userid','=','teacher.userid')
                ->where('faspriv.usertype','1')
                ->where('faspriv.deleted','0')
                ->where('teacher.deleted','0')
                ->where('teacher.isactive','1')
                ->get();

            $teachers = collect();
            $teachers = collect($teachers)->merge($getteachers1);
            $teachers = collect($teachers)->merge($getteachers2);
            $teachers = collect($teachers)->unique('id')->values();
            $teachers = collect($teachers)->sortBy('sortname')->values();

            return $teachers;
            // foreach($gradelevels as $gradelevel)
            // {
            //     if($gradelevel->acadprogid == 6)
            //     {
            //         $enrollees = DB::table('college_enrolledstud')
            //             ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','college_enrolledstud.yearLevel as levelid','college_enrolledstud.sectionID as sectionid','college_enrolledstud.studstatus')
            //             ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
            //             ->whereIn('college_enrolledstud.studstatus',[1,2,4])
            //             ->where('college_enrolledstud.deleted','0')
            //             ->where('college_enrolledstud.syid',$syid)
            //             ->where('college_enrolledstud.semid',$semid)
            //             ->where('college_enrolledstud.yearLevel',$gradelevel->id)
            //             ->get();

            //     }elseif($gradelevel->acadprogid == 5)
            //     {
            //         $enrollees = DB::table('sh_enrolledstud')
            //             ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','sh_enrolledstud.levelid','sh_enrolledstud.sectionid','sh_enrolledstud.studstatus')
            //             ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
            //             ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
            //             ->where('sh_enrolledstud.deleted','0')
            //             ->where('sh_enrolledstud.syid',$syid)
            //             ->where('sh_enrolledstud.semid',$semid)
            //             ->where('sh_enrolledstud.levelid',$gradelevel->id)
            //             ->get();
            //     }else{
            //         $enrollees = DB::table('enrolledstud')
            //             ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','enrolledstud.levelid','enrolledstud.sectionid','enrolledstud.studstatus')
            //             ->join('studinfo','enrolledstud.studid','=','studinfo.id')
            //             ->whereIn('enrolledstud.studstatus',[1,2,4])
            //             ->where('enrolledstud.deleted','0')
            //             ->where('enrolledstud.syid',$syid)
            //             ->where('enrolledstud.levelid',$gradelevel->id)
            //             ->get();
            //     }
            //     $gradelevel->enrollees = $enrollees;
            //     $gradelevel->enrolleesmalecount = collect($enrollees)->whereIn('gender',['male','Male','MALE'])->count();
            //     $gradelevel->enrolleesfemalecount = collect($enrollees)->whereIn('gender',['female','Female','FEMALE'])->count();
            //     $gradelevel->enrolleescount = count($enrollees);
            // }
            return view('adminITPortal.pages.academic.filterresults.enrollees')
                ->with('gradelevels', $gradelevels);
        }
        else{
            $levelid = $request->get('levelid');      
            
            $enrolledstud = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    DB::raw('LOWER(`gender`) as gender'),
                    'enrolledstud.sectionid as ensectid',
                    'enrolledstud.sectionid',
                    'enrolledstud.levelid',
                    'sections.sectionname',
                    'gradelevel.acadprogid'
                    )
                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
                ->whereIn('enrolledstud.studstatus',[1,2,4])
                ->where('enrolledstud.deleted','0')
                ->where('enrolledstud.syid',$syid)
                ->get();
                    
            $sh_enrolledstud = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    DB::raw('LOWER(`gender`) as gender'),
                    'sh_enrolledstud.sectionid as ensectid',
                    'sh_enrolledstud.levelid',
                    'sh_enrolledstud.sectionid',
                    'sh_enrolledstud.strandid',
                    'sections.sectionname',
                    'sh_strand.strandcode',
                    'gradelevel.levelname',
                    'gradelevel.acadprogid'
                    )
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
                ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.syid',$syid)
                ->where('sh_enrolledstud.semid',$semid)
                ->get();
    
            // $college_enrolledstud = DB::table('studinfo')
            //     ->select(
            //         'studinfo.id',
            //         'lastname',
            //         'firstname',
            //         'middlename',
            //         'suffix',
            //         'gender',
            //         'sh_enrolledstud.sectionid',
            //         'college_enrolledstud.yearLevel as levelid'
            //         )
            //     ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
            //     ->whereIn('college_enrolledstud.studstatus',[1,2,4])
            //     ->where('college_enrolledstud.deleted','0')
            //     ->where('college_enrolledstud.syid',$syid)
            //     ->get();
    
            $students = collect();
            $students = $students->merge($enrolledstud);
            $students = $students->merge($sh_enrolledstud);
            $students = collect($students)->unique('id')->all();
            if($request->has('action'))
            {
                foreach($gradelevels as $gradelevel)
                {
                    $gradelevel->female = collect($students)->where('levelid', $gradelevel->id)->where('gender','female')->count();
                    $gradelevel->male = collect($students)->where('levelid', $gradelevel->id)->where('gender','male')->count();
                    $gradelevel->total = collect($students)->where('levelid', $gradelevel->id)->count();
                }
                if($request->get('action') == 'filter')
                {
                    // $students = $students->merge($college_enrolledstud);
                    
                    
                    return view('adminITPortal.pages.academic.filterresults')
                        ->with('gradelevels', $gradelevels);
                }elseif($request->get('action') == 'getcatrecords'){
                    $gradelevels = collect($gradelevels)->where('id', $levelid)->values();
                    foreach($gradelevels as $gradelevel)
                    {
                        if($gradelevel->acadprogid == 5)
                        {
                            $gradelevel->tracks = array();
                            $gradelevel->sections = array();
                            $gradelevel->strands = DB::table('sh_track')
                                ->select('sh_track.trackname','sh_strand.id as strandid','sh_strand.strandcode')
                                ->join('sh_strand','sh_track.id','=','sh_strand.trackid')
                                ->where('sh_track.deleted','0')
                                ->where('sh_strand.deleted','0')
                                ->get();
                        }else{
                            $gradelevel->sections = DB::table('sections')
                                ->where('levelid', $gradelevel->id)
                                ->where('deleted','0')
                                ->get();
                            $gradelevel->strands = array();
                            $gradelevel->tracks = array();
                        }
                    }
                    
                    $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                    
                    if($request->get('filtertype') == 'basiced')
                    {
                        $students = collect($students)->whereIn('levelid',collect($gradelevels)->pluck('id'))->values();
                        
                        if(count($students)>0)
                        {
                            foreach($students as $eachenstud)
                            {
        
                                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                // {
                                    // if($checkGradingVersion->version == 'v1'){
                                    //     $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV4($eachenstud, true, 'sf9',$syid);
                                    
                                    // }
                                    // if($checkGradingVersion->version == 'v2'){
                                    //     $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($eachenstud, true, 'sf9',$syid);    
                                    // }
                                        // return collect($eachenstud);
                                    $schoolyear = DB::table('sy')->where('id',$syid)->first();
                                    Session::put('schoolYear', $schoolyear);
                                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                    {
                                        $eachenstud->acadprogid = 4;
                                        $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                                    
                                        if($checkGradingVersion->version == 'v1'){
                                            $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV4($student, true, 'sf9',$schoolyear->id);
                                        
                                        }
                                        if($checkGradingVersion->version == 'v2'){
                                            $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($student, true, 'sf9',$schoolyear->id);    
                                            
                                        }
                                        $grades = $gradesv4;
                                
                                        $grades = collect($grades)->unique('subjectcode');
                                        $generalaverage = array();
                                    }
                                    elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                                    {
                                        $schoolyear = DB::table('sy')->where('id',$syid)->first();
                                        Session::put('schoolYear', $schoolyear);
                                        $grades = GenerateGrade::reportCardV4($student, true, 'sf9');
                                        
                                        $generalaverage =  \App\Models\Grades\GradesData::general_average($grades);
                                        $grades =  \App\Models\Grades\GradesData::get_finalrating($grades,$eachenstud->acadprogid);
                                        $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($generalaverage,$eachenstud->acadprogid);
                                    }else{
    
                                        // return $eachenstud->id;
                                        // $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$sy->strandid,null,$sy->ensectid,true);
                                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($eachenstud->levelid,$eachenstud->id,$schoolyear->id,null,null,$eachenstud->ensectid,true);
                                        // return $studgrades;
                                        $temp_grades = array();
                                        $generalaverage = array();
                                        foreach($studgrades as $item){
                                            if($item->id == 'G1'){
                                                array_push($generalaverage,$item);
                                                // array_push($temp_grades,$item);
                                            }else{
                                                array_push($temp_grades,$item);
                                            }
                                        }
                                    
                                        $studgrades = $temp_grades;
                                        $grades = collect($studgrades)->sortBy('sortid')->values();
                                    }
                                $grades = collect($grades)->values();
                                // return $grades;
                                if(count($grades)>0)
                                {
                                    foreach($grades as $grade)
                                    {
                                        if(!collect($grade)->contains('mapeh'))
                                        {
                                            $grade->mapeh = 0;
                                        }
                                        // if($grade->mapeh == 0)
                                        // {
                                        //     $finalrate = $grade->quarter1+$grade->quarter2+$grade->quarter3+$grade->quarter4;
                                        //     if($finalrate>0)
                                        //     {
                                        //         $grade->finalrating = $finalrate/4;
                                        //     }else{
                                        //         $grade->finalrating = 0;
                                        //     }
                                        // }
                                    }
                                }
                                $genave = collect($grades)->where('mapeh','0')->average('finalrating');
                                
                                if(count($generalaverage) == 0)
                                {
                                    $eachenstud->generalaverage = $genave;
    
                                }else{
                                    $eachenstud->generalaverage = $generalaverage[0]->finalrating;
                                }
                                
                                if($eachenstud->generalaverage <= 74 && $eachenstud->generalaverage != null)
                                {
                                    $eachenstud->genavecat = 'B';
                                }
    
                                if($eachenstud->generalaverage >= 75 && $eachenstud->generalaverage <= 79)
                                {
                                    $eachenstud->genavecat = 'D';
                                }
    
                                if($eachenstud->generalaverage >= 80 && $eachenstud->generalaverage <= 84)
                                {
                                    $eachenstud->genavecat = 'AP';
                                }
    
                                if($eachenstud->generalaverage >= 85 && $eachenstud->generalaverage <= 89)
                                {
                                    $eachenstud->genavecat = 'P';
                                }
    
                                if($eachenstud->generalaverage >= 90)
                                {
                                    $eachenstud->genavecat = 'A';
                                }
                                // }
                                
                            }
                            
                        }
                        // return $students;
                        $category = $request->get('category');
                        
                        $sections = array();
                        $strands = array();
                        if(!$request->has('export'))
                        {
                            foreach($gradelevels as $gradelevel)
                            {
                                $eachlevelstud = collect($students)->where('levelid', $gradelevel->id)->values();
                                
                                $studrecord = collect($eachlevelstud)->filter(function ($item) use($category) {
                                    if($category == 'B')
                                    {
                                        if($item->generalaverage <= 74 && $item->generalaverage != null)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'D')
                                    {
                                        if($item->generalaverage >= 75 && $item->generalaverage <= 79)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'AP')
                                    {
                                        if($item->generalaverage >= 80 && $item->generalaverage <= 84)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'P')
                                    {
                                        if($item->generalaverage >= 85 && $item->generalaverage <= 89)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'A')
                                    {
                                        if($item->generalaverage >= 90)
                                        {
                                            return $item;
                                        }
                                    }
                                    
                                })->values();
                                if(count($gradelevel->sections)>0)
                                {
                                    foreach($gradelevel->sections as $eachsection)
                                    {
                                        $eachsection->levelname = $gradelevel->levelname;
                                        $eachsection->male = collect($studrecord)->where('sectionid', $eachsection->id)->where('gender', 'male')->count();
                                        $eachsection->female = collect($studrecord)->where('sectionid', $eachsection->id)->where('gender', 'female')->count();
                                        $eachsection->total = collect($studrecord)->where('sectionid', $eachsection->id)->count();
                                        array_push($sections, $eachsection);
                                    }
                                }
                        
            
                            }
                            return $sections;
                        }else{
                            $students = collect($students)->where('genavecat', $category)->all();
                            // return $students;
                            if(count($students)>0)
                            {
                                foreach($students as $student)
                                {
                                    $student->sortname = $student->lastname.', '.$student->firstname;
                                }
                            }
                            $levelname = DB::table('gradelevel')->where('id', $request->get('levelid'))->first()->levelname;
                            $students = collect($students)->sortBy('sortname')->values();
                            $syinfo = db::table('sy')->where('id', $syid)->first();
                            $seminfo = db::table('semester')->where('id', $semid)->first();
                            $pdf = PDF::loadview('adminITPortal.pages.academic.pdf_gradescat',compact('students','syinfo','seminfo','category','levelname'));
                            return $pdf->stream('SHS Students With '.$category.' Grade.pdf');
                        }
                    }
                    elseif($request->get('filtertype') == 'shs')
                    {
                        $students = collect($students)->whereIn('levelid',collect($gradelevels)->pluck('id'))->values();
                        if(count($students)>0)
                        {
                            foreach($students as $eachenstud)
                            {
        
                                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                // {
                                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $eachenstud->levelid,$eachenstud->id,$syid,$eachenstud->strandid,null,$eachenstud->ensectid);
                                    $studgrades = collect($studgrades)->where('semid', $semid)->values();
                                    $temp_grades = array();
                                    $generalaverage = array();
                                    foreach($studgrades as $item){
                                        if($item->id == 'G1'){
                                            array_push($generalaverage,$item);
                                        }else{
                                            if($item->strandid == $eachenstud->strandid){
                                                array_push($temp_grades,$item);
                                            }
                                            if($item->strandid == null){
                                                array_push($temp_grades,$item);
                                            }
                                        }
                                    }
                                    $grades = $temp_grades;
                                    $grades = collect($grades)->values();
                                    if(count($grades)>0)
                                    {
                                        foreach($grades as $grade)
                                        {
                                            if(!collect($grade)->contains('mapeh'))
                                            {
                                                $grade->mapeh = 0;
                                            }
                                        }
                                    }
                                        
                                    foreach($grades as $key=>$item){
                                        $checkStrand = DB::table('sh_subjstrand')
                                                            ->where('subjid',$item->subjid)
                                                            ->where('deleted',0)
                                                            ->get();
                                        if( count($checkStrand) > 0 ){
                                            $check_same_strand = collect($checkStrand)->where('strandid',$eachenstud->strandid)->count();
                                            if( $check_same_strand == 0){
                                                unset($grades[$key]); 
                                            }
                                        }
                                    }
                                    $genave = collect($grades)->where('mapeh','0')->average('finalrating');
                                    if(count($generalaverage) == 0)
                                    {
                                        $eachenstud->generalaverage = $genave;
    
                                    }else{
                                        $eachenstud->generalaverage = $generalaverage[0]->finalrating;
                                    }
                                    
                                    if($eachenstud->generalaverage <= 74 && $eachenstud->generalaverage != null)
                                    {
                                        $eachenstud->genavecat = 'B';
                                    }
    
                                    if($eachenstud->generalaverage >= 75 && $eachenstud->generalaverage <= 79)
                                    {
                                        $eachenstud->genavecat = 'D';
                                    }
    
                                    if($eachenstud->generalaverage >= 80 && $eachenstud->generalaverage <= 84)
                                    {
                                        $eachenstud->genavecat = 'AP';
                                    }
    
                                    if($eachenstud->generalaverage >= 85 && $eachenstud->generalaverage <= 89)
                                    {
                                        $eachenstud->genavecat = 'P';
                                    }
    
                                    if($eachenstud->generalaverage >= 90)
                                    {
                                        $eachenstud->genavecat = 'A';
                                    }
                            }
                            
                        }
                        
                        $category = $request->get('category');
                        
                        $strands = array();
                        
                        if(!$request->has('export'))
                        {
                            foreach($gradelevels as $gradelevel)
                            {
                                $eachlevelstud = collect($students)->where('levelid', $gradelevel->id)->values();
                                
                                $studrecord = collect($eachlevelstud)->filter(function ($item) use($category) {
                                    if($category == 'B')
                                    {
                                        if($item->generalaverage <= 74 && $item->generalaverage != null)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'D')
                                    {
                                        if($item->generalaverage >= 75 && $item->generalaverage <= 79)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'AP')
                                    {
                                        if($item->generalaverage >= 80 && $item->generalaverage <= 84)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'P')
                                    {
                                        if($item->generalaverage >= 85 && $item->generalaverage <= 89)
                                        {
                                            return $item;
                                        }
                                    }
                                    elseif($category == 'A')
                                    {
                                        if($item->generalaverage >= 90)
                                        {
                                            return $item;
                                        }
                                    }
                                    
                                })->values();
        
                                if(count($gradelevel->strands)>0)
                                {
                                    foreach($gradelevel->strands as $eachstrand)
                                    {
                                        $eachstrand->levelname = $gradelevel->levelname;
                                        $eachstrand->male = collect($studrecord)->where('strandid', $eachstrand->strandid)->where('gender', 'male')->count();
                                        $eachstrand->female = collect($studrecord)->where('strandid', $eachstrand->strandid)->where('gender', 'female')->count();
                                        $eachstrand->total = collect($studrecord)->where('strandid', $eachstrand->strandid)->count();
                                        array_push($strands, $eachstrand);
                                    }
                                }
            
                            }
                            return $strands;
                        }else{
                            $students = collect($students)->where('genavecat', $category)->all();
                            // return $students;
                            if(count($students)>0)
                            {
                                foreach($students as $student)
                                {
                                    $student->sortname = $student->lastname.', '.$student->firstname;
                                }
                            }
                            $levelname = DB::table('gradelevel')->where('id', $request->get('levelid'))->first()->levelname;
                            $students = collect($students)->sortBy('sortname')->values();
                            $syinfo = db::table('sy')->where('id', $syid)->first();
                            $seminfo = db::table('semester')->where('id', $semid)->first();
                            $pdf = PDF::loadview('adminITPortal.pages.academic.pdf_gradescatshs',compact('students','syinfo','seminfo','category','levelname'));
                            return $pdf->stream('SHS Students With '.$category.' Grade.pdf');
                        }
    
                    }
    
                }elseif($request->get('action') == 'export')
                {
                    if($request->get('exporttype') == 'numberofenrollees')
                    {
                        $syinfo = db::table('sy')->where('id', $syid)->first();
                        $pdf = PDF::loadview('adminITPortal.pages.academic.pdf_numberofenrollees',compact('gradelevels','syinfo'));
                        return $pdf->stream('Number of Enrollees.pdf');
                    }
                }
            }else{
                return view('adminITPortal.pages.academic.index')
                    ->with('gradelevels', $gradelevels);
            }
        }

    }
    public function academicstudents(Request $request)
    {
        
        if($request->has('syid'))
        {
            $syid = $request->get('syid');
        }else{
            $syid = DB::table('sy')
                ->where('isactive','1')
                ->first()
                ->id;
        }
        if($request->has('semid'))
        {
            $semid = $request->get('semid');
        }else{
            $semid = DB::table('semester')
                ->where('isactive','1')
                ->first()
                ->id;
        }
            
        $gradelevels = DB::table('gradelevel')
            ->select('id','levelname','acadprogid','sortid')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        foreach($gradelevels as $gradelevel)
        {
            if($gradelevel->acadprogid == 6)
            {
                $enrollees = DB::table('college_enrolledstud')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','college_enrolledstud.yearLevel as levelid','college_enrolledstud.sectionID as sectionid','college_enrolledstud.studstatus','college_courses.courseabrv','college_colleges.collegeabrv')
                    ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                    ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                    ->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                    ->where('college_enrolledstud.deleted','0')
                    ->where('college_enrolledstud.syid',$syid)
                    ->where('college_enrolledstud.semid',$semid)
                    ->where('college_enrolledstud.yearLevel',$gradelevel->id)
                    ->get();

            }elseif($gradelevel->acadprogid == 5)
            {
                $enrollees = DB::table('sh_enrolledstud')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','sh_enrolledstud.levelid','sh_enrolledstud.sectionid','sh_enrolledstud.studstatus','sections.sectionname','sh_strand.strandcode','sh_track.trackname')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                    ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                    ->where('sh_enrolledstud.deleted','0')
                    ->where('sh_enrolledstud.syid',$syid)
                    ->where('sh_enrolledstud.semid',$semid)
                    ->where('sh_enrolledstud.levelid',$gradelevel->id)
                    ->get();
            }else{
                $enrollees = DB::table('enrolledstud')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','enrolledstud.levelid','enrolledstud.sectionid','enrolledstud.studstatus','sections.sectionname')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('sections','enrolledstud.sectionid','=','sections.id')
                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                    ->where('enrolledstud.deleted','0')
                    ->where('enrolledstud.syid',$syid)
                    ->where('enrolledstud.levelid',$gradelevel->id)
                    ->get();
            }
            $gradelevel->label = ucwords(strtolower($gradelevel->levelname)).': '.count($enrollees).' enrollees';
            $gradelevel->enrollees = $enrollees;
            $gradelevel->enrolleesmalecount = collect($enrollees)->whereIn('gender',['male','Male','MALE'])->count();
            $gradelevel->enrolleesfemalecount = collect($enrollees)->whereIn('gender',['female','Female','FEMALE'])->count();
            $gradelevel->enrolleescount = count($enrollees);
        }
        if($request->get('action') == 'getenrollmentresults')
        {
            $sydesc = DB::table('sy')->where('id', $syid)->first()->sydesc;
            $semdesc = DB::table('semester')->where('id', $semid)->first()->semester;
            return view('adminITPortal.pages.academic.filterresults.enrollees')
                ->with('gradelevels', $gradelevels)
                ->with('sydesc', $sydesc)
                ->with('semdesc', $semdesc);
        }
        elseif($request->get('action') == 'getstatistics_sf5')
        {
            if($request->has('levelid'))
            {
                $results = collect($gradelevels)->where('id', $request->get('levelid'))->values();
                $levelid = $request->get('levelid');
            }else{
                $results = collect($gradelevels)->take(1);
                $levelid = $results[0]->id;
            }
            $schoolyear = DB::table('sy')->where('id',$syid)->first();
            Session::put('schoolYear', $schoolyear);
            foreach($results as $gradelevel)
            {
                if(count($gradelevel->enrollees)>0)
                {
                    foreach($gradelevel->enrollees as $eachenrollee)
                    {
                        $eachenrollee->ensectid = $eachenrollee->sectionid;
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                        {
                            if($syid == 2){
                                $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$eachenrollee->id,null);
                                
                                
                                if($request->has('action'))
                                {
                                    $studentInfo[0]->data = DB::table('studinfo')
                                                        ->select('studinfo.*','studinfo.sectionid as ensectid','studinfo.levelid as enlevelid','gradelevel.levelname','acadprogid')
                                                        ->where('studinfo.id',$eachenrollee->id)
                                    
                                                        ->join('gradelevel','studinfo.levelid','=','gradelevel.id')->get();
                                    $studentInfo[0]->count = 1;
                                    $studentInfo[0]->data[0]->teacherfirstname = "";
                                    $studentInfo[0]->data[0]->teachermiddlename = " ";
                                    $studentInfo[0]->data[0]->teacherlastname = "";
                                }
                        
                                if($studentInfo[0]->count == 0){
                        
                                    $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$eachenrollee->id,null,4);
                                    
                                    $studentInfo = DB::table('enrolledstud')
                                        ->where('studid',$eachenrollee->id)
                                        ->where('enrolledstud.deleted',0)
                                        ->select(
                                            'enrolledstud.sectionid as ensectid',
                                            'acadprogid',
                                            'enrolledstud.studid as id',
                                            'enrolledstud.strandid',
                                            'enrolledstud.semid',
                                            'lastname',
                                            'firstname',
                                            'middlename',
                                            'lrn',
                                            'dob',
                                            'gender',
                                            'levelname',
                                            'sections.sectionname as ensectname'
                                            )
                                        ->join('gradelevel',function($join){
                                            $join->on('enrolledstud.levelid','=','gradelevel.id');
                                            $join->where('gradelevel.deleted',0);
                                        })
                                        ->join('sections',function($join){
                                            $join->on('enrolledstud.sectionid','=','sections.id');
                                            $join->where('sections.deleted',0);
                                        })
                                            ->join('studinfo',function($join){
                                            $join->on('enrolledstud.studid','=','studinfo.id');
                                            $join->where('gradelevel.deleted',0);
                                        })
                                        ->get();
                                                        
                                    $studentInfo = array((object)[
                                            'data'=>   $studentInfo                             
                                        ]);
                                                        
                                                        
                                }
                                $acad = $eachenrollee->acadprogid;
                                $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($studentInfo[0]->data[0], true, 'sf9',2);    
                                       
                                $grades = $gradesv4;
                                $grades = collect($grades)->unique('subjectcode');
                                
                            }else{
                                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $gradelevel->id,$eachenrollee->id,$syid,null,null,$eachenrollee->sectionid);
                           
                                $temp_grades = array();
                                $finalgrade = array();
                                foreach($studgrades as $item){
                                    if($item->id == 'G1'){
                                        array_push($finalgrade,$item);
                                    }else{
                                        array_push($temp_grades,$item);
                                    }
                                }
                               
                                $studgrades = $temp_grades;
                                $grades = collect($studgrades)->sortBy('sortid')->values();
                            }
                            $generalaverage =  array();
                        }
                        elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                        {
                            $grades = GenerateGrade::reportCardV4($eachenrollee, true, 'sf9');
                              
                            $generalaverage =  \App\Models\Grades\GradesData::general_average($grades);
                            $grades =  \App\Models\Grades\GradesData::get_finalrating($grades,$gradelevel->acadprogid);
                            $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($generalaverage,$gradelevel->acadprogid);
                        }else{
                            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel->levelid,$eachenrollee->id,$schoolyear->id,null,null,$eachenrollee->sectionid,true);
                            $temp_grades = array();
                            $generalaverage = array();
                            foreach($studgrades as $item){
                                if($item->id == 'G1'){
                                    if($item->finalrating == null)
                                    {
                                        $item->finalrating = $item->lfr;
                                    }
                                    array_push($generalaverage,$item);
                                }else{
                                    array_push($temp_grades,$item);
                                }
                            }
                        
                            $studgrades = $temp_grades;
                            $grades = collect($studgrades)->sortBy('sortid')->values();
                        }
                        $genave = null;
                        
                        if(count($generalaverage)>0)
                        {
                            $genave = $generalaverage[0]->finalrating;
                        }else{
                            if(collect($grades)->where('finalrating',null)->where('mapeh','0')->where('inTLE','0')->count() == 0)
                            {
                                $genave = number_format(collect($grades)->where('mapeh','0')->where('inTLE','0')->avg('finalrating'));
                            }
                        }
                        $eachenrollee->genave = $genave;
                        $teachername ='';

                        $getteachername = DB::table('sectiondetail')
                            ->select('teacher.*')
                            ->leftJoin('teacher','sectiondetail.teacherid','=','teacher.id')
                            ->where('sectiondetail.sectionid', $eachenrollee->sectionid)
                            ->where('sectiondetail.syid', $syid)
                            // ->where('sectiondetail.semid', $semid)
                            ->where('sectiondetail.deleted','0')
                            ->first();

                        if($getteachername)
                        {
                            $teachername =$getteachername->firstname.' '.$getteachername->middlename.' '.$getteachername->lastname.' '.$getteachername->suffix;;
                        }
                        $eachenrollee->teachername = $teachername;
                    }
                }
            }

            $didnotmeet_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','<','75')->whereIn('gender',['MALE','Male','male'])->count();
            $didnotmeet_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','<','75')->whereIn('gender',['FEMALE','Female','female'])->count();
            $didnotmeet_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','<','75')->count();

            $fairlysatisfactory_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->whereIn('gender',['MALE','Male','male'])->count();
            $fairlysatisfactory_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->whereIn('gender',['FEMALE','Female','female'])->count();
            $fairlysatisfactory_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->count();

            $satisfactory_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->whereIn('gender',['MALE','Male','male'])->count();
            $satisfactory_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->whereIn('gender',['FEMALE','Female','female'])->count();
            $satisfactory_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->count();

            $verysatisfactory_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->whereIn('gender',['MALE','Male','male'])->count();
            $verysatisfactory_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->whereIn('gender',['FEMALE','Female','female'])->count();
            $verysatisfactory_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->count();

            $outsatanding_m = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','90')->whereIn('gender',['MALE','Male','male'])->count();
            $outsatanding_f = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','90')->whereIn('gender',['FEMALE','Female','female'])->count();
            $outsatanding_t = collect($gradelevel->enrollees)->where('genave','>',0)->where('genave','>=','90')->count();
            
            return view('adminITPortal.pages.academic.filterresults.statistics_sf5')
                ->with('didnotmeet_m', $didnotmeet_m)
                ->with('didnotmeet_f', $didnotmeet_f)
                ->with('didnotmeet_t', $didnotmeet_t)
                ->with('fairlysatisfactory_m', $fairlysatisfactory_m)
                ->with('fairlysatisfactory_f', $fairlysatisfactory_f)
                ->with('fairlysatisfactory_t', $fairlysatisfactory_t)
                ->with('satisfactory_m', $satisfactory_m)
                ->with('satisfactory_f', $satisfactory_f)
                ->with('satisfactory_t', $satisfactory_t)
                ->with('verysatisfactory_m', $verysatisfactory_m)
                ->with('verysatisfactory_f', $verysatisfactory_f)
                ->with('verysatisfactory_t', $verysatisfactory_t)
                ->with('outsatanding_m', $outsatanding_m)
                ->with('outsatanding_f', $outsatanding_f)
                ->with('outsatanding_t', $outsatanding_t)
                ->with('results', $results)
                ->with('levelid', $levelid)
                ->with('gradelevels', $gradelevels);
        }else{
            return view('adminITPortal.pages.academic.students')
                ->with('gradelevels', $gradelevels);
        }

    }


    public function financeindex(Request $request)
    {
        if($request->has('syid'))
        {
            $syid = $request->get('syid');
        }else{
            $syid = DB::table('sy')->where('isactive','1')->first()->id;
        }
        if($request->has('syid'))
        {
            $semid = $request->get('semid');
        }else{
            $semid = DB::table('semester')->where('isactive','1')->first()->id;
        }
        $result = CarbonPeriod::create(DB::table('sy')->where('id',$syid)->first()->sdate, '1 month', DB::table('sy')->where('id',$syid)->first()->edate);
        $months = array();
        // foreach ($result as $dt) {
        //     array_push($months, (object)array(
        //         'month'   => date('Y-m', strtotime($dt)),
        //         'monthint'   => (int) date('m', strtotime($dt)),
        //         'monthdesc'   => str_replace('"', "'", date('M', strtotime($dt))),
        //         'monthname'   => str_replace('"', "'", date('F', strtotime($dt))),
        //         'year'   => str_replace('"', "'", date('Y', strtotime($dt)))
        //     ));
            
        // }
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()->startOfMonth()->subMonth($i);
            $year = Carbon::today()->startOfMonth()->subMonth($i)->format('Y');
            array_push($months, (object) array(
                'month' => $month->shortMonthName,
                'monthint'   => (int) date('m', strtotime($month)),
                'monthdesc'   => str_replace('"', "'", date('M', strtotime($month))),
                'monthname'   => str_replace('"', "'", date('F', strtotime($month))),
                'year' => $year
            ));
        }
        
        $students = collect();

        $enrolledstud_1 = DB::table('enrolledstud')
            ->select('studid')
            ->where('syid', $syid)
            ->where('studstatus','!=',0)
            ->where('deleted', 0)
            ->get();;

        $enrolledstud_2 = DB::table('sh_enrolledstud')
            ->select('studid')
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('studstatus','!=',0)
            ->where('deleted', 0)
            ->get();

        $enrolledstud_3 = DB::table('college_enrolledstud')
            ->select('studid')
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('studstatus','!=',0)
            ->where('deleted', 0)
            ->get();

        $students = $students->merge($enrolledstud_1);
        $students = $students->merge($enrolledstud_2);
        $students = $students->merge($enrolledstud_3);
        $students = $students->unique()->values()->all();


        // return $months;
        $receivables = DB::table('studledger')
            // ->select(DB::raw('SUM(amount) AS assessed, SUM(payment) AS payment, SUM(amount) - SUM(payment) AS receivables'),'createddatetime')
            // ->select(DB::raw('SELECT sydesc, SUM(amount) AS assessed, SUM(payment) AS payment, SUM(amount) - SUM(payment) AS receivables'))
            // ->where('amount','0')
            ->where('deleted','0')
            ->where('semid',$semid)
            ->where('syid',$syid)
            ->whereIn('studid',collect($students)->pluck('studid'))
            ->where('void','0')
            ->get();

            // return $receivables;
        if(count($receivables)>0)
        {
            foreach($receivables as $receivable)
            {
                $receivable->receivables = ($receivable->amount - $receivable->payment);
                $receivable->monthint = (int) date('m', strtotime($receivable->createddatetime));
            }
        }
        $income = DB::table('chrngtrans')
            ->select('transdate',DB::raw('SUM(amountpaid) as totalamountpaid'))
            ->where('cancelled','0')
            ->where('syid', $syid)
            ->where('semid', $semid)
            // ->groupBy(DB::raw('YEAR(transdate), MONTH(transdate), DAY(transdate)'))
            ->orderByDesc('transdate')
            ->groupBy(DB::raw('YEAR(transdate), MONTH(transdate), DAY(transdate)'))
            ->get();



        if(count($income)>0)
        {
            foreach($income as $eachincome)
            {
                $eachincome->monthint = (int) date('m', strtotime($eachincome->transdate));
            }
        }
        // return $income;
        foreach($months as $month)
        {
            $month->totalreceivables = round(collect($receivables)->where('monthint', $month->monthint)->sum('receivables'),2) > 0 ?  round(collect($receivables)->where('monthint', $month->monthint)->sum('receivables'),2) : 0;
            $month->totalincome = round(collect($income)->where('monthint', $month->monthint)->sum('totalamountpaid'),2);
        }
        // return $months;
        if(!$request->has('action'))
        {
            return view('adminITPortal.pages.finance.index')
                ->with('months', $months);
        }else{
            
            if(!$request->has('export'))
            {
                return view('adminITPortal.pages.finance.results')
                    ->with('sydesc', DB::table('sy')->where('id',$syid)->first()->sydesc)
                    ->with('months', $months);
            }else{
                if($request->get('report') == 'receivables')
                {
                    $syinfo = DB::table('sy')->where('id', $syid)->first();
                    $seminfo = DB::table('semester')->where('id', $semid)->first();
                    $pdf = PDF::loadview('adminITPortal.pages.finance.pdf_receivables',compact('months','syinfo','seminfo'));
                    return $pdf->stream('Receivables.pdf');
                }elseif($request->get('report') == 'income')
                {
                    $syinfo = DB::table('sy')->where('id', $syid)->first();
                    $seminfo = DB::table('semester')->where('id', $semid)->first();
                    $pdf = PDF::loadview('adminITPortal.pages.finance.pdf_income',compact('months','syinfo','seminfo'));
                    return $pdf->stream('Income.pdf');

                }
            }
        }

    }
	
    public function hrindex(Request $request)
    {
        $employees = DB::table('teacher')
          ->select('teacher.id','teacher.userid','lastname','firstname','middlename','suffix','tid',DB::raw('UPPER(`gender`) as gender'),'utype as designation','isactive','hr_empstatus.description as employmentstatus','teacher.datehired','employee_personalinfo.dob','employee_personalinfo.address','employee_personalinfo.email','teacher.picurl')
          ->leftJoin('employee_personalinfo','teacher.id','=', 'employee_personalinfo.employeeid')
          ->leftJoin('hr_empstatus','teacher.employmentstatus','=', 'hr_empstatus.id')
          ->join('usertype', 'teacher.usertypeid','=','usertype.id')
          ->where('teacher.deleted','0')
          // ->where('employee_personalinfo.deleted','0')
          // ->where('teacher.isactive','1')
          ->orderBy('lastname','asc')
          ->get();
      
        if(count($employees)>0)
        {
          foreach($employees as $employee)
          {
            $countrecords = 0;
            $checktap = DB::table('taphistory')
              ->where('studid', $employee->id)
              ->where('tdate', date('Y-m-d'))
              ->where('deleted','0')
              ->count();
            $countrecords+=$checktap;
            $checkatt = DB::table('hr_attendance')
              ->where('studid', $employee->id)
              ->where('tdate', date('Y-m-d'))
              ->where('deleted','0')
              ->count();
            $countrecords+=$checkatt;
            if($countrecords == 0)
            {
              $employee->attendancestatus = 0;
            }else{
              $employee->attendancestatus = 1;
            }
            if($employee->datehired == null)
            {
                $employee->worked = "";
            }else{
                $datetime1 = new DateTime($employee->datehired);
                $datetime2 = new DateTime(date('Y-m-d'));
                $interval = $datetime1->diff($datetime2);
                $employee->worked = $interval->format('%y yrs %m mths');
            }

            $educationinfo = DB::table('employee_educationinfo')
                ->where('employeeid', $employee->id)
                ->where('deleted','0')
                ->get();
            $employee->educationinfo = $educationinfo;

            $otherportals = DB::table('faspriv')
                ->select('faspriv.*','usertype.utype')
                ->join('usertype', 'faspriv.usertype','=','usertype.id')
                ->where('faspriv.userid', $employee->userid)
                ->where('faspriv.deleted','0')
                ->get();

            $employee->otherportals = $otherportals;
          }
        }  
        // return $employees;

        if(!$request->has('action'))
        {
            return view('adminITPortal.pages.hr.index')
                ->with('employees',$employees);
        }else{
            $pdf = PDF::loadview('adminITPortal/pages/hr/printables/pdf_employees',compact('employees'))->setPaper('8.5x11');
            return $pdf->stream('Employees Msaterlist.pdf');
        }
    }
}
