<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Crypt;

class Section extends Model
{
    public static function validateSectionForm($request, $sectionid = null){

        if($sectionid!=null){
            $sectionid = Crypt::decrypt($sectionid);
        }

        $messages = [
            'sn.required'    => 'Section name is required',
            'sn.unique'    => 'Section name already exist',
            'gl.required'    => 'Grade level is required.',
            't.required'     => 'Teacher is required',
            'r.required'     => 'Rom is required',
        ];

        $validator = Validator::make($request->all(), [
                            'sn' => ['required',Rule::unique('sections', 'sectionname')->ignore($sectionid,'id')],
                            'gl' => 'required',
        ],$messages);

        return $validator;

    }


    public static function storeSection($request){

      
        $sectionId = DB::table('sections')->insertGetId([
            'sectionname'=>strtoupper($request->get('sn')),
            'teacherid'=>$request->get('t'),
            'levelid'=>$request->get('gl'),
            'roomid'=>$request->get('r'),
            'createdby'=>auth()->user()->id,
            'deleted'=>0
            ]);

        $activesy = DB::table('sy')->where('isactive','1')->first();

        DB::table('sectiondetail')->insert([
            'sectionid'=> $sectionId,
            'teacherid'=>$request->get('t'),
            'syid'=>$activesy->id,
            'deleted'=>'0'
        ]);

    }
    

    public static function updateSection($request){

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        DB::table('sections')->where('id',Crypt::decrypt($request->get('sid')))->update([
            'sectionname'=>strtoupper($request->get('sn')),
            'teacherid'=>$request->get('t'),
            'levelid'=>$request->get('gl'),
            'roomid'=>$request->get('r'),
            'editedby'=>auth()->user()->id,
            'editeddatetime'=>$date
            ]);

        DB::table('sectiondetail')
                ->where('sectionid',Crypt::decrypt($request->get('sid')))
                ->update([
                    'teacherid'=>$request->get('t'),
                    'deleted'=>'0']);
     
    }

    public static function getSectionByRoomId($roomid){

        return DB::table('sections')
                ->where('roomid',$roomid)
                ->where('deleted',0)
                ->get();

    }

    public static function getSectionByTeacher($teacherid){

        return DB::table('sections')
                ->where('teacherid',$teacherid)
                ->where('deleted',0)
                ->get();
                
    }

    public static function getsectionBySectionname($sectionname){
        return DB::table('sections')
                    ->where('sectionname',$sectionname)
                    ->where('deleted',0)
                    ->take(1)
                    ->get();
    }

    public static function getsectionWithException($sectionname,$sectionid){

        return DB::table('sections')
                    ->where('sectionname',$sectionname)
                    ->where('id','!=',$sectionid)
                    ->where('deleted',0)
                    ->take(1)
                    ->get();

    }

    public static function getSectionInformation($request){

        return DB::table('sections')
                    ->leftJoin('teacher',function($join){
                        $join->on('sections.teacherid','=','teacher.id');
                        $join->where('teacher.deleted','0');
                        $join->where('teacher.isactive','1');
                    })
                    ->leftJoin('rooms',function($join){
                        $join->on('sections.roomid','=','rooms.id');
                        $join->where('rooms.deleted','0');
                    })
                    ->select(
                        'sections.id as si',
                        'sections.teacherid as ti',
                        'sections.roomid as ri',
                        'sections.levelid as gli',
                        'firstname as fn',
                        'lastname as ln',
                        'roomname as rn',
                        'sectionname as sn'
                    )
                    ->where('sections.id',$request->get('si'))
                    ->where('sections.deleted','0')
                    ->take(1)
                    ->get();
    }

