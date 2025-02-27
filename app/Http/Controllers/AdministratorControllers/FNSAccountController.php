<?php

namespace App\Http\Controllers\AdministratorControllers;

use Illuminate\Http\Request;
use DB;
use Hash;
// use Illuminate\Support\Facades\Validator;
use Auth;
use PDO;

class FNSAccountController extends \App\Http\Controllers\Controller
{
    public static function update_active(Request $request){
        $teacherid = $request->get('teacher');
        $status = $request->get('status');

        try{

            DB::table('teacher')
                ->where('id',$teacherid)
                ->where('syncstat','!=',0)
                ->take(1)
                ->update([
                    'syncstat'=>2,
                    'isactive'=>$status,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);


            DB::table('teacher')
                ->where('id',$teacherid)
                ->where('syncstat',0)
                ->take(1)
                ->update([
                    'syncstat'=>0,
                    'isactive'=>$status,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            if($status == 0){
                return array((object)[
                    'status'=>1,
                    'data'=>'Acount Deactivated!',
                ]);
            }else{
                return array((object)[
                    'status'=>1,
                    'data'=>'Account Activated!',
                ]);
            }

        }catch(\Exception $e){
            return self::store_error($e);
        }


    }


    public static function teacher_acadprog(Request $request){

        $syid = $request->get('syid');
        $teacherid = $request->get('teacherid');

        $teacheracadprog = DB::table('teacheracadprog')
                                ->where('syid',$syid)
                                ->where('teacherid',$teacherid)
                                ->where('deleted',0)
                                ->get();

        return $teacheracadprog;

    }


    public static function update_fas_priv_ajax(Request $request){
        $userid = $request->get('userid');
        $usertype = $request->get('usertype');
        $status = $request->get('status');
        if (!Auth::user()){
            $updateuserid = $request->get('updateuserid');
        }else{
            $updateuserid = auth()->user()->id;
        }
        return self::update_fas_priv($userid,$usertype,$status,$updateuserid);
    }

    public static function update_fas_principal_ajax(Request $request){
        $acadprog = $request->get('acadprog');
        $teacher = $request->get('teacher');
        $status = $request->get('status');
        return self::update_fas_principal($acadprog,$teacher,$status);
    }

    public static function update_fas_acadprog_ajax(Request $request){

        try{
            $teacherid = $request->get('teacherid');
            $acadprog = $request->get('acadprog');
            $syid = $request->get('syid');
            $usertype = $request->get('usertype');

            if($acadprog == null || $acadprog == ""){
                  $acadprog = array();
            }
            
            $sectiondetail = [];
            
            if($usertype == 1){
                  $sectiondetail = DB::table('sectiondetail')
                                          ->join('sections',function($join){
                                                $join->on('sectiondetail.sectionid','=','sections.id');
                                                $join->where('sections.deleted',0);
                                          })
                                          ->join('gradelevel',function($join){
                                                $join->on('sections.levelid','=','gradelevel.id');
                                                $join->where('gradelevel.deleted',0);
                                          })
                                          ->where('sectiondetail.teacherid',$teacherid)
                                          ->where('sectiondetail.syid',$syid)
                                          ->where('sectiondetail.deleted',0)
                                          ->select('acadprogid')
                                          ->get();
            }

            

            //remove no acad
            DB::table('teacheracadprog')
                  ->whereNotIn('acadprogid',$acadprog)
                  ->whereNotIn('acadprogid',collect($sectiondetail)->pluck('acadprogid'))
                  ->where('syid',$syid)
                  ->where('deleted',0)
                  ->where('syncstat','!=',0)
                  ->where('acadprogutype',$usertype)
                  ->where('teacherid',$teacherid)
                  ->update([
                        'syncstat'=>3,
                        'deleted'=>1,
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('teacheracadprog')
                  ->whereNotIn('acadprogid',$acadprog)
                  ->whereNotIn('acadprogid',collect($sectiondetail)->pluck('acadprogid'))
                  ->where('syid',$syid)
                  ->where('deleted',0)
                  ->where('syncstat',0)
                  ->where('acadprogutype',$usertype)
                  ->where('teacherid',$teacherid)
                  ->update([
                        'syncstat'=>0,
                        'deleted'=>1,
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

            $get_all_acad = DB::table('teacheracadprog')
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->where('acadprogutype',$usertype)
                              ->where('teacherid',$teacherid)
                              ->get();

            foreach($acadprog as $item){
                  $check  = collect($get_all_acad)->where('acadprogid',$item)->count();
                  if($check == 0){
                        DB::table('teacheracadprog')
                              ->insert([
                                    'syncstat'=>0,
                                    'acadprogid'=>$item,
                                    'teacherid'=>$teacherid,
                                    'deleted'=>0,
                                    'syid'=>$syid,
                                    'acadprogutype'=>$usertype,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
                  if($usertype == 2){

                        DB::table('academicprogram')
                              ->where('id',$item)
                              ->update([
                                    'principalid'=>$teacherid,
                                    'updatedby'=>$usertype,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }
            }

            return array((object)[
                  'status'=>1,
                  'data'=>'Academic Program Updated!'
            ]);

      }catch(\Exception $e){
            return self::store_error($e);
      }

    }
    

    

    public static function list(Request $request){

        $teacherid = $request->get('teacherid');
        $search = $request->get('search');
        $search = $search['value'];
        $status = $request->get('status');
        
      
        $temp_faspriv_search = array();
        if($search != null){
            $temp_faspriv_search = DB::table('faspriv')
                                    ->join('usertype',function($join) use($search){
                                        $join->on('faspriv.usertype','=','usertype.id');
                                        $join->where('utype','like','%'.$search.'%');
                                    })
                                    ->where('faspriv.deleted',0)
                                    ->select(
                                        'faspriv.userid'
                                    )
                                    ->get();
        }

        $teacher = DB::table('teacher');

        if($teacherid != null){
            $teacher = $teacher->where('teacher.id',$teacherid);
        }

        if($status != ""){
            $teacher = $teacher->where('teacher.isactive', $status);
        }

        $teacher = $teacher->leftJoin('usertype',function($join){
                                    $join->on('teacher.usertypeid','=','usertype.id');
                                })
                                ->where(function($query) use($search,$temp_faspriv_search){
                                    if($search != null){
                                        $query->orWhere('firstname','like','%'.$search.'%');
                                        $query->orWhere('lastname','like','%'.$search.'%');
                                        $query->orWhere('tid','like','%'.$search.'%');
                                        $query->orWhere('utype','like','%'.$search.'%');
                                        $query->orWhereIn('userid',collect($temp_faspriv_search)->pluck('userid'));
                                    }
                                });

        if($request->get('length') != ""){
            $teacher = $teacher->take($request->get('length'));
        }
        if($request->get('length') != ""){
            $teacher = $teacher->skip($request->get('start'));
        }
                               
                                
        $teacher = $teacher->where('teacher.deleted',0)
                                ->select(
                                    'teacher.acadtitle',
                                    'teacher.title',
                                    'teacher.id',
                                    'teacher.userid',
                                    'teacher.lastname',
                                    'teacher.firstname',
                                    'teacher.middlename',
                                    'teacher.suffix',
                                    'teacher.isactive',
                                    'teacher.tid',
                                    'teacher.picurl',
                                    'teacher.usertypeid',
                                    'with_acad',
                                    'utype',
                                    'refid'
                                )
                                ->get();

        $teacher_count = DB::table('teacher');

        if($teacherid != null){
            $teacher_count = $teacher_count->where('teacher.id',$teacherid);
        }

        if($status != ""){
            $teacher_count = $teacher_count->where('teacher.isactive', $status);
        }

        $teacher_count = $teacher_count->leftJoin('usertype',function($join){
                                    $join->on('teacher.usertypeid','=','usertype.id');
                                })
                                ->where(function($query) use($search,$temp_faspriv_search){
                                    if($search != null){
                                        $query->orWhere('firstname','like','%'.$search.'%');
                                        $query->orWhere('lastname','like','%'.$search.'%');
                                        $query->orWhere('tid','like','%'.$search.'%');
                                        $query->orWhere('utype','like','%'.$search.'%');
                                        $query->orWhereIn('userid',collect($temp_faspriv_search)->pluck('userid'));
                                    }
                                })
                                ->where('teacher.deleted',0)
                                ->select('id')
                                ->count();

        // $syid = DB::table('sy')->where('isactive',1)->first();

        $temp_users = DB::table('users')
                        ->whereIN('email',collect($teacher)->pluck('tid'))
                        ->join('usertype',function($join){
                            $join->on('users.type','=','usertype.id');
                        })
                        ->select(
                            'utype',
                            'email',
                            'passwordstr',
                            'type',
                            'isDefault',
                            'password'
                        )
                        ->get();

        $temp_faspriv = DB::table('faspriv')
                            ->whereIn('faspriv.userid',collect($teacher)->pluck('userid'))
                            ->join('usertype',function($join){
                                $join->on('faspriv.usertype','=','usertype.id');
                            })
                            ->where('faspriv.deleted',0)
                            ->select(
                                'faspriv.usertype',
                                'privelege',
                                'utype',
                                'userid',
                                'with_acad'
                            )
                            ->get();

        
        $academicprog = DB::table('teacheracadprog')
                            ->where('teacheracadprog.deleted',0)
                            ->whereIn('teacherid',collect($teacher)->pluck('id'))
                            ->join('academicprogram',function($join){
                                $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                            })
                            ->select('acadprogcode','academicprogram.id','teacherid','syid','acadprogutype')
                            ->distinct('acadprogid')
                            ->get();

                            
        $dean = DB::table('teacherdean')
                            ->where('teacherdean.deleted',0)
                            ->join('college_colleges',function($join){
                                $join->on('teacherdean.collegeid','=','college_colleges.id');
                                $join->where('college_colleges.deleted',0);
                            })
                            ->whereIn('teacherid',collect($teacher)->pluck('id'))
                            ->select(
                                'syid',
                                'collegeid',
                                'teacherid',
                                'collegeabrv'
                            )
                            ->get();

        $phead = DB::table('teacherprogramhead')
                            ->join('college_courses',function($join){
                                $join->on('teacherprogramhead.courseid','=','college_courses.id');
                                $join->where('college_courses.deleted',0);
                            })
                            ->where('teacherprogramhead.deleted',0)
                            ->whereIn('teacherid',collect($teacher)->pluck('id'))
                            ->select(
                                'teacherprogramhead.syid',
                                'teacherprogramhead.courseid',
                                'teacherprogramhead.teacherid',
                                'college_courses.courseabrv'
                            )
                            ->get();

        foreach($teacher as $item){

            $userinfo = collect($temp_users)->where('email',$item->tid)->values();
			
			if(count($userinfo) > 0){
				foreach($userinfo as $userinfo_item){
					if(!Hash::check('123456', $userinfo_item->password)){
						$userinfo_item->isDefault = 0;
					}
				}
			}

            $item->user = $userinfo;

            $faspriv = collect($temp_faspriv)->where('userid',$item->userid)->values();

            $item->faspriv = $faspriv; 

            $item->acad = collect($academicprog)->where('teacherid',$item->id)->values();
            $item->colleges = collect($dean)->where('teacherid',$item->id)->values();
            $item->courses = collect($phead)->where('teacherid',$item->id)->values();

            $item->actiontaken = null;
            $middlename = explode(" ",$item->middlename);
            $temp_middle = '';
            if($middlename != null){
                foreach ($middlename as $middlename_item) {
                    if(strlen($middlename_item) > 0){
                        $temp_middle .= $middlename_item[0].'.';
                    } 
                }
            }

            $temp_acadtitle = '';
            if($item->acadtitle != null){
                $temp_acadtitle = ', '.$item->acadtitle;
            }

            $item->fullname = $item->title.' '.$item->firstname.' '.$temp_middle.' '.$item->lastname.' '.$item->suffix.$temp_acadtitle;

        }


        return @json_encode((object)[
            'data'=>$teacher,
            'recordsTotal'=>$teacher_count,
            'recordsFiltered'=>$teacher_count
        ]);

        return $teacher;

    }

    
    public static function update_college_dean(Request $request){

        $collegeid = $request->get('collegeid');
        $teacher = $request->get('teacher');
        $status = $request->get('status');
        $syid = $request->get('syid');

        try{


            if($status == 1){

                $check = DB::table('teacherdean')
                                          ->where('syid',$syid)
                                          ->where('collegeid',$collegeid)
                                          ->where('deleted',0)
                                          ->where('teacherid',$teacher)
                                          ->count();

                if($check == 0){

                    DB::table('teacherdean')
                        ->insert([
                            'semid'=>1,
                            'syid'=>$syid,
                            'collegeid'=>$collegeid,
                            'teacherid'=>$teacher,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                }

            }else{

                DB::table('teacherdean')
                    ->where('syid',$syid)
                    ->where('collegeid',$collegeid)
                    ->where('teacherid',$teacher)
                    ->where('deleted',0)
                    ->update([
                        'deleted'=>1,
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }


            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully!',
            ]);


        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function update_course_chairperson(Request $request){

        $couresid = $request->get('courseid');
        $teacher = $request->get('teacher');
        $status = $request->get('status');
        $syid = $request->get('syid');

        try{

            if($status == 1){

                $check = DB::table('teacherprogramhead')
                                          ->where('syid',$syid)
                                          ->where('courseid',$couresid)
                                          ->where('deleted',0)
                                          ->where('teacherid',$teacher)
                                          ->count();

                if($check == 0){

                    DB::table('teacherprogramhead')
                        ->insert([
                            'semid'=>1,
                            'syid'=>$syid,
                            'courseid'=>$couresid,
                            'teacherid'=>$teacher,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                }

            }else{

                DB::table('teacherprogramhead')
                    ->where('syid',$syid)
                    ->where('courseid',$couresid)
                    ->where('teacherid',$teacher)
                    ->where('deleted',0)
                    ->update([
                        'deleted'=>1,
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }


            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully!',
            ]);


        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function update_fas_principal($acadprog,$teacher,$status){


        try{


            if($status == 1){

                DB::table('academicprogram')
                        ->where('id',$acadprog)
                        ->take(1)
                        ->update([
                            'principalid'=>$teacher,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);

            }else{

                DB::table('academicprogram')
                        ->where('id',$acadprog)
                        ->where('principalid',$teacher)
                        ->take(1)
                        ->update([
                            'principalid'=>null,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);

            }


            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully!',
            ]);


        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function create_fas_account(Request $request){

        try{

            $usertypeinfo = DB::table('usertype')
                                ->where('id',$request->get('utype'))
                                ->first();

            $refid = null;

            if (!Auth::user()){
                $userid = $request->get('userid');
            }else{
                $userid = auth()->user()->id;
            }

            if(isset($usertypeinfo->refid)){
                $refid = $usertypeinfo->refid;
            }

            $sy = DB::table('sy')->where('isactive',1)->first();
            $sydesc = explode('-',$sy->sydesc);

            $teacherid = DB::table('teacher')
                            ->insertGetId([
                                'lastname'=>$request->get('lname'),
                                'middlename'=>$request->get('mname'),
                                'firstname'=>$request->get('fname'),
                                'title'=>$request->get('title'),
                                'acadtitle'=>$request->get('acadtitle'),
                                'suffix'=>$request->get('suffix'),
                                'licno'=>$request->get('lcn'),
                                'createdby'=>$userid,
                                'usertypeid'=> $request->get('utype'),
                                'deleted'=>0,
                                'isactive'=>1,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'syncstat'=>0
                            ]);


            $teacheremail = $sydesc[0].sprintf('%04d', $teacherid);

            $userId = DB::table('users')->insertGetId([
                                'email'=> $teacheremail,
                                'type'=> $request->get('utype'),
                                'password'=>Hash::make('123456'),
                                'name'=>$request->get('lname').', '.$request->get('fname')
                            ]);

            DB::table('teacher')
                        ->where('id',$teacherid)
                        ->take(1)
                        ->update([
                            'userid'=>$userId,
                            'tid'=>$teacheremail,
                            'updatedby'=>$userid,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);
            
            DB::table('employee_personalinfo')->insert([
                            'employeeid' => $teacherid,
                            'dob'=> $request->get('bdate'),
                            'gender'=> $request->get('gender'),
                            'nationalityid'=> $request->get('national'),
                            'maritalstatusid'=> $request->get('marital'),
                            'contactnum'=> $request->get('mobile'),
                            'email'=> $request->get('email'),
                            'address'=>$request->get('address'),
                            'createdby'=>$userid,
                            'created_on' => \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d'),
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }

        return array((object)[
            'status'=>1,
            'data'=>'Created Successfully!'
        ]);
        
    }

    public static function update_fas_priv($userid = null, $usertype = null, $status = null, $updateuserid = null){

        try{

            if($status == 1){
                
                $check =  DB::table('faspriv')
                            ->where('userid',$userid)
                            ->where('usertype',$usertype)
                            ->where('deleted',0)
                            ->count();

                if($check == 0){

                    DB::table('faspriv')
                            ->insert([
                                'userid'=>$userid,
                                'usertype'=>$usertype,
                                'createdby'=>$updateuserid,
                                'privelege'=>2,
                                'syncstat'=>0,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                }

            }else{

                DB::table('faspriv')
                        ->where('userid',$userid)
                        ->where('usertype',$usertype)
                        ->where('deleted',0)
                        ->where('syncstat','!=',0)
                        ->take(1)
                        ->update([
                            'syncstat'=>2,
                            'deleted'=>1,
                            'syncstat'=>3,
                            'deletedby'=>$updateuserid,
                            'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                DB::table('faspriv')
                        ->where('userid',$userid)
                        ->where('usertype',$usertype)
                        ->where('deleted',0)
                        ->where('syncstat',0)
                        ->take(1)
                        ->update([
                            'syncstat'=>2,
                            'deleted'=>1,
                            'syncstat'=>0,
                            'deletedby'=>$updateuserid,
                            'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                $teacherid = DB::table('teacher')
                                ->where('userid',$userid)
                                ->first()->id;
                    
                $syid = DB::table('sy')->where('isactive',1)->first();

            }

            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully!',
            ]);


        }catch(\Exception $e){
            return self::store_error($e);
        }

    }


    public static function update_fas_acadprog($teacher,$acadprog = null,$status = null,$userId,$syid = null,$usertype = null){

        $check = DB::table('sy')->where('id',$syid)->first();

		if(isset($check->ended)){
			 if($check->ended == 1){
				return array((object)[
					'status'=>2,
					'data'=>'S.Y. already ended!',
				]);
			}
		}
       
        try{

            if($status == 1){
                
                $check =  DB::table('teacheracadprog')
                            ->where('teacherid',$teacher)
                            ->where('acadprogid',$acadprog)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->count();

                if($check == 0){

                    DB::table('teacheracadprog')
                            ->insert([
                                'teacherid'=>$teacher,
                                'acadprogid'=>$acadprog,
                                'syid'=>$syid,
                                'deleted'=>0,
                                'syncstat'=>0,
                                'createdby'=>$userId,
                                'acadprogutype'=>$usertype,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                }

            }else{

                DB::table('teacheracadprog')
                        ->where('teacherid',$teacher)
                        ->where('acadprogid',$acadprog)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->take(1)
                        ->update([
                            'syncstat'=>2,
                            'deleted'=>1,
                            'syncstat'=>3,
                            'deletedby'=>$userId,
                            'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            }

            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully!',
            ]);


        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function update_account_info(Request $request){

        if (!Auth::user()){
            $updateuserid = $request->get('updateuserid');
        }else{
            $updateuserid = auth()->user()->id;
        }
        
        $teacher = $request->get('teacher');

        $userid = DB::table('teacher')
                        ->where('id',$teacher)
                        ->select('userid')
                        ->first()
                        ->userid;

        DB::table('users')
            ->where('id',$userid)
            ->take(1)
            ->update([
                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                'updatedby'=>$updateuserid,
                'name'=>$request->get('fname').' '.$request->get('lname'),
                'type'=>$request->get('utype')
            ]);
    }

    public static function update_fas_info(Request $request){

        try{

            if($request->get('acad') == ''){
                $acadprog = $request->get('acad');
            }else{
                $acadprog = array();
            }
            
            $teacher = $request->get('teacher');

            if (!Auth::user()){
                $updateuserid = $request->get('updateuserid');
            }else{
                $updateuserid = auth()->user()->id;
            }

            $check_syncstat =  DB::table('teacher')
                                ->where('id',$teacher)
                                ->select('syncstat')
                                ->first();

            $syncstat = 0;

            if($check_syncstat->syncstat != 0){
                $syncstat = 2;
            }

            DB::table('teacher')
                ->where('id',$teacher)
                ->where('deleted',0)
                ->take(1)
                ->update([
                    'syncstat'=>$syncstat,
                    'lastname'=>$request->get('lname'),
                    'middlename'=>$request->get('mname'),
                    'firstname'=>$request->get('fname'),
                    'title'=>$request->get('title'),
                    'suffix'=>$request->get('suffix'),
                    'licno'=>$request->get('lcn'),
                    'usertypeid'=> $request->get('utype'),
                    'acadtitle'=>$request->get('acadtitle'),
                    'deleted'=>0,
                    'isactive'=>1,
                    'updatedby'=>$updateuserid,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            $employeeid = 0;

            $employeeid = DB::table('employee_personalinfo')
                ->where('employeeid',$teacher)
                ->where('deleted', 0)
                ->select('employeeid')
                ->count();
            
                if($employeeid != 0){
                    DB::table('employee_personalinfo')
                            ->where('employeeid',$teacher)
                            ->where('deleted',0)
                            ->take(1)
                            ->update([
                                'dob'=>$request->get('bdate'),
                                'gender'=>$request->get('gender'),
                                'nationalityid'=>$request->get('national'),
                                'maritalstatusid'=>$request->get('marital'),
                                'contactnum'=>$request->get('mobile'),
                                'email'=>$request->get('email'),
                                'address'=>$request->get('address'),
                                'deleted'=>0,
                                'updatedby'=>$updateuserid,
                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                }else{
                    DB::table('employee_personalinfo')->insert([
                        'employeeid' => $teacher,
                        'dob'=> $request->get('bdate'),
                        'gender'=> $request->get('gender'),
                        'nationalityid'=> $request->get('national'),
                        'maritalstatusid'=> $request->get('marital'),
                        'contactnum'=> $request->get('mobile'),
                        'email'=> $request->get('email'),
                        'address'=>$request->get('address'),
                        'createdby'=>$updateuserid,
                        'created_on' => \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d'),
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    ]);
                }

            $userid = DB::table('teacher')
                        ->where('id',$teacher)
                        ->select('userid')
                        ->first()
                        ->userid;

            $check_syncstat =  DB::table('users')
                                ->where('id',$userid)
                                ->select('syncstat')
                                ->first();

            $syncstat = 0;

            if( $check_syncstat->syncstat != 0){
                $syncstat = 2;
            }

            DB::table('users')
                ->where('id',$userid)
                ->take(1)
                ->update([
                    'syncstat'=>$syncstat,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'updatedby'=>$updateuserid,
                    'name'=>$request->get('fname').' '.$request->get('lname'),
                    'type'=>$request->get('utype')
                ]);

            $usertype = $request->get('utype');

            $check_faspriv = DB::table('faspriv')
                                ->where('userid',$userid)
                                ->where('deleted',0)
                                ->whereIn('usertype',[1,2,3])
                                ->count();

            $check_faspriv = DB::table('faspriv')
                                ->where('userid',$userid)
                                ->where('deleted',0)
                                ->whereIn('usertype',[18])
                                ->count();
            
            $check_faspriv = DB::table('faspriv')
                                ->where('userid',$userid)
                                ->where('deleted',0)
                                ->where('usertype',$usertype)
                                ->count();

            if($check_faspriv > 0){
            
                DB::table('faspriv')
                        ->where('userid',$userid)
                        ->where('usertype',$usertype)
                        ->where('deleted',0)
                        ->where('syncstat','!=',0)
                        ->update([
                            'syncstat'=>2,
                            'deleted'=>1,
                            'syncstat'=>3,
                            'deletedby'=>$updateuserid,
                            'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                DB::table('faspriv')
                        ->where('userid',$userid)
                        ->where('usertype',$usertype)
                        ->where('deleted',0)
                        ->where('syncstat',0)
                        ->update([
                            'syncstat'=>0,
                            'deleted'=>1,
                            'syncstat'=>3,
                            'deletedby'=>$updateuserid,
                            'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            }
        
            return array((object)[
                'status'=>1,
                'data'=>'Updated Successfully!',
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function change_password(Request $request){

        $type = $request->get('type');
        $tid = $request->get('tid');

        try{
            DB::enableQueryLog();
            DB::table('users')
                ->where('email', $tid)
                ->update([
                   //'syncstat'=>2,
                    'updated_at'=>\Carbon\Carbon::now('Asia/Manila'),
                    'isDefault' => 1,
                    'password'      => Hash::make('123456'),
                    //'updatedby'=>auth()->user()->id,
                    //'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                ]);
            DB::disableQueryLog();
            $logs = json_encode(DB::getQueryLog());
            
            DB::table('updatelogs')
                ->insert([
                    'type'=>1,
                    'sql'=> $logs,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'message'=>'Account Reset'
            ]);
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function remove_sched(Request $request){


        try{

            $teacher = $request->get('teacher');

            $check_sched = DB::table('assignsubjdetail')
                                ->where('teacherid',$teacher)
                                ->where('deleted',0)
                                ->count();

            if($check_sched > 0){
                return array((object)[
                    'status'=>0,
                    'message'=>'Please remove GS and HS schedule'
                ]);
            }

            $check_sched = DB::table('sh_classsched')
                            ->where('teacherid',$teacher)
                            ->where('deleted',0)
                            ->count();

            if($check_sched > 0){
                return array((object)[
                    'status'=>0,
                    'message'=>'Please remove SHS schedule'
                ]);
            }

            $check_sched = DB::table('college_classsched')
                            ->where('teacherID',$teacher)
                            ->where('deleted',0)
                            ->count();

            if($check_sched > 0){
                return array((object)[
                    'status'=>0,
                    'message'=>'Please remove College schedule'
                ]);
            }

            $teacherinfo = DB::table('teacher')
                                ->where('id',$teacher)
                                ->first();


            //remove users
            DB::table('users')
                ->where('email','like','%'.$teacherinfo->tid.'%')
                ->where('deleted',0)
                ->where('syncstat','!=',0)
                ->take(1)
                ->update([
                    'syncstat'=>3,
                    'deleted'=>1
                ]);

            DB::table('users')
                ->where('email','like','%'.$teacherinfo->tid.'%')
                ->where('deleted',0)
                ->where('syncstat',0)
                ->take(1)
                ->update([
                    'syncstat'=>0,
                    'deleted'=>1
                ]);
            //remove users

            //remove academic program
            DB::table('teacheracadprog')
                ->where('teacherid',$teacher)
                ->where('deleted',0)
                ->where('syncstat','!=',0)
                ->take(1)
                ->update([
                    'syncstat'=>3,
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('teacheracadprog')
                ->where('teacherid',$teacher)
                ->where('deleted',0)
                ->where('syncstat',0)
                ->take(1)
                ->update([
                    'syncstat'=>0,
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
            //remove academic program

            //remove privilege
            DB::table('faspriv')
                ->where('userid',$teacherinfo->userid)
                ->where('syncstat','!=',0)
                ->where('deleted',0)
                ->update([
                    'deleted'=>1,
                    'syncstat'=>3,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('faspriv')
                ->where('userid',$teacherinfo->userid)
                ->where('deleted',0)
                ->where('syncstat',0)
                ->update([
                    'deleted'=>1,
                    'syncstat'=>0,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
            //remove privilege

            //remove teacher
            DB::table('teacher')
                ->where('id',$teacher)
                ->where('deleted',0)
                ->where('syncstat','!=',0)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'syncstat'=>3,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('teacher')
                ->where('id',$teacher)
                ->where('deleted',0)
                ->where('syncstat',0)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'syncstat'=>0,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
            //remove teacher

            return array((object)[
                'status'=>1,
                'message'=>'Account Removed!'
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }



    }

    public static function store_error($e){

        DB::table('zerrorlogs')
                ->insert([
                    'error'=>$e,
                    // 'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

        return array((object)[
              'status'=>0,
              'data'=>'Something went wrong!'
        ]);
    }

    public static function fas_account(){

        $fas_account = DB::table('teacher')
                            ->where('teacher.deleted',0)
                            ->leftJoin('users',function($join){
                                $join->on('teacher.userid','=','users.id');
                            })        
                            ->select(
                                'email',
                                'name',
                                'firstname',
                                'lastname',
                                'middlename',
                                'tid'
                            )
                            ->get();

        return $fas_account;

    }


    public static function sync_delete(Request $request){

        try{

            $tablename = $request->get('tablename');
            $data = $request->get('data');


              DB::table($tablename)
                    ->take(1)
                    ->where('id',$data['id'])
                    ->update([
                        'deleted'=>1,
                        'deleteddatetime'=>$data['deleteddatetime']
                    ]);

              return array((object)[
                    'status'=>1,
                    'data'=>'Updated Successfully!'
              ]);
              
        }catch(\Exception $e){
              return self::store_error($e);
        }
        
    }

    public static function sync_update(Request $request){

        try{

            $tablename = $request->get('tablename');
            $data = $request->get('data');

              $dataid = $data['id'];
              unset($data['id']);
              
              

                if($tablename == 'users'){

                    unset($data['password']);
                    unset($data['passwordstr']);
                    unset($data['isdefault']);
                    unset($data['loggedIn']);
                    unset($data['dateLoggedIn']);

                    DB::table($tablename)
                        ->take(1)
                        ->where('id',$dataid)
                        ->update($data);
                }else{
                    DB::table($tablename)
                            ->take(1)
                            ->where('id',$dataid)
                            ->update($data);
                }

              return array((object)[
                    'status'=>1,
                    'data'=>'Updated Successfully!'
              ]);
              
        }catch(\Exception $e){
              return self::store_error($e);
        }
        
    }

    public static function sync_insert(Request $request){

        try{

            $tablename = $request->get('tablename');
            $data = $request->get('data');

                if($tablename == 'users'){

                    $check = DB::table('users')
                                ->where('id',$data['id'])
                                ->first();

                    if(isset($check->id)){
                        if($check->email != $data['email']){
                            DB::table('users')
                                ->insert([
                                    'name'=>$check->name,
                                    'email'=>$check->email,
                                    'type'=>$check->type,
                                    'deleted'=>$check->name,
                                    'password'=>$check->password,
                                    'passwordstr'=>$check->passwordstr,
                                    'remember_token'=>$check->remember_token,
                                    'isDefault'=>$check->isDefault,
                                ]);
                                
                            DB::table('users')
                                ->where('id',$check->id)
                                ->update([
                                    'name'=> $data['name'],
                                    'email'=>$data['email'],
                                    'type'=>$data['type'],
                                    'deleted'=>0,
                                    'password'=>Hash::make('123456'),
                                    'remember_token'=>null,
                                    'isDefault'=>1,
                                ]);
                        }
                    }else{
                        DB::table($tablename)   
                            ->insert($data);
                    }

                }else{
                    DB::table($tablename)
                        ->insert($data);
                }

            return array((object)[
                    'status'=>1,
                    'data'=>'Added Successfully!'
            ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }
        
    }

    // public static function check_user_accounts(){

    //     $teachers = DB::table('teachers')
    //                     ->where('deleted',0)
    //                     ->get();


    //     foreach($teachers as $item){
            
    //         $check_if_exist = DB::table('users')
    //                             ->where('id',$item->userid)
    //                             ->first();

    //         if(isset($check_if_exist->id)){



    //         }else{

    //         }




    //     }




    // }

    // public static function update_online_information(Request $request){

    //     $teachers = DB::table('teacher')
    //                     ->where('deleted',0)
    //                     ->where('syncstat',0)
    //                     ->get();


    //     return $teachers;
        

        
       


    // }

    // get new created information
    public static function get_new_info(Request $request){

        $tablename = $request->get('tablename');

        $table_date = DB::table($tablename)
                        ->where('syncstat',0)
                        ->get();

        return $table_date;

    }


     // get updated information
    public static function get_updated_info(Request $request){

        $tablename = $request->get('tablename');

        $table_date = DB::table($tablename)
                        ->where('syncstat',2)
                        ->get();

        return $table_date;

    }

     // get deleted information
    public static function get_deleted_info(Request $request){

        $tablename = $request->get('tablename');

        $table_date = DB::table($tablename)
                        ->where('syncstat',3)
                        ->get();
        return $table_date;
    }

    public static function get_updatestat(Request $request){

        $tablename = $request->get('tablename');
        $data = $request->get('data');

        DB::table($tablename)
                        ->where('id', $data['id'])
                        ->take(1)
                        ->update([
                            'syncstat'=>1,
                            'syncstatdate'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

    }

    // fetch from table teacher & employee_personalinfo
    public static function get_more_info(Request $request){

        $more_info = DB::table('teacher')
                ->join('employee_personalinfo', function ($join) use($request) {
                    $join->on('teacher.id', '=', 'employee_personalinfo.employeeid')
                        ->where('employee_personalinfo.employeeid', $request->get('id'))
                        ->where('employee_personalinfo.deleted', 0);
                    })
                ->get();
        return $more_info;            
    }

    // new - 0
    // processed - 1
    // updated - 2
    // deleted - 3
    // no account - 4

}
