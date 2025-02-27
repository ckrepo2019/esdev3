<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class UserTypeController extends \App\Http\Controllers\Controller
{


    public static function create_usertype(Request $request){

        try{

            $description = $request->get('description');
            $ref_num = $request->get('ref_num');
            $resourcepath = $request->get('resourcepath');
            $default = $request->get('default');
            $status = $request->get('status');
            $withacad = $request->get('withacad');

            $check = DB::table('usertype')
                        ->where('utype','like','%'.$description.'%')
                        ->where('deleted',0)
                        ->count();

            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'data'=>'Already Exist!'
                 ]);
            }

            DB::table('usertype')
                ->insert([
                    'utype'=>$description,
                    'refid'=>$ref_num,
                    'resourcepath'=>$resourcepath,
                    'constant'=>$default,
                    'type_active'=>$status,
                    'with_acad'=>$withacad
                ]);

            return array((object)[
                'status'=>1,
                'data'=>'User type created!'
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function update_usertype(Request $request){

        try{

            $id = $request->get('id');
            $description = $request->get('description');
            $ref_num = $request->get('ref_num');
            $resourcepath = $request->get('resourcepath');
            $default = $request->get('default');
            $status = $request->get('status');
            $withacad = $request->get('withacad');
           
            DB::table('usertype')
                ->take(1)
                ->where('id',$id)
                ->update([
                    'updated_on'=>\Carbon\Carbon::now('Asia/Manila'),
                    'utype'=>$description,
                    'refid'=>$ref_num,
                    'resourcepath'=>$resourcepath,
                    'constant'=>$default,
                    'type_active'=>$status,
                    'with_acad'=>$withacad
                ]);

            return array((object)[
                'status'=>1,
                'data'=>'User type updated!'
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }

    }


    public static function remove_usertype(Request $request){
        
        try{
            
            $id = $request->get('id');

            $check = DB::table('users')
                        ->where('type',$id)
                        ->where('deleted',0)
                        ->count();

            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'data'=>'Already Used!'
                    ]);
            }    

            $check = DB::table('teacher')
                        ->where('usertypeid',$id)
                        ->where('deleted',0)
                        ->count();

            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'data'=>'Already Used!'
                    ]);
            }    

            $check = DB::table('faspriv')
                        ->where('usertype',$id)
                        ->where('deleted',0)
                        ->count();

            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'data'=>'Already Used!'
                    ]);
            }    


            if($id <= 18){
                return array((object)[
                    'status'=>0,
                    'data'=>'Unable to process!'
                    ]);
            }

            DB::table('usertype')
                ->where('id',$id)
                ->update([
                    'deleted'=>1,
                ]);

            return array((object)[
                'status'=>1,
                'data'=>'User type deleted!'
            ]);


        }catch(\Exception $e){
            return self::store_error($e);
        }
    }
    
    public static function usertype(Request $request){

       

        $usertype = DB::table('usertype');

        if($request->get('length') != null){
            $search = $request->get('search');
            $search = $search['value'];
            $usertype = $usertype->where('utype','like','%'.$search.'%');
        }
        
        if($request->get('length') != null){
            $usertype = $usertype->take($request->get('length'))
                                  ->skip($request->get('start'));
        }

        $usertype = $usertype->orderBy('utype')
                        ->where('deleted',0)
                        ->get();
                    
        $usertype_count = DB::table('usertype');

        if($request->get('length') != null){
            $usertype_count = $usertype_count->where('utype','like','%'.$search.'%');
        }


        $usertype_count = $usertype_count->where('deleted',0)
                        ->select('id')
                        ->count();

        return @json_encode((object)[
            'data'=>$usertype,
            'recordsTotal'=>$usertype_count,
            'recordsFiltered'=>$usertype_count
        ]);

    }

    public static function store_error($e){
        DB::table('zerrorlogs')
        ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
        return array((object)[
              'status'=>0,
              'data'=>'Something went wrong!'
        ]);
    }

      
}
