<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;



class TeacherEvaluationController extends  \App\Http\Controllers\Controller
{
    public static function teval_list(Request $request){

        $list = DB::table('teval')
                    ->where('deleted',0)
                    ->select(
                        'teval.*',
                        'teval_desc as text'
                    )
                    ->get();

        return @json_encode((object)[ 
            'data'=>$list
        ]);

    }

    public static function teval_create(Request $request){
        try{
            $teval_desc = $request->get('teval_desc');

            $customMessages = [
                'teval_desc.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'teval_desc' => [
                    'required', 
                    Rule::unique('teval')
                        ->where('teval_desc', $teval_desc)
                        ->where('deleted', 0)
                ],
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval')
                ->insert([
                    'teval_desc'=>$teval_desc,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Created successfully'], 200);
        
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_update(Request $request){

        try{
            $teval_desc = $request->get('teval_desc');
            $id = $request->get('id');

            $customMessages = [
                'teval_desc.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'teval_desc' => [
                    'required', 
                    Rule::unique('teval')
                        ->where('teval_desc', $teval_desc)
                        ->whereNot('id', $id)
                        ->where('deleted', 0)
                ],
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'teval_desc'=>$teval_desc,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }

        
    }

    public static function teval_delete(Request $request){
        try{

            $id = $request->get('id');

            DB::table('teval')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_question_option_list(Request $request){

        $teval_id = $request->get('teval_id');

        $list = DB::table('teval_option')
                    ->where('teval_id',$teval_id)
                    ->where('deleted',0)
                    ->select(
                        'teval_option.*',
                        'desc as text'
                    )
                    ->get();

        foreach($list as $item){
            $item->details = self::teval_question_option_detail_list($request,$item->id);
        }

        return $list;

    }

    public static function teval_question_option_create(Request $request){
        try{
            $desc = $request->get('desc');
            $teval_id = $request->get('teval_id');

            $customMessages = [
                'desc.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'desc' => [
                    'required', 
                    Rule::unique('teval_option')
                        ->where('desc', $desc)
                        ->where('teval_id',$teval_id)
                        ->where('deleted', 0)
                ],
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_option')
                ->insert([
                    'teval_id'=>$teval_id,
                    'desc'=>$desc,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Created successfully'], 200);
        
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_question_option_update(Request $request){

        try{
            $desc = $request->get('desc');
            $id = $request->get('id');
            $teval_id = $request->get('teval_id');

            $customMessages = [
                'desc.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'desc' => [
                    'required', 
                    Rule::unique('teval_option')
                        ->where('desc', $desc)
                        ->where('teval_id', $teval_id)
                        ->whereNot('id', $id)
                        ->where('deleted', 0)
                ],
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_option')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'desc'=>$desc,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }

        
    }

    public static function teval_question_option_delete(Request $request){
        try{

            $id = $request->get('id');

            $check = DB::table('teval_option_detail')
                        ->where('headerid',$id)
                        ->where('deleted',0)
                        ->count();

            if($check > 0){
                $errors = (object)[
                        'containsdetail'=>["Option already contains details. Please remove details."]
                    ];

                return response()->json(['errors' =>$errors ], 422);
            }

            DB::table('teval_option')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_question_option_detail_list(Request $request , $headerid = null){

        if($headerid == null){
            $headerid = $request->get('headerid');
        }
      

        $list = DB::table('teval_option_detail')
                    ->where('headerid',$headerid)
                    ->where('deleted',0)
                    ->orderBy('sort')
                    ->get();

        return $list;

    }

    public static function teval_question_option_detail_create(Request $request){
        try{
            $headerid = $request->get('headerid');
            $display = $request->get('display');
            $value = $request->get('value');
            $sort = $request->get('sort');
            $desc = $request->get('desc');

            $customMessages = [
                'display.unique' => 'The Display has already been taken.',
                'desc.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'display' => [
                    'required', 
                    Rule::unique('teval_option_detail')
                        ->where('display', $display)
                        ->where('headerid',$headerid)
                        ->where('deleted', 0)
                ],
                'value'=>'required',
                'sort'=> 'required',
                'desc'=> 'required'
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_option_detail')
                ->insert([
                    'headerid'=>$headerid,
                    'display'=>$display,
                    'value'=>$value,
                    'sort'=>$sort,
                    'desc'=>$desc,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Created successfully'], 200);
        
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_question_option_detail_update(Request $request){

        try{
            $id = $request->get('id');
            $headerid = $request->get('headerid');
            $display = $request->get('display');
            $value = $request->get('value');
            $sort = $request->get('sort');
            $desc = $request->get('desc');

            $customMessages = [
                'desc.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'desc' => [
                    'required', 
                    Rule::unique('teval_option')
                        ->where('display', $display)
                        ->where('headerid',$headerid)
                        ->whereNot('id', $id)
                        ->where('deleted', 0)
                ],
                'value'=>'required',
                'sort'=> 'required',
                'desc'=> 'required'
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_option_detail')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'display'=>$display,
                    'value'=>$value,
                    'sort'=>$sort,
                    'desc'=>$desc,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_question_option_detail_delete(Request $request){
        try{

            $id = $request->get('id');

            DB::table('teval_option_detail')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_group_list(Request $request){

        $teval_id = $request->get('teval_id');

        $list = DB::table('teval_group')
                    ->where('teval_id',$teval_id)
                    ->where('deleted',0)
                    ->orderBy('group_sort')
                    ->select(
                        'teval_group.*',
                        'teval_group.description as text'
                    )
                    ->get();

        foreach($list as $item){
            $item->question = self::teval_question_list($request,$item->id);
        }

        return $list;

    }

    public static function teval_group_create(Request $request){
        try{
            $description = $request->get('description');
            $group_sort = $request->get('group_sort');
            $teval_id = $request->get('teval_id');

            $customMessages = [
                'description.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'description' => [
                    'required', 
                    Rule::unique('teval_group')
                        ->where('description', $description)
                        ->where('teval_id', $teval_id)
                        ->where('deleted', 0)
                ],
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_group')
                ->insert([
                    'teval_id'=>$teval_id,
                    'description'=>$description,
                    'group_sort'=>$group_sort,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Created successfully'], 200);
        
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_group_update(Request $request){

        try{
            $description = $request->get('description');
            $group_sort = $request->get('group_sort');
            $teval_id = $request->get('teval_id');
            $id = $request->get('id');

            $customMessages = [
                'description.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'description' => [
                    'required', 
                    Rule::unique('teval_group')
                        ->where('description', $description)
                        ->where('teval_id', $teval_id)
                        ->whereNot('id', $id)
                        ->where('deleted', 0)
                ],
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_group')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'description'=>$description,
                    'group_sort'=>$group_sort,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }

        
    }

    public static function teval_group_delete(Request $request){
        try{

            $id = $request->get('id');

            DB::table('teval_group')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_question_list(Request $request , $group = null){

        if($group == null){
            $group = $request->get('group');
        }

        $list = DB::table('teval_question')
                    ->leftJoin('teval_option',function($join){
                        $join->on('teval_question.option_id','=','teval_option.id');
                        $join->where('teval_option.deleted',0);
                    })
                    ->where('group',$group)
                    ->where('teval_question.deleted',0)
                    ->orderBy('teval_question.sort')
                    ->select(
                        'teval_question.*',
                        'teval_option.desc as option_desc'
                    )
                    ->get();

        foreach($list as $item){
            if($item->type == 'multiple_choice'){
                $item->mc_detail = self::teval_question_option_detail_list($request,$item->option_id);
            }
        }

        return $list;
        // return @json_encode((object)[ 
        //     'data'=>$list
        // ]);

    }

    public static function teval_question_create(Request $request){
        try{
            $teval_id = $request->get('teval_id');
            $question_desc = $request->get('question_desc');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $type = $request->get('type');
            $option_id = $request->get('option_id');


            $customMessages = [
                'question_desc.unique' => 'The Description has already been taken.',
                'option_id.required_if' => 'The Multiple Choice Setup is required.',
            ];

            $validate = Validator::make($request->all(),[
                'question_desc' => [
                    'required', 
                    Rule::unique('teval_question')
                        ->where('question_desc', $question_desc)
                        ->where('type', $type)
                        ->where('teval_id', $teval_id)
                        ->where('deleted', 0)
                ],
                'type'=>'required',
                'option_id'=>'required_if:type,multiple_choice'
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_question')
                ->insert([
                    'teval_id'=>$teval_id,
                    'question_desc'=>$question_desc,
                    'sort'=>$sort,
                    'group'=>$group,
                    'type'=>$type,
                    'option_id'=>$option_id,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Created successfully'], 200);
        
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_question_update(Request $request){

        try{
            $teval_id = $request->get('teval_id');
            $question_desc = $request->get('question_desc');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $type = $request->get('type');
            $id = $request->get('id');
            $option_id = $request->get('option_id');

            $customMessages = [
                'question_desc.unique' => 'The Description has already been taken.',
            ];

            $validate = Validator::make($request->all(),[
                'question_desc' => [
                    'required', 
                    Rule::unique('teval_question')
                        ->where('question_desc', $question_desc)
                        ->where('type', $type)
                        ->where('teval_id', $teval_id)
                        ->whereNot('id', $id)
                        ->where('deleted', 0)
                ],
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_question')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'question_desc'=>$question_desc,
                    'sort'=>$sort,
                    'group'=>$group,
                    'type'=>$type,
                    'option_id'=>$option_id,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }

        
    }

    public static function teval_question_delete(Request $request){
        try{

            $id = $request->get('id');

            DB::table('teval_question')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_sy_list(Request $request , $group = null){

        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $type = $request->get('type');

        $list = DB::table('teval_sy')
                    ->where('teval_sy.syid',$syid)
                    ->where('teval_sy.deleted',0)
                    ->join('teval',function($join){
                        $join->on('teval_sy.teval_id','=','teval.id');
                    })
                    ->where(function($query) use($levelid,$type){
                        if($levelid != null){
                            $query->where('levelid',$levelid);
                        }
                        if($type != null){
                            $query->where('type',$type);
                        }
                    })
                    ->select(
                        'teval_sy.*',
                        'teval_desc',
                        DB::raw('DATE_FORMAT(teval_sy.dateactivated, "%d-%b-%Y %h:%i %p") as dateactivated')
                    )
                    ->get();

         return $list;

    }

    public static function teval_sy_create(Request $request){
        try{
            $teval_id = $request->get('teval_id');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $term = $request->get('term');
            $type = $request->get('type');
            
            $customMessages = [
                'teval_id.required' => 'Evaluation setup is required.',
            ];

            $validate = Validator::make($request->all(),[
                'teval_id' =>'required',
                'term' =>'required'
                   
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_sy')
                ->insert([
                    'teval_id'=>$teval_id,
                    'syid'=>$syid,
                    'levelid'=>$levelid,
                    'term'=>$term,
                    'type'=>$type,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Created successfully'], 200);
        
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_sy_update(Request $request){

        try{
            $teval_id = $request->get('teval_id');
            $question_desc = $request->get('question_desc');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $type = $request->get('type');
            $id = $request->get('id');
            $option_id = $request->get('option_id');

            $customMessages = [
                'teval_id.required' => 'Evaluation setup is required.',
            ];

            $validate = Validator::make($request->all(),[
                'question_desc' => [
                    'required', 
                    Rule::unique('teval_question')
                        ->where('question_desc', $question_desc)
                        ->where('type', $type)
                        ->where('teval_id', $teval_id)
                        ->whereNot('id', $id)
                        ->where('deleted', 0)
                ],
            ],$customMessages);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            }

            DB::table('teval_question')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'question_desc'=>$question_desc,
                    'sort'=>$sort,
                    'group'=>$group,
                    'type'=>$type,
                    'option_id'=>$option_id,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Updated Successfully!'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }

        
    }

    public static function teval_sy_delete(Request $request){
        try{

            $id = $request->get('id');

            DB::table('teval_sy')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Deleted successfully!'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_sy_activate(Request $request){
        try{

            $id = $request->get('id');

            DB::table('teval_sy')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'activated'=>1,
                    'dateactivated'=>\Carbon\Carbon::now('Asia/Manila'),
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Activated Successfully!'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }
   
    public static function teval_sy_end(Request $request){
        try{

            $id = $request->get('id');

            DB::table('teval_sy')
                ->where('id',$id)
                ->take(1)
                ->update([
                    'deleted'=>1,
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return response()->json(['success' => true, 'message' => 'Ended Successfully!'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function teval_submit_answer(Request $request){

        try{

            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
            
            $syid = $request->get('syid');
            $subjid = $request->get('subjid');
            $term = $request->get('term');
            $teacherid = $request->get('teacherid');
            $answers = $request->get('answers');

            if($answers == null){
                $answers = array();
            }

            $check = Db::table('teval_ans')
                        ->where('deleted',0)
                        ->where('studid',$studid)
                        ->where('subjid',$subjid)
                        ->where('syid',$syid)
                        ->where('term',$term)
                        ->where('teacherid',$teacherid)
                        ->count();

            if($check > 0){
                return response()->json(['success' => false, 'message' => 'Already Evaluated'], 500);
            }

            $id = DB::table('teval_ans')
                    ->insertGetId([
                        'syid'=>$syid,
                        'studid'=>$studid,
                        'term'=>$term,
                        'subjid'=>$subjid,
                        'teacherid'=>$teacherid,
                        // 'type'=>$type,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            foreach($answers as $item){
                DB::table('teval_ans_detail')
                ->insertGetId([
                    'teval_ans_id'=>$id,
                    'teval_qid'=>$item['teval_qid'],
                    'answer'=>$item['answer'],
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Submitted Successfully!'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function teval_submit_answer_teacher(Request $request){

        try{
            
            $syid = $request->get('syid');
            $term = $request->get('term');
            $teacherid = $request->get('teacherid');
            $answers = $request->get('answers');
            $evaluator_id = DB::table('teacher')->where('tid',auth()->user()->email)->first()->id;

            if($answers == null){
                $answers = array();
            }

            $check = Db::table('teval_ans_teacher')
                        ->where('deleted',0)
                        ->where('syid',$syid)
                        ->where('term',$term)
                        ->where('evaluator_id',$evaluator_id)
                        ->where('teacherid',$teacherid)
                        ->count();

            if($check > 0){
                return response()->json(['success' => false, 'message' => 'Already Evaluated'], 500);
            }

            $id = DB::table('teval_ans_teacher')
                    ->insertGetId([
                        'evaluator_id'=>$evaluator_id,
                        'syid'=>$syid,
                        'term'=>$term,
                        'teacherid'=>$teacherid,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            foreach($answers as $item){
                DB::table('teval_ans_detail_teacher')
                ->insertGetId([
                    'teval_ans_id'=>$id,
                    'teval_qid'=>$item['teval_qid'],
                    'answer'=>$item['answer'],
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Submitted Successfully!'], 200);

        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function teval_answer(Request $request, $students = array() , $subjid = null , $type = null){

        $studid = null;

        if(auth()->user()->type == 7){
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        $syid = $request->get('syid');

        $header = DB::table('teval_ans')
                    ->where('syid',$syid)
                    ->where(function($query) use($students,$studid,$subjid,$type){
                        if($studid == null){
                            $query->whereIn('studid',$students);
                        }else{
                            $query->where('studid',$studid);
                        }
                        if($subjid != null){ $query->where('subjid',$subjid); }
                    })
                    ->where('deleted',0)
                    ->select(
                        'teval_ans.*',
                        DB::raw('DATE_FORMAT(teval_ans.createddatetime, "%d-%b-%Y %h:%i %p") as createddatetime')
                    )
                    ->get();

        foreach($header as $item){
         
            $item->answer = DB::table('teval_ans_detail')
                                ->join('teval_question',function($join){
                                    $join->on('teval_ans_detail.teval_qid','=','teval_question.id');
                                })
                                ->select(
                                    'teval_ans_detail.answer',
                                    'teval_ans_detail.id',
                                    'teval_ans_detail.teval_ans_id',
                                    'teval_ans_detail.teval_qid',
                                    'teval_question.type'
                                )
                                ->where('teval_ans_detail.teval_ans_id',$item->id)
                                ->where('teval_ans_detail.deleted',0)
                                ->get();

        }
        return $header;
    }

    public static function teval_answer_teacher(Request $request,$teachers=array()){

        $teacherid = $request->get('teacherid');
        $syid = $request->get('syid');

        $evaluator_id = null;
        if(Session::get('currentPortal') == 1){
            $evaluator_id = DB::table('teacher')->where('tid',auth()->user()->email)->first()->id;
        }

        $header = DB::table('teval_ans_teacher')
                    ->where('syid',$syid)
                    ->where(function($query) use($teacherid, $evaluator_id, $teachers){
                        if($teacherid != null){
                            $query->where('teacherid',$teacherid);
                        }

                        if(count($teachers) != 0){
                            $query->whereIn('teacherid',$teachers);
                        }

                        if($evaluator_id != null){
                            $query->where('evaluator_id',$evaluator_id);
                        }
                    })
                    ->where('deleted',0)
                    ->select(
                        'teval_ans_teacher.*',
                        DB::raw('DATE_FORMAT(teval_ans_teacher.createddatetime, "%d-%b-%Y %h:%i %p") as createddatetime')
                    )
                    ->get();

        foreach($header as $item){
         
            $item->answer = DB::table('teval_ans_detail_teacher')
                                ->join('teval_question',function($join){
                                    $join->on('teval_ans_detail_teacher.teval_qid','=','teval_question.id');
                                })
                                ->select(
                                    'teval_ans_detail_teacher.answer',
                                    'teval_ans_detail_teacher.id',
                                    'teval_ans_detail_teacher.teval_ans_id',
                                    'teval_ans_detail_teacher.teval_qid',
                                    'teval_question.type'
                                )
                                ->where('teval_ans_id',$item->id)
                                ->where('teval_ans_detail_teacher.deleted',0)
                                ->get();

        }
        return $header;
    }
   
    public static function teval_monitoring(Request $request){

        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $strandid =  $request->get('strandid');
        $schedtype =  $request->get('schedtype');

        if( $schedtype == "teacher"){
            $subjects = \App\Http\Controllers\SuperAdminController\TeacherProfileController::schedule($request);
            $subjects = collect($subjects)->where('acadprogid','!=',6)->values();
        }else{
            if($levelid == 14 || $levelid == 15){
                $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid,$semid,$strandid);
            }
            elseif($levelid >= 17 && $levelid <= 20){
                $schedule = \App\Http\Controllers\SuperAdminController\StudentLoading::collegestudentsched_plot($studid,$syid,$semid);
                if($schedule[0]->status == 1){
                    $schedule = $schedule[0]->info;
                    $schedule = collect($schedule)->where('schedstatus','!=','DROPPED')->values();
                    foreach($schedule as $item){
                        $item->subjdesc = $item->subjDesc;
                        $item->subjcode = $item->subjCode;
                        foreach($item->schedule as $sched_item){
                            $sched_item->teacher = $item->teacher;
                        }
                        $item->datatype = "college";
                    }
                }else{
                    $schedule = array();
                }
            }
            else{
                $subjects = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid,$semid,$strandid);
    
                
            }
        }
       
        foreach($subjects as $item){
            if($sectionid == null){
                $sectionid = $item->sectionid;
            }
            
            $item->students =  \App\Http\Controllers\SuperAdminController\TeacherECRController::get_students($levelid,$syid,$sectionid,$item->subjid,$strandid,null,$semid);
            $temp_students = collect($item->students )->pluck('studid');
            $item->evaluations = self::teval_answer($request,$temp_students,$item->subjid,'student');

        }

        return $subjects;


        

    }

    public static function teval_monitoring_teacher(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $teacherid = $request->get('teacherid');

        $teachers = \App\Http\Controllers\SuperAdminController\TeacherProfileController::teacher_list($request);

        if($teacherid != null){
            $teachers = collect($teachers)->where('id',$teacherid)->values();
        }
        
        foreach($teachers as $item){
            $request->request->add(['teacherid' => $item->id]);
            $item->evaluations = self::teval_answer_teacher($request);
        }
        return $teachers;

    }

    public static function teval_print(Request $request){

        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $strandid =  $request->get('strandid');
        $schedtype =  $request->get('schedtype');
        $subjid =  $request->get('subjid');


        $results = self::teval_monitoring($request);
        $results = collect($results)->where('subjid',$subjid)->values();

        $temp_results = array();
        foreach($results as $item){
            if(count($item->evaluations)){
                 array_push($temp_results,$item);
            }
        }

        $results = $temp_results;


        $eval_setup = self::teval_sy_list($request);
        $request->request->add(['teval_id'=> $eval_setup[0]->teval_id]);
        $questionnaire = self::teval_group_list($request);

        $syinfo = DB::table('sy')
                    ->where('id',$syid)
                    ->first();
        // return $questionnaire;
    
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("Evaluation Report.xlsx");

        $font_bold = [
            'font' => [
                'bold' => true,
            ]
        ];

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        $row = 2;
        foreach($questionnaire as $item){
            $sheet->setCellValue('B'.$row,$item->description);
            $sheet->getStyle('B'.$row)->applyFromArray($font_bold);
            $row += 1;
            foreach( $item->question as $key=>$question){
                $sheet->setCellValue('C'.$row,$question->question_desc);
                $sheet->setCellValue('A'.$row,$item->group_sort.($key+1));
                $sheet->getStyle('A'.$row)->applyFromArray($font_bold);
                $row += 1;
            }
        }


        $col = array();

        foreach(range('A','Z') as $columnID) {
            array_push($col,$columnID);
        }

        foreach(range('A','Z') as $columnID) {
            array_push($col,'A'.$columnID);
        }

        foreach(range('A','Z') as $columnID) {
            array_push($col,'B'.$columnID);
        }
        
        // return $questionnaire;
        foreach($results as $item){

            $sheetname = str_replace('GRADE ','',$item->levelname).'-'.substr($item->sectionname,0,10);

            $clonedWorksheet = clone $spreadsheet->getSheetByName('Section 1');
            $clonedWorksheet->setTitle($sheetname);
            $spreadsheet->addSheet($clonedWorksheet);
            $sheet = $spreadsheet->getSheetByName($sheetname);

            $sheet->setCellValue('B1',$syinfo->sydesc);
            $sheet->setCellValue('B2',$item->sectionname);
            $sheet->setCellValue('B3',$item->levelname);
            $sheet->setCellValue('B4',$item->teacher);
            $sheet->setCellValue('G1',$results[0]->subjdesc);

            $sum_col = array();

            $col_count = 2;
            foreach($questionnaire as $questionnaire_item){
                foreach( $questionnaire_item->question as $key=>$question){

                    if($question->type == 'long_answer'){
                        $column = $col[$col_count];
                        $sheet->setCellValue($column.'6','SUM');
                        $sheet->getColumnDimension($column)->setAutoSize(false);
                        $sheet->getColumnDimension($column)->setWidth(8);
                        $col_count += 1;
                    }


                    $column = $col[$col_count];
                    $sheet->setCellValue($column.'6',$questionnaire_item->group_sort.($key+1));

                    $sheet->getColumnDimension($column)->setAutoSize(false);
                    $sheet->getColumnDimension($column)->setWidth(8);

                    $col_count += 1;
                }
                if($question->type == 'multiple_choice'){
                    $column = $col[$col_count];
                    $sheet->setCellValue($column.'6',$questionnaire_item->group_sort.' SUM');
                    array_push( $sum_col, $column);
                }
                $sheet->getColumnDimension($column)->setAutoSize(false);
                $sheet->getColumnDimension($column)->setWidth(8);
                $col_count += 1;
            }

            $row = 7;
            foreach($item->evaluations as $eval_key=>$evaluation){
                $sheet->setCellValue('A'.$row,'Student'.($eval_key + 1));
                $col_count = 2;
                foreach($questionnaire as $questionnaire_item){
                    $start_col = $col[$col_count];
                    foreach( $questionnaire_item->question as $key=>$question){

                        if($question->type == 'long_answer'){
                            $column = $col[$col_count];
                            $sum_col_func = '';
                            foreach($sum_col as $sum_col_item){
                                $sum_col_func .= $sum_col_item.$row.',';
                            }
                            $sum_col_func = substr($sum_col_func, 0, -1);
                            $sheet->setCellValue($column.$row,'=AVERAGE('.$sum_col_func.')');
                            $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                            $col_count += 1;
                        }

                        $column = $col[$col_count];
                        $answer = collect($evaluation->answer)->where('teval_qid',$question->id)->first();
                        if(isset($answer)){
                            $sheet->setCellValue($column.$row,$answer->answer);
                        }
                        if($question->type == 'long_answer'){
                            $sheet->getStyle($column.$row)
                                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        }
                        $last_col = $col[$col_count];
                        $col_count += 1;
                        $column = $col[$col_count];
                    }
                    if($question->type == 'multiple_choice'){
                        $sheet->setCellValue($column.$row,'=AVERAGE('.$start_col.$row.':'.$last_col.$row.')');
                        $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                    }
                    $col_count += 1;
                }


                $row += 1;
            }
            $row += 1;
            $col_count = 2;
            $sheet->setCellValue('A'.$row,'Summary');
            $sheet_sum = array();
            foreach($questionnaire as $questionnaire_item){
                foreach( $questionnaire_item->question as $key=>$question){
                    if($question->type == 'long_answer'){
                        $column = $col[$col_count];
                        $sum_col_func = '';
                        foreach($sum_col as $sum_col_item){
                            $sum_col_func .= $sum_col_item.$row.',';
                        }
                        $sum_col_func = substr($sum_col_func, 0, -1);
                        $sheet->setCellValue($column.$row,'=AVERAGE('.$sum_col_func.')');
                        $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');

                        array_push($sheet_sum,(object)[
                            'qid'=>'SUM',
                            'sheet'=>"='".$sheetname."'!".$column.$row
                        ]);

                        $col_count += 1;
                    }

                    $column = $col[$col_count];
                    if($question->type == 'multiple_choice'){
                        $sheet->setCellValue($column.$row,'=AVERAGE('.$column.'7'.':'.$column.($row-2).')');
                        $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                        array_push($sheet_sum,(object)[
                            'qid'=>$question->id,
                            'sheet'=>"='".$sheetname."'!".$column.$row
                        ]);
                    }
                    $col_count += 1;
                }
                if($question->type == 'multiple_choice'){
                    $column = $col[$col_count];
                    $sheet->setCellValue($column.$row,'=AVERAGE('.$column.'7'.':'.$column.($row-2).')');
                    $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                    array_push($sheet_sum,(object)[
                        'qid'=>'GROUP SUM',
                        'sheet'=>"='".$sheetname."'!".$column.$row
                    ]);
                }
                $col_count += 1;
            }
            $item->sum_col = $sheet_sum;
        }

        //Summary Sheet
        $sheet = $spreadsheet->getSheetByName('Summary');

        $sheet->setCellValue('B1',$syinfo->sydesc);
        $sheet->setCellValue('B2',$item->levelname);
        $sheet->setCellValue('D1',$results[0]->subjdesc);
        $sheet->setCellValue('D2',$item->teacher);

        $col_count = 1;
        $start_col = $col[$col_count];
        foreach($results as $item){
            $column = $col[$col_count];
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $sheetname = str_replace('GRADE ','',$item->levelname).'-'.substr($item->sectionname,0,10);
            $sheet->setCellValue($column.'5',$sheetname);
            $row = 8;
            foreach($item->sum_col as $sum_col_item){
                if($sum_col_item->qid == 'SUM'){
                    $row += 1;
                    $sheet->getStyle('A'.$row)->applyFromArray($font_bold);
                }
                $sheet->setCellValue( $column.$row,$sum_col_item->sheet);
                $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                $row += 1;
                if($sum_col_item->qid == 'GROUP SUM'){
                    $sheet->getStyle('A'.$row)->applyFromArray($font_bold);
                    $row += 1;
                }
            }
            $end_col = $col[$col_count];
            $end_col_rating = $col[$col_count+2];
            $col_count += 1;
        }

        $column = $col[$col_count];
        $sheet->setCellValue($column.'5','SUMMARY');
        $sheet->getColumnDimension($column)->setAutoSize(false);
        $sheet->getColumnDimension($column)->setWidth(15);

        $row = 8;
        foreach($questionnaire as $item){
            foreach( $item->question as $key=>$question){
                if($question->type == 'long_answer'){
                    $row += 1;
                    $sheet->setCellValue('A'.$row,'TOTAL');
                    $sheet->getStyle('A'.$row)->applyFromArray($font_bold);
                    
                    $sheet->setCellValue($column.$row,'=AVERAGE('.$start_col.$row.':'.$end_col.$row.')');
                    $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');

                    $sheet->setCellValue($end_col_rating.$row,'=IF('.$column.$row.'="","",VLOOKUP('.$column.$row.',N34:O40,2,true))');
                    $sheet = $spreadsheet->getSheetByName('Printable_Student');
                    $sheet->setCellValue('H19','=FIXED(Summary!'.$column.$row.',2)&" - "&'.'Summary!'.$end_col_rating.$row);
                    $sheet = $spreadsheet->getSheetByName('Summary');

                }
                if($question->type == 'multiple_choice'){
                    $sheet->setCellValue('A'.$row,$item->group_sort.($key+1));

                    $sheet->setCellValue($column.$row,'=AVERAGE('.$start_col.$row.':'.$end_col.$row.')');
                    $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');

                    $row += 1;
                }
            }
            if($question->type == 'multiple_choice'){
           
                $sheet->setCellValue('A'.$row,$item->group_sort.' TOTAL');
                $sheet->getStyle('A'.$row)->applyFromArray($font_bold);

                $sheet->setCellValue($column.$row,'=AVERAGE('.$start_col.$row.':'.$end_col.$row.')');
                $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                $row += 1;
                $row += 1;
            }
        }


        $sheet = $spreadsheet->getSheetByName('Printable_Student');
        $sheet->setCellValue('G10',  $results[0]->teacher);
        $sheet->setCellValue('B482',  $results[0]->teacher);
        $sheet->setCellValue('G11',  $results[0]->subjdesc);

        $comment_row = 23;
        foreach($results as $result){
            foreach($result->evaluations as $evaluation){
                $comments = collect($evaluation->answer)->where('type','long_answer')->values();
                foreach($comments as $comment){
                    if($comment->answer != null){
                        $sheet->setCellValue('B'. $comment_row,$comment->answer);
                        if(strlen($comment->answer) > 178){
                            $sheet->getRowDimension($comment_row)->setRowHeight(50,'in');
                        }
                        else if(strlen($comment->answer) > 89){
                            $sheet->getRowDimension($comment_row)->setRowHeight(71,'in');
                        }
                        $comment_row += 1;
                    }
                }
            }
        }

        for($x = $comment_row ; $x <= 470; $x++){
            $sheet->getRowDimension($x)->setVisible(false);
        }
      
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Section 1')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);

        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Printable')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
        
        $sheet = $spreadsheet->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Teacher Evaluation.xlsx"');
        $writer->save("php://output");
        exit();
        
        return $results;

    }

    public static function teval_print_teacher(Request $request){

        $syid = $request->get('syid');
        $teacherid = $request->get('teacherid');

        $results = self::teval_monitoring_teacher($request);
        $request->request->add(['type'=> "teacher"]);
        $eval_setup = self::teval_sy_list($request);
        $request->request->add(['teval_id'=> $eval_setup[0]->teval_id]);
       
        $questionnaire = self::teval_group_list($request);

        $syinfo = DB::table('sy')
                    ->where('id',$syid)
                    ->first();
        // return $questionnaire;
    
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("Evaluation Report.xlsx");

        $font_bold = [
            'font' => [
                'bold' => true,
            ]
        ];

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        $row = 2;
        foreach($questionnaire as $item){
            $sheet->setCellValue('B'.$row,$item->description);
            $sheet->getStyle('B'.$row)->applyFromArray($font_bold);
            $row += 1;
            foreach( $item->question as $key=>$question){
                $sheet->setCellValue('C'.$row,$question->question_desc);
                $sheet->setCellValue('A'.$row,$item->group_sort.($key+1));
                $sheet->getStyle('A'.$row)->applyFromArray($font_bold);
                $row += 1;
            }
        }


        $col = array();

        foreach(range('A','Z') as $columnID) {
            array_push($col,$columnID);
        }

        foreach(range('A','Z') as $columnID) {
            array_push($col,'A'.$columnID);
        }

        foreach(range('A','Z') as $columnID) {
            array_push($col,'B'.$columnID);
        }
        
        // return $questionnaire;
        foreach($results as $item){

            $sheetname = 'Evalutaions';

            $clonedWorksheet = clone $spreadsheet->getSheetByName('Section 1');
            $clonedWorksheet->setTitle($sheetname);
            $spreadsheet->addSheet($clonedWorksheet);
            $sheet = $spreadsheet->getSheetByName($sheetname);

            $sheet->setCellValue('B1',$syinfo->sydesc);
            $sheet->setCellValue('B2',$item->fullname);
            $sheet->setCellValue('A2',"Teacher");
            $sheet->setCellValue('A3',"");
            $sheet->setCellValue('A4',"");
            $sheet->setCellValue('F1',"");
            $sum_col = array();

            $col_count = 2;
            foreach($questionnaire as $questionnaire_item){
                foreach( $questionnaire_item->question as $key=>$question){

                    if($question->type == 'long_answer'){
                        $column = $col[$col_count];
                        $sheet->setCellValue($column.'6','SUM');
                        $sheet->getColumnDimension($column)->setAutoSize(false);
                        $sheet->getColumnDimension($column)->setWidth(8);
                        $col_count += 1;
                    }


                    $column = $col[$col_count];
                    $sheet->setCellValue($column.'6',$questionnaire_item->group_sort.($key+1));

                    $sheet->getColumnDimension($column)->setAutoSize(false);
                    $sheet->getColumnDimension($column)->setWidth(8);

                    $col_count += 1;
                }
                if($question->type == 'multiple_choice'){
                    $column = $col[$col_count];
                    $sheet->setCellValue($column.'6',$questionnaire_item->group_sort.' SUM');
                    array_push( $sum_col, $column);
                }
                $sheet->getColumnDimension($column)->setAutoSize(false);
                $sheet->getColumnDimension($column)->setWidth(8);
                $col_count += 1;
            }

            $row = 7;
            foreach($item->evaluations as $eval_key=>$evaluation){
                $sheet->setCellValue('A'.$row,'Teacher'.($eval_key + 1));
                $col_count = 2;
                foreach($questionnaire as $questionnaire_item){
                    $start_col = $col[$col_count];
                    foreach( $questionnaire_item->question as $key=>$question){

                        if($question->type == 'long_answer'){
                            $column = $col[$col_count];
                            $sum_col_func = '';
                            foreach($sum_col as $sum_col_item){
                                $sum_col_func .= $sum_col_item.$row.',';
                            }
                            $sum_col_func = substr($sum_col_func, 0, -1);
                            $sheet->setCellValue($column.$row,'=AVERAGE('.$sum_col_func.')');
                            $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                            $col_count += 1;
                        }

                        $column = $col[$col_count];
                        $answer = collect($evaluation->answer)->where('teval_qid',$question->id)->first();
                        if(isset($answer)){
                            $sheet->setCellValue($column.$row,$answer->answer);
                        }
                        if($question->type == 'long_answer'){
                            $sheet->getStyle($column.$row)
                                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        }
                        $last_col = $col[$col_count];
                        $col_count += 1;
                        $column = $col[$col_count];
                    }
                    if($question->type == 'multiple_choice'){
                        $sheet->setCellValue($column.$row,'=AVERAGE('.$start_col.$row.':'.$last_col.$row.')');
                        $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                    }
                    $col_count += 1;
                }


                $row += 1;
            }
            $row += 1;
            $col_count = 2;
            $sheet->setCellValue('A'.$row,'Summary');
            $sheet_sum = array();
            foreach($questionnaire as $questionnaire_item){
                foreach( $questionnaire_item->question as $key=>$question){
                    if($question->type == 'long_answer'){
                        $column = $col[$col_count];
                        $sum_col_func = '';
                        foreach($sum_col as $sum_col_item){
                            $sum_col_func .= $sum_col_item.$row.',';
                        }
                        $sum_col_func = substr($sum_col_func, 0, -1);
                        $sheet->setCellValue($column.$row,'=AVERAGE('.$sum_col_func.')');
                        $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');

                        array_push($sheet_sum,(object)[
                            'qid'=>'SUM',
                            'sheet'=>"='".$sheetname."'!".$column.$row
                        ]);

                        $col_count += 1;
                    }

                    $column = $col[$col_count];
                    if($question->type == 'multiple_choice'){
                        $sheet->setCellValue($column.$row,'=AVERAGE('.$column.'7'.':'.$column.($row-2).')');
                        $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                        array_push($sheet_sum,(object)[
                            'qid'=>$question->id,
                            'sheet'=>"='".$sheetname."'!".$column.$row
                        ]);
                    }
                    $col_count += 1;
                }
                if($question->type == 'multiple_choice'){
                    $column = $col[$col_count];
                    $sheet->setCellValue($column.$row,'=AVERAGE('.$column.'7'.':'.$column.($row-2).')');
                    $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                    array_push($sheet_sum,(object)[
                        'qid'=>'GROUP SUM',
                        'sheet'=>"='".$sheetname."'!".$column.$row
                    ]);
                }
                $col_count += 1;
            }
            $item->sum_col = $sheet_sum;
        }

        //Summary Sheet
        $sheet = $spreadsheet->getSheetByName('Summary');

        $sheet->setCellValue('B1',$syinfo->sydesc);
        $sheet->setCellValue('A2',"Teacher");
        $sheet->setCellValue('A2',$results[0]->fullname);
        $sheet->setCellValue('C1',"");
        $sheet->setCellValue('C2',"");
       

        $col_count = 1;
        $start_col = $col[$col_count];
        foreach($results as $item){
            $column = $col[$col_count];
            $sheet->getColumnDimension($column)->setAutoSize(true);
            // $sheetname = str_replace('GRADE ','',$item->levelname).'-'.substr($item->sectionname,0,10);
            $sheetname = 'Evaluations';
            $sheet->setCellValue($column.'5',$sheetname);
            $row = 8;
            foreach($item->sum_col as $sum_col_item){
                if($sum_col_item->qid == 'SUM'){
                    $row += 1;
                    $sheet->getStyle('A'.$row)->applyFromArray($font_bold);
                }
                $sheet->setCellValue( $column.$row,$sum_col_item->sheet);
                $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                $row += 1;
                if($sum_col_item->qid == 'GROUP SUM'){
                    $sheet->getStyle('A'.$row)->applyFromArray($font_bold);
                    $row += 1;
                }
            }
            $end_col = $col[$col_count];
            $end_col_rating = $col[$col_count+2];
            $col_count += 1;
        }

        $column = $col[$col_count];
        $sheet->setCellValue($column.'5','SUMMARY');
        $sheet->getColumnDimension($column)->setAutoSize(false);
        $sheet->getColumnDimension($column)->setWidth(15);
        
        $comment_id = null;
        $row = 8;
        $print_row = [22,23,24,25];
        foreach($questionnaire as $q_key=>$item){
            foreach( $item->question as $key=>$question){
                if($question->type == 'long_answer'){
                    $comment_id = $question->id;
                    $row += 1;
                    $sheet->setCellValue('A'.$row,'TOTAL');
                    $sheet->getStyle('A'.$row)->applyFromArray($font_bold);
                    $sheet->setCellValue($column.$row,'='.$start_col.$row);
                    $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');
                    $sheet->setCellValue($end_col_rating.$row,'=IF('.$column.$row.'="","",VLOOKUP('.$column.$row.',N24:O29,2,true))');
                    $sheet = $spreadsheet->getSheetByName('Printable');
                    $sheet->setCellValue('F27','=FIXED(Summary!'.$column.$row.',2)&" - "&'.'Summary!'.$end_col_rating.$row);
                    $sheet = $spreadsheet->getSheetByName('Summary');
                }
                if($question->type == 'multiple_choice'){
                    $sheet->setCellValue('A'.$row,$item->group_sort.($key+1));

                    $sheet->setCellValue($column.$row,'=AVERAGE('.$start_col.$row.':'.$end_col.$row.')');
                    $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');

                    $row += 1;
                }
            }
            if($question->type == 'multiple_choice'){
           
                $sheet->setCellValue('A'.$row,$item->group_sort.' TOTAL');
                $sheet->getStyle('A'.$row)->applyFromArray($font_bold);

                $sheet->setCellValue($column.$row,'=AVERAGE('.$start_col.$row.':'.$end_col.$row.')');
                $sheet->getStyle($column.$row)->getNumberFormat()->setFormatCode('0.00');

                if(isset($print_row[$q_key])){
                    $sheet = $spreadsheet->getSheetByName('Printable');
                    $sheet->setCellValue('G'.$print_row[$q_key],'=Summary!'.$column.$row);
                    $sheet->getStyle('G'.$print_row[$q_key])->getNumberFormat()->setFormatCode('0.00');
                }
               

                $sheet = $spreadsheet->getSheetByName('Summary');

                $row += 1;
                $row += 1;
            }
        }

        $designation = DB::table('sectiondetail')
                        ->join('sections',function($join){
                            $join->on('sectiondetail.sectionid','=','sections.id');
                        })
                        ->join('gradelevel',function($join){
                            $join->on('sections.levelid','=','gradelevel.id');
                        })
                        ->where('sectiondetail.teacherid',$teacherid)
                        ->where('sectiondetail.deleted',0)
                        ->where('sectiondetail.syid',$syid)
                        ->select(
                            'levelname',
                            'sectionname'
                        )
                        ->first();
      

        $sheet = $spreadsheet->getSheetByName('Printable');

        
        if(isset($designation)){
            $sheet->setCellValue('F11','Adviser'.' - '.$designation->levelname.' - '.$designation->sectionname);
        }else{
            $sheet->setCellValue('F11','Subject Teacher');
        }

        $sheet->setCellValue('F10',$results[0]->fullname);
        $sheet->setCellValue('A180',$results[0]->fullname);

        $comment_row = 30;
        foreach($results as $result){
            foreach($result->evaluations as $evaluation){
                $comments = collect($evaluation->answer)->where('type','long_answer')->values();
                foreach($comments as $comment){
                    $sheet->setCellValue('C'. $comment_row,$comment->answer);
                    if(strlen($comment->answer) > 178){
                        $sheet->getRowDimension($comment_row)->setRowHeight(50,'in');
                    }
                    else if(strlen($comment->answer) > 89){
                        $sheet->getRowDimension($comment_row)->setRowHeight(71,'in');
                    }
                    $comment_row += 1;
                }
            }
        }

        for($x = $comment_row ; $x <= 170; $x++){
            $sheet->getRowDimension($x)->setVisible(false);
        }

      
       

        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Section 1')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);

        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Printable_Student')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
        
        $sheet = $spreadsheet->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Teacher Evaluation.xlsx"');
        $writer->save("php://output");
        exit();
        
        return $results;

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