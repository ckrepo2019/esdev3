<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;

class SectionsController extends \App\Http\Controllers\Controller
{
      public static function sctnSelect(Request $request){

            $search = $request->get('search');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');

            $section = DB::table('sectiondetail')
                              ->join('sections',function($join) use($levelid){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                                    $join->where('sections.levelid',$levelid);
                                   
                              })
                              ->where(function($query) use($search){
                                    if($search != null && $search != ""){
                                          $query->orWhere('sections.sectionname','like','%'.$search.'%');
                                    }
                              })
                              ->where('sectiondetail.deleted',0)
                              ->where('sectiondetail.syid',$syid)
                              ->select(
                                    'sections.id',
                                    'sections.sectionname as text'
                              )
                              ->take(10)
                              ->skip($request->get('page')*10)
                              ->get();

            $section_count = DB::table('sectiondetail')
                              ->join('sections',function($join) use($levelid){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                                    $join->where('sections.levelid',$levelid);
                              })
                              ->where(function($query) use($search){
                                    if($search != null && $search != ""){
                                          $query->orWhere('sections.sectionname','like','%'.$search.'%');
                                    }
                              })
                              ->where('sectiondetail.deleted',0)
                              ->where('sectiondetail.syid',$syid)
                              ->count();

          

            return @json_encode((object)[
                  "results"=>$section,
                  "pagination"=>(object)[
                        "more"=>$section_count > ( ( $request->get('page') + 1 ) *10)  ? true :false
                  ],
                  "count_filtered"=>$section_count
            ]);
      }


      public static function print_sid(Request $request){

            $sections = self::all_sections($request);


            $center = [
                  'alignment' => [
                      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                  ]
              ];

              $right = [
                  'alignment' => [
                      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                  ]
              ];

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
        
            foreach(collect($sections)->sortBy('sectionname')->values() as $item ){

                  $worksheet1  = $spreadsheet->createSheet();
                  $worksheet1 ->setTitle($item->sectionname);

                  $request->request->add(['sectionid' => $item->id]);
                  $request->request->add(['levelid' => $item->levelid]);

                  $enrolled = self::enrolled_learners($request);

                  //$row = 2;
                  //$worksheet1->setCellValue('B'.$row,'Section:');
                  //$worksheet1->setCellValue('C'.$row,$item->sectionname);

                 // $worksheet1->getStyle('B'.$row)->applyFromArray($right);

                  $worksheet1->getColumnDimension('A')->setWidth(15);
                 
                  $row = 1;
                  $worksheet1->setCellValue('A'.$row,'ROLLNO');
                  $worksheet1->setCellValue('B'.$row,'NAME');
                  $worksheet1->setCellValue('F'.$row,'CLASS');
                  $worksheet1->setCellValue('G'.$row,'EMAILID');
                  $worksheet1->setCellValue('H'.$row,'PHONENO');

                  $worksheet1->getStyle('A'.$row)->applyFromArray($center);
                  $worksheet1->getStyle('B'.$row)->applyFromArray($center);
                  

                  $row += 1;
                  $count = 1;
                  foreach(collect($enrolled)->sortBy('lastname')->values() as $studitem){


                       

                        $worksheet1->getStyle('A'.$row)->applyFromArray($center);
                        //$worksheet1->getStyle('B'.$row)->applyFromArray($center);

                        //$worksheet1->setCellValue('A'.$row,$count.'.');
                        $worksheet1->setCellValue('A'.$row,$studitem->sid);
                        $worksheet1->setCellValue('B'.$row,$studitem->studentname);
                        $worksheet1->setCellValue('F'.$row,$item->sectionname);
                        // $worksheet1->setCellValue('G'.$row,$item->semail);
                        // $worksheet1->setCellValue('H'.$row,$item->contactno);
                        
                        $row += 1;
                        $count += 1;
                  }
                  

            }

            $spreadsheet->removeSheetByIndex(0);
            $spreadsheet->setActiveSheetIndex(0);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Student SID.xlsx"');
            $writer->save("php://output");
            exit();


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

                  // if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){

                  //       $acadprog = DB::table('academicprogram')
                  //                         ->where('principalid',$teacherid)
                  //                         ->get();

                  // }else{

                        

                        $acadprog = DB::table('teacheracadprog')
                                    ->where('teacherid',$teacherid)
                                    ->where('acadprogutype',Session::get('currentPortal'))
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->select(
                                          'acadprogid as id'
                                    )
                                    ->distinct('acadprogid')
                                    ->get();
                  // }
            }


