<?php

namespace App\Http\Controllers\AdministratorControllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Principal\SPP_SchoolYear;
use App\Models\Principal\SPP_Teacher;
use App\Models\Principal\SPP_AcademicProg;
use App\Models\Principal\SPP_Rooms;
use App\Models\Principal\SPP_Calendar;
use App\Models\Principal\SPP_Privelege;

use App\Models\Principal\SPP_EnrolledStudent;

use App\Models\Principal\SPP_PermissionRequest;
use App\Models\Principal\SPP_Announcement;
use App\Models\Principal\SPP_Building;

use Session;
use Crypt;
use File;
use App\FilePath;
use Image;
use Redirect;
use Artisan;
use Config;
use Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\SuperAdmin\PasswordGenerator;

class AdministratorController extends \App\Http\Controllers\Controller
{
    
    public function manageaccounts(){

        // return  Hash::make('iUQKIBBg');
        
        $activesy = SPP_SchoolYear::getActiveSchoolyear();

        if(!isset($activesy )){

            return view('generalPages.adminErrorBlade')
                    ->with('message','No active school year.')
                    ->with('link','manageschoolyear');

        }

        $teachersinfo = SPP_Teacher::filterTeacherFaculty(null,6,null,null,null);

        $utype = DB::table('usertype')
                        ->where('deleted',0)
                        ->whereNotIn('id',[7,9,17,12,6])
                        ->get();
        
        $nationality = DB::table('nationality')
                        ->where('deleted',0)
                        ->get();
    
        $civilstatus = DB::table('civilstatus')
                        ->where('deleted',0)
                        ->get();
                    
        return view('adminPortal.pages.account')
                ->with('usertype',$utype)
                ->with('nationality', $nationality)
                ->with('civilstatus', $civilstatus)
                ->with('data',$teachersinfo);

    }

    public function updateAccountInfo(Request $request){

  

        return SPP_Teacher::updateFAS(
            $request->get('ui'),
            $request->get('fn'),
            $request->get('mn'),
            $request->get('ln'),
            $request->get('lcn'),
            $request->get('ut'),
            $request->get('q')
        );

    }


    public static function admininsertfaculty(Request $request){

      

        return SPP_Teacher::createFAS(
            null,
            $request->get('fn'),
            $request->get('mn'),
            $request->get('ln'),
            $request->get('lcn'),
            $request->get('ut'),
            $request->get('q')
        );

    }

    public static function adminRemoveFAS(Request $request){

        return SPP_Teacher::removeFAS(
                        $request->get('rid'),
                        $request->get('ps')
                    );

    }


    //----------------------------------------------- School Year --------------------------------------------

    public static function manageschoolyear(){


        $latestRequest = DB::table('perreq')
                    ->where('perreqtype','1')
                    ->where('status','0')
                    ->orderBy('perreq.createddatetime','desc')
                    ->select('reqid')
                    ->first();

        return view('adminPortal.pages.schoolyear')
                ->with('schoolyear',SPP_SchoolYear::loadAllSchoolYear())
                ->with('latestRequest',$latestRequest);

    }

    public static function storeschoolyear(Request $request){

        return SPP_SchoolYear::storeschoolyear($request);

    }

    public static function adminupdatesy(Request $request){

        return SPP_SchoolYear::adminupdatesy(
            $request->get('si'),
            $request->get('sdate'),
            $request->get('edate')
        );


    }

    public static function setschoolyearactive($id){



        $juniorsispromoted = false;
        $gradesschoolispromoted = false;
        $seniorsispromoted = false;
        $isNextSchoolYear = false;

        $countUnpromotedElem = DB::table('enrolledstud')
                                        ->join('sy',function($join) use($id){
                                            $join->on('sy.id','=','enrolledstud.syid');
                                            $join->where('sy.isactive','1');
                                        })
                                        ->join('gradelevel',function($join){
                                            $join->on('enrolledstud.levelid','=','gradelevel.id');
                                            $join->where('gradelevel.acadprogid',3);
                                        })
                                        ->whereNotIn('studstatus',[0,3,5])
                                        ->where('promotionstatus','0')
                                        ->count();

        $countUnpromotedHS = DB::table('enrolledstud')
                ->join('sy',function($join){
                    $join->on('sy.id','=','enrolledstud.syid');
                    $join->where('sy.isactive','1');
                })
                ->join('gradelevel',function($join){
                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                    $join->where('gradelevel.acadprogid',2);
                })
                ->whereNotIn('studstatus',[0,3,5])
                ->where('promotionstatus','0')
                ->count();

        $countNotPromotedStudentSH = DB::table('sh_enrolledstud')
                ->join('sy',function($join){
                    $join->on('sy.id','=','sh_enrolledstud.syid');
                    $join->where('sy.isactive','1');
                })
                ->whereNotIn('studstatus',[0,3,5])
                ->where('promotionstatus','0')
                ->count();


        if($countUnpromotedHS == 0){
            $juniorsispromoted = true;
        }

        if($countUnpromotedElem == 0){
            $gradesschoolispromoted = true;
        }

        if($countNotPromotedStudentSH == 0){

            $seniorsispromoted = true;
        }

        if( $juniorsispromoted && $gradesschoolispromoted && $seniorsispromoted){

            return SPP_SchoolYear::setschoolyearactive(Crypt::decrypt($id));

        }

    }
    public static function setsemaractive($id){

        DB::table('semester')->update(['isactive'=>'0']);

        DB::table('semester')->where('id',Crypt::decrypt($id))->update(['isactive'=>'1']);

        return back();
    }


    

