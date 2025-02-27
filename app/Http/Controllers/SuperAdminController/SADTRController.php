<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\CarbonPeriod;

class SADTRController extends \App\Http\Controllers\Controller
{

      public static function get_taphistory(Request $request){

            $date = $request->get('date');
            $tapstate = $request->get('tapstate');
            $syid = $request->get('syid');

            if(Session::get('currentPortal') != 7 && Session::get('currentPortal') != 9){
                  if($request->get('studid') != "" && $request->get('studid') != null){
                        $explodedinfo = explode('-',$request->get('studid'));
                        $studid = $explodedinfo[1];
                        if($explodedinfo[0] == 'T'){
                              $type = 1;
                        }else{
                              $type = 7;
                        }
                  }else{
                        $studid = $request->get('studid');
                  }
                  
            }else{
                  if(Session::get('currentPortal') == 9){
                        $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
                  }else{
                        $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
                  }
                  $type = 7;
            }

            $enrolled = array();

            if(Session::get('currentPortal') == 1){

                  $enrolled = collect(array());

                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->first();

                  $sectioninfo = DB::table('sectiondetail')
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->where('teacherid',$teacherid->id)
                                          ->get();

                   $enrolledstud = DB::table('enrolledstud')
                                          ->where('deleted',0)
                                          ->whereIn('sectionid',collect($sectioninfo)->pluck('sectionid'))
                                          ->where('syid',$syid)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->select('studid')
                                          ->get();

                  $enrolled = $enrolled->merge($enrolledstud);     

                  $enrolledstud = DB::table('sh_enrolledstud')
                                          ->where('deleted',0)
                                          ->whereIn('sectionid',collect($sectioninfo)->pluck('sectionid'))
                                          ->where('syid',$syid)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->select('studid')
                                          ->get();

                  $enrolled = $enrolled->merge($enrolledstud);  

            }


            $alldates = array();
            if($date != null && $date != ''){
                  $explodeddates = explode('-',$date);
                  $begin = new DateTime($explodeddates[0]);
                  $end = new DateTime($explodeddates[1]);
                  $endmonth = $end->modify( '+1 day' ); 
                  $interval = new DateInterval('P1D');
                  $period = new DatePeriod($begin, $interval, $end);
                  $alldates = array();
                  
                  foreach ($period as $dt) {
                        array_push($alldates,(object)['date'=>$dt->format("Y-m-d")]);
                  }
            }
            
            $search = $request->get('search');
            $search = $search['value'];


            $taphistory = DB::table('taphistory')
                              ->orderBy('tdate','desc')
                              ->select(
                                    'taphistory.*',
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname,' - ',studinfo.sid) as studentname")
                              )
                              ->join('studinfo',function($join){
                                    $join->on('taphistory.studid','=','studinfo.id');
                              })
                              ->where(function($query) use($date,$alldates,$studid,$search,$tapstate,$enrolled){
                                    $query->where('utype',7);
                                    if($studid != "" && $studid != null){
                                          $query->where('studid',$studid);
                                    }
                                    if($tapstate != "" && $tapstate != null){
                                          $query->where('tapstate',$tapstate);
                                    }

                                    if(Session::get('currentPortal') == 1){
                                          $query->whereIn('studid',collect($enrolled)->pluck('studid'));
                                    }
                                    
                                    if($date != "" && $date != null){
                                          $query->whereIn('tdate',collect($alldates)->pluck('date'));
                                    }
                                    if($search != "" && $search != null){
                                          $query ->where(function($query) use($search){
                                                $query->orWhereRaw("CONCAT(`lastname`,', ',`firstname`) LIKE ?",['%'.$search.'%']);
                                                $query->orWhere('tdate','like','%'.$search.'%');
                                                $query->orWhere('sid','like','%'.$search.'%');
                                          }); 
                                    }
                              });
                             
                             

            $tapcount =  $taphistory->count();
            
