<?php

namespace App\Http\Controllers\RegistrarControllers;

use Illuminate\Http\Request;
use DB;
use File;




class RegistrarFormsController extends \App\Http\Controllers\Controller
{
   

      public function fillupsf10(){

            $students = DB::table('studinfo')
                              ->select(
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.id',
                                    'gradelevel.levelname'
                                    )
                              ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                              })
                              ->take(10)
                              ->get();
                              
            return $students;
      }

      public function getStudentsf10($studid,$gradelevel){

            // DB::table('sf10_schoollist')->truncate();
            // DB::table('sf10_student_elem')->truncate();
            // DB::table('sf10_student_jh')->truncate();
            // DB::table('sf10_student_sh')->truncate();
            // DB::table('sf10_student_sh')->truncate();
            // DB::table('sf10childgrades')->truncate();

            
            $isGradeSchoool = false;
            $isJuniorHigh = false;
            $isSeniorHigh = false;
            $sf10gradeschool = null;
            $sf10juniorhigh = null;
            $sf10seniorhigh = null;

            if($gradelevel >= 14 &&  $gradelevel <= 15){

                  $isSeniorHigh = true;
                  $isJuniorHigh = true;
                  $isGradeSchoool = true;

            }

            if($gradelevel >= 10 &&  $gradelevel <= 13){

                  $isJuniorHigh = true;
                  $isGradeSchoool = true;

            }

            if($gradelevel >= 1 &&  $gradelevel <= 9){


                  $isGradeSchoool = true;

            }

           


            if($isGradeSchoool){
                  
                  $sf10gradeschool = DB::table('sf10_student_elem')
                                          ->where('sf10_student_elem.studid',$studid)
                                          ->join('gradelevel',function($join){
                                                $join->on('sf10_student_elem.levelid','=','gradelevel.id');
                                          })
                                          ->join('sf10childgrades',function($join){
                                                $join->on('sf10_student_elem.id','=','sf10childgrades.headerid');
                                          })
                                          ->join('sf10_schoollist',function($join){
                                                $join->on('sf10_student_elem.sf10_schoolid','=','sf10_schoollist.id');
                                          })
                                          ->get();

            }

            if($isJuniorHigh){

                  $sf10juniorhigh = DB::table('sf10_student_jh')
                                    ->where('sf10_student_jh.studid',$studid)
                                    ->join('sf10childgrades',function($join){
                                          $join->on('sf10_student_jh.id','=','sf10childgrades.headerid');
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('sf10_student_jh.levelid','=','gradelevel.id');
                                    })
                                    ->join('sf10_schoollist',function($join){
                                          $join->on('sf10_student_jh.sf10_schoolid','=','sf10_schoollist.id');
                                    })
                                    ->get();
            }

            if($isSeniorHigh){

                  $sf10seniorhigh = DB::table('sf10_student_sh')
                                          ->where('sf10_student_sh.studid',$studid)
                                          ->join('sf10childgrades',function($join){
                                                $join->on('sf10_student_sh.id','=','sf10childgrades.headerid');
                                          })
                                          ->join('gradelevel',function($join){
                                                $join->on('sf10_student_sh.levelid','=','gradelevel.id');
                                          })
                                          ->join('sf10_schoollist',function($join){
                                                $join->on('sf10_student_sh.sf10_schoolid','=','sf10_schoollist.id');
                                          })
                                          ->get();
                  
            }


            // foreach(collect($sf10gradeschool)->groupby('headerid') as $key=>$item){
            //       if($key == 12){
            //             foreach($item as $newitem){
            //                   return $newitem->subj_desc;
            //             }
                    
            //       }
          
            // };

            // return collect($sf10gradeschool)->groupby('headerid');

            // return $sf10gradeschool;

            return view('registrar.forms.sf10')
                        ->with('isSeniorHigh',$isSeniorHigh)
                        ->with('isJuniorHigh',$isJuniorHigh)
                        ->with('isGradeSchoool',$isGradeSchoool)
                        ->with('sf10gradeschool',collect($sf10gradeschool)->groupby('headerid'))
                        ->with('sf10juniorhigh',$sf10juniorhigh)
                        ->with('sf10seniorhigh',$sf10seniorhigh);

            $studentGrades = DB::table('sf10childgrades')
                                    ->where('studid',$studid)
                                    ->get();

            


      }

      public static function insertsf10grade(Request $request){

            $sf10header = (object)[
                  'studid'=>'75',
                  'schoolid'=>'201120008',
                  'schoolname'=>'SOUTH CITY CENTRAL SCHOOL',
                  'schooladdress'=>'Nazareth, Cagayan de Oro City',
                  'levelid'=>'2',
                  'schoolyear'=>'2007-2008',
                  'sectionname'=>'MAGANDA',
                  'adviser'=>null,
                  'unitsearn'=>'1',
                  'yearsinschool'=>'2'
            ];  

            $checkIfSchoolExist = DB::table('sf10_schoollist')->where('schoolname',$sf10header->schoolname)->count();

            if($checkIfSchoolExist == 0){

                  $schoolid = DB::table('sf10_schoollist')
                                    ->insertGetId([
                                          'schoolname'=>$sf10header->schoolname,
                                          'schooladdress'=>$sf10header->schooladdress,
                                          'schoolid'=>$sf10header->schoolid
                                    ]);
            }
            else{
                  
                  $schoolid = DB::table('sf10_schoollist')
                                    ->where([
                                          'schoolname'=>$sf10header->schoolname,
                                    ])->first()->id;
            
            }

            $sf10grades = array(

                  (object)[
                        'subj_desc'=>'Math 1',
                        'core'=>null,
                        'quarter1'=>'80',
                        'quarter2'=>'81',
                        'quarter3'=>'82',
                        'quarter4'=>'83',
                        'finalrating'=>'85',
                        'action'=>'Passed',
                        'credits'=>null,
                        'semester'=>null
                  ],

                  (object)[
                        'subj_desc'=>'Math 1',
                        'core'=>null,
                        'quarter1'=>'86',
                        'quarter2'=>'87',
                        'quarter3'=>'88',
                        'quarter4'=>'89',
                        'finalrating'=>'90',
                        'action'=>'Passed',
                        'credits'=>null,
                        'semester'=>null
                  ],

                  (object)[
                        'subj_desc'=>'General Average',
                        'core'=>null,
                        'quarter1'=>'91',
                        'quarter2'=>'90',
                        'quarter3'=>'89',
                        'quarter4'=>'88',
                        'finalrating'=>'85',
                        'action'=>'Passed',
                        'credits'=>null,
                        'semester'=>null
                  ]
            
            );  
            
            $headerid = DB::table('sf10_student_elem')
                  ->insertGetId([
                        'studid'=> $sf10header->studid ,
                        'sf10_schoolid'=>  $schoolid ,
                        'schooladdress'=> $sf10header->schooladdress ,
                        'levelid'=> $sf10header->levelid ,
                        'schoolyear'=> $sf10header->schoolyear ,
                        'sectionname'=> $sf10header->sectionname ,
                        'adviser'=> $sf10header->adviser ,
                        'unitsearned'=> $sf10header->unitsearn ,
                        'yearsinschool'=> $sf10header->yearsinschool 
                  ]);

            foreach($sf10grades as $item){

                  DB::table('sf10childgrades')
                                    ->insert([
                                          'headerid'=> $headerid ,
                                          'studid'=> $sf10header->studid ,
                                          'core'=> $item->core ,
                                          'subj_desc'=> $item->subj_desc ,
                                          'quarter1'=> $item->quarter1 ,
                                          'quarter2'=> $item->quarter2 ,
                                          'quarter3'=> $item->quarter3 ,
                                          'quarter4'=> $item->quarter4 ,
                                          'finalrating'=> $item->finalrating ,
                                          'action'=> $item->action ,
                                          'credits'=> $item->credits ,
                                          'semester'=> $item->semester 
                                    ]);
            }

            // return $sf10header;

      }
      public function studdisplayphotoindex(Request $request)
      {
            // $students = DB::table('studinfo')
            //       ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender')
            //       ->where('deleted','0')
            //       ->where('studstatus','<=','5')
            //       ->get();

            $students = DB::table('college_enrolledstud')
                  // ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','levelid','gradelevel.levelname')
                  // ->leftJoin('gradelevel','studinfo.levelid','=','gradelevel.id')
                  ->select('studinfo.id','sid','lrn','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','gradelevel.levelname')
                  ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                  ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                  ->where('college_enrolledstud.deleted','0')
                  ->where('gradelevel.acadprogid','6')
                  ->where('studinfo.lastname','!=',null)
                  ->where('studinfo.deleted','0')
                  ->where('college_enrolledstud.studstatus','<=','5')
                  ->distinct('studinfo.id')
                  ->orderBy('lastname','asc')
                  ->get();
            
            
            return view('registrar.setup.studdisplayphoto.index')
            ->with('students', $students);
      }
      public function studdisplayphotoget(Request $request)
      {
            $studinfo = DB::table('studinfo')
                  ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender','studinfo.dob','studinfo.street','studinfo.barangay','studinfo.city','studinfo.province')
                  ->where('id', $request->get('id'))
                  ->first();

            $getphoto = DB::table('studdisplayphoto')
                  ->where('studid', $request->get('id'))
                  ->where('deleted','0')
                  ->first();

            $enrollmentdetails = collect();
            $enrolledstud = DB::table('enrolledstud')
                  ->select('enrolledstud.*','gradelevel.levelname','sections.sectionname')
                  ->where('studid', $studinfo->id)
                  ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                  ->join('sections','enrolledstud.sectionid','=','sections.id')
                  ->get();

            $sh_enrolledstud = DB::table('sh_enrolledstud')
                  ->select('sh_enrolledstud.*','gradelevel.levelname','sections.sectionname')
                  ->where('studid', $studinfo->id)
                  ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                  ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                  ->get();

            $enrollmentdetails = $enrollmentdetails->merge($enrolledstud);
            $enrollmentdetails = $enrollmentdetails->merge($sh_enrolledstud);


            // return $enrollmentdetails;
            // return $sh_enrolledstud;
            $currentenrollmentdetails = $enrollmentdetails->where('syid', DB::table('sy')->where('isactive','1')->first()->id)->values();
            
            if(count($currentenrollmentdetails)>0)
            {
                  foreach($currentenrollmentdetails as $currentenrollmentdetail)
                  {
                        $teacheraname = '';
                        $sectiondetail = DB::table('sectiondetail')
                              ->select('teacher.*')
                              ->join('teacher','sectiondetail.teacherid','=','teacher.id')
                              // ->where('levelid', $currentenrollmentdetail->levelid)
                              ->where('sectionid', $currentenrollmentdetail->sectionid)
                              ->where('syid',DB::table('sy')->where('isactive','1')->first()->id)
                              ->where('semid',DB::table('semester')->where('isactive','1')->first()->id)
                              ->where('sectiondetail.deleted','0')
                              ->first();
                        if($sectiondetail)
                        {
                              $teacheraname.=$sectiondetail->firstname.' ';
                              $teacheraname.=$sectiondetail->middlename.' ';
                              $teacheraname.=$sectiondetail->lastname.' ';
                              $teacheraname.=$sectiondetail->suffix;
                        }
                        $currentenrollmentdetail->teacheraname = $teacheraname;
                        
                  }
            }
            // return collect($currentenrollmentdetails);
            return view('registrar.setup.studdisplayphoto.viewphoto')
            ->with('currentenrollmentdetails', $currentenrollmentdetails)
            ->with('studinfo', $studinfo)
            ->with('getphoto', $getphoto);
      }
      public function studdisplayphotoupload(Request $request)
      {
            
            $studentinfo = DB::table('studinfo')->where('id',$request->studid)->first();
            $sy = DB::table('sy')
                  ->where('isactive','1')
                  ->first();

            $urlFolder = str_replace('https://','',$request->root());

            if (! File::exists(public_path().'studentsdisplayphotos/'.$sy->sydesc)) {

                  $path = public_path('studentsdisplayphotos/'.$sy->sydesc);

                  if(!File::isDirectory($path)){
                        
                        File::makeDirectory($path, 0777, true, true);

                  }else{
                        
                  }
                  
            }
            
            if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/studentsdisplayphotos/'.$sy->sydesc)) {

                  $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/studentsdisplayphotos/'.$sy->sydesc;
                  
                  if(!File::isDirectory($cloudpath)){

                        File::makeDirectory($cloudpath, 0777, true, true);
                        
                  }
                  
            }
            
            $lastname = str_replace(' ', '_', $studentinfo->lastname);
            $lastname = str_replace('', '.', $lastname);
                  
            $data = $request->image;

            list($type, $data) = explode(';', $data);

            list(, $data)      = explode(',', $data);

            $data = base64_decode($data);

            $extension = 'png';

            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/studentsdisplayphotos/'.$sy->sydesc.'/'.$lastname.'_'.$studentinfo->firstname.'.'.$extension;
            
            try{

                  file_put_contents($clouddestinationPath, $data);
                  
            }
            catch(\Exception $e){
                  

            }

            $destinationPath = public_path('studentsdisplayphotos/'.$sy->sydesc.'/'.$lastname.'_'.$studentinfo->firstname.'.'.$extension);
            
            file_put_contents($destinationPath, $data);

            $checkifexists = DB::table('studdisplayphoto')
                  ->where('studid',$request->studid)
                  ->where('deleted','0')
                  ->first();
            if($checkifexists)
            {
                  DB::table('studdisplayphoto')
                        ->where('id',$checkifexists->id)
                        ->update([
                              'picurl' => 'studentsdisplayphotos/'.$sy->sydesc.'/'.$lastname.'_'.$studentinfo->firstname.'.'.$extension,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime'=> date('Y-m-d H:i:s')
                        ]);
            }else{
                  DB::table('studdisplayphoto')
                        ->insert([
                              'studid'          => $request->studid,
                              'picurl' => 'studentsdisplayphotos/'.$sy->sydesc.'/'.$lastname.'_'.$studentinfo->firstname.'.'.$extension,
                              'extension' => $extension,
                              'createdby' => auth()->user()->id,
                              'createddatetime'=> date('Y-m-d H:i:s')
                        ]);
            }

            return asset('studentsdisplayphotos/'.$sy->sydesc.'/'.$lastname.'_'.$studentinfo->firstname.'.'.$extension);
      }

   
}
