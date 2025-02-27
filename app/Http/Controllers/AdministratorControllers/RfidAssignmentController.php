<?php

namespace App\Http\Controllers\AdministratorControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use File;
use Image;
use Session;
class RfidAssignmentController extends Controller
{
    public function adminstudentrfidassignindex(Request $request)
    {
            
        if($request->has('action'))
        {
            
            $search = $request->get('search');
            $studentstatus = $request->get('studentstatus');
            $search = $search['value'];

            $students = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'sid',
                    'lastname',
                    'firstname',
                    'middlename',
                    'rfid',
                    'picurl',
                    'studisactive'
                    )
                ->where('studinfo.deleted','0')
                ->where(function($join) use($studentstatus){
                    if($studentstatus != null && $studentstatus != ""){
                        $join->where('studisactive',$studentstatus);
                    }
                });

            if($search != null){
                    $students = $students->where(function($query) use($search){
                                      $query->orWhere('firstname','like','%'.$search.'%');
                                      $query->orWhere('lastname','like','%'.$search.'%');
                                      $query->orWhere('sid','like','%'.$search.'%');
                                      $query->orWhere('rfid','like','%'.$search.'%');
                                });
              }
            
            $students = $students->take($request->get('length'))
                ->skip($request->get('start'))
                ->orderBy('lastname','asc')
                ->get();
    
            $studentscount = DB::table('studinfo')
                ->select('studinfo.id','sid','lastname','firstname','middlename','rfid','studentstatus.description as studentstatus','picurl')
                ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                ->where('studinfo.deleted','0')
                ->where(function($join) use($studentstatus){
                    if($studentstatus != null && $studentstatus != ""){
                        $join->where('studisactive',$studentstatus);
                    }
                })
                ->orderBy('lastname','asc');
                
            if($search != null){
                    $studentscount = $studentscount->where(function($query) use($search){
                                    $query->orWhere('firstname','like','%'.$search.'%');
                                    $query->orWhere('lastname','like','%'.$search.'%');
                                    $query->orWhere('sid','like','%'.$search.'%');
                                });
            }
            
            $studentscount = $studentscount->take($request->get('length'))
                ->orderBy('lastname','asc')
                ->count();

            $history = DB::table('rfidcard_renewal')
                        ->join('rfidcard',function($join){
                            $join->on('rfidcard_renewal.rfid','=','rfidcard.id');
                        })
                        ->whereIn('rfidcard_renewal.holderid',collect($students)->pluck('id'))
                        ->where('rfidcard_renewal.holdertype',7)
                        ->where('rfidcard_renewal.deleted',0)
                        ->select(
                            'rfidcard_renewal.*',
                            'rfidcode'
                        )
                        ->get();

            foreach($history as $item){
                $item->regdate = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:mm A');
                if($item->dateexpired != null){
                    $item->expiredate = \Carbon\Carbon::create($item->dateexpired)->isoFormat('MMM DD, YYYY hh:mm A');
                }else{
                    $item->expiredate = 'Not Expired';
                }
            }

            foreach($students as $item){
                $item->history = collect($history)->where('holderid',$item->id)->values();
            }

            // return $students;
                
            return @json_encode((object)[
                'data'=>$students,
                'recordsTotal'=>$studentscount,
                'recordsFiltered'=>$studentscount
            ]);
        }else{
            return view('adminPortal.pages.rfidassignment.student.index');
        }
        // return view('adminPortal.pages.rfidassignment.student.index')
        //     ->with('students',$students);
    }
    public function adminstudentrfidassignuploadphoto(Request $request)
    {

        $session_student = DB::table('studinfo')->where('id', $request->get('studid'))->first();
        $message = [
            'image.required'=>'Student Picture is required',
        ];

        $validator = Validator::make($request->except(['studid', '_token']), [
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


            $studendinfo = DB::table('studinfo')
                        ->where('deleted',0)
                        ->where('id',$session_student->id)
                        ->select('sid','id')
                        ->first();

            $link = DB::table('schoolinfo')
                            ->select('essentiellink')
                            ->first()
                            ->essentiellink;

            if($link == null){
                return array( (object)[
                    'status'=>'0',
                    'message'=>'Error',
                    'errors'=>array(),
                    'inputs'=>$request->all()
                ]);
            }

            $urlFolder = str_replace('http://','',$link);
			$urlFolder = str_replace('https://','',$urlFolder);

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

                $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHmmss');
                $data = $request->image;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                $extension = 'png';
                $destinationPath = public_path('storage/STUDENT/'.$studendinfo->sid.'_'.$date.'.'.$extension);
                $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT/'.$studendinfo->sid.'_'.$date.'.'.$extension;
                file_put_contents($clouddestinationPath, $data);
                file_put_contents($destinationPath, $data);

                DB::table('studinfo')
                        ->where('id',$studendinfo->id)
                        ->take(1)
                        ->update(['picurl'=>'storage/STUDENT/'.$studendinfo->sid.'_'.$date.'.'.$extension ]);

                //$session_student->picurl = 'storage/STUDENT/'.$studendinfo->sid.'_'.$date.'.'.$extension;

                $data = array(
                    (object)
                  [
                    'status'=>'1',
                ]);
    
                return $data;

            }

    }

    public function adminemployeerfidassignuploadphoto(Request $request)
    {


        
        $session_teacher = DB::table('teacher')->where('id', $request->get('empid'))->first();
       

        $message = [
            'image.required'=>'Teacher Picture is required',
        ];

        $validator = Validator::make($request->except(['empid', '_token']), [
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


            $teacherinfo = DB::table('teacher')
                        ->where('deleted',0)
                        ->where('id',$session_teacher->id)
                        ->first();

            $link = DB::table('schoolinfo')
                            ->select('essentiellink')
                            ->first()
                            ->essentiellink;

                                              
            $sy = DB::table('sy')
                    ->where('isactive',1)
                    ->first();

            if($link == null){
                return array( (object)[
                    'status'=>'0',
                    'message'=>'Error',
                    'errors'=>array(),
                    'inputs'=>$request->all()
                ]);
            }

           
            $urlFolder = str_replace('https://','',$link);
            $urlFolder = str_replace('http://','',$urlFolder);

            if (! File::exists(public_path().'employeeprofile/'.$sy->sydesc)) {
                  $path = public_path('employeeprofile/'.$sy->sydesc);
                  if(!File::isDirectory($path)){
                        File::makeDirectory($path, 0777, true, true);
                  }
            }

            if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc)) {
                  $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc;
                  if(!File::isDirectory($cloudpath)){
                        File::makeDirectory($cloudpath, 0777, true, true);
                  }
            }

            $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHmmss');
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $extension = 'png';
            $destinationPath = public_path('employeeprofile/'.$sy->sydesc.'/'.$teacherinfo->tid.'_'.$teacherinfo->lastname.'_'.$date.'.'.$extension);
            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/employeeprofile/'.$sy->sydesc.'/'.$teacherinfo->tid.'_'.$teacherinfo->lastname.'_'.$date.'.'.$extension;
            file_put_contents($clouddestinationPath, $data);
            file_put_contents($destinationPath, $data);

            DB::table('teacher')
                  ->where('id',$teacherinfo->id)
                  ->take(1)
                  ->update(['picurl'=>'employeeprofile/'.$sy->sydesc.'/'.$teacherinfo->tid.'_'.$teacherinfo->lastname.'_'.$date.'.'.$extension ]);

            $data = array((object)
                  [
                        'status'=>'1',
                  ]);
    
                return $data;

            }

    }


    public function adminstudentrfidassignupdate(Request $request)
    {
        if($request->ajax())
        {
            // return $request->all();
            $studentid = $request->get('studentid');
            $rfid = $request->get('rfid');
            $renew = $request->get('renew');
			
             $checkifregistered = DB::table('rfidcard')
                ->where('rfidcode', $rfid)
                ->where('deleted','0')
				->first();

             if($checkifregistered)
             {
				if($checkifregistered->isexpired == 1 && $renew != "renew" ){
					return 3;
				}
				 
                $checkifassigned = DB::table('studinfo')
                    ->where('rfid', $rfid)
                    ->where('deleted','0')
                    ->first();

                $checkifassigned_2 = DB::table('teacher')
                    ->where('rfid', $rfid)
                    ->where('deleted','0')
                    ->first();
					
                if($checkifassigned || $checkifassigned_2)
                {
                    return 2;
                }else{

					$studinfo = DB::table('studinfo')
								->where('id',$studentid)
								->select(
									'rfid',
									'id',
									DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
								)
								->first();
						
                    if($renew == 'renew'){
                        
                        $currentholder =  DB::table('rfidcard')
                                            ->where('rfidcode',$rfid)
                                            ->where('deleted',0)
                                            ->first();

                        if($currentholder->holder != $studentid){
                            return 4;
                        }
                        
                        DB::table('rfidcard')
                            ->where('rfidcode',$rfid)
                            ->update([
                                'holder'=>$studentid,
                                'holdertype'=>7,
                                'holdername'=>$studinfo->studentname,
                                'type'=>'renewal',
                            ]);
                            
                        DB::table('rfidcard_renewal')
                            ->insert([
                                'holderid'=>$studentid,
                                'holdertype'=>7,
                                'type'=>'renewal',
                                'holdername'=>$studinfo->studentname,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'rfid'=>$checkifregistered->id
                            ]);

                        DB::table('rfidcard')
                            ->where('rfidcode',$rfid)
                            ->update([
                                'isexpired'=>0,
                            ]);
                    }else{

                        DB::table('rfidcard')
                            ->where('rfidcode',$rfid)
                            ->update([
                                'holder'=>$studentid,
                                'type'=>7,
                                'holdername'=>$studinfo->studentname,
                                'type'=>'new',
                                'holdertype'=>7,
                            ]);
                            
                        DB::table('rfidcard_renewal')
                            ->insert([
                                'holderid'=>$studentid,
                                'holdertype'=>7,
                                'type'=>'new',
                                'holdername'=>$studinfo->studentname,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'rfid'=>$checkifregistered->id
                            ]);
                    }

                    
                    DB::table('studinfo')
                        ->where('id', $studentid)
                        ->update([
                            'rfid'      => $rfid,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
				
                    return 1;
                }
             }else{
                return 0;
             }
        }
    }

    public function adminstudentrfidassignreset(Request $request)
    {
        if($request->ajax())
        {
            // return $request->all();
            $studentid = $request->get('studentid');

            $studinfo = DB::table('studinfo')
                            ->where('id', $studentid)
                            ->select(
                                'rfid'
                            )
                            ->first();

            DB::table('studinfo')
                ->where('id', $studentid)
                ->take(1)
                ->update([
                    'rfid'      => null,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            $rfidstat =  DB::table('rfidcard')
                            ->where('rfidcode', $studinfo->rfid)
                            ->first();

            DB::table('rfidcard_renewal')
                ->where('holderid', $studentid)
                ->where('holdertype', 7)
                ->where('deleted',0)
                ->whereNull('dateexpired')
                ->update([
                    'deleted'      => 1,
                    'deletedby'     =>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            $isexpired = 0;
            $type = null;
            $holder = null;
            $holdertype = null;
            $holdername = null;
            $dateexpire = null;

            if(isset($rfidstat)){
                if($rfidstat->type == 'renewal'){
                    $isexpired = 1;
                    $type = 'expired';
                    $holdertype = $rfidstat->holdertype;
                    $holdername = $rfidstat->holdername;
                    $dateexpire = $rfidstat->dateexpire;
                    $holder = $rfidstat->holder;
                }
            }


           
            DB::table('rfidcard')
                ->where('rfidcode', $studinfo->rfid)
                ->take(1)
                ->update([
                    'holder'        => $holder,
                    'holdertype'    => $holdertype,
                    'type'          =>  $type,
                    'dateexpire'    => $dateexpire,
                    'holdername'    => $holdername,
                    'isexpired'     =>  $isexpired
                ]);
          
            

        }
    }
    public function adminemployeesetupindex(Request $request)
    {
            
        if($request->has('action'))
        {
            
            $search = $request->get('search');
            $fasstatus = $request->get('fasstatus');
            $search = $search['value'];

            $employees = DB::table('teacher')
                ->select(
                    'teacher.id',
                    'lastname',
                    'firstname',
                    'middlename',
                    'rfid',
                    'picurl',
                    'tid',
                    'isactive'
                )
                ->where('teacher.deleted','0')
                ->where(function($join) use($fasstatus){
                    if($fasstatus != "" && $fasstatus != null){
                        $join->where('teacher.isactive',$fasstatus);
                    }
                });

            if($search != null){
                    $employees = $employees->where(function($query) use($search){
                                      $query->orWhere('firstname','like','%'.$search.'%');
                                      $query->orWhere('lastname','like','%'.$search.'%');
                                      $query->orWhere('tid','like','%'.$search.'%');
                                      $query->orWhere('rfid','like','%'.$search.'%');
                                });
              }
            
            $employees = $employees->take($request->get('length'))
                ->skip($request->get('start'))
                ->orderBy('lastname','asc')
                ->get();
    
            $employeescount = DB::table('teacher')
                    ->select('teacher.id','lastname','firstname','middlename','rfid','picurl','tid')
                    ->where('teacher.deleted','0')
                    ->orderBy('lastname','asc')
                    ->where(function($join) use($fasstatus){
                        if($fasstatus != "" && $fasstatus != null){
                            $join->where('teacher.isactive',$fasstatus);
                        }
                    });
                
            if($search != null){
                    $employeescount = $employeescount->where(function($query) use($search){
                                    $query->orWhere('firstname','like','%'.$search.'%');
                                    $query->orWhere('lastname','like','%'.$search.'%');
                                    $query->orWhere('tid','like','%'.$search.'%');
                                });
            }
            
            $employeescount = $employeescount->take($request->get('length'))
                ->orderBy('lastname','asc')
                ->count();

                
            $history = DB::table('rfidcard_renewal')
                        ->join('rfidcard',function($join){
                            $join->on('rfidcard_renewal.rfid','=','rfidcard.id');
                        })
                        ->whereIn('rfidcard_renewal.holderid',collect($employees)->pluck('id'))
                        ->where('rfidcard_renewal.holdertype',1)
                        ->where('rfidcard_renewal.deleted',0)
                        ->select(
                            'rfidcard_renewal.*',
                            'rfidcode'
                        )
                        ->get();

            foreach($history as $item){
                $item->regdate = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:mm A');
                if($item->dateexpired != null){
                    $item->expiredate = \Carbon\Carbon::create($item->dateexpired)->isoFormat('MMM DD, YYYY hh:mm A');
                }else{
                    $item->expiredate = 'Not Expired';
                }
            }

            foreach($employees as $item){
                $item->history = collect($history)->where('holderid',$item->id)->values();
            }
                
            return @json_encode((object)[
                'data'=>$employees,
                'recordsTotal'=>$employeescount,
                'recordsFiltered'=>$employeescount
          ]);
        }else{
            return view('adminPortal.pages.rfidassignment.employees.index');
        }

    }
    public function  adminemployeesetupupdate(Request $request)
    {
        if($request->ajax())
        {
            $employeeid = $request->get('employeeid');
            $rfid = $request->get('rfid');
            $renew = $request->get('renew');
            $datatype = $request->get('datatype');

            $checkifassigned = DB::table('teacher')
                                ->where('rfid', $rfid)
                                ->where('deleted','0')
                                ->first();

            $checkifassigned_2 = DB::table('studinfo')
                                ->where('rfid', $rfid)
                                ->where('deleted','0')
                                ->first();

            $checkifregistered = DB::table('rfidcard')
                ->where('rfidcode', $rfid)
                ->where('deleted','0')
				->first();

            if(!isset($checkifregistered)){
                return 0;
            }  

            if($checkifregistered->isexpired == 1 && $renew != "renew" ){
                return 3;
            }
    
                if($checkifassigned || $checkifassigned_2)
                {
                    return 2;
                }else{
                    DB::table('teacher')
                        ->where('id', $employeeid)
                        ->update([
                            'rfid'      => $rfid,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
						
					$teacher = DB::table('teacher')
								->where('id',$employeeid)
								->select(
									'rfid',
									'id',
									DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as studentname")
								)
								->first();

                    if($renew == 'renew'){

                        $currentholder =  DB::table('rfidcard')
                                            ->where('rfidcode',$rfid)
                                            ->where('deleted',0)
                                            ->first();

                        if($currentholder->holder != $employeeid){
                            return 4;
                        }

                        DB::table('rfidcard')
                            ->where('rfidcode',$rfid)
                            ->update([
                                'holder'=>$employeeid,
                                'holdertype'=>1,
                                'holdername'=>$teacher->studentname,
                                'type'=>'renewal',
                            ]);
                            
                        DB::table('rfidcard_renewal')
                            ->insert([
                                'holderid'=>$employeeid,
                                'holdertype'=>1,
                                'type'=>'renewal',
                                'holdername'=>$teacher->studentname,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'rfid'=>$checkifregistered->id
                            ]);

                        DB::table('rfidcard')
                            ->where('rfidcode',$rfid)
                            ->update([
                                'isexpired'=>0,
                            ]);
                    }else{

                        DB::table('rfidcard')
                            ->where('rfidcode',$rfid)
                            ->update([
                                'holder'=>$employeeid,
                                'holdername'=>$teacher->studentname,
                                'type'=>'new',
                                'holdertype'=>1,
                            ]);
                            
                        DB::table('rfidcard_renewal')
                            ->insert([
                                'holderid'=>$employeeid,
                                'holdertype'=>1,
                                'type'=>'new',
                                'holdername'=>$teacher->studentname,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'rfid'=>$checkifregistered->id
                            ]);
                    }
						
					
                    return 1;
                }
            // }else{
            //     return 0;
            // }
        }
    }
    public function adminemployeesetupreset(Request $request)
    {
        if($request->ajax())
        {
            // return $request->all();
            $employeeid = $request->get('employeeid');

            $teacherinfo = DB::table('teacher')
                            ->where('id', $employeeid)
                            ->first();

            DB::table('teacher')
                ->where('id', $employeeid)
                ->update([
                    'rfid'      => null,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            $rfidstat =  DB::table('rfidcard')
                            ->where('rfidcode', $teacherinfo->rfid)
                            ->first();

            DB::table('rfidcard_renewal')
                ->where('holderid', $employeeid)
                ->where('holdertype', 1)
                ->where('deleted',0)
                ->whereNull('dateexpired')
                ->update([
                    'deleted'      => 1,
                    'deletedby'     =>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            $isexpired = 0;
            $type = null;
            $holder = null;
            $holdertype = null;
            $holdername = null;
            $dateexpire = null;

            if(isset($rfidstat)){
                if($rfidstat->type == 'renewal'){
                    $isexpired = 1;
                    $type = 'expired';
                    $holdertype = $rfidstat->holdertype;
                    $holdername = $rfidstat->holdername;
                    $dateexpire = $rfidstat->dateexpire;
                    $holder = $rfidstat->holder;
                }
            }


           
            DB::table('rfidcard')
                ->where('rfidcode', $teacherinfo->rfid)
                ->take(1)
                ->update([
                    'holder'        => $holder,
                    'holdertype'    => $holdertype,
                    'type'          =>  $type,
                    'dateexpire'    => $dateexpire,
                    'holdername'    => $holdername,
                    'isexpired'     =>  $isexpired
                ]);
        }
    }
}
