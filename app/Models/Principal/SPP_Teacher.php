<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Principal\SPP_SHClassSchedule;
use App\Models\Principal\SPP_Blocks;
use App\Models\Principal\Section;
use App\Models\Principal\SPP_AcademicProg;
use App\Models\Principal\SPP_Privelege;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Session;
use Crypt;

class SPP_Teacher extends Model
{




    public static function getTeacherAcadProgIfPrincipal($teacherid){

        return DB::table('teacher')
                    ->join('academicprogram','teacher.id','=','academicprogram.principalid')
                    ->where('teacher.deleted','0')
                    ->where('teacher.id',$teacherid)
                    ->select('academicprogram.*')
                    ->get();

    }

    public static function getTeacherAcadProgIfTeacher($teacherid){

        return DB::table('teacher')
                    ->join('teacheracadprog',function($join){
                        $join->on('teacher.id','=','teacheracadprog.teacherid');
                        $join->where('teacheracadprog.deleted','0');
                        $join->whereIn('teacheracadprog.syid',function($query){
                            $query->select('id')->from('sy')->where('sy.isactive','1');
                        });
                    })
                    ->join('academicprogram','teacheracadprog.acadprogid','=','academicprogram.id')
                    ->where('teacher.deleted','0')
                    ->where('teacher.id',$teacherid)
                    ->select('academicprogram.*')
                    ->get();
    }

    public static function getAllTeacher(){

        return DB::table('teacher')
                    ->leftJoin('users',function($join){
                        $join->on('teacher.userid','=','users.id');
                    })
                    ->select('teacher.*')
                    ->whereIn('users.type',['1',null])
                    ->where('teacher.deleted','0')
                    // ->where('teacher.isactive','1')
                    ->get();

    }

    public static function getAllTeacherSHSSubject($teacherid){

        return SPP_SHClassSchedule::getAllTeacherSHSSubject($teacherid);

    }

    public static function getTeacherBlockSubjects($teacherid){

        return SPP_Blocks::getTeacherBlockSubjects($teacherid);

    }

    public static function getAllTeacherSHSSchedule($teacherid){

        return SPP_SHClassSchedule::getAllTeacherSHSSchedule($teacherid);

    }

    public static function getAllTeacherBlockSchedule($teacherid){

        return SPP_Blocks::getAllTeacherBlockSchedule($teacherid);

    }

    public static function teacherStoreAnnouncement(){

    }


    public static function getVacantTeacher($exceptTeacher = null){

        
        $teacher = self::filterTeacherFaculty(null,null,null,'1',null);

        $teachers = $teacher[0]->data;

        foreach($teachers as $key=>$item){

            $teacherisnotavailable=null;
            
            if($item->id!=$exceptTeacher){

                $teacherisnotavailable = Section::getSectionByTeacher($item->id);
            }
            
            if($teacherisnotavailable != null){
                if(count($teacherisnotavailable)>0){
                    unset($teachers[$key]);
                }
            }
            

        }
        
        return $teachers;
        

    }

