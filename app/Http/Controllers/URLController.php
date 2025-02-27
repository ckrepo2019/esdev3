<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;



class URLController extends  \App\Http\Controllers\Controller
{
    public static function url_list(Request $request){

        $url = Db::table('urls')
                    ->where('deleted',0);

        $url_count =  $url->count();


        if($request->get('length') != null){
            $url = $url->take($request->get('length'))
                       ->skip($request->get('start'));
        }

        $url_list  = $url->select(
                        'urls.*',
                        'desc as text'
                    )
                    ->get();

        
        return @json_encode((object)[
            'data'=>$url_list,
            'recordsTotal'=>$url_count,
            'recordsFiltered'=>$url_count
        ]);

    }

    public static function url_create(Request $request){
        try{
            $url = $request->get('url');
            $desc = $request->get('desc');
            $active = $request->get('active');

            $customMessages = [
                'url_id.unique' => 'URL already exist.',
            ];

            $validate = Validator::make($request->all(),[
                'url' =>  [
                    'required', 
                    Rule::unique('urls')
                        ->where('url', $url)
                        ->where('deleted', 0)
                ] ,
                'desc' => [
                    'required', 
                    Rule::unique('urls')
                        ->where('desc', $desc)
                        ->where('deleted', 0)
                ],
            ]);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('urls')
                ->insert([
                    'url'=>$url,
                    'desc'=>$desc,
                    'url_active'=>$active
                ]);

            return response()->json(['success' => true, 'message' => 'Created successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function url_update(Request $request){
        try{
            $url = $request->get('url');
            $desc = $request->get('description');
            $id = $request->get('id');
            $active = $request->get('active');

            $validate = Validator::make($request->all(),[
                'url' => 'required',
                'description' => 'required',
                'id' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('urls')
                ->where('id',$id)
                ->update([
                    'url'=>$url,
                    'desc'=>$desc,
                    'url_active'=>$active
                ]);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function url_delete(Request $request){
        try{
            $id = $request->get('id');

            $check = Db::table('urls_setup')
                        ->where('url_id',$id)
                        ->where('deleted',0)
                        ->count();

            if( $check > 0){
                return response()->json(['success' => false, 'message' => 'URL is already used.'], 200);       
            }

            DB::table('urls')
                    ->where('id',$id)
                    ->update([
                        'deleted'=>1
                    ]);

            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);       

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function url_setup_list(Request $request){

        $usertype = $request->get('usertype');

        $urls_setups = DB::table('urls_setup')
                    ->leftJoin('urls',function($join){
                        $join->on('urls_setup.url_id','=','urls.id');
                    })
                    ->where('usertype',$usertype)
                    ->where('urls_setup.deleted',0)
                    ->orderBy('sort')
                    ->select(
                        'urls_setup.*',
                        'url',
                        'desc',
                        'header_desc'
                    )
                    ->get();

        $temp_setup = array();
        foreach(collect($urls_setups)->whereNull('url_group')->values() as $urls_setup){
            $urls_setup->level = 0;
            if($urls_setup->url_id == null){
                
                    $check_details = collect( $urls_setups)
                                        ->where('url_group',$urls_setup->id)
                                        ->toArray();

              
                
                if(count($check_details) > 0){
                    array_push($temp_setup,$urls_setup);
                    foreach($check_details as $check_detail){
                        $check_detail->level = 1;
                        $check_details_2 = collect( $urls_setups)
                                            ->where('url_group',$check_detail->id)
                                            ->toArray();

                        if(count($check_details_2) > 0){
                            array_push($temp_setup,$check_detail);
                            foreach($check_details_2 as $check_detail_2){
                                $check_detail_2->level = 2;
                                array_push($temp_setup,$check_detail_2);
                            }
                        }else{
                            array_push($temp_setup,$check_detail);
                        }
                    }
                }else{
                    array_push($temp_setup,$urls_setup);
                }
            }else{
                array_push($temp_setup,$urls_setup);
            }
        }

        return $temp_setup;

    }

    public static function url_setup_create(Request $request){
        try{

            $url_id = $request->get('url_id');
            $header =  $request->get('header');
            $usertype = $request->get('usertype');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $active = $request->get('active');
            $header_description = $request->get('header_description');

            $customMessages = [
                'url_id.required_if' => 'The URL field is required when the header is not checked.',
                'url_id.unique' => 'URL already exist.',
                'sort.required' => 'The sort field is required.'
            ];
        

            $validate = Validator::make($request->all(),[
                'url_id' => [
                    'required_if:header,not checked',
                    Rule::unique('urls_setup')
                        ->where('url_id', $url_id)
                        ->where('usertype', $usertype)
                        ->where('deleted', 0),
                ],
                'header_description' => 'required_if:header,checked',
                'sort' => 'required'
            ],$customMessages);

           
      

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            // return "a";

            DB::table('urls_setup')
                ->insert([
                    'url_id'=>$url_id,
                    'usertype'=>$usertype,
                    'sort'=>$sort,
                    'header_desc'=>$header_description,
                    'url_group'=>$group
                ]);

            return response()->json(['success' => true, 'message' => 'Created successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function url_setup_update(Request $request){
        try{

            $id = $request->get('id');
            $url_id = $request->get('url_id');
            $header =  $request->get('header');
            $usertype = $request->get('usertype');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $active = $request->get('active');
            $header_description = $request->get('header_description');

            $customMessages = [
                'url_id.required_if' => 'The URL field is required when the header is not checked.',
                'url_id.unique' => 'URL already exist.',
                'sort.required' => 'The sort field is required.'
            ];
        

            $validate = Validator::make($request->all(),[
                'url_id' => [
                    'required_if:header,not checked',
                    Rule::unique('urls_setup')
                        ->where('url_id', $url_id)
                        ->where('usertype', $usertype)
                        ->whereNot('id',$id)
                        ->where('deleted', 0)
                ],
                'header_description' => 'required_if:header,checked',
                'sort' => 'required'
            ],$customMessages);

           
      

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('urls_setup')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'url_id'=>$url_id,
                    'usertype'=>$usertype,
                    'sort'=>$sort,
                    'header_desc'=>$header_description,
                    'url_group'=>$group,
                    'active'=>$active
                ]);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function url_setup_delete(Request $request){
        try{
            $id = $request->get('id');

            $check = Db::table('urls_setup')
                        ->where('url_group',$id)
                        ->where('deleted',0)
                        ->count();

            if($check > 0){
                return response()->json(['success' => false, 'message' => 'Setup Contains details.'], 200);
            }

            DB::table('urls_setup')
                    ->where('id',$id)
                    ->update([
                        'deleted'=>1
                    ]);

            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);       

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

        return response()->json(['success' => false, 'message' => 'Error occurred during the operation'], 500);

    }

    
   
}