    public static function getSectionInformationbyId($sectionid){

        return DB::table('sections')
                    ->leftJoin('teacher',function($join){
                        $join->on('sections.teacherid','=','teacher.id');
                        $join->where('teacher.deleted','0');
                        $join->where('teacher.isactive','1');
                    })
                    ->leftJoin('rooms',function($join){
                        $join->on('sections.roomid','=','rooms.id');
                        $join->where('rooms.deleted','0');
                    })
                    ->leftJoin('gradelevel',function($join){
                        $join->on('sections.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->leftJoin('users as cb','sections.createdby','=','cb.id')
                    ->leftJoin('users as ub','sections.editedby','=','ub.id')
                    ->select(
                        'sections.*',
                        'firstname as fn',
                        'lastname as ln',
                        'rooms.roomname as rn',
                        'levelname as lvn', 
                        'sectionname as sn',
                        'gradelevel.acadprogid',
                        'cb.name as cbname', 
                        'ub.name as ubname'
                    )
                    ->where('sections.id',$sectionid)
                    ->where('sections.deleted','0')
                    ->first();
    }

    public static function getSections(
            $skip = null, 
            $take = null, 
            $sectionid = null, 
            $sectionname = null, 
            $gradelevel = null,
            $acadprogid = null
        ){

        $sections =  self::sectionQuery($acadprogid);

        if($acadprogid!=null){
            $sections->where('academicprogram.principalid',$acadprogid);
        }

        if($sectionid!=null){
            $sections->where('id',$sectionid);
        }

        if($gradelevel!=null){
            $sections->whereIn('levelid',$gradelevel);
        }

        if($sectionname!=null){
           $sections->where(function($query) use($sectionname){
                $query->where('sections.sectionname','like',$sectionname.'%');
                $query->orWhere('gradelevel.levelname','like',$sectionname.'%');
            });
        }

        $count = $sections->count();

        if($take!=null){
            $sections->take($take);
        }

        if($skip!=null){
            $sections->skip(($skip-1)*$take);
        }

        $sections->select(
                'sections.sectionname',
                'teacher.lastname',
                'teacher.firstname',
                'gradelevel.levelname',
                'sections.id')
            ->where('sectionname','!=',null);

        $sections = $sections->get();

        $data = array();

        array_push($data,(object)['data'=>$sections,'count'=>$count]);

        return $data;           

    }

  
    public static function sectionQuery(){

        return DB::table('academicprogram')
                    ->leftJoin('gradelevel',function($join){
                        $join->on('academicprogram.id','=','gradelevel.acadprogid');
                        $join->where('deleted','0');
                    })
                    ->leftJoin('sections',function($join){
                        $join->on('gradelevel.id','=','sections.levelid');
                        $join->where('sections.deleted','0');
                    })
                    ->leftJoin('teacher',function($join){
                        $join->on('sections.teacherid','=','teacher.id');
                        $join->where('teacher.deleted','0');
                        $join->where('teacher.isactive','1');
                    });
                   
    }

    public static function sectionString($data){

        $dataString = '';
        if($data[0]->count>0){
            $dataString .='<div class="row d-flex align-items-stretch p-4">';
            foreach($data[0]->data as $section){
                $dataString .= '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                                    <div class="card bg-light w-100">
                                        <div class="card-body pt-0 mt-2">
                                        <div class="row">
                                            <div class="col-7">
                                            <h2 class="lead"><b> '.$section->levelname.'<span class="h6"><br>'.$section->sectionname.'</span></b></h2>';
                                            if($section->lastname!=null){
                                                $dataString .= '<p class="text-muted text-sm"><b>Class Adviser</b><br>'.$section->lastname.', '.explode(' ',trim($section->firstname))[0].'</p>';
                                            }
                                            else{
                                                $dataString .= '<p class="text-muted text-sm"><b>Class Adviser</b><br>No Adviser Assigned</p>';
                                            }
                                            
                                            
                                            
                                $dataString .= '  </div>
                                            <div class="col-5 text-center ">
                                            <img src="../../dist/img/user2-160x160.jpg" alt="" class="img-circle img-fluid">
                                            </div>
                                        </div>
                                        </div>
                                        <a href="/principalPortalSectionProfile/'.Crypt::encrypt($section->id).'" class="card-footer bg-info text-center"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>';
                }
                $dataString .=' </div>';
            }
            else{
                $dataString .='<div class="row d-flex align-items-stretch p-4">';
                $dataString .= '<a class=" w-100 text-center">No Section Found</a>';
                $dataString .= '</div>';
            }

        return  $dataString;
    }

}