    public function viewschoolyearinformation($id){

        $juniorsispromoted = false;
        $gradesschoolispromoted = false;
        $seniorsispromoted = false;
        $isNextSchoolYear = false;
        $isActiveShoolYear = false;
        $isFirstSem = false;

        if(Crypt::decrypt($id) > DB::table('sy')->where('isactive','1')->first()->id){
            $isNextSchoolYear = true;
        }


        $countUnpromotedElem = DB::table('enrolledstud')
                                        ->join('sy',function($join) use($id){
                                            $join->on('sy.id','=','enrolledstud.syid');
                                            $join->where('sy.isactive','1');
                                        })
                                        ->join('gradelevel',function($join){
                                            $join->on('enrolledstud.levelid','=','gradelevel.id');
                                            $join->where('gradelevel.acadprogid',3);
                                        })
                                        ->whereNotIn('studstatus',[0,3,5])
                                        ->where('promotionstatus','0')
                                        ->count();

        $countUnpromotedHS = DB::table('enrolledstud')
            ->join('sy',function($join){
                $join->on('sy.id','=','enrolledstud.syid');
                $join->where('sy.isactive','1');
            })
            ->join('gradelevel',function($join){
                $join->on('enrolledstud.levelid','=','gradelevel.id');
                $join->where('gradelevel.acadprogid',2);
            })
            ->whereNotIn('studstatus',[0,3,5])
            ->where('promotionstatus','0')
            ->count();

        $countNotPromotedStudentSH = DB::table('sh_enrolledstud')
                ->join('sy',function($join){
                    $join->on('sy.id','=','sh_enrolledstud.syid');
                    $join->where('sy.isactive','1');
                })
                ->whereNotIn('studstatus',[0,3,5])
                ->where('promotionstatus','0')
                ->count();

      
                            
        if($countUnpromotedHS == 0){
            $juniorsispromoted = true;
        }

        if($countUnpromotedElem == 0){
            $gradesschoolispromoted = true;
        }

        if($countNotPromotedStudentSH == 0){

            $seniorsispromoted = true;
        }
            


        $schoolYearInfo = DB::table('sy')->where('id',Crypt::decrypt($id))->get();

        $active_semester = DB::table('semester')->where('isactive','1')->first();



        $firstSy = SPP_SchoolYear::getSchoolYear(null,null,null,null,'first');

        if($schoolYearInfo[0]->isactive == 1){
            
            $isActiveShoolYear = true;

        }

        if($active_semester->id == 1){
            
            $isFirstSem = true;

        }

        $isFirstYear = false;

        if($firstSy[0]->data[0]->id == Crypt::decrypt($id)){

            $isFirstYear = true;

        }

        $lastestRequest = DB::table('perreq')
                            ->where('perreqtype','1')
                            ->orderBy('perreq.createddatetime','desc')
                            ->join('sy','perreq.reqid','=','sy.id')
                            ->select('perreq.*','sy.sydesc')
                            ->first();


        $lastestSemRequest = DB::table('perreq')
                            ->where('perreqtype','2')
                            ->orderBy('perreq.createddatetime','desc')
                            ->join('semester','perreq.reqid','=','semester.id')
                            ->select('perreq.*','semester.semester')
                            ->first();


        $no_sy_request = false;
        $no_sem_request = false;

        if(!isset($lastestRequest)){

            $no_sy_request = true;

        }

        if(!isset($lastestSemRequest)){

            $no_sem_request = true;
        }

        try{

            $semrequestdetails = DB::table('perreqdetail')
                                    ->where('perreqdetail.headerid',$lastestSemRequest->id)
                                    ->join('users','perreqdetail.approvedby','=','users.id')
                                    ->select(
                                        'users.name',
                                        'perreqdetail.response'
                                    )->get();

        }
        catch (\Exception $e) {
            $semrequestdetails = null;
        }
       
                    
        $activeSchoolyear = DB::table('semester')->get();
       
        $requestDetails = null;

        if($schoolYearInfo[0]->isactive == 1){

            $reqid = DB::table('perreq')
                        ->where('perreq.reqid',$schoolYearInfo[0]->id)
                        ->orderBy('perreq.createddatetime','desc')
                        ->select('perreq.id')
                        ->first();

            try{

                $requestDetails = DB::table('perreq')
                                        ->where('perreq.id',$reqid->id)
                                        ->join('perreqdetail',function($join){
                                            $join->on('perreq.id','=','perreqdetail.headerid');
                                        })
                                        ->where('perreqtype','1')
                                        ->join('users','perreqdetail.approvedby','=','users.id')
                                        ->leftJoin('users as senderinfo','perreq.createdby','=','senderinfo.id')
                                        ->join('sy','perreq.reqid','=','sy.id')
                                        ->select(
                                            'status',
                                            'users.name',
                                            'senderinfo.name as sendername',
                                            'perreqtype',
                                            'perreq.id',
                                            'perreqdetail.response',
                                            'sy.sydesc'
                                        )->get();

            }
            catch (\Exception $e) {

                $requestDetails = null;
                
            }

        }

        if(!$isFirstYear){

            try{

                $latestRequestDetails = DB::table('perreq')
                    ->where('perreq.id',$lastestRequest->id)
                    ->join('perreqdetail',function($join){
                        $join->on('perreq.id','=','perreqdetail.headerid');
                    })
                    ->where('perreqtype','1')
                    ->join('users','perreqdetail.approvedby','=','users.id')
                    ->leftJoin('users as senderinfo','perreq.createdby','=','senderinfo.id')
                    ->join('sy','perreq.reqid','=','sy.id')
                    ->select(
                        'status',
                        'users.name',
                        'senderinfo.name as sendername',
                        'perreqtype',
                        'perreq.id',
                        'perreqdetail.response',
                        'sy.sydesc'
                    )->get();
            }
            catch (\Exception $e) {

                $latestRequestDetails = null;
                
            }
        }

        else{

            $latestRequestDetails = null;

        }



        

        return view('adminPortal.pages.schoolyear.viewschoolyear')
                ->with('syinfo',$schoolYearInfo)
                ->with('isFirstYear',$isFirstYear)
                ->with('active_semester',$active_semester)
                ->with('semrequestdetails',$semrequestdetails)
                ->with('lastestSemRequest',$lastestSemRequest)
                ->with('no_sy_request',$no_sy_request)
                ->with('no_sem_request',$no_sem_request)
                ->with('latestRequest',$lastestRequest)
                ->with('requestDetails',$requestDetails)
                ->with('latestRequestDetails',$latestRequestDetails)
                ->with('juniorsispromoted',$juniorsispromoted)
                ->with('gradesschoolispromoted',$gradesschoolispromoted)
                ->with('seniorsispromoted',$seniorsispromoted)
                ->with('isActiveShoolYear',$isActiveShoolYear)
                ->with('isFirstSem',$isFirstSem)
                ->with('isNextSchoolYear',$isNextSchoolYear);


                // $juniorsispromoted = false;
                // $gradesschoolispromoted = false;
                // $seniorsispromoted = false;
                // $isActiveShoolYear



                

    }

    public function cancelrequest($id){

        DB::table('perreq')->where('id',Crypt::decrypt($id))->update(['status'=>3]);

        return back();

    }


    //----------------------------------------------- School Year --------------------------------------------

    //----------------------------------------------- Rooms --------------------------------------------

