<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Hash;
use Session;

class SF1ToSystemController extends \App\Http\Controllers\Controller
{

      // public static function get_column(Request $request){

      //       $department = $request->get('dept');
            
      //       $columns = DB::table("sf1_column")
      //                         ->where('department',$department)
      //                         ->et();

      //       return $columns;

      // }

      public static function savecolumns(Request $request){
            try{

                  DB::table('sf1_column')
                        ->updateOrInsert(
                              [
                                    'id'=>$request->get('id')
                              ],
                              [
                                    'lrn'=>$request->get('lrn'),
                                    'name'=>$request->get('name'),
                                    'gender'=>$request->get('gender'),
                                    'dob'=>$request->get('dob'),
                                    'street'=>$request->get('street'),
                                    'barangay'=>$request->get('barangay'),
                                    'city'=>$request->get('city'),
                                    'province'=>$request->get('province'),
                                    'fname'=>$request->get('fname'),
                                    'mname'=>$request->get('mname'),
                                    'gname'=>$request->get('gname'),
                                    'grelation'=>$request->get('grelation'),
                                    'contactno'=>$request->get('contact'),
                                    'mothertongue'=>$request->get('mothertongue'),
                                    'ethnicgroup'=>$request->get('ethnicgroup'),
                                    'religion'=>$request->get('religion'),
                                    'course'=>$request->get('course'),
                                    'firstname'=>$request->get('firstname'),
                                    'middlename'=>$request->get('middlename'),
                                    'lastname'=>$request->get('lastname'),
                                    'levelid'=>$request->get('levelid'),
                                    'suffix'=>$request->get('suffix'),
                                    'altsid'=>$request->get('altsid')
                              ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Columns Saved!'
                  ]);

            }catch(\Exception $e){
                 return $e;
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }

      }


