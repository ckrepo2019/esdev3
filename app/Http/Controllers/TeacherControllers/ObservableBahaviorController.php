<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Hash;
use Session;
use \Carbon\Carbon;

class ObservableBahaviorController extends Controller
{
        public static function student_list(Request $request, $sectionid = null, $levelid = null, $syid = null){

                if($sectionid == null){
                        if($request->get('sectionid') != null){
                        $sectionid = array($request->get('sectionid'));
                        }
                }
                if($levelid == null){
                        $levelid = $request->get('levelid');
                }
                if($syid == null){
                        $syid = $request->get('syid');
                }


                
                if(Session::get('currentPortal') == 1){
                        if($sectionid == null){
                        $sectionid = collect(self::teacher_class($syid,$request))->pluck('id');
                        }
                }

                if(Session::get('currentPortal') == 3){
                        if($sectionid == null){
                        $sectionid = collect(self::teacher_class($syid,$request))->pluck('id');
                        }
                }

                $search = $request->get('search');
                $search = $search['value'];

                $enrolledstud = collect(array())->values(); 

                $students =     DB::table('sh_enrolledstud');

                if($syid != null){
                        $students = $students->where('syid',$syid);
                } 
                
                if($sectionid != null){
                        $students = $students->whereIn('sectionid',$sectionid);
                } 

                $students = $students->where('sh_enrolledstud.deleted',0)
                                        ->whereIn('studstatus',[1,2,4])
                                        ->select('studid')
                                        ->distinct('studid')
                                        ->get();

                $enrolledstud = $enrolledstud->merge(collect($students)->values());

                $students =     DB::table('enrolledstud') ;

                if($syid != null){
                        $students = $students->where('syid',$syid);
                }

                if($sectionid != null){
                        $students = $students->whereIn('sectionid',$sectionid);
                } 

                $students = $students->where('enrolledstud.deleted',0)
                                        ->whereIn('studstatus',[1,2,4])
                                        ->select('studid')
                                        ->distinct('studid')
                                        ->get();
                                        

                $enrolledstud = $enrolledstud->merge(collect($students)->values());

                $students =     DB::table('college_enrolledstud') ;

                if($syid != null){
                        $students = $students->where('syid',$syid);
                }

                // if($sectionid != null){
                //       $students = $students->whereIn('sectionID',$sectionid);
                // } 

                $students = $students->where('college_enrolledstud.deleted',0)
                                        ->whereIn('studstatus',[1,2,4])
                                        ->select('studid')
                                        ->distinct('studid')
                                        ->get();

                $enrolledstud = $enrolledstud->merge(collect($students)->values());

                $students = dB::table('studinfo');

                if($search != null){
                        $students = $students->where(function($query) use($search){
                                        $query->orWhere('firstname','like','%'.$search.'%');
                                        $query->orWhere('lastname','like','%'.$search.'%');
                                        $query->orWhere('sid','like','%'.$search.'%');
                                });
                }

                $students = $students->whereIn('id',collect($enrolledstud)->pluck('studid'))
                                ->take($request->get('length'))
                                ->skip($request->get('start'))
                                ->orderBy('studentname','asc')
                                ->select(
                                        'studinfo.id',
                                        'studinfo.lastname',
                                        'studinfo.firstname',
                                        'studinfo.middlename',
                                        'studinfo.contactno',
                                        'studinfo.fcontactno',
                                        'studinfo.mcontactno',
                                        'studinfo.gcontactno',
                                        'studinfo.fathername',
                                        'studinfo.mothername',
                                        'studinfo.guardianname',
                                        'suffix',
                                        'sid',
                                        DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                                )
                                ->get();

                $student_count = dB::table('studinfo');

                if($search != null){
                        $student_count = $student_count->where(function($query) use($search){
                                        $query->orWhere('firstname','like','%'.$search.'%');
                                        $query->orWhere('lastname','like','%'.$search.'%');
                                        $query->orWhere('sid','like','%'.$search.'%');
                                });
                }

                $student_count = $student_count->whereIn('id',collect($enrolledstud)->pluck('studid'))
                                ->select(
                                        'studinfo.id',
                                        'studinfo.lastname',
                                        'studinfo.firstname',
                                        'studinfo.middlename',
                                        'suffix',
                                        'sid'
                                )
                                ->count();

                foreach($students as $item){

                        $student_email = array();
                        array_push($student_email,'S'.$item->sid);
                        array_push($student_email,'P'.$item->sid);

                        $users = DB::table('users')
                                        ->whereIn('email',$student_email)
                                        ->select('email','passwordstr','id','isDefault')
                                        ->where('deleted',0)
                                        ->get();

                        $middlename = explode(" ",$item->middlename);
                        $temp_middle = '';
                        $suffix = '';

                        if($item->middlename != null){
                        $temp_middle = $item->middlename[0].'.';
                        }
                        if($suffix != null){
                        $suffix = $temp_middle[0].' ';
                        }

                        $item->student = $item->lastname.', '.$item->firstname.' '.$suffix.$temp_middle;

                        $parent_creds = collect($users)->where('email','P'.$item->sid)->values();
                        $student_creds = collect($users)->where('email','S'.$item->sid)->values();


                        $item->parent_credentials = $parent_creds;
                        $item->student_credentials = $student_creds;

                        

                }


                return @json_encode((object)[
                        'data'=>$students,
                        'recordsTotal'=>$student_count,
                        'recordsFiltered'=>$student_count
                ]);
                        

                // return $enrolledstud;

                

        }

