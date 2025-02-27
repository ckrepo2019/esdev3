<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use PDF;
use Illuminate\Support\Facades\Validator;

class PreSchoolGradingController extends \App\Http\Controllers\Controller
{

      public static function studentinfo_list(){
            return array(
                        (object)['desc'=>'Student Name','def'=>'studname','withdefault'=>1],
                        (object)['desc'=>'Date of Birth','def'=>'dob','withdefault'=>1],
                        (object)['desc'=>'Gender','def'=>'gender','withdefault'=>1],
                        (object)['desc'=>'School Year','def'=>'sy','withdefault'=>1],
                        (object)['desc'=>'Adviser','def'=>'adviser','withdefault'=>1],
                        (object)['desc'=>'Principal','def'=>'principal','withdefault'=>1],
                        (object)['desc'=>'Address','def'=>'address','withdefault'=>1],
            );
      }
    
    public static function pdf_format(Request $request){

      $studid = $request->get('studid');
      $syid = $request->get('syid');
      $levelid = $request->get('levelid');
      $studinfolist = self::studentinfo_list();

      $PHPWord = new \PhpOffice\PhpWord\PhpWord();

      $wordtemplate = DB::table('grading_system')
                        ->join('grading_system_detail',function($join){
                              $join->on('grading_system.id','=','grading_system_detail.headerid');
                              $join->where('grading_system_detail.deleted',0);
                        })
                        ->where('levelid',$levelid)
                        ->where('syid',$syid)
                        ->where('type',7)
                        ->where('grading_system.deleted',0)
                        ->get();

      if(count($wordtemplate) == 0){
            return "No word template uploaded.";
      }


      $document = $PHPWord->loadTemplate( $wordtemplate[0]->description);
      $setups = self::grading_setup_list($request);
	  
	 
      foreach($setups as $setup){
            $setupdetail = self::get_preschool_setup($syid, $levelid,$setup->id);
			
			$grades = self::get_student_grades_data($studid,$syid, $setupdetail[0]->detail, $setup->type);
			
            foreach($setupdetail[0]->detail as $item){
                  $cellvalue = collect($setupdetail[0]->cellvalue)->where('gsdid',$item->id)->first();
				  
				 
                  if(isset($cellvalue)){
                        $row_grade = collect($grades)->where('gsdid',$item->id)->first();
						// return collect($setupdetail[0]->cellvalue);
                        if(isset($row_grade)){
                              for($x=1; $x<=4;$x++){
									$celval = 'q'.$x.'cellval';
                                    $gradeval = 'q'.$x.'evaltext';
                                    if($cellvalue->$celval != null){
                                          $cell = str_replace('${','',$cellvalue->$celval);
                                          $cell = str_replace('}','',$cell);
                                          
                                          if($item->description == 'SCALED SCORE'){	
                                                $document->setValue($cell, $row_grade->$gradeval);
                                          }else{
                                                      if($setup->type == 1 || $setup->type == 2){
                                                            if($row_grade->$gradeval){
                                                                        $document->setValue($cell, '/');
                                                            }else{
                                                                        $document->setValue($cell, '');
                                                            }
                                                            
                                                      }elseif($setup->type == 5){
															$cellgradevalue = '';
															if(isset($row_grade->$gradeval)){
																$cellgradevalue = $row_grade->$gradeval;
															}
															$document->setValue($cell, ' '.$cellgradevalue.' ');
                                                      }else{
                                                      }
                                          }
                                    }
                              }
                        }else{
					if($item->description == 'TOTAL'){
                                    $detailid = collect($setupdetail[0]->detail)
                                                      ->whereNotIn('description',['SCALED SCORE','TOTAL'])
                                                      ->where('group',$item->group)
                                                      ->pluck('id');
                                   
                                    for($x=1; $x<=4;$x++){
                                          $celval = 'q'.$x.'cellval';
                                          $gradeval = 'q'.$x.'evaltext';
                                          try{
                                                $totalgrades = collect($grades)->whereIn('gsdid',$detailid)->sum($gradeval);
												  
												//return collect($grades)->whereIn('gsdid',$detailid)->values();
                                          }catch(\Exception $e){
                                                $totalgrades = '';
                                          }
                                          if($totalgrades == 0){
                                                $totalgrades = '';
                                          }
                                          $cell = str_replace('${','',$cellvalue->$celval);
                                          $cell = str_replace('}','',$cell);
										  
                                          $document->setValue($cell, $totalgrades);
                                    }
                                  
                              }if($item->value == 0 && $item->group == null){
                                    
                                    $sub_domain = collect($setupdetail[0]->detail)
                                                      ->where('group',$item->sort)
                                                      ->where('value',0)
                                                      ->pluck('sort');
                                                                                          
                                    if(count($sub_domain) > 0){
                                          $detailid = collect($setupdetail[0]->detail)
                                                            ->whereNotIn('description',['SCALED SCORE','TOTAL'])
                                                            ->whereIn('group',$sub_domain)
                                                            ->pluck('id');
                                                            
                                    }else{
                                          $detailid = collect($setupdetail[0]->detail)
                                                            ->whereNotIn('description',['SCALED SCORE','TOTAL'])
                                                            ->where('group',$item->sort)
                                                            ->pluck('id');
                                    }
								 
                                    for($x=1; $x<=4;$x++){
                                          $celval = 'q'.$x.'cellval';
                                          $gradeval = 'q'.$x.'evaltext';
                                          try{
                                                $totalgrades = collect($grades)->whereIn('gsdid',$detailid)->sum($gradeval);
                                          }catch(\Exception $e){
                                                $totalgrades = '';
                                          }
                                          if($totalgrades == 0){
                                                $totalgrades = '';
                                          }
                                          $cell = str_replace('${','',$cellvalue->$celval);
                                          $cell = str_replace('}','',$cell);
										  
                                          $document->setValue($cell, $totalgrades);
                                    }
								  
                              }else{
                                    for($x=1; $x<=4;$x++){
                                          $celval = 'q'.$x.'cellval';
                                          $cell = str_replace('${','',$cellvalue->$celval);
                                          $cell = str_replace('}','',$cell);
                                          $document->setValue($cell, '');
                                    }
                              }

                             
                        }
                  }
            }
      }

     


      $document->saveAs("Result.docx");

      $file_url = 'Result.docx';
      header('Content-Type: application/octet-stream');  
      header("Content-Transfer-Encoding: utf-8");   
      header("Content-disposition: attachment; filename=Result.docx");   
      readfile($file_url);  

      exit(); 


      return "sdfsdf";

            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');

            if($studid == null){
                  return "No student selected.";
            }

            $student = DB::table('studinfo')
                           ->where('id',$studid)
                           ->select(
                                 'id',
                                 'gender',
                                 'firstname',
                                 'lastname',
                                 'suffix',
                                 'middlename',
                                 'dob'
                           )
                           ->first();   

            $grades = self::get_student_grades_data($studid,$syid);
            $setup = self::get_preschool_setup($syid,$levelid);
       
            
            $section = Db::table('enrolledstud')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->join('sections',function($join){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->where('enrolledstud.deleted',0)
                              ->select(
                                    'sectionname',
                                    'sections.id'
                                    )
                              ->first();

            foreach($setup as $item){
                  $grade = array((object)[
                        'q1grade'=>null,
                        'q2grade'=>null,
                        'q3grade'=>null,
                        'q4grade'=>null,
                  ]);
                  $row_grade = collect($grades)->where('gsdid',$item->id)->values();
                  if(count($row_grade) > 0){
                        $grade = array((object)[
                              'q1grade'=>$row_grade[0]->q1evaltext,
                              'q2grade'=>$row_grade[0]->q2evaltext,
                              'q3grade'=>$row_grade[0]->q3evaltext,
                              'q4grade'=>$row_grade[0]->q4evaltext,
                        ]);
                  }
                  $item->grade = $grade;
            }

           
            $sectioninfo = DB::table('sectiondetail')
                            ->where('sectionid',$section->id)
                            ->where('syid',$syid)
                            ->join('teacher',function($join){
                                $join->on('sectiondetail.teacherid','=','teacher.id');
                                $join->where('teacher.deleted',0);
                            })
                            ->select(
                                'lastname',
                                'firstname',
                                'middlename',
                                'suffix',
                                'teacherid'
                            )
                            ->get();

            $adviser = '';
            $teacherid = null;

            foreach($sectioninfo as $item){
                  $temp_middle = '';
                  if($item->middlename != null){
                        $temp_middle = $item->middlename[0].'.';
                  }
                  $adviser = $item->firstname.' '.$temp_middle.' '.$item->lastname.' '.$item->suffix;
                  $teacherid = $item->teacherid;
                  $item->checked = 0;

            }

            $schoolyear = Db::table('sy')
                              ->where('id',$syid)
                              ->first();

            if($syid == 3){
                  $all_ages = array((object)[
                        'b_y'=>\Carbon\Carbon::parse($student->dob)->diff(\Carbon\Carbon::now())->format('%y'),
                        'b_m'=>\Carbon\Carbon::parse($student->dob)->diff(\Carbon\Carbon::now())->format('%m'),
                        'e_y'=>\Carbon\Carbon::parse($student->dob)->diff(\Carbon\Carbon::now())->format('%y'),
                        'e_m'=>\Carbon\Carbon::parse($student->dob)->diff(\Carbon\Carbon::now())->format('%m')
                  ]);
                  $principal = "";
            }else {
                  $all_ages = array((object)[
                        'b_y'=>"",
                        'b_m'=>"",
                        'e_y'=>"",
                        'e_m'=>""
                  ]);
                  $principal = "ALFIE P. BILLION";
            }
			
			

            //Attendance
            $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($syid);

            foreach( $attendance_setup as $item){

                  $sf2_setup = DB::table('sf2_setup')
                              ->where('month',$item->month)
                              ->where('year',$item->year)
                              ->where('sectionid',$section->id)
                              ->where('sf2_setup.deleted',0)
                              ->join('sf2_setupdates',function($join){
                                    $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                    $join->where('sf2_setupdates.deleted',0);
                              })
                              ->select('dates')
                              ->get();

                  if(count($sf2_setup) == 0){

                  $sf2_setup = DB::table('sf2_setup')
                              ->where('month',$item->month)
                              ->where('year',$item->year)
                              ->where('teacherid',$teacherid)
                              ->where('sf2_setup.deleted',0)
                              ->join('sf2_setupdates',function($join){
                                    $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                    $join->where('sf2_setupdates.deleted',0);
                              })
                              ->select('dates')
                              ->get();

                  }

                  $temp_days = array();

                  foreach($sf2_setup as $sf2_setup_item){
                        array_push($temp_days,$sf2_setup_item->dates);
                  }

                  $student_attendance = DB::table('studattendance')
                                          ->where('studid',$studid)
                                          ->where('deleted',0)
                                          ->whereIn('tdate',$temp_days)
                                          ->select([
                                          'present',
                                          'absent',
                                          'tardy',
                                          'cc'
                                          ])
                                          ->get();

                  $item->present = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count();
                  $item->absent = collect($student_attendance)->where('absent',1)->count();
            
            }
            
            
            $sydirectory = explode('-',$schoolyear->sydesc);
            
            
            //return $setup;
            
            $schoolinfo = DB::table('schoolinfo')->get();
           
            if($levelid == 2){
                $pdf = PDF::loadView('principalsportal.forms.sf9layout.ndm.'.$sydirectory[0].'.kinder1',compact('schoolinfo','syid','student','grades','setup','section','all_ages','adviser','principal','age_setup','remarks_setup','attendance_setup'))->setPaper('legal','landscape');
                $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            }else{
                $pdf = PDF::loadView('principalsportal.forms.sf9layout.ndm.'.$sydirectory[0].'.kinder2',compact('schoolinfo','syid','student','grades','setup','section','all_ages','adviser','principal','age_setup','remarks_setup','attendance_setup'))->setPaper('legal','landscape');
                $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            }
            
    
            return $pdf->stream();
      }

     public static function get_student_grades_data($studid = null, $syid = null, $detailid = array(), $type = null){
		 

            $student_grade = DB::table('grading_system_pgrades');
			
			if(count($detailid) > 0){
				$student_grade = $student_grade->whereIn('gsdid', collect($detailid)->pluck('id'));
			}
			
            $student_grade = $student_grade->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->get();
							  
						  
							  
			if($type == 5){
				
				$studinfo = DB::table('studinfo')
								->where('id',$studid)
								->first();
								
				$enrollment_info = DB::table('enrolledstud')
										->where('studid',$studid)
										->where('syid',$syid)
										->where('deleted',0)
										->select(
											'sectionid'
										)
										->first();
										
				 $sectioninfo = DB::table('sectiondetail')
									->where('sectionid',$enrollment_info->sectionid)
									->where('syid',$syid)
									->join('teacher',function($join){
										$join->on('sectiondetail.teacherid','=','teacher.id');
										$join->where('teacher.deleted',0);
									})
									->select(
										'lastname',
										'firstname',
										'middlename',
										'suffix',
										'teacherid',
										'title',
										'acadtitle'
										
									)
									->get();

				$adviser = '';
				$teacherid = null;
				foreach($sectioninfo as $item){
					$temp_middle = '';
					$temp_suffix = '';
					$temp_title = '';
					$temp_acadtitle = '';
					if(isset($item->middlename)){
						$temp_middle = $item->middlename[0].'.';
					}
					if(isset($item->title)){
						$temp_title = $item->title.'. ';
					}
					if(isset($item->suffix)){
						$temp_suffix = ', '.$item->suffix;
					}
					if(isset($item->acadtitle)){
						$temp_acadtitle = ', '.$item->acadtitle;
					}
					$adviser = $temp_title.$item->firstname.' '.$temp_middle.' '.$item->lastname.$temp_suffix.$temp_acadtitle;
				}						
							
				$middlename = explode(" ",$studinfo->middlename);							
				$temp_middle = '';
				
				if($middlename != null){
						foreach ($middlename as $middlename_item) {
							  if(strlen($middlename_item) > 0){
									$temp_middle .= $middlename_item[0].'. ';
							  } 
						}
				}

				if(isset($studinfo->suffix)){
						foreach ($middlename as $middlename_item) {
							  if(strlen($middlename_item) > 0){
									$temp_middle .= $middlename_item[0].'. ';
							  } 
						}
				}
				
				$principal = null;

				$signatory = DB::table('signatory')
								->where('form','report_card')
								->where('syid',$syid)
								->where('acadprogid',2)
								->where('deleted',0)
								->select(
									'name',
									'title'
								)
								->first();

				if(isset($signatory->name)){
					$principal = $signatory->name;
				}
			
				$dob = \Carbon\Carbon::create($studinfo->dob)->isoFormat('MMMM DD, YYYY');
				$gender = $studinfo->gender;
				$studname = $studinfo->firstname.' '.$temp_middle.$studinfo->lastname.' '.$studinfo->suffix;
			
				$studinfodetail = self::studentinfo_list();
				
				$address = '';
				if(strlen($studinfo->street) > 0){
					$address .= $studinfo->street;
				}
				if(strlen($studinfo->barangay) > 0){
					if($address != ''){
						$address .=', '.$studinfo->barangay;
					}else{
						$address .=$studinfo->barangay;
					}
				}
				if(strlen($studinfo->city) > 0){
					if($address != ''){
						$address .= ', '.$studinfo->city;
					}else{
						$address .=$studinfo->city;
					}
				}
				if(strlen($studinfo->province) > 0){
					if($address != ''){
						$address .= ', '.$studinfo->province;
					}else{
						$address .=$studinfo->province;
					}
				}

				
				
				$student_grade = collect($student_grade)->toArray();
		
				foreach($studinfodetail as $item){
					$get_detail = collect($detailid)->where('description',$item->desc)->first();
				
					if(isset($get_detail)){
							
						$get_grades = collect($student_grade)->where('gsdid',$get_detail->id)->first();
						
						if(isset($get_grades)){
						
						}else{
							$tempstring = $item->def;
							array_push($student_grade,(object)[
								"syid"=> $syid,
								"studid"=> $studid,
								"gsdid"=> $get_detail->id,
								"q1evaltext"=> $$tempstring
							]);
						}
					}
					
				}

			}
		
            return $student_grade;


      }


      public static function get_student_grades(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');
			$levelid = $request->get('levelid');
			   
			$setups = self::grading_setup_list($request);
			$temp_grades = array();
	
			foreach($setups as $setup){
				$setupdetail = self::get_preschool_setup($syid, $levelid,$setup->id);
				$grades = self::get_student_grades_data($studid,$syid, $setupdetail[0]->detail, $setup->type);
		
				foreach($grades as $gradeitem){
			
					$detailinfo = collect($setupdetail[0]->detail)->where('id',$gradeitem->gsdid)->first();
					$gradeitem->description = $setup->description;
					$gradeitem->type = $setup->type;
					$gradeitem->detaildesc = $detailinfo->description;
					array_push($temp_grades,$gradeitem);
				}
			}

            return $temp_grades;


      }

      public static function store_grades(Request $request){

            $gsdid = $request->get('gsdid');
            $studid = $request->get('studid');
            $quarter = $request->get('quarter');
            $value = $request->get('value');
            $syid = $request->get('syid');

            try{

                  $check_if_exist = DB::table('grading_system_pgrades')
                              ->where('studid',$studid)
                              ->where('syid',$syid)
                              ->where('gsdid',$gsdid)
                              ->where('deleted',0)
                              ->first();
               
                  if(isset($check_if_exist->id)){
                        $quarter_val = 'q'.$quarter.'evaltext';
                        DB::table('grading_system_pgrades')
                                    ->where('id',$check_if_exist->id)
                                    ->update([
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          $quarter_val=>$value
                                    ]);
                        
                  }else{
                        $quarter_val = 'q'.$quarter.'evaltext';
                        DB::table('grading_system_pgrades')
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

      public static function teacher_class(Request $request){

            $syid = $request->get('syid');

            $teacherid = DB::table('teacher')
                              ->where('userid',auth()->user()->id)
                              ->where('deleted',0)
                              ->select('id')
                              ->first()
                              ->id;

            $sections = DB::table('sectiondetail')
                        ->join('sections',function($join){
                              $join->on('sectiondetail.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('sections.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                              $join->where('acadprogid',2);
                        })
                        ->where('sectiondetail.syid',$syid)
                        ->where('sectiondetail.teacherid', $teacherid)
                        ->where('sectiondetail.deleted',0)
                        ->select(
                              'sectionname',
                              'sectionname as text',
                              'sections.id',
                              'sections.levelid'
                        )
                        ->get();

            $all_info = array();

            foreach($sections as $item){

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
                                          'suffix'
                                    )
                                    ->get();

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
                        'id'=>$item->id,
                        'sectionname'=>$item->sectionname,
                        'text'=>$item->sectionname,
                        'students'=>$students,
                        'levelid'=>$item->levelid,
                  ]);

            }

            return $all_info;

      }


      public static function get_preschool_setup_ajax(Request $request){
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $headerid = $request->get('dataheaderid');
      
            return self::get_preschool_setup($syid,$levelid,$headerid);

      }

      public static function get_preschool_setup(
            $syid = null,
            $levelid = null,
            $headerid = null
      ){
          
                
            $preschool_setup = DB::table('grading_system')
                            ->join('grading_system_detail',function($join) use($headerid){
                                  $join->on('grading_system.id','=','grading_system_detail.headerid');
                                  $join->where('grading_system_detail.deleted',0);
                                  $join->where('grading_system_detail.headerid',$headerid);
                            })
                            ->where('acadprogid',2)
                            ->where('levelid',$levelid)
                            ->where('syid',$syid)
                            ->where('grading_system.deleted',0)
                            ->select(
                                  'grading_system_detail.*'
                            )
                            ->orderBy('sort')
                            ->orderBy('group')
                            ->get();

            $cellvalue = Db::table('grading_system_celldatail')
                              ->where('deleted',0)
                              ->whereIn('gsdid',collect($preschool_setup)->pluck('id'))
                              ->get();

            foreach($cellvalue as $item){
                  $item->q1cellval = $item->q1cellval != null ? $item->q1cellval : '';
                  $item->q2cellval = $item->q2cellval != null ? $item->q2cellval : '';
                  $item->q3cellval = $item->q3cellval != null ? $item->q3cellval : '';
                  $item->q4cellval = $item->q4cellval != null ? $item->q4cellval : '';
            }
            
                                    
            $ratingvalue = DB::table('grading_system_ratingvalue')
                                    ->where('gsid',$headerid)
                                    ->where('deleted',0)
                                    ->get();

            $studinfodetail = self::studentinfo_list();

            foreach($preschool_setup as $item){
                  $check = collect( $studinfodetail)->where('desc',$item->description)->first();
                  if(isset($check)){
                        $item->withdefault = $check->withdefault;
                  }
            }
            
            return array((object)[
                  'detail'=>$preschool_setup,
                  'ratingvalue'=>$ratingvalue,
                  'cellvalue'=>$cellvalue
            ]);

      }

      public static function create_setup_ajax(Request $request){
            $decription = $request->get('decription');
            $sort = $request->get('sort');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $type = $request->get('type');
            $dataid = $request->get('dataid');
            $dataheaderid =  $request->get('dataheaderid');

            return self::create_setup($decription,$sort,$syid,$type,$dataid,$levelid,$dataheaderid);
      }

      public static function create_setup(
            $decription = null,
            $sort = null,
            $syid = null,
            $type = null,
            $dataid = null,
            $levelid = null,
            $dataheaderid = null
      ){

            try{

                  $value = 5;
                  $header = $dataheaderid;
                  $group = strtoupper($sort);

                  if($type == "header"){
                        $value = 0;
                        $sort_count = DB::table('grading_system_detail')
                                          ->where('value',0)
                                          ->where('deleted',0)
                                          ->where('headerid',$header)
                                          ->select(
                                                'grading_system_detail.id',
                                                'grading_system_detail.sort')
                                          ->get();
                     
                        $count = 1;
                        foreach($sort_count as $item){
                              if(strlen($item->sort) == 1){
                                    $count += 1;
                              }
                        }

                        $sort =  chr(64+$count);
                        $group = NULL;
                      
                  }

                  if($dataid != null){

                        $headersort = DB::table('grading_system_detail')
                                   ->where('id',$dataid)
                                   ->where('deleted',0)
                                   ->select('sort','group')
                                   ->first();

                        if(isset($headersort->sort)){

                              $sort_count = DB::table('grading_system_detail')
                                                ->where('group',$headersort->sort)
                                                ->where('headerid',$header)
                                                ->where('deleted',0)
                                                ->count();
                              
                              $sort = $headersort->sort . chr(65+$sort_count);
                              
                              $group = strtoupper($headersort->sort);

                        }

                        
                  }

                  $headerid = DB::table('grading_system_detail')
                        ->insertGetId([
                              'headerid'=>$header,
                              'description'=>$decription,
                              'value'=>$value,
                              'sort'=>strtoupper($sort),
                              'group'=>$group
                        ]);

                  //generate cellvalue 
                  $headerinfo = DB::table('grading_system')
                                    ->where('id',$header)
                                    ->where('deleted',0)
                                    ->first();
                  
                  

                  if($headerinfo->type == 5){
                        $checkcelldata = DB::table('grading_system_celldatail')
                                                ->where('gsdid',$headerid)
                                                ->where('deleted',0)
                                                ->count();
                        if($checkcelldata == 0){

                              $availableletter = self::get_availableletter($levelid,$syid);

                              DB::table('grading_system_celldatail')
                              ->insert([
                                    'gsdid'=>$headerid,
                                    'q1cellval'=>$availableletter[0],
                                    'q2cellval'=>$availableletter[1],
                                    'q3cellval'=>$availableletter[2],
                                    'q4cellval'=>$availableletter[3],
                              ]);
                        }
                  }
                       
                  return array((object)[
                        'header'=>$headerid,
                        'status'=>1,
                        'data'=>'Created Successfully!',
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }


      public static function get_availableletter($levelid, $syid){

            $grading_header = DB::table('grading_system')
                                    ->where('deleted',0)
                                    ->where('levelid',$levelid)
                                    ->where('syid',$syid)
                                    ->select('id')
                                    ->get();

            $detail = DB::table('grading_system_detail')
                        ->whereIn('headerid',collect($grading_header)->pluck('id'))
                        ->where('deleted',0)
                        ->select('id')
                        ->get();

            $cellvalue = Db::table('grading_system_celldatail')
                              ->whereIn('gsdid',collect($detail)->pluck('id'))
                              ->where('deleted',0)
                              ->get();

            $all_letters = array();
            for ($i = 'aa'; $i !== 'zz'; $i++){
                  array_push($all_letters,$i);
            }
            
            
            $usedletters = array();
            foreach($cellvalue as $item){
                  for($x = 1; $x <= 4; $x++){
                        $celcol = 'q'.$x.'cellval';
                        if($item->$celcol != null){
                              array_push($usedletters , $item->$celcol);
                        }
                  }
            }
            
            $all_letters = array_values(array_diff_key($all_letters,$usedletters ));

            return $all_letters;


      }

      public static function delete_setup_ajax(Request $request){
            $dataid = $request->get('dataid');
            $syid = $request->get('syid');
            return self::delete_setup($dataid,$syid);
            
      }

      public static function delete_setup(
            $dataid = null,
            $syid = null
      ){
            try{

                  $check_info = DB::table('grading_system_detail')
                                    ->where('id',$dataid)
                                    ->where('deleted',0)
                                    ->first();

                  if(isset($check_info->id)){
                        if($check_info->value == 0){

                              $check_info = DB::table('grading_system_detail')
                                                ->where('group',$check_info->sort)
                                                ->where('headerid',$check_info->headerid)
                                                ->where('deleted',0)
                                                ->where('value','!=',0)
                                                ->count();

                              if($check_info > 0){
                                    return array((object)[
                                          'status'=>2,
                                          'message'=>'Please remove header items!',
                                    ]);
                              }
                        }
                  }


                  DB::table('grading_system_detail')
                        ->where('id',$dataid)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Deleted Successfully!',
                        'info'=>self::get_preschool_setup($syid)
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update_setup_ajax(Request $request){
            $dataid = $request->get('dataid');
            $description = $request->get('description');
            $type = $request->get('type');
            $syid = $request->get('syid');
            return self::update_setup($dataid,$description,$type,$syid);
            
      }

      public static function update_setup(
            $dataid = null,
            $description = null,
            $type = null,
            $syid
      ){
            try{

                  DB::table('grading_system_detail')
                        ->where('id',$dataid)
                        ->where('deleted',0)
                        ->update([
                              'description'=>$description,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Deleted Successfully!',
                        'info'=>self::get_preschool_setup($syid)
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function create_setup_header_ajax(Request $request){

            
      }

      public static function create_setup_header(
            $syid = null,
            $levelid = null
      ){
            try{

                  $header = DB::table('grading_system')
                        ->insertGetId([
                              'description'=>'Pre-school Compentencies',
                              'syid'=>$syid,
                              'acadprogid'=>2,
                              'type'=>3,
                              'specification'=>1,
                              'levelid'=>$levelid,
                              'deleted'=>0,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Created Successfully!',
                        'new_id'=>$header
                        // 'info'=>$header
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
                  'data'=>'Something went wrong!',
                  'message'=>'Something went wrong!'
            ]);
      }


      public static function copy_setup(){
          
          $syid = 5;
          $levelid = 3;
          $copy_to_syid = 3;
          
          
           $setup = self::get_preschool_setup($syid,$levelid);
           
              $header = DB::table('grading_system')
                          ->where('syid',$copy_to_syid)
                          ->where('levelid',$levelid)
                          ->where('acadprogid',2)
                          ->where('deleted',0)
                          ->first();
    
              if(isset($header->id)){
                    $header = $header->id;
              }else{
                    $header = self::create_setup_header($copy_to_syid,$levelid);
                    $header = $header[0]->new_id;
                   
              }
              
              $check = DB::table('grading_system_detail')
                        ->where('headerid',$header)
                        ->where('deleted',0)
                        ->count();
                        
                        
                if($check == 0){
                    foreach($setup as $item){
                            DB::table('grading_system_detail')
                                ->insert([
                                      'headerid'=>$header,
                                      'description'=>$item->description,
                                      'value'=>$item->value,
                                      'sort'=>$item->sort,
                                      'group'=>$item->group
                                ]);
                      }
                    
                }
              
              
              
              
            

          
          
      }


      public static function get_preschool_setup_age_ajax(
            $syid = null,
            Request $request
      ){

            $syid = $request->get('syid');
            $preschool_setup = DB::table('grading_system')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system.id','=','grading_system_detail.headerid');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->where('acadprogid',2)
                                    ->where('levelid',3)
                                    ->where('syid',3)
                                    ->where('grading_system.description','Kinder Age')
                                    ->where('grading_system.deleted',0)
                                    ->select(
                                          'grading_system_detail.*'
                                    )
                                    ->orderBy('sort')
                                    ->orderBy('group')
                                    ->get();


            return $preschool_setup;

      }

      public static function get_preschool_setup_remarks_ajax(
            $syid = null,
            Request $request
      ){

           
            $preschool_setup = DB::table('grading_system')
                                    ->join('grading_system_detail',function($join){
                                          $join->on('grading_system.id','=','grading_system_detail.headerid');
                                          $join->where('grading_system_detail.deleted',0);
                                    })
                                    ->where('acadprogid',2)
                                    ->where('levelid',3)
                                    ->where('syid',3)
                                    ->where('grading_system.description','Kinder Comments')
                                    ->where('grading_system.deleted',0)
                                    ->select(
                                          'grading_system_detail.*'
                                    )
                                    ->orderBy('sort')
                                    ->orderBy('group')
                                    ->get();


            return $preschool_setup;

      }


      //type 1 : 3 Term/Quarter checklist
      //type 2 : 4 Term/Quarter checklist
      //type 3 : 4 Term/Quarter Rating Value
      //type 4 : 4 Term/Quarter Rating Value
      //specification 1 : preschool kiner

      //grading system setup
      public static function grading_setup_list(Request $request){

            $levelid = $request->get('levelid');
            $syid = $request->get('syid');

            $setup = DB::table('grading_system')
                        ->where('syid',$syid)
                        ->where('levelid',$levelid)
                        ->where('specification',1)
                        ->where('deleted',0)
                        ->get();

            return $setup;

      }
      public static function grading_setup_create(Request $request){
            try{

              

                  $levelid = $request->get('levelid');
                  $syid = $request->get('syid');
                  $description = $request->get('description');
                  $type = $request->get('type');
      
                  $validated = self::grading_setup_validate($levelid,$syid,$description,$type);
      
                  if($validated[0]->status == 0){
                        return $validated;
                  }
      
                  DB::table('grading_system')
                        ->insert([
                              'description'=>$description,
                              'syid'=>$syid,
                              'type'=>$type,
                              'specification'=>1,
                              'levelid'=>$levelid,
                              'acadprogid'=>2,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
      
                  
                  return array((object)[
                        'status'=>1,
                        'message'=>'Setup Created!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }
      public static function grading_setup_update(Request $request){
            try{
                  $levelid = $request->get('levelid');
                  $syid = $request->get('syid');
                  $description = $request->get('description');
                  $type = $request->get('type');
                  $id = $request->get('id');
      
                  $validated = self::grading_setup_validate($levelid,$syid,$description,$type,$id);
      
                  if($validated[0]->status == 0){
                        return $validated;
                  }
      
                  DB::table('grading_system')
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                              'description'=>$description,
                              'type'=>$type,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
      
                  
                  return array((object)[
                        'status'=>1,
                        'message'=>'Setup Updated!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
                  
            }
      }
      public static function grading_setup_delete(Request $request){
            try{
                  $id = $request->get('id');

                  // $check_if_contains_detail = DB::table('grading_system_detail')
                  //                               ->where('headerid',$id)
                  //                               ->where('deleted',0)
                  //                               ->count();

                  // if($check_if_contains_detail > 0){
                  //       return array((object)[
                  //             'status'=>0,
                  //             'message'=>'Contains Detail!'
                  //       ]);
                  // }

                  DB::table('grading_system')
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('grading_system_detail')
                        ->where('headerid',$id)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Setup Deleted!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function grading_setup_validate($levelid = null, $syid = null, $description = null, $type = null, $id = null){

            if($description == ""){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Description is empty!'
                  ]);
            }

            if($type == ""){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Type is empty!'
                  ]);
            }

            $check = DB::table('grading_system');
            if($id  != null){
                  $check = $check->where('id','!=',$id);
            }
            $check = $check->where('description',$description)
                        ->where('levelid',$levelid)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->count();

            if($check > 0){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Setup already exist!'
                  ]);
            }

            return array((object)[
                  'status'=>1,
                  'message'=>'Setup valid!'
            ]);

      }
    
      //rating value
      public static function ratingvalue_list(Request $request){

            $headerid = $request->get('headerid');

            $ratingvalue = Db::table('grading_system_ratingvalue')
                              ->where('gsid',$headerid)
                              ->where('deleted',0)
                              ->get();

            return $ratingvalue;

      }

      public static function ratingvalue_create(Request $request){

            $sort= $request->get('sort');
            $description = $request->get('description');
            $value = $request->get('value');
            $headerid = $request->get('headerid');

            try{

                  DB::table('grading_system_ratingvalue')
                        ->insert([
                              'sort'=>$sort,
                              'gsid'=>$headerid,
                              'description'=>$description,
                              'value'=>$value
                        ]);

                        

                  return array((object)[
                        'status'=>1,
                        'message'=>'Created Successfully!'
                  ]);

            }catch(Exeption $e){
                  return self::store_error($e);
            }

      }

      
      public static function ratingvalue_update(Request $request){

            $sort= $request->get('sort');
            $description = $request->get('description');
            $value = $request->get('value');
            $headerid = $request->get('headerid');
            $id = $request->get('id');

            try{

                  DB::table('grading_system_ratingvalue')
                        ->take(1)
                        ->where('id',$id)
                        ->where('gsid',$headerid)
                        ->update([
                              'sort'=>$sort,
                              'description'=>$description,
                              'value'=>$value,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Updated Successfully!'
                  ]);

            }catch(Exeption $e){
                  return self::store_error($e);
            }

      }

      public static function ratingvalue_delete(Request $request){
            
            $headerid = $request->get('headerid');
            $id = $request->get('id');

            try{

                  DB::table('grading_system_ratingvalue')
                        ->take(1)
                        ->where('id',$id)
                        ->where('gsid',$headerid)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Deleted Successfully!'
                  ]);

            }catch(Exeption $e){
                  return self::store_error($e);
            }
      }

      public static function ratingvalue_validate($request){

            $message = [
                  'type.required'=>'Event type is required.',
                  'description.required'=>'Description is required.',
                  'value.required'=>'Value is required.',
                  ];

            $validator = Validator::make(collect($request->all())->toArray(), [
                  'sort' => 'required',
                  'description' => 'required|unique:description,id'.$request->get('id'),
                  'value'=>'required'
                  ], $message);

            return $validator->errors();
      }


      public static function download_detail(Request $request){


            $headerid = $request->get('headerid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');


            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
            $sheet = $spreadsheet->getActiveSheet();

            $setup = self::get_preschool_setup($syid,$levelid,$headerid);

            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            // $sheet->getColumnDimension('B')->setWidth(60);

            // $sheet->getColumnDimension('A')->setAutoSize(false);
            // $sheet->getColumnDimension('A')->setWidth(5);

            $font_bold = [
                  'font' => [
                      'bold' => true,
                  ]
              ];

            $row = 1;
            $detail = $setup[0]->detail;
            $cellvalues = $setup[0]->cellvalue;
            
            foreach($detail as $item){
                  if($item->value == 0){
                        $row += 1;
                        // $sheet->mergeCells('A'.$row.':B'.$row);
                        if($item->group != null){
                              $sheet->setCellValue('F'.$row,$item->description);
                              $sheet->getStyle('F'.$row)->applyFromArray($font_bold);
                        }else{
                              $sheet->setCellValue('E'.$row,$item->description);
                              $sheet->getStyle('E'.$row)->applyFromArray($font_bold);
                        }
                  }else{
                        if(strlen($item->group) == 2){
                              $sheet->setCellValue('G'.$row,$item->description);
                        }else{
                              $sheet->setCellValue('F'.$row,$item->description);
                        }
                  }

                  $cellvalue = collect($cellvalues)->where('gsdid',$item->id)->first();
                  if(isset($cellvalue)){
                        $sheet->setCellValue('A'.$row,'${'.$cellvalue->q1cellval.'}');
                        $sheet->setCellValue('B'.$row,'${'.$cellvalue->q2cellval.'}');
                        $sheet->setCellValue('C'.$row,'${'.$cellvalue->q3cellval.'}');
                        $sheet->setCellValue('D'.$row,'${'.$cellvalue->q4cellval.'}');

                        $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('center');
                        $sheet->getStyle('B'.$row)->getAlignment()->setHorizontal('center');
                        $sheet->getStyle('C'.$row)->getAlignment()->setHorizontal('center');
                        $sheet->getStyle('D'.$row)->getAlignment()->setHorizontal('center');

                        $sheet->getStyle('A'.$row)->getAlignment()->setVertical('center');
                        $sheet->getStyle('B'.$row)->getAlignment()->setVertical('center');
                        $sheet->getStyle('C'.$row)->getAlignment()->setVertical('center');
                        $sheet->getStyle('D'.$row)->getAlignment()->setVertical('center');
                  }

                  if($item->value == 0 || $item->description == 'SCALED SCORE'){
                        $row += 2;
                  }else{
                        $row += 1;
                  }
                  
            }

            ob_end_clean();
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="FG_1.xlsx"');
            $writer->save("php://output");
           exit();
      }

      public static function upload_template(Request $request){

            $syid = $request->get('syid');
            $levelid = $request->get('levelid');

            $userid = auth()->user()->id;
            
            try{

                 

                  //grading info
                  $check_detail = DB::table('grading_system')
                                          ->where('syid',$syid)
                                          ->where('deleted',0)
                                          ->where('levelid',$levelid)
                                          ->where('type',7)
                                          ->first();

                  if(!isset($check_detail)){

                        $acad = DB::table('gradelevel')
                                    ->where('id',$levelid)
                                    ->first();

                        $header = DB::table('grading_system')
                              ->insertGetId([
                                    'description'=>'Word Template',
                                    'type'=>7,
                                    'specification'=>1,
                                    'levelid'=>$levelid,
                                    'syid'=>$syid,
                                    'acadprogid'=> $acad->acadprogid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        $header = $check_detail->id;
                  }


                  DB::table('grading_system_detail')
                        ->where('headerid',$header)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $count = DB::table('grading_system_detail')
                              ->where('headerid',$header)
                              ->count();


                  $file = $request->file('input_fileupload_template');
                  $extension = $file->getClientOriginalExtension();
                  $filename = 'PSRC_template_'.($count+1).'_'.$levelid.'_'.$syid.'.'.$extension;

                  DB::table('grading_system_detail')
                        ->insert([
                              'headerid'=>$header,
                              'description'=>$filename,
                              'group'=>'A',
                              'sort'=>1,
                              'createdby'=>$userid,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                
                  $file->move(public_path(), $filename);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Template Uploaded!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }


      public static function upload_detail(Request $request){

            try{

                  $all_letters = array();
                  for ($i = 'aa'; $i !== 'zz'; $i++){
                        array_push($all_letters,$i);
                  }

                  $path = $request->file('input_fileupload')->getRealPath();
                  $headerid = $request->get('headerid');
                  
                  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                  $spreadsheet = $reader->load($path);

                  $worksheet = $spreadsheet->setActiveSheetIndex(0);
                  $data = $worksheet->toArray();
                  $student_info = array();

                  $headerinfo = DB::table('grading_system')
                                    ->where('id',$headerid)
                                    ->first();


                  $all_letters = self::get_availableletter($headerinfo->levelid,$headerinfo->syid);

                  $count  = 0;

                  $currentheader = null;
                  foreach($data as $key=>$item){
                        if($item[0] != ""){
                              if(isset($data[$key+1][1])){
                                    if($data[$key+1][1] != ""){
                                          $mainheader = self::create_setup($item[0],null,null,'header',null,null,$headerid)[0]->header;
                                          $all_letters = self::inssertcelldetail($mainheader,$all_letters);
                                          $ddid = self::create_setup('SCALED SCORE',null,null,null, $mainheader,null,$headerid)[0]->header;
                                          $all_letters = self::inssertcelldetail($ddid,$all_letters);
                                          $count +=1;
                                    }
                              }
                        }
                        else if($item[1] != ""){
                              if(isset($data[$key+1][2])){
                                    if($data[$key+1][2] != ""){
                                          $currentheader = self::create_setup($item[1],null,null,'header', $mainheader,null,$headerid)[0]->header;
                                          $all_letters = self::inssertcelldetail($ddid,$all_letters);
                                          $all_letters = self::inssertcelldetail($currentheader,$all_letters);
                                          $count +=1;
                                    }
                              }else{
                                    $ddid = self::create_setup($item[1],null,null,null, $mainheader,null,$headerid)[0]->header;
                                    $all_letters = self::inssertcelldetail($ddid,$all_letters);
                                    if(isset($data[$key+1][0])){
                                          $ddid = self::create_setup('TOTAL',null,null,null, $mainheader,null,$headerid)[0]->header;
                                          $all_letters = self::inssertcelldetail($ddid,$all_letters);
                                          
                                    }
                              }
                        }else if($item[2] != ""){
                              $ddid = self::create_setup($item[2],null,null,null, $currentheader,null,$headerid)[0]->header;
                              $all_letters = self::inssertcelldetail($ddid,$all_letters);
                              if(isset($data[$key+1][0]) || isset($data[$key+1][1])){
                                    $ddid = self::create_setup('TOTAL',null,null,null, $currentheader,null,$headerid)[0]->header;
                                    $all_letters = self::inssertcelldetail($ddid,$all_letters);
                              }
                        }
                  }

                  if($headerinfo->type == 1 || $headerinfo->type == 3){
                        $ddid = self::create_setup('TOTAL',null,null,null, $mainheader,null,$headerid)[0]->header;
                        $all_letters = self::inssertcelldetail($ddid,$all_letters);
                        $ddid = self::create_setup('SCALED SCORE',null,null,null, $mainheader,null,$headerid)[0]->header;
                        $all_letters = self::inssertcelldetail($ddid,$all_letters);

                        $ddid = self::create_setup('SUM OF STANDARD SCORE',null,null,null, $mainheader,null,$headerid)[0]->header;
                        $all_letters = self::inssertcelldetail($ddid,$all_letters);
                        $ddid = self::create_setup('SUM OF SCALED SCORE',null,null,null, $mainheader,null,$headerid)[0]->header;
                        $all_letters = self::inssertcelldetail($ddid,$all_letters);
                  }
                  

                  return array((object)[
                        'status'=>1,
                        'message'=>'Uploaded Successfully!'
                  ]);
            }catch(Exeption $e){
                  return self::store_error($e);
            }

      }
     
      public static function inssertcelldetail($ddid, $all_letters){

            DB::table('grading_system_celldatail')
                  ->insert([
                        'gsdid'=> $ddid,
                        'q1cellval'=>$all_letters[0],
                        'q2cellval'=>$all_letters[1],
                        'q3cellval'=>$all_letters[2],
                        'q4cellval'=>$all_letters[3]
                  ]);

            array_shift($all_letters);
            array_shift($all_letters);
            array_shift($all_letters);
            array_shift($all_letters);

            return $all_letters;
      }

 
}