    public static function admingetrooms(){

        $activesy = SPP_SchoolYear::getActiveSchoolyear();

        if(!isset($activesy )){

            return view('generalPages.adminErrorBlade')
                    ->with('message','No active school year.')
                    ->with('link','manageschoolyear');

        }
  
        return view('adminPortal.pages.rooms')->with('data',SPP_Rooms::getRooms(null,10,null,null));

    }
	
	public static function rooms(){

        $rooms = DB::table('rooms')
                    ->join('building',function($join){
                        $join->on('rooms.buildingid','=','building.id');
                        $join->where('building.deleted',0);
                    })  
                    ->where('rooms.deleted',0)
                    ->orderBy('roomname')
                    ->select(
                        'rooms.id',
                        'rooms.roomname',
                        'rooms.capacity',
                        'buildingid',
                        'description'
                    )
                    ->get();

                
        // return collect($rooms)->sortBy('roomname')->values();

        return $rooms;

    }

     public static function buildings(){

        $buildings = DB::table('building')
                ->where('building.deleted',0)
                ->orderBy('description')
                ->select(
                    'building.id',
                    'building.description',
                    'building.capacity',
                    'building.description as text'
                )
                ->get();

        return $buildings;

     }

    
    public static function create_room(Request $request){

        $roomname = $request->get('roomname');
        $capacity = $request->get('capacity');
        $building = $request->get('building');

        try{

            $check = DB::table('rooms')
                        ->where('roomname',$roomname)
                        ->where('deleted',0)
                        ->count();

            if($check == 0){
                DB::table('rooms')
                    ->insert([
                        'deleted'=>0,
                        'roomname'=>$roomname,
                        'buildingid'=>$building,
                        'capacity'=>$capacity,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return  array((object)[
                    'status'=>1,
                    'message'=>'Room Created'
                ]);
            }else{
                return  array((object)[
                    'status'=>0,
                    'message'=>'Already Exist'
                ]);
            }

        }catch(\Exception $e){
            return $e;
            return  array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);

        }

    }

    public static function udpate_room(Request $request){

        $roomname = $request->get('roomname');
        $capacity = $request->get('capacity');
        $building = $request->get('building');
        $id = $request->get('id');

        try{

            $check = DB::table('rooms')
                        ->where('roomname',$roomname)
                        ->where('id','!=',$id)
                        ->where('deleted',0)
                        ->count();

            if($check == 0){

                DB::table('rooms')
                    ->where('id',$id)
                    ->take(1)
                    ->update([
                        'roomname'=>$roomname,
                        'buildingid'=>$building,
                        'capacity'=>$capacity,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return  array((object)[
                    'status'=>1,
                    'message'=>'Room Updated'
                ]);
            }else{
                return  array((object)[
                    'status'=>0,
                    'message'=>'Already Exist'
                ]);
            }

        }catch(\Exception $e){
            return  array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);

        }

    }

    public static function delete_room(Request $request){

        $id = $request->get('id');

        try{

            $check_usage = DB::table('classscheddetail')
                            ->where('roomid',$id)
                            ->where('deleted',0)
                            ->count();

            if($check_usage > 0){
                return  array((object)[
                    'status'=>2,
                    'message'=>'Room is already used!'
                ]);
            }

            $check_usage = DB::table('sh_classscheddetail')
                        ->where('roomid',$id)
                        ->where('deleted',0)
                        ->count();

            if($check_usage > 0){
                return  array((object)[
                    'status'=>2,
                    'message'=>'Room is already used!'
                ]);
            }

            $check_usage = DB::table('scheddetail')
                        ->where('roomid',$id)
                        ->where('deleted',0)
                        ->count();

            if($check_usage > 0){
                return  array((object)[
                    'status'=>2,
                    'message'=>'Room is already used!'
                ]);
            }

            DB::table('rooms')
                ->where('id',$id)
                ->update([
                    'deleted'=>1,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
            
            return  array((object)[
                'status'=>1,
                'message'=>'Room Deleted!'
            ]);
    
        }catch(\Exception $e){
            return $e;
            return  array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);

        }

    }

    public static function admingetroom(Request $request){
  
        return SPP_Rooms::getRooms(null,10,$request->get('id'),null);

    }

    public static function admincreateroom(Request $request){

       return SPP_Rooms::createRoom($request->get('roomName'),$request->get('roomCapacity'),$request->get('building'));

    }

    public static function adminupdateroom(Request $request){

        return SPP_Rooms::updateroom($request->get('si'),$request->get('roomName'),$request->get('roomCapacity'),$request->get('building'));

    }

    public static function adminremoveroom($id){

        $roomInUse = SPP_Rooms::getRoomUsage($id);

        if(!$roomInUse){

            DB::table('rooms')
                    ->where('id',$id)
                    ->update([
                        'deleted'=>'1',
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');

        }
        else{

            toast('Room is already used','error')->autoClose(2000)->toToast($position = 'top-right');

        }

      
        return back();
        

    }

    public function adminsearchroom(Request $request){

        $rooms = SPP_Rooms::getRooms($request->get('pagenum'),10,$request->get('id'),$request->get('data'));

        return view('search.admin.room')->with('data',$rooms);

        
    }

   

    //----------------------------------------------- Rooms --------------------------------------------

    //----------------------------------------------- Faculty and Staff --------------------------------------------

    public function getprincipalacadprog(Request $request){


        return SPP_Teacher::getTeacherAcadProgIfPrincipal($request->get('d'));

    }

    public function adminGetTeacherAcadProg(Request $request){


        return SPP_Teacher::getTeacherAcadProgIfTeacher($request->get('d'));

    }

    public static function searchfacultystaff(Request $request){


        $teachers =  SPP_Teacher::filterTeacherFaculty($request->get('pagenum'),6,null,null,$request->get('data'));

        return view('search.admin.facultystaff')->with('data',$teachers);
        
    }

    public static function adminggetfacultyinfo(Request $request){

        // return App\Models\Principal\SPP_AcademicProg::getAllAcadProg();

        return SPP_Teacher::filterTeacherFaculty(null,1,$request->get('d'),null,null);

    }
    
    public static function adminloadholidays(){

        $activesy = SPP_SchoolYear::getActiveSchoolyear();

        if(!isset($activesy )){

            return view('generalPages.adminErrorBlade')
                    ->with('message','No active school year.')
                    ->with('link','manageschoolyear');

        }


        // return SPP_Calendar::getHoliday(null,10);

        return view('adminPortal.pages.calendar.holidays')->with('data',SPP_Calendar::getHoliday(null,10));

    }

    public static function admininsertholiday(Request $request){


        return SPP_Calendar::insertHoliday(
            $request->get('day'),
            $request->get('des'),
            $request->get('type'),
            $request->get('noclass'),
            $request->get('annual')
        );

    }

    public static function adminsearchholiday(Request $request){

        return view('search.admin.holiday')->with('data',SPP_Calendar::getHoliday($request->get('pagenum'),10,null,$request->get('data')));
    }

    public static function admingetholiday(Request $request){
        
        return SPP_Calendar::getHoliday(null,null,$request->get('id'));

    }

    public static function adminupdateholiday(Request $request){

        return SPP_Calendar::updateholiday(
            $request->get('day'),
            $request->get('des'),
            $request->get('type'),
            $request->get('noclass'),
            $request->get('annual'),
            $request->get('si')
        );

    }

    public static function adminremoveholiday($id){

        return SPP_Calendar::removeHoliday($id);

    }

    public static function admingeteventtype(Request $request){

        return SPP_Calendar::getEventType($request->get('id'));

    }

    public function viewFacutlyInfo($id){

        $facultyInfo = SPP_Teacher::filterTeacherFaculty(null,1,$id,null,null)[0]->data;
		


        if(count($facultyInfo) == 0){

            $facultyInfo = DB::table('teacher')
                            ->where('id',$id)
                            ->get();

            $utype = DB::table('usertype')
                            ->where('deleted',0)
                            ->whereNotIn('id',[7,9,17,12,6])
                            ->get();   

            return view('adminPortal.pages.facultyandstaff.view')
                    ->with('facultyInfo', $facultyInfo)
                    ->with('usertype',$utype)
                    ->with('acadProg', array())
                    ->with('privelege', array());
        }

     

        $acadProg = SPP_Teacher::getTeacherAcadProgIfTeacher($id);

        $principalAcadProg = DB::table('academicprogram')->where('principalid',$facultyInfo[0]->id)->get();

        $privelege = DB::table('faspriv')
                        ->join('usertype','faspriv.usertype','=','usertype.id')
                        ->select('faspriv.*','usertype.utype')
                        ->where('userid', $facultyInfo[0]->userid)
                        ->where('faspriv.deleted','0')
                        ->get();
        
        $utype = DB::table('usertype')
                        ->whereNotIn('id',['7','9'])
                        ->where('constant','1')
                        ->get();  

        return view('adminPortal.pages.facultyandstaff.view')
                    ->with('facultyInfo', $facultyInfo)
                    ->with('acadProg', $acadProg)
                    ->with('privelege', $privelege)
                    ->with('principalAcadProg',$principalAcadProg)
                    ->with('usertype',$utype);

       
    }


    public static function adminaddprivelege(Request $request){

        $privstat = SPP_Privelege::storePriveleg(
            $request->get('sid'),
            $request->get('privut'),
            $request->get('priv')
        );

        if( gettype($privstat) == 'object'){

            return $privstat;
        }
 
        $activeSy = DB::table('sy')->where('isactive','1')->first();

        $tid = Crypt::decrypt($request->get('sid'));

        $teacher = DB::table('teacher')->where('userid',$tid)->first();

        DB::table('teacheracadprog')
                ->where('teacherid',$teacher->id)
                ->where('syid',$activeSy->id)
                ->update([
                    'deleted'=>'1',
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

        if($request->get('q') != null){
       
            foreach($request->get('q')  as $item){
           
                $checkAcadProg = DB::table('teacheracadprog')
                        ->join('sy',function($join){
                            $join->on('teacheracadprog.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->where('teacherid',$teacher->id)
                        ->where('acadprogid',$item)
                        ->count();

                if($checkAcadProg==1){

                    $checkAcadProg = DB::table('teacheracadprog')
                            ->join('sy',function($join){
                                $join->on('teacheracadprog.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->where('teacherid',$teacher->id)
                            ->where('acadprogid',$item)
                            ->update([
                                'teacheracadprog.deleted'=>'0',
                                'teacheracadprog.updatedby'=>auth()->user()->id,
                                'teacheracadprog.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                }

                if($checkAcadProg==0){

                    DB::table('teacheracadprog')
                        ->insert([
                            'teacherid'=> $teacher->id,
                            'syid'=>$activeSy->id,
                            'acadprogid'=>$item,
                            'deleted'=>'0',
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                }


            }

            return back();
           
        }   
        else{

            $data = [
                'q'=>$request->get('q')
            ];
    
           
            if($request->get('privut') == 1 || $request->get('privut') == 3){

                $validator = Validator::make($data,['q'=>'required']);

                toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

                return back()->withErrors($validator)->withInput()->with('invalidpriv',true);


            }
            else{


                toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
                return back();
            }
           
           
        }

       
        

    }

    public static function setactive($user, $type){

       DB::table('teacher')->where('id',$user)->update(['isactive'=>$type]);

    }

    // public static function adminudpatepriv(Request $request){

    //     foreach($request->get('privid') as $key=>$item){

    //         SPP_Privelege::updatepriv(
    //             $item,
    //             $request->get('selectpriv')[$key]
    //         );
        
    //     }

    //     toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
    //     return back();

    // }


    public function updatepriv($priv, $utype){

        $activeSy = DB::table('sy')->where('isactive','1')->first();

        $priv = Crypt::decrypt($priv);

        $fasprivInfo = explode(' ',$utype);

        DB::table('faspriv')
                    ->where('userid',$fasprivInfo[1])
                    ->where('usertype',$fasprivInfo[0])
                    ->update([
                        'privelege'=>$priv
                    ]);
                    
        $teacher = DB::table('teacher')->where('userid',$fasprivInfo[1])->first();

        if($fasprivInfo[0] == 1 && $priv == 0){

            DB::table('teacheracadprog')
                ->where('teacherid',$teacher->id)
                ->where('syid',$activeSy->id)
                ->update([
                    'deleted'=>'1',
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

        }
        else{

            DB::table('teacheracadprog')
                        ->where('teacherid',$teacher->id)
                        ->where('syid',$activeSy->id)
                        ->update([
                            'deleted'=>'0',
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);

            
        }
                    

    }

    

    //----------------------------------------------- Faculty and Staff --------------------------------------------

   

    public static function insertinfo(Request $request){

        //DB::table('schoolinfo')->truncate();

        DB::table('schoolinfo')->update([
            'schoolid'=>strtoupper($request->get('schoolid')),
            'schoolname'=>strtoupper($request->get('schoolname')),
            'divisiontext'=>strtoupper($request->get('division')),
            'regiontext'=>strtoupper($request->get('region')),
            'districttext'=>strtoupper($request->get('district')),
            'address'=>strtoupper($request->get('address')),
            'abbreviation'=>strtoupper($request->get('abbreviation')),
            'tagline'=>strtoupper($request->get('schooltagline')),
            'updatedby'=>auth()->user()->id,
            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);

       
        
        toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
        return back();
 
     }


     public static function admingetcity(Request $request){

        return DB::table('refcitymun')->where('regDesc',$request->get('data'))->get();

    }


    public function requestsempermission($id){

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $principal = DB::table('teacher')
                    ->whereIn('usertypeid',['2'])
                    ->join('academicprogram',function($join){
                        $join->on('teacher.id','=','academicprogram.principalid');
                        $join->where('academicprogram.id',5);
                    })
                    ->where('deleted','0')
                    ->where('isactive','1')
                    ->select('userid')
                    ->get();

        $syrequest = DB::table('perreq')
                        ->where('perreqtype','2')
                        ->where('deleted','0')
                        ->where('status','0')
                        ->count();

        if($syrequest == 0){

            $reqid = DB::table('perreq')->insertGetId([
                'perreqtype'=>2,
                'status'=>0,
                'reqid'=>Crypt::decrypt($id),
                'createdby'=>auth()->user()->id,
                'createddatetime'=>  $date
            ]);

            foreach( $principal as $item){

                DB::table('perreqdetail')->insertGetId([
                    'headerid'=>$reqid,
                    'approvedby'=>$item->userid,
                    'response'=>'0'
                ]);
               
            }

        }

        return back();
    }

    public function requestpermission($id){

    

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $users = DB::table('teacher')
                ->whereIn('usertypeid',['2'])
                ->where('deleted','0')
                ->where('isactive','1')
                ->select('userid')
                ->get();

        $syrequest = DB::table('perreq')
                        ->where('perreqtype','1')
                        ->where('deleted','0')
                        ->where('status','0')
                        ->count();

        if($syrequest == 0){

            $reqid = DB::table('perreq')->insertGetId([
                'perreqtype'=>1,
                'status'=>0,
                'reqid'=>Crypt::decrypt($id),
                'createdby'=>auth()->user()->id,
                'createddatetime'=>  $date
            ]);

            foreach( $users as $item){

                DB::table('perreqdetail')->insertGetId([
                    'headerid'=>$reqid,
                    'approvedby'=>$item->userid,
                    'response'=>'0',
                ]);
               
            }

        }

        return back();

    }

    public function viewschoolinfo()
    {

        $schoolInfo = DB::table('schoolinfo')->first();

        return view('adminPortal.pages.schoolinformation')->with('schoolInfo',$schoolInfo); 
    }

    public function updateschoolinfo(Request $request)
    {

        // return $request->all();

      

        DB::table('schoolinfo')->update([
            'schoolid'=>strtoupper($request->get('schoolid')),
            'schoolname'=>strtoupper($request->get('schoolname')),
            'divisiontext'=>strtoupper($request->get('division')),
            'regiontext'=>strtoupper($request->get('region')),
            'districttext'=>strtoupper($request->get('district')),
            'address'=>strtoupper($request->get('address')),
            'abbreviation'=>strtoupper($request->get('abbreviation')),
            'tagline'=>strtoupper($request->get('schooltagline')),
            'updatedby'=>auth()->user()->id,
            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);

        if($request->has('schoollogo')){

            $message = [
                'schoollogo.mimes'=>'School Logo must be a file of type: jpeg, png.',
            ];

            $validator = Validator::make($request->all(), [
                'schoollogo' => ['mimes:jpeg,png']
            
            ], $message);

            if ($validator->fails()) {

                toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
    
                return back()->withErrors($validator)->withInput();
            }
            else{

                self::adminuploadlogo($request->file('schoollogo'), $request);

            }
           
        }

        return back();

    }
    public function roomsinfo($id)
    {
    
        $roomsinfodetails = DB::table('rooms')
                                ->leftJoin('building','rooms.buildingid','=','building.id')
                                ->where('rooms.id', $id)
                                ->where('rooms.deleted','0')
                                ->select('rooms.*','building.description')
                                ->get();

      
        $roomInUse = SPP_Rooms::getRoomUsage($id);

        if(count($roomsinfodetails)==0){

            return view('adminPortal.pages.roomsinfo')->with('deleted',true);

        }

        $cdetails = DB::table('classsched')
                        ->join('classscheddetail', 'classsched.id', '=', 'classscheddetail.headerid')
                        ->join('sections', 'classsched.sectionid', '=', 'sections.id')
                        ->where('classscheddetail.roomid', $id)
                        ->where('classscheddetail.deleted', '0')
                        ->where('classsched.deleted','0')
                        ->distinct()
                        ->get();

        $firstgroup =  collect($cdetails)->groupBy('sectionname');

     

        // foreach($firstgroup  as $item){

        //     $secondgroup = collect($item)->groupby('stime')->sortBy('days');
          
        //     foreach($secondgroup  as $itemsecond){

        //         $dayString = '';
        //         $data = null;

        //         foreach($itemsecond  as $itemThird){
        //             $data = $itemThird;
        //             $dayString.= substr($itemThird->description, 0,3).' / ';

        //         }

        //         array_push($schedulesummary,(object)[
        //             'daysum'=>$dayString,
        //             'subjinfo'=>$data
        //         ]);

        //     }

        // }


        // return $dayString;

        $shcdetail = DB::table('sh_classsched')
                ->leftJoin('sh_classscheddetail', 'sh_classsched.id', '=','sh_classscheddetail.headerid')
                ->where('sh_classscheddetail.roomid', $id)
                ->where('sh_classscheddetail.deleted', '0')
                ->where('sh_classsched.deleted','0')
                ->get();


        $shcblocksched = DB::table('sh_blocksched')
                ->leftJoin('sh_blockscheddetail', 'sh_blocksched.id', '=','sh_blockscheddetail.headerid')
                ->where('sh_blockscheddetail.roomid', $id)
                ->where('sh_blockscheddetail.deleted', '0')
                ->where('sh_blocksched.deleted','0')
                ->get();
   
        return view('adminPortal.pages.roomsinfo')
                    ->with('cdetails',$cdetails)
                    ->with('shcdetail',$shcdetail)
                    ->with('shcblocksched',$shcblocksched)
                    ->with('roomsdetail',$roomsinfodetails)
                    ->with('inUse',$roomInUse)
                    ->with('deleted',false); 
    }

    public function calendarinfo($id)
    {
        $calendarinfodetails = DB::table('adimages')
                            ->where('adimages.id', $id)
                            ->where('deleted','0')
                            ->get();

        return view('adminPortal.pages.calendarinfodetails')
                    ->with('cidetails', $calendarinfodetails);
    }
    public function adminsendsmstext(Request $request){
        
        $students = DB::table('studinfo')
                ->select(
                    'mcontactno',
                    'fcontactno',
                    'gcontactno',
                    'ismothernum',
                    'isfathernum',
                    'isguardannum'
                    )
                ->get();


        SPP_Announcement::smstexter($students, $request->get('message'));
        
        return back();
    }

    public function adminviewsmstexter(){


        $students = DB::table('studinfo')
                ->select(
                    'mcontactno',
                    'fcontactno',
                    'gcontactno',
                    'ismothernum',
                    'isfathernum',
                    'isguardannum'
                    )
                ->get();

        $studentCount = count($students);
        $studentWithNum = 0;
        $studentWithNoNum = 0;

        $summary = array();
        
        foreach($students as $student){

            if($student->isfathernum == 1){
                $contactnum = $student->fcontactno;
            }
            elseif($student->ismothernum == 1){
                $contactnum = $student->mcontactno;
            }
            else{
                $contactnum = $student->gcontactno;
            }
            
            if($contactnum != null || $contactnum != 0){
                $studentWithNum +=1;
            }
            else{
                $studentWithNoNum +=1;
            }

        }

        array_push($summary, (object)['studentcount'=>$studentCount,'withNum'=>$studentWithNum,'withoutNum'=>$studentWithNoNum]);
   

        return view('adminportal.pages.smstexter')->with('summary',$summary);

    }

    public static function adminuploadlogo($schoollogoimage, Request $request){

          

            $urlFolder = str_replace('http://','',$request->root());
            $urlFolder = str_replace('https://','',$urlFolder);

            if (! File::exists(public_path().'schoollogo/')) {

                $path = public_path('schoollogo');

                if(!File::isDirectory($path)){

                    File::makeDirectory($path, 0777, true, true);
                }
            }

            if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/schoollogo/')) {

                $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/schoollogo/';

                if(!File::isDirectory($cloudpath)){

                    File::makeDirectory($cloudpath, 0777, true, true);

                }
                
            }

            $file = $schoollogoimage;
        
            $extension = $file->getClientOriginalExtension();
            
            $img = Image::make($file->path());

            $fileName = 'schoolLogo';


            $destinationPath = public_path('schoollogo/schoollogo.'.$extension);

            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.'schoollogo/schoollogo.'.$extension;

            $img->resize(500, 500, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->resizeCanvas(500, 500,'center')->save($destinationPath);

            $img->resize(500, 500, function ($constraint) {
                            $constraint->aspectRatio();
                        })->resizeCanvas(500, 500,'center')->save($clouddestinationPath);

            DB::table('schoolinfo')
                    ->update([
                        'picurl'=>'schoollogo/schoollogo.'.$extension.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"',
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
     
               

    }

    public function adminviewadvertisements(Request $request){

        $adimages = DB::table('adimages')->orderBy('isactive','desc')->where('deleted','0')->get();

        return view('adminPortal.pages.advertisement.advertisements')->with('adimages',$adimages);

    }

    public function adminstoreadvertisements(Request $request){


        
        $message = [
            'adimage.mimes'=>'Advertisement image must be a file of type: jpeg, png.',
            'adimage.required'=>'Image is required.'
        ];

        $validator = Validator::make($request->all(), [
            'adimage' => ['mimes:jpeg,png','required']
        
        ], $message);

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
    
            return back()->withErrors($validator)->withInput();

        }
        else{

            

     

        $urlFolder = str_replace('http://','',$request->root());

        // if (self::isDomainAvailible('http://tapapp.ck/')){

            $path = public_path('advertisements/advertisement4.jpg');

        $headers = array(
            'Content-Type: => application/pdf',
        );

        if (! File::exists(public_path().'advertisements/')) {

            $path = public_path('advertisements');

            if(!File::isDirectory($path)){

                File::makeDirectory($path, 0777, true, true);
            }
        }

        if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/advertisements/')) {

            $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/advertisements/';

            if(!File::isDirectory($cloudpath)){

                File::makeDirectory($cloudpath, 0777, true, true);

            }
            
        }

            $adcount = DB::table('adimages')->count();

            $id = $adcount+1;

            $file = $request->file('adimage');
            
            $extension = $file->getClientOriginalExtension();
            
            $img = Image::make($file->path());

            $destinationPath = public_path('advertisements/advertisement'.$id.'.'.$extension);

            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.'advertisements/advertisement'.$id.'.'.$extension;

            $img->resize(1360, 768)->save($destinationPath);

            $img->resize(500, 500)->save($clouddestinationPath);
            

            DB::table('adimages')
                    ->insert(['picurl'=>'advertisements/advertisement'.$id.'.'.$extension]);

            // $url = "http://tapapp.ck/uploadImage?filename=advertisement".$id.'.'.$extension.'&path='.$request->root();

            // return Redirect::to($url);

            return back();

        }
        // }
        // else{

        //     return back()->withErrors(['tapoff'=>'Unable to upload image! Please turn on tapping station']);

        // }
   

      
    }

    public static function isDomainAvailible($domain)
    {
            
            if(!filter_var($domain, FILTER_VALIDATE_URL))
            {
                    return false;
            }

            //initialize curl
            $curlInit = curl_init($domain);
            curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
            curl_setopt($curlInit,CURLOPT_HEADER,true);
            curl_setopt($curlInit,CURLOPT_NOBODY,true);
            curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

            //get answer
            $response = curl_exec($curlInit);

            curl_close($curlInit);

            if ($response) return true;

            return false;
    }


    
    public static function setimageisactive($id,$status,Request $request){



    //    if (self::isDomainAvailible('http://tapapp.ck/')){

            DB::table('adimages')->where('id',$id)->update(['isactive'=>$status]);

            return back();
            // $url = "http://tapapp.ck/setimageisactive?id=".$id.'&status='.$status.'&path='.$request->root();

            // return Redirect::to($url);
        // }
        // else{

        //     toast('Please turn on tapping station!','error')->autoClose(5000)->toToast($position = 'top-right');
        //     return back();
        // }



    }

    public function getBuilding(){

        $activesy = SPP_SchoolYear::getActiveSchoolyear();

        if(!isset($activesy )){

            return view('generalPages.adminErrorBlade')
                    ->with('message','No active school year.')
                    ->with('link','manageschoolyear');

        }

        return view('adminPortal.pages.building.showbuildings')->with('data',SPP_Building::getBuilding(null,10));

    }

    public function addbuilding(Request $request){


        return SPP_Building::addbuilding($request);
      
    }

    
    public function updatebuilding(Request $request){

    
        return SPP_Building::updatebuilding($request);
      
    }

    public function searchbuilding(Request $request){

        return view('adminPortal.pages.building.buildingtable')
                ->with('data',SPP_Building::getBuilding($request->get('pagenum'),10,$request->get('id'),$request->get('data')));
      
    }

    public function buildinginfo($buildingName){

        $buildingInfo = DB::table('building')
                            ->where('id',$buildingName)
                            ->where('deleted','0')
                            ->first();

        if(isset($buildingInfo->id) == 0){

            return view('adminPortal.pages.building.buildinginfo')->with('deleted',true);

        }
                    
        $buildingUsage = DB::table('rooms')
                            ->where('buildingid',$buildingInfo->id)
                            ->where('deleted','0')
                            ->get();

        

        return view('adminPortal.pages.building.buildinginfo')
                                ->with('deleted',false)
                                ->with('buildingUsage',$buildingUsage)
                                ->with('building',$buildingInfo);
               
      
    }

    
    public function removeimage($id){



        DB::table('adimages')->where('id',$id)->update(['deleted'=>'1','isactive'=>0]);

        return Redirect::to('/adminviewadvertisements');

    }

    public function removebuilding($id){

        $usageCount = DB::table('rooms')->select('capacity')->where('buildingid',$id)->where('deleted','0')->sum('capacity');

        if($usageCount == 0){

            DB::table('building')
                ->where('id',$id)
                ->update([
                    'deleted'=>'1',
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
            toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');

            return back();

        }

      

    }

    public function checkbuildingcapacity($id, $roomcapacity){

        if($roomcapacity == 0){
            $data = array(
                (object)
              [
                'status'=>'1',
                'message'=>'Please specity room capacity!'
            ]);
            return $data;
        }
        else{

            $buidlingUsage = DB::table('rooms')->select('capacity')->where('deleted','0')->where('buildingid',$id)->sum('capacity');
            $buidlingInfo = DB::table('building')->where('id',$id)->first();

            if($buidlingUsage+$roomcapacity > $buidlingInfo->capacity){

                $data = array(
                    (object)
                  [
                    'status'=>'1',
                    'message'=>'Building reached its maximum capacity! '.( $buidlingUsage+$roomcapacity ).' / '. $buidlingInfo->capacity
                ]);
                return $data;
            }

        }


        return "sdfsdf";

    }


    public function updatefacultycollege($id,$college,Request $requests){

        $teacherInfo = DB::table('teacher')->where('id',$id)->first();

        if($requests->get('status') == 1){

            if($teacherInfo->usertypeid == 14){

                DB::table('college_colleges')
                    ->where('id',$college)
                    ->update([
						'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'dean'=>$id
                    ]);
    
            }
        }
        else if($requests->get('status') == 2){

            if($teacherInfo->usertypeid == 14){

                DB::table('college_colleges')
                    ->where('id',$college)
                    ->update([
						'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'dean'=>null
                    ]);
    
            }

        }
     
        return "done";

    }    

    public function updatefacultycourse($id,$course,Request $requests){

        $teacherInfo = DB::table('teacher')->where('id',$id)->first();

        if($requests->get('status') == 1){

            if($teacherInfo->usertypeid == 16){

                DB::table('college_courses')
                    ->where('id',$course)
                    ->update([
						'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'courseChairman'=>$id
                    ]);
    
            }
        }
        else if($requests->get('status') == 2){

            if($teacherInfo->usertypeid == 16){

                DB::table('college_courses')
                    ->where('id',$course)
                    ->update([
						'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'courseChairman'=>null
                    ]);
    
            }

        }
     
        return "done";

    }    


    public static function viewcolleges(){

        $colleges = DB::table('college_colleges')
                        ->where('deleted','0')
                        ->get();

        $inputArray = array();

        $inputs = array(
            (object)['name'=>'collegeDesc','type'=>'input','table'=>null,'label'=>'COLLEGE DESCRIPTION','value'=>null],
            (object)['name'=>'collegeabrv','type'=>'input','table'=>null,'label'=>'COLLEGE ABBREVIATION','value'=>null]
        );

        $modalInfo = (object)[
            'modalName'=>'collegeModal',
            'modalheader'=>'COLLEGE',
            'method'=>'',
            'crud'=>'CREATE'];


        $inputs1 = array(
            (object)['name'=>'courseDesc','type'=>'input','table'=>null,'label'=>'COURSE DESCRIPTION','value'=>null],
            (object)['name'=>'courseabrv','type'=>'input','table'=>null,'label'=>'COURSE ABBREVIATION','value'=>null],
        );

        $modalInfo1 = (object)[
            'modalName'=>'courseModal',
            'modalheader'=>'COURSE',
            'method'=>'',
            'crud'=>'CREATE'];


        array_push($inputArray,
            [$inputs , $modalInfo ],
            [$inputs1 , $modalInfo1 ]
          
        );

        $extension = '';

        return view('adminPortal.pages.colleges.viewcolleges')
                ->with('inputArray',$inputArray)
                ->with('extension',$extension)
                ->with('colleges',$colleges);

    }

    public function adminresetpasstable(){

        $userCount = DB::table('users')
                ->whereIn('users.type',[7,9])
                ->select('email','name','type','isdefault')
                ->count();

   

        $users = DB::table('users')
                ->whereIn('users.type',[7,9])
                ->join('usertype',function($join){
                    $join->on('users.type','=','usertype.id');
                })
                ->select('email','name','type','isdefault','utype','users.id')
                ->get();

        // return count($users);

        $data = array((object)[
            'data'=>$users,
            'count'=>$userCount
        ]);

        return view('adminPortal.pages.passresetter.credentialblade')->with('data',$data);

    }

    public function errormessage(){

 
        return response()->json(['error' => 'Error msg'], 404); 

    }
    
      public function passwordgenerator(Request $request)
      {
            if(strtolower($request->get('action')) == 'index')
            {
                  $usertypes = DB::table('usertype')
                        ->where('deleted','0')
                        ->orderBy('utype','asc')
                        ->get();

                  return view('adminPortal.pages.passgenerator.index')
                        ->with('usertypes', $usertypes);
            }
            elseif(strtolower($request->get('action')) == 'filter')
            {
                  $usertypeid = $request->get('usertypeid');
                  $users = PasswordGenerator::getallusers($usertypeid);
                  return view('adminPortal.pages.passgenerator.userstable')
                        ->with('users', $users);
            }
            elseif(strtolower($request->get('action')) == 'generatepassword')
            {
                  $password = PasswordGenerator::generatepassword($request->get('userid'));
                  return collect($password);
            }elseif(strtolower($request->get('action')) == 'custompassword')
            { 
                  $random_string = $request->get('newpassword');
          
                  $checkifexists = DB::table('users')
                      ->where('passwordstr','like','%'.$random_string.'%')
                      ->first();
          
                  if(!$checkifexists)
                  {
                      $hashed = Hash::make($random_string);
                      $data = (object)[
                          'code'=>$random_string,
                          'hash'=>$hashed
                      ];
                      DB::table('users')
                          ->where('id', $request->get('userid'))
                          ->update([
                              'passwordstr'   => $random_string,
                              'password'      => $hashed
                          ]);
                  }
                  
                  //return collect($data);

            }elseif(strtolower($request->get('action')) == 'export')
            {     
                  $usertypeid = $request->get('usertypeid');
                  $users = PasswordGenerator::getallusers($usertypeid);
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet
                    ->setCellValue('A1', 'User ID')
                    ->setCellValue('B1', 'Name')
                    ->setCellValue('C1', 'Username')
                    ->setCellValue('D1', 'Password')
                    ->setCellValue('E1', 'User Type');

                if(count($users)>0)
                {
                    $count = 2;
                    foreach($users as $userinfo)
                    {
                        $sheet
                            ->setCellValue('A'.$count, $userinfo->userid)
                            ->setCellValue('B'.$count, $userinfo->name)
                            ->setCellValue('C'.$count, $userinfo->email)
                            ->setCellValue('D'.$count, $userinfo->passwordstr)
                            ->setCellValue('E'.$count, $userinfo->usertype);

                        $count+=1;
                    }
                }
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="Users.xlsx"');
                $writer->save("php://output");
            }
      }

    // public static function backupdb(){

    //     $stringToAppend = 'testing';

    //     if (! File::exists(public_path().'dbbackup/')) {

    //         $path = public_path('dbbackup');

    //         if(!File::isDirectory($path)){

    //             File::makeDirectory($path, 0777, true, true);
    //         }
    //     }

    //     $newLine = "\r\n";
    //     $targetTables = [];


    //     $queryTables = DB::select(DB::raw('SHOW TABLES'));

    //     $dbname = str_replace("[\"Tables_in_","",collect($queryTables[0])->keys());

    //     $dbname = str_replace("\"]","",$dbname);

    //     foreach($queryTables as $key=>$table){
           
    //         $targetTables[] = $table->Tables_in_es_test;

    //         if($dbname == ''){
               
    //         }
            
    //     }

    //     $content = "";

    //     foreach($targetTables as $table){

    //         try{
    //             $tableData = DB::select(DB::raw('SELECT * FROM '.$table));
    //         }
    //         catch (\Exception $e) {
                
    //         }

    //         $content .= "DROP TABLE IF EXISTS `".$table."`";

    //         $res = DB::select(DB::raw('SHOW CREATE TABLE '.$table));
         
    //         $content .= $res[0]->{'Create Table'}.$newLine.$newLine;

           
    //         if(count($tableData)>0){

    //             $content .=" INSERT INTO "."`".$table."` (";

    //             $fields = array_keys(collect($tableData[0])->toArray());

    //             foreach($fields as $field){

    //                 $content .= "`".$field."`,";

    //             }

    //             $content .= ") values ";

    //             foreach($tableData as $item){

    //                 $content.="(";
                    
    //                 foreach($item as $key=>$data){

    //                     if($data == ''){
                        
    //                         $content .= "NULL";

    //                     }
    //                     else{

    //                         if(gettype($data) == 'integer'){
    //                             $content .= $data;
    //                         }
    //                         else{
    //                             $content .= " `".$data."`";
    //                         }
                           

    //                     }
                        
    //                     $content .= ",";

    //                 }

    //                 $content = substr($content,0,-1);
                   
    //                 $content.='),';
                    
    //             }
                

    //             $content = substr($content,0,-1);

    //             $content.=$newLine.$newLine;
               
    //         }

            


    //     }

    //     date_default_timezone_set('Asia/Manila');
    //     $date = date('mdY His');

    //     // return $filename;

    //     file_put_contents('dbbackup/'.$dbname.' '.$date.'.sql', $content, FILE_APPEND);

    //     return $content;

    // }

    // public static function changeenv(){

    //     Artisan::call('artisan env:set app_name Example');
    
    //     // $str = file_get_contents($envFile);


    //     config(['database.connections.mysql.database'=>'es_dev']);

    //     // return $str;

    //     //  env('DB_DATABASE','es_dev');
         
    //     //  Config::set('app.DB_DATABASE', 'es_dev');

    //     // Artisan::call('config:cache');

    //     $queryTables = DB::select(DB::raw('SHOW TABLES'));

    //     return $queryTables;

    // }

    // public function something(){
    //     // some code
    //     $env_update = $this->changeEnv([
    //         'DB_DATABASE'   => 'new_db_name',
    //         'DB_USERNAME'   => 'new_db_user',
    //         'DB_HOST'       => 'new_db_host'
    //     ]);
    //     if($env_update){

    //     } else {

    //     }
    //     // more code
    // }
    
    // public function changeEnv($data = array()){

    //     if(count($data) > 0){
          
    //         $env = file_get_contents(base_path() . '/.env');
           
    //         $env = preg_split('/\s+/', $env);
       
    //         foreach((array)$data as $key => $value){

              
    //             foreach($env as $env_key => $env_value){

                
    //                 $entry = explode("=", $env_value, 2);

                 
    //                 if($entry[0] == $key){

    //                     $env[$env_key] = $key . "=" . $value;

    //                 } else {

    //                     $env[$env_key] = $env_value;

    //                 }
    //             }
    //         }

    //         $env = implode("\n", $env);

    //         file_put_contents(base_path() . '/.env', $env);
            
    //         return true;

    //     } else {
            
    //         return false;
    //     }
    // }

    


}