    public static function filterTeacherFaculty(
        $skip = null, 
        $take = null, 
        $teacherid = null, 
        $usertype = null, 
        $teachername = null,
        $acadprogid = null,
        $type = 'all',
        $isactive = null,
        $acadprogutype = []
    ){

        $data = array();
        
        $teachers = DB::table('teacher')->where('teacher.deleted',0);
        
        $teachers = $teachers->leftJoin('faspriv',function($join){
                        $join->on('teacher.userid','=','faspriv.userid');
                        $join->where('teacher.usertypeid','!=',1);
                        $join->where('faspriv.deleted',0);
        });
        
        if(auth()->user()->type == 2){
            
            $teachers = $teachers->where(function($query){
                $query->where('teacher.usertypeid','1');
                // $query->orWhere('usertypeid','2');
                $query->orWhere('usertype','1');
            });
          
        }else{
            if(Session::has('prinInfo')){
    
                if(Session::get('prinInfo')->refid){
                    
                    $teachers = $teachers->where(function($query){
                        $query->where('usertypeid','1');
                        $query->orWhere('usertypeid','2');
                        $query->orWhere('usertype','1');
                    });
                }

            }

        }

        $teachers->when('teacher.usertypeid == 1',function($query){

            return  $query->leftJoin('teacheracadprog',function($join){
                        $join->on('teacher.id','=','teacheracadprog.teacherid');
                        $join->where('teacheracadprog.deleted','0');
                        $join->join('academicprogram',function($join){
                            $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                        });
                       
                    });

        });

        $teachers->where( function($query) use($acadprogutype) {
            if(isset($acadprogutype) && is_array($acadprogutype) && count($acadprogutype) > 0){
                $query->whereIn('teacheracadprog.acadprogutype',$acadprogutype);
            }
        });


        if(auth()->user()->type == 2){

            if(Session::has('schoolYear')){

                $teachers->join('sy',function($join) {
                                    $join->on('sy.id','=','teacheracadprog.syid');
                                    $join->where('sy.id',Session::get('schoolYear')->id);
                                });
                
            }
            else{

                $teachers->join('sy',function($join){
                        $join->on('sy.id','=','teacheracadprog.syid');
                        $join->where('sy.isactive','1');
                    });

            }   

        }   
   
 
               
        if($teacherid != null){
            
            $teachers->where('teacher.id',$teacherid);
        }

        if($usertype != null){

            $teachers->where('teacher.usertypeid',$usertype);

        }

        $teachers->join('users','teacher.userid','=','users.id');

        $teachers->join('usertype','teacher.usertypeid','=','usertype.id');
        
       
        if($acadprogid != null){

            $teachers->where('teacheracadprog.acadprogid',$acadprogid);

        }

        else{

            if(auth()->user()->type == 2){

                $acadprog = array();
    
                foreach(Session::get('principalInfo') as $item){
                    array_push($acadprog,$item->acadid);
                }
    
                $teachers->whereIn('teacheracadprog.acadprogid',$acadprog);
    
            }
  

        }

        $teachers->where('teacher.deleted','0');

        if($isactive != null){

            $teachers->where('teacher.isactive','1');

        }
        
        if($teachername != null){

            $teachers->where(function($query) use($teachername){
                $query->where('teacher.firstname','like','%'.$teachername.'%');
                $query->orWhere('teacher.lastname','like','%'.$teachername.'%');
                $query->orWhere('usertype.utype','like','%'.$teachername.'%');
                $query->orWhere('usertype.utype','like','%'.$teachername.'%');
                $query->orWhere('academicprogram.progname','like','%'.$teachername.'%');
            });
        }
        

        $teachers->orderBy('lastname','asc');
        
        if($type == 'all'){

            $teachers->select(
                'teacher.*',
                'users.email',
                'usertype.utype',
                'teacheracadprog.acadprogutype'
            );

        }
        else if($type == 'basic'){

            $teachers->select(
                'teacher.lastname',
                'teacher.firstname',
                'teacher.usertypeid',
                'usertype.utype',
                'teacher.tid',
                'teacher.id',
                'teacher.picurl',
                'teacher.userid'
            
            );

        }
        else if($type == 'vacant'){

            $teachers->leftJoin('sections',function($join){
                $join->on('teacher.id','=','sections.teacherid');
                $join->where('sections.deleted','0');
            });

            $teachers->where('sections.id',null)->select('sections.id');

            $teachers->select(
                'teacher.lastname',
                'teacher.firstname',
                'teacher.usertypeid',
                'usertype.utype',
                'teacher.tid',
                'teacher.id',
                'teacher.picurl',
                'teacher.userid'
            );

        }

        // return $teachers->get();

        $count = $teachers->distinct()->get()->count();
     

        if($take!=null){

            $teachers->take($take);

        }

        if($skip!=null){

            $teachers->skip(($skip-1)*$take);
        }

        // return $teachers->get();

        $teacher = $teachers->distinct()->get();

        // if(auth()->user()->type == 2){

        //     foreach($allteacher as $key=>$item){

        //         if($item->usertypeid != 1){

        //             $checkWithPriv = DB::table('faspriv')
        //                                 ->where('userid',$item->userid)
        //                                 ->where('usertype',1)
        //                                 ->count();

        //             if($checkWithPriv == 0){

        //                 unset($allteacher[$key]);

        //             }

        //         }

                
        //     }

        //     $count = count($teacher);
            
        // }
            
    
        
        array_push($data,(object)['data'=>$teacher,'count'=>$count]);

        return $data;                                            



    }             
    
    public static function validateForm($data){

        if($data['ut']  == '2' || $data['ut']=='1' || $data['ut']=='8' || $data['ut']=='3'){
            $qrequired = 'Academic program is required';
        }
        else if($data['ut']  == '16'){
            $qrequired = 'Course is required';
        }
        else{
            $qrequired = '';
        }

        $message = [
            'fn.required'=>'First name is required',
            'mn.required'=>'Middle name is required',
            'ln.required'=>'Last name is required',
            'ut.required'=>'User type is required',
            'q.required'=>$qrequired
        ];
       
        $validator = Validator::make($data, [
            'fn' => 'required',
            'mn' => 'required',
            'ln' => 'required',
            'ut' => 'required',
            'q' => Rule::requiredIf($data['ut']=='2'||$data['ut']=='1'||$data['ut']=='8'||$data['ut']=='3')
        ], $message);

        return $validator;

    }

