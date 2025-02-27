<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class ObservedValuesController extends \App\Http\Controllers\Controller
{
      public static function get_gradelevel(Request $request){

            $syid = $request->get('syid');

            $acad = self::get_acad($syid);

            if(Session::get('currentPortal') == 2){
                  
                  $teacherid = DB::table('teacher')
                                    ->where('deleted',0)
                                    ->where('tid',auth()->user()->email)
                                    ->first();
      
                  $gradelevel = DB::table('gradelevel')
                              ->where('deleted',0)
                              ->whereIn('acadprogid',$acad)
                              ->where('gradelevel.acadprogid','!=',6)
                              ->orderBy('sortid')
                              ->select(
                                    'gradelevel.levelname as text',
                                    'gradelevel.id',
                                    'acadprogid'
                              )
                              ->get(); 
      
            }else{
      
                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->select('id')
                                    ->first()
                                    ->id;
      
                  $gradelevel = DB::table('gradelevel')
                              ->where('deleted',0)
                              ->where('gradelevel.acadprogid','!=',6)
                              ->whereIn('gradelevel.acadprogid',$acad)
                              ->orderBy('sortid')
                              ->select(
                                    'gradelevel.levelname as text',
                                    'gradelevel.id',
                                    'acadprogid'
                              )
                              ->get(); 
            }


            return $gradelevel;

      }


      public static function get_acad($syid = null){

            if(auth()->user()->type == 17){
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

                  if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){

                        $acadprog = DB::table('academicprogram')
                                          ->where('principalid',$teacherid)
                                          ->get();

                  }else{

                        $acadprog = DB::table('teacheracadprog')
                                    ->where('teacherid',$teacherid)
                                    ->where('acadprogutype',Session::get('currentPortal'))
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->select('acadprogid as id')
                                    ->distinct('acadprogid')
                                    ->get();
                  }
            }


            $acadprog_list = array();
            foreach($acadprog as $item){
                  array_push($acadprog_list,$item->id);
            }

            return $acadprog_list;

      }

      public static function get_student_grades_ajax(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');

            return self::get_student_grades($syid,$studid);

      }

      public static function get_student_grades(
            $syid = null,
            $studid = null
      ){

            $student_grade = DB::table('grading_system_grades_cv')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->get();

            return $student_grade;

      }


      public static function store_grades(Request $request){

            $gsdid = $request->get('gsdid');
            $studid = $request->get('studid');
            $quarter = $request->get('quarter');
            $value = $request->get('value');
            $syid = $request->get('syid');

            try{

                  $check_if_exist = DB::table('grading_system_grades_cv')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('gsdid',$gsdid)
                              ->where('deleted',0)
                              ->first();
               
                  if(isset($check_if_exist->id)){
                        $quarter_val = 'q'.$quarter.'eval';
                        DB::table('grading_system_grades_cv')
                                    ->where('id',$check_if_exist->id)
                                    ->update([
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          $quarter_val=>$value
                                    ]);
                        
                  }else{
                        $quarter_val = 'q'.$quarter.'eval';
                        DB::table('grading_system_grades_cv')
                                    ->insert([
                                          'syid'=>$syid,
                                          'gsdid'=>$gsdid,
                                          'studid'=>$studid,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          $quarter_val=>$value
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

            $teacherid = DB::table('teacher')
                              ->where('tid',auth()->user()->email)
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
                        ->select(
                              'levelid',
                              'sectionname',
                              'sectionname as text',
                              'sections.id'
                        )
                        ->get();

            $all_info = array();

            foreach($sections as $item){

                  if($item->levelid == 14 || $item->levelid == 15){
                        $students = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->where('sh_enrolledstud.sectionid',$item->id)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->distinct('studid')
                              ->select(
                                    'sid',
                                    'studid as id',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->orderBy('studentname','asc')
                              ->get();
                  }else{

                        $students = DB::table('enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->where('enrolledstud.sectionid',$item->id)
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.deleted',0)
                              ->select(
                                    'sid',
                                    'studid as id',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->orderBy('studentname','asc')
                              ->get();
                              
                  }

                  

                  foreach($students as $student_item){
                        $middlename = explode(" ",$student_item->middlename);
                        $temp_middle = '';
                        if($middlename != null){
                              foreach ($middlename as $middlename_item) {
                                    if(strlen($middlename_item) > 0){
                                    $temp_middle .= $middlename_item[0].'.';
                                    } 
                              }
                        }
                        $student_item->student=$student_item->lastname.', '.$student_item->firstname.' '.$student_item->suffix.' '.$temp_middle;
                        $student_item->text = $student_item->sid.' - '.$student_item->student;
                  }

                  array_push($all_info,(object)[
                        'levelid'=>$item->levelid,
                        'id'=>$item->id,
                        'sectionname'=>$item->sectionname,
                        'text'=>$item->sectionname,
                        'students'=>$students
                  ]);

            }

            return $all_info;

      }

      //Rating Value
      public static function ratingvalue_list_ajax(Request $request){

            $description = $request->get('description');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $syid = $request->get('syid');
            $gradelevel = $request->get('gradelevel');

            return self::ratingvalue_list($description,$sort,$group,$syid,$gradelevel);

      }

      public static function ratingvalue_list(
            $description = null ,
            $sort = null ,
            $group = null ,
            $syid = null ,
            $gradelevel = null 
      ){


            $list = DB::table('grading_system')
                        ->where('grading_system.type',3)
                        ->where('grading_system.specification',2)
                        ->where('grading_system.deleted',0);
                        
            if($gradelevel != null){
                  $list = $list->where('levelid',$gradelevel);
            }
            if($syid != null){
                  $list = $list->where('syid',$syid);
            }

            $list = $list->join('grading_system_ratingvalue',function($join){
                                    $join->on('grading_system.id','=','grading_system_ratingvalue.gsid');
                                    $join->where('grading_system_ratingvalue.deleted',0);
                              })
                        ->select(
                              'grading_system_ratingvalue.description',
                              'grading_system_ratingvalue.id',
                              'grading_system_ratingvalue.value',
                              'grading_system_ratingvalue.sort',
                              'grading_system.syid',
                              'grading_system.levelid'
                        )
                        ->orderBy('grading_system_ratingvalue.sort')
                        ->get();

            return $list;

      }

      public static function observedvalues_list_v1(){
            $list = DB::table('grading_system')
                        ->where('grading_system.type',3)
                        ->where('grading_system.specification',2)
                        ->where('grading_system.deleted',0)
                        ->where('grading_system.syid',null)
                        ->where('grading_system.levelid',null)
                        ->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                        ->select(
                              'grading_system_detail.description',
                              'grading_system_detail.id',
                              'grading_system_detail.group',
                              'grading_system_detail.sort',
                              'grading_system.syid',
                              'grading_system.levelid',
                              'headerid',
                              'value'
                        )
                        ->orderBy('grading_system_detail.sort')
                        ->get();
            return $list;
      }

      public static function ratingvalue_create_ajax(Request $request){

            $description = $request->get('description');
            $value = $request->get('value');
            $sort = $request->get('sort');
            $syid = $request->get('syid');
            $gradelevel = $request->get('gradelevel');
            $value = $request->get('value');

            return self::ratingvalue_create($description,$sort,$value,$syid,$gradelevel);

      }

      public static function ratingvalue_create(
            $description = null,
            $sort = null,
            $value = null,
            $syid = null,
            $gradelevel = null
      ){

            try{
      
                  $check_header = DB::table('grading_system')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('levelid',$gradelevel)
                                    ->where('type',3)
                                    ->where('specification',2)
                                    ->first();

                  if(!isset($check_header->id)){

                        $headerid = DB::table('grading_system')
                                          ->insertGetId([
                                                'description'=>'Observed Values',
                                                'type'=>3,
                                                'specification'=>2,
                                                'syid'=>$syid,
                                                'levelid'=>$gradelevel,
                                                'isactive'=>1,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                  }else{
                        $headerid = $check_header->id;
                  }

                  $check = DB::table('grading_system_ratingvalue')
                              ->where('deleted',0)
                              ->where('gsid',$headerid)
                              ->where('description',$description)
                              ->count();

                  if($check > 0){

                        return array((object)[
                              'status'=>0,
                              'data'=>'Already Exist!',
                        ]);

                  }

                  
                  DB::table('grading_system_ratingvalue')
                        ->insert([
                              'gsid'=>$headerid,
                              'description'=>$description,
                              'value'=>$value,
                              'sort'=>$sort,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $info = self::ratingvalue_list($description,$sort,$value,$syid,$gradelevel);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'info'=>$info
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }


      public static function ratingvalue_update_ajax(Request $request){

            $id = $request->get('id');
            $description = $request->get('description');
            $sort = $request->get('sort');
            $value = $request->get('value');
            $syid = $request->get('syid');
            $gradelevel = $request->get('gradelevel');

            return self::ratingvalue_update($id,$description,$sort,$value,$syid,$gradelevel);

      }

      public static function ratingvalue_update(
            $id = null ,
            $description = null ,
            $sort = null ,
            $value = null ,
            $syid = null ,
            $gradelevel = null
      ){

            try{
                  
                  DB::table('grading_system_ratingvalue')
                              ->where('id',$id)
                              ->take(1)
                              ->update([
                                    'description'=>$description,
                                    'sort'=>$sort,
                                    'value'=>$value,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  $info = self::ratingvalue_list($description,$sort,$value,$syid,$gradelevel);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!',
                        'info'=>$info
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }


      public static function ratingvalue_delete_ajax(Request $request){

            $id = $request->get('id');
            $description = $request->get('description');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $syid = $request->get('syid');
            $gradelevel = $request->get('gradelevel');

            return self::ratingvalue_delete($id,$description,$sort,$group,$syid,$gradelevel);

      }

      public static function ratingvalue_delete(
            $id = null ,
            $description = null ,
            $sort = null ,
            $group = null ,
            $syid = null ,
            $gradelevel = null 
      ){

            try{

                  $check = DB::table('grading_system_grades_cv')
                                    ->where('deleted',0)
                                    ->where(function($query) use($id){
                                          $query->where('q1eval',$id);
                                          $query->orWhere('q2eval',$id);
                                          $query->orWhere('q3eval',$id);
                                          $query->orWhere('q4eval',$id);
                                    })
                                    ->where('syid',$syid)
                                    ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Already Used!',
                        ]);
                  }
                  
                  DB::table('grading_system_ratingvalue')
                              ->where('id',$id)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  $info = self::ratingvalue_list($description,$sort,$group,$syid,$gradelevel);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!',
                        'info'=>$info
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }



      //Observed Values
      public static function observedvalues_list_ajax(Request $request){

            $description = $request->get('description');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $syid = $request->get('syid');
            $gradelevel = $request->get('gradelevel');

            return self::observedvalues_list($description,$sort,$group,$syid,$gradelevel);

      }

      public static function observedvalues_list(
            $description = null ,
            $sort = null ,
            $group = null ,
            $syid = null ,
            $gradelevel = null 
      ){


            $list = DB::table('grading_system')
                        ->where('grading_system.type',3)
                        ->where('grading_system.specification',2)
                        ->where('grading_system.deleted',0);
                        
            if($gradelevel != null){
                  $list = $list->where('levelid',$gradelevel);
            }
            if($syid != null){
                  $list = $list->where('syid',$syid);
            }

            $list = $list->join('grading_system_detail',function($join){
                                    $join->on('grading_system.id','=','grading_system_detail.headerid');
                                    $join->where('grading_system_detail.deleted',0);
                              })
                        ->select(
                              'grading_system_detail.description',
                              'grading_system_detail.id',
                              'grading_system_detail.group',
                              'grading_system_detail.sort',
                              'grading_system.syid',
                              'grading_system.levelid',
                              'headerid',
                              'value'
                        )
                        ->orderBy('grading_system_detail.sort')
                        ->get();

            return $list;

      }

      public static function copy_to_ajax(Request $request){

            $gradelevel_to = $request->get('gradelevel_to');
            $gradelevel_from = $request->get('gradelevel_from');
            $syid_to = $request->get('syid_to');
            $syid_from = $request->get('syid_from');

            return self::copy_to($gradelevel_to, $gradelevel_from, $syid_to, $syid_from);
      }

      public static function copy_to(
            $gradelevel_to = null,
            $gradelevel_from = null,
            $syid_to = null,
            $syid_from = null
      ){
        
            $observedvalues = self::observedvalues_list(null,null,null,$syid_from,$gradelevel_from);

            $copy_count = 0;

            foreach($observedvalues as $item){

                  $temp_gradelevel = $gradelevel_to != null ?  $gradelevel_to : $item->levelid;
                  $temp_sy = $syid_to != null ? $temp_sy = $syid_to : $item->syid;


                  $status = self::observedvalues_create(
                        $item->description,
                        $item->sort,
                        $item->group,
                        $temp_sy,
                        $temp_gradelevel
                  );

                  if($status[0]->status == 1){
                        $copy_count += 1;
                  }

            }

            $rating_value = self::ratingvalue_list(null,null,null,$syid_from,$gradelevel_from);

            foreach($rating_value as $item){

                  $temp_gradelevel = $gradelevel_to != null ?  $gradelevel_to : $item->levelid;
                  $temp_sy = $syid_to != null ? $temp_sy = $syid_to : $item->syid;

                  $status = self::ratingvalue_create(
                        $item->description,
                        $item->sort,
                        $item->value,
                        $temp_sy,
                        $temp_gradelevel
                  );

                  if($status[0]->status == 1){
                        $copy_count += 1;
                  }

            }

            if($copy_count == 0){
                  return array((object)[
                        'status'=>1,
                        'data'=>'Items are already added!',
                  ]);
            }else{
                  return array((object)[
                        'status'=>1,
                        'data'=>$copy_count.' item(s) Added!',
                  ]);
            }

           

      }


      public static function observedvalues_create_ajax(Request $request){

            $description = $request->get('description');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $syid = $request->get('syid');
            $gradelevel = $request->get('gradelevel');

            return self::observedvalues_create($description,$sort,$group,$syid,$gradelevel);

      }



      public static function observedvalues_create($description,$sort,$group,$syid,$gradelevel){

            try{
      
                  $check_header = DB::table('grading_system')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('levelid',$gradelevel)
                                    ->where('type',3)
                                    ->where('specification',2)
                                    ->first();

                  if(!isset($check_header->id)){

                        $headerid = DB::table('grading_system')
                                          ->insertGetId([
                                                'description'=>'Observed Values',
                                                'type'=>3,
                                                'specification'=>2,
                                                'syid'=>$syid,
                                                'levelid'=>$gradelevel,
                                                'isactive'=>1,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                  }else{
                        $headerid = $check_header->id;
                  }

                  $check = DB::table('grading_system_detail')
                              ->where('deleted',0)
                              ->where('headerid',$headerid)
                              ->where('description',$description)
                              ->count();

                  if($check > 0){

                        return array((object)[
                              'status'=>0,
                              'data'=>'Already Exist!',
                        ]);

                  }

                  
                  DB::table('grading_system_detail')
                        ->insert([
                              'headerid'=>$headerid,
                              'description'=>$description,
                              'value'=>5,
                              'sort'=>$sort,
                              'group'=>$group,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $info = self::observedvalues_list($description,$sort,$group,$syid,$gradelevel);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'info'=>$info
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }
     

      
      public static function observedvalues_update_ajax(Request $request){

            $id = $request->get('id');
            $description = $request->get('description');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $syid = $request->get('syid');
            $gradelevel = $request->get('gradelevel');

            return self::observedvalues_update($id,$description,$sort,$group,$syid,$gradelevel);

      }

      public static function observedvalues_update(
            $id = null ,
            $description = null ,
            $sort = null ,
            $group = null ,
            $syid = null ,
            $gradelevel = null 
      ){

            try{
                  
                  DB::table('grading_system_detail')
                              ->where('id',$id)
                              ->take(1)
                              ->update([
                                    'description'=>$description,
                                    'sort'=>$sort,
                                    'group'=>$group,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  $info = self::observedvalues_list($description,$sort,$group,$syid,$gradelevel);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!',
                        'info'=>$info
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }


      public static function observedvalues_delete_ajax(Request $request){

            $id = $request->get('id');
            $description = $request->get('description');
            $sort = $request->get('sort');
            $group = $request->get('group');
            $syid = $request->get('syid');
            $gradelevel = $request->get('gradelevel');

            return self::observedvalues_delete($id,$description,$sort,$group,$syid,$gradelevel);

      }

      public static function observedvalues_delete(
            $id = null ,
            $description = null ,
            $sort = null ,
            $group = null ,
            $syid = null ,
            $gradelevel = null 
      ){

            try{


                  $check = DB::table('grading_system_grades_cv')
                                    ->where('deleted',0)
                                    ->where('gsdid',$id)
                                    ->where('syid',$syid)
                                    ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Already Used!',
                        ]);
                  }
                  
                  
                  DB::table('grading_system_detail')
                              ->where('id',$id)
                              ->take(1)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  $info = self::observedvalues_list($description,$sort,$group,$syid,$gradelevel);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!',
                        'info'=>$info
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
                  'data'=>'Something went wrong!'
            ]);
      }
      

      public static function create_logs($message = null, $id = null){
           DB::table('logs') 
             ->insert([
                  'dataid'=>$id,
                  'module'=>1,
                  'message'=>$message,
                  'createdby'=>auth()->user()->id,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
      }
      

      

}