        public static function status($syid = null,Request $request){

                $students_record = DB::table('guidance_behavior')
                                        ->where('guidance_behavior.createdby', auth()->user()->id)
                                        ->where('guidance_behavior.deleted', 0)
                                        ->leftjoin('guidance_behavior_notify',function($join){
                                                $join->on('guidance_behavior_notify.headerid','=','guidance_behavior.id');
                                                $join->where('guidance_behavior_notify.deleted',0);
                                        })
                                        ->join('studinfo',function($join){
                                                $join->on('studinfo.id','=','guidance_behavior.studentid');
                                                $join->where('studinfo.deleted',0);
                                        })
                                        ->select(DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname"),  'studinfo.contactno' ,  'studinfo.sid',  'guidance_behavior.remark' ,  'guidance_behavior.behavior' ,  'guidance_behavior_notify.createddatetime', 'guidance_behavior.resolve' )
                                        ->get();


                $student_count = count($students_record);

                foreach($students_record as $item){

                        if(isset($item->createddatetime)){

                                $carbonDate = Carbon::parse($item->createddatetime);
                                $item->cdate = $carbonDate->format('F j, Y ');

                        }else{

                                $item->cdate ='Not yet Notified';
                        }

                        if( $item->resolve == 1){
                                $item->status = 'Resolved';
                                $item->badge = 'badge-success';

                        }else{

                                if(isset($item->createddatetime)){
                                        $item->status = 'Notified';
                                        $item->badge = 'badge-primary';
                                }else{
                                        $item->status = 'Pending';
                                        $item->badge = 'badge-secondary';
                                }
                        }


                        


                }

                return @json_encode((object)[
                        'data'=> $students_record,
                        'recordsTotal'=>$student_count,
                        'recordsFiltered'=>$student_count
                ]);
                        

        }



        public static function teacher_class($syid = null,Request $request){

                if($syid == null){
                        if($request->get('syid') != null){
                                $syid = $request->get('syid');
                        }
                        else{
                                $syid = DB::table('sy')
                                        ->where('isactive',1)
                                        ->first()
                                        ->id;
                        }
                }

                if(Session::get('currentPortal') == 1){
                        
                        $teacherid = DB::table('teacher')
                                        ->where('userid',auth()->user()->id)
                                        ->where('deleted',0)
                                        ->select('id')
                                        ->first()
                                        ->id;

                        $sections = DB::table('sectiondetail')
                                ->join('sections',function($join){
                                        $join->on('sectiondetail.sectionid','=','sections.id');
                                        $join->where('sections.deleted',0);
                                })
                                ->where('sectiondetail.syid',$syid)
                                ->where('sectiondetail.deleted',0)
                                ->where('sectiondetail.teacherid', $teacherid)
                                ->orderBy('sectionname')
                                ->select(
                                        'levelid',
                                        'sectionname',
                                        'sectionname as text',
                                        'sections.id'
                                )
                                ->get();

                }else if(Session::get('currentPortal') == 3){

                        $all_acad = self::get_acad($syid);

                        $sections = DB::table('sectiondetail')
                                        ->join('sections',function($join){
                                                $join->on('sectiondetail.sectionid','=','sections.id');
                                                $join->where('sections.deleted',0);
                                        })
                                        ->join('gradelevel',function($join) use($all_acad){
                                                $join->on('sections.levelid','=','gradelevel.id');
                                                $join->where('gradelevel.deleted',0);
                                                $join->whereIn('gradelevel.acadprogid',$all_acad);
                                        })
                                        ->where('sectiondetail.syid',$syid)
                                        ->where('sectiondetail.deleted',0)
                                        ->orderBy('sectionname')
                                        ->select(
                                                'levelid',
                                                'sectionname',
                                                'sectionname as text',
                                                'sections.id'
                                        )
                                        ->get();

                }
                else if(Session::get('currentPortal') == 17 || Session::get('currentPortal') == 6){

                        $sections = DB::table('sectiondetail')
                                ->join('sections',function($join){
                                        $join->on('sectiondetail.sectionid','=','sections.id');
                                        $join->where('sections.deleted',0);
                                })
                                ->where('sectiondetail.syid',$syid)
                                ->where('sectiondetail.deleted',0)
                                ->orderBy('sectionname')
                                ->select(
                                        'levelid',
                                        'sectionname',
                                        'sectionname as text',
                                        'sections.id'
                                )
                                ->get();

                }else{

                        $sections = DB::table('sectiondetail')
                                ->join('sections',function($join){
                                $join->on('sectiondetail.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                                })
                                ->where('sectiondetail.syid',$syid)
                                ->where('sectiondetail.deleted',0)
                                ->orderBy('sectionname')
                                ->select(
                                'levelid',
                                'sectionname',
                                'sectionname as text',
                                'sections.id'
                                )
                                ->get();

                }



                $all_info = array();

                foreach($sections as $item){

                        array_push($all_info,(object)[
                                'levelid'=>$item->levelid,
                                'id'=>$item->id,
                                'sectionname'=>$item->sectionname,
                                'text'=>$item->sectionname,
                        ]);

                }

                return $all_info;

        
        }

        public static function submitBehavior(Request $request){



                DB::table('guidance_behavior')
                ->insert([
                        'studentid'               =>  $request->get('id'),
                        'behavior'                =>  $request->get('behavior'),
                        'cdate'                   =>  $request->get('date'),
                        'card'                    =>  $request->get('card'),
                        'actionrecommended'       =>  $request->get('action'),
                        'createddatetime'         =>  date('Y-m-d H:i:s'),
                        'createdby'               =>  auth()->user()->id
                ]);


        }

        public static function reportTable(Request $request){



                $report = DB::table('guidance_behavior')
                        ->join('studinfo', function($join) {
                                $join->on('studinfo.id', '=', 'guidance_behavior.studentid')
                                ->where('studinfo.deleted', 0);
                        })
                        ->leftJoin('guidance_behavior_notify', function($join) {
                                $join->on('guidance_behavior_notify.headerid', '=', 'guidance_behavior.id')
                                ->where('studinfo.deleted', 0);
                        });

                        if($request->has('search') && $request->get('search') != null)
                        {

                                $report = $report->where(function($query) use($request){
                                        $query->where('studinfo.lastname','like','%'.$request->get('search').'%');
                                        $query->orWhere('studinfo.firstname','like','%'.$request->get('search').'%');
                                });
                        };

                        if ($request->has('date') && $request->get('date') != null) {       
                                $selecteddates = explode(' - ', $request->get('date'));
                                $datefrom = date('Y-m-d', strtotime($selecteddates[0]));
                                $dateto = date('Y-m-d', strtotime($selecteddates[1]));

                                $report = $report->where(function($query) use ($datefrom, $dateto) {
                                        $query->whereBetween('guidance_behavior.cdate', [$datefrom, $dateto]);
                                });
                        }

                        $report =  $report->where('guidance_behavior.deleted', 0)
                        ->select('guidance_behavior.*', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename', 'studinfo.picurl', 'studinfo.gender', 'guidance_behavior_notify.message', 'guidance_behavior_notify.scheddate')
                        ->where('guidance_behavior.resolve', 0)
                        ->whereNull('guidance_behavior_notify.message')
                        ->whereNull('guidance_behavior_notify.scheddate')
                        ->get();



                foreach($report as $item){
                        $item->studname = $item->firstname. ' ' . $item->lastname. ' ' . $item->middlename;
                        $carbonDate = Carbon::parse($item->cdate);
                        $item->search = $item->behavior.' '.$item->card .' '.$item->studname;
                        $item->cdate2 = $carbonDate->format('F j, Y ');

                        if($item->card == 'Green'){

                                $item->color = "#B2FBA5";

                        }else{

                                $item->color = "#FFD1DC";


                        }

                        if(empty($item->picurl)){

                                if($item->gender == "MALE")

                                        $item->picurl = '/assets/images/avatars/male.png';

                                else{

                                        $item->picurl = '/assets/images/avatars/female.png';


                                }
                        }

                }

                return $report;
        }

        public static function reportTable2(Request $request){



                $report = DB::table('guidance_behavior')
                        ->join('studinfo',function($join){
                                $join->on('studinfo.id','=','guidance_behavior.studentid');
                                $join->where('studinfo.deleted',0);
                        })
                        ->join('guidance_behavior_notify',function($join){
                                $join->on('guidance_behavior_notify.headerid','=','guidance_behavior.id');
                                $join->where('guidance_behavior_notify.deleted',0);
                        });

                        if($request->has('search') && $request->get('search') != null)
                        {

                                $report = $report->where(function($query) use($request){
                                        $query->where('studinfo.lastname','like','%'.$request->get('search').'%');
                                        $query->orWhere('studinfo.firstname','like','%'.$request->get('search').'%');
                                });
                        };

                        if ($request->has('date') && $request->get('date') != null) {       
                                $selecteddates = explode(' - ', $request->get('date'));
                                $datefrom = date('Y-m-d', strtotime($selecteddates[0]));
                                $dateto = date('Y-m-d', strtotime($selecteddates[1]));

                                $report = $report->where(function($query) use ($datefrom, $dateto) {
                                        $query->whereBetween('guidance_behavior.cdate', [$datefrom, $dateto]);
                                });
                        }


                        $report = $report->where('guidance_behavior.deleted',0)
                        ->select('guidance_behavior.*', 'studinfo.lastname' , 'studinfo.firstname' , 'studinfo.middlename','studinfo.gender', 'studinfo.picurl' ,  'guidance_behavior_notify.message', 'guidance_behavior_notify.scheddate')
                        ->where('guidance_behavior.resolve',0)
                        ->get();


                foreach($report as $item){
                        $item->studname = $item->firstname. ' ' . $item->lastname. ' ' . $item->middlename;
                        $carbonDate = Carbon::parse($item->scheddate);
                        $carbonDate2 = Carbon::parse($item->updateddatetime);
                        $item->search = $item->behavior.' '.$item->card.' '.$item->studname;
                        $item->cdate2 = $carbonDate->format('F j, Y ');
                        $item->cdate3 = $carbonDate2->format('F j, Y ');

                        if(empty($item->picurl)){

                                if($item->gender == "MALE")

                                        $item->picurl = '/assets/images/avatars/male.png';

                                else{

                                        $item->picurl = '/assets/images/avatars/female.png';


                                }
                        }

                        if($item->card == 'Green'){

                                $item->color = "#B2FBA5";

                        }else{

                                $item->color = "#FFD1DC";


                        }

                }

                return $report;
        }

        public static function reportResolve(Request $request){



                $report = DB::table('guidance_behavior')
                        ->join('studinfo',function($join){
                                $join->on('studinfo.id','=','guidance_behavior.studentid');
                                $join->where('studinfo.deleted',0);
                        })
                        ->leftjoin('guidance_behavior_notify',function($join){
                                $join->on('guidance_behavior_notify.headerid','=','guidance_behavior.id');
                                $join->where('guidance_behavior_notify.deleted',0);
                        });
                        if($request->has('search') && $request->get('search') != null)
                        {

                                $report = $report->where(function($query) use($request){
                                        $query->where('studinfo.lastname','like','%'.$request->get('search').'%');
                                        $query->orWhere('studinfo.firstname','like','%'.$request->get('search').'%');
                                });
                        };

                        if ($request->has('date') && $request->get('date') != null) {       
                                $selecteddates = explode(' - ', $request->get('date'));
                                $datefrom = date('Y-m-d', strtotime($selecteddates[0]));
                                $dateto = date('Y-m-d', strtotime($selecteddates[1]));

                                $report = $report->where(function($query) use ($datefrom, $dateto) {
                                        $query->whereBetween('guidance_behavior.cdate', [$datefrom, $dateto]);
                                });
                        }

                        $report = $report->where('guidance_behavior.deleted',0)
                        ->select('guidance_behavior.*', 'studinfo.lastname' , 'studinfo.firstname' , 'studinfo.middlename','studinfo.gender', 'studinfo.picurl' ,  'guidance_behavior_notify.message', 'guidance_behavior_notify.scheddate')
                        ->where('guidance_behavior.resolve', 1)
                        ->get();


                foreach($report as $item){
                        $item->studname = $item->firstname. ' ' . $item->lastname. ' ' . $item->middlename;
                        $carbonDate = Carbon::parse($item->scheddate);
                        $carbonDate2 = Carbon::parse($item->updateddatetime);
                        $item->search = $item->behavior.' '.$item->card.' '.$item->studname;
                        $item->cdate2 = $carbonDate->format('F j, Y ');
                        $item->cdate3 = $carbonDate2->format('F j, Y ');

                        if(empty($item->picurl)){

                                if($item->gender == "MALE")

                                        $item->picurl = '/assets/images/avatars/male.png';

                                else{

                                        $item->picurl = '/assets/images/avatars/female.png';


                                }
                        }

                        if($item->card =='Green'){

                                $item->color = "#B2FBA5";

                        }else{

                                $item->color = "#FFD1DC";


                        }

                }

                

                return $report;
        }

        public static function resolveReport(Request $request){



                DB::table('guidance_behavior')
                        ->where('id', $request->get('id'))
                        ->update([
                                'resolve'   => $request->get('resolve'),
                                'remark'   =>  $request->get('remarks'),
                                'updateddatetime'         =>  date('Y-m-d H:i:s')
                        ]);
        }

        public static function summonReport(Request $request){


                $checkifexit = Db::table('guidance_behavior_notify')
                                ->where('headerid', $request->get('id'))
                                ->where('parent', $request->get('type'))
                                ->get();


                if(count($checkifexit) > 0){

                        DB::table('guidance_behavior')
                                ->where('headerid', $request->get('id'))
                                ->update([
                                        'scheddate'               =>  $request->get('date'),
                                        'message'                  =>  $request->get('message'),
                                        'updateddatetime'         =>  date('Y-m-d H:i:s'),
                                        'updatedby'               =>  auth()->user()->id
                                ]);




                }else{

                        DB::table('guidance_behavior_notify')
                                ->insert([
                                        'headerid'                =>  $request->get('id'),
                                        'studentid'               =>  $request->get('studid'),
                                        'scheddate'               =>  $request->get('date'),
                                        'message'                  =>  $request->get('message'),
                                        'parent'                    =>  $request->get('type'),
                                        'createddatetime'         =>  date('Y-m-d H:i:s'),
                                        'createdby'               =>  auth()->user()->id
                                ]); 
                }
        }

        public static function getAbsence(Request $request){


                $data = Db::table('studattendance')
                        ->where('studattendance.deleted', 0)
                        ->where('studattendance.absent', 0)
                        ->where('studattendance.syid', 1)
                        ->groupBy('studattendance.studid')
                        ->havingRaw('count(*) > 5')
                        ->join('studinfo',function($join){
                                        $join->on('studinfo.id','=','studattendance.studid');
                                        $join->where('studinfo.deleted',0);
                                });

                        if($request->has('search') && $request->get('search') != null)
                        {

                                $data = $data->where(function($query) use($request){
                                        $query->where('studinfo.lastname','like','%'.$request->get('search').'%');
                                        $query->orWhere('studinfo.firstname','like','%'.$request->get('search').'%');
                                });
                        };
                        $data = $data->select('studattendance.studid', 'studinfo.lastname' , 'studinfo.firstname' , 'studinfo.picurl' , 'studinfo.gender' ,  'studinfo.middlename', DB::raw('count(*) as count'))
                        ->orderByDesc('count')
                        ->get();



                foreach($data as $item){
                        $item->studname = $item->firstname. ' ' . $item->lastname. ' ' . $item->middlename;
                        $item->search = $item->studname;

                        if(empty($item->picurl)){

                                if($item->gender == "MALE")

                                        $item->picurl = '/assets/images/avatars/male.png';

                                else{

                                        $item->picurl = '/assets/images/avatars/female.png';


                                }
                        }

                }



                return $data;
        }

        public static function getabsenceDate(Request $request){


                $data = Db::table('studattendance')
                        ->where('studattendance.deleted', 0)
                        ->where('studattendance.absent', 0)
                        ->where('studattendance.syid', 1)
                        ->where('studattendance.studid',  $request->get('studid'))
                        ->select('studattendance.tdate')
                        ->get();



                foreach($data as $item){
                        $carbonDate = Carbon::parse($item->tdate);
                        $item->date = $carbonDate->format('F j, Y ');
                        $item->search = $item->date;


                }



                return $data;
        }

        public static function studentSelect(Request $request){


                $page = $request->get('page')*10;
                $search = $request->get('search');



                $query = Db::table('studinfo')
                ->select(
                        'studinfo.id as id',
                        DB::raw("CONCAT(studinfo.firstname, ' ', studinfo.middlename, ' ', studinfo.lastname) as text")
                )
                ->where('deleted','0');


                if ($search) {
                        $query->where(function ($query) use ($search) {
                                $query->orWhere('studinfo.firstname', 'LIKE', '%' . $search . '%')
                                ->orWhere('studinfo.middlename', 'LIKE', '%' . $search . '%')
                                ->orWhere('studinfo.lastname', 'LIKE', '%' . $search . '%');
                        });
                        }

                
                $query =$query->take(10)
                ->skip($page)
                ->get();


                $query_count = count($query);



                return @json_encode((object)[
                        "results"=>$query,
                        "pagination"=>(object)[
                                "more"=>$query_count > ($page)  ? true :false
                        ],
                        "count_filtered"=>$query_count
                        ]);
        }



}