    public static function updateFAS(
        $tid = null,
        $firstname=null,
        $middlename=null,
        $lastname=null,
        $lcn = null,
        $usertype=null,
        $principalassignment=null

    ){

        $data = [
            'ui'=>$tid,
            'fn'=>$firstname,
            'mn'=>$middlename,
            'ln'=>$lastname,
            'lcn'=>$lcn,
            'ut'=>$usertype,
            'q'=>$principalassignment
        ];

        $validator = self::validateForm($data); 

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput()->with('update','update');

        }
        else{

            $teacherInfo = self::filterTeacherFaculty(null,null,$tid);

            $activeSy = DB::table('sy')->where('isactive','1')->first();

            DB::table('teacher')
                ->join('users',function($join){
                    $join->on('teacher.userid','=','users.id');
                })
                ->where('teacher.id',$tid)
                ->update([
                    'teacher.firstname'=>strtoupper($firstname),
                    'teacher.lastname'=>strtoupper($lastname),
                    'teacher.middlename'=>strtoupper($middlename),
                    'teacher.licno'=>$lcn,
                    'teacher.usertypeid'=>$usertype,
                    'teacher.editedby'=>auth()->user()->id,
                    'users.name'=>strtoupper($firstname).' '.strtoupper($lastname),
                    'users.type'=>$usertype,
                    'teacher.updatedby'=>auth()->user()->id,
                    'teacher.updateddatetime'=>Carbon::now('Asia/Manila')
                ]);

            //when removed from principal

            if( $teacherInfo[0]->data[0]->usertypeid == 2 && $usertype != 2){

                DB::table('academicprogram')
                        ->where('principalid',$teacherInfo[0]->data[0]->id)
                        ->update([
                            'principalid'=>'0',
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>Carbon::now('Asia/Manila')
                        ]);

                DB::table('teacheracadprog')
                        ->where('teacherid',$tid)
                        ->where('syid',$activeSy->id)
                        ->update([
                            'deleted'=>'1',
                            'deletedby'=>auth()->user()->id,
                            'deleteddatetime'=>Carbon::now('Asia/Manila')
                            ]);

                SPP_Privelege::updatepriv(null,null,$teacherInfo[0]->data[0]->userid,1);

            }

            if( $teacherInfo[0]->data[0]->usertypeid == 1 && $usertype != 1){

                DB::table('teacheracadprog')
                        ->where('teacherid',$teacherInfo[0]->data[0]->id)
                        ->update([
                            'deleted'=>'0',
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>Carbon::now('Asia/Manila')
                        ]);

                SPP_Privelege::updatepriv(null,null,$teacherInfo[0]->data[0]->userid,1);

            }



            if($usertype=='2'){

                SPP_Privelege::storePriveleg(
                    Crypt::encrypt($teacherInfo[0]->data[0]->userid),
                    Crypt::encrypt(1),
                    Crypt::encrypt(2)
                );


                $principalacadprog = DB::table('academicprogram')->get();

                foreach($principalacadprog  as $item){

                    $given = false;
                    foreach($principalassignment as $userPorgId){

                        if($item->id == $userPorgId){
                            $given = true;
                        }

                    }
                    if($given){


                        DB::table('teacheracadprog')
                                ->join('sy',function($join){
                                    $join->on('teacheracadprog.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->where('teacherid',$item->principalid)
                                ->where('acadprogid',$item->id)
                                ->update([
                                    'deleted'=>'1',
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>Carbon::now('Asia/Manila')
                                ]);

                        DB::table('academicprogram')
                                ->where('id',$item->id)
                                ->update([
                                    'principalid'=>$tid,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>Carbon::now('Asia/Manila')
                                ]);

                       
                    }
                    else{
                        if($item->principalid == $tid){
                            DB::table('academicprogram')
                                ->where('id',$item->id)
                                ->update([
                                    'principalid'=>'0',
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>Carbon::now('Asia/Manila')
                                ]);
                        }
                    }
                }
            }

            if($usertype=='1' || $usertype=='2' || $usertype=='3' || $usertype=='8'){

                DB::table('teacheracadprog')
                            ->join('sy',function($join){
                                $join->on('teacheracadprog.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->where('teacherid',$tid)
                            ->update([
                                'deleted'=>'1',
                                'deletedby'=>auth()->user()->id,
                                'deleteddatetime'=>Carbon::now('Asia/Manila')
                            ]);

                if($principalassignment != null){

                    foreach($principalassignment  as $item){

                        $checkAcadProg = DB::table('teacheracadprog')
                                ->join('sy',function($join){
                                    $join->on('teacheracadprog.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->where('teacherid',$tid)
                                ->where('acadprogid',$item)
                                ->count();

                        if($checkAcadProg==1){

                            $checkAcadProg = DB::table('teacheracadprog')
                                    ->join('sy',function($join){
                                        $join->on('teacheracadprog.syid','=','sy.id');
                                        $join->where('sy.isactive','1');
                                    })
                                    ->where('teacherid',$tid)
                                    ->where('acadprogid',$item)
                                    ->update([
                                        'deleted'=>'0',
                                        'deletedby'=>auth()->user()->id,
                                        'deleteddatetime'=>Carbon::now('Asia/Manila')
                                    ]);
                        }

                        if($checkAcadProg==0){

                            $currentShoolYear = DB::table('sy')->where('isactive','1')->first();

                            // DB::table('teacheracadprog')
                            //     ->insert([
                            //         'teacherid'=> $tid,
                            //         'syid'=>$currentShoolYear->id,
                            //         'acadprogid'=>$item,
                            //         'deleted'=>'0',
                            //         'createdby'=>auth()->user()->id,
                            //         'createddatetime'=>Carbon::now('Asia/Manila')
                            //     ]);

                            DB::table('teacheracadprog')
                                ->updateOrInsert(
                                    [
                                        'teacherid'=> $tid,
                                        'syid'=>$currentShoolYear->id,
                                        'acadprogid'=>$item,
                                    ],
                                    [
                                        'deleted'=>'0',
                                        'createdby'=>auth()->user()->id,
                                        'createddatetime'=>Carbon::now('Asia/Manila'),
                                        'updatedby'=>auth()->user()->id,
                                        'updateddatetime'=>Carbon::now('Asia/Manila'),
                                    ]
                                );
                        }


                    }
                }

            }

            if($usertype == '16'){

                DB::table('college_courses')
                        ->where('courseChairman',$teacherInfo[0]->data[0]->id)
                        ->update([
                            'courseChairman'=>0
                        ]);

                // foreach($principalassignment as $item){
                //     DB::table('college_courses')
                //     ->where('id',$item)
                //     ->update([
                //         'courseChairman'=>$teacherInfo[0]->data[0]->id
                //     ]);
                   
                // }
            }

            if($usertype=='18' || $usertype=='14' || $usertype=='16'){

                // DB::table('teacheracadprog')
                //         ->insert([
                //             'teacherid'=> $tid,
                //             'syid'=>$activeSy->id,
                //             'acadprogid'=>'6',
                //             'deleted'=>'0',
                //             'createdby'=>auth()->user()->id,
                //             'createddatetime'=>Carbon::now('Asia/Manila')
                //         ]);

                DB::table('teacheracadprog')
                        ->updateOrInsert(
                            [
                                'teacherid'=> $tid,
                                'syid'=>$activeSy->id,
                                'acadprogid'=>'6',
                            ],
                            [
                                'deleted'=>'0',
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>Carbon::now('Asia/Manila'),
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>Carbon::now('Asia/Manila'),
                            ]
                        );


            }

            $principals = DB::table('teacher')->where('deleted','0')->where('usertypeid','2')->get();

            foreach($principals as $item ){

                $acadprog = SPP_AcademicProg::getPrincipalAcadProg($item->id);
                $currentShoolYear = DB::table('sy')->where('isactive','1')->first();

                if(count($acadprog) == 0){

                    DB::table('teacher')
                            ->where('id',$item->id)
                            ->update([
                                'usertypeid'=>'1',
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>Carbon::now('Asia/Manila')
                            ]);

                    DB::table('users')
                            ->where('id',$item->userid)
                            ->update(['type'=>'1']);


                     DB::table('teacheracadprog')
                                ->where('teacherid',$item->id)
                                ->where('syid',$currentShoolYear->id)
                                ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>Carbon::now('Asia/Manila')
                                ]);  
                        
                    DB::table('faspriv')->where('userid',$item->userid)
                            ->update([
                                'deleted'=>1,
                                'deletedby'=>auth()->user()->id,
                                'deleteddatetime'=>Carbon::now('Asia/Manila')
                            ]);  

                }

            }
            

            toast('Successful!','success')->autoClose(2000)->toToast($position = 'top-right');
            return back();

        }
        
    }

    public static function createFAS(
        $tid = null,
        $firstname=null,
        $middlename=null,
        $lastname=null,
        $lcn = null,
        $usertype=null,
        $principalassignment=null
    ){

        $data = [
            'ui'=>$tid,
            'fn'=>$firstname,
            'mn'=>$middlename,
            'ln'=>$lastname,
            'lcn'=>$lcn,
            'ut'=>$usertype,
            'q'=>$principalassignment
        ];

        $validator = self::validateForm($data); 

        if ($validator->fails()) {

            toast('Invalid Inputs','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }
        else{

      
          
            $teacherId = DB::table('teacher')->insertGetId([
                    'firstname'     =>strtoupper($firstname),
                    'lastname'      =>strtoupper($lastname),
                    'middlename'    =>strtoupper($middlename),
                    'licno'         =>$lcn,
                    'usertypeid'    =>$usertype,
                    'deleted'       =>'0',
                    'isactive'      =>'1'
                ]);

            $teacheremail = Carbon::now()->isoFormat('YYYY').sprintf('%04d', $teacherId);

            $userId = DB::table('users')->insertGetId([
                            'email'=> $teacheremail,
                            'type'=> $usertype,
                            'password'=>Hash::make('123456'),
                            'name'=>strtoupper($lastname).', '.strtoupper($firstname)
                        ]);

            $currentShoolYear = DB::table('sy')->where('isactive','1')->first();
            $picurl = 'storage/'.$currentShoolYear->sydesc.'/FSA/'.$teacheremail.'.jpg';

            DB::table('teacher')
                ->where('id',$teacherId)
                ->update([
                    'tid'=> $teacheremail ,
                    'picurl'=>$picurl,
                    'userid'=>$userId,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>Carbon::now('Asia/Manila')
                ]);

            if($usertype=='2'){

                $principalacadprog = DB::table('academicprogram')->get();

                foreach($principalacadprog  as $item){

                    $given = false;

                    foreach($principalassignment as $userPorgId){

                        if($item->id == $userPorgId){

                            $given = true;

                        }
                    }

                    if($given){

                        DB::table('academicprogram')
                                ->where('id',$item->id)
                                ->update([
                                    'principalid'=>$teacherId,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>Carbon::now('Asia/Manila')
                                ]);

                        $currentShoolYear = DB::table('sy')->where('isactive','1')->first();

                        DB::table('teacheracadprog')
                                ->updateOrInsert(
                                    [
                                        'teacherid'=> $teacherId,
                                    'syid'=>$currentShoolYear->id,
                                    'acadprogid'=>$item->id,
                                    ],
                                    [
                                        'deleted'=>'0',
                                        'createdby'=>auth()->user()->id,
                                        'createddatetime'=>Carbon::now('Asia/Manila'),
                                        'updatedby'=>auth()->user()->id,
                                        'updateddatetime'=>Carbon::now('Asia/Manila'),
                                    ]
                                );

                    }
                    else{

                        if($item->principalid == $teacherId){
                            DB::table('academicprogram')
                                ->where('id',$item->id)
                                ->update([
                                    'principalid'=>'0',
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>Carbon::now('Asia/Manila')
                                ]);
                        }
                    }

                }

                SPP_Privelege::storePriveleg(
                    Crypt::encrypt($userId),
                    Crypt::encrypt(1),
                    Crypt::encrypt(2)
                    
                );

            }


            $isAssPrincipal = false;

            $usertyperef = DB::table('usertype')->where('id',$usertype)->first();

            if(isset($usertyperef->refid)){

                if($usertyperef->refid == 20){

                    $isAssPrincipal = true;

                }

            }

            if($usertype=='1' || $usertype=='3' || $isAssPrincipal || $usertype=='8' ){


                foreach($principalassignment  as $item){

                    DB::table('teacheracadprog')
                            ->updateOrInsert(
                                [
                                    'teacherid'=> $teacherId,
                                    'syid'=>$currentShoolYear->id,
                                    'acadprogid'=>$item,
                                ],
                                [
                                    'deleted'=>'0',
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>Carbon::now('Asia/Manila'),
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>Carbon::now('Asia/Manila'),
                                ]
                            );

                }

            }

            $isAssPrincipal = false;

            

            if($usertype=='18' || $usertype=='14' || $usertype=='16'){

                DB::table('teacheracadprog')
                        ->updateOrInsert(
                            [
                                'teacherid'=> $teacherId,
                                'syid'=>$currentShoolYear->id,
                                'acadprogid'=>'6'
                            ],
                            [
                                'deleted'=>'0',
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>Carbon::now('Asia/Manila'),
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>Carbon::now('Asia/Manila'),
                            ]
                        );


            }


            if($usertype == '16'){

                DB::table('college_courses')
                        ->where('courseChairman', $teacherId)
                        ->update([
                            'courseChairman'=>0
                        ]);

                foreach($principalassignment as $item){
                    DB::table('college_courses')
                    ->where('id',$item)
                    ->update([
                        'courseChairman'=>$teacherId
                    ]);
                   
                }
            }

            toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
            return back();

        }

    }

    public static function removeFAS(
        $tid = null,
        $pass = null
    ){
        
        $data = [
            'rid'=>$tid,
            'ps'=>$pass

        ];

        $message = [
            'ps.required'=>'Password is required'
        ];
       
        $validator = Validator::make($data, [
            'ps' => 'required'
        ], $message);

        $validator->after(function($validator) use ($pass) {

            $check = auth()->validate([
                'email'    => auth()->user()->email,
                'password' => $pass
            ]);
    
            if (!$check):
                $validator->errors()->add('wrongpasss', 
                    'Incorrect password, please try again.');
            endif;
        });


        if ($validator->fails()) {


            toast('Deletion Failed','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput()->with('deleteerror','deleteerror');

        }
        else{

            $teacherInfo = DB::table('teacher')->where('id',$tid)->first();

            DB::table('teacher')
                    ->where('id',$tid)
                    ->update([
                        'deleted'=>'1',
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>Carbon::now('Asia/Manila')
                    ]);
            DB::table('users')
                    ->where('id',$teacherInfo->userid)
                    ->update([
                        'deleted'=>'1',
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>Carbon::now('Asia/Manila')
                    ]);

            toast('Deletion is successfull','success')->autoClose(2000)->toToast($position = 'top-right');
            return back();
        }
        
    }

    public static function getTeacherInfo(
        $teacherid = null,
        $teachersubjects = false,
        $teachersubjectswithsection = false,
        $teacherclasssched = false,
        $gradessubmitted = false,
        $teacherInfo = false
    ){

        if($teacherid == null){
            return back();
        }

        if($teacherInfo){

            $teacherinfo = DB::table('teacher')
                    ->where('teacher.id',$teacherid)
                    ->leftJoin('sectiondetail',function($join){
                        $join->on('teacher.id','=','sectiondetail.teacherid');
                        $join->join('sections',function($join){
                            $join->on('sectiondetail.sectionid','=','sections.id');
                            $join->where('sections.deleted','0');
                        });
                        $join->join('gradelevel',function($join){
                            $join->on('sections.levelid','=','gradelevel.id');
                        });
                    });

                    if(Session::has('schoolYear')){
                  
                        $teacherinfo->leftJoin('sy',function($join){
                            $join->on('sectiondetail.syid','=','sy.id');
                            $join->where('sy.id',Session::get('schoolYear')->id);
                        });

                    }
                    else{
                     
                        $teacherinfo->leftJoin('sy',function($join){
                            $join->on('sectiondetail.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        });
                    }

            return $teacherinfo->get();
        }
             
        $classsched = null;
        $shclasssched = null;
        $blocksched = null;

     

        if($teachersubjects ){

            $classsched = DB::table('teacher')
                            ->join('assignsubjdetail',function($join){
                                $join->on('teacher.id','=','assignsubjdetail.teacherid');
                                $join->where('assignsubjdetail.deleted','0');
                            })
                            ->join('assignsubj',function($assjoin){
                                $assjoin->on('assignsubjdetail.headerid','=','assignsubj.ID');
                                $assjoin->where('assignsubj.deleted','0');
                            })
                            ->join('subjects',function($join){
                                $join->on('assignsubjdetail.subjid','=','subjects.id');
                                $join->where('subjects.deleted','0');
                            })
                            

                            ->where('teacher.id',$teacherid);

            $shclasssched = DB::table('teacher')
                            ->join('sh_classsched',function($join){
                                $join->where('sh_classsched.deleted','0');
                                $join->on('teacher.id','=','sh_classsched.teacherid');
                            })
                            ->join('sh_subjects as shsubjects',function($join){
                                $join->on('sh_classsched.subjid','=','shsubjects.id');
                                $join->where('shsubjects.deleted','0');
                            })
                            ->where('teacher.id',$teacherid);

            

            $blocksched = DB::table('teacher')
                            ->leftJoin('sh_blocksched',function($join){
                                $join->where('sh_blocksched.deleted','0');
                                $join->on('teacher.id','=','sh_blocksched.teacherid');
                               
                            })
                            ->join('sh_subjects as shblocksubjects',function($join){
                                $join->on('sh_blocksched.subjid','=','shblocksubjects.id');
                                $join->where('shblocksubjects.deleted','0');
                            })
                            ->join('sh_sectionblockassignment',function($join){
                                $join->on('sh_blocksched.blockid','=','sh_sectionblockassignment.blockid');
                                $join->where('sh_sectionblockassignment.deleted','0');
                            })
                            ->select('sh_sectionblockassignment.sectionid')
                            ->where('teacher.id',$teacherid);
                          


            if(Session::has('schoolYear')){

                $classsched->join('sy',function($join) {
                    $join->on('sy.id','=','assignsubj.syid');
                    $join->where('sy.id',Session::get('schoolYear')->id);
                });
                    
                $shclasssched->join('sy',function($join) {
                    $join->on('sy.id','=','sh_classsched.syid');
                    $join->where('sy.id',Session::get('schoolYear')->id);
                });

                $blocksched->join('sy',function($join) {
                    $join->on('sy.id','=','sh_blocksched.syid');
                    $join->where('sy.id',Session::get('schoolYear')->id);
                });
                
            }
            else{

                $classsched->join('sy',function($join){
                        $join->on('sy.id','=','assignsubj.syid');
                        $join->where('sy.isactive','1');
                    });

                $shclasssched->join('sy',function($join){
                    $join->on('sy.id','=','sh_classsched.syid');
                    $join->where('sy.isactive','1');
                });

                $blocksched->join('sy',function($join){
                    $join->on('sy.id','=','sh_blocksched.syid');
                    $join->where('sy.isactive','1');
                });

            }  

            if(Session::has('semester')){

                $shclasssched->join('semester',function($join) {
                    $join->on('sh_classsched.semid','=','semester.id');
                    $join->where('semester.id',Session::get('semester')->id);
                });

                $blocksched->join('semester',function($join){
                    $join->on('sh_blocksched.semid','=','semester.id');
                    $join->where('semester.id',Session::get('semester')->id);
                });
            
            }
            else{

                $shclasssched->join('semester',function($join){
                    $join->on('sh_classsched.semid','=','semester.id');
                    $join->where('semester.isactive','1');
                });

                $blocksched->join('semester',function($join){
                    $join->on('sh_blocksched.semid','=','semester.id');
                    $join->where('semester.isactive','1');
                });

            }

        }

      

        if($teachersubjectswithsection){
      
            $classsched->join('sections',function($join){
                        $join->on('assignsubj.sectionid','=','sections.id');
                        $join->where('sections.deleted','0');
                    })
                    ->join('gradelevel',function($join){
                        $join->on('sections.levelid','=','gradelevel.id');
                    });

            $shclasssched->join('sections as shsections',function($join){
                    $join->on('sh_classsched.sectionid','=','shsections.id');
                    $join->where('shsections.deleted','0');
                })
                ->join('gradelevel as shgradelevel',function($join){
                    $join->on('shsections.levelid','=','shgradelevel.id');
                });

            $blocksched->leftJoin('sections as shblocksections',function($join){
                            $join->on('sh_sectionblockassignment.sectionid','=','shblocksections.id');
                            $join->where('shblocksections.deleted','0');
                        })
                        ->join('gradelevel as shblockgradelevel',function($join){
                                $join->on('shblocksections.levelid','=','shblockgradelevel.id');
                        });

            
           

        }

     

        if($gradessubmitted){

            $classsched->join('grades',function($join){
                $join->on('assignsubj.sectionid','=','grades.sectionid');
                $join->on('assignsubjdetail.subjid','=','grades.subjid');
            });

            $shclasssched->join('grades as shgrades',function($join){
                $join->on('sh_classsched.sectionid','=','shgrades.sectionid');
                $join->on('sh_classsched.subjid','=','shgrades.subjid');
            });

            $blocksched->join('grades as shblockgrades',function($join){
                $join->on('sh_sectionblockassignment.sectionid','=','shblockgrades.sectionid');
                $join->on('sh_blocksched.subjid','=','shblockgrades.subjid');
            });

        }


  
       

        if($teacherclasssched){

         
         
            $classsched->join('classsched',function($join){
                $join->on('assignsubj.sectionid','=','classsched.sectionid');
                $join->on('assignsubjdetail.subjid','=','classsched.subjid');
                $join->where('classsched.deleted','0');
            })
            ->join('classscheddetail',function($join){
                $join->on('classsched.id','=','classscheddetail.headerid');
                $join->where('classscheddetail.deleted','0');
            })
            ->join('days',function($join){
                $join->on('classscheddetail.days','=','days.id');
            })
            ->join('rooms',function($join){
                $join->on('classscheddetail.roomid','=','rooms.id');
            });

            $shclasssched->leftJoin('sh_classscheddetail',function($join){
                $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                $join->where('sh_classscheddetail.deleted','=','0');
            })
            ->join('days as shdays',function($join){
                $join->on('sh_classscheddetail.day','=','shdays.id');
            })
            ->whereIn('sh_classsched.syid',function($query){
                $query->select('sy.id')->from('sy')->where('sy.isactive','1');
            })
            ->join('rooms as shrooms',function($join){
                $join->on('sh_classscheddetail.roomid','=','shrooms.id');
            });


         

           
            $blocksched->leftJoin('sh_blockscheddetail',function($join){
                $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                $join->where('sh_blockscheddetail.deleted','=','0');
            })
            ->join('days as shblockdays',function($join){
                $join->on('sh_blockscheddetail.day','=','shblockdays.id');
            })
            ->join('rooms as shblockrooms',function($join){
                $join->on('sh_blockscheddetail.roomid','=','shblockrooms.id');
            });

            
            

           

          

        }

        if($teachersubjects ){

            $classsched->select(
                'assignsubjdetail.subjid',
                'subjects.subjcode',
                'subjects.subjdesc'
               
            );   

            $shclasssched->select(
                'sh_classsched.subjid as subjid',
                'shsubjects.subjtitle as subjdesc',
                'shsubjects.subjcode as subjcode'
            );    

            $blocksched->select(
                'sh_blocksched.subjid as subjid',
                'shblocksubjects.subjtitle as subjdesc',
                'shblocksubjects.subjcode as subjcode'
            ); 
            
        }

        if($teachersubjectswithsection){


            $classsched->addSelect(
                'sections.sectionname',
                'assignsubj.sectionid',
                'gradelevel.levelname',
                'sections.levelid'
            );  
    
            $shclasssched->addSelect(
                'shsections.sectionname',
                'shsections.levelid',
                'sh_classsched.sectionid',
                'shgradelevel.levelname'
               
            );    
    
            $blocksched->addSelect(
                'shblocksections.sectionname',
                'shblockgradelevel.levelname',
                'sh_sectionblockassignment.sectionid',
                'shblocksections.levelid'
            );  

        }

        if($teacherclasssched){

            $classsched->addSelect(
                'classscheddetail.stime as stime',
                'classscheddetail.etime as etime',
                'days.description as description',
                'rooms.roomname as roomname',
                'classscheddetail.days as days',
                'classscheddetail.classification'
            );   
    
            $shclasssched->addSelect(
                'sh_classscheddetail.stime as etime',
                'sh_classscheddetail.etime as stime',
                'shdays.description as description',
                'shrooms.roomname as roomname',
                'sh_classscheddetail.day as days',
                'sh_classscheddetail.classification'
            );    
    
            $blocksched->addSelect(
                'sh_blockscheddetail.stime as etime',
                'sh_blockscheddetail.etime as stime',
                'shblockdays.description as description',
                'shblockrooms.roomname as roomname',
                'sh_blockscheddetail.day as days',
                'sh_blockscheddetail.classification'
            );  

        }

        if($gradessubmitted){

             $classsched->addSelect(
                'grades.quarter as quarter',
                'grades.date_submitted as date_submitted',
                'grades.submitted as submitted',
                'grades.status as status',
                'grades.id as gradeid'
            );   
    
            $shclasssched->addSelect(
                'shgrades.quarter as quarter',
                'shgrades.date_submitted as date_submitted',
                'shgrades.submitted as submitted',
                'shgrades.status as status',
                'shgrades.id as gradeid'
            );    
    
            $blocksched->addSelect(
                'shblockgrades.quarter as quarter',
                'shblockgrades.date_submitted as date_submitted',
                'shblockgrades.submitted as submitted',
                'shblockgrades.status as status',
                'shblockgrades.id as gradeid'
            );  
             
         }

        $data = array();



        foreach($classsched->get() as $item){

            array_push($data,$item);

        }
        foreach($shclasssched->get() as $item){

            array_push($data,$item);

        }

        foreach($blocksched->get() as $item){

            array_push($data,$item);

        }

        if($teacherclasssched){

            $data = Collect($data)->sortBy('days');

        }
        
        return $data;
    
    }

    public static function principalGetTeachers(){

        $teacher = DB::table('teacher')->where('userid',auth()->user()->id)->first();

        $acadprogid = DB::table('academicprogram')->where('principalid',$teacher->id)->get();

        $acad = array();

        foreach( $acadprogid as $item){

            array_push($acad,$item->id);

        }


        $teachers = DB::table('teacheracadprog')
                        ->join('sy',function($join){
                            $join->on('teacheracadprog.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('teacher',function($join){
                            $join->on('teacher.id','teacheracadprog.teacherid');
                            $join->where('teacher.deleted','0');
                            $join->where('teacher.isactive','1');
                        })
                        ->whereIn('acadprogid',$acad)
                        ->where('teacheracadprog.deleted',0)
                        ->select('teacher.*')
                        ->orderby('lastname')
                        ->distinct()
                        ->get();

        foreach($teachers as $key=>$item){

            if($item->usertypeid != 1){

                $checkWithPriv = DB::table('faspriv')
                                    ->where('userid',$item->userid)
                                    ->where('usertype',1)
                                    ->count();

                if($checkWithPriv == 0){

                    unset($teachers[$key]);

                }

            }

            
        }
        

        return array((object)['data'=>$teachers]);

    }

    


}
      