      public static function saveinfo(Request $request){
            try{

                  $levelid = $request->get('levelid');
                  $lrn = $request->get('lrn');
                  $grelation = null;
                  $department = $request->get('department');

                  if($department == 2){

                        if($request->get('cllg-lastname') == null || $request->get('cllg-lastname') == "null"){
                              return array((object)[
                                    'status'=>2,
                                    'message'=>'Empty information'
                              ]);
                        }


                        $courseid = DB::table('college_courses')
                                          ->where('courseabrv',$request->get('cllg-course'))
                                          ->first();

                        if(!isset($courseid)){
                              return array((object)[
                                    'status'=>2,
                                    'message'=>'Course does not exist!'
                              ]);
                        }

                        $check = DB::table('studinfo')
                                    ->where('firstname',$request->get('cllg-firstname'))
                                    ->where('lastname',$request->get('cllg-lastname'))
                                    ->where('dob',$request->get('cllg-dob'))
                                    ->where('deleted',0)
                                    ->count();

                        if($check > 0){
                              return array((object)[
                                    'status'=>2,
                                    'message'=>'Aleardy Exist!'
                              ]);
                        }

                        $acadprog = DB::table('gradelevel')
                                    ->where('id',$request->get('cllg-levelid'))
                                    ->first()
                                    ->acadprogid;


                        $studid = DB::table('studinfo')
                              ->insertGetId([
                                    'firstname'=>$request->get('cllg-firstname'),
                                    'lastname'=>$request->get('cllg-lastname'),
                                    'middlename'=>$request->get('cllg-middlename') == 'null' ? null : $request->get('cllg-middlename'),
                                    'suffix'=>$request->get('cllg-suffix'),
                                    'gender'=>$request->get('cllg-gender'),
                                    'dob'=>$request->get('cllg-dob'),
                                    'levelid'=>$request->get('cllg-levelid'),
                                    'courseid'=>$courseid->id,
                                    'deleted'=>0,
                                    'grantee'=>1,
                                    'nodp'=>0,
                                    'studstatus'=>0,
                                    'studtype'=>'old'
                              ]);

                        $sid = \App\RegistrarModel::idprefix($acadprog,$studid);

                        DB::table('studinfo')
                              ->where('id', $studid)
                              ->take(1)
                              ->update([
                              'sid' => $sid
                              ]);

                        return array((object)[
                              'status'=>1,
                              'message'=>'Student Saved!'
                        ]);

                  }

                  // if($school == 'SCPS'){

                  //       $lrn = $request->get('lrn');
                  //       if($levelid == 1){
                  //             $levelid = 17;
                  //       }else if($levelid == 2){
                  //             $levelid = 18;
                  //       }else if($levelid == 3){
                  //             $levelid = 19;
                  //       }else if($levelid == 4){
                  //             $levelid = 20;
                  //       }

                  //       if($request->get('lrn') == 'BSED ENGLISH'){
                  //             $courseid = 7;
                  //       }else if($request->get('lrn') == 'BAELS'){
                  //             $courseid = 1;
                  //       }else if($request->get('lrn') == 'BSBA MM'){
                  //             $courseid = 3;
                  //       }else if($request->get('lrn') == 'BSBA FM'){
                  //             $courseid = 4;
                  //       }else if($request->get('lrn') == 'BSED VED'){
                  //             $courseid = 8;
                  //       }else if($request->get('lrn') == 'CPE'){
                  //             $courseid = 10;
                  //       }else if($request->get('lrn') == 'BSED FIL'){
                  //             $courseid = 6;
                  //       }else if($request->get('lrn') == 'AB English'){
                  //             $courseid = 7;
                  //       }else if($request->get('lrn') == 'BEED'){
                  //             $courseid = 5;
                  //       }else if($request->get('lrn') == 'BSCS'){
                  //             $courseid = 11;
                  //       }else if($request->get('lrn') == 'BSED MATH'){
                  //             $courseid = 9;
                  //       }
                        
                  // }


                  // if($levelid == 17){



                  //       $levelid = $request->get('contact');
                  //       if($levelid == 1){
                  //             $levelid = 17;
                  //       }else if($levelid == 2){
                  //             $levelid = 18;
                  //       }else if($levelid == 3){
                  //             $levelid = 19;
                  //       }else if($levelid == 4){
                  //             $levelid = 20;
                  //       }
                        

                  //       $courseid = 0;

                  //       // if($request->get('mothertongue') == 'Bachelor of Science in Business Administration Major in Human Resource Management'){
                  //       //       $courseid = 1;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Secondary Education  Major in English'){
                  //       //       $courseid = 2;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Science in Business Administration Major in Marketing Management'){
                  //       //       $courseid = 3;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Science in Business Administration Major in Marketing Management'){
                  //       //       $courseid = 4;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Science in Accountancy'){
                  //       //       $courseid = 5;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Secondary Education Major in Filipino'){
                  //       //       $courseid = 6;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Science in Entreprenuership'){
                  //       //       $courseid = 7;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Elementary Education'){
                  //       //       $courseid = 8;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Science in Computer Science'){
                  //       //       $courseid = 9;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Early Childhood Education'){
                  //       //       $courseid = 10;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Special Needs Education'){
                  //       //       $courseid = 11;
                  //       // }else if($request->get('mothertongue') == 'BBachelor of Technical-Vocational Teacher Education Major in Food and Service Management'){
                  //       //       $courseid = 12;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Agricultural Technology'){
                  //       //       $courseid = 13;
                  //       // }else if($request->get('mothertongue') == 'Bachelor of Science in Electro-Mechanical Technologyt'){
                  //       //       $courseid = 14;
                  //       // }


                  //       // if($request->get('contact') == 'Bachelor Of Secondary Education'){
                  //       //       $courseid = 1;
                  //       // }else if($request->get('contact') == 'Bachelor Of Secondary Education'){
                  //       //       $courseid = 2;
                  //       // }else if($request->get('contact') == 'Bachelor Of Library And Information Science'){
                  //       //       $courseid = 6;
                  //       // }else if($request->get('contact') == 'Bachelor Of Science In Computer Science'){
                  //       //       $courseid = 7;
                  //       // }else if($request->get('contact') == 'Bachelor Of Science in Information Systems'){
                  //       //       $courseid = 8;
                  //       // }else if($request->get('contact') == 'Bachelor Of Arts'){
                  //       //       $courseid = 9;
                  //       // }else if($request->get('contact') == 'Bachelor In Public Administration'){
                  //       //       $courseid = 10;
                  //       // }else if($request->get('contact') == 'Bachelor Of Theology And Arts'){
                  //       //       $courseid = 11;
                  //       // }else if($request->get('contact') == 'Bachelor Of Science In Business Administration'){
                  //       //       $courseid = 13;
                  //       // }else if($request->get('contact') == 'Bachelor Of Science In Accountancy'){
                  //       //       $courseid = 15;
                  //       // }else if($request->get('contact') == 'Graduate Midwifery'){
                  //       //       $courseid = 16;
                  //       // }
                  //       $contact = null;
                  //       $lrn = null;
                  //       $grelation = null;

                  // }else{
                      
                  // }

                  $lrn = $request->get('lrn');
                  $contact = $request->get('contact');
                  $courseid = 0;

                  $acadprog = DB::table('gradelevel')
                                    ->where('id',$levelid)
                                    ->first()
                                    ->acadprogid;


              

                  if($request->get('mothertongue') != null){
                        $count = DB::table('mothertongue')
                                          ->where('mtname',$request->get('mothertongue'))
                                          ->where('deleted',0)
                                          ->count();
                        if($count == 0){
                              DB::table('mothertongue')
                                          ->insert([
                                                'mtname'=>$request->get('mothertongue'),
                                                'deleted'=>0
                                          ]);
                        }
                  }

                  if($request->get('religion') != null){
                        $count = DB::table('religion')
                                          ->where('mtname',$request->get('religion'))
                                          ->where('deleted',0)
                                          ->count();
                        if($count == 0){
                              DB::table('religion')
                                          ->insert([
                                                'religionname',$request->get('religion'),
                                                'deleted'=>0
                                          ]);
                        }
                  }

                  if($request->get('ethnic') != null){
                        $count = DB::table('ethnic')
                                          ->where('egname',$request->get('ethnic'))
                                          ->where('deleted',0)
                                          ->count();
                        if($count == 0){
                              DB::table('ethnic')
                                          ->insert([
                                                'egname',$request->get('ethnic'),
                                                'deleted'=>0
                                          ]);
                        }
                  }
                        
                  $check = DB::table('studinfo')
                              ->where('firstname',$request->get('firstname'))
                              ->where('lastname',$request->get('lastname'))
                              ->where('dob',$request->get('dob'))
                              ->where('deleted',0)
                              ->count();

                 

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Aleardy Exist!'
                        ]);
                  }

                  $studid = DB::table('studinfo')
                              ->insertGetId([
                                    'strandid'=>$request->get('strandid'),
                                    'courseid'=>$request->get('courseid'),
                                    'lrn'=>$lrn,
                                    'firstname'=>$request->get('firstname'),
                                    'lastname'=>$request->get('lastname'),
                                    'middlename'=>$request->get('middlename'),
                                    'suffix'=>$request->get('suffix'),
                                    'gender'=>$request->get('gender'),
                                    'dob'=>$request->get('dob'),
                                    'street'=>$request->get('street'),
                                    'barangay'=>$request->get('barangay'),
                                    'city'=>$request->get('city'),
                                    'province'=>$request->get('province'),
                                    'fathername'=>$request->get('fname'),
                                    'mothername'=>$request->get('mname'),
                                    'guardianname'=>$request->get('gname'),
                                    'guardianrelation'=>$grelation,
                                    'contactno'=>$contact,
                                    'courseid'=>$courseid,
                                    'levelid'=>$levelid,

                                    'mtname'=>$request->get('mothertongue'),
                                    'egname'=>$request->get('ethnicgroup'),
                                    'religionname'=>$request->get('religion'),

                                    'deleted'=>0,
                                    'grantee'=>1,
                                    'nodp'=>0,
                                    'studstatus'=>0,
                                    'studtype'=>'old'
                              ]);

                  $sid = \App\RegistrarModel::idprefix($acadprog,$studid);

                  DB::table('studinfo')
                        ->where('id', $studid)
                        ->take(1)
                        ->update([
                        'sid' => $sid
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Student Saved!'
                  ]);

            }catch(\Exception $e){
                  
                  return $e;

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong!'
                  ]);
            }

      }

      
      public static function generate_student_from_excel(Request $request){


            $college_courses = DB::table('college_courses')
                                    ->where('deleted',0)
                                    ->select('courseDesc')
                                    ->get();

            $school = 'SCPS';

            $cell_value  = self::letter_to_number();

            $path = $request->file('input_sf1')->getRealPath();
            
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path);

            $worksheet = $spreadsheet->setActiveSheetIndex(0);
            $data = $worksheet->toArray();
            $student_info = array();
            
            $department = $request->get('department');

            // return $request->all();

            if($department == 1){
                  $lrn_col = collect($cell_value)->where('letter',$request->get('lrn'))->first()->number;
                  $name_col = collect($cell_value)->where('letter',$request->get('name'))->first()->number;
                  $gender_col = collect($cell_value)->where('letter',$request->get('gender'))->first()->number;
                  $dob_col = collect($cell_value)->where('letter',$request->get('dob'))->first()->number;
                  $street_col = collect($cell_value)->where('letter',$request->get('street'))->first()->number;
                  $barangay_col = collect($cell_value)->where('letter',$request->get('barangay'))->first()->number;
                  $city_col = collect($cell_value)->where('letter',$request->get('city'))->first()->number;
                  $province_col = collect($cell_value)->where('letter',$request->get('province'))->first()->number;
                  $fname_col = collect($cell_value)->where('letter',$request->get('fname'))->first()->number;
                  $mname_col = collect($cell_value)->where('letter',$request->get('mname'))->first()->number;
                  $gname_col = collect($cell_value)->where('letter',$request->get('gname'))->first()->number;
                  $grelation_col = collect($cell_value)->where('letter',$request->get('grelation'))->first()->number;
                  $contact_col = collect($cell_value)->where('letter',$request->get('contact'))->first()->number;
                  $mothertongue_col = collect($cell_value)->where('letter',$request->get('mothertongue'))->first()->number;
                  $ethnicgroup_col = collect($cell_value)->where('letter',$request->get('ethnicgroup'))->first()->number;
                  $religion_col = collect($cell_value)->where('letter',$request->get('religion'))->first()->number;
      
            }else{


                  $gender_col = collect($cell_value)->where('letter',$request->get('cllg-gender'))->first()->number;
                  $dob_col = collect($cell_value)->where('letter',$request->get('cllg-dob'))->first()->number;
                  $course_col = collect($cell_value)->where('letter',$request->get('course'))->first()->number;
                  $firstname_col = collect($cell_value)->where('letter',$request->get('firstname'))->first()->number;
                  $lastname_col = collect($cell_value)->where('letter',$request->get('lastname'))->first()->number;
                  $middlename_col = collect($cell_value)->where('letter',$request->get('middlename'))->first()->number;
                  $levelid_col = collect($cell_value)->where('letter',$request->get('levelid'))->first()->number;
                  $suffix_col = collect($cell_value)->where('letter',$request->get('suffix'))->first()->number;
                  $altsid_col = collect($cell_value)->where('letter',$request->get('cllg-altsid'))->first()->number;
            }
           

           

            $start_row = 1;

            

            foreach(array_slice($data,$start_row) as $item){


             

                  if($department == 2){
                        // if($item[10] != null){

                              $courseExist = collect($college_courses)->where('courseDesc',trim($item[$course_col]))->count() == 0 ? 0 : 1   ;

                              try{
                                    array_push($student_info,(object)[
                                          'course'=>trim($item[$course_col]),
                                          'gender'=>strtoupper($item[$gender_col]) == 'MALE' ? 'MALE' : 'FEMALE',
                                          'lastname'=>$item[$lastname_col],
                                          'firstname'=>$item[$firstname_col],
                                          'middlename'=>$item[$middlename_col],
                                          'levelid'=>$item[$levelid_col],
                                          'altsid'=>$item[$altsid_col],
                                          'dob'=>\Carbon\Carbon::create($item[$dob_col])->isoFormat('YYYY-MM-DD'),
                                          'courseExist'=>$courseExist,
                                          'suffix'=>null
                                    ]);

                              
                              }catch(\Exception $e){
                                    
                                    array_push($student_info,(object)[
                                          'course'=>trim($item[$course_col]),
                                          'gender'=>strtoupper($item[$gender_col]) == 'MALE' ? 'MALE' : 'FEMALE',
                                          'lastname'=>$item[$lastname_col],
                                          'firstname'=>$item[$firstname_col],
                                          'middlename'=>$item[$middlename_col],
                                          'levelid'=>$item[$levelid_col],
                                          'altsid'=>$item[$altsid_col],
                                          'dob'=>null,
                                          'suffix'=>null,
                                          'courseExist'=>$courseExist
                                    ]);
                              }
                        // }
                  }else{

                        $lrn = $item[$lrn_col];
                        $name =  $item[$name_col];
                        $gender =  $item[$gender_col];
                        $dob =  str_replace('-','/',$item[$dob_col]);
                        $street =  $item[$street_col];
                        $barangay =  $item[$barangay_col];
                        $city =  $item[$city_col];
                        $province =  $item[$province_col];
                        $fname =  $item[$fname_col];
                        $mname =  $item[$mname_col];
                        $gname =  $item[$gname_col];
                        $grelation =  $item[$grelation_col];
                        $contact =  $item[$contact_col];

                        $mothertongue =  $item[$mothertongue_col];
                        $ethnicgroup =  $item[$ethnicgroup_col];
                        $religion =  $item[$religion_col];

                        if(str_replace(' ','',strtoupper($gender)) == 'M' || str_replace(' ','',strtoupper($gender)) == 'F'){
                              $exploded_name = explode(',',$name);
                        
                              if(count($exploded_name) == 3){
                                    $middlename = strtoupper($exploded_name[2]);
                                    $suffix = '';
                              }else if(count($exploded_name) == 4){
                                    $middlename = strtoupper($exploded_name[3]);
                                    $suffix = strtoupper($exploded_name[2]);
                              }else{
                                    $suffix = '';
                                    $middlename = '';
                              }

                              try{

                                    array_push($student_info,(object)[
                                          'lrn'=>substr($lrn,0,12),
                                          'gender'=>strtoupper($gender) == 'M' ? 'MALE' : 'FEMALE',
                                          'lastname'=>strtoupper($exploded_name[0]),
                                          'firstname'=>strtoupper($exploded_name[1]),
                                          'middlename'=> $middlename,
                                          'suffix'=>  $suffix,
                                          'dob'=>\Carbon\Carbon::create($dob)->isoFormat('YYYY-MM-DD'),
                                          'street'=>strtoupper($street),
                                          'barangay'=>strtoupper($barangay),
                                          'city'=>strtoupper($city),
                                          'province'=>strtoupper($province),
                                          'fathername'=>strtoupper($fname),
                                          'mothername'=>strtoupper($mname),
                                          'guardianname'=>strtoupper($gname),
                                          'guardianrelation'=>$grelation,
                                          'contactno'=>$contact,

                                          'mothertongue'=>strtoupper($mothertongue),
                                          'ethnicgroup'=>strtoupper($ethnicgroup),
                                          'religion'=>strtoupper($religion),
                                    ]);
                                    
                              }catch(\Exception $e){
                                    // return $e;
                                    // return collect($item);
                              }
                              
                              
                        }
                  }

            }

            return $student_info;

      }

      public static function letter_to_number(){

            $letters = array();
            $index_count = 0;
            for($x = 65 ; $x <= 90; $x ++){
                  array_push($letters,(object)['letter'=>chr($x),'number'=>$index_count]);
                  $index_count += 1;
            }
            for($x = 65 ; $x <= 90; $x ++){
                  array_push($letters,(object)['letter'=>'A'.chr($x),'number'=>$index_count]);
                  $index_count += 1;
            }
            for($x = 65 ; $x <= 90; $x ++){
                  array_push($letters,(object)['letter'=>'B'.chr($x),'number'=>$index_count]);
                  $index_count += 1;
            }
            for($x = 65 ; $x <= 90; $x ++){
                  array_push($letters,(object)['letter'=>'C'.chr($x),'number'=>$index_count]);
                  $index_count += 1;
            }
            return $letters;

      }
}