            $acadprog_list = array();
            foreach($acadprog as $item){
                  array_push($acadprog_list,$item->id);
            }

            return $acadprog_list;

      }

      public static function delete_section(Request $request){
          
            try{

                  $sectionid = $request->get('id');
                  $levelid = $request->get('levelid');

                  if (!Auth::check()) {
                        $userid = $request->get('userid');
                  }else{
                        $userid = auth()->user()->id;
                  }

                  $check_enrollment = self::check_enrolment($sectionid,$levelid);

                  if($check_enrollment > 0){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Contains enrollment record!'
                        ]);
                  }

                  $check_info = DB::table('sections')
                                    ->where('id',$sectionid)
                                    ->where('levelid',$levelid)
                                    ->where('deleted',0)
                                    ->select('id')
                                    ->first();

                  if(!isset($check_info->id)){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Section does not exist!'
                        ]);
                  }

                  DB::table('sections')
                        ->where('id',$sectionid)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>$userid,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('sectiondetail')
                        ->where('sectionid',$sectionid)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>$userid,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                
                  return array((object)[
                        'status'=>1,
                        'data'=>'Section Deleted!'
                  ]);
                  

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function check_enrolment($sectionid = null, $levelid = null, $syid = null){

            if($levelid == 14 || $levelid == 15){

                  $enrolled = DB::table('sh_enrolledstud')
                              ->where('deleted',0);
                        
                  if($syid != null){
                        $enrolled = $enrolled->where('syid',$syid);
                  }
                              
                  $enrolled = $enrolled->where('sectionid',$sectionid)
                              ->whereIn('studstatus',[1,2,4])
                              ->count();

            }else{

                  $enrolled = DB::table('enrolledstud')
                              ->where('deleted',0);

                  if($syid != null){
                        $enrolled = $enrolled->where('syid',$syid);
                  }

                  $enrolled = $enrolled->where('sectionid',$sectionid)
                              ->whereIn('studstatus',[1,2,4])
                              ->count();
            }

            return $enrolled;

      }

      public static function all_sections(Request $request){

            

            $acadprog_list = self::get_acad($request->get('syid'));

            $sections = DB::table('sections')
                              ->join('gradelevel',function($join) use($acadprog_list){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('acadprogid',$acadprog_list);
                              })
                              ->where('sections.deleted',0)
                              ->select(
                                    'sections.id',
                                    'sections.sectionname',
                                    'sections.levelid',
                                    'gradelevel.levelname',
                                    'sections.updateddatetime',
                                    'sections.deleteddatetime'
                              )
                              ->get();

            return $sections;
      }

      public static function add_section_detail(Request $request){


            try{
                  $sectionid = $request->get('sectionid');
                  $syid = $request->get('syid');

                  if (!Auth::check()) {
                        $userid = $request->get('userid');
                  }else{
                        $userid = auth()->user()->id;
                  }


                  $check = DB::table('sectiondetail')
                              ->where('syid',$syid)
                              ->where('sectionid',$sectionid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>1,
                              'data'=>'Section Already Exist'
                        ]);
                  }


                  $sectioninfo = DB::table('sections')
                                    ->where('id',$sectionid)
                                    ->select('sectionname')
                                    ->first();


                  DB::table('sectiondetail')
                        ->insert([
                              'syid'=>$syid,
                              'sectionid'=>$sectionid,
                              'sectname'=>$sectioninfo->sectionname,
                              'deleted'=>0,
                              'createdby'=>$userid,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Section Added'
                  ]);
                        
            }catch(\Exception $e){
                  return self::store_error($e);
            }
            
           
      }


      public static function add_strand_to_section($syid = null, $strandid = null, $sectionid = null , $levelid = null, $userid){

            try{
                      
                $check = DB::table('sh_block')
                            ->where('strandid',$strandid)
                            ->where('levelid',$levelid)
                            ->where('deleted',0)
                            ->select('id')
                            ->first();
    
                if(!isset($check->id)){
    
                    $strand_info = DB::table('sh_strand')
                                        ->where('id',$strandid)
                                        ->select('strandcode')
                                        ->first();
    
                    $blockid = DB::table('sh_block')
                                ->insertGetId([
                                    'blockname'=>$strand_info->strandcode,
                                    'strandid'=>$strandid,
                                    'levelid'=>$levelid,
                                    'createdby'=>$userid,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);
    
    
                }else{
                    $blockid = $check->id;
                }
    
                $check = DB::table('sh_sectionblockassignment')
                            ->join('sh_block',function($join) use($strandid){
                                $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                $join->where('strandid',$strandid);
                                $join->where('sh_block.deleted',0);
                            })
                            ->where('sh_sectionblockassignment.deleted',0)
                            ->where('sh_sectionblockassignment.sectionid',$sectionid)
                            ->where('sh_sectionblockassignment.syid',$syid)
                            ->select('sh_sectionblockassignment.id')
                            ->first();
    
                if(isset($check->id)){
                    return false;
                }
    
                DB::table('sh_sectionblockassignment')
                            ->insert([
                                'syid'=>$syid,
                                'deleted'=>0,
                                'sectionid'=>$sectionid,
                                'blockid'=>$blockid,
                                'createdby'=>$userid,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
    
            }catch(\Exception $e){
                    return self::store_error($e);
            }
      }

      public static function update_section_info(Request $request){

            $syid = $request->get('syid');
            $strand = $request->get('strand');
            $levelid = $request->get('levelid');
            $id = $request->get('sectionid');
            $teacherid = $request->get('teacherid');
            $roomid = $request->get('roomid');
            $session = $request->get('session');
            $sundayclass = $request->get('sundayclass');
            $active = $request->get('active');
            $issp = $request->get('issp');
            $ismwsp = $request->get('ismwsp');

            if (!Auth::check()) {
                  $userid = $request->get('userid');
            }else{
                  $userid = auth()->user()->id;
            }
         

            try{

                  if($strand != null || $strand != ''){

                        $blockarray = array();

                        $blocks = DB::table('sh_block')
                                          ->whereIn('strandid',$strand)
                                          ->where('deleted',0)
                                          ->get();

                        foreach($blocks as $item){
                              array_push($blockarray,$item->id);
                        }

                        DB::table('sh_sectionblockassignment')
                              ->whereNotIn('blockid',$blockarray)
                              ->where('syid',$syid)
                              ->where('sectionid',$id)
                              ->where('deleted',0)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>$userid,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        foreach($strand as $item){
                              self::add_strand_to_section($syid,$item, $id, $levelid,$userid);
                        }

                  }else{
                       
                        DB::table('sh_sectionblockassignment')
                              ->where('sectionid',$id)
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->update([
                                    'deleted'=>1,
                                    'updatedby'=>$userid,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
                

                  DB::table('sections')
                        ->take(1)
                        ->where('id',$id)
                        ->where('deleted',0)
                        ->update([
                              'teacherid'=>$teacherid,
                              'roomid'=>$roomid,
                              'session'=>$session,
                              'sundaySchool'=>$sundayclass,
                              'sectactive'=>$active,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>$userid
                        ]);

                  $check_info = DB::table('sectiondetail')
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->where('sectionid',$id)
                                    ->first();


                  if(isset($check_info->id)){
                        DB::table('sectiondetail')
                              ->where('sectionid',$id)
                              ->where('syid',$syid)
                              ->update([
                                    'sd_ismwsp'=>$ismwsp,
                                    'sd_issp'=>$issp,
                                    'sd_roomid'=>$roomid,
                                    'sd_session'=>$session,
                                    'teacherid'=>$teacherid,
                                    'updatedby'=>$userid,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{

                        $check_info = DB::table('sections')
                                          ->where('id',$id)
                                          ->where('deleted',0)
                                          ->first();

                        DB::table('sectiondetail')
                              ->insert([
                                    'sd_ismwsp'=>$ismwsp,
                                    'sd_issp'=>$issp,
                                    'sectname'=>$check_info->sectionname,
                                    'sectionid'=>$id,
                                    'semid'=>1,
                                    'syid'=>$syid,
                                    'teacherid'=>$teacherid,
                                    'sd_roomid'=>$roomid,
                                    'sd_session'=>$session,
                                    'deleted'=>0,
                                    'createdby'=>$userid,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
                        
                

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      
      }

      //attendance setup start
      public static function list_ajax(Request $request){
            $id = $request->get('id');
            $levelid = $request->get('leveleid');
            $syid = $request->get('syid');


          

            return self::list($id, $levelid, $syid);
      }

      public static function create_ajax(Request $request){
            $sectionname = $request->get('sectionname');
            $levelid = $request->get('levelid');
            $teacherid = $request->get('teacherid');
            $roomid = $request->get('roomid');
            $session = $request->get('session');
            $sundayclass = $request->get('sundayclass');
            $active = $request->get('active');
            $syid = $request->get('syid');
            $userid = $request->get('userid');

            if(Auth::check()){
                  $userid = auth()->user()->id;
            }

            return self::section_create($sectionname, $levelid, $teacherid, $roomid, $session, $sundayclass, null, $syid, $userid);
      }
      public static function update_ajax(Request $request){
            $id = $request->get('id');
        
            $sectionname = $request->get('sectionname');
            $levelid = $request->get('levelid');
            $teacherid = $request->get('teacherid');
            $roomid = $request->get('roomid');
            $session = $request->get('session');
            $sundayclass = $request->get('sundayclass');
            $active = $request->get('active');
            $userid = $request->get('userid');

            if(Auth::check()){
                  $userid = auth()->user()->id;
            }

            return self::section_update($id, $sectionname, $levelid, $teacherid, $roomid, $session, $sundayclass, $active,$userid);
      }
      public static function delete_ajax(Request $request){
            $id = $request->get('id');
            $syid = $request->get('syid');

            if(!Auth::check()){
                  $userid = $request->get('userid');
            }else{
                  $userid = auth()->user()->id;
            }

            return self::section_delete($id,$syid,$userid);
      }
      //attendance setup end

      //proccess
      public static function section_create(
            $sectionname = null,
            $levelid = null, 
            $teacherid = null, 
            $roomid = null, 
            $session = null, 
            $sundayclass = null, 
            $active = null,
            $syid = null,
            $userid = null
      ){
            try{

                  $check = DB::table('sections')
                              ->where('deleted',0)
                              ->where('sectionname',$sectionname)
                              ->where('levelid',$levelid)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Already Exist!',
                        ]); 
                  }



                  $section_id = DB::table('sections')
                        ->insertGetId([
                              'sectionname'=>$sectionname,
                              'levelid'=>$levelid,
                              'sectactive'=>1,
                              'deleted'=>0,
                              'createdby'=>$userid,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                 

                  DB::table('sectiondetail')
                        ->insert([
                              'sectionid'=>$section_id,
                              'syid'=>$syid,
                              'semid'=>1,
                              'sectname'=>$sectionname,
                              'deleted'=>0,
                              'createdby'=>$userid,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  // $sections = self::list();
                  $message = $userid.' added '.$sectionname.' section';
                  
                  self::create_logs($message,$section_id,$userid);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        // 'info'=> $sections
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function section_update(
            $id = null,
            $sectionname = null,
            $levelid = null, 
            $teacherid = null, 
            $roomid = null, 
            $session = null, 
            $sundayclass = null, 
            $active = null,
            $syid = null,
            $userid = null
      ){
            try{
                  $check = DB::table('sections')
                              ->where('deleted',0)
                              ->where('id','!=',$id)
                              ->where('sectionname',$sectionname)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'Already Exist!',
                        ]); 
                  }

                  $section_info = DB::table('sections')
                                    ->where('deleted',0)
                                    ->where('id',$id)
                                    ->first();

                  if($section_info->levelid == 14 || $section_info->levelid == 15){

                        $check_enrollment = Db::table('sh_enrolledstud')
                                    ->where('deleted',0)
                                    ->where('sectionid',$id)
                                    ->count();
                  }else{

                        $check_enrollment = Db::table('enrolledstud')
                                    ->where('deleted',0)
                                    ->where('sectionid',$id)
                                    ->count();
                  }


                  if($syid == null){
                        $syid = DB::table('sy')->where('isactive',1)->first()->id;
                  }

                  if($check_enrollment > 0){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Contains Enrolled Learners!',
                        ]); 
                  }

                  DB::table('sections')
                        ->take(1)
                        ->where('id',$id)
                        ->where('deleted',0)
                        ->update([
                              'sectionname'=>$sectionname,
                              'levelid'=>$levelid,
                              'sectactive'=>1,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>$userid
                        ]);

                  $message = $userid.' updated '.$sectionname.' section';
                  self::create_logs($message,$id);

                  // $sections = self::list();

                  return array((object)[
                        'status'=>1,
                        // 'info'=>$sections,
                        'data'=>'Updated Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      
      public static function section_delete(
            $id = null,
            $syid = null,
            $userid = null
      ){
            try{

                  $section_info = DB::table('sections')
                                    ->where('deleted',0)
                                    ->where('id',$id)
                                    ->first();

                  if($section_info->levelid == 14 || $section_info->levelid == 15){

                        $check_enrollment = Db::table('sh_enrolledstud')
                                    ->where('deleted',0)
                                    ->where('sectionid',$id)
                                    ->where('syid',$syid)
                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                    ->count();
                  }else{

                        $check_enrollment = Db::table('enrolledstud')
                                    ->where('deleted',0)
                                    ->where('sectionid',$id)
                                    ->where('syid',$syid)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->count();
                  }

                  if($check_enrollment > 0){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Contains Enrolled Learners!',
                        ]); 
                  }

                  if($section_info->levelid == 14 || $section_info->levelid == 15){
                       

                        $check = DB::table('sh_classsched')
                                    ->where('syid',$syid)
                                    ->where('sectionid',$id)
                                    ->where('deleted',0)
                                    ->count();
                                    
                        if($check > 0){
                              return array((object)[
                                    'status'=>2,
                                    'data'=>'Contains Schedule!',
                              ]); 
                        }

                  }else{

                        $check = DB::table('assignsubj')
                                    ->where('syid',$syid)
                                    ->where('sectionid',$id)
                                    ->where('deleted',0)
                                    ->count();

                        if($check > 0){
                              return array((object)[
                                    'status'=>2,
                                    'data'=>'Contains Schedule!',
                              ]); 
                        }

                        $check = DB::table('classsched')
                                    ->where('syid',$syid)
                                    ->where('sectionid',$id)
                                    ->where('deleted',0)
                                    ->count();

                        if($check > 0){
                              return array((object)[
                                    'status'=>2,
                                    'data'=>'Contains Schedule!',
                              ]); 
                        }

                  }

                  

                  // $check = DB::table('grades')
                  //             ->where('syid',$syid)
                  //             ->where('sectionid',$id)
                  //             ->where('deleted',0)
                  //             ->count();

                  // if($check > 0){
                  //       return array((object)[
                  //             'status'=>2,
                  //             'data'=>'Contains Grades!',
                  //       ]); 
                  // }


                  // $section_setup = self::list($id, null, $syid);

                  DB::table('sectiondetail')
                        ->take(1)
                        ->where('sectionid',$id)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'deletedby'=>$userid
                        ]);
                        
                  $message = $userid.' removed '.$id.' section';
                  self::create_logs($message,$id);

                  // $section_setup = self::list(null,null,$syid);

                  return array((object)[
                        'status'=>1,
                        // 'info'=>$section_setup,
                        'data'=>'Updated Successfully!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function gradelevel_list(Request $request){

            $acadprog_list = self::get_acad($request->get('syid'));

            $gradelevel = DB::table('gradelevel')
                              ->where('deleted',0)
                              ->whereIn('acadprogid',$acadprog_list)
                              ->whereNotIn('acadprogid',[6,7])
                              ->orderBy('sortid')
                              ->select(
                                    'id',
                                    'levelname as text',
                                    'levelname'
                              )
                              ->get();

            return $gradelevel;

      }


      public static function teachers_list(Request $request){

            $acadprog_list = self::get_acad($request->get('syid'));

            

            $teacher_list = array();
            $teacher_array = array();

            $teacher_acad = DB::table('teacheracadprog')
                              ->where('deleted',0)
                              ->where('acadprogutype',1)
                              ->where('syid',$request->get('syid'))
                              ->whereIn('acadprogid',$acadprog_list)
                              ->select('teacherid')
                              ->get();

            foreach($teacher_acad as $item){
                  array_push($teacher_array,$item->teacherid);
            }
            
            
            $teacher = DB::table('teacher')
                              ->where('deleted',0)
                              ->where('usertypeid',1)
                              ->where('isactive',1)
                              ->whereIn('id',$teacher_array)
                              ->select(
                                    'id',
                                    'tid',
                                    'lastname',
                                    'firstname',
                                    DB::raw("CONCAT(tid,' - ',lastname,', ',firstname) as text")
                              )
                              ->get(); 
      
            foreach($teacher as $item){
                  array_push($teacher_list,$item);
            }
      
            $teacher = DB::table('faspriv')
                              ->where('faspriv.deleted',0)
                              ->where('faspriv.usertype',1)
                              ->join('teacher',function($join) use($teacher_array){
                                    $join->on('faspriv.userid','=','teacher.userid');
                                    $join->where('teacher.deleted',0);
                                    $join->where('teacher.isactive',1);
                                    $join->whereIn('teacher.id',$teacher_array);
                              })
                              ->select(
                                    'teacher.id',
                                    'tid',
                                    'lastname',
                                    'firstname',
                                    DB::raw("CONCAT(tid,' - ',lastname,', ',firstname) as text")
                              )
                              ->get(); 

           
      
            foreach($teacher as $item){
                  array_push($teacher_list,$item);
                 
            }


      
            $teacher = $teacher_list;

            return $teacher;

      }
      
      public static function copy_section(Request $request){

            try{

                  $syid_from = $request->get('syid_from');
                  $syid_to = $request->get('syid_to');

                  $check_sy = DB::table('sy')
                                    ->where('id',$syid_to)
                                    ->first();

                  if($check_sy->ended == 1){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Selected school year has ended!'
                        ]);

                  }

      
                  $section_detail_from = DB::table('sectiondetail')
                                          ->where('deleted',0)
                                          ->where('syid',$syid_from)
                                          ->get();

                  $section_detail_to = DB::table('sectiondetail')
                                          ->where('syid',$syid_to)
                                          ->where('deleted',0)
                                          ->get();
      
                  foreach($section_detail_from as $item){
                        
                        $check = collect($section_detail_to)
                                    ->where('sectionid',$item->sectionid)
                                    ->count();

                        if($check == 0){

                              DB::table('sectiondetail')
                                    ->insert([
                                          'sectionid'=>$item->sectionid,
                                          'syid'=>$syid_to,
                                          'sectname'=>$item->sectname,
                                          'teacherid'=>$item->teacherid,
                                          'sd_roomid'=>$item->sd_roomid,
                                          'sd_session'=>$item->sd_session,
                                          'deleted'=>0,
                                          'semid'=>1,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
      
                  }
      
                  $strand_detail_from = DB::table('sh_sectionblockassignment')
                                          ->where('deleted',0)
                                          ->where('syid',$syid_from)
                                          ->get();

                  $strand_detail_to = DB::table('sh_sectionblockassignment')
                                          ->where('syid',$syid_to)
                                          ->where('deleted',0)
                                          ->get();
      
                  foreach($strand_detail_from as $item){
                        
                        $check = collect($strand_detail_to)
                                    ->where('sectionid',$item->sectionid)
                                    ->where('blockid',$item->blockid)
                                    ->count();

                        if($check == 0){

                              DB::table('sh_sectionblockassignment')
                                    ->insert([
                                          'sectionid'=>$item->sectionid,
                                          'syid'=>$syid_to,
                                          'blockid'=>$item->blockid,
                                          'deleted'=>0,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
      
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Information Copied!'
                  ]);
                  

            }catch(\Exception $e){
                  return self::store_error($e);
            }

            
          
      
      }

      public static function enrolled_learners(Request $request){
            
            $syid = $request->get('syid');
            $sectionid = $request->get('sectionid');
            $levelid = $request->get('levelid');

           

            if($levelid == 14 || $levelid == 15){
                  $enrolled = DB::table('sh_enrolledstud')
                                    ->join('studinfo',function($join){
                                          $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->leftJoin('sh_strand',function($join){
                                          $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                          $join->where('sh_strand.deleted',0);
                                    })
                                    ->join('studentstatus',function($join){
                                          $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                                    })
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->where('sh_enrolledstud.sectionid',$sectionid)
                                    ->distinct('studid')
                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                          'description',
                                          'strandcode',
                                          'strandname',
                                          'firstname',
                                          'middlename',
                                          'lastname',
                                          'sid',
                                          'contactno',
                                          'semail',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                    )
                                    ->get();


                  

            }else{
                  $enrolled = DB::table('enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('studentstatus',function($join){
                                    $join->on('enrolledstud.studstatus','=','studentstatus.id');
                              })
                              ->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.sectionid',$sectionid)
                              ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->select(
                                    'description',
                                    'firstname',
                                          'middlename',
                                          'lastname',
                                    'sid',
                                    'contactno',
                                          'semail',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->get();
            }

            return $enrolled;
                 
      }

      //data
      public static function list($id = null, $levelid = null, $syid = null){


            $acadprog_list = self::get_acad($syid);
            $teacherinfo = DB::table('teacher')->where('userid',auth()->user()->id)->first();


            $sections = DB::table('sectiondetail')
                              ->join('sections',function($join) use($acadprog_list){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($acadprog_list){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                    $join->whereIn('acadprogid',$acadprog_list);
                              })
                              ->leftJoin('teacher',function($join){
                                    $join->on('sectiondetail.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->leftJoin('rooms',function($join){
                                    $join->on('sectiondetail.sd_roomid','=','rooms.id');
                                    $join->where('rooms.deleted',0);
                              })
                              ->where('sectiondetail.syid',$syid)
                              ->where('sectiondetail.deleted',0)
                              ->where(function($query) use($teacherinfo){
                                    if(Session::get('currentPortal') == 1){
                                          $query->where('sectiondetail.teacherid',$teacherinfo->id);
                                    }
                              });

            if($id != null){
                  $sections = $sections->where('sectiondetail.sectionid',$id);
            }
            if($levelid != null){
                  $sections = $sections->where('sections.levelid',$levelid);
            }

            $sections = $sections
                        ->select(
                              'sectiondetail.id',
                              'sectiondetail.sd_roomid as roomid',
                              'sections.teacherid',
                              'sectiondetail.sd_session as session',
                              'sections.sundaySchool',
                              'sections.levelid',
                              'sections.sectionname',
                              'gradelevel.levelname',
                              'sectionid',
                              'capacity',
                              'firstname',
                              'lastname',
                              'middlename',
                              'roomname',
                              'title',
                              'sectactive',
                              'suffix',
                              'sortid',
                              'tid',
                              'sectiondetail.teacherid',
                              'lastname',
                              'firstname',
                              'middlename',
                              'title',
                              'suffix',
                              'sections.updateddatetime as sections_updateddatetime',
                              'sections.deleteddatetime as sections_deleteddatetime',
                              'sectiondetail.updateddatetime as sectiondetail_updateddatetime',
                              'sectiondetail.deleteddatetime as sectiondetail_deleteddatetime',
                              'sections.id as sections_id',
                              'sd_issp',
                              'sd_ismwsp',
							  'sectionname as text'
                        )
                        ->get();

            if($syid == null){
                  $syid = DB::table('sy')->where('isactive',1)->first()->id;
            }else{
                  $syid = DB::table('sy')->where('id',$syid)->first()->id;
            }

            foreach($sections as $item){

                  if($item->levelid == 14 || $item->levelid == 15){


                        $enrolled = DB::table('sh_enrolledstud')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('sectionid',$item->sectionid)
                                    ->distinct('studid')
                                    ->whereIn('studstatus',[1,2,4])
                                    ->count();

                        $strand = DB::table('sh_sectionblockassignment')
                                    ->join('sh_block',function($join){
                                          $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                          $join->where('sh_block.deleted',0);
                                    })
                                    ->where('sh_sectionblockassignment.syid',$syid)
                                    ->where('sh_sectionblockassignment.sectionid',$item->sectionid)
                                    ->where('sh_sectionblockassignment.deleted',0)
                                    ->select(
                                          'blockname',
                                          'strandid',
                                          'strandid as id',
                                          'blockname as text'
                                    )
                                    ->get();

                        $item->strand = $strand;

                  }else{
                        $enrolled = DB::table('enrolledstud')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('sectionid',$item->sectionid)
                                    ->whereIn('studstatus',[1,2,4])
                                    ->count();
                  }

                  if(isset($item->teacherid)){
                        
                        if(isset($item->teacherid)){
                              $temp_title = '';
                              $temp_middle = '';
                              $temp_suffix = '';
                              if(isset($item->middlename)){
                                    $temp_middle = $item->middlename[0].'.';
                              }
                              if(isset($item->title)){
                                    $temp_title = $item->title.'. ';
                              }
                              if(isset($item->suffix)){
                                    $temp_suffix = ', '.$item->suffix;
                              }
                              $item->teacherid = $item->teacherid;
                              $item->teacher  = $temp_title.$item->firstname.' '.$temp_middle.' '.$item->lastname.$temp_suffix;
                        }else{
                              $item->teacher  = 'No adviser';
                        }
                       
                  }else{
                        $item->teacher  = 'No adviser';
                  }

                  if($item->session == 1){
                        $item->sessiondesc = 'Morning';
                  }elseif($item->session == 2){
                        $item->sessiondesc = 'Afternoon';
                  }
                  elseif($item->session == 3){
                        $item->sessiondesc = 'Night';
                  }else{
                        $item->sessiondesc = 'Whole Day';
                  }
                  $item->enrolled = $enrolled;
            }

            return $sections;
            
      }


      public static function logs($syid = null){
            return DB::table('logs')->where('module',1)->get();
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

      public static function create_logs($message = null, $id = null,$userid = null){

            if(Auth::check()){
                  $userid = auth()->user()->id;
            }

           DB::table('logs') 
             ->insert([
                  'dataid'=>$id,
                  'module'=>4,
                  'message'=>$message,
                  'createdby'=>$userid,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
      }


      
     
      

}
