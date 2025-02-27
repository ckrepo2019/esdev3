<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class SubjGroup extends \App\Http\Controllers\Controller
{
    public static function subjgroup(Request $request){

        $subjgroup = DB::table('setup_subjgroups')
                          ->where('deleted',0)
                          ->select(
                                'sort',
                                'id',
                                'sortnum',
                                'description',
                                'description as text'
                          )
                          ->get();

        return $subjgroup;

    }


    public static function subjgroup_datatable(Request $request){

        $search = $request->get('search');
        $search = $search['value'];

        $subjgroup = DB::table('setup_subjgroups')
                            ->where(function($query) use($search){
                                $query->orWhere('sortnum','like','%'.$search.'%');
                                $query->orWhere('description','like','%'.$search.'%');
                            })
                            ->take($request->get('length'))
                            ->skip($request->get('start'))
                            ->where('deleted',0)
                            ->select(
                                    'sort',
                                    'id',
                                    'sortnum',
                                    'description',
                                    'description as text'
                            )
                          ->get();

        $subjgroup_count = DB::table('setup_subjgroups')
                          ->where(function($query) use($search){
                              $query->orWhere('sortnum','like','%'.$search.'%');
                              $query->orWhere('description','like','%'.$search.'%');
                          })
                        ->count();


        return @json_encode((object)[
            'data'=>$subjgroup,
            'recordsTotal'=>$subjgroup_count,
            'recordsFiltered'=>$subjgroup_count
        ]);
          

    }


    public static function subjgroup_create(Request $request){

        try{

            $numorder = $request->get('numorder');
            $description = $request->get('description');
            $sort = $request->get('sort');

            $check = DB::table('setup_subjgroups')
                        ->where('description',$description)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('setup_subjgroups')
                    ->insert([
                        'sort'=>$sort,
                        'sortnum'=>$numorder,
                        'description'=>$description,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'createdby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Subject Group Created!'
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function subjgroup_update(Request $request){
        try{

            $numorder = $request->get('numorder');
            $description = $request->get('description');
            $sort = $request->get('sort');
            $id = $request->get('id');

            $check = DB::table('setup_subjgroups')
                        ->where('description',$description)
                        ->where('id','!=',$id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('setup_subjgroups')
                    ->take(1)
                    ->where('id',$id)
                    ->update([
                        'sort'=>$sort,
                        'sortnum'=>$numorder,
                        'description'=>$description,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Subject Group Updated!'
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function subjgroup_delete(Request $request){
        try{

            $id = $request->get('id');

            DB::table('setup_subjgroups')
                    ->take(1)
                    ->where('id',$id)
                    ->update([
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'deletedby'=>auth()->user()->id,
                        'deleted'=>1
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Subject Group Deleted!'
            ]);


        }catch(\Exception $e){
            return self::store_error($e);
        }
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
              'icon'=>'error',
              'message'=>'Something went wrong!'
        ]);
    }
      
}