            $taphist =    $taphistory->take($request->get('length'))
                                    ->skip($request->get('start'))
                                  
                                    ->get();

        
            foreach($taphist as $dateitem){
                  $dateitem->newdate = \Carbon\Carbon::create($dateitem->tdate)->isoFormat('MMM DD, YYYY');
                  $dateitem->newtime = \Carbon\Carbon::create($dateitem->ttime)->isoFormat('hh:mm A');
            }


            
            return @json_encode((object)[
                  'data'=>$taphist,
                  'recordsTotal'=>$tapcount,
                  'recordsFiltered'=>$tapcount
            ]);

      }


      public static function get_acad($syid = null){

            $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
            $refid = $check_refid->refid;

            if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15 || Session::get('currentPortal') == 17 ||  $refid == 28 || auth()->user()->type == 6 || Session::get('currentPortal') == 6){
                  $acadprog = DB::table('academicprogram')
                                          ->select('id')
                                          ->get();
            }elseif(Session::get('currentPortal') == 14 ){
                  $acadprog = DB::table('academicprogram')
                                    ->select('id')
                                    ->get();
            }
            else{

                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->select('id')
                                    ->first()
                                    ->id;

                  $acadprog = DB::table('teacheracadprog')
                              ->where('teacherid',$teacherid)
                              ->where('deleted',0)
                              ->where('syid',$syid)
                              ->where('acadprogutype',Session::get('currentPortal'))
                              ->select('acadprogid as id')
                              ->distinct('acadprogid')
                              ->get();
            }


            $acadprog_list = array();
            foreach($acadprog as $item){
                  array_push($acadprog_list,$item->id);
            }

            return $acadprog_list;

      }

      public static function SADTRstudents(Request $request){


            $enrolled = array();
            $syid = $request->get('syid');

            if(Session::get('currentPortal') == 2){
                  
                  $acad = self::get_acad($syid);

                  $enrolled = collect(array());

                  $gradelevel = DB::table('gradelevel')
                                    ->whereIn('acadprogid',$acad)
                                    ->get();

                  $enrolledstud = DB::table('enrolledstud')
                                          ->where('deleted',0)
                                          ->whereIn('levelid',collect($gradelevel)->pluck('id'))
                                          ->where('syid',$syid)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->select('studid')
                                          ->get();

                  $enrolled = $enrolled->merge($enrolledstud);     

                  $enrolledstud = DB::table('sh_enrolledstud')
                                          ->where('deleted',0)
                                          ->whereIn('levelid',collect($gradelevel)->pluck('id'))
                                          ->where('syid',$syid)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->select('studid')
                                          ->get();

                  $enrolled = $enrolled->merge($enrolledstud);     


            }else if(Session::get('currentPortal') == 14){

                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->first();

                  $college_assinged = DB::table('teacherdean')
                                          ->where('deleted',0)
                                          ->where('teacherid',$teacherid->id)
                                          ->get();

                  $enrolled = DB::table('college_enrolledstud')
                                          ->where('deleted',0)
                                          ->whereIn('courseid',collect($college_assinged)->pluck('collegeid'))
                                          ->where('syid',$syid)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->select('studid')
                                          ->get();

            }else if(Session::get('currentPortal') == 1){


                  $enrolled = collect(array());

                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->first();

                  $sectioninfo = DB::table('sectiondetail')
                                          ->where('deleted',0)
                                          ->where('syid',$syid)
                                          ->where('teacherid',$teacherid->id)
                                          ->get();

                   $enrolledstud = DB::table('enrolledstud')
                                          ->where('deleted',0)
                                          ->whereIn('sectionid',collect($sectioninfo)->pluck('sectionid'))
                                          ->where('syid',$syid)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->select('studid')
                                          ->get();

                  $enrolled = $enrolled->merge($enrolledstud);     

                  $enrolledstud = DB::table('sh_enrolledstud')
                                          ->where('deleted',0)
                                          ->whereIn('sectionid',collect($sectioninfo)->pluck('sectionid'))
                                          ->where('syid',$syid)
                                          ->whereIn('studstatus',[1,2,4])
                                          ->select('studid')
                                          ->get();

                  $enrolled = $enrolled->merge($enrolledstud);  

            }

            $students = DB::table('studinfo')
                              ->where('deleted',0)
                              ->where('studisactive',1)
                              ->whereNotNull('lastname');

            if(Session::get('currentPortal') == 2 || Session::get('currentPortal') == 14 || Session::get('currentPortal') == 1){
                  $students =  $students->whereIn('id',collect($enrolled)->pluck('studid'));
            }
           
            $students =  $students->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'sid',
                                    'id'
                              )
                              ->get();

            
            foreach($students as $item){
                  $temp_middle = '';
                  $temp_suffix = '';
                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }
                  $item->name = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                  $item->text =  $item->sid.' - '.$item->name;
                  $item->type = 7;
                  $item->id = 'S-'.$item->id;
            }

            $students = collecT($students)->toArray();
            $teachers = array();

            if(Session::get('currentPortal') == 14){

                  $allteachers = collect(array());

                  $teachers = DB::table('teacher')
                                    ->where('deleted',0)
                                    ->where('isactive',1)
                                    ->where('usertypeid',18)
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'tid',
                                          'id'
                                    )
                                    ->get();

                  $allteachers = $allteachers->merge($teachers);      
                  
                  $teachers = DB::table('faspriv')
                                    ->where('faspriv.deleted',0)
                                    ->where('usertype',18)
                                    ->join('teacher',function($join){
                                          $join->on('faspriv.userid','=','teacher.userid');
                                          $join->where('teacher.deleted',0);
                                          $join->where('isactive',1);
                                    })
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'tid',
                                          'teacher.id'
                                    )
                                    ->get();

                  $allteachers = $allteachers->merge($teachers);  

                  $teachers = $allteachers;

            }else if(Session::get('currentPortal') == 2){

                  $allteachers = collect(array());

                  $teachers = DB::table('teacher')
                        ->where('deleted',0)
                        ->where('isactive',1)
                        ->whereIn('usertypeid',[1])
                        ->select(
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'tid',
                              'teacher.id'
                        )
                        ->get();

                  $allteachers = $allteachers->merge($teachers);      
                  
                  $teachers = DB::table('faspriv')
                                    ->where('faspriv.deleted',0)
                                    ->where('usertype',1)
                                    ->join('teacher',function($join){
                                          $join->on('faspriv.userid','=','teacher.userid');
                                          $join->where('teacher.deleted',0);
                                          $join->where('isactive',1);
                                    })
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'tid',
                                          'teacher.id'
                                    )
                                    ->get();

                  $allteachers = $allteachers->merge($teachers);  

                  $teachers = $allteachers;
            }

           

            foreach($teachers as $item){
                  $temp_middle = '';
                  $temp_suffix = '';
                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }
                  $item->name = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                  $item->text =  $item->tid.' - '.$item->name;
                  $item->type = 1;
                  $item->id = 'T-'.$item->id;
                  array_push($students,$item);
            }

            return collect($students)->sortBy('name')->values();

      }

      public static function SADTRattendancelogs(Request $request){


            if(Session::get('currentPortal') != 7 && Session::get('currentPortal') != 9){
                  if($request->get('studid') != null && $request->get('studid') != ""){
                        $explodedinfo = explode('-',$request->get('studid'));
                        $studid = $explodedinfo[1];
                        if($explodedinfo[0] == 'T'){
                              $type = 1;
                        }else{
                              $type = 7;
                        }
                  }else{
                        $type = 7;
                  }
                  
            }else{
                  if(Session::get('currentPortal') == 9){
                        $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
                  }else{
                        $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
                  }
                  
                  $type = 7;
            }
          
            $date = $request->get('date');
         

        

            if($date != null && $date != ''){
                  $explodeddates = explode('-',$date);
                  $begin = new DateTime($explodeddates[0]);
                  $end = new DateTime($explodeddates[1]);
                  $endmonth = $end->modify( '+1 day' ); 
                  $interval = new DateInterval('P1D');
                  $period = new DatePeriod($begin, $interval, $end);
                  $alldates = array();
                  
                  foreach ($period as $dt) {
                        array_push($alldates,(object)['date'=>$dt->format("Y-m-d")]);
                  }
            }else{
                  $alldates = DB::table('taphistory')
                              
                              ->where('deleted',0);

                  if($request->get('studid') != null && $request->get('studid') != ""){
                        $alldates = $alldates->where('studid',$studid);
                  }

                  if($type == 1){
                        $alldates = $alldates->where('utype','!=',7);
                  }else{
                        $alldates = $alldates->where('utype',7);
                  }
                  
                  $alldates = $alldates->select(
                                    'tdate as date'
                              )
                              ->distinct('date')
                              ->get();
                              
            }



            $alldates = collect( $alldates)->sortByDesc('date')->values();

            $timelogs = DB::table('taphistory');

            if($request->get('studid') != null && $request->get('studid') != ""){
                  $timelogs = $timelogs->where('studid',$studid);
            }

            if($type == 1){
                  $timelogs = $timelogs->where('utype','!=',7);
            }else{
                  $timelogs = $timelogs->where('utype',7);
            }

            $timelogs = $timelogs->where('deleted',0)
                              ->whereIn('tdate',collect($alldates)->pluck('date'))
                              ->get();


            

            foreach($alldates as $dateitem){
                  $logs = collect($timelogs)
                              ->where('tdate',$dateitem->date)
                              ->values();

                  $logs = collect($logs)->unique()->values();

                  foreach($logs as $logitem){
                        $logitem->newtime = Carbon::create($logitem->ttime)->isoFormat('hh:mm A');
                  }

                  $dateitem->newdate = \Carbon\Carbon::create($dateitem->date)->isoFormat('MMM DD, YYYY');
                  $dateitem->day = \Carbon\Carbon::create($dateitem->date)->isoFormat('ddd');
                  $dateitem->logs = $logs;

            }

            return $alldates;
      
      }

      public static function SADTRLateStudent(Request $request){
       
            $date = $request->get('date');
            $explodeddates = explode('-',$date);
            $begin = new DateTime($explodeddates[0]);

            if($date == "" || $date == null){
                  $alldates = Db::table('taphistory')
                                          ->where('utype',7)
                                          ->distinct('tdate')
                                          ->select('tdate')
                                          ->get();

                  // $alldates = collect( $alldates)->pluck('tdate');
            }else{

                  $explodeddates = explode('-',$date);
                  $begin = new DateTime($explodeddates[0]);
                  $end = new DateTime($explodeddates[1]);
                  $endmonth = $end->modify( '+1 day' ); 
                  $interval = new DateInterval('P1D');
                  $period = new DatePeriod($begin, $interval, $end);
                  $alldates = array();
                  
                  foreach ($period as $dt) {
                        array_push($alldates,(object)['tdate'=>$dt->format("Y-m-d")]);
                  }
            }

            // return $request->all();

            // return $alldates;

     
            
            foreach($alldates as $alldate_item){
                  
                  $morninglate = Db::table('taphistory')
                                    ->where('utype',7)
                                    ->where('tdate',$alldate_item->tdate)
                                    ->where('ttime' ,'>',$request->get('amtimestart'))
                                    ->where('ttime' ,'<',$request->get('amtimeend'))
                                    ->where('tapstate','IN')
                                    ->distinct('studid')
                                    ->join('studinfo',function($join){
                                          $join->on('taphistory.studid','=','studinfo.id');
                                    })
                                    ->select(
                                          'tdate',
                                          'ttime',
                                          'studid',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                                          'sid'

                                    )
                                    ->get();

                  // if($alldate_item->tdate == '2023-11-28'){
                  //       return        $request->get('amtimestart').':00';
                  // }
                                    
                  $morninglate = collect($morninglate)->sortBy('ttime')->values();    
                  $morninglate = collect($morninglate)->unique('studid')->values();    
            
                  $afternoonlate = Db::table('taphistory')
                                    ->where('utype',7)
                                    ->where('tdate',$alldate_item->tdate)
                                    ->where('ttime' ,'>',$request->get('pmtimestart'))
                                    ->where('ttime' ,'<',$request->get('pmtimeend'))
                                    ->where('tapstate','IN')
                                    ->distinct('studid')
                                    ->join('studinfo',function($join){
                                          $join->on('taphistory.studid','=','studinfo.id');
                                    })
                                    ->select(
                                          'tdate',
                                          'ttime',
                                          'studid',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                                          'sid'

                                    )
                                    ->get();
            
            
            
                  $afternoonlate = collect($afternoonlate)->sortBy('ttime')->values();    
                  $afternoonlate = collect($afternoonlate)->unique('studid')->values();  

                  $alldate_item->morninglate = $morninglate;
                  $alldate_item->afternoonlate =   $afternoonlate;
           
            }

            $amstarttime = $request->get('amtimestart');
            $amendtime = $request->get('amtimeend');
            $pmstarttime = $request->get('pmtimestart');
            $pmendtime = $request->get('pmtimeend');
            // return $alldates;

            $schoolinfo = DB::table('schoolinfo')->first();
            
            $pdf = PDF::loadView('superadmin.pages.printable.sadtrpdf_late',compact('alldates','morninglate','afternoonlate','schoolinfo','amstarttime','amendtime','pmstarttime','pmendtime', ))->setPaper('8.5X11');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream('Tap Monitoring (Late).pdf');


      }

      public static function SADTRprint(Request $request){
          
            $attendance = self::SADTRattendancelogs($request);
            $schoolinfo = DB::table('schoolinfo')->first();
            $date = $request->get('date');
            $id = null;
            
            if(Session::get('currentPortal') != 7 && Session::get('currentPortal') != 9){
                  if($request->get('studid') != null && $request->get('studid') != ""){
                        $explodedinfo = explode('-',$request->get('studid'));
                        $id = $explodedinfo[1];
                        if($explodedinfo[0] == 'T'){
                              $type = 1;
                        }else{
                              $type = 7;
                        }
                  }else{
                        $type = 7;
                        
                  }
                  
            }else{
                  if(Session::get('currentPortal') == 9){
                        $id = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
                  }else{
                        $id = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
                  }
                  
                  $type = 7;
            }
            
            if($type == 7){

                  if($request->get('studid') != null && $request->get('studid') != ""){
                        $info = DB::table('studinfo')
                                    ->where('id',$id)
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'id',
                                          'sid'
                                    )
                                    ->get();

                  }else{
                        $info =  DB::table('studinfo')
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'id',
                                          'sid'
                                    )
                                    ->get();
                  }

                
            }else{

                  $info = DB::table('teacher')
                        ->where('id',$id)
                        ->select(
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'id',
                              'tid as sid'
                        )
                        ->first();
            }

            $name = null;
            foreach($info as $item){
                  $temp_middle = '';
                  $temp_suffix = '';
                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                  }
                  if(isset($item->suffix)){
                        $temp_suffix = ' '.$item->suffix;
                  }
                  $item->name = $item->lastname.', '.$item->firstname.$temp_middle.$temp_suffix;
                  $name = $item->name;
            }
         
            // return $attendance;
                  
            $pdf = PDF::loadView('superadmin.pages.printable.sadtrpdf',compact('name','id','info','attendance','schoolinfo'))->setPaper('8.5X11');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream('Tap Monitoring.pdf');
      }



